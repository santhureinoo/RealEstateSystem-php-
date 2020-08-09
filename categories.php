<!DOCTYPE html>
<?php
	if(!isset($_SESSION)) {
		session_start();
	}
	include("DB-Connection/property.php");
	include("DB-Connection/login.php");
	$currentPage = 1;
	$queryData = false;
	if(isset($_SERVER['QUERY_STRING'])) {
		$queries = array();
		parse_str($_SERVER['QUERY_STRING'], $queries);
		if(isset($queries['page'])) {
			$currentPage = $queries['page'];
		}
		else if(isset($queries['region']) || isset($queries['township']) || isset($queries['city']) || isset($queries['type']) || isset($queries['minprice']) || isset($queries['maxprice'])){
			$queryData= true;
		}
	}
?>
<html lang="en">
<head>
	<title>Property Rental System</title>
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
						<a href="#" class="site-logo"><img class"img-fluid" style="max-width:200px;" src="img/logo.png" alt=""></a>
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
			<h2>All Post</h2>
		</div>

	
	</section>
	<!--  Page top end -->

	<!-- Breadcrumb -->
	<div class="site-breadcrumb">
		<div class="container">
			<a href=""><i class="fa fa-home"></i>Home</a>
			<span><i class="fa fa-angle-right"></i>All Posts</span>

			<!-- Filter form section -->
			<div class="filter-search">
				<div class="container">
					<form action='' method='post' class="filter-form">
							<div>
								<select id= 'city' name='city'>
									<option value="" selected>Select A City </option>
									
								</select>
							
								<select name='type'>
									<option value="" selected>Select Type</option>
								<?php getTypes();?>
								</select>
							<input type="text" name='minprice' placeholder="Enter Minimum">
							<input type="text" name='maxprice' placeholder="Enter Maximum">
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
								<input type="text" name='propertyName' placeholder="Enter Property Name">
								<input type='submit' name='search' class="site-btn fs-submit" value= "SEARCH"></button>
							</div>
					</form>
				</div>
			</div>
			<!-- Filter form section end -->
		</div>
	</div>


	<!-- page -->
	<section class="page-section categories-page">
		<div class="container">
			<div class="row">
			<?php 
					$startPostIndex = ($currentPage * 6) - 6;
					if(isset($_POST['search'])){
						getPartialProperties($startPostIndex,6,$_POST['region']!=''?$_POST['region']:'',$_POST['township']!=''?$_POST['township']:'',$_POST['propertyName']!=''?$_POST['propertyName']:'',$_POST['city'],$_POST['type'],$_POST['minprice'],$_POST['maxprice']);
					}
					else if($queryData == true){ 
						getPartialProperties($startPostIndex,6,isset($queries["region"])?$queries["region"]:null,isset($queries["township"]) && $queries['township'] !=''?$queries["township"]:'',isset($queries["propertyName"]) && $queries['propertyName'] !=''?$queries["propertyName"]:'',isset($queries["city"])?$queries["city"]:null,isset($queries['type'])?$queries['type']:null,isset($queries['minprice'])?$queries['minprice']:null,isset($queries['maxprice'])?$queries['maxprice']:null);
					}
					else {
						getPartialProperties($startPostIndex,6,null,null,null,null);
					}
					
				?>
			</div>
			<div class="site-pagination">
				<a href="categories.php?page=1"><i class="fa fa-angle-left"></i></a>
				<?php
						for($i = 1 ;$i<= getPagination();$i++){
							if($currentPage == $i){
								echo "<span>$currentPage</span>";
							}
							else {
								echo "<a href='categories.php?page=$i'>$i</a>";
							}
						}
				 ?>
				<a href="categories.php?page=<?php echo getCountPosts(); ?>"><i class="fa fa-angle-right"></i></a>
			</div>
		</div>
	</section>
	<!-- page end -->
	<!-- Footer section -->
	<footer class="footer-section set-bg" style="background-color:#30caa0;">
		<div class="container">
			<div class="row">
				<div class="col-lg-4 col-md-6 footer-widget">
					<img src="img/logo.png" alt="">
					<p>PROPERTY RENTAL SYSTEM</p>
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