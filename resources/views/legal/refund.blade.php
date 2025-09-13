<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Refund Policy - Parent Planner</title>
    
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
                            <h1 class="mb-3">Refund Policy</h1>
                            <span class="badge" style="background-color: var(--bs-primary); color: var(--bs-heading-color); font-size: 1rem; padding: 0.5rem 1rem; border-radius: 7px; font-weight: 600;">Engineeric Trading As Parent Planner</span>
                        </div>
                    </div>
                </div>
            </section>
            
            <section class="section">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="legal-content">
                                <p class="mb-4"><strong>Effective Date:</strong> 13 September 2025</p>
                                <p class="mb-4"><strong>Last Updated:</strong> 13 September 2025</p>
                                
                                <h2>Company Information</h2>
                                <p>This Refund Policy is provided by Engineeric Trading As Parent Planner (the "Company", "we", "our", or "us").</p>
                                
                                <h2>Refund Eligibility</h2>
                                <p>We offer a 14-day money-back guarantee for new subscriptions. If you are not satisfied with our service within this period, you may request a full refund of your initial subscription payment.</p>
                                <p>After the initial 14-day period, refunds for prepaid subscriptions will be provided on a prorated basis for the unused portion of your subscription period, minus any applicable processing fees.</p>
                                
                                <h2>Refund Request Process</h2>
                                <p>To request a refund, please contact us via email at info@parentplanner.site with the following information:</p>
                                <ul>
                                    <li>Your name and email address associated with your account</li>
                                    <li>The date of your subscription purchase</li>
                                    <li>The reason for your refund request</li>
                                </ul>
                                
                                <h2>Refund Processing</h2>
                                <p>Refund requests are typically processed within 5-10 business days. Refunds will be issued to the original payment method used for the purchase.</p>
                                <p>Please note that depending on your payment provider, it may take an additional 5-10 business days for the refund to appear in your account.</p>
                                
                                <h2>Special Circumstances</h2>
                                <p>We may issue refunds outside of our standard policy in special circumstances, such as:</p>
                                <ul>
                                    <li>Service interruptions or technical issues that significantly impact usability</li>
                                    <li>Billing errors on our part</li>
                                    <li>Unauthorized charges</li>
                                </ul>
                                
                                <h2>Non-Refundable Items</h2>
                                <p>Certain fees and charges are non-refundable, including but not limited to:</p>
                                <ul>
                                    <li>Processing fees for payments and refunds</li>
                                    <li>Fees for services already rendered</li>
                                    <li>One-time setup fees</li>
                                </ul>
                                
                                <h2>Changes to This Policy</h2>
                                <p>We reserve the right to modify this Refund Policy at any time. Changes will be effective immediately upon posting on our website. Your continued use of our services after any changes constitutes your acceptance of the modified policy.</p>
                                
                                <h2>Contact Us</h2>
                                <p>If you have any questions about our Refund Policy or would like to request a refund, please contact us at:</p>
                                <p><strong>Email:</strong> info@parentplanner.site</p>
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
                        © <script>document.write(new Date().getFullYear());</script> Engineeric Trading As Parent Planner. All rights reserved.
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