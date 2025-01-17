<!-- Preloader -->
<div class="preloader">
    <div class="preloader-inner">
        <div class="preloader-icon">
            <span></span>
            <span></span>
        </div>
    </div>
</div>
<!-- End Preloader -->

<!-- Header -->
<header class="header shop">
    <!-- Topbar -->
    <div class="topbar">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-12 col-12">
                    <!-- Top Left -->
                    <div class="top-left">
                        <ul class="list-main">
                            <li><i class="ti-headphone-alt"></i> +060 (800) 801-582</li>
                            <li><i class="ti-email"></i> support@shophub.com</li>
                        </ul>
                    </div>
                    <!--/ End Top Left -->
                </div>
                <div class="col-lg-8 col-md-12 col-12">
                    <!-- Top Right -->
                    <div class="right-content">
                        <ul class="list-main">
                            @if(Auth::check())
                            <li><i class="ti-user"></i> <a href="#">My account</a></li>
                            <li><i class="ti-power-off"></i><a href="{{ url('logout') }}">Logout</a></li>
                            @else
                            <li><i class="ti-power-off"></i><a href="{{ url('login') }}">Login</a></li>
                            @endif
                        </ul>
                    </div>
                    <!-- End Top Right -->
                </div>
            </div>
        </div>
    </div>
    <!-- End Topbar -->
    <div class="middle-inner">
        <div class="container">
            <div class="row">
                <div class="col-lg-2 col-md-2 col-12">
                    <!-- Logo -->
                    <div class="logo">
                        <a href="index.html"><img src="{{ url('public/assets/images/logo.png') }}" alt="logo"></a>
                    </div>
                    <!--/ End Logo -->
                    <!-- Search Form -->
                    <div class="search-top">
                        <div class="top-search"><a href="#0"><i class="ti-search"></i></a></div>
                        <!-- Search Form -->
                        <div class="search-top">
                            <form class="search-form">
                                <input type="text" placeholder="Search here..." name="search">
                                <button value="search" type="submit"><i class="ti-search"></i></button>
                            </form>
                        </div>
                        <!--/ End Search Form -->
                    </div>
                    <!--/ End Search Form -->
                    <div class="mobile-nav"></div>
                </div>
                <div class="col-lg-8 col-md-7 col-12">
                    <div class="search-bar-top">
                        <div class="search-bar">
                            <select>
                                <option selected="selected">All Category</option>
                                <option>watch</option>
                                <option>mobile</option>
                                <option>kid’s item</option>
                            </select>
                            <form>
                                <input name="search" placeholder="Search Products Here....." type="search">
                                <button class="btnn"><i class="ti-search"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-3 col-12">
                    <div class="right-bar">
                        <!-- Search Form -->
                        <div class="sinlge-bar">
                            <a href="#" class="single-icon"><i class="fa fa-heart-o" aria-hidden="true"></i></a>
                        </div>
                        <div class="sinlge-bar">
                            <a href="#" class="single-icon"><i class="fa fa-user-circle-o" aria-hidden="true"></i></a>
                        </div>
                        <div class="sinlge-bar shopping" id="shopping-box">
                            <a href="#" class="single-icon"><i class="ti-bag"></i> <span class="total-count">{{ (!empty($cartProducts)) ? count($cartProducts) : 0  }}</span></a>
                            
                            @if(empty($cartProducts))
                                <div class="shopping-item">
                                    <span>No items added to the cart</span>
                                </div>  
                            @else
                                 <!-- Shopping Item -->
                                <div class="shopping-item" id="shopping-item">
                                    <div class="dropdown-cart-header">
                                        <span>{{ count($cartProducts) }} Items</span>
                                        <a href="#">View Cart</a>
                                    </div>
                                    <ul class="shopping-list">
                                        <?php $subTotal = 0; ?>
                                        @foreach($cartProducts as $cart_key => $product)
                                            
                                            <li>
                                                <a href="#" class="remove" title="Remove this item" onclick="remove_product_from_cart(this, '{{ $cart_key }}');" href="javascript:void(0);" ><i class="fa fa-remove"></i></a>
                                                <a class="cart-img" href="#"><img src="{{ ($product->product_image) ? asset('public/uploads/product/'.$product->product_image) : '' }}" alt="#"></a>
                                                <h4><a href="#">{{ $product->product_title }}</a></h4>
                                                <p class="quantity">{{ $product->qty }}x - <span class="amount">${{ number_format($product->qty*$product->price,2,'.',',') }}</span></p>
                                                <?php
                                                    $subTotal += $product->qty*$product->price;
                                                ?>
                                            </li>
                                        @endforeach
                                        
                                    </ul>
                                    <div class="bottom">
                                        <div class="total">
                                            <span>Total</span>
                                            <span class="total-amount">${{ number_format($subTotal,'2','.',',') }}</span>
                                        </div>
                                        <a href="{{ route('product.cart') }}" class="btn animate">Cart</a>
                                    </div>
                                </div>
                                <!--/ End Shopping Item -->   
                            @endif
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Header Inner -->
    <div class="header-inner">
        <div class="container">
            <div class="cat-nav-head">
                <div class="row">
                    <?php /* ?>
                    <div class="col-lg-3">
                        <div class="all-category">
                            <h3 class="cat-heading"><i class="fa fa-bars" aria-hidden="true"></i>CATEGORIES</h3>
                            <ul class="main-category">
                                <li><a href="#">New Arrivals <i class="fa fa-angle-right" aria-hidden="true"></i></a>
                                    <ul class="sub-category">
                                        <li><a href="#">accessories</a></li>
                                        <li><a href="#">best selling</a></li>
                                        <li><a href="#">top 100 offer</a></li>
                                        <li><a href="#">sunglass</a></li>
                                        <li><a href="#">watch</a></li>
                                        <li><a href="#">man’s product</a></li>
                                        <li><a href="#">ladies</a></li>
                                        <li><a href="#">westrn dress</a></li>
                                        <li><a href="#">denim </a></li>
                                    </ul>
                                </li>
                                <li class="main-mega"><a href="#">best selling <i class="fa fa-angle-right" aria-hidden="true"></i></a>
                                    <ul class="mega-menu">
                                        <li class="single-menu">
                                            <a href="#" class="title-link">Shop Kid's</a>
                                            <div class="image">
                                                <img src="https://via.placeholder.com/225x155" alt="#">
                                            </div>
                                            <div class="inner-link">
                                                <a href="#">Kids Toys</a>
                                                <a href="#">Kids Travel Car</a>
                                                <a href="#">Kids Color Shape</a>
                                                <a href="#">Kids Tent</a>
                                            </div>
                                        </li>
                                        <li class="single-menu">
                                            <a href="#" class="title-link">Shop Men's</a>
                                            <div class="image">
                                                <img src="https://via.placeholder.com/225x155" alt="#">
                                            </div>
                                            <div class="inner-link">
                                                <a href="#">Watch</a>
                                                <a href="#">T-shirt</a>
                                                <a href="#">Hoodies</a>
                                                <a href="#">Formal Pant</a>
                                            </div>
                                        </li>
                                        <li class="single-menu">
                                            <a href="#" class="title-link">Shop Women's</a>
                                            <div class="image">
                                                <img src="https://via.placeholder.com/225x155" alt="#">
                                            </div>
                                            <div class="inner-link">
                                                <a href="#">Ladies Shirt</a>
                                                <a href="#">Ladies Frog</a>
                                                <a href="#">Ladies Sun Glass</a>
                                                <a href="#">Ladies Watch</a>
                                            </div>
                                        </li>
                                    </ul>
                                </li>
                                <li><a href="#">accessories</a></li>
                                <li><a href="#">top 100 offer</a></li>
                                <li><a href="#">sunglass</a></li>
                                <li><a href="#">watch</a></li>
                                <li><a href="#">man’s product</a></li>
                                <li><a href="#">ladies</a></li>
                                <li><a href="#">westrn dress</a></li>
                                <li><a href="#">denim </a></li>
                            </ul>
                        </div>
                    </div>
                    <?php */ ?>
                    <div class="col-lg-9 col-12">
                        <div class="menu-area">
                            <!-- Main Menu -->
                            <nav class="navbar navbar-expand-lg">
                                <div class="navbar-collapse">	
                                    <div class="nav-inner">	
                                        <ul class="nav main-menu menu navbar-nav">
                                                <li class="{{ (request()->is('/')) ? 'active' : '' }}"><a href="{{ url('') }}">Home</a></li>
                                                <li class="{{ (request()->is('products')) ? 'active' : '' }}"><a href="{{ route('products') }}">Product</a></li>												
                                                <li><a href="#">Service</a></li>
                                                <li><a href="#">Shop<i class="ti-angle-down"></i><span class="new">New</span></a>
                                                    <ul class="dropdown">
                                                        <li><a href="shop-grid.html">Shop Grid</a></li>
                                                        <li><a href="cart.html">Cart</a></li>
                                                        <li><a href="checkout.html">Checkout</a></li>
                                                    </ul>
                                                </li>
                                                <li><a href="#">Pages</a></li>									
                                                <li><a href="#">Blog<i class="ti-angle-down"></i></a>
                                                    <ul class="dropdown">
                                                        <li><a href="blog-single-sidebar.html">Blog Single Sidebar</a></li>
                                                    </ul>
                                                </li>
                                                <li><a href="contact.html">Contact Us</a></li>
                                            </ul>
                                    </div>
                                </div>
                            </nav>
                            <!--/ End Main Menu -->	
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ End Header Inner -->
</header>
<!--/ End Header -->