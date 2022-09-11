<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Astro_daily_prediction extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('admin/astro_dailyPrediction_model');
	}
	
	public function index()
	{
		
		$data['Menu_id'] = get_menu_details_by_menu_name('Daily Predictions');
		if(defined("USERACCESS_VIEW".$data['Menu_id']) && constant("USERACCESS_VIEW". $data['Menu_id']) == '1') 
		{
			
			$data['title']		= 'Astrology - Daily Predictions';
			$data['template'] 	= 'astro_daily_predictions';
			$data['raasi_lists'] = $this->get_raasi_list();
			//$data['results'] = 
			$this->load->view('admin_template',$data);
			
		}
		else 
		{ 
			redirect(folder_name.'/common/access_permission/astro_daily_prediction');		
		}
	}
	
	public function create_page()
	{
		$data['Menu_id'] = get_menu_details_by_menu_name('Daily Predictions');	
		if(defined("USERACCESS_ADD".$data['Menu_id']) && constant("USERACCESS_ADD". $data['Menu_id']) == '1') 
		{
			$data['title']		= 'Create Daily Predictions';
			$data['template'] 	= 'astro_daily_prediction_form';
			$data['raasi_lists'] = $this->get_raasi_list();
			$this->load->view('admin_template',$data);
		}
		else
		{
			redirect(folder_name.'/common/access_permission/add_daily_predictions');		
		}
	}
	
	public function update_daily_predictions()
	{
		$data['Menu_id'] = get_menu_details_by_menu_name('Daily Predictions');
		if(defined("USERACCESS_EDIT".$data['Menu_id']) && constant("USERACCESS_EDIT".$data['Menu_id']) == '1') 
		{			
			$raasi_id = base64_decode(urldecode($this->uri->segment(4))); 
			$data['raasi_lists'] = $this->get_raasi_list();
			$data['fetch_values'] = $this->astro_dailyPrediction_model->get_daily_predictions($raasi_id);
			$data['title']		= 'Edit Daily Predictions';
			$data['template'] 	= 'astro_daily_prediction_form';
			$this->load->view('admin_template',$data);
		}
		else 
		{
			redirect(folder_name.'/common/access_permission/edit_daily_predictions');		
		}
		
	}
	
	public function add_daily_predictions() //func for inerting values
	{
			$this->load->library('form_validation');
			$this->form_validation->set_rules('txtPredictionDetails','Daily Predicitons', 'trim|required');			
			$this->form_validation->set_rules('raasi_name','Raasi Name','trim|required|strip_tags|xss_clean');
			
			if($this->form_validation->run() == FALSE)
			{
				$this->create_page();
			}
			else
			{
				$this->astro_dailyPrediction_model->manipulate(USERID);
			}
	}
	
	public function get_astro_dailypredictions(){
		$this->astro_dailyPrediction_model->get_astro_dailypredictions();	
	}
	
	public function get_raasi_list(){
		return $this->astro_dailyPrediction_model->get_raasi_list();	
	}

	public function check_alreadyExists()
	{
		$rows = $this->astro_dailyPrediction_model->check_alreadyExists();
		if($rows > 0)
		{
			echo "exists";
			//return FALSE;
		}	
		else
		{
			//return TRUE;
		}
	}
}

?>