@extends('layouts.frontend')

@section('title', 'About Us - Dhuria Ayurveda')

@section('content')

<style>
.dh-about-page {
    max-width: 1100px;
    margin: 0 auto;
    padding: 40px 20px 70px;
}
.dh-about-header {
    text-align: center;
    margin-bottom: 45px;
}
.dh-about-kicker {
    font-size: 0.8rem;
    font-weight: 800;
    letter-spacing: 2px;
    color: #b89326;
    text-transform: uppercase;
    margin-bottom: 10px;
    display: inline-block;
}
.dh-about-title {
    font-family: 'Cinzel', serif;
    font-size: 2.6rem;
    font-weight: 800;
    color: #061528;
    margin: 0 0 14px 0;
}
.dh-about-lead {
    font-size: 1.05rem;
    color: #475569;
    line-height: 1.8;
    max-width: 820px;
    margin: 0 auto;
}

.dh-about-section-heading {
    font-family: 'Cinzel', serif;
    font-size: 1.4rem;
    font-weight: 800;
    color: #061528;
    margin: 40px 0 20px 0;
}
.dh-about-p {
    font-size: 0.95rem;
    color: #475569;
    line-height: 1.75;
    margin-bottom: 25px;
}

/* Founder Box */
.dh-founder-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    padding: 35px 40px;
    box-shadow: 0 10px 30px rgba(6, 21, 40, 0.04);
    margin: 50px 0;
    position: relative;
}
.dh-founder-badge {
    display: inline-block;
    background: #f1f5f9;
    color: #061528;
    font-size: 0.75rem;
    font-weight: 800;
    padding: 4px 14px;
    border-radius: 20px;
    letter-spacing: 1px;
    text-transform: uppercase;
    margin-bottom: 12px;
}
.dh-founder-title {
    font-family: 'Cinzel', serif;
    font-size: 1.6rem;
    font-weight: 800;
    color: #061528;
    margin: 0 0 15px 0;
}
.dh-founder-quote-box {
    background: #fefce8;
    border-left: 4px solid #d4af37;
    padding: 14px 20px;
    border-radius: 0 8px 8px 0;
    margin: 20px 0;
    font-style: italic;
    color: #713f12;
    font-size: 0.92rem;
}
.dh-btn-explore {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #061528;
    color: #d4af37 !important;
    border: 1px solid #d4af37;
    padding: 14px 36px;
    border-radius: 30px;
    font-weight: 800;
    font-size: 0.95rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    text-decoration: none !important;
    transition: all 0.3s;
}
.dh-btn-explore:hover {
    background: #d4af37;
    color: #061528 !important;
    transform: translateY(-2px);
}
</style>

