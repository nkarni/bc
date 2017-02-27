<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<!--<![endif]-->
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content= "<?php echo $keywords; ?>" />
<?php } ?>
<script src="catalog/view/javascript/jquery/jquery-2.1.1.min.js" type="text/javascript"></script>
<link href="catalog/view/javascript/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen" />
<script src="catalog/view/javascript/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<link href="catalog/view/javascript/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link href="//fonts.googleapis.com/css?family=Open+Sans:400,400i,300,700" rel="stylesheet" type="text/css" />
<link href="catalog/view/theme/default/stylesheet/stylesheet.css" rel="stylesheet">
<?php foreach ($styles as $style) { ?>
<link href="<?php echo $style['href']; ?>" type="text/css" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<script src="catalog/view/javascript/common.js" type="text/javascript"></script>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<?php foreach ($scripts as $script) { ?>
<script src="<?php echo $script; ?>" type="text/javascript"></script>
<?php } ?>
<?php foreach ($analytics as $analytic) { ?>
<?php echo $analytic; ?>
<?php } ?>

<!-- Owl Stylesheets -->
    <link rel="stylesheet" href="catalog/view/javascript/jquery/owl-carousel/owl.carousel.css">
    <link rel="stylesheet" href="catalog/view/javascript/jquery/owl-carousel/owl.theme.default.min.css">
<!-- javascript -->
    <script src="catalog/view/javascript/jquery/owl-carousel/owl.carousel.js"></script>
    <script src="catalog/view/javascript/slick/slick.min.js" type="text/javascript"></script>
    <link href="catalog/view/javascript/slick/slick.css" rel="stylesheet">
    <link href="catalog/view/javascript/slick/slick-theme.css" rel="stylesheet">
</head>
<body class="<?php echo $class; ?>">
<nav id="top">
  <div class="container">
    <?php //echo $currency; ?>
    <?php //echo $language; ?>
    <!-- <div id="top-links" class="nav pull-right"> -->
      <!-- <ul class="list-inline"> -->
        <!--<li><a href="<?php echo $contact; ?>"><i class="fa fa-phone"></i></a> <span class="hidden-xs hidden-sm hidden-md"><?php echo $telephone; ?></span></li>-->
        <!-- <li class="dropdown"><a href="<?php echo $account; ?>" title="<?php echo $text_account; ?>" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_account; ?></span> <span class="caret"></span></a> -->
          <!-- <ul class="dropdown-menu dropdown-menu-right"> -->
            <?php if ($logged) { ?>
            <!-- <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
            <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
            <li><a href="<?php echo $transaction; ?>"><?php echo $text_transaction; ?></a></li>
            <li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>
            <li><a href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a></li> -->
            <?php } else { ?>
            <!-- <li><a href="<?php echo $register; ?>"><?php echo $text_register; ?></a></li>
            <li><a href="<?php echo $login; ?>"><?php echo $text_login; ?></a></li> -->
            <?php } ?>
          <!-- </ul> -->
        <!-- </li> -->
        <!-- <li><a href="<?php echo $wishlist; ?>" id="wishlist-total" title="<?php echo $text_wishlist; ?>"><i class="fa fa-heart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_wishlist; ?></span></a></li>
        <li><a href="<?php echo $shopping_cart; ?>" title="<?php echo $text_shopping_cart; ?>"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_shopping_cart; ?></span></a></li>
        <li><a href="<?php echo $checkout; ?>" title="<?php echo $text_checkout; ?>"><i class="fa fa-share"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_checkout; ?></span></a></li> -->
      <!-- </ul> -->
    <!-- </div> -->
  <!-- </div> -->
</nav>
<header>
  <div class="container">
    <div class="row">
      <div class="col-sm-12 col-md-3">
        <div id="logo">
          <?php if ($logo) { ?>
          <a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" class="img-responsive" /></a>
          <?php } else { ?>
          <h1><a href="<?php echo $home; ?>"><?php echo $name; ?></a></h1>
          <?php } ?>
        </div>
      </div>
      <div class="col-sm-12 col-md-9">
	  
	  <div class="row">
	  <div class="col-sm-8 col-md-9 prinav">
    		<nav id="menu" class="navbar topmenu">
        <div class="navbar-header"><!--<span id="category" class="visible-xs"><?php //echo $text_category; ?></span>-->
          <button type="button" class="btn btn-navbar navbar-toggle topnav" data-toggle="collapse" data-target=".navbar-ex1-collapse"><i class="fa fa-bars"></i></button>
        </div>
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <?php /*
          <ul class="nav navbar-nav">
            <li class="dropdown"><a href="about-us" class="dropdown-toggle" data-toggle="dropdown">About</a>
              <div class="dropdown-menu">
                <div class="dropdown-inner">
                  <ul class="list-unstyled">
                    <li><a href="#">Focus</a></li>
                    <li><a href="#">Australian Made</a></li>
                    <li><a href="#">Consultancy</a></li>
                    <li><a href="#">Fitouts</a></li>
                    <li><a href="#">Trials</a></li>
                    <li><a href="#">Environment</a></li>
                    <li><a href="#">Franchise</a></li>
                  </ul>
                </div>
            </li>
            <li class="dropdown"><a href="resources" class="dropdown-toggle" data-toggle="dropdown">Resources</a>
              <div class="dropdown-menu">
                <div class="dropdown-inner">
                  <ul class="list-unstyled">
                    <li><a href="#">Office Seating</a></li>
                    <li><a href="#">Ergonomic Accessories</a></li>
                    <li><a href="#">Consultancy</a></li>
                    <li><a href="#">Health</a></li>
                    <li><a href="#">Sleep</a></li>
                    <li><a href="#">Support</a></li>
                  </ul>
                </div>
            </li>
            <li class="dropdown"><a href="#" class="dropdown-toggle">FAQ</a></li>
            <li class="dropdown"><a href="index.php?route=account/login" class="dropdown-toggle">Login</a></li>
            <li class="dropdown"><a href="#" class="dropdown-toggle">Contact Us</a></li>
            <li class="dropdown"><a href="#" class="dropdown-toggle">Wishlist</a></li>
            <li><?php echo $search; ?></li>
          </ul> */
            ?>
            <ul class="nav navbar-nav">
            <?php   echo $very_top_menu ; ?>
            <li><?php echo $search; ?></li>
            </ul>
        </div>
      </nav>
    </div>
		</nav>
    <!-- <div class="col-sm-2 cart"><?php echo $cart; ?></div> -->
	  
	  <div class="col-sm-12">
	  <nav id="menu" class="navbar secondary-navigation">
		<div class="navbar-header"><!--<span id="category" class="visible-xs"><?php //echo $text_category; ?></span>-->
		  <button type="button" class="btn btn-navbar navbar-toggle" data-toggle="collapse" data-target=".navbar-ex2-collapse">Main Navigation</button>
		</div>
		<div class="collapse navbar-collapse navbar-ex2-collapse second-nav">
		  <div class="pull-right"><ul class="nav navbar-nav">
			<?php foreach ($categories as $category) { ?>
			<?php if ($category['children']) { ?>
			<li class="dropdown <?php if ($category['active']) { echo 'active'; } ?>"><a href="<?php echo $category['href']; ?>" class="dropdown-toggle disabled" data-toggle="dropdown"><?php echo $category['name']; ?></a>
			  <div class="menudrop">
			  <div class="dropdown-menu">
				<div class="dropdown-inner">
				  <?php foreach (array_chunk($category['children'], ceil(count($category['children']) / $category['column'])) as $children) { ?>
				  <ul class="list-unstyled">
					<?php foreach ($children as $child) { ?>
					<li><a href="<?php echo $child['href']; ?>"><?php echo $child['name']; ?></a></li>
					<?php } ?>
				  </ul>
				  <?php } ?>
				</div>
				<a href="<?php echo $category['href']; ?>" class="see-all"><?php echo $text_all; ?> <?php echo $category['name']; ?></a> </div>
				</div>				
			</li>
			
			<?php } ?>
			<?php } ?>
		  </ul>
		</div>
	  </nav>
	  </div>
	  </div>
	  </div>
	  
	  </div>
  
    </div>
  </div>
</header>
