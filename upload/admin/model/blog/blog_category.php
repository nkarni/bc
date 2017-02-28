<?php
class ModelBlogBlogCategory extends Model {
	public function addBlogCategory($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "easy_blog_category SET parent_id = '" . (int)$data['parent_id'] . "', `column` = '" . (int)$data['column'] . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "'");

		$blog_category_id = $this->db->getLastId();

		foreach ($data['blog_category_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "easy_blog_category_description SET blog_category_id = '" . (int)$blog_category_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		// MySQL Hierarchical Data Closure Table Pattern
		$level = 0;

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "easy_blog_category_path` WHERE blog_category_id = '" . (int)$data['parent_id'] . "' ORDER BY `level` ASC");

		foreach ($query->rows as $result) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "easy_blog_category_path` SET `blog_category_id` = '" . (int)$blog_category_id . "', `path_id` = '" . (int)$result['path_id'] . "', `level` = '" . (int)$level . "'");

			$level++;
		}

		$this->db->query("INSERT INTO `" . DB_PREFIX . "easy_blog_category_path` SET `blog_category_id` = '" . (int)$blog_category_id . "', `path_id` = '" . (int)$blog_category_id . "', `level` = '" . (int)$level . "'");

		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'blog_category_id=" . (int)$blog_category_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->cache->delete('blog_category');

		return $blog_category_id;
	}

	public function editBlogCategory($blog_category_id, $data) {

		$this->db->query("UPDATE " . DB_PREFIX . "easy_blog_category SET parent_id = '" . (int)$data['parent_id'] . "', `column` = '" . (int)$data['column'] . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "' WHERE blog_category_id = '" . (int)$blog_category_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "easy_blog_category_description WHERE blog_category_id = '" . (int)$blog_category_id . "'");

		foreach ($data['blog_category_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "easy_blog_category_description SET blog_category_id = '" . (int)$blog_category_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
		}

		// MySQL Hierarchical Data Closure Table Pattern
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "easy_blog_category_path` WHERE path_id = '" . (int)$blog_category_id . "' ORDER BY level ASC");

		if ($query->rows) {
			foreach ($query->rows as $blog_category_path) {
				// Delete the path below the current one
				$this->db->query("DELETE FROM `" . DB_PREFIX . "easy_blog_category_path` WHERE blog_category_id = '" . (int)$blog_category_path['blog_category_id'] . "' AND level < '" . (int)$blog_category_path['level'] . "'");

				$blog_path = array();

				// Get the nodes new parents
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "easy_blog_category_path` WHERE blog_category_id = '" . (int)$data['parent_id'] . "' ORDER BY level ASC");

				foreach ($query->rows as $result) {
					$blog_path[] = $result['path_id'];
				}

				// Get whats left of the nodes current path
				$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "easy_blog_category_path` WHERE blog_category_id = '" . (int)$blog_category_path['blog_category_id'] . "' ORDER BY level ASC");

				foreach ($query->rows as $result) {
					$blog_path[] = $result['path_id'];
				}

				// Combine the paths with a new level
				$level = 0;

				foreach ($blog_path as $path_id) {
					$this->db->query("REPLACE INTO `" . DB_PREFIX . "easy_blog_category_path` SET blog_category_id = '" . (int)$blog_category_path['blog_category_id'] . "', `path_id` = '" . (int)$path_id . "', level = '" . (int)$level . "'");

					$level++;
				}
			}
		} else {
			// Delete the path below the current one
			$this->db->query("DELETE FROM `" . DB_PREFIX . "easy_blog_category_path` WHERE blog_category_id = '" . (int)$blog_category_id . "'");

			// Fix for records with no paths
			$level = 0;

			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "easy_blog_category_path` WHERE blog_category_id = '" . (int)$data['parent_id'] . "' ORDER BY level ASC");

			foreach ($query->rows as $result) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "easy_blog_category_path` SET blog_category_id = '" . (int)$blog_category_id . "', `path_id` = '" . (int)$result['path_id'] . "', level = '" . (int)$level . "'");

				$level++;
			}

			$this->db->query("REPLACE INTO `" . DB_PREFIX . "easy_blog_category_path` SET blog_category_id = '" . (int)$blog_category_id . "', `path_id` = '" . (int)$blog_category_id . "', level = '" . (int)$level . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'blog_category_id=" . (int)$blog_category_id . "'");

		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'blog_category_id=" . (int)$blog_category_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->cache->delete('blog_category');
	}

	public function deleteBlogCategory($blog_category_id) {

        $this->db->query("DELETE FROM " . DB_PREFIX . "easy_blog_category_path WHERE blog_category_id = '" . (int)$blog_category_id . "'");

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "easy_blog_category_path WHERE path_id = '" . (int)$blog_category_id . "'");

		foreach ($query->rows as $result) {
			$this->deleteBlogCategory($result['blog_category_id']);
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "easy_blog_category WHERE blog_category_id = '" . (int)$blog_category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "easy_blog_category_description WHERE blog_category_id = '" . (int)$blog_category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "easy_blog_article_to_blog_category WHERE blog_category_id = '" . (int)$blog_category_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'blog_category_id=" . (int)$blog_category_id . "'");

		$this->cache->delete('blog_category');

	}

	public function repairBlogCategories($parent_id = 0) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "easy_blog_category WHERE parent_id = '" . (int)$parent_id . "'");

		foreach ($query->rows as $blog_category) {
			// Delete the path below the current one
			$this->db->query("DELETE FROM `" . DB_PREFIX . "easy_blog_category_path` WHERE blog_category_id = '" . (int)$blog_category['blog_category_id'] . "'");

			// Fix for records with no paths
			$level = 0;

			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "easy_blog_category_path` WHERE blog_category_id = '" . (int)$parent_id . "' ORDER BY level ASC");

			foreach ($query->rows as $result) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "easy_blog_category_path` SET blog_category_id = '" . (int)$blog_category['blog_category_id'] . "', `path_id` = '" . (int)$result['path_id'] . "', level = '" . (int)$level . "'");

				$level++;
			}

			$this->db->query("REPLACE INTO `" . DB_PREFIX . "easy_blog_category_path` SET blog_category_id = '" . (int)$blog_category['blog_category_id'] . "', `path_id` = '" . (int)$blog_category['blog_category_id'] . "', level = '" . (int)$level . "'");

			$this->repairBlogCategories($blog_category['blog_category_id']);
		}
	}

	public function getBlogCategory($blog_category_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT GROUP_CONCAT(cd1.name ORDER BY level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') FROM " . DB_PREFIX . "easy_blog_category_path cp LEFT JOIN " . DB_PREFIX . "easy_blog_category_description cd1 ON (cp.path_id = cd1.blog_category_id AND cp.blog_category_id != cp.path_id) WHERE cp.blog_category_id = c.blog_category_id AND cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY cp.blog_category_id) AS blog_path, (SELECT DISTINCT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'blog_category_id=" . (int)$blog_category_id . "') AS keyword FROM " . DB_PREFIX . "easy_blog_category c LEFT JOIN " . DB_PREFIX . "easy_blog_category_description cd2 ON (c.blog_category_id = cd2.blog_category_id) WHERE c.blog_category_id = '" . (int)$blog_category_id . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getBlogCategories($data = array()) {
		$sql = "SELECT cp.blog_category_id AS blog_category_id, GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') AS name, c1.parent_id, c1.sort_order FROM " . DB_PREFIX . "easy_blog_category_path cp LEFT JOIN " . DB_PREFIX . "easy_blog_category c1 ON (cp.blog_category_id = c1.blog_category_id) LEFT JOIN " . DB_PREFIX . "easy_blog_category c2 ON (cp.path_id = c2.blog_category_id) LEFT JOIN " . DB_PREFIX . "easy_blog_category_description cd1 ON (cp.path_id = cd1.blog_category_id) LEFT JOIN " . DB_PREFIX . "easy_blog_category_description cd2 ON (cp.blog_category_id = cd2.blog_category_id) WHERE cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND cd2.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sql .= " GROUP BY cp.blog_category_id";

		$sort_data = array(
			'name',
			'sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY sort_order";
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

	public function getBlogCategoryDescriptions($blog_category_id) {
		$blog_category_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "easy_blog_category_description WHERE blog_category_id = '" . (int)$blog_category_id . "'");

		foreach ($query->rows as $result) {
			$blog_category_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'meta_title'       => $result['meta_title'],
				'meta_description' => $result['meta_description'],
				'meta_keyword'     => $result['meta_keyword'],
				'description'      => $result['description']
			);
		}

		return $blog_category_description_data;
	}


	public function getTotalBlogCategories() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "easy_blog_category");

		return $query->row['total'];
	}
}
