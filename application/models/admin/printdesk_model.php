<?php
class printdesk_model extends CI_Model{

	public function __construct(){
		parent::__construct();
		$this->load->database();
		$CI = &get_instance();
		$this->live_db = $CI->load->database('live_db', TRUE);
	}
	public function GetPrintDeskArticles($Type,$Where=[]){
		$PrintDeskDB=$this->load->database('printdesk_db',TRUE);
		if($Where['search']==false){
			$q="";
		}else{
			$q=$Where['query'];
		}
		if($Type==1){
			return $PrintDeskDB->query("SELECT desk_id,min(version) FROM printdesk_articlemaster WHERE desk_id!='' ".$q." GROUP BY (story_code)")->num_rows();
			//return $PrintDeskDB->query("SELECT desk_id FROM printdesk_articlemaster master1 WHERE version=(SELECT min(version) FROM printdesk_articlemaster master2 WHERE master2.story_code=master1.story_code ) ".$q." GROUP BY (story_code)")->num_rows();

		}
		if($Type==2){
		//	return $PrintDeskDB->query("SELECT desk_id,story_code,title,auth_name,section_name,created_on,status FROM printdesk_articlemaster WHERE desk_id!='' ".$q." ORDER BY desk_id DESC  LIMIT ".$Where['limit']." , ".$Where['per_page']."")->result();
		//	return $PrintDeskDB->query("SELECT desk_id,story_code,title,auth_name,section_name,created_on,status FROM printdesk_articlemaster master1 WHERE version=(SELECT min(version) FROM printdesk_articlemaster master2 WHERE master2.story_code=master1.story_code )  ".$q." GROUP BY (story_code) ORDER BY desk_id DESC LIMIT ".$Where['limit']." , ".$Where['per_page']."")->result();
		
		return $PrintDeskDB->query("SELECT desk_id,story_code,title,auth_name,section_name,created_on,status,min(version) FROM printdesk_articlemaster WHERE desk_id!='' ".$q." GROUP BY (story_code) ORDER BY desk_id DESC  LIMIT ".$Where['limit']." , ".$Where['per_page']."")->result();
		}
	}
	
	public function GetChildNodes($story_code,$desk_id){
		$PrintDeskDB=$this->load->database('printdesk_db',TRUE);
		return $PrintDeskDB->query("SELECT desk_id,story_code,title,auth_name,section_name,created_on,status FROM printdesk_articlemaster  WHERE story_code ='".$story_code."' AND desk_id!='".$desk_id."' ORDER BY desk_id")->result();
	}
	
	public function ArticleEditDetails($ContentID){
		$PrintDeskDB=$this->load->database('printdesk_db',TRUE);
		$Result['print']=$PrintDeskDB->query("SELECT desk_id,story_code,title,summary,content,tags,image_path,section_name,agency_name,auth_name,approved_by,created_by,created_on,modified_on,status FROM printdesk_articlemaster WHERE desk_id='".$ContentID."'")->result();
		$Result['country']=$this->db->query("SELECT Country_id ,CountryName FROM countrymaster")->result();
		return $Result;
	}
	
	public function GetStateAndCityDetails($Id,$type){
		if($type==1){
			return $this->db->query("SELECT State_Id ,StateName FROM statemaster WHERE Country_id='".$Id."'")->result();
		}
		if($type==2){
			return $this->db->query("SELECT City_id ,CityName FROM citymaster WHERE State_Id='".$Id."'")->result();
		}
	}
	
	public function SectionDetails($type,$SectionId=''){
		if($type==1){
			return $this->db->query("SELECT Section_id ,Sectionname FROM sectionmaster WHERE Status=1 AND IsSubSection=0 AND (Sectionname!='Galleries' OR Sectionname!='Videos' OR Sectionname!='Audios' OR  Sectionname!='Resources')")->result();
		}
		if($type==2){
			return $this->db->query("SELECT Section_id ,Sectionname FROM sectionmaster WHERE Status=1 AND ParentSectionID='".$SectionId."'  ")->result();
		}
	}
	
