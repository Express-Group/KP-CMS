<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class tabtable_master extends CI_Controller{
	
	public function __construct(){
	
		parent::__construct();
		$this->load->helper('form');
		$this->load->helper('url');
	}

	
	public function index(){
		$data['Menu_id'] = get_menu_details_by_menu_name('Tab & Table Master');
		if(defined("USERACCESS_VIEW".$data['Menu_id']) && constant("USERACCESS_VIEW".$data['Menu_id']) == 1) {
			$data['tabs'] = '';
			$data['title'] = 'Tab & Table Master';
			$data['template'] = 'tabtable_view';
			$filepath = FCPATH.'application/views/view_template/tab.table';
			if(!file_exists($filepath)){
				$data['tabs'] = '';
			}else{
				$content = file_get_contents($filepath);
				if($content!=''){
					$data['tabs']  = $content;
				}
			}
			$this->load->view('admin_template',$data);
		}else{
			echo '<h5>You are not authorised to view this page</h5>';
		}
	}
	
	public function update(){
		$Details =  $this->input->post('json');
		$Details = json_encode($Details);
		$path = FCPATH.'application/views/view_template/tab.table';
		file_put_contents($path,$Details);
		$post_data = array('file_name' => 'tab.table','file_contents'=> $Details);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, BASEURL.'user/commonwidget/post_file_intimation');
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		$result=curl_exec ($ch);
		curl_close ($ch);
		echo 1;
	
	}
}  
?> 