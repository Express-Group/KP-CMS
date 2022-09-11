<?php
class sponsor_article extends CI_Controller{
	
	private $menuId;
	private $menuName;
	private $userId;
	private $userRights;
	private $errorMessage;
	public function __construct(){
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->model("admin/sponsor_article_model");
		$this->menuId = 52;
		$this->userId = USERID;
		$this->menuName = 'Sponsor Article';
		$this->errorMessage = '<title>*** You are not authorized to view this page.***</title><h3 style=\'color:red;\'>***Not authorized to view this page.***</h3><a href="'.HOMEURL.folder_name.'">Go to home</a>';
		$this->userRights = self::useracess();
	}
	
	public function index(){
		if($this->userRights['view']==1){
			$view['title'] = $this->menuName;
			$view['role'] = $this->userRights;
			$view['template'] = 'sponsor_article_view';
			$type = 0;
			if($this->input->get('query')!='' && is_numeric($this->input->get('query'))){ $type = 1; }
			if($this->input->get('query')!='' && !is_numeric($this->input->get('query'))){ $type = 2; }
			$totalRows = $this->sponsor_article_model->get_totalarticlecount($type);
			$perPage=(isset($_GET['per_page']) && $_GET['per_page'])?$_GET['per_page']:0;
			$baseurl = HOMEURL.folder_name.'/sponsor_article';
			$articlelist = 10;
			$paginationConfiguration = ['base_url'=>$baseurl,'total_rows'=>$totalRows,'per_page'=>$articlelist,'num_links'=>5,'page_query_string'=>TRUE,'reuse_query_string'=>FALSE,'suffix'=>'&query='.$this->input->get('query').'&section='.$this->input->get('section'),'cur_tag_open'=>'<a class="active">','cur_tag_close'=>'</a>','use_page_numbers'=>TRUE,'first_url'=>$baseurl.'?query='.$this->input->get('query').'&section='.$this->input->get('section'),'first_link'=>FALSE,'last_link'=>FALSE];
			$this->pagination->initialize($paginationConfiguration);
			$view['pagination']=$this->pagination->create_links();
			$view['articlelist']=$this->sponsor_article_model->get_articles($perPage,$articlelist,$type);
			$this->load->view('admin_template',$view);
			
		}else{
			echo $this->errorMessage;
		}
	}
	public function useracess(){
		return $this->sponsor_article_model->get_useraccess($this->userId,$this->menuId);
	}
	
	public function updatesponsorarticle(){
		$contentid = $this->input->post('content_id');
		if($contentid!=''){
			echo json_encode($this->sponsor_article_model->insertsponsorarticle($contentid));
		}else{
			echo 0;
		}
	
	}
	
	public function sponsor_article(){
		return $this->sponsor_article_model->get_sponsor_article();
	} 
	public function get_article_title($content_id){
		return $this->sponsor_article_model->article_title($content_id);
	}
	public function update_expiry_date(){
		$sid = $this->input->post('sid');
		$expiry_date = date_create($this->input->post('expiry_date'));
		$expiry_date =  date_format($expiry_date,"Y-m-d H:i:s");
		$this->sponsor_article_model->update_expirydate($sid,$expiry_date);
		echo $expiry_date;
	}
	
	public function delete_sponsor_article(){
		$sid = $this->input->post('sid');
		echo $this->sponsor_article_model->delete_sponsorarticle($sid);
	}
	
	public function publish(){
		echo $this->sponsor_article_model->publish_articles($this->input->post('data'));
		
	}
	
	public function getsections($type,$id){
		if($type==1){
			return $this->sponsor_article_model->get_sections($type,$id);
		}
		if($type==2){
			return $this->sponsor_article_model->get_sections($type,$id);
		}
		
	}
}
?>