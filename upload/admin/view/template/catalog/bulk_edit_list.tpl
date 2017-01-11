<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
                <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-category"><?php echo $entry_category; ?></label>
                <input type="text" name="filter_category" value="<?php echo $filter_category; ?>" placeholder="<?php echo $entry_category; ?>" id="input-category" class="form-control" />
                <input type="hidden" name="filter_category_id" value="<?php echo $filter_category_id; ?>" />
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-filter"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered table-hover">
            <thead>
              <tr>
                <td class="text-left"><?php if ($sort == 'pd.name') { ?>
                  <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                  <?php } else { ?>
                  <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                  <?php } ?></td>
                <td class="text-right" width="20%"><?php if ($sort == 'p.price') { ?>
                  <a href="<?php echo $sort_price; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_price; ?></a>
                  <?php } else { ?>
                  <a href="<?php echo $sort_price; ?>"><?php echo $column_price; ?></a>
                  <?php } ?></td>
                <td class="text-right" width="100"><?php echo $column_action; ?></td>
              </tr>
            </thead>
            <tbody>
              <?php if ($products) { ?>
              <?php foreach ($products as $product) { ?>
              <tr data-id="<?php echo $product['product_id']; ?>" class="product-row">
                <td class="text-left" style="border-bottom: 0"><?php echo $product['name']; ?></td>
                <td class="text-right" style="border-bottom: 0">
                  <input type="text" class="form-control product-price" value="<?php echo $product['price']; ?>" data-current-value="<?php echo $product['price']; ?>">
                </td>
                <td class="text-right" style="border-bottom: 0">
                  <button data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default product-cancel"><i class="fa fa-times"></i></button>
                  <button data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary product-save"><i class="fa fa-save"></i></button>
                  <button data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary product-edit"><i class="fa fa-pencil"></i></button>
                </td>
              </tr>
              <tr data-id="<?php echo $product['product_id']; ?>" class="form-row" style="height: 0px">
              <td colspan="3" style="padding: 0; border-top: 0;"><div></div></td>
              </tr>
              <?php } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
 
  </div>
  <style type="text/css">
  .form-row > td > div {
    padding: 8px; 
    overflow: scroll;
    max-height: 60vh;
  }
  .product-row > td > .product-cancel,
  .product-row > td > .product-save {
    display: none;
  }
  .product-row.price-changed > td > .product-cancel,
  .product-row.price-changed > td > .product-save,
  .product-row.open > td > .product-cancel,
  .product-row.open > td > .product-save {
    display: inline;
  }

  .product-row.price-changed > td > .product-edit,
  .product-row.open > td > .product-edit {
    display: none;
  }
  </style>
  <script type="text/javascript"><!--
  $('tr.form-row td div').hide();
$('#button-filter').on('click', function() {
	var url = 'index.php?route=catalog/bulk_edit&token=<?php echo $token; ?>';

	var filter_name = $('input[name=\'filter_name\']').val();

	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}

	var filter_category = $('input[name=\'filter_category\']').val();

	if (filter_category) {
		url += '&filter_category=' + encodeURIComponent(filter_category);
	}

	var filter_category_id = $('input[name=\'filter_category_id\']').val();

	if (filter_category_id) {
		url += '&filter_category_id=' + parseInt(filter_category_id);
	}

	location = url;
});

$('input[name=\'filter_name\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['product_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_name\']').val(item['label']);
	}
});  

$('input[name=\'filter_category\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['category_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_category\']').val(item['label']);
		$('input[name=\'filter_category_id\']').val(item['value']);
	}
});  

$('button.product-cancel').on('click', function(e) {
  var id = $(e.target).parents('tr').data('id');
  $('tr.product-row[data-id=' + id + '].open input.product-price').prop('disabled', false);
  $('tr.product-row[data-id=' + id + '].open').removeClass('open');
  if ($('tr.product-row[data-id=' + id + '].price-changed').length) {
    $('tr.product-row[data-id=' + id + '].price-changed input.product-price').val($('tr.product-row.price-changed input.product-price').data('current-value'));
    $('tr.product-row[data-id=' + id + '].price-changed').removeClass('price-changed');
  }
  $('tr.form-row[data-id=' + id + '] td > div.open').html('').slideToggle().css('min-height', 0).removeClass('open');
});

$('button.product-edit').on('click', function(e) {
  var id = $(e.target).parents('tr').data('id');
  if ($('tr.product-row.price-changed').length) {
    $('tr.product-row.price-changed input.product-price').val($('tr.product-row.price-changed input.product-price').data('current-value'));
    $('tr.product-row.price-changed').removeClass('price-changed');
  }
  $('tr.product-row.open input.product-price').prop('disabled', false);
  $('tr.product-row.open').removeClass('open');
  $('tr.form-row td > div.open').html('').slideToggle().css('min-height', 0).removeClass('open');
  $.ajax({
    url: 'index.php?route=catalog/product/editInPlace&token=<?php echo $token; ?>&product_id=' + parseInt(id),
    dataType: 'html',
    success: function(html) {
      var prev_row = $('tr.form-row[data-id=' + id + ']').prev();
      var row = $('tr.form-row[data-id=' + id + '] td > div');
      prev_row.addClass('open');
      prev_row.find('input.product-price').prop('disabled', true);
      row.addClass('open');
      row.html(html).slideToggle('slow', function() {
        row.css('min-height', '250px');
      });
    }
  });
});

function submitPrice(id, price) {
  var data = {
    product_id: id,
    price: price
  };
  $('tr.product-row[data-id=' + id + '].price-changed input.product-price').prop('disabled', true);
  $.post('index.php?route=catalog/bulk_edit/updatePrice&token=<?php echo $token; ?>', data, function(result) {
    $('tr.product-row[data-id=' + id + '].price-changed input.product-price').prop('disabled', false).data('current-value', price);
    $('tr.product-row[data-id=' + id + '].price-changed').removeClass('price-changed');
  });
}

$('button.product-save').on('click', function(e) {
  var id = $(e.target).parents('tr').data('id');
  if ($(e.target).parents('tr').hasClass('price-changed')) {
    submitPrice(id, $(e.target).parents('tr').find('input.product-price').val());
  }
  else {
    $.post('index.php?route=catalog/product/edit&token=<?php echo $token; ?>&product_id=' + parseInt(id), $("#form-product").serialize(), function(result) {
      var id = $(e.target).parents('tr').data('id');
      $('tr.product-row.open input.product-price').prop('disabled', false);
      $('tr.product-row.open').removeClass('open');
      $('tr.form-row td > div.open').html('').slideToggle().css('min-height', 0).removeClass('open');
    });
  }
});

$('input.product-price').on('keyup', function(e) {
  var el = $(e.target);
  var id = el.parents('tr').data('id');
  if (el.data('current-value') == el.val()) {
    el.parents('tr').removeClass('price-changed');
    return;
  }
  else if (e.which == 13) {
    submitPrice(id, el.val());
  }
  el.parents('tr').addClass('price-changed');
});
 
//--></script></div>
<?php echo $footer; ?>