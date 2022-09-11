<?php 
class astro_monthlyPrediction_model extends CI_Model
{

	public function get_astro_monthlypredictions()  
	{	
		$class_obj = new monthly_predictions;
		return $class_obj->get_monthlyPredictions();
	}
	
	public function get_raasi_list()  
	{	
		$this->db->reconnect();
		$result =  $this->db->query('CALL get_astro_Ids()')->result_array();
		return $result;
	}
	
	public function get_monthly_predictions($prediction_id = '')  
	{	
		$this->db->reconnect();
		$result =  $this->db->query("CALL get_astro_monthlyPredictionById('".$prediction_id."')")->row_array();
		
		return $result;
	}
	
	public function manipulate($user_id)
	{
		$class_obj = new manipulate_monthly_predictions;
		return $class_obj->insert_update($user_id);
	}
	public function tamil_prediction_manipulate($user_id)
	{
		$class_obj = new manipulate_monthly_predictions;
		return $class_obj->insert_update_tamil_prediction($user_id);
	}
	
	public function check_alreadyExists()
	{
		$raasi_id = trim($this->input->post('raasi_id'));
		$prediction_id = trim($this->input->post('prediction_id'));
		$lang = ($this->input->post('lang')=='tamil')? 'tamil' : 'english';
		if($lang!='tamil'){
		$month = $this->input->post('month');
		$date=$this->input->post('schd_date');
		$date_format = date('Y-m-d',strtotime($date));
		//error_log("CALL check_astroMonthlyPrediction_exists('".$raasi_id."', '".$start_date."', '".$end_date."', '".$prediction_id."')");
		$check_data = $this->db->query("CALL check_astroMonthlyPrediction_exists('".$raasi_id."','".$month."', '".$date_format."', '".$prediction_id."')");
		return $check_data->num_rows();
		}else{
		$predict_start_date = date('Y-m-d', strtotime($this->input->post('prediction_start_date')));
		$predict_end_date   = date('Y-m-d', strtotime($this->input->post('prediction_end_date')));
		$check_data = $this->db->query("CALL check_astrotamilMonthlyPrediction_exists('".$raasi_id."','".$predict_start_date."', '', '".$prediction_id."')");
		
		$result = (($check_data->num_rows())!=2)? 0 : $check_data->num_rows() ;
		return $result;
		}
		
	}
	
}


