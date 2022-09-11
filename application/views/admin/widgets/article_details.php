<article class="WidthFloat_L printthis">
<?php
					$widget_instance_id =  $content['widget_values']['data-widgetinstanceid'];
				    $content_id= $content['content_id'];
					$content_type_id = $content['content_type'];
					$view_mode          = $content['mode'];
					if($content_id!=''){
										 
					if($content['content_from'] =="live"){
					//$content_details = $this->widget_model->widget_article_content_by_id($content_id, $content_type_id, ""); from live
					$content_details = $content['detail_content'];   // from Template Controller
						if($content_type_id==4){
							$tblname = "videomaster";
						}else if($content_type_id==3){
							$tblname = "gallerymaster";
						}else{
							$tblname = "articlemaster";
						}
					}else if($content['content_from']=="archive"){
						$year = $this->uri->segment($this->uri->total_segments() - 3);
						if($content_type_id==4){
							$tblname = "video_".$year;
						}else if($content_type_id==3){
							$tblname = "gallery_".$year;
						}else{
							$tblname = "article_".$year;
						}
						$content_details = $content['detail_content'];
					}
					$content_det= $content_details[0];
					$section_id = $content_det['section_id'];
					if($content['content_from'] =="live"){
						$editorName = $this->db->query("SELECT Firstname , Lastname FROM usermaster WHERE User_id=(SELECT Createdby FROM ".$tblname." WHERE content_id='".$content_det['content_id']."')")->row_array();
						$editorName = @$editorName['Firstname'].' '.@$editorName['Lastname'];
					}else if($content['content_from']=="archive"){
						$this->archive_db = $this->load->database('archive_db' ,TRUE);
						$editorName = $this->archive_db->query("SELECT created_by FROM ".$tblname." WHERE content_id='".$content_det['content_id']."'")->row_array();
						$editorName = @$editorName['created_by'];
					}
					//print_r($content_det);exit;
					$domain_name =  base_url();
					$article_url = $domain_name.$content_det['url'];
					$section_details = $this->widget_model->get_sectionDetails($section_id, $view_mode);
					$check_parent_section_id	= $section_details['ParentSectionID'];
					
					
					// Code added for Advertismnet 
					$home_section_name = 'Home';
					$child_section_name = $section_details['Sectionname'];
					$child_section_name1 = $section_details['URLSectionStructure'];
					$url_structure       = $section_details['URLSectionStructure'];
					$sub_section_name = 'Home';
					if($section_details['IsSubSection'] =='1'&& $section_details['ParentSectionID']!=''&& $section_details['ParentSectionID']!=0 ){
					$sub_section = $this->widget_model->get_sectionDetails($section_details['ParentSectionID'], $view_mode);
					$sub_section_name = ($sub_section['Sectionname']!='')? $sub_section['Sectionname'] : '' ;
					$sub_section_name1= $sub_section['URLSectionStructure'];
					 if($sub_section['IsSubSection'] =='1'&& $sub_section['ParentSectionID']!=''&& $sub_section['ParentSectionID']!=0 ){
					$grand_sub_section = $this->widget_model->get_sectionDetails($sub_section['ParentSectionID'], $view_mode);
					$grand_parent_section_name = $grand_sub_section['Sectionname'];
					$grand_parent_section_name1 = $grand_sub_section['URLSectionStructure'];
					$section_link = '<a href="'.$domain_name.'">'.$home_section_name.'</a> <i class="fa fa-angle-right"></i> <a href="'.$domain_name.$grand_parent_section_name1.'">'.$grand_parent_section_name.'</a> <i class="fa fa-angle-right"></i> <a href="'.$domain_name.$sub_section_name1.'">'.$sub_section_name.'</a> <i class="fa fa-angle-right"></i> <a href="'.$domain_name.$child_section_name1.'">'.$child_section_name.'</a>';
					}else{
					$section_link = '<a href='.$domain_name.' >'.$home_section_name.'</a> <i class="fa fa-angle-right"></i> <a href='.$domain_name.$sub_section_name1.' >'.$sub_section_name.'</a> <i class="fa fa-angle-right"></i> <a href='.$domain_name.$child_section_name1.' >'.$child_section_name.'</a>';
					}
					}elseif(strtolower($child_section_name) != "home"){
					$section_link = '<a href= '.$domain_name.' >'.$home_section_name.'</a> <i class="fa fa-angle-right"></i> <a href='.$domain_name.$child_section_name1.' >'.$child_section_name.'</a>';
					}elseif(strtolower($child_section_name) == "home" || strtolower($child_section_name) == "home"){
					$section_link = '<a href= '.$domain_name.' >'.$home_section_name.'</a>';
					}
				  echo '<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
				  echo '<div class="bcrums"> 
				   '.$section_link.'  </div>
				  </div>
				  </div>';
				$morefrom = $this->uri->segment_array();
				array_pop($morefrom);array_pop($morefrom);array_pop($morefrom);array_pop($morefrom);
				$morefrom = base_url().implode('/',$morefrom);
				if($sub_section_name!=''&& $sub_section_name!='Home' && $sub_section_name=='Entertainment'){
					$subsname = ucwords($sub_section_name).' ';
				}else{
					$subsname = '';
				}
				$mainsec = $section_details['Sectionname'];
				if($mainsec=='Other'){ $mainsec='Sport';	}
				$Externalink =  '<style>.morefrom{width: 100%;float: left;margin:2% 0 2%;} .morefrom a{font-weight:bold;color: #4672db;} .morefrom span{float:left;font-size: 16px;}</style> <div class="morefrom"><span>Stay up to date on all the latest <a href="'.$morefrom.'" >'.@$subsname.$mainsec.'</a> news with The New Indian Express App. <a target="_BLANK" href="https://play.google.com/store/apps/details?id=com.newindianexpress.news"> Download now</a></span></div>';
				  ?>

<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ArticleDetail">
    <?php 		
					//////  For Article title  ////		
					$content_title = $content_det['title'];
					if( $content_title != '')
					{
						//$content_title = stripslashes(preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $content_title));
						$content_title = stripslashes(strip_tags($content_title, '</p>'));
					}
					else
					{
						$content_title = '';
					}
                    $content_url = '';
					$page_index_number = '';
					if($content_type_id ==1){	
					$linked_to_columnist = $content_det['author_name'];
					$author_name    = $content_det['author_name'];
					$agency_name    = $content_det['agency_name'];
					$summary_html   = $content_det['summary_html'];
					//$is_author      = ($content_det['author_name']!='')? 1 : 0;
					$is_author      = 0;
					$is_agency      = ($content_det['agency_name']!='')? 1 : 0;
					$author_pos     = stripos($author_name, $child_section_name);
				   // $author_url     = ($author_pos === false)? "author/".join("-", explode(" ", $author_name)) : "columns/".join("-", explode(" ", $author_name));
					$author_url     = ($author_pos === false)? "author/".join("-", explode(" ", $author_name)) : $child_section_name1;
					}else{
					$is_author      = 0;
					$is_agency      = ($content_det['agency_name']!='')? 1 : 0;
					$author_name    = '';
					$agency_name    = $content_det['agency_name'];
					}
					
				   $published_date = date('dS  F Y h:i A' , strtotime($content_det['publish_start_date']));
				   $last_updated_date  = date('dS  F Y h:i A' , strtotime($content_det['last_updated_on']));
				   $allow_social_btn= 1; //$content_det['allow_social_button'];
				   $allow_comments= $content_det['allow_comments'];
				
				   $email_shared = 0; //$content_det['emailed'];
				   if ($email_shared > 999 && $email_shared <= 999999) {
					$email_shared = round($email_shared / 1000, 1).'K';
				   } else if ($email_shared > 999999) {
				   $email_shared = round($email_shared / 1000000, 1).'M';
				   } else {
					$email_shared = $email_shared;
				   }
				   $publish_start_date = $content_det['publish_start_date'];

	  ?>
    <h1 class="<?php if($content_type_id == 3 || $content_type_id == 4){ echo 'GalleryHead'; }else{ echo 'ArticleHead';} ?>" id="content_head" itemprop="name"><?php echo $content_title;?></h1>
	<div class="straptxt article_summary"><?php echo $summary_html;?></div>
    	<?php if($allow_social_btn==1) { ?>
	<div class="Social_Fonts1 FixedOptions1">
      <div class="PrintSocial1" >  	
		<span  class="Share_Icons"><a href="javascript:;" class="csbuttons" data-type="facebook"><img src="<?php echo image_url ?>/images/FrontEnd/images/social-article/fb.png" class="img-responsive"></a></span>
		<span class="Share_Icons">
			<a href="javascript:;" class="csbuttons" data-type="twitter" data-txt="<?php echo strip_tags($content_title);?>" data-via="KannadaPrabha">
			<img src="<?php echo image_url ?>/images/FrontEnd/images/social-article/twitter.png" class="img-responsive"></a>
		</span> 
		<span class="Share_Icons" style="display:none;">
			<a href="javascript:;" class="csbuttons" data-type="google" data-lang="en">
			<img src="<?php echo image_url ?>/images/FrontEnd/images/social-article/g-plus.png" class="img-responsive"></i></a>
		</span> 
		<span class="Share_Icons PositionRelative">
			<i id="popoverId"><img src="<?php echo image_url ?>/images/FrontEnd/images/social-article/mail.png" class="img-responsive"></i></i>
			<!-- <span class="csbuttons-count"><?php echo $email_shared;?></span> -->
		</span>
		<span  class="Share_Icons">
			<a class="whatsapp" data-txt="<?php echo strip_tags($content_title);?>" data-link="<?php echo $article_url; ?>"  data-count="true">
			<i class="fa fa-whatsapp fa_social"></i></a>
		</span>
        <div id="popover-content" class="popover_mail_form fade right in " style="cursor:pointer;">
          <div class="arrow"></div>
          <h3 class="popover-title">Share Via Email</h3>
          <div class="popover-content">
            <form class="form-inline Mail_Tooltip" action="<?php echo base_url(); ?>user/commonwidget/share_article_via_email" name="mail_share" method="post" id="mail_share" role="form">
              <div class="form-group">
                <input type="text" placeholder="Name" name="sender_name" id="name" class="form-control">
                <input type="text" placeholder="Your Mail" name="share_email" id="share_email" class="form-control">
                <input type="text" placeholder="Friends's Mail" name="refer_email" id="refer_email" class="form-control">
                <textarea placeholder="Type your Message" class="form-control" name="message" id="message"></textarea>
                <input type="hidden"  class="content_id" name="content_id" value="<?php echo $content_id;?>" />
                <input type="hidden"  class="section_id" name="section_id" value="<?php echo $section_id;?>" />
                <input type="hidden"  class="content_type_id" name="content_type_id" value="<?php echo $content_type_id;?>" />
                <input type="hidden"  class="article_created_on" name="article_created_on" value="<?php echo $publish_start_date;?>" />
                <input type="reset" value="Reset" class="submit_to_email submit_post">
                <!--<input type="submit" value="share" class="submit_to_email submit_post" name="submit">-->
                <input type="button" value="Share" id="share_submit" class="submit_to_email submit_post" onclick="mail_form_validate();" name="submit">
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <?php  } ?>
	    <p class="ArticlePublish margin-bottom-10"> 
      Published: <span><?php echo $published_date;?></span>&nbsp;&nbsp;|&nbsp;&nbsp;
      <?php  if($content_type_id!= 1){ ?>
      Last Updated: <span><?php echo $last_updated_date;?></span>&nbsp;&nbsp;
    <?php  } ?>

    <?php  if($content_type_id=='1'){ ?>
     Last Updated: <span><?php echo $last_updated_date;?></span>
	 <span class="print_wrap"><span class="FontSize" id="print_article">
	<i class="fa fa-print"></i>&nbsp;&nbsp;|&nbsp;&nbsp;</span><span class="FontSize" id="incfont" data-toggle="tooltip" title="Zoom In">A+</span><span class="FontSize" id="resetMe" data-toggle="tooltip" title="Reset">A&nbsp;</span><span class="FontSize" id="decfont" data-toggle="tooltip" title="Zoom Out">A-</span></span> </p>
		<?php } ?>
		<!--<div class="vuukle-powerbar"></div>-->
	  </div>
	</div>
<?php
	  
	/* ------------------------------------- Article content Type --------------------------------------------*/	
				  
	if($content_type_id=='1'){
	
	$article_body_text =  stripslashes($content_det['article_page_content_html']);	
	
	$Image600X390      = str_replace(' ', "%20", $content_det['article_page_image_path']);
	$verticalalign = '';
	if (getimagesize(image_url_no . imagelibrary_image_path . $Image600X390) && $Image600X390 != '')
	{
	$imagedetails = getimagesize(image_url_no . imagelibrary_image_path.$Image600X390);
	$imagewidth = $imagedetails[0];
	$imageheight = $imagedetails[1];
	
	if ($imageheight > $imagewidth){
		$Image600X390 	= $content_det['article_page_image_path'];
		$verticalalign = 'article-image-vertical';
	}else{	
		$Image900X450 	= str_replace("original","w900X450", $content_det['article_page_image_path']);
		if(getimagesize(image_url_no . imagelibrary_image_path . $Image900X450) && $Image900X450 != ''){
			$Image600X390 	= str_replace("original","w900X450", $content_det['article_page_image_path']);
		}else{
			$Image600X390 	= str_replace("original","w600X300", $content_det['article_page_image_path']);
		}
		
		//$Image600X390 	= $content_det['article_page_image_path'];
		$verticalalign = '';
	}
	$image_path='';
	
		$image_path = image_url. imagelibrary_image_path . $Image600X390;
		
	}
	else{
	$image_path='';
	$image_caption='';	
	
	}
	$show_image = ($image_path != '') ? $image_path : "no_image";
	$image_caption= $content_det['article_page_image_title'];
	$image_alt =  $content_det['article_page_image_alt'];
	$content_url       = base_url().$content_det['url'];
	$page_index_number = ($content_det['allow_pagination']==1)? $content['image_number'] : "no_pagination";
	$special_class     = (strtolower($section_details['Sectionname'])=="specials")? 'special_class': '';
	?>
<div class="row">
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ArticleDetail ArticleDetailContent <?php echo $special_class;?>">
  <div id="content" class="content" itemprop="description">
    <?php if($show_image!='no_image'){ ?>
	<div class="article_image_wrap">
    <figure class="AticleImg <?php echo @$verticalalign; ?>">
      <!-- <div class="image-Zoomin"><i class="fa fa-search-plus"></i><i class="fa fa-search-minus"></i></div> -->
      <img src="<?php echo $show_image;?>" title="<?php echo $image_caption;?>" alt="<?php echo $image_alt;?>" itemprop="image">
      <?php if($image_caption!=''){ ?>
      <p class="AticleImgBottom"><?php echo strip_tags($image_caption);?></p>
      <?php } ?>
    </figure>
	</div>
    <?php } 
	if($content['content_from']=="live"){
		$related_image = $this->widget_model->get_related_image_by_contentid($content_id);	
		$Related_article_content = $this->widget_model->get_related_article_by_contentid($content_id, $content['content_from']);	
		$related_image_path = @$related_image['result'][0]->related_imagepath;
		if (($related_image_path !='') || (count($Related_article_content)>0)){ 
			$relatedclass ='hasrelatedarticle';
			$relatedclass1 ='hasrelatedarticle1';
		}else{
			$relatedclass = 'articlerelatedcontent';
			$relatedclass1 = 'articlestorycontent';
		}
		
				
	}
?>
	   <div class="author_txt"><span class="author_des">Posted By : <span><a><?php echo $editorName;?></a></span></span></div><div class="agency_txt"><span>Source : <?php echo $agency_name;?></span></div>
	 <div id="wholeContent" style="width:100%; float:left;">
		<div id="relatedContent" class="<?php echo $relatedclass ?>">
	  <?php 
	 if($content['content_from']=="live"){
					//$related_image = $this->widget_model->get_related_image_by_contentid($content_id);
					//print_r($related_image);
		if($related_image['count']!=0){
			$related_image_path = $related_image['result'][0]->related_imagepath;
			$related_caption = $related_image['result'][0]->related_imagecaption;
			$related_imagealt = $related_image['result'][0]->related_imagealt; 			
			$related_image_type = $related_image['result'][0]->related_image_type; 					
			if ($related_caption !=""){
				$related_image_txt = $related_caption;
			}else{
				$related_image_txt = $related_imagealt;
			}
			$relatedimageresponse= $related_image['result'][0];
			if($related_image_path !='' ){ 
				$related_image_path = image_url. imagelibrary_image_path .$related_image_path;
			 ?>
				<ul class="RelatedImgArticle" style="">
					<li><div class="RelatedImgArt">Related <?php if($related_image_type=='1'){ echo 'GFX'; }else{ echo 'Image'; } ?></div></li>
					<li><a href="#RelatedImgArticle" data-toggle="modal" data-target="#RelatedImgArticlemodal"><img src="<?php echo $related_image_path;?>" title="<?php echo $related_image_txt;?>" alt="<?php echo $image_alt;?>" itemprop="image"></a></li>
					<li><a href="#RelatedImgArticle" data-toggle="modal" data-target="#RelatedImgArticlemodal">Click on the <?php if($related_image_type=='1'){ echo 'GFX'; }else{ echo 'image'; } ?> to expand</a></li>
				 </ul>
				 <div class="modal fade" id="RelatedImgArticlemodal" tabindex="-1" role="dialog" aria-labelledby="RelatedImgArticlemodalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
								<h4 class="modal-title" id="myModalLabel">Related <?php if($related_image_type=='1'){ echo 'GFX'; }else{ echo 'Image'; } ?></h4>
							</div>
							<div class="modal-body" style="float:left;">
								<img src="<?php echo $related_image_path;?>" title="<?php echo $related_image_txt;?>" alt="<?php echo $image_alt;?>" itemprop="image">
								<p class="AticleImgBottom"><?php echo strip_tags($related_image_txt);?></p>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal" style="color: #fff;background-color: #040a2f;">Close</button>
							</div>
						</div>
					</div>
				</div>
			<?php }
					
		}
	}
	 if($content['content_from']=="live"){
	 $Related_article_content = $this->widget_model->get_related_article_by_contentid($content_id, $content['content_from']);
	 if(count($Related_article_content)>0){
	 ?>
  <ul class="RelatedArticle" style="">
    <div class="RelatedArt">Related Article</div>
    <?php foreach ($Related_article_content as $get_article){
		$relatedarticle_title = strip_tags($get_article['related_articletitle']);
		$related_url          = $get_article['related_articleurl'];
		$param                = $content['close_param'];
		$domain_name          =  base_url();
		$related_article_url  = $domain_name.$related_url.$param;
		?>
    <li><a href="<?php echo $related_article_url;?>"  class="article_click" target="_blank"><span style="color:#0072bc"><i class="fa fa-circle"></i></span><?php echo $relatedarticle_title;?></a></li>
    <?php  } }?>
  </ul>
  <?php }elseif($content['content_from']=="archive"){
	 $live_query_string 	    = explode("/",$this->uri->uri_string());
	 $year                      = $live_query_string[count($live_query_string)-4];
	 $table                     = "relatedcontent_".$year;
	 $Related_article_content = $this->widget_model->get_related_article_from_archieve($content_id, $table);
	 if(count($Related_article_content)>0){
	 ?>
  <ul class="RelatedArticle" style="">
    <div class="RelatedArt">Related Article</div>
    <?php foreach ($Related_article_content as $get_article){
		$relatedarticle_title = strip_tags($get_article['related_articletitle']);
		$related_url          = $get_article['related_articleurl'];
		$param                = $content['close_param'];
		$domain_name          =  base_url();
		$related_article_url  = $domain_name.$related_url.$param;
		?>
    <li><a href="<?php echo $related_article_url;?>"  class="article_click" target="_blank"><i class="fa fa-circle"></i><?php echo $relatedarticle_title;?></a></li>
    <?php  } }?>
  </ul>
  <?php }
	  ?>
	 </div>
     <div id="storyContent" class="<?php echo $relatedclass1 ?>">
    <?php echo $article_body_text; ?> 

    </div>
	</div>
    </div>
  <!--<div class="pagination pagina">
    <ul>
      <li><a href="javascript:;" id="prev" class="prevnext element-disabled">« Previous</a></li>
      <li><a href="javascript:;" id="next" class="prevnext">Next »</a></li>
    </ul>
    <br />
  </div>-->
  <div class="text-center">
  <ul class="article_pagination" id="article_pagination">
    </ul></div>
  <div id="keywordline"></div>
   <?php $LiveCount=$this->widget_model->GetLiveNewsCount($content_id,$section_id); if($LiveCount!=0): ?>
  <!--start of code-->
  <div class="livenow-content">
		<input type="hidden" value="<?php print $image_path; ?>" id="livenow_article_img">
		<div class="livenow_loader"><a><span class="livenow-flash">Live Updates</span><img class="loader-img-livenow"  src="<?php print BASEURL ?>images/live_now_loader.gif" alt="" style="width:18px; height:18px;display:none;" /></a></div>
		<input type="hidden" value="<?php print $content_id ?>" id="article_id">
		<div class="livenow-content1">
		</div>
	</div>
		
	<script>

	var loaderid=0;
	function load(){
		//$('.livenow-flash').html('Live Updating...').show();
		$('.loader-img-livenow').show();
		var image_name=$('#livenow_article_img').val();
		$.ajax({
			type        : 'post',
			url			: '<?php echo base_url(); ?>user/commonwidget/getLivenowContentStatic',
			cache       : false,
			data		: {'article_id':<?php print $content_id ?>  ,'article_url':'<?php print $article_url ?>'  , 'image_url': image_name},
			success     : function(result){
							if(result!=''){
								$('.livenow-content1').html(result);
							}
			},
			error       :function(code,status){
						//alert(status);
			}
		});
		$('.livenow-flash').html('LIVE Updates');
		$('.loader-img-livenow').hide(700);
		clearInterval(loaderid);
		loaderid = setInterval('load()',25000);
	}
	load();
		$(document).on("click",'.whatsapp1', function(e) {
					//alert('clicked');
        if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
		var article = ($(this).attr("data-txt").trim()=='')? $('.ArticleHead').text() : $(this).attr("data-txt");
        var weburl = $(this).attr("data-link");
        var whats_app_message = article +" - "+encodeURIComponent(weburl);
        var whatsapp_url = "whatsapp://send?text="+whats_app_message;
        window.location.href= whatsapp_url;
						//alert(whatsapp_url);
        } else{
			var article = ($(this).attr("data-txt").trim()=='')? $('.ArticleHead').text() : $(this).attr("data-txt");
			var weburl = $(this).attr("data-link");
			//alert('you are not using mobile device.');
			//alert(article);
			//alert(weburl);
        }
    });


	 
</script>
<?php endif; ?>
	<!--end of code-->
	<?php echo $Externalink ; ?> 
</div>
<?php }
	/* ------------------------------------- Gallery content Type --------------------------------------------*/	
