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
                    <form class="form" method="post"  id="payment-form" class="require-validation lgy__billing_form" data-publish-key="{{$stripe_publish_key}}" data-cc-on-file="false" data-stripe-publishable-key="{{$stripe_publish_key}}">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <label>First Name<span>*</span></label>
                                    <input type="text" name="name" placeholder="" required="required">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <label>Last Name<span>*</span></label>
                                    <input type="text" name="name" placeholder="" required="required">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <label>Email Address<span>*</span></label>
                                    <input type="email" name="email" placeholder="" required="required">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <label>Phone Number<span>*</span></label>
                                    <input type="number" name="number" placeholder="" required="required">
                                </div>
                            </div>
                            
                           
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <label>Address Line 1<span>*</span></label>
                                    <input type="text" name="address" placeholder="" required="required">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <label>Address Line 2<span>*</span></label>
                                    <input type="text" name="address" placeholder="" required="required">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12">
                                <div class="form-group">
                                    <label>Postal Code<span>*</span></label>
                                    <input type="text" name="post" placeholder="" required="required">
                                </div>
                            </div>
                           
                            <div class="col-12">
                                <div class="form-group create-account">
                                    <input id="cbox" type="checkbox">
                                    <label>Create an account?</label>
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

                    <div class="single-widget">
                        <h2>Checkout Summery</h2>
                        <div class="content">
                            <ul>
                                <?php $subTotal = 0; ?>
                                @foreach ( $product_cart_list as $product )
                                    <li>{{ $product->product_title }} x (<small>{{ $product->qty }}</small>)<span>{{ number_format($product->qty*$product->price,'2','.',',') }}</span></li>
                                    <?php $subTotal += $product->qty*$product->price ?>
                                @endforeach
                                
                            </ul>
                        </div>
                    </div>

                    <div class="single-widget">
                        <h2>CART  TOTALS</h2>
                        <div class="content">

                            
                            <ul>
                                <li>Sub Total<span>{{ number_format($subTotal,'2','.',',') }}</span></li>
                                <li class="last">Total<span>{{ number_format($subTotal,'2','.',',') }}</span></li>
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

                    <input type="text" id="card-number" placeholder="Card Number" class="form-control" required>
                    <input type="text" id="card-expiry" placeholder="MM/YY" class="form-control" required>
                    <input type="text" id="card-cvc" placeholder="CVC" required class="form-control">
                    <input type="number" id="amount" placeholder="Amount in USD" required class="form-control">
                    <button type="submit">Pay</button>
                    <div id="error-message"></div>

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
                                <a href="#" class="btn">proceed to checkout</a>
                            </div>
                        </div>
                    </div>
                    <!--/ End Button Widget -->
                </div>
            </div>
        </div>
    </div>
</section>
<!--/ End Checkout -->

<script src="https://js.stripe.com/v3/"></script>
<script>
    $(document).ready(function() {
        const stripe = "{{$stripe_publish_key}}";
    
        $('#payment-form').on('submit', function(event) {
            event.preventDefault();
    
            const amount = $('#amount').val() * 100; // Convert to cents
    
            $.ajax({
                url: '/api/create-payment-intent',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ amount: amount }),
                success: function(data) {
                    const clientSecret = data.clientSecret;
    
                    stripe.confirmCardPayment(clientSecret, {
                        payment_method: {
                            card: {
                                number: $('#card-number').val(),
                                exp_month: $('#card-expiry').val().split('/')[0],
                                exp_year: $('#card-expiry').val().split('/')[1],
                                cvc: $('#card-cvc').val(),
                            },
                        },
                    }).then(function(result) {
                        if (result.error) {
                            $('#error-message').text(result.error.message);
                        } else {
                            if (result.paymentIntent.status === 'succeeded') {
                                alert('Payment successful!');
                            }
                        }
                    });
                },
                error: function() {
                    $('#error-message').text('Payment failed. Please try again.');
                }
            });
        });
    });
    </script>