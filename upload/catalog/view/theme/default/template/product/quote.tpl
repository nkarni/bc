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

      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal quotereq">
        <fieldset>
          <div class="form-group required">
            <label class="col-sm-12 control-label" for="input-name"><?php echo $entry_name; ?></label>
            <div class="col-sm-12">
              <input type="text" name="name" value="<?php echo $name; ?>" id="input-name" class="form-control" />
              <?php if ($error_name) { ?>
              <div class="text-danger"><?php echo $error_name; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-12 control-label" for="input-email"><?php echo $entry_email; ?></label>
            <div class="col-sm-12">
              <input type="text" name="email" value="<?php echo $email; ?>" id="input-email" class="form-control" />
              <?php if ($error_email) { ?>
              <div class="text-danger"><?php echo $error_email; ?></div>
              <?php } ?>
            </div>
          </div>
        <div class="form-group required">
            <label class="col-sm-12 control-label" for="input-address1"><?php echo $entry_address1; ?></label>
            <div class="col-sm-12 address1">
              <input type="text" name="address1" value="<?php echo $address1; ?>" id="input-address1" placeholder="Address" class="form-control" />
              <?php if ($error_address1) { ?>
              <div class="text-danger"><?php echo $error_address1; ?></div>
              <?php } ?>
            </div>
		</div>
		<div class="form-group">	
            <div class="col-sm-12">
              <input type="text" name="address2" value="<?php echo $address2; ?>" id="input-address2" placeholder="Street" class="form-control" />
              <?php if ($error_address2) { ?>
              <div class="text-danger"><?php echo $error_address2; ?></div>
              <?php } ?>
            </div>
		</div>
		<div class="form-group">	
            <div class="col-sm-12">
              <input type="text" name="city" value="<?php echo $city; ?>" id="input-city" placeholder="City" class="form-control" />
              <?php if ($error_city) { ?>
              <div class="text-danger"><?php echo $error_city; ?></div>
              <?php } ?>
            </div>
		</div>
		<div class="form-group">	
            <div class="col-sm-12">
              <input type="text" name="postcode" value="<?php echo $postcode; ?>" id="input-postcode" placeholder="Postcode" class="form-control" />
              <?php if ($error_postcode) { ?>
              <div class="text-danger"><?php echo $error_postcode; ?></div>
              <?php } ?>
            </div>
		</div>
		<div class="form-group">	
            <div class="col-sm-12">
			  <div class="styled_select">
					<select name="country" id="input-country" class="form-control">
						<option value="">Please Select</option>
						<?php foreach ($countries as $countryRecord) { ?>
						<?php if ($countryRecord['name'] == $country) { ?>
						<option value="<?php echo $countryRecord['name']; ?>" selected="selected"><?php echo $countryRecord['name']; ?></option>
						<?php } else { ?>
						<option value="<?php echo $countryRecord['name']; ?>"><?php echo $countryRecord['name']; ?></option>
						<?php } ?>
						<?php } ?>
					</select>

              <?php if ($error_country) { ?>
              <div class="text-danger"><?php echo $error_country; ?></div>
              <?php } ?>
            </div>
		</div>	
            
           <div class="form-group required">
            <label class="col-sm-12 control-label" for="input-phone"><?php echo $entry_phone; ?></label>
            <div class="col-sm-12">
              <input type="text" name="phone" value="<?php echo $phone; ?>" id="input-phone" class="form-control" />
              <?php if ($error_phone) { ?>
              <div class="text-danger"><?php echo $error_phone; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-12 control-label" for="input-quote"><?php echo $entry_quote; ?></label>
            <div class="col-sm-12">
              <textarea name="quote" rows="10" id="input-quote" class="form-control"><?php if (strpos((string)$quote, '!!') !== false) { echo trim(str_replace("!!","\r\n",$quote));}else{ echo trim($quote);}?></textarea>
              <?php if ($error_quote) { ?>
              <div class="text-danger"><?php echo $error_quote; ?></div>
              <?php } ?>
            </div>
          </div>
        </fieldset>
        <div class="buttons">
          <div class="pull-left">
            <input class="btn btn-primary submit" type="submit" value="<?php echo $button_submit; ?>" />
          </div>
        </div>
      </form>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>
