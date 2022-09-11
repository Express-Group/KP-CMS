<?php
defined('BASEPATH') OR exit('No direct script access allowed'); 
class flash_news extends CI_Controller{

	public function __construct(){
		parent::__construct();
		
	}
	
	public function index(){
		$view=[];
		$view['title']='Flash news';
		$filename = FCPATH.'application/views/flash/flash.news';
		$view['contents'] = 'EMPTY';
		$view['template'] = 'flash_news_view';
		if(file_exists($filename)){
			$response = file_get_contents($filename);
			if($response==''){
				$view['contents'] = 'EMPTY';
			}else{
				$view['contents'] = json_decode($response,true);
			}
		}
		$this->load->view('admin_template',$view);
	}
	
	public function insertnews(){
		$data = $this->input->post('data');
		if($data!=''){
			$response['response'] = $data;
			$result = json_encode($response);
			$filename = FCPATH.'application/views/flash/flash.news';
			file_put_contents($filename,$result);
			$this->movetolive('flash.news',$result);
			$this->history($result);
			echo chmod($filename,0777);
		}else{
			echo 0;
		}
	}
	
	public function movetolive($file_name,$file_data){
		$post_data = array('file_name' => $file_name,'file_contents'=> $file_data);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, BASEURL.'user/commonwidget/save_flashnews');
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		$result=curl_exec ($ch);
		curl_close ($ch);
	
	}
	public function history($result){
		$this->load->database();
		return $this->db->insert('flashnews_history',array('user_id' => USERID , 'history' => $result));
		
	}
	
	public function history_view(){
		$row=(isset($_GET['per_page']) && $_GET['per_page']!='')?$_GET['per_page']:0;
		$this->load->database();
		$totalRows = $this->db->query("SELECT fid FROM  flashnews_history ORDER BY  created_on DESC")->num_rows();
		$config['base_url'] = HOMEURL.folder_name.'/flash_news/history_view';
		$config['total_rows'] = $totalRows;
		$config['per_page'] = 5;
		$config['num_links'] = 5;
		$config['page_query_string'] = TRUE;
		$config['use_page_numbers'] = TRUE;
		$config['next_link'] = 'next';
		$config['prev_link'] = 'prev';
		$this->pagination->initialize($config);
		$view['pagination'] = $this->pagination->create_links();
		$view['result']=$this->db->query("SELECT fid,user_id,history,created_on FROM flashnews_history ORDER BY  created_on DESC LIMIT ".$row.", ".$config['per_page']."")->result();
		$view['title']='Flash news history';
		$view['template'] = 'flash_news_history';
		$this->load->view('admin_template',$view);
	}
	
	public function get_username($userid){
		$this->load->database();
		$UserDetails = $this->db->query("SELECT Firstname, Lastname FROM  usermaster WHERE User_id='".$userid."'");
		if($UserDetails->num_rows()==0){
			return '';
		}else{
			$result = $UserDetails->result();
			return $result[0]->Firstname.' '.$result[0]->Lastname;
		
		}
	}
	
} 
?>