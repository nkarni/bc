<?php
class ControllerExtensionModuleCmenu extends Controller {
	private $error = array();

	public function index() {

		//$this->load->language('module/cmenu');

		$this->document->setTitle('Menus Manager');

		$this->load->model('setting/setting');
		
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('cmenu', $this->request->post);

			$this->session->data['success'] = 'Success';

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		//check table to create
		$cmenu = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "cmenu` (`cmenu_id` int(11) NOT NULL auto_increment, `type` varchar(64) collate utf8_bin NOT NULL default '', `parent_id` int(11) NOT NULL default '0', `sort_order` int(11) NOT NULL default '0', `value` int(11) NOT NULL default '0', `url` varchar(255) collate utf8_bin NOT NULL, `menu_name` varchar(255) collate utf8_bin NOT NULL, PRIMARY KEY  (`cmenu_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;";
		$this->db->query($cmenu);
		$cmenu_descriptions = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "cmenu_title` (`cmenu_id` int(11) NOT NULL default '0', `language_id` int(11) NOT NULL default '0', `title` varchar(64) collate utf8_bin NOT NULL default '', PRIMARY KEY  (`cmenu_id`,`language_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;";
		$this->db->query($cmenu_descriptions);
		$cmenu_to_store = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "cmenu_to_store` (`cmenu_id` int(11) NOT NULL, `store_id` int(11) NOT NULL, PRIMARY KEY  (`cmenu_id`, `store_id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;";
		$this->db->query($cmenu_to_store);
		//
		
		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		
		$data['token'] = $this->session->data['token'];
		
		$data['heading_title'] = 'Custom Menu';
		
		$data['text_edit'] = 'Edit';

		$data['button_save'] = 'Save';
		$data['button_cancel'] = 'Cancel';

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/cmenu', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('extension/module/cmenu', 'token=' . $this->session->data['token'], 'SSL');
		$data['add'] = $this->url->link('extension/module/cmenu/addMenu', 'token=' . $this->session->data['token'], 'SSL');
		$data['update'] = $this->url->link('extension/module/cmenu/updateMenu', 'token=' . $this->session->data['token'], 'SSL');
		$data['editItem'] = $this->url->link('extension/module/cmenu/editItem', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'], 'SSL');

		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		// add multi menu adjustment
        $data['menus'] = array('Very Top' => 'very_top', 'Footer' => 'footer');

		$this->response->setOutput($this->load->view('extension/module/cmenu.tpl', $data));
	}
	
	public function addItem(){
		$json = array();
		if(isset($this->request->post)){
			$this->addItemSql($this->request->post);
			$json['success'] = 'Success';
		}else{
			$json['error'] = 'Error: do not exit data';
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function addItemSql($data) {
		
		$query = $this->db->query("SELECT MAX(sort_order) AS position FROM " . DB_PREFIX . "cmenu");
		$sort_order = (int)$query->row['position'] + 1;
		
		$this->db->query("INSERT INTO " . DB_PREFIX . "cmenu SET url = '" . $data['url'] . "', sort_order = '" . (int)$sort_order . "', type = '" . $data['type'] . "', value = '" . $data['value'] . "' , menu_name = '" . $data['menu_name'] . "', parent_id = '0'");
		$cmenu_id = $this->db->getLastId();
		
		foreach ($data['title'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "cmenu_title SET cmenu_id = '" . (int)$cmenu_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value) . "'");
		}
	
		if (isset($data['cmenu_store'])) {
			foreach ($data['cmenu_store'] as $store_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "cmenu_to_store SET cmenu_id = '" . (int)$cmenu_id . "', store_id = '" . (int)$store_id . "'");
			}
		}
	}
		
	public function updateMenu(){
		$json = array();
		if(isset($this->request->post)){
			$menu = $this->request->post['menu'];
			$this->sortOrderMenu($menu,0);
			$json['success'] ='success';
		}else{
			$json['error'] = 'error: do not pass data';
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function sortOrderMenu($menu,$parent_id){
		$i = 0;
		foreach($menu as $val){
			
			$this->db->query("UPDATE " . DB_PREFIX . "cmenu SET sort_order = '" . $i . "', parent_id='" . $parent_id . "' WHERE cmenu_id='" . $val['id'] . "'");
			if(isset($val['children'])){
				$this->sortOrderMenu($val['children'],$val['id']);
			}
			$i++;
		}
		
		return true;
	}
	
	public function updateItem(){
		$json = array();
		if(isset($this->request->get['id']) && $this->request->post){
			$cmenu_id = $this->request->get['id'];
			$menu = $this->request->post;
			//check children
			$this->db->query("UPDATE " . DB_PREFIX . "cmenu SET url='". $menu['curl'] ."'  WHERE cmenu_id='" . $cmenu_id . "'");
			
			$this->db->query("DELETE FROM " . DB_PREFIX . "cmenu_title WHERE cmenu_id='" . $cmenu_id . "'");
			foreach ($menu['ctitle'] as $language_id => $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "cmenu_title SET cmenu_id = '" . (int)$cmenu_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value) . "'");
			}
			$json['message'] = 'success!';
		}else{
			$json['message'] = 'error';
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function deleteItem(){
		$json = array();
		if(isset($this->request->get['id'])){
			$cmenu_id = $this->request->get['id'];
			//check children
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cmenu WHERE parent_id='" . $cmenu_id . "'");
			if($query->rows){
				foreach($query->rows as $val){
					$this->db->query("UPDATE " . DB_PREFIX . "cmenu SET parent_id='0' WHERE cmenu_id='" . $val['cmenu_id'] . "'");
				}
			}
			$this->db->query("DELETE FROM " . DB_PREFIX . "cmenu WHERE cmenu_id='" . $cmenu_id . "'");
			$json['success'] = 'success!';
		}else{
			$json['error'] = 'error';
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function menuHtml(){
		$json = array();
		$sql = "SELECT * FROM " . DB_PREFIX . "cmenu i LEFT JOIN " . DB_PREFIX . "cmenu_title id ON (i.cmenu_id = id.cmenu_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "' AND menu_name = '" .  htmlspecialchars($_REQUEST['menu_name']) . "' ORDER BY sort_order ASC";
		$query = $this->db->query($sql);
		$results = $query->rows;
		
		$menu = array();
		foreach($results as $result){
			$cmenu_title_data = array();
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cmenu_title WHERE cmenu_id = '" . (int)$result['cmenu_id'] . "'");
			foreach ($query->rows as $val) {
				$cmenu_title_data[$val['language_id']] = array(
					'title'            => $val['title']
				);
			}
			
			$menu[] = array(
				'cmenu_id' => $result['cmenu_id'],
				'parent_id' => $result['parent_id'],
				'type' => $result['type'],
				'url' => $result['url'],
				'title' => $result['title'],
				'menu_title_data' => $cmenu_title_data
			);
		}

		$json['menu'] = $this->buildMenu($menu,0);
		
		echo $json['menu'];
	}
	
	public function buildMenu($menu, $parentid) 
	{ 
	  //$result = '<div class="dd">';
	  //$result .= '<ol class="dd-list">';
	  $result = null;
	  foreach ($menu as $item){
		if ($item['parent_id'] == $parentid) { 
			$result .= '<li class="dd-item" data-id="' . $item['cmenu_id'] . '">
					    <div class="dd-handle" id="' . $item['cmenu_id'] . '">'. $item['title'] . '<span class="pull-right"><a class="button-delete text-success dd-nodrag" style="cursor:pointer;" onclick="$(\'#item_'. $item['cmenu_id'] .'\').css(\'display\',\'block\');" >Edit</a> | <a style="cursor:pointer;" class="button-delete text-danger dd-nodrag" onclick="deleteItem(' . $item['cmenu_id'] . ');">Delete</a></span></div>';
			if($item['menu_title_data']){
				$result .= '<div style="display:none;" class="row" id="item_'. $item['cmenu_id'] .'"><form id="form_' . $item['cmenu_id'] . '" class="item_detail" >';
				$result .= '<b>Menu Type:</b>' . $item['type'] . '<br />';
				foreach($item['menu_title_data'] as $language_id => $item_detail){
					$result .= '<div class="col-sm-5"><input type="text" name="ctitle[' . $language_id .']" value="' . $item_detail['title'] . '" class="form-control" /></div>';
				}
				if($item['type'] == 'custom'){
					
					$result .= '<br /><div class="col-sm-12">url: <input type="text" name="curl" value="'. $item['url'] .'" class="form-control"/></div>';
				}
				$result .= '<div class="row"></div>';
				$result .= '<div class="col-sm-5"><a style="cursor:pointer;" onclick="updateItem(' . $item['cmenu_id'] . ');">Save</a> | <a onclick="$(\'#item_'. $item['cmenu_id'] .'\').css(\'display\',\'none\');" style="cursor:pointer;">Close</a></div>';
				
				$result .= '</form></div>';
			}
			
			$result .= $this->buildMenu($menu, $item['cmenu_id']); 
			$result .= "</li>";
		} 
		  
	  }
	 // $result .= "</ol></div>";
	     
	  return $result ?  "\n<ol class=\"dd-list\">\n$result</ol>\n" : null; 
	} 
	
	public function categoryAutocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			$this->load->model('catalog/category');

			$filter_data = array(
				'filter_name' => $this->request->get['filter_name'],
				'sort'        => 'name',
				'order'       => 'ASC',
				'start'       => 0,
				'limit'       => 15
			);

			$results = $this->model_catalog_category->getCategories($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'id' => $result['category_id'],
					'name'        => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
				);
			}
		}

		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function productAutocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			$this->load->model('catalog/product');
			//$this->load->model('catalog/option');

			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}
			
			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 15;
			}

			$filter_data = array(
				'filter_name'  => $filter_name,
				'start'        => 0,
				'limit'        => $limit
			);

			$results = $this->model_catalog_product->getProducts($filter_data);

			foreach ($results as $result) {
				
				$json[] = array(
					'id' => $result['product_id'],
					'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function ManufacturerAutocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			$this->load->model('catalog/manufacturer');

			$filter_data = array(
				'filter_name' => $this->request->get['filter_name'],
				'start'       => 0,
				'limit'       => 15
			);

			$results = $this->model_catalog_manufacturer->getManufacturers($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'id' => $result['manufacturer_id'],
					'name'            => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
				);
			}
		}

		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function informationAutocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			//$this->load->model('catalog/infomation');

			$filter_data = array(
				'filter_name' => $this->request->get['filter_name'],
				'start'       => 0,
				'limit'       => 15
			);
			$sql = "SELECT * FROM " . DB_PREFIX . "information i LEFT JOIN " . DB_PREFIX . "information_description id ON (i.information_id = id.information_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "'";

			$sql .= " AND id.title LIKE '%" . $this->request->get['filter_name'] . "%'";
		
			$sql .= " ORDER BY id.title ASC";
			

			$sql .= " LIMIT 0,10";

			$query = $this->db->query($sql);
			$results = $query->rows;
			//$results = $this->getInformations($filter_data);
			
			if($results){
				
				foreach ($results as $result) {
					//print_r($result);
					$json[] = array(
						'id' => $result['information_id'],
						'name' => strip_tags(html_entity_decode($result['title'], ENT_QUOTES, 'UTF-8'))
					);
				}
			}
			
		}

		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/cmenu')) {
			$this->error['warning'] = 'You do not have permission';
		}

		return !$this->error;
	}
}