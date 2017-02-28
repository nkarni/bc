<?php
class ModelBlogBlogCategory extends Model {
	public function getBlogCategory($blog_category_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "easy_blog_category c LEFT JOIN " . DB_PREFIX . "easy_blog_category_description cd ON (c.blog_category_id = cd.blog_category_id) WHERE c.blog_category_id = '" . (int)$blog_category_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND c.status = '1'");

		return $query->row;
	}

	public function getBlogCategories($parent_id = 0) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "easy_blog_category c LEFT JOIN " . DB_PREFIX . "easy_blog_category_description cd ON (c.blog_category_id = cd.blog_category_id) WHERE c.parent_id = '" . (int)$parent_id . "' AND cd.language_id = '" . (int)$this->config->get('config_language_id') . "'  AND c.status = '1' ORDER BY c.sort_order, LCASE(cd.name)");

		return $query->rows;
	}

	public function getTotalBlogCategoriesByCategoryId($parent_id = 0) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "easy_blog_category WHERE parent_id = '" . (int)$parent_id . "' AND status = '1'");

		return $query->row['total'];
	}

    public function getParentBlogCategoriesByBlogCategoryId($id) {
        $cats = array();
        array_unshift($cats, $id);
        $query = $this->db->query("SELECT `parent_id` FROM `" . DB_PREFIX . "easy_blog_category` WHERE `blog_category_id` = ".$id);
        while ($query->row['parent_id']) { // если он есть
            array_unshift($cats, $query->row['parent_id']); // пишем его в начало массива
            $query = $this->db->query("SELECT `parent_id` FROM `" . DB_PREFIX . "easy_blog_category` WHERE `blog_category_id` = ".$query->row['parent_id']);
        }
        return $cats;
    }
}