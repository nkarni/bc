<?php

// Add product specifications and features fields

class ModelUpgrade1010 extends Model {
	public function upgrade() {
    // Check if upgrade is necessary
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "product_description' AND COLUMN_NAME = 'short_description'");

		if (!$query->num_rows) {
      // Add specifications and features columns 
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "product_description` ADD `short_description` TEXT AFTER `meta_keyword`");

      // Migrate data from specs_features table
      $query = $this->db->query("SELECT * FROM `product_descriptions_import`");
      $rows = $query->rows;
      foreach($query->rows as $product) {
        $query = $this->db->query("UPDATE `" . DB_PREFIX . "product_description` SET description = '" . $this->db->escape($product['post_content']) . "', short_description = '" . $this->db->escape($product['post_excerpt']) . "' WHERE name = '" . $this->db->escape($product['post_title']) . "'");
      }
		}
	}
}
