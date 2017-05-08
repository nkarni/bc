<?php
class ControllerProductCompare extends Controller {
	public function index() {
		$this->load->language('product/compare');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		if (!isset($this->session->data['compare'])) {
			$this->session->data['compare'] = array();
		}

		if (isset($this->request->get['remove'])) {

            unset($this->session->data['compare'][$this->request->get['remove']]);

            $this->session->data['compare'] = array_values($this->session->data['compare']);

			$this->response->redirect($this->url->link('product/compare'));
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('product/compare')
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_product'] = $this->language->get('text_product');
		$data['text_name'] = $this->language->get('text_name');
		$data['text_image'] = $this->language->get('text_image');
		$data['text_price'] = $this->language->get('text_price');
		$data['text_model'] = $this->language->get('text_model');
		$data['text_manufacturer'] = $this->language->get('text_manufacturer');
		$data['text_availability'] = $this->language->get('text_availability');
		$data['text_rating'] = $this->language->get('text_rating');
		$data['text_summary'] = $this->language->get('text_summary');
		$data['text_weight'] = $this->language->get('text_weight');
		$data['text_dimension'] = $this->language->get('text_dimension');
		$data['text_empty'] = $this->language->get('text_empty');

		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_remove'] = $this->language->get('button_remove');
        $data['customer_id'] = ($this->customer->isLogged())?$this->customer->getId():0;

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['review_status'] = $this->config->get('config_review_status');

		$data['products'] = array();

		$data['attribute_groups'] = array();

		$i = 0;

        foreach ($this->session->data['compare'] as $key => $item) {
            foreach ($item as $product_id => $options) {

                $raw_options = $options;

                $options = json_decode($options, true);

                $product_info = $this->model_catalog_product->getProduct($product_id);

                if ($product_info) {
                    if ($product_info['image']) {
                        $image = $this->model_tool_image->resize($product_info['image'], $this->config->get($this->config->get('config_theme') . '_image_compare_width'), $this->config->get($this->config->get('config_theme') . '_image_compare_height'));
                    } else {
                        $image = false;
                    }

                    if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                        if( $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')) > 0){
                            $price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                        }else{
                            $price = 'Not Specified';
                        }

                    } else {
                        $price = false;
                    }

                    if ((float)$product_info['special']) {
                        $special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                    } else {
                        $special = false;
                    }

                    if ($product_info['quantity'] <= 0) {
                        $availability = $product_info['stock_status'];
                    } elseif ($this->config->get('config_stock_display')) {
                        $availability = $product_info['quantity'];
                    } else {
                        $availability = $this->language->get('text_instock');
                    }

                    $selected_options = [];
                    foreach ($options as $key => $value) {
                        $selected_options[] = $this->model_catalog_product->getProductSelectedOptions($product_id, $key, $value);
                    }

                    //$options = $this->model_catalog_product->getProductSelectedOptions($product_id, array_keys($options)[0], $options[array_keys($options)[0]]);

                    $data['products'][][$product_id] = array(
                        'product_id'   => $product_info['product_id'],
                        'name'         => $product_info['name'],
                        'thumb'        => $image,
                        'price'        => $price,
                        'special'      => $special,
                        'description'  => utf8_substr(strip_tags(html_entity_decode($product_info['short_description'], ENT_QUOTES, 'UTF-8')), 0, 200) . '..',
                        'model'        => $product_info['model'],
                        'specifications'=> html_entity_decode($product_info['specifications']),
                        'features'     => html_entity_decode($product_info['features']),
                        'model'        => $product_info['model'],
                        'manufacturer' => $product_info['manufacturer'],
                        'availability' => $availability,
                        'minimum'      => $product_info['minimum'] > 0 ? $product_info['minimum'] : 1,
                        'rating'       => (int)$product_info['rating'],
                        'reviews'      => sprintf($this->language->get('text_reviews'), (int)$product_info['reviews']),
                        'weight'       => $this->weight->format($product_info['weight'], $product_info['weight_class_id']),
                        'length'       => $this->length->format($product_info['length'], $product_info['length_class_id']),
                        'width'        => $this->length->format($product_info['width'], $product_info['length_class_id']),
                        'height'       => $this->length->format($product_info['height'], $product_info['length_class_id']),
                        'options'    => $selected_options,
                        'href'         => $this->url->link('product/product', 'product_id=' . $product_id),
                        'remove'       => $this->url->link('product/compare', 'remove=' . $i ),
                        'index'        => $i,
                    );


                } else {
    				unset($this->session->data['compare'][$key]);
                }
                $i++;
            }
        }



		// Wishlists starts here
		$this->load->model('account/wishlists');
		$data['wishlists'] = array();
		$data['multiplewishlist'] = $this->config->get('wishlists_status');
		$wishparams = array();
		if ($this->customer->isLogged()) {
			$data['show_wishlist'] = 1;

		}else{
			$data['show_wishlist'] = 0;
		}
		$data['text_login_must'] = $this->language->get('You must be logged in');
		// Wishlists ends here

		$data['continue'] = $this->url->link('common/home');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
/*
		echo '<pre>';
        print_r($this->session->data['compare']);
		print_r($data);
		echo '</pre>';
*/

		$this->response->setOutput($this->load->view('product/compare', $data));
	}

	public function add() {
		$this->load->language('product/compare');

		$json = array();

		if (!isset($this->session->data['compare'])) {
			$this->session->data['compare'] = array();
		}

		if (isset($this->request->post['product_id'])) {
			$product_id = $this->request->post['product_id'];
		} else {
			$product_id = 0;
		}

		$this->load->model('catalog/product');

		$product_info = $this->model_catalog_product->getProduct($product_id);

        if (isset($this->request->post['options'])) {

            $options = htmlspecialchars_decode($this->request->post['options']);
            $options = str_replace('[','{', $options);
            $options = str_replace(']','}', $options);
            $options = stripslashes($options);


        } else {
            $options = '';
        }

		if ($product_info) {

            $item = array($product_id => $options);
			if (!in_array($item, $this->session->data['compare'])) {

			    if(count($this->session->data['compare']) > 5){
                    $json['info'] = sprintf($this->language->get('text_limit'), $this->url->link('product/compare'));
                }else{
                    $this->session->data['compare'][] = $item;
                    $json['total'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
                    $json['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/compare'));
                }
			}else{

                $json['info'] = sprintf($this->language->get('text_already_in'), $this->url->link('product/compare'));

            }

		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
