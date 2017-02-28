<div class="easy-blog">
    <h3><?php echo $heading_title . ' - ' . $name; ?></h3>
    <?php if ($column == 1) { ?>
        <?php foreach ($articles as $article) { ?>
            <div class="row">
                <div class="col-xs-12">
                    <div class="article-intro">
                        <div>
                            <h2><a href="<?php echo $article['href']; ?>"><?php echo $article['name']; ?></a></h2>
                            <hr>
                            <p><?php echo $article['intro_text']; ?></p>
                        </div>
                    </div>
                    <div class="buttons">
                        <div class="pull-right"><a href="<?php echo $article['href']; ?>"
                                                   class="btn btn-primary"><?php echo $button_read_more; ?></a></div>
                    </div>
                </div>
            </div>
        <?php } ?>
    <?php } else {
        $count = 0; ?>
        <?php foreach ($articles as $article) {
            $count += 1; ?>
            <?php if ($count % 2 == 1) { ?>
                <div class="row"> <?php } ?>

                <div class="col-xs-6">
                    <div class="article-intro">
                        <div>
                            <h2><a href="<?php echo $article['href']; ?>"><?php echo $article['name']; ?></a></h2>
                            <hr>
                            <p><?php echo $article['intro_text']; ?></p>
                        </div>
                    </div>
                    <div class="buttons">
                        <div class="pull-right"><a href="<?php echo $article['href']; ?>"
                                                   class="btn btn-primary"><?php echo $button_read_more; ?></a></div>
                    </div>
                </div>

            <?php if ($count % 2 == 0) { ?>
                </div><?php } ?>
        <?php } ?>
    <?php } ?>
</div>