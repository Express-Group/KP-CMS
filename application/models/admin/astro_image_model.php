<?php
/**
 * Article Image Model Controller Class
 *
 * @package	NewIndianExpress
 * @category	News
 * @author	IE Team
 */

class Astro_image_model extends CI_Model

{
	public function insert_image_library($upload_data)

	{
		$class_object = new Insert_image_class;
		return $class_object->insert_image_library($upload_data);
	}
	public function insert_image_temp($imagedata, $caption, $alt_tag, $physical_name, $file_size, $file_name, $file_type, $width, $height, $userid, $type) {
		
		$createdon 			= date('Y-m-d H:i:s');
		$modifiedon 		= date('Y-m-d H:i:s');
		$Null_value 		= 'NULL';
		$Imagedata 			= '';
		
		$this->db->reconnect();
		
		$query 				= $this->db->query("CALL insert_temp_images('" . $userid . "','" . $type . "','" . $caption . "','" . $alt_tag . "','".$physical_name."','" . $file_size . "','" . addslashes($Imagedata) . "',".$Null_value.",".$Null_value.",".$Null_value.",".$Null_value.",".$Null_value.",".$Null_value.",".$Null_value.",".$Null_value.",'" . $file_name . "','" . $width . "','" . $height . "','" . $file_type . "',1,'" . $createdon . "','" . $modifiedon . "',@insert_id," . $Null_value . ")");
		
		$this->db->reconnect();
		$query 				= $this->db->query("SELECT @insert_id");
		$returnid 			= $query->result_array();
		$data['image_id'] 	= $returnid['0']['@insert_id'];
		
		$Physical_extension_array = explode('.',addslashes($file_name));
		
		$data['image'] 		= image_url.article_temp_image_path.$file_name;

		
		$data['caption'] 	= $caption;
		$data['alt_tag'] 	= $alt_tag;
		$data['physical_name'] = $physical_name;
		$data['imagecontent_id'] = '';
		$data['physical_extension'] = $Physical_extension_array[1];
		
		$data['image1_type'] = $data['image2_type'] = $data['image3_type'] = $data['image4_type'] = 0;
		
		return $data;

		
	}
	public function get_image_library()

	{
		$class_object = new Get_image_class;
		return $class_object->get_image_library();
	}
	public function search_image_library()

	{
		$class_object = new Get_image_class;
		return $class_object->search_image_library();
	}
	
	public function search_image_library_scroll($page)

	{
		$class_object = new Get_image_class;
		return $class_object->search_image_library_scroll($page);
	}
	public function Insert_temp_from_image_library()

	{
		$class = new Insert_temp_Image_model;
		return $class->Insert_temp_from_image_library();
	}
	public function get_image_library_scroll($page)

	{
		
		$offset = ($page*16) - 16;
		
		$this->db->reconnect();
		$Order = "ORDER BY Modifiedon desc LIMIT ".$offset.", 16";
		$image = $this->db->query('CALL get_image_related_data("'.$Order.'")');
		return $image->result();
	}
}
class Insert_image_class extends Astro_image_model

{
	public function insert_image_library($upload_data)

