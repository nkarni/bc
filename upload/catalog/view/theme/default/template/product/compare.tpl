<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($success) { ?>
  <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h1><?php echo $heading_title; ?></h1>
      <?php if ($products) { ?>
      <table class="table table-bordered">
        <thead>
          <tr>
            <td colspan="<?php echo count($products) + 1; ?>"><strong><?php echo $text_product; ?></strong></td>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><?php echo $text_name; ?></td>
            <?php foreach ($products as $product) { ?>
            <td><a href="<?php echo $product['href']; ?>"><strong><?php echo $product['name']; ?></strong></a></td>
            <?php } ?>
          </tr>
          <tr>
            <td><?php echo $text_image; ?></td>
            <?php foreach ($products as $product) { ?>
            <td class="text-center"><?php if ($product['thumb']) { ?>
              <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-thumbnail" />
              <?php } ?></td>
            <?php } ?>
          </tr>
          <tr>
            <td><?php echo $text_price; ?></td>
            <?php foreach ($products as $product) { ?>
            <td><?php if ($product['price']) { ?>
              <?php if (!$product['special']) { ?>
              <?php echo $product['price']; ?>
              <?php } else { ?>
              <strike><?php echo $product['price']; ?></strike> <?php echo $product['special']; ?>
              <?php } ?>
              <?php } ?></td>
            <?php } ?>
          </tr>

          <?php if ($review_status) { ?>
          <tr>
            <td><?php echo $text_rating; ?></td>
            <?php foreach ($products as $product) { ?>
            <td class="rating"><?php for ($i = 1; $i <= 5; $i++) { ?>
              <?php if ($product['rating'] < $i) { ?>
              <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
              <?php } else { ?>
              <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
              <?php } ?>
              <?php } ?>
              <br />
              <?php echo $product['reviews']; ?></td>
            <?php } ?>
          </tr>
          <?php } ?>
          <tr>
            <td><?php echo $text_summary; ?></td>
            <?php foreach ($products as $product) { ?>
            <td class="description"><?php echo $product['description']; ?></td>
            <?php } ?>
          </tr>
          <tr>
            <td>Specifications</td>
            <?php foreach ($products as $product) { ?>
            <td><?php echo $product['specifications']; ?></td>
            <?php } ?>
          </tr>
          <tr>
            <td>Features</td>
            <?php foreach ($products as $product) { ?>
            <td><?php echo $product['features']; ?></td>
            <?php } ?>
          </tr>
        </tbody>
        <?php foreach ($attribute_groups as $attribute_group) { ?>
        <thead>
          <tr>
            <td colspan="<?php echo count($products) + 1; ?>"><strong><?php echo $attribute_group['name']; ?></strong></td>
          </tr>
        </thead>
        <?php foreach ($attribute_group['attribute'] as $key => $attribute) { ?>
        <tbody>
          <tr>
            <td><?php echo $attribute['name']; ?></td>
            <?php foreach ($products as $product) { ?>
            <?php if (isset($product['attribute'][$key])) { ?>
            <td><?php echo $product['attribute'][$key]; ?></td>
            <?php } else { ?>
            <td></td>
            <?php } ?>
            <?php } ?>
          </tr>
        </tbody>
        <?php } ?>
        <?php } ?>
        <tr>
          <td></td>
          <?php foreach ($products as $product) { ?>
          <td>
            <?php if($show_wishlist==1 && $multiplewishlist==1) { ?>
            <button class="wishlist-add-form btn btn-primary btn-block" rel="popover" product="<?php echo $product['product_id']; ?>" title="Wishlist" type="button">ADD TO WISHLIST</button>
            <?php } else { ?>
            <button data-placement="top" data-toggle="tooltip" title="<?php echo $text_login_must; ?>" class="btn btn-primary btn-block" >ADD TO WISHLIST</button>
            <?php } ?>
            <a href="<?php echo $product['remove']; ?>" class="btn btn-danger btn-block"><?php echo $button_remove; ?></a></td>
          <?php } ?>
        </tr>
      </table>
      <?php } else { ?>
      <p><?php echo $text_empty; ?></p>
      <div class="buttons">
        <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-default"><?php echo $button_continue; ?></a></div>
      </div>
      <?php } ?>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<script type="text/javascript"><!--
    function getwishlists() {
        $listitems = '';
        $.ajax({
            url: 'index.php?route=account/wishlists/getwishlists',
            dataType: 'json',
            type: 'post',
            async: false,
            success: function (json) {
                var items = [];
                $.each(json, function (key, data) {
                    items.push('<button type="button" style="cursor:pointer;" class="list-group-item dowishlist" wishlist=' + key + '>' + data + '<i class="fa fa-check-square-o label-icon-right"></i></button>');
                });
                if (items.length > 0)
                    $listitems = '<div class="list-group mywishlist-group">' + items.join('') + '</div>';
            }
        });
        return $listitems;
    }

    // For mouseover style the wishlist items
    $(document).on("mouseover", '.mywishlist-group button', function () {
        $(this).addClass('btn-success');
    });
    // For mouseout style the wishlist items
    $(document).on("mouseout", '.mywishlist-group button', function () {
        $(this).removeClass('btn-success');
    });
    // For add new wishlist with product
    $(document).on("click", '.addlist', function () {
        //  $product = $(this).attr('product');
        $product = $(this).parents('.popover-content').find("input.active_product_id").val();
        $wishlist = $(this).parent("span").parent("div").find("input#wishlist_name").val();
        $.ajax({
            url: 'index.php?route=account/wishlists/add',
            dataType: 'json',
            type: 'post',
            data: 'wishlist_name=' + encodeURIComponent($wishlist) + '&product_id=' + $product,
            success: function (json) {
                $return = '';
                if (json.success) {
                    $return = json.success;
                    $title = "Success  <span class='close'>&times;</span>";
                }
                else if (json.info) {
                    $return = json.info;
                    $title = "Information  <span class='close'>&times;</span>";
                }
                //Show alert message
                $('#content').parent().before('<div class="alert alert-info"><i class="fa fa-info-circle"></i>' + $return + ' <button type="button" class="close" data-dismiss="alert">&times;</button> <div>');
                //close popover window
                $('.popover').popover('hide');
            }
        });
    });

    // For closing popover
    $(document).click(function (e) {
        if ($(e.target).is('.close')) {
            $('.popover').popover('hide');
        }
    });

    // For add product to existing wishlist
    $(document).on("click", ".dowishlist", function () {
        $wishlist = $(this).attr('wishlist');
        $product = $(this).parents('.popover-content').find("input.active_product_id").val();
        $.ajax({
            url: 'index.php?route=account/wishlists/add',
            dataType: 'json',
            type: 'post',
            data: 'wishlist_id=' + $wishlist + '&product_id=' + $product,
            success: function (json) {
                $return = '';
                if (json.success) {
                    $return = json.success;
                    $title = "Success  <span class='close'>&times;</span>";
                }
                else if (json.info) {
                    $return = json.info;
                    $title = "Information <span class='close'>&times;</span>";
                }
                //Show alert message
                $('#content').parent().before('<div class="alert alert-info"><i class="fa fa-info-circle"></i>' + $return + ' <button type="button" class="close" data-dismiss="alert">&times;</button> <div>');
                //close popover widget
                $('.popover').popover('hide');
            }
        });
    });

    // For wishlist form
    $('.wishlist-add-form').click(function () {
        $currentproduct = $(this).attr('product');
        $addbuttonhtml = '<div class="input-group"><input type="text" class="form-control" name="wishlist_name" id="wishlist_name" placeholder="Type a new wishlist name" ><span class="input-group-btn"><button type="button" product="' + $currentproduct + '" class="addlist btn btn-default" >ADD</button></span></div>';

        $(this).popover({
            html: true,
            trigger: 'manual',
            placement: 'top',
            container: 'body',

            content: function () {
                $buttons = getwishlists();
                if ($buttons) $buttons += "<p>Or add a new Wish List:</p>";
                $activeproductrow = '<input type="hidden" class="active_product_id" value="' + $currentproduct + '" />';
                return $activeproductrow + $buttons + $addbuttonhtml;
            }
        }).popover('toggle');
    });
</script>
<?php echo $footer; ?>