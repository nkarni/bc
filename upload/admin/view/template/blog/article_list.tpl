<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="submit" form="form-article" formaction="<?php echo $copy; ?>" data-toggle="tooltip" title="<?php echo $button_copy; ?>" class="btn btn-default"><i class="fa fa-copy"></i></button>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-article').submit() : false;"><i class="fa fa-trash-o"></i></button>
      </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
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
                    <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
                    <select name="filter_status" id="input-status" class="form-control">
                        <option value="*"></option>
                        <?php if ($filter_status) { ?>
                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                        <?php } else { ?>
                        <option value="1"><?php echo $text_enabled; ?></option>
                        <?php } ?>
                        <?php if (!$filter_status && !is_null($filter_status)) { ?>
                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                        <?php } else { ?>
                        <option value="0"><?php echo $text_disabled; ?></option>
                        <?php } ?>
                    </select>
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-article">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-left"><?php if ($sort == 'ad.name') { ?>
                    <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'a.sort_order') { ?>
                    <a href="<?php echo $sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_sort_order; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_order; ?>"><?php echo $column_sort_order; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'a.date_modified') { ?>
                    <a href="<?php echo $sort_date; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date; ?>"><?php echo $column_date; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'a.viewed') { ?>
                    <a href="<?php echo $sort_views; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_views; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_views; ?>"><?php echo $column_views; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'comments_total') { ?>
                    <a href="<?php echo $sort_comments; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_comments; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_comments; ?>"><?php echo $column_comments; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'a.status') { ?>
                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                    <?php } ?></td>
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($articles) { ?>
                <?php foreach ($articles as $article) { ?>
                <tr>
                  <td class="text-center"><?php if (in_array($article['article_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $article['article_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $article['article_id']; ?>" />
                    <?php } ?></td>
                  <td class="text-left"><?php echo $article['name']; ?></td>
                  <td class="text-right"><?php echo $article['sort_order']; ?></td>
                  <td class="text-left"><?php echo $article['date_modified']; ?></td>
                  <td class="text-right"><?php echo $article['viewed']; ?></td>
                  <td class="text-right">
                      <div class="btn-group" role="group">
                          <a href="<?php echo $article['comments_total_link']; ?>" role="button" class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="<?php echo $help_comments_total; ?>"><?php echo $article['comments_total']; ?></a>
                          <?php if ($article['comments_unpublished']) { ?>
                          <a href="<?php echo $article['comments_unpublished_link']; ?>" role="button" class="btn btn-danger" data-toggle="tooltip" data-placement="bottom" title="<?php echo $help_comments_unpublished; ?>"><?php echo $article['comments_unpublished']; ?></a>
                          <?php }?>
                      </div>
                  <td class="text-right"><?php if ($article['status']) { ?>
                      <a href="<?php echo $article['status_link']; ?>" role="button" class="btn btn-success" data-toggle="tooltip" data-placement="bottom" title="<?php echo $help_enabled_button; ?>"><?php echo $text_enabled; ?></a>
                      <?php } else { ?>
                      <a href="<?php echo $article['status_link']; ?>" role="button" class="btn btn-danger" data-toggle="tooltip" data-placement="bottom" title="<?php echo $help_disabled_button; ?>"><?php echo $text_disabled; ?></a>
                      <?php } ?></td>
                  <td class="text-right"><a href="<?php echo $article['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
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
        </form>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	var url = 'index.php?route=blog/article&token=<?php echo $token; ?>';

	var filter_name = $('input[name=\'filter_name\']').val();

	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}

	var filter_status = $('select[name=\'filter_status\']').val();

	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}

	location = url;
});
//--></script> 
  <script type="text/javascript"><!--
$('input[name=\'filter_name\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=blog/article/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['article_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_name\']').val(item['label']);
	}
});
//--></script></div>
<?php echo $footer; ?>