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
                            <div class="row">
                                <div class="article-info col-sm-12">
                                    <?php if ($show['date'] && $article['date_modified']) { ?>
                                        <span class="info-span"><i
                                                class="fa fa-calendar"></i> <?php echo $article['date_modified']; ?></span>
                                    <?php } ?>
                                    <?php if ($show['author'] && $article['author']) { ?>
                                        <span class="info-span"><i
                                                class="fa fa-user"></i> <?php echo $article['author']; ?></span>
                                    <?php } ?>
                                    <?php if ($show['view'] && $article['viewed']) { ?>
                                        <span class="info-span"><i
                                                class="fa fa-eye"></i> <?php echo $article['viewed']; ?></span>
                                    <?php } ?>
                                    <?php if ($show['comment'] && $article['comments']) { ?>
                                        <span class="info-span"><i
                                                class="fa fa-comments-o"></i> <?php echo $article['comments']; ?></span>
                                    <?php } ?>
                                    <?php if ($show['category'] && $article['categories']) { ?>
                                        <span class="info-span"><i class="fa fa-folder-open"></i>
                                            <?php foreach ($article['categories'] as $category) { ?>
                                                <a href="<?php echo $category['href'] ?>"><span
                                                        class="label label-category"><?php echo $category['name'] ?></span></a>
                                            <?php } ?>
                              </span>
                                    <?php } ?>
                                    <?php if ($show['tag'] && $article['tags']) { ?>
                                        <span class="info-span"><i class="fa fa-tags"></i>
                                            <?php foreach ($article['tags'] as $tag) { ?>
                                                <a href="<?php echo $tag['href'] ?>"><span
                                                        class="label label-info"><?php echo $tag['tag'] ?></span></a>
                                            <?php } ?>
                              </span>
                                    <?php } ?>
                                </div>
                            </div>
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
                            <div class="row">
                                <div class="article-info col-sm-12">
                                    <?php if ($show['date'] && $article['date_modified']) { ?>
                                        <span class="info-span"><i
                                                class="fa fa-calendar"></i> <?php echo $article['date_modified']; ?></span>
                                    <?php } ?>
                                    <?php if ($show['author'] && $article['author']) { ?>
                                        <span class="info-span"><i
                                                class="fa fa-user"></i> <?php echo $article['author']; ?></span>
                                    <?php } ?>
                                    <?php if ($show['view'] && $article['viewed']) { ?>
                                        <span class="info-span"><i
                                                class="fa fa-eye"></i> <?php echo $article['viewed']; ?></span>
                                    <?php } ?>
                                    <?php if ($show['comment'] && $article['comments']) { ?>
                                        <span class="info-span"><i
                                                class="fa fa-comments-o"></i> <?php echo $article['comments']; ?></span>
                                    <?php } ?>
                                    <?php if ($show['category'] && $article['categories']) { ?>
                                        <span class="info-span"><i class="fa fa-folder-open"></i>
                                            <?php foreach ($article['categories'] as $category) { ?>
                                                <a href="<?php echo $category['href'] ?>"><span
                                                        class="label label-category"><?php echo $category['name'] ?></span></a>
                                            <?php } ?>
                              </span>
                                    <?php } ?>
                                    <?php if ($show['tag'] && $article['tags']) { ?>
                                        <span class="info-span"><i class="fa fa-tags"></i>
                                            <?php foreach ($article['tags'] as $tag) { ?>
                                                <a href="<?php echo $tag['href'] ?>"><span
                                                        class="label label-info"><?php echo $tag['tag'] ?></span></a>
                                            <?php } ?>
                              </span>
                                    <?php } ?>
                                </div>
                            </div>
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