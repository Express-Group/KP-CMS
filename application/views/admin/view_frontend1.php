<?php
    //$ExpireTime = 600; // seconds (= 10 mins)
    $ExpireTime = 60; // seconds (= 1 mins)

	$this->output->set_header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
	$this->output->set_header("Cache-Control: cache, must-revalidate");
	$this->output->set_header("Cache-Control: max-age=".$ExpireTime);
	$this->output->set_header("Pragma: cache");
?>
<?php
$css_path 		= image_url."css/FrontEnd/";
$js_path 		= image_url."js/FrontEnd/";
$images_path	= image_url."images/FrontEnd/";
//if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start();
if(count($section_details) > 0){
$index             = ($section_details['Noindexed']=='1')? 'NOINDEX' : 'INDEX';
$follow            = ($section_details['Nofollow'] == '1') ? 'NOFOLLOW' : 'FOLLOW';
$Canonicalurl      = (strtolower($section_details['URLSectionStructure'])=='home')? base_url() : base_url().$section_details['URLSectionStructure'];//($section_details['Canonicalurl']!='') ? $section_details['Canonicalurl'] : '';
$meta_title        = $section_details['MetaTitle'];
$meta_description  = $section_details['MetaDescription'];
$meta_keywords     = $section_details['MetaKeyword'];
$section_name      = $section_details['Sectionname'];
$section_img_path  = ($section_details['BGImage_path']!='')? image_url.$section_details['BGImage_path']: '';
if($section_details['Sectionname']=='Search' && $this->uri->segment(2) !='' ){
$GetMetaDetails=$this->widget_model->GetMetaData(str_replace('_',' ',$this->uri->segment(2)));
$meta_description=$GetMetaDetails['description'];
$meta_title=$GetMetaDetails['name'];
}
}else{
	$index            = "";
	$follow           = "";
	$Canonicalurl     = "";
	$meta_title       = "";
	$meta_description = "";
	$meta_keywords    = "";
	$section_name     = "Clone widget Template";
	$section_img_path = "";
}
$page_variable = $this->input->get('per_page');
if($page_variable!='')
{
 if($this->uri->segment(1)!='topic'){
 $settings = $this->widget_model->select_setting($viewmode);
 $per_page = $settings['subsection_otherstories_count_perpage'];
 }else
 {
 $page_variable = $this->input->get('per_page');
 $per_page      = 15;
 }
 $page_variable = ($page_variable/$per_page)+1;
}

