<?php
class leadcontent extends CI_Controller{
	
	public function __construct(){
		parent::__construct();
		$CI = &get_instance();
		$this->live_db = $CI->load->database('live_db' , TRUE);
		
	}
	
	public function index(){
		$data['title'] = 'Lead Content Master';
		$data['template'] = 'leadcontent_view';
		$data['data'] = $this->live_db->query("SELECT lead_id ,title,description,result,imagepath,created_by,modified_by, DATE_FORMAT(modified_on, '%M %D %Y %l:%i:%s %p') as curr_date , status FROM leadcontent_master ORDER BY lead_id DESC")->result();
		$this->load->model('admin/common_model');
		$data['section_mapping'] = $this->common_model->multiple_section_mapping();
		$this->load->view('admin_template' , $data);
	}
	
	public function add_news(){
		$response =[];
		$response['return'] = 0;
		$response['error'] = 'no inputs found';
		if(count($_POST)==3 && count($_FILES)==1){
			$config['upload_path'] = destination_base_path.'images/leadcontent/';
			$config['allowed_types'] = 'gif|jpg|png';
			$this->load->library('upload');
			$this->upload->initialize($config);
			if(!$this->upload->do_upload('image')){
                $error = $this->upload->display_errors();
				$response['error'] = strip_tags($error);
            }else{
                $result = $this->upload->data();
				$filename =  $result['file_name'];
				$response['imagepath'] = image_url.'leadcontent/'.$filename;
				$date = date('Y-m-d H:i:s');
				$data = ['title' => $this->input->post('title') , 'description'=> $this->input->post('description') , 'result' => $this->input->post('result'), 'imagepath'=> $filename , 'created_by'=> USERID , 'modified_by'=> USERID , 'modified_on'=> $date];
				$return = $this->live_db->insert('leadcontent_master' , $data);
				if($return==1){
					$response['lead_id'] = $this->live_db->insert_id();
					$response['return'] = 1;
					$response['error'] =null;
				}else{
					$response['error'] = 'Failed to add news.please try again';	
				}
            }
		}
		echo json_encode($response);
	}
	
	public function get_news(){
		$sectionId = $this->input->post('sectionid');
		$template ='';
		$data = $this->live_db->query("SELECT a.lead_id ,a.title,a.status,b.mid, DATE_FORMAT(b.modified_on, '%M %D %Y %l:%i:%s %p') as curr_date,b.order_id,c.Sectionname FROM leadcontent_master as a INNER JOIN leadcontent_mapping as b ON a.lead_id=b.lead_id RIGHT JOIN sectionmaster as c ON b.section_id=c.Section_id WHERE b.section_id='".$sectionId."' ORDER BY b.order_id ASC");
		if($data->num_rows() > 0){
			foreach($data->result() as $result){
				$template .='<tr mid="'.$result->mid.'" lead="'.$result->lead_id.'" order="'.$result->order_id.'">';
				$template .='<td>'.$result->mid.'</td>';
				$template .='<td>'.$result->Sectionname.'</td>';
				if($result->status){
					$template .='<td>'.$result->title.' <span style="color:green;">(active)</span></td>';
				}else{
					$template .='<td>'.$result->title.' <span style="color:red;">(inactive)</span></td>';
				}
				$template .='<td class="sort-ui">'.$result->order_id.'</td>';
				$template .='<td>'.$result->curr_date.'</td>';
				$template .='<td><button style="background: #807f7f !important;" class="btn btn-primary btn-custom" onclick="delete_mapping('.$result->mid.')"><i class="fa fa-trash-o" aria-hidden="true"></i></button></td>';
				$template .='</tr>';
			}
		}
		echo $template;
	}
	
	public function delete_mapping(){
		$this->live_db->where('mid' ,$this->input->post('mid'));
		echo $this->live_db->delete('leadcontent_mapping');
	}
	
