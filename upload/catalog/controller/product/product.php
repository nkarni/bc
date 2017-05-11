<?php
class ControllerProductProduct extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('product/product');
        $this->load->model('tool/image');

		$data['breadcrumbs'] = array();
        $data['category_crumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

        $data['showOptionPrices'] = false;

		$this->load->model('catalog/category');
                
                if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
                    $toEmail = $this->config->get('config_email');
                    $mail = new Mail();
                    $mail->protocol = $this->config->get('config_mail_protocol');
                    $mail->parameter = $this->config->get('config_mail_parameter');
                    $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
                    $mail->smtp_username = $this->config->get('config_mail_smtp_username');
                    $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
                    $mail->smtp_port = $this->config->get('config_mail_smtp_port');
                    $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

                    $mail->setTo($toEmail);
                    $mail->setFrom($this->config->get('config_email'));
                    $mail->setReplyTo($this->request->post['email']);
                    $mail->setSender(html_entity_decode($this->request->post['first_name'], ENT_QUOTES, 'UTF-8'));
                    $mail->setSubject(html_entity_decode(sprintf($this->language->get('email_subject'), $this->request->post['first_name']), ENT_QUOTES, 'UTF-8'));
                    $mail->setText($this->request->post['other_information']);
                    $mail->send();
                    $this->response->redirect($this->url->link(''));
                }else if(($this->request->server['REQUEST_METHOD'] == 'POST') && !$this->validate()){
                    $data['default_open_moreinfo'] = 'show';
                }else{
                    $data['default_open_moreinfo'] = 'hide';
                }
                

		if (isset($this->request->get['path'])) {
			$path = '';
            $url = '';

			$parts = explode('_', (string)$this->request->get['path']);

			$category_id = (int)array_pop($parts);

			foreach ($parts as $path_id) {
				if (!$path) {
					$path = $path_id;
				} else {
					$path .= '_' . $path_id;
				}

				$category_info = $this->model_catalog_category->getCategory($path_id);

				if ($category_info) {
					$data['breadcrumbs'][] = array(
						'text' => $category_info['name'],
						'href' => $this->url->link('product/category', 'path=' . $path)
					);

                    $data['category_crumbs'][] = array(
                        'name' => $category_info['name'],
                        'href' => $this->url->link('product/category', 'path=' . $path . '_' . $category_info['category_id'] . $url),
                        'image' => $this->model_tool_image->cropsize($category_info['image'], 320, 200),
                        'parent_id' => $category_info['parent_id'],
                        'category_id' => $category_info['category_id']
                    );
				}
			}

			// Set the last category breadcrumb
			$category_info = $this->model_catalog_category->getCategory($category_id);

			if ($category_info) {
				$url = '';

				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}

				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}

				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}

				if (isset($this->request->get['limit'])) {
					$url .= '&limit=' . $this->request->get['limit'];
				}

				$data['breadcrumbs'][] = array(
					'text' => $category_info['name'],
					'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url)
				);

                $data['category_crumbs'][] = array(
                    'name' => $category_info['name'],
                    'href' => $this->url->link('product/category', 'path=' . $path . '_' . $category_info['category_id'] . $url),
                    'image' => $this->model_tool_image->cropsize($category_info['image'], 320, 200),
                    'parent_id' => $category_info['parent_id'],
                    'category_id' => $category_info['category_id']
                );
			}
		}

		$this->load->model('catalog/manufacturer');

		if (isset($this->request->get['manufacturer_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_brand'),
				'href' => $this->url->link('product/manufacturer')
			);

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($this->request->get['manufacturer_id']);

			if ($manufacturer_info) {
				$data['breadcrumbs'][] = array(
					'text' => $manufacturer_info['name'],
					'href' => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $this->request->get['manufacturer_id'] . $url)
				);
			}
		}

		if (isset($this->request->get['search']) || isset($this->request->get['tag'])) {
			$url = '';

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . $this->request->get['search'];
			}

			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . $this->request->get['tag'];
			}

			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}

			if (isset($this->request->get['category_id'])) {
				$url .= '&category_id=' . $this->request->get['category_id'];
			}

			if (isset($this->request->get['sub_category'])) {
				$url .= '&sub_category=' . $this->request->get['sub_category'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_search'),
				'href' => $this->url->link('product/search', $url)
			);
		}

		if (isset($this->request->get['product_id'])) {
			$product_id = (int)$this->request->get['product_id'];
		} else {
			$product_id = 0;
		}

		$this->load->model('catalog/product');

		$product_info = $this->model_catalog_product->getProduct($product_id);

		if ($product_info) {
			$url = '';

			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['manufacturer_id'])) {
				$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
			}

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . $this->request->get['search'];
			}

			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . $this->request->get['tag'];
			}

			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}

			if (isset($this->request->get['category_id'])) {
				$url .= '&category_id=' . $this->request->get['category_id'];
			}

			if (isset($this->request->get['sub_category'])) {
				$url .= '&sub_category=' . $this->request->get['sub_category'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $product_info['name'],
				'href' => $this->url->link('product/product', $url . '&product_id=' . $this->request->get['product_id'])
			);
			
			$_SESSION['last_viewed_product_url'] = $this->url->link('product/product', $url . '&product_id=' . $this->request->get['product_id']);

            $this->session->data['redirect'] = $this->url->link('account/account', '', 'SSL');

            $this->document->setTitle($product_info['meta_title']);
			$this->document->setDescription($product_info['meta_description']);
			$this->document->setKeywords($product_info['meta_keyword']);
			$this->document->addLink($this->url->link('product/product', 'product_id=' . $this->request->get['product_id']), 'canonical');
			$this->document->addScript('catalog/view/javascript/jquery/magnific/jquery.magnific-popup.min.js');
			$this->document->addStyle('catalog/view/javascript/jquery/magnific/magnific-popup.css');
			$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.js');
			$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
			$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

			$data['heading_title'] = $product_info['name'];

			$data['text_select'] = $this->language->get('text_select');
			$data['text_manufacturer'] = $this->language->get('text_manufacturer');
			$data['text_model'] = $this->language->get('text_model');
			$data['text_reward'] = $this->language->get('text_reward');
			$data['text_points'] = $this->language->get('text_points');
			$data['text_stock'] = $this->language->get('text_stock');
			$data['text_discount'] = $this->language->get('text_discount');
			$data['text_tax'] = $this->language->get('text_tax');
			$data['text_option'] = $this->language->get('text_option');
			$data['text_minimum'] = sprintf($this->language->get('text_minimum'), $product_info['minimum']);
			$data['text_write'] = $this->language->get('text_write');
			$data['text_login'] = sprintf($this->language->get('text_login'), $this->url->link('account/login', '', true), $this->url->link('account/register', '', true));
			$data['text_note'] = $this->language->get('text_note');
			$data['text_tags'] = $this->language->get('text_tags');
			$data['text_related'] = $this->language->get('text_related');
			$data['text_payment_recurring'] = $this->language->get('text_payment_recurring');
			$data['text_loading'] = $this->language->get('text_loading');

			$data['entry_qty'] = $this->language->get('entry_qty');
			$data['entry_name'] = $this->language->get('entry_name');
			$data['entry_review'] = $this->language->get('entry_review');
			$data['entry_rating'] = $this->language->get('entry_rating');
			$data['entry_good'] = $this->language->get('entry_good');
			$data['entry_bad'] = $this->language->get('entry_bad');
                        
			$data['entry_first_name'] = $this->language->get('entry_first_name');
			$data['entry_last_name'] = $this->language->get('entry_last_name');
			$data['entry_email'] = $this->language->get('entry_email');
			$data['entry_company'] = $this->language->get('entry_company');
			$data['entry_phone'] = $this->language->get('entry_phone');
			$data['entry_country'] = $this->language->get('entry_country');
			$data['entry_postcode'] = $this->language->get('entry_postcode');
			$data['entry_project_size'] = $this->language->get('entry_project_size');
			$data['entry_industry_sector'] = $this->language->get('entry_industry_sector');
			$data['entry_other_information'] = $this->language->get('entry_other_information');
                        
                        $data['action'] = $_SERVER["REQUEST_URI"];

			$data['button_cart'] = $this->language->get('button_cart');
			$data['button_quote'] = $this->language->get('button_quote');
			$data['button_more_info'] = $this->language->get('button_more_info');
			$data['button_wishlist'] = $this->language->get('button_wishlist');
			$data['button_compare'] = $this->language->get('button_compare');
			$data['button_upload'] = $this->language->get('button_upload');
			$data['button_continue'] = $this->language->get('button_continue');
			$data['button_submit'] = $this->language->get('button_submit');

			$this->load->model('catalog/review');

			$data['tab_description'] = $this->language->get('tab_description');
			$data['tab_attribute'] = $this->language->get('tab_attribute');
			$data['tab_review'] = sprintf($this->language->get('tab_review'), $product_info['reviews']);
			$data['tab_specifications'] = $this->language->get('tab_specifications');
			$data['tab_features'] = $this->language->get('tab_features');
			$data['tab_review'] = sprintf($this->language->get('tab_review'), $product_info['reviews']);

			$data['product_id'] = (int)$this->request->get['product_id'];
			$data['manufacturer'] = $product_info['manufacturer'];
			$data['manufacturers'] = $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $product_info['manufacturer_id']);
			$data['model'] = $product_info['model'];
			$data['reward'] = $product_info['reward'];
			$data['points'] = $product_info['points'];
			$data['sku'] = $product_info['sku'];
			$data['description'] = html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8');
			$data['short_description'] = html_entity_decode($product_info['short_description'], ENT_QUOTES, 'UTF-8');
			$data['specifications'] = html_entity_decode($product_info['specifications'], ENT_QUOTES, 'UTF-8');
			$data['features'] = html_entity_decode($product_info['features'], ENT_QUOTES, 'UTF-8');
            $this->load->model('localisation/country');

            $data['countries'] = $this->model_localisation_country->getCountries();

            $data['customer_id'] = ($this->customer->isLogged())?$this->customer->getId():0;

			if ($product_info['quantity'] <= 0) {
				$data['stock'] = $product_info['stock_status'];
			} elseif ($this->config->get('config_stock_display')) {
				$data['stock'] = $product_info['quantity'];
			} else {
				$data['stock'] = $this->language->get('text_instock');
			}

			$this->load->model('tool/image');

			if ($product_info['image']) {
				$data['popup'] = $this->model_tool_image->cropsize($product_info['image'], $this->config->get($this->config->get('config_theme') . '_image_popup_width'), $this->config->get($this->config->get('config_theme') . '_image_popup_height'));
			} else {
				$data['popup'] = '';
			}
                        
                        $data['main_image_height'] = $this->config->get($this->config->get('config_theme') . '_image_popup_height');
                        $data['main_image_width'] = $this->config->get($this->config->get('config_theme') . '_image_popup_width');

                        if ($product_info['image']) {
				$data['thumb'] = $this->model_tool_image->cropsize($product_info['image'], $this->config->get($this->config->get('config_theme') . '_image_thumb_width'), $this->config->get($this->config->get('config_theme') . '_image_thumb_height'));
			} else {
				$data['thumb'] = '';
			}

			$data['images'] = array();

			$results = $this->model_catalog_product->getProductImages($this->request->get['product_id']);

			foreach ($results as $result) {
				$data['images'][] = array(
					'popup' => $this->model_tool_image->cropsize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_popup_width'), $this->config->get($this->config->get('config_theme') . '_image_popup_height')),
					'thumb' => $this->model_tool_image->cropsize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_thumb_width'), $this->config->get($this->config->get('config_theme') . '_image_thumb_height'))
				);
			}
                        
                        $data['thumb_height'] = $this->config->get($this->config->get('config_theme') . '_image_additional_height');
                        $data['thumb_width'] = $this->config->get($this->config->get('config_theme') . '_image_additional_width');
                        
			if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
				$data['price'] = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
                                $data['price_amount'] = (float) $product_info['price'];
			} else {
				$data['price'] = false;
                                $data['price_amount'] = false;
			}

			if ((float)$product_info['special']) {
				$data['special'] = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
			} else {
				$data['special'] = false;
			}

			if ($this->config->get('config_tax')) {
				$data['tax'] = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price'], $this->session->data['currency']);
			} else {
				$data['tax'] = false;
			}

			$discounts = $this->model_catalog_product->getProductDiscounts($this->request->get['product_id']);

			$data['discounts'] = array();

			foreach ($discounts as $discount) {
				$data['discounts'][] = array(
					'quantity' => $discount['quantity'],
					'price'    => $this->currency->format($this->tax->calculate($discount['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency'])
				);
			}

			$data['options'] = array();

			foreach ($this->model_catalog_product->getProductOptions($this->request->get['product_id']) as $option) {
				$product_option_value_data = array();

				foreach ($option['product_option_value'] as $option_value) {
					if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
						if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$option_value['price']) {
							$price_value = $this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax') ? 'P' : false);
							$price = $this->currency->format($price_value, $this->session->data['currency']);
						} else {
							$price_value = false;
							$price = false;
						}

						$product_option_value_data[] = array(
							'product_option_value_id' => $option_value['product_option_value_id'],
							'option_value_id'         => $option_value['option_value_id'],
							'name'                    => $option_value['name'],
							'image'                   => $this->model_tool_image->resize($option_value['image'], 50, 50),
							'price'                   => $price,
							'price_value'             => (float) $price_value,
							'price_prefix'            => $option_value['price_prefix']
						);
					}
				}

				$data['options'][] = array(
					'product_option_id'    => $option['product_option_id'],
					'product_option_value' => $product_option_value_data,
					'option_id'            => $option['option_id'],
					'name'                 => $option['name'],
					'type'                 => $option['type'],
					'value'                => $option['value'],
					'required'             => $option['required']
				);
			}

			if ($product_info['minimum']) {
				$data['minimum'] = $product_info['minimum'];
			} else {
				$data['minimum'] = 1;
			}

			$data['review_status'] = $this->config->get('config_review_status');

			if ($this->config->get('config_review_guest') || $this->customer->isLogged()) {
				$data['review_guest'] = true;
			} else {
				$data['review_guest'] = false;
			}

			if ($this->customer->isLogged()) {
				$data['customer_name'] = $this->customer->getFirstName() . '&nbsp;' . $this->customer->getLastName();
			} else {
				$data['customer_name'] = '';
			}

			$data['reviews'] = sprintf($this->language->get('text_reviews'), (int)$product_info['reviews']);
			$data['rating'] = (int)$product_info['rating'];

			// Captcha
			if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('review', (array)$this->config->get('config_captcha_page'))) {
				$data['captcha'] = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha'));
			} else {
				$data['captcha'] = '';
			}

			$data['share'] = $this->url->link('product/product', 'product_id=' . (int)$this->request->get['product_id']);

			$data['attribute_groups'] = $this->model_catalog_product->getProductAttributes($this->request->get['product_id']);

			$data['products'] = array();

			$results = $this->model_catalog_product->getProductRelated($this->request->get['product_id']);

			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_related_width'), $this->config->get($this->config->get('config_theme') . '_image_related_height'));
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get($this->config->get('config_theme') . '_image_related_width'), $this->config->get($this->config->get('config_theme') . '_image_related_height'));
				}

				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$price = false;
				}

				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$special = false;
				}

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = (int)$result['rating'];
				} else {
					$rating = false;
				}
                                
                                $category = $this->model_catalog_product->getCategories($result['product_id']);
                                if ($category){
                                    $category_array = $this->model_catalog_category->getCategory($category[0]['category_id']);
                                    $category_name  = $category_array['name'];
                                } else {
                                    $category_name = '';
                                }

				$data['products'][] = array(
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
					'name'        => $result['name'],
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..',
					'price'       => $price,
					'special'     => $special,
					'tax'         => $tax,
					'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
					'rating'      => $rating,
                                        'category_name' => $category_name,
					'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
				);
			}
                        
                        
                        if (isset($this->error['first_name'])) {
                                $data['error_first_name'] = $this->error['first_name'];
                        } else {
                                $data['error_first_name'] = '';
                        }

                        if (isset($this->error['last_name'])) {
                                $data['error_last_name'] = $this->error['last_name'];
                        } else {
                                $data['error_last_name'] = '';
                        }
                        if (isset($this->error['email'])) {
                                $data['error_email'] = $this->error['email'];
                        } else {
                                $data['error_email'] = '';
                        }

                        if (isset($this->error['company'])) {
                                $data['error_company'] = $this->error['company'];
                        } else {
                                $data['error_company'] = '';
                        }

                        if (isset($this->error['phone'])) {
                                $data['error_phone'] = $this->error['phone'];
                        } else {
                                $data['error_phone'] = '';
                        }
                       
                        if (isset($this->error['postcode'])) {
                                $data['error_postcode'] = $this->error['postcode'];
                        } else {
                                $data['error_postcode'] = '';
                        }
                        
                        if (isset($this->error['country'])) {
                                $data['error_country'] = $this->error['country'];
                        } else {
                                $data['error_country'] = '';
                        }
                        
                        if (isset($this->error['project_size'])) {
                                $data['error_project_size'] = $this->error['project_size'];
                        } else {
                                $data['error_project_size'] = '';
                        }
                        
                        if (isset($this->error['industry_sector'])) {
                                $data['error_industry_sector'] = $this->error['industry_sector'];
                        } else {
                                $data['error_industry_sector'] = '';
                        }
                        
                        if (isset($this->error['other_information'])) {
                                $data['error_other_information'] = $this->error['other_information'];
                        } else {
                                $data['error_other_information'] = '';
                        }


                        if (isset($this->request->post['first_name'])) {
                                $data['first_name'] = $this->request->post['first_name'];
                        } else {
                                $data['first_name'] = $this->customer->getFirstName();
                        }
                        
                        if (isset($this->request->post['last_name'])) {
                                $data['last_name'] = $this->request->post['last_name'];
                        } else {
                                $data['last_name'] = $this->customer->getLastName();
                        }

                        if (isset($this->request->post['email'])) {
                                $data['email'] = $this->request->post['email'];
                        } else {
                                $data['email'] = $this->customer->getEmail();
                        }

                        if (isset($this->request->post['phone'])) {
                                $data['phone'] = $this->request->post['phone'];
                        } else {
                                $data['phone'] = $this->customer->getTelephone();
                        }
                        $this->load->model('account/address');
                        $addresses = $this->model_account_address->getAddress($this->customer->getAddressId());

                        if (isset($this->request->post['postcode'])) {
                                $data['postcode'] = $this->request->post['postcode'];
                        } else {
                                $data['postcode'] = (!empty($addresses)?$addresses['postcode'] :'');
                        }
                        if (isset($this->request->post['country'])) {
                                $data['country'] = $this->request->post['country'];
                        } else {
                                $data['country'] = (!empty($addresses)?$addresses['country'] :'');
                        }
                        if (isset($this->request->post['company'])) {
                                $data['company'] = $this->request->post['company'];
                        } else {
                                $data['company'] = '';
                        }
                        if (isset($this->request->post['project_size'])) {
                                $data['project_size'] = $this->request->post['project_size'];
                        } else {
                                $data['project_size'] = '';
                        }
                        if (isset($this->request->post['industry_sector'])) {
                                $data['industry_sector'] = $this->request->post['industry_sector'];
                        } else {
                                $data['industry_sector'] = '';
                        }
                        if (isset($this->request->post['other_information'])) {
                                $data['other_information'] = $this->request->post['other_information'];
                        } else {
                                $data['other_information'] = '';
                        }


			$data['tags'] = array();

			if ($product_info['tag']) {
				$tags = explode(',', $product_info['tag']);

				foreach ($tags as $tag) {
					$data['tags'][] = array(
						'tag'  => trim($tag),
						'href' => $this->url->link('product/search', 'tag=' . trim($tag))
					);
				}
			}

			$data['recurrings'] = $this->model_catalog_product->getProfiles($this->request->get['product_id']);

			$this->model_catalog_product->updateViewed($this->request->get['product_id']);

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			if (isset($_SESSION['alert_success'])) {
			    $data['alert_success'] = $_SESSION['alert_success'];
                unset($_SESSION['alert_success']);
            }

			$this->response->setOutput($this->load->view('product/product', $data));
		} else {
			$url = '';

			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['manufacturer_id'])) {
				$url .= '&manufacturer_id=' . $this->request->get['manufacturer_id'];
			}

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . $this->request->get['search'];
			}

			if (isset($this->request->get['tag'])) {
				$url .= '&tag=' . $this->request->get['tag'];
			}

			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}

			if (isset($this->request->get['category_id'])) {
				$url .= '&category_id=' . $this->request->get['category_id'];
			}

			if (isset($this->request->get['sub_category'])) {
				$url .= '&sub_category=' . $this->request->get['sub_category'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_error'),
				'href' => $this->url->link('product/product', $url . '&product_id=' . $product_id)
			);

			$this->document->setTitle($this->language->get('text_error'));

			$data['heading_title'] = $this->language->get('text_error');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_continue'] = $this->language->get('button_continue');

			$data['continue'] = $this->url->link('common/home');

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$data['showOptionPrices'] = false;

			$this->response->setOutput($this->load->view('error/not_found', $data));
		}
	}
        
        protected function validate() {
            if ((utf8_strlen($this->request->post['first_name']) < 3) || (utf8_strlen($this->request->post['first_name']) > 32)) {
                    $this->error['first_name'] = $this->language->get('error_first_name');
            }
            if ((utf8_strlen($this->request->post['last_name']) < 3) || (utf8_strlen($this->request->post['last_name']) > 32)) {
                    $this->error['last_name'] = $this->language->get('error_last_name');
            }

            if (!filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
                    $this->error['email'] = $this->language->get('error_email');
            }
            if ((utf8_strlen($this->request->post['phone']) < 10)) {
                    $this->error['phone'] = $this->language->get('error_phone');
            }
            
//            if ((empty($this->request->post['company']))) {
//                    $this->error['company'] = $this->language->get('company');
//            }
            
            if ((empty($this->request->post['country']))) {
                    $this->error['country'] = $this->language->get('error_country');
            }
            
            if ((empty($this->request->post['postcode']))) {
                    $this->error['postcode'] = $this->language->get('error_postcode');
            }
            
            if ((empty($this->request->post['project_size']))) {
                    $this->error['project_size'] = $this->language->get('error_project_size');
            }
            if ((empty($this->request->post['industry_sector']))) {
                    $this->error['industry_sector'] = $this->language->get('error_industry_sector');
            }

            return !$this->error;
	}

	public function review() {
		$this->load->language('product/product');

		$this->load->model('catalog/review');

		$data['text_no_reviews'] = $this->language->get('text_no_reviews');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['reviews'] = array();

		$review_total = $this->model_catalog_review->getTotalReviewsByProductId($this->request->get['product_id']);

		$results = $this->model_catalog_review->getReviewsByProductId($this->request->get['product_id'], ($page - 1) * 5, 5);

		foreach ($results as $result) {
			$data['reviews'][] = array(
				'author'     => $result['author'],
				'text'       => nl2br($result['text']),
				'rating'     => (int)$result['rating'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			);
		}

		$pagination = new Pagination();
		$pagination->total = $review_total;
		$pagination->page = $page;
		$pagination->limit = 5;
		$pagination->url = $this->url->link('product/product/review', 'product_id=' . $this->request->get['product_id'] . '&page={page}');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($review_total) ? (($page - 1) * 5) + 1 : 0, ((($page - 1) * 5) > ($review_total - 5)) ? $review_total : ((($page - 1) * 5) + 5), $review_total, ceil($review_total / 5));

		$this->response->setOutput($this->load->view('product/review', $data));
	}

	public function write() {
		$this->load->language('product/product');

		$json = array();

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 25)) {
				$json['error'] = $this->language->get('error_name');
			}

			if ((utf8_strlen($this->request->post['text']) < 25) || (utf8_strlen($this->request->post['text']) > 1000)) {
				$json['error'] = $this->language->get('error_text');
			}

			if (empty($this->request->post['rating']) || $this->request->post['rating'] < 0 || $this->request->post['rating'] > 5) {
				$json['error'] = $this->language->get('error_rating');
			}

			// Captcha
			if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('review', (array)$this->config->get('config_captcha_page'))) {
				$captcha = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha') . '/validate');

				if ($captcha) {
					$json['error'] = $captcha;
				}
			}

			if (!isset($json['error'])) {
				$this->load->model('catalog/review');

				$this->model_catalog_review->addReview($this->request->get['product_id'], $this->request->post);

				$json['success'] = $this->language->get('text_success');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

    public function quote() {
        $this->load->language('product/product');
        $this->load->model('catalog/product');
        $this->load->model('account/customer');
        $this->load->model('account/address');
        $json = array();
        $str = '';

        $product_info = $this->model_catalog_product->getProduct($this->request->post['product_id']);

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
        $items .= '<tr>';
        $items .= '<td valign="top" align="left"><a href="' . $data['share'] = $this->url->link('product/product', 'product_id=' . (int)$this->request->post['product_id']) . '">' .  $product_info['name'] . '</a></td>';
        $items .= '<td valign="top" align="left">' . $this->request->post['options'] .'</td>';
        $items .= '<td valign="top" align="left">' . $this->request->post['quantity'] . '</td>';
        $items .= '</tr></table>';

        if($this->request->post['more-info']){
            $str = '<h2>More information request for: ' . $product_info['name'] . '</h2>';
        }else{
            $str = '<h2>Quote request for: ' . $product_info['name'] . '</h2>';
        }

        $str .= $customer_details_str . $items;
        $str .= '<br><strong>Further Info:</strong><br>' . (strlen($this->request->post['notes']) > 0 ? $this->request->post['notes'] : 'None provided.');


        // send email

        if($this->request->post['more-info']){
            $subject = sprintf('More information request for - ', html_entity_decode( $product_info['name'] , ENT_QUOTES, 'UTF-8'));
        }else{
            $subject = sprintf('Quote request for - ', html_entity_decode( $product_info['name'] , ENT_QUOTES, 'UTF-8'));
        }


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

        if($this->request->post['more-info']){
            $json['success'] = 'Your information request was sent, we will be in touch shortly.'; // no way to know if send worked!!!
        }else{
            $json['success'] = 'Your quote request was sent, we will be in touch shortly.'; // no way to know if send worked!!!
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

	public function getRecurringDescription() {
		$this->load->language('product/product');
		$this->load->model('catalog/product');

		if (isset($this->request->post['product_id'])) {
			$product_id = $this->request->post['product_id'];
		} else {
			$product_id = 0;
		}

		if (isset($this->request->post['recurring_id'])) {
			$recurring_id = $this->request->post['recurring_id'];
		} else {
			$recurring_id = 0;
		}

		if (isset($this->request->post['quantity'])) {
			$quantity = $this->request->post['quantity'];
		} else {
			$quantity = 1;
		}

		$product_info = $this->model_catalog_product->getProduct($product_id);
		$recurring_info = $this->model_catalog_product->getProfile($product_id, $recurring_id);

		$json = array();

		if ($product_info && $recurring_info) {
			if (!$json) {
				$frequencies = array(
					'day'        => $this->language->get('text_day'),
					'week'       => $this->language->get('text_week'),
					'semi_month' => $this->language->get('text_semi_month'),
					'month'      => $this->language->get('text_month'),
					'year'       => $this->language->get('text_year'),
				);

				if ($recurring_info['trial_status'] == 1) {
					$price = $this->currency->format($this->tax->calculate($recurring_info['trial_price'] * $quantity, $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					$trial_text = sprintf($this->language->get('text_trial_description'), $price, $recurring_info['trial_cycle'], $frequencies[$recurring_info['trial_frequency']], $recurring_info['trial_duration']) . ' ';
				} else {
					$trial_text = '';
				}

				$price = $this->currency->format($this->tax->calculate($recurring_info['price'] * $quantity, $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);

				if ($recurring_info['duration']) {
					$text = $trial_text . sprintf($this->language->get('text_payment_description'), $price, $recurring_info['cycle'], $frequencies[$recurring_info['frequency']], $recurring_info['duration']);
				} else {
					$text = $trial_text . sprintf($this->language->get('text_payment_cancel'), $price, $recurring_info['cycle'], $frequencies[$recurring_info['frequency']], $recurring_info['duration']);
				}

				$json['success'] = $text;
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
