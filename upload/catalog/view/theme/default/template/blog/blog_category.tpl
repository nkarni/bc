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
      <div class="easy-blog">
      <h2><?php echo $heading_title; ?></h2>
      <?php if ($description) { ?>
      <div class="row">
        <div class="col-sm-12"><?php echo $description; ?></div>
      </div>
      <hr>
      <?php } ?>
      <?php if ($blog_categories) { ?>

      <div class="row">
        <?php foreach (array_chunk($blog_categories, ceil(count($blog_categories) / 4)) as $blog_categories) { ?>
          <?php foreach ($blog_categories as $blog_category) { ?>
        <div class="col-sm-3"><a class="btn btn-primary" href="<?php echo $blog_category['href']; ?>"><?php echo $blog_category['name']; ?></a></div>
          <?php } ?>
        <?php } ?>
      <hr>
      </div>
      <?php } ?>
      <?php if ($articles && count($blog_categories) === 0) { ?>
        <div class="row">
          <?php foreach ($articles as $article) { ?>
          <div class="panel panel-default">
            <div class="panel-body">
              <div class="article-layout article-list col-xs-12">
                <div class="article-intro">
                  <div>
                    <h2><a href="<?php echo $article['href']; ?>"><?php echo $article['name']; ?></a></h2>
                    <hr>
                    <p><?php echo $article['intro_text']; ?></p>
                  </div>
                </div>
                <div class="buttons">
                  <div class="pull-right"><a href="<?php echo $article['href']; ?>" class="btn btn-primary"><?php echo $button_read_more; ?></a></div>
                </div>
              </div>
            </div>
          </div>
          <?php } ?>
        </div>
      <div class="row">
        <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
        <div class="col-sm-6 text-right"><?php echo $results; ?></div>
      </div>
      <?php } ?>
      <?php if (!$blog_categories && !$articles) { ?>
      <p><?php echo $text_empty; ?></p>
      <div class="buttons">
        <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
      </div>
      <?php } ?>
      </div>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>
