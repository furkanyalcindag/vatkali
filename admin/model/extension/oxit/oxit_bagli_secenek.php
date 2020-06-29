<?php

class ModelExtensionOxitOxitBagliSecenek extends Model
{

    public function addRelatedOption($data,$product_id){

        $requred =1;
        $subtract =1;
        $prefix ='+';
        $points =0;
        $weight =0;
        $value ='';
        $this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$data['parent_option_id']. "', value = '" . $this->db->escape($value) . "', required = '" . (int)$requred . "'");

        $product_option_id = $this->db->getLastId();
        $this->load->model('catalog/option');


        for($i=0;$i<count($data['parent']);$i++){



            $this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET 
             product_option_id = '" . (int)$product_option_id . "',
             product_id = '" . (int)$product_id . "', 
             option_id = '" . (int)$data['parent_option_id'] . "', 
             option_value_id = '" . (int)$data['parent'][$i] . "', 
             quantity = '" . (int)$data['stock'][$i] . "', 
             subtract = '" . (int)$subtract . "', 
             price = '" . (float)$$data['price_differ'][$i] . "', 
             price_prefix = '" . $this->db->escape($prefix) . "', 
             points = '" . (int)$points . "', 
             points_prefix = '" . $this->db->escape($prefix) . "', 
             weight = '" . (float)$weight . "', 
             weight_prefix = '" . $this->db->escape($prefix) . "'");



        }






        return "fdfdfşlşkflşd";
/*
        $this->db->query("INSERT INTO " . DB_PREFIX . "oxit_bagli_secenek      
         SET produtId = '" . (int)$data['productId'] . "
         ', optionId = '" . (int)$data['optionId'] . "
          ', optionValueId = '" . (int)$data['optionValueId'] . "
        
         ', productOptionId = '" . (int)$data['parentOptionId'] . "
        
         ', quantity = '" . (int)$data['quantity'] . "
         
        
        
         '");


        $oxit_id = $this->db->getLastId();

        return $oxit_id; */
    }










}
