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
      <h1><?php echo $heading_title; ?></h1>
      <?php if ($description) { ?>
      <div class="row">
        <div class="col-sm-12"><?php echo $description; ?></div>
      </div>
      <hr>
      <?php } ?>
      <?php if ($articles) { ?>
      <div class="row">
        <?php foreach ($articles as $article) { ?>
          <div class="panel panel-default">
              <div class="panel-body">
                <div class="article-layout article-list col-xs-12">
                  <div class="article-intro">
                    <div>
                      <h2><a href="<?php echo $article['href']; ?>"><?php echo $article['name']; ?></a></h2>
                      <hr>
                      <div class="row">
                          <div class="article-info col-sm-12">
                              <?php if ($show['date'] && $article['date_modified']) { ?>
                              <span class="info-span"><i class="fa fa-calendar"></i> <?php echo $article['date_modified']; ?></span>
                              <?php }?>
                              <?php if ($show['author'] && $article['author']) { ?>
                              <span class="info-span"><i class="fa fa-user"></i> <?php echo $article['author']; ?></span>
                              <?php }?>
                              <?php if ($show['view'] && $article['viewed']) { ?>
                              <span class="info-span"><i class="fa fa-eye"></i> <?php echo $article['viewed']; ?></span>
                              <?php }?>
                              <?php if ($show['comment'] && $article['comments']) { ?>
                              <span class="info-span"><i class="fa fa-comments-o"></i> <?php echo $article['comments']; ?></span>
                              <?php }?>
                              <?php if ($show['category'] && $article['categories']) { ?>
                              <span class="info-span"><i class="fa fa-folder-open"></i>
                                  <?php foreach($article['categories'] as $category) { ?>
                                  <a href="<?php echo $category['href']?>"><span class="label label-category"><?php echo $category['name']?></span></a>
                                  <?php }?>
                              </span>
                              <?php }?>
                              <?php if ($show['tag'] && $article['tags']) { ?>
                              <span class="info-span"><i class="fa fa-tags"></i>
                                    <?php foreach($article['tags'] as $tag) { ?>
                                    <a href="<?php echo $tag['href']?>"><span class="label label-info"><?php echo $tag['tag']?></span></a>
                                    <?php }?>
                              </span>
                              <?php }?>
                          </div>
                      </div>
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
      <?php if (!$articles) { ?>
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
