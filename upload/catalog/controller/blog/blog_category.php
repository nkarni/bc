<?php
class ControllerBlogBlogCategory extends Controller {
	public function index() {
		$this->load->language('blog/blog_category');

		$this->load->model('blog/blog_category');

		$this->load->model('blog/article');

		if (isset($this->request->get['filter'])) {
			$filter = $this->request->get['filter'];
		} else {
			$filter = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.sort_order';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = $this->config->get('easy_blog_global_article_limit');
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

//		$data['breadcrumbs'][] = array(
//			'text' => $this->config->get('easy_blog_home_page_name'),
//			'href' => $this->url->link('blog/blog')
//		);

		if (isset($this->request->get['blog_path'])) {
			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$blog_path = '';

			$parts = explode('_', (string)$this->request->get['blog_path']);

			$blog_category_id = (int)array_pop($parts);

			foreach ($parts as $path_id) {
				if (!$blog_path) {
					$blog_path = (int)$path_id;
				} else {
					$blog_path .= '_' . (int)$path_id;
				}

				$blog_category_info = $this->model_blog_blog_category->getBlogCategory($path_id);

				if ($blog_category_info) {
					$data['breadcrumbs'][] = array(
						'text' => $blog_category_info['name'],
						'href' => $this->url->link('blog/blog_category', 'blog_path=' . $blog_path . $url)
					);
				}
			}
		} else {
			$blog_category_id = 0;
		}

		$blog_category_info = $this->model_blog_blog_category->getBlogCategory($blog_category_id);

		if ($blog_category_info && ($this->config->get('easy_blog_global_status'))) {
			if ($this->config->get('easy_blog_home_page_meta_title')) {
                $this->document->setTitle($this->config->get('easy_blog_home_page_meta_title') . ' - ' . $blog_category_info['meta_title']);
            } else {
                $this->document->setTitle($blog_category_info['meta_title']);
            }

            if ($this->config->get('easy_blog_home_page_meta_description')){
                $this->document->setDescription($this->config->get('easy_blog_home_page_meta_description') . ' ' .$blog_category_info['meta_description']);
            } else {
                $this->document->setDescription($blog_category_info['meta_description']);
            }

            if ($this->config->get('easy_blog_home_page_meta_keyword')){
                $this->document->setKeywords($this->config->get('easy_blog_home_page_meta_keyword') . ' ' .$blog_category_info['meta_keyword']);
            } else {
                $this->document->setKeywords($blog_category_info['meta_keyword']);
            }

			$this->document->setKeywords($blog_category_info['meta_keyword']);
			$this->document->addLink($this->url->link('blog/blog_category', 'blog_path=' . $this->request->get['blog_path']), 'canonical');

			$this->document->addStyle('catalog/view/javascript/easy_blog/easy_blog.css');

			$data['heading_title'] = $blog_category_info['name'];

			// Set the last category breadcrumb
			$data['breadcrumbs'][] = array(
				'text' => $blog_category_info['name'],
				'href' => $this->url->link('blog/blog_category', 'blog_path=' . $this->request->get['blog_path'])
			);

			$data['description'] = html_entity_decode($blog_category_info['description'], ENT_QUOTES, 'UTF-8');

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['blog_categories'] = array();

			$results = $this->model_blog_blog_category->getBlogCategories($blog_category_id);

			foreach ($results as $result) {
				$filter_data = array(
					'filter_blog_category_id'  => $result['blog_category_id'],
					'filter_sub_category' => true
				);

				$data['blog_categories'][] = array(
					'name'  => $result['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_blog_article->getTotalArticles($filter_data) . ')' : ''),
					'href'  => $this->url->link('blog/blog_category', 'blog_path=' . $this->request->get['blog_path'] . '_' . $result['blog_category_id'] . $url)
				);
			}

			$data['articles'] = array();

			$filter_data = array(
				'filter_blog_category_id' => $blog_category_id,
                'filter_sub_category' => true,
				'filter_filter'      => $filter,
				'sort'               => $sort,
				'order'              => $order,
				'start'              => ($page - 1) * $limit,
				'limit'              => $limit
			);



			$article_total = $this->model_blog_article->getTotalArticles($filter_data);

			$results = $this->model_blog_article->getArticles($filter_data);

			foreach ($results as $result) {
				$categories_result = $this->model_blog_article->getCategoriesWithName($result['article_id']);
				$categories = array();
				foreach ($categories_result as $category){
					$categories[]=array(
						'name' => $category['name'],
						'href' => $this->url->link('blog/blog_category', 'blog_path=' . $category['blog_category_id'] . $url)
					);
				}

				$tags=array();
				if ($result['tag']) {
					$tags_result = explode(',', $result['tag']);
					foreach ($tags_result as $tag) {
						$tags[] = array(
							'tag' => trim($tag),
							'href' => $this->url->link('blog/blog', 'tag=' . trim($tag))
						);
					}
				}

				$data['articles'][] = array(
					'article_id'    => $result['article_id'],
					'name'          => $result['name'],
					'date_modified' => date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
					'author'        => $result['author'],
					'viewed'        => $result['viewed'],
					'comments'      => $result['comments'],
					'intro_text'    => html_entity_decode($result['intro_text'], ENT_QUOTES, 'UTF-8'),
					'href'          => $this->url->link('blog/article', 'blog_path=' . $this->request->get['blog_path'] . '&article_id=' . $result['article_id']),
					'categories'    => $categories,
					'tags'          => $tags
				);
			}

			$data['show'] = array(
				'date' => $this->config->get('easy_blog_category_show_date'),
				'author' => $this->config->get('easy_blog_category_show_author'),
				'view' => $this->config->get('easy_blog_category_show_viewed'),
				'comment' => $this->config->get('easy_blog_category_show_number_of_comments'),
				'category' => $this->config->get('easy_blog_category_show_category'),
				'tag'=> $this->config->get('easy_blog_category_show_tag')
			);

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$data['sorts'] = array();

			$data['sorts'][] = array(
				'text'  => $this->language->get('text_default'),
				'value' => 'p.sort_order-ASC',
				'href'  => $this->url->link('blog/blog_category', 'blog_path=' . $this->request->get['blog_path'] . '&sort=p.sort_order&order=ASC' . $url)
			);

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			$data['limits'] = array();

			$limits = array_unique(array($this->config->get('easy_blog_global_article_limit'), 50, 75, 100));

			sort($limits);

			foreach($limits as $value) {
				$data['limits'][] = array(
					'text'  => $value,
					'value' => $value,
					'href'  => $this->url->link('blog/blog_category', 'blog_path=' . $this->request->get['blog_path'] . $url . '&limit=' . $value)
				);
			}

			$url = '';

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

			$pagination = new Pagination();
			$pagination->total = $article_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->url = $this->url->link('blog/blog_category', 'blog_path=' . $this->request->get['blog_path'] . $url . '&page={page}');

			$data['pagination'] = $pagination->render();

			$data['results'] = sprintf($this->language->get('text_pagination'), ($article_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($article_total - $limit)) ? $article_total : ((($page - 1) * $limit) + $limit), $article_total, ceil($article_total / $limit));

			$data['sort'] = $sort;
			$data['order'] = $order;
			$data['limit'] = $limit;
			
            $data['button_read_more'] = $this->language->get('button_read_more');
            $data['text_empty'] = $this->language->get('text_empty');
            $data['button_continue'] = $this->language->get('button_continue');
            $data['continue'] = $this->url->link('common/home');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('blog/blog_category', $data));
			
		} else {
            $this->load->language('error/not_found');

            $url = '';

			if (isset($this->request->get['blog_path'])) {
				$url .= '&blog_path=' . $this->request->get['blog_path'];
			}

			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
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
				'href' => $this->url->link('product/category', $url)
			);

			$this->document->setTitle($this->language->get('text_error'));

			$data['heading_title'] = $this->language->get('text_error');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_read_more'] = $this->language->get('button_read_more');

            $data['text_empty'] = $this->language->get('text_empty');
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
}