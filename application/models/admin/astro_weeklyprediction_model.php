<?php 
class astro_weeklyPrediction_model extends CI_Model
{

	public function get_astro_weeklypredictions()  
	{	
		$class_obj = new weekly_predictions;
		return $class_obj->get_weeklyPredictions();
	}
	
	public function get_raasi_list()  
	{	
		$this->db->reconnect();
		$result =  $this->db->query('CALL get_astro_Ids()')->result_array();
		return $result;
	}
	
	public function get_weekly_predictions($prediction_id = '')  
	{	
		$this->db->reconnect();
		$result =  $this->db->query("CALL get_astro_weeklyPredictionById('".$prediction_id."')")->row_array();
		return $result;
	}
	
	public function manipulate($user_id)
	{
		$class_obj = new manipulate_weekly_predictions;
		return $class_obj->insert_update($user_id);
	}
	
	public function check_alreadyExists()
	{
		$raasi_id = trim($this->input->post('raasi_id'));
		$prediction_id = trim($this->input->post('prediction_id'));
		$date=$this->input->post('schd_date');
		$post_date = date('Y-m-d',strtotime($date));
		$check_data = $this->db->query("CALL check_astroWeeklyPrediction_exists('".$raasi_id."', '".$post_date."', '".$prediction_id."')");
		return $check_data->num_rows();
	}
	
}


class weekly_predictions extends astro_weeklyPrediction_model
{
	public function get_weeklyPredictions()
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
		$Total_rows = $this->db->query('CALL get_astro_WeeklyPrediction("","","","")')->num_rows();
		
		$this->db->reconnect();
		
		if($from_date != '')  {
		$check_in_date 	= new DateTime($from_date);
		$from_date = $check_in_date->format('Y-m-d');
		}
		
		if($to_date != '')  {
		$check_out_date = new DateTime($to_date);
		$to_date = $check_out_date->format('Y-m-d');
		}
		
		$weeklyPrediction_values =  $this->db->query('CALL get_astro_WeeklyPrediction(" ORDER BY '.$order_field.' '.$order.' LIMIT '.$start.', '.$length.'","'.$from_date.'","'.$to_date.'","'.$rasi_id.'")')->result_array();

		$this->db->reconnect();

		$recordsFiltered =  $this->db->query('CALL get_astro_WeeklyPrediction("","'.$from_date.'","'.$to_date.'","'.$rasi_id.'")')->num_rows();
		$data['draw'] = $draw;
		$data["recordsTotal"] = $Total_rows;
  		$data["recordsFiltered"] = $recordsFiltered ;
		$data['data'] = array();
		$Count = 0;
		
		$Menu_id = get_menu_details_by_menu_name('Weekly Predictions');
		
		foreach($weeklyPrediction_values as $weekly_prediction) {
			
			$subdata = array();
		
			$subdata[] = date("d-m-Y",strtotime($weekly_prediction['prediction_date']));
			$subdata[] = $weekly_prediction['Sectionname'];
			$subdata[] = $weekly_prediction['Username'];
			$subdata[] =  date("d-m-Y H:i:s",strtotime($weekly_prediction['modified_on']));
		
			
			$set_rights = "";
			
			if(defined("USERACCESS_EDIT".$Menu_id) && constant("USERACCESS_EDIT".$Menu_id) == '1'){
				$set_rights .= '<div class="buttonHolder"><a class="button heart tooltip-3"  href="'.base_url(folder_name).'/astro_weekly_prediction/update_weekly_predictions/'.urlencode(base64_encode($weekly_prediction['prediction_id'])).'" data-toggle="tooltip" title="Edit"> <i class="fa fa-pencil" ></i> </a>';
			} else { $set_rights.="";			}
			
			if(defined("USERACCESS_DELETE".$Menu_id) && constant("USERACCESS_DELETE".$Menu_id) == '1')			{
		//	$set_rights .= '<a class="button heart tooltip-3" href="#" data-toggle="tooltip" onclick="delete_weeklyPrediction('.$weekly_prediction['prediction_id'].')" title="Move to Trash"  id=""> <i class="fa fa-trash-o"></i> </a></div>'; 
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

class manipulate_weekly_predictions extends astro_weeklyPrediction_model 
{
	public function delete_last($raasi_name,$dat)
    {  
	
	   $query = $this->db->query("CALL delete_last_record ('".$raasi_name."', '".$dat."','sel','rasi_weekly_predictions')")->result_array();
	
		$created_on = array();
		foreach( $query  as $content_val)
		{
			$created_on[] = $content_val['prediction_date'];
		}
		//print_r($created_on);
	   $datt = end($created_on);
		 
		 $query1 = $this->db->query("CALL delete_last_record ('".$raasi_name."', '".$datt."','del','rasi_weekly_predictions')");
		

    }
	public function insert_update($user_id)
	{
		extract($_POST);
		
		if($prediction_date != '')  {
			$Date = strtotime($prediction_date);
			$Date = date('Y-m-d', $Date);
			$To_Date = strtotime($prediction_end_date);
			$To_Date = date('Y-m-d', $To_Date);
		}
		
		
		if($hidden_id == "")
		{
			$dat =date('Y-m-d'); 
			//$this->delete_last($raasi_name,$dat);
			$this->db->trans_begin();
			$this->db->reconnect();				
			
			$this->db->query("CALL astro_weeklyPrediction_insert('".$raasi_name."', '".$Date."', '".$To_Date."', '".$txtPredictionDetails."','".$user_id."','".date('Y-m-d H:i:s')."','".$user_id."','".date('Y-m-d H:i:s')."')");
			$this->delete_last($raasi_name,$dat);
			
			if($this->db->trans_status() == FALSE)
			{
				$this->db->trans_rollback();
				$this->session->set_flashdata('error', "Problem while inserting. Please try again");
				redirect(folder_name.'/astro_weekly_prediction');
			}
			else
			{
				$this->db->trans_commit();
				$this->session->set_flashdata('success', 'Inserted Successfully');
				redirect(folder_name.'/astro_weekly_prediction');
			}
		}
		else
		{
			$this->db->trans_begin();
			$this->db->reconnect();
			
			$this->db->query("CALL astro_weeklyPrediction_update('".$raasi_name."', '".$Date."', '".$To_Date."', '".$txtPredictionDetails."','".$user_id."','".date('Y-m-d H:i:s')."','".$user_id."','".date('Y-m-d H:i:s')."', '".$hidden_id."')");
			
			if($this->db->trans_status() == FALSE)
			{
				$this->db->trans_rollback();
				$this->session->set_flashdata('error', "Problem while updating. Please try again");
				redirect(folder_name.'/astro_weekly_prediction');
			}
			else
			{
				$this->db->trans_commit();
				$this->session->set_flashdata('success', 'Updated Successfully');
				redirect(folder_name.'/astro_weekly_prediction');
			}
		
		}
	
	}


}

?>