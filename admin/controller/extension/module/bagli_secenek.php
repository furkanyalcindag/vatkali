<?php
require_once(DIR_SERVICE . 'oxit_bagli_secenek.php');
class ControllerExtensionModuleBagliSecenek extends Controller
{
    private $error = array();

    public function index()
    {



        $this->load->language('extension/module/bagli_secenek');
        $this->document->setTitle("sssss");
        $this->load->model('setting/module');


        $x = $this->model_setting_module->getModuleByCode('oxit_bagli_secenek');




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
        $this->response->setOutput($this->load->view('extension/module/oxit_bagli_secenek', $data));
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
            'name' => 'OXIT Bağlı Seçenek',


            'module_description' => [
                '1' => ['title' => 'OXIT Bağlı Seçenek']
            ],

            'status' => 1 /* Enabled by default*/
        ];


        $this->load->model('setting/setting');
        $this->model_setting_setting->editSetting('module_oxit_bagli_secenek', ['module_oxit_bagli_secenek_status' => 0]);


        $this->load->model('setting/module');
        $this->model_setting_module->addModule('oxit_bagli_secenek', $initial2);




        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "oxit_bagli_secenek` (
		  `oxit_id` int(11) NOT NULL AUTO_INCREMENT,
		  `productId` int(11) NOT NULL,		
		  `productOptionValueId` int(11) NOT NULL,	  
		  `optionId` int(11) NOT NULL,
          `optionValueId` int(11) NOT NULL,
          `quantity` int(4) NOT NULL,
		  PRIMARY KEY (`oxit_id`)
		)");










    }
    public function uninstall()
    {

        //md5($this->apiKey.$this->secretKey.$this->time);

        $this->load->model('setting/setting');
        $this->model_setting_setting->deleteSetting('module_oxit_bagli_secenek');


        $this->load->model('setting/module');
        $this->model_setting_module->deleteModulesByCode('oxit_bagli_secenek');



        $this->db->query("Drop TABLE  `" . DB_PREFIX . "oxit_bagli_secenek`");

    }



    public function getAllSituation(){
        $service_oxit =new OxitBagliSecenekService();



        //option_id
        $parent_id = $this->request->get['parent_id'];
        $child_id = $this->request->get['child_id'];



        //option_value
        $this->load->model('catalog/option');
        $parent_values = array();
        $parent_values = $this->model_catalog_option->getOptionValues($parent_id) ;

        $child_values = array();
        $child_values = $this->model_catalog_option->getOptionValues($child_id) ;

        $data = $service_oxit->getAllSituationService($parent_values,$child_values);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($data));


    }



}
