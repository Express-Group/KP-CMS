<?php
class advertisement extends CI_Controller{
	
	public function __construct(){
		
		parent::__construct();
	}
	
	public function index(){
		$data['title'] = 'Advertisement';
		$data['template'] = 'advertisement';
		$this->load->view('admin_template',$data);	
	}
}
?> 