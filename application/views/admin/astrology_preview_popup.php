<style>
#preview_article_popup_container .SectionContainer{
	padding: 0 13px !important;
}
@media only screen and (max-width: 1550px) and (min-width: 1297px){
 .container, .remodal.main-menu {
    width: 980px;
}
}
#preview_article_popup{
	display: block;

}
.remodal-overlay {
    background-color: #000 !important;
}
.remodal-overlay:after{
display:inline;
}
#preview_article_popup .pagination{
background:none;
box-shadow:none;
width:auto;
margin: 0 !important;
padding-right: 0;
}
#preview_article_popup .SectionContainer{
	overflow:hidden;
}
#preview_article_popup .page{
	background:#fff !important;
	color:#000 !important;
	font-weight:normal;
}
#preview_article_popup .article_pagination{
	width:80%;
    
}
#preview_article_popup #auto-play .fa-pause{
	margin:0;
}
#preview_article_popup #auto-play i{
	
	font-size:12px;
}
#preview_article_popup .remodal-close{
	top:0;
}

@media only screen and (max-width: 1550px) and (min-width: 1297px){
#preview_article_popup .remodal-close{
	right:-166px;
}
#preview_article_popup{
	display: block;
    background: none;
    box-shadow: none;
    width: 866px;
    margin: auto 150px;

}
}
#preview_article_popup .VideoScriptContent, #preview_article_popup .AudioContent{
	margin-bottom:30px;
}
#preview_article_popup .play-pause-icon {
    background: #fff none repeat scroll 0 0;
    border: 2px solid #ccc;
    border-radius: 100%;
    height: 27px;
    padding: 5px 0;
    position: absolute;
    right: 24px;
    top: 10px;
    width: 28px;
    z-index: 999;
}
#preview_article_popup p {
	line-height:18px; 
}
#preview_article_popup .page{
padding:0;
margin:0;
}
#preview_article_popup .pagination a{
	font-size:12px;
			float: left;
	padding: 6px 10px;	
}

#preview_article_popup iframe, #preview_article_popup audio {
	margin:10px 0;
}

#preview_article_popup li{
	float: none; list-style: initial;
	margin-left: 16px;
}

#preview_article_popup blockquote {
     padding-left: 20px !important;
    padding-right: 8px !important;
    border-left-width: 5px;
    border-color: #ccc;
    font-style: italic;
	margin:10px 0 !important;
 padding: 12px 16px !important;
 font-size:13px !important;
}
#preview_article_popup  blockquote p{font-size:13px; !important;text-align:center; }
#preview_article_popup li a{ float:none}
</style>


<?php

/*if(isset($rasi_id) && $rasi_id != '') {*/
	
	switch($rasi_name) {
		case 'மேஷம்':
			$ClassName = "rasi_mesham";
		break;
		case 'ரிஷபம்':
			$ClassName = "rasi_rishabam";
		break;
		case 'தனுசு':
			$ClassName = "rasi_dhanusu";
		break;
		case 'கடகம்':
			$ClassName = "rasi_kadagam";
		break;
		case 'கன்னி':
		$ClassName = "rasi_kanni";
		break;
		case 'கும்பம்':
			$ClassName = "rasi_kumbam";
		break;
		case 'மகரம்':
			$ClassName = "rasi_magaram";
		break;
		case 'மிதுனம்':
			$ClassName = "rasi_midhunam";
		break;
		case 'சிம்மம்':
			$ClassName = "rasi_simmam";
		break;
		case 'துலாம்':
			$ClassName = "rasi_thulam";
		break;
		case 'விருச்சிகம்':
			$ClassName = "rasi_viruchigam";
		break;
		case 'மீனம்':
			$ClassName = "rasi_menam";
		break;
		case '- Select -':
			$rasi_name = "";
			$ClassName = "rasi_mesham";
		break;
		default:
			$ClassName = "rasi_mesham";
		break;
	}
	/*}*/
	
	
//$astrology_results = astrology_list(@$rasi_id);
?>
<section>

<div class="container SectionContainer ">	
<div class="row">
<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12" style="margin-top:10px;">
 <!--<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">-->

