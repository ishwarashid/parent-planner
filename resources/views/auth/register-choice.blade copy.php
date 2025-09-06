<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Choose Account Type - Parent Planner</title>
    
    <!-- ======= Google Font =======-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <!-- End Google Font-->
    
    <!-- ======= Styles =======-->
    <link href="{{asset('assets/vendors/bootstrap/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/vendors/bootstrap-icons/font/bootstrap-icons.min.css')}}" rel="stylesheet">
    <!-- End Styles-->
    
    <!-- ======= Theme Style =======-->
    <link href="{{asset('assets/css/style.css')}}" rel="stylesheet">
    <!-- End Theme Style-->
    
    <style>
        .register-choice-bg {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4edf9 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .header {
            background-color: var(--nav-dark-bg);
            padding: 1rem 0;
            box-shadow: 0 0.1875rem 0.375rem rgba(0, 0, 0, 0.05);
        }
        
        .logo-text {
            font-size: 1.75rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
        }
        
        .card-option {
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 51, 0.2);
            background: white;
        }
        
        .card-option:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 51, 0.1);
            border-color: var(--bs-primary);
        }
        
        .icon-circle {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }
        
        .parent-icon {
            background-color: rgba(64, 224, 208, 0.1);
            color: var(--bs-primary);
        }
        
        .professional-icon {
            background-color: rgba(175, 238, 238, 0.3);
            color: #00CED1;
        }
        
        .feature-list li {
            margin-bottom: 0.75rem;
        }
        
        .feature-list .bi {
            width: 20px;
            height: 20px;
            line-height: 20px;
            text-align: center;
            background-color: var(--bs-primary);
            color: var(--bs-heading-color);
            border-radius: 50%;
            font-size: 0.75rem;
            margin-right: 0.75rem;
        }
        
        .btn-register {
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .btn-parent {
            background-color: var(--bs-primary);
            color: var(--bs-heading-color);
            border: none;
        }
        
        .btn-parent:hover {
            background-color: var(--bs-primary-hover);
            transform: translateY(-2px);
        }
        
        .btn-professional {
            background-color: #AFEEEE;
            color: var(--bs-heading-color);
            border: none;
        }
        
        .btn-professional:hover {
            background-color: #00CED1;
            color: white;
            transform: translateY(-2px);
        }
        
        .back-to-home {
            color: var(--bs-primary);
            text-decoration: none;
            font-weight: 500;
        }
        
        .back-to-home:hover {
            color: var(--bs-primary-hover);
            text-decoration: underline;
        }
        
        .main-content {
            flex: 1;
            display: flex;
            align-items: center;
            padding: 2rem 0;
        }
    </style>
</head>
<body>
    <div class="register-choice-bg">
        <!-- Header with Logo -->
        <header class="header">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ url('/') }}" class="logo-text">Parent Planner</a>
                    <a href="{{ url('/') }}" class="back-to-home d-inline-flex align-items-center">
                        <i class="bi bi-arrow-left me-2"></i>Go Back
                    </a>
                </div>
            </div>
        </header>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="text-center mb-5">
                            <h1 class="fw-bold mb-3" style="color: var(--bs-heading-color);">Join Parent Planner</h1>
                            <p class="lead mb-4">Choose the account type that best describes you</p>
                        </div>
                        
                        <div class="row g-4">
                            <!-- Parent Account Card -->
                            <div class="col-md-6">
                                <div class="card-option rounded-4 p-5 h-100">
                                    <div class="icon-circle parent-icon mb-4">
                                        <i class="bi bi-people fs-3"></i>
                                    </div>
                                    <h3 class="text-center fw-bold mb-3" style="color: var(--bs-heading-color);">Parent Account</h3>
                                    <p class="text-center mb-4" style="color: var(--bs-body-color);">
                                        For parents managing their family's schedule and co-parenting arrangements
                                    </p>
                                    
                                    <ul class="feature-list list-unstyled mb-4">
                                        <li class="d-flex align-items-center">
                                            <i class="bi bi-check"></i>
                                            <span>Shared family calendar</span>
                                        </li>
                                        <li class="d-flex align-items-center">
                                            <i class="bi bi-check"></i>
                                            <span>Expense tracking</span>
                                        </li>
                                        <li class="d-flex align-items-center">
                                            <i class="bi bi-check"></i>
                                            <span>Document storage</span>
                                        </li>
                                        <li class="d-flex align-items-center">
                                            <i class="bi bi-check"></i>
                                            <span>Visitation scheduling</span>
                                        </li>
                                    </ul>
                                    
                                    <a href="{{ route('register') }}" 
                                       class="btn btn-register btn-parent w-100 mt-auto">
                                        Register as Parent
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Professional Account Card -->
                            <div class="col-md-6">
                                <div class="card-option rounded-4 p-5 h-100">
                                    <div class="icon-circle professional-icon mb-4">
                                        <i class="bi bi-briefcase fs-3"></i>
                                    </div>
                                    <h3 class="text-center fw-bold mb-3" style="color: var(--bs-heading-color);">Professional Account</h3>
                                    <p class="text-center mb-4" style="color: var(--bs-body-color);">
                                        For professionals offering services to co-parenting families
                                    </p>
                                    
                                    <ul class="feature-list list-unstyled mb-4">
                                        <li class="d-flex align-items-center">
                                            <i class="bi bi-check"></i>
                                            <span>Connect with families</span>
                                        </li>
                                        <li class="d-flex align-items-center">
                                            <i class="bi bi-check"></i>
                                            <span>Profile visibility</span>
                                        </li>
                                        <li class="d-flex align-items-center">
                                            <i class="bi bi-check"></i>
                                            <span>Service promotion</span>
                                        </li>
                                        <li class="d-flex align-items-center">
                                            <i class="bi bi-check"></i>
                                            <span>Client management</span>
                                        </li>
                                    </ul>
                                    
                                    <a href="{{ route('professional.register') }}" 
                                       class="btn btn-register btn-professional w-100 mt-auto">
                                        Register as Professional
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center mt-5">
                            <p class="mb-0" style="color: var(--bs-body-color);">
                                Already have an account? 
                                <a href="{{ route('login') }}" class="fw-medium" style="color: var(--bs-primary);">Sign in here</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- ======= Javascripts =======-->
    <script src="{{asset('assets/vendors/bootstrap/bootstrap.bundle.min.js')}}"></script>
    <!-- End JavaScripts-->
</body>
</html>