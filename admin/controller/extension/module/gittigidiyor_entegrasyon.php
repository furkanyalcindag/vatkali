<?php
require_once(DIR_SERVICE . 'oxit_gittigidiyorapi.php');
class ControllerExtensionModuleGittigidiyorEntegrasyon extends Controller
{
    private $error = array();

    public function index()
    {



        $this->load->language('extension/module/gittigidiyor_entegrasyon');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('setting/module');
        $this->load->model('extension/oxit/gittigidiyor_entegrasyon');

        $x = $this->model_setting_module->getModuleByCode('oxit_gittigidiyor');
        $exist_array = $this->model_extension_oxit_gittigidiyor_entegrasyon->getInfo();


        if (count($exist_array) > 0) {

            for ($i = 0; $i < count($exist_array); $i++) {

                $data['username'] = $exist_array[$i]['username'];
                $data['password'] = $exist_array[$i]['password'];
                $data['developerId'] = $exist_array[$i]['developerId'];
            }
        }


        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {



                

            if (count($exist_array) > 0) {

                $message = $this->model_extension_oxit_gittigidiyor_entegrasyon->editInfo($this->request->post);

                if ($message == 'success') {

                    $data['success_message'] = "Gittigidiyor konfigurasyonunuz başarıyla gerçekleşmiştir";
                } else {

                    $data['error_message'] = $message;
                }
            } else {


                $message = $this->model_extension_oxit_gittigidiyor_entegrasyon->addInfo($this->request->post);

                if ($message == 'success') {

                    $data['success_message'] = "Gittigidiyor konfigurasyonunuz başarıyla gerçekleşmiştir";
                } else {

                    $data['error_message'] =$message;
                }
            }


            $this->model_setting_module->editModule($this->request->get['module_id'], $this->request->post);

            $xx = $this->model_extension_oxit_gittigidiyor_entegrasyon->addInfo($this->request->post);

            $data['response'] = $xx;

            //$this->session->data['success'] = $this->language->get('text_success');
            $this->session->data['success'] = $data['success_message'];
            $this->session->data['success'] = $data['error_message'];
            $this->session->data['success'] = $this->request->post['username'];

            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
        }
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }
        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = '';
        }
        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
        );
        if (!isset($this->request->get['module_id'])) {
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('extension/module/gittigidiyor_entegrasyon', 'user_token=' . $this->session->data['user_token'], true)
            );
        } else {
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('extension/module/gittigidiyor_entegrasyon', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true)
            );
        }
        if (!isset($this->request->get['module_id'])) {
            $data['action'] = $this->url->link('extension/module/gittigidiyor_entegrasyon', 'user_token=' . $this->session->data['user_token'], true);
        } else {
            $data['action'] = $this->url->link('extension/module/gittigidiyor_entegrasyon', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true);
        }
        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
        if (isset($x) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $module_info = $this->model_setting_module->getModuleByCode('oxit_gittigidiyor');
        }
        if (isset($this->request->post['name'])) {
            $data['name'] = $this->request->post['name'];
        } elseif (!empty($module_info)) {
            $data['name'] = $module_info['name'];
            $data['module_description'] = $module_info['module_description'];
        } else {
            $data['name'] = '';
        }
        $this->load->model('localisation/language');
        $data['languages'] = $this->model_localisation_language->getLanguages();
        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($module_info)) {
            $data['status'] = $module_info['status'];
        } else {
            $data['status'] = '';
        }
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $data['x'] = $x;
        $this->response->setOutput($this->load->view('extension/module/gittigidiyor_entegrasyon', $data));
    }
    protected function validate()
    {


        if (!$this->user->hasPermission('modify', 'extension/module/gittigidiyor_entegrasyon')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['developerId']) < 3) || (utf8_strlen($this->request->post['developerId']) > 255)) {
            $this->error['developerId'] = $this->language->get('error_name');
        }

        return !$this->error;
    }
    public function install()
    {

        $initial2 =    [
            'name' => 'OXIT Gittigidiyor API Entegrasyon',


            'module_description' => [
                '1' => ['title' => 'OXIT Gittigidiyor API Entegrasyon']
            ],

            'status' => 1 /* Enabled by default*/
        ];


        $this->load->model('setting/setting');
        $this->model_setting_setting->editSetting('module_gittigidiyor_entegrasyon', ['module_gittigidiyor_entegrasyon_status' => 0]);


        $this->load->model('setting/module');
        $this->model_setting_module->addModule('gittigidiyor_entegrasyon', $initial2);



        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "oxit_gittigidiyor` (
		  `oxit_id` int(11) NOT NULL AUTO_INCREMENT,
		  `apiKey` varchar(255) NOT NULL,
		  `secretKey` varchar(255) NOT NULL,
          `appName` varchar(255) NOT NULL,
          `developerId` varchar(255) NOT NULL,
          `username` varchar(255) NOT NULL,
          `password` varchar(255) NOT NULL,
		  PRIMARY KEY (`oxit_id`)
		)");




        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "oxit_gittigidiyor_product_settings` (
		  `oxit_id` int(11) NOT NULL AUTO_INCREMENT,
		  `cargoFirm` varchar(255) NOT NULL,
		  `cargoCity` varchar(255) NOT NULL,
          `cargoWhere` varchar(255) NOT NULL,
          `cargoPay` varchar(255) NOT NULL,
          `cargoTime` varchar(255) NOT NULL,
        `cargoWho` varchar(255) NOT NULL,
		  PRIMARY KEY (`oxit_id`)
		)");



        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "oxit_gittigidiyor_products` (
		  `oxit_id` int(11) NOT NULL AUTO_INCREMENT,
		  `productId`  int(11) NOT NULL,	  
		  `categoryCode` varchar(255) NOT NULL,
		  `marketPrice` varchar(255) NOT NULL,
		  PRIMARY KEY (`oxit_id`)
		)");



        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "oxit_gittigidiyor_pfeature` (
		  `oxit_id` int(11) NOT NULL AUTO_INCREMENT,
		  `productId`  int(11) NOT NULL,	  
		  `name` varchar(255) NOT NULL,
		  `value` varchar(255) NOT NULL,
		  PRIMARY KEY (`oxit_id`)
		)");



    }
    public function uninstall()
    {

        //md5($this->apiKey.$this->secretKey.$this->time);

        $this->load->model('setting/setting');
        $this->model_setting_setting->deleteSetting('module_gittigidiyor_entegrasyon');


        $this->load->model('setting/module');
        $this->model_setting_module->deleteModulesByCode('oxit_gittigidiyor');



        $this->db->query("Drop TABLE  `" . DB_PREFIX . "oxit_gittigidiyor`");
        $this->db->query("Drop TABLE  `" . DB_PREFIX . "oxit_gittigidiyor_products`");
        $this->db->query("Drop TABLE  `" . DB_PREFIX . "oxit_gittigidiyor_product_settings`");
        $this->db->query("Drop TABLE  `" . DB_PREFIX . "oxit_gittigidiyor_pfeature`");
    }


    public function insert(){

        $service_oxit =new OxitGittigidiyorService();

        $product_id = $this->request->get['product_id'];
        $this->load->model('catalog/product');


        $this->load->model('extension/oxit/gittigidiyor_entegrasyon');
        $results = $this->model_catalog_product->getProduct($product_id);
        $gg_info = $this->model_extension_oxit_gittigidiyor_entegrasyon->getGGProductInfo($product_id);
        $data =  $this->model_extension_oxit_gittigidiyor_entegrasyon->getInfo()[0];

        $product_images = $this->model_catalog_product->getProductImages($this->request->get['product_id']);

        $path = DIR_IMAGE . $product_images[0]['image'];
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data1 = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data1);


        $x=$service_oxit->insertProduct($data, $gg_info,$results,$base64);


        $this->session->data['success'] = $x['message1'];

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_model'])) {
            $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_price'])) {
            $url .= '&filter_price=' . $this->request->get['filter_price'];
        }

        if (isset($this->request->get['filter_quantity'])) {
            $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }



        $this->response->redirect($this->url->link('catalog/product', 'user_token=' . $this->session->data['user_token'] . $url, true));







    }


    public function listDeepst(){
        $service_oxit =new OxitGittigidiyorService();

        $startOffset = $this->request->get['start'];
        $this->load->model('extension/oxit/gittigidiyor_entegrasyon');
        $data =  $this->model_extension_oxit_gittigidiyor_entegrasyon->getInfo()[0];

        $x=$service_oxit->getCategoryDeepest($data ,$startOffset);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($x));




    }





    public function settings(){

        $this->load->model('extension/oxit/gittigidiyor_entegrasyon');
        $this->document->setTitle("Ürün Ayarları");


        $exist_array = $this->model_extension_oxit_gittigidiyor_entegrasyon->getSettings();

        $data['action'] = $this->url->link('extension/module/gittigidiyor_entegrasyon/settings', 'user_token=' . $this->session->data['user_token'], true);


        if (count($exist_array) > 0) {

            for ($i = 0; $i < count($exist_array); $i++) {

                $data['cargoFirm'] = $exist_array[$i]['cargoFirm'];
                $data['cargoCity'] = $exist_array[$i]['cargoCity'];
                $data['cargoPay'] = $exist_array[$i]['cargoPay'];
                $data['cargoWhere'] = $exist_array[$i]['cargoWhere'];
                $data['cargoWho'] = $exist_array[$i]['cargoWho'];
                $data['cargoTime'] = $exist_array[$i]['cargoTime'];
            }
        }


        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {



            if (count($exist_array) > 0) {

                $message = $this->model_extension_oxit_gittigidiyor_entegrasyon->editSettings($this->request->post);

                if ($message == 'success') {

                    $data['success_message'] = "Gittigidiyor ürün konfigurasyonunuz başarıyla güncellenmiştir";
                } else {

                    $data['error_message'] = $message;
                }
            } else {


                $message = $this->model_extension_oxit_gittigidiyor_entegrasyon->addSettings($this->request->post);

                if ($message == 'success') {

                    $data['success_message'] = "Gittigidiyor ürün konfigurasyonunuz başarıyla güncellenmiştir";
                } else {

                    $data['error_message'] =$message;
                }
            }
            $this->session->data['deneme'] ="deneme";
            $this->session->data['success'] = $data['success_message'];
            $this->session->data['error_warning'] = $data['error_message'];
            $this->response->redirect($this->url->link('extension/module/gittigidiyor_entegrasyon/settings','user_token=' . $this->session->data['user_token'],'&type=module', true));

        }






        $data['success'] = $this->session->data['success'];
        $this->session->data['success']=null;
        if ( $this->session->data['success']!=null){

            $data['error_warning'] = $this->session->data['error_warning'];
        }

        $this->session->data['error_warning']=null;
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $data['heading_title']= "Gittigidiyor Ürün Ayarları";


        $this->response->setOutput($this->load->view('extension/module/gittigidiyor_entegrasyon_settings', $data));

    }



    public function listSpecs(){
        $service_oxit =new OxitGittigidiyorService();

        $categoryCode = $this->request->get['category_code'];
        $this->load->model('extension/oxit/gittigidiyor_entegrasyon');
        $data =  $this->model_extension_oxit_gittigidiyor_entegrasyon->getInfo()[0];

        $x=$service_oxit->getSpecs($data ,$categoryCode);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($x['specs']));




    }



    public function getCatandSpec(){


        $productId = $this->request->get['productId'];
        $this->load->model('extension/oxit/gittigidiyor_entegrasyon');
        $data =  $this->model_extension_oxit_gittigidiyor_entegrasyon->getSpecAndCat($productId);


        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));




    }


    public function saveProductFeature(){
        $this->load->model('extension/oxit/gittigidiyor_entegrasyon');
       /* $service_oxit =new OxitGittigidiyorService();

        $categoryCode = $this->request->get['category_code'];
        $this->load->model('extension/oxit/gittigidiyor_entegrasyon');
        $data =  $this->model_extension_oxit_gittigidiyor_entegrasyon->getInfo()[0];

        $x=$service_oxit->getSpecs($data ,$categoryCode);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($x['specs']));*/
        $x=array();



        $productId = $this->request->post['product_id'];

        $dataProductSpec =  $this->model_extension_oxit_gittigidiyor_entegrasyon->getSpecAndCat($productId);


        if(count($dataProductSpec['oxit_info']) > 0) {

            $messageDelete = $this->model_extension_oxit_gittigidiyor_entegrasyon->deleteSpecAndCat($productId);


            foreach($this->request->post as $key => $value){
                // Process here using $value for the content of the field.
                array_push($x, $key." ".$value );





                if($key!='product_id'){
                    $data= array();
                    $data['name']=$key;
                    $data['value']=$value;
                    $data['productId']= $this->request->post['product_id'];
                    $message = $this->model_extension_oxit_gittigidiyor_entegrasyon->editSpecAndCat($data);

                }


            }


        }
        else{
            foreach($this->request->post as $key => $value) {
                // Process here using $value for the content of the field.
                array_push($x, $key." ".$value );





                if($key!='product_id'){
                    $data= array();
                    $data['name']=$key;
                    $data['value']=$value;
                    $data['productId']= $this->request->post['product_id'];
                    $message = $this->model_extension_oxit_gittigidiyor_entegrasyon->addSpecAndCat($data);

                }


            }
        }



        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($x));



    }


    public function productList(){

        $this->load->language('catalog/product');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/product');

        $this->getList();






    }

    protected function getList()
    {


        $this->load->model('extension/oxit/gittigidiyor_entegrasyon');

        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        } else {
            $filter_name = '';
        }

        if (isset($this->request->get['filter_model'])) {
            $filter_model = $this->request->get['filter_model'];
        } else {
            $filter_model = '';
        }

        if (isset($this->request->get['filter_price'])) {
            $filter_price = $this->request->get['filter_price'];
        } else {
            $filter_price = '';
        }

        if (isset($this->request->get['filter_quantity'])) {
            $filter_quantity = $this->request->get['filter_quantity'];
        } else {
            $filter_quantity = '';
        }

        if (isset($this->request->get['filter_status'])) {
            $filter_status = $this->request->get['filter_status'];
        } else {
            $filter_status = '';
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'pd.name';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_model'])) {
            $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_price'])) {
            $url .= '&filter_price=' . $this->request->get['filter_price'];
        }

        if (isset($this->request->get['filter_quantity'])) {
            $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('catalog/product', 'user_token=' . $this->session->data['user_token'] . $url, true)
        );

        $data['add'] = $this->url->link('catalog/product/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
        $data['copy'] = $this->url->link('catalog/product/copy', 'user_token=' . $this->session->data['user_token'] . $url, true);
        $data['delete'] = $this->url->link('catalog/product/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);

        $data['products'] = array();

        $filter_data = array(
            'filter_name' => $filter_name,
            'filter_model' => $filter_model,
            'filter_price' => $filter_price,
            'filter_quantity' => $filter_quantity,
            'filter_status' => $filter_status,
            'sort' => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
        );

        $this->load->model('tool/image');

        $product_total = $this->model_catalog_product->getTotalProducts($filter_data);

        $results = $this->model_catalog_product->getProducts($filter_data);

        foreach ($results as $result) {
            if (is_file(DIR_IMAGE . $result['image'])) {
                $image = $this->model_tool_image->resize($result['image'], 40, 40);
            } else {
                $image = $this->model_tool_image->resize('no_image.png', 40, 40);
            }

            $special = false;

            $product_specials = $this->model_catalog_product->getProductSpecials($result['product_id']);

            foreach ($product_specials as $product_special) {
                if (($product_special['date_start'] == '0000-00-00' || strtotime($product_special['date_start']) < time()) && ($product_special['date_end'] == '0000-00-00' || strtotime($product_special['date_end']) > time())) {
                    $special = $this->currency->format($product_special['price'], $this->config->get('config_currency'));

                    break;
                }
            }

            $data['products'][] = array(
                'product_id' => $result['product_id'],
                'image' => $image,
                'name' => $result['name'],
                'model' => $result['model'],
                'price' => $this->currency->format($result['price'], $this->config->get('config_currency')),
                'special' => $special,
                'quantity' => $result['quantity'],
                'status' => $result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
                'edit' => $this->url->link('catalog/product/edit', 'user_token=' . $this->session->data['user_token'] . '&product_id=' . $result['product_id'] . $url, true),
                'gittigidiyor' => $this->url->link('extension/module/gittigidiyor_entegrasyon/insert', 'user_token=' . $this->session->data['user_token'] . '&product_id=' . $result['product_id'] . $url, true),
                'gg_cat' => $this->model_extension_oxit_gittigidiyor_entegrasyon->getGGCategory($result['product_id'])
            );
        }

        $data['user_token'] = $this->session->data['user_token'];

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];

            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array)$this->request->post['selected'];
        } else {
            $data['selected'] = array();
        }

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_model'])) {
            $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_price'])) {
            $url .= '&filter_price=' . $this->request->get['filter_price'];
        }

        if (isset($this->request->get['filter_quantity'])) {
            $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['sort_name'] = $this->url->link('catalog/product', 'user_token=' . $this->session->data['user_token'] . '&sort=pd.name' . $url, true);
        $data['sort_model'] = $this->url->link('catalog/product', 'user_token=' . $this->session->data['user_token'] . '&sort=p.model' . $url, true);
        $data['sort_price'] = $this->url->link('catalog/product', 'user_token=' . $this->session->data['user_token'] . '&sort=p.price' . $url, true);
        $data['sort_quantity'] = $this->url->link('catalog/product', 'user_token=' . $this->session->data['user_token'] . '&sort=p.quantity' . $url, true);
        $data['sort_status'] = $this->url->link('catalog/product', 'user_token=' . $this->session->data['user_token'] . '&sort=p.status' . $url, true);
        $data['sort_order'] = $this->url->link('catalog/product', 'user_token=' . $this->session->data['user_token'] . '&sort=p.sort_order' . $url, true);

        $url = '';

        if (isset($this->request->get['filter_name'])) {
            $url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_model'])) {
            $url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
        }

        if (isset($this->request->get['filter_price'])) {
            $url .= '&filter_price=' . $this->request->get['filter_price'];
        }

        if (isset($this->request->get['filter_quantity'])) {
            $url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
        }

        if (isset($this->request->get['filter_status'])) {
            $url .= '&filter_status=' . $this->request->get['filter_status'];
        }

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $pagination = new Pagination();
        $pagination->total = $product_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('extension/module/gittigidiyor_entegrasyon/productList', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

        $data['pagination'] = $pagination->render();

        $data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, ceil($product_total / $this->config->get('config_limit_admin')));

        $data['filter_name'] = $filter_name;
        $data['filter_model'] = $filter_model;
        $data['filter_price'] = $filter_price;
        $data['filter_quantity'] = $filter_quantity;
        $data['filter_status'] = $filter_status;

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['gg_categories']=array();
        $service_oxit =new OxitGittigidiyorService();
        $this->load->model('extension/oxit/gittigidiyor_entegrasyon');
        $gg_info=$this->model_extension_oxit_gittigidiyor_entegrasyon->getInfo();

        $x=  $service_oxit->getCategoryList($gg_info[0]);



        $a = json_encode($x['categories']);

        foreach($x['categories'] as $item) {
            $cat=null;
            for($i=0;$i<count($item);$i++){

                $cat['catName']=$item[$i]->categoryPerma;
                $cat['catCode']=$item[$i]->categoryCode;

                array_push($data['gg_categories'],$cat);

            }//foreach element in $arr

        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/gittigidiyor_entegrasyon_product_list', $data));
    }
}
