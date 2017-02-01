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
        if (!isset($products[$product['post_title']])) {
          $products[$product['post_title']] = array();
        }
        $products[$product['post_title']][$product['meta_key']] = str_replace('\\r\\n', "\n", $product['meta_value']);
        $products[$product['post_title']][$product['meta_key']] = str_replace('\r\n', "\n", $product['meta_value']);
      }

      foreach($products as $name => $product) {
        $query = $this->db->query("UPDATE `" . DB_PREFIX . "product_description` SET specifications = '" . $this->db->escape($product['specifications']) . "', features = '" . $this->db->escape($product['features']) . "' WHERE name = '" . $this->db->escape($name) . "'");
      }
		}
	}
}
