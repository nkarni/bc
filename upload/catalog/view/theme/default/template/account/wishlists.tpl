<?php echo $header; ?>
<script src="catalog/view/javascript/wishlists/bootswitch/js/bootstrap-switch.min.js" type="text/javascript"></script>
<link href="catalog/view/javascript/wishlists/bootswitch/css/bootstrap2/bootstrap-switch.min.css" rel="stylesheet" media="screen" />

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
        <!--
      <h2><?php echo $heading_title; ?></h2>
        -->

      <?php if ($wishlists) { ?>
        <div class="panel">
      <table class="table my-wishlist-table">
        <thead>
        <tr>
            <th class="text-left"><?php echo $column_name; ?></th>
            <th class="text-right"><?php echo $column_visiblity; ?></th>
            <th class="text-center"><?php echo $column_purchasecount; ?></th>
            <th class="text-right"><?php // echo $column_action; ?></th>
        </tr>
        </thead>
        <tbody>
          <?php foreach ($wishlists as $wishlist) { ?>
          <tr>
            <td class="text-left"><a href="<?php echo $wishlist['href']; ?>"><?php echo $wishlist['wishlist_name']; ?></a></td>

            <td class="text-right">
                <input type="checkbox" name="visiblity"  class="wishlist_visiblity" data-on-color="success" data-off-color="danger" onText="Yes" offText="No" <?php echo ($wishlist['visiblity'] == 1)?"checked":""; ?>  wishlist_id="<?php echo $wishlist['wishlist_id']; ?>">
            </td>


              <td class="text-center">
                  <span class="count-text" ><?php echo $wishlist['purchasecount']; ?></span>
              </td>

            <td class="text-right">
              <a href="<?php echo $wishlist['remove']; ?>" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn-sm btn-default"><i class="fa fa-times-circle" style="color:#CE2727;"></i></a></td>

          </tr>
          <?php } ?>
        </tbody>
      </table>
        </div>
      <?php } else { ?>
      <p><?php echo $text_empty; ?></p>
      <?php } ?>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>
<script>
    $(document).ready(function(){
        // Add switch
        $("[name='visiblity']").bootstrapSwitch();

        $('input[name="visiblity"]').on('switchChange.bootstrapSwitch', function(event, state) {

            $statevalue = $(this).is(":checked") ? $(this).val() : null;
            $currentvalue = ($statevalue == "on")?1:0;

            $wishlist_id = $(this).attr('wishlist_id');

            $.ajax({
                url: 'index.php?route=account/wishlists/edit',
                type: 'post',
                data: 'wishlist_id=' + $wishlist_id + '&visiblity=' + $currentvalue,
                dataType: 'json',
                success: function(json) {

                    $('.alert, .text-danger').remove();

                    if (json['redirect']) {
                        location = json['redirect'];
                    }

                    if (json['success']) {
                        $('#content').parent().before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

                        // Need to set timeout otherwise it wont update the total

                        $('html, body').animate({ scrollTop: 0 }, 'slow');
                    }
                }
            });
        });

    });
</script>
