<?php
class ControllerBlogArticle extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('blog/article');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->config->get('easy_blog_home_page_name'),
			'href' => $this->url->link('blog/blog')
		);

		$this->load->model('blog/blog_category');

		if (isset($this->request->get['blog_path'])) {
			$blog_path = '';

			$parts = explode('_', (string)$this->request->get['blog_path']);

			$blog_category_id = (int)array_pop($parts);

			foreach ($parts as $path_id) {
				if (!$blog_path) {
					$blog_path = $path_id;
				} else {
					$blog_path .= '_' . $path_id;
				}

				$blog_category_info = $this->model_blog_blog_category->getBlogCategory($path_id);

				if ($blog_category_info) {
					$data['breadcrumbs'][] = array(
						'text' => $blog_category_info['name'],
						'href' => $this->url->link('blog/blog_category', 'blog_path=' . $blog_path)
					);
				}
			}

			// Set the last category breadcrumb
			$blog_category_info = $this->model_blog_blog_category->getBlogCategory($blog_category_id);

			if ($blog_category_info) {
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
					'text' => $blog_category_info['name'],
					'href' => $this->url->link('blog/blog_category', 'blog_path=' . $this->request->get['blog_path'] . $url)
				);
			}
		}

		if (isset($this->request->get['article_id'])) {
			$article_id = (int)$this->request->get['article_id'];
		} else {
			$article_id = 0;
		}

		$this->load->model('blog/article');

		$article_info = $this->model_blog_article->getArticle($article_id);

		if ($article_info && ($this->config->get('easy_blog_global_status'))) {

            $this->model_blog_article->updateViewed($article_id);

            $url = '';

			if (isset($this->request->get['blog_path'])) {
				$url .= '&blog_path=' . $this->request->get['blog_path'];
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}

			if (isset($this->request->get['blog_category_id'])) {
				$url .= '&blog_category_id=' . $this->request->get['blog_category_id'];
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
				'text' => $article_info['name'],
				'href' => $this->url->link('blog/article', $url . '&article_id=' . $this->request->get['article_id'])
			);
            if ($this->config->get('easy_blog_home_page_meta_title')) {
                $this->document->setTitle($this->config->get('easy_blog_home_page_meta_title') . ' - ' . $article_info['meta_title']);
            } else {
                $this->document->setTitle($article_info['meta_title']);
            }

            if ($this->config->get('easy_blog_home_page_meta_description')){
                $this->document->setDescription($this->config->get('easy_blog_home_page_meta_description') . ' ' .$article_info['meta_description']);
            } else {
                $this->document->setDescription($article_info['meta_description']);
            }

            if ($this->config->get('easy_blog_home_page_meta_keyword')){
                $this->document->setKeywords($this->config->get('easy_blog_home_page_meta_keyword') . ' ' .$article_info['meta_keyword']);
            } else {
                $this->document->setKeywords($article_info['meta_keyword']);
            }

            if ($this->config->get('easy_blog_comment_captcha_status')) {
                $this->document->addScript('https://www.google.com/recaptcha/api.js');
                $data['site_key'] = $this->config->get('easy_blog_comment_captcha_public');
            } else {
                $data['site_key'] = '';
            }

			$data['show'] = array(
				'date' => $this->config->get('easy_blog_article_show_date'),
				'author' => $this->config->get('easy_blog_article_show_author'),
				'view' => $this->config->get('easy_blog_article_show_viewed'),
				'comment' => $this->config->get('easy_blog_article_show_number_of_comments'),
				'category' => $this->config->get('easy_blog_article_show_category'),
				'tag'=> $this->config->get('easy_blog_article_show_tag')
			);

			$this->document->addStyle('catalog/view/javascript/easy_blog/easy_blog.css');

			$this->document->addLink($this->url->link('blog/article', 'article_id=' . $this->request->get['article_id']), 'canonical');

            $this->load->model('blog/comment');

			$categories_result = $this->model_blog_article->getCategoriesWithName($article_info['article_id']);
			$data['categories'] = array();
			foreach ($categories_result as $category){
				$data['categories'][] = array(
					'name' => $category['name'],
					'href' => $this->url->link('blog/blog_category', 'blog_path=' . $category['blog_category_id'] . $url)
				);
			}

			$data['tags'] = array();

            if ($article_info['tag']) {
                $tags = explode(',', $article_info['tag']);
                foreach ($tags as $tag) {
                    $data['tags'][] = array(
                        'tag'  => trim($tag),
                        'href' => $this->url->link('blog/blog', 'tag=' . trim($tag))
                    );
                }
            }


            $data['comment_status']=$this->config->get('easy_blog_comment_config_status');
			if ($this->config->get('easy_blog_comment_config_guest') || $this->customer->isLogged()) {
				$data['comment_guest'] = true;
			} else {
				$data['comment_guest'] = false;
			}

			if ($this->customer->isLogged()) {
				$data['author_name'] = $this->customer->getFirstName() . '&nbsp;' . $this->customer->getLastName();
			} else {
				$data['author_name'] = '';
			}

            $data['text_tags'] = $this->language->get('text_tags');
            $data['text_write'] = $this->language->get('text_write');
            $data['text_note'] = $this->language->get('text_note');
            $data['text_loading'] = $this->language->get('text_loading');
			$data['text_login'] = sprintf($this->language->get('text_login'), $this->url->link('account/login', '', true), $this->url->link('account/register', '', true));
            $data['button_continue'] = $this->language->get('button_continue');
            $data['entry_name'] = $this->language->get('entry_name');
            $data['entry_comment'] = $this->language->get('entry_comment');
            $data['entry_comments'] = $this->language->get('entry_comments');
			$data['heading_title'] = $article_info['name'];
            $data['date'] = date($this->language->get('date_format_short'), strtotime($article_info['date_modified']));
			$data['article_id'] = (int)$this->request->get['article_id'];
			$data['description'] = html_entity_decode($article_info['description'], ENT_QUOTES, 'UTF-8');
			$data['date_modified'] = date($this->language->get('date_format_short'), strtotime($article_info['date_modified']));
			$data['viewed'] = $article_info['viewed'];
			$data['comments'] = $article_info['comments'];
            $data['author'] = html_entity_decode($article_info['author'], ENT_QUOTES, 'UTF-8');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('blog/article', $data));
			
		} else {

            $this->load->language('error/not_found');

            $url = '';

			if (isset($this->request->get['blog_path'])) {
				$url .= '&blog_path=' . $this->request->get['blog_path'];
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . $this->request->get['search'];
			}

			if (isset($this->request->get['description'])) {
				$url .= '&description=' . $this->request->get['description'];
			}

			if (isset($this->request->get['blog_category_id'])) {
				$url .= '&blog_category_id=' . $this->request->get['blog_category_id'];
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
				'href' => $this->url->link('blog/article', $url . '&article_id=' . $article_id)
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

			$this->response->setOutput($this->load->view('error/not_found', $data));
			
		}
	}

    public function comment() {
        $this->load->language('blog/article');

        $this->load->model('blog/comment');

        $data['text_no_comments'] = $this->language->get('text_no_comments');
        $data['entry_comments'] = $this->language->get('entry_comments');

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

		$comment_total = $this->model_blog_comment->getTotalCommentsByArticleId($this->request->get['article_id']);

		$data['comments'] = $comment_total;

		$data['comments_list'] = array();

		$comments_list = $this->model_blog_comment->getCommentsByArticleId($this->request->get['article_id'], 0, 1000);

		foreach ($comments_list as $comment) {
			$data['comments_list'][] = array(
				'author'     => $comment['author'],
				'text'       => nl2br($comment['text']),
				'date_modified' => date($this->language->get('datetime_format'), strtotime($comment['date_modified']))
			);
		}

        $this->response->setOutput($this->load->view('blog/comment', $data));
       
    }

    public function write() {
        $this->load->language('blog/article');

        $json = array();

        if ($this->request->server['REQUEST_METHOD'] == 'POST') {
            if ((utf8_strlen($this->request->post['name']) < 1) || (utf8_strlen($this->request->post['name']) > 25)) {
                $json['error'] = $this->language->get('error_name');
            }

            if ((utf8_strlen($this->request->post['text']) < 1) || (utf8_strlen($this->request->post['text']) > 10000)) {
                $json['error'] = $this->language->get('error_text');
            }

            if ($this->config->get('easy_blog_comment_captcha_status') && empty($json['error'])) {
                if (isset($this->request->post['g-recaptcha-response'])) {
                    $recaptcha = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($this->config->get('easy_blog_comment_captcha_secret')) . '&response=' . $this->request->post['g-recaptcha-response'] . '&remoteip=' . $this->request->server['REMOTE_ADDR']);

                    $recaptcha = json_decode($recaptcha, true);

                    if (!$recaptcha['success']) {
                        $json['error'] = $this->language->get('error_captcha');
                    }
                } else {
                    $json['error'] = $this->language->get('error_captcha');
                }
            }

            if (!isset($json['error'])) {
                $this->load->model('blog/comment');

                $this->model_blog_comment->addComment($this->request->get['article_id'], $this->request->post);

				if ($this->config->get('easy_blog_comment_config_approve')){
					$json['success'] = $this->language->get('text_success') . " " . $this->language->get('text_after_approve');
				} else {
					$json['success'] = $this->language->get('text_success');
				}
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

}