	public function publish(){
		$data = $this->input->post('data');
		$type = $this->input->post('type');
		if(count($data) > 0){
			$date = date('Y-m-d H:i:s');
			for($i=0;$i<count($data);$i++){
				$update =array();
				$update['order_id'] = $data[$i]['order'];
				if($type==1){
					$update['modified_on'] = $date ;	
				}
				$this->live_db->where('mid' ,$data[$i]['mid']);
				$this->live_db->update('leadcontent_mapping',$update); 
			}
			echo 1;
		}else{
			echo 0;
		}
	}
	
	public function mappinglist(){
		$sectionid = $this->input->post('sectionid');
		$template ='<input type="hidden" id="hidden_id" value="'.$sectionid.'">';
		$template .='<table class="table table-bordered dy">';
		$template .='<thead>';
		$template .='<tr><th>Id</th><th>Title</th><th>Action</th></tr>';
		$template .='</thead><tbody>';
		$data = $this->live_db->query("SELECT lead_id , title FROM `leadcontent_master` WHERE lead_id NOT IN(SELECT lead_id FROM leadcontent_mapping WHERE section_id='".$sectionid."') AND status=1")->result();
		foreach($data as $news){
			$template .='<tr>';
			$template .='<td>'.$news->lead_id.'</td>';
			$template .='<td>'.$news->title.'</td>';
			$template .='<td><input class="ckeck_sum" type="checkbox" value="'.$news->lead_id.'"></td>';
			$template .='</tr>';
			
		}
		$template .='</tbody></table>';
		echo $template;
	}
	
	public function update_maplist(){
		$orderid = 0;
		$id = $this->input->post('id');
		$sectionid = $this->input->post('sectionid');
		$lastid = $this->live_db->query("SELECT MAX(order_id) as s FROM leadcontent_mapping WHERE section_id='".$sectionid."'")->result();
		if(count($lastid) > 0){
			if($lastid[0]->s==null){
				$orderid = 0;	
			}else{
				$orderid = $lastid[0]->s;
			}
			
		}
		
		$date = date('Y-m-d H:i:s');
		for($i=0;$i<count($id);$i++){
			$orderid = $orderid + 1;
			$data = array('lead_id'=>$id[$i] ,'section_id' => $sectionid ,'order_id'=> $orderid  ,'created_by'=>USERID , 'modified_on'=>$date);
			$this->live_db->insert('leadcontent_mapping' , $data);
		}
		echo 1;
	}
	
	public function delete_leadnews(){
		$id = $this->input->post('lead_id');
		$this->live_db->where('lead_id' ,$id);
		echo $this->live_db->update('leadcontent_master' , ['status' =>0]);
	}
	
	public function edit_leadnews(){
		$lead = $this->input->post('lead_id');
		$result = $this->live_db->query("SELECT title,description,result,imagepath FROM leadcontent_master WHERE lead_id='".$lead."'")->result();
		$response =[];
		$response['title'] = $result[0]->title;
		$response['description'] = $result[0]->description;
		$response['result'] = $result[0]->result;
		$response['imagepath'] = image_url.'images/leadcontent/'.$result[0]->imagepath;
		echo json_encode($response);
	}
	
	public function update_news(){
		$response =[];
		$response['return'] = 0;
		$date = date('Y-m-d H:i:s');
		$response['1'] = $_FILES['image']['name'];
			$data =array();
			$data['title'] = $this->input->post('title');
			$data['description'] = $this->input->post('description');
			$data['result'] = $this->input->post('result');
			$data['modified_by'] = USERID;
			$data['modified_on'] = $date;
			if($_FILES['image']['name']!=''){
				$config['upload_path'] = destination_base_path.'images/leadcontent/';
				$config['allowed_types'] = 'gif|jpg|png';
				$this->load->library('upload');
				$this->upload->initialize($config);
				$this->upload->do_upload('image');
				$result = $this->upload->data();
				$filename =  $result['file_name'];
				$data['imagepath'] = $filename;
			}
			$this->live_db->where('lead_id' ,$this->input->post('lead'));
			$response['return'] = $this->live_db->update('leadcontent_master' ,$data );

		echo json_encode($response);
	}
}
?> 