else if($content_type_id=='3'){ 
$get_gallery_images	 = $content_details;
$image_number        = $content['image_number'];
$content_url         = base_url().$content_details[0]['url'];
$page_index_number   = $content['image_number'];
?>
<div class="row">
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 features">
  <div class="gallery-single-item" style="width:100% !important">
    <?php 
	$gallerycount = count($get_gallery_images);
	$g=1;
	foreach($get_gallery_images as $gallery_image){ 
				  
                  $gallery_caption = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $gallery_image['gallery_image_title']);
				  $gallery_alt =  $gallery_image['gallery_image_alt'];
				  $Image600X390= str_replace(' ', "%20",$gallery_image['gallery_image_path']);
				  if (getimagesize(image_url_no . imagelibrary_image_path . $Image600X390) && $Image600X390 != '')
					{
				  $imagedetails = getimagesize(image_url_no . imagelibrary_image_path.$Image600X390);
					$imagewidth = $imagedetails[0];
                    $imageheight = $imagedetails[1];
					if ($imageheight > $imagewidth)
					{
						$Image600X390 	= $gallery_image['gallery_image_path'];
						$is_verticle    = 'style="width:100%"';
					}else if($imagewidth > 600 && $imagewidth < 700) // minimum width image
					{				
						$Image600X390 	= $gallery_image['gallery_image_path'];
						$is_verticle    = 'style="width:100%"'; 
					}
					else if($imagewidth < 600) // minimum width image
					{				
						$Image600X390 	= $gallery_image['gallery_image_path'];
						$is_verticle    = 'style="width:100%"'; //'class="gallery_minimum_pixel"';
					}else  // normal image
					{				
						$Image600X390 	= $gallery_image['gallery_image_path'];
						$is_verticle    = '';
					}
						$show_gallery_image = image_url. imagelibrary_image_path . $Image600X390;
					}else{
						$show_gallery_image	= image_url. imagelibrary_image_path.'logo/nie_logo_600X390.jpg';
						$is_verticle        = '';
					}
					$dynamicUrl = str_replace('.html' , '--'.$g.'.html' , $content_details[0]['url']);
                  ?>
    <div class="item gallery-item" rel="/<?php echo $dynamicUrl; ?>">
      <figure class="PositionRelative"> <img src="<?php echo $show_gallery_image;?>" title="<?php echo $gallery_caption;?>" alt="<?php echo $gallery_alt;?>" <?php echo $is_verticle;?> <?php if($gallery_caption==''){ echo 'style="border-bottom-left-radius: 8px;border-bottom-right-radius: 8px;";'; } ?>>
       <?php if($gallery_caption!=''){ ?>
        <div class="TransLarge Font14"><?php echo $gallery_caption;?></div>
	   <?php } ?>
		<span class="gallery-count"><b><?php echo $g; ?></b><span> / <?php echo $gallerycount; ?></span></span>
		<div class="gallerysocial-icon">
			<img rel="facebook"  src="<?php echo image_url.'images/FrontEnd/images/social-article/gallery-facebook.png' ?>">
			<img rel="twitter"  src="<?php echo image_url.'images/FrontEnd/images/social-article/gallery-twitter-squared.png' ?>">
			<img class="pinterest" rel="pinterest" data-url="<?php echo base_url().$dynamicUrl; ?>" data-imageurl="<?php echo $show_gallery_image; ?>" data-description="<?php echo $gallery_caption; ?>" src="<?php echo image_url.'images/FrontEnd/images/social-article/gallery-pinterest-squared.png' ?>"> 
		</div>

      </figure>
    </div>
    <?php 
					$g++; } ?>
  </div>
  <?php echo $Externalink ; ?>
  <script>
	var galleryContent = $('.gallery-item');
	var firstContent = $(galleryContent).eq(0).attr('rel');
	var currentUrl = firstContent;
	var isbackScoll = false;
	var previousScroll = 0;
	$(document).scroll(function () {
		var currentPosition = $(this).scrollTop();
		$(galleryContent).each(function(index){
			var top	= window.pageYOffset;
			var distance = top - $(this).offset().top + 50;
			var hash  = $(this).attr('rel');
			if (distance < 20 && distance > -20 && currentUrl != hash){
				currentUrl = hash;
				history.replaceState({}, '', currentUrl);
				ga('set', {location: '<?php echo str_replace('.com/','.com',BASEURL); ?>'+hash, page: hash});
				ga("send", "pageview");

			}
			if(distance > 250 && distance < 400 && currentHash != hash){
				currentUrl = hash;
				history.replaceState({}, '', currentUrl);
				ga('set', {location: '<?php echo str_replace('.com/','.com',BASEURL); ?>'+hash, page: hash});
				ga("send", "pageview");
			}
		});
	});
	$('.gallerysocial-icon img').on('click' ,function(e){
		var rel = $(this).attr('rel');
		if(rel=='pinterest'){
			var url = encodeURI($(this).attr('data-url'));
			var imageUrl = encodeURI($(this).attr('data-imageurl'));
			var description = $(this).attr('data-description');
			var pin = window.open("https://in.pinterest.com/pin/create/button/?url="+url+"&media="+imageUrl+"&description="+description, "", "width=670,height=340"); 
		}else{
			$('a[data-type="'+rel+'"]').trigger('click');
		}
		
	});
 </script>
    <script>