	public function ArticleDetails($parameter,$type,$sectionDetails=[]){
		if($type == 1){
			return $this->db->query("SELECT Section_id , ParentSectionID , URLSectionStructure ,Sectionname , AuthorID FROM sectionmaster WHERE Section_id='".$parameter."'")->result();
		}
		if($type == 2){
			$return=[];
			if($parameter!='' && $parameter!=null):
				$AgencyDetails=$this->db->query("SELECT Agency_id , Agency_name FROM newsagencymaster WHERE Agency_name='".$parameter."'");
				if($AgencyDetails->num_rows()==0){
					$this->db->insert('newsagencymaster',['Agency_name'=>$parameter,'Createdby'=>USERID,'Createdon'=>date('Y-m-d h:i:s'),'Status'=>1]);
					$response = $this->db->insert_id();
					$Result=$this->db->query("SELECT Agency_id , Agency_name FROM newsagencymaster WHERE Agency_id='".$response."'")->result();
					$return['agencyid']=$Result[0]->Agency_id;
					$return['agencyname']=$Result[0]->Agency_name;
				}else{
					$Result=$AgencyDetails->result();
					$return['agencyid']=$Result[0]->Agency_id;
					$return['agencyname']=$Result[0]->Agency_name;
				}
			else:
				$return['agencyid']=null;
				$return['agencyname']=null;
			endif;
			return $return;
		}
		
		if($type==3){
			$return=[];
			if($parameter!='' && $parameter!=null):
				if(@$sectionDetails['AuthorID']!=null && @$sectionDetails['AuthorID']!=''){
					$result=$this->db->query("SELECT  Author_id , AuthorName ,authorType ,image_path ,image_alt ,image_caption FROM authormaster WHERE Author_id='".@$sectionDetails['AuthorID']."'")->result();
					$return['authorid']=$result[0]->Author_id;
					$return['authorname']=$result[0]->AuthorName;
					$return['authortype']=$result[0]->authorType;
					$return['imagepath']=$result[0]->image_path;
					$return['imagealt']=$result[0]->image_alt;
					$return['imagecaption']=$result[0]->image_caption;
				
				}else{
					$AuthorDetails=$this->db->query("SELECT  Author_id , AuthorName ,authorType ,image_path ,image_alt ,image_caption FROM authormaster WHERE AuthorName='".$parameter."'");
					if($AuthorDetails->num_rows()==0){
						$this->db->insert('authormaster',['AuthorName'=>$parameter,'Createdby'=>USERID,'Createdon'=>date('Y-m-d h:i:s'),'Status'=>1,'Modifiedby'=>USERID]);
						$response = $this->db->insert_id();
						$result=$this->db->query("SELECT  Author_id , AuthorName ,authorType ,image_path ,image_alt ,image_caption FROM authormaster WHERE Author_id='".$response."'")->result();
						$return['authorid']=$result[0]->Author_id;
						$return['authorname']=$result[0]->AuthorName;
						$return['authortype']=$result[0]->authorType;
						$return['imagepath']=$result[0]->image_path;
						$return['imagealt']=$result[0]->image_alt;
						$return['imagecaption']=$result[0]->image_caption;
					
					}else{
						$response=$AuthorDetails->result();
						$return['authorid']=$response[0]->Author_id;
						$return['authorname']=$response[0]->AuthorName;
						$return['authortype']=$response[0]->authorType;
						$return['imagepath']=$response[0]->image_path;
						$return['imagealt']=$response[0]->image_alt;
						$return['imagecaption']=$response[0]->image_caption;
					
					}
				
				}
			else:
				$return['authorid']=NULL;
				$return['authorname']='';
				$return['authortype']=NULL;
				$return['imagepath']='';
				$return['imagealt']='';
				$return['imagecaption']='';
			endif;
			
			return $return;
		}
	}
	
	public function SetTags($tags){
		$return=[];
		$return['cmstag']=[];
		$return['livetag']=[];
		if($tags!=''):
			for($i=0;$i<count($tags);$i++){
				$Tag=trim($tags[$i]);
				$GetTag=$this->db->query("SELECT tag_name , tag_id FROM tag_master WHERE tag_name='".$Tag."'");
				if($GetTag->num_rows()==0){
					$this->db->insert('tag_master',['tag_name'=>$Tag,'created_by'=>USERID,'status'=>1,'modified_by'=>USERID]);
					$response=$this->db->insert_id();
					$NewTag=$this->db->query("SELECT tag_name , tag_id FROM tag_master WHERE tag_id='".$response."'")->result();
					array_push($return['cmstag'],"IE".$NewTag[0]->tag_id."IE");
					array_push($return['livetag'],$NewTag[0]->tag_name);
				}else{
					$result=$GetTag->result();
					array_push($return['cmstag'],"IE".$result[0]->tag_id."IE");
					array_push($return['livetag'],$result[0]->tag_name);
				}
			}
		else:
			$return['cmstag']='';
			$return['livetag']='';
		endif;
		return $return;
	}
	
	public function getCountryStateCityDetails($ID,$Type){
		if($Type==1){
			if($ID=='' || $ID==null){
				return '';
			}else{
				$countryName=$this->db->query("SELECT CountryName FROM  countrymaster WHERE Country_id='".$ID."'")->result();
				return @$countryName[0]->CountryName;
			}
		}
		if($Type==2){
			if($ID=='' || $ID==null){
				return '';
			}else{
				$stateName=$this->db->query("SELECT StateName FROM  statemaster WHERE State_Id='".$ID."'")->result();
				return @$stateName[0]->StateName;
			}
		}
		if($Type==3){
			if($ID=='' || $ID==null){
				return '';
			}else{
				$cityName=$this->db->query("SELECT CityName FROM  statemaster WHERE City_id='".$ID."'")->result();
				return @$cityName[0]->CityName;
			}
		}
	
	}
	public function ParentSectionDetails($parentSectionId){
		if($parentSectionId=='' || $parentSectionId==null ){
			$return['parentsectionid']=null;
			$return['parentsectionname']='';
			$return['grantparentsectionid']=null;
			$return['grantparentsectionname']='';
		}else{
			$SectionDetails=$this->db->query("SELECT ParentSectionID , Sectionname FROM sectionmaster WHERE Section_id='".$parentSectionId."'")->result();
			$return['parentsectionid']=$parentSectionId;
			$return['parentsectionname']=$SectionDetails[0]->Sectionname;
			if($SectionDetails[0]->ParentSectionID=='' || $SectionDetails[0]->ParentSectionID==null ){
				$return['grantparentsectionid']=null;
				$return['grantparentsectionname']='';
			}else{
				$GrantSectionDetails=$this->db->query("SELECT Section_id,ParentSectionID , Sectionname FROM sectionmaster WHERE Section_id='".$SectionDetails[0]->ParentSectionID."'")->result();
				$return['grantparentsectionid']=$GrantSectionDetails[0]->Section_id;
				$return['grantparentsectionname']=$GrantSectionDetails[0]->Sectionname;
			}
			
		}
		return $return;
	}
	
