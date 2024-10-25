

  

 <script src="{{ url('public/assets/libs/jquery-validation/js/jquery.validate.min.js')}}"></script>
 <script src="{{url('public/assets/libs/dropify/dropify.min.js')}}"></script>
 <script src="{{ url('public/assets/libs/sweetalert/sweetalert2.all.min.js')}}"></script>
 <script src="{{ url('public/assets/libs/jquery-toast/jquery.toast.min.js')}}"></script>

 <script src="{{url('public/assets/admin/js/custom.js')}}"></script>
 
  <!-- App js-->
  <script src="{{url('public/assets/admin/js/app.min.js')}}"></script>

 <script>
    /** success alert message toast  */
    let success_alert_msg = "{{ (!empty(Session::get('success_msg'))) ? Session::get('success_msg') : '' }}";
    if(success_alert_msg && success_alert_msg!=''){
        $.toast({heading: "Success",text: success_alert_msg,position: "top-right",loaderBg: "#5ba035",icon: "success"});
    }

    /** error alert message toast  */
    let error_alert_msg = "{{ (!empty(Session::get('error_msg'))) ? Session::get('error_msg') : '' }}";
    if(error_alert_msg && error_alert_msg!=''){
        $.toast({heading: "Error",text: error_alert_msg,position: "top-right",loaderBg: "#5ba035",icon: "error"});
    }

 </script>