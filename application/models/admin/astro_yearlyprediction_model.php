<?php 
class astro_yearlyPrediction_model extends CI_Model
{

	public function get_astro_yearlypredictions()  
	{	
		$class_obj = new yearly_predictions;
		return $class_obj->get_yearlyPredictions();
	}
	
	public function get_raasi_list()  
	{	
		$this->db->reconnect();
		$result =  $this->db->query('CALL get_astro_Ids()')->result_array();
		return $result;
	}
	
	public function get_yearly_predictions($prediction_id = '')  
	{	
		$this->db->reconnect();
		$result =  $this->db->query("CALL get_astro_yearlyPredictionById('".$prediction_id."')")->row_array();
		return $result;
	}
	
	public function manipulate($user_id)
	{
		$class_obj = new manipulate_yearly_predictions;
		return $class_obj->insert_update($user_id);
	}
	
	public function check_alreadyExists()
	{
		$raasi_id = trim($this->input->post('raasi_id'));
		$prediction_id = trim($this->input->post('prediction_id'));
		$date=$this->input->post('schd_date');
		$date_format = date('Y-m-d',strtotime($date));
		//error_log("CALL check_astroYearlyPrediction_exists('".$raasi_id."', '".$date_format."', '".$prediction_id."')");
		$check_data = $this->db->query("CALL check_astroYearlyPrediction_exists('".$raasi_id."', '".$date_format."', '".$prediction_id."')");
		return $check_data->num_rows();
	}
	
}


class yearly_predictions extends astro_yearlyPrediction_model
{
	public function get_yearlyPredictions()
	{
		extract($_POST);
		
		$Field = $order[0]['column'];
		$order = $order[0]['dir'];

		switch ($Field)
			{
			case 0:
				$order_field = 'prediction_date';
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
			$order_field = 'prediction_date';
			}
		
		$this->db->reconnect();
		$Total_rows = $this->db->query('CALL get_astro_YearlyPrediction("","","","")')->num_rows();
		
		$this->db->reconnect();
		
		if($from_date != '')  {
		$check_in_date 	= new DateTime($from_date);
		$from_date = $check_in_date->format('Y-m-d');
		}
		
		if($to_date != '')  {
		$check_out_date = new DateTime($to_date);
		$to_date = $check_out_date->format('Y-m-d');
		}
		
		$yearlyPrediction_values =  $this->db->query('CALL get_astro_YearlyPrediction(" ORDER BY '.$order_field.' '.$order.' LIMIT '.$start.', '.$length.'","'.$from_date.'","'.$to_date.'","'.$rasi_id.'")')->result_array();

		$this->db->reconnect();

		$recordsFiltered =  $this->db->query('CALL get_astro_YearlyPrediction("","'.$from_date.'","'.$to_date.'","'.$rasi_id.'")')->num_rows();
		$data['draw'] = $draw;
		$data["recordsTotal"] = $Total_rows;
  		$data["recordsFiltered"] = $recordsFiltered ;
		$data['data'] = array();
		$Count = 0;
		
		$Menu_id = get_menu_details_by_menu_name('Yearly Predictions');
		
		foreach($yearlyPrediction_values as $yearly_prediction) {
			
			$subdata = array();
		
			$subdata[] = date("Y",strtotime($yearly_prediction['prediction_date']));
			$subdata[] = $yearly_prediction['Sectionname'];
			$subdata[] = $yearly_prediction['Username'];
			$subdata[] =  date("d-m-Y H:i:s",strtotime($yearly_prediction['modified_on']));
		
			
			$set_rights = "";
			
			if(defined("USERACCESS_EDIT".$Menu_id) && constant("USERACCESS_EDIT".$Menu_id) == '1'){
				$set_rights .= '<div class="buttonHolder"><a class="button heart tooltip-3"  href="'.base_url(folder_name).'/astro_yearly_prediction/update_yearly_predictions/'.urlencode(base64_encode($yearly_prediction['prediction_id'])).'" data-toggle="tooltip" title="Edit"> <i class="fa fa-pencil" ></i> </a>';
			} else { $set_rights.="";			}
			
			if(defined("USERACCESS_DELETE".$Menu_id) && constant("USERACCESS_DELETE".$Menu_id) == '1')			{
			//$set_rights .= '<a class="button heart tooltip-3" href="#" data-toggle="tooltip" onclick="delete_yearlyPrediction('.$yearly_prediction['prediction_id'].')" title="Move to Trash"  id=""> <i class="fa fa-trash-o"></i> </a></div>'; 
			$set_rights.="";
			}else {	$set_rights.=""; }
	   
	   		
			$subdata[] = $set_rights;
			$data['data'][$Count] = $subdata;
			$Count++;
		}
		
		
		echo json_encode($data);
		exit;
		
	}
}

class manipulate_yearly_predictions extends astro_yearlyPrediction_model 
{
	public function insert_update($user_id)
	{
		extract($_POST);
		
		if($prediction_date != '')  {
			$Date = strtotime($prediction_date);
			$Date = date('Y-m-d', $Date);
		}
		
		
		if($hidden_id == "")
		{
			$this->db->trans_begin();
			$this->db->reconnect();				
			
			$this->db->query("CALL astro_yearlyPrediction_insert('".$raasi_name."', '".$Date."', '".$txtPredictionDetails."','".$user_id."','".date('Y-m-d H:i:s')."','".$user_id."','".date('Y-m-d H:i:s')."')");
			
			if($this->db->trans_status() == FALSE)
			{
				$this->db->trans_rollback();
				$this->session->set_flashdata('error', "Problem while inserting. Please try again");
				redirect(folder_name.'/astro_yearly_prediction');
			}
			else
			{
				$this->db->trans_commit();
				$this->session->set_flashdata('success', 'Inserted Successfully');
				redirect(folder_name.'/astro_yearly_prediction');
			}
			
		}
		else
		{
			$this->db->trans_begin();
			$this->db->reconnect();
		
			$this->db->query("CALL astro_yearlyPrediction_update('".$raasi_name."', '".$Date."','".$txtPredictionDetails."','".$user_id."','".date('Y-m-d H:i:s')."','".$user_id."','".date('Y-m-d H:i:s')."', '".$hidden_id."')");
			
			if($this->db->trans_status() == FALSE)
			{
				$this->db->trans_rollback();
				$this->session->set_flashdata('error', "Problem while updating. Please try again");
				redirect(folder_name.'/astro_yearly_prediction');
			}
			else
			{
				$this->db->trans_commit();
				$this->session->set_flashdata('success', 'Updated Successfully');
				redirect(folder_name.'/astro_yearly_prediction');
			}
		
		}
	
	}


}

?>