<?php 
class ModelExtensionOxitCombine extends Model {
    
    
    public function addCombine($data,$product_id) {
        
        
        	if (isset($data['product_combined'])) {
			foreach ($data['product_combined'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "combine WHERE product_id = '" . (int)$product_id . "' AND related_id = '" . (int)$related_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "combine SET product_id = '" . (int)$product_id . "', related_id = '" . (int)$related_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "combine WHERE product_id = '" . (int)$related_id . "' AND related_id = '" . (int)$product_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "combine SET product_id = '" . (int)$related_id . "', related_id = '" . (int)$product_id . "'");
			}
		}
        
        
        
        
        
        
        
    }
    
    
     public function beforeEditCombine($product_id) {
         
        $this->db->query("DELETE FROM " . DB_PREFIX . "combine WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "combine WHERE related_id = '" . (int)$product_id . "'");
         
         
     }
    
    
    public function getProductCombined($product_id) {
		$product_combined_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "combine WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_combined_data[] = $result['related_id'];
		}

		return $product_combined_data;
	}
    
 
 
 
 
 
 
 
 
 
 
 
 
    
    
}