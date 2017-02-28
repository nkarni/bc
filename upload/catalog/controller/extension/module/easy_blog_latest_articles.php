<?php
class ControllerExtensionModuleEasyBlogLatestArticles extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/easy_blog_latest_articles');
        $this->load->model('blog/blog_category');
        $this->load->model('blog/article');

        $this->document->addStyle('catalog/view/javascript/easy_blog/easy_blog.css');

		$data['heading_title'] = $this->language->get('heading_title');
        $data['button_read_more'] = $this->language->get('button_read_more');
        $data['name'] = $setting['name'];

        $data['column'] = $setting['column'];

        $data['show'] = array(
            'date' => $setting['show_date'],
            'author' => $setting['show_author'],
            'view' => $setting['show_viewed'],
            'comment' => $setting['show_number_of_comments'],
            'category' => $setting['show_category'],
            'tag'=> $setting['show_tag']
        );
        
        $data['articles'] = array();

        $filter_data = array(
            'filter_blog_category_id' => $setting['blog_category_id'],
            'start'              => 0,
            'limit'              => $setting['limit'],
            'filter_sub_category' => $setting['include_sub_category']
        );

        $results = $this->model_blog_article->getArticles($filter_data);

        foreach ($results as $result) {
            $categories_result = $this->model_blog_article->getCategoriesWithName($result['article_id']);
            $categories = array();
            foreach ($categories_result as $category){
                $categories[]=array(
                    'name' => $category['name'],
                    'href' => $this->url->link('blog/blog_category', 'blog_path=' . $category['blog_category_id'])
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
                'href'          => $this->url->link('blog/article', 'article_id=' . $result['article_id']),
                'categories'    => $categories,
                'tags'          => $tags
            );
        }

        if ($data['articles']) {
            return $this->load->view('extension/module/easy_blog_latest_articles', $data);           
        }
	}
}