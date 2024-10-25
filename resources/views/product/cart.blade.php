<!-- Breadcrumbs -->
<div class="breadcrumbs">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="bread-inner">
                    <ul class="bread-list">
                        <li><a href="index1.html">Home<i class="ti-arrow-right"></i></a></li>
                        <li class="active"><a href="blog-single.html">Cart</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Breadcrumbs -->
        
<!-- Shopping Cart -->

@if(empty($product_cart_list))
    <div class="shopping-cart section">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center"><h5>No items added to the cart</h5></div>
                <div class="col-12 text-center"><a class="fs-18 font-18 mt-4" href="{{ route('products') }}"><u>Browse products</u> </a></div>
            </div>
        </div>            
    </div>    
@else
    <div class="shopping-cart section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <!-- Shopping Summery -->
                    <table class="table shopping-summery">
                        <thead>
                            <tr class="main-hading">
                                <th>PRODUCT</th>
                                <th>NAME</th>
                                <th class="text-center">UNIT PRICE</th>
                                <th class="text-center">QUANTITY</th>
                                <th class="text-center">TOTAL</th> 
                                <th class="text-center"><i class="ti-trash remove-icon"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $subTotal = 0; ?>
                            @if(!empty($product_cart_list))

                            @foreach ($product_cart_list as $cart_key=>$product)

                                    <tr>
                                        <td class="image" data-title="No"><img src="{{ ($product->product_image) ? asset('public/uploads/product/'.$product->product_image) : '' }}" alt="#"></td>
                                        <td class="product-des" data-title="Description">
                                            <p class="product-name"><a href="#">{{ $product->product_title }}</a></p>
                                            <!-- <p class="product-des">Maboriosam in a tonto nesciung eget  distingy magndapibus.</p> -->
                                        </td>
                                        <td class="price" data-title="Price">&dollar;<span>{{ $product->price }}</span></td>
                                        <td class="qty" data-title="Qty"><!-- Input Order -->
                                            <div class="input-group">
                                                <div class="button minus">
                                                    <button type="button" class="btn btn-primary btn-number" disabled="disabled" data-type="minus" data-field="quant[1]">
                                                        <i class="ti-minus"></i>
                                                    </button>
                                                </div>
                                                <input type="text" name="quant[1]" class="input-number"  data-min="1" data-max="100" value="{{ $product->qty }}">
                                                <div class="button plus">
                                                    <button type="button" class="btn btn-primary btn-number" data-type="plus" data-field="quant[1]">
                                                        <i class="ti-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <!--/ End Input Order -->
                                        </td>
                                        <td class="total-amount" data-title="Total">
                                            <?php
                                                $total = $product->price * $product->qty;
                                                $subTotal += $total;
                                            ?>
                                            $<span>{{ number_format($total,2,'.',',') }}</span>

                                        </td>
                                        <td class="action" data-title="Remove"><a onclick="remove_product_from_cart(this, '{{ $cart_key }}');" href="javascript:void(0);"><i class="ti-trash remove-icon"></i></a></td>
                                    </tr>        
                                @endforeach
                            
                            @endif;
                            
                        </tbody>
                    </table>
                    <!--/ End Shopping Summery -->
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <!-- Total Amount -->
                    <div class="total-amount">
                        <div class="row">
                            <div class="col-lg-8 col-md-5 col-12">
                                <div class="left">
                                    <div class="coupon">
                                        <form action="#" target="_blank">
                                            <input name="Coupon" placeholder="Enter Your Coupon">
                                            <button class="btn">Apply</button>
                                        </form>
                                    </div>
                                    <div class="checkbox">
                                        <label class="checkbox-inline" for="2"><input name="news" id="2" type="checkbox"> Shipping (+10$)</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-7 col-12">
                                <div class="right">
                                    <ul>
                                        <li>Cart Subtotal <span>{{ number_format($subTotal,2,'.',',') }}</span><span>&dollar;</span></li>
                                        <li>Shipping<span>Free</span></li>
                                        <li>You Save<span>$20.00</span></li>
                                        <?php $totalAmount = $subTotal ?>
                                        <li class="last">You Pay<span>{{ number_format($totalAmount,2,'.',',') }}</span><span>&dollar;</span></li>
                                    </ul>
                                    <div class="button5">
                                        <a href="{{ route('product.checkout') }}" class="btn">Checkout</a>
                                        <a href="{{ route('products') }}" class="btn">Continue shopping</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ End Total Amount -->
                </div>
            </div>
        </div>
    </div>
@endif

<section class="shop-services section">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-12">
                <!-- Start Single Service -->
                <div class="single-service">
                    <i class="ti-rocket"></i>
                    <h4>Free shiping</h4>
                    <p>Orders over $100</p>
                </div>
                <!-- End Single Service -->
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <!-- Start Single Service -->
                <div class="single-service">
                    <i class="ti-reload"></i>
                    <h4>Free Return</h4>
                    <p>Within 30 days returns</p>
                </div>
                <!-- End Single Service -->
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <!-- Start Single Service -->
                <div class="single-service">
                    <i class="ti-lock"></i>
                    <h4>Sucure Payment</h4>
                    <p>100% secure payment</p>
                </div>
                <!-- End Single Service -->
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <!-- Start Single Service -->
                <div class="single-service">
                    <i class="ti-tag"></i>
                    <h4>Best Peice</h4>
                    <p>Guaranteed price</p>
                </div>
                <!-- End Single Service -->
            </div>
        </div>
    </div>
</section>
<!-- End Shop Newsletter -->

<script src="{{ asset('public/assets/js/product.js') }}"></script>