<?php
class version_revert extends CI_Model{

	public function __construct(){
		parent::__construct();
		$this->load->database();
		$CI 			= &get_instance();
		$this->live_db 	= $this->load->database('live_db', TRUE);
	}
	
	public function render_version($query,$type=1,$limit=''){
		
		if($type==1){
			return $this->db->query("SELECT vid FROM version_master WHERE status=0 ".$query)->num_rows();
		}else{
			return$this->db->query("SELECT vid,version_name,section_name,page_type,created_on,version_id FROM version_master WHERE status=0 ".$query." ORDER BY vid DESC LIMIT ".$limit.", 10")->result();
		}
	}
	
	public function render_menu(){
		return $this->db->query("SELECT Section_id ,Sectionname FROM sectionmaster WHERE Status=1")->result();
	}
	
	public function deleteversion($version_id,$role){
		if($role=='H'){
			$this->db->where('vid', $version_id);
			return $this->db-> delete('version_master');
		}else{
			$this->db->where(['vid'=>$version_id]);
			return $this->db->update('version_master',array('status'=>1));
		}
	}
	
	public function previewversion($version_id){
		$this->db->select('version_xml');
		$this->db->from('version_master');
		$this->db->where('vid', $version_id);
		$Result=$this->db->get();
		$Result=$Result->result();
		return htmlentities($Result[0]->version_xml, ENT_QUOTES);
		
	}
	
	public function makeaction($versionID,$type){
		$response='';
		
		$VersionDetails=$this->db->query("SELECT version_xml, header_advscript, common_header, common_rightpanel, common_footer, published_version_id, menu_id, page_type, templateid,version_id FROM version_master WHERE vid='".$versionID."'")->result();
		
		if($type=='reset'){
			$Update=['Template_XML'=>$VersionDetails[0]->version_xml,'Header_Adscript'=>$VersionDetails[0]->header_advscript,'common_header'=>$VersionDetails[0]->common_header,'common_rightpanel'=>$VersionDetails[0]->common_rightpanel,'common_footer'=>$VersionDetails[0]->common_footer,'Version_Status'=>1];
			$this->db->where('Version_Id',$VersionDetails[0]->version_id);
			$response=$this->db->update('page_template_versions',$Update);
			
		}
		if($type=='publish'){
			$Update=['Template_XML'=>$VersionDetails[0]->version_xml,'Header_Adscript'=>$VersionDetails[0]->header_advscript,'common_header'=>$VersionDetails[0]->common_header,'common_rightpanel'=>$VersionDetails[0]->common_rightpanel,'common_footer'=>$VersionDetails[0]->common_footer,'Version_Status'=>1];
			$this->db->where('Version_Id',$VersionDetails[0]->version_id);
			$this->db->update('page_template_versions',$Update);
			 $GetPageMasterID=$this->db->query("SELECT Page_master_id FROM page_template_versions WHERE Version_Id='".$VersionDetails[0]->version_id."'")->result();
			
			$PagemasterUpdate=['published_templatexml'=>$VersionDetails[0]->version_xml,'templateid'=>$VersionDetails[0]->templateid,'Header_Adscript'=>$VersionDetails[0]->header_advscript,'common_header'=>$VersionDetails[0]->common_header,'common_rightpanel'=>$VersionDetails[0]->common_rightpanel,'common_footer'=>$VersionDetails[0]->common_footer,'Published_Version_Id'=>$VersionDetails[0]->version_id,'page_status'=>1];
			
			$this->db->trans_begin();
			
			$this->db->where(['id'=>$GetPageMasterID[0]->Page_master_id,'menuid'=>$VersionDetails[0]->menu_id]);
			$this->db->update('page_master',$PagemasterUpdate);
			
			if ($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				$response=0;
			}else{
				$this->db->trans_commit();
				$this->live_db->where(['id'=>$GetPageMasterID[0]->Page_master_id,'menuid'=>$VersionDetails[0]->menu_id]);
				$response=$this->live_db->update('page_master',$PagemasterUpdate);
			}
			
			
			
		}
		
		return $response;
	}
}
?>