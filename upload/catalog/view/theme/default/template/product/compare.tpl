<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb no-print">
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
      <input type="hidden" id="customer-id" name="customer-id" value="<?php echo $customer_id; ?>"/>
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
            <?php foreach ($products as $item) { foreach($item as $product) { ?>
            <td><a href="<?php echo $product['href']; ?>"><strong><?php echo $product['name']; ?></strong></a></td>
            <?php } }?>
          </tr>
          <tr>
            <td><?php echo $text_image; ?></td>
            <?php
              foreach ($products as $item) { foreach($item as $product) {
              ?>
            <td class="text-center"><?php if ($product['thumb']) { ?>
              <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-thumbnail" /></a>
              <?php } ?></td>
            <?php } }?>
          </tr>

          <tr>
            <td>Selected Options</td>
            <?php foreach ($products as $item) { foreach($item as $product) { ?>
            <td><?php  foreach ($product['options'] as $option) { echo   $option[0]['name'] . ': ' . $option[0]['value'] . '<br>'; } ?></td>
            <?php  } } ?>
          </tr>

          <tr>
            <td><?php echo $text_summary; ?></td>
            <?php foreach ($products as $item) { foreach($item as $product) { ?>
            <td class="description"><?php echo $product['description']; ?></td>
            <?php } } ?>
          </tr>
          <tr>
            <td>Specifications</td>
            <?php foreach ($products as $item) { foreach($item as $product) { ?>
            <td><?php echo $product['specifications']; ?></td>
            <?php }} ?>
          </tr>
          <tr>
            <td>Features</td>
            <?php foreach ($products as $item) { foreach($item as $product) { ?>
            <td><?php echo $product['features']; ?></td>
            <?php } } ?>
          </tr>

        <tr>
          <td></td>
          <?php foreach ($products as $item) {  foreach($item as $product) { ?>
          <td>
            <button class="wishlist-btn btn-primary btn-lg btn-block" title="ADD TO WISHLIST" data-index="<?php echo $product['index'] ; ?>"  type="button">ADD TO WISHLIST</button>
            <a href="<?php echo $product['remove']; ?>" class=" btn-danger btn-lg btn-block">REMOVE</a></td>
          <?php } } ?>
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
            beforeSend: function() {
                $(".addlist").addClass('disabled');
            },
            complete: function() {
                $(".addlist").removeClass('disabled');
            },
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
    // For wishlist form
    $('.wishlist-btn').click(function () {

        if(!isLoggedIn()){
            $return = 'Please <a href="/index.php?route=account/login">login</a> before adding to wishlist';
            $('#content').parent().before('<div class="alert alert-danger"><i class="fa fa-info-circle"></i> ' + $return + ' <button type="button" class="close" data-dismiss="alert">&times;</button> <div>');
            return ;
        }

        var index = $(this).attr('data-index');
        $addbuttonhtml = '<div class="input-group"><input type="text" class="form-control" name="wishlist_name" id="wishlist_name" placeholder="Type a new wishlist name" ><span class="input-group-btn"><button type="button" data-index="' + index + '" class="addlist btn btn-default" >ADD</button></span></div>';
        $(this).popover({
            html: true,
            trigger: 'manual',

            content: function () {
                $buttons = getwishlists();
                if ($buttons) $buttons += "<p>Or add a new Wish List:</p>";
                $activeproductrow = '<input type="hidden" class="active_product_id" value="' + index + '" />';
                return $activeproductrow + $buttons + $addbuttonhtml;
            }
        }).popover('toggle');

    });

    // For closing popover
    $(document).click(function (e) {
        if ($(e.target).is('.close')) {
            $('.popover').popover('hide');
        }
    });

    function isLoggedIn(){
        if($('#customer-id').val() > 0){
            return true;
        }
        return false;
    }

    // For add product to existing wishlist
    $(document).on("click", ".add-product-to-wishlist", function () {

        // build the options field:

        options = $(this).attr()
        var quantity = $('#input-quantity').val();

        $wishlist = $(this).attr('wishlist');
        var index = $(this).parents('.popover-content').find("input.active_product_id").val();
        $.ajax({
            url: 'index.php?route=account/wishlists/add',
            dataType: 'json',
            type: 'post',
            data: {
                'wishlist_id': $wishlist,
                'index': index
            },
            beforeSend: function() {
                $(".add-product-to-wishlist").addClass('disabled');
            },
            complete: function() {
                $(".add-product-to-wishlist").removeClass('disabled');
            },
            success: function (json) {
                alertHandler.success(json);
                //close popover widget
                $('.popover').popover('hide');
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alertHandler.error(xhr, ajaxOptions, thrownError);
            }
        });
    });


    // For add product to existing wishlist
    $(document).on("click", ".dowishlist", function () {
        $wishlist = $(this).attr('wishlist');
        $product = $(this).parents('.popover-content').find("input.active_product_id").val();
        $.ajax({
            url: 'index.php?route=account/wishlists/add',
            dataType: 'json',
            type: 'post',
            data: 'wishlist_id=' + $wishlist + '&index=' + $product,
            success: function (json) {
                alertHandler.success(json)
                //close popover widget
                $('.popover').popover('hide');
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alertHandler.error(xhr, ajaxOptions, thrownError);
            }
        });
    });


</script>
<?php echo $footer; ?>