<?php
	ini_set('display_errors',1);
	error_reporting(E_ALL);

	include("lib/configure.php");
	session_start();
	
	if(isset($_REQUEST['lang'])){
		isset($_REQUEST['lang']) ? $lang = $_REQUEST['lang'] : $lang = "th";		
	}else{
		isset($_SESSION["lang"]) ? $lang = $_SESSION['lang'] : $lang = "th";
	}
	
	if($lang=="en"){
		include("lib/en.php");
		$_SESSION["lang"] = "en";
	}else{
		include("lib/th.php");
		$_SESSION["lang"] = "th";
	}

?>

<!DOCTYPE html>
<html lang="zxx" class="no-js">
<head>
	<!-- Mobile Specific Meta -->
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- Favicon-->
	<link rel="shortcut icon" href="img/fav.png">
	<!-- Author Meta -->
	<meta name="author" content="Colorlib">
	<!-- Meta Description -->
	<meta name="description" content="">
	<!-- Meta Keyword -->
	<meta name="keywords" content="">
	<!-- meta character set -->
	<meta charset="tis-620">
	<!-- Site Title -->
	<title><?php echo $title?></title>

	<link href="https://fonts.googleapis.com/css?family=Poppins:100,300,500" rel="stylesheet">
		<!--
		CSS
		============================================= -->
		<link rel="stylesheet" href="css/linearicons.css">
		<link rel="stylesheet" href="css/owl.carousel.css">
		<link rel="stylesheet" href="css/font-awesome.min.css">
		<link rel="stylesheet" href="css/nice-select.css">
		<link rel="stylesheet" href="css/magnific-popup.css">
		<link rel="stylesheet" href="css/bootstrap.css">
		<link rel="stylesheet" href="css/main.css">
		<script type="text/javascript" src="js/autocomplete.js"></script>
		<link rel="stylesheet" href="css/autocomplete.css"  type="text/css"/>
			
		
		
	
	</head>
	
	
	
	<body>
	<div class="oz-body-wrap">
		<!-- Start Header Area -->
		<header class="default-header">
			<div class="container-fluid">
				<div class="header-wrap">
					<div class="header-top d-flex justify-content-between align-items-center">
						<div class="logo">
							<?php
								$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
								if($lang=="en"){
									if(strpos($actual_link, "?")) $actual_link=$actual_link."&lang=th"; else $actual_link=$actual_link."?lang=th";
									echo "<img src='img/en.png' alt='' height='30'>&nbsp;";
									echo "<a href='$actual_link'><img src='img/th.png' alt='' height='30'></a>";
								}else{
									if(strpos($actual_link, "?")) $actual_link=$actual_link."&lang=en"; else $actual_link=$actual_link."?lang=en";
									echo "<a href='$actual_link'><img src='img/en.png' alt='' height='30'></a>&nbsp;";
									echo "<img src='img/th.png' alt='' height='30'>";
								}
							?>

						</div>
						<div class="main-menubar d-flex align-items-center">
							<nav class="hide">
								<a href="main.php">Home</a>
								<a href="orgchart.php">Organization</a>
								<a href="information.php">Information</a>
								<a href="leave.php">Leave Management</a>
								<a href="main.php#contactus">Contact us</a>
							</nav>
							<div class="menu-bar"><span class="lnr lnr-menu"></span></div>
						</div>
					</div>
				</div>
			</div>
		</header>
		<!-- End Header Area -->
		