<div class="dh-about-page">
    <div class="dh-about-header">
        <span class="dh-about-kicker">✦ SACRED AYURVEDA HERITAGE ✦</span>
        <h1 class="dh-about-title">About Dhuria</h1>
        <p class="dh-about-lead">
            Welcome to Dhuria, your trusted destination for natural health, internal harmony, and classical Ayurvedic well-being. Born from a profound dedication to authentic Vedic wisdom, we formulate pure, lab-certified botanical remedies designed to balance mind, body, and spirit.
        </p>
    </div>

    <!-- Our Commitment to Purity -->
    <h3 class="dh-about-section-heading">Our Commitment to Purity:</h3>
    
    <!-- Doctor Card Embedded -->
    <div style="background: linear-gradient(135deg, #0a223f 0%, #051323 100%); border-radius: 20px; border: 1px solid rgba(212,175,55,0.35); padding: 30px 40px; display: grid; grid-template-columns: 180px 1fr; align-items: center; gap: 30px; margin-bottom: 25px;">
        <div style="width: 150px; height: 150px; border-radius: 50%; border: 3px solid #d4af37; overflow: hidden; background: #fff; margin: 0 auto;">
            <img src="{{ asset('images/doctor_portrait.jpg') }}" alt="Doctor Endorsement" style="width: 100%; height: 100%; object-fit: cover;">
        </div>
        <div>
            <span style="display:inline-block; background:rgba(212,175,55,0.2); border:1px solid #d4af37; color:#fde68a; font-size:0.75rem; font-weight:800; padding:4px 14px; border-radius:20px; letter-spacing:1px; margin-bottom:8px;">SAFE AND TESTED</span>
            <h3 style="font-size:1.5rem; font-weight:800; color:#fff; font-family:'Cinzel',serif; margin:0 0 6px 0;">RECOMMENDED BY HEALTH PROFESSIONALS</h3>
            <div style="font-size:1rem; color:#d4af37; font-weight:700; margin-bottom:10px; display:flex; align-items:center; gap:8px;">
                <span>WE ARE AN HONEST AYURVEDA BRAND</span>
                <i class="bi bi-patch-check-fill text-green-400"></i>
            </div>
            <p style="color:#cbd5e1; font-size:0.92rem; line-height:1.6; margin:0;">
                "At Dhuria, we firmly believe the foundation of vitality is absolute purity. Every plant extract undergoes rigorous chromatography and microbial screening in NABL laboratories to ensure you receive unadulterated healing power."
            </p>
        </div>
    </div>

    <p class="dh-about-p">
        We never compromise on sourcing. Our botanical ingredients—including wild-harvested Kutki from the Himalayas, certified organic Gurmar from Central India, and pristine Vijaysar barks—are handpicked at peak seasonal potency.
    </p>

    <!-- Quality Assurance -->
    <h3 class="dh-about-section-heading">Quality Assurance:</h3>
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:20px; margin-bottom:30px;">
        <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:14px; padding:20px; text-align:center;">
            <i class="bi bi-patch-check" style="font-size:2rem; color:#b89326; margin-bottom:8px; display:block;"></i>
            <h5 style="font-weight:800; color:#061528; margin:0 0 4px 0;">AYUSH Standard</h5>
            <p style="font-size:0.8rem; color:#64748b; margin:0;">Manufactured in strict adherence to classical Pharmacopoeia standards.</p>
        </div>
        <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:14px; padding:20px; text-align:center;">
            <i class="bi bi-eyedropper" style="font-size:2rem; color:#0284c7; margin-bottom:8px; display:block;"></i>
            <h5 style="font-weight:800; color:#061528; margin:0 0 4px 0;">Zero Heavy Metals</h5>
            <p style="font-size:0.8rem; color:#64748b; margin:0;">Certified 0.00% Lead, Arsenic, Mercury and synthetic residues.</p>
        </div>
        <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:14px; padding:20px; text-align:center;">
            <i class="bi bi-shield-check" style="font-size:2rem; color:#10b981; margin-bottom:8px; display:block;"></i>
            <h5 style="font-weight:800; color:#061528; margin:0 0 4px 0;">GMP Certified</h5>
            <p style="font-size:0.8rem; color:#64748b; margin:0;">Cleanroom pharmaceutical facility maintaining sterile encapsulation.</p>
        </div>
    </div>

    <!-- Cursive Banner Callout -->
    <div style="background: #0f233a; border-radius: 16px; padding: 35px 20px; text-align: center; border: 2px solid #d4af37; margin: 40px 0;">
        <h2 style="font-family:'Brush Script MT', 'Great Vibes', cursive; font-size:3rem; color:#fef08a; margin:0 0 6px 0;">Enriched With Goodness Of Nature</h2>
        <p style="color:#e2e8f0; font-size:0.85rem; letter-spacing:2px; text-transform:uppercase; margin:0;">Pure Ayurvedic Ingredients &bull; Ancient Vedic Recipes &bull; Holistic Healing</p>
    </div>

    <!-- A Personal Word from Our Founder -->
    <div class="dh-founder-card">
        <span class="dh-founder-badge">FOUNDER'S STORY &amp; VISION</span>
        <h3 class="dh-founder-title">A Personal Word from Our Founder</h3>
        <p class="dh-about-p">
            "Dhuria was founded with a singular conviction: to bridge authentic ancient Ayurveda with transparent modern quality standards. Over the decades, commercial shortcuts diluted the true therapeutic power of classical recipes. We returned to the roots—honoring ancient grinding methods, using pure copper decoction vessels, and refusing every synthetic filler."
        </p>

        <div class="dh-founder-quote-box">
            "When you hold a Dhuria formulation in your hands, you hold the pure healing trust of Mother Nature, blessed by generations of Ayurvedic wisdom."
        </div>

        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:15px; border-top:1px solid #f1f5f9; padding-top:20px;">
            <div>
                <strong style="display:block; color:#061528; font-size:1.1rem;">Dr. Ramesh Dhuria</strong>
                <span style="font-size:0.85rem; color:#64748b;">Founder &amp; Chief Vaidya, Dhuria Healthcare</span>
            </div>
            <a href="{{ route('shop') }}" class="dh-btn-explore">
                <span>Explore Complete Collection</span> <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
</div>

@endsection
