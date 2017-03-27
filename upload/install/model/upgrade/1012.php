<?php

// Add product specifications and features fields

class ModelUpgrade1012 extends Model {
    public function upgrade() {
        // Check if upgrade is necessary
        $query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "wishlistitems' AND COLUMN_NAME = 'quantity'");

        if (!$query->num_rows) {
            // Add options  column
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "wishlistitems` ADD `quantity` TEXT AFTER `product_id`");

        }
    }
}

