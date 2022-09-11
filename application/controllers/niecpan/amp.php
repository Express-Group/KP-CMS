<?php
class amp extends CI_Controller{

	public function __construct(){
		parent::__construct();
		$CI = &get_instance();
		$this->live_db = $CI->load->database('live_db', TRUE);
	}
	public function index(){
		$data['title']='AMP - ARTICLE';
		$data['template']='amp_page';
		$this->load->view('admin_template',$data);
	}
	public function page(){
		$DetailsIntoJson=[];
		$Details['records']=[];
		$PerPage=$this->input->post('per_page');
		$Limit=$this->input->post('limit');
		$Limit=($Limit=='' ||$Limit==0)?0:$Limit;
		if($this->input->post('search_term')!='' && $this->input->post('search_term')!=null ){
			$TotalRows=$this->live_db->query("SELECT content_id FROM article WHERE status='P' AND content_id='".$this->input->post('search_term')."'")->num_rows();
		}else{
			$TotalRows=$this->live_db->query("SELECT content_id FROM article WHERE status='P'")->num_rows();
		}
		
		$_GET['per_page']=$Limit;
		$config['base_url']="";
		$config['total_rows']=$TotalRows;
		$config['per_page']=$PerPage;
		$config['num_links']=5;
		$config['page_query_string']=TRUE;
		$config['reuse_query_string']=TRUE;
		$config['use_page_numbers']=TRUE;
		$config['first_link'] = FALSE;
		$config['last_link'] = FALSE;
		$config['anchor_class'] = 'class="pager_num"';
		$this->pagination->initialize($config);
		$Details['pagination']=str_replace('<a','<a pagination ',$this->pagination->create_links());
		if($this->input->post('search_term')!='' && $this->input->post('search_term')!=null ){
			$Data=$this->live_db->query("SELECT content_id,title,section_name,last_updated_on,amp_status FROM  article WHERE status='P' AND content_id ='".$this->input->post('search_term')."' ORDER BY last_updated_on DESC LIMIT ".$Limit.", ".$PerPage."")->result();
		}else{
			$Data=$this->live_db->query("SELECT content_id,title,section_name,last_updated_on,amp_status FROM  article WHERE status='P' ORDER BY last_updated_on DESC LIMIT ".$Limit.", ".$PerPage."")->result();
		}
		
		foreach($Data as $DataValue):
			$DetailsIntoJson['content_id']=$DataValue->content_id;
			$DetailsIntoJson['title']=strip_tags($DataValue->title);
			$DetailsIntoJson['section_name']=$DataValue->section_name;
			$DetailsIntoJson['last_updated_on']=$DataValue->last_updated_on;
			$DetailsIntoJson['amp_status']=$DataValue->amp_status;
			$Details['records'][]=$DetailsIntoJson;
		endforeach;
		echo json_encode($Details);
		
	}
	public function save(){
		$contentID=$this->input->post('content_id');
		if($contentID!=''){
			$this->live_db->where('content_id',$contentID);
			echo $this->live_db->update('article',['amp_status'=>1]);
		}else{
			echo 0;
		}
	}
}
?>