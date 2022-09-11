<?php
class standout extends CI_Controller{
	public function __construct(){
		parent::__construct();
		$CI = &get_instance();
		$this->live_db = $CI->load->database('live_db', TRUE);
		$this->load->database();
	}
	
	public function index(){
		$response=['title'=>'STANDOUT ARTICLE MANAGER','template'=>'standout_view'];
		if(isset($_GET['title']) && $_GET['title']!=''){ $title=$_GET['title'];}else{ $title=''; }
		if(isset($_GET['contentid']) && $_GET['contentid']!=''){ $contentid=$_GET['contentid'];}else{ $contentid=''; }
		$response['livearticle']=[];
		if($contentid!='' && $title==''){
			$response['livearticle']=$this->db->query("SELECT content_id,title FROM  articlemaster WHERE content_id='".$contentid."' AND status='P' ")->result();
		}
		if($contentid=='' && $title!=''){
			$response['livearticle']=$this->db->query("SELECT content_id,title FROM  articlemaster WHERE title LIKE'%".$title."%' AND status='P' ")->result();
		}
		if($contentid!='' && $title!=''){
			$response['livearticle']=$this->db->query("SELECT content_id,title FROM  articlemaster WHERE title LIKE'%".$title."%' AND content_id='".$contentid."' AND status='P' ")->result();
		}
		if($contentid=='' && $title==''){
		$response['livearticle']=$this->db->query("SELECT content_id,title FROM  articlemaster WHERE status='P' ORDER BY publish_start_date DESC LIMIT 1 ")->result();
		}
		$response['response']=$this->live_db->query("SELECT content_id,sid FROM standoutarticlemanager")->result();
		$response['controller']=$this;
		$this->load->view('admin_template',$response);
	}
	
	public function standoutarticle($Content_id){
		$response=$this->db->query("SELECT content_id,title FROM articlemaster WHERE content_id='".$Content_id."'")->result();
		return $response;
	}
	
	public function save(){
		$contentID=$this->input->post('contentid');
		if($contentID==0 || $contentID==null){
			echo 0;
		}else{
			$getcount=$this->live_db->query("SELECT sid FROM standoutarticlemanager")->num_rows();
			if($getcount==0 || $getcount > 1 ){
				$this->live_db->empty_table('standoutarticlemanager');
				echo $this->live_db->insert('standoutarticlemanager',['content_id' => $contentID]);
			}else{
				echo $this->live_db->query("UPDATE standoutarticlemanager SET content_id='".$contentID."'");
			}
		}
	
	}
}
?>