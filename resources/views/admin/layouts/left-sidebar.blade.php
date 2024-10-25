<div class="left-side-menu">

    <div class="slimscroll-menu">

        <!--- Sidemenu -->
        <div id="sidebar-menu">

            <ul class="metismenu" id="side-menu">

               <li class="menu-title"> </li>

               <li class=""><a href="{{ url('admin/dashboard') }}" class=""><i class="fas fa-tachometer-alt" aria-hidden="true"></i> <span> Dashboard </span></a></li>

               @if( Auth::guard('admin')->user()->hasPermission('admin.users','can_view') )
                    <li class=""><a href="{{ route('admin.users') }}" class=""><i class="fas fa-user-alt" aria-hidden="true"></i> <span> Users</span></a></li>
               @endif

               @if( Auth::guard('admin')->user()->hasPermission('admin.user-roles','can_view') )
                    <li class=""><a href="{{ route('admin.user-roles') }}" class=""><i class="mdi mdi-account-group" aria-hidden="true"></i> <span> User Roles</span></a></li>
               @endif

               @if( Auth::guard('admin')->user()->hasPermission('admin.products','can_view') )
                    <li class=""><a href="{{ route('admin.products') }}" class=""><i class="fe-shopping-bag" aria-hidden="true"></i> <span> Products</span></a></li>
               @endif

               @if( Auth::guard('admin')->user()->hasPermission('admin.category','can_view') )
               <li class=""><a href="{{ route('admin.category') }}" class=""><i class="fas fa-list" aria-hidden="true"></i> <span> Categorys</span></a></li>
               @endif

            </ul>

        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>