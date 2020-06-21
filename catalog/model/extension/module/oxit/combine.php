<?php 
class ModelExtensionModuleOxitCombine extends Model {
    
    
    
    
    
  
    
    
    public function getProductCombined($product_id) {
		$product_combined_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "combine WHERE product_id = '" . (int)$product_id . "'");
        $this->load->model('catalog/product');
		foreach ($query->rows as $result) {
			$product_combined_data[$result['related_id']] = $this->model_catalog_product->getProduct($result['related_id']);
        }
      

		return $product_combined_data;
	}
    
 
 
 
 
 
 
 
 
 
 
 
 
    
    
}