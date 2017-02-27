<?php
class ControllerCommonCmenu extends Controller {

    public function getVeryTopMenu(){
        return $this->getOnemenu('very_top');
    }

    public function getFooterMenu(){
        return $this->getOnemenu('footer');
    }

    public function getOnemenu($menu_name){
        $sql = "SELECT * FROM " . DB_PREFIX . "cmenu i LEFT JOIN " . DB_PREFIX . "cmenu_title id ON (i.cmenu_id = id.cmenu_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "' AND menu_name ='" . $menu_name . "' ORDER BY sort_order ASC";
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

	public function index($settings) {

		//$data = array();

        //		$sql = "SELECT * FROM " . DB_PREFIX . "cmenu i LEFT JOIN " . DB_PREFIX . "cmenu_title id ON (i.cmenu_id = id.cmenu_id) WHERE id.language_id = '" . (int)$this->config->get('config_language_id') . "' AND menu_name ='" . $menu_name . "' ORDER BY sort_order ASC";

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

		if ($level == 0) {
			foreach ($menu as $item){
				if ($item['parent_id'] == $parentid) { 
					$result .= '<li class="dropdown">';
					$result .= '<a class="dropdown-toggle" href="'. $item['href'] .'">'. $item['title'] . $item['caret'] . '</a>';
					$result .= $this->buildMenu($menu, $item['cmenu_id']); 
					$result .= "</li>";
				} 
			} 
			return $result;
		}
		else {
			foreach ($menu as $item){
				if ($item['parent_id'] == $parentid) { 
					$result .= '<li>';
					$result .= '<a href="'. $item['href'] .'">'. $item['title'] . $item['caret'] . '</a>';
					$result .= $this->buildMenu($menu, $item['cmenu_id']); 
					$result .= "</li>";
				} 
			} 
			return $result ?  "\n<div class='dropdown-menu'><div class='dropdown-inner'><ul class='list-unstyled'>\n$result</ul></div></div>\n" : null; 
		}
	} 
}
