<?php
class tagmaster extends CI_Controller{
	
	public function __construct(){
		parent::__construct();
	}
	public function index(){
		$data['title']='Tagmaster';
		$data['template']='tag_master';
		$this->load->view('admin_template',$data);
	}
	public function tags(){
		$this->load->database();
		$DetailsIntoJson=[];
		$Details['records']=[];
		$PerPage=$this->input->post("per_page");
		$SearchTerm=$this->input->post("search_term");
		$Limit=$this->input->post("limit");
		$Limit=($Limit=='' ||$Limit==0)?0:$Limit;
		if($SearchTerm==''){
			$TotalRows=$this->db->query("SELECT tag_id FROM tag_master WHERE status=1")->num_rows();
		}else{
			$TotalRows=$this->db->query("SELECT tag_id FROM tag_master WHERE tag_name LIKE '%".$SearchTerm."%'  AND status=1")->num_rows();
		}
		
		$_GET['per_page']=$Limit;
		$config['base_url']="";
		$config['total_rows']=$TotalRows;
		$config['per_page']=$PerPage;
		$config['num_links']=5;
		$config['page_query_string']=TRUE;
		$config['reuse_query_string']=TRUE;
		$config['use_page_numbers']=TRUE;
		$config['anchor_class'] = 'class="pager_num"';
		$this->pagination->initialize($config);
		if($SearchTerm==''){
		$Data=$this->db->query("SELECT tag_id,tag_name,meta_name,meta_description FROM  tag_master WHERE status=1 ORDER BY tag_id DESC LIMIT ".$Limit.", ".$PerPage."")->result();
		}else{
			
			$Data=$this->db->query("SELECT tag_id,tag_name,meta_name,meta_description FROM  tag_master WHERE tag_name LIKE '%".$SearchTerm."%'  AND status=1 ORDER BY tag_id DESC LIMIT ".$Limit.", ".$PerPage."")->result();
		}
		foreach($Data as $DataValue):
			$DetailsIntoJson['tag_id']=$DataValue->tag_id;
			$DetailsIntoJson['tag_name']=$DataValue->tag_name;
			$DetailsIntoJson['meta_name']=$DataValue->meta_name;
			$DetailsIntoJson['meta_description']=$DataValue->meta_description;
			$Details['records'][]=$DetailsIntoJson;
		endforeach;
		$Details['pagination']=str_replace('<a','<a pagination ',$this->pagination->create_links());
		echo json_encode($Details);
	}
	
	public function update_tags(){
	
		$MetaTitle=$this->input->post('meta_name');
		$MetaDescription=$this->input->post('meta_description');
		$TagID=$this->input->post('tag_id');
		$this->db->where('tag_id',$TagID);
		echo $this->db->update('tag_master',['meta_name'=>$MetaTitle,'meta_description'=>$MetaDescription]);
	}

} 
?>