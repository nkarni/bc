<h3>
    <i class="fa fa-comments-o"></i> <?php echo $entry_comments . '(' . $comments . ')'; ?>
</h3>
<div id="comments-list">
    <?php if ($comments_list) { ?>
        <?php foreach ($comments_list as $comment) { ?>
            <div class="comment-title">
                <strong><?php echo $comment['author']; ?></strong>
                <small
                    class="pull-right"><?php echo $comment['date_modified']; ?></small>
            </div>
            <div class="comment-description">
                <p><?php echo $comment['text']; ?></p>
            </div>
            <hr>
        <?php } ?>
    <?php } ?>
</div>