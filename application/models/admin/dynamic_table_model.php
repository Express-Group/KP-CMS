<?php
class dynamic_table_model extends CI_model{

	public function __construct(){
		parent::__construct();
		$this->load->database();
	}
	
	public function GetTable(){
		$Data=$this->db->query("CALL gettablemaster()")->result();
		return $Data;
	}
	
	public function add_table($tablename,$total){
		$this->db->insert('tablemaster',array('table_name'=>$tablename,'total'=>$total));
		return $this->db->insert_id();
	}
	public function add_parameter_details($Data,$tid,$total){
		$this->db->where('tid',$tid);
		return $this->db->update('tablemaster',array('table_properties'=>$Data,"total"=>$total));
	}
	public function preview_data($tid){
		return $this->db->query("SELECT table_name,table_properties,total FROM tablemaster WHERE tid='".$tid."' AND status='0'")->result();
	}
	
	public function table_delete($tid){
		$this->db->where('tid',$tid);
		return $this->db->delete('tablemaster');
	}
	
	public function tablename($tid,$tablename){
		$this->db->where('tid',$tid);
		return $this->db->update('tablemaster',array('table_name'=>$tablename));
	}
}
?>