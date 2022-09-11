<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class user_productivity_report extends CI_Controller 
{
	public function __construct() 
	{
		parent::__construct();
		//$this->load->model('admin/user_productivity_model');
		$this->load->helper('form');  
		$this->load->library('form_validation');
		$this->load->model('admin/user_model');
		$this->load->model('admin/user_productivity_model');
	}
	
	public function index()
	{
		$data['Menu_id'] = get_menu_details_by_menu_name('User Productivity');
		if(defined("USERACCESS_VIEW".$data['Menu_id']) && constant("USERACCESS_VIEW". $data['Menu_id']) == '1') {
			$rawdata['title']		= 'User Productivity Reports';
			$rawdata['template'] 	= 'user_productivity_report';
			$rawdata['role_name'] 	= $this->user_model->get_rolename();
			$this->load->view('admin_template',$rawdata);
		}else {
			redirect(folder_name.'/common/access_permission/user_productivity');
		}
	}
	
	public function user_report_datatable()
	{
		$this->user_productivity_model->user_report_datatable();
	}
	
	public function user_report_excel()
	{
		extract($_GET);
		$this->user_productivity_model->user_excel_report();
	}
	public function article_prod_report(){
		$fromdate = $this->input->post('fromdate');
		$enddate = $this->input->post('enddate');
		$fromdate  = date('Y-m-d' , strtotime($fromdate));
		$enddate  = date('Y-m-d' , strtotime($enddate));
		$fromdate = $fromdate.' 00:00:00';
		$enddate = $enddate.' 23:59:59';
		$this->load->database();
		$articlepublished = $this->db->query("SELECT count(content_id) as articlecount FROM articlemaster WHERE Createdon BETWEEN '".$fromdate."' AND '".$enddate."' AND status='P'")->row_array();
		$articleunpublished = $this->db->query("SELECT count(content_id) as articlecount FROM articlemaster WHERE Createdon BETWEEN '".$fromdate."' AND '".$enddate."' AND status='U'")->row_array();
		$articledraft = $this->db->query("SELECT count(content_id) as articlecount FROM articlemaster WHERE Createdon BETWEEN '".$fromdate."' AND '".$enddate."' AND status='D'")->row_array();
		
		$gallerypublished = $this->db->query("SELECT count(content_id) as articlecount FROM gallerymaster WHERE Createdon BETWEEN '".$fromdate."' AND '".$enddate."' AND status='P'")->row_array();
		$galleryunpublished = $this->db->query("SELECT count(content_id) as articlecount FROM gallerymaster WHERE Createdon BETWEEN '".$fromdate."' AND '".$enddate."' AND status='U'")->row_array();
		$gallerydraft = $this->db->query("SELECT count(content_id) as articlecount FROM gallerymaster WHERE Createdon BETWEEN '".$fromdate."' AND '".$enddate."' AND status='D'")->row_array();
		
		$videopublished = $this->db->query("SELECT count(content_id) as articlecount FROM videomaster WHERE Createdon BETWEEN '".$fromdate."' AND '".$enddate."' AND status='P'")->row_array();
		$videounpublished = $this->db->query("SELECT count(content_id) as articlecount FROM videomaster WHERE Createdon BETWEEN '".$fromdate."' AND '".$enddate."' AND status='U'")->row_array();
		$videodraft = $this->db->query("SELECT count(content_id) as articlecount FROM videomaster WHERE Createdon BETWEEN '".$fromdate."' AND '".$enddate."' AND status='D'")->row_array();
		
		$articleuser = $this->db->query("SELECT count(a.content_id) as articlecount , a.Createdby , b.Username , CONCAT(b.Firstname ,' ',b.Lastname) as fullname FROM articlemaster as a INNER JOIN usermaster as b ON a.Createdby = b.User_id WHERE a.Createdon BETWEEN '".$fromdate."' AND '".$enddate."' GROUP BY a.Createdby HAVING count(a.Createdby) > 0")->result();
		
		$galleryuser = $this->db->query("SELECT count(a.content_id) as articlecount , a.Createdby , b.Username , CONCAT(b.Firstname ,' ',b.Lastname) as fullname FROM gallerymaster as a INNER JOIN usermaster as b ON a.Createdby = b.User_id WHERE a.Createdon BETWEEN '".$fromdate."' AND '".$enddate."' GROUP BY a.Createdby HAVING count(a.Createdby) > 0")->result();
		$videouser = $this->db->query("SELECT count(a.content_id) as articlecount , a.Createdby , b.Username , CONCAT(b.Firstname ,' ',b.Lastname) as fullname FROM videomaster as a INNER JOIN usermaster as b ON a.Createdby = b.User_id WHERE a.Createdon BETWEEN '".$fromdate."' AND '".$enddate."' GROUP BY a.Createdby HAVING count(a.Createdby) > 0")->result();
		$template ='<table class="table" style="border:1px solid #eee;">';
		$template .='<thead>';
		$template .='<tr style="background:#eee;"><th>TYPE</th><th>PUBLISHED</th><th>UNPUBLISHED</th><th>DRAFT</th><th>TOTAL</th></tr>';
		$template .='</thead>';
		$template .='<tbody>';
		$template .='<tr><th>ARTICLE</th><th>'.$articlepublished['articlecount'].'</th><th>'.$articleunpublished['articlecount'].'</th><th>'.$articledraft['articlecount'].'</th><th>'.($articlepublished['articlecount'] + $articleunpublished['articlecount'] + $articledraft['articlecount']).'</th></tr>';
		$template .='<tr><th>GALLERY</th><th>'.$gallerypublished['articlecount'].'</th><th>'.$galleryunpublished['articlecount'].'</th><th>'.$gallerydraft['articlecount'].'</th><th>'.($gallerypublished['articlecount'] + $galleryunpublished['articlecount'] + $gallerydraft['articlecount']).'</th></tr>';
		$template .='<tr><th>VIDEO</th><th>'.$videopublished['articlecount'].'</th><th>'.$videounpublished['articlecount'].'</th><th>'.$videodraft['articlecount'].'</th><th>'.($videopublished['articlecount'] + $videounpublished['articlecount'] + $videodraft['articlecount']).'</th></tr>';
		$template .='</tbody>';
		$template .='</table>';
		
		$template .='<table class="table" style="border:1px solid #eee;width:50%;margin:0 auto;">';
		$template .='<thead>';
		$template .='<tr style="background:#eee;"><th>USERNAME</th><th>COUNT</th></tr>';
		$template .='</thead>';
		$template .='<tbody>';
		$template .='<tr style="background:#eee;font-weight:700;"><td colspan="2" class="text-center">ARTICLE</td></tr>';
		foreach($articleuser as $user){
			$template .='<tr>';
			$template .='<td>'.$user->fullname.'</td>';
			$template .='<td>'.$user->articlecount.'</td>';
			$template .='</tr>';
		}
		$template .='<tr style="background:#eee;font-weight:700;"><td colspan="2" class="text-center">GALLERY</td></tr>';
		foreach($galleryuser as $user){
			$template .='<tr>';
			$template .='<td>'.$user->fullname.'</td>';
			$template .='<td>'.$user->articlecount.'</td>';
			$template .='</tr>';
		}
		$template .='<tr style="background:#eee;font-weight:700;"><td colspan="2" class="text-center">VIDEO</td></tr>';
		foreach($videouser as $user){
			$template .='<tr>';
			$template .='<td>'.$user->fullname.'</td>';
			$template .='<td>'.$user->articlecount.'</td>';
			$template .='</tr>';
		}
		$template .='</tbody>';
		$template .='</table>';
		
		
		echo $template;
	}
	
}


?>