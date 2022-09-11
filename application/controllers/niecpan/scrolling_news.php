<?php
Class scrolling_news extends CI_Controller{
	
	public function __construct(){
		parent::__construct();
		$this->load->model('admin/scrolling_data');
		$this->load->helper('url');
	}
	
	public function index(){

		$data['section_mapping'] 	= $this->common_model->multiple_section_mapping();
		$data['content']=$this->scrolling_data->fetch_scrolling_data('' ,@$_GET['id']);
		$this->load->view('admin/common/header');
		$this->load->view('admin/scrolling',$data);
		$this->load->view('admin/common/footer');

	
	}
	
	
	public function save_news(){
		$news=$this->input->post('news');
		$section_id= $this->input->post('section_id');
		$section_name= $this->input->post('section_name');
		echo $this->scrolling_data->save_scrolling_data($news,$section_id,$section_name);
	}
	
	
	public function save_edit_news(){
		$news=$this->input->post('news');
		$sid=$this->input->post('sid');
		$section_id=$this->input->post('section_id');
		$section_name=$this->input->post('section_name');
		echo $this->scrolling_data->save_edit_scrolling_data($news,$sid,$section_id,$section_name);
	}
	
	public function delete_news(){
		$sid=$this->input->post('sid');
		echo $this->scrolling_data->delete_data($sid);
	}
	
	public function render_news(){
		$rendered=$this->scrolling_data->fetch_scrolling_data();
		$Template='<ul>';
		foreach($rendered as $data){
			$date=explode(' ',$data->created_on);
			$date=explode(':',$date[1]);
			$date=$date[0].':'.$date[1];
			$Template .='<li><span class="date-color">'.$date.' :</span> <span class="content-color">'.$data->content.'</span></li>';
		}
		$Template .='</ul>';
		echo $Template;
	
	}
	
	public function upload_image(){
		$this->load->library('upload');
		$config['upload_path']          = source_base_path.'uploads/scroll_news/';
		$config['allowed_types']        = 'gif|jpg|png';
		$this->upload->initialize($config);
		if ( ! $this->upload->do_upload('file'))
		{
			$data =$this->upload->display_errors();
		}
		else
		{
			$data = $this->upload->data();
		}
		echo json_encode($data);
	}
	
	

	public function fetch_edit_news(){
		$sid=$this->input->post('sid');
		$edit_data = $this->scrolling_data->fetch_edit_news_data($sid);
		foreach($edit_data as $data){
			$ren_data = $data->content;
			$sec_data = $data->section_id;
		}
		$data = ($sec_data !='') ? $sec_data : '';
		echo json_encode(['id' => $data, 'content' => $ren_data]);
	}


}
?>