<?php if($menu_name == 'Astrology General Prediction' )
{ ?>
<!--general-->		
<div class="rasi-full">
<?php
if(isset($rasi_name) && $rasi_name != '') { ?>

  <h4 class="rasi-title"><?php echo $rasi_name; ?></h4>
  
  <?php } ?>
  <div class="common-rasi"> 
  	<div class="rasi-cover">
       <span class="rais-img <?php echo $ClassName; ?>"></span>
    </div>
    <!--<p>20 March – 19 April</p>-->
  <?php if(trim(strip_tags($body_text)) != '') echo @$body_text; else echo "<p> Please give the Astrology General Prediction Body Text </p>"; ?>
  </div>
</div>

<!--<img src="<?php echo base_url().'images/FrontEnd/images/ast_daily_popup.jpg'; ?>"/>
<img src="<?php echo base_url().'images/FrontEnd/images/ast_weekly_popup.jpg'; ?>"/>
<img src="<?php echo base_url().'images/FrontEnd/images/ast_monthly_popup.jpg'; ?>"/>
<img src="<?php echo base_url().'images/FrontEnd/images/ast_yearly_popup.jpg'; ?>"/>
--> <!--general end--> 
<?php } ?>


<?php if($menu_name == 'Astrology Daily Prediction' )
{ ?>
<!--<img src="<?php echo base_url().'images/FrontEnd/images/ast_general_popup.jpg'; ?>"/>
--><div class="rasi-today">
    <h4 class="rasi-title">Today</h4>
    <?php if(trim(strip_tags($body_text)) != '') echo @$body_text; else echo "<p> Please give the Astrology Daily Prediction Body Text </p>"; ?>
  </div>
<!--<img src="<?php echo base_url().'images/FrontEnd/images/ast_weekly_popup.jpg'; ?>"/>
<img src="<?php echo base_url().'images/FrontEnd/images/ast_monthly_popup.jpg'; ?>"/>
<img src="<?php echo base_url().'images/FrontEnd/images/ast_yearly_popup.jpg'; ?>"/>
-->
<?php } ?>


<?php if($menu_name == 'Astrology Weekly Prediction' )
{ ?>
<!--<img src="<?php echo base_url().'images/FrontEnd/images/ast_general_popup.jpg'; ?>"/>
<img src="<?php echo base_url().'images/FrontEnd/images/ast_daily_popup.jpg'; ?>"/>
--><div class="rasi-today">
    <h4 class="rasi-title">This Week</h4>
     <?php if(trim(strip_tags($body_text)) != '') echo @$body_text; else echo "<p> Please give the Astrology Weekly Prediction Body Text </p>"; ?>
  </div>
<!--<img src="<?php echo base_url().'images/FrontEnd/images/ast_monthly_popup.jpg'; ?>"/>
<img src="<?php echo base_url().'images/FrontEnd/images/ast_yearly_popup.jpg'; ?>"/>
--><?php } ?>


<?php if($menu_name == 'Astrology Monthly Prediction' )
{ ?>
<!--<img src="<?php echo base_url().'images/FrontEnd/images/ast_general_popup.jpg'; ?>"/>
<img src="<?php echo base_url().'images/FrontEnd/images/ast_daily_popup.jpg'; ?>"/>
<img src="<?php echo base_url().'images/FrontEnd/images/ast_weekly_popup.jpg'; ?>"/>
--><div class="rasi-today">
    <h4 class="rasi-title">This Month</h4>
    <?php if(trim(strip_tags($body_text)) != '') echo @$body_text; else echo "<p> Please give the Astrology Monthly Prediction Body Text </p>"; ?>
  </div>
<!--<img src="<?php echo base_url().'images/FrontEnd/images/ast_yearly_popup.jpg'; ?>"/>
--><?php } ?>



<?php if($menu_name == 'Astrology Yearly Prediction' )
{ ?>
<!--<img src="<?php echo base_url().'images/FrontEnd/images/ast_general_popup.jpg'; ?>"/>
<img src="<?php echo base_url().'images/FrontEnd/images/ast_daily_popup.jpg'; ?>"/>
<img src="<?php echo base_url().'images/FrontEnd/images/ast_weekly_popup.jpg'; ?>"/>
<img src="<?php echo base_url().'images/FrontEnd/images/ast_monthly_popup.jpg'; ?>"/>
--><div class="rasi-today">
     <h4 class="rasi-title">This Year</h4>
   <?php if(trim(strip_tags($body_text)) != '') echo @$body_text; else echo "<p> Please give the Astrology Yearly Prediction Body Text </p>"; ?>
  </div>
<?php } ?>



</div>

<!--<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<img src="<?php echo base_url().'images/FrontEnd/images/add_300_250-small.jpg'; ?>"/>
		<img src="<?php echo base_url().'images/FrontEnd/images/most-read.jpg'; ?>"/>
		<img src="<?php echo base_url().'images/FrontEnd/images/facebook-twitter.jpg'; ?>"/>
	</div>
	</div>
</div>-->


</div>
</div>
</section>	