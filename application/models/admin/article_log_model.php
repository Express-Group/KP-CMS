<?php
class article_log_model extends CI_Model{

	public function __construct(){
		parent::__construct();
		$this->load->database();
	}
	
	public function getrows(){
	//	return $this->db->select("article_log_id")->from("article_log")->get()->num_rows();
		return $this->db->query("SELECT t2.article_id,log FROM article_log as t2 WHERE created_on=(SELECT MAX(created_on) FROM article_log WHERE article_id=t2.article_id) ORDER BY created_on DESC")->num_rows();
	}
	public function getrecords($row,$perpage){
		return $this->db->query("SELECT article_log_id,t2.article_id,log,created_on,created_by FROM article_log as t2 WHERE created_on=(SELECT MAX(created_on) FROM article_log WHERE article_id=t2.article_id) ORDER BY created_on DESC LIMIT ".$row.", ".$perpage."")->result();
	}
	public function getusername($userid){
		$query = $this->db->query("SELECT Firstname,Lastname FROM usermaster WHERE User_id='".$userid."'");
		if($query->num_rows() > 0){
			$response= $query->result();
			return $response[0]->Firstname.' '.$response[0]->Lastname;
		}else{
			return '';
		}
	}
	public function getarticledetails($logid){
		return $this->db->query("SELECT article_log_id,article_id,log,created_on,created_by FROM article_log WHERE article_log_id='".$logid."'")->result();
	}
	
	public function get_articles($logid,$articleid){
		$template =''; 
		$query = $this->db->query("SELECT article_log_id,article_id,log,created_on,created_by FROM article_log WHERE article_id='".$articleid."' AND article_log_id!='".$logid."' ORDER BY created_on DESC");
		if($query->num_rows()==0){
			$template .='<p>No articles found</p>';
		}else{
			$template .='<table class="table table-bordered">';
			$template .='<tr><th>Version</th><th>article id</th><th>crreated on</th><th>crreated by</th><th>Action</th></tr>';
			$i=1;
			foreach($query->result() as $result){
				$template .='<tr>';
				$template .='<td>version-'.$i.'</td>';
				$template .='<td>'.$result->article_id.'</td>';
				$template .='<td>'.$result->created_on.'</td>';
				$template .='<td>'.$this->getusername($result->created_by).'</td>';
				$template .='<td><button class="button-primary" onclick="compareit('.$logid.','.$result->article_log_id.')">Compare</button></td>';
				$template .='</tr>';
				$i++;
			}
			$template .='</table>';
		}
		return $template; 
	}
	
	public function get_articlesforview($logid,$articleid){
		$template =''; 
		$query = $this->db->query("SELECT article_log_id,article_id,log,created_on,created_by FROM article_log WHERE article_id='".$articleid."'  ORDER BY created_on DESC");
		if($query->num_rows()==0){
			$template .='<p>No articles found</p>';
		}else{
			$template .='<table class="table table-bordered">';
			$template .='<tr><th>Version</th><th>article id</th><th>crreated on</th><th>crreated by</th><th>Action</th></tr>';
			$i=1;
			foreach($query->result() as $result){
				$template .='<tr>';
				if($result->article_log_id==$logid){
					$template .='<td>SELECTED VERSION</td>';
				}else{
					$template .='<td>version-'.$i.'</td>';
				}
				
				$template .='<td>'.$result->article_id.'</td>';
				$template .='<td>'.$result->created_on.'</td>';
				$template .='<td>'.$this->getusername($result->created_by).'</td>';
				$template .='<td><button class="button-primary" onclick="viewit('.$logid.','.$result->article_log_id.')">View</button></td>';
				$template .='</tr>';
				$i++;
			}
			$template .='</table>';
		}
		return $template; 
	}
	
	public function comparearticle($oldlogid,$newlogid){
		$result =[];
		$result['old']=$this->db->query("SELECT article_log_id,article_id,log,created_on,created_by FROM article_log WHERE article_log_id='".$oldlogid."'")->result();
		$result['new']=$this->db->query("SELECT article_log_id,article_id,log,created_on,created_by FROM article_log WHERE article_log_id='".$newlogid."'")->result();
		return $result;
	}
	
	public function getversion_count($article_id){
		return $this->db->select('article_log_id')->from('article_log')->where('article_id',$article_id)->get()->num_rows();
	}
}
?>