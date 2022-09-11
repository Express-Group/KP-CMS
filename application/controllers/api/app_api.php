<?php
error_reporting(E_ALL);
class app_api extends CI_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->database();
	}
	
	public function index(){
		
	}
	public function verfiy_user(){
		$username = trim($this->input->post('user'));
		$password = trim($this->input->post('pass'));
		if($username!='' &&  $password!=''){
			$userdata = $this->checkuser($username,$password);
			if($userdata['status']==0){
				echo json_encode(['type'=>0]);
			}elseif($userdata['status']==1){
				$details = $userdata['data'];
				if($details[0]->status==1){
					//$this->session->userid = $details[0]->User_id;
					//$this->session->username = $details[0]->Username;
					//$this->session->firstname = $details[0]->Firstname;
					//$this->session->lastname = $details[0]->Lastname;
					echo json_encode(['type'=>1,'userid'=>$details[0]->User_id,'username'=>$details[0]->Username,'firstname'=>$details[0]->Firstname,'lastname'=>$details[0]->Lastname]);
				}else{
					echo json_encode(['type'=>2]);
				}
			}
		}else{
			echo json_encode(['type'=>0]);
		}
	}
	
	public function checkuser($username,$password){
		$pass = hash('sha512', $password);
		$userdetails = $this->db->query("SELECT User_id, Username, Firstname, Lastname, status FROM usermaster WHERE (Username='".$username."' OR Employeecode='".$username."') AND Password='".$pass."'");
		if($userdetails->num_rows()==0){
			return ['status'=>0];
		}else{
			return ['status'=>1,'data'=>$userdetails->result()];
		}
	}
}
?> 