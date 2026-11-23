<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Banner;
use App\Models\Testimonial;

class FrontendController extends Controller
{
    public function home(Request $request)
    {
        $banners = Banner::where('is_active', true)->get();
        $testimonials = Testimonial::where('is_active', true)->get();
        $bestSellers = Product::where('is_active', true)->where('is_bestseller', true)->latest()->take(10)->get();
        $featuredProducts = Product::where('is_active', true)->where('is_featured', true)->latest()->take(10)->get();
        $dealOfWeek = Product::where('is_active', true)->where('deal_of_week', true)->first();
        $categories = Category::where('is_active', true)->get();

        // Fallbacks if database has no flagged products
        if ($bestSellers->isEmpty()) {
            $bestSellers = Product::where('is_active', true)->latest()->take(10)->get();
        }
        if ($featuredProducts->isEmpty()) {
            $featuredProducts = Product::where('is_active', true)->latest()->take(10)->get();
        }
        $spotlightProduct1 = Product::where('name', 'like', '%MadhuCurex%')->first() ?? $dealOfWeek ?? $bestSellers->first();
        $spotlightProduct2 = Product::where('name', 'like', '%Pilecurex%')->first() ?? ($featuredProducts->count() > 1 ? $featuredProducts->skip(1)->first() : $bestSellers->skip(1)->first());

        // Paginated "All Products" grid for the infinite scroll section
        $allProducts = Product::where('is_active', true)->latest()->paginate(10)->withQueryString();

        return view('frontend.home', compact('banners', 'testimonials', 'bestSellers', 'featuredProducts', 'dealOfWeek', 'spotlightProduct1', 'spotlightProduct2', 'categories', 'allProducts'));
    }

    public function about()
    {
        return view('frontend.about');
    }

