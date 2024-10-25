
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title"><i class="fe-user mr-1"></i>My Account</h4>
        </div>
    </div>
</div>
<div class="card-box">
    <form action="{{ route('admin.update-user-profile') }}" enctype="multipart/form-data"  method="post" name="user_frofile_frm" id="user_frofile_frm" class="">    
        @csrf
        <input type="hidden" name="user_id" value="{{ (!empty($user)) ? $user->id : '' }}">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                        <label>First Name</label>
                        <input type="text" name="first_name" id="first_name" class="form-control"  placeholder="First Name" value="{{ (!empty($user)) ? $user->first_name : '' }}">
                        
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" name="last_name" id="last_name" class="form-control"  placeholder="Last Name" value="{{ (!empty($user)) ? $user->last_name : '' }}">
                    </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" id="email" class="form-control"  placeholder="Email Address" value="{{ (!empty($user)) ? $user->email : '' }}">
                    <div><span class="small-tips">Email use for login so it's affected your login access if your update email</span></div>
                </div>
            </div>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="form-group">
                <label>Contact</label>
                <input type="number" name="contact" id="contact" class="form-control"  placeholder="contact" value="{{ (!empty($user)) ? $user->contact : '' }}">
            </div>
        </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                    <label>Profile Image</label>
                    <input type="file" name="profile_image"  id="profile_image" class="dropify" accept=".jpg,.jpeg,.png,.webp" data-max-file-size="1M" data-allowed-file-extensions="jpg jpeg png webp" data-default-file = "{{ (!empty($user->user_avatar)) ? asset('public/uploads/user_profile/'.$user->user_avatar) : '' }}" data-height="100"> 
					<input type="hidden" id="old_profile_image" name="old_profile_image" value="{{ (!empty($user)) ?  $user->user_avatar: '' }}" >
                </div>
            </div>
        </div>

        <div class='row'>
            <div class='col-md-12 col-sm-12 col-xs-12'>
                <button type="submit" class="btn btn-secondary float-right"><i class="fe-save"></i> Update</button>
            </div>
        </div>
    </form>
</div>
<script>
$(function(){
    $('.dropify').dropify();
	/********************
	  user form validation
	********************/
	$('#user_frofile_frm').validate({
		rules:{
			"first_name" : {required : true},
    		"last_name" : {required : true},
            "email" : {
                required : true,
                remote : {
                    async:false,
                    url: "{{ url('admin/user/checkexists') }}",
                    type: "post",
                    "headers": {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
                    data: {
                        'name': 'email',
                        email: function () {
                            return $("#user_frofile_frm input[name=email]").val();
                        },
                        "user_id" : function () {
                            return $("#user_frofile_frm input[name=user_id]").val();
                        },
                    }
                },

            },
    		
    		"contact" : {required : true, number : true,maxlength : 12, minlength : 10},
		},
		messages :{
			"email":{ remote : "Email already exist."},
			"contact" : {number : "Please enter valid contact.", maxlength : "Please enter valid contact.", minlength : "Please enter valid contact."}
		},
	});
});


</script>

