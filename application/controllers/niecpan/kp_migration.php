<?php
ini_set('memory_limit', '-1');
class kp_migration extends CI_Controller{
	
	public function __construct(){
		parent::__construct();
		$CI = &get_instance();
		$this->load->database();
		$this->kp_migration = $this->load->database('kp_migration' , TRUE);
		$this->kp_archive = $this->load->database('archive_db' , TRUE);
		$this->live_db = $this->load->database('live_db' , TRUE);
	}
	
	public function index(){
		$data['title'] = 'Move Article';
		$data['articleCount'] = $this->kp_migration->query("SELECT ArticleID FROM ExpressBuzz_Article")->num_rows();
		$data['archivearticleCount3'] = $this->kp_migration->query("SELECT ArticleID FROM kp_article_archive_2013")->num_rows();
		$data['archivearticleCount4'] = $this->kp_migration->query("SELECT ArticleID FROM kp_article_archive_2014")->num_rows();
		$this->load->view('admin/move_article' , $data);
	}
	
	public function gallery(){
		$data['title'] = 'Move Gallery';
		$data['articleCount'] = $this->kp_migration->query("SELECT GalleryID FROM ExpressBuzz_Gallery")->num_rows();
		$this->load->view('admin/move_gallery' , $data);
	}
	
	public function video(){
		$data['title'] = 'Move Video';
		$data['articleCount'] = $this->kp_migration->query("SELECT VideoID FROM Expressbuzz_Video")->num_rows();
		$this->load->view('admin/move_video' , $data);
	}
	
