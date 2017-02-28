<?php echo $header; ?><?php echo $column_left; ?>
    <div id="content">
        <div class="page-header">
            <div class="container-fluid">
                <div class="pull-right">
                    <button type="submit" form="form-account" data-toggle="tooltip" title="<?php echo $button_save; ?>"
                            class="btn btn-primary"><i class="fa fa-save"></i></button>
                    <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>"
                       class="btn btn-default"><i class="fa fa-reply"></i></a></div>
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
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
                </div>
                <div class="panel-body">
                    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-setting"
                          class="form-horizontal">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a>
                            </li>
                            <li><a href="#tab-home-page" data-toggle="tab"><?php echo $tab_home_page; ?></a></li>
                            <li><a href="#tab-category" data-toggle="tab"><?php echo $tab_category; ?></a></li>
                            <li><a href="#tab-article" data-toggle="tab"><?php echo $tab_article; ?></a></li>
                            <li><a href="#tab-comment" data-toggle="tab"><?php echo $tab_comment; ?></a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab-general">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"
                                           for="input-status"><?php echo $entry_status; ?></label>

                                    <div class="col-sm-10">
                                        <select name="easy_blog_global_status" id="input-status" class="form-control">
                                            <?php if ($easy_blog_global_status) { ?>
                                                <option value="1"
                                                        selected="selected"><?php echo $text_enabled; ?></option>
                                                <option value="0"><?php echo $text_disabled; ?></option>
                                            <?php } else { ?>
                                                <option value="1"><?php echo $text_enabled; ?></option>
                                                <option value="0"
                                                        selected="selected"><?php echo $text_disabled; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-2 control-label" for="input-blog-limit"><span
                                            data-toggle="tooltip"
                                            title="<?php echo $help_article_limit; ?>"><?php echo $entry_article_limit; ?></span></label>

                                    <div class="col-sm-10">
                                        <input type="text" name="easy_blog_global_article_limit"
                                               value="<?php echo $easy_blog_global_article_limit; ?>"
                                               placeholder="<?php echo $entry_article_limit; ?>" id="input-blog-limit"
                                               class="form-control"/>
                                        <?php if ($error_article_limit) { ?>
                                            <div class="text-danger"><?php echo $error_article_limit; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab-home-page">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"
                                           for="input-name"><?php echo $entry_name; ?></label>

                                    <div class="col-sm-10">
                                        <input type="text" name="easy_blog_home_page_name"
                                               value="<?php echo $easy_blog_home_page_name; ?>"
                                               placeholder="<?php echo $entry_name; ?>" id="input-name"
                                               class="form-control"/>
                                        <?php if ($error_name) { ?>
                                            <div class="text-danger"><?php echo $error_name; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"
                                           for="input-home-page-description"><?php echo $entry_description; ?></label>

                                    <div class="col-sm-10">
                                        <textarea name="easy_blog_home_page_description"
                                                  placeholder="<?php echo $entry_description; ?>"
                                                  id="input-home-page-description" class="form-control summernote"><?php echo isset($easy_blog_home_page_description) ? $easy_blog_home_page_description : ''; ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"
                                           for="input-blog-home-page-seo-url"><?php echo $entry_keyword; ?></label>

                                    <div class="col-sm-10">
                                        <input type="text" name="easy_blog_home_page_seo_url"
                                               value="<?php echo $easy_blog_home_page_seo_url; ?>"
                                               placeholder="<?php echo $entry_keyword; ?>"
                                               id="input-blog-home-page-seo-url" class="form-control"/>
                                        <?php if ($error_keyword) { ?>
                                            <div class="text-danger"><?php echo $error_keyword; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"
                                           for="input-blog-meta-title"><?php echo $entry_meta_title; ?></label>

                                    <div class="col-sm-10">
                                        <input type="text" name="easy_blog_home_page_meta_title"
                                               value="<?php echo $easy_blog_home_page_meta_title; ?>"
                                               placeholder="<?php echo $entry_meta_title; ?>" id="input-blog-meta-title"
                                               class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"
                                           for="input-blog-meta-description"><?php echo $entry_meta_description; ?></label>

                                    <div class="col-sm-10">
                                        <input type="text" name="easy_blog_home_page_meta_description"
                                               value="<?php echo $easy_blog_home_page_meta_description; ?>"
                                               placeholder="<?php echo $entry_meta_description; ?>"
                                               id="input-blog-meta-description" class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"
                                           for="input-blog-meta-keyword"><?php echo $entry_meta_keyword; ?></label>

                                    <div class="col-sm-10">
                                        <input type="text" name="easy_blog_home_page_meta_keyword"
                                               value="<?php echo $easy_blog_home_page_meta_keyword; ?>"
                                               placeholder="<?php echo $entry_meta_keyword; ?>"
                                               id="input-blog-meta-keyword" class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"
                                           for="input-blog-home-page-show"><?php echo $entry_show_for_articles; ?></label>

                                    <div class="col-sm-10" id="input-blog-home-page-show">
                                        <div class="checkbox">
                                            <label class="checkbox">
                                                <input type="checkbox"
                                                       name="easy_blog_home_page_show_date" <?php echo($easy_blog_home_page_show_date ? 'checked' : '') ?> > <?php echo $entry_show_date; ?>
                                            </label>
                                        </div>
                                        <div class="checkbox"><label class="checkbox">
                                                <input type="checkbox"
                                                       name="easy_blog_home_page_show_author" <?php echo($easy_blog_home_page_show_author ? 'checked' : '') ?> > <?php echo $entry_show_author; ?>
                                            </label>
                                        </div>
                                        <div class="checkbox"><label class="checkbox">
                                                <input type="checkbox"
                                                       name="easy_blog_home_page_show_viewed" <?php echo($easy_blog_home_page_show_viewed ? 'checked' : '') ?> > <?php echo $entry_show_viewed; ?>
                                            </label>
                                        </div>
                                        <div class="checkbox"><label class="checkbox">
                                                <input type="checkbox"
                                                       name="easy_blog_home_page_show_number_of_comments" <?php echo($easy_blog_home_page_show_number_of_comments ? 'checked' : '') ?> > <?php echo $entry_show_comment; ?>
                                            </label>
                                        </div>
                                        <div class="checkbox"><label class="checkbox">
                                                <input type="checkbox"
                                                       name="easy_blog_home_page_show_category" <?php echo($easy_blog_home_page_show_category ? 'checked' : '') ?> > <?php echo $entry_show_categories; ?>
                                            </label>
                                        </div>
                                        <div class="checkbox"><label class="checkbox">
                                                <input type="checkbox"
                                                       name="easy_blog_home_page_show_tag" <?php echo($easy_blog_home_page_show_tag ? 'checked' : '') ?> > <?php echo $entry_show_tags; ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab-category">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"
                                           for="input-blog-category-show"><?php echo $entry_show_for_articles; ?></label>

                                    <div class="col-sm-10" id="input-blog-category-show">
                                        <div class="checkbox"><label class="checkbox">
                                                <input type="checkbox"
                                                       name="easy_blog_category_show_date" <?php echo($easy_blog_category_show_date ? 'checked' : '') ?> > <?php echo $entry_show_date; ?>
                                            </label></div>
                                        <div class="checkbox"><label class="checkbox">
                                                <input type="checkbox"
                                                       name="easy_blog_category_show_author" <?php echo($easy_blog_category_show_author ? 'checked' : '') ?> > <?php echo $entry_show_author; ?>
                                            </label></div>
                                        <div class="checkbox"><label class="checkbox">
                                                <input type="checkbox"
                                                       name="easy_blog_category_show_viewed" <?php echo($easy_blog_category_show_viewed ? 'checked' : '') ?> > <?php echo $entry_show_viewed; ?>
                                            </label></div>
                                        <div class="checkbox"><label class="checkbox">
                                                <input type="checkbox"
                                                       name="easy_blog_category_show_number_of_comments" <?php echo($easy_blog_category_show_number_of_comments ? 'checked' : '') ?> > <?php echo $entry_show_comment; ?>
                                            </label></div>
                                        <div class="checkbox"><label class="checkbox">
                                                <input type="checkbox"
                                                       name="easy_blog_category_show_category" <?php echo($easy_blog_category_show_category ? 'checked' : '') ?> > <?php echo $entry_show_categories; ?>
                                            </label>
                                        </div>
                                        <div class="checkbox"><label class="checkbox">
                                                <input type="checkbox"
                                                       name="easy_blog_category_show_tag" <?php echo($easy_blog_category_show_tag ? 'checked' : '') ?> > <?php echo $entry_show_tags; ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="tab-pane" id="tab-article">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label"
                                           for="input-blog-article-show"><?php echo $entry_show_for_articles; ?></label>

                                    <div class="col-sm-10" id="input-blog-article-show">

                                        <div class="checkbox"><label class="checkbox">
                                                <input type="checkbox"
                                                       name="easy_blog_article_show_date" <?php echo($easy_blog_article_show_date ? 'checked' : '') ?> > <?php echo $entry_show_date; ?>
                                            </label></div>
                                        <div class="checkbox"><label class="checkbox">
                                                <input type="checkbox"
                                                       name="easy_blog_article_show_author" <?php echo($easy_blog_article_show_author ? 'checked' : '') ?> > <?php echo $entry_show_author; ?>
                                            </label></div>
                                        <div class="checkbox"><label class="checkbox">
                                                <input type="checkbox"
                                                       name="easy_blog_article_show_viewed" <?php echo($easy_blog_article_show_viewed ? 'checked' : '') ?> > <?php echo $entry_show_viewed; ?>
                                            </label></div>
                                        <div class="checkbox"><label class="checkbox">
                                                <input type="checkbox"
                                                       name="easy_blog_article_show_number_of_comments" <?php echo($easy_blog_article_show_number_of_comments ? 'checked' : '') ?> > <?php echo $entry_show_comment; ?>
                                            </label></div>
                                        <div class="checkbox"><label class="checkbox">
                                                <input type="checkbox"
                                                       name="easy_blog_article_show_category" <?php echo($easy_blog_article_show_category ? 'checked' : '') ?> > <?php echo $entry_show_categories; ?>
                                            </label>
                                        </div>
                                        <div class="checkbox"><label class="checkbox">
                                                <input type="checkbox"
                                                       name="easy_blog_article_show_tag" <?php echo($easy_blog_article_show_tag ? 'checked' : '') ?> > <?php echo $entry_show_tags; ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab-comment">
                                <fieldset>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label"><span data-toggle="tooltip"
                                                                                    title="<?php echo $help_comment_status; ?>"><?php echo $entry_comment_status; ?></span></label>

                                        <div class="col-sm-10">
                                            <label class="radio-inline">
                                                <?php if ($easy_blog_comment_config_status) { ?>
                                                    <input type="radio" name="easy_blog_comment_config_status" value="1"
                                                           checked="checked"/>
                                                    <?php echo $text_yes; ?>
                                                <?php } else { ?>
                                                    <input type="radio" name="easy_blog_comment_config_status"
                                                           value="1"/>
                                                    <?php echo $text_yes; ?>
                                                <?php } ?>
                                            </label>
                                            <label class="radio-inline">
                                                <?php if (!$easy_blog_comment_config_status) { ?>
                                                    <input type="radio" name="easy_blog_comment_config_status" value="0"
                                                           checked="checked"/>
                                                    <?php echo $text_no; ?>
                                                <?php } else { ?>
                                                    <input type="radio" name="easy_blog_comment_config_status"
                                                           value="0"/>
                                                    <?php echo $text_no; ?>
                                                <?php } ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label"><span data-toggle="tooltip"
                                                                                    title="<?php echo $help_comment_guest; ?>"><?php echo $entry_comment_guest; ?></span></label>

                                        <div class="col-sm-10">
                                            <label class="radio-inline">
                                                <?php if ($easy_blog_comment_config_guest) { ?>
                                                    <input type="radio" name="easy_blog_comment_config_guest" value="1"
                                                           checked="checked"/>
                                                    <?php echo $text_yes; ?>
                                                <?php } else { ?>
                                                    <input type="radio" name="easy_blog_comment_config_guest"
                                                           value="1"/>
                                                    <?php echo $text_yes; ?>
                                                <?php } ?>
                                            </label>
                                            <label class="radio-inline">
                                                <?php if (!$easy_blog_comment_config_guest) { ?>
                                                    <input type="radio" name="easy_blog_comment_config_guest" value="0"
                                                           checked="checked"/>
                                                    <?php echo $text_no; ?>
                                                <?php } else { ?>
                                                    <input type="radio" name="easy_blog_comment_config_guest"
                                                           value="0"/>
                                                    <?php echo $text_no; ?>
                                                <?php } ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label"><span data-toggle="tooltip"
                                                                                    title="<?php echo $help_comment_approve; ?>"><?php echo $entry_comment_approve; ?></span></label>

                                        <div class="col-sm-10">
                                            <label class="radio-inline">
                                                <?php if ($easy_blog_comment_config_approve) { ?>
                                                    <input type="radio" name="easy_blog_comment_config_approve"
                                                           value="1" checked="checked"/>
                                                    <?php echo $text_yes; ?>
                                                <?php } else { ?>
                                                    <input type="radio" name="easy_blog_comment_config_approve"
                                                           value="1"/>
                                                    <?php echo $text_yes; ?>
                                                <?php } ?>
                                            </label>
                                            <label class="radio-inline">
                                                <?php if (!$easy_blog_comment_config_approve) { ?>
                                                    <input type="radio" name="easy_blog_comment_config_approve"
                                                           value="0" checked="checked"/>
                                                    <?php echo $text_no; ?>
                                                <?php } else { ?>
                                                    <input type="radio" name="easy_blog_comment_config_approve"
                                                           value="0"/>
                                                    <?php echo $text_no; ?>
                                                <?php } ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label"><span data-toggle="tooltip"
                                                                                    title="<?php echo $help_comment_mail; ?>"><?php echo $entry_comment_mail; ?></span></label>

                                        <div class="col-sm-10">
                                            <label class="radio-inline">
                                                <?php if ($easy_blog_comment_config_mail) { ?>
                                                    <input type="radio" name="easy_blog_comment_config_mail" value="1"
                                                           checked="checked"/>
                                                    <?php echo $text_yes; ?>
                                                <?php } else { ?>
                                                    <input type="radio" name="easy_blog_comment_config_mail" value="1"/>
                                                    <?php echo $text_yes; ?>
                                                <?php } ?>
                                            </label>
                                            <label class="radio-inline">
                                                <?php if (!$easy_blog_comment_config_mail) { ?>
                                                    <input type="radio" name="easy_blog_comment_config_mail" value="0"
                                                           checked="checked"/>
                                                    <?php echo $text_no; ?>
                                                <?php } else { ?>
                                                    <input type="radio" name="easy_blog_comment_config_mail" value="0"/>
                                                    <?php echo $text_no; ?>
                                                <?php } ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input-comment-order"><?php echo $entry_comment_order; ?></label>
                                        <div class="col-sm-10">
                                            <select name="easy_blog_comment_order" id="input-comment-order" class="form-control">
                                                <?php if ($easy_blog_comment_order == "ASC") { ?>
                                                    <option value="ASC" selected="selected"><?php echo $text_older_first; ?></option>
                                                    <option value="DESC"><?php echo $text_newer_first; ?></option>
                                                <?php } else { ?>
                                                    <option value="ASC"><?php echo $text_older_first; ?></option>
                                                    <option value="DESC" selected="selected"><?php echo $text_newer_first; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <legend><?php echo $text_captcha; ?></legend>
                                    <div class="alert alert-info"><?php echo $help_easy_blog_captcha; ?></div>
                                    <div class="form-group">
                                        <label
                                            class="col-sm-2 control-label"><?php echo $entry_easy_blog_captcha; ?></label>

                                        <div class="col-sm-10">
                                            <label class="radio-inline">
                                                <?php if ($easy_blog_comment_captcha_status) { ?>
                                                    <input type="radio" name="easy_blog_comment_captcha_status"
                                                           value="1" checked="checked"/>
                                                    <?php echo $text_yes; ?>
                                                <?php } else { ?>
                                                    <input type="radio" name="easy_blog_comment_captcha_status"
                                                           value="1"/>
                                                    <?php echo $text_yes; ?>
                                                <?php } ?>
                                            </label>
                                            <label class="radio-inline">
                                                <?php if (!$easy_blog_comment_captcha_status) { ?>
                                                    <input type="radio" name="easy_blog_comment_captcha_status"
                                                           value="0" checked="checked"/>
                                                    <?php echo $text_no; ?>
                                                <?php } else { ?>
                                                    <input type="radio" name="easy_blog_comment_captcha_status"
                                                           value="0"/>
                                                    <?php echo $text_no; ?>
                                                <?php } ?>
                                            </label>
                                        </div>
                                    </div>
                                    <?php if ($recaptcha_manual) { ?>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label"
                                                   for="input-google-captcha-public"><?php echo $entry_google_captcha_public; ?></label>

                                            <div class="col-sm-10">
                                                <input type="text" name="easy_blog_comment_captcha_public"
                                                       value="<?php echo $easy_blog_comment_captcha_public; ?>"
                                                       placeholder="<?php echo $entry_google_captcha_public; ?>"
                                                       id="input-google-captcha-public" class="form-control"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label"
                                                   for="input-google-captcha-secret"><?php echo $entry_google_captcha_secret; ?></label>

                                            <div class="col-sm-10">
                                                <input type="text" name="easy_blog_comment_captcha_secret"
                                                       value="<?php echo $easy_blog_comment_captcha_secret; ?>"
                                                       placeholder="<?php echo $entry_google_captcha_secret; ?>"
                                                       id="input-google-captcha-secret" class="form-control"/>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </fieldset>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<script type="text/javascript" src="view/javascript/summernote/summernote.js"></script>
<link href="view/javascript/summernote/summernote.css" rel="stylesheet" />
<script type="text/javascript" src="view/javascript/summernote/opencart.js"></script>
<?php echo $footer; ?>