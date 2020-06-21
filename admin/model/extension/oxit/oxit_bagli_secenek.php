<?php

class ModelExtensionOxitBagliSecenek extends Model
{

    public function addOption($data){
        $this->db->query("INSERT INTO " . DB_PREFIX . "oxit_bagli_secenek      
         SET producyId = '" . (int)$data['productId'] . "
         ', optionId = '" . (int)$data['optionId'] . "
         ', isParent = '" . (int)$data['isParent'] . "
         ', parentOptionId = '" . (int)$data['parentOptionId'] . "
        
         ', quantity = '" . (int)$data['quantity'] . "
         ', image = '" . $this->db->escape($data['image']) . "
        
        
         '");

        $oxit_id = $this->db->getLastId();

        return $oxit_id;
    }










}
