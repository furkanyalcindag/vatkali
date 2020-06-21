<?php

class applicationInfo
{

    public $developerId;
    public $name;
    public $description;
    public $accessType;
    public $appType;
    public $descDetail;
    public $successReturnUrl;
    public $failReturnUrl;


    function set_developerId($developerId)
    {
        $this->developerId = $developerId;
    }

    function get_developerId()
    {
        return $this->developerId;
    }

    function set_name($name)
    {
        $this->name = $name;
    }

    function get_name()
    {
        return $this->name;
    }

    function set_description($description)
    {
        $this->description = $description;
    }

    function get_description()
    {
        return $this->description;
    }

    function set_accessType($accessType)
    {
        $this->accessType = $accessType;
    }

    function get_accessType()
    {
        return $this->accessType;
    }


    function set_appType($appType)
    {
        $this->appType = $appType;
    }

    function get_appType()
    {
        return $this->appType;
    }


    function set_descDetail($descDetail)
    {
        $this->descDetail = $descDetail;
    }

    function get_descDetail()
    {
        return $this->descDetail;
    }

    function set_successReturnUrl($successReturnUrl)
    {
        $this->successReturnUrl = $successReturnUrl;
    }

    function get_successReturnUrl()
    {
        return $this->successReturnUrl;
    }


    function set_failReturnUrl($failReturnUrl)
    {
        $this->failReturnUrl = $failReturnUrl;
    }

    function get_failReturnUrl()
    {
        return $this->failReturnUrl;
    }
}


class ModelExtensionOxitGittigidiyorEntegrasyon extends Model
{


    public function getInfo()
    {


        $oxit_info = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "oxit_gittigidiyor");

        foreach ($query->rows as $result) {
            $oxit_info[] = $result;
        }

