<?php

// Add product specifications and features fields

class ModelUpgrade1009 extends Model {
	public function upgrade() {
    // Check if upgrade is necessary
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "product_description' AND COLUMN_NAME = 'specifications'");

		if (!$query->num_rows) {
      // Add specifications and features columns 
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "product_description` ADD `specifications` TEXT AFTER `meta_keyword`");
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "product_description` ADD `features` TEXT AFTER `specifications`");

      // Migrate data from specs_features table
      $query = $this->db->query("SELECT * FROM `specs_features`");
      $rows = $query->rows;
      $products = array();
      foreach($query->rows as $product) {
        if (!isset($products[$product['post_name']])) {
          $products[$product['post_name']] = array();
        }
        $products[$product['post_name']][$product['meta_key']] = str_replace('\r\n', "\n", $product['meta_value']);
      }

      foreach($products as $slug => $product) {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "url_alias` WHERE keyword = '$slug' AND `query` LIKE 'product_id%'");
        if ($query->row) {
          $product_id = $query->row['query'];
          $product_id = substr($product_id, strpos($product_id, "=") + 1);

          $query = $this->db->query("UPDATE `" . DB_PREFIX . "product_description` SET specifications = '" . $this->db->escape($product['specifications']) . "', features = '" . $this->db->escape($product['features']) . "' WHERE product_id = " . (int)$product_id);
        }
      }
		}
	}
}
