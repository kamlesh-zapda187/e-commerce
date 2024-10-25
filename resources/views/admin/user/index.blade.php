							
<?php 
	$status = array("Inactive","Active"); 
	$badgeClass = array("bg-soft-danger text-danger","bg-soft-success text-success");
?>



<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title"><i class="fe-user mr-1"></i>Users</h4>
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
									
	        					</div>
	        					<div class="col-xl-3 col-lg-4 col-md-5 col-sm-6 pr-lg-1 pr-md-1 pr-sm-1">
	        						<?php // form_dropdown(array('name'=>'filter_status','id'=>'filter_status','onchange'=>'searchRecord(this)','class'=>'form-control'),array(''=>'All','A'=>'Active','IN'=>'Inactive'),$this->input->get('filter_status'))  ?>
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
	                            	<a href="{{ url('admin/users') }}"  class="btn btn-secondary waves-effect waves-light ml-0"><i class="mdi mdi-autorenew"></i></a>
	        					</div>
	        					
	        					<?php /* if ($this->userInfo->role_id == $this->adminRoleId) : ?>
									<div class="col-xl-2 col-lg-3 col-md-4  col-sm-4 pl-lg-1 pl-md-1 pl-sm-1 mb-1">
										<?php $filter = "role_id=".$this->input->get('filter_role').'&status='.$this->input->get('filter_status')."&search_txt=".$this->input->get('filter_status');?>
										<a style="padding-left: 7px;" href="<?= site_url('auth/users/exportUsers?'.$filter) ?>"  class="btn btn-secondary waves-effect waves-light wd-100" title="Export users" data-tooltip="tooltip" data-placement="top"><i class="fe-plus"></i> Export users</a>
									</div>
				  				<?php endif; */ ?>
	        					
							</div>
        				</form>
        			 </div>
					 @if( Auth::guard('admin')->user()->hasPermission('admin.users','can_insert') )
					 <div class="col-lg-2 col-md-3 col-sm-4 pl-lg-1 pl-md-1 pl-sm-1">
						<a href="javascript:void(0)" onclick="ajaxModal('Add User','{{ route('admin.add-user-modal') }}','modal-lg')" class="btn btn-secondary waves-effect waves-light wd-100"><i class="fe-plus"></i> Add User</a>
					</div>
					@endif
			     </div>

				<div class="table-responsive">
					<table class="table table-striped">
						<thead>
							<tr>
								<th>Image</th>
								<th>User Role</th>
								<th>Full Name</th>
								<th>Email</th>
								<th>Mobile</th>
								<th>Joining Date</th>
								<th>Status</th>
								<th class="text-center">Action</th>
							</tr>
						</thead>
						<tbody>
							@if(count($users)>0)
								@foreach ($users as $user)
									<tr>
										<td class="table-user"><img class="rounded-circle" src="{{ url('public/assets/images/default.png') }}"></td>
										<td>{{ $user->role_constant }}</td>
										<td>{{ $user->full_name }}</td>
										<td>{{ $user->email }}</td>
										<td>{{ $user->contact }}</td>
										<td>{{ $user->created_at }}</td>
										<td>
											@if($user->is_verify)
												<span class="badge {{ $badgeClass[$user->is_active] }} cursor-pointer" onclick="changeStatus(this,'{{ route('admin.change-status') }}', 'users' ,'{{ $user->is_active }}', '{{ $user->id }}')"   data-tooltip="tooltip" title="Change Status">{{ $status[$user->is_active] }}</span>
											@else
												<span class="badge bg-soft-danger text-danger cursor-pointer"  data-tooltip="tooltip" title="Change Status">No Verify</span>
											@endif
										</td>
										<td class="text-center">
											@if( Auth::guard('admin')->user()->hasPermission('admin.users','can_update') )
											<a href="javascript:void(0)" onclick="ajaxModal('Update User','{{ route('admin.add-user-modal') }}','modal-lg',{{ $user->id }})" class="action-icon" data-tooltip="tooltip" data-container="body" title="Edit User" data-trigger="hover" data-placement="top"><i class="mdi mdi-square-edit-outline"></i></a>
											@endif

											<a href="javascript:void(0)" onclick="ajaxModal('View User | <?= ucfirst($user->first_name).' '. ucfirst($user->last_name)?>','auth/users/viewUserDetail/{{ $user->id }}','modal-lg')" class="action-icon" data-tooltip="tooltip" data-container="body" title="View User" data-trigger="hover" data-placement="top"><i class="mdi mdi-eye"></i></a>

											@if( Auth::guard('admin')->user()->hasPermission('admin.users','can_delete') )
												<a href="javascript:void(0)" onclick="deleteUser(this,<?= $user->id ?>);" class="action-icon" data-tooltip="tooltip" data-container="body" title="Remove User" data-trigger="hover" data-placement="top"><i class="mdi mdi-delete"></i></a>
											@endif
											
										</td>
										


									</tr>									
								@endforeach
							@else
								<tr>
									<td colspan="8" align="center">No user found</td>
								</tr>										
							@endif
						
						</tbody>
					</table>
				</div>
				@if(count($users)>0)
					<div class="float-right">
						{{$users->onEachSide(5)->links()}}
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
	
function deleteUser(obj,user_id)
{
	Swal.fire({
		title: 'Are you sure?',
		text: "You want to delete User.",
		icon: "warning",
		showCancelButton: true,
		confirmButtonColor: "#3085d6",
		cancelButtonColor: "#d33",
		confirmButtonText: "Yes, delete it!"
		}).then((result) => {
		if (result.isConfirmed) {
			$.ajax({
				"headers": {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
				url: "{{ route('admin.delete-user') }}",
		        type: "post",
		        dataType: "json",
		        data: {'user_id':user_id},
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