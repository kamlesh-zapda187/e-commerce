							
<?php 
$status = array("Inactive","Active"); 
$badgeClass = array("bg-soft-danger text-danger","bg-soft-success text-success");
?>



<div class="row">
<div class="col-12">
    <div class="page-title-box">
        <h4 class="page-title"><i class="fe-shopping-bag mr-1"></i>Products</h4>
    </div>
</div>
</div>

<div class="row">
<div class="col-12">
    <div class="card">
        <div class="card-body">
            <div class="row justify-content-end mb-3">
                <div class="col-lg-10 col-md-9 col-sm-8 col-xs-12">
                    <?php // form_open('',array('name'=>'search_frm','id'=>'search_frm','method'=>'get'))?>
                    <form action="" id="search_frm">
                        <div class="row justify-content-end">

                            <div class="col-xl-3 col-lg-4 col-md-5 col-sm-6 pr-lg-1 pr-md-1 pr-sm-1">
                                <?php // form_dropdown(array('name'=>'filter_role','id'=>'filter_role','class'=>'form-control','onchange'=>'searchRecord(this)'),["" => " -- User Role -- "]+$role_data,$this->input->get('filter_role'))?>
                                <select name="status" class="form-control" onchange="searchRecord(this)">
                                    <option value="">All</option>
                                    <option value="active" {{ (request()->get('status') && request()->get('status')=='active') ? 'selected' : ''}} >Actiive</option>
                                    <option value="inactive" {{ (request()->get('status') && request()->get('status')=='inactive') ? 'selected' : ''}}>Inactive</option>
                                </select>
                            </div>
                            
                            <div class="col-xl-3 col-lg-4 col-md-5 col-sm-6 px-lg-1 px-md-1 px-sm-1">
                                <div class="input-group">
                                    <input type="text" name="search_text" value="{{ (request()->get('search_text')) ? request()->get('search_text') : ''}}" id="search_text" class="form-control" placeholder="Search"  >
                                    <div class="input-group-append">
                                        <button class="btn btn-secondary waves-effect waves-light" type="button" onclick="searchRecord(this)"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-1 col-lg-1 col-md-2 col-sm-2 px-lg-1 px-md-1 px-sm-1">
                                <a href="{{ route('admin.products') }}"  class="btn btn-secondary waves-effect waves-light ml-0"><i class="mdi mdi-autorenew"></i></a>
                            </div>
                            
                            
                            
                        </div>
                    </form>
                 </div>
                 <div class="col-lg-2 col-md-3 col-sm-4 pl-lg-1 pl-md-1 pl-sm-1">
                    <a href="javascript:void(0)" onclick="ajaxModal('Add Product','{{ route('admin.add-product-modal') }}','modal-md')" class="btn btn-secondary waves-effect waves-light wd-100"><i class="fe-plus"></i> Add Product</a>
                </div>
             </div>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($products)>0)
                            @foreach ($products as $product)
                                <tr>
                                    <td class="table-td-image"><img class="rounded-circle" src="{{ ($product->product_image) ? asset('public/uploads/product/'.$product->product_image) : '' }}"></td>
                                    <td>{{ $product->product_title }}</td>
                                    <td>{{ $product->price }}</td>
                                    <td>{{ $product->category->name }}</td>
                                    <td>
                                        <span class="badge {{ $badgeClass[$product->is_active] }} cursor-pointer" onclick="changeStatus(this,'{{ route('admin.change-status') }}', 'products' ,'{{ $product->is_active }}', '{{ $product->id }}')"   data-tooltip="tooltip" title="Change Status">{{ $status[$product->is_active] }}</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="javascript:void(0)" onclick="ajaxModal('Update Product','{{ route('admin.add-product-modal') }}','modal-md',{{ $product->id }})" class="action-icon" data-tooltip="tooltip" data-container="body" title="Edit User" data-trigger="hover" data-placement="top"><i class="mdi mdi-square-edit-outline"></i></a>
                                        <a href="javascript:void(0)" onclick="remove_item(this,<?= $product->id ?>);" class="action-icon" data-tooltip="tooltip" data-container="body" title="Remove User" data-trigger="hover" data-placement="top"><i class="mdi mdi-delete"></i></a>
                                    </td>
                                    


                                </tr>									
                            @endforeach
                        @else
                            <tr>
                                <td colspan="8" align="center">No Products found</td>
                            </tr>										
                        @endif
                    
                    </tbody>
                </table>
            </div>
            @if(count($products)>0)
                <div class="float-right">
                    {{ $products->onEachSide(5)->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
</div>
<script>
$(document).ready(function(){

});

function searchRecord(obj){
$('#search_frm').submit();
}	

function remove_item (obj,id)
{
Swal.fire({
    title: 'Are you sure?',
    text: "You want to delete Product.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!"
    }).then((result) => {
    if (result.isConfirmed) {
        $.ajax({
            "headers": {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
            url: "{{ route('admin.delete-product') }}",
            type: "post",
            dataType: "json",
            data: {'id':id},
            success : function(result)
            {
                if(result.code == "1000" && result.code != '')
                {
                    $(obj).parent().parent().remove();
                    $.toast({
                        heading: "Success",
                        text: result.message,
                        position: "top-right",
                        loaderBg: "#5ba035",
                        icon: "success"
                    });
                    // setTimeout(function(){ location.reload(); }, 3000);
                }
                else
                {
                    $.toast({
                        heading: "Error",
                        text: result.message,
                        position: "top-right",
                        loaderBg: "#5ba035",
                        icon: "error"
                    });
                }
            }
        });

    }
});
}

</script>