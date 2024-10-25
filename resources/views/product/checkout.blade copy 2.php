<?php
    $stripe_publish_key = env('STRIPE_PUBLISH_KEY');
 ?>
<!-- Breadcrumbs -->
<div class="breadcrumbs">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="bread-inner">
                    <ul class="bread-list">
                        <li><a href="index1.html">Home<i class="ti-arrow-right"></i></a></li>
                        <li class="active"><a href="blog-single.html">Checkout</a></li>
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
        <div class="row"> 
            <div class="col-lg-8 col-12">
                <div class="checkout-form">
                    <h2>Make Your Checkout Here</h2>
                    <p>Please register in order to checkout more quickly</p>
                    <!-- Form -->
                    
                    <form class="form" role="form" method="post"
                            class="require-validation lgy__billing_form" data-cc-on-file="false"
                            data-stripe-publishable-key="{{$stripe_publish_key}}" id="payment-form">

                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <label>First Name<span>*</span></label>
                                    <input type="text" name="name" placeholder="" required="required" value="Kamlesh">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <label>Last Name<span>*</span></label>
                                    <input type="text" name="name" placeholder="" required="required" value="ZApda">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <label>Email Address<span>*</span></label>
                                    <input type="email" name="email" placeholder="" required="required" value="kamlesh@gmail.com">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <label>Phone Number<span>*</span></label>
                                    <input type="number" name="number" placeholder="" required="required" value="7894561230">
                                </div>
                            </div>
                            
                           
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <label>Address Line 1<span>*</span></label>
                                    <input type="text" name="address" placeholder="" required="required" value="rajkot">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <label>Address Line 2<span>*</span></label>
                                    <input type="text" name="address" placeholder="" required="required" value="bhanvad">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <label>Postal Code<span>*</span></label>
                                    <input type="text" name="post" placeholder="" required="required" value="360501">
                                </div>
                            </div>
                           
                        </div>
                    </form>
                    <!--/ End Form -->
                </div>
            </div>
            <div class="col-lg-4 col-12">
                    <div class="order-details">
                        <!-- Order Widget -->
                        <div class="order_overview order_review">
                            <form class="order_review" id="review_order_items">
                                <div class="single-widget">
                                    <h2>Checkout Summery</h2>
                                    <div class="content">
                                        <ul>
                                            <?php $subTotal = 0; ?>
                                            @foreach ( $product_cart_list as $product )
                                                <li>{{ $product->product_title }} x (<small>{{ $product->qty }}</small>)<span>{{ number_format($product->qty*$product->price,'2','.',',') }}</span></li>
                                                <?php $subTotal += $product->qty*$product->price ?>
    
                                                <input type="hidden" name="products[]" class="product_id" value="<?= $product->id . "," . $product->qty . ":" . $product->price  ?>">
    
                                            @endforeach
                                            
                                        </ul>
                                    </div>
                                </div>
    
                                <div class="single-widget">
                                    <h2>CART  TOTALS</h2>
                                    <div class="content">
    
                                        
                                        <ul>
                                            <li>
                                                    Sub Total<span>{{ number_format($subTotal,'2','.',',') }}</span>
                                                    <input type="hidden" name="sub_total" class="sub_total" value="<?= number_format((float) $subTotal, 2, ".", "") ?>">
                                            </li>
                                            <li class="last">
                                                Total<span>{{ number_format($subTotal,'2','.',',') }}</span>
                                                <input type="hidden" name="total_amount" class="total_amount" value="<?= number_format((float) $subTotal, 2, ".", "") ?>">
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <!--/ End Order Widget -->
                                <!-- Order Widget -->
                                <div class="single-widget">
                                    <h2>Payments</h2>
                                    <div class="content">
                                        <div class="checkbox">
                                            <label class="checkbox-inline" for="1"><input name="updates" id="1" type="checkbox"> Check Payments</label>
                                            <label class="checkbox-inline" for="2"><input name="news" id="2" type="checkbox"> Cash On Delivery</label>
                                            <label class="checkbox-inline" for="3"><input name="news" id="3" type="checkbox"> PayPal</label>
                                        </div>
                                    </div>
                                </div>
                                <!--/ End Order Widget -->
                                <!-- Payment Method Widget -->
    
                            
    
                                <?php /* ?>
                                <div class="single-widget payement">
                                    <div class="content">
                                        <img src="images/payment-method.png" alt="#">
                                    </div>
                                </div>
                                <?php */ ?>
                                <!--/ End Payment Method Widget -->
                                <!-- Button Widget -->
                                <div class="single-widget get-button">
                                    <div class="content">
                                        <div class="button">
                                            <button type="submit" class="btn">proceed to checkout</button>
    
                                            <button
                                                class="btn btn-fill-out btn-block place-order-btn pay-out-btn stripe_payment_btn trigger_stripe_order"
                                                style="cursor: pointer;" type="submit"> <!-- blue_btn width_100 -->
                                                <span class="btn-title">Place Order to Continue</span>
                                            </button>
    
                                        </div>
                                    </div>
                                </div>
                                

                        
                        <div id="lgy__sp_payment_form">
                        </div>

                    </form>
                </div>    

                        <!--/ End Button Widget -->
                    </div>
                   
                </div>>        
            </div>
        </div>
    </div>
</section>
<!--/ End Checkout -->

 <!-- END SECTION SHOP -->
 <script type="text/javascript" src="https://js.stripe.com/v3/"></script>
 
 <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
 <script src="{{asset('public/assets/js/app.apiservers.payment.js')}}"></script>


