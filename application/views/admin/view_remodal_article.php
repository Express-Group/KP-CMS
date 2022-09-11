<?php
$sec=30;
$page=Current_url();
$css_path 		= image_url."css/FrontEnd/";
$js_path 		= image_url."js/FrontEnd/";
$images_path	= image_url."images/FrontEnd/";
///if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler"); else ob_start();
$content_id      = @$content_id;
$content_from    = $content_from;
$content_type_id = @$content_type;
$viewmode        = $viewmode;
//$page_det = $this->widget_model->widget_article_content_by_id($content_id, $content_type_id);
$page_det        = $article_details;
$page_det        = $page_det[0];
$Image600X390    = "";
$Image600X390 	 = ($content_type_id==1)? $page_det['article_page_image_path']: (($content_type_id==3)? $page_det['first_image_path']: (($content_type_id==4)? $page_det['video_image_path']: $page_det['audio_image_path']));
if ($Image600X390 != '' && getimagesize(image_url_no . imagelibrary_image_path . $Image600X390))
	{
	$imagedetails = getimagesize(image_url_no . imagelibrary_image_path.$Image600X390);
	$imagewidth   = $imagedetails[0];
	$imageheight  = $imagedetails[1];
	
	if ($imageheight > $imagewidth)
	{
		$Image600X390 	= $Image600X390;
	}
	else
	{				
		$Image600X390 	= str_replace("original","w600X390", $Image600X390);
	}
	$image_path = '';
	$image_path = image_url. imagelibrary_image_path . $Image600X390;
	}
else
{
	$image_path	   = image_url. imagelibrary_image_path.'logo/nie_logo_600X390.jpg';
	$image_caption = '';	
}
$content      = strip_tags($page_det['summary_html']);
$current_url  = explode('?', Current_url());
$share_url    = base_url().$page_det['url'];
$index        = ($page_det['no_indexed']==1)? 'NOINDEX' : 'INDEX';
$follow       = ($page_det['no_follow'] == 1) ? 'NOFOLLOW' : 'FOLLOW';
$Canonicalurl = $share_url;//($page_det['canonical_url']!='') ? $page_det['canonical_url'] : '';
$meta_title   = stripslashes(str_replace('\\', '', $page_det['meta_Title']));//($page_det['meta_Title']);
$meta_description = stripslashes($page_det['meta_description']);
$tags         = count($page_det['tags'])? $page_det['tags'] : '';

$query_string = ($_SERVER['QUERY_STRING']!='') ? "?".$_SERVER['QUERY_STRING'] : "";
?>
<?php
    $ExpireTime = ($content_from=="live") ? 60 : 86400; // seconds (= 2 mins)
	//$ExpireTime = ($content_from=="live") ? 0 : 86400; // seconds (= 2 mins)
	//$this->output->set_header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
	$this->output->set_header("Cache-Control: cache, must-revalidate");
	$this->output->set_header("Cache-Control: max-age=".$ExpireTime);
	$this->output->set_header("Pragma: cache");
?>
<!DOCTYPE HTML>
<html>
<head>
<link rel="alternate" href="<?php echo Current_url().$query_string;?>" hreflang="en"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo strip_tags($meta_title);?>– Kannada Prabha</title>
<!-- for-mobile-apps -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="title" content="<?php echo strip_tags($meta_title);?>" />
<meta name="description" content="<?php echo $meta_description;?>">
<meta name="keywords" content="<?php echo $tags;?>">
<meta name="news_keywords" content="<?php echo $tags;?>">
<meta name="msvalidate.01" content="E3846DEF0DE4D18E294A6521B2CEBBD2" />
<link rel="canonical" href="<?php echo $Canonicalurl;?>" />
<meta name="robots" content="<?php echo $index;?>, <?php echo $follow;?>">
<meta property="og:url" content="<?php echo $share_url;?>" />
<meta property="og:type" content="article" />
<meta property="og:title" content="<?php echo strip_tags($page_det['title']);?>"/>
<meta property="og:image" content="<?php echo $image_path;?>"/>
<meta property="og:image:width" content="450"/>
<meta property="og:image:height" content="298"/>
<meta property="og:site_name" content="The New Indian Express"/>
<meta property="og:description" content="<?php echo $content;?>"/>
<!--<meta name="twitter:card" content="<?php echo $content;?>" /> -->
<meta name="twitter:card" content="summary_large_image" /> 
<meta name="twitter:creator" content="NewIndianXpress" />
<meta name="twitter:site" content="@newindianexpress.com" />
<meta name="twitter:title" content="<?php echo strip_tags($page_det['title']);?>" />
<meta name="twitter:description" content="<?php echo $content;?>" />
<meta name="twitter:image" content="<?php echo $image_path;?>" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ if (window.scrollY == 0) window.scrollTo(0,1); }; </script>
<link rel="shortcut icon" href="<?php echo $images_path; ?>images/favicon.ico" type="image/x-icon" />
<link rel="stylesheet" href="<?php echo $css_path; ?>css/font-awesome.min.css" type="text/css">
<link rel="stylesheet" href="<?php echo $css_path; ?>css/bootstrap.min.css" type="text/css">
<link rel="stylesheet" href="<?php echo $css_path; ?>css/style.css?version=1.8" type="text/css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js" defer async></script>
<script src="<?php echo $js_path; ?>js/slider-custom-lazy.min.js" type="text/javascript"></script>
<script type="text/javascript">
<?php 
	  $section_id              = $page_det['section_id'];
	  $parent_section_id       = $page_det['parent_section_id'];
	  $grand_parent_section_id = $page_det['grant_section_id'];
	  $mode = $viewmode; ?>
	  var Section_id = '<?php echo $section_id;?>';
	  var PSection_id = '<?php echo $parent_section_id;?>';
	  var GPSection_id = '<?php echo $grand_parent_section_id;?>';
	  var view_mode = '<?php echo $mode;?>';
	  <?php if(isset($html_header)&& $html_header==true){ ?>
	   var call_active_menu = 1;
	   <?php }else{ ?>
	   var call_active_menu = 0;
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
});
</script>