$background_image = ($section_img_path!='')? 'style="background:url('.$section_img_path.') left;"' : "";
?><!DOCTYPE HTML>
<html>
  <head>
   <!--<link rel="alternate" href="<?php echo rtrim(current_url(), "/");?>" hreflang="en"/>-->
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <?php $title_tag = ($section_name=="Home")? "Latest News, Breaking News, India News, Bollywood, World, Business, Sports &amp; Politics" : $section_name; ?>
	<?php  
	if($section_details['Sectionname']=='Search'): 
		$Stitle=($this->uri->segment(2)!='')? str_replace('_',' ',$this->uri->segment(2)): $this->input->get('term'); 
	?>
	<title> <?php print $Stitle; ?>  | Latest and Breaking News on <?php print $Stitle; ?> | Kannada Prabha</title>
  <?php else: ?>
    <title><?php echo trim(strip_tags($meta_title)); ?><?php echo ($page_variable!='')? "- page".$page_variable: "";?></title>
  <?php endif; ?>
  <!-- for-mobile-apps -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="title" content="<?php echo strip_tags($meta_title);?>" />
  <meta name="description" content="<?php echo $meta_description;?>">
  <meta name="keywords" content="<?php echo $meta_keywords;?>">
  <meta name="news_keywords" content="<?php echo $meta_keywords;?>">


 <meta content="News" name="classification" />
 <meta name="Distribution" content="Global" />
 <meta http-equiv="content-language" content="kn" />
 <meta property="fb:pages" content="396142997103510" /><title>



 <meta name="msvalidate.01" content="7AE7E46B8B1979D40D9ED0E6E7C9FDF4" />
 
 
  
  <link rel="canonical" href="<?php echo $Canonicalurl;?>" />
  <meta property="og:type" content="website" />
  <meta property="og:title" content="<?php echo strip_tags($meta_title);?>"/>
  <meta property="og:image" content="<?php echo $section_img_path;?>"/>
  <meta property="og:site_name" content="KannadaPrabha"/>
  <meta property="og:description" content="<?php echo $meta_description;?>"/>
  <meta name="twitter:site" content="@KannadaPrabha" />
  <meta name="twitter:title" content="<?php echo strip_tags($meta_title);?>" />
  <meta name="twitter:description" content="<?php echo $meta_description;?>" />
  <meta name="twitter:image" content="<?php echo $section_img_path;?>" />
  <meta name="robots" content="<?php echo $index;?>, <?php echo $follow;?>">
  <?php if($section_name=="Home"){?> 
  <!--<meta name="google-site-verification" content="kq62qNfBVMPIHNNc5z9EtDxRdqA64bs4Z_Y6zoTMrp4" />
  <meta name="google-site-verification" content="b2kgMjuHVoLQcx-EKiY0gvMUlSl0jCrxoK1Mg-OnsU4" />-->
  <meta name="p:domain_verify" content="e0001e5ce89ed0c9a5e34a79adbf7c93" />


  <!--<meta name="msvalidate.01" content="281F8BAF23ACF515E82E9CAD6292308D" />-->
  <meta name="msvalidate.01" content="7AE7E46B8B1979D40D9ED0E6E7C9FDF4" />


  <meta name="google-site-verification" content="m-XsLT25bStFxhVhGgHyCl9JC1GmEcFuGaZUBEyTrfo" />
  <meta name="fb:app_id" content="114452938652867" />
  <meta name="svg-x-verification" content="28ce15a5f25454ac21d27910e3bc837973c7486b" />

  
  <meta name="alexaVerifyID" content="k702jOh6qXW-_R39j6C3hHiFmD0" />
  <meta name="author" content="Kannadaprabha" />
  <!--<meta name="robots" content="noodp, noydir" />
  <meta name="slurp" content="noindex, nofollow" />-->
  <?php }?>
  
  <link href='https://fonts.googleapis.com/css?family=Roboto+Condensed:700' rel='stylesheet' type='text/css'>
  <link href='https://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
  <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro' rel='stylesheet' type='text/css'>
  <link rel="shortcut icon" href="<?php echo $images_path; ?>images/favicon.ico" type="image/x-icon" />
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo $css_path; ?>css/combine_style.css?version=1.5" type="text/css">

  <link rel="dns-prefetch" href="//d3.zedo.com/jsc/d3/fo.js" />
  <link rel="dns-prefetch" href="http://cricket.sportzdeck.stats.com/v3/widgets/newindianexpress-othermatches.html" />
  <link rel="dns-prefetch" href="//widget.crowdynews.com/NewIndianExpress_news.js" /> 
  

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>


  <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ if (window.scrollY == 0) window.scrollTo(0,1); }; </script>