class monthly_predictions extends astro_monthlyPrediction_model
{
	public function get_monthlyPredictions()
	{
		extract($_POST);
		
		$Field = $order[0]['column'];
		$order = $order[0]['dir'];
        $language  = $lang; 
		 
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
		$call_procedure  = ($language=='english') ? 'get_astro_MonthlyPrediction': 'get_astro_MonthlyPrediction_tamil';
		$Total_rows = $this->db->query('CALL '.$call_procedure.'("","","","")')->num_rows();
		
		$this->db->reconnect();
		
		if($from_date != '')  {
		$check_in_date 	= new DateTime($from_date);
		$from_date = $check_in_date->format('Y-m-d');
		}
		
		if($to_date != '')  {
		$check_out_date = new DateTime($to_date);
		$to_date = $check_out_date->format('Y-m-d');
		}
		
		$monthlyPrediction_values =  $this->db->query('CALL '.$call_procedure.'(" ORDER BY '.$order_field.' '.$order.' LIMIT '.$start.', '.$length.'","'.$from_date.'","'.$to_date.'","'.$rasi_id.'")')->result_array();

		$this->db->reconnect();

		$recordsFiltered =  $this->db->query('CALL '.$call_procedure.'("","'.$from_date.'","'.$to_date.'","'.$rasi_id.'")')->num_rows();
		$data['draw'] = $draw;
		$data["recordsTotal"] = $Total_rows;
  		$data["recordsFiltered"] = $recordsFiltered ;
		$data['data'] = array();
		$Count = 0;
		$menu_name  = ($language=='english') ? 'Monthly Predictions': 'Monthly Predictions Tamil';
		$Menu_id = get_menu_details_by_menu_name($menu_name);
		$qry  = ($language=='english') ? '': '?lang='.$language;
		foreach($monthlyPrediction_values as $monthly_prediction) {
			
			$subdata = array();
		
			$subdata[] = date("d-m-Y",strtotime($monthly_prediction['prediction_date']));
			$subdata[] = $monthly_prediction['Sectionname'];
			$subdata[] = $monthly_prediction['Username'];
			$subdata[] =  date("d-m-Y H:i:s",strtotime($monthly_prediction['modified_on']));
		
			
			$set_rights = "";
			
			if(defined("USERACCESS_EDIT".$Menu_id) && constant("USERACCESS_EDIT".$Menu_id) == '1'){
				$set_rights .= '<div class="buttonHolder"><a class="button heart tooltip-3"  href="'.base_url(folder_name).'/astro_monthly_prediction/update_monthly_predictions/'.urlencode(base64_encode($monthly_prediction['prediction_id'])).$qry.'" data-toggle="tooltip" title="Edit"> <i class="fa fa-pencil" ></i> </a>';
			} else { $set_rights.="";			}
			
			if(defined("USERACCESS_DELETE".$Menu_id) && constant("USERACCESS_DELETE".$Menu_id) == '1')			{
			//$set_rights .= '<a class="button heart tooltip-3" href="#" data-toggle="tooltip" onclick="delete_monthlyPrediction('.$monthly_prediction['prediction_id'].')" title="Move to Trash"  id=""> <i class="fa fa-trash-o"></i> </a></div>'; 
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

class manipulate_monthly_predictions extends astro_monthlyPrediction_model 
{
	
	
	public function delete_last($raasi_name,$dat)
    {  
	$dat = strtotime($dat);
	$dat =date('Y-m-d',$dat); 
	
	   $query = $this->db->query("CALL delete_last_record ('".$raasi_name."', '".$dat."','sel','rasi_monthly_predictions')")->result_array();
	
		$created_on = array();
		foreach( $query  as $content_val)
		{
			$created_on[] = $content_val['prediction_date'];
		}
		//print_r($created_on);
	   $datt = end($created_on);
		 
		 $query1 = $this->db->query("CALL delete_last_record ('".$raasi_name."', '".$datt."','del','rasi_monthly_predictions')");
		

    }
	
	public function insert_update($user_id)
	{
		extract($_POST);
		
		//print_r($_POST);
		
		
		switch($prediction_month) {
		case 'January':
			$ClassName = "01";
		break;
		case 'February':
			$ClassName = "02";
		break;
		case 'March':
			$ClassName = "03";
		break;
		case 'April':
			$ClassName = "04";
		break;
		case 'May':
		$ClassName = "05";
		break;
		case 'June':
			$ClassName = "06";
		break;
		case 'July':
			$ClassName = "07";
		break;
		case 'August':
			$ClassName = "08";
		break;
		case 'September':
			$ClassName = "09";
		break;
		case 'October':
			$ClassName = "10";
		break;
		
		case 'November':
			$ClassName = "11";
		break;
		case 'December':
			$ClassName = "12";
		break;
		case '- Select -':
			//$rasi_name = "";
			$ClassName = "01";
		break;
		default:
			$ClassName = "01";
		break;
	}
		if($prediction_date != '')  {
			
			$Date = '01-'.$ClassName.'-'.$prediction_date;
			$Date = strtotime($Date);
			$prediction_date = date('Y-m-d', $Date);
			
		}
		
		/*if($prediction_date != '')  {
			$Date = strtotime($prediction_date);
			$Date = date('Y-m-d', $Date);
			$To_Date = strtotime($prediction_end_date);
			$To_Date = date('Y-m-d', $To_Date);
		}*/
		
		
		if($hidden_id == "")
		{
			$dat = date('Y-m').'-01';
			//echo 'dxdf';exit;
			//$dat =date('Y-m-d'); 
			$this->delete_last($raasi_name,$dat);
			$this->db->trans_begin();
			$this->db->reconnect();				
			
			$this->db->query("CALL astro_monthlyPrediction_insert('".$raasi_name."', '".$prediction_month."', '".$prediction_date."', '".$txtPredictionDetails."','".$user_id."','".date('Y-m-d H:i:s')."','".$user_id."','".date('Y-m-d H:i:s')."')");
			$this->delete_last($raasi_name,$dat);
			if($this->db->trans_status() == FALSE)
			{
				$this->db->trans_rollback();
				$this->session->set_flashdata('error', "Problem while inserting. Please try again");
				redirect(folder_name.'/astro_monthly_prediction');
			}
			else
			{
				$this->db->trans_commit();
				$this->session->set_flashdata('success', 'Inserted Successfully');
				redirect(folder_name.'/astro_monthly_prediction');
			}
			
		}
		else
		{
			$this->db->trans_begin();
			$this->db->reconnect();
			
			$this->db->query("CALL astro_monthlyPrediction_update('".$raasi_name."', '".$prediction_month."', '".$prediction_date."', '".$txtPredictionDetails."','".$user_id."','".date('Y-m-d H:i:s')."','".$user_id."','".date('Y-m-d H:i:s')."', '".$hidden_id."')");
						
			if($this->db->trans_status() == FALSE)
			{
				$this->db->trans_rollback();
				$this->session->set_flashdata('error', "Problem while updating. Please try again");
				redirect(folder_name.'/astro_monthly_prediction');
			}
			else
			{
				$this->db->trans_commit();
				$this->session->set_flashdata('success', 'Updated Successfully');
				redirect(folder_name.'/astro_monthly_prediction');
			}
		
		}
	
	}
	
	public function insert_update_tamil_prediction($user_id)
	{
		extract($_POST);
				
		if($prediction_start_date != '')  {
			$Date = strtotime($prediction_start_date);
			$Date = date('Y-m-d', $Date);
			$To_Date = strtotime($prediction_end_date);
			$To_Date = date('Y-m-d', $To_Date);
		}
		
		if($hidden_id == "")
		{
			$dat = date('Y-m').'-01';
			//$this->delete_last($raasi_name,$dat);
			$this->db->trans_begin();
			$this->db->reconnect();				
			
			$this->db->query("CALL astro_tamilmonthlyPrediction_insert('".$raasi_name."', '".$Date."', '".$To_Date."', '".$txtPredictionDetails."','".$user_id."','".date('Y-m-d H:i:s')."','".$user_id."','".date('Y-m-d H:i:s')."', 2)");
			//$this->delete_last($raasi_name,$dat);
			if($this->db->trans_status() == FALSE)
			{
				$this->db->trans_rollback();
				$this->session->set_flashdata('error', "Problem while inserting. Please try again");
				redirect(folder_name.'/astro_monthly_prediction/tamil');
			}
			else
			{
				$this->db->trans_commit();
				$this->session->set_flashdata('success', 'Inserted Successfully');
				redirect(folder_name.'/astro_monthly_prediction/tamil');
			}
			
		}
		else
		{
			$this->db->trans_begin();
			$this->db->reconnect();
			
			$this->db->query("CALL astro_tamilmonthlyPrediction_update('".$raasi_name."', '".$Date."', '".$To_Date."', '".$txtPredictionDetails."','".$user_id."','".date('Y-m-d H:i:s')."','".$user_id."','".date('Y-m-d H:i:s')."', '".$hidden_id."')");
						
			if($this->db->trans_status() == FALSE)
			{
				$this->db->trans_rollback();
				$this->session->set_flashdata('error', "Problem while updating. Please try again");
				redirect(folder_name.'/astro_monthly_prediction/tamil');
			}
			else
			{
				$this->db->trans_commit();
				$this->session->set_flashdata('success', 'Updated Successfully');
				redirect(folder_name.'/astro_monthly_prediction/tamil');
			}
		
		}
	
	}


}

?>