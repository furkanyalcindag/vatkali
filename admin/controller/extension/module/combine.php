<?php
class ControllerExtensionModuleCombine extends Controller
{
	private $error = array();
	public function index()
	{ 
	    
	    
	    
	    $this->load->language('extension/module/combine');
	$this->document->setTitle($this->language->get('heading_title'));
	$this->load->model('setting/module');
	
	$x=$this->model_setting_module-> getModuleByCode('combine');
	
	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
		
			$this->model_setting_module->editModule($this->request->get['module_id'], $this->request->post);
		
		$this->session->data['success'] = $this->language->get('text_success');
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
			'href' => $this->url->link('extension/module/combine', 'user_token=' . $this->session->data['user_token'], true)
		);
	} else {
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/combine', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true)
		);
	}
	if (!isset($this->request->get['module_id'])) {
		$data['action'] = $this->url->link('extension/module/combine', 'user_token=' . $this->session->data['user_token'], true);
	} else {
		$data['action'] = $this->url->link('extension/module/combine', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true);
	}
	$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
	if (isset($x) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
		$module_info = $this->model_setting_module->getModuleByCode('deneme');
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
	$this->response->setOutput($this->load->view('extension/module/combine', $data));
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	}
	protected function validate()
	{
	    
	    
	    if (!$this->user->hasPermission('modify', 'extension/module/combine')) {
		$this->error['warning'] = $this->language->get('error_permission');
	}

	if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
		$this->error['name'] = $this->language->get('error_name');
	}

	return !$this->error;
	    
	    
	    
	    
	    
	    
	    
	}
	public function install()
	{ 
	    
	    
	
	     
	        
	     $initial2=    [
		'name' => 'Combine Module',
	
		
		'module_description'=>[
		   '1'=> ['title'=>'Combine'] ],
	
		'status' => 1 /* Enabled by default*/
	];

	    
	        $this->load->model('setting/setting');
	        $this->model_setting_setting->editSetting('module_combine', ['module_combine_status' => 0]);
	    
	    
	    	$this->load->model('setting/module');
	    	$this->model_setting_module->addModule('combine', $initial2);
	    	
	    	
	    	
	    	$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "combine` (
		  `combine_id` int(11) NOT NULL AUTO_INCREMENT,
		  `product_id` int(11) NOT NULL,
		  `related_id` int(11) NOT NULL,
		  PRIMARY KEY (`combine_id`)
		)");
	    
	    
	    
	    
	}
	public function uninstall()
	{
	    
	    $this->load->model('setting/setting');
	    $this->model_setting_setting->deleteSetting('module_combine');
	    
	    
	    $this->load->model('setting/module');
	    $this->model_setting_module->deleteModulesByCode('combine');
	    
	    
	    
	    $this->db->query("Drop TABLE  `" . DB_PREFIX . "combine`");
	    
	    
	    
	    
	}
}