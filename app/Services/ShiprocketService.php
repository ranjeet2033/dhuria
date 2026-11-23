<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ShiprocketService
{
    protected $baseUrl = 'https://apiv2.shiprocket.in/v1/external';

    /**
     * Authenticate and get JWT token from Shiprocket.
     */
    public function getToken()
    {
        $settings = Setting::pluck('value', 'key')->all();
        $email = $settings['shiprocket_email'] ?? '';
        $password = $settings['shiprocket_password'] ?? '';

        if (empty($email) || empty($password)) {
            throw new \Exception('Shiprocket email and password are not configured in settings.');
        }

        $response = Http::post("{$this->baseUrl}/auth/login", [
            'email' => $email,
            'password' => $password,
        ]);

        if ($response->successful()) {
            return $response->json('token');
        }

        Log::error('Shiprocket Authentication Failed: ' . $response->body());
        throw new \Exception('Unable to authenticate with Shiprocket API: ' . ($response->json('message') ?? 'Unknown error'));
    }

    /**
     * Estimate packed weight of a product variant in kilograms.
     */
    public function estimateWeight($productName)
    {
        $name = strtolower($productName);
        if (str_contains($name, 'sticks') && str_contains($name, 'pet')) {
            return 0.161; // 161 grams
        } elseif (str_contains($name, 'cones') && str_contains($name, 'pet')) {
            return 0.165; // 165 grams
        } elseif (str_contains($name, 'sticks') && str_contains($name, 'corrugated')) {
            return 0.147; // 147 grams
        } elseif (str_contains($name, 'cones') && str_contains($name, 'corrugated')) {
            return 0.141; // 141 grams
        }
        return 0.200; // default 200 grams
    }

    /**
     * Create shipment order in Shiprocket.
     */
    public function createShipment(Order $order)
    {
        $token = $this->getToken();
        $settings = Setting::pluck('value', 'key')->all();
        
        $pickupLocation = $settings['shiprocket_pickup_location'] ?? 'Primary';
        
        $orderItems = [];
        $totalWeight = 0;
        $maxLength = 0; $maxWidth = 0; $maxHeight = 0;

        foreach ($order->items as $item) {
            // Use actual product weight from DB; fall back to 200g if not found
            $product = $item->product;
            $unitWeight = $product ? (float) $product->weight : 0.200;
            $itemWeight = $unitWeight * $item->quantity;
            $totalWeight += $itemWeight;

            // Track largest box dimensions across all items
            if ($product) {
                $maxLength = max($maxLength, (int) $product->length);
                $maxWidth  = max($maxWidth,  (int) $product->width);
                $maxHeight = max($maxHeight, (int) $product->height);
            }

            $sku = ($product && !empty($product->sku)) ? $product->sku : ('DHURIA-' . $item->product_id);

            $orderItems[] = [
                'name' => $item->product_name,
                'sku' => $sku,
                'units' => (int) $item->quantity,
                'selling_price' => (float) $item->unit_price,
            ];
        }

        // Split name into first and last name for Shiprocket requirements
        $nameParts = explode(' ', trim($order->shipping_name), 2);
        $firstName = $nameParts[0];
        $lastName = $nameParts[1] ?? 'StoreCustomer';

        // Payment method mapping
        $isCod = strtolower($order->payment_method) === 'cod';
        $paymentMethod = $isCod ? 'COD' : 'Prepaid';

        $payload = [
            'order_id' => $order->order_number,
            'order_date' => $order->created_at->format('Y-m-d H:i'),
            'pickup_location' => $pickupLocation,
            'billing_customer_name' => $firstName,
            'billing_last_name' => $lastName,
            'billing_address' => $order->shipping_address,
            'billing_city' => $order->shipping_city,
            'billing_pincode' => $order->shipping_zip,
            'billing_state' => $order->shipping_state,
            'billing_country' => 'India',
            'billing_email' => $order->shipping_email,
            'billing_phone' => $order->shipping_phone,
            'shipping_is_billing' => true,
            'order_items' => $orderItems,
            'payment_method' => $paymentMethod,
            'sub_total' => (float) $order->total_amount,
            'length' => max(10, $maxLength), // actual box length in cm
            'width' => max(5, $maxWidth),
            'height' => max(5, $maxHeight),
            'weight' => max(0.1, $totalWeight), // total weight in kg (minimum 100g)
        ];

        Log::info('Shiprocket Order Creation Payload: ' . json_encode($payload));

        $response = Http::withToken($token)->post("{$this->baseUrl}/orders/create/adhoc", $payload);

        if ($response->successful()) {
            $data = $response->json();
            
            $order->update([
                'shiprocket_order_id' => $data['order_id'] ?? null,
                'shiprocket_shipment_id' => $data['shipment_id'] ?? null,
                'shiprocket_status' => 'NEW',
                'shiprocket_awb_code' => $data['awb_code'] ?? null,
            ]);

            return [
                'success' => true,
                'order_id' => $data['order_id'] ?? null,
                'shipment_id' => $data['shipment_id'] ?? null,
            ];
        }

        Log::error('Shiprocket Order Creation Failed: ' . $response->body());
        $message = $response->json('message') ?? 'Unknown Shiprocket Error';
        if (is_array($response->json('errors'))) {
            $message .= ' - ' . json_encode($response->json('errors'));
        }
        
        throw new \Exception($message);
    }

    /**
     * Generate AWB for a shipment.
     */
    public function generateAwb($shipmentId, $courierId = null)
    {
        $token = $this->getToken();
        $payload = ['shipment_id' => $shipmentId];
        if ($courierId) {
            $payload['courier_id'] = $courierId;
        }

        $response = Http::withToken($token)->post("{$this->baseUrl}/courier/assign/awb", $payload);

        if ($response->successful()) {
            return $response->json();
        }

        Log::error('Shiprocket AWB Generation Failed: ' . $response->body());
        throw new \Exception($response->json('message') ?? 'Unable to generate AWB.');
    }

    /**
     * Track shipment by AWB code.
     */
    public function trackByAwb($awbCode)
    {
        $token = $this->getToken();
        $response = Http::withToken($token)->get("{$this->baseUrl}/courier/track/awb/{$awbCode}");

        if ($response->successful()) {
            return $response->json();
        }

        Log::warning('Shiprocket AWB Tracking Failed for ' . $awbCode . ': ' . $response->body());
        return null;
    }

    /**
     * Track shipment by Order ID.
     */
    public function trackByOrderId($orderId)
    {
        $token = $this->getToken();
        $response = Http::withToken($token)->get("{$this->baseUrl}/courier/track", [
            'order_id' => $orderId
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        Log::warning('Shiprocket Order Tracking Failed for ' . $orderId . ': ' . $response->body());
        return null;
    }

    /**
     * Generate Shipping Label PDF URL.
     */
    public function generateLabel($shipmentId)
    {
        $token = $this->getToken();
        $response = Http::withToken($token)->post("{$this->baseUrl}/courier/generate/label", [
            'shipment_id' => [$shipmentId]
        ]);

        if ($response->successful()) {
            return $response->json('label_url');
        }

        Log::error('Shiprocket Label Generation Failed: ' . $response->body());
        throw new \Exception($response->json('message') ?? 'Unable to generate shipping label.');
    }

    /**
     * Check Pincode Serviceability.
     */
    public function checkServiceability($pickupPincode, $deliveryPincode, $weight = 0.5, $cod = false)
    {
        $token = $this->getToken();
        $response = Http::withToken($token)->get("{$this->baseUrl}/courier/serviceability", [
            'pickup_postcode'   => $pickupPincode,
            'delivery_postcode' => $deliveryPincode,
            'weight'            => $weight,
            'cod'               => $cod ? 1 : 0,
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }
}
