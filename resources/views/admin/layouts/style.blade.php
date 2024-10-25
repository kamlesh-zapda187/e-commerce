    <meta charset="utf-8" />
    <title> Admin | {{ $page_title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf_token" content="{{ csrf_token() }}" />
    <meta name="description" content=""> 
    <meta name="keywords" content="">
    <meta property="og:title" content=""/>
    <meta property="og:url" content="{{ url('') }}"/>
    <meta name="" property="og:image" content="">
    <meta property="og:type" content="website" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{url('public/assets/admin/images/favicon.ico')}}">

    <link href="{{url('public/assets/libs/dropify/dropify.min.css')}}" rel="stylesheet" type="text/css" />
            
    <!-- App css -->
    <link href="{{url('public/assets/admin/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    
    <link href="{{url('public/assets/libs/jquery-toast/jquery.toast.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{url('public/assets/libs/sweetalert/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />
    
    <link href="{{url('public/assets/admin/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{url('public/assets/admin/css/app.min.css')}}" rel="stylesheet" type="text/css" />

    <link href="{{ url('public/assets/admin/css/admin-custome.css') }}" rel="stylesheet" type="text/css" />

    <!-- Vendor js -->
  <script src="{{url('public/assets/admin/js/vendor.min.js')}}"></script>