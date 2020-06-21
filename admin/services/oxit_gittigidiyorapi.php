<?php


class OxitGittigidiyorService
{

    public function generateSign($data)
    {

        list($usec, $sec) = explode(" ", microtime());
        $time = round(((float)$usec + (float)$sec) * 100) . '0';
        $milliseconds = intval(microtime(true) /1000);

        $concat = trim($data['apiKey']) . trim($data['secretKey']) . trim( $time);
        $sign['concat'] = $concat;
        $sign['sign'] = md5($concat);
        $sign['time'] = $time;

        return $sign;
    }

    public function getCargoCompany($data)
    {

        $wsdl = "https://dev.gittigidiyor.com:8443/listingapi/ws/IndividualCargoService?wsdl";
        $username = $data['username'];
        $password = $data['password'];
        $client = new SoapClient($wsdl, array('trace' => 1, 'login' => $username, 'password' => $password, 'authentication' => SOAP_AUTHENTICATION_BASIC));  // The trace param will show you errors stack
        $sign = $this->generateSign($data);
        $request_param = array(
            "apiKey" => $data['apiKey'],
            "sign" => $sign['sign'],
            "time" => $sign['time'],
            "lang" => "tr"
        );


        $message = null;
        /*<categories>
        <category>
           <categoryCode>a</categoryCode>
           <categoryPerma>/antika-sanat</categoryPerma> */
        try {
            $responce_param = $client->getCargoCompany($request_param);
            //  var_dump( $client->__getFunctions());
            if ($responce_param != null && $responce_param->ackCode == "success") {
                $responce['message'] = 'success';
                $responce['count'] = $responce_param->categoryCount;
                $responce['categories'] = $responce_param->categories;
                return $responce;
            } else {
                $responce['message'] = $responce_param->result->message;
                $responce['categories'] = null;
                return $responce;
            }
            return $message;
            //$responce_param =  $client->call("webservice_methode_name", $request_param); // Alternative way to call soap method
        } catch (Exception $e) {
            echo "<h2>Exception Error!</h2>";
            echo $e->getMessage();
            $responce['message'] = $e->getMessage();
            $responce['categories'] = null;
            return $responce;
        }


    }

    public function getSubCategory($data, $categoryCode)
    {
        $wsdl = "http://dev.gittigidiyor.com:8080/listingapi/ws/CategoryService?wsdl";
        $username = $data['username'];
        $password = $data['password'];
        $client = new SoapClient($wsdl, array('trace' => 1, 'login' => $username, 'password' => $password, 'authentication' => SOAP_AUTHENTICATION_BASIC));  // The trace param will show you errors stack


        // web service input params
        $request_param = array(
            "categoryCode" => $categoryCode,

            "withSpecs" => false,
            "withDeepest" => True,
            "withCatalog" => false,
            "lang" => "tr"
        );

        $message = null;

        try {
            $responce_param = $client->__soapCall('getSubCategories', $request_param);
            //  var_dump( $client->__getFunctions());
            if ($responce_param != null && $responce_param->ackCode == "success") {
                if ($responce_param->categoryCount == 0) {

                    return true;


                } else {
                    return false;
                }

            } else {
                $responce['message'] = $responce_param->result->message;
                $responce['categories'] = null;
                return false;
            }
            return $message;
            //$responce_param =  $client->call("webservice_methode_name", $request_param); // Alternative way to call soap method
        } catch (Exception $e) {
            echo "<h2>Exception Error!</h2>";
            echo $e->getMessage();
            $responce['message'] = $e->getMessage();
            $responce['categories'] = null;
            return false;
        }

    }