	{
		$article_image_temp 	= $_FILES['userImage']['tmp_name'];
		$type 					= $_FILES['userImage']['type'];
		$data 					= 'data:' . $type . ';base64,' . base64_encode(file_get_contents($article_image_temp));
		$null_value 			= "NULL";
		$publishedon 			= date("Y-m-d H:i:s");
		
		extract($_POST);
		
		/* New coding */
		$this->db->reconnect();
		if (trim($caption) != '')
		{
			$contentmaster = $this->db->query('CALL add_content_master ("2","' . $publishedon . '","","P","'.USERID.'","' . date("Y-m-d H:i:s") . '",@insert_id,"'.USERID.'","' . date("Y-m-d H:i:s") . '")');
			$result = $this->db->query("SELECT @insert_id")->result_array();
			
			$content_id = $result[0]['@insert_id'];
			
			if (isset($content_id) && $content_id != '')
			{
				$content = $this->db->query('CALL add_content_version ("' . $content_id . '","1","' . addslashes($caption) . '","'.addslashes($caption).'","",' . $null_value . ',"",' . $null_value . ',' . $null_value . ',"' . $main_section . '",' . $null_value . ',' . $null_value . ',' . $null_value . ',' . $null_value . ',' . $null_value . ',' . $null_value . ',' . $null_value . ',' . $null_value . ',' . $null_value . ',' . $null_value . ',' . $null_value . ',' . $null_value . ',' . $null_value . ',"0","Y","' . $publishedon . '","","'.USERID.'","' . date("Y-m-d H:i:s") . '",@insert_id,"'.USERID.'","' . date("Y-m-d H:i:s") . '")');
				
				$result = $this->db->query("SELECT @insert_id")->result_array();
				$content_version_id = $result[0]['@insert_id'];
				
				$update_versionid = $this->db->query('CALL add_content_version_mapping("' . $content_id . '","' . $content_version_id . '","'.USERID.'","' . date("Y-m-d H:i:s") . '","'.USERID.'","' . date("Y-m-d H:i:s") . '")');
				
				add_content_version_publish_history($content_version_id, date("Y-m-d H:i:s") , USERID);
			}
			if (isset($content_version_id) && $content_version_id != '')
			{
				$Null_value 	= 'NULL';
				$insert_array 	= array();
				$insert_array[] = $content_version_id;
				$insert_array[] = $alt_tag;
				$insert_array[] = $upload_data['upload_data']['image_height'];
				$insert_array[] = $upload_data['upload_data']['image_width'];
				$insert_array[] = $upload_data['upload_data']['file_size'];
				$insert_array[] = addslashes($data);
				$insert_array[] = $upload_data['upload_data']['raw_name'] . $upload_data['upload_data']['file_ext'];
				$insert_array[] = '';
				$insert_array[] = '';
				$insert_array[] = '';
				$insert_array[] = '';
				$insert_array[] = 0;
				$insert_array[] = 0;
				$insert_array[] = 0;
				$insert_array[] = 0;
				$insert_array[] = USERID;
				$insert_array[] = date("Y-m-d H:i:s");
				$insert_array[] = USERID;
				$insert_array[] = date("Y-m-d H:i:s");
				
				$result = implode('","', $insert_array);
				$image_gallery = $this->db->query('CALL add_image_related_data("' . $result . '",@insert_id)');
				$image_gallery->result();
				
				$result_data['message'] 		= 'Image Uploaded Successfully...';
				$result_data['status'] 			= 1;
				$result_data['image_id'] 		= $content_id;
				$result_data['image_binary'] 	= $data;
				
				return $result_data;
			}
		}
	}
}
class Get_image_class extends Astro_image_model

{
	public function get_image_library()

	{
		$this->db->reconnect();
		$Order = "ORDER BY Modifiedon desc LIMIT 0, 16";
		$image = $this->db->query('CALL get_image_related_data("'.$Order.'")');
		return $image->result();
	}
	public function search_image_library_scroll($page)

	{
		
		$this->db->reconnect();
		$offset = ($page*16) - 16;
		$Section = $this->session->userdata('image_section');
		$Caption =	$this->session->userdata('image_caption');
		
		$Order = "ORDER BY Modifiedon desc LIMIT ".$offset.", 16";
		
		if($Section != '' || $Caption != '')
		$search = $this->db->query('CALL search_image_related_data("' . $Section . '","' . $Caption . '","'.$Order.'")');
		else
		$search = $this->db->query('CALL get_image_related_data("'.$Order.'")');
		
		return $search->result();
	}
	
	public function search_image_library()