var currentimageIndex = "<?php echo (($image_number)> count($get_gallery_images))? 1: $image_number;  ?>";
var TotalIndex = "<?php echo (count($get_gallery_images));  ?>";
$( document ).ready(function() {
$('html').addClass('gallery_video_remodal');
<?php if(($image_number) > 1 ){ ?>
 $('.GalleryDetailSlide').slick('slickGoTo', <?php echo $image_number-1;?>);
<?php } ?>
});
</script>
  <div id="keywordline"></div>
</div>
<?php 
							}
						/* ------------------------------------- Video content Type --------------------------------------------*/	
							else if($content_type_id=='4')
							{    
							 $video_scipt       = htmlspecialchars_decode($content_det['video_script']);
							 $video_description = $content_det['summary_html'];
							 $content_url       = base_url().$content_det['url'];
						?>
<div class="row">
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
  <div class="videodetail" style="text-align: center;">
    <?php if($content_det['video_site']=='ventunovideo'){ 
	//$video_scipt = trim(stripslashes($video_scipt),'"');
	?>
    <object width="630" height="441" id="ventuno_player_0" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000">
      <param name="movie" value="http://cfplayer.ventunotech.com/player/vtn_player_2.swf?vID==<?php echo $video_scipt;?>"/>
      <param name="allowscriptaccess" value="always"/>
      <param name="allowFullScreen" value="true"/>
      <param name="wmode" value="transparent"/>
      <embed src="http://cfplayer.ventunotech.com/player/vtn_player_2.swf?vID==<?php echo $video_scipt;?>" width="630" height="441" 
wmode="transparent" allownetworking="all" allowscriptaccess="always" allowfullscreen="true"></embed>
    </object>
    <?php }else{
		echo $video_scipt;
	}
		 ?>
  </div>
  <p class="video_caption"> <?php echo $video_description;?></p>
  <div id="keywordline"></div>
  <?php echo $Externalink ; ?> 
</div>
<script>
						 $( document ).ready(function() {
						 $('html').addClass('gallery_video_remodal');
						});
						</script>
<?php 
							}
					/* ------------------------------------- Audio content Type --------------------------------------------*/	
							else 
							{ 
							 $audio_path       = image_url. audio_source_path.$content_det['audio_path'];
							 $audio_description = $content_det['summary_html'];
							 $content_url       = base_url().$content_det['url'];
							?>
<div class="row">
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="">
      <audio class="margin-left-10 margin-top-5" controls="" src="<?php echo $audio_path;?>"> </audio>
      <p> <?php echo $audio_description;?></p>
    </div>
    <div id="keywordline"></div>
  </div>
  <?php	}
}?>
  <?php 
			  $article_tags= $content_det['tags'];
              $get_tags =array();
			  if($article_tags!='')
			  $get_tags=  explode(",", $article_tags);
			  if(count($get_tags)>0){
			   ?>
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ArticleDetail">
    <div class="tags">
      <div> <span>TAGS</span> </div>
      <?php 
	  $tag_col='article';
	  if($content_type_id=='1'){ $tag_col='article'; }
	  if($content_type_id=='3'){ $tag_col='gallery'; }
	  if($content_type_id=='4'){ $tag_col='video'; }
	  foreach($get_tags as $tag){
		             if($tag!=''){
				     $tag_title = join( "_",( explode(" ", trim($tag) ) ) );
		             $tag_url_title = preg_replace('/[^A-Za-z0-9\_]/', '', $tag_title); 

							if($content_type_id=='1'){
							$tag_link = base_url().'topic/'.$tag_title; 
							}else{
							$tag_link = base_url().'topic/'.$tag_title.'/'.$tag_col; 
							}
                            echo '<a href="'.$tag_link.'">'.$tag.'</a>';
					       }
                            } ?>
    </div>
  </div>
  <?php }  ?>
