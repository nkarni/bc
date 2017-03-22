<?php

// Add product specifications and features fields

class ModelUpgrade1011 extends Model {
    public function upgrade() {
        // Check if upgrade is necessary
        $query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . "wishlistitems' AND COLUMN_NAME = 'options'");

        if (!$query->num_rows) {
            // Add options  column
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "wishlistitems` ADD `options` TEXT AFTER `product_id`");

        }
    }
}

