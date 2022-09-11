<?php
header("Content-type: text/xml"); 
$base_url = base_url();
$url =  "http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
$url = urldecode($url);
$atom_url= htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' );
echo "<?xml version='1.0' encoding='UTF-8'?> 
<rss version='2.0' xmlns:atom='http://www.w3.org/2005/Atom'>
<channel>
<title>The New Indian Express - $Section - $url_section/</title>
<link>$base_url</link>
<atom:link href=\"$atom_url\" rel=\"self\" type=\"application/rss+xml\"/>
<description>RSS Feed from Kannadaprabha</description>
<language>en-us</language>
<copyright>Copyright 2016 Kannadaprabha. All rights reserved.</copyright>\n"; 

//print_r($rss_article);exit;
if($Content_type!=7):
foreach($rss_article as $article){
 $id          = $article['content_id'];
 $psection    = $Parentsection;
 if($psection=='')
 {
 $psection    = $Section;
 $section     = '';
 }else{
 $psection    = $Parentsection;
 $section     =  $Section;
 }
 $title         = html_entity_decode($article['title']); //html_entity_decode
 $search        = array('&', '&#39;');
 $replace       = array('&amp;', "'");
 $title         = strip_tags(str_replace($search, $replace , $title)); 
 $link		    =  base_url().$article['url'];
 $image_path    = '<image> </image>';
 $image_caption = '<imagecaption> </imagecaption>';
 if($content_type == 1){
 $author_name = $article['author_name'];
 $agency_name = $article['agency_name'];
 if($author_name!='')
 $author_name = htmlspecialchars($article['author_name']);
 else
 $author_name = htmlspecialchars($article['agency_name']);
 $id= "<id>".$id."</id>";
 $description   = $article['summary_html'];
 //$story        = '<story><![CDATA['.html_entity_decode($article['articlestory']).']]></story>'; //str_replace('&#39;' ,"'",(str_replace('&nbsp;', '',$article['articlestory'])))
$story          = '<story><![CDATA['.$article['articlestory'].']]></story>'; //str_replace('&#39;' ,"'",(str_replace('&nbsp;', '',$article['articlestory'])))
$article_page_image_path = $article['article_page_image_path'];
if($article_page_image_path !='')
{
  $Image600X390  = str_replace("original","w600X390", $article_page_image_path);
	
	if ($Image600X390 != '' && getimagesize(image_url_no . imagelibrary_image_path . $Image600X390))
	{
		$image_path = "<image>".image_url. imagelibrary_image_path . $Image600X390."</image>";
		$image_caption= "<imagecaption>".$article['article_page_image_title']."</imagecaption>";
	}

}
 }elseif($content_type == 3){
 $author_name = htmlspecialchars($article['agency_name']);
  $id= "<Galleryid>".$id."</Galleryid>";
  $description    = $article['summary_html'];
  $story        = '';
if($article['content_id'] != '') {
$gallery_images = $this->widget_model->get_gallery_images_by_id($article['content_id']);
$image_path='';
$i=1;
foreach($gallery_images as $gallery_image){ 
				  
                  $gallery_caption = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $gallery_image['gallery_image_title']);
				  $Image600X390 	= str_replace("original","w600X390", $gallery_image['gallery_image_path']);
				  $Image150X150 	= str_replace("original","w150X150", $gallery_image['gallery_image_path']);												
					if($i==1){
					if ($Image150X150 != '' && getimagesize(image_url_no . imagelibrary_image_path . $Image150X150))
					{
						$image_path.= "<thumbimage>".image_url. imagelibrary_image_path . $Image150X150."</thumbimage>";
						
					}
					$image_path.= "<item>";
					$image_path.= "<img>".image_url. imagelibrary_image_path . $Image600X390."</img>";
					$image_path.= "<description>".$gallery_caption."</description>";
					$image_path.= "</item>";
					}else{
					if ($Image600X390 != '' && getimagesize(image_url_no . imagelibrary_image_path . $Image600X390))
					{
					$image_path.= "<item>";
					$image_path.= "<img>".image_url. imagelibrary_image_path . $Image600X390."</img>";
					$image_path.= "<description>".$gallery_caption."</description>";
					$image_path.= "</item>";
						
					}
					}
					$i++;
                  }
				  $image_caption="<imagecaption> </imagecaption>";
}
 }elseif($content_type ==4){
	  $author_name = htmlspecialchars($article['agency_name']);
  $id= "<Videoid>'".$id."'</Videoid>";
  $description    = $article['summary_html'];
  $video_script   = $article['video_script'];
 // $story        = '<videoscript>'.$article['VideoScript'].'</videoscript>';
  $story        = '';
  $image = '';
  $image_caption='<imagecaption> </imagecaption>';
  $video_image_path = $article['video_image_path'];
if($video_image_path!=''){
$Image600X390 	= str_replace("original","w600X390", $video_image_path);
$image_path='<image> </image>';

if ($Image600X390 != '' && getimagesize(image_url_no . imagelibrary_image_path . $Image600X390))
{
	$image_path = "<image>".image_url. imagelibrary_image_path . $Image600X390."</image>";
	
}
}
 }elseif($content_type ==5){
  $author_name = htmlspecialchars($article['agency_name']);
  $id= "<Audioid>'".$id."'</Audioid>";
  $description    = $article['summary_html'];
  $audio_path    = image_url. audio_source_path.$article['audio_path'];
  $story        = '';
  $image = '';
  $image_caption='<imagecaption> </imagecaption>';
  $audio_image_path = $article['audio_image_path'];
if($audio_image_path!=''){
$Image600X390 	= str_replace("original","w600X390", $audio_image_path);
$image_path='<image> </image>';

if ($Image600X390 != '' && getimagesize(image_url_no . imagelibrary_image_path . $Image600X390))
{
	$image_path = "<image>".image_url. imagelibrary_image_path . $Image600X390."</image>";
	
}
}
 }
		
 //$description =  trim(str_replace("&#039","&#39",$description),'"');
