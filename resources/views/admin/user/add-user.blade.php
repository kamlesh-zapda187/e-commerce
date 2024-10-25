
<style>
    @media (min-width: 992px){
        .modal-lg {
            max-width: 910px;
        }
    }
</style>


<form action="{{route('admin.add-user')}}" name="user_frm" id="user_frm" method="POST">
@csrf

<input type="hidden" name="user_id" value="{{ (!empty($user)) ? $user->id : '' }}" >

	<div class="row">
		<div class="col-md-6 col-sm-6 col-xs-12">
	          <div class="form-group">
	          		<label>First Name</label>
					<input type="text" name="first_name", id="first_name", class="form-control", placeholder="First Name" value="{{ (!empty($user)) ? $user->first_name : '' }}">
	          </div>
        </div>
       <div class="col-md-6 col-sm-6 col-xs-12">
       		<div class="form-group">
            	<label>Last Name</label>
				<input type="text" name="last_name", id="last_name", class="form-control", placeholder="Last Name" value="{{ (!empty($user)) ? $user->last_name : '' }}">
            </div>
       </div>
	   <!-- 
       <div class="col-md-4 col-sm-12 col-xs-12">
	          <div class="form-group">
	          		<label>User Role</label>
	            	<select name="" id=""></select>
	          </div>
        </div>
		-->

		
   </div>
    <div class="row">
    	<div class="col-md-6 col-sm-6 col-xs-12">
        	<div class="form-group">
            	<label>Email</label>
				<input type="email" name="email", id="email", class="form-control", placeholder="Email Address" value="{{ (!empty($user)) ? $user->email : '' }}">
            </div>
        </div>
       <div class="col-md-6 col-sm-6 col-xs-12">
      	  <div class="form-group">
        	<label>Contact</label>
			<input type="text" name="contact", id="contact", class="form-control", placeholder="contact" value="{{ (!empty($user)) ? $user->contact : '' }}">
         </div>
      </div>
    </div>

	<div class="row">
    	<div class="col-md-6 col-sm-6 col-xs-12">
        	<div class="form-group">
            	<label>Password</label>
				<input type="password" name="password", id="password", class="form-control", placeholder="Password" value="">
            </div>
        </div>
     	<div class="col-md-6 col-sm-6 col-xs-12 mt-1">
			<div class="form-group">
				<label></label>
            	<button type="button" class="btn btn-secondary wd-100 btn-block mt-29" onclick="generate_password(8)">Generate Password</button>
            </div>
        </div>
    </div>
    <div class='row'>
    	<div class='col-md-12 col-sm-12 col-xs-12'>
    		<button class="btn btn-secondary float-right"><i class="fe-save"></i> {{ (!empty($user)) ? 'Update' : 'Add' }}</button>
    	</div>
    </div>
</form>



<script>
$(function(){
	/********************
	  user form validation
	********************/
	$('#user_frm').validate({
		rules:{
			"first_name" : {required : true},
    		"last_name" : {required : true},
    		"role_id" : {required : true},
    		"email" : {
        				required : true,
        				email:true,
						remote : {
	        					async:false,
	                            url: "{{ url('admin/user/checkexists') }}",
	                            type: "post",
								"headers": {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
	                            data: {
	                                'name': 'email',
	                                email: function () {
	                                    return $("#user_frm input[name=email]").val();
	                                },
	                                "user_id" : function () {
	                                    return $("#user_frm input[name=user_id]").val();
	                                },

	                            }
        					},
					},
    		"contact" : {required : true, number : true,maxlength : 12, minlength : 10},
    		<?php if(empty($user)):?>
    		"password" : {required : true,},
    		<?php endif;?>
		},
		messages :{
			"email":{ remote : "Email already exist."},
			"contact" : {number : "Please enter valid contact.", maxlength : "Please enter valid contact.", minlength : "Please enter valid contact."}
		},
	});
	

});

/****************************
 * generate random password
 ***************************/
function generate_password(len) {
    var randomstring = Math.random().toString(36).slice(-len);
    $('input[name=password]').val(randomstring).removeClass('error');
    $('#password-error').hide();
}
</script>

<?php exit; ?>