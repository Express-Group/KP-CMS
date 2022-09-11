<?php
class widget_master extends CI_Controller{
	
	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->helper('url');
	}
	public function index(){
		$data['Menu_id'] = get_menu_details_by_menu_name('Widget master');
		if(defined("USERACCESS_VIEW".$data['Menu_id']) && constant("USERACCESS_VIEW".$data['Menu_id']) == 1) {
			$data['title']		= 'widget master';
			$data['template'] 	= 'widget_manager';
			$data['widgets'] 	= $this->db->query("SELECT widgetId, widgetName, widgetfilePath, status FROM widget_master")->result();
			$this->load->view('admin_template',$data);
		
		}else{
			redirect(folder_name.'/common/access_permission/add_widget');
		}
	}
	
	public function action(){
		$type = $this->uri->segment('4');
		$widgetid =  $this->uri->segment('5');
		$data['Menu_id'] = get_menu_details_by_menu_name('Widget master');
		if(defined("USERACCESS_VIEW".$data['Menu_id']) && constant("USERACCESS_VIEW".$data['Menu_id']) == 1 && is_numeric($type) && is_numeric($widgetid) ) {
			if($type=='1'){
				$this->db->where('widgetId',$widgetid);
				$this->db->update('widget_master',['status'=>2]);
				redirect(folder_name.'/widget_master');
			}else if($type=='2'){
				$this->db->where('widgetId',$widgetid);
				$this->db->update('widget_master',['status'=>1]);
				redirect(folder_name.'/widget_master');
			}else{
				redirect(folder_name.'/widget_master/access_permission/add_widget');
			}
		
		}else{
			redirect(folder_name.'/common/access_permission/add_widget');
		}
	}
}
?> 