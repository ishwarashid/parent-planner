<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Terms and Conditions - Parent Planner</title>
    
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
                            <h1 class="mb-3">Terms and Conditions</h1>
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
                                
                                <h2>Acceptance of Terms</h2>
                                <p>Welcome to Engineeric Trading As Parent Planner (the "Company", "we", "our", or "us"). These Terms and Conditions govern your access to and use of Parent Planner's website, services, and applications (collectively, the "Service").</p>
                                <p>By accessing or using our Service, you agree to be bound by these Terms and all applicable laws and regulations. If you do not agree with any part of these Terms, you must not access the Service.</p>
                                <p>We reserve the right to modify these Terms at any time. We will notify you of any changes by posting the new Terms on this page and updating the "Effective Date." Your continued use of the Service after such changes constitutes your acceptance of the modified Terms.</p>
                                
                                <h2>Eligibility</h2>
                                <p>To use our Service, you must be at least 18 years old or have the consent of a parent or guardian. By using our Service, you represent and warrant that you meet these requirements.</p>
                                <p>You agree to use the Service only for lawful purposes and in accordance with these Terms. You are responsible for all activities that occur under your account.</p>
                                
                                <h2>Use of the App</h2>
                                <p>You are granted a limited, non-exclusive, non-transferable license to access and use the Service for your personal or internal business purposes. You may not:</p>
                                <ul>
                                    <li>Copy, modify, or create derivative works of the Service</li>
                                    <li>Reverse engineer, decompile, or disassemble the Service</li>
                                    <li>Rent, lease, lend, sell, redistribute, or sublicense the Service</li>
                                    <li>Remove any proprietary notices or labels on the Service</li>
                                    <li>Use the Service for any illegal purpose or in violation of any laws</li>
                                </ul>
                                
                                <h2>User Content</h2>
                                <p>You retain ownership of any content you submit to the Service ("Your Content"). By submitting content, you grant us a worldwide, non-exclusive, royalty-free license to use, reproduce, adapt, publish, translate, and distribute your content in connection with the Service.</p>
                                <p>You are solely responsible for your content and the consequences of posting or publishing it. You represent and warrant that you have all necessary rights to grant the license above and that your content does not infringe any third-party rights.</p>
                                
                                <h2>Payment & Subscriptions</h2>
                                <p>Some features of the Service require payment of fees. All payments are processed through our payment processor.</p>
                                <p>Unless otherwise stated, all fees are non-refundable. You may cancel your subscription at any time, and your access will continue until the end of your current billing period. For information on refunds, please refer to our <a href="{{ route('refund') }}">Refund Policy</a>.</p>
                                <p>We reserve the right to change our fees at any time with prior notice. Your continued use of the Service after such changes constitutes acceptance of the modified fees.</p>
                                <p>All payments are processed through Paddle, our Merchant of Record. By subscribing to our service, you agree to Paddle's terms and conditions.</p>
                                
                                <h2>Termination</h2>
                                <p>We may terminate or suspend your account and access to the Service immediately, without prior notice, for any reason, including but not limited to breach of these Terms.</p>
                                <p>Upon termination, your right to use the Service will cease immediately. If you wish to terminate your account, you may simply discontinue using the Service.</p>
                                
                                <h2>Disclaimers</h2>
                                <p>The Service is provided on an "as is" and "as available" basis. We make no warranties, expressed or implied, regarding the Service.</p>
                                <p>We do not warrant that the Service will be uninterrupted, timely, secure, or error-free. We do not warrant that the results obtained from the use of the Service will be accurate or reliable.</p>
                                
                                <h2>Limitation of Liability</h2>
                                <p>To the fullest extent permitted by law, we shall not be liable for any indirect, incidental, special, consequential, or punitive damages, or any loss of profits or revenues.</p>
                                <p>In no event shall our total liability to you for all damages exceed the amount paid by you, if any, for accessing the Service during the twelve months immediately preceding the event giving rise to the claim.</p>
                                
                                <h2>Modifications</h2>
                                <p>We reserve the right to modify, suspend, or discontinue the Service (in whole or in part) at any time without notice. We will not be liable to you or any third party for any modification, suspension, or discontinuation of the Service.</p>
                                
                                <h2>Governing Law</h2>
                                <p>These Terms shall be governed by and construed in accordance with the laws of South Africa, without regard to its conflict of law provisions.</p>
                                <p>Any disputes arising from or relating to these Terms or the Service shall be resolved exclusively in the courts located in South Africa.</p>
                                
                                <h2>Contact Us</h2>
                                <p>If you have any questions about these Terms, please contact us at:</p>
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