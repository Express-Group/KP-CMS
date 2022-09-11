<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class articlelist extends CI_COntroller{
	
	public function __construct(){
		parent::__construct();
		$this->load->model('admin/articlelistmodel');
	}
	
	public function index(){
		$content_type 		= "Article Manager";
		$button_name		= "Create Article"; 
		$addPage_url 		= folder_name."/article";
		$menu_name			= "Article";
		$data['Menu_id'] = get_menu_details_by_menu_name($menu_name);
		if(defined("USERACCESS_VIEW".$data['Menu_id']) && constant("USERACCESS_VIEW". $data['Menu_id']) == 1){
			$data['section_mapping'] 	= $this->common_model->multiple_section_mapping();
			$data['title']				= $content_type;
			$data['btn_name']			= $button_name;
			$data['addPage_url']		= $addPage_url;
			$data['articles'] = $this->articlelistmodel->articlelist();
			$data['template'] 	= 'articlelist_template';
			$this->load->view('admin_template',$data);
		}
	}
}
?> 