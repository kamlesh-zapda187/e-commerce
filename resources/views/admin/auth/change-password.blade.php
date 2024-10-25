<form action="{{ route('admin.change-password') }}" name="changePasswordFrm" id="changePasswordFrm" class="changePasswordFrm" method="post">
	@csrf
    <div class='row'>
        <div class="form-group col-md-12">
        <div class="input-group">
            <input type="password" name="old_password" id="old_password" class="form-control" placeholder="Old Password">
            <div class="input-group-prepend">
                <span class="input-group-text oldShowPassword" id="view-password" style="cursor: pointer;"><i class="fa fa-eye-slash" aria-hidden="true"></i></span>
            </div>
        </div>
        </div>
    </div>
    <div class='row'>	
        <div class="form-group col-md-12">
        <div class="input-group">
            
            <input type="password" name="new_password" id="new_password" class="form-control" placeholder="New Password">
            <div class="input-group-prepend">
                <span class="input-group-text showPassword" id="view-password" style="cursor: pointer;"><i class="fa fa-eye-slash" aria-hidden="true"></i></span>
            </div>
			<span class="small-tips">Make sure add different password new password can't be equal to the previous password.</span>
        </div>
        </div>
    </div>
    <div class='row'>
        <div class="form-group col-md-12">
        <div class="input-group">
            <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm Password">  
            <div class="input-group-prepend">
                <span class="input-group-text showConfirmPassword" id="view-confirm-password" style="cursor: pointer;"><i class="fa fa-eye-slash" aria-hidden="true"></i></span>
            </div>
        </div>
        </div>
    </div>

    <div class='row'>
        <div class="col-md-12 col-sm-12 col-xs-12">
            <button type="submit" class="tg-btn btn btn-secondary float-right" onclick='changePassword()'>Submit</button>
        </div>
    </div>

</form>

<script>

$(function(){

	/*******************************************
	Form Validation
	*******************************************/
	$("#changePasswordFrm").validate({
		errorPlacement: function(error, element) {
            error.appendTo( element.parent().parent());
        },
		rules :
		{
			"old_password" : {required : true,},
			"old_password" : {
				required : true,
				remote : {
					async:false,
					headers:{'X-CSRF-TOKEN' : $('meta[name="csrf_token"]').attr('content')},
                    url: '{{ route('admin.check-user-password') }}',
                    type: "post",
                    data: {
                        old_password: function () {
                            return $("#changePasswordFrm #old_password").val();
                        },
                    }
				},
			},
			"new_password" : {required : true,},
			"confirm_password" : {required : true,equalTo: "#new_password"}
		},
		messages :
		{
			"old_password" : {required : "Please enter old password",remote : "Old password incorrect."},
			"new_password" : {required : "Please enter new password"},
			"confirm_password" : {required : "Please enter confirm password",equalTo:'Confirm password not match'},

		},
	});
/*******************************************
	Form Validation End
*******************************************/

});


$(".oldShowPassword").click(function(){
	var attr = $("#old_password").attr('show');
	if (typeof attr !== typeof undefined && attr !== false){
		$("#old_password").removeAttr('show');
		$("#old_password").attr('type','password');
		$(this).html('<i class="fa fa-eye-slash" aria-hidden="true"></i>');
	}
	else
	{
		$("#old_password").attr('show',true);
		$("#old_password").attr('type','text');
		$(this).html('<i class="fa fa-eye" aria-hidden="true"></i>');
	}
	
});

$(".showPassword").click(function(){
	var attr = $("#new_password").attr('show');
	if (typeof attr !== typeof undefined && attr !== false){
		$("#new_password").removeAttr('show');
		$("#new_password").attr('type','password');
		$(this).html('<i class="fa fa-eye-slash" aria-hidden="true"></i>');
	}
	else
	{
		$("#new_password").attr('show',true);
		$("#new_password").attr('type','text');
		$(this).html('<i class="fa fa-eye" aria-hidden="true"></i>');
	}
	
});

$(".showConfirmPassword").click(function(){
	var attr = $("#confirm_password").attr('show');
	if (typeof attr !== typeof undefined && attr !== false){
		$("#confirm_password").removeAttr('show');
		$("#confirm_password").attr('type','password');
		$(this).html('<i class="fa fa-eye-slash" aria-hidden="true"></i>');
	}
	else
	{
		$("#confirm_password").attr('show',true);
		$("#confirm_password").attr('type','text');
		$(this).html('<i class="fa fa-eye" aria-hidden="true"></i>');
	}
	
});

/*function changePassword()
{
	var old_password = $('#old_password').val();
	var new_password = $('#new_password').val();

	  $.ajax({
	        url: SITE_URL + 'admin/dashboard/changePassword',
	        type: "post",
	        dataType: "json",
	        data: {'new_password':new_password,'old_password':old_password},
	        success: function (response){
	    		var option = "<option value=''>--Select State--</option>";
	        	if(response!='')
	        	{
	            	$.each(response,function(index,value){
	            		option += "<option value='"+index+"'>"+value+"</option>";
	            	});
	            	$('#state').html('');
	            	$('#state').html(option);
	        	}else{
	          		$('#state').html('');
	          		$('#state').html(option);
	        	}
	        },
	    });
}*/
</script>
<?php exit;?>