	public function move_gallery($count){
		$galleryList = $this->kp_migration->query("SELECT GalleryID , CaptionName , GalleryGroupID , CreatedBy , CreatedDate , ModifiedBy , ModifiedDate , ThumbImage , seo , GalDescription , Status FROM ExpressBuzz_Gallery ORDER BY CreatedDate ASC LIMIT ".$count."")->result();
		foreach($galleryList as $gallery){
			$sectionDetails = $this->kp_migration->query("SELECT GalleryGroupName , GalGroupDescription FROM ExpressBuzz_GalleryGroup WHERE GalleryGroupID='".$gallery->GalleryGroupID."'")->row_array();
			$currentSectionDetails = $this->db->query("SELECT Section_id , IsSubSection  , ParentSectionID , URLSectionStructure , URLSectionName , Sectionname FROM sectionmaster WHERE ParentSectionID=24 AND Sectionname='".$sectionDetails ['GalGroupDescription']."'")->row_array();
			if(count($currentSectionDetails) > 0){
				$url = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $gallery->CaptionName));
				$url = strtolower(preg_replace('/-+/', '-', $url));
				$publishedTime = date('/Y/M/d/' , strtotime($gallery->CreatedDate));
				$tags = $this->formTags($gallery->seo);
				$details['ecenic_id'] = $liveDetails['ecenic_id'] = $gallery->GalleryID;
				$details['url_title'] = $url;
				$details['title'] = $details['MetaTitle']  =  $liveDetails['title'] = $liveDetails['meta_Title'] = trim(addslashes($gallery->GalDescription));	
				$details['summaryHTML'] =  $details['MetaDescription'] = $liveDetails['summary_html'] = $liveDetails['meta_description']='';
				$details['Tags'] = $tags['cms'];
				$liveDetails['tags'] = $tags['app'];
				$details['publish_start_date'] = $liveDetails['publish_start_date'] =  date('Y-m-d H:i:s' , strtotime($gallery->CreatedDate));
				$details['Noindexed'] =  $details['Nofollow'] = $liveDetails['no_indexed'] =  $liveDetails['no_follow'] = 0;
				$details['Canonicalurl'] =  $liveDetails['canonical_url'] = '';
				$details['Allowcomments'] =  $liveDetails['allow_comments'] = 1;
				$details['Section_id'] =  $gallerySectionMapping['Section_ID'] =  $liveDetails['section_id'] = $currentSectionDetails['Section_id'];
				$liveDetails['section_name'] =$currentSectionDetails['Sectionname'];
				$liveDetails['parent_section_id'] =24;
				$liveDetails['parent_section_name'] ='ಫೋಟೊ ಗ್ಯಾಲರಿ';
				$liveDetails['grant_section_id'] =null;
				$liveDetails['grant_parent_section_name'] ='';
				$liveDetails['agency_name'] = $liveDetails['author_name'] = $liveDetails['state_name']= $liveDetails['city_name'] ='';
				$liveDetails['country_name'] = 'India';
				$details['Agency_ID'] =  $details['Author_ID'] = null;
				$details['Country_ID'] =  101;
				$details['State_ID'] = $details['City_ID'] =  null;
				$details['status'] = ($gallery->Status==1) ? 'P' : 'U';
				$liveDetails['status'] = $details['status'];
				$Createdby = $this->userDetails($gallery->CreatedBy);
				$details['Createdby'] = $Createdby['userid'];
				$details['Createdon'] = date('Y-m-d H:i:s' , strtotime($gallery->CreatedDate));
				if($gallery->ModifiedBy!=''){
					$Modifiedby = $this->userDetails($gallery->ModifiedBy);
				}else{
					$Modifiedby = $Createdby;
				}
				$details['Modifiedby'] = $Modifiedby['userid'];
				$details['Modifiedon'] =  $liveDetails['last_updated_on'] = date('Y-m-d H:i:s' , strtotime($gallery->ModifiedDate));
				$this->db->trans_begin();
				$this->db->insert('gallerymaster' , $details);
				$contentId = $this->db->insert_id();
				$details['content_id'] = $gallerySectionMapping['content_id'] = $liveDetails['content_id'] = $contentId;
				$currenturl = $currentSectionDetails['URLSectionStructure'].strtolower($publishedTime).$url.'-'.$details['content_id'].'.html';
				$this->db->where('content_id' ,$contentId );
				$this->db->update('gallerymaster' , ['url'=>$currenturl]);
				$liveDetails['url'] = $currenturl;
				if($this->db->trans_status() === FALSE){
					$this->db->where('content_id' , $contentId);
					$this->db->delete('gallerymaster');
					$this->db->trans_rollback();
				}else{
					$this->db->trans_commit();
					$this->db->insert('gallerysectionmapping', $gallerySectionMapping);
					$imagesList = $this->kp_migration->query("SELECT PhotoID FROM ExpressBuzz_GalleryPhotoRelationship WHERE GalleryID='".$gallery->GalleryID."' AND status=1 ORDER BY SortOrder ASC")->result();
					$galleryRelatedCms = $galleryRelatedLive = [];
					$count=1;
					foreach($imagesList as $image){
						$photoDetails = $this->kp_migration->query("SELECT CaptionName , Path FROM ExpressBuzz_Photo WHERE PhotoID='".$image->PhotoID."'")->row_array();
						$serverpath = '/images/';
						$imagefile = str_replace(['../I','../i'],['i','i'],$photoDetails['Path']);
						if(file_exists($serverpath.$imagefile)){
							$Year = date('Y' , strtotime($gallery->CreatedDate));
							$Month = date('n' , strtotime($gallery->CreatedDate));
							$Day = date('j' , strtotime($gallery->CreatedDate));
							$destinationPath = destination_base_path.imagelibrary_image_path;
							$yearPath = $destinationPath.$Year.'/';
							$monthPath = $yearPath.$Month.'/';
							$dayPath = $monthPath.$Day.'/';
							$img900X450 = $dayPath.'w900X450/';
							$img600X390 = $dayPath.'w600X390/';
							$img600X300 = $dayPath.'w600X300/';
							$img150X150 = $dayPath.'w150X150/';
							$img100X65 = $dayPath.'w100X65/';
							$originalPath = $dayPath.'original/';
							if(!is_dir($yearPath)){ mkdir($yearPath); chmod($yearPath , 0777); }
							if(!is_dir($monthPath)){ mkdir($monthPath); chmod($monthPath , 0777); }
							if(!is_dir($dayPath)){ mkdir($dayPath); chmod($dayPath , 0777); }
							if(!is_dir($img900X450)){ mkdir($img900X450); chmod($img900X450 , 0777); }
							if(!is_dir($img600X390)){ mkdir($img600X390); chmod($img600X390 , 0777); }
							if(!is_dir($img600X300)){ mkdir($img600X300); chmod($img600X300 , 0777); }
							if(!is_dir($img150X150)){ mkdir($img150X150); chmod($img150X150 , 0777); }
							if(!is_dir($img100X65)){ mkdir($img100X65); chmod($img100X65 , 0777); }
							if(!is_dir($originalPath)){ mkdir($originalPath); chmod($originalPath , 0777); }
							$ImageName = substr($imagefile , strrpos($imagefile , '/') + 1 , strlen($imagefile));
							$response = copy($serverpath.$imagefile,$originalPath.$ImageName);
							if($response==1){
								chmod($originalPath.$ImageName , 0777);
								$this->image_resize($originalPath.$ImageName , $img900X450 , 900 , 450 ,$ImageName);
								$this->image_resize($originalPath.$ImageName , $img600X390 , 600 , 390 ,$ImageName);
								$this->image_resize($originalPath.$ImageName , $img600X300 , 600 , 300 ,$ImageName);
								$this->image_resize($originalPath.$ImageName , $img150X150 , 150 , 150 ,$ImageName);
								$this->image_resize($originalPath.$ImageName , $img100X65 , 100 , 65 ,$ImageName);
								$date = date('Y-m-d H:i:s');
								$imglivepath = $Year.'/'.$Month.'/'.$Day.'/original/'.$ImageName;
								$imageData = ['ImageCaption' => trim($photoDetails['CaptionName']) , 'ImageAlt' => null , 'ImagePhysicalPath' => $imglivepath, 'Image1Type' => 1 , 'Image2Type' => 1 , 'Image3Type' => 1 ,'Image4Type' => 1 , 'status' => 1 ,'Createdby' => 149 , 'Createdon' => $date , 'Modifiedby' => 149 , 'Modifiedon' => $date];
								$this->db->insert('imagemaster' ,$imageData);
								$imgid = $this->db->insert_id();
								$galleryRelatedCms[] = ['content_id' => $contentId , 'ImageID' => $imgid , 'display_order' => $count];
								$galleryRelatedLive[] = ['content_id' => $contentId , 'gallery_image_path' => $imglivepath , 'gallery_image_title' =>  trim($photoDetails['CaptionName']) , 'gallery_image_alt' => '' , 'display_order' => $count];
								$count++;
							}
						}
					}
					if(count($galleryRelatedLive) > 0){
						$liveDetails['first_image_path'] = $galleryRelatedLive[0]['gallery_image_path'];
						$liveDetails['first_image_title'] = $galleryRelatedLive[0]['gallery_image_title'];	
						$liveDetails['first_image_alt'] = '';
					}
					if(count($galleryRelatedCms) > 0){
						$this->db->insert_batch('galleryrelatedimages' , $galleryRelatedCms);
					}
					if($details['status']=='P'){
						
						$this->live_db->insert('gallery' ,$liveDetails);
						$this->live_db->insert('gallery_section_mapping' ,['content_id' => $contentId , 'section_id' => $details['Section_id']]);
						if(count($galleryRelatedLive) > 0){
							$this->live_db->insert_batch('gallery_related_images' , $galleryRelatedLive);
						}
					}
					 $this->kp_migration->insert('temp_gallery' , ['id' => $gallery->GalleryID]);
					 $this->kp_migration->delete('ExpressBuzz_Gallery' , ['GalleryID' => $gallery->GalleryID]);
				}
				
			}else{
				$this->kp_migration->query("INSERT INTO ExpressBuzz_Gallery_error SELECT * FROM ExpressBuzz_Gallery WHERE GalleryID='".$gallery->GalleryID."'");
				$this->kp_migration->delete('ExpressBuzz_Gallery' , ['GalleryID' => $gallery->GalleryID]);
			}
			$details = $liveDetails=''; 
		}
	}
	
	public function movearticle($dbtype=1 ,$count=0){
		$tableName = ($dbtype==1) ? 'ExpressBuzz_Article' : 'kp_article_archive_2013';
		if($dbtype==1){
			$tableName = 'ExpressBuzz_Article';
			$errortbl = 'ExpressBuzz_Article_error';
			$tmptbl = 'temp_live';
		}else if($dbtype==2){
			$tableName = 'kp_article_archive_2013';
			$errortbl = 'kp_article_archive_2013_error';
			$tmptbl = 'temp_2013';
		}else{
			$tableName = 'kp_article_archive_2014';
			$errortbl = 'kp_article_archive_2014_error';
			$tmptbl = 'temp_2014';
		}
		$sortyBY = ' ORDER BY ArticleID ASC';
		$articleList = $this->kp_migration->query("SELECT ArticleID , NewsID , SectionID , Status , Title , HeadLine30 , HeadLine1 , HeadLine2 , DetailNews , PublishDate , ExpiryDate , ImpressionCount , ReadCount , PrintCount , BookmarkCount , Emailcount , AuthorID , ApproverID , ThumbnailPhoto , LargePhoto , FlashVsVideo , GalleryID , AuthorBannerValid , RelatedArticles , SEOKeywords , CreatedBy , CreatedDate , ModifiedBy , ModifiedDate , XmlSourcePath , multipleSection , PhotoCaption , CommentEnabled , Agency , SortPriority , RelatedTitle , VideoCode , SeoSummary , PhotoAlt , URI FROM ".$tableName."".$sortyBY." LIMIT ".$count."")->result();
		foreach($articleList as $article):
			$sectionDetails = $this->sectionDetails($article->SectionID);
			if(count($sectionDetails['section']) > 0){
				$publishedYear = date('Y' , strtotime($article->PublishDate));
				if($publishedYear < date('Y')){
					$this->movearchivearticle($article , $publishedYear , $tableName , $sectionDetails , $tmptbl);
				}else{
					$this->movelivearticle($article , $publishedYear , $tableName , $sectionDetails ,$tmptbl);
				} 
			}else{
				$this->kp_migration->query("INSERT INTO ".$errortbl." SELECT * FROM ".$tableName." WHERE ArticleID='".$article->ArticleID."'");
				 $this->kp_migration->delete($tableName , ['ArticleID' => $article->ArticleID]); 
			}
			
		endforeach;
	}
	
	public function movearchivearticle($article , $year ,$tableName , $sectionDetails ,$tmptbl){
		$this->hasTable($year);
		$details = [];
		$sectionmapping =[];
		//allocate contentID in articlemaster
		$this->db->query("INSERT INTO articlemaster (title) VALUES('articlemster')");
		$contentID =  $this->db->insert_id();
		if($contentID!='' && $contentID!=0){
			$this->db->query("DELETE FROM articlemaster WHERE content_id='".$contentID."'");
			$details['content_id'] = $contentID;
			$details['ecenic_id'] = $article->ArticleID;
			$tags = $this->formTags($article->SEOKeywords);
			$details['tag_ids'] = $tags['cms'];
			$details['tags'] = $tags['app'];
			$details['title'] = trim(addslashes($article->HeadLine1));
			$details['summary_html'] = trim(addslashes($article->HeadLine2));
			$details['article_page_content_html'] = trim(addslashes($article->DetailNews));
			$publishedTime = date('/Y/M/d/' , strtotime($article->PublishDate));
			$details['url'] = $sectionDetails['section']['URLSectionStructure'].strtolower($publishedTime).$article->URI.'-'.$details['content_id'].'.html';
			$details['section_id'] = $sectionDetails['section']['Section_id'];
			$details['section_name'] = $sectionDetails['section']['Sectionname'];
			$sectionmapping = ['content_id' => $contentID ,'section_id' => $details['section_id']];
			$details['parent_section_id'] = $details['parent_section_name'] = $details['grant_section_id'] = $details['grant_parent_section_name'] = null;
			if(count($sectionDetails['parentSection']) > 0){
				$details['parent_section_id'] = $sectionDetails['parentSection']['Section_id'];
				$details['parent_section_name'] = $sectionDetails['parentSection']['Sectionname'];
			}
			$details['publish_start_date'] = date('Y-m-d H:i:s' , strtotime($article->PublishDate));
			$details['created_on']  = date('Y-m-d H:i:s' , strtotime($article->CreatedDate));
			if($article->ModifiedDate!='0000-00-00 00:00:00'){
				$details['modified_on']  =  $details['last_updated_on'] =date('Y-m-d H:i:s' , strtotime($article->ModifiedDate));
			}else{
				$details['modified_on']  =  $details['last_updated_on'] =date('Y-m-d H:i:s' , strtotime($article->CreatedDate));
			}
			$details['allow_comments']  =  $details['no_indexed'] = $details['no_follow'] = 1;
			$details['allow_pagination'] =0;
			$details['meta_Title'] = $details['title'];
			$details['meta_description'] = $details['summary_html'];
			
			$details['status'] =($article->Status==1) ?  'P' : 'U';
			$details['created_by'] = $article->CreatedBy;
			$details['modified_by'] = $article->ModifiedBy;
			$agencyDetails = $this->formAgency($article->Agency);
			//$authorDetails = $this->formAgency($article->Agency);
			$details['agency_name'] = $agencyDetails['name'];
			$authorDetails = $this->formAuthor($article->AuthorID);
			$details['author_id'] = $authorDetails['id'];
			$details['author_name'] = $authorDetails['name'];
			$details['author_image_path'] = $authorDetails['imagepath'];
			$details['author_image_title'] = $authorDetails['caption'];
			$details['author_image_alt'] = $authorDetails['alt'];
			$ImageDetails = $this->formImage($article->LargePhoto ,$article);
			if($ImageDetails[0]!=null){
				$details['articlepageimageid']  = $ImageDetails[0];
				$details['article_page_image_path']  = $ImageDetails[1]['ImagePhysicalPath'];
				$details['article_page_image_title']  = $ImageDetails[1]['ImageCaption'];
				$details['article_page_image_alt']  = $ImageDetails[1]['ImageAlt'];
			}
			//$this->kp_archive->save_queries = TRUE;
			//$this->kp_archive->trans_begin();
			$res = $this->kp_archive->insert('article_'.$year , $details);
			
			//echo $this->kp_archive->last_query();
		//	$this->kp_archive->insert('article_section_mapping_'.$year , $sectionmapping);
			/* if($this->kp_archive->trans_status() === FALSE){
				$this->kp_archive->trans_rollback();
			}else{
				 $this->kp_archive->trans_commit();
				 $this->kp_migration->insert($tmptbl , ['id' => $article->ArticleID]);
				$this->kp_migration->delete($tableName , ['ArticleID' => $article->ArticleID]); 
			} */
			
			if($res==1){
				$checkecenic = $this->kp_archive->query("SELECT ecenic_id FROM article_".$year." WHERE ecenic_id='".$article->ArticleID."'")->row_array();
				if(is_array($checkecenic) && count($checkecenic) > 0){
					$this->kp_migration->insert($tmptbl , ['id' => $article->ArticleID]);
					$this->kp_migration->delete($tableName , ['ArticleID' => $article->ArticleID]);
				}
			}
		}
	}
	
	public function movelivearticle($article , $year ,$tableName ,$sectionDetails , $tmptbl){
		$details = $livedetails = [];
		$sectionmapping =[];
		$articlerelated = [];
		$details['ecenic_id'] = $livedetails['ecenic_id'] = $article->ArticleID;	
		$details['url_title'] = $article->URI;
		$details['title'] = $livedetails['title'] =  $details['MetaTitle'] = $livedetails['meta_Title'] = trim(addslashes($article->HeadLine1));
		$details['summaryHTML'] = $livedetails['summary_html'] = $details['MetaDescription'] = $livedetails['meta_description'] = trim(addslashes($article->HeadLine2));
		$details['ArticlePageContentHTML'] = $livedetails['article_page_content_html'] = trim(addslashes($article->DetailNews));
		$details['publish_start_date'] =  $livedetails['publish_start_date'] = date('Y-m-d H:i:s' , strtotime($article->PublishDate));
		$tags = $this->formTags($article->SEOKeywords);
		$details['Tags'] = $tags['cms'];
		$livedetails['tags'] = $tags['app'];
		$details['Noindexed'] = $details['Nofollow'] = $details['section_promotion'] = $details['link_to_resource'] = $livedetails['no_indexed'] = $livedetails['no_follow'] = $livedetails['section_promotion'] =  $livedetails['link_to_resource'] = 0;
		$details['Allowcomments'] =  $livedetails['allow_comments'] = 1;
		$details['allow_pagination'] = $livedetails['allow_pagination'] =0;
		$details['Canonicalurl'] = $livedetails['canonical_url'] ='';
		$details['status'] = ($article->Status==1) ? 'P' : 'U';
		$livedetails['status'] = $details['status'];
		$details['Createdon']  = date('Y-m-d H:i:s' , strtotime($article->CreatedDate));
		if($article->ModifiedDate!='0000-00-00 00:00:00'){
			$details['Modifiedon']  = date('Y-m-d H:i:s' , strtotime($article->ModifiedDate));
		}else{
			$details['Modifiedon']  = date('Y-m-d H:i:s' , strtotime($article->CreatedDate));
		}
		
		$userdetails = $this->userDetails($article->CreatedBy);
		$details['Createdby'] = $userdetails['userid'];
		$userdetails = $this->userDetails($article->ModifiedBy);
		$details['Modifiedby'] = $userdetails['userid'];
		$ImageDetails = $this->formImage($article->LargePhoto ,$article);
		$agencyDetails = $this->formAgency($article->Agency);
		$authorDetails = $this->formAuthor($article->AuthorID);
		$livedetails['author_name'] = $authorDetails['name'];
		$livedetails['author_image_path'] = $authorDetails['imagepath'];
		$livedetails['author_image_title'] = $authorDetails['caption'];
		$livedetails['author_image_alt'] = $authorDetails['alt'];
		$publishedTime = date('/Y/M/d/' , strtotime($article->PublishDate));
		//$this->db->trans_begin();
		$this->db->insert('articlemaster' , $details);
		$contentId = $this->db->insert_id();
		$livedetails['content_id'] = $contentId;
		$livedetails['section_id'] = $sectionDetails['section']['Section_id'];
		$livedetails['section_name'] = $sectionDetails['section']['Sectionname'];
		$livedetails['parent_section_id'] = (count($sectionDetails['parentSection']) > 0) ? $sectionDetails['parentSection']['Section_id'] : null;
		$livedetails['parent_section_name'] = (count($sectionDetails['parentSection']) > 0) ? $sectionDetails['parentSection']['Sectionname'] : '';
		$livedetails['grant_section_id'] = null;
		$livedetails['grant_parent_section_name'] = '';
		$livedetails['linked_to_columnist'] = 0;
		if($article->ModifiedDate!='0000-00-00 00:00:00'){
			$livedetails['last_updated_on'] = date('Y-m-d H:i:s' , strtotime($article->ModifiedDate));
		}else{
			$livedetails['last_updated_on'] = date('Y-m-d H:i:s' , strtotime($article->CreatedDate));
		}
		
		$url = $sectionDetails['section']['URLSectionStructure'].strtolower($publishedTime).$article->URI.'-'.$contentId.'.html';
		$livedetails['url'] = $url;
		if($ImageDetails[0]!=null){
			$livedetails['article_page_image_path']  = $ImageDetails[1]['ImagePhysicalPath'];
			$livedetails['article_page_image_title']  = $ImageDetails[1]['ImageCaption'];
			$livedetails['article_page_image_alt']  = $ImageDetails[1]['ImageAlt'];
		}
		$livedetails['agency_name'] = $agencyDetails['name'];
		$livedetails['country_name'] = 'India';
		
		if($contentId!='' && $contentId!=0){
			$this->db->where('content_id' ,$contentId );
			$this->db->update('articlemaster' , ['url'=>$url]);
			$articlerelated = array('content_id' => $contentId , 'Section_id' => $sectionDetails['section']['Section_id'] , 'Agency_ID' => $agencyDetails['id'] , 'Author_ID' =>$authorDetails['id'] , 'Country_ID' =>101 , 'State_ID' =>null , 'City_ID' => null , 'homepageimageid' => null , 'Sectionpageimageid' => null , 'articlepageimageid' =>  $ImageDetails[0]);
			$this->db->insert('articlerelateddata' , $articlerelated);
			$this->db->insert('articlesectionmapping' , ['content_id' => $contentId , 'Section_ID' => $sectionDetails['section']['Section_id']]);
			if($article->Status==1){
				$this->live_db->insert('article' , $livedetails);
				$this->live_db->insert('article_section_mapping' , ['content_id' => $contentId , 'section_id' => $sectionDetails['section']['Section_id']]);
			}
			$checkecenic = $this->db->query("SELECT ecenic_id FROM articlemaster WHERE ecenic_id='".$article->ArticleID."'")->row_array();
			if(is_array($checkecenic) && count($checkecenic) > 0){
				$this->kp_migration->delete($tableName , ['ArticleID' => $article->ArticleID]);
				$this->kp_migration->insert($tmptbl , ['id' => $article->ArticleID]);
			}
		}
		
		/* if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			$this->db->where('content_id' , $contentId);
			$this->db->delete('articlesectionmapping');
			$this->db->where('content_id' , $contentId);
			$this->db->delete('articlerelateddata');
			$this->db->where('content_id' , $contentId);
			$this->db->delete('articlemaster');
		}else{
			$this->db->trans_commit();
			
		} */
	}
	
	public function move_video($count=0){
		$videoList = $this->kp_migration->query("SELECT VideoID , CatName ,CatID , SubCatID , SubCatName , Title , Headline1 , Headline2 , Byline , VideoCode , VideoCaption , PublishedDate , ThumbImage , Keywords , Status FROM Expressbuzz_Video ORDER BY VideoID ASC LIMIT ".$count."")->result();
		foreach($videoList as $video){
			$sectionDetails =  $this->kp_migration->query("SELECT Description ,Name FROM Expressbuzz_VideoSubGroup WHERE ID='".$video->SubCatID."'")->row_array();
			$currentSectionDetails = $this->db->query("SELECT Section_id , IsSubSection  , ParentSectionID , URLSectionStructure , URLSectionName , Sectionname FROM sectionmaster WHERE ParentSectionID=30 AND Sectionname='".$sectionDetails ['Description']."'")->row_array();
			if(count($currentSectionDetails) >0){
				$title = ($video->Headline1!='') ? $video->Headline1 : $video->Headline2;
				$url = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $title));
				$url = strtolower(preg_replace('/-+/', '-', $url));
				$publishedTime = date('/Y/M/d/' , strtotime($video->PublishedDate));
				$tags = $this->formTags($video->Keywords);
				
				$details['ecenic_id'] = $liveDetails['ecenic_id'] = $video->VideoID;
				$details['url_title'] = $url;
				$details['title'] = $details['MetaTitle'] = $liveDetails['title'] = $liveDetails['meta_Title'] = trim(addslashes($video->Headline2));
				$details['summaryHTML'] = $liveDetails['summary_html'] = trim(addslashes($video->VideoCaption));
				$details['Tags'] = $tags['cms'];
				$details['MetaDescription'] =  $liveDetails['meta_description'] = '';
				$details['publish_start_date'] = $liveDetails['publish_start_date'] = $liveDetails['last_updated_on'] = date('Y-m-d H:i:s' , strtotime($video->PublishedDate));
				$details['Noindexed'] = $details['Nofollow']= $liveDetails['no_indexed'] = $liveDetails['no_follow']= 0;
				$details['Canonicalurl'] = $liveDetails['canonical_url'] = '';
				$details['Allowcomments'] =  $liveDetails['allow_comments'] = 1;
				$details['Section_id'] =  $liveDetails['section_id'] = $currentSectionDetails['Section_id'];
				$liveDetails['section_name'] = $currentSectionDetails['Sectionname'];
				$liveDetails['parent_section_id'] = 30;
				$liveDetails['parent_section_name'] = 'ವಿಡಿಯೋ';
				$liveDetails['grant_section_id'] = null;
				$liveDetails['grant_parent_section_name'] = '';
				$liveDetails['agency_name'] = $liveDetails['author_name']  = $liveDetails['country_name'] = $liveDetails['state_name']  = $liveDetails['city_name'] ='';
				$details['Agency_ID'] =  $details['Author_ID'] = $details['Country_ID'] =  $details['State_ID'] =   $details['City_ID'] = null;
				$details['VideoScript'] = $liveDetails['video_script'] = $video->VideoCode;
				$details['status'] = $liveDetails['status'] = ($video->Status==1) ? 'P' : 'U'; 
				$details['Createdby'] = $details['Modifiedby'] = 173; 
				$details['Createdon'] = $details['Modifiedon'] =date('Y-m-d H:i:s' , strtotime($video->PublishedDate));
				$serverpath = '/images/';
				$imagefile = str_replace(['../I','../i'],['i','i'],$video->ThumbImage);
				if(file_exists($serverpath.$imagefile)){
					$Year = date('Y' , strtotime($video->PublishedDate));
					$Month = date('n' , strtotime($video->PublishedDate));
					$Day = date('j' , strtotime($video->PublishedDate));
					$destinationPath = destination_base_path.imagelibrary_image_path;
					$yearPath = $destinationPath.$Year.'/';
					$monthPath = $yearPath.$Month.'/';
					$dayPath = $monthPath.$Day.'/';
					$img900X450 = $dayPath.'w900X450/';
					$img600X390 = $dayPath.'w600X390/';
					$img600X300 = $dayPath.'w600X300/';
					$img150X150 = $dayPath.'w150X150/';
					$img100X65 = $dayPath.'w100X65/';
					$originalPath = $dayPath.'original/';
					if(!is_dir($yearPath)){ mkdir($yearPath); chmod($yearPath , 0777); }
					if(!is_dir($monthPath)){ mkdir($monthPath); chmod($monthPath , 0777); }
					if(!is_dir($dayPath)){ mkdir($dayPath); chmod($dayPath , 0777); }
					if(!is_dir($img900X450)){ mkdir($img900X450); chmod($img900X450 , 0777); }
					if(!is_dir($img600X390)){ mkdir($img600X390); chmod($img600X390 , 0777); }
					if(!is_dir($img600X300)){ mkdir($img600X300); chmod($img600X300 , 0777); }
					if(!is_dir($img150X150)){ mkdir($img150X150); chmod($img150X150 , 0777); }
					if(!is_dir($img100X65)){ mkdir($img100X65); chmod($img100X65 , 0777); }
					if(!is_dir($originalPath)){ mkdir($originalPath); chmod($originalPath , 0777); }
					$ImageName = substr($imagefile , strrpos($imagefile , '/') + 1 , strlen($imagefile));
					$response = copy($serverpath.$imagefile,$originalPath.$ImageName);
					if($response==1){
						chmod($originalPath.$ImageName , 0777);
						$this->image_resize($originalPath.$ImageName , $img900X450 , 900 , 450 ,$ImageName);
						$this->image_resize($originalPath.$ImageName , $img600X390 , 600 , 390 ,$ImageName);
						$this->image_resize($originalPath.$ImageName , $img600X300 , 600 , 300 ,$ImageName);
						$this->image_resize($originalPath.$ImageName , $img150X150 , 150 , 150 ,$ImageName);
						$this->image_resize($originalPath.$ImageName , $img100X65 , 100 , 65 ,$ImageName);
						$date = date('Y-m-d H:i:s');
						$imglivepath = $Year.'/'.$Month.'/'.$Day.'/original/'.$ImageName;
						$imageData = ['ImageCaption' => trim(addslashes($video->Headline1)) , 'ImageAlt' => null , 'ImagePhysicalPath' => $imglivepath, 'Image1Type' => 0 , 'Image2Type' => 0 , 'Image3Type' => 0 ,'Image4Type' => 0 , 'status' => 1 ,'Createdby' => 149 , 'Createdon' => $date , 'Modifiedby' => 149 , 'Modifiedon' => $date];
						$this->db->insert('imagemaster' ,$imageData);
						$imgid = $this->db->insert_id();
						$details['image_id'] = $imgid;
						$liveDetails['video_image_path'] = $imglivepath;
						$liveDetails['video_image_title'] = trim(addslashes($video->Headline1));
						$liveDetails['video_image_alt'] = '';
						$liveDetails['tags'] = $tags['app'];
					}
				}

				$this->db->trans_begin();
				$t = $this->db->insert('videomaster' , $details);
				$contentId = $this->db->insert_id(); 
				$details['content_id'] = $liveDetails['content_id'] = $contentId;
				$currenturl = $currentSectionDetails['URLSectionStructure'].strtolower($publishedTime).$url.'-'.$details['content_id'].'.html';
				$this->db->where('content_id' ,$contentId );
				$this->db->update('videomaster' , ['url'=>$currenturl]);
				$liveDetails['url'] = $currenturl;
				if($this->db->trans_status() === FALSE){
					$this->db->where('content_id' , $contentId);
					$this->db->delete('videomaster');
					$this->db->trans_rollback();
				}else{
					$this->db->trans_commit();
					$this->db->insert('videosectionmapping', ['content_id' => $details['content_id'] , 'Section_ID' => $details['Section_id']]);
					if($details['status']=='P'){
						$this->live_db->insert('video',$liveDetails);
						$this->live_db->insert('video_section_mapping', ['content_id' => $details['content_id'] , 'section_id' => $details['Section_id']]);
					}
					$this->kp_migration->insert('temp_video' , ['id' => $video->VideoID]);
					$this->kp_migration->delete('Expressbuzz_Video' , ['VideoID' => $video->VideoID]);
				}
			}else{
				$this->kp_migration->query("INSERT INTO Expressbuzz_Video_error SELECT * FROM Expressbuzz_Video WHERE VideoID='".$video->VideoID."'");
				$this->kp_migration->delete('Expressbuzz_Video' , ['VideoID' => $video->VideoID]);
			}
			$details = $liveDetails='';
		}
	}
	
	public function formTags($tags){
		$tags = explode(',',$tags);
		$cms = $app = [];
		for($i=0;$i<count($tags);$i++):
			$tag = trim($tags[$i]);
			if($tag!=''){
				$response = $this->db->query("SELECT tag_id , tag_name FROM tag_master WHERE tag_name='".addslashes($tag)."' LIMIT 1");
				if($response->num_rows() > 0){
					$result = $response->row_array();
					array_push($cms , 'IE'.$result['tag_id'].'IE');
					array_push($app , $result['tag_name']);
				}else{
					$this->db->insert('tag_master' , ['tag_name'=>addslashes($tag) , 'created_by'=>USERID , 'modified_by' => USERID]);
					array_push($cms , 'IE'.$this->db->insert_id().'IE');
					array_push($app , addslashes($tag));
					
				}
			}
		endfor;
		return ['cms'=>implode(',' ,$cms) , 'app'=>implode(',' ,$app)];
	}
	
	public function formAgency($agencyName){
		$agencyName = trim($agencyName);
		$response['name'] = $response['id'] = '';
		if($agencyName!=''){
			$query = $this->db->query("SELECT Agency_id FROM newsagencymaster WHERE Agency_name='".$agencyName."' AND Status=1");
			if($query->num_rows() > 0){
				$result = $query->row_array();
				$response['name'] = $agencyName;
				$response['id'] = $result['Agency_id'];
			}else{
				$date = date('Y-m-d H:i:s');
				$this->db->insert('newsagencymaster' , ['Status' => 1 , 'Createdon' => $date  , 'Createdby' => 149 , 'Modifiedon' => $date ,'Modifiedby' => 149 ,'Agency_name' => $agencyName]);
				$response['name'] = $agencyName;
				$response['id'] = $this->db->insert_id();
			}
		}
		return $response;
	}
	
	public function formAuthor($authorID){
		$result = ['id' => 0, 'name' => null ,  'imagepath' => '' , 'alt' => null , 'caption' => null];
		if($authorID!='' && $authorID!=null){
			$details = $this->kp_migration->query("SELECT AliasName , AuthorPhoto , LoginEmail ,Biography FROM ExpressBuzz_Author WHERE AuthorID='".$authorID."'")->row_array();
			if(count($details) > 0){
				$date = date('Y-m-d H:i:s');
				$authorDetails = $this->db->query("SELECT Author_id , AuthorName , image_path ,image_alt , image_caption FROM  authormaster WHERE AuthorName='".$details['AliasName']."' LIMIT 1")->row_array();
				if(count($authorDetails) > 0){
					$result = ['id' => $authorDetails['Author_id'] , 'name' => $authorDetails['AuthorName'] ,  'imagepath' => $authorDetails['image_path'] , 'alt' => $authorDetails['image_alt'] , 'caption' => $authorDetails['image_caption']];
				}else{
					$data =['authorType' => 2 , 'Createdby' => 149 , 'Modifiedby' => 149 , 'Createdon' => $date , 'Modifiedon' => $date , 'AuthorName' => $details['AliasName'] , 'ShortBiography' => $details['Biography']];
					$this->db->insert('authormaster' , $data);
					$author =$this->db->insert_id();
					if($details['AuthorPhoto']!=''){
						$serverpath = '/images/';
						$imagefile = str_replace(['../I','../i'],['i','i'],$details['AuthorPhoto']);
						$file='';
						if(file_exists($serverpath.$imagefile)){
							$file = destination_base_path.columinst_image_path.$author.'.jpg';
							$response = copy($serverpath.$imagefile,$file);
							if($response==1){
								chmod($file,0777);
							}
							$this->db->where('Author_id' , $author);
							$this->db->update('authormaster' , ['image_path' => columinst_image_path.$author.'.jpg']);
							
						}
					}
					$result = ['id' => $author, 'name' => $details['AliasName'] ,  'imagepath' => $file , 'alt' => '' , 'caption' => ''];
				}
				
			}
		}
		
		return $result;
	}
	
	public function userDetails($name){
		$details = $this->db->query("SELECT User_id , Username FROM usermaster WHERE Username='".trim($name)."'");
		if($details->num_rows() > 0){
			$result = $details->row_array();
			return ['userid' => $result['User_id'] , 'username' => $result['Username'] ];
		}else{
			$date = date('Y-m-d H:i:s');
			$data = array('role_id' => 18 , 'status' => 1 , 'Createdby' => 149 , 'Createdon' => $date , 'Modifiedby' => 149 , 'Modifiedon' => $date , 'Username' =>trim($name) , 'Password' => md5(trim($name)) , 'Firstname' => trim($name) , 'Lastname' => trim($name));
			$this->db->insert('usermaster' , $data);
			return ['userid' => $this->db->insert_id() , 'username' => trim($name) ];
		}
	}
	
	public function formImage($imagefile='',$article){
		//$serverpath = '/var/www/html/kannadaprabha/temp/';
		$serverpath = '/images/';
		if($imagefile!=''){
			$imagefile = str_replace(['../I','../i'],['i','i'],$imagefile);
		}
		if(file_exists($serverpath.$imagefile)){
			$Year = date('Y' , strtotime($article->PublishDate));
			$Month = date('n' , strtotime($article->PublishDate));
			$Day = date('j' , strtotime($article->PublishDate));
			$destinationPath = destination_base_path.imagelibrary_image_path;
			$yearPath = $destinationPath.$Year.'/';
			$monthPath = $yearPath.$Month.'/';
			$dayPath = $monthPath.$Day.'/';
			$img900X450 = $dayPath.'w900X450/';
			$img600X390 = $dayPath.'w600X390/';
			$img600X300 = $dayPath.'w600X300/';
			$img150X150 = $dayPath.'w150X150/';
			$img100X65 = $dayPath.'w100X65/';
			$originalPath = $dayPath.'original/';
			if(!is_dir($yearPath)){ mkdir($yearPath); chmod($yearPath , 0777); }
			if(!is_dir($monthPath)){ mkdir($monthPath); chmod($monthPath , 0777); }
			if(!is_dir($dayPath)){ mkdir($dayPath); chmod($dayPath , 0777); }
			if(!is_dir($img900X450)){ mkdir($img900X450); chmod($img900X450 , 0777); }
			if(!is_dir($img600X390)){ mkdir($img600X390); chmod($img600X390 , 0777); }
			if(!is_dir($img600X300)){ mkdir($img600X300); chmod($img600X300 , 0777); }
			if(!is_dir($img150X150)){ mkdir($img150X150); chmod($img150X150 , 0777); }
			if(!is_dir($img100X65)){ mkdir($img100X65); chmod($img100X65 , 0777); }
			if(!is_dir($originalPath)){ mkdir($originalPath); chmod($originalPath , 0777); }
			$ImageName = substr($imagefile , strrpos($imagefile , '/') + 1 , strlen($imagefile));
			if(file_exists($originalPath.$ImageName)){
				
			}
			$response = copy($serverpath.$imagefile,$originalPath.$ImageName);
			if($response==1){
				chmod($originalPath.$ImageName , 0777);
				$this->image_resize($originalPath.$ImageName , $img900X450 , 900 , 450 ,$ImageName);
				$this->image_resize($originalPath.$ImageName , $img600X390 , 600 , 390 ,$ImageName);
				$this->image_resize($originalPath.$ImageName , $img600X300 , 600 , 300 ,$ImageName);
				$this->image_resize($originalPath.$ImageName , $img150X150 , 150 , 150 ,$ImageName);
				$this->image_resize($originalPath.$ImageName , $img100X65 , 100 , 65 ,$ImageName);
				$date = date('Y-m-d H:i:s');
				$imglivepath = $Year.'/'.$Month.'/'.$Day.'/original/'.$ImageName;
				$imageData = ['ImageCaption' => $article->PhotoCaption , 'ImageAlt' => $article->PhotoAlt , 'ImagePhysicalPath' => $imglivepath, 'Image1Type' => 0 , 'Image2Type' => 0 , 'Image3Type' => 0 ,'Image4Type' => 0 , 'status' => 1 ,'Createdby' => 149 , 'Createdon' => $date , 'Modifiedby' => 149 , 'Modifiedon' => $date];
				$this->db->insert('imagemaster' ,$imageData);
				return [$this->db->insert_id() , $imageData];	
			}else{
				return [null ,''];
			}
			
		}else{
			return [null ,''];
		}
		
		
	}
	
	public function image_resize($originalPath , $desPath , $width , $height,$fileName){
		 $this->load->library('image_lib');
		$config['image_library'] = 'gd2';
		$config['source_image'] = $originalPath;
		$config['create_thumb'] = FALSE;
		$config['maintain_ratio'] = FALSE;
		$config['width'] = $width;
		$config['height'] = $height;
		$config['quality']= 75;
		$config['new_image']=$desPath;
		$this->image_lib->clear();
		$this->image_lib->initialize($config);
		$this->image_lib->resize();
		chmod($desPath.$fileName , 0777);
	}
	
	public function sectionDetails($sectionID){
		$parentLiveSection = $liveSection = [];
		
		$section = $this->kp_migration->query("SELECT SectionID , SectionName , SectionParentID , SectionName , Description FROM ExpressBuzz_Section WHERE SectionID='".$sectionID."'")->row_array();
		if(count($section) > 0){
			if($section['SectionParentID']!='' && $section['SectionParentID']!=0){
				$liveparent = $this->kp_migration->query("SELECT SectionID , SectionName , SectionParentID , SectionName , Description FROM ExpressBuzz_Section WHERE SectionID='".$section['SectionParentID']."'")->row_array();
			
				$sectionDetails = $this->db->query("SELECT Section_id , IsSubSection  , ParentSectionID , URLSectionStructure , URLSectionName , Sectionname FROM sectionmaster WHERE Sectionname='".$section['Description']."' AND Section_id NOT IN (24,25,26,27,28,29,30,31,32,33,34,35,36,37) AND ParentSectionID=(SELECT Section_id FROM sectionmaster WHERE IsSubSection=0 AND Sectionname='".$liveparent['Description']."' AND ParentSectionID IS NULL )")->row_array();
			}else{
				$sectionDetails = $this->db->query("SELECT Section_id , IsSubSection  , ParentSectionID , URLSectionStructure , URLSectionName , Sectionname FROM sectionmaster WHERE Sectionname='".$section['Description']."' AND Section_id NOT IN (24,25,26,27,28,29,30,31,32,33,34,35,36,37)")->row_array();
			}
			
			if(count($sectionDetails) > 0){
				if($sectionDetails['IsSubSection']==1){
					$parentLiveSection = $this->db->query("SELECT Section_id , IsSubSection  , ParentSectionID , URLSectionStructure , URLSectionName , Sectionname FROM sectionmaster WHERE Section_id='".$sectionDetails['ParentSectionID']."' AND Section_id NOT IN (24,25,26,27,28,29,30,31,32,33,34,35,36,37)")->row_array();
				}
				$liveSection = $sectionDetails;	
			}
		}
		return ['section' => $liveSection , 'parentSection' => $parentLiveSection];
	
	}
	
	public function RemoveSpecialCharacters($data) {
		 $replace = array("`","~","!","@","#","$",'%',"^","&","*","(",")","+","=","[","]","{","}","|","/","\\",":",";","'",'"',"?","<",">",",",".","’","‘","”","“","—");
		return str_replace($replace,"",$data);	
	}
	
	public function hasTable($year){
		$tableName = 'article_'.$year;
		$sectionMappingTable = 'article_section_mapping_'.$year;
		$relatedContentTable = 'relatedcontent_'.$year;
		if(!$this->kp_archive->table_exists($tableName)){
			$this->kp_archive->query('SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO"');
			$this->kp_archive->query('SET time_zone = "+00:00"');
			$this->kp_archive->query("CREATE TABLE {$tableName} ( 
					content_id mediumint(8) UNSIGNED NOT NULL,
					ecenic_id mediumint(9) UNSIGNED DEFAULT NULL,
					tag_ids varchar(255) NOT NULL,
					agency_id tinyint(4) UNSIGNED DEFAULT NULL,
					author_id smallint(6) UNSIGNED DEFAULT NULL,
				    country_id smallint(6) UNSIGNED DEFAULT NULL,
				    state_id smallint(6) UNSIGNED DEFAULT NULL,
				    city_id smallint(6) UNSIGNED DEFAULT NULL,
				    homepageimageid mediumint(9) UNSIGNED DEFAULT NULL,
				    sectionpageimageid mediumint(9) UNSIGNED DEFAULT NULL,
				    articlepageimageid mediumint(9) UNSIGNED DEFAULT NULL,
				    section_id smallint(6) DEFAULT NULL,
				    section_name varchar(50) CHARACTER SET utf8 NOT NULL,
				    parent_section_id smallint(6) DEFAULT NULL,
				    parent_section_name varchar(50) CHARACTER SET utf8 DEFAULT NULL,
				    grant_section_id smallint(6) DEFAULT NULL,
				    grant_parent_section_name varchar(50) CHARACTER SET utf8 DEFAULT NULL,
				    linked_to_columnist tinyint(1) NOT NULL,
				    publish_start_date datetime NOT NULL,
				    publish_end_date datetime NOT NULL,
				    scheduled_article tinyint(1) NOT NULL DEFAULT '0',
				    last_updated_on datetime NOT NULL,
				    title varchar(255) CHARACTER SET utf8 NOT NULL,
				    url varchar(255) CHARACTER SET utf8 NOT NULL,
				    summary_html text CHARACTER SET utf8,
				    article_page_content_html mediumtext CHARACTER SET utf8 NOT NULL,
				    home_page_image_path varchar(255) NOT NULL,
				    home_page_image_title varchar(255) CHARACTER SET utf8 DEFAULT NULL,
				    home_page_image_alt varchar(255) CHARACTER SET utf8 DEFAULT NULL,
				    section_page_image_path varchar(255) NOT NULL,
				    section_page_image_title varchar(255) CHARACTER SET utf8 DEFAULT NULL,
				    section_page_image_alt varchar(255) CHARACTER SET utf8 DEFAULT NULL,
				    article_page_image_path varchar(255) NOT NULL,
				    article_page_image_title varchar(255) CHARACTER SET utf8 DEFAULT NULL,
				    article_page_image_alt varchar(255) CHARACTER SET utf8 DEFAULT NULL,
				    column_name varchar(100) CHARACTER SET utf8 DEFAULT NULL,
				    hits mediumtext,
				    tags varchar(255) CHARACTER SET utf8 DEFAULT NULL,
				    allow_comments tinyint(1) NOT NULL,
				    allow_pagination tinyint(1) DEFAULT '0' COMMENT '1- allow, 0 - not allow',
				    agency_name varchar(50) CHARACTER SET utf8 DEFAULT NULL,
				    author_name varchar(100) CHARACTER SET utf8 DEFAULT NULL,
				    author_image_path varchar(255) NOT NULL,
				    author_image_title varchar(255) CHARACTER SET utf8 DEFAULT NULL,
				    author_image_alt varchar(255) CHARACTER SET utf8 DEFAULT NULL,
				    country_name varchar(100) NOT NULL,
				    state_name varchar(100) NOT NULL,
				    city_name varchar(100) NOT NULL,
				    no_indexed tinyint(1) NOT NULL,
				    no_follow tinyint(1) NOT NULL,
				    canonical_url varchar(255) CHARACTER SET utf8 DEFAULT NULL,
				    meta_Title varchar(255) CHARACTER SET utf8 DEFAULT NULL,
				    meta_description varchar(255) CHARACTER SET utf8 DEFAULT NULL,
				    section_promotion tinyint(1) NOT NULL,
				    link_to_resource tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - unlinked, 1 - linked',
				    related_imagepath varchar(255) NOT NULL,
				    related_imagealt text NOT NULL,
				    related_imagecaption text NOT NULL,
				    related_image_type int(1) NOT NULL DEFAULT '0',
				    amp_status int(1) NOT NULL DEFAULT '0',
				    status char(1) NOT NULL COMMENT 'P - Published, U - Unpublished',
				    created_by varchar(250) CHARACTER SET utf8 NOT NULL,
				    created_on datetime NOT NULL,
				    modified_by varchar(250) CHARACTER SET utf8 NOT NULL,
				    modified_on datetime NOT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=latin1");
			$this->kp_archive->query("ALTER TABLE {$tableName}
					ADD PRIMARY KEY (`content_id`),
					ADD KEY `section_id` (`section_id`),
					ADD KEY `parent_section_id` (`parent_section_id`),
					ADD KEY `section_id_2` (`section_id`),
					ADD KEY `grant_section_id` (`grant_section_id`),
					ADD KEY `ecenic_id` (`ecenic_id`),
					ADD KEY `tag_ids` (`tag_ids`,`agency_id`,`author_id`,`country_id`,`state_id`,`city_id`,`homepageimageid`,`sectionpageimageid`,`articlepageimageid`),
					ADD KEY `allow_pagination` (`allow_pagination`),
					ADD KEY `scheduled_article` (`scheduled_article`)");
		}
		
		if(!$this->kp_archive->table_exists($sectionMappingTable)){
			$this->kp_archive->query('SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO"');
			$this->kp_archive->query('SET time_zone = "+00:00"');
			$this->kp_archive->query("CREATE TABLE {$sectionMappingTable} (
				  `content_id` mediumint(11) UNSIGNED NOT NULL,
				  `section_id` smallint(6) NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=latin1");
			$this->kp_archive->query("ALTER TABLE {$sectionMappingTable}
				  ADD KEY `content_id` (`content_id`,`section_id`),
				  ADD KEY `section_id` (`section_id`)");	
		}
		
		if(!$this->kp_archive->table_exists($relatedContentTable)){
			$this->kp_archive->query('SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO"');
			$this->kp_archive->query('SET time_zone = "+00:00"');
			$this->kp_archive->query("CREATE TABLE {$relatedContentTable} (
				`content_id` mediumint(9) UNSIGNED NOT NULL,
				`contenttype` tinyint(1) NOT NULL,
				`related_content_id` mediumint(9) UNSIGNED DEFAULT NULL,
				`related_articletitle` varchar(255) CHARACTER SET utf8 NOT NULL,
				`related_articleurl` varchar(255) CHARACTER SET utf8 NOT NULL,
				`display_order` tinyint(4) NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=latin1");
			$this->kp_archive->query("ALTER TABLE {$relatedContentTable}
				ADD KEY `content_id` (`content_id`)");
		}
	}
}
?> 