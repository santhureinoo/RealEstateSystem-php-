<!DOCTYPE html>
<?php
	if(!isset($_SESSION)) {
		session_start();
	}
	include("DB-Connection/property.php");
	include("DB-Connection/login.php");
	include("DB-Connection/indexPage.php");
	checkStatus();
	getLatestPost();
	$cityList = getNumbersFromCities();
	if(isset($_POST['search'])){
		header('Location: categories.php?region='.$_POST['region'].'&township='.$_POST['township'].'&name='.$_POST["property_name"].'&square_feet='.$_POST['square_feet'].'&rooms='.$_POST['rooms'].'&city='.$_POST["city"].'&type='.$_POST['type'].'&minprice='.$_POST['minprice'].'&maxprice='.$_POST['maxprice']);
	}
?>
<html lang="en">
<head>
	<title>HN - Home</title>
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
									echo '<li class="dropdown" style="list-style-type:none;">
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
						<a href="#" class="site-logo"><img style="width:200px;" class="img-fluid" src="img/logo.png" alt=""></a>
						<div class="nav-switch">
							<i class="fa fa-bars"></i>
						</div>
						<ul class="main-menu">
							<li><a href="index.php">Home</a></li>
							<li><a href="categories.php">All Posts</a></li>
							<li><a href="about.php">ABOUT US</a></li>
							<li><a href="contact.php">Contact</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</header>
	<!-- Header section end -->


	<!-- Hero section -->
	<section class="hero-section set-bg" data-setbg="img/bg.jpeg">
		<div class="container hero-text text-white">
			<h2>Property Rental & Selling System</h2>
			
				<!-- Filter form section -->
			<div class="filter-search">
				<div class="container">
					<form action='' method='post' class="filter-form">
					<div>
						<select id='city' name='city'>
							<option value=''>Select City </option>
							
						</select>
						<select name='type'>
							<option value=''>Select Type </option>
							<?php getTypes();?>
						</select>
						<input type="text" name='minprice' placeholder="Enter Minimum Price">
						<input type="text" name='maxprice' placeholder="Enter Maximum Price">
						
					</div>
					<div class="mt-3">
					<select id='city' name='region'>
							<option value=''>Select Region </option>
							<?php
									// $row = 1;
									$regions = array();
									if (($handle = fopen("township.csv", "r")) !== FALSE) {
										while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
											array_push($regions,$data[1]);
											
											// $num = count($data);
											// echo "<p> $num fields in line $row: <br /></p>\n";
											// $row++;
											// for ($c=0; $c < $num; $c++) {
											// 	echo $data[$c] . "<br />\n";
											// }
										}
										foreach (array_unique($regions) as $region) {
											echo "<option>$region</option>";
										}
										fclose($handle);
									} 
							?>
						</select>
						<select id='city' name='township'>
							<option value=''>Select TownShip </option>
							<?php
									// $row = 1;
									if (($handle = fopen("township.csv", "r")) !== FALSE) {
										while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
											if($data[3] !== ''){
												echo "<option>".$data[3] . "</option>";
											}
											
											// $num = count($data);
											// echo "<p> $num fields in line $row: <br /></p>\n";
											// $row++;
											// for ($c=0; $c < $num; $c++) {
											// 	echo $data[$c] . "<br />\n";
											// }
										}
										fclose($handle);
									} 
							?>
						</select>
						<input type="text" name='property_name' placeholder="Enter Property Name">
						<!-- <input type="text" name='region' placeholder="Enter Region">
						<input type="text" name='township' id='township' placeholder="Enter Township"> -->
						<input type='submit' name='search' class="site-btn fs-submit" value= "SEARCH"></button>
					</div>
					</form>
				</div>
			</div>
			<!-- Filter form section end -->
				</div>
	</section>
	<!-- Hero section end -->

	<!-- Properties section -->
	<section class="properties-section spad">
		<div class="container">	
				<?php 
						$latestPosts = getLatestPost();
						if(isset($latestPosts)){
							echo '<div class="section-title text-center">
									<h3>RECENT PROPERTIES</h3>
									<p>Discover how much the latest properties have been sold for</p>
								</div>
								<div class="row">';
							foreach($latestPosts as $post) {
								$encodedImage = base64_encode($post["image"]);
									echo '
									<div class="col-md-4">
										<div class="propertie-item set-bg">
											<img src="data:image/jpeg;base64,'.$encodedImage.'" style=" display: block;
											width: 100vw;
											height: 100vh;
											object-fit: cover;position:absolute;">
											<div class="propertie-info text-white">
												<div class="info-warp">
													<h5>'.$post["description"].'</h5>
													<p><i class="fa fa-map-marker"></i> '.$post["address"].', '.$post["city"].'</p>
												</div>
												<div class="price"><a href="property-detail.php?id='.$post['id'].'">'.$post["initial_amount"].' Kyats/Month</a></div>
											</div>
										</div>
									</div>
									';
							}
							echo '		</div>
							</div>';
						}
						else {
							echo '<h1 class="text-center">No Active Post Currently </h1>';
						}
					
				?>
	
	</section>
	<!-- Properties section end -->

	<!-- feature section -->
				<?php getPostsFromCity();?>
	<!-- feature section end -->

	<!-- Gallery section -->
	<section class="gallery-section spad" style="padding-top:-100px;">
		<div class="container">
			<div class="section-title text-center">
				<h3>Popular Places</h3>
				<p>We understand the value and importance of place</p>
			</div>
			<div class="gallery">
				<div class="grid-sizer"></div>
				<a href="categories.php?city=nay pyi taw" class="gallery-item set-bg" data-setbg="img/gallery/1.jpg">
					<div class="gi-info">
						<h3>Naypyitaw</h3>
						<p><?php echo $cityList["Nay Pyi Taw"]; ?> Properties</p>
					</div>
				</a>
				<a href="categories.php?city=mandalay" class="gallery-item set-bg" data-setbg="img/gallery/2.jpg">
					<div class="gi-info">
						<h3>Mandalay</h3>
						<p><?php echo $cityList["Mandalay"]; ?> Properties</p>
					</div>
				</a>
				<a href="categories.php?city=Rangoon" class="gallery-item set-bg" data-setbg="img/gallery/3.jpg">
					<div class="gi-info">
						<h3>Rangoon</h3>
						<p><?php echo $cityList["Rangoon"]; ?> Properties</p>
					</div>
				</a>
				<a href="categories.php?city=other" class="gallery-item set-bg">
					<div class="gi-info">
						<h3>Others</h3>
						<p><?php echo $cityList["other"]; ?> Properties</p>
					</div>
				</a>
				
			</div>
		</div>
	</section>
	<!-- Gallery section end -->


	<!-- Footer section -->
	<footer class="footer-section set-bg" style="background-color:#30caa0;">
		<div class="container">
			<div class="row">
				<div class="col-lg-4 col-md-6 footer-widget">
					<img src="img/logo.png" alt="">
					<p>PROPERTY RENTAL SYSTEM</p>
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
	
	<script>
		
	
		  $.getJSON( "mm.json", function( data ) {
			var items = [];
			var currentCity = "<?php if(isset($selectedProperty)){echo $selectedProperty["city"];}{ echo "";} ?>"  
			$('#city').append("<option>Select a City </option>")
			$.each( data, function( key, val ) {
				if(currentCity != val.city) {
				$('#city').append("<option value='"+val.city+"'>"+val.city+"</option>");
				}
				else {
				$('#city').append("<option selected value='"+val.city+"'>"+val.city+"</option>");
				}
			
			});
			});
	</script>
</body>
</html>