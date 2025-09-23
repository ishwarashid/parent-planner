<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Privacy Policy – Parent Planner</title>
    
    <!-- ======= Google Font =======-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <!-- End Google Font-->
    
    <!-- ======= Styles =======-->
    <link href="{{asset('assets/vendors/bootstrap/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/vendors/bootstrap-icons/font/bootstrap-icons.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/css/style.css')}}" rel="stylesheet">
    <!-- End Styles-->
    
    <style>
        .legal-content {
            line-height: 1.6;
        }
        .legal-content h2 {
            margin-top: 2rem;
            margin-bottom: 1rem;
            font-size: 1.5rem;
            color: var(--bs-heading-color);
        }
        .legal-content h3 {
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
            font-size: 1.25rem;
            color: var(--bs-heading-color);
        }
        .legal-content p {
            margin-bottom: 1rem;
            color: var(--bs-body-color);
        }
        .legal-content ul {
            margin-bottom: 1rem;
            padding-left: 1.5rem;
        }
        .legal-content li {
            margin-bottom: 0.5rem;
            color: var(--bs-body-color);
        }
        .page-title {
            padding-top: 100px !important;
            padding-bottom: 50px !important;
            background-color: rgba(var(--inverse-color-rgb), 0.03);
        }
    </style>
</head>
<body>
    <!-- ======= Site Wrap =======-->
    <div class="site-wrap">
        <!-- ======= Header =======-->
        <header class="fbs__net-navbar navbar navbar-expand-lg dark" aria-label="freebootstrap.net navbar">
            <div class="container d-flex align-items-center justify-content-between">
                <!-- Start Logo-->
                <a class="navbar-brand w-auto" href="{{ url('/') }}">
                    <img class="logo img-fluid" src="{{asset('assets/images/light-logo.png')}}" alt="Parent Planner logo" width="200px">
                </a>
                <!-- End Logo-->
                
                <!-- Start offcanvas-->
                <div class="offcanvas offcanvas-start w-75" id="fbs__net-navbars" tabindex="-1" aria-labelledby="fbs__net-navbarsLabel">
                    <div class="offcanvas-header">
                        <div class="offcanvas-header-logo">
                            <h5 id="fbs__net-navbarsLabel" class="offcanvas-title">Parent Planner</h5>
                            <a class="logo-link" id="fbs__net-navbarsLabel" href="{{ url('/') }}">
                                <img class="logo light img-fluid" src="{{asset('assets/images/light-logo.png')}}" alt="Parent Planner logo">
                            </a>
                        </div>
                        <button class="btn-close btn-close-black" type="button" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    
                    <div class="offcanvas-body align-items-lg-center">
                        <ul class="navbar-nav nav me-auto ps-lg-5 mb-2 mb-lg-0">
                            <li class="nav-item"><a class="nav-link scroll-link" href="{{ url('/') }}#home">Home</a></li>
                            <li class="nav-item"><a class="nav-link scroll-link" href="{{ url('/') }}#about">About</a></li>
                            <li class="nav-item"><a class="nav-link scroll-link" href="{{ url('/') }}#pricing">Pricing</a></li>
                            <li class="nav-item"><a class="nav-link scroll-link" href="{{ url('/') }}#how-it-works">How It Works</a></li>
                            <li class="nav-item"><a class="nav-link scroll-link" href="{{ url('/') }}#services">Services</a></li>
                            <li class="nav-item"><a class="nav-link scroll-link" href="{{ url('/') }}#contact">Contact</a></li>
                        </ul>
                    </div>
                </div>
                <!-- End offcanvas-->
                
                <div class="ms-auto w-auto">
                    @auth
                        <div class="header-social d-flex align-items-center gap-1">
                            <a class="btn btn-primary py-2" href="{{ url('/dashboard') }}">Dashboard</a>
                            <button class="fbs__net-navbar-toggler justify-content-center align-items-center ms-auto" data-bs-toggle="offcanvas" data-bs-target="#fbs__net-navbars" aria-controls="fbs__net-navbars" aria-label="Toggle navigation" aria-expanded="false">
                                <svg class="fbs__net-icon-menu" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="21" x2="3" y1="6" y2="6"></line>
                                    <line x1="15" x2="3" y1="12" y2="12"></line>
                                    <line x1="17" x2="3" y1="18" y2="18"></line>
                                </svg>
                                <svg class="fbs__net-icon-close" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M18 6 6 18"></path>
                                    <path d="m6 6 12 12"></path>
                                </svg>
                            </button>
                        </div>
                    @else
                        <div class="header-social d-flex align-items-center gap-1">
                            <a class="btn btn-outline-primary py-2 me-2" href="{{ route('login') }}">Log in</a>
                            <a class="btn btn-primary py-2" href="{{ route('register.choice') }}">Get Started</a>
                            <button class="fbs__net-navbar-toggler justify-content-center align-items-center ms-auto" data-bs-toggle="offcanvas" data-bs-target="#fbs__net-navbars" aria-controls="fbs__net-navbars" aria-label="Toggle navigation" aria-expanded="false">
                                <svg class="fbs__net-icon-menu" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="21" x2="3" y1="6" y2="6"></line>
                                    <line x1="15" x2="3" y1="12" y2="12"></line>
                                    <line x1="17" x2="3" y1="18" y2="18"></line>
                                </svg>
                                <svg class="fbs__net-icon-close" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M18 6 6 18"></path>
                                    <path d="m6 6 12 12"></path>
                                </svg>
                            </button>
                        </div>
                    @endauth
                </div>
            </div>
        </header>
        <!-- End Header-->

        <!-- ======= Main =======-->
        <main>
            <section class="section page-title">
                <div class="container">
                    <div class="row">
                        <div class="col-12 text-center">
                            <h1 class="mb-3">Privacy Policy</h1>
                            <span class="badge" style="background-color: var(--bs-primary); color: var(--bs-heading-color); font-size: 1rem; padding: 0.5rem 1rem; border-radius: 7px; font-weight: 600;">Parent Planner</span>
                        </div>
                    </div>
                </div>
            </section>
            
            <section class="section">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="legal-content">
                                <p class="mb-4"><strong>Effective Date:</strong> 22 September 2025</p>
                                <p class="mb-4"><strong>Last Updated:</strong> 22 September 2025</p>
                                
                                <p>At Parent Planner, your privacy is important to us. This Privacy Policy explains how we collect, use, store, and protect your personal information when you use our app and services.</p>
                                
                                <h2>1. Information We Collect</h2>
                                <p>We may collect the following information:</p>
                                <ul>
                                    <li>Personal Information: Name, email address, and contact details when you register.</li>
                                    <li>Child-Related Data: Names, schedules, visitation dates, and financial details related to your child.</li>
                                    <li>Payment Information: If applicable, financial transactions and payment history (we do not store credit card details directly).</li>
                                    <li>Documents: Files uploaded by you (e.g., legal agreements, school records, receipts).</li>
                                    <li>Usage Data: Device info, app usage data, and log files to help improve our services.</li>
                                </ul>
                                
                                <h2>2. How We Use Your Information</h2>
                                <p>We use your information to:</p>
                                <ul>
                                    <li>Provide and maintain our app's functionality.</li>
                                    <li>Facilitate scheduling, financial tracking, and document storage features.</li>
                                    <li>Send notifications and updates related to your child's schedule or account.</li>
                                    <li>Improve and personalise your experience.</li>
                                    <li>Comply with legal obligations.</li>
                                </ul>
                                
                                <h2>3. Sharing of Information</h2>
                                <p>We do not sell or share your personal data with third parties for marketing purposes. Information may be shared only when:</p>
                                <ul>
                                    <li>Required by law or legal process.</li>
                                    <li>Necessary to protect our rights or comply with a court order.</li>
                                    <li>Shared with a trusted service provider working on our behalf (e.g., secure cloud storage).</li>
                                </ul>
                                
                                <h2>4. Data Security</h2>
                                <p>We implement appropriate security measures (including encryption, secure servers, and access controls) to protect your information from unauthorized access or disclosure.</p>
                                
                                <h2>5. Data Retention</h2>
                                <p>We retain your data as long as your account is active or as required by law. You may request deletion of your data at any time by contacting us at info@parentplanner.site</p>
                                
                                <h2>6. Your Rights</h2>
                                <p>Depending on your location, you may have rights to:</p>
                                <ul>
                                    <li>Access the information we hold about you.</li>
                                    <li>Request corrections or deletion of your data.</li>
                                    <li>Object to certain uses of your data.</li>
                                    <li>Withdraw consent at any time.</li>
                                </ul>
                                
                                <h2>7. Children's Privacy</h2>
                                <p>Parent Planner is intended for use by adults managing children's information. We do not knowingly collect personal information from children under 13.</p>
                                
                                <h2>8. Changes to This Policy</h2>
                                <p>We may update this Privacy Policy from time to time. You'll be notified of significant changes via email or within the app.</p>
                                
                                <h2>9. Contact Us</h2>
                                <p>For questions or concerns, please contact: info@parentplanner.site</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        <!-- End Main-->

        <!-- ======= Footer =======-->
        <footer class="footer mt-5">
            <div class="container">
                <div class="row credits pt-3">
                    <div class="col-xl-8 text-center text-xl-start mb-3">
                        © <script>document.write(new Date().getFullYear());</script> Parent Planner. All rights reserved.
                    </div>
                    <div class="col-xl-4 text-center text-xl-end mb-3">
                        <a href="{{ route('terms') }}" class="text-decoration-none me-3">Terms</a>
                        <a href="{{ route('privacy') }}" class="text-decoration-none me-3">Privacy</a>
                        <a href="{{ route('refund') }}" class="text-decoration-none">Refund Policy</a>
                    </div>
                </div>
            </div>
        </footer>
        <!-- End Footer-->

        <!-- ======= Javascripts =======-->
        <script src="{{asset('assets/vendors/bootstrap/bootstrap.bundle.min.js')}}"></script>
        <!-- End JavaScripts-->
    </div>
</body>
</html>