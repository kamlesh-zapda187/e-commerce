


<!-- Breadcrumbs -->
<div class="breadcrumbs">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="bread-inner">
                    <ul class="bread-list">
                        <li><a href="{{ url('') }}">Home<i class="ti-arrow-right"></i></a></li>
                        <li class="active"><a href="#">Success Booking</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Breadcrumbs -->
        
<!-- Start Checkout -->
<section class="shop checkout section">
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="text-center order_complete">
                	<i class="fa fa-check-circle" style="font-size: 60px;"></i>
                    <div class="heading_s1">
                  	<h3>Your order request has been submitted</h3>
                    </div>
                  	<p>Thank you for your order! It is being processed and we will contact you soon. You will receive an email confirmation when your order has been confirmed.</p>
                  	
                  	<div class="row mt-3 justify-content-center">
                  		@if(Auth::check())
	                    	@if(!empty($orderInfo))
		                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                            <a href="" class="btn blue_btn marg_top_10">Create an account</a>
		                        </div>
                          @endif
	                    @endif
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <a href="{{url('')}}" class="btn  btn-fill-out  marg_top_10" style="color: #fff;">Continue Browsing</a>
                        </div>
                    </div>
                  	
                    
                </div>
            </div>
        </div>

    </div>
        
</section>
<!--/ End Checkout -->

 <!-- END SECTION SHOP -->
 

