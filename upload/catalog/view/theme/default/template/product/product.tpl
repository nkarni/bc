<?php echo $header; ?>
<div class="container">
  <?php if (isset($category_crumbs) && sizeOf($category_crumbs)>0) {
  $last_cat =  end($category_crumbs);
  ?>
  <div class="row">
    <div class="col-sm-12">
      <ul class="breadcrumb pull-left">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>

  <div class="row inner-category">
    <?php
         foreach($category_crumbs as $category){ //$category['image'] = 'http://bcdev.utopiacreative.com/image/cache/catalog/category/ergofit-hero-750x550-cr-320x200.jpg'; ?>
    <div class="col-md-2 col-sm-4 col-xs-4 sub-category cat-breadcrumbs">
      <div class="image_wrapper">
        <a href="<?php echo $category['href'] ?>">
          <img src="<?php echo $category['image'] ?>" class="attachment-home_category size-home_category"
               alt="<?php $category['name'] ?>" height="200" width="320">
        </a>
      </div>

      <div class="title <?php echo ($last_cat['category_id'] == $category['category_id']) ? 'active' : ''; ?>">
        <p>
          <a href="<?php echo $category['href'] ?>"><?php echo $category['name'] ?></a>
        </p>
      </div>
    </div>
    <?php } ?>
  </div>
  <?php } else { // if category breadcrumbs ?>
  <ul class="breadcrumb pull-left">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php } ?>

  <?php if (isset($alert_success) && $alert_success) { ?>
  <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $alert_success; ?>
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

      <div class="row">
        <?php if ($column_left || $column_right) { ?>
        <?php $class = 'col-sm-6'; ?>
        <?php } else { ?>
        <?php $class = 'col-sm-6'; ?>
        <?php } ?>
        <div class="<?php echo $class; ?>">
          <?php if ($thumb || $images) { ?>
          <ul class="thumbnails">
            <?php if ($thumb) { ?>
            <li class="image-main"><a class="thumbnail" href="<?php echo $popup; ?>"
                                      title="<?php echo $heading_title; ?>"><img src="<?php echo $popup; ?>"
                                                                                 title="<?php echo $heading_title; ?>"
                                                                                 alt="<?php echo $heading_title; ?>"/></a>
            </li>
            <li class="image-additional"><a class="thumbnail" href="<?php echo $popup; ?>"
                                            title="<?php echo $heading_title; ?>"><img
                        src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>"
                        alt="<?php echo $heading_title; ?>"/></a></li>
            <?php } ?>
            <?php if ($images) { ?>
            <?php foreach ($images as $image) { ?>
            <li class="image-additional"><a class="thumbnail" href="<?php echo $image['popup']; ?>"
                                            title="<?php echo $heading_title; ?>"> <img
                        src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>"
                        alt="<?php echo $heading_title; ?>"/></a></li>
            <?php } ?>
            <?php } ?>
          </ul>
          <?php } ?>

        </div>
        <?php if ($column_left || $column_right) { ?>
        <?php $class = 'col-sm-6'; ?>
        <?php } else { ?>
        <?php $class = 'col-sm-6'; ?>
        <?php } ?>
        <div class="<?php echo $class; ?>">
          <h1><?php echo $heading_title; ?></h1>
          <p class="text-justify"><?php echo $short_description; ?></p>
          <ul class="list-unstyled">
            <?php if ($manufacturer) { ?>
            <li><?php echo $text_manufacturer; ?> <a
                      href="<?php echo $manufacturers; ?>"><?php echo $manufacturer; ?></a></li>
            <?php } ?>
            <?php if($model){ ?>
            <li><?php echo $text_model; ?> <?php echo $model; ?></li>
            <?php } ?>
            <?php if ($reward) { ?>
            <li><?php echo $text_reward; ?> <?php echo $reward; ?></li>
            <?php } ?>
          </ul>

          <?php if ($sku) { ?>
          <p>Product Code: <?php echo $sku; ?></p>
          <?php } ?>

          <div class="panel-group" id="accordion">
            <div class="panel ">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#build-options"><i class="fa fa-chevron-right"></i>BUILD /
                    OPTIONS</a>
                </h4>
              </div>
              <div id="build-options" class="panel-collapse collapse">
                <div class="panel-body">
                  <div id="product">
                    <?php if ($options) { ?>
                    <?php foreach ($options as $option) { ?>
                    <?php if ($option['type'] == 'select') { ?>
                    <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                      <label class="control-label" id="label-option-<?php echo $option['product_option_id']; ?>"
                             for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
                      <select name="option[<?php echo $option['product_option_id']; ?>]"
                              id="input-option<?php echo $option['product_option_id']; ?>"
                              class="form-control">
                        <option value=""><?php echo $text_select; ?></option>
                        <?php foreach ($option['product_option_value'] as $option_value) { ?>
                        <option value="<?php echo $option_value['product_option_value_id']; ?>"
                                data-price="<?php echo $option_value['price_value'] ?: ''; ?>"><?php echo $option_value['name']; ?>
                          <?php if ($option_value['price'] && $showOptionPrices) { ?>
                          (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>
                          )
                          <?php } ?>
                        </option>
                        <?php } ?>
                      </select>
                    </div>
                    <?php } ?>
                    <?php if ($option['type'] == 'radio') { ?>
                    <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                      <label class="control-label"><?php echo $option['name']; ?></label>
                      <div id="input-option<?php echo $option['product_option_id']; ?>">
                        <?php foreach ($option['product_option_value'] as $option_value) { ?>
                        <div class="radio">
                          <label>
                            <input type="radio"
                                   name="option[<?php echo $option['product_option_id']; ?>]"
                                   value="<?php echo $option_value['product_option_value_id']; ?>"/>
                            <?php if ($option_value['image']) { ?>
                            <img src="<?php echo $option_value['image']; ?>"
                                 alt="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>"
                                 class="img-thumbnail"/>
                            <?php } ?>
                            <?php echo $option_value['name']; ?>
                            <?php if ($option_value['price']) { ?>
                            (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>
                            )
                            <?php } ?>
                          </label>
                        </div>
                        <?php } ?>
                      </div>
                    </div>
                    <?php } ?>
                    <?php if ($option['type'] == 'checkbox') { ?>
                    <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                      <label class="control-label"><?php echo $option['name']; ?></label>
                      <div id="input-option<?php echo $option['product_option_id']; ?>">
                        <?php foreach ($option['product_option_value'] as $option_value) { ?>
                        <div class="checkbox">
                          <label>
                            <input type="checkbox"
                                   name="option[<?php echo $option['product_option_id']; ?>][]"
                                   value="<?php echo $option_value['product_option_value_id']; ?>"/>
                            <?php if ($option_value['image']) { ?>
                            <img src="<?php echo $option_value['image']; ?>"
                                 alt="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>"
                                 class="img-thumbnail"/>
                            <?php } ?>
                            <?php echo $option_value['name']; ?>
                            <?php if ($option_value['price']) { ?>
                            (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>
                            )
                            <?php } ?>
                          </label>
                        </div>
                        <?php } ?>
                      </div>
                    </div>
                    <?php } ?>
                    <?php if ($option['type'] == 'text') { ?>
                    <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                      <label class="control-label"
                             for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
                      <input type="text"
                             name="option[<?php echo $option['product_option_id']; ?>]"
                             value="<?php echo $option['value']; ?>"
                             placeholder="<?php echo $option['name']; ?>"
                             id="input-option<?php echo $option['product_option_id']; ?>"
                             class="form-control"/>
                    </div>
                    <?php } ?>
                    <?php if ($option['type'] == 'textarea') { ?>
                    <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                      <label class="control-label"
                             for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
                      <textarea name="option[<?php echo $option['product_option_id']; ?>]"
                                rows="5" placeholder="<?php echo $option['name']; ?>"
                                id="input-option<?php echo $option['product_option_id']; ?>"
                                class="form-control"><?php echo $option['value']; ?></textarea>
                    </div>
                    <?php } ?>
                    <?php if ($option['type'] == 'file') { ?>
                    <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                      <label class="control-label"><?php echo $option['name']; ?></label>
                      <button type="button"
                              id="button-upload<?php echo $option['product_option_id']; ?>"
                              data-loading-text="<?php echo $text_loading; ?>"
                              class="btn btn-default btn-block"><i
                                class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
                      <input type="hidden"
                             name="option[<?php echo $option['product_option_id']; ?>]" value=""
                             id="input-option<?php echo $option['product_option_id']; ?>"/>
                    </div>
                    <?php } ?>
                    <?php if ($option['type'] == 'date') { ?>
                    <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                      <label class="control-label"
                             for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
                      <div class="input-group date">
                        <input type="text"
                               name="option[<?php echo $option['product_option_id']; ?>]"
                               value="<?php echo $option['value']; ?>"
                               data-date-format="YYYY-MM-DD"
                               id="input-option<?php echo $option['product_option_id']; ?>"
                               class="form-control"/>
                        <span class="input-group-btn">
                <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                </span></div>
                    </div>
                    <?php } ?>
                    <?php if ($option['type'] == 'datetime') { ?>
                    <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                      <label class="control-label"
                             for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
                      <div class="input-group datetime">
                        <input type="text"
                               name="option[<?php echo $option['product_option_id']; ?>]"
                               value="<?php echo $option['value']; ?>"
                               data-date-format="YYYY-MM-DD HH:mm"
                               id="input-option<?php echo $option['product_option_id']; ?>"
                               class="form-control"/>
                        <span class="input-group-btn">
                <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                </span></div>
                    </div>
                    <?php } ?>
                    <?php if ($option['type'] == 'time') { ?>
                    <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                      <label class="control-label"
                             for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
                      <div class="input-group time">
                        <input type="text"
                               name="option[<?php echo $option['product_option_id']; ?>]"
                               value="<?php echo $option['value']; ?>" data-date-format="HH:mm"
                               id="input-option<?php echo $option['product_option_id']; ?>"
                               class="form-control"/>
                        <span class="input-group-btn">
                <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                </span></div>
                    </div>
                    <?php } ?>
                    <?php } ?>
                    <?php } ?>
                    <?php if ($recurrings) { ?>
                    <hr>
                    <h3><?php echo $text_payment_recurring; ?></h3>
                    <div class="form-group required">
                      <select name="recurring_id" class="form-control">
                        <option value=""><?php echo $text_select; ?></option>
                        <?php foreach ($recurrings as $recurring) { ?>
                        <option value="<?php echo $recurring['recurring_id']; ?>"><?php echo $recurring['name']; ?></option>
                        <?php } ?>
                      </select>
                      <div class="help-block" id="recurring-description"></div>
                    </div>
                    <?php } ?>

                    <div class="floating-cart">
                      <div class="ghost-container hidden">
                        <h4><?php echo $heading_title; ?></h4>
                        <div class="ghost-wrapper">

                          <?php if ($options) {  $i = 1 ;?>
                            <?php foreach ($options as $option) {  $i++; ?>
                              <div class="ghost-input-option<?php echo $option['product_option_id']; ?> ghost-option <?php echo (($i % 2) == 0 ? 'white-bg' : '') ; ?>">
                                    <div><strong><?php echo $option['name']; ?></strong></div>
                                    <div class="ghost-selected-input-option<?php echo $option['product_option_id']; ?>"></div>
                                    <div class="ghost-selected-price-input-option<?php echo $option['product_option_id']; ?>"></div>
                              </div>
                            <?php } ?>
                          <?php } ?>
                        </div>
                      </div>

                      <div class="text-center">
                        <?php // if($price_amount > 0){ ?>
                        <ul class="list-unstyled hidden">
                          <?php if (!$special) { ?>
                          <li>
                            <h2 id="product-price"
                                data-base-price="<?php echo $price_amount; ?>"><?php echo $price; ?></h2>
                          </li>
                          <?php } else { ?>
                          <li>
                            <span style="text-decoration: line-through;"><?php echo $price; ?></span>
                          </li>
                          <li>
                            <h2><?php echo $special; ?></h2>
                          </li>
                          <?php } ?>
                          <?php if ($points) { ?>
                          <li><?php echo $text_points; ?> <?php echo $points; ?></li>
                          <?php } ?>
                          <?php if ($discounts) { ?>
                          <li>
                            <hr>
                          </li>
                          <?php foreach ($discounts as $discount) { ?>
                          <li><?php echo $discount['quantity']; ?><?php echo $text_discount; ?><?php echo $discount['price']; ?></li>
                          <?php } ?>
                          <?php } ?>
                        </ul>

                        <div class="qty white-bg">
                          <label class="control-label qty-label" for="input-quantity"><?php echo $entry_qty; ?></label>
                          <div class="input-group qty-input-group" >
                            <span class="input-group-btn ">
                              <button type="button" class="btn dark-grey-bg btn-number"  data-type="minus" data-field="quant[2]">
                                  <span class="glyphicon glyphicon-minus"></span>
                              </button>
                            </span>
                            <input type="text" id="input-quantity" name="quant[2]" class="form-control dark-grey-bg input-number" value="1" min="1" max="9999" >
                            <span class="input-group-btn">
                              <button type="button" class="btn dark-grey-bg btn-number" data-type="plus" data-field="quant[2]">
                                  <span class="glyphicon glyphicon-plus"></span>
                              </button>
                            </span>
                          </div>



                        </div>

                        <button type="button" id="button-cart" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary btn-lg btn-block hidden">ADD TO ORDER</button>
                        <?php // } ?>

                        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>"/><input type="hidden" id="customer-id" name="customer-id" value="<?php echo $customer_id; ?>"/>
                        <input type="hidden" name="default_open_moreinfo" id="default_open_moreinfo" value="<?php echo $default_open_moreinfo; ?>"/>
                        <div class="text-left">
                          <button class="wishlist-btn btn-primary btn-lg btn-block" title="<?php echo $button_wishlist; ?>" product="<?php echo $product_id; ?>" type="button">ADD TO WISHLIST</button>
                          <button type="button"
                                  data-loading-text="<?php echo $text_loading; ?>"
                                  class="request-quote btn btn-primary btn-lg btn-block">REQUEST A QUOTE</button>
                          <div class=" row quote-request-wrapper ">
                            <div class="col-sm-12"><textarea rows="10" id="quote-request-notes" placeholder="Please provide any other relevant information and submit." class="form-control"></textarea>
                            </div>
                            <div class="col-sm-12">
                              <button class="btn-primary btn-lg btn-block" title="Submit" product="<?php echo $product_id; ?>" id="send-quote-request" data-product="<?php echo $product_id; ?>" type="button">SUBMIT</button>
                              <button class="btn-primary btn-lg btn-block" title="Cancel" id="send-quote-cancel" type="button">CANCEL</button>
                            </div>
                          </div>
                          <button type="button"  id="btn-compare" data-toggle="tooltip" class="btn btn-primary btn-lg btn-block" data-product="<?php echo $product_id; ?>"  title="<?php echo $button_compare; ?>">ADD TO COMPARISON</button>
                        </div>

                      </div>


                    </div>

                  </div>
                </div>
              </div>

              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#request-info"><i class="fa fa-chevron-right"></i>REQUEST MORE
                    INFORMATION</a>
                </h4>
              </div>
              <div id="request-info" class="panel-collapse collapse">
                <div class="panel-body">
                  <div id="more_info_div">
                    <?php if ($customer_id > 0) { ?>

                    <div class=" row more-info-request-wrapper ">
                      <div class="col-sm-12"><textarea rows="10" id="more-info-request-notes" placeholder="Please provide any other relevant information and submit." class="form-control"></textarea>
                      </div>
                      <div class="col-sm-12">
                        <button class="btn-primary btn-lg btn-block" title="Submit" product="<?php echo $product_id; ?>" id="send-more-info-request" data-product="<?php echo $product_id; ?>" type="button">SUBMIT</button>
                        <button class="btn-primary btn-lg btn-block" title="Cancel" id="send-more-info-cancel" type="button">CANCEL</button>
                      </div>
                    </div>

                    <?php } else { ?>

                   Please <a href="/index.php?route=account/login" class="normal-link">Click Here</a> to register or login.

                    <?php } ?>

                  </div>
                </div>
              </div>

              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#desc"><i class="fa fa-chevron-right"></i>DESCRIPTION</a>
                </h4>
              </div>
              <div id="desc" class="panel-collapse collapse">
                <div class="panel-body">
                  <?php echo $description; ?>
                </div>
              </div>

              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#specs"><i class="fa fa-chevron-right"></i>SPECIFICATIONS</a>
                </h4>
              </div>
              <?php if ($specifications) { ?>
              <div id="specs" class="panel-collapse collapse">
                <div class="panel-body">
                  <div class="tab-pane" id="tab-specifications"><?php echo $specifications; ?></div>
                </div>
              </div>
              <?php } ?>

              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#features"><i class="fa fa-chevron-right"></i>FEATURES</a>
                </h4>
              </div>
              <?php if ($features) { ?>
              <div id="features" class="panel-collapse collapse">
                <div class="panel-body">
                  <div class="tab-pane" id="tab-features"><?php echo $features; ?></div>
                </div>
              </div>
              <?php } ?>
            </div>

          </div>


          <?php if ($review_status) { ?>
          <div class="rating">
            <p>
              <?php for ($i = 1; $i <= 5; $i++) { ?>
              <?php if ($rating < $i) { ?>
              <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-1x"></i></span>
              <?php } else { ?>
              <span class="fa fa-stack"><i class="fa fa-star fa-stack-1x"></i><i
                        class="fa fa-star-o fa-stack-1x"></i></span>
              <?php } ?>
              <?php } ?>
              <a href=""
                 onclick="$('a[href=\'#tab-review\']').trigger('click'); return false;"><?php echo $reviews; ?></a>
              / <a href=""
                   onclick="$('a[href=\'#tab-review\']').trigger('click'); return false;"><?php echo $text_write; ?></a>
            </p>
            <hr>
            <!-- AddThis Button BEGIN -->
            <div class="addthis_toolbox addthis_default_style" data-url="<?php echo $share; ?>"><a
                      class="addthis_button_facebook_like" fb:like:layout="button_count"></a> <a
                      class="addthis_button_tweet"></a> <a class="addthis_button_pinterest_pinit"></a> <a
                      class="addthis_counter addthis_pill_style"></a></div>
            <script type="text/javascript"
                    src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-515eeaf54693130e"></script>
            <!-- AddThis Button END -->
          </div>
          <?php } ?>
        </div>
      </div>

      <div id="product_submenu">
        <ul class="list-unstyled">
          <li><a data-action="build" href="javascript:$('a[href=#build-options]').click();"><i class="fa fa-chevron-right"></i> Build</a></li>
          <li><a data-action="desc" href="javascript:$('a[href=#desc]').click();"><i class="fa fa-chevron-right"></i> Description</a></li>
          <li><a data-action="request_info" href="javascript:$('a[href=#request-info]').click();"><i class="fa fa-chevron-right"></i> Request More Information</a></li>
          <li><a data-action="back_to_top" href="javascript: backToTop();"><i class="fa fa-chevron-up"></i> Back To Top</a></li>
        </ul>
      </div>



      <?php if ($products) { ?>
      <h3 class="related"><?php echo $text_related; ?></h3>
      <div class="row">
        <?php $i = 0; ?>
        <?php foreach ($products as $product) { ?>
        <?php if ($column_left && $column_right) { ?>
        <?php $class = 'col-xs-8 col-sm-6 col-lg-2'; ?>
        <?php } elseif ($column_left || $column_right) { ?>
        <?php $class = 'col-xs-6 col-md-4 col-lg-2'; ?>
        <?php } else { ?>
        <?php $class = 'col-xs-6 col-sm-3 col-lg-2'; ?>
        <?php } ?>
        <div class="<?php echo $class; ?>">
          <div class="product-thumb transition" style="border: none !important;">
            <div class="image"><a href="<?php echo $product['href']; ?>"><img
                        src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>"
                        title="<?php echo $product['name']; ?>" class="img-responsive"/></a></div>
            <div class="caption" style="padding: 0px;">
              <h4 style="font-size: 14px">
                <a href="<?php echo $product['href']; ?>">
                  <p style="" class="related-category"><?php echo $product['category_name']; ?></p>
                  <p class="related-name"><?php echo $product['name']; ?></p>
                </a>
              </h4>
            </div>
          </div>
        </div>
        <?php if (($column_left && $column_right) && (($i+1) % 2 == 0)) { ?>
        <div class="clearfix visible-md visible-sm"></div>
        <?php } elseif (($column_left || $column_right) && (($i+1) % 3 == 0)) { ?>
        <div class="clearfix visible-md"></div>
        <?php } elseif (($i+1) % 4 == 0) { ?>
        <div class="clearfix visible-md"></div>
        <?php } ?>
        <?php $i++; ?>
        <?php } ?>
      </div>
      <?php } ?>

      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<script type="text/javascript"><!--
    $('select[name=\'recurring_id\'], input[name="quantity"]').change(function () {
        $.ajax({
            url: 'index.php?route=product/product/getRecurringDescription',
            type: 'post',
            data: $('input[name=\'product_id\'], input[name=\'quantity\'], select[name=\'recurring_id\']'),
            dataType: 'json',
            beforeSend: function () {
                $('#recurring-description').html('');
            },
            success: function (json) {
                $('.alert, .text-danger').remove();

                if (json['success']) {
                    $('#recurring-description').html(json['success']);
                }
            }
        });
    });


    $('#button-cart').on('click', function () {
        $.ajax({
            url: 'index.php?route=checkout/cart/add',
            type: 'post',
            data: $('#product input[type=\'text\'], #product input[type=\'hidden\'], #product input[type=\'radio\']:checked, #product input[type=\'checkbox\']:checked, #product select, #product textarea'),
            dataType: 'json',
            beforeSend: function () {
                $('#button-cart').button('loading');
            },
            complete: function () {
                $('#button-cart').button('reset');
            },
            success: function (json) {
                $('.alert, .text-danger').remove();
                $('.form-group').removeClass('has-error');

                if (json['error']) {
                    if (json['error']['option']) {
                        for (i in json['error']['option']) {
                            var element = $('#input-option' + i.replace('_', '-'));

                            if (element.parent().hasClass('input-group')) {
                                element.parent().after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
                            } else {
                                element.after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
                            }
                        }
                    }

                    if (json['error']['recurring']) {
                        $('select[name=\'recurring_id\']').after('<div class="text-danger">' + json['error']['recurring'] + '</div>');
                    }

                    // Highlight any found errors
                    $('.text-danger').parent().addClass('has-error');
                }

                if (json['success']) {
                    $('.breadcrumb').after('<div class="alert alert-success">' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

                    $('#cart > button').html('<span id="cart-total"><i class="fa fa-shopping-cart"></i> ' + json['total'] + '</span>');

                    $('html, body').animate({scrollTop: 0}, 'slow');

                    $('#cart > ul').load('index.php?route=common/cart/info ul li');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alertHandler.error(xhr, ajaxOptions, thrownError);
            }
        });
    });


    $('.date').datetimepicker({
        pickTime: false
    });

    $('.datetime').datetimepicker({
        pickDate: true,
        pickTime: true
    });

    $('.time').datetimepicker({
        pickDate: false
    });

    $('button[id^=\'button-upload\']').on('click', function () {
        var node = this;

        $('#form-upload').remove();

        $('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

        $('#form-upload input[name=\'file\']').trigger('click');

        if (typeof timer != 'undefined') {
            clearInterval(timer);
        }

        timer = setInterval(function () {
            if ($('#form-upload input[name=\'file\']').val() != '') {
                clearInterval(timer);

                $.ajax({
                    url: 'index.php?route=tool/upload',
                    type: 'post',
                    dataType: 'json',
                    data: new FormData($('#form-upload')[0]),
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        $(node).button('loading');
                    },
                    complete: function () {
                        $(node).button('reset');
                    },
                    success: function (json) {
                        $('.text-danger').remove();

                        if (json['error']) {
                            $(node).parent().find('input').after('<div class="text-danger">' + json['error'] + '</div>');
                        }

                        if (json['success']) {
                            alert(json['success']);

                            $(node).parent().find('input').val(json['code']);
                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
            }
        }, 500);
    });

    $('.image-additional img').on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();

        var obj = $(this);
        var image = obj.attr('src');
        var thumbnail = obj.parents('a').attr('href');

        $('.image-main a.thumbnail').attr('href', image);
        $('.image-main a.thumbnail img').attr('src', thumbnail);
    });

    $('.thumbnail-small').on('click', function (e) {
        e.preventDefault();
        var obj = $(this);
        var imageSrc = obj.attr('href');

        var large = $('.thumbnail');
        var largeImageSrc = large.attr('href');

        large.attr('href', imageSrc);
        obj.attr('href', largeImageSrc);

        large.find('img').attr('src', imageSrc);
        obj.find('img').attr('src', largeImageSrc);
    });

    $(document).ready(function () {
        $('.thumbnail').magnificPopup({
            type: 'image',
        });
    });

    function allSelected(){
        var allSelected = true;

        $('#product select').each(function (idx, select) {
            var text = $(select).find('option:selected').text();
            var id = $(select).attr("id");
            var selectedIndex = document.getElementById(id).selectedIndex;
            if(selectedIndex == 0){
                allSelected = false;
            }
            $('.ghost-selected-' + id).html(text);
        });

        return allSelected;
    }

    function applySelectionToGhost(){
        if(allSelected()){
            $('.ghost-container').removeClass('hidden');
        }
    }

    function onProductSelectChange(){
        applySelectionToGhost();
        recalculateTotal();
    }

    function recalculateTotal() {

        var quantity = parseInt($('#input-quantity').val());
        if (!quantity)
            return;

        var basePrice = parseFloat($('#product-price').data('base-price'));
        if (!basePrice)
            return;

        $('#product select').each(function (idx, select) {
            var optionPrice = $(select.selectedOptions[0]).data('price');
            if (optionPrice) basePrice += optionPrice;
        });

        var total = Math.round(quantity * basePrice * 100) / 100;
        $('#product-price').text('$' + total.toLocaleString('en-AU', {minimumFractionDigits: 2}));
    }

    $(document).ready(function () {
        $('#product select').on('change', onProductSelectChange);
        $('#input-quantity').on('keyup', recalculateTotal);
    });

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
                    items.push('<button type="button" style="cursor:pointer;" class="list-group-item add-product-to-wishlist" wishlist=' + key + '>' + data + '<i class="fa fa-check-square-o label-icon-right"></i></button>');
                });
                if (items.length > 0)
                    $listitems = '<div class="list-group mywishlist-group">' + items.join('') + '</div>';
            }
        });
        return $listitems;
    }

    function getOptionsSelected(){
        // build the options field:
        var options = [];
        $('#product select').each(function (idx, select) {

            var text = $(select).find('option:selected').text();
            var id = $(select).attr("id");
            var optionId = id.slice(12, id.length);
            var selectedIndex = document.getElementById(id).selectedIndex;
            options.push( optionId + '":"' + $(select).val());

        });
        return JSON.stringify(options);
    }
    function getOptionsSelectedStrings(){
        // build the options field:
        var str = '';
        $('#product select').each(function (idx, select) {
            var id = $(select).attr("id");
            var optionId = id.slice(12, id.length);
            var text = $(select).find('option:selected').text();
            var label = $('#label-option-' + optionId).text();
            var selectedIndex = document.getElementById(id).selectedIndex;
            str += label + ': ' + text + '<br>'
        });
        return str;
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
        $product = $(this).parents('.popover-content').find("input.active_product_id").val();
        $wishlist = $(this).parent("span").parent("div").find("input#wishlist_name").val();
        var options = getOptionsSelected();
        var quantity = $('#input-quantity').val();

        if (!$wishlist.trim()) {
          alert("Please provide a name of your wishlist!");
          return;
        }

        $.ajax({
            url: 'index.php?route=account/wishlists/add',
            dataType: 'json',
            type: 'post',
            beforeSend: function() {
                $(".addlist").addClass('disabled');
            },
            complete: function() {
                $(".addlist").removeClass('disabled');
            },
            data: {
                'wishlist_name': $wishlist,
                'product_id': $product,
                'options': options,
                'quantity' : quantity
            },
            success: function (json) {
                alertHandler.success(json);
                //close popover window
                $('.popover').popover('hide');
            },

        });
    });

    // For closing popover
    $(document).click(function (e) {
        if ($(e.target).is('.close')) {
            $('.popover').popover('hide');
        }
    });

    // For add product to existing wishlist
    $(document).on("click", ".add-product-to-wishlist", function () {

        if(!allSelected()){
            $('.popover').popover('hide');
            alert('Please select all product options before adding to wishlist');
            return false;
        }

        // build the options field:

        options = getOptionsSelected();
        var quantity = $('#input-quantity').val();

        $wishlist = $(this).attr('wishlist');
        $product = $(this).parents('.popover-content').find("input.active_product_id").val();
        $.ajax({
            url: 'index.php?route=account/wishlists/add',
            dataType: 'json',
            type: 'post',
            data: {
                'wishlist_id': $wishlist,
                'product_id': $product,
                'options': options,
                'quantity' : quantity
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

    function isLoggedIn(){
        if($('#customer-id').val() > 0){
            return true;
        }
        return false;
    }

    // For wishlist form
    $('.wishlist-btn').click(function () {

        if(!isLoggedIn()){
            $('.alert').remove();
            $return = 'Please <a href="/index.php?route=account/login">Register or Login</a> before adding to wishlist';
            $('#content').parent().before('<div class="alert alert-danger"><i class="fa fa-info-circle"></i> ' + $return + ' <button type="button" class="close" data-dismiss="alert">&times;</button> <div>');
            $('html, body').animate({ scrollTop: 0 }, 'slow');
            return ;
        }

        if(allSelected()){
            $currentproduct = $(this).attr('product');
            $addbuttonhtml = '<div class="input-group"><input type="text" class="form-control" name="wishlist_name" id="wishlist_name" placeholder="Type a new wishlist name" ><span class="input-group-btn"><button type="button" product="' + $currentproduct + '" class="addlist btn btn-default" >ADD</button></span></div>';

            var placement = window.innerWidth < 768 ? 'bottom' : 'left';
            $(this).popover({
                html: true,
                trigger: 'manual',
                placement: placement,

                content: function () {
                    $buttons = getwishlists();
                    if ($buttons) $buttons += "<p>Or add a new Wish List:</p>";
                    $activeproductrow = '<input type="hidden" class="active_product_id" value="' + $currentproduct + '" />';
                    return $activeproductrow + $buttons + $addbuttonhtml;
                }
            }).popover('toggle');
        }else{
            alert('Please select all product options before adding to wishlist.')
        }

    });

    //For compare
    $('#btn-compare').click(function () {

        if(allSelected()){
            var product_id = $(this).attr('data-product');
            var options = getOptionsSelected();
            $.ajax({
                url: 'index.php?route=product/compare/add',
                dataType: 'json',
                type: 'post',
                data: {
                    'product_id': product_id,
                    'options': options
                },
                beforeSend: function() {
                    $("#btn-compare").addClass('disabled');
                },
                complete: function() {
                    $("#btn-compare").removeClass('disabled');
                },
                success: function(json) {
                  alertHandler.success(json);
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alertHandler.error(xhr, ajaxOptions, thrownError);
                }
            });
        }else{
            alert('Please select all product options before ading to comparison.')
        }
    });

    // For request a quote
    $('#send-quote-cancel').click(function () {
        $('.quote-request-wrapper').hide();
    });


    $('.request-quote').click(function () {
        if (!isLoggedIn()) {
            $('.alert').remove();
            $return = 'Please <a href="/index.php?route=account/login">Register or Login</a> before requesting a quote';
            $('#content').parent().before('<div class="alert alert-danger"><i class="fa fa-info-circle"></i> ' + $return + ' <button type="button" class="close" data-dismiss="alert">&times;</button> <div>');
            $('html, body').animate({ scrollTop: 0 }, 'slow');
            return;
        }

        if (!allSelected()) {
            alert('Please select all product options before requesting a quote.');
            return;
        }

        $('.quote-request-wrapper').toggle();
    });


    $('#send-more-info-request').click(function () {

            $product = $(this).attr('data-product');
            var notes = $('#more-info-request-notes').val();
            var options = getOptionsSelectedStrings();
            var quantity = $('#input-quantity').val();
            $.ajax({
                url: 'index.php?route=product/product/quote',
                dataType: 'json',
                type: 'post',
                data: {
                    'product_id': $product,
                    'options': options,
                    'quantity' : quantity,
                    'notes' : notes,
                    'trigger' : 'more-info'
                },
                beforeSend: function() {
                    $("#send-more-info-request").addClass('disabled');
                },
                complete: function() {
                    $("#send-more-info-request").removeClass('disabled');
                },
                success: function (json) {
                    alertHandler.success(json);
                    $('#more-info-request-notes').val('');
                    $('#request-info').removeClass('in');
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alertHandler.error(xhr, ajaxOptions, thrownError);
                }
            });


    });

    $('#send-more-info-cancel').click(function () {
        $('#more-info-request-notes').val('');
        $('#request-info').removeClass('in');
    });

    $('.btn-number').click(function(e){
        e.preventDefault();

        fieldName = $(this).attr('data-field');
        type      = $(this).attr('data-type');
        var input = $("input[name='"+fieldName+"']");
        var currentVal = parseInt(input.val());
        if (!isNaN(currentVal)) {
            if(type == 'minus') {

                if(currentVal > input.attr('min')) {
                    input.val(currentVal - 1).change();
                }
                if(parseInt(input.val()) == input.attr('min')) {
                    $(this).attr('disabled', true);
                }

            } else if(type == 'plus') {

                if(currentVal < input.attr('max')) {
                    input.val(currentVal + 1).change();
                }
                if(parseInt(input.val()) == input.attr('max')) {
                    $(this).attr('disabled', true);
                }

            }
        } else {
            input.val(0);
        }
    });
    $('.input-number').focusin(function(){
        $(this).data('oldValue', $(this).val());
    });
    $('.input-number').change(function() {

        minValue =  parseInt($(this).attr('min'));
        maxValue =  parseInt($(this).attr('max'));
        valueCurrent = parseInt($(this).val());

        name = $(this).attr('name');
        if(valueCurrent >= minValue) {
            $(".btn-number[data-type='minus'][data-field='"+name+"']").removeAttr('disabled')
        } else {
            alert('Sorry, the minimum value was reached');
            $(this).val($(this).data('oldValue'));
        }
        if(valueCurrent <= maxValue) {
            $(".btn-number[data-type='plus'][data-field='"+name+"']").removeAttr('disabled')
        } else {
            alert('Sorry, the maximum value was reached');
            $(this).val($(this).data('oldValue'));
        }


    });
    $(".input-number").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
            // Allow: Ctrl+A
            (e.keyCode == 65 && e.ctrlKey === true) ||
            // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
            // let it happen, don't do anything
            return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

    function backToTop() {
      $('html, body').animate({
        scrollTop: 0
      }, 500);
    }

    $(function() {
      $(document).on('scroll', function(e) { 
        if ($('body')[0].scrollTop > 119) {
          $('body').addClass("scrolled-after-header");
        } else {
          $('body').removeClass("scrolled-after-header");
        }
      });
    });
    --></script>
<?php echo $footer; ?>