</div>
</article>
<div class="NextArticle FixedOptions" style="display:none;">
  <?php
					//$time1 = microtime(true);
					$prev_article = $this->widget_model->get_section_previous_article($content['content_id'], $content_det['section_id'],$content_type_id);
					$next_article = $this->widget_model->get_section_next_article($content['content_id'], $content_det['section_id'],$content_type_id);
										
//$time2 = microtime(true);
//echo "script execution time: ".($time2-$time1); //value in seconds				

					//print_r($select_section_prev_article);exit;
					?>
  <?php if(count($prev_article)> 0){
					$prev_content_id = $prev_article['content_id'];
					$prev_section_id = $prev_article['section_id'];
					$param = $content['close_param'];
					$prev_string_value = $domain_name.$prev_article['url'].$param;
	                 ?>
					 <input type="hidden" value="<?php echo $prev_string_value ?>" id="mb_prev">
  <a class="prev_article_click LeftArrow" href="<?php echo $prev_string_value;?>" title="<?php echo strip_tags($prev_article['title']);?>"><i class="fa fa-chevron-left"></i></a>
  <?php } ?>
  <?php if(count($next_article)> 0){
					$next_content_id = $next_article['content_id'];
					$next_section_id = $next_article['section_id'];
					$param = $content['close_param'];
					$next_string_value = $domain_name.$next_article['url'].$param;
					?>
					<input type="hidden" value="<?php echo $next_string_value ?>" id="mb_next">
  <a class="next_article_click RightArrow" href="<?php echo $next_string_value;?>" title="<?php echo strip_tags($next_article['title']);?>"><i class="fa fa-chevron-right"></i></a>
  <?php } ?>
 <?php //$bitly = getSmallLink($content_url); 
       $bitly_url = "";//$bitly['id'];
	   $bitly_message = "";//$bitly['msg'];
 ?>
