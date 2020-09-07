<?php
session_start();
if(!isset($_SESSION["current_user"])) {
    header('Location: login.php');
}
    include("DB-Connection/property.php");
	include("DB-Connection/login.php");
	if(isset($_SERVER['QUERY_STRING'])) {
		$queries = array();
		parse_str($_SERVER['QUERY_STRING'], $queries);
		if(isset($queries['id'])) {
		  	$id = $queries['id'];
			$post_detail = getPostById($id);
			$user_detail = getUserById($post_detail["ownerid"]);
			$property_detail = getPropertyByID($post_detail["propertyid"]);
			$month =  $post_detail["postType"] !== "Sale"?'Kyats/Month':'Kyats';
			$images = getPostImages($post_detail["id"]);
		}
	}
	if(isset($_POST['confirmProposal'])) {
		addProposal($post_detail["id"],$post_detail["ownerid"],$_SESSION['userid']);
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Property Rental And Selling System</title>
	<meta charset="UTF-8">
	<meta name="description" content="LERAMIZ Landing Page Template">
	<meta name="keywords" content="LERAMIZ, unica, creative, html">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- Favicon -->   
	<link href="img/favicon.ico" rel="shortcut icon"/>

	<!-- Google Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">

	<!-- Stylesheets -->
	<link rel="stylesheet" href="css/bootstrap.min.css"/>
	<link rel="stylesheet" href="css/font-awesome.min.css"/>
	<link rel="stylesheet" href="css/animate.css"/>
	<link rel="stylesheet" href="css/owl.carousel.css"/>
	<link rel="stylesheet" href="css/magnific-popup.css"/>
	<link rel="stylesheet" href="css/style.css"/>


	<!--[if lt IE 9]>
	  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

</head>
<body>
	<!-- Page Preloder -->
	<div id="preloder">
		<div class="loader"></div>
	</div>
	
	<!-- Header section -->
	<header class="header-section">
		<div class="header-top">
			<div class="container">
				<div class="row">
					<div class="col-lg-6 header-top-left">
					<div class="top-info">
							<i class="fa fa-phone"></i>
							(+95) 976 821 3224
						</div>
						<div class="top-info">
							<i class="fa fa-envelope"></i>
							HninNander@gmail.com
						</div>
					</div>
					<div class="col-lg-6 text-lg-right header-top-right">
						<!-- <div class="top-social">
							<a href=""><i class="fa fa-facebook"></i></a>
							<a href=""><i class="fa fa-twitter"></i></a>
							<a href=""><i class="fa fa-instagram"></i></a>
							<a href=""><i class="fa fa-pinterest"></i></a>
							<a href=""><i class="fa fa-linkedin"></i></a>
						</div> -->
						<div class="user-panel">
						<?php 
								if(!isset($_SESSION["current_user"])){
									echo '<a href="login.php"><i class="fa fa-user-circle-o"></i> Register</a>
									<a href="login.php"><i class="fa fa-sign-in"></i> Login</a>';
								}
								else {
									echo '<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown">'.$_SESSION["current_user"].' <span class="caret"></span></a>				
									<ul class="dropdown-menu" role="menu">
										<li><a href="profile.php" style="color:black;">Profile</a></li>
										<li><a href="myinbox.php" style="color:black;">Inbox</a></li>
										<li><a href="myproperties.php" style="color:black;">My Properties</a></li>
										<li><a href="myposts.php" style="color:black;">My  Posts</a></li>
										<li><a href="myproposals.php" style="color:black;">My Proposals</a></li>
										<li class="divider"></li>
										<li><a href="login.php?logout=y" style="color:black;">Log Out</a></li>
									</ul>                
									</li>';
								}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="site-navbar">
						<a href="#" class="site-logo"><img class="img-fluid" style="max-width:200px" src="img/logo.png" alt=""></a>
						<div class="nav-switch">
							<i class="fa fa-bars"></i>
						</div>
						<ul class="main-menu">
						<ul class="main-menu">
							<li><a href="index.php">Home</a></li>
							<li><a href="categories.php">All Posts</a></li>
							<li><a href="about.php">ABOUT US</a></li>
							<li><a href="contact.php">Contact</a></li>
						</ul>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</header>
	<!-- Header section end -->


	<!-- Page top section -->
	<section class="page-top-section set-bg" data-setbg="img/page-top-bg.jpg">
		<div class="container text-white">
			<h2>Property Details</h2>
		</div>
	</section>
	<!--  Page top end -->

	<!-- Breadcrumb -->
	<div class="site-breadcrumb">
		<div class="container">
			<a href=""><i class="fa fa-home"></i>Home</a>
			<span><i class="fa fa-angle-right"></i>Property Details</span>
		</div>
	</div>

	<!-- Page -->
	<section class="page-section">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 single-list-page">
					<?php $postImages = getPostImages($post_detail["id"]);
							if(isset($postImages)){
								echo '<div class="single-list-slider owl-carousel" id="sl-slider">';
							foreach(getPostImages($post_detail["id"]) as $imageObj) {
									$encodedImage = base64_encode($imageObj["image"]);
									echo '<div class="sl-item set-bg">
									<img src="data:image/jpeg;base64,'.$encodedImage.'" style="position:absolute;">
								</div>';
							}
							echo '</div>';
						}
						
					?>
					
					<?php $postImages = getPostImages($post_detail["id"]);
						if(isset($postImages)){
							echo '<div class="owl-carousel sl-thumb-slider" id="sl-slider-thumb">';
							foreach(getPostImages($post_detail["id"]) as $imageObj) {
								$encodedImage = base64_encode($imageObj["image"]);
								echo '<div class="sl-thumb set-bg" data-setbg="data:image/jpeg;base64,'.$encodedImage.'">
							</div>';
							}
							echo "</div>";
						}
						
					?>
					<div class="single-list-content">
						<div class="row">
							<div class="col-xl-8 sl-title">
								<h2><?php echo $property_detail["name"];?></h2>
								<p><i class="fa fa-map-marker"></i><?php echo $property_detail["address"].", ".$property_detail["city"]; ?></p>
							</div>
							<div class="col-xl-4">
								<button data-toggle="modal" data-target="#exampleModal" class="price-btn"><?php echo $post_detail["initial_amount"].$month?></button>
								
								<!-- Modal -->
								<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										Are you sure you want to send proposal to owner of this post?
									</div>
									<div class="modal-footer">
									<form action="" method="post">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
											<button type="confirm" name="confirmProposal" class="btn btn-primary">Confirm</button>
										</form>	
									</div>
									</div>
								</div>
								</div>
							</div>
						</div>
						<h3 class="sl-sp-title">Property Description</h3>
						<div class="description">
							<p><?php echo $property_detail["description"]; ?></p>
						</div>
						<h3 class="sl-sp-title">Property Details</h3>
						<div class="row property-details-list">
								<?php
									showAllPostFeature($post_detail["id"]);
								 ?>
						</div>
					</div>
				</div>
				<!-- sidebar -->
				<div class="col-lg-4 col-md-7 sidebar">
					<div class="author-card">
						<div class="author-img set-bg" data-setbg="data:image/jpeg;base64,<?php
						$encodedImage = base64_encode($user_detail["photo"]);
						echo $encodedImage ?>"></div>
						<div class="author-info">
							<h5><?php echo $user_detail["username"];?></h5>
							<p><?php echo $user_detail["job"]; ?></p>
						</div>
						<div class="author-contact">
							<p><i class="fa fa-phone"></i>+95 <?php echo $user_detail["ph_no"];?></p>
							<p><i class="fa fa-envelope"></i><?php echo $user_detail["email"];?></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- Page end -->
	<!-- Footer section -->
	<footer class="footer-section set-bg" style="background-color:#30caa0;">
		<div class="container">
			<div class="row">
				<div class="col-lg-4 col-md-6 footer-widget">
					<img src="img/logo.png" alt="">
					<p>MYANMAR REAL ESTATE - BUY, SELL AND RENT PROPERTY ONLINE</p>
					<!-- <div class="social">
						<a href="#"><i class="fa fa-facebook"></i></a>
						<a href="#"><i class="fa fa-twitter"></i></a>
						<a href="#"><i class="fa fa-instagram"></i></a>
						<a href="#"><i class="fa fa-pinterest"></i></a>
						<a href="#"><i class="fa fa-linkedin"></i></a>
					</div> -->
				</div>
				<div class="col-lg-4 col-md-6 footer-widget">
					<div class="contact-widget">
						<h5 class="fw-title">CONTACT US</h5>
						<p><i class="fa fa-map-marker"></i>Block 22, Unit 5 MinglarMandalay, Mandalay </p>
						<p><i class="fa fa-phone"></i>(+95) 976 821 3224</p>
						<p><i class="fa fa-envelope"></i>HninNander@gmail.com</p>
						<p><i class="fa fa-clock-o"></i>Mon - Sat, 08 AM - 06 PM</p>
					</div>
				</div>
				<div class="col-lg-4 col-md-6  footer-widget">
				<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3790015.543826699!2d93.60431385875863!3d21.927359283657886!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30cb6d23f0d27411%3A0xf1fb4e3347a64e35!2sMandalay%20Region!5e0!3m2!1sen!2smm!4v1592979331218!5m2!1sen!2smm" width="400" height="300" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
				</div>
			</div>
		</div>
	</footer>
	<!-- Footer section end -->

                                        
	<!--====== Javascripts & Jquery ======-->
	<script src="js/jquery-3.2.1.min.js"></script>
	<script src="js/popper.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/owl.carousel.min.js"></script>
	<script src="js/masonry.pkgd.min.js"></script>
	<script src="js/magnific-popup.min.js"></script>
	<script src="js/main.js"></script>


	<!-- load for map -->
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB0YyDTa0qqOjIerob2VTIwo_XVMhrruxo"></script>
	<script src="js/map-2.js"></script>

</body>
</html>