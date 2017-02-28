<?php echo $header; ?>
<div class="contact-us container">
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>">
      <div class="contact-us-info">
        <?php echo isset($article) ? $article : ''; ?>
      </div>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
        <fieldset>
          <legend><?php echo $text_contact; ?></legend>
          <div class="form-group required">
            <div class="col-md-4 col-sm-6 col-xs-12">
              <input type="text" name="name" value="<?php echo $name; ?>" id="input-name" placeholder="First and Last name *" />
              <?php if ($error_name) { ?>
              <div class="text-danger"><?php echo $error_name; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <div class="col-md-4 col-sm-6 col-xs-12">
              <input type="text" name="email" value="<?php echo $email; ?>" id="input-email" placeholder="Email *" />
              <?php if ($error_email) { ?>
              <div class="text-danger"><?php echo $error_email; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <div class="col-md-4 col-sm-6 col-xs-12">
              <textarea name="enquiry" rows="10" id="input-enquiry" placeholder="Question"><?php echo $enquiry; ?></textarea>
              <?php if ($error_enquiry) { ?>
              <div class="text-danger"><?php echo $error_enquiry; ?></div>
              <?php } ?>
            </div>
          </div>
          <?php echo $captcha; ?>
        </fieldset>
        <div class="buttons">
          <button class="btn btn-primary" type="submit"><?php echo $button_submit; ?></button>
        </div>
      </form>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>
