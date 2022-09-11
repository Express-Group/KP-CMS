<?php
class article_log extends CI_Controller{
	
	public function __construct(){
		parent::__construct();
		$this->load->model("admin/article_log_model");
		$this->load->helper('url');
	}
	
	public function index(){
		$data['num_rows'] = $this->article_log_model->getrows();
		$data['per_page'] = 10;
		$row=(isset($_GET['per_page']) && $_GET['per_page'])?$_GET['per_page']:0;
		$paginationconfig = ['base_url'=>base_url().folder_name.'/article_log','total_rows'=>$data['num_rows'],'per_page'=>$data['per_page'],'num_links'=>5,'page_query_string'=>TRUE,'reuse_query_string'=>FALSE,'cur_tag_open'=>'<a class="active">','cur_tag_close'=>'</a>','use_page_numbers'=>TRUE];
		$this->pagination->initialize($paginationconfig);
		$data['pagination'] = $this->pagination->create_links();
		$data['records'] = $this->article_log_model->getrecords($row,$data['per_page']);
		$data['title'] = "Article log - TNIE";
		$data['template'] = "articlelog";
		$this->load->view('admin_template',$data);
	}
	public function viewlog(){
		if($this->uri->segment(4)==''){
			redirect('niecpan/article_log');
		}
		if($this->uri->segment(5)!=''){
			$data['details'] = $this->article_log_model->comparearticle($this->uri->segment(4),$this->uri->segment(5));
			$data['title'] = "Article log - TNIE";
			$data['type'] = "compare";
			$data['template'] = "articlelog_view";
			$this->load->view('admin_template',$data);
		
		}else{
			$data['details'] = $this->article_log_model->getarticledetails($this->uri->segment(4));
			$data['title'] = "Article log - TNIE";
			$data['type'] = "view";
			$data['template'] = "articlelog_view";
			$this->load->view('admin_template',$data);
		}
	}
	public function getrelavantversion(){
		echo $this->article_log_model->get_articles($this->input->post('logid'),$this->input->post('articleid'));
	}
	public function getrelavantversionforview(){
		echo $this->article_log_model->get_articlesforview($this->input->post('logid'),$this->input->post('articleid'));
	}
	public function getusernamebyid($userid){
		return $this->article_log_model->getusername($userid);
	}
	
	public function getversioncount($articleid){
		return $this->article_log_model->getversion_count($articleid);
	}


}
?>