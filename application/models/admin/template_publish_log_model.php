<?php 
class template_publish_log_model extends CI_Model 
{
	
	function datatable_publish_logs()
	{
		
		extract($_POST);
		
		$Field = $order[0]['column'];
		$order = $order[0]['dir'];

		switch ($Field) {
		case 0:
		   $order_field = 't2.Sectionname';
			break;
		case 2:
		   $order_field = 't4.Version_Name';
			break;
		case 3:
		   $order_field = 't3.Username';
			break;
		default:
        $order_field = 't2.Sectionname';
		}
		
		
		$Total_rows = $this->db->query('CALL template_logs_datatable("","","","","'.$filterby.'",0)')->num_rows();
		
		
		
		if($from_date != '')  {
		$check_in_date 	= new DateTime($from_date);
		$from_date = $check_in_date->format('Y-m-d');
		}
		
		if($to_date != '')  {
		$check_out_date = new DateTime($to_date);
		$to_date = $check_out_date->format('Y-m-d');
		}
		
		$searchtxt= htmlspecialchars(trim($searchtxt));
		$searchtxt = addslashes(str_replace("'", "&#039;", $searchtxt));
			
		$log_manager =  $this->db->query('CALL template_logs_datatable(" ORDER BY '.$order_field.' '.$order.' LIMIT '.$start.', '.$length.'","'.$from_date.'","'.$to_date.'","'.$searchtxt.'","'.$filterby.'","'.$status.'")')->result_array();	

		//echo $this->db->last_query();exit;
		
		$recordsFiltered =  $this->db->query('CALL template_logs_datatable("","'.$from_date.'","'.$to_date.'","'.$searchtxt.'","'.$filterby.'","'.$status.'")')->num_rows();
		$data['draw'] = $draw;
		$data["recordsTotal"] = $Total_rows;
  		$data["recordsFiltered"] = $recordsFiltered ;
		$data['data'] = array();
		$Count = 0;
		
		
		$data['Menu_id'] = get_menu_details_by_menu_name("Template logs");

		
		foreach($log_manager as $logmaster) 
		{
			$subdata = array();
			$subdata[] = ($logmaster['section_id']!=10000)? (($logmaster['section_id']!=10001)? $logmaster['Sectionname']: 'Common widgets Template'): (($logmaster['section_type']==1)? 'Common Section Template' : 'Common Article Page');
			$subdata[] = ($logmaster['section_type']==1)? 'Section' : 'Article';
			$subdata[] = (($logmaster['prev_versionname']!='')? $logmaster['prev_versionname']: '').' ('.$logmaster['prev_versionid'].')';
			$subdata[] = $logmaster['prev_username'];
			$subdata[] = date("d-m-Y h:i:s",strtotime($logmaster['previous_version_published_on']));
			$subdata[] = (($logmaster['cur_versionname']!='')? $logmaster['cur_versionname']: '').' ('.$logmaster['cur_versionid'].')';
			$subdata[] = $logmaster['cur_username'];
			$subdata[] = date("d-m-Y h:i:s",strtotime($logmaster['current_version_published_on']));
				
			   			  
			 /* if(defined("USERACCESS_EDIT".$data['Menu_id']) && constant("USERACCESS_EDIT".$data['Menu_id']) == 1){
			  $set_rights .= '<div><a class="button tick" href="'.base_url().folder_name.'/rolemaster_manager/edit_details/'.$rolemaster['role_id'].'" data-toggle="tooltip" title="Edit"> <i class="fa fa-pencil" ></i> </a>';
			  } 
			  else 
			  { 
			  	$set_rights.="";
			  }
			  */
   			
			
			$data['data'][$Count] = $subdata;
			$Count++;
		}
		
				if($recordsFiltered == 0) {

				}
		
		echo json_encode($data);
		exit;

	}
	
}

?>