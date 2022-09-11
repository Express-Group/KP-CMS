<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Amp_advertisements extends CI_Controller{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function  index(){
		$data['Menu_id'] = get_menu_details_by_menu_name('AMP Advertisements');
		if(defined("USERACCESS_VIEW".$data['Menu_id']) && constant("USERACCESS_VIEW".$data['Menu_id']) == 1) {
			$cmsFilePath = FCPATH.'application/views/view_template/amp_adv.json';
			$data['title'] = 'AMP ADVERTISEMENTS';
			$data['template'] = 'amp_adv';
			$data['adv'] = json_decode(file_get_contents($cmsFilePath) ,true);
			$this->load->view('admin_template' , $data);
		}else{
			redirect(folder_name.'/common/access_permission/amp_advertisements');
		}
	}
	
	public function save_content(){
		$data =[];
		$data['after_title'] = $this->input->post('after_title');
		$data['after_tags'] = $this->input->post('after_tags');
		$data['between_article'] = $this->input->post('between_article');
		$data['between_msection'] = $this->input->post('between_msection');
		$data['between_gallery_images'] = $this->input->post('between_gallery_images');
		$data = json_encode($data);
		$cmsFilePath = FCPATH.'application/views/view_template/amp_adv.json';
		file_put_contents($cmsFilePath ,$data);
		$post_data = array('file_name' => 'amp_adv.json','file_contents'=> $data);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, MOBILEURL.'article_controller/post_file_intimation');
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		$result=curl_exec ($ch);
		curl_close ($ch);
		if($result){
			redirect(folder_name.'/amp_advertisements');
			exit;
		}
		
	}
}
?> 