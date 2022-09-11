<?php
/**
 * astro Image Controller Class
 *
 * @package	NewIndianExpress
 * @category	News
 * @author	IE Team
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');
class Astro_image extends CI_Controller

{
	public function __construct()

	{
		parent::__construct();
		$this->load->model('admin/astro_image_model');
		$this->load->model('admin/image_model');
	}
	public function image_upload()

	{
		$image_upload_object = new Astro_imagesub_class;
		$image_upload_object->image_upload();
	}
	public function search_image_library()

	{
		$create_astro_object = new Astro_imagesub_class;
		$create_astro_object->search_image_library();
	}
	
	public function search_image_library_scroll()

	{
		$create_astro_object = new Astro_imagesub_class;
		$create_astro_object->search_image_library_scroll();
	}
	
	public function Insert_temp_from_image_library()

	{
		$class = new Insert_temp();
		$class->Insert_temp_from_image_library();
	}
	public function delete_temp_image() 
	
	{
			$this->image_model->delete_temp_image('astro');
	}
	public function get_image_library_scroll() {
		
		$Section = $this->session->set_userdata('image_section','');
		$Caption =	$this->session->set_userdata('image_caption','');
		
		$data['pages']				= $this->uri->segment('4');
		$data['image_library'] 		= $this->astro_image_model->get_image_library_scroll($data['pages']);
		$data['nextpages']			=  $this->uri->segment('4')+1;
		
		
		echo $this->load->view('admin/image_scroll', $data);
		
	}
}
class Astro_imagesub_class extends Astro_image

{
	public function image_upload()

	{
			extract($_POST);
			$result = array();
			if($popuptype == 'bodytext') {
				
				//$result['image'] = 'data:' . $_FILES['imagelibrary']['type'] . ';base64,' . base64_encode(file_get_contents($_FILES['imagelibrary']['tmp_name']));
				
				$oldget =  getcwd();
				chdir(source_base_path.ckeditor_astroimage_path);
				
				$config = array(
					'upload_path' 		=> getcwd(),
					'allowed_types' 	=> "gif|jpg|png|jpeg",
					'encrypt_name' 		=> false,
					'overwrite'			=> false
				);
				
				chdir($oldget);
				$this->upload->initialize($config);
				$result_data = array();
				if (!$this->upload->do_upload('imagelibrary')) {
					$error = array(
						'error' => $this->upload->display_errors()
					);
					$result_data['message'] = $error['error'];
					$result_data['status'] 	= 0;
					
				} else {
					$data = array(
						'upload_data'		=> $this->upload->data()
					);
					
					ImageJPEG(ImageCreateFromString(file_get_contents($data['upload_data']['full_path'])),$data['upload_data']['full_path'], 45);
					
			/*	$this->load->library('image_lib');
				$config1['source_image'] = $data['upload_data']['full_path'];
				$config1['quality'] = 90;

				$this->image_lib->clear();
				$this->image_lib->initialize($config1);
				$this->image_lib->resize(); */
					
				$result['image']  = image_url.ckeditor_astroimage_path.'/'.$data['upload_data']['file_name'];
					
				}
				
				
			} else {
					
				$oldget =  getcwd();
				chdir(source_base_path.astrology_temp_image_path);
					
				$config = array(
					'upload_path' 		=> getcwd(),
					'allowed_types' 	=> "gif|jpg|png|jpeg",
					'encrypt_name' 		=> TRUE
				);
				
				chdir($oldget);
				$this->upload->initialize($config);
				$result_data = array();
				if (!$this->upload->do_upload('imagelibrary'))
				{
					$error = array(
						'error' => $this->upload->display_errors()
					);
					
					
					
					$result_data['message'] = $error['error'];
					$result_data['status'] 	= 0;
				}
				else
				{
					$data = array(
						'upload_data'		=> $this->upload->data()
					);
					
				ImageJPEG(ImageCreateFromString(file_get_contents($data['upload_data']['full_path'])),$data['upload_data']['full_path'], 45);
					
		/*		$this->load->library('image_lib');
				$config1['source_image'] = ;
				$config['width']	= $_FILES['imagelibrary']['width'];
				$config['height']	= $_FILES['imagelibrary']['height'];
				$config1['quality'] = 65;

				$this->image_lib->clear();
				$this->image_lib->initialize($config1);
				$this->image_lib->resize(); */
					
						$type 				= "Astrology";
						$imagefile 			= $_FILES['imagelibrary']['tmp_name'];
						$caption_array 		= explode('.', $data['upload_data']['orig_name']);
						$caption 			= $caption_array[0];
					
						$result 	= $this->astro_image_model->insert_image_temp($imagefile, $caption, $caption, $caption, $data['upload_data']['file_size'], $data['upload_data']['file_name'], $data['upload_data']['file_type'], $data['upload_data']['image_width'], $data['upload_data']['image_height'], USERID, $type);
						
					
				}
				
			
		}
		
				echo json_encode($result);
				exit;
		
	}
	public function search_image_library()

	{
		$this->astro_image_model->search_image_library();
	}
	
		public function search_image_library_scroll()

	{
		$data['pages']				= $this->uri->segment('4');
		$data['image_library'] 		= $this->astro_image_model->search_image_library_scroll($data['pages']);
		$data['nextpages']			=  $this->uri->segment('4')+1;
		
		
		echo $this->load->view('admin/image_scroll', $data);
	}
	
}
class Insert_temp extends Astro_image

{
	public function Insert_temp_from_image_library()

	{
		$this->astro_image_model->Insert_temp_from_image_library();
	}
}
/* End of file Astro_image.php */
/* Location: ./application/controllers/Astro_image.php */
