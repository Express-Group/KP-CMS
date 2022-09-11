<?php
class seoadmin extends CI_Model{

	public function __construct(){
		parent::__construct();
		$this->load->database();
	}
	
	public function GetArticleCount($Type='article'){
		if($Type=='article'){ $tbl="articlemaster";}
		if($Type=='gallery'){ $tbl="gallerymaster";}
		if($Type=='video'){ $tbl="videomaster";}
		return $this->db->query("SELECT content_id FROM ".$tbl." WHERE status='P'")->num_rows();
	}
	
	public function GetArticleContent($perpage,$page,$Type='article'){
		if($Type=='article'){ $tbl="articlemaster";}
		if($Type=='gallery'){ $tbl="gallerymaster";}
		if($Type=='video'){ $tbl="videomaster";}
		return $this->db->query("SELECT content_id,title,url,Createdon FROM ".$tbl." WHERE status='P'  ORDER BY content_id DESC LIMIT ".$page.",".$perpage." ")->result();
	}
	
	public function GetEditContent($content_id,$Type){
		if($Type=='article'){ $tbl="articlemaster";}
		if($Type=='gallery'){ $tbl="gallerymaster";}
		if($Type=='video'){ $tbl="videomaster";}
		return $this->db->query("SELECT Tags,MetaTitle,MetaDescription FROM ".$tbl." WHERE content_id='".$content_id."'")->result();
	}
	
	public function TagName($TagID){
		$data=$this->db->query("SELECT tag_name FROM tag_master WHERE tag_id='".$TagID."'")->result();
		return $data[0]->tag_name;
	}
	
	public function GetID($TagName){
		$data=$this->db->query("SELECT tag_id FROM tag_master WHERE tag_name='".$TagName."' AND status='1'");
		if($data->num_rows()==0){
			$this->db->insert('tag_master',array('tag_name'=>$TagName,'status'=>'1','created_by'=>$this->session->userdata('userID'),'modified_by'=>$this->session->userdata('userID')));
			$insert_id = $this->db->insert_id();
			return $insert_id;
			
		}else{
			$result=$data->result();
			return $result[0]->tag_id;
		}
	}
	
	public function SaveContent($ContentID,$MetaTitle,$MetaDescription,$TagID,$TagValue,$Type){
		if($Type=='article'){ $tbl="articlemaster"; $tbllive="article"; }
		if($Type=='gallery'){ $tbl="gallerymaster"; $tbllive="gallery"; }
		if($Type=='video'){ $tbl="videomaster"; $tbllive="video";  }
		
		$CI = &get_instance();
		$this->live_db = $CI->load->database('live_db', TRUE);
		$ModifiedDate=date('Y-m-d h:i:s');
		$CreatedBy=$this->session->userdata('userID');
		
		$this->db->where('content_id',$ContentID);
		$this->db->update($tbl,array('Tags'=>$TagID,'MetaTitle'=>$MetaTitle,'MetaDescription'=>$MetaDescription,'Modifiedby'=>$CreatedBy,'Modifiedon'=>$ModifiedDate)); 
		
		$this->live_db->where('content_id',$ContentID);
		return $this->live_db->update($tbllive,array('tags'=>$TagValue,'meta_Title'=>$MetaTitle,'meta_description'=>$MetaDescription)); 

	}
	public function checkID($ContentID,$Type){
		if($Type=='article'){ $tbl="articlemaster";}
		if($Type=='gallery'){ $tbl="gallerymaster";}
		if($Type=='video'){ $tbl="videomaster";}
		$Query=$this->db->query("SELECT content_id,status FROM ".$tbl." WHERE content_id='".$ContentID."'");
		if($Query->num_rows()==0){
			return 1;
		}else{
			$Data=$Query->result();
			if($Data[0]->status=='P'){
				return 0;
			}else{
				return 2;
			}
		}
	}
}
?>