<?php
class pie_chart extends CI_Controller{

	public function __construct(){
		parent::__construct();
	}
	
	public function index(){
		$menuId = get_menu_details_by_menu_name('Pie chart');
		if(defined("USERACCESS_VIEW".$menuId) && constant("USERACCESS_VIEW".$menuId) == 1):
			$filepath = FCPATH.'application/views/view_template/tmp_chart.txt';
			$savefilepath = FCPATH.'application/views/view_template/chart.txt';
			$view['template'] = 'pie_chart_view';
			if(file_exists($filepath)):
				$view['hasfile'] =true;
				$view['content'] =json_decode(file_get_contents($filepath),true);
			else:
				$view['hasfile'] =false;
				$view['content'] ='';
			endif;
			if(file_exists($savefilepath)):
				$view['haslivefile'] =true;
				$view['livecontent'] =json_decode(file_get_contents($savefilepath),true);
			else:
				$view['haslivefile'] =false;
				$view['livecontent'] ='';
			endif;
			$this->load->view('admin_template', $view);
		else:
			redirect(folder_name.'/common/access_permission/add_article');
		endif;
	}
	
	public function save_details(){
		$Datas = $this->input->post('data');
		$type = $this->input->post('type');
		if($type==0){
			$filepath = FCPATH.'application/views/view_template/tmp_chart.txt';
			chmod($filepath,0777);
			file_put_contents($filepath,json_encode($Datas));
			chmod($filepath,0777);
			echo 1;
		}else if($type==1){
			$filepath = FCPATH.'application/views/view_template/chart.txt';
			chmod($filepath,0777);
			file_put_contents($filepath,json_encode($Datas));
			chmod($filepath,0777);
			$post_data = array('file_name' => 'chart.txt','file_contents'=> json_encode($Datas));
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, BASEURL.'user/commonwidget/post_file_intimation');
			curl_setopt($ch, CURLOPT_POST,1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
			$result=curl_exec ($ch);
			curl_close ($ch);
			echo 1;
		}else{
			echo 0;
		}
	
	}

}
?> 