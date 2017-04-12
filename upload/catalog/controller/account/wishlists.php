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

	public function requestQuotation(){



        $this->load->model('account/wishlists');
        $this->load->model('account/address');
        $json = array();
        $str = '';

        // get wishlist
        $data = $this->mywishlist(false);

        $customerdata = $this->model_account_customer->getCustomer($this->customer->getId());
        // need to get address from $customerdata['address_id']
        $address = $this->model_account_address->getAddress($customerdata['address_id']);

         $customer_details_str = '<p><strong>Customer Details:</strong><br>
            Name: ' . $customerdata['firstname'] . ' '  . $customerdata['lastname'] . '<br>
            Email: <a href="mailto:' . $customerdata['email'] . '">'  . $customerdata['email'] . '</a><br>
            Phone: '  . $customerdata['telephone'] . '<br><br>
            Address:<br>'
            . $address['address_1'] . '<br>'
            . ($address['address_2'] != '' ? '(Address line 2: '  . $address['address_2'] . '<br>' : '') .
             $address['postcode'] . '<br>'
            . $address['city'] . '<br>'
            . $address['country'] . '
        </p>';

        $items = '<br><br><table cellpadding="10" style="width:100%"><TH  valign="top" align="left" style="width:40%">Product</TH><TH valign="top" align="left" style="width:40%">Options</TH><TH valign="top" align="left">Qty</TH>';

        foreach ($data['wishlistitems'] as $wishlistitem) {

            $items .= '<tr>';
            $items .= '<td valign="top" align="left"><a href="' .  $wishlistitem["href"]. '">' .  $wishlistitem['product_name'] . '</a></td>';
            $items .= '<td valign="top" align="left">';
            foreach ($wishlistitem['full_product_data'][0]['option'] as $option) {
                $items .= '<small>' . $option['name'] . ': ' . $option['value'] . '</small><br>';
            }
            $items .= '</td>';
            $items .= '<td valign="top" align="left">' . $wishlistitem['quantity'] . '</td>';
            $items .= '</tr>';
        }

        $str = '<h2>Quote request for wishlist: ' . $data['heading_title'] . '</h2>';
        $str .= $customer_details_str . $items;




        // get admin email

        // send email
        $subject = sprintf('Quote request for wishlit - ', html_entity_decode( $data['heading_title'] , ENT_QUOTES, 'UTF-8'));

        $mail = new Mail();
        $mail->protocol = $this->config->get('config_mail_protocol');
        $mail->parameter = $this->config->get('config_mail_parameter');
        $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
        $mail->smtp_username = $this->config->get('config_mail_smtp_username');
        $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
        $mail->smtp_port = $this->config->get('config_mail_smtp_port');
        $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

        $mail->setTo($this->config->get('config_email'));
        $mail->setFrom($this->config->get('config_email'));
        $mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
        $mail->setSubject($subject);
        $mail->setHtml($str);
        $mail->send();

        $json['success'] = 'Your quote request was sent, we will be in touch shortly.'; // no way to know if send worked!!!

        // return a message to user
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function mywishlist($loadTemplate = true) {

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
        $data['wishlist_id'] = $wishlist_id;

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
        $data['button_update'] = $this->language->get('button_update');

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        $data['wishlistitems'] = array();
        
        $data['is_owner'] = ($data['islogged'] == $customer)?1:0;

        $results = $this->model_account_wishlists->getWishlistItems($wishlist_id,"All");

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

                // get options data
                $option_data = array();

                $full_product_data = $this->getProductWithOptions($result);


/*
                foreach (json_decode($result['options']) as $option) {
                    if ($option['type'] != 'file') {
                        $value = $option['value'];
                    } else {
                        $upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

                        if ($upload_info) {
                            $value = $upload_info['name'];
                        } else {
                            $value = '';
                        }
                    }

                    $option_data[] = array(
                        'name'  => $option['name'],
                        'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
                    );
                }
*/

/*
                        echo '<pre>';
                        print_r($result);
                print_r(json_decode($result['options']));
                        echo '</pre>';

                echo '<pre>';
                print_r($option_data);
                echo '</pre>';
*/



                $data['wishlistitems'][] = array(
                    'product_id'   => $result['product_id'],
                    'wishlist_item_id'   => $result['wishlist_item_id'],
                    'product_name' => $product_info['name'],
                    'thumb'      => $image,
                    'price' => $price,
                    'price_num' =>$full_product_data[0]['total'],
                    'minimum' => $product_info['minimum'],
                    'special' => $special,
                    'tax' => $tax,
                    'purchase_count' => $purchase_count,

                    'is_bought' => $is_bought,
                    'customer' => $customer,
                    'purchased_by' => $purchased_by,

                    'href'       => $this->url->link('product/product', 'product_id=' . $product_info['product_id']),
                    'remove'        => $this->url->link('account/wishlists/mywishlist', 'remove=' . $result['product_id']."&wishlist_id=".$wishlist_id),
                    'full_product_data' => $full_product_data,
                    'formatted_price' => $this->currency->format($full_product_data[0]['total'],  $this->session->data['currency']),
                    'short_description' => html_entity_decode($product_info['short_description'], ENT_QUOTES, 'UTF-8'),
                    'quantity' => $result['quantity']
                );

            }
        }
        if(! $loadTemplate){
            $data['customerdata'] = $customerdata;
            return $data;
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
        if(!$loadTemplate){
            $pagination->limit = 999;
        }
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

        if(!$loadTemplate){
            return $data;
        }

		
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

        if (isset($this->request->post['quantity'])) {
            $quantity = $this->request->post['quantity'];
        } else {
            $quantity = 0;
        }

        if (isset($this->request->post['options'])) {

            $options = htmlspecialchars_decode($this->request->post['options']);
            $options = str_replace('[','{', $options);
            $options = str_replace(']','}', $options);

        } else {
            $options = '';
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

                    if(!$this->model_account_wishlists->wishlistItemExists($product_id,$wishlist_id, $options)){

                        $this->model_account_wishlists->addWishlistitem($product_id,$wishlist_id, $options, $quantity);

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

                    $this->model_account_wishlists->addWishlistitem($product_id,$wishlist_id, $options, $quantity);

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


    public function addItemToOrder(){

        $this->load->model('account/wishlists');

        $json = array();

        $data['text_success'] = $this->language->get('text_success');

        if (isset($this->request->post['wishlist_item_id'])) {
            $wishlist_item_id = $this->request->post['wishlist_item_id'];
        } else {
            $wishlist_item_id = false;
        }

        if($wishlist_item_id && (! $this->model_account_wishlists->isWishlistOwnerByItem($wishlist_item_id))){
            $json['info'] = "Only the wishlist owner can update it.";
        }elseif($wishlist_item_id) {

            $full_item = $this->model_account_wishlists->getOneWishlistitem($wishlist_item_id);

            if($full_item){
                $this->cart->add($full_item['product_id'], $full_item['quantity'], json_decode($full_item['options']));
                $json['success'] = $this->request->post['quantity'] . " X " . $this->request->post['wishlist_item_name'] . " was added to your <a href=\"/index.php?route=checkout/cart\">current order</a>.";
            }else{
                $json['info'] = "Could not find the item.";
            }
        }else{
            $json['info'] = "No wishlist item id provided.";
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));

    }

    public function updateWishlistitemQty(){

        $this->load->model('account/wishlists');

        $this->load->model('catalog/product');

        $json = array();

        $data['text_success'] = $this->language->get('text_success');

        if (isset($this->request->post['wishlist_item_id'])) {
            $wishlist_item_id = $this->request->post['wishlist_item_id'];
        } else {
            $wishlist_item_id = false;
        }

        if($wishlist_item_id && (! $this->model_account_wishlists->isWishlistOwnerByItem($wishlist_item_id))){
            $json['info'] = "Only the wishlist owner can update it.";
        }elseif($wishlist_item_id) {

            $qty = $this->request->post['quantity'];

            if($qty > 0){
                $wishlist = $this->model_account_wishlists->updateWishlistItemQty($wishlist_item_id,$qty);
            }else{
                $wishlist = $this->model_account_wishlists->deleteWishlistitem($wishlist_item_id);
            }

            if ($wishlist) {

                $json['success'] = "Your wishlist was updated Successfully!.";

            }else{
                $json['info'] = "Could not update your wishlist.";
            }
        }else{
            $json['info'] = "No wishlist item id provided.";
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
        }else{
            $json['info'] = $result['wishlist_name'];
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));

    }

    public function getProductWithOptions($cart) {
    $product_data = array();



    //$cart_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cart WHERE api_id = '" . (isset($this->session->data['api_id']) ? (int)$this->session->data['api_id'] : 0) . "' AND customer_id = '" . (int)$this->customer->getId() . "' AND session_id = '" . $this->db->escape($this->session->getId()) . "'");


   // $cart_query=$this->db->query("SELECT * FROM " . DB_PREFIX . "wishlistitems WHERE wishlist_id = " . (int)$wishlist_id );

   // foreach ($cart_query->rows as $cart) {
        $stock = true;

        $product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_store p2s LEFT JOIN " . DB_PREFIX . "product p ON (p2s.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' AND p2s.product_id = '" . (int)$cart['product_id'] . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.date_available <= NOW() AND p.status = '1'");

        if ($product_query->num_rows && ($cart['quantity'] > 0)) {
            $option_price = 0;
            $option_points = 0;
            $option_weight = 0;

            $option_data = array();

            foreach (json_decode($cart['options']) as $product_option_id => $value) {
                $option_query = $this->db->query("SELECT po.product_option_id, po.option_id, od.name, o.type FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_option_id = '" . (int)$product_option_id . "' AND po.product_id = '" . (int)$cart['product_id'] . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");

                if ($option_query->num_rows) {
                    if ($option_query->row['type'] == 'select' || $option_query->row['type'] == 'radio') {
                        $option_value_query = $this->db->query("SELECT pov.option_value_id, ovd.name, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int)$value . "' AND pov.product_option_id = '" . (int)$product_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

                        if ($option_value_query->num_rows) {
                            if ($option_value_query->row['price_prefix'] == '+') {
                                $option_price += $option_value_query->row['price'];
                            } elseif ($option_value_query->row['price_prefix'] == '-') {
                                $option_price -= $option_value_query->row['price'];
                            }

                            if ($option_value_query->row['points_prefix'] == '+') {
                                $option_points += $option_value_query->row['points'];
                            } elseif ($option_value_query->row['points_prefix'] == '-') {
                                $option_points -= $option_value_query->row['points'];
                            }

                            if ($option_value_query->row['weight_prefix'] == '+') {
                                $option_weight += $option_value_query->row['weight'];
                            } elseif ($option_value_query->row['weight_prefix'] == '-') {
                                $option_weight -= $option_value_query->row['weight'];
                            }

                            if ($option_value_query->row['subtract'] && (!$option_value_query->row['quantity'] || ($option_value_query->row['quantity'] < $cart['quantity']))) {
                                $stock = false;
                            }

                            $option_data[] = array(
                                'product_option_id'       => $product_option_id,
                                'product_option_value_id' => $value,
                                'option_id'               => $option_query->row['option_id'],
                                'option_value_id'         => $option_value_query->row['option_value_id'],
                                'name'                    => $option_query->row['name'],
                                'value'                   => $option_value_query->row['name'],
                                'type'                    => $option_query->row['type'],
                                'quantity'                => $option_value_query->row['quantity'],
                                'subtract'                => $option_value_query->row['subtract'],
                                'price'                   => $option_value_query->row['price'],
                                'price_prefix'            => $option_value_query->row['price_prefix'],
                                'points'                  => $option_value_query->row['points'],
                                'points_prefix'           => $option_value_query->row['points_prefix'],
                                'weight'                  => $option_value_query->row['weight'],
                                'weight_prefix'           => $option_value_query->row['weight_prefix']
                            );
                        }
                    } elseif ($option_query->row['type'] == 'checkbox' && is_array($value)) {
                        foreach ($value as $product_option_value_id) {
                            $option_value_query = $this->db->query("SELECT pov.option_value_id, pov.quantity, pov.subtract, pov.price, pov.price_prefix, pov.points, pov.points_prefix, pov.weight, pov.weight_prefix, ovd.name FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (pov.option_value_id = ovd.option_value_id) WHERE pov.product_option_value_id = '" . (int)$product_option_value_id . "' AND pov.product_option_id = '" . (int)$product_option_id . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

                            if ($option_value_query->num_rows) {
                                if ($option_value_query->row['price_prefix'] == '+') {
                                    $option_price += $option_value_query->row['price'];
                                } elseif ($option_value_query->row['price_prefix'] == '-') {
                                    $option_price -= $option_value_query->row['price'];
                                }

                                if ($option_value_query->row['points_prefix'] == '+') {
                                    $option_points += $option_value_query->row['points'];
                                } elseif ($option_value_query->row['points_prefix'] == '-') {
                                    $option_points -= $option_value_query->row['points'];
                                }

                                if ($option_value_query->row['weight_prefix'] == '+') {
                                    $option_weight += $option_value_query->row['weight'];
                                } elseif ($option_value_query->row['weight_prefix'] == '-') {
                                    $option_weight -= $option_value_query->row['weight'];
                                }

                                if ($option_value_query->row['subtract'] && (!$option_value_query->row['quantity'] || ($option_value_query->row['quantity'] < $cart['quantity']))) {
                                    $stock = false;
                                }

                                $option_data[] = array(
                                    'product_option_id'       => $product_option_id,
                                    'product_option_value_id' => $product_option_value_id,
                                    'option_id'               => $option_query->row['option_id'],
                                    'option_value_id'         => $option_value_query->row['option_value_id'],
                                    'name'                    => $option_query->row['name'],
                                    'value'                   => $option_value_query->row['name'],
                                    'type'                    => $option_query->row['type'],
                                    'quantity'                => $option_value_query->row['quantity'],
                                    'subtract'                => $option_value_query->row['subtract'],
                                    'price'                   => $option_value_query->row['price'],
                                    'price_prefix'            => $option_value_query->row['price_prefix'],
                                    'points'                  => $option_value_query->row['points'],
                                    'points_prefix'           => $option_value_query->row['points_prefix'],
                                    'weight'                  => $option_value_query->row['weight'],
                                    'weight_prefix'           => $option_value_query->row['weight_prefix']
                                );
                            }
                        }
                    } elseif ($option_query->row['type'] == 'text' || $option_query->row['type'] == 'textarea' || $option_query->row['type'] == 'file' || $option_query->row['type'] == 'date' || $option_query->row['type'] == 'datetime' || $option_query->row['type'] == 'time') {
                        $option_data[] = array(
                            'product_option_id'       => $product_option_id,
                            'product_option_value_id' => '',
                            'option_id'               => $option_query->row['option_id'],
                            'option_value_id'         => '',
                            'name'                    => $option_query->row['name'],
                            'value'                   => $value,
                            'type'                    => $option_query->row['type'],
                            'quantity'                => '',
                            'subtract'                => '',
                            'price'                   => '',
                            'price_prefix'            => '',
                            'points'                  => '',
                            'points_prefix'           => '',
                            'weight'                  => '',
                            'weight_prefix'           => ''
                        );
                    }
                }
            }

            $price = $product_query->row['price'];

            // Product Discounts
            $discount_quantity = 0;

            foreach ($product_query->rows as $cart_2) {
                if ($cart_2['product_id'] == $cart['product_id']) {
                    $discount_quantity += $cart_2['quantity'];
                }
            }

            $product_discount_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$cart['product_id'] . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND quantity <= '" . (int)$discount_quantity . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY quantity DESC, priority ASC, price ASC LIMIT 1");

            if ($product_discount_query->num_rows) {
                $price = $product_discount_query->row['price'];
            }

            // Product Specials
            $product_special_query = $this->db->query("SELECT price FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$cart['product_id'] . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((date_start = '0000-00-00' OR date_start < NOW()) AND (date_end = '0000-00-00' OR date_end > NOW())) ORDER BY priority ASC, price ASC LIMIT 1");

            if ($product_special_query->num_rows) {
                $price = $product_special_query->row['price'];
            }

            // Reward Points
            $product_reward_query = $this->db->query("SELECT points FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$cart['product_id'] . "' AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "'");

            if ($product_reward_query->num_rows) {
                $reward = $product_reward_query->row['points'];
            } else {
                $reward = 0;
            }

            // Downloads
            $download_data = array();

            $download_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_to_download p2d LEFT JOIN " . DB_PREFIX . "download d ON (p2d.download_id = d.download_id) LEFT JOIN " . DB_PREFIX . "download_description dd ON (d.download_id = dd.download_id) WHERE p2d.product_id = '" . (int)$cart['product_id'] . "' AND dd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

            foreach ($download_query->rows as $download) {
                $download_data[] = array(
                    'download_id' => $download['download_id'],
                    'name'        => $download['name'],
                    'filename'    => $download['filename'],
                    'mask'        => $download['mask']
                );
            }

            // Stock
            if (!$product_query->row['quantity'] || ($product_query->row['quantity'] < $cart['quantity'])) {
                $stock = false;
            }

//            $recurring_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "recurring r LEFT JOIN " . DB_PREFIX . "product_recurring pr ON (r.recurring_id = pr.recurring_id) LEFT JOIN " . DB_PREFIX . "recurring_description rd ON (r.recurring_id = rd.recurring_id) WHERE r.recurring_id = '" . (int)$cart['recurring_id'] . "' AND pr.product_id = '" . (int)$cart['product_id'] . "' AND rd.language_id = " . (int)$this->config->get('config_language_id') . " AND r.status = 1 AND pr.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "'");

//            if ($recurring_query->num_rows) {
//                $recurring = array(
//                    'recurring_id'    => $cart['recurring_id'],
//                    'name'            => $recurring_query->row['name'],
//                    'frequency'       => $recurring_query->row['frequency'],
//                    'price'           => $recurring_query->row['price'],
//                    'cycle'           => $recurring_query->row['cycle'],
//                    'duration'        => $recurring_query->row['duration'],
//                    'trial'           => $recurring_query->row['trial_status'],
//                    'trial_frequency' => $recurring_query->row['trial_frequency'],
//                    'trial_price'     => $recurring_query->row['trial_price'],
//                    'trial_cycle'     => $recurring_query->row['trial_cycle'],
//                    'trial_duration'  => $recurring_query->row['trial_duration']
//                );
//            } else {
//                $recurring = false;
//            }

            // currenlty not using recurring, certainly not in wishlist
            $recurring = false;

            $product_data[] = array(
              //  'cart_id'         => $cart['cart_id'],
                'product_id'      => $product_query->row['product_id'],
                'name'            => $product_query->row['name'],
                'model'           => $product_query->row['model'],
                'shipping'        => $product_query->row['shipping'],
                'image'           => $product_query->row['image'],
                'option'          => $option_data,
                'download'        => $download_data,
                'quantity'        => $cart['quantity'],
                'minimum'         => $product_query->row['minimum'],
                'subtract'        => $product_query->row['subtract'],
                'stock'           => $stock,
                'price'           => ($price + $option_price),
                'total'           => ($price + $option_price) * $cart['quantity'],
                'reward'          => $reward * $cart['quantity'],
                'points'          => ($product_query->row['points'] ? ($product_query->row['points'] + $option_points) * $cart['quantity'] : 0),
                'tax_class_id'    => $product_query->row['tax_class_id'],
                'weight'          => ($product_query->row['weight'] + $option_weight) * $cart['quantity'],
                'weight_class_id' => $product_query->row['weight_class_id'],
                'length'          => $product_query->row['length'],
                'width'           => $product_query->row['width'],
                'height'          => $product_query->row['height'],
                'length_class_id' => $product_query->row['length_class_id'],
                'recurring'       => $recurring
            );
        } else {
            // $this->remove($cart['cart_id']);
        }


    return $product_data;
}
}
