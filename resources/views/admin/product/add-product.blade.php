
<style>
    @media (min-width: 992px){
        .modal-lg {
            max-width: 910px;
        }
    }
</style>

{!! Form::open(['url'=>route('admin.add-product'),'method'=>'post','enctype'=>'multipart/form-data']) !!}

	@csrf
	{!! Form::hidden('product_id', (($product) ? $product->id : "")  ) !!}



	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="form-group">
					<label>Product Name</label>
					{!! Form::text('product_title', (($product) ? $product->product_title : ""), ['class'=>'form-control']) !!}
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="form-group">
					<label>Product Category</label>
					{!! Form::select('category_id', ['' => 'Select Category']+$categories, (($product) ? $product->category_id : ""), ['class'=>'form-control']) !!}
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="form-group">
					<label>Price</label>
					{!! Form::number('price', (($product) ? $product->price : ""), ['class'=>'form-control']) !!}
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="form-group">
				<label>Product Image</label>
				<input type="file" name="product_image"  id="product_image" class="dropify" accept=".jpg,.jpeg,.png,.webp" data-max-file-size="1M" data-allowed-file-extensions="jpg jpeg png webp" data-default-file = "{{ (!empty($product->product_image)) ? asset('public/uploads/product/'.$product->product_image) : '' }}" data-height="100"> 
				<input type="hidden" id="old_product_image" name="old_product_image" value="" >
			</div>
		</div>
	</div>

	<div class='row'>
		<div class='col-md-12 col-sm-12 col-xs-12'>
			<button class="btn btn-secondary float-right"><i class="fe-save"></i> {{ (!empty($category)) ? 'Update' : 'Add' }}</button>
		</div>
	</div>
{!! Form::close() !!}


<script>
$(function(){
	$('.dropify').dropify();
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