    public function shop(Request $request)
    {
        $query = Product::where('is_active', true);

        // Filter by search query
        if ($request->has('search') && $request->input('search') != '') {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        // Selected categories resolver (supports both categories[] IDs and cat=slug with subcategories)
        $selectedCategories = [];
        if ($request->has('categories')) {
            $selectedCategories = (array) $request->input('categories');
        }
        if ($request->has('cat') && $request->input('cat') != '') {
            $cat = Category::where('slug', $request->input('cat'))->with('allChildren')->first();
            if ($cat) {
                $categoryIds = collect([$cat->id]);
                $collectChildren = function($category) use (&$collectChildren, &$categoryIds) {
                    if ($category->children) {
                        foreach ($category->children as $child) {
                            $categoryIds->push($child->id);
                            $collectChildren($child);
                        }
                    }
                };
                $collectChildren($cat);
                $selectedCategories = array_unique(array_merge($selectedCategories, $categoryIds->toArray()));
            }
        }

        // Filter by categories
        if (!empty($selectedCategories)) {
            $query->whereIn('category_id', $selectedCategories);
        }

        // Filter by highlights
        if ($request->has('highlight')) {
            $highlight = $request->input('highlight');
            if ($highlight == 'bestseller') {
                $query->where('is_bestseller', true);
            } elseif ($highlight == 'new') {
                $query->latest();
            } elseif ($highlight == 'sale') {
                $query->whereNotNull('sale_price');
            }
        }

        // Filter by max price
        if ($request->has('max_price')) {
            $maxPrice = (float) $request->input('max_price');
            $query->where(function($q) use ($maxPrice) {
                $q->where(function($sub) use ($maxPrice) {
                    $sub->whereNull('sale_price')->where('price', '<=', $maxPrice);
                })->orWhere(function($sub) use ($maxPrice) {
                    $sub->whereNotNull('sale_price')->where('sale_price', '<=', $maxPrice);
                });
            });
        }

        // Sort products
        if ($request->has('sort_by')) {
            $sort = $request->input('sort_by');
            if ($sort == 'price_low' || $sort == 'price-asc') {
                $query->orderByRaw('COALESCE(sale_price, price) asc');
            } elseif ($sort == 'price_high' || $sort == 'price-desc') {
                $query->orderByRaw('COALESCE(sale_price, price) desc');
            } elseif ($sort == 'latest') {
                $query->latest();
            } elseif ($sort == 'popularity' || $sort == 'bestseller') {
                $query->orderBy('is_bestseller', 'desc')->orderBy('id', 'desc');
            } else {
                $query->orderBy('id', 'desc');
            }
        } else {
            $query->orderBy('id', 'desc');
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::parents()->with('children.children')->where('is_active', true)->get();

        return view('frontend.shop', compact('products', 'categories', 'selectedCategories'));
    }

    public function product($slug)
    {
        $product = Product::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $relatedProducts = Product::where('category_id', $product->category_id)->where('id', '!=', $product->id)->take(3)->get();
        if ($relatedProducts->count() < 3) {
            $more = Product::where('id', '!=', $product->id)->whereNotIn('id', $relatedProducts->pluck('id'))->take(3 - $relatedProducts->count())->get();
            $relatedProducts = $relatedProducts->concat($more);
        }
        return view('frontend.product', compact('product', 'relatedProducts'));
    }

    public function contact()
    {
        return view('frontend.contact');
    }

    public function liveSearch(Request $request)
    {
        $q = trim($request->input('q', ''));
        if (strlen($q) < 1) {
            $popular = Product::where('is_active', true)
                ->where(function($query) {
                    $query->where('is_bestseller', true)->orWhere('is_featured', true);
                })
                ->with('category')
                ->take(8)
                ->get();
            $categories = Category::where('is_active', true)->take(6)->get();
            return response()->json([
                'success' => true,
                'is_empty' => true,
                'popular' => $popular->map(fn($p) => [
                    'id' => $p->id,
                    'name' => $p->name,
                    'slug' => $p->slug,
                    'category' => $p->category->name ?? 'Pure Ayurveda',
                    'price' => number_format($p->sale_price ?? $p->price, 0),
                    'old_price' => $p->sale_price ? number_format($p->price, 0) : null,
                    'discount' => ($p->sale_price && $p->price > $p->sale_price) ? round((($p->price - $p->sale_price) / $p->price) * 100) : null,
                    'image' => $p->primary_image_url,
                    'url' => route('product.show', $p->slug)
                ]),
                'categories' => $categories->map(fn($c) => [
                    'name' => $c->name,
                    'slug' => $c->slug,
                    'url' => route('shop', ['cat' => $c->slug])
                ])
            ]);
        }

        $productQuery = Product::where('is_active', true)
            ->where(function($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('description', 'like', "%{$q}%")
                      ->orWhere('short_description', 'like', "%{$q}%")
                      ->orWhereHas('category', function($catQ) use ($q) {
                          $catQ->where('name', 'like', "%{$q}%");
                      });
            });

        $totalCount = (clone $productQuery)->count();
        $products = (clone $productQuery)->with('category')->take(12)->get();

        $matchingCategories = Category::where('is_active', true)
            ->where('name', 'like', "%{$q}%")
            ->take(5)
            ->get();

        return response()->json([
            'success' => true,
            'is_empty' => false,
            'query' => $q,
            'total' => $totalCount,
            'all_results_url' => route('shop', ['search' => $q]),
            'categories' => $matchingCategories->map(fn($c) => [
                'name' => $c->name,
                'slug' => $c->slug,
                'url' => route('shop', ['cat' => $c->slug])
            ]),
            'products' => $products->map(fn($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'slug' => $p->slug,
                'category' => $p->category->name ?? 'Pure Ayurveda',
                'price' => number_format($p->sale_price ?? $p->price, 0),
                'old_price' => $p->sale_price ? number_format($p->price, 0) : null,
                'discount' => ($p->sale_price && $p->price > $p->sale_price) ? round((($p->price - $p->sale_price) / $p->price) * 100) : null,
                'image' => $p->primary_image_url,
                'url' => route('product.show', $p->slug)
            ])
        ]);
    }

    public function apiProductDetails($slug)
    {
        $product = Product::where('slug', $slug)->where('is_active', true)->with('category')->first();
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found.'], 404);
        }
        
        $image = $product->primary_image_url;
        
        return response()->json([
            'success' => true,
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'sale_price' => $product->sale_price,
                'image' => $image,
                'description' => $product->description,
                'short_description' => $product->short_description,
                'slug' => $product->slug,
                'category_name' => $product->category->name ?? 'Dhoop Sticks'
            ]
        ]);
    }

    public function terms()
    {
        return view('frontend.terms');
    }

    public function privacy()
    {
        return view('frontend.privacy');
    }

    public function refund()
    {
        return view('frontend.refund');
    }

    public function cancellation()
    {
        return view('frontend.cancellation');
    }

    public function shipping()
    {
        return view('frontend.shipping');
    }

    public function sitemap()
    {
        $products = Product::where('is_active', true)->get();
        $categories = Category::where('is_active', true)->get();

        return response()->view('frontend.sitemap', compact('products', 'categories'))
                         ->header('Content-Type', 'text/xml');
    }
}

