<style>
.masthead {
    min-height: 23vh !important;
    height: 23vh !important;
}
.masthead:before {
    min-height: 23vh !important;
    height: 23vh !important;
}
.about-hero {
    background: linear-gradient(135deg, #0066cc, #17a2b8);
    padding: 50px 0;
    text-align: center;
    color: white;
}
.about-hero h2 {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 15px;
}
.about-hero p {
    font-size: 1.1rem;
    max-width: 700px;
    margin: 0 auto;
    opacity: 0.9;
}
.section-card {
    background: #fff;
    border-radius: 12px;
    padding: 30px;
    margin-bottom: 25px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.08);
}
.section-card h4 {
    color: #0066cc;
    font-weight: 700;
    margin-bottom: 15px;
    border-left: 4px solid #17a2b8;
    padding-left: 12px;
}
.section-card p {
    color: #555;
    line-height: 1.8;
    margin: 0;
}
.feature-box {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 25px 20px;
    text-align: center;
    height: 100%;
    transition: all 0.3s ease;
    border-bottom: 3px solid transparent;
}
.feature-box:hover {
    border-bottom: 3px solid #17a2b8;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transform: translateY(-3px);
}
.feature-box .icon {
    font-size: 2rem;
    margin-bottom: 12px;
}
.feature-box h6 {
    font-weight: 700;
    color: #333;
    margin-bottom: 8px;
}
.feature-box p {
    color: #777;
    font-size: 13px;
    margin: 0;
}
.contact-box {
    background: linear-gradient(135deg, #0066cc, #17a2b8);
    border-radius: 12px;
    padding: 35px;
    color: white;
    text-align: center;
}
.contact-box h4 {
    font-weight: 700;
    margin-bottom: 20px;
}
.contact-item {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 12px;
    font-size: 15px;
}
.contact-item i {
    margin-right: 10px;
    font-size: 18px;
}
</style>

<!-- Header -->
<header class="masthead">
    <div class="container h-100">
        <div class="row h-100 align-items-center justify-content-center text-center">
            <div class="col-lg-10 align-self-end mb-4">
                <h1 class="text-uppercase text-white font-weight-bold">About Us</h1>
                <hr class="divider my-4" />
            </div>
        </div>
    </div>
</header>

<!-- Hero Banner -->
<div class="about-hero">
    <div class="container">
        <h2>Welcome to PSGCAS Alumni Connect</h2>
        <p>Connecting PSGCAS graduates with their alma mater, fellow alumni, and current students — fostering a lifelong bond across the world.</p>
    </div>
</div>

<!-- Main Content -->
<div style="background-color: #f0f4f8; padding: 50px 0;">
    <div class="container">

        <!-- Mission & Vision Row -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="section-card h-100">
                    <h4><i class="fa fa-bullseye mr-2"></i>Our Mission</h4>
                    <p>To build a thriving alumni community that supports professional growth, mentorship, and meaningful connections — keeping every graduate connected to the PSG legacy.</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="section-card h-100">
                    <h4><i class="fa fa-eye mr-2"></i>Our Vision</h4>
                    <p>To be the most active and impactful alumni network in the region, empowering graduates at every stage of their career through collaboration, knowledge sharing, and community engagement.</p>
                </div>
            </div>
        </div>

        <!-- What We Offer -->
        <div class="section-card mb-4">
            <h4><i class="fa fa-star mr-2"></i>What We Offer</h4>
            <div class="row mt-3">
                <div class="col-md-4 mb-3">
                    <div class="feature-box">
                        <div class="icon">👥</div>
                        <h6>Alumni Directory</h6>
                        <p>Reconnect with batchmates and build your professional network</p>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="feature-box">
                        <div class="icon">💼</div>
                        <h6>Job Portal</h6>
                        <p>Exclusive job opportunities posted by fellow alumni and companies</p>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="feature-box">
                        <div class="icon">📅</div>
                        <h6>Events</h6>
                        <p>Reunions, workshops, and networking meetups organized by the college</p>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="feature-box">
                        <div class="icon">💬</div>
                        <h6>Forums</h6>
                        <p>Discussions, knowledge sharing and peer-to-peer support</p>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="feature-box">
                        <div class="icon">🎓</div>
                        <h6>Mentorship</h6>
                        <p>Senior alumni guiding fresh graduates in their career journey</p>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="feature-box">
                        <div class="icon">🖼️</div>
                        <h6>Gallery</h6>
                        <p>Memories and photos from college events and reunions</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admin About Content -->
        <?php if(!empty($_SESSION['system']['about_content'])): ?>
        <div class="section-card mb-4">
            <h4><i class="fa fa-info-circle mr-2"></i>More About Us</h4>
            <div style="color:#555; line-height:1.8;">
                <?php echo html_entity_decode($_SESSION['system']['about_content']) ?>
            </div>
        </div>
        <?php endif; ?>

        

    </div>
</div>