	{
		extract($_POST);
		$this->db->reconnect();
		
			$this->session->set_userdata('image_section',$Section);
			$this->session->set_userdata('image_caption',$Caption);
		
		$Order = "ORDER BY Modifiedon desc LIMIT 0, 16";
		
		if($Section != '' || $Caption != '')
		$search = $this->db->query('CALL search_image_related_data("' . $Section . '","' . $Caption . '","'.$Order.'")');
		else
		$search = $this->db->query('CALL get_image_related_data("'.$Order.'")');
		
		echo json_encode($search->result());
	}
}
class Insert_temp_Image_model extends Astro_image_model

{
	public function Insert_temp_from_image_library()

	{
		extract($_POST);
		
			/*$TempDetails = CheckImageContentIdInTemp($content_id);
			if (empty($TempDetails))
			{  */
				$NewImageName		= md5(rand(10000000000000000,99999999999999999).date('yymmddhis'));
				$SourceURL  		= imagelibrary_image_path;
				$DestinationURL		= article_temp_image_path;
				
				$ImageDetails 	= GetImageDetailsByContentId($content_id);
				
				$path = $ImageDetails['ImagePhysicalPath'];
				
				$NewPath = GenerateNewImageName($path, $NewImageName);
				
				ImageLibraryCopyToTemp($path,$NewPath, $SourceURL, $DestinationURL);
				$path = $NewPath;
			
				$createdon 		= $modifiedon = date('Y-m-d H:i:s');
				
				if (isset($caption))
				{
					
					
					$OrignalImage 	= $ImageDetails['ImageBinaryData'];
					
					$ImageBinaryData1 	= $ImageDetails['ImageBinaryData1'];
					$ImageBinaryData2 	= $ImageDetails['ImageBinaryData2'];
					$ImageBinaryData3 	= $ImageDetails['ImageBinaryData3'];
					$ImageBinaryData4 	= $ImageDetails['ImageBinaryData4'];
					
					$Image1Type 	= $ImageDetails['Image1Type'];
					$Image2Type 	= $ImageDetails['Image2Type'];
					$Image3Type 	= $ImageDetails['Image3Type'];
					$Image4Type 	= $ImageDetails['Image4Type'];
					
					$PhysicalName = GetPhysicalNameFromPhysicalPath($ImageDetails['ImagePhysicalPath']);
					
					
					$this->db->reconnect();
					$query 		= $this->db->query("CALL insert_temp_images('".USERID."','Article','" . $caption . "','" . $alt . "','" . $PhysicalName . "','" . $size . "','" . addslashes($OrignalImage) . "','".addslashes($ImageBinaryData1)."','".addslashes($ImageBinaryData2)."','".addslashes($ImageBinaryData3)."','".addslashes($ImageBinaryData4)."','".$Image1Type."','".$Image2Type."','".$Image3Type."','".$Image4Type."','" . $path . "','" . $width . "','" . $height . "','0',1,'" . $createdon . "','" . $modifiedon . "',@insert_id,'" . $content_id . "')");
					
					$result 			= $this->db->query("SELECT @insert_id")->result_array();
					$image_temp_id 		= $result[0]['@insert_id'];
					$data['image_id'] 	= $image_temp_id;
					$data['source'] 	= image_url.article_temp_image_path.$path;
					
					$Physical_extension_array = explode(".",$path);
					
					$data['caption'] 	= $caption;
					$data['alt'] 		= $alt;
					
					$data['physical_name'] 		= $PhysicalName;
					$data['physical_extension'] = $Physical_extension_array[1];
					
					$data['imagecontent_id'] 		= $content_id;
					$data['image1_type'] = $Image1Type; 
					$data['image2_type'] = $Image2Type;
					$data['image3_type'] = $Image3Type;
					$data['image4_type'] = $Image4Type;
					
					
				}
			/*}
			else
			{
				$data['image_id'] 	= $TempDetails['imageid'];
				$data['source'] 	= $TempDetails['image_binary_file'];
				$data['caption'] 	= $TempDetails['caption'];
				$data['alt'] 		= $TempDetails['alt_tag'];
			} */
		
		echo json_encode($data);
	}
}
/* End of file Astro_image_model.php */
/* Location: ./application/models/admin/Astro_image_model.php */
?>
