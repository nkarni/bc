<?php
class ModelBlogComment extends Model {
	public function addComment($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "easy_blog_comment SET author = '" . $this->db->escape($data['author']) . "', article_id = '" . (int)$data['article_id'] . "', text = '" . $this->db->escape(strip_tags($data['text'])) . "', status = '" . (int)$data['status'] . "', date_modified = NOW()");

		$comment_id = $this->db->getLastId();

		$this->cache->delete('article');

		return $comment_id;
	}

	public function editComment($comment_id, $data) {

		$this->db->query("UPDATE " . DB_PREFIX . "easy_blog_comment SET author = '" . $this->db->escape($data['author']) . "', article_id = '" . (int)$data['article_id'] . "', text = '" . $this->db->escape(strip_tags($data['text'])) . "', status = '" . (int)$data['status'] . "', date_modified = NOW() WHERE comment_id = '" . (int)$comment_id . "'");

		$this->cache->delete('article');
	}

	public function deleteComment($comment_id) {

		$this->db->query("DELETE FROM " . DB_PREFIX . "easy_blog_comment WHERE comment_id = '" . (int)$comment_id . "'");

		$this->cache->delete('article');

	}

	public function getComment($comment_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT ad.name FROM " . DB_PREFIX . "easy_blog_article_description ad WHERE ad.article_id = c.article_id AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "') AS article FROM " . DB_PREFIX . "easy_blog_comment c WHERE c.comment_id = '" . (int)$comment_id . "'");

		return $query->row;
	}

	public function getComments($data = array()) {
		$sql = "SELECT c.comment_id, c.text, ad.name, c.author, c.status, c.date_modified FROM " . DB_PREFIX . "easy_blog_comment c LEFT JOIN " . DB_PREFIX . "easy_blog_article_description ad ON (c.article_id = ad.article_id) WHERE ad.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_article'])) {
			$sql .= " AND ad.name LIKE '" . $this->db->escape($data['filter_article']) . "%'";
		}

        if (!empty($data['filter_article_id'])) {
			$sql .= " AND c.article_id=" . $this->db->escape($data['filter_article_id']);
		}

		if (!empty($data['filter_author'])) {
			$sql .= " AND c.author LIKE '" . $this->db->escape($data['filter_author']) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND c.status = '" . (int)$data['filter_status'] . "'";
		}

		if (!empty($data['filter_date_modified'])) {
			$sql .= " AND DATE(c.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}

		$sort_data = array(
			'ad.name',
			'c.author',
			'c.status',
			'c.date_modified'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY c.date_modified";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
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

	public function getTotalComments($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "easy_blog_comment c LEFT JOIN " . DB_PREFIX . "easy_blog_article_description ad ON (c.article_id = ad.article_id) WHERE ad.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_article'])) {
			$sql .= " AND ad.name LIKE '" . $this->db->escape($data['filter_article']) . "%'";
		}

        if (!empty($data['filter_article_id'])) {
			$sql .= " AND c.article_id = " . $this->db->escape($data['filter_article_id']) ;
		}

		if (!empty($data['filter_author'])) {
			$sql .= " AND c.author LIKE '" . $this->db->escape($data['filter_author']) . "%'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND c.status = '" . (int)$data['filter_status'] . "'";
		}

		if (!empty($data['filter_date_modified'])) {
			$sql .= " AND DATE(c.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalCommentsAwaitingApproval() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "easy_blog_comment WHERE status = '0'");

		return $query->row['total'];
	}

    public function getCommentsByArticleId($article_id, $start = 0, $limit = 20) {
        if ($start < 0) {
            $start = 0;
        }

        if ($limit < 1) {
            $limit = 20;
        }

        $query = $this->db->query("SELECT c.comment_id, c.author, c.text, a.article_id, ad.name, c.date_modified FROM " . DB_PREFIX . "easy_blog_comment c LEFT JOIN " . DB_PREFIX . "easy_blog_article a ON (c.article_id = a.article_id) LEFT JOIN " . DB_PREFIX . "easy_blog_article_description ad ON (a.article_id = ad.article_id) WHERE a.article_id = '" . (int)$article_id . "' AND a.status = '1' AND c.status = '1' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY c.date_modified DESC LIMIT " . (int)$start . "," . (int)$limit);

        return $query->rows;
    }

    public function getTotalCommentsByArticleId($article_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "easy_blog_comment c LEFT JOIN " . DB_PREFIX . "easy_blog_article a ON (c.article_id = a.article_id) LEFT JOIN " . DB_PREFIX . "easy_blog_article_description ad ON (a.article_id = ad.article_id) WHERE a.article_id = '" . (int)$article_id . "' AND a.status = '1' AND c.status = '1' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "'");

        return $query->row['total'];
    }

    public function setCommentStatus($comment_id, $status){
        $this->db->query("UPDATE " . DB_PREFIX . "easy_blog_comment SET status = '" . (int)$status . "' WHERE comment_id = '" . (int)$comment_id . "'");
    }
}