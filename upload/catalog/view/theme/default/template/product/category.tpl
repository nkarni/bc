<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
		

	<div class="inner-category">
            <div class="row">
                <div class="col-md-12">
                    <h2><?php echo $heading_title; ?></h2>
                </div>
            </div>

            <div class="row">
                <?php 
                foreach($categories as $category){ ?>
                    <div class="col-md-2 col-sm-4 col-xs-6 sub-category">
                        <div class="image_wrapper">
                            <a href="<?php echo $category['href'] ?>">
                                <img src="image/<?php echo $category['image'] ?>" class="attachment-home_category size-home_category" alt="<?php $category['name'] ?>" height="200" width="320">
                            </a>			
                        </div>

                        <div class="title <?php echo ($category_id == $category['category_id']) ? 'active' : ''; ?>">
                            <p>
                                <a href="<?php echo $category['href'] ?>"><?php echo $category['name'] ?></a>
                            </p>
                        </div>
                    </div>
                <?php } ?>
            </div>
	</div>

	<div class="slick-carousel">
            <?php foreach($products as $product){ ?>
                <div class="item">
                    <div class="slider_item">
                        <a href="<?php echo $product['href']; ?>">
                            <img alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" src="<?php echo $product['thumb']; ?>">
                            <p><?php echo $product['name']; ?></p>
                        </a>
                    </div>
                </div>
            <?php } ?>
	</div>
	
	<script>
            $(document).ready(function() {
                $('.slick-carousel').slick({
                    dots: false,
                    infinite: true,
                    speed: 300,
                    slidesToShow: 1,
                    variableWidth: true
                });
            });
        </script>
	
    <div id="content" class="<?php echo $class; ?>">
	<?php echo $content_top; ?>
      <h2><?php echo $heading_title; ?></h2>
      <?php if ($thumb || $description) { ?>
      <div class="row">
        <?php if ($thumb) { ?>
        <div class="col-sm-2"><img src="<?php echo $thumb; ?>" alt="<?php echo $heading_title; ?>" title="<?php echo $heading_title; ?>" class="img-thumbnail" /></div>
        <?php } ?>
        <?php if ($description) { ?>
        <div class="col-sm-10"><?php echo $description; ?></div>
        <?php } ?>
      </div>
      
      <?php } ?>	  
	  </div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>
