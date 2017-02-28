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
                <div class="row">
                    <div class="easy-blog">
                        <div class="article-title">
                            <h2><?php echo $heading_title; ?></h2>
                        </div>
                        <div class="article-subtitle">
                            <div class="article-info">
                                <?php if ($show['date'] && $date_modified) { ?>
                                    <span class="info-span"><i class="fa fa-calendar"></i> <?php echo $date_modified; ?></span>
                                <?php } ?>
                                <?php if ($show['author'] && $author) { ?>
                                    <span class="info-span"><i class="fa fa-user"></i> <?php echo $author; ?></span>
                                <?php } ?>
                                <?php if ($show['view'] && $viewed) { ?>
                                    <span class="info-span"><i class="fa fa-eye"></i> <?php echo $viewed; ?></span>
                                <?php } ?>
                                <?php if ($show['comment'] && $comments) { ?>
                                    <span class="info-span"><i
                                            class="fa fa-comments-o"></i> <?php echo $comments; ?></span>
                                <?php } ?>
                                <?php if ($show['category'] && $categories) { ?>
                                    <span class="info-span"><i class="fa fa-folder-open"></i>
                                        <?php foreach ($categories as $category) { ?>
                                            <a href="<?php echo $category['href'] ?>"><span
                                                    class="label label-category"><?php echo $category['name'] ?></span></a>
                                        <?php } ?>
                              </span>
                                <?php } ?>
                                <?php if ($show['tag'] && $tags) { ?>
                                    <span class="info-span"><i class="fa fa-tags"></i>
                                        <?php foreach ($tags as $tag) { ?>
                                            <a href="<?php echo $tag['href'] ?>"><span
                                                    class="label label-info"><?php echo $tag['tag'] ?></span></a>
                                        <?php } ?>
                                                </span>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="article-description">
                            <p><?php echo $description; ?></p>
                        </div>
                    </div>
                </div>
                <?php echo $content_bottom; ?></div>
            <?php echo $column_right; ?></div>
    </div>
    <script type="text/javascript"><!--
        $('#comments').load('index.php?route=blog/article/comment&article_id=<?php echo $article_id; ?>');
        $('#button-comment').on('click', function () {
            $.ajax({
                url: 'index.php?route=blog/article/write&article_id=<?php echo $article_id; ?>',
                type: 'post',
                dataType: 'json',
                data: $("#form-comment").serialize(),
                beforeSend: function () {
                    $('#button-comment').button('loading');
                },
                complete: function () {
                    $('#button-comment').button('reset');
                },
                success: function (json) {
                    $('.alert-success, .alert-danger').remove();

                    if (json['error']) {
                        $('#comment').after('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
                    }

                    if (json['success']) {
                        $('#comment').after('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');
                        $('#comments').empty();
                        $('#comments').load('index.php?route=blog/article/comment&article_id=<?php echo $article_id; ?>');
                        grecaptcha.reset();
                        $('textarea[name=\'text\']').val('');
                    }
                }
            });
        });
        //--></script>
<?php echo $footer; ?>