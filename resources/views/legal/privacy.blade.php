<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Privacy Policy - Parent Planner</title>
    
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
                                
                                <p>Engineeric Trading As Parent Planner (the "Company", "we", "our", or "us") is committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our website, services, and applications (collectively, the "Service").</p>
                                
                                <h2>Information We Collect</h2>
                                <h3>Personal Information</h3>
                                <p>We may collect personally identifiable information that you voluntarily provide to us when you register, express an interest in obtaining information about us or our products and services, participate in activities on the Service, or otherwise contact us.</p>
                                <p>The personal information we collect may include:</p>
                                <ul>
                                    <li>Name</li>
                                    <li>Email address</li>
                                    <li>Phone number</li>
                                    <li>Postal address</li>
                                    <li>Payment information</li>
                                    <li>Child-related information (as necessary for our services)</li>
                                </ul>
                                
                                <h3>Usage Data</h3>
                                <p>We may automatically collect information about your device and usage of our Service, including:</p>
                                <ul>
                                    <li>IP address</li>
                                    <li>Browser type and version</li>
                                    <li>Pages visited</li>
                                    <li>Time and date of visit</li>
                                    <li>Time spent on pages</li>
                                </ul>
                                
                                <h2>How We Use Your Information</h2>
                                <p>We use your information for various purposes, including:</p>
                                <ul>
                                    <li>To provide and maintain our Service</li>
                                    <li>To notify you about changes to our Service</li>
                                    <li>To allow you to participate in interactive features of our Service</li>
                                    <li>To provide customer support</li>
                                    <li>To gather analysis or valuable information to improve our Service</li>
                                    <li>To monitor usage of our Service</li>
                                    <li>To detect, prevent, and address technical issues</li>
                                </ul>
                                
                                <h2>Sharing of Information</h2>
                                <p>We may share your information in the following situations:</p>
                                <ul>
                                    <li><strong>With Service Providers:</strong> We may share your information with third-party service providers to monitor and analyze the use of our Service or to process payments.</li>
                                    <li><strong>For Business Transfers:</strong> We may share or transfer your information in connection with, or during negotiations of, any merger, sale of company assets, financing, or acquisition of all or a portion of our business.</li>
                                    <li><strong>With Affiliates:</strong> We may share your information with our affiliates, in which case we will require those affiliates to honor this Privacy Policy.</li>
                                    <li><strong>With Business Partners:</strong> We may share your information with our business partners to offer you certain products, services, or promotions.</li>
                                    <li><strong>With Your Consent:</strong> We may disclose your information for any other purpose with your consent.</li>
                                </ul>
                                
                                <h2>Data Security</h2>
                                <p>We use administrative, technical, and physical security measures to protect your personal information. However, no method of transmission over the Internet or method of electronic storage is 100% secure.</p>
                                
                                <h2>Data Retention</h2>
                                <p>We will retain your information for as long as necessary to fulfill the purposes outlined in this Privacy Policy, unless a longer retention period is required or permitted by law.</p>
                                
                                <h2>Your Rights</h2>
                                <p>Depending on your location, you may have certain rights regarding your personal information:</p>
                                <ul>
                                    <li><strong>Right to Access:</strong> You have the right to request copies of your personal data.</li>
                                    <li><strong>Right to Rectification:</strong> You have the right to request that we correct any information you believe is inaccurate or complete information you believe is incomplete.</li>
                                    <li><strong>Right to Erasure:</strong> You have the right to request that we erase your personal data, under certain conditions.</li>
                                    <li><strong>Right to Restrict Processing:</strong> You have the right to request that we restrict the processing of your personal data, under certain conditions.</li>
                                    <li><strong>Right to Object:</strong> You have the right to object to our processing of your personal data, under certain conditions.</li>
                                    <li><strong>Right to Data Portability:</strong> You have the right to request that we transfer the data that we have collected to another organization, or directly to you, under certain conditions.</li>
                                </ul>
                                
                                <h2>Children's Privacy</h2>
                                <p>Our Service does not address anyone under the age of 13. We do not knowingly collect personally identifiable information from anyone under the age of 13. If you are a parent or guardian and you are aware that your child has provided us with personal information, please contact us.</p>
                                
                                <h2>Changes to This Policy</h2>
                                <p>We may update our Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page and updating the "Effective Date."</p>
                                
                                <h2>Contact Us</h2>
                                <p>If you have any questions about this Privacy Policy, please contact us at:</p>
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