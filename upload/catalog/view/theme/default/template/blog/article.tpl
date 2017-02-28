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