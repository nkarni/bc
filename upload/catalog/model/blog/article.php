<?php
class ModelBlogArticle extends Model {
    public function updateViewed($article_id) {
        $this->db->query("UPDATE " . DB_PREFIX . "easy_blog_article SET viewed = (viewed + 1) WHERE article_id = '" . (int)$article_id . "'");
    }
    public function getArticle($article_id) {
        $query = $this->db->query("SELECT DISTINCT a.article_id, a.sort_order, a.status, a.date_modified, a.viewed, ad.language_id, ad.name, ad.description, ad.intro_text, ad.meta_title, ad.meta_description, ad.meta_keyword, ad.author, ad.tag, (SELECT COUNT(*) FROM " . DB_PREFIX . "easy_blog_comment c WHERE c.article_id = a.article_id AND c.status=1) AS comments_published FROM " . DB_PREFIX . "easy_blog_article a LEFT JOIN " . DB_PREFIX . "easy_blog_article_description ad ON (a.article_id = ad.article_id) WHERE a.article_id = '" . (int)$article_id . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND a.status = '1' ");

		if ($query->num_rows) {
			return array(
				'article_id'       => $query->row['article_id'],
				'name'             => $query->row['name'],
				'description'      => $query->row['description'],
				'meta_title'       => $query->row['meta_title'],
				'meta_description' => $query->row['meta_description'],
				'meta_keyword'     => $query->row['meta_keyword'],
				'intro_text'       => $query->row['intro_text'],
				'sort_order'       => $query->row['sort_order'],
				'status'           => $query->row['status'],
				'date_modified'    => $query->row['date_modified'],
                'author'           => $query->row['author'],
                'tag'              => $query->row['tag'],
                'comments'         => $query->row['comments_published'],
                'viewed'           => $query->row['viewed']
			);
		} else {
			return false;
		}
	}

    public function getArticles($data = array()) {
        $sql = "SELECT a.article_id ";

        if (!empty($data['filter_blog_category_id'])) {
            if (!empty($data['filter_sub_category'])) {
                $sql .= " FROM " . DB_PREFIX . "easy_blog_category_path ca LEFT JOIN " . DB_PREFIX . "easy_blog_article_to_blog_category a2c ON (ca.blog_category_id = a2c.blog_category_id)";
            } else {
                $sql .= " FROM " . DB_PREFIX . "easy_blog_article_to_blog_category a2c";
            }

            $sql .= " LEFT JOIN " . DB_PREFIX . "easy_blog_article a ON (a2c.article_id = a.article_id)";

        } else {
            $sql .= " FROM " . DB_PREFIX . "easy_blog_article a";
        }

        $sql .= " LEFT JOIN " . DB_PREFIX . "easy_blog_article_description ad ON (a.article_id = ad.article_id) WHERE ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND a.status = '1' ";

        if (!empty($data['filter_blog_category_id'])) {
            if (!empty($data['filter_sub_category'])) {
                $sql .= " AND ca.path_id = '" . (int)$data['filter_blog_category_id'] . "'";
            } else {
                $sql .= " AND a2c.blog_category_id = '" . (int)$data['filter_blog_category_id'] . "'";
            }
        }

        if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
            $sql .= " AND (";

            if (!empty($data['filter_name'])) {
                $implode = array();

                $words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

                foreach ($words as $word) {
                    $implode[] = "ad.name LIKE '%" . $this->db->escape($word) . "%'";
                }

                if ($implode) {
                    $sql .= " " . implode(" AND ", $implode) . "";
                }

                if (!empty($data['filter_description'])) {
                    $sql .= " OR ad.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
                }
            }

            if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
                $sql .= " OR ";
            }

            if (!empty($data['filter_tag'])) {
                $sql .= "ad.tag LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_tag'])) . "%'";
            }

            $sql .= ")";
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

        $article_data = array();

        $query = $this->db->query($sql);

        foreach ($query->rows as $result) {
            $article_data[$result['article_id']] = $this->getArticle($result['article_id']);
        }

        return $article_data;
    }

	public function getLatestArticles($limit) {
		$article_data = $this->cache->get('article.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$limit);

        if (!$article_data) {
            $query = $this->db->query("SELECT article_id FROM " . DB_PREFIX . "easy_blog_article WHERE status = '1' ORDER BY date_modified DESC LIMIT " . (int)$limit);

            foreach ($query->rows as $result) {
                $article_data[$result['article_id']] = $this->getArticle($result['article_id']);
            }

            $this->cache->set('article.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$limit, $article_data);
        }

        return $article_data;
	}

	public function getCategories($article_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "easy_blog_article_to_blog_category WHERE article_id = '" . (int)$article_id . "'");

		return $query->rows;
	}

    public function getCategoriesWithName($article_id) {
        $query = $this->db->query("SELECT atc.blog_category_id, cd.name FROM " . DB_PREFIX . "easy_blog_category_description cd LEFT JOIN " . DB_PREFIX . "easy_blog_article_to_blog_category atc ON cd.blog_category_id = atc.blog_category_id WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND atc.article_id = '" . (int)$article_id . "'");

        return $query->rows;
    }

	public function getTotalArticles($data = array()) {
		$sql = "SELECT COUNT(DISTINCT a.article_id) AS total";

		if (!empty($data['filter_blog_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM " . DB_PREFIX . "easy_blog_category_path cp LEFT JOIN " . DB_PREFIX . "easy_blog_article_to_blog_category a2c ON (cp.blog_category_id = a2c.blog_category_id)";
			} else {
				$sql .= " FROM " . DB_PREFIX . "easy_blog_article_to_blog_category a2c";
			}
			$sql .= " LEFT JOIN " . DB_PREFIX . "easy_blog_article a ON (a2c.article_id = a.article_id)";
		} else {
			$sql .= " FROM " . DB_PREFIX . "easy_blog_article a";
		}

		$sql .= " LEFT JOIN " . DB_PREFIX . "easy_blog_article_description ad ON (a.article_id = ad.article_id) WHERE ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND a.status = '1' ";

		if (!empty($data['filter_blog_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND cp.path_id = '" . (int)$data['filter_blog_category_id'] . "'";
			} else {
				$sql .= " AND a2c.blog_category_id = '" . (int)$data['filter_blog_category_id'] . "'";
			}
		}

        if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
            $sql .= " AND (";

            if (!empty($data['filter_name'])) {
                $implode = array();

                $words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

                foreach ($words as $word) {
                    $implode[] = "ad.name LIKE '%" . $this->db->escape($word) . "%'";
                }

                if ($implode) {
                    $sql .= " " . implode(" AND ", $implode) . "";
                }

                if (!empty($data['filter_description'])) {
                    $sql .= " OR ad.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
                }
            }

            if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
                $sql .= " OR ";
            }

            if (!empty($data['filter_tag'])) {
                $sql .= "ad.tag LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_tag'])) . "%'";
            }

            $sql .= ")";
        }

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

}
