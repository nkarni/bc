<?php
class ControllerAccountWishLists extends Controller {
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/wishlists', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('account/wishlists');

		$this->load->model('account/wishlists');

        $this->load->model('catalog/product');

		$this->load->model('tool/image');

		$this->document->setTitle($this->language->get('heading_title'));
	
	
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('account/wishlists')
		);

        if (isset($this->request->get['remove'])) {

            $remove_id = $this->request->get['remove'];

            $this->model_account_wishlists->removeWishlist($remove_id);

            $this->session->data['success'] = $this->language->get('text_remove');

            $this->response->redirect($this->url->link('account/wishlists'));
        }

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_empty'] = $this->language->get('text_empty');
        $data['text_visiblity_private'] = $this->language->get('text_visiblity_private');
        $data['text_visiblity_public'] = $this->language->get('text_visiblity_public');

		$data['column_image'] = $this->language->get('column_image');
		$data['column_name'] = $this->language->get('column_name');
		$data['column_model'] = $this->language->get('column_model');
		$data['column_stock'] = $this->language->get('column_stock');
		$data['column_price'] = $this->language->get('column_price');
		$data['column_action'] = $this->language->get('column_action');
        $data['column_share'] = $this->language->get('column_share');


        $data['column_name'] = $this->language->get('column_name');
        $data['column_visiblity'] = $this->language->get('column_visiblity');
        $data['column_action'] = $this->language->get('column_action');
        $data['column_purchasecount'] = $this->language->get('column_purchasecount');
        $data['column_status'] = $this->language->get('column_status');


		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_remove'] = $this->language->get('button_remove');

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['wishlists'] = array();

        $results = $this->model_account_wishlists->getWishlists();

		foreach ($results as $result) {

            $totalitems = $this->model_account_wishlists->getCurrentWishlistItemsCount($result['wishlist_id']);
            $boughtitems = $this->model_account_wishlists->getCurrentWishlistPurchasedItemsCount($result['wishlist_id'],0,$result['customer']);

            $data['wishlists'][] = array(
                'wishlist_id'   => $result['wishlist_id'],
                'wishlist_name' => $result['wishlist_name'],
                'visiblity'     => $result['visiblity'],
                'status'        => $result['status'],
                'purchasecount' => $boughtitems."/".$totalitems,
                'href'          => $this->url->link('account/wishlists/mywishlist', 'wishlist_id=' . $result['wishlist_id']),
                'remove'        => $this->url->link('account/wishlists', 'remove=' . $result['wishlist_id'])
            );

		}

		$data['continue'] = $this->url->link('account/account', '', 'SSL');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/wishlists.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/wishlists.tpl', $data));
		} else {
			//exit;
			
		$this->response->setOutput($this->load->view('account/wishlists', $data));
		}
	}

    public function mywishlist() {


        $wishlist_id = $this->request->get['wishlist_id'];
        
        $this->load->language('account/wishlists');

        $this->load->model('account/wishlists');

        $this->load->model('catalog/product');

        $this->load->model('tool/image');

        $this->document->setTitle($this->language->get('heading_title'));

        $wishlist_info = $this->model_account_wishlists->getWishlist($wishlist_id);

        if (!$this->customer->isLogged() && $wishlist_info['visiblity'] != 1) {
			
			$this->session->data['redirect'] = $this->url->link('account/wishlist', '', true);
			
			$this->response->redirect($this->url->link('account/login', '', true));
        }
        
        $customer = $wishlist_info['customer'];

        $wishlist_name = $wishlist_info['wishlist_name'];

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_account'),
            'href' => $this->url->link('account/account', '', 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('account/wishlists')
        );

        $data['breadcrumbs'][] = array(
            'text' => $wishlist_name,
            'href' => $this->url->link('account/wishlists/mywishlist','wishlist_id='.$wishlist_id)
        );
		
        $data['visiblity'] = $wishlist_info['visiblity'];

        $data['mysharelink'] = $this->url->link('account/wishlists/mywishlist','wishlist_id='.$wishlist_id);

        if (isset($this->request->get['remove'])) {
            $remove_id = $this->request->get['remove'];

            $this->model_account_wishlists->removeWishlistitem($wishlist_id,$remove_id);

            $this->session->data['success'] = $this->language->get('text_remove');

            $this->response->redirect($this->url->link('account/wishlists/mywishlist','wishlist_id='.$wishlist_id));
        }

        $data['islogged'] = ($this->customer->isLogged())?$this->customer->getId():0;

        $data['created_on'] = date("d/m/Y",strtotime($wishlist_info['created_on']));

        $this->load->model("account/customer");

        $customerdata = $this->model_account_customer->getCustomer($wishlist_info['created_by']);

        $data['created_by'] = '';
        
        if($customerdata){
			$data['created_by'] = $customerdata['firstname']." ".$customerdata['lastname'];
        }
        $data['heading_title'] = $wishlist_name;

        $data['text_empty'] = $this->language->get('text_empty');
        $data['text_tax'] = $this->language->get('text_tax');

        $data['column_image'] = $this->language->get('column_image');
        $data['column_name'] = $this->language->get('column_name');
        $data['column_model'] = $this->language->get('column_model');
        $data['column_stock'] = $this->language->get('column_stock');
        $data['column_price'] = $this->language->get('column_price');
        $data['column_action'] = $this->language->get('column_action');

        $data['column_name'] = $this->language->get('column_name');
        $data['column_visiblity'] = $this->language->get('column_visiblity');
        $data['column_action'] = $this->language->get('column_action');
        $data['column_purchasecount'] = $this->language->get('column_purchasecount');
        $data['column_status'] = $this->language->get('column_status');

        $data['button_continue'] = $this->language->get('button_continue');
        $data['button_cart'] = $this->language->get('button_cart');
        $data['button_remove'] = $this->language->get('button_remove');

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        $data['wishlistitems'] = array();
        
        $data['is_owner'] = ($data['islogged'] == $customer)?1:0;

        $results = $this->model_account_wishlists->getWishlistItems($wishlist_id,"All");
/*
        echo '<pre>';
        print_r($results);
        echo '</pre>';
*/

        $product_total = count($results);

        foreach ($results as $result) {

            $purchase_count = $this->model_account_wishlists->getCurrentWishlistPurchasedItemsCount($wishlist_id,$result['product_id'],$customer);

            $product_info = $this->model_catalog_product->getProduct($result['product_id']);

            $is_bought = $this->model_account_wishlists->isproductpurchased($wishlist_id,$result['product_id'],$customer);

            $purchased_by = $this->model_account_wishlists->getCustomerName($is_bought);
            
            
            if(is_array($is_bought)){
                $bought_by = $this->model_account_wishlists->getCustomerName($is_bought['purchased_by']);
                $bought_on = ($is_bought['purchased_on']== '')? '' : " On ".date("d/m/Y H:m:s",strtotime($is_bought['purchased_on'])) ;

                $purchased_by =  ($customer == $is_bought['purchased_by']) ? "Purchased by ".$bought_by.$bought_on : "Purchased by ".$bought_by.$bought_on;

                $is_bought =$is_bought['purchased_by'];
            }
			
            if ($product_info) {
                if ($product_info['image']) {
                    //$image = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_wishlist_width'), $this->config->get('config_image_wishlist_height'));
					$image = $this->model_tool_image->cropsize($product_info['image'], 150, 150);
                } else {
                    $image = false;
                }
                
                if ($product_info['price']) {
					$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')),$this->session->data['currency']);
				} else {
					$price = false;
				}

				if ((float)$product_info['special']) {
					$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')),$this->session->data['currency']);
				} else {
					$special = false;
				}

                if ($this->config->get('config_tax')) {
                    $tax = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price'],$this->session->data['currency']);
                } else {
                    $tax = false;
                }

                $data['wishlistitems'][] = array(
                    'product_id'   => $result['product_id'],
                    'product_name' => $product_info['name'],
                    'thumb'      => $image,
                    'price' => $price,
                    'price_num' => $product_info['price'],
                    'minimum' => $product_info['minimum'],
                    'special' => $special,
                    'tax' => $tax,
                    'purchase_count' => $purchase_count,

                    'is_bought' => $is_bought,
                    'customer' => $customer,
                    'purchased_by' => $purchased_by,

                    'href'       => $this->url->link('product/product', 'product_id=' . $product_info['product_id']),
                    'remove'        => $this->url->link('account/wishlists/mywishlist', 'remove=' . $result['product_id']."&wishlist_id=".$wishlist_id)
                );

            }
        }

        $data['social_list'] = array(
            'fb'=>'facebook',
            'tw'=>'twitter',
            'gp'=>'google',
            'li'=>'linkedin',
            'tm'=>'tumblr',
            'pt'=>'pinterest',
        );

        $data['wishlists_status']=$this->config->get('wishlists_status');
        $data['wishlists_copy_url_status']=$this->config->get('wishlists_copy_url_status');
        $data['wishlists_socials']=($this->config->get('wishlists_socials') != '')?explode(",",$this->config->get('wishlists_socials')):array();

        $page = 1;
        $limit = 10;

        $url = '';

        $pagination = new Pagination();
        $pagination->total = $product_total;
        $pagination->page = $page;
        $pagination->limit = $limit;
        $pagination->url = $this->url->link('account/wishlists/mywishlist&wishlist_id='.$wishlist_id);

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));

        $data['limit'] = $limit;

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');
	
		
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/mywishlists.tpl')) {
            $this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/mywishlists.tpl', $data));
        } else {
//$this->response->setOutput($this->load->view('default/template/account/mywishlists.tpl', $data));
$this->response->setOutput($this->load->view('account/mywishlists.tpl', $data));
        }
		//$this->response->setOutput($this->load->view('account/wishlist', $data));
    }

	public function add() {

		$this->load->language('account/wishlists');

		$json = array();

        if (isset($this->request->post['wishlist_name'])) {
            $wishlist_name = $this->request->post['wishlist_name'];
        } else {
            $wishlist_name = '';
        }

        if (isset($this->request->post['wishlist_id'])) {
            $wishlist_id = $this->request->post['wishlist_id'];
        } else {
            $wishlist_id = 0;
        }

		if (isset($this->request->post['product_id'])) {
			$product_id = $this->request->post['product_id'];
		} else {
			$product_id = 0;
		}

        $this->load->model('account/wishlists');

		$this->load->model('catalog/product');

        $product_info = $this->model_catalog_product->getProduct($product_id);

		if ($product_info) {

            if ($this->customer->isLogged()) {

                $customer = $this->customer->getId();

                $wishlist_id = ($wishlist_id ==0)?$this->model_account_wishlists->getWishlistId($wishlist_name,$customer):$wishlist_id;

                $wishlists = $this->model_account_wishlists->getWishlistItems($wishlist_id);

                if ($wishlist_id){ //Existing wishlists

                    $wishlist_name = ($wishlist_name == '')?$this->model_account_wishlists->getWishlistName($wishlist_id):$wishlist_name;

                    if(!in_array($product_id, $wishlists) ){

                        $this->model_account_wishlists->addWishlistitem($product_id,$wishlist_id);

                        $json['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'product_id=' . (int)$this->request->post['product_id']), $product_info['name'], $this->url->link('account/wishlists/mywishlist','wishlist_id='.$wishlist_id), $wishlist_name);

                    }else {
                        $json['info'] = sprintf($this->language->get('text_exists'), $this->url->link('product/product', 'product_id=' . (int)$this->request->post['product_id']), $product_info['name'],$this->url->link('account/wishlists/mywishlist','wishlist_id='.$wishlist_id), $wishlist_name);
                    }

                }
                else{ // New Wishlists
                    $data_wishlist = array(
                        'wishlist_name' => $wishlist_name,
                        'visiblity' => 1,
                        'status' => 1
                    );

                    $wishlist_id=$this->model_account_wishlists->addWishlists($data_wishlist);

                    $this->model_account_wishlists->addWishlistitem($product_id,$wishlist_id);

//                    $json['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'product_id=' . (int)$this->request->post['product_id']), $product_info['name'], $this->url->link('account/wishlists/mywishlist'));

                    $json['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'product_id=' . (int)$this->request->post['product_id']), $product_info['name'], $this->url->link('account/wishlists/mywishlist','wishlist_id='.$wishlist_id), $wishlist_name);

                }
            } else {

//                $json['info'] = sprintf($this->language->get('text_login'), $this->url->link('account/login', '', 'SSL'), $this->url->link('account/register', '', 'SSL'), $this->url->link('product/product', 'product_id=' . (int)$this->request->post['product_id']), $product_info['name'], $this->url->link('account/wishlist'));
                $json['info'] = sprintf($this->language->get('text_exists'), $this->url->link('product/product', 'product_id=' . (int)$this->request->post['product_id']), $product_info['name'],$this->url->link('account/wishlists/mywishlist','wishlist_id='.$wishlist_id), $wishlist_name);
            }

            $wishlists = $this->model_account_wishlists->getWishlistItems($wishlist_id);

//			$json['total'] = sprintf($this->language->get('text_wishlist'), (is_array($wishlists) ? count($wishlists) : 0));
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

//Edit wishlist
	public function edit() {

        $this->load->model('account/wishlists');

        $this->load->model('catalog/product');

        $json = array();

        $data['text_success'] = $this->language->get('text_success');

        if (isset($this->request->post['wishlist_id'])) {
            $wishlist_id = $this->request->post['wishlist_id'];
        } else {
            $wishlist_id = 0;
        }

        if($wishlist_id != 0) {

            $data['wishlist_id'] = $wishlist_id;

            $wishlist = $this->model_account_wishlists->getWishlist($wishlist_id);

            if ($wishlist) {

                if (isset($this->request->post['wishlist_name'])) {
                    $data['wishlist_name'] = $this->request->post['wishlist_name'];
                }
                elseif($wishlist['wishlist_name']){
                    $data['wishlist_name'] = $wishlist['wishlist_name'];
                }
                else {
                    $data['wishlist_name'] = '';
                }

                if (isset($this->request->post['visiblity'])) {
                    $data['visiblity'] = $this->request->post['visiblity'];
                }
                elseif($wishlist['visiblity']){
                    $data['visiblity'] = $wishlist['visiblity'];
                }
                else {
                    $data['visiblity'] = 0;
                }

                if (isset($this->request->post['status'])) {
                    $data['status'] = $this->request->post['status'];
                }
                elseif($wishlist['status']){
                    $data['status'] = $wishlist['status'];
                }
                else {
                    $data['status'] = 0;
                }
                
                
                if (isset($this->request->post['purchased_by'])) {
                    $data['purchased_by'] = $this->request->post['purchased_by'];
                }
                elseif($wishlist['purchased_by']){
                    $data['purchased_by'] = $wishlist['purchased_by'];
                }
                else {
                    $data['purchased_by'] = 0;
                }
                
                
                $this->model_account_wishlists->editWishlists($data);

                $json['success'] = "Your wishList has updated Successfully!.";

            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));


	}
	
	public function editWishlistitem(){

        $this->load->model('account/wishlists');

        $this->load->model('catalog/product');

        $json = array();

        $data['text_success'] = $this->language->get('text_success');

        if (isset($this->request->post['wishlist_id'])) {
            $wishlist_id = $this->request->post['wishlist_id'];
        } else {
            $wishlist_id = 0;
        }

        if (isset($this->request->post['product_id'])) {
            $product_id = $this->request->post['product_id'];
        } else {
            $product_id = 0;
        }

        if($wishlist_id != 0 && $product_id != 0 ) {

            $data['wishlist_id'] = $wishlist_id;
            $data['product_id'] = $product_id;

            $wishlist = $this->model_account_wishlists->getWishlistitem($wishlist_id,$product_id);

            if ($wishlist) {
               
               if($this->customer->getId()){
                    $data['purchased_by'] = $this->customer->getId();;
                }
                else {
                    $data['purchased_by'] = 0;
                }                   
                
                $this->model_account_wishlists->editWishlistitem($data);

                $json['success'] = "Your wishList has updated Successfully!.";

            }
        }
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
		
	}



    /* Filter Wishlist name */
    public function autocomplete(){

        $json = array();

        if (isset($this->request->get['wishlist_name'])) {

            $this->load->model('account/wishlists');

            if (isset($this->request->get['wishlist_name'])) {
                $wishlist_name = $this->request->get['wishlist_name'];
            } else {
                $wishlist_name = '';
            }

            $filter_data = array(
                'wishlist_name'  => $wishlist_name,

            //    'start'        => 0,
            //    'limit'        => $limit
            );

            $results = $this->model_account_wishlists->getWishlists($filter_data);

            foreach ($results as $result) {
                $option_data = array();


                $json[] = array(
                    'wishlist_id' => $result['wishlist_id'],
                    'wishlist_name'       => $result['wishlist_name']
                );
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));

    }
//getwishlists
    public function getwishlists(){

        $json = array();
        
        $customer = ($this->customer->isLogged())?$this->customer->getId():0;
        

        if ($customer) {

            $this->load->model('account/wishlists');
            
            $wishparams['customer']= $customer;
          
            $filter_data = array(
                'customer'  => $customer,
            );

            $results = $this->model_account_wishlists->getWishlists($filter_data);

            foreach ($results as $result) {
                $json[$result['wishlist_id']] = $result['wishlist_name'];
                
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));

    }
}
