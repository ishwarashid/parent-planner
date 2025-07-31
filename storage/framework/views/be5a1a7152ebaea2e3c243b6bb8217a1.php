<!DOCTYPE html>
<!--
Template name: Nova
Template author: FreeBootstrap.net
Author website: https://freebootstrap.net/
License: https://freebootstrap.net/license
-->
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> Family Planner </title>
     
    <!-- ======= Google Font =======-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&amp;display=swap" rel="stylesheet">
    <!-- End Google Font-->
    
    <!-- ======= Styles =======-->
    <link href="<?php echo e(asset('assets/vendors/bootstrap/bootstrap.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('assets/vendors/bootstrap-icons/font/bootstrap-icons.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('assets/vendors/glightbox/glightbox.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('assets/vendors/swiper/swiper-bundle.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('assets/vendors/aos/aos.css')); ?>" rel="stylesheet">
    <!-- End Styles-->
    
    <!-- ======= Theme Style =======-->
    <link href="assets/css/style.css" rel="stylesheet">
    <!-- End Theme Style-->
    
    <!-- ======= Apply theme =======-->
    <script>
      // Apply the theme as early as possible to avoid flicker
      (function() {
      const storedTheme = localStorage.getItem('theme') || 'light';
      document.documentElement.setAttribute('data-bs-theme', storedTheme);
      })();
    </script>
  </head>
  <body>
    
    
    <!-- ======= Site Wrap =======-->
    <div class="site-wrap">
      
      
      <!-- ======= Header =======-->
      <header class="fbs__net-navbar navbar navbar-expand-lg dark" aria-label="freebootstrap.net navbar">
        <div class="container d-flex align-items-center justify-content-between">
          
          
          <!-- Start Logo-->
          <a class="navbar-brand w-auto" href="index.html">
            <!-- If you use a text logo, uncomment this if it is commented-->
            Parent Planner
            
            <!-- If you plan to use an image logo, uncomment this if it is commented-->
            
            
            
            </a>
          <!-- End Logo-->
          
          <!-- Start offcanvas-->
          <div class="offcanvas offcanvas-start w-75" id="fbs__net-navbars" tabindex="-1" aria-labelledby="fbs__net-navbarsLabel">
            
            
            <div class="offcanvas-header">
              <div class="offcanvas-header-logo">
                <!-- If you use a text logo, uncomment this if it is commented-->
                
                <h5 id="fbs__net-navbarsLabel" class="offcanvas-title">Parent Planner</h5>
                
                <!-- If you plan to use an image logo, uncomment this if it is commented-->
                <a class="logo-link" id="fbs__net-navbarsLabel" href="index.html">
                  
                  
                  <!-- logo dark--><img class="logo dark img-fluid" src="assets/images/logo-dark.svg" alt="Parent Planner logo"> 
                  
                  <!-- logo light--><img class="logo light img-fluid" src="assets/images/logo-light.svg" alt="Parent Planner logo"></a>
                
              </div>
              <button class="btn-close btn-close-black" type="button" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            
            <div class="offcanvas-body align-items-lg-center">
              
              
              <ul class="navbar-nav nav me-auto ps-lg-5 mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link scroll-link active" aria-current="page" href="#home">Home</a></li>
                <li class="nav-item"><a class="nav-link scroll-link" href="#about">About</a></li>
                <li class="nav-item"><a class="nav-link scroll-link" href="#pricing">Pricing</a></li>
                <li class="nav-item"><a class="nav-link scroll-link" href="#how-it-works">How It Works</a></li>
                <li class="nav-item"><a class="nav-link scroll-link" href="#services">Services</a></li>
                <li class="nav-item"><a class="nav-link scroll-link" href="#contact">Contact</a></li>
              </ul>
              
            </div>
          </div>
          <!-- End offcanvas-->
          
          <div class="ms-auto w-auto">
            
            
            <div class="header-social d-flex align-items-center gap-1"><a class="btn btn-primary py-2" href="<?php echo e(route('register')); ?>">Get Started</a>
              
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
            
          </div>
        </div>
      </header>
      <!-- End Header-->
      
      <!-- ======= Main =======-->
      <main>
        
        
        <!-- ======= Hero =======-->
        <section class="hero__v6 section" id="home">
          <div class="container">
            <div class="row">
              <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="row">
                  <div class="col-lg-11"><span class="hero-subtitle text-uppercase" data-aos="fade-up" data-aos-delay="0">Welcome to Parent Planner</span>
                    <h1 class="hero-title mb-3" data-aos="fade-up" data-aos-delay="100">Simplifying Parenting, Together</h1>
                    <p class="hero-description mb-4 mb-lg-5" data-aos="fade-up" data-aos-delay="200">Whether you're managing school schedules, extracurriculars, or navigating co-parenting after separation or divorce, we're here to help.</p>
                    <div class="cta d-flex gap-2 mb-4 mb-lg-5" data-aos="fade-up" data-aos-delay="300"><a class="btn" href="<?php echo e(route('register')); ?>">Get Started Now</a><a class="btn btn-white-outline" href="<?php echo e(route('login')); ?>">Learn More 
                        <svg class="lucide lucide-arrow-up-right" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                          <path d="M7 7h10v10"></path>
                          <path d="M7 17 17 7"></path>
                        </svg></a></div>
                    <div class="logos mb-4" data-aos="fade-up" data-aos-delay="400"><span class="logos-title text-uppercase mb-4 d-block">Making parenting life better</span>
                      
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="hero-img">
                  
                  <img class="img-main img-fluid rounded-4" src="assets/images/hero-img-1-minn.jpg" alt="Hero Image" data-aos="fade-in" data-aos-delay="500"></div>
              </div>
            </div>
          </div>
          <!-- End Hero-->
        </section>
        <!-- End Hero-->
        
        <!-- ======= About =======-->
        <section class="about__v4 section" id="about">
          <div class="container">
            <div class="row">
              <div class="col-md-6 order-md-2">
                <div class="row justify-content-end">
                  <div class="col-md-11 mb-4 mb-md-0"><span class="subtitle text-uppercase mb-3" data-aos="fade-up" data-aos-delay="0">About us</span>
                    <h2 class="mb-4" data-aos="fade-up" data-aos-delay="100">Designed for Modern Parents</h2>
                    <div data-aos="fade-up" data-aos-delay="200">
                      <p>Parent Planner is your all-in-one solution for managing your child's schedule and keeping family life organized.</p>
                      <p>Our platform makes it easy for both single and co-parents to stay informed, connected, and in control—so you can focus on what matters most: your kids.</p>
                    </div>
                    <h4 class="small fw-bold mt-4 mb-3" data-aos="fade-up" data-aos-delay="300">Why Choose Parent Planner?</h4>
                    <ul class="d-flex flex-row flex-wrap list-unstyled gap-3 features" data-aos="fade-up" data-aos-delay="400">
                      <li class="d-flex align-items-center gap-2"><span class="icon rounded-circle text-center"><i class="bi bi-check"></i></span><span class="text">Easy-to-use interface</span></li>
                      <li class="d-flex align-items-center gap-2"><span class="icon rounded-circle text-center"><i class="bi bi-check"></i></span><span class="text">Built for both single and co-parents</span></li>
                      <li class="d-flex align-items-center gap-2"><span class="icon rounded-circle text-center"><i class="bi bi-check"></i></span><span class="text">Transparent financial tracking</span></li>
                      <li class="d-flex align-items-center gap-2"><span class="icon rounded-circle text-center"><i class="bi bi-check"></i></span><span class="text">Real-time updates & notifications</span></li>
                      <li class="d-flex align-items-center gap-2"><span class="icon rounded-circle text-center"><i class="bi bi-check"></i></span><span class="text">Secure and private</span></li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="col-md-6"> 
                <div class="img-wrap position-relative"><img class="img-fluid rounded-4" src="assets/images/about_2-min.jpg" alt="FreeBootstrap.net image placeholder" data-aos="fade-up" data-aos-delay="0">
                  <div class="mission-statement p-4 rounded-4 d-flex gap-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="mission-icon text-center rounded-circle"><i class="bi bi-lightbulb fs-4"></i></div>
                    <div>
                      <h3 class="text-uppercase fw-bold">Our Goal</h3>
                      <p class="fs-5 mb-0">To make parenting and co-parenting less stressful, with no more confusion, miscommunication, or forgotten commitments.</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
        <!-- End About-->
        
        <!-- ======= Features =======-->
        <section class="section features__v2" id="features">
          <div class="container">
            <div class="row">
              <div class="col-12">
                <div class="d-lg-flex p-5 rounded-4 content" data-aos="fade-in" data-aos-delay="0">
                  <div class="row">
                    <div class="col-lg-5 mb-5 mb-lg-0" data-aos="fade-up" data-aos-delay="0">
                      <div class="row"> 
                        <div class="col-lg-11">
                          <div class="h-100 flex-column justify-content-between d-flex">
                            <div>
                              <h2 class="mb-4">Key Features</h2>
                              <p class="mb-5">Parent Planner offers a range of features to simplify parenting, from smart scheduling and visitation calendars to expense management and detailed reporting. Our goal is to provide a seamless and organized experience for all parents.</p>
                            </div>
                            <div class="align-self-start"><a class="glightbox btn btn-play d-inline-flex align-items-center gap-2" href="https://www.youtube.com/watch?v=DQx96G4yHd8" data-gallery="video"><i class="bi bi-play-fill"></i> Watch the Video</a></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-7">
                      <div class="row justify-content-end">
                        <div class="col-lg-11">
                          <div class="row">
                            <div class="col-sm-6" data-aos="fade-up" data-aos-delay="0">
                              <div class="icon text-center mb-4"><i class="bi bi-calendar-check fs-4"></i></div>
                              <h3 class="fs-6 fw-bold mb-3">Smart Scheduling</h3>
                              <p>Create and manage daily, weekly, and monthly schedules with a shared calendar to keep both parents in sync.</p>
                            </div>
                            <div class="col-sm-6" data-aos="fade-up" data-aos-delay="100">
                              <div class="icon text-center mb-4"><i class="bi bi-people fs-4"></i></div>
                              <h3 class="fs-6 fw-bold mb-3">Visitation Calendar</h3>
                              <p>Track custody arrangements and parenting time with automated reminders and color-coded views.</p>
                            </div>
                            <div class="col-sm-6" data-aos="fade-up" data-aos-delay="200">
                              <div class="icon text-center mb-4"><i class="bi bi-cash-coin fs-4"></i></div>
                              <h3 class="fs-6 fw-bold mb-3">Expense Management</h3>
                              <p>Split costs, track shared expenses like school supplies and medical bills, and record payments with a clear financial log.</p>
                            </div>
                            <div class="col-sm-6" data-aos="fade-up" data-aos-delay="300">
                              <div class="icon text-center mb-4"><i class="bi bi-file-earmark-text fs-4"></i></div>
                              <h3 class="fs-6 fw-bold mb-3">Detailed Reports</h3>
                              <p>Generate accurate, organized reports for personal records or legal and financial purposes at any time.</p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
        <!-- End Features-->
        
        <!-- ======= Pricing =======-->
        <section class="section pricing__v2" id="pricing">
          <div class="container">
            <div class="row mb-5">
              <div class="col-md-5 mx-auto text-center"><span class="subtitle text-uppercase mb-3" data-aos="fade-up" data-aos-delay="0">Pricing</span>
                <h2 class="mb-3" data-aos="fade-up" data-aos-delay="100">A plan for every family</h2>
                <p data-aos="fade-up" data-aos-delay="200">Choose a subscription that's right for you and start simplifying your parenting journey today.</p>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4 mb-4 mb-md-0" data-aos="fade-up" data-aos-delay="300">
                <div class="p-5 rounded-4 price-table h-100">
                  <h3>Basic</h3>
                  <p>For single parents or those new to co-parenting, get essential features to organize your family life.</p>
                  <div class="price mb-4"><strong>$9.99</strong><span>/ month</span></div>
                  <div><a class="btn" href="<?php echo e(route('pricing')); ?>">Get Started</a></div>
                </div>
              </div>
              <div class="col-md-8" data-aos="fade-up" data-aos-delay="400">
                <div class="p-5 rounded-4 price-table popular h-100">
                  <div class="row">
                    <div class="col-md-6">
                      <h3 class="mb-3">Premium</h3>
                      <p>For co-parents who need advanced tools for seamless coordination and financial tracking.</p>
                      <div class="price mb-4"><strong class="me-1">$19.99</strong><span>/ month</span></div>
                      <div><a class="btn btn-white hover-outline" href="#">Get Started</a></div>
                    </div>
                    <div class="col-md-6 pricing-features">
                      <h4 class="text-uppercase fw-bold mb-3">Features</h4>
                      <ul class="list-unstyled d-flex flex-column gap-3">
                        <li class="d-flex gap-2 align-items-start mb-0"><span class="icon rounded-circle position-relative mt-1"><i class="bi bi-check"></i></span><span>All Basic features</span></li>
                        <li class="d-flex gap-2 align-items-start mb-0"><span class="icon rounded-circle position-relative mt-1"><i class="bi bi-check"></i></span><span>Advanced financial reporting</span></li>
                        <li class="d-flex gap-2 align-items-start mb-0"><span class="icon rounded-circle position-relative mt-1"><i class="bi bi-check"></i></span><span>Secure document storage for legal papers</span></li>
                        <li class="d-flex gap-2 align-items-start mb-0"><span class="icon rounded-circle position-relative mt-1"><i class="bi bi-check"></i></span><span>Priority customer support</span></li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
        <!-- End Pricing-->
        
        <!-- ======= How it works =======-->
        <section class="section howitworks__v1" id="how-it-works">
          <div class="container">
            <div class="row mb-5">
              <div class="col-md-6 text-center mx-auto"><span class="subtitle text-uppercase mb-3" data-aos="fade-up" data-aos-delay="0">How it works</span>
                <h2 data-aos="fade-up" data-aos-delay="100">Get Started in 4 Easy Steps</h2>
                <p data-aos="fade-up" data-aos-delay="200">Our platform is designed for simplicity. Follow these easy steps to begin organizing your parenting life: </p>
              </div>
            </div>
            <div class="row g-md-5">
              <div class="col-md-6 col-lg-3">
                <div class="step-card text-center h-100 d-flex flex-column justify-content-start position-relative" data-aos="fade-up" data-aos-delay="0">
                  <div data-aos="fade-right" data-aos-delay="500"><img class="arch-line" src="assets/images/arch-line.svg" alt="FreeBootstrap.net image placeholder"></div><span class="step-number rounded-circle text-center fw-bold mb-5 mx-auto">1</span>
                  <div>
                    <h3 class="fs-5 mb-4">Sign Up</h3>
                    <p>Create your secure Parent Planner account in just a few minutes.</p>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="600">
                <div class="step-card reverse text-center h-100 d-flex flex-column justify-content-start position-relative">
                  <div data-aos="fade-right" data-aos-delay="1100"><img class="arch-line reverse" src="assets/images/arch-line-reverse.svg" alt="FreeBootstrap.net image placeholder"></div><span class="step-number rounded-circle text-center fw-bold mb-5 mx-auto">2</span>
                  <h3 class="fs-5 mb-4">Set Up Your Family</h3>
                  <p>Add your children's information and connect with your co-parent if applicable.</p>
                </div>
              </div>
              <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="1200">
                <div class="step-card text-center h-100 d-flex flex-column justify-content-start position-relative">
                  <div data-aos="fade-right" data-aos-delay="1700"><img class="arch-line" src="assets/images/arch-line.svg" alt="FreeBootstrap.net image placeholder"></div><span class="step-number rounded-circle text-center fw-bold mb-5 mx-auto">3</span>
                  <h3 class="fs-5 mb-4">Organize & Track</h3>
                  <p>Start adding schedules, expenses, and important documents to your shared space.</p>
                </div>
              </div>
              <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="1800">
                <div class="step-card last text-center h-100 d-flex flex-column justify-content-start position-relative"><span class="step-number rounded-circle text-center fw-bold mb-5 mx-auto">4</span>
                  <div>
                    <h3 class="fs-5 mb-4">Stay in Sync</h3>
                    <p>Receive real-time updates and notifications to stay informed and accountable.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
        <!-- End How it works-->
        
        <!-- ======= Stats =======-->
        <section class="stats__v3 section">
          <div class="container">
            <div class="row">
              <div class="col-12">
                <div class="d-flex flex-wrap content rounded-4" data-aos="fade-up" data-aos-delay="0">
                  <div class="rounded-borders">
                    <div class="rounded-border-1"></div>
                    <div class="rounded-border-2"></div>
                    <div class="rounded-border-3"></div>
                  </div>
                  <div class="col-12 col-sm-6 col-md-4 mb-4 mb-md-0 text-center" data-aos="fade-up" data-aos-delay="100">
                    <div class="stat-item">
                      <h3 class="fs-1 fw-bold"><span class="purecounter" data-purecounter-start="0" data-purecounter-end="10" data-purecounter-duration="2">0</span><span>K+</span></h3>
                      <p class="mb-0">Happy Families</p>
                    </div>
                  </div>
                  <div class="col-12 col-sm-6 col-md-4 mb-4 mb-md-0 text-center" data-aos="fade-up" data-aos-delay="200">
                    <div class="stat-item">
                      <h3 class="fs-1 fw-bold"> <span class="purecounter" data-purecounter-start="0" data-purecounter-end="98" data-purecounter-duration="2">0</span><span>%</span></h3>
                      <p class="mb-0">User Satisfaction</p>
                    </div>
                  </div>
                  <div class="col-12 col-sm-6 col-md-4 mb-4 mb-md-0 text-center" data-aos="fade-up" data-aos-delay="300">
                    <div class="stat-item">
                      <h3 class="fs-1 fw-bold"><span class="purecounter" data-purecounter-start="0" data-purecounter-end="5" data-purecounter-duration="2">0</span><span>M+</span></h3>
                      <p class="mb-0">Events Scheduled</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
        <!-- End Stats-->
        
        <!-- ======= Services =======-->
        <section class="section services__v3" id="services">
          <div class="container">
            <div class="row mb-5">
              <div class="col-md-8 mx-auto text-center"><span class="subtitle text-uppercase mb-3" data-aos="fade-up" data-aos-delay="0">Our Services</span>
                <h2 class="mb-3" data-aos="fade-up" data-aos-delay="100">Empowering Parents Through Technology</h2>
              </div>
            </div>
            <div class="row g-4">
              <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="0">
                <div class="service-card p-4 rounded-4 h-100 d-flex flex-column justify-content-between gap-5">
                  <div><span class="icon mb-4"><i class="bi bi-calendar-event fs-1"></i></span>
                    <h3 class="fs-5 mb-3">Scheduling & Calendars</h3>
                    <p class="mb-4">Easily create and manage your child’s daily, weekly, and monthly schedules. Our shared calendar keeps both parents in sync, avoiding double-bookings and confusion.</p>
                  </div><a class="special-link d-inline-flex gap-2 align-items-center text-decoration-none" href="#"><span class="icons"><i class="icon-1 bi bi-arrow-right-short"></i><i class="icon-2 bi bi-arrow-right-short"> </i></span><span>Read more</span></a>
                </div>
              </div>
              <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="service-card p-4 rounded-4 h-100 d-flex flex-column justify-content-between gap-5">
                  <div><span class="icon mb-4"><i class="bi bi-cash-stack fs-1"></i></span>
                    <h3 class="fs-5 mb-3">Financial Tracking</h3>
                    <p class="mb-4">Log, track, and manage child support and other maintenance payments. Split costs for school supplies, medical bills, and extracurricular activities with full transparency.</p>
                  </div><a class="special-link d-inline-flex gap-2 align-items-center text-decoration-none" href="#"><span class="icons"><i class="icon-1 bi bi-arrow-right-short"></i><i class="icon-2 bi bi-arrow-right-short"> </i></span><span>Read more</span></a>
                </div>
              </div>
              <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                <div class="service-card p-4 rounded-4 h-100 d-flex flex-column justify-content-between gap-5">
                  <div><span class="icon mb-4"><i class="bi bi-file-text fs-1"></i></span>
                    <h3 class="fs-5 mb-3">Reporting</h3>
                    <p class="mb-4">Generate detailed reports for your own records or for legal and financial purposes. Get clear breakdowns of payments, expenses, and visitation history.</p>
                  </div><a class="special-link d-inline-flex gap-2 align-items-center text-decoration-none" href="#"><span class="icons"><i class="icon-1 bi bi-arrow-right-short"></i><i class="icon-2 bi bi-arrow-right-short"> </i></span><span>Read more</span></a>
                </div>
              </div>
              <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="400">
                <div class="service-card p-4 rounded-4 h-100 d-flex flex-column justify-content-between gap-5">
                  <div><span class="icon mb-4"><i class="bi bi-shield-lock fs-1"></i></span>
                    <h3 class="fs-5 mb-3">Secure Document Storage</h3>
                    <p class="mb-4">Securely upload and store essential documents like birth certificates, school records, court orders, and custody agreements in one convenient location.</p>
                  </div><a class="special-link d-inline-flex gap-2 align-items-center text-decoration-none" href="#"><span class="icons"><i class="icon-1 bi bi-arrow-right-short"></i><i class="icon-2 bi bi-arrow-right-short"> </i></span><span>Read more</span></a>
                </div>
              </div>
             
            </div>
          </div>
        </section>
        <!-- Services-->
        
        <!-- ======= Testimonials =======-->
        <section class="section testimonials__v2" id="testimonials">
          <div class="container">
            <div class="row mb-5">
              <div class="col-lg-5 mx-auto text-center"><span class="subtitle text-uppercase mb-3" data-aos="fade-up" data-aos-delay="0">Testimonials</span>
                <h2 class="mb-3" data-aos="fade-up" data-aos-delay="100">What Our Users Are Saying</h2>
                <p data-aos="fade-up" data-aos-delay="200">Real stories from parents who have simplified their lives with Parent Planner.</p>
              </div>
            </div>
            <div class="row g-4" data-masonry="{"percentPosition": true }">
              <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="0">
                <div class="testimonial rounded-4 p-4">
                  <blockquote class="mb-3">
                     “
                    This app has been a lifesaver for co-parenting. The shared calendar and expense tracker have eliminated so many arguments and misunderstandings.
                    ”
                  </blockquote>
                  <div class="testimonial-author d-flex gap-3 align-items-center">
                    <div class="author-img"><img class="rounded-circle img-fluid" src="assets/images/person-sq-2-min.jpg" alt="FreeBootstrap.net image placeholder"></div>
                    <div class="lh-base"><strong class="d-block">John D.</strong><span>Co-Parent</span></div>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="testimonial rounded-4 p-4">
                  <blockquote class="mb-3">
                     “
                    As a single mom, keeping track of everything was overwhelming. Parent Planner has helped me stay organized and on top of all the appointments and school events.
                    ”
                  </blockquote>
                  <div class="testimonial-author d-flex gap-3 align-items-center">
                    <div class="author-img"><img class="rounded-circle img-fluid" src="assets/images/person-sq-1-min.jpg" alt="FreeBootstrap.net image placeholder"></div>
                    <div class="lh-base"><strong class="d-block">Emily S.</strong><span>Single Parent</span></div>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="testimonial rounded-4 p-4">
                  <blockquote class="mb-3">
                     “
                    The ability to generate financial reports for my legal case was incredibly helpful. Everything is clear, organized, and easy to export.
                    ”
                  </blockquote>
                  <div class="testimonial-author d-flex gap-3 align-items-center">
                    <div class="author-img"><img class="rounded-circle img-fluid" src="assets/images/person-sq-5-min.jpg" alt="FreeBootstrap.net image placeholder"></div>
                    <div class="lh-base"><strong class="d-block">Michael R.</strong><span>Recently Divorced Parent</span></div>
                  </div>
                </div>
              </div>
              
            </div>
          </div>
        </section>
        <!-- Testimonials-->
        
        <!-- ======= FAQ =======-->
        <section class="section faq__v2" id="faq">
          <div class="container">
            <div class="row mb-4">
              <div class="col-md-6 col-lg-7 mx-auto text-center"><span class="subtitle text-uppercase mb-3" data-aos="fade-up" data-aos-delay="0">FAQ</span>
                <h2 class="h2 fw-bold mb-3" data-aos="fade-up" data-aos-delay="0">Frequently Asked Questions</h2>
                <p data-aos="fade-up" data-aos-delay="100">Find answers to common questions about Parent Planner.</p>
              </div>
            </div>
            <div class="row">
              <div class="col-md-8 mx-auto" data-aos="fade-up" data-aos-delay="200">
                <div class="faq-content">
                  <div class="accordion custom-accordion" id="accordionPanelsStayOpenExample">
                    <div class="accordion-item">
                      <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne"> Who is Parent Planner for? </button>
                      </h2>
                      <div class="accordion-collapse collapse show" id="panelsStayOpen-collapseOne">
                        <div class="accordion-body">Parent Planner is designed for all modern parents, including single parents and co-parents who are separated or divorced. Our features are built to support various parenting situations.</div>
                      </div>
                    </div>
                    <div class="accordion-item">
                      <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo"> Is my data secure? </button>
                      </h2>
                      <div class="accordion-collapse collapse" id="panelsStayOpen-collapseTwo">
                        <div class="accordion-body">Yes, we take data security very seriously. Parent Planner allows you to securely upload and store essential documents related to your child. Your information is protected and always available when you need it.</div>
                      </div>
                    </div>
                    <div class="accordion-item">
                      <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false" aria-controls="panelsStayOpen-collapseThree"> Can I use the reports for legal purposes? </button>
                      </h2>
                      <div class="accordion-collapse collapse" id="panelsStayOpen-collapseThree">
                        <div class="accordion-body">Yes, you can generate detailed financial and visitation reports that are organized and accurate. These reports can be downloaded and used for legal or financial documentation.</div>
                      </div>
                    </div>
                    <div class="accordion-item">
                      <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseFour" aria-expanded="false" aria-controls="panelsStayOpen-collapseFour"> How does the visitation calendar work for co-parents? </button>
                      </h2>
                      <div class="accordion-collapse collapse" id="panelsStayOpen-collapseFour">
                        <div class="accordion-body">The intuitive visitation calendar allows both parents to track custody arrangements and parenting time clearly. It features automated reminders and color-coded views for seamless coordination between co-parents.</div>
                      </div>
                    </div>
                   
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- End FAQ-->
        </section>
        <!-- End FAQ-->
        
        <!-- ======= Contact =======-->
        <section class="section contact__v2" id="contact">
          <div class="container">
            <div class="row mb-5">
              <div class="col-md-6 col-lg-7 mx-auto text-center"><span class="subtitle text-uppercase mb-3" data-aos="fade-up" data-aos-delay="0">Contact</span>
                <h2 class="h2 fw-bold mb-3" data-aos="fade-up" data-aos-delay="0">Contact Us</h2>
                <p data-aos="fade-up" data-aos-delay="100">Have a question or need support? Get in touch with us.</p>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="d-flex gap-5 flex-column">
                  <div class="d-flex align-items-start gap-3" data-aos="fade-up" data-aos-delay="0">
                    <div class="icon d-block"><i class="bi bi-telephone"></i></div><span> <span class="d-block">Phone</span><strong>+(01 234 567 890)</strong></span>
                  </div>
                  <div class="d-flex align-items-start gap-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="icon d-block"><i class="bi bi-send"></i></div><span> <span class="d-block">Email</span><strong>support@parentplanner.com</strong></span>
                  </div>
                  <div class="d-flex align-items-start gap-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="icon d-block"><i class="bi bi-geo-alt"></i></div><span> <span class="d-block">Address</span>
                      <address class="fw-bold">123 Main Street Apt 4B Springfield, <br> IL 62701 United States</address></span>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-wrapper" data-aos="fade-up" data-aos-delay="300">
                  <form id="contactForm">
                    <div class="row gap-3 mb-3">
                      <div class="col-md-12">
                        <label class="mb-2" for="name">Name</label>
                        <input class="form-control" id="name" type="text" name="name" required="">
                      </div>
                      <div class="col-md-12">
                        <label class="mb-2" for="email">Email</label>
                        <input class="form-control" id="email" type="email" name="email" required="">
                      </div>
                    </div>
                    <div class="row gap-3 mb-3">
                      <div class="col-md-12">
                        <label class="mb-2" for="subject">Subject</label>
                        <input class="form-control" id="subject" type="text" name="subject">
                      </div>
                    </div>
                    <div class="row gap-3 gap-md-0 mb-3">
                      <div class="col-md-12">
                        <label class="mb-2" for="message">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="5" required=""></textarea>
                      </div>
                    </div>
                    <button class="btn btn-primary fw-semibold" type="submit">Send Message</button>
                  </form>
                  <div class="mt-3 d-none alert alert-success" id="successMessage">Message sent successfully!</div>
                  <div class="mt-3 d-none alert alert-danger" id="errorMessage">Message sending failed. Please try again later.</div>
                </div>
              </div>
            </div>
          </div>
        </section>
        <!-- End Contact-->
        
        <!-- ======= Footer =======-->
        <footer class="footer pt-5 pb-5">
          <div class="container">
            <div class="row mb-5 pb-4">
              <div class="col-md-7">
                <h2 class="fs-5">Join our newsletter</h2>
                <p>Stay updated with our latest features and offers—join our newsletter today!</p>
              </div>
              <div class="col-md-5">
                <form class="d-flex gap-2">
                  <input class="form-control bg-light" type="email" placeholder="Email your email" required="">
                  <button class="btn btn-primary fs-6" type="submit">Subscribe</button>
                </form>
              </div>
            </div>
           
            <div class="row credits pt-3">
              <div class="col-xl-8 text-center text-xl-start mb-3 mb-xl-0">
                
                ©
                <script>document.write(new Date().getFullYear());</script> Parent Planner. 
                 All rights reserved.
              </div>
              
            </div>
          </div>
        </footer>
        <!-- End Footer-->
        
      </main>
    </div>
    
    <!-- ======= Back to Top =======-->
    <button id="back-to-top"><i class="bi bi-arrow-up-short"></i></button>
    <!-- End Back to top-->
    
    <!-- ======= Javascripts =======-->
    <script src="assets/vendors/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="assets/vendors/gsap/gsap.min.js"></script>
    <script src="assets/vendors/imagesloaded/imagesloaded.pkgd.min.js"></script>
    <script src="assets/vendors/isotope/isotope.pkgd.min.js"></script>
    <script src="assets/vendors/glightbox/glightbox.min.js"></script>
    <script src="assets/vendors/swiper/swiper-bundle.min.js"></script>
    <script src="assets/vendors/aos/aos.js"></script>
    <script src="assets/vendors/purecounter/purecounter.js"></script>
    <script src="assets/js/custom.js"></script>
    <script src="assets/js/send_email.js"></script>
    <!-- End JavaScripts-->
  </body>
</html><?php /**PATH C:\laragon\www\parent-planner-v2\resources\views/landing2.blade.php ENDPATH**/ ?>