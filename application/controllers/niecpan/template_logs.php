<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Template_logs extends CI_Controller 
{
	public function __construct() 
	{
		parent::__construct();
		$this->load->model('admin/template_publish_log_model');
	}
	public function index()
	{
		$data['Menu_id'] = get_menu_details_by_menu_name("Template logs");
		if(defined("USERACCESS_VIEW".$data['Menu_id']) && constant("USERACCESS_VIEW". $data['Menu_id']) == 1) 
		{
			$rawdata['title']		= 'Template Publish Log Manager';
			$rawdata['template'] 	= 'template_logs';
			$this->load->view('admin_template',$rawdata);
		}
		else 
		{
			redirect(folder_name.'/common/access_permission/template_logs');
		}
	}
		
	public function template_datatable()
	{
		$this->template_publish_log_model->datatable_publish_logs();
	}
	
}

?>