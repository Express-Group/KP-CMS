<?php
Class scrolling_data extends CI_Model{

	public function __construct(){
		parent::__construct();
		$this->load->database();
	}
	
	

	public function fetch_scrolling_data($type,$section_id){
		$qry ='';
		if($section_id !=''){ $qry = "FIND_IN_SET('$section_id', section_id) AND" ; }		
		$content=$this->db->query("SELECT sid,content,created_on FROM scrolling_newsmaster WHERE  ".$qry." status=1 ORDER BY created_on DESC");
		if($type==1){
			return $content->num_rows();
		}else{
			return $content->result();
		}
	}

	
	

	public function save_scrolling_data($news,$section_id,$section_name){
		return $this->db->insert('scrolling_newsmaster',['content'=>$news, 'section_id' => $section_id,'section_name' => $section_name, 'status'=>1]);
	}
	
	

	public function save_edit_scrolling_data($news,$sid,$section_id,$section_name){
		$this->db->where('sid',$sid);
		return $this->db->update('scrolling_newsmaster',['content'=>$news, 'section_id' => $section_id,'section_name' => $section_name]);
	}
	
	public function delete_data($sid){
		$this->db->where('sid',$sid);
		return $this->db->delete('scrolling_newsmaster');
	}
	
	

	public function fetch_edit_news_data($sid){
		$content=$this->db->query("SELECT sid,content,section_id FROM scrolling_newsmaster WHERE sid=$sid ORDER BY created_on ASC");
		return $content->result();		
	}

}
?>