<?php
class ModelAccountWishlists extends Model {

    public function addWishlists($data) {

        $customer = $this->customer->getId();

        $this->db->query("INSERT INTO " . DB_PREFIX . "wishlist SET wishlist_name = '" . $data['wishlist_name'] . "', customer = '" . (int)$customer . "', visiblity = '" . $data['visiblity'] . "', status = '" . $data['status'] . "', created_by = '" . (int)$customer . "', created_on = NOW(), edited_by = '" . (int)$customer . "', edited_on = NOW() ");

        $wishlist_id = $this->db->getLastId();

        return $wishlist_id;
    }

    public function editWishlists($data) {

        $customer = $this->customer->getId();

        $this->db->query("UPDATE " . DB_PREFIX . "wishlist SET wishlist_name = '" . $data['wishlist_name'] . "', customer = '" . (int)$customer . "', visiblity = '" . $data['visiblity'] . "', status = '" . $data['status'] . "', edited_by = '" . (int)$customer . "', edited_on = NOW() WHERE wishlist_id=".$data['wishlist_id']);

        return $data['wishlist_id'];
    }

    public function addWishlistitem($product_id,$wishlist_id, $options, $quantity) {

        $this->db->query("INSERT INTO " . DB_PREFIX . "wishlistitems SET wishlist_id = '" . $wishlist_id . "', quantity = '" . $quantity . "', options = '" . $options . "', product_id = '" . (int)$product_id . "', added_on = NOW()");

    }
    
    
    public function getWishlistitem($wishlist_id,$product_id) {

        $customer = $this->customer->getId();

        $query=$this->db->query("SELECT * FROM " . DB_PREFIX . "wishlistitems WHERE wishlist_id = '" . (int)$wishlist_id . "' AND product_id = '" . (int)$product_id . "'");
        
        return $query->row;

    }

    public function updateWishlistItemQty($wishlist_item_id, $qty) {

        $this->db->query("UPDATE " . DB_PREFIX . "wishlistitems SET quantity='". (int)$qty ."' WHERE wishlist_item_id = '" . (int)$wishlist_item_id . "'");

        return $wishlist_item_id;
    }


    public function editWishlistitem($data) {

        $customer = $this->customer->getId();

        $this->db->query("UPDATE " . DB_PREFIX . "wishlistitems SET  purchased_by='". (int)$data['purchased_by'] ."', purchased_on = NOW() WHERE wishlist_id = '" . (int)$data['wishlist_id'] . "' AND product_id = '" . (int)$data['product_id'] . "'");

        return $data['wishlist_id'];
    }


    public function getWishlistId($wishlistname,$customer=0) {
        $return = 0;
        if($customer != 0){
            $query = $this->db->query("SELECT wishlist_id FROM `" . DB_PREFIX . "wishlist` WHERE wishlist_name LIKE '".$wishlistname."' AND customer=".$customer);
            if($query->row){
                $return=$query->row['wishlist_id'];
            }
        }
            return $return;

    }

    public function isWishlistOwnerByItem($wishlist_item_id) {

        $query = $this->db->query("SELECT wishlist_id FROM `" . DB_PREFIX . "wishlistitems` WHERE wishlist_item_id = '".$wishlist_item_id. "'");

        $wishlist_id = $query->row['wishlist_id'];

        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "wishlist` WHERE wishlist_id=".$wishlist_id);

        $customer = $this->customer->getId();

