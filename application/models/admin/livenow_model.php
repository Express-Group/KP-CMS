<?php
class livenow_model extends CI_Model{

	public function __construct(){
		parent::__construct();
		$this->load->database();
	}
	public function GetLivenowContent($status=0,$title=0,$content=0,$lid=0,$article_id=0){
		if($status==1){
			$Result=$this->db->query("CALL get_live_now_content('".$status."','".$data."',' ',' ',' ')")->result();
		}else if($status==2){
			$Result=$this->db->query("CALL get_live_now_content('".$status."','".$title."','".$content."',' ','".$article_id."')")->result();
		}else if($status==3){
			$Result=$this->db->query("CALL get_live_now_content('".$status."','".$title."','".$content."',' ',' ')")->result();
		}else if($status==4){
			$Result=$this->db->query("CALL get_live_now_content('".$status."','".$title."','".$content."','".$lid."','".$article_id."')")->result();
		}else if($status==5){
			$Result=$this->db->query("CALL get_live_now_content('".$status."','".$title."','".$content."','".$lid."',' ')")->result();
		}
		return $Result;
	}
	
	public function GetArticleData($SectionId,$data=''){
		$query ='';
		if($data!=''){
				if(is_numeric($data)==true){
					$query .=" AND content_id='".trim($data)."'";
				}else{
					$query .=" AND title LIKE '%".trim($data)."%'";
				}
		}
		$this->live_db = $this->load->database('live_db', TRUE);
		return $this->live_db->query("SELECT content_id,title FROM article where section_id='".$SectionId."' ".$query ." ORDER BY content_id DESC")->result();
	}
	
	public function add_details($data){
		return $this->db->insert('livenowmaster',$data);
	}
	
	public function edit_details($article_id){
		$Query=$this->db->query("SELECT title,content,lid FROM livenowmaster WHERE status='0' AND article_id='".$article_id."' ORDER BY lid DESC");
		if($Query->num_rows()==0){
			return 0;
		}else{
			return $Query->result();
		}
	}
	
	public function UpdateLiveNowContent($data,$lid){
		$this->db->where('lid',$lid);
		return $this->db->update('livenowmaster',$data);
	
	}
	
	public function DeleteLiveNowContent($lid){
		$this->db->where('lid',$lid);
		return $this->db->update('livenowmaster',array('status'=>'1'));
	}
	
}
?>