	public function InsertArticleToCmsAndLive($CmsArticle , $CmsRelatedData , $CmsSectionMapping , $LiveArticle ,$url){
		$this->db->trans_begin();
		$this->db->insert('articlemaster',$CmsArticle);
		$ContentId=$this->db->insert_id();
		if ($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return 'CMSERROR';
		}else{
			$this->db->trans_commit();
			
			$url .='-'.$ContentId.'.html';
			$CmsRelatedData['content_id']=$ContentId;
			$CmsSectionMapping['content_id']=$ContentId;
			$LiveArticle['content_id']=$ContentId;
			$LiveArticle['url']=strtolower($url);
			
			$this->db->trans_begin();
			$this->db->where('content_id',$ContentId);
			$this->db->update('articlemaster' ,['url' => strtolower($url) ,'Modifiedby' => USERID ,'Modifiedon' => date('Y-m-d h:i:s')]);
			$this->db->insert('articlerelateddata',$CmsRelatedData);
			$this->db->insert('articlesectionmapping',$CmsSectionMapping);
			if ($this->db->trans_status() === FALSE){
				$this->db->where('content_id', $ContentId);
				$this->db->delete('articlemaster');
				return 'CMSERROR';
			}else{
				$this->db->trans_commit();
				return $this->live_db->insert('article',$LiveArticle);
			}
			
			
		}
	
	}
	
	public function InsertImage($imageName){
		$this->db->insert('imagemaster',['ImagePhysicalPath' => $imageName ,'Image1Type' =>2, 'Image2Type'=>2 ,'Image3Type' => 2, 'Image4Type' => 2, 'status' => 1 , 'Createdby' =>USERID ,'Createdon' => date('Y-m-d h:i:s'),'Modifiedby' =>USERID ,'Modifiedon' => date('Y-m-d h:i:s')]);
		$return=[];
		$return['id']=$this->db->insert_id();
		$return['imagename']=$imageName;
		return $return;
	}
	
	public function ChangePrintdeskArticleStatus($DeskID){
		$PrintDeskDB=$this->load->database('printdesk_db',TRUE);
		return $PrintDeskDB->query("UPDATE printdesk_articlemaster SET status=1 WHERE desk_id='".$DeskID."'");
	}
	
	public function LoadMoreImage($limit,$query){
		$limit=(int)$limit;
		$limit=($limit==0)?0 : $limit + 20 ;
		if($query!=''){
			return $this->db->query("SELECT content_id , ImagePhysicalPath FROM imagemaster WHERE status=1 AND (ImageCaption LIKE '%".$query."%' OR ImageAlt LIKE '%".$query."%') ORDER BY content_id DESC LIMIT ".$limit.",20")->result();
		}else{
			return $this->db->query("SELECT content_id , ImagePhysicalPath FROM imagemaster WHERE status=1 ORDER BY content_id DESC LIMIT ".$limit.",20")->result();
		}
		
	}
	
	public function getCustomImageName($imageId){
		$query=$this->db->query("SELECT content_id , ImagePhysicalPath FROM imagemaster WHERE content_id='".$imageId."' ")->result();
		return @$query[0]->ImagePhysicalPath;
	}
	
	public function articlelatest($DeskID){
		$return = [];
		$PrintDeskDB=$this->load->database('printdesk_db',TRUE);
		$Result = $PrintDeskDB->query("SELECT value FROM  article_insert_log  WHERE log_id=1")->result();
		if(@$Result[0]->value == $DeskID){
			$return['old_deskid'] = $DeskID;
			$return['new_deskid'] = @$Result[0]->value;
			$return['status'] = 0;
			$return['title'] = '';
		}else{
			$GetContentDetails = $PrintDeskDB->query("SELECT title FROM  printdesk_articlemaster  WHERE desk_id='".$Result[0]->value."'")->result();
			$return['old_deskid'] = $DeskID;
			$return['new_deskid'] = @$Result[0]->value;
			$return['status'] = 1;
			$return['title'] = strip_tags(@$GetContentDetails[0]->title);
		}
		return $return;
		
	}
}
?> 
