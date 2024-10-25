<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <h4 class="page-title"><i class="mdi mdi-account-group mr-1"></i>Manage Role</h4>
        </div>
    </div>
</div>

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<div class="row mb-2">
					 <div class="col-sm-4 col-md-4 short-role">
                        <select name="by_role" class="selectpicker form-control" data-style="btn-white" data-live-search="true" data-live-Search-Placeholder="Search Role">
                            @foreach ($role_data as $role):
                                <option value="{{ $role->id }}">{{ $role->role_constant }}</option>
                            @endforeach;
                        </select>
                    </div>
					<div class="col-8">
						
					</div>
				</div>
				

                <form action="{{ route('admin.userRole.add-role-permission') }}" name="role_access_frm" id="role_access_frm" method="post">
                    @csrf

                    <input type="hidden" name="role_id", id="role_id"> 
                    <div class="table-responsive role-table">
                        <table class="table table-centered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Module Name</th>
                                    <th class='text-center'>View</th>
                                    <th class='text-center'>Insert</th>
                                    <th class='text-center'>Update</th>
                                    <th class='text-center'>Delete</th>
                                </tr>
                            </thead>

                            <tbody>
                                
                                {!! \App\Libraries\UserRoleModules::getDynamicModule() !!}
                                
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="col-4 float-right">
                        <button class="btn btn-secondary waves-effect waves-light float-right"><i class='fa fa-save'></i> Save Changes</button>
                    </div>
                </form>
				
				

				
			</div>
		</div>
	</div>
</div>
<script>
    //if view disable all other disable
    function disable_all(module_id) {
        can_view = $('#view_'+module_id).prop("checked");
        if(can_view == false){
            $('#insert_'+module_id).prop("checked",false);
            $('#update_'+module_id).prop("checked",false);
            $('#delete_'+module_id).prop("checked",false);
        }
    }

$(document).ready(function(){
	
	
	/*************************************
			Get Module By Role
	*************************************/
	$("select[name='by_role']").on("change",function(){
		$("#role_id").val($(this).val());

		$.ajax({
			url : "{{ url('admin/userRole/getModuleAccessByRole') }}",
			type : "get",
			dataType : "json",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')},
			data : {"role_id":$(this).val()},
			success : function(result)
			{
				if(result.status == 1 && result.code != '')
				{
					$.each(result.data,function(index,values){
						if(values.can_view == 1)
						{
							$("#view_"+values.module_id).prop("checked",true);
						}
						else
						{
							$("#view_"+values.module_id).prop("checked",false);
						}

						if(values.can_insert == 1)
						{
							$("#insert_"+values.module_id).prop("checked",true);
						}
						else
						{
							$("#insert_"+values.module_id).prop("checked",false);
						}

						if(values.can_update == 1)
						{
							$("#update_"+values.module_id).prop("checked",true);
						}
						else
						{
							$("#update_"+values.module_id).prop("checked",false);
						}

						if(values.can_delete == 1)
						{
							$("#delete_"+values.module_id).prop("checked",true);
						}
						else
						{
							$("#delete_"+values.module_id).prop("checked",false);
						}
					});
				}
				else
				{
					$("#role_access_frm input[type='checkbox']").prop("checked",false);
				}
			}
		});
	});

	$("select[name='by_role']").trigger("change");
	/*************************************
			Get Module By Role End
	*************************************/


	$("#btn-update_role_access").removeAttr("disabled");
});
</script>