<!-- Start Advertisement Script -->
<?php echo urldecode($header_ad_script); ?>
<!-- End Advertisement Script -->
<script>
var cache=localStorage.getItem('nie-c-clear');
if(cache==null || cache==undefined || cache==''){
	localStorage.setItem('nie-c-clear','success');
	location.reload(true);
}
</script>

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
<script>
    window._newsroom = window._newsroom || [];
    !function (e, f, u) {
        e.async = 1;
        e.src = u;
        f.parentNode.insertBefore(e, f);
    }(document.createElement('script'),
        document.getElementsByTagName('script')[0], '//c2.taboola.com/nr/newindianexpress-newindianexpresscom/newsroom.js');
</script>



</head>
<?php
$content_url = $page_det['url'];

$url_array = explode('/', $content_url);
$get_seperation_count = count($url_array)-4;

$sectionURL = ($get_seperation_count==1)? $url_array[0] : (($get_seperation_count==2)? $url_array[0]."/".$url_array[1] : $url_array[0]."/".$url_array[1]."/".$url_array[2]);
$section_url = base_url().$sectionURL."/";
/*if($content_from=="live"){
$section_url =  $section_url; 
}*/
?>
<body class="article_body" itemscope itemtype="<?php echo $section_url;?>">
<?php 
	if($viewmode == "live")
	{
	?>
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
ga('create', 'UA-2311935-30', 'auto');
ga('send', 'pageview');
setTimeout("ga('send','event','adjusted bounce rate','page visit 30 seconds or more')",30000);
</script>
<!-- Begin comScore Tag -->
<script>
  var comscore = comscore || [];
  _comscore.push({ c1: "2", c2: "16833363" });
  (function() {
    var s = document.createElement("script"), el = document.getElementsByTagName("script")[0]; s.async = true;
    s.src = (document.location.protocol == "https:" ? "https://sb" : "http://b") + ".scorecardresearch.com/beacon.js";
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

<style>
.cssload-container-article img{
position: absolute;
    right:0;
    top: 0;
    width: 70px;
}
.cssload-container-article .cssload-zenith {
    height: 70px;
    width: 70px;
}
.cssload-container-article figure{ 
    left: 50%;
    position: fixed;
    top: 50%;
}

.CenterMarginBg{
	z-index:0;
}


.main-menu {
	 margin-left: 0 !important;
	  width: 100% !important;
}
.widget-container-30 .col-lg-12{
	padding:0 !important;
}
.CenterMargin {
    width: 950px;
}
.RightArrow {
    margin-left: 1085px;
    top: 360px;
}
.LeftArrow{
left: 38px;
}
.PrintSocial{
	left:1%;
}
}


.ArticleDetail p{
	font-size:18px ;
}

.article-col .col-md-4{
	margin-top:3%;
}
.LeftArrow,.RightArrow{
	display:none !important;
}
.section-header,.section-content,.section-footer{
	background:transparent;
}

</style>
<div class="cssload-container cssload-container-article" id="load_spinner">
  <figure> <img src="<?php echo $images_path; ?>images/loader-Nie.png" />
    <div class="cssload-zenith"></div>
  </figure>
</div>
<div class="container side-bar-overlay">
  <div class="left-trans"></div>
  <div class="right-trans"></div>
</div>
<?php //echo $header; ?>
<!--<div class="wait" id="load_spinner">
   <i class="wait-spinner wait-spin centerZone"></i>
  </div>-->
<div class=""  data-remodal-options="hashTracking: false, closeOnOutsideClick: false" role="dialog"  id="" style="position:relative;"> <?php echo  $header.$body .$footer; ?> </div>
<?php 
if(isset($_GET['pm'])!=0 && is_numeric($_GET['pm'])){
$section_details = $this->widget_model->get_sectionDetails($_GET['pm'], $viewmode); //live db
$close_url       = (count($section_details)>0)? base_url().$section_details['URLSectionStructure']: "home";
}else{
$close_url ="home";
}

?>
<!--<script src="<?php echo $js_path; ?>js/remodal_custom.min.js" type="text/javascript"></script>
--> 
<script src="<?php echo $js_path; ?>js/jquery.csbuttons.js" type="text/javascript"></script> 
<?php if($content_type_id==1){ ?>
<script src="<?php echo $js_path; ?>js/article-pagination.js?art=<?php print rand(1,12000); ?>" type="text/javascript"></script>
<?php } ?>
<?php if($content_type_id==1 || $content_type_id==3){ ?>
<script src="<?php echo $js_path; ?>js/jquery.twbsPagination.min.js" type="text/javascript"></script>
<?php } ?>
<script src="<?php echo $js_path; ?>js/slider-custom-lazy.min.js" type="text/javascript"></script> 
<script>
var close_url = "<?php echo $close_url;?>";
$( document ).ready(function() {
	$('#load_spinner').hide();
	$('.menu').affix({
	offset: {
	top: $('header').height()
	}
	});
/*$("html, body").animate({
	scrollTop: 0
});*/
//$('html').addClass('loading_time');
var inst = $('[data-remodal-id=article]').remodal();
inst.open();
 //$('[data-remodal-id=article]').remodal();

$(document).on('opened', '.remodal', function () {
  console.log('Modal is opened');
   $('.SectionContainer').append('<button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>');
 $('.CenterMarginBg').hide();
  $('#load_spinner').hide();
  $('.side-bar-overlay').show();
   $('.menu').affix({
	offset: {
	top: $('header').height()
	}
	});	
	$('.remodal-close').affix({
	offset: {
	top: $('header').height()
	}
	});
});

   $(document).on('closed', '.remodal', function () {	
	<?php /*?><?php if($close_url =='home'){ ?>
	window.location.href = '<?php echo base_url();?>';
    <?php } else {	?>
	window.location.href = '<?php echo $close_url;?>';
	 <?php  }?><?php */?>

	 var bck = localStorage.getItem("callback_section");
	 if(bck =='null'||bck ==null)
	   {
		window.location.href ="http://www.newindianexpress.com/";
	   }
	 else
	   {
	 window.location.href = localStorage.getItem("callback_section");
	   }
	 //window.location.href = (localStorage.getItem("callback_section")!="null")? localStorage.getItem("callback_section"): //window.location.origin;
   });

$('.remodal-main-overlay:not(.container)').click(function(){
inst.close();
});
  $('.LeftArrow').click(function(){
  //inst.close();
  $('#load_spinner').show();
 });
  $('.RightArrow').click(function(){
  //inst.close();
  $('#load_spinner').show();
 });
});
</script>

<!--<script src="<?php echo $js_path; ?>js/postscribe.min.js"></script>-->

<div class="mobile_share">
	<span class="mf" onclick="mfb('facebook')"><i class="fa fa-facebook-square" aria-hidden="true"></i> facebook</span>
	<span class="mt" onclick="mfb('twitter')"><i class="fa fa-twitter-square" aria-hidden="true"></i> twitter</span>
	<span class="mw" onclick="mfb('whatsapp')"><i class="fa fa-whatsapp" aria-hidden="true"></i> whatsapp</span>
	<span class="mbn" id="mbn" style="display:none;" onclick="mfb('next')"><i class="fa fa-caret-square-o-right" aria-hidden="true"></i> Next</span>
	<script>
		var mb_prev = $('#mb_prev').val();
		var mb_next = $('#mb_next').val();
		if(mb_prev!='' && mb_prev!=undefined){
			$('#mbp').show();
		}
		if(mb_next!='' && mb_next!=undefined){
			$('#mbn').show();
		}
		function mfb(type){
			if(type=='whatsapp'){
				$('.whatsapp').click();
			}else if(type=='email'){
				var sub =$('a[data-type="twitter"]').attr('data-txt');
				var body  =$('meta[property="og:url"]').attr('content');
				window.open('mailto:?subject='+sub+'&body='+body);
			}else if(type=='prev'){
				window.location.href= mb_prev;
			}else if(type=='next'){
				window.location.href= mb_next;
			}else{
				$('a[data-type="'+type+'"]').click();
			}
		}
	</script>
</div>
</body>
</html>
