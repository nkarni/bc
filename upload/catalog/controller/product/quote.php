<?php
class ControllerProductQuote extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('product/quote');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		if (isset($this->request->post['product_id'])) {
			$product_id = (int)$this->request->post['product_id'];
		} else {
			$product_id = 0;
		}

		$this->load->model('catalog/product');

		$product_info = $this->model_catalog_product->getProduct($product_id);
                $json = array();
		if ($product_info) {
			if (isset($this->request->post['quantity']) && ((int)$this->request->post['quantity'] >= $product_info['minimum'])) {
				$quantity = (int)$this->request->post['quantity'];
			} else {
				$quantity = $product_info['minimum'] ? $product_info['minimum'] : 1;
			}

			if (isset($this->request->post['option'])) {
				$option = array_filter($this->request->post['option']);
			} else {
				$option = array();
			}

			$product_options = $this->model_catalog_product->getProductOptions($this->request->post['product_id']);
                        $optionHtml = '';
			foreach ($product_options as $product_option) {
				if ($product_option['required'] && empty($option[$product_option['product_option_id']])) {
					$json['error']['option'][$product_option['product_option_id']] = sprintf($this->language->get('error_required'), $product_option['name']);
				}
                                if(is_array($product_option['product_option_value']) && !empty($option[$product_option['product_option_id']])){
                                    foreach ($product_option['product_option_value'] as $optionData){
                                        if($optionData['product_option_value_id'] == $option[$product_option['product_option_id']]){
                                            $optionHtml .= "- " . $product_option['name'] . ": ". $optionData['name'] ."!!";
                                        }
                                    }
                                }                               
			}

			if (isset($this->request->post['recurring_id'])) {
				$recurring_id = $this->request->post['recurring_id'];
			} else {
				$recurring_id = 0;
			}

			$recurrings = $this->model_catalog_product->getProfiles($product_info['product_id']);

			if ($recurrings) {
				$recurring_ids = array();

				foreach ($recurrings as $recurring) {
					$recurring_ids[] = $recurring['recurring_id'];
				}

				if (!in_array($recurring_id, $recurring_ids)) {
					$json['error']['recurring'] = $this->language->get('error_recurring_required');
				}
			}                       

			if (!$json) {
                            $html = 'Please provide a quote for the following product!!';
                            $html .= $product_info['name'] . " ( ". $quantity. " )!!";
                            $html .= $optionHtml;
                            $userLogged = $this->customer->isLogged();
                            $data = array();
                            $data['html'] = $html;
                            $data['isLogged'] = $userLogged;                           
                            $data['button_submit'] = $this->language->get('button_submit');
                            $data['action'] = $this->url->link('product/quote', '', true);
                            $htmlform = '<input name="name" value="" type="text">';
                            $htmlform .= '<input name="email" value="" type="text">';
                            $htmlform .= '<textarea name="quote" value="" type="text">'.$html.'</textarea>';
                            $htmlform .= '<input name="phone" value="" type="text">';
                            $htmlform .= '<input name="type" value="show" type="text">';
                            $json['success'] = $htmlform;
			} else {
                            $json['redirect'] = str_replace('&amp;', '&', $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']));
			}
                        $this->response->addHeader('Content-Type: application/json');
                        echo json_encode($json);die;
		}
	}
        
        
        public function showquote(){
            $this->load->model('account/address');
            $data = array();
            $this->load->language('product/quote');
            if (($this->request->server['REQUEST_METHOD'] == 'POST') && empty($this->request->post['type']) && $this->validate()) {
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
                $mail->setSender(html_entity_decode($this->request->post['name'], ENT_QUOTES, 'UTF-8'));
                $mail->setSubject(html_entity_decode(sprintf($this->language->get('email_subject'), $this->request->post['name']), ENT_QUOTES, 'UTF-8'));
                $mail->setText($this->request->post['quote']);
                $mail->send();
                $_SESSION['alert_success'] = "Thank you, your request for quote has been sent!";
                $this->response->redirect($_SESSION['last_viewed_product_url'] ?: '/');
            }
            
            
            $data['action'] = $this->url->link('product/quote/showquote', '', true);
            
            $data['heading_title'] = $this->language->get('heading_title');		
            $data['entry_address'] = $this->language->get('entry_address');
            $data['entry_phone'] = $this->language->get('entry_phone');
            $data['entry_name'] = $this->language->get('entry_name');
            $data['entry_email'] = $this->language->get('entry_email');
            $data['entry_quote'] = $this->language->get('entry_quote');
            $data['entry_address1'] = $this->language->get('entry_address1');
            $data['entry_address2'] = $this->language->get('entry_address2');
            $data['entry_city'] = $this->language->get('entry_city');
            $data['entry_postcode'] = $this->language->get('entry_postcode');
            $data['entry_country'] = $this->language->get('entry_country');
            
            
            $data['error_name'] = $this->language->get('error_name');
            $data['error_email'] = $this->language->get('error_email');
            $data['error_phone'] = $this->language->get('error_phone');
            $data['error_quote'] = $this->language->get('error_quote');
            $data['error_address1'] = $this->language->get('error_address1');
            $data['error_address2'] = $this->language->get('error_address2');
            $data['error_city'] = $this->language->get('error_city');
            $data['error_postcode'] = $this->language->get('error_postcode');
            $data['error_country'] = $this->language->get('error_country');
            
            if (isset($this->error['name'])) {
                    $data['error_name'] = $this->error['name'];
            } else {
                    $data['error_name'] = '';
            }

            if (isset($this->error['email'])) {
                    $data['error_email'] = $this->error['email'];
            } else {
                    $data['error_email'] = '';
            }

            if (isset($this->error['quote'])) {
                    $data['error_quote'] = $this->error['quote'];
            } else {
                    $data['error_quote'] = '';
            }
            
            if (isset($this->error['phone'])) {
                    $data['error_phone'] = $this->error['phone'];
            } else {
                    $data['error_phone'] = '';
            }
            if (isset($this->error['address1'])) {
                    $data['error_address1'] = $this->error['address1'];
            } else {
                    $data['error_address1'] = '';
            }
            if (isset($this->error['address2'])) {
                    $data['error_address2'] = $this->error['address2'];
            } else {
                    $data['error_address2'] = '';
            }
            if (isset($this->error['city'])) {
                    $data['error_city'] = $this->error['city'];
            } else {
                    $data['error_city'] = '';
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
            
            
            if (isset($this->request->post['name'])) {
                    $data['name'] = $this->request->post['name'];
            } else {
                    $data['name'] = $this->customer->getFirstName();
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
            
            $addresses = $this->model_account_address->getAddress($this->customer->getAddressId());
            
            if (isset($this->request->post['quote'])) {
                    $data['quote'] = $this->request->post['quote'];
            } else {
                    $data['quote'] = !empty($html)?$html:'';
            }
            if (isset($this->request->post['address1'])) {
                    $data['address1'] = $this->request->post['address1'];
            } else {
                    $data['address1'] = (!empty($addresses)?$addresses['address1'] :'');
            }
            if (isset($this->request->post['address2'])) {
                    $data['address2'] = $this->request->post['address2'];
            } else {
                    $data['address2'] = (!empty($addresses)?$addresses['address2'] :'');
            }
            if (isset($this->request->post['city'])) {
                    $data['city'] = $this->request->post['city'];
            } else {
                    $data['city'] = (!empty($addresses)?$addresses['city'] :'');
            }
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

            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => ''
            );

            $this->document->setTitle($this->language->get('heading_title'));

            $data['heading_title'] = $this->language->get('heading_title');

            $data['text_quote'] = $this->language->get('quote_request');

            $data['button_continue'] = $this->language->get('button_continue');                            

            $data['continue'] = $this->url->link('common/home');

            $data['column_left'] = '';
            $data['column_right'] = '';
            $data['content_top'] = '';
            $data['content_bottom'] = '';
            $data['footer'] = $this->load->controller('common/footer');
            $data['header'] = $this->load->controller('common/header');
            $data['button_submit'] = $this->language->get('button_submit');

            $this->load->model('localisation/country');

            $data['countries'] = $this->model_localisation_country->getCountries();

            $this->response->setOutput($this->load->view('product/quote', $data));
        }
        
        protected function validate() {
            if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 32)) {
                    $this->error['name'] = $this->language->get('error_name');
            }

            if (!filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
                    $this->error['email'] = $this->language->get('error_email');
            }
            if ((utf8_strlen($this->request->post['phone']) < 10)) {
                    $this->error['phone'] = $this->language->get('error_phone');
            }
            
            if ((empty($this->request->post['address1']))) {
                    $this->error['address1'] = $this->language->get('error_address1');
            }
            
            if ((empty($this->request->post['country']))) {
                    $this->error['country'] = $this->language->get('error_country');
            }
            
            if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 32)) {
                    $this->error['name'] = $this->language->get('error_name');
            }

            if ((utf8_strlen($this->request->post['quote']) < 10) || (utf8_strlen($this->request->post['quote']) > 10000)) {
                    $this->error['quote'] = $this->language->get('error_quote');
            }
            return !$this->error;
	}
        
}
