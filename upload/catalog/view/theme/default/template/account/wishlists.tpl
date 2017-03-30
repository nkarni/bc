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
    <div id="content" class="col-sm-12"><?php echo $content_top; ?>
        <!--
      <h2><?php echo $heading_title; ?></h2>
        -->

      <?php if ($wishlists) { ?>
        <div class="panel">
      <table class="table my-wishlist-table">
        <thead>
        <tr>
            <th class="text-left"><?php echo $column_name; ?></th>
            <th class="text-right"><?php // echo $column_action; ?></th>
        </tr>
        </thead>
        <tbody>
          <?php foreach ($wishlists as $wishlist) { ?>
          <tr>
            <td class="text-left"><a href="<?php echo $wishlist['href']; ?>"><?php echo $wishlist['wishlist_name']; ?></a></td>

            <td class="text-right">
              <a href="#" data-item-remove="<?php echo $wishlist['remove']; ?>" data-toggle="tooltip" style="background: #9b9b9b;border-radius: 0px;"  title="<?php echo $button_remove; ?>" class="btn-sm btn-default remove-wishlist"><i class="fa fa-times-circle" style="color:white;"></i></a></td>

          </tr>
          <?php } ?>
        </tbody>
      </table>
        </div>
      <?php } else { ?>
      <p><?php echo $text_empty; ?></p>
      <?php } ?>
      <?php echo $content_bottom; ?></div>
    <?php // echo $column_right; ?></div>
</div>
<?php echo $footer; ?>
<script>
    $(document).ready(function(){


        $(document).on('click', '.remove-wishlist', function() {
            if(confirm('Are you sure?')){
                var url = $(this).data('item-remove');
                console.log(url);
                window.location.href = url;
            }
        });


    });
</script>