<script type="application/ld+json">
{
  "@context" : "http://schema.org",
  "@type" : "WebSite", 
  "name" : "The New Indian Express",
  "url" : "http://www.newindianexpress.com",
  "potentialAction" : {
    "@type" : "SearchAction",
    "target" : "http://www.newindianexpress.com?s={search_term}",
    "query-input" : "required name=search_term"
  }                     
}
</script>
  <script type="application/ld+json">
{
  "@context" : "http://schema.org",
  "@type" : "Organization",
  "name" : "The New Indian Express",
  "url" : "http://www.newindianexpress.com",
  "sameAs" : [
    "https://www.facebook.com/thenewindianxpress",
    "https://twitter.com/NewIndianXpress",
    "https://plus.google.com/107417132035811835892/"
  ]
}
</script>
<?php if(strtolower($section_details['URLSectionStructure'])=='home'){ ?>
<script>
    window._newsroom = window._newsroom || [];
    window._newsroom.push({pageTemplate: 'home'});
    window._newsroom.push({pageDashboard: 'home-desktop'});
    window._newsroom.push('auditClicks');
    window._newsroom.push('trackPage');
    !function (e, f, u) {
        e.async = 1;
        e.src = u;
        f.parentNode.insertBefore(e, f);
    }(document.createElement('script'),
        document.getElementsByTagName('script')[0], '//c2.taboola.com/nr/newindianexpress-newindianexpresscom/newsroom.js');
</script>
<?php }else{ ?>
<script>
    window._newsroom = window._newsroom || [];
    !function (e, f, u) {
        e.async = 1;
        e.src = u;
        f.parentNode.insertBefore(e, f);
    }(document.createElement('script'),
        document.getElementsByTagName('script')[0], '//c2.taboola.com/nr/newindianexpress-newindianexpresscom/newsroom.js');
</script>
<?php } ?>

 
  </head>
  <body <?php echo $background_image;?>>
  <?php 
	if($viewmode != "" && $viewmode == "live")
	{
	?>


<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
  ga('create', 'UA-2311935-3', 'auto');
  ga('require', 'displayfeatures');
  ga('send', 'pageview');
  setTimeout("ga('send','event','adjusted bounce rate','page visit 60 seconds or more')",60000);
</script>
<!-- Begin comScore Tag -->
<script>
var _comscore = _comscore || [];
_comscore.push({ c1: "2", c2: "16833363" });
(function() {
var s = document.createElement("script"), el = document.getElementsByTagName("script")[0]; s.async = true;
s.src = (document.location.protocol == "https:" ? "https://sb" : "http://b") + ".//scorecardresearch.com/beacon.js";
el.parentNode.insertBefore(s, el);
})();
</script>
<noscript>
<img src="http://b.scorecardresearch.com/p?c1=2&c2=16833363&cv=2.0&cj=1" />
</noscript>
<!-- End comScore Tag -->

<?php	
	}
?>
<?php
echo $header;
echo $body. $footer; ?>
<script src="<?php echo $js_path; ?>js/slider-custom-lazy.min.js" type="text/javascript"></script> 
<script type="text/javascript">
	  <?php 
	  $section_id = $section_details['Section_id'];
	  $parent_section_id = $section_details['ParentSectionID'];
	  $mode = $content['mode']; ?>
	  var Section_id = '<?php echo $section_id;?>';
	  var PSection_id = '<?php echo $parent_section_id;?>';
	  var view_mode = '<?php echo $mode;?>';
	<?php  if(isset($html_header)&& $html_header==true){ ?>
	  var call_breadcrumb = 1;
	  <?php }else{ ?>
	    var call_breadcrumb = 0;
	   <?php }
	  if(isset($html_rightpanel)&& $html_rightpanel==true){ ?>
	    var call_otherstories = 1;
	  <?php }else{ ?>
	    var call_otherstories = 0;
	<?php  }?>
$(document).ready(function () {
<!--replace slick preview as arrow-->
	$('.slick-prev').addClass('fa fa-chevron-left');
	$('.slick-next').addClass('fa fa-chevron-right');	
	  
    $('.menu').affix({
	  offset: {
		top: $('header').height()
	  }
    });	
	$('.article_click').click(function(){localStorage.setItem("callback_section", window.location);});
});
</script>
<?php if($this->uri->segment(1)==folder_name || $this->uri->segment(1)=='topic'){ ?>
<script src="<?php echo $js_path; ?>js/bootstrap-datepicker.js" type="text/javascript"></script>
<?php } ?>
<script type="text/javascript">
 var base_url = "<?php echo base_url(); ?>";
 var css_url  = "<?php echo $css_path; ?>";
</script> 
<script src="<?php echo $js_path; ?>js/custom.js?version=12" type="text/javascript"></script>

<script src="<?php echo $js_path; ?>js/postscribe.min.js"></script>
<!-- Start Advertisement Script --> 
<?php echo urldecode(@$header_ad_script); ?> 
<!-- End Advertisement Script -->
</body>
</html>