</div>
<script type="text/javascript">
            $(document).ready(function() {
                $('.whatsapp').on("click", function(e) {
                    if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {

                        var article = $(this).attr("data-txt");
                        var weburl = $(this).attr("data-link");
                        var whats_app_message = article +" - "+encodeURIComponent(weburl);
                        var whatsapp_url = "whatsapp://send?text="+whats_app_message;
                        window.location.href= whatsapp_url;
						//alert(whatsapp_url);
                    }/* else{
                        alert('you are not using mobile device.');
                    } */
                });
            });
 </script>

		
<!--style overwriting editor body content-->
<style>
.ArticleDetailContent li{float: none; list-style: inherit;}
.ArticleDetailContent blockquote {
    padding-left: 20px !important;
    padding-right: 8px !important;
    border-left-width: 5px;
    border-color: #ccc;
    font-style: italic;
	margin:10px 0 !important;
	padding: 12px 16px !important;
	font-size:13px !important;
}
.ArticleDetailContent blockquote p{font-size:13px !important;text-align:center;}
@media screen and ( max-width: 768px){
 audio { width:100%;}
}
</style>
<script type="text/javascript">
	var base_url        = "<?php echo base_url(); ?>";
	var content_id      = "<?php echo $content_id; ?>";
	var content_type_id = "<?php echo $content_type_id; ?>";
	var page_Indexid    = "<?php echo $page_index_number; ?>";
	var section_id      = "<?php echo $section_id; ?>";
	//location.reload(true);
	var content_url     = "<?php echo $content_url; ?>";
	var page_param      = "<?php echo $content['page_param']; ?>";
	var content_from    = "<?php echo $content['content_from']; ?>";
	var bitly_url       = "<?php echo $bitly_url;?>";
	var bitly_message   = "<?php echo $bitly_message;?>";
	$("#storyContent p:eq(0)").before($('.author_txt').show());
	$("#storyContent p:eq(0)").before($('.agency_txt').show());
	
</script>
<div class="recent_news">
<div id="topover" class="slide-open" style="visibility: hidden;">
  <p>O<br>
    P<br>
    E<br>
    N</p>
</div>
</div>
<script src="<?php echo image_url; ?>js/FrontEnd/js/remodal-article_updated.js?version=1"></script>
<script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>

<?php //echo "open me";exit;?>
