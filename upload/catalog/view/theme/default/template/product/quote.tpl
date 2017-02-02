<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
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

      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
        <fieldset>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
            <div class="col-sm-10">
              <input type="text" name="name" value="<?php echo $name; ?>" id="input-name" class="form-control" />
              <?php if ($error_name) { ?>
              <div class="text-danger"><?php echo $error_name; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_email; ?></label>
            <div class="col-sm-10">
              <input type="text" name="email" value="<?php echo $email; ?>" id="input-email" class="form-control" />
              <?php if ($error_email) { ?>
              <div class="text-danger"><?php echo $error_email; ?></div>
              <?php } ?>
            </div>
          </div>
        <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-address1"><?php echo $entry_address1; ?></label>
            <div class="col-sm-10">
              <input type="text" name="address1" value="<?php echo $address1; ?>" id="input-address1" class="form-control" />
              <?php if ($error_address1) { ?>
              <div class="text-danger"><?php echo $error_address1; ?></div>
              <?php } ?>
            </div>
            <div class="col-sm-10">
              <input type="text" name="address2" value="<?php echo $address2; ?>" id="input-address2" class="form-control" />
              <?php if ($error_address2) { ?>
              <div class="text-danger"><?php echo $error_address2; ?></div>
              <?php } ?>
            </div>
            <div class="col-sm-10">
              <input type="text" name="city" value="<?php echo $city; ?>" id="input-city" class="form-control" />
              <?php if ($error_city) { ?>
              <div class="text-danger"><?php echo $error_city; ?></div>
              <?php } ?>
            </div>
            <div class="col-sm-10">
              <input type="text" name="postcode" value="<?php echo $postcode; ?>" id="input-postcode" class="form-control" />
              <?php if ($error_postcode) { ?>
              <div class="text-danger"><?php echo $error_postcode; ?></div>
              <?php } ?>
            </div>
            <div class="col-sm-10">
              <input type="text" name="country" value="<?php echo $country; ?>" id="input-country" class="form-control" />
              <?php if ($error_country) { ?>
              <div class="text-danger"><?php echo $error_country; ?></div>
              <?php } ?>
            </div>
          </div>
            
           <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-phone"><?php echo $entry_phone; ?></label>
            <div class="col-sm-10">
              <input type="text" name="phone" value="<?php echo $phone; ?>" id="input-phone" class="form-control" />
              <?php if ($error_phone) { ?>
              <div class="text-danger"><?php echo $error_phone; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-quote"><?php echo $entry_quote; ?></label>
            <div class="col-sm-10">
              <textarea name="quote" rows="10" id="input-quote" class="form-control">
                <?php
                    echo str_replace('<br>', "\n", $quote);
                ?>
              </textarea>
              <?php if ($error_quote) { ?>
              <div class="text-danger"><?php echo $error_quote; ?></div>
              <?php } ?>
            </div>
          </div>
        </fieldset>
        <div class="buttons">
          <div class="pull-right">
            <input class="btn btn-primary" type="submit" value="<?php echo $button_submit; ?>" />
          </div>
        </div>
      </form>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>