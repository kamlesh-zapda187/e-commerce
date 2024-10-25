<?php
    $prices = (!empty(request()->get('price'))) ? request()->get('price') : [];
?>
<!-- Breadcrumbs -->
<div class="breadcrumbs">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="bread-inner">
                    <ul class="bread-list">
                        <li><a href="{{url('')}}">Home<i class="ti-arrow-right"></i></a></li>
                        <li class="active"><a href="javascript:void(0);">Products</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Breadcrumbs -->

<!-- Product Style -->
<section class="product-area shop-sidebar shop section">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-4 col-12">
                <form  name="product_filters_frm" id="product_filters_frm">
                    <input type="hidden"  name="category" id="category">
                <div class="shop-sidebar">
                        <!-- Single Widget -->
                        <div class="single-widget category">
                            <h3 class="title">Categories</h3>
                            <ul class="categor-list">
                                @if(!empty($categories))
                                    @foreach ($categories as $cat_id => $category)
                                        <li><a class="{{ (request()->get('category') == $category) ? 'active' : "" }}" onclick="searchProductByCategory(this,'{{$category}}')" href="javascript:void(0);">{{$category}}</a></li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                        <!--/ End Single Widget -->
                        <!-- Shop By Price -->
                            <div class="single-widget range">
                                <h3 class="title">Shop by Price</h3>
                                <ul class="check-box-list">
                                    <li>
                                        <label class="checkbox-inline" for="1"><input onclick="submit_product_filters_frm();" name="price[]" id="1" value="20-50" type="checkbox" {{ in_array('20-50',$prices) ? 'checked' : '' }} >$20 - $50<span class="count">(3)</span></label>
                                    </li>
                                    <li>
                                        <label class="checkbox-inline" for="2"><input onclick="submit_product_filters_frm();" name="price[]" id="2" value="50-100" type="checkbox" {{ in_array('50-100',$prices) ? 'checked' : '' }} >$50 - $100<span class="count" >(5)</span></label>
                                    </li>
                                    <li>
                                        <label class="checkbox-inline" for="3"><input onclick="submit_product_filters_frm();" name="price[]" id="3" value="100-250" type="checkbox" {{ in_array('100-250',$prices) ? 'checked' : '' }} >$100 - $250<span class="count">(8)</span></label>
                                    </li>
                                </ul>
                            </div>
                            <!--/ End Shop By Price -->
                        <!-- Single Widget -->
                        <div class="single-widget recent-post">
                            <h3 class="title">Recent post</h3>
                            <!-- Single Post -->
                            <div class="single-post first">
                                <div class="image">
                                    <img src="https://via.placeholder.com/75x75" alt="#">
                                </div>
                                <div class="content">
                                    <h5><a href="#">Girls Dress</a></h5>
                                    <p class="price">$99.50</p>
                                    <ul class="reviews">
                                        <li class="yellow"><i class="ti-star"></i></li>
                                        <li class="yellow"><i class="ti-star"></i></li>
                                        <li class="yellow"><i class="ti-star"></i></li>
                                        <li><i class="ti-star"></i></li>
                                        <li><i class="ti-star"></i></li>
                                    </ul>
                                </div>
                            </div>
                            <!-- End Single Post -->
                            <!-- Single Post -->
                            <div class="single-post first">
                                <div class="image">
                                    <img src="https://via.placeholder.com/75x75" alt="#">
                                </div>
                                <div class="content">
                                    <h5><a href="#">Women Clothings</a></h5>
                                    <p class="price">$99.50</p>
                                    <ul class="reviews">
                                        <li class="yellow"><i class="ti-star"></i></li>
                                        <li class="yellow"><i class="ti-star"></i></li>
                                        <li class="yellow"><i class="ti-star"></i></li>
                                        <li class="yellow"><i class="ti-star"></i></li>
                                        <li><i class="ti-star"></i></li>
                                    </ul>
                                </div>
                            </div>
                            <!-- End Single Post -->
                            <!-- Single Post -->
                            <div class="single-post first">
                                <div class="image">
                                    <img src="https://via.placeholder.com/75x75" alt="#">
                                </div>
                                <div class="content">
                                    <h5><a href="#">Man Tshirt</a></h5>
                                    <p class="price">$99.50</p>
                                    <ul class="reviews">
                                        <li class="yellow"><i class="ti-star"></i></li>
                                        <li class="yellow"><i class="ti-star"></i></li>
                                        <li class="yellow"><i class="ti-star"></i></li>
                                        <li class="yellow"><i class="ti-star"></i></li>
                                        <li class="yellow"><i class="ti-star"></i></li>
                                    </ul>
                                </div>
                            </div>
                            <!-- End Single Post -->
                        </div>
                        <!--/ End Single Widget -->
                        <!-- Single Widget -->
                        <div class="single-widget category">
                            <h3 class="title">Manufacturers</h3>
                            <ul class="categor-list">
                                <li><a href="#">Forever</a></li>
                                <li><a href="#">giordano</a></li>
                                <li><a href="#">abercrombie</a></li>
                                <li><a href="#">ecko united</a></li>
                                <li><a href="#">zara</a></li>
                            </ul>
                        </div>
                        <!--/ End Single Widget -->
                </div>



            </div>
            <div class="col-lg-9 col-md-8 col-12">
                <div class="row">
                    <div class="col-12">
                        <!-- Shop Top -->
                        <div class="shop-top">
                            <div class="shop-shorter">
                                <div class="single-shorter">
                                    <label>Show :</label>
                                    <select name="per_page_limit" onchange="submit_product_filters_frm();">
                                        <option {{ (request()->get('per_page_limit') == 5) ? 'selected' : '' }} value="5">5</option>
                                        <option {{ (request()->get('per_page_limit') == 7) ? 'selected' : '' }} value="7">7</option>
                                        <option {{ (request()->get('per_page_limit') == 10) ? 'selected' : '' }} value="10">10</option>
                                        <option {{ (request()->get('per_page_limit') == 50) ? 'selected' : '' }} value="50">50</option>
                                    </select>
                                </div>
                                <div class="single-shorter">
                                    <label>Sort By :</label>
                                    <select name="short_by" onchange="submit_product_filters_frm();">
                                        <option {{ (request()->get('short_by') == 'name') ? 'selected' : '' }}  value="name">Name</option>
                                        <option {{ ( request()->get('short_by') == 'price' ) ? 'selected' : '' }}  value="price">Price</option>
                                    </select>
                                </div>
                            </div>
                            <ul class="view-mode">
                                <li class="active"><a href="shop-grid.html"><i class="fa fa-th-large"></i></a></li>
                                <li><a href="shop-list.html"><i class="fa fa-th-list"></i></a></li>
                            </ul>
                        </div>
                        <!--/ End Shop Top -->
                    </div>
                </div>
                <div class="row">
                    @if(!empty($products))
                        @foreach ($products as $product)
                            <div class="col-lg-4 col-md-6 col-12">
                                <div class="single-product">
                                    <div class="product-img">
                                        <a href="product-details.html">
                                            <img class="default-img" src="{{ asset('public/uploads/product/'.$product->product_image) }}" alt="#">
                                            <img class="hover-img" src="{{ asset('public/uploads/product/'.$product->product_image) }}" alt="#">
                                        </a>
                                        <div class="button-head">
                                            <div class="product-action">
                                                <a data-toggle="modal" data-target="#exampleModal" title="Quick View" href="#"><i class=" ti-eye"></i><span>Quick Shop</span></a>
                                                <a title="Wishlist" href="#"><i class=" ti-heart "></i><span>Add to Wishlist</span></a>
                                            </div>
                                            <div class="product-action-2">
                                                <a title="Add to cart" href="javascript:void(0);" onclick="add_to_cart({{ $product->id }}, 1);">Add to cart</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="product-content">
                                        <h3><a href="product-details.html">{{ $product->product_title }}</a></h3>
                                        <div class="product-price">
                                            <span>${{ number_format($product->price, 2, '.', ' ') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif    
                    
                  
                </div>
                @if(count($products)>0)
                    <div class="col-md-12 float-right">
                        {{ $products->onEachSide(5)->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
<!--/ End Product Style 1  -->	
<script src="{{ asset('public/assets/js/product.js') }}"></script>
<script>


</script>