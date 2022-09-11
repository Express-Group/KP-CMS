<?php
class article_search extends CI_Controller{
	
	public function __construct(){
		parent::__construct();
		$this->load->helper('url');
		$this->load->database();
		$CI = &get_instance();
		$this->archive_db = $CI->load->database('archive_db', TRUE);
	}
	
	public function index(){
		$menu_name		= "Article";
		$Menu_id = get_menu_details_by_menu_name($menu_name);
		$data['template'] = 'article_search';
		$contentId = trim($this->input->get('contentid'));
		$title = trim($this->input->get('title'));
		$articledata = [];
		
		$searchquery ="m.status !='X'";
		$archivequery = "status !='X'";
		if($contentId !='' && is_numeric($contentId)==true){
			$searchquery .=" AND m.content_id=".$contentId." ";
			$archivequery .=" AND content_id=".$contentId." ";
		}else if($contentId !='' && is_numeric($contentId)==false){
			$searchquery .=" AND m.title LIKE '%".$contentId."%' ";
			$archivequery .=" AND title LIKE '%".$contentId."%' ";
		}
		if($contentId!=''){
			$livetotal = $this->db->query("SELECT  m.content_id FROM articlesectionmapping as csp LEFT JOIN `articlemaster` as m ON m.`content_id`= csp.`content_id` JOIN `articlerelateddata` as ard ON m.`content_id` = ard.`content_id`  LEFT JOIN imagemaster as im ON im.content_id = ard.articlepageimageid  LEFT JOIN `sectionmaster` As s ON s.Section_id = ard.Section_id LEFT JOIN authormaster as am ON am.Author_id = ard.Author_id LEFT JOIN `usermaster` AS um ON m.CreatedBy = um.User_id WHERE ".$searchquery." LIMIT 50000")->num_rows();
			if($livetotal > 0){
				$livedata = $this->db->query("SELECT m.title, m.content_id , m.url, m.status, m.Modifiedon,m.publish_start_date ,s.Section_id,s.Sectionname, am.AuthorName,um.Username,ard.	homepageimageid, ard.Sectionpageimageid, ard.articlepageimageid, im.ImagePhysicalPath,s.URLSectionStructure  FROM articlesectionmapping as csp LEFT JOIN `articlemaster` as m ON m.`content_id`= csp.`content_id` JOIN `articlerelateddata` as ard ON m.`content_id` = ard.`content_id`  LEFT JOIN imagemaster as im ON im.content_id = ard.articlepageimageid  LEFT JOIN `sectionmaster` As s ON s.Section_id = ard.Section_id LEFT JOIN authormaster as am ON am.Author_id = ard.Author_id LEFT JOIN `usermaster` AS um ON m.CreatedBy = um.User_id WHERE ".$searchquery." ORDER BY m.content_id DESC")->result();
				foreach($livedata as $article):
					$edit_url = "edit_article/".urlencode(base64_encode($article->content_id));
					$Style = "";
					if($article->status == 'P' && strtotime($article->publish_start_date) > strtotime(date('d-m-Y H:i:s'))){ $Style = "style='color:red'"; } 
					if($article->status == 'D'){ $Style = "style='color:#0004FE'"; }
					$articleDetails = '';
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
					$articleDetails .='</div></td>';	
					$articleDetails .='</tr>';
					array_push($articledata , $articleDetails);
					$articleDetails = '';
				endforeach;
			}
			$archiverange = array_reverse(range(2009 , Date('Y')-1));
			$archiverows = 0;
			for($i=0;$i<count($archiverange);$i++){
				$tablename = 'article_'.$archiverange[$i];
				if($this->archive_db->table_exists($tablename)){
					$arhivetotalrows = $this->archive_db->query("SELECT content_id FROM ".$tablename." WHERE ".$archivequery."")->num_rows();
					$archiverows += $arhivetotalrows;
					if($arhivetotalrows > 0){
						$archivearticles = $this->archive_db->query("SELECT content_id,title,publish_start_date,status, url,articlepageimageid,homepageimageid,sectionpageimageid,article_page_image_path,author_name,created_by,modified_by,modified_on FROM ".$tablename." WHERE ".$archivequery."")->result();
						foreach($archivearticles as $article):
							$edit_url = "edit_archive_article/".$archiverange[$i]."/".urlencode(base64_encode($article->content_id));
							$Style = "";
							if($article->status == 'P' && strtotime($article->publish_start_date) > strtotime(date('d-m-Y H:i:s'))){
								$Style = "style='color:red'";
							}
							$articleDetails = '';
							$articleDetails .='<tr>';
							$articleDetails .='<td>'.$article->content_id.'</td>';
							$articleDetails .='<td><p class="tooltip_cursor" '.$Style.' title="'.strip_tags($article->title).'">'.shortDescription(strip_tags($article->title)).'</p></td>';
							$articleDetails .='<td>'.(GetBreadCrumbByURL($article->url)).'</td>';
							if($article->articlepageimageid != '' ||  $article->homepageimageid || $article->sectionpageimageid ) {
								if($article->articlepageimageid != '0') {
									$Image150X150 	= str_replace("original","w150X150", $article->article_page_image_path);
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
							array_push($articledata , $articleDetails);
							$articleDetails = '';							
						endforeach;
					}
					
				}
			}
			
		}
		$data['total_rows'] = $livetotal +  $archiverows;
		$config['base_url'] = base_url().folder_name.'/article_search';
		$config['total_rows'] = $data['total_rows'];
		$config['per_page'] = 20;
		$config['num_links'] = 5;
		$config['page_query_string'] = TRUE;
		$config['suffix'] = '&contentid='.$this->input->get('contentid');
		$config['first_url'] = base_url().folder_name.'/article_search?&contentid='.$this->input->get('contentid');
		$_GET['per_page'] = (isset($_GET['per_page']) && $_GET['per_page']!='') ? $_GET['per_page'] : 0;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['articles'] =  implode(array_slice($articledata,$_GET['per_page'],$config['per_page']));
		
		$this->load->view('admin_template',$data);
	}
}
?> 