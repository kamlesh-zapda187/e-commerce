<?php
//dd($page_content);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        @include('admin.layouts.style')
    </head>    
    <body> 

        <!-- Begin page -->
            <div id="wrapper">
                
                <!-- Topbar Start -->
                    @include('admin.layouts.topbar')
                <!-- Topbar End -->

                <!-- Left sidebar Start -->
                @include('admin.layouts.left-sidebar')
                <!-- Left sidebar End -->

                <!-- main content start -->
                <div class="content-page">
                    <div class="content">
                        <div class="container-fluid">
                            {{-- @yield('main-container') --}}
                            <?php echo $page_content;?>
                        </div>
                    </div>

                     <!-- footer Start -->
                         @include('admin.layouts.footer')
                    <!-- footer End -->

                </div>    
                <!-- main content End -->

            </div>
        @include('admin.layouts.script')
    </body>
</html>    