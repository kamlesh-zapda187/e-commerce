<!DOCTYPE html>
<html lang="zxx">
<head>
	@include('layout.style')
</head>
<body class="js">
	
	<!--  Top header -->
	@include('layout.header')

	<!-- Start page content -->
	<?php echo $page_content;?>
	<!-- /End  page content -->
	
	<!-- Start Footer Area -->
		@include('layout/footer');
	<!-- /End Footer Area -->
 
	@include('layout/script');
</body>
</html>