    public function getCategoryDeepest($data, $startOffset)
    {
        $wsdl = "http://dev.gittigidiyor.com:8080/listingapi/ws/CategoryService?wsdl";
        $username = $data['username'];
        $password = $data['password'];
        $client = new SoapClient($wsdl, array('trace' => 1, 'login' => $username, 'password' => $password, 'authentication' => SOAP_AUTHENTICATION_BASIC));  // The trace param will show you errors stack


        // web service input params
        $request_param = array(
            "startOffset" => $startOffset * 100,

            "rowCount" => 100,

            "withSpecs" => false,
            "lang" => "tr"
        );

        $message = null;

        try {
            $responce_param = $client->__soapCall('getDeepestCategories', $request_param);
            //  var_dump( $client->__getFunctions());
            if ($responce_param != null && $responce_param->ackCode == "success") {
                $responce['message'] = 'success';
                $responce['count'] = $responce_param->categoryCount;
                $responce['categories'] = $responce_param->categories;
                return $responce;
            } else {
                $responce['message'] = $responce_param->result->message;
                $responce['categories'] = null;
                return $responce;
            }
            return $message;
            //$responce_param =  $client->call("webservice_methode_name", $request_param); // Alternative way to call soap method
        } catch (Exception $e) {
            echo "<h2>Exception Error!</h2>";
            echo $e->getMessage();
            $responce['message'] = $e->getMessage();
            $responce['categories'] = null;
            return $responce;
        }

    }

    public function getCategoryList($data)
    {
        $wsdl = "http://dev.gittigidiyor.com:8080/listingapi/ws/CategoryService?wsdl";
        $username = $data['username'];
        $password = $data['password'];
        $client = new SoapClient($wsdl, array('trace' => 1, 'login' => $username, 'password' => $password, 'authentication' => SOAP_AUTHENTICATION_BASIC));  // The trace param will show you errors stack


        // web service input params
        $request_param = array(

            "lang" => "tr"

        );

        $message = null;

        try {
            $responce_param = $client->getCategoryMetaData("tr");
            //  var_dump( $client->__getFunctions());
            if ($responce_param != null && $responce_param->ackCode == "success") {
                $responce['message'] = 'success';
                $responce['count'] = $responce_param->categoryCount;
                $responce['categories'] = $responce_param->categories;

                return $responce;
            } else {
                $responce['message'] = $responce_param->result->message;
                $responce['categories'] = null;
                return $responce;
            }
            return $message;
            //$responce_param =  $client->call("webservice_methode_name", $request_param); // Alternative way to call soap method
        } catch (Exception $e) {
            echo "<h2>Exception Error!</h2>";
            echo $e->getMessage();
            $responce['message'] = $e->getMessage();
            $responce['categories'] = null;
            return $responce;
        }

    }


    public function getSpecs($data, $categoryCode)
    {

        $wsdl = "http://dev.gittigidiyor.com:8080/listingapi/ws/CategoryService?wsdl";
        $username = $data['username'];
        $password = $data['password'];
        $client = new SoapClient($wsdl, array('trace' => 1, 'login' => $username, 'password' => $password, 'authentication' => SOAP_AUTHENTICATION_BASIC));  // The trace param will show you errors stack


        // web service input params
        $request_param = array(


            "categoryCode" => $categoryCode,
            "lang" => "tr",

        );

        $message = null;

        try {
            $responce_param = $client->__soapCall('getCategorySpecs', $request_param);

            //  var_dump( $client->__getFunctions());
            if ($responce_param != null && $responce_param->ackCode == "success") {
                $responce['message'] = 'success';

                $responce['specs'] = $responce_param;
                return $responce;
            } else {
                $responce['message'] = $responce_param->result->message;
                $responce['specs'] = null;
                return $responce;
            }
            return $message;
            //$responce_param =  $client->call("webservice_methode_name", $request_param); // Alternative way to call soap method
        } catch (Exception $e) {
            echo "<h2>Exception Error!</h2>";
            echo $e->getMessage();
            $responce['message'] = $e->getMessage();
            $responce['specs'] = null;
            return $responce;
        }


    }


