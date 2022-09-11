<?php
class revert_version extends CI_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->model('admin/version_revert');
	}
	
	public function index(){
		$menu_name="Revert Version";
		$data['Menu_id'] = get_menu_details_by_menu_name($menu_name);
		if(defined("USERACCESS_VIEW".$data['Menu_id']) && constant("USERACCESS_VIEW". $data['Menu_id']) == 1) {
		extract($_GET);
		$query="";
		if($menu_id!=''){
			$query .=" AND menu_id='".$menu_id."'";
		}
		if($page_type!=''){
			$query .=" AND page_type='".$page_type."'";
		}
		$total_rows=$this->version_revert->render_version($query,1);
		$config['base_url']=HOMEURL.folder_name.'/revert_version';
		$config['total_rows']=$total_rows;
		$config['per_page']=10;
		$config['num_links']=5;
		$config['page_query_string']=TRUE;
		$config['reuse_query_string']=TRUE;
		$config['suffix']='&menu_id='.$menu_id.'&page_type='.$page_type;
		$config['use_page_numbers']=TRUE;
		$config['first_url']=HOMEURL.folder_name.'/revert_version?&menu_id='.$menu_id.'&page_type='.$page_type;
		$this->pagination->initialize($config);
		$limit=(isset($_GET['per_page']) && $_GET['per_page'])?$_GET['per_page']:0;
		$VersionContent=$this->version_revert->render_version($query,2,$limit);
		$data['title']= 'Revert Version';
		$data['template']= 'revert-version';
		$data['controller']= $this;
	    $data['pagination']=$this->pagination->create_links();
	    $data['data']=$VersionContent;
		$data['menu_id']=$this->version_revert->render_menu();
		$this->load->view('admin_template',$data);
		}else {
				redirect(folder_name.'/common/access_permission/revertversion');
		}
	}
	
	public function delete_version(){
		$version_id=$this->input->post('version_id');
		$role=$this->input->post('role');
		if($version_id==''){
			echo 2;
		}else{
			echo $this->version_revert->deleteversion($version_id,$role);
		}
	
	}
	
	public function preview_version(){
		$version_id=$this->input->post('version_id');
		echo $this->version_revert->previewversion($version_id);
	}
	
	public function action(){
		$versionID= $this->input->post('version_id');
		$type= $this->input->post('type');
		echo $this->version_revert->makeaction($versionID,$type);

	}
}
?>