// $description = str_replace(array('\'', '"'), '', $description);
$description = strip_tags($description);
//$description ='';
//$story =''; 
$tags = $article['tags'];
$published_date = date("l, F j, Y h:i A", strtotime($article['last_updated_on'])); 
if($content_type == 1){
echo "<item>
$id
<Pcategory>$psection</Pcategory>
<category>$section</category> 
<title>$title</title>
<author>$author_name</author>
<source>$agency_name</source>
<pubDate>$published_date +0530</pubDate>
<description><![CDATA[$description]]></description>
$story
<tags>$tags</tags>
$image_path
$image_caption
<link>$link</link>
</item>"; 
}elseif($content_type == 3){
echo "<album>
$id
<Pcategory>$psection</Pcategory>
<category>$section</category> 
<title>$title</title>
<author>$author_name</author>
<source>$author_name</source>
<pubDate>$published_date +0530</pubDate>
<description><![CDATA[$description]]></description>
$story
<tags>$tags</tags>
$image_path
$image_caption
<link>$link</link>
</album>"; 
}elseif($content_type == 4){
echo "<item>
$id
<Pcategory>$psection</Pcategory>
<category>$section</category> 
<title>$title</title>
<author>$author_name</author>
<source>$author_name</source>
<pubDate>$published_date +0530</pubDate>
<description><![CDATA[$description]]></description>
$story
<tags>$tags</tags>
$image_path
$image_caption
<video_script>$video_script</video_script>
<link>$link</link>
</item>"; 
}elseif($content_type == 5){
echo "<item>
$id
<Pcategory>$psection</Pcategory>
<category>$section</category> 
<title>$title</title>
<author>$author_name</author>
<source>$author_name</source>
<pubDate>$published_date +0530</pubDate>
<description><![CDATA[$description]]></description>
$story
<tags>$tags</tags>
$image_path
$image_caption
<audio_path>$audio_path</audio_path>
<link>$link</link>
</item>"; 
}
} 
endif;
if($Content_type==7):
foreach($rss_article as $article){
	$qid=$article['Question_id'];
	$question=strip_tags($article['Questiontext']);
	$question	= htmlentities($question);
	$answer=strip_tags($article['AnswerInPlainText']);
	$answer	= htmlentities($answer);
	$username=$article['UserName'];
	$place=$article['Place'];
	$submit_date = date("l, F j, Y h:i A", strtotime($article['SubmittedOn'])); 
	echo "<item>
	<id>$qid</id>
	<category>$Section</category>
	<name>$username</name>
	<place>$place</place>
	<question>$question</question>
	<answer>$answer</answer>
	<pubDate>$submit_date +0530</pubDate>
	</item>"; 
}
endif; 
echo "</channel></rss>";
?>