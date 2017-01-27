<?php echo $header; ?>
<?php echo $content_homeslider; ?>
<div class="container">
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>">
	<?php echo $content_top; ?>
	<?php echo $content_bottom; ?>
	
	<div id="shop_categories">
	<div class="category col-md-3 col-sm-4 col-xs-6">
		<div class="image_wrapper">
			<a href="workspaces"><img src="image/catalog/home/Workspaces-home-icon.jpg" class="attachment-home_category size-home_category img-responsive" alt="workspaces-home-icon" sizes="(max-width: 304px) 100vw, 304px" width="304" height="190"></a>							
		</div>
		<div class="title">
			<p><a href="#">WORKSPACES &gt;</a></p>
		</div>
	</div>
					
	<div class="category col-md-3 col-sm-4 col-xs-6">
		<div class="image_wrapper">
			<a href="health"><img src="image/catalog/home/Hero-image-SHOP-320x200.png" class="attachment-home_category size-home_category img-responsive" alt="HEALTH Hero" sizes="(max-width: 320px) 100vw, 320px" width="320" height="200"></a>
		</div>
		<div class="title">
			<p><a href="#">HEALTH &gt;</a></p>
		</div>
	</div>
		
	<div class="category col-md-3 col-sm-4 col-xs-6">
		<div class="image_wrapper">
			<a href="sleep"><img src="image/catalog/home/Hero-image-BASES-AND-MATTRESSES-320x200.png" class="attachment-home_category size-home_category img-responsive" alt="SLEEP hero" sizes="(max-width: 320px) 100vw, 320px" width="320" height="200"></a>
		</div>
		<div class="title">
			<p><a href="#">SLEEP &gt;</a></p>
		</div>
	</div>

	<div class="category col-md-3 col-sm-4 col-xs-6">
		<div class="image_wrapper">
			<a href="support"><img src="image/catalog/home/Hero-image-HEALTH-PROFESSHIONAL1-320x200.png" class="attachment-home_category size-home_category img-responsive" alt="Support hero" sizes="(max-width: 320px) 100vw, 320px" width="320" height="200"></a>
		</div>
		<div class="title">
			<p><a href="#">SUPPORT &gt;</a></p>
		</div>
	</div>
		
	<div class="category col-md-3 col-sm-4 col-xs-6">
		<div class="image_wrapper">
			<a href="home"><img src="image/catalog/home/Comfort-Seating-Hero-e1363043032348-320x200.jpg" class="attachment-home_category size-home_category img-responsive" alt="HOME Hero" width="320" height="200"></a>
		</div>
		<div class="title">
			<p><a href="#">@HOME &gt;</a></p>
		</div>
	</div>
	
	<div class="category col-md-3 col-sm-4 col-xs-6">
		<div class="image_wrapper">
			<a href="resources"><img src="image/catalog/home/Resources_v2-320x200.jpg" class="attachment-home_category size-home_category img-responsive" alt="Resources" width="320" height="200"></a>
		</div>
		<div class="title">
			<p><a href="#">RESOURCES &gt;</a></p>
		</div>
	</div>

	<div class="category col-md-3 col-sm-4 col-xs-6">
		<div class="image_wrapper">
			<a href="about-us"><img src="image/catalog/home/ABOUT-US-Hero1-750x550-320x200.jpg" class="attachment-home_category size-home_category img-responsive" alt="About Us" sizes="(max-width: 320px) 100vw, 320px" width="320" height="200"></a>
			</div>
		<div class="title">
			<p><a href="#">ABOUT &gt;</a></p>
		</div>
	</div>

	<div class="category col-md-3 col-sm-4 col-xs-6">
		<div class="image_wrapper">
			<a href="index.php?route=information/contact"><img src="image/catalog/home/Contact-Us-Hero-3-755x550-320x200.jpg" class="attachment-home_category size-home_category img-responsive" alt="Contact Us" sizes="(max-width: 320px) 100vw, 320px" width="320" height="200"></a>
		</div>
		<div class="title">
			<p><a href="#">CONTACT US &gt;</a></p>
		</div>
	</div>
	
</div>
	
	</div>
    <?php echo $column_right; ?></div>
	
</div>

<?php echo $footer; ?>