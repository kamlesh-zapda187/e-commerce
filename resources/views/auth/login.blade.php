	
	<!-- Breadcrumbs -->
	<div class="breadcrumbs">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="bread-inner">
						<ul class="bread-list">
							<li><a href="index1.html">Home<i class="ti-arrow-right"></i></a></li>
							<li class="active"><a href="blog-single.html">Contact</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- End Breadcrumbs -->
  
	<!-- Start Contact -->
	<section id="contact-us" class="contact-us section">
		<div class="container">
				<div class="contact-head">
					<div class="row">
						<div class="col-lg-2 col-12"></div>
						<div class="col-lg-8 col-12">
							<div class="form-main">
								<div class="title">
									<h4>Lolog</h4>
								</div>

								<div>
									@if(Session::has('error'))
									<div class=" mb-2 mt-1 login_error_message success_msg text-danger">
										{{ Session::get('error') }}	
									</div>	
									@endif

								</div>

								<form class="form" method="post" action="{{  url('login') }}">
									@csrf
									<div class="row">
										
										<div class="col-lg-12 col-12">
											<div class="form-group">
												<label>Your Email<span>*</span></label>
												<input name="email" type="email" placeholder="">
												@error('email')
												<span class="error">{{$message}}</span>
												@enderror
											</div>	
										</div>
										<div class="col-lg-12 col-12">
											<div class="form-group">
												<label>Password<span>*</span></label>
												<input name="password" type="password" placeholder="">
												@error('password')
												<span class="error">{{$message}}</span>
												@enderror

											</div>	
										</div>
									
										<div class="col-12">
											<div class="form-group button">
												<button type="submit" class="btn ">Login</button>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
						
					</div>
				</div>
			</div>
	</section>
	<!--/ End Contact -->