        return $oxit_info;
    }


    public function addInfo($data)
    {

        $this->db->query("DELETE FROM " . DB_PREFIX . "oxit_gittigidiyor ");

        $wsdl = "http://dev.gittigidiyor.com:8080/listingapi/ws/ApplicationService?wsdl";


        $username = $data['username'];
        $password = $data['password'];
        $client = new SoapClient($wsdl, array('login' => $username, 'password' => $password, 'authentication' => SOAP_AUTHENTICATION_BASIC));  // The trace param will show you errors stack

        // web service input params
        $request_param = array(
            "developerId" => $data['developerId'],
            "name" => "Oxit Gittigidiyor Entegrasyon",
            "description" => "E-ticaret yazılımı entegrasuonu",
            "accessType" => "I",
            "appType" => "W",
            "descDetail" => "",
            "successReturnUrl" => "",
            "failReturnUrl" => "",
            "lang" => "tr"

        );

        $message = '';


        try {
            $responce_param = $client->createApplication($request_param);

            if ($responce_param != null && $responce_param->ackCode == "success") {

                $this->db->query("INSERT INTO " . DB_PREFIX . "oxit_gittigidiyor SET apiKey = '" . $responce_param->application->apiKey . "
                ', secretKey = '" . $responce_param->application->secretKey . "',appName = '" . $responce_param->application->name . "',developerId = '" . $responce_param->developerId . "',username = '" . $username . "',password = '" . $password . "'");

                $message = 'success';
            } else {
                $message = $responce_param->result->message;
            }


            return $message;
            //$responce_param =  $client->call("webservice_methode_name", $request_param); // Alternative way to call soap method
        } catch (Exception $e) {
            echo "<h2>Exception Error!</h2>";
            echo $e->getMessage();
            return $e->getMessage();
        }
        /*$this->db->query("INSERT INTO " . DB_PREFIX . "oxit_gittigidiyor SET apiKey = '" . $data['apiKey'] . "
        ', secretKey = '" . $data['secretKey'] . "',appName = '" . $data['appName'] . "',developerId = '" . $data['developerId'] . "'"); */
    }


    public function addSettings($data)
    {
        $this->db->query("INSERT INTO " . DB_PREFIX . "oxit_gittigidiyor_product_settings SET cargoFirm = '" . $data['cargoFirm'] . "',cargoCity= '" . $data['cargoCity'] . "',cargoWhere='" . $data['cargoWhere'] . "',cargoWho='" . $data['cargoWho'] . "',cargoPay='" . $data['cargoPay'] . "',cargoTime='" . $data['cargoTime'] . "'");

        return "success";
    }

    public function addSpecAndCat($data)
    {
        $this->db->query("INSERT INTO " . DB_PREFIX . "oxit_gittigidiyor_pfeature SET productId = '" . $data['productId'] . "',name= '" . $this->db->escape($data['name']) . "',value='" . $this->db->escape($data['value']) . "'");

        return "success";
    }


    public function deleteSpecAndCat($data)
    {

        $this->db->query("DELETE FROM " . DB_PREFIX . "oxit_gittigidiyor_pfeature  WHERE productId = '" . $data . "'");
        return "success";


    }


    public function editSpecAndCat($data)
    {
        // $this->db->query("DELETE FROM " . DB_PREFIX . "oxit_gittigidiyor_pfeature  WHERE productId = '".$data['productId']."'");
        $this->db->query("INSERT INTO " . DB_PREFIX . "oxit_gittigidiyor_pfeature SET productId = '" . $data['productId'] . "',name= '" . $this->db->escape($data['name']) . "',value='" . $this->db->escape($data['value']) . "'");

        return "success";
    }


    public function getSpecAndCat($productId)
    {


        $oxit_info = array();
        $data = null;

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "oxit_gittigidiyor_pfeature WHERE productId = '" . $productId . "'");

        foreach ($query->rows as $result) {
            $oxit_info[] = $result;
            if ($result['name'] == 'gg_code') {
                $data['gg_code'] = $result['value'];
            }
        }
        $data['oxit_info'] = $oxit_info;

        return $data;
    }


    public function editSettings($data)
    {
        $this->db->query("DELETE FROM " . DB_PREFIX . "oxit_gittigidiyor_product_settings ");
        $this->db->query("INSERT INTO " . DB_PREFIX . "oxit_gittigidiyor_product_settings SET cargoFirm = '" . $data['cargoFirm'] . "',cargoCity= '" . $data['cargoCity'] . "',cargoWhere='" . $data['cargoWhere'] . "',cargoWho='" . $data['cargoWho'] . "',cargoPay='" . $data['cargoPay'] . "',cargoTime='" . $data['cargoTime'] . "'");

        return "success";
    }


    public function getSettings()
    {


        $oxit_info = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "oxit_gittigidiyor_product_settings");

        foreach ($query->rows as $result) {
            $oxit_info[] = $result;
        }

        return $oxit_info;
    }


    public function editInfo($data)
    {


        $message = $this->deleteApp();


        if ($message == 'success') {

            $this->db->query("DELETE FROM " . DB_PREFIX . "oxit_gittigidiyor ");

            $wsdl = "http://dev.gittigidiyor.com:8080/listingapi/ws/ApplicationService?wsdl";


            $username = $data['username'];
            $password = $data['password'];
            $client = new SoapClient($wsdl, array('login' => $username, 'password' => $password, 'authentication' => SOAP_AUTHENTICATION_BASIC));  // The trace param will show you errors stack

            // web service input params
            $request_param = array(
                "developerId" => $data['developerId'],
                "name" => "Oxit Gittigidiyor Entegrasyon",
                "description" => "E-ticaret yazılımı entegrasuonu",
                "accessType" => "I",
                "appType" => "W",
                "descDetail" => "",
                "successReturnUrl" => "",
                "failReturnUrl" => "",
                "lang" => "tr"

            );

            $message = '';


            try {
                $responce_param = $client->createApplication($request_param);

                if ($responce_param != null && $responce_param->ackCode == "success") {

                    $this->db->query("INSERT INTO " . DB_PREFIX . "oxit_gittigidiyor SET apiKey = '" . $responce_param->application->apiKey . "
                    ', secretKey = '" . $responce_param->application->secretKey . "',appName = '" . $responce_param->application->name . "',developerId = '" . $responce_param->developerId . "',username = '" . $username . "',password = '" . $password . "'");

                    $message = 'success';
                } else {
                    $message = $responce_param->result->message;
                }


                $deneme = $responce_param->application->apiKey;

                return $message;
                //$responce_param =  $client->call("webservice_methode_name", $request_param); // Alternative way to call soap method
            } catch (Exception $e) {
                echo "<h2>Exception Error!</h2>";
                $message = $e->getMessage();
                return $message;
            }

        } else {
            $message = 'error';

            return $message;
        }


    }


    public function deleteApp()
    {


        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "oxit_gittigidiyor ");

        $result = $query->rows[0];


        $wsdl = "http://dev.gittigidiyor.com:8080/listingapi/ws/ApplicationService?wsdl";


        $username = $result['username'];
        $password = $result['password'];
        $developerId = $result['developeId'];
        $apiKey = $result['apiKey'];
        $client = new SoapClient($wsdl, array('login' => $username, 'password' => $password, 'authentication' => SOAP_AUTHENTICATION_BASIC));  // The trace param will show you errors stack


        $request_param = array(
            "developerId" => $developerId,
            "apiKey" => $apiKey,
            "lang" => "tr"

        );

        $message = '';


        try {
            $responce_param = $client->deleteApplication($request_param);

            if ($responce_param != null && $responce_param->ackCode == "success") {


                $message = 'success';
            } else {
                $message = 'error';
            }


            return $message;
            //$responce_param =  $client->call("webservice_methode_name", $request_param); // Alternative way to call soap method
        } catch (Exception $e) {
            echo "<h2>Exception Error!</h2>";
            echo $e->getMessage();
            return $e->getMessage();
        }
    }


    public function getProductCombined($product_id)
    {
        $product_combined_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "combine WHERE product_id = '" . (int)$product_id . "'");

        foreach ($query->rows as $result) {
            $product_combined_data[] = $result['related_id'];
        }

        return $product_combined_data;
    }


    public function addProduct($data, $product_id)
    {
        $x = $this->db->query("INSERT INTO " . DB_PREFIX . "oxit_gittigidiyor_products SET productId = '" . (int)$product_id . "', categoryCode = '" . $data['gg_code'] . "',marketPrice='" . $data['gg_price'] . "'");
        var_dump($x);
    }


    public function updateProduct($data, $product_id)
    {
        $x = $this->db->query("UPDATE " . DB_PREFIX . "oxit_gittigidiyor_products SET productId = '" . (int)$product_id . "', categoryCode = '" . $data['gg_code'] . "',marketPrice='" . $data['gg_price'] . "' WHERE productId='" . $product_id . "' ");
        var_dump($x);
    }


    public function getGGProductInfo($product_id)
    {

        $gg_product_info = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "oxit_gittigidiyor_products where productId=" . $product_id);

        foreach ($query->rows as $result) {
            $gg_product_info[] = $result;
        }

        return $gg_product_info[0];

    }


    public function getGGCategory($productId){


        $oxit_info = array();

        $cat = null;

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "oxit_gittigidiyor_pfeature where productId= '" . $productId . "'");

        foreach ($query->rows as $result) {
            if($result['name']=='gg_code'){
                $cat = $result['value'];
            }


        }


        if( $cat==null){
            $cat = 'Seçiniz';
        }

        return $cat;



    }
}
