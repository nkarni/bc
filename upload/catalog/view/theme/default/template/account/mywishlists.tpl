<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb no-print">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($success) { ?>
  <div class="alert alert-success no-print"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
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
    <?php }

$class = 'col-sm-12';
?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h2><?php echo $heading_title; ?></h2>
      <h5>Created On :<?php echo $created_on; ?></h5>        
      <?php if(!$is_owner){ ?>
      <h5>Created By :<?php echo $created_by; ?></h5>
      <?php } ?>

        <?php if($visiblity && count($wishlistitems) > 0) { ?>
        <div class="no-print" syle="width:100%;">
          <div class=" share-wrapper hidden" >
            <div class="row">
              <div class="col-sm-12"><p>Email this wishlist to:</p></div>
              <div class="col-sm-12"><input type="text"  id="share-name" placeholder="Name" class="form-control"/></div>
              <div class="col-sm-12"><input type="text"  id="share-email" placeholder="Email" class="form-control"/></div>
            </div>
          <div class="row">
              <div class="col-sm-4 pull-right">
                <button class="btn-primary btn-small btn-block share-wishlist" title="Submit" type="button">SUBMIT</button>
              </div>
              <div class="col-sm-4 pull-left">
              <button class="btn-primary btn-small btn-block" title="Cancel" id="share-cancel" type="button">CANCEL</button>
            </div>
          </div>
          </div>
            <div class="goodshare-color row" >
                <?php if($wishlists_copy_url_status) { ?>
                    <span class="show-copy-link btn btn-primary btn-small"  onclick="js:window.print();" style="padding:none !important;line-height:0.5 !important;">Print</span>
                <?php } ?>
                    <a class="show-copy-link btn btn-primary btn-small share-button" href="#" style="padding:none !important;line-height:0.5 !important;">Share Via Email</a>
              <span class="show-copy-link btn btn-primary btn-small" id="submit-btn"  onclick="js:requestQuotation();" style="padding:none !important;line-height:0.5 !important;">Submit for a Quotation</span>
              <input type="hidden" id="wishlist-id" value="<?php echo $wishlist_id; ?>"/>

            </div>
              <div class="row"><div class="col-sm-12">&nbsp;</div>
            </div>

        </div>

        <?php } ?>


        <?php if (count($wishlistitems) > 0) { ?>

      <table class="table table-hover my-wishlist-table">
        <thead>
        <tr>
          <td class="text-left hidden-xs" width="15%"></td>

          <td class="text-left" width="45%"><strong>Product</strong></td>

          <td class="text-left hidden-xs" width="25%"><strong>Selected Options</strong></td>

          <td class="text-center " width="15%"><strong>Quantity</strong></td>
        </tr>
        </thead>
        <tbody>
          <?php foreach ($wishlistitems as $wishlistitem) { ?>
          <tr id="tr-<?php echo $wishlistitem['wishlist_item_id']; ?>" class="wishlist-item">
              <td class="text-center hidden-xs"><?php if ($wishlistitem['thumb']) { ?>

                  <a href="<?php echo $wishlistitem['href']; ?>"><img src="<?php echo $wishlistitem['thumb']; ?>" alt="<?php echo $wishlistitem['product_name']; ?>" title="<?php echo $wishlistitem['product_name']; ?>" style="width: 100%" /></a>
                  <?php } ?></td>
            <td class="text-left">
            <a href="<?php echo $wishlistitem['href']; ?>" class="visible-xs no-print" style="display: block; margin-bottom: 8px"><img src="<?php echo $wishlistitem['thumb']; ?>" alt="<?php echo $wishlistitem['product_name']; ?>" title="<?php echo $wishlistitem['product_name']; ?>" style="width: 100%" /></a>
              <a href="<?php echo $wishlistitem['href']; ?>"><strong><?php echo $wishlistitem['product_name']; ?></strong></a><br>
                <small class="hidden-xs"><?php echo $wishlistitem['short_description'] ; ?></small>

            </td>
              <td  class="text-left hidden-xs">
              <?php
                foreach ($wishlistitem['full_product_data'][0]['option'] as $option) {
                  echo '<small><strong>' . $option['name'] . ':</strong> ' . $option['value'] . '</small><br>';
                }
              ?>
              </td>
              <td class="">
                <div class="print-only" style="width: 100%; padding-left: 20px;"><?php echo $wishlistitem['quantity']; ?></div>

                <div class="input-group btn-block pull-right" style="max-width: 140px;">

                  <input type="text" name="quantity[<?php echo $wishlistitem['wishlist_item_id']; ?>]" value="<?php echo $wishlistitem['quantity']; ?>" size="1" class="input-number form-control no-print" />
                  <input type="hidden" id="itemname_<?php echo $wishlistitem['wishlist_item_id']; ?>" value="<?php echo $wishlistitem['product_name']; ?>"/>
                  <span class="input-group-btn no-print">
                    <button type="submit" data-toggle="tooltip" data-item-id="<?php echo $wishlistitem['wishlist_item_id']; ?>" title="<?php echo $button_update; ?>" class="btn btn-primary update-qty"><i class="fa fa-refresh"></i></button>
                    <button type="button" data-toggle="tooltip" data-item-id="<?php echo $wishlistitem['wishlist_item_id']; ?>" title="<?php echo $button_remove; ?>" class="btn btn-danger remove-wishlist-item"><i class="fa fa-times-circle"></i></button>
                  </span>
                </div>
              </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
      <?php if($visiblity && count($wishlistitems) > 0) { ?>
      <div class="no-print" syle="width:100%;">

        <div class="goodshare-color row" >
          <?php if($wishlists_copy_url_status) { ?>
          <span class="show-copy-link btn btn-primary btn-small"  onclick="js:window.print();" style="padding:none !important;line-height:0.5 !important;">Print</span>
          <?php } ?>
          <a class="show-copy-link btn btn-primary btn-small share-button" style="padding:none !important;line-height:0.5 !important;">Share Via Email</a>
          <span class="show-copy-link btn btn-primary btn-small" id="submit-btn"  onclick="js:requestQuotation();" style="padding:none !important;line-height:0.5 !important;">Submit for a Quotation</span>
          <input type="hidden" id="wishlist-id" value="<?php echo $wishlist_id; ?>"/>
        </div>

      </div>

      <?php } ?>

      <?php } else { ?>
      <h3><strong>You currently don't have any items in this Wishlist.</strong></h3>
      <p>To add items, simply browse through our wide range of products and add your preferred items by clicking the "Add to WISHLIST" button.</p>
      <div class="buttons">
        <a href="/" class="btn btn-primary">Browse Our Range</a>
      </div>
      <?php } ?>

      <?php echo $content_bottom; ?></div>
    <?php // echo $column_right; ?></div>
</div>
<?php echo $footer; ?>

<script type="text/javascript">

      function requestQuotation(){
          var wishListId = $('#wishlist-id').val();
          $.ajax({
              url: 'index.php?route=account/wishlists/requestQuotation',
              type: 'get',
              data: {
                  'wishlist_id': wishListId,
                  'trigger' : 'quote'
              },
              dataType: 'json',
              beforeSend: function() {
                  $("#submit-btn").addClass('disabled');
              },
              complete: function() {
                  $("#submit-btn").removeClass('disabled');
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
      }

      function shareWishlist(){
          var wishListId = $('#wishlist-id').val();
          var shareName = $('#share-name').val();
          var shareEmail = $('#share-email').val();

          if(!shareEmail.length || !shareName.length){
              alert('Please enter name and email');
              return;
          }
          $.ajax({
              url: 'index.php?route=account/wishlists/requestQuotation',
              type: 'get',
              data: {
                  'wishlist_id': wishListId,
                  'share-name': shareName,
                  'share-email' : shareEmail,
                  'trigger': 'share'
              },
              dataType: 'json',
              beforeSend: function() {
                  $(".share-wishlist").addClass('disabled');
                  $(".share-wrapper").addClass('opacity3');
              },
              complete: function() {
                  $(".share-wishlist").removeClass('disabled');
                  $(".share-wrapper").removeClass('opacity3');
              },
              success: function (json) {
                  $('.share-wrapper').addClass('hidden');
                  alertHandler.success(json);
                  //close popover widget
                  $('.popover').popover('hide');
              },
              error: function(xhr, ajaxOptions, thrownError) {
                  alertHandler.error(xhr, ajaxOptions, thrownError);
              }
          });
      }

      $(document).on('click', '.share-wishlist', function() {
          shareWishlist();
      });

      $(document).on('click', '.share-button', function() {
          $('.alert').remove();
          $('.share-wrapper').removeClass('hidden');
          $('html, body').animate({scrollTop: 0}, 'slow');
          return false;
      });

      $(document).on('click', '#share-cancel', function() {
          $('.share-wrapper').addClass('hidden');
          return false;
      });

    function updateListItem(itemId, quantity, action){
        var wishListId = $('#wishlist-id').val();
        $.ajax({
            url: 'index.php?route=account/wishlists/' + action,
            type: 'post',
            data: {
                'wishlist_item_id': itemId,
                'quantity' : quantity,
                'action': action,
                'wishlist_id' : wishListId
            },
            dataType: 'json',
            beforeSend: function() {
                $(".table").find("[data-item-id='" + itemId + "']").addClass('disabled');
            },
            complete: function() {
                $(".table").find("[data-item-id='" + itemId + "']").removeClass('disabled');
            },
            success: function (json) {
                alertHandler.success(json);

                //close popover widget
                $('.popover').popover('hide');

                if(quantity == 0){
                    if ($('.wishlist-item').length - $('.wishlist-item[style]').length === 1) {
                        window.location.reload()
                    }
                    $('#tr-' + itemId).hide('slow');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alertHandler.error(xhr, ajaxOptions, thrownError);
            }
        });
    }

    $(document).on('click', '.update-qty', function() {
        var itemId = $(this).data('item-id');
        var qty = $('[name="quantity[' + itemId + ']"]').val();
        if(isNaN(qty)){
            alert('Quantity must be a number');
        }else{
            updateListItem(itemId, qty, 'updateWishlistitemQty' );
        }
    });

    $(document).on('click', '.remove-wishlist-item', function() {
        if(confirm('Are you sure?')){
            var itemId = $(this).data('item-id');
            updateListItem(itemId, '0', 'updateWishlistitemQty' );
        }
    });

    function CopyToClipboard(text) {
        window.prompt("Copy to clipboard: Ctrl+C, Enter", text);
    }
         
    $('.wishlist-add-form').click(function () {
		
        $currentproduct = $(this).attr('product');  
        $minimum = $(this).attr('minimum');  
		
		$buttontext = '<div class="buttons">';
		
		$buttontext += '<span id="addtoorder" product="'+$currentproduct+'" minimum="'+$minimum+'" class="btn btn-success">Yes</span>';
		
		$buttontext += '&nbsp;&nbsp;&nbsp;&nbsp;<span class="wishlist-close btn btn-danger">No</span>';   
		
		$buttontext += '</div>';   
		
        
       // $addbuttonhtml = 'test';

        $(this).popover({
            html: true,
            trigger: 'manual',
            placement: 'top',

            content: function () {
                $buttons = $('.popover-body1').html();
               // $activeproductrow = 'test';
                return $buttons+$buttontext;
                
            }
            
        }).popover('toggle');

    });


    $(document).on('click', '.add-to-order', function() {
        var itemId = $(this).data('item-id');
        var qty = $('[name="quantity[' + itemId + ']"]').val();
        var itemName = $('#itemname_' + itemId).val();


        if($.isNumeric(itemId) && $.isNumeric(qty)){
            $.ajax({
                url: 'index.php?route=account/wishlists/addItemToOrder',
                type: 'post',
                data: {
                    'wishlist_item_id': itemId,
                    'wishlist_item_name': itemName,
                    'quantity': qty
                },
                dataType: 'json',
                beforeSend: function() {
                    $('#cart > button').button('loading');
                },
                complete: function() {
                    $('#cart > button').button('reset');
                },
                success: function(json) {
                    if (json.success) {
                        $return = json.success;
                        $('#content').parent().before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + $return + ' <button type="button" class="close" data-dismiss="alert">&times;</button> <div>');
                    }
                    else if (json.info) {
                        $return = json.info;
                        $('#content').parent().before('<div class="alert alert-info"><i class="fa fa-info-circle"></i> ' + $return + ' <button type="button" class="close" data-dismiss="alert">&times;</button> <div>');
                    }
                }
            });
        }
        $('.popover').popover('hide');
    });


   $(document).on('click', '.wishlist-close', function() { 
		$('.popover').popover('hide');
	});

</script>
