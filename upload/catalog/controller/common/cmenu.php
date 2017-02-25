<?php
class ControllerCommonCmenu extends Controller {
	public function index() {
		//$data = array();
		$sql = "SELECT * FROM " . DB_PREFIX . "cmenu i LEFT JOIN " . DB_PREFIX . "cmenu_title id ON (i.cmenu_id = id.cmenu_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY sort_order ASC";
		$query = $this->db->query($sql);
		$results = $query->rows;
		
		$menu = array();
		foreach($results as $result){
			
			if($result['type'] == 'custom'){
				$href = $result['url'];
			}elseif($result['type'] == 'category'){
				$href = $this->url->link('product/category', 'path=' . $result['value']);
			}elseif($result['type'] == 'product'){
				$href = $this->url->link('product/product', 'product_id=' . $result['value']);
			}elseif($result['type'] == 'information'){
				$href = $this->url->link('information/information', 'information_id=' . $result['value']);
			}elseif($result['type'] == 'manufacturer'){
				$href = $this->url->link('manufacturer/manufacturer', 'manufacturer_=' . $result['value']);
			}else{
				$href ='#';
			}
			
			$sql = "SELECT * FROM " . DB_PREFIX . "cmenu WHERE parent_id = '" . (int)$result['cmenu_id'] . "'";
			$query = $this->db->query($sql);
			if(count($query->rows) > 0 && $result['parent_id'] != 0){
				$caret = '<span style="float:right;"> » </span>';
			}else{
				$caret ='';
			}
		
			$menu[] = array(
				'cmenu_id' => $result['cmenu_id'],
				'parent_id' => $result['parent_id'],
				'type' => $result['type'],
				'url' => $result['url'],
				'href' => $href,
				'caret' => $caret,
				'title' => $result['title']
			);
		}
		
		$data['cmenu'] = $this->buildMenu($menu,0);
		return $this->load->view('common/cmenu', $data);
	}
	
	
	
	public function buildMenu($menu, $parentid) 
	{ 
		  //$result = '<div class="dd">';
		  //$result .= '<ol class="dd-list">';
		$result = null;
		$level = $parentid;
		foreach ($menu as $item){
			if ($item['parent_id'] == $parentid) { 
				$result .= '<li>';
				$result .= '<a href="'. $item['href'] .'">'. $item['title'] . $item['caret'] . '</a>';
				$result .= $this->buildMenu($menu, $item['cmenu_id']); 
				$result .= "</li>";
			} 
			  
		} 
		if($level == 0){
			return $result;
		}
		return $result ?  "\n<ul>\n$result</ul>\n" : null; 
	} 
}
