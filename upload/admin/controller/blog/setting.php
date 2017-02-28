<?php
class ControllerBlogSetting extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('blog/setting');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSettingValue('easy_blog', 'easy_blog_global_status', $this->request->post['easy_blog_global_status']);
            $this->model_setting_setting->editSettingValue('easy_blog', 'easy_blog_global_article_limit', $this->request->post['easy_blog_global_article_limit']);

            $this->model_setting_setting->editSettingValue('easy_blog', 'easy_blog_home_page_meta_title', $this->request->post['easy_blog_home_page_meta_title']);
            $this->model_setting_setting->editSettingValue('easy_blog', 'easy_blog_home_page_meta_description', $this->request->post['easy_blog_home_page_meta_description']);
            $this->model_setting_setting->editSettingValue('easy_blog', 'easy_blog_home_page_meta_keyword', $this->request->post['easy_blog_home_page_meta_keyword']);
            $this->model_setting_setting->editSettingValue('easy_blog', 'easy_blog_home_page_name', $this->request->post['easy_blog_home_page_name']);
            $this->model_setting_setting->editSettingValue('easy_blog', 'easy_blog_home_page_description', $this->request->post['easy_blog_home_page_description']);
            $this->model_setting_setting->editSettingValue('easy_blog', 'easy_blog_home_page_seo_url', $this->request->post['easy_blog_home_page_seo_url']);
            $this->model_setting_setting->editSettingValue('easy_blog', 'easy_blog_home_page_show_date', (isset($this->request->post['easy_blog_home_page_show_date'])?1:0));
            $this->model_setting_setting->editSettingValue('easy_blog', 'easy_blog_home_page_show_author', (isset($this->request->post['easy_blog_home_page_show_author'])?1:0));
            $this->model_setting_setting->editSettingValue('easy_blog', 'easy_blog_home_page_show_viewed', (isset($this->request->post['easy_blog_home_page_show_viewed'])?1:0));
            $this->model_setting_setting->editSettingValue('easy_blog', 'easy_blog_home_page_show_number_of_comments', (isset($this->request->post['easy_blog_home_page_show_number_of_comments'])?1:0));
            $this->model_setting_setting->editSettingValue('easy_blog', 'easy_blog_home_page_show_category', (isset($this->request->post['easy_blog_home_page_show_category'])?1:0));
            $this->model_setting_setting->editSettingValue('easy_blog', 'easy_blog_home_page_show_tag', (isset($this->request->post['easy_blog_home_page_show_tag'])?1:0));

            $this->model_setting_setting->editSettingValue('easy_blog', 'easy_blog_category_show_date', (isset($this->request->post['easy_blog_category_show_date'])?1:0));
            $this->model_setting_setting->editSettingValue('easy_blog', 'easy_blog_category_show_author', (isset($this->request->post['easy_blog_category_show_author'])?1:0));
            $this->model_setting_setting->editSettingValue('easy_blog', 'easy_blog_category_show_viewed', (isset($this->request->post['easy_blog_category_show_viewed'])?1:0));
            $this->model_setting_setting->editSettingValue('easy_blog', 'easy_blog_category_show_number_of_comments', (isset($this->request->post['easy_blog_category_show_number_of_comments'])?1:0));
            $this->model_setting_setting->editSettingValue('easy_blog', 'easy_blog_category_show_category', (isset($this->request->post['easy_blog_category_show_category'])?1:0));
            $this->model_setting_setting->editSettingValue('easy_blog', 'easy_blog_category_show_tag', (isset($this->request->post['easy_blog_category_show_tag'])?1:0));

            $this->model_setting_setting->editSettingValue('easy_blog', 'easy_blog_article_show_date', (isset($this->request->post['easy_blog_article_show_date'])?1:0));
            $this->model_setting_setting->editSettingValue('easy_blog', 'easy_blog_article_show_author', (isset($this->request->post['easy_blog_article_show_author'])?1:0));
            $this->model_setting_setting->editSettingValue('easy_blog', 'easy_blog_article_show_viewed', (isset($this->request->post['easy_blog_article_show_viewed'])?1:0));
            $this->model_setting_setting->editSettingValue('easy_blog', 'easy_blog_article_show_number_of_comments', (isset($this->request->post['easy_blog_article_show_number_of_comments'])?1:0));
            $this->model_setting_setting->editSettingValue('easy_blog', 'easy_blog_article_show_category', (isset($this->request->post['easy_blog_article_show_category'])?1:0));
            $this->model_setting_setting->editSettingValue('easy_blog', 'easy_blog_article_show_tag', (isset($this->request->post['easy_blog_article_show_tag'])?1:0));

            $this->model_setting_setting->editSettingValue('easy_blog', 'easy_blog_comment_config_status', $this->request->post['easy_blog_comment_config_status']);
            $this->model_setting_setting->editSettingValue('easy_blog', 'easy_blog_comment_config_guest', $this->request->post['easy_blog_comment_config_guest']);
            $this->model_setting_setting->editSettingValue('easy_blog', 'easy_blog_comment_config_mail', $this->request->post['easy_blog_comment_config_mail']);
            $this->model_setting_setting->editSettingValue('easy_blog', 'easy_blog_comment_config_approve', $this->request->post['easy_blog_comment_config_approve']);
            $this->model_setting_setting->editSettingValue('easy_blog', 'easy_blog_comment_order', $this->request->post['easy_blog_comment_order']);
            $this->model_setting_setting->editSettingValue('easy_blog', 'easy_blog_comment_captcha_status', $this->request->post['easy_blog_comment_captcha_status']);

            if (!($this->config->has('config_google_captcha_public') && $this->config->has('config_google_captcha_secret')) && isset($this->request->post['easy_blog_comment_captcha_public']) && isset($this->request->post['easy_blog_comment_captcha_secret'])) {
                $this->model_setting_setting->editSettingValue('easy_blog', 'easy_blog_comment_captcha_public', $this->request->post['easy_blog_comment_captcha_public']);
                $this->model_setting_setting->editSettingValue('easy_blog', 'easy_blog_comment_captcha_secret', $this->request->post['easy_blog_comment_captcha_secret']);
            } else {
                $this->model_setting_setting->editSettingValue('easy_blog', 'easy_blog_comment_captcha_public', $this->config->get('config_google_captcha_public'));
                $this->model_setting_setting->editSettingValue('easy_blog', 'easy_blog_comment_captcha_secret', $this->config->get('config_google_captcha_secret'));
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('blog/article', 'token=' . $this->session->data['token'], true));
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_select'] = $this->language->get('text_select');
        $data['text_none'] = $this->language->get('text_none');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_captcha'] = $this->language->get('text_captcha');
        $data['text_older_first'] = $this->language->get('text_older_first');
        $data['text_newer_first'] = $this->language->get('text_newer_first');

        $data['tab_general'] = $this->language->get('tab_general');
        $data['tab_home_page'] = $this->language->get('tab_home_page');
        $data['tab_category'] = $this->language->get('tab_category');
        $data['tab_article'] = $this->language->get('tab_article');
        $data['tab_comment'] = $this->language->get('tab_comment');

        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_article_limit'] = $this->language->get('entry_article_limit');
        $data['entry_meta_title'] = $this->language->get('entry_meta_title');
        $data['entry_description'] = $this->language->get('entry_description');
        $data['entry_keyword'] = $this->language->get('entry_keyword');
        $data['entry_meta_description'] = $this->language->get('entry_meta_description');
        $data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_show_for_articles'] = $this->language->get('entry_show_for_articles');
        $data['entry_show_date'] = $this->language->get('entry_show_date');
        $data['entry_show_author'] = $this->language->get('entry_show_author');
        $data['entry_show_viewed'] = $this->language->get('entry_show_viewed');
        $data['entry_show_comment'] = $this->language->get('entry_show_comment');
        $data['entry_show_categories'] = $this->language->get('entry_show_categories');
        $data['entry_show_tags'] = $this->language->get('entry_show_tags');
        $data['entry_comment_status'] = $this->language->get('entry_comment_status');
        $data['entry_comment_guest'] = $this->language->get('entry_comment_guest');
        $data['entry_comment_approve'] = $this->language->get('entry_comment_approve');
        $data['entry_comment_mail'] = $this->language->get('entry_comment_mail');
        $data['entry_comment_order'] = $this->language->get('entry_comment_order');
        $data['entry_easy_blog_captcha'] = $this->language->get('entry_easy_blog_captcha');
        $data['entry_google_captcha_public'] = $this->language->get('entry_google_captcha_public');
        $data['entry_google_captcha_secret'] = $this->language->get('entry_google_captcha_secret');

        $data['help_article_limit'] = $this->language->get('help_article_limit');
        $data['help_meta_title'] = $this->language->get('help_meta_title');
        $data['help_meta_description'] = $this->language->get('help_meta_description');
        $data['help_meta_keyword'] = $this->language->get('help_meta_keyword');
        $data['help_comment_status'] = $this->language->get('help_comment_status');
        $data['help_comment_guest'] = $this->language->get('help_comment_guest');
        $data['help_comment_approve'] = $this->language->get('help_comment_approve');
        $data['help_comment_mail'] = $this->language->get('help_comment_mail');

        if (!($this->config->has('config_google_captcha_public') && $this->config->has('config_google_captcha_secret'))){
            $data['help_easy_blog_captcha'] = $this->language->get('help_easy_blog_captcha_old');
        } else {
            $data['help_easy_blog_captcha'] = sprintf($this->language->get('help_easy_blog_captcha_new'),$this->url->link('setting/store', 'token=' . $this->session->data['token'], true));
        }
        $data['recaptcha_manual'] = !($this->config->has('config_google_captcha_public') && $this->config->has('config_google_captcha_secret'));

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = '';
        }

        if (isset($this->error['keyword'])) {
            $data['error_keyword'] = $this->error['keyword'];
        } else {
            $data['error_keyword'] = '';
        }

        if (isset($this->error['article_limit'])) {
            $data['error_article_limit'] = $this->error['article_limit'];
        } else {
            $data['error_article_limit'] = '';
        }


        $data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('blog/setting', 'token=' . $this->session->data['token'], true)
		);

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['action'] = $this->url->link('blog/setting', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('blog/article', 'token=' . $this->session->data['token'], true);

		$data['token'] = $this->session->data['token'];

        if (isset($this->request->post['easy_blog_global_status'])) {
            $data['easy_blog_global_status'] = $this->request->post['easy_blog_global_status'];
        } else {
            $data['easy_blog_global_status'] = $this->config->get('easy_blog_global_status');
        }

        if (isset($this->request->post['easy_blog_global_article_limit'])) {
            $data['easy_blog_global_article_limit'] = $this->request->post['easy_blog_global_article_limit'];
        } else {
            $data['easy_blog_global_article_limit'] = $this->config->get('easy_blog_global_article_limit');
        }

        if (isset($this->request->post['easy_blog_home_page_name'])) {
            $data['easy_blog_home_page_name'] = $this->request->post['easy_blog_home_page_name'];
        } else {
            $data['easy_blog_home_page_name'] = $this->config->get('easy_blog_home_page_name');
        }

        if (isset($this->request->post['easy_blog_home_page_meta_title'])) {
            $data['easy_blog_home_page_meta_title'] = $this->request->post['easy_blog_home_page_meta_title'];
        } else {
            $data['easy_blog_home_page_meta_title'] = $this->config->get('easy_blog_home_page_meta_title');
        }

        if (isset($this->request->post['easy_blog_home_page_meta_description'])) {
            $data['easy_blog_home_page_meta_description'] = $this->request->post['easy_blog_home_page_meta_description'];
        } else {
            $data['easy_blog_home_page_meta_description'] = $this->config->get('easy_blog_home_page_meta_description');
        }

        if (isset($this->request->post['easy_blog_home_page_description'])) {
            $data['easy_blog_home_page_description'] = $this->request->post['easy_blog_home_page_description'];
        } else {
            $data['easy_blog_home_page_description'] = $this->config->get('easy_blog_home_page_description');
        }

        if (isset($this->request->post['easy_blog_home_page_meta_keyword'])) {
            $data['easy_blog_home_page_meta_keyword'] = $this->request->post['easy_blog_home_page_meta_keyword'];
        } else {
            $data['easy_blog_home_page_meta_keyword'] = $this->config->get('easy_blog_home_page_meta_keyword');
        }

        if (isset($this->request->post['easy_blog_home_page_seo_url'])) {
            $data['easy_blog_home_page_seo_url'] = $this->request->post['easy_blog_home_page_seo_url'];
        } else {
            $data['easy_blog_home_page_seo_url'] = $this->config->get('easy_blog_home_page_seo_url');
        }
        
        if (isset($this->request->post['easy_blog_home_page_show_date'])) {
            $data['easy_blog_home_page_show_date'] = $this->request->post['easy_blog_home_page_show_date'];
        } else {
            $data['easy_blog_home_page_show_date'] = $this->config->get('easy_blog_home_page_show_date');
        }
        
        if (isset($this->request->post['easy_blog_home_page_show_author'])) {
            $data['easy_blog_home_page_show_author'] = $this->request->post['easy_blog_home_page_show_author'];
        } else {
            $data['easy_blog_home_page_show_author'] = $this->config->get('easy_blog_home_page_show_author');
        }
        
        if (isset($this->request->post['easy_blog_home_page_show_viewed'])) {
            $data['easy_blog_home_page_show_viewed'] = $this->request->post['easy_blog_home_page_show_viewed'];
        } else {
            $data['easy_blog_home_page_show_viewed'] = $this->config->get('easy_blog_home_page_show_viewed');
        }
        
        if (isset($this->request->post['easy_blog_home_page_show_number_of_comments'])) {
            $data['easy_blog_home_page_show_number_of_comments'] = $this->request->post['easy_blog_home_page_show_number_of_comments'];
        } else {
            $data['easy_blog_home_page_show_number_of_comments'] = $this->config->get('easy_blog_home_page_show_number_of_comments');
        }

        if (isset($this->request->post['easy_blog_home_page_show_category'])) {
            $data['easy_blog_home_page_show_category'] = $this->request->post['easy_blog_home_page_show_category'];
        } else {
            $data['easy_blog_home_page_show_category'] = $this->config->get('easy_blog_home_page_show_category');
        }
        
        if (isset($this->request->post['easy_blog_home_page_show_tag'])) {
            $data['easy_blog_home_page_show_tag'] = $this->request->post['easy_blog_home_page_show_tag'];
        } else {
            $data['easy_blog_home_page_show_tag'] = $this->config->get('easy_blog_home_page_show_tag');
        }

        if (isset($this->request->post['easy_blog_category_show_date'])) {
            $data['easy_blog_category_show_date'] = $this->request->post['easy_blog_category_show_date'];
        } else {
            $data['easy_blog_category_show_date'] = $this->config->get('easy_blog_category_show_date');
        }
        
        if (isset($this->request->post['easy_blog_category_show_author'])) {
            $data['easy_blog_category_show_author'] = $this->request->post['easy_blog_category_show_author'];
        } else {
            $data['easy_blog_category_show_author'] = $this->config->get('easy_blog_category_show_author');
        }
        
        if (isset($this->request->post['easy_blog_category_show_viewed'])) {
            $data['easy_blog_category_show_viewed'] = $this->request->post['easy_blog_category_show_viewed'];
        } else {
            $data['easy_blog_category_show_viewed'] = $this->config->get('easy_blog_category_show_viewed');
        }
        
        if (isset($this->request->post['easy_blog_category_show_number_of_comments'])) {
            $data['easy_blog_category_show_number_of_comments'] = $this->request->post['easy_blog_category_show_number_of_comments'];
        } else {
            $data['easy_blog_category_show_number_of_comments'] = $this->config->get('easy_blog_category_show_number_of_comments');
        }


        if (isset($this->request->post['easy_blog_category_show_category'])) {
            $data['easy_blog_category_show_category'] = $this->request->post['easy_blog_category_show_category'];
        } else {
            $data['easy_blog_category_show_category'] = $this->config->get('easy_blog_category_show_category');
        }

        if (isset($this->request->post['easy_blog_category_show_tag'])) {
            $data['easy_blog_category_show_tag'] = $this->request->post['easy_blog_category_show_tag'];
        } else {
            $data['easy_blog_category_show_tag'] = $this->config->get('easy_blog_category_show_tag');
        }

        if (isset($this->request->post['easy_blog_article_show_date'])) {
            $data['easy_blog_article_show_date'] = $this->request->post['easy_blog_article_show_date'];
        } else {
            $data['easy_blog_article_show_date'] = $this->config->get('easy_blog_article_show_date');
        }

        if (isset($this->request->post['easy_blog_article_show_author'])) {
            $data['easy_blog_article_show_author'] = $this->request->post['easy_blog_article_show_author'];
        } else {
            $data['easy_blog_article_show_author'] = $this->config->get('easy_blog_article_show_author');
        }

        if (isset($this->request->post['easy_blog_article_show_viewed'])) {
            $data['easy_blog_article_show_viewed'] = $this->request->post['easy_blog_article_show_viewed'];
        } else {
            $data['easy_blog_article_show_viewed'] = $this->config->get('easy_blog_article_show_viewed');
        }

        if (isset($this->request->post['easy_blog_article_show_number_of_comments'])) {
            $data['easy_blog_article_show_number_of_comments'] = $this->request->post['easy_blog_article_show_number_of_comments'];
        } else {
            $data['easy_blog_article_show_number_of_comments'] = $this->config->get('easy_blog_article_show_number_of_comments');
        }

        if (isset($this->request->post['easy_blog_article_show_category'])) {
            $data['easy_blog_article_show_category'] = $this->request->post['easy_blog_article_show_category'];
        } else {
            $data['easy_blog_article_show_category'] = $this->config->get('easy_blog_article_show_category');
        }

        if (isset($this->request->post['easy_blog_article_show_tag'])) {
            $data['easy_blog_article_show_tag'] = $this->request->post['easy_blog_article_show_tag'];
        } else {
            $data['easy_blog_article_show_tag'] = $this->config->get('easy_blog_article_show_tag');
        }

        if (isset($this->request->post['easy_blog_comment_config_status'])) {
            $data['easy_blog_comment_config_status'] = $this->request->post['easy_blog_comment_config_status'];
        } else {
            $data['easy_blog_comment_config_status'] = $this->config->get('easy_blog_comment_config_status');
        }

        if (isset($this->request->post['easy_blog_comment_config_guest'])) {
            $data['easy_blog_comment_config_guest'] = $this->request->post['easy_blog_comment_config_guest'];
        } else {
            $data['easy_blog_comment_config_guest'] = $this->config->get('easy_blog_comment_config_guest');
        }

        if (isset($this->request->post['easy_blog_comment_config_approve'])) {
            $data['easy_blog_comment_config_approve'] = $this->request->post['easy_blog_comment_config_approve'];
        } else {
            $data['easy_blog_comment_config_approve'] = $this->config->get('easy_blog_comment_config_approve');
        }

        if (isset($this->request->post['easy_blog_comment_config_mail'])) {
            $data['easy_blog_comment_config_mail'] = $this->request->post['easy_blog_comment_config_mail'];
        } else {
            $data['easy_blog_comment_config_mail'] = $this->config->get('easy_blog_comment_config_mail');
        }

        if (isset($this->request->post['easy_blog_comment_order'])) {
            $data['easy_blog_comment_order'] = $this->request->post['easy_blog_comment_order'];
        } else {
            $data['easy_blog_comment_order'] = $this->config->get('easy_blog_comment_order');
        }

        if (isset($this->request->post['easy_blog_comment_captcha_status'])) {
            $data['easy_blog_comment_captcha_status'] = $this->request->post['easy_blog_comment_captcha_status'];
        } else {
            $data['easy_blog_comment_captcha_status'] = $this->config->get('easy_blog_comment_captcha_status');
        }

        if (isset($this->request->post['easy_blog_comment_captcha_public'])) {
            $data['easy_blog_comment_captcha_public'] = $this->request->post['easy_blog_comment_captcha_public'];
        } else {
            $data['easy_blog_comment_captcha_public'] = $this->config->get('easy_blog_comment_captcha_public');
        }

        if (isset($this->request->post['easy_blog_comment_captcha_secret'])) {
            $data['easy_blog_comment_captcha_secret'] = $this->request->post['easy_blog_comment_captcha_secret'];
        } else {
            $data['easy_blog_comment_captcha_secret'] = $this->config->get('easy_blog_comment_captcha_secret');
        }

        $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('blog/setting', $data));
	}

	protected function validate() {
        if (!$this->user->hasPermission('modify', 'blog/setting')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['easy_blog_global_article_limit']) {
            $this->error['article_limit'] = $this->language->get('error_article_limit');
        }

        if ((utf8_strlen($this->request->post['easy_blog_home_page_name']) < 3) || (utf8_strlen($this->request->post['easy_blog_home_page_name']) > 64)) {
            $this->error['name'] = $this->language->get('error_name');
        }

        if (utf8_strlen($this->request->post['easy_blog_home_page_seo_url']) > 0) {
            $this->load->model('catalog/url_alias');

            $url_alias_info = $this->model_catalog_url_alias->getUrlAlias($this->request->post['easy_blog_home_page_seo_url']);

            if ($url_alias_info) {
                $this->error['keyword'] = sprintf($this->language->get('error_keyword'));
            }
        }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
	}

}