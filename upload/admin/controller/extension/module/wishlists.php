<?php
class ControllerExtensionModuleWishlists extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/wishlists');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

            $insertdata = array();

            $activesocialitems = array();

            if(isset($this->request->post['wishlists_socials']))

                $this->request->post['wishlists_socials'] = implode(",",$this->request->post['wishlists_socials']);

			$this->model_setting_setting->editSetting('wishlists', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['entry_status'] = $this->language->get('entry_status');

        $data['entry_social_share'] = $this->language->get('entry_social_share');

        $data['entry_wishlists_copy_url_status'] = $this->language->get('entry_wishlists_copy_url_status');

        $data['social_list'] = array(
                                        'fb'=>'facebook',
                                        'tw'=>'twitter',
                                        'gp'=>'google',
                                        'li'=>'linkedin',
                                        'tm'=>'tumblr',
                                        'pt'=>'pinterest',
                                    );

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true),
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true),
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/wishlists', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('extension/module/wishlists', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);

		if (isset($this->request->post['wishlists_status'])) {
			$data['wishlists_status'] = $this->request->post['wishlists_status'];
		} else {
			$data['wishlists_status'] = $this->config->get('wishlists_status');
		}

        if (isset($this->request->post['wishlists_copy_url_status'])) {
            $data['wishlists_copy_url_status'] = $this->request->post['wishlists_copy_url_status'];
        } else {
            $data['wishlists_copy_url_status'] = $this->config->get('wishlists_copy_url_status');
        }

        if (isset($this->request->post['wishlists_socials'])) {
            $data['active_social_list'] = $this->request->post['wishlists_socials'];
        } else {
            $data['active_social_list'] = ($this->config->get('wishlists_socials') != '')?explode(",",$this->config->get('wishlists_socials')):array();
        }


        $data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/wishlists', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/wishlists')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}



    public function install() {

        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "wishlist` (
                          `wishlist_id` int(11) NOT NULL AUTO_INCREMENT,
                          `wishlist_name` varchar(250) NOT NULL,
                          `customer` int(11) NOT NULL,
                          `visiblity` int(11) NOT NULL,
                          `status` enum('1','0') NOT NULL,
                          `created_by` int(11) NOT NULL,
                          `created_on` datetime NOT NULL,
                          `edited_by` int(11) NOT NULL,
                          `edited_on` datetime NOT NULL,
                          PRIMARY KEY (wishlist_id)
                        )");

        $this->db->query("CREATE TABLE IF NOT EXISTS `oc_wishlistitems` (
                          `wishlist_item_id` int(11) NOT NULL AUTO_INCREMENT,
                          `wishlist_id` int(11) NOT NULL,
                          `product_id` int(11) NOT NULL,
						  `purchased_by` int(11) NOT NULL,
                          `added_on` date NOT NULL,
						  `purchased_on` datetime DEFAULT NULL,
                          PRIMARY KEY (wishlist_item_id)
                        )");

        
        //set status enable
        $enable=1;

        $this->load->model('setting/setting');

        $this->model_setting_setting->editSetting('wishlists',array('wishlists_status'=>$enable));

    }

    public function uninstall() {

        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "wishlistitems`");

        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "wishlist`");

        $this->load->model('setting/setting');

        $this->model_setting_setting->deleteSetting('wishlists');

    }
}