        return ($query->row['created_by'] == $customer);
    }

    public function getWishlist($wishlist_id=0) {

            $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "wishlist` r WHERE wishlist_id=".$wishlist_id);

            return $query->row;
    }

	public function getWishlists($data=array()) {

        $wherecondition = " 1 ";

        $customer = $this->customer->getId();

       // if(isset($data['customer']) && is_numeric($customer)){

            $wherecondition .= " AND r.customer=".$customer;

      //  }
        if($customer){

            $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "wishlist` r WHERE ".$wherecondition);

            return $query->rows;
        }
	}

    public function getWishlistscount($data=array()) {

        $wherecondition = " 1 ";

        $customer = $this->customer->getId();

        $wherecondition .= " AND r.customer=".$customer;
  
        if($customer){

            $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "wishlist` r WHERE ".$wherecondition);

            return count($query->rows);
        }
        return 0;
    }

    public function getWishlistItems($wishlist_id=0,$list="Productids") {
        $returnarray = array();
        if($wishlist_id != 0)
        {
            if($list == "All"){
                $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "wishlistitems` WHERE wishlist_id=".$wishlist_id);
                if($query->rows){
                    $returnarray=$query->rows;
                }
            }
            else{
                $query = $this->db->query("SELECT product_id FROM `" . DB_PREFIX . "wishlistitems` WHERE wishlist_id=".$wishlist_id);

                foreach($query->rows as $result){
                    $returnarray[]=$result['product_id'];
                }
            }
        }
        return $returnarray;
    }

    public function getCurrentWishlistItemsCount($wishlist_id=0) {
        $returncount = 0;
        if($wishlist_id != 0)
        {
            $query = $this->db->query("SELECT COUNT(*) AS totalcount FROM `" . DB_PREFIX . "wishlistitems` WHERE wishlist_id=".$wishlist_id);
            if($query->row){
                $returncount = $query->row['totalcount'];
            }
        }
        return $returncount;
    }

    public function getCurrentWishlistPurchasedItemsCount($wishlist_id=0,$product_id=0,$customer=0) {

        $returncount = 0;

        if($wishlist_id != 0)
        {			//echo "SELECT COUNT(*) AS totalcount FROM `" . DB_PREFIX . "order` oo INNER JOIN `" . DB_PREFIX . "order_product` oop ON (oop.order_id = oo.order_id) WHERE oop.product_id IN (SELECT product_id FROM `" . DB_PREFIX . "wishlistitems` WHERE wishlist_id=".$wishlist_id.") AND oo.customer_id=".$customer; 
			/*
			 SELECT COUNT(*) AS total FROM oc_wishlistitems wli 
LEFT JOIN oc_wishlist wl ON (wl.wishlist_id = wli.wishlist_id)
LEFT JOIN oc_order_product orp on (orp.product_id = wli.product_id) 
LEFT JOIN oc_order o ON (o.customer_id = wl.customer AND orp.product_id = wli.product_id)
WHERE wli.wishlist_id=1 AND ((o.customer_id=1 AND wli.purchased_by =0 ) OR (wli.purchased_by<>0))
-

			 SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "wishlistitems` wli 
LEFT JOIN `" . DB_PREFIX . "wishlist` wl ON (wl.wishlist_id = wli.wishlist_id)
LEFT JOIN `" . DB_PREFIX . "order_product` orp on (orp.product_id = wli.product_id) 
LEFT JOIN `" . DB_PREFIX . "order` o ON (o.customer_id = wl.customer AND orp.product_id = wli.product_id)
WHERE wli.wishlist_id=".$wishlist_id." AND ((o.customer_id=".$customer." AND wli.purchased_by =0 ) OR (wli.purchased_by<>0))

*/
            if($product_id == 0){
//                $query = $this->db->query("SELECT COUNT(*) AS totalcount FROM `" . DB_PREFIX . "order` oo INNER JOIN `" . DB_PREFIX . "order_product` oop ON (oop.order_id = oo.order_id) WHERE oop.product_id IN (SELECT product_id FROM `" . DB_PREFIX . "wishlistitems` WHERE wishlist_id=".$wishlist_id.") AND oo.customer_id=".$customer);
                $query = $this->db->query(" SELECT COUNT(*) AS totalcount FROM `" . DB_PREFIX . "wishlistitems` wli 
LEFT JOIN `" . DB_PREFIX . "wishlist` wl ON (wl.wishlist_id = wli.wishlist_id)
LEFT JOIN `" . DB_PREFIX . "order_product` orp on (orp.product_id = wli.product_id) 
LEFT JOIN `" . DB_PREFIX . "order` o ON (o.customer_id = wl.customer AND orp.product_id = wli.product_id)
WHERE wli.wishlist_id=".$wishlist_id." AND ((o.customer_id=".$customer." AND wli.purchased_by =0 ) OR (wli.purchased_by<>0))");
}
            else{
                $query = $this->db->query("SELECT COUNT(*) AS totalcount FROM `" . DB_PREFIX . "order` oo INNER JOIN `" . DB_PREFIX . "order_product` oop ON (oop.order_id = oo.order_id) WHERE oop.product_id=".$product_id." AND oo.customer_id=".$customer);
			}

            if($query->row){
                $returncount = $query->row['totalcount'];
            }
        }
        return $returncount;
    }

    public function isproductpurchased($wishlist_id=0,$product_id=0,$customer=0) {

        $returncount = 0;

        if($wishlist_id != 0 && $product_id != 0)
        {

                $query = $this->db->query("SELECT purchased_by,purchased_on FROM `" . DB_PREFIX . "wishlistitems` WHERE wishlist_id=".$wishlist_id." AND product_id=".$product_id." AND purchased_by != 0");
                
                if($query->row){
                    $returncount = array();
					$returncount['purchased_by'] = $query->row['purchased_by'];
                    $returncount['purchased_on'] = $query->row['purchased_on'];
				}
				else{
					$query1 = $this->db->query("SELECT COUNT(*) AS totalcount FROM `" . DB_PREFIX . "order` oo INNER JOIN `" . DB_PREFIX . "order_product` oop ON (oop.order_id = oo.order_id) WHERE oop.product_id=".$product_id." AND oo.customer_id=".$customer);
                    if($query1->rows){
                        $returncount = array();
						//$returncount = $customer;
                        $returncount['purchased_by'] = $customer;
                        $returncount['purchased_on'] = '';
					}	
				}
        }
        return $returncount;
    }

  

    public function getWishlistName($wishlist_id=0) {

        $wishlist_name = "";

        if($wishlist_id != 0){

            $query = $this->db->query("SELECT wishlist_name FROM `" . DB_PREFIX . "wishlist` WHERE wishlist_id = ".$wishlist_id);

            if($query->row)
                $wishlist_name = $query->row['wishlist_name'];
        }

        return $wishlist_name;
    }

    public function removeWishlist($wishlist_id=0) {
        if($wishlist_id != 0){
            $this->db->query("DELETE FROM `" . DB_PREFIX . "wishlistitems` WHERE wishlist_id = ".$wishlist_id);
            $this->db->query("DELETE FROM `" . DB_PREFIX . "wishlist` WHERE wishlist_id = ".$wishlist_id);
        }
    }

    public function removeWishlistitem($wishlist_id=0,$product_id=0) {
        if($wishlist_id != 0 && $product_id !=0){
            $this->db->query("DELETE FROM `" . DB_PREFIX . "wishlistitems` WHERE wishlist_id = ".$wishlist_id." AND product_id=".$product_id);
        }
    }
    
	public function getCustomerName($customer_id=0) {
		$fullname = "";
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");
		if($query->row){
			$fullname = $query->row['firstname']." ".$query->row['lastname'];
		}
		return $fullname;
	}

}
