<?php
class advsave extends CI_Controller{
	public function __construct(){
		parent::__construct();
	}
	public function index(){
		$widgetinstanceid = $this->input->post('widgetinstance');
		$title = $this->input->post('title');
		$content = $this->input->post('widgetcontent');
		$Response =[];
		$Response['title'] = $title;
		$Response['content'] = $content;
		$Response['widgetinstanceid'] = $widgetinstanceid;
		$result = json_encode($Response);
		$filename = FCPATH.'application/views/adv/'.$widgetinstanceid.'.adv';
		file_put_contents($filename ,$result);
		chmod($filename ,0777);
		$this->update_script_to_app(1,$result,$widgetinstanceid.'.adv');
		echo 1;
		
	}
	
	public function advremove(){
		$widgetinstanceid = $this->input->post('widgetinstance');
		$file = FCPATH.'application/views/adv/'.$widgetinstanceid.'.adv';
		if(file_exists($file)){
			unlink($file);
		}
		$this->update_script_to_app(2,'',$widgetinstanceid.'.adv');
		echo 1;
	}
	
	public function update_script_to_app($type=1,$content='',$filename=''){
		
		$post_data = array('file_name' => $filename,'file_contents'=> $content,'type'=>$type);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, BASEURL.'user/commonwidget/post_scriptfile_intimation');
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		$result=curl_exec ($ch);
		curl_close ($ch);
	}
}
?>