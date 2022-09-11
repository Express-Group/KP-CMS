<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Astro_yearly_prediction extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('admin/astro_yearlyPrediction_model');
		$this->load->model('admin/astro_image_model');
		$this->load->model('admin/image_model');
	}
	
	public function index()
	{
		
		$data['Menu_id'] = get_menu_details_by_menu_name('Yearly Predictions');
		if(defined("USERACCESS_VIEW".$data['Menu_id']) && constant("USERACCESS_VIEW". $data['Menu_id']) == '1') 
		{
			
			$data['title']		= 'Astrology - Yearly Predictions';
			$data['template'] 	= 'astro_yearly_predictions';
			$data['raasi_lists'] = $this->get_raasi_list();
			$this->image_model->DeleteTempAllImages(2);
			//$data['results'] = 
			$this->load->view('admin_template',$data);
			
		}
		else 
		{ 
			redirect(folder_name.'/common/access_permission/astro_yearly_prediction');		
		}
	}
	
	public function create_page()
	{
		$data['Menu_id'] = get_menu_details_by_menu_name('Yearly Predictions');	
		if(defined("USERACCESS_ADD".$data['Menu_id']) && constant("USERACCESS_ADD". $data['Menu_id']) == '1') 
		{
			$data['title']		= 'Create Yearly Predictions';
			$data['template'] 	= 'astro_yearly_prediction_form';
			$data['raasi_lists'] = $this->get_raasi_list();
			$data['image_library'] = $this->astro_image_model->get_image_library();
			$data['get_country'] 		= $this->common_model->get_country_details();
			$data['get_agency'] 		= $this->common_model->get_agency_details();
			$data['section_mapping'] 	= $this->common_model->multiple_section_mapping();
			$data['get_content_type'] 	= $this->common_model->get_content_type();
			$this->load->view('admin_template',$data);
		}
		else
		{
			redirect(folder_name.'/common/access_permission/add_yearly_predictions');		
		}
	}
	
	public function update_yearly_predictions()
	{
		$data['Menu_id'] = get_menu_details_by_menu_name('Yearly Predictions');
		if(defined("USERACCESS_EDIT".$data['Menu_id']) && constant("USERACCESS_EDIT".$data['Menu_id']) == '1') 
		{			
			$raasi_id = base64_decode(urldecode($this->uri->segment(4))); 
			$data['raasi_lists'] = $this->get_raasi_list();
			$data['fetch_values'] = $this->astro_yearlyPrediction_model->get_yearly_predictions($raasi_id);
			$data['title']		= 'Edit Yearly Predictions';
			$data['template'] 	= 'astro_yearly_prediction_form';
			$data['image_library'] = $this->astro_image_model->get_image_library();
			$data['get_country'] 		= $this->common_model->get_country_details();
			$data['get_agency'] 		= $this->common_model->get_agency_details();
			$data['section_mapping'] 	= $this->common_model->multiple_section_mapping();
			$data['get_content_type'] 	= $this->common_model->get_content_type();
			$this->load->view('admin_template',$data);
		}
		else 
		{
			redirect(folder_name.'/common/access_permission/edit_yearly_predictions');		
		}
		
	}
	
	public function add_yearly_predictions() //func for inerting values
	{
			$this->load->library('form_validation');
			$this->form_validation->set_rules('txtPredictionDetails','Yearly Predicitons', 'trim|required');			
			$this->form_validation->set_rules('raasi_name','Raasi Name','trim|required|strip_tags|xss_clean');
			
			if($this->form_validation->run() == FALSE)
			{
				$this->create_page();
			}
			else
			{
				$this->astro_yearlyPrediction_model->manipulate(USERID);
			}
	}
	
	public function get_astro_yearlypredictions(){
		$this->astro_yearlyPrediction_model->get_astro_yearlypredictions();	
	}
	
	public function get_raasi_list(){
		return $this->astro_yearlyPrediction_model->get_raasi_list();	
	}

	public function check_alreadyExists()
	{
		$rows = $this->astro_yearlyPrediction_model->check_alreadyExists();
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