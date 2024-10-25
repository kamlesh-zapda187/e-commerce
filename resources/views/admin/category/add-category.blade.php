
<style>
    @media (min-width: 992px){
        .modal-lg {
            max-width: 910px;
        }
    }
</style>


<form action="{{route('admin.add-category')}}" name="add_category_frm" id="add_category_frm" method="POST">
@csrf

<input type="hidden" name="category_id" value="{{ (!empty($category)) ? $category->id : '' }}" >

	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
	          <div class="form-group">
	          		<label>Category Name</label>
					<input type="text" name="name", id="name", class="form-control", placeholder="Name" value="{{ (!empty($category)) ? $category->name : '' }}">
	          </div>
        </div>
	</div>	
      

	
    <div class='row'>
    	<div class='col-md-12 col-sm-12 col-xs-12'>
    		<button class="btn btn-secondary float-right"><i class="fe-save"></i> {{ (!empty($category)) ? 'Update' : 'Add' }}</button>
    	</div>
    </div>
</form>



<script>
$(function(){
	/********************
	  user form validation
	********************/
	$('#add_category_frm').validate({
		rules:{
    		"name" : {
        				required : true,
						remote : {
	        					async:false,
	                            url: "{{ url('admin/category/checkexists') }}",
	                            type: "post",
								"headers": {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
	                            data: {
	                                name: function () {
	                                    return $("#add_category_frm input[name=name]").val();
	                                },
	                                "id" : function () {
	                                    return $("#add_category_frm input[name=category_id]").val();
	                                },

	                            }
        					},
					},
		},
		messages :{
			"name":{ remote : "Category name already exist."},
			
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