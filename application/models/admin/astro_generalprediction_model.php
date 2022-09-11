<?php 
class astro_generalPrediction_model extends CI_Model
{

	public function get_astro_predictions()  
	{	
		$class_obj = new general_predictions;
		return $class_obj->get_generalPredictions();
	}
	
	public function get_raasi_list()  
	{	
		$this->db->reconnect();
		$result =  $this->db->query('CALL get_astro_Ids()')->result_array();
		//print_r($result);exit;
		return $result;
	}
	
	public function get_general_predictions($section_id = '')  
	{	
		$this->db->reconnect();
		$result =  $this->db->query("CALL get_astro_GeneralPredictionBySectionId('".$section_id."')")->row_array();
		//print_r($result);exit;
		return $result;
	}
	
	public function manipulate($user_id)
	{
		$class_obj = new manipulate_general_predictions;
		return $class_obj->insert_update($user_id);
	}
	
	public function check_alreadyExists()
	{
		$raasi_id = trim($this->input->post('raasi_id'));
		$prediction_id = trim($this->input->post('prediction_id'));
		//error_log("CALL check_astroGeneralPrediction_exists('".$raasi_id."','".$prediction_id."')");
		$check_data = $this->db->query("CALL check_astroGeneralPrediction_exists('".$raasi_id."','".$prediction_id."')");
		return $check_data->num_rows();
	}
	
}


class general_predictions extends astro_generalPrediction_model
{
	public function get_generalPredictions()
	{
		extract($_POST);
		
		$Field = $order[0]['column'];
		$order = $order[0]['dir'];

		switch ($Field)
			{
			case 0:
				$order_field = 'section_id'; 
				break;
			case 1:
				$order_field = 'Sectionname';
				break;
			case 2:
				$order_field = 'Username';
				break;
			case 3:
				$order_field = 'modified_on';
				break;
			default:
			$order_field = 'section_id';
			}
		
		$this->db->reconnect();
		$Total_rows = $this->db->query('CALL get_astro_GeneralPrediction("","","","")')->num_rows();
		
		$this->db->reconnect();
		
		if($from_date != '')  {
		$check_in_date 	= new DateTime($from_date);
		$from_date = $check_in_date->format('Y-m-d');
		}
		
		if($to_date != '')  {
		$check_out_date = new DateTime($to_date);
		$to_date = $check_out_date->format('Y-m-d');
		}
		
		$generalPrediction_values =  $this->db->query('CALL get_astro_GeneralPrediction(" ORDER BY '.$order_field.' '.$order.' LIMIT '.$start.', '.$length.'","'.$from_date.'","'.$to_date.'","'.$rasi_id.'")')->result_array();

		$this->db->reconnect();

		$recordsFiltered =  $this->db->query('CALL get_astro_GeneralPrediction("","'.$from_date.'","'.$to_date.'","'.$rasi_id.'")')->num_rows();
		$data['draw'] = $draw;
		$data["recordsTotal"] = $Total_rows;
  		$data["recordsFiltered"] = $recordsFiltered ;
		$data['data'] = array();
		$Count = 0;
		
		$Menu_id = get_menu_details_by_menu_name('General Predictions');
		
		foreach($generalPrediction_values as $general_prediction) {
			
			$subdata = array();
			$raasi_name = $general_prediction['Sectionname'];
			switch ($raasi_name)
			{
			case "மேஷம்":
				$raasi_id = 1; 
				break;
			case "ரிஷபம்":
				$raasi_id = 2;
				break;
			case "மிதுனம்":
				$raasi_id = 3;
				break;
			case "கடகம்":
				$raasi_id = 4;
				break;
			case "சிம்மம்":
				$raasi_id = 5; 
				break;
			case "கன்னி":
				$raasi_id = 6;
				break;
			case "துலாம்":
				$raasi_id = 7;
				break;
			case "விருச்சிகம்":
				$raasi_id = 8;
				break;
			case "தனுசு":
				$raasi_id = 9; 
				break;
			case "மகரம்":
				$raasi_id = 10;
				break;
			case "கும்பம்":
				$raasi_id = 11;
				break;
			case "மீனம்":
				$raasi_id = 12;
				break;
			default:
			$raasi_id = 1;
			}
	        $subdata[] = $raasi_id;
			$subdata[] = $general_prediction['Sectionname'];
			$subdata[] = $general_prediction['Username'];
			$subdata[] =  date("d-m-Y H:i:s",strtotime($general_prediction['modified_on']));
		
			
			$set_rights = "";
			
			if(defined("USERACCESS_EDIT".$Menu_id) && constant("USERACCESS_EDIT".$Menu_id) == '1'){
				$set_rights .= '<div class="buttonHolder"><a class="button heart tooltip-3"  href="'.base_url(folder_name).'/astro_general_prediction/update_general_predictions/'.urlencode(base64_encode($general_prediction['section_id'])).'" data-toggle="tooltip" title="Edit"> <i class="fa fa-pencil" ></i> </a>';
			} else { $set_rights.="";			}
			
			if(defined("USERACCESS_DELETE".$Menu_id) && constant("USERACCESS_DELETE".$Menu_id) == '1')			{
			//$set_rights .= '<a class="button heart tooltip-3" href="#" data-toggle="tooltip" onclick="delete_generalPrediction('.$general_prediction['prediction_id'].')" title="Move to Trash"  id=""> <i class="fa fa-trash-o"></i> </a></div>';
			$set_rights .= ''; 
			}else {	$set_rights.=""; }
	   
	   		
			$subdata[] = $set_rights;
			$data['data'][$Count] = $subdata;
			$Count++;
		}
		
		
		echo json_encode($data);
		exit;
		
	}
}

class manipulate_general_predictions extends astro_generalPrediction_model 
{
	public function insert_update($user_id)
	{
		extract($_POST);
		
		/*if($prediction_date != '')  {
			$Date = strtotime($prediction_date);
			$Date = date('Y-m-d', $Date);
		}*/
		
		
		if($hidden_id == "")
		{
			$this->db->trans_begin();
			$this->db->reconnect();				
			
			$this->db->query("CALL astro_generalPrediction_insert('".$raasi_name."', '".$txtPredictionDetails."','".$user_id."','".date('Y-m-d H:i:s')."','".$user_id."','".date('Y-m-d H:i:s')."')");
			
			if($this->db->trans_status() == FALSE)
			{
				$this->db->trans_rollback();
				$this->session->set_flashdata('error', "Problem while inserting. Please try again");
				redirect(folder_name.'/astro_general_prediction');
			}
			else
			{
				$this->db->trans_commit();
				$this->session->set_flashdata('success', 'Inserted Successfully');
				redirect(folder_name.'/astro_general_prediction');
			}
		}
		else
		{
			$this->db->trans_begin();
			$this->db->reconnect();
			
			$this->db->query("CALL astro_generalPrediction_update('".$raasi_name."','".$txtPredictionDetails."','".$user_id."','".date('Y-m-d H:i:s')."','".$user_id."','".date('Y-m-d H:i:s')."', '".$hidden_id."')");
						
			if($this->db->trans_status() == FALSE)
			{
				$this->db->trans_rollback();
				$this->session->set_flashdata('error', "Problem while updating. Please try again");
				redirect(folder_name.'/astro_general_prediction');
			}
			else
			{
				$this->db->trans_commit();
				$this->session->set_flashdata('success', 'Updated Successfully');
				redirect(folder_name.'/astro_general_prediction');
			}
		
		}
	
	}


}

?>