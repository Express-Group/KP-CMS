<?php
class sponsor_article_model extends CI_Model{
	
	public function __construct(){
		parent::__construct();
		$this->load->database();
		$CI = &get_instance();
		$this->live_db = $CI->load->database('live_db',true);
	}
	public function get_useraccess($userId,$menuId){
		$rights=['view'=>0,'add'=>0,'edit'=>0,'delete'=>0,'publish'=>0,'unpublish'=>0];
		$userRights = $this->db->query("SELECT IsViewAllowed, IsAddAllowed, IsEditAllowed, IsDeleteAllowed, IsPublishAllowed, IsUnPublishAllowed FROM userrights WHERE user_id='".$userId."' AND Menu_Id='".$menuId."' ORDER BY userAccessrights_ID DESC LIMIT 1");
		if($userRights->num_rows() == 0){
			return $rights;
		}else{
			$result = $userRights->result();
			$rights=['view'=>$result[0]->IsViewAllowed,'add'=>$result[0]->IsAddAllowed,'edit'=>$result[0]->IsEditAllowed,'delete'=>$result[0]->IsDeleteAllowed,'publish'=>$result[0]->IsPublishAllowed,'unpublish'=>$result[0]->IsUnPublishAllowed];
			return $rights;
		}
		
	}
	public function get_totalarticlecount($type){
		$sectionquery = "";
		if($this->input->get('section')!=''){  $sectionquery = " AND a1.section_id='".$this->input->get('section')."'"; }
		if($type==0){
			return $this->live_db->query("SELECT a1.content_id FROM `article` as a1 WHERE a1.status='P'".$sectionquery." LIMIT 1000")->num_rows();
		}elseif($type==1){
			return $this->live_db->query("SELECT a1.content_id FROM `article` as a1 WHERE a1.status='P' AND a1.content_id='".$this->input->get('query')."'".$sectionquery." LIMIT 1000")->num_rows();
		}else{
			return $this->live_db->query("SELECT a1.content_id FROM `article` as a1  WHERE a1.status='P' AND  (a1.title LIKE '%".$this->input->get('query')."%' OR a1.title ='".$this->input->get('query')."')".$sectionquery." LIMIT 1000")->num_rows();
		}
	}
	
	public function get_articles($limit,$perpage,$type){
		$sectionquery = "";
		if($this->input->get('section')!=''){  $sectionquery = " AND a1.section_id='".$this->input->get('section')."'"; } 
		if($type==0){
			return $this->live_db->query("SELECT a1.content_id,a1.title FROM `article` as a1  WHERE a1.status='P'".$sectionquery."  ORDER BY a1.publish_start_date DESC LIMIT ".$limit.", ".$perpage."")->result();
		}elseif($type==1){
			return $this->live_db->query("SELECT a1.content_id,a1.title FROM `article` as a1 WHERE a1.status='P' AND a1.content_id='".$this->input->get('query')."'".$sectionquery." ORDER BY a1.publish_start_date DESC LIMIT ".$limit.", ".$perpage."")->result();
		}else{
			return $this->live_db->query("SELECT a1.content_id,a1.title FROM `article` as a1 WHERE a1.status='P' AND  (a1.title LIKE '%".$this->input->get('query')."%' OR a1.title ='".$this->input->get('query')."')".$sectionquery." ORDER BY a1.publish_start_date DESC LIMIT ".$limit.", ".$perpage."")->result();
		}
	}
	
	public function insertsponsorarticle($contentid){
		$date = date("Y-m-d H:i:s");
		$this->live_db->insert('sponsor_article',['content_id'=>$contentid,'created_by'=>USERID,'expiry_date'=>$date]);
		return ['id' => $this->live_db->insert_id() ,'date' => $date];
	}
	
	public function get_sponsor_article(){
		return $this->live_db->query("SELECT sid,content_id,status,expiry_date,created_by,created_on FROM sponsor_article ORDER BY display_order ASC")->result();
	}
	
	public function article_title($contentid){
		$result =  $this->db->query("SELECT title FROM `articlemaster` WHERE content_id='".$contentid."'")->result();
		return strip_tags($result[0]->title);
	}
	public function update_expirydate($sid,$expiry_date){
		return $this->live_db->query("UPDATE sponsor_article SET expiry_date='".$expiry_date."' WHERE sid='".$sid."'");
	}
	public function delete_sponsorarticle($sid){
		return $this->live_db->query("DELETE FROM sponsor_article WHERE sid='".$sid."'");
	}
	public function publish_articles($data){
		for($i=0;$i<count($data);$i++):
			$this->live_db->query("UPDATE sponsor_article SET status=1,display_order='".$data[$i]['order']."' WHERE sid='".$data[$i]['sid']."'");
		endfor;
		return 1;
	}
	
	public function get_sections($type,$id){
		if($type==1){
			return $this->db->query("SELECT `Section_id`, `Sectionname`, `DisplayOrder`,`Section_landing`, `IsSeperateWebsite`,`URLSectionStructure`,AuthorID FROM `sectionmaster` WHERE `Status` =  1  AND `section_allowed_for_hosting` = 1 AND `ParentSectionID` IS NULL AND `ExternalLinkURL` = '' ORDER BY `DisplayOrder` ASC")->result();
		}
		if($type==2){
			return $this->db->query("SELECT `Section_id`, `Sectionname`, `DisplayOrder`,`Section_landing`, `IsSeperateWebsite`,`URLSectionStructure`,AuthorID FROM `sectionmaster` WHERE `Status` =  1  AND `section_allowed_for_hosting` = 1 AND `ParentSectionID` ='".$id."' AND `ExternalLinkURL` = '' ORDER BY `DisplayOrder` ASC")->result();
		}
	}
}
?> 