<?php
class ControllerExtensionModuleEasyBlogCategoryMenu extends Controller {
	public function index() {
		$this->load->language('extension/module/easy_blog_category_menu');

		$data['heading_title'] = $this->language->get('heading_title');

        $this->load->model('blog/blog_category');

        $this->load->model('blog/article');

        if (isset($this->request->get['blog_path'])) {
            $parts = explode('_', (string)$this->request->get['blog_path']);
        } else {
            if (isset($this->request->get['article_id'])) {
				$cats = array();
				$cats = $this->model_blog_article->getCategories($this->request->get['article_id']);
                if (isset($cats[0]) && $cats[0]) {
                        $parts = $this->model_blog_blog_category->getParentBlogCategoriesByBlogCategoryId($cats[0]['blog_category_id']);
                }
            } else {
                $parts =  array();
            }

        }

		if (isset($parts[0])) {
			$data['blog_category_id'] = $parts[0];
		} else {
			$data['blog_category_id'] = 0;
		}

		if (isset($parts[1])) {
			$data['blog_child_id'] = $parts[1];
		} else {
			$data['blog_child_id'] = 0;
		}

		$data['blog_categories'] = array();

		$blog_categories = $this->model_blog_blog_category->getBlogCategories(0);

		foreach ($blog_categories as $blog_category) {
			$children_data = array();

			if ($blog_category['blog_category_id'] == $data['blog_category_id']) {
				$children = $this->model_blog_blog_category->getBlogCategories($blog_category['blog_category_id']);

				foreach($children as $child) {
					$filter_data = array('filter_blog_category_id' => $child['blog_category_id'], 'filter_sub_category' => true);

					$children_data[] = array(
						'blog_category_id' => $child['blog_category_id'],
						//TODO config articles count
                        'name' => $child['name'] . ($this->config->get('easy_blog_category_menu_article_count') ? ' (' . $this->model_blog_article->getTotalArticles($filter_data) . ')' : ''),
						'href' => $this->url->link('blog/blog_category', 'blog_path=' . $blog_category['blog_category_id'] . '_' . $child['blog_category_id'])
					);
				}
			}

			$filter_data = array(
				'filter_blog_category_id'  => $blog_category['blog_category_id'],
				'filter_sub_category' => true
			);

			$data['blog_categories'][] = array(
				'blog_category_id' => $blog_category['blog_category_id'],
                //TODO config articles count
                'name'        => $blog_category['name'] . ($this->config->get('easy_blog_category_menu_article_count') ? ' (' . $this->model_blog_article->getTotalArticles($filter_data) . ')' : ''),
				'children'    => $children_data,
				'href'        => $this->url->link('blog/blog_category', 'blog_path=' . $blog_category['blog_category_id'])
			);
		}

		return $this->load->view('extension/module/easy_blog_category_menu', $data);
		
	}
}