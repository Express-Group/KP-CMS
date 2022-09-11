<?php
Class Push_notification extends CI_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->model('admin/push_nofity');
		
	}
	public function notify(){
		$query ='';
		if($this->uri->segment(5)!='' && $this->uri->segment(5)!='null'){
			if(is_numeric($this->uri->segment(5))==true){
				$query =' AND content_id ="'.$this->uri->segment(5).'"';
			}else{
				$query =' AND title LIKE "%'.rawurldecode($this->uri->segment(5)).'%"';
			}
		}
		$mq =($this->uri->segment(5)=='') ?  'null' : $this->uri->segment(5);
		$type = $this->uri->segment(6);
		$total_rows=$this->push_nofity->article_total_count($query , $type);
		
		$this->load->library('pagination');
		$config['base_url']=site_url('niecpan/Push_notification/notify');
		$config['total_rows'] = $total_rows;
		$config["per_page"] = 10;
		$config['num_links'] = 5;
		$config['use_page_numbers'] = TRUE;
		$config['uri_segment'] = 4;
		$config['first_url'] = site_url('niecpan/Push_notification/notify/0/'.$mq.'/'.$this->uri->segment(6));
		$config['suffix'] = '/'.$mq.'/'.$this->uri->segment(6);
		$config['cur_tag_open'] = '<li class="active"><a>';
		$config['cur_tag_close'] = '</a></li>';
		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul">';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['next_link'] = 'next';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['prev_link'] = 'previous';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		if($this->uri->segment(4)):
			$page = ($this->uri->segment(4)) ;
		else:
			$page = 0;
		endif;
		$article_data['data']=$this->push_nofity->get_latest_article($config["per_page"],$page ,$query , $type);
		$article_data['pagination']=$this->pagination->create_links();
		$article_data['controller']=$this;

		$this->load->view('admin/common/header');
		$this->load->view('admin/pushnotification',$article_data);
		$this->load->view('admin/common/footer');
		
	}
	
	public function check_notification($content_id){
		return $this->push_nofity->checknotification($content_id);
	
	}
	
	public function send_notification(){
		
		$content_id=$this->input->post('c');
		$title=$this->input->post('t');
		$a_url=$this->input->post('u');
		$result=$this->onesignalapi($a_url,$title);

		if($result){		echo $this->push_nofity->insertnotification($content_id);
		}else{
			echo 0;
		};
	}
	
	public function onesignalapi($a_url,$title){
		$content = array(
					"en" => $title
					);
		$fullUrl  = BASEURL.$a_url;
		$fields = array(
					'app_id' => ONE_SIGNAL_APP_ID,
					'included_segments' => array('All'),
					'url' => $fullUrl,
					'contents' => $content
					);

		$fields = json_encode($fields);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
												   'Authorization: Basic '.ONE_SIGNAL_REST_API));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		$response = curl_exec($ch);
		curl_close($ch);
		return $response;
	}
} 
?>