    public function insertProduct($data, $gg_info, $results, $base64)
    {


        //descriptioon dil kontrol et.
        $wsdl = "https://dev.gittigidiyor.com:8443/listingapi/ws/IndividualProductService?wsdl";
        $username = $data['username'];
        $password = $data['password'];

        $sign = $this->generateSign($data);
        //var_dump($data);
        //var_dump($sign);
        //var_dump($base64);


        //var_dump($results);

        $request_param = array(
            "apiKey" =>trim($data['apiKey']),
            "sign" => $sign['sign'],
            "time" => $sign['time'],
            "itemId" => "1",

            //"categoryCode" => $gg_info['categoryCode'],
            "product" => array(
                "categoryCode" => "gdk",
                "storeCategoryId" => "0",
                "title" => $results['name'],

                'specs' => array(
                    'spec' => array(
                        array("required" => "true","type"=>"Combo", "value" => "Kodak", "name" => "Marka"),
                        array("required" => "true", "type"=>"Combo","value" => "Yeni", "name" => "Durum"),
                        array("required" => "true", "type"=>"Combo","value" => "10.0 - 11.9 MP", "name" => "Çözünürlük (Megapiksel)"),
                        array("required" => "true", "type"=>"Combo","value" => "Yok", "name" => "Garanti"),
                        array("required" => "true", "type"=>"Combo","value" => "Yok", "name" => "Garanti Süresi"),
                        array("required" => "true", "type"=>"Combo","value" => "Türkiye", "name" => "Garanti Geçerlilik Yeri"),
                    )),

                /*<spec name="Garanti Süresi" value="Yok" type="Combo" required="true" />
                 *
                 *  <spec name="Durum" value="Yeni" type="Combo" required="true" />
         <spec name="Çözünürlük (Megapiksel)" value="10.0 - 11.9 MP" type="Combo" required="true" />
         <spec name="Marka" value="Kodak" type="Combo" required="true" />
          <spec name="Garanti" value="Yok" type="Combo" required="true" />
                 */

                "photos" => array(
                    "photo" => array(
                        array("photoId" => "0", "url" => "https://mcdn01.gittigidiyor.net/53642/536423161_0.jpg"),
                        array("photoId" => "1", "url" => "https://mcdn01.gittigidiyor.net/53642/536423161_0.jpg"),

                    )
                ),
                "pageTemplate" => "2",
                "description" => $results['description'],
                "startDate" => null,

                "catalogId" => "0",
                "newCatalogId" => null,
                "catalogDetail" => "0",
                "catalogFilter" => null,
                "format" => "S",
                "startPrice" => null,
                "buyNowPrice" => "240",
                "netEarning" => null,
                "listingDays" => "30",
                "productCount" => $results['quantity'],
                "cargoDetail" => array(
                    "city" => "34",

                    "shippingPayment" => "B",
                    "shippingWhere" => "country",
                    "cargoCompanyDetails" => array(
                        "cargoCompanyDetail" => array(
                            "name" => "aras",

                            "cityPrice"=>"3.00",
                            "countryPrice"=>"5.00",
                        )
                    ),
                    "shippingTime" => array(
                        "days" => "2-3days",
                        "beforeTime"=>"14:15",
                    )

                ),
                "affiliateOption" => false,
                "boldOption" => false,
                "catalogOption" => false,
                "vitrineOption" => false,
            ),
            "forceToSpecEntry" => false,
            "nextDateOption" => false,
            "lang" => "tr",

        );


        $client = new SoapClient($wsdl, array('trace' => 1, 'login' => $username, 'password' => $password, 'authentication' => SOAP_AUTHENTICATION_BASIC));  // The trace param will show you errors stack


        $message = null;
        /*<categories>
        <category>
           <categoryCode>a</categoryCode>
           <categoryPerma>/antika-sanat</categoryPerma> */
        try {

            $responce_param = $client->__soapCall('insertProductWithNewCargoDetail', $request_param);
            //var_dump( $client->__getFunctions());
            //var_dump($client->__getLastRequest());
            if ($responce_param != null && $responce_param->ackCode == "success") {
                $responce['message'] = 'success';
                $responce['message1'] = 'success1';
                //var_dump(  $client->__getLastResponse());
                return $responce;
            } else {
                var_dump($responce_param);
               $responce['message1'] = $client->__getLastResponse();
                //$responce['message1'] = 'success2';
                var_dump( $responce_param);

                return $responce;
            }
            return $message;
            //$responce_param =  $client->call("webservice_methode_name", $request_param); // Alternative way to call soap method
        } catch (Exception $e) {
            echo "<h2>Exception Error!</h2>";
            $responce = null;
            echo $e->getMessage();
            /*$responce['message'] = $e->getMessage();
            $responce['message1'] = $e;*/
            //var_dump($e);

            return $responce;
        }


    }
}




