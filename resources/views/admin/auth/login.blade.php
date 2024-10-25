
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Login | Admin Dashboard</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="laravel project" name="description" />
        <meta content="Coderthemes" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ url('public/assets/admin/images/favicon.ico') }}">

        <!-- App css -->
        <link href="{{ url('public/assets/admin/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ url('public/assets/admin/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ url('public/assets/admin/css/app.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{url('public/assets/libs/sweetalert/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{ url('public/assets/admin/css/admin-custome.css') }}" rel="stylesheet" type="text/css" />

        <!-- Vendor js -->
        <script src="{{url('public/assets/admin/js/vendor.min.js')}}"></script>

    </head>

    <body class="authentication-bg authentication-bg-pattern">

        <div class="account-pages mt-5 mb-5 login-page-content">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card bg-pattern">

                            <div class="card-body p-4">
                                
                                <div class="text-center w-75 m-auto">
                                    <a href="index.html">
                                        <span>
                                             <h2>Admin Login</h2>   
                                            <?php /* ?><img src="{{ url('public/assets/admin/images/logo-dark.png') }}" alt="" height="22"> <?php */ ?>
                                        </span>
                                    </a>
                                    <p class="text-muted mb-4 mt-3">Enter your email address and password to access admin panel.</p>
                                    @if(Session::has('success'))
                                    <div class=" mb-2 mt-1 login_error_message success_msg text-sucess">
                                    	{{ Session::get('success') }}
                                    </div>
                                    @endif

                                    @if(Session::has('error'))
                                    <div class=" mb-2 mt-1 login_error_message success_msg text-danger">
                                    	{{ Session::get('error') }}
                                    </div>
                                    @endif

                                </div>

                                <form action="{{url('admin/admin-login')}}" name="adminLoginFrm" id="adminLoginFrm" method="POST">
                                    @csrf

                                    <div class="form-group mb-3">
                                        <label for="emailaddress">Email address</label>
                                        
                                        <input class="form-control" type="email" name="email"  id="emailaddress"  placeholder="Enter your email" value="{{ old('email') }}">
                                        @error('email')
                                        <span class="error">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="password">Password</label>
                                        <input class="form-control" type="password" name="password"  id="password" placeholder="Enter your password">
                                        @error('password')
                                            <span class="error">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <?php /* ?>
                                    <div class="form-group mb-3">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="checkbox-signin" checked>
                                            <label class="custom-control-label" for="checkbox-signin">Remember me</label>
                                        </div>
                                    </div>
                                    <?php */ ?>
                                    
                                    
                                    <div class="form-group mb-0 text-center">
                                        <button class="btn btn-primary btn-block" type="submit"> Log In </button>
                                    </div>

                                    <div class="form-group text-center mt-3">
                                        <p> <a href="javascript:void(0);" onclick='forgotPasswordBox(this)' class="forgot-password ml-1">Forgot your password?</a></p>
                                    </div>
                                    

                                </form>

                                <?php /*
                                <div class="text-center">
                                    <h5 class="mt-3 text-muted">Sign in with</h5>
                                    <ul class="social-list list-inline mt-3 mb-0">
                                        <li class="list-inline-item">
                                            <a href="javascript: void(0);" class="social-list-item border-primary text-primary"><i class="mdi mdi-facebook"></i></a>
                                        </li>
                                        <li class="list-inline-item">
                                            <a href="javascript: void(0);" class="social-list-item border-danger text-danger"><i class="mdi mdi-google"></i></a>
                                        </li>
                                        <li class="list-inline-item">
                                            <a href="javascript: void(0);" class="social-list-item border-info text-info"><i class="mdi mdi-twitter"></i></a>
                                        </li>
                                        <li class="list-inline-item">
                                            <a href="javascript: void(0);" class="social-list-item border-secondary text-secondary"><i class="mdi mdi-github-circle"></i></a>
                                        </li>
                                    </ul>
                                </div>
                                <?php */ ?>

                            </div> <!-- end card-body -->
                        </div>
                        <!-- end card -->


                    </div> <!-- end col -->
                </div>
                <!-- end row -->
            </div>
        </div>    

            <div class="account-pages mt-5 mb-5 forgot-password-content" style="display: none;">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-8 col-lg-6 col-xl-5">
                            <div class="card bg-pattern">
                                <div class="card-body p-4">
                                    <div class="text-center w-75 m-auto">
                                        <h3>Forgot Password</h3>
                                        <p class="text-muted mb-4 mt-3">Enter your email address We will send you forgot password link.</p>
                                        <div class=" mb-2 mt-1 login_error_message text-danger">
                                            {{ (Session::has('error')) ? Session::has('error') : '' }}
                                        </div>
                                    </div>
    
                                    <form action="{{ route('admin.send-forgot-password-link') }}" method="post" id='sendForgotPasswordLinkFrm' name='sendForgotPasswordLinkFrm'>
                                        @csrf
                                        <div class="form-group mb-3">
                                            <label for="emailaddress">Email address</label>
                                            <input class="form-control" type="email" name='email' id="email" required placeholder="Enter your email" value="">
                                        </div>
    
                                        <div class="form-group mb-0 text-center row">
                                            <div class='col-md-6 col-sm-12'>
                                                <button class="btn btn-primary btn-block" type="submit" onclick='logonBox(this)'><i class="fa fa-arrow-left" aria-hidden="true"></i> Back To Login</button>
                                            </div>
                                             <div class='col-md-6 col-sm-12'>
                                                <button class="btn btn-primary btn-block" type="submit"><i class="fa fa-paper-plane" aria-hidden="true"></i>  Submit </button>
                                            </div>
                                        </div>
    
                                    </form>
    
                                </div> <!-- end card-body -->
                            </div>
    
                        </div> <!-- end col -->
                    </div>
                    <!-- end row -->
                </div>
                <!-- end container -->
            </div>
            <!-- end page -->


        <footer class="footer footer-alt">
            2024 &copy; <a href="" class="text-white-50">Laravel setup</a> 
        </footer>


        <script src="{{ url('public/assets/libs/jquery-validation/js/jquery.validate.min.js')}}"></script>
        <script src="{{ url('public/assets/libs/sweetalert/sweetalert2.all.min.js')}}"></script>

        <!-- App js -->
        <script src="{{ url('public/assets/admin/js/app.min.js')}}"></script>
        
        <script>
            $(document).ready(function(){

                /** success sweetalert message  */
                let success_sweetalert_msg = "{{ (!empty(Session::get('virifySuccessMsg'))) ? Session::get('virifySuccessMsg') : '' }}";
                console.log('msg = '+success_sweetalert_msg);
                if(success_sweetalert_msg && success_sweetalert_msg!=''){
                    Swal.fire({
                        type: "success",
                        title: success_sweetalert_msg,
                        text: " ",
                    });
                }

                /*******************************************
                    forgot password Validation
                    *******************************************/
                $("#adminLoginFrm").validate({
                    /* errorPlacement:function(error,element)
                    {
                        error.appendTo($(element).parent().parent());
                    }, */
                    rules:
                    {
                        "email" : {
                            required : true, 
                            email : true,
                        },
                        "password" : {required : true},
                    },
                    messages:
                    {
                        //"email" : {remote : "invalid Email address.",},
                    },
                });
                /*******************************************
                    forgot password Form Validation End
                *******************************************/

                /*******************************************
                    forgot password Validation
                *******************************************/
                    $("#sendForgotPasswordLinkFrm").validate({
                        rules:
                        {
                            "email" : {
                                required : true, 
                                email : true,
                                remote : {
                                    async:false,
                                    url: '{{ route('auth.check-user-email')  }}',
                                    type: "post",
                                    data: {
                                        _token: "{{ csrf_token() }}",
                                        email: function () {
                                            return $("#sendForgotPasswordLinkFrm input[name=email]").val();
                                        },
                                    }
                                },
                            },
                        },
                        messages:
                        {
                            "email" : {remote : "We cannot find an account with this email address. Please check that the email entered is correct or sign up below",},
                        },
                    });
                /*******************************************
                    forgot password Form Validation End
                *******************************************/
            });

            function forgotPasswordBox(obj)
            {
                $('.forgot-password-content').show(1000);
                $('.login-page-content').hide(1000);
                $("#sendForgotPasswordLinkFrm input[name=email]").val('');
                
            }

            function logonBox(obj)
            {
                $('.login-page-content').show(1000);
                $('.forgot-password-content').hide(1000);
            }
            
        </script>        
    </body>
</html>