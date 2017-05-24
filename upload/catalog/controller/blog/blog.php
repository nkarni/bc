<?php
class ControllerBlogBlog extends Controller {
	public function index() {
		$this->load->language('blog/blog');

		$this->load->model('blog/article');

        if (isset($this->request->get['tag'])) {
            $tag = $this->request->get['tag'];
        } else {
            $tag = '';
        }

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

        if ($this->config->get('easy_blog_global_status')) {

            $this->document->addStyle('catalog/view/javascript/easy_blog/easy_blog.css');

            if (isset($this->request->get['tag'])) {
                $this->document->setTitle($this->config->get('easy_blog_home_page_meta_title') .  ' - ' . $this->language->get('heading_tag') . $this->request->get['tag']);
            } else {
                $this->document->setTitle($this->config->get('easy_blog_home_page_meta_title'));
            }

            if ($this->config->get('easy_blog_home_page_meta_description')){
                $this->document->setDescription($this->config->get('easy_blog_home_page_meta_description'));
            }
            if ($this->config->get('easy_blog_home_page_meta_keyword')){
                $this->document->setKeywords($this->config->get('easy_blog_home_page_meta_keyword'));
            }

//            $data['breadcrumbs'][] = array(
//                'text' => $this->config->get('easy_blog_home_page_name'),
//                'href' => $this->url->link('blog/blog')
//            );

			$url = '';

			$data['articles'] = array();

			$filter_data = array(
				'filter_filter'      => $filter,
                'filter_tag'          => $tag,
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
					'href'          => $this->url->link('blog/article', 'article_id=' . $result['article_id'] . $url),
                    'categories'    => $categories,
                    'tags'          => $tags
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

            if (isset($this->request->get['tag'])) {
                $url .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
            }

			$pagination = new Pagination();
			$pagination->total = $article_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->url = $this->url->link('blog/blog', $url . '&page={page}');

			$data['pagination'] = $pagination->render();

			$data['results'] = sprintf($this->language->get('text_pagination'), ($article_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($article_total - $limit)) ? $article_total : ((($page - 1) * $limit) + $limit), $article_total, ceil($article_total / $limit));

			$data['show'] = array(
                'date' => $this->config->get('easy_blog_home_page_show_date'),
                'author' => $this->config->get('easy_blog_home_page_show_author'),
                'view' => $this->config->get('easy_blog_home_page_show_viewed'),
                'comment' => $this->config->get('easy_blog_home_page_show_number_of_comments'),
                'category' => $this->config->get('easy_blog_home_page_show_category'),
                'tag'=> $this->config->get('easy_blog_home_page_show_tag')
            );

            $data['sort'] = $sort;
			$data['order'] = $order;
			$data['limit'] = $limit;

            $data['button_read_more'] = $this->language->get('button_read_more');
            $data['text_empty'] = $this->language->get('text_empty');
            $data['button_continue'] = $this->language->get('button_continue');
            $data['continue'] = $this->url->link('common/home');
            $data['description'] = html_entity_decode($this->config->get('easy_blog_home_page_description'), ENT_QUOTES, 'UTF-8');

            if (isset($this->request->get['tag'])) {
                $data['heading_title'] = $this->language->get('text_tag_result') . $this->request->get['tag'];
            } else {
                $data['heading_title'] = false ; // html_entity_decode($this->config->get('easy_blog_home_page_name'), ENT_QUOTES, 'UTF-8');
            }

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('blog/blog', $data));
			
		} else {
            $this->load->language('error/not_found');

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

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

            if (isset($this->request->get['tag'])) {
                $url .= '&tag=' . urlencode(html_entity_decode($this->request->get['tag'], ENT_QUOTES, 'UTF-8'));
            }

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