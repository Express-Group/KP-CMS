<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class articlelistmodel extends CI_Model{
	
	public function __construct(){
		parent::__construct();
		
		$CI = &get_instance();
		//setting the second parameter to TRUE (Boolean) the function will return the database object.
		$this->live_db = $CI->load->database('live_db', TRUE);
		
		$CI = &get_instance();
		//setting the second parameter to TRUE (Boolean) the function will return the database object.
		$this->archive_db = $CI->load->database('archive_db', TRUE);
	}
	
	
	public function articlelist(){
		extract($_GET);
		$query ="";
		$archivequery = "";
		$suffix = '';
		if(trim($status)!=''){
			$query .="m.status ='".$status."'";
			$archivequery .="status ='".$status."'";
			$suffix .= '&status='.$status;
		}else{
			$query .="m.status !='X' ";
			$archivequery .="status !='X' ";
		}
		$Search_text = trim($Search_text);
		if(trim($Search_by) == 'ContentId') {
			$Search_result = filter_var($Search_text, FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND);
			$Search_value = $Search_result;
			$suffix .= '&status='.$Search_value;
			$suffix .= '&Search_by='.$Search_by;
		} else {
			$Search_value = $Search_text;
			$suffix .= '&status='.$Search_value;
			$suffix .= '&Search_by='.$Search_by;
		}
		$Search_value = htmlentities($Search_value, ENT_QUOTES | ENT_IGNORE, "UTF-8");
		$Search_value =  str_replace("&#039","&#39",$Search_value);
		$Search_value =  addslashes($Search_value);
		if(trim($Search_by)=='Title'){
			$query .="AND m.title LIKE '%".$Search_value."%'";
			$archivequery .="AND title LIKE '%".$Search_value."%'";
		}
		if(trim($Search_by)=='ByLine'){
			$query .="AND m.AuthorName LIKE '%".$Search_value."%'";
			$archivequery .="AND author_name LIKE '%".$Search_value."%'";
		}
		if(trim($Search_by)=='created_by'){
			$query .="AND m.Username LIKE '%".$Search_value."%'";
			$archivequery .="AND created_by LIKE '%".$Search_value."%'";
		}
		if(trim($Search_by)=='ContentId'){
			$query .="AND m.content_id = '".$Search_value."'";
			$archivequery .="AND content_id = '".$Search_value."'";
		}
		if(trim($Search_by)=='' && $Search_value!=''){
			$query .=" AND ( m.title LIKE '%".$Search_value."%' OR am.AuthorName LIKE '%".$Search_value."%' OR um.Username LIKE '%".$Search_value."%')";
			$archivequery .=" AND ( title LIKE '%".$Search_value."%' OR author_name LIKE '%".$Search_value."%' OR created_by LIKE '%".$Search_value."%')";
		}
		if(trim($Section)!=''){
			$query .=" AND ( csp.Section_id IN ( SELECT Section_id FROM `sectionmaster` WHERE IF(`ParentSectionID` !='0', `ParentSectionID`, `Section_id`) = ".$Section." OR `Section_id` = ".$Section." ) )";
			$archivequery .=" AND section_id ='".$Section."'";
			$suffix .= '&Section='.$Section;
		}
		if(trim($fromdate)!=''){
			$check_in_date 	= new DateTime($fromdate);
			$check_in 		= $check_in_date->format('Y-m-d');
			$query .=" AND date(m.Modifiedon) >= '".$check_in."'";
			$archivequery .=" AND date(publish_start_date) >= '".$check_in."'";
			$CheckInYear 	=  $check_in_date->format('Y');
			$suffix .= '&fromdate='.$fromdate;
		}
		if(trim($todate)!=''){
			$check_out_date 	= new DateTime($todate);
			$check_out 		= $check_out_date->format('Y-m-d');
			$query .=" AND date(m.Modifiedon) <= '".$check_out."'";
			$archivequery .=" AND date(publish_start_date) <= '".$check_out."'";
			$CheckOutYear 	=  $check_out_date->format('Y');
			$suffix .= '&todate='.$todate;
		}
		$CheckInYear = Date('Y');
		$TableName = "article_".$CheckInYear;
		$response = [];
		$this->load->library('pagination');
		$arhivetotalrows = 0;
		$totalrows = $this->db->query("SELECT  m.content_id FROM articlesectionmapping as csp LEFT JOIN `articlemaster` as m ON m.`content_id`= csp.`content_id` JOIN `articlerelateddata` as ard ON m.`content_id` = ard.`content_id`  LEFT JOIN imagemaster as im ON im.content_id = ard.articlepageimageid  LEFT JOIN `sectionmaster` As s ON s.Section_id = ard.Section_id LEFT JOIN authormaster as am ON am.Author_id = ard.Author_id LEFT JOIN `usermaster` AS um ON m.CreatedBy = um.User_id WHERE ".$query." LIMIT 50000")->num_rows();
		$liverows = $totalrows;
		if($fromdate!='' && $todate!=''){
			if($CheckInYear < date('Y')){
				
				$TableName = "article_".$CheckInYear;
				if ($this->archive_db->table_exists($TableName)) {
					
					$arhivetotalrows = $this->archive_db->query("SELECT content_id FROM ".$TableName." WHERE ".$archivequery."")->num_rows();
				}
				
			}
		}
		if($arhivetotalrows!=0){  $totalrows = $totalrows +  $arhivetotalrows ;}
		$config['base_url'] = base_url().folder_name.'/articlelist';
		$config['total_rows'] = $totalrows;
		$config['per_page'] = 20;
		$config['num_links'] = 5;
		$config['page_query_string'] = TRUE;
		$config['suffix'] = $suffix;
		$_GET['per_page'] = (isset($_GET['per_page']) && $_GET['per_page']!='') ? $_GET['per_page'] : 0;
		$this->pagination->initialize($config);
		$response['pagination'] = $this->pagination->create_links();
		//if($totalrows > ($_GET['per_page'] + $config['per_page'])){
			
			
	//	}
		$response['records'] = $response['archiverecords'] =  array();
		$response['records'] = $this->db->query("SELECT m.title, m.content_id , m.url, m.status, m.Modifiedon,m.publish_start_date ,s.Section_id,s.Sectionname, am.AuthorName,um.Username,ard.	homepageimageid, ard.Sectionpageimageid, ard.articlepageimageid, im.ImagePhysicalPath,s.URLSectionStructure  FROM articlesectionmapping as csp LEFT JOIN `articlemaster` as m ON m.`content_id`= csp.`content_id` JOIN `articlerelateddata` as ard ON m.`content_id` = ard.`content_id`  LEFT JOIN imagemaster as im ON im.content_id = ard.articlepageimageid  LEFT JOIN `sectionmaster` As s ON s.Section_id = ard.Section_id LEFT JOIN authormaster as am ON am.Author_id = ard.Author_id LEFT JOIN `usermaster` AS um ON m.CreatedBy = um.User_id WHERE ".$query." ORDER BY m.content_id DESC  LIMIT ".$_GET['per_page']." , ".$config['per_page']."")->result();
		if(($_GET['per_page'] >  $liverows) || $liverows==0){			
			if($liverows==0){
				$archivelimitrows = $_GET['per_page'];
			}else{
				if($_GET['per_page'] > 20){
				$archivelimitrows = $_GET['per_page']  - $liverows;
				}else{
					$archivelimitrows = $_GET['per_page'];
				}
			}	
			if ($this->archive_db->table_exists($TableName)) {			
				$response['archiverecords'] = $this->archive_db->query("SELECT content_id,title,publish_start_date,status, url,articlepageimageid,homepageimageid,sectionpageimageid,author_name,created_by,modified_by,modified_on FROM ".$TableName." WHERE ".$archivequery." LIMIT ".$archivelimitrows." , ".$config['per_page']."")->result();
			}
		}
		$articleDetails ='';
		$menu_name		= "Article";
		$Menu_id = get_menu_details_by_menu_name($menu_name);
		foreach($response['records'] as $article):
			$edit_url = "edit_article/".urlencode(base64_encode($article->content_id));
			$Style = "";
			if($article->status == 'P' && strtotime($article->publish_start_date) > strtotime(date('d-m-Y H:i:s'))){ $Style = "style='color:red'"; } 
			if($article->status == 'D'){ $Style = "style='color:#0004FE'"; }
			$articleDetails .='<tr>';
			$articleDetails .='<td>'.$article->content_id.'</td>';
			$articleDetails .='<td><p class="tooltip_cursor" '.$Style.' title="'.strip_tags($article->title).'">'.shortDescription(strip_tags($article->title)).'</p></td>';
			$articleDetails .='<td>'.GenerateBreadCrumbBySectionId($article->Section_id).'</td>';
			if($article->articlepageimageid != '' ||  $article->homepageimageid || $article->Sectionpageimageid ) {
				if($article->ImagePhysicalPath != '') {
					$Image150X150 	= str_replace("original","w150X150", $article->ImagePhysicalPath);
					$articleDetails .='<td><a href="javascript:void()"><i class="fa fa-picture-o"></i></a><div class="img-hover"><img  src="'.image_url.imagelibrary_image_path.$Image150X150.'" /></div></td>';
				}else{
					$articleDetails .='<td><i class="fa fa-picture-o"></i></td>';	
				}
			}else{
				$articleDetails .='<td>-</td>';
			}
			if($article->AuthorName != ''){
				$articleDetails .='<td>'.$article->AuthorName.'</td>'; 
			}else{
				$articleDetails .='<td>-</td>';
			}
			$articleDetails .='<td>'.$article->Username.'</td>';	
			$articleDetails .='<td>'.$article->Username.'</td>';	
			$articleDetails .='<td>'.date('d-m-Y H:i:s', strtotime($article->Modifiedon)).'</td>';
			switch($article->status){
				case("P"):
					$status_icon = '<span data-toggle="tooltip" title="Published" href="javascript:void()" id="img_change'.$article->content_id.'" data-original-title="Active"><i id="status_img'.$article->content_id.'"  class="fa fa-check"></i></span>';
					break;
				case("U"):	
					$status_icon = '<span data-toggle="tooltip" title="Unpublished" href="javascript:void()" id="img_change'.$article->content_id.'"  data-original-title="Active"><i id="status_img'.$article->content_id.'" class="fa fa-times"></i></span>';
					break;
				case("D"):			
					$status_icon = '<span data-toggle="tooltip" title="Draft" href="javascript:void()" id="img_change'.$article->content_id.'"  data-original-title="Active"><i id="status_img'.$article->content_id.'" class="fa fa-floppy-o"></i></span>';
					break;	
				default;
					$status_icon = '';
			}
			$articleDetails .='<td>'.$status_icon.'</td>';
			$articleDetails .='<td><div class="buttonHolder">';
			if(defined("USERACCESS_EDIT".$Menu_id) && constant("USERACCESS_EDIT".$Menu_id) == 1){
				$articleDetails .='<a class="button tick tooltip-2"  href="'.base_url().folder_name.'/'.$edit_url.'" target="_blank" title="Edit"><i class="fa fa-pencil"></i></a><a class="button tick tooltip-2"  href="'.BASEURL.$article->url.'?page=preview" target="_blank" title="Preview"><i class="fa fa-eye"></i></i></a>';
			}else{
				$articleDetails .='';
			}
			if($article->status=="P"){
				if(defined("USERACCESS_UNPUBLISH".$Menu_id) && constant("USERACCESS_UNPUBLISH".$Menu_id) == 1) { 
					$articleDetails .='<a class="button heart tooltip-3" data-toggle="tooltip" href="#"  title="Unpublish" content_id = '.$article->content_id.' status ="'.$article->status.'" name="'.strip_tags($article->title).'" id="status_change"><i id="status'.$article->content_id.'" class="fa fa-pause"></i></a>'.' ';
				}
			}elseif($article->status=="U"){ 
				if(defined("USERACCESS_PUBLISH".$Menu_id) && constant("USERACCESS_PUBLISH".$Menu_id) == 1) {
					$articleDetails .='<a data-toggle="tooltip" href="#" title="Publish" class="button heart" data-original-title="" content_id = '.$article->content_id.' status ="'.$article->status.'" name="'.strip_tags($article->title).'" id="status_change"><i id="status'.$article->content_id.'" class="fa fa-caret-right"></i></a>'.'';
				}
			} 
			if($article->status=="P" ) {
				if(defined("USERACCESS_UNPUBLISH".$Menu_id) && constant("USERACCESS_UNPUBLISH".$Menu_id) == 1) {
					$articleDetails .='<span class="button tooltip-2 DataTableCheck" title="" ><input type="checkbox" title="Select"  name="unpublish_checkbox[]" value="'.$article->content_id.'" id="unpublish_checkbox_id" status ="'.$article->status.'"    ></span>';
				}
			}
			if($article->status=="U" || $article->status=="D") {
				if(defined("USERACCESS_PUBLISH".$Menu_id) && constant("USERACCESS_PUBLISH".$Menu_id) == 1) {
					$articleDetails .='<span class="button tooltip-2 DataTableCheck" title="" ><input type="checkbox"  title="Select"    title="Select"   name="publish_checkbox[]" value="'.$article->content_id.'"   status ="'.$article->status.'"    id="publish_checkbox_id" ></span>';
				}
			}
			$articleDetails .='</div></td>';	
			$articleDetails .='</tr>';
		endforeach;
		foreach($response['archiverecords'] as $article):
			$edit_url = "edit_archive_article/".$CheckInYear."/".urlencode(base64_encode($article->content_id));
			$Style = "";
			if($article->status == 'P' && strtotime($article->publish_start_date) > strtotime(date('d-m-Y H:i:s'))){
				$Style = "style='color:red'";
			}			
			$articleDetails .='<tr>';
			$articleDetails .='<td>'.$article->content_id.'</td>';
			$articleDetails .='<td><p class="tooltip_cursor" '.$Style.' title="'.strip_tags($article->title).'">'.shortDescription(strip_tags($article->title)).'</p></td>';
			$articleDetails .='<td>'.(GetBreadCrumbByURL($article->url)).'</td>';
			if($article->articlepageimageid != '' ||  $article->homepageimageid || $article->sectionpageimageid ) {
				if($article->articlepageimageid != '') {
					$Image150X150 	= str_replace("original","w150X150", $article->articlepageimageid);
					$articleDetails .='<td><a href="javascript:void()"><i class="fa fa-picture-o"></i></a><div class="img-hover"><img  src="'.image_url.imagelibrary_image_path.$Image150X150.'" /></div></td>';
				} else {
					$articleDetails .='<td><a href="javascript:void()"><i class="fa fa-picture-o"></i></a></td>';	
				}
			}else{
				$articleDetails .='<td><a href="javascript:void()">-</a></td>';
			}
			if($article->author_name!=''){
				$articleDetails .='<td>'.$article->author_name.'</td>';
			}else{
				$articleDetails .='<td>-</td>';
			}
			$articleDetails .='<td>'.$article->created_by.'</td>';
			$articleDetails .='<td>'.$article->modified_by.'</td>';
			$change_date_format = date('d-m-Y H:i:s', strtotime($article->modified_on));
			$articleDetails .='<td>'.$change_date_format.'</td>';
			switch($article->status){
				case("P"):
					$status_icon = '<a data-toggle="tooltip" title="Published" href="javascript:void()" id="img_change'.$article->content_id.'" data-original-title="Active"><i id="status_img'.$article->content_id.'"  class="fa fa-check"></i></a>';
					break;
				case("U"):	
					$status_icon = '<a data-toggle="tooltip" title="Unpublished" href="javascript:void()" id="img_change'.$article->content_id.'"  data-original-title="Active"><i id="status_img'.$article->content_id.'" class="fa fa-times"></i></a>';
					break;
				case("D"):			
					$status_icon = '<a data-toggle="tooltip" title="Draft" href="javascript:void()" id="img_change'.$article->content_id.'"  data-original-title="Active"><i id="status_img'.$article->content_id.'" class="fa fa-floppy-o"></i></a>';
					break;	
				default;
					$status_icon = '';
			}
			$articleDetails .='<td>'.$status_icon.'</td>';
			$articleDetails .='<td><div class="buttonHolder">';
			if(defined("USERACCESS_EDIT".$Menu_id) && constant("USERACCESS_EDIT".$Menu_id) == 1){
				$articleDetails .='<a class="button tick tooltip-2"  href="'.base_url().folder_name.'/'.$edit_url.'" target="_blank" title="Edit"><i class="fa fa-pencil"></i></a><a class="button tick tooltip-2"  href="'.BASEURL.$article->url.'?page=preview" target="_blank" title="Preview"><i class="fa fa-eye"></i></i></a>';
			}else {
				$articleDetails .='';
			}
			$articleDetails .='</div></td>';
			$articleDetails .='</tr>';
		endforeach;
		
		$response['total_rows'] = $totalrows;
		$response['recordsdata'] = $articleDetails;
		return $response;
	}
}
?> 