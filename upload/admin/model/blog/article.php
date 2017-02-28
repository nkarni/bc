<?php
class ModelBlogArticle extends Model {

    public function addArticle($data) {

        $this->db->query("INSERT INTO " . DB_PREFIX . "easy_blog_article SET sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW()");

		$article_id = $this->db->getLastId();
        
		foreach ($data['article_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "easy_blog_article_description SET article_id = '" . (int)$article_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', tag = '" . $this->db->escape($value['tag'])  . "', intro_text = '" . $this->db->escape($value['intro_text']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', author = '" . $this->db->escape($value['author'])  . "'");
		}

		if (isset($data['article_blog_category'])) {
			$data['article_blog_category'] = array_unique($data['article_blog_category']);
			foreach ($data['article_blog_category'] as $blog_category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "easy_blog_article_to_blog_category SET article_id = '" . (int)$article_id . "', blog_category_id = '" . (int)$blog_category_id . "'");
			}
		}

		if (isset($data['keyword'])) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'article_id=" . (int)$article_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->cache->delete('article');

		return $article_id;
	}

	public function editArticle($article_id, $data) {

		$this->db->query("UPDATE " . DB_PREFIX . "easy_blog_article SET sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "', date_modified = NOW() WHERE article_id = '" . (int)$article_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "easy_blog_article_description WHERE article_id = '" . (int)$article_id . "'");

		foreach ($data['article_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "easy_blog_article_description SET article_id = '" . (int)$article_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', intro_text = '" . $this->db->escape($value['intro_text']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', tag = '" . $this->db->escape($value['tag']) . "', author = '" . $this->db->escape($value['author']) . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "easy_blog_article_to_blog_category WHERE article_id = '" . (int)$article_id . "'");
		
		

		if (isset($data['article_blog_category'])) {
			$data['article_blog_category'] = array_unique($data['article_blog_category']);
			foreach ($data['article_blog_category'] as $blog_category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "easy_blog_article_to_blog_category SET article_id = '" . (int)$article_id . "', blog_category_id = '" . (int)$blog_category_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'article_id=" . (int)$article_id . "'");

		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'article_id=" . (int)$article_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->cache->delete('article');
	}

	public function copyArticle($article_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "easy_blog_article a LEFT JOIN " . DB_PREFIX . "easy_blog_article_description ad ON (a.article_id = ad.article_id) WHERE a.article_id = '" . (int)$article_id . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		if ($query->num_rows) {
			$data = array();

			$data = $query->row;
            $data['status'] = '0';
            $data['viewed'] = 0;
			$data = array_merge($data, array('article_description' => $this->getArticleDescriptions($article_id)));
			$data = array_merge($data, array('article_blog_category' => $this->getArticleCategories($article_id)));

			$this->addArticle($data);
		}
	}

	public function deleteArticle($article_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "easy_blog_article WHERE article_id = '" . (int)$article_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "easy_blog_article_description WHERE article_id = '" . (int)$article_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "easy_blog_article_to_blog_category WHERE article_id = '" . (int)$article_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'article_id=" . (int)$article_id . "'");
		$this->cache->delete('article');
	}

	public function getArticle($article_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'article_id=" . (int)$article_id . "') AS keyword FROM " . DB_PREFIX . "easy_blog_article a LEFT JOIN " . DB_PREFIX . "easy_blog_article_description ad ON (a.article_id = ad.article_id) WHERE a.article_id = '" . (int)$article_id . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getArticles($data = array()) {
		$sql = "SELECT *, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "easy_blog_comment c WHERE c.article_id = a.article_id) AS comments_total, (SELECT COUNT(*) AS total_unpublished FROM " . DB_PREFIX . "easy_blog_comment c WHERE c.article_id = a.article_id AND c.status=0) AS comments_unpublished FROM " . DB_PREFIX . "easy_blog_article a LEFT JOIN " . DB_PREFIX . "easy_blog_article_description ad ON (a.article_id = ad.article_id) WHERE ad.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND ad.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND a.status = '" . (int)$data['filter_status'] . "'";
		}
		
		$sql .= " GROUP BY a.article_id";

        $sort_data = array(
            'ad.name',
            'a.status',
            'a.sort_order',
            'a.date_modified',
            'a.viewed',
            'comments_total'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY a.sort_order ASC, a.date_modified";
        }

        if (isset($data['order']) && ($data['order'] == 'ASC')) {
            $sql .= " ASC";
        } else {
            $sql .= " DESC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getArticlesByBlogCategoryId($blog_category_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "easy_blog_article a LEFT JOIN " . DB_PREFIX . "easy_blog_article_description ad ON (a.article_id = ad.article_id) LEFT JOIN " . DB_PREFIX . "easy_blog_article_to_blog_category a2c ON (a.article_id = a2c.article_id) WHERE ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND a2c.blog_category_id = '" . (int)$blog_category_id . "' ORDER BY ad.name ASC");

		return $query->rows;
	}

	public function getArticleDescriptions($article_id) {
		$article_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "easy_blog_article_description WHERE article_id = '" . (int)$article_id . "'");

		foreach ($query->rows as $result) {
			$article_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'description'      => $result['description'],
				'meta_title'       => $result['meta_title'],
				'meta_description' => $result['meta_description'],
				'meta_keyword'     => $result['meta_keyword'],
				'intro_text'       => $result['intro_text'],
				'author'           => $result['author'],
                'tag'              => $result['tag']
			);
		}

		return $article_description_data;
	}

	public function getArticleCategories($article_id) {
		$article_category_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "easy_blog_article_to_blog_category WHERE article_id = '" . (int)$article_id . "'");

		foreach ($query->rows as $result) {
			$article_category_data[] = $result['blog_category_id'];
		}

		return $article_category_data;
	}

	public function getTotalArticles($data = array()) {
		$sql = "SELECT COUNT(DISTINCT a.article_id) AS total FROM " . DB_PREFIX . "easy_blog_article a LEFT JOIN " . DB_PREFIX . "easy_blog_article_description ad ON (a.article_id = ad.article_id)";

		$sql .= " WHERE ad.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND ad.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND a.status = '" . (int)$data['filter_status'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

    public function setArticleStatus($article_id, $status){
        $this->db->query("UPDATE " . DB_PREFIX . "easy_blog_article SET status = '" . (int)$status . "' WHERE article_id = '" . (int)$article_id . "'");
    }

}