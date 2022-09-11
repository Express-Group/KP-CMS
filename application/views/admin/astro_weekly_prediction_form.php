<span class="css_and_js_files">
<link href="<?php echo base_url(); ?>css/admin/prabu-styles.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>css/admin/bootstrap.min.css" type="text/css">
<link href="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/css/bootstrap-combined.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>css/admin/jquery_panchangam-ui.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" media="screen" href="http://tarruda.github.com/bootstrap-datetimepicker/assets/css/bootstrap-datetimepicker.min.css">
<link href="<?php echo base_url(); ?>includes/ckeditor/contents.css" rel="stylesheet" type="text/css" />
</span>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-1.8.3.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>includes/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/additional-methods.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/astro_weekly_predictions.js"></script>
<span class="previewcontainer">

</span>
</head><style>
label.error {
	color:#F00;
	display: block;
}
#mandatory
{
	color:red;	
}
.section_content label{text-align:left !important;}
</style>
<script type="text/javascript"> var base_url = '<?php echo base_url(); ?>'; </script>
<script>
$(document).ready(function()
{
	
	CKEDITOR.replace( 'txtPredictionDetails',
    {
        toolbar : [ { name: 'basicstyles', items: [ 'Bold', 'Italic', 'TextColor','FontSize','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock' ] } ]
		
    });	
	
	
});
</script>
<?php
if(isset($fetch_values['prediction_id']) && $fetch_values['prediction_id'] != '' )
{
	$title1 = 'Astrology Weekly predictions - Edit';
}else{ $title1 = 'Astrology Weekly predictions - Create';}

?>

<div class="Container">
	<div class="BodyWhiteBG">
		<?php
		if(($this->session->flashdata("success"))) { ?>
			<div id="flash_msg_id" class="FloatLeft SessionSuccess"><?php echo $this->session->flashdata("success");?></div>
		<?php } ?>
		<?php if($this->session->flashdata("success_delete")) { ?>
			<div class="FloatLeft SessionSuccess" id="flash_msg_id"><?php echo $this->session->flashdata("success_delete");?></div>
		<?php } ?>
		<?php if(($this->session->flashdata("error"))) { ?>
			<div id="flash_msg_id" class="FloatLeft SessionError"><?php echo $this->session->flashdata("error");?></div>
		<?php } ?>
		<form name="weeklyPredictions_form" id="weeklyPredictions_form" action="<?php echo base_url(folder_name); ?>/astro_weekly_prediction/add_weekly_predictions" method="post" enctype="multipart/form-data">
			<div class="BodyHeadBg Overflow clear">
				<div class="FloatLeft BreadCrumbsWrapper">
					<div class="breadcrumbs"><a href="<?php echo base_url(folder_name); ?>">Dashboard ></a><a href="#">Astrology -</a>  <a href="#"><?php echo $title; ?></a></div>
					<h2 class="FloatLeft"><?php echo $title1; ?></h2>
				</div>
				<!--<div class="FloatLeft Error">Error Message</div>-->
				<p class="FloatRight save-back save_margin article_save"> 
				 <a class="FloatLeft back-top" href="<?php echo base_url(folder_name); ?>/astro_weekly_prediction"><i class="fa fa-reply fa-2x"></i></a>
				 <a class="back-top FloatLeft top_iborder" href="#" data-remodal-target="preview_article_popup" title="Preview" id="preview_id" ><i class="fa fa-desktop i_extra">
				</i></a>
					<button class="btn-primary btn" type="button" name="btnWeeklyPrediction" id="btnWeeklyPrediction"><i class="fa fa-file-text-o"></i> &nbsp;Save</button>
				</p>
			</div>

			<div class="panchangam-time">
				<div class="section_content  ">
					<div class="section_form">

							<div class="panchangam-sec1">
							    <div class="panchangam-date1">
									<div class="qns">
										<label class="question" style="width: 104px;">From Date<span id="mandatory" >*</span></label>
										<label class="question" style="width: 104px;margin-left: 10.5%;">To Date<span id="mandatory">*</span></label>
									</div>
									<div class="ans panchangam-date">
										<div id="datetimepicker1" class="input-append date panchangam-kalam">
											<input style="height: 33px;" data-format="dd-MM-yyyy " type="text" name="prediction_date" id="prediction_date" value="<?php if(isset($fetch_values['prediction_date']) && $fetch_values['prediction_date'] != "") { echo $fetch_values['prediction_date'];} ?>" />
											<span style="padding-left:20px;"><input style="height: 33px;" type="text" name="prediction_end_date" id="prediction_end_date" value="<?php if(isset($fetch_values['prediction_end_date']) && $fetch_values['prediction_end_date'] != "") { echo $fetch_values['prediction_end_date'];} ?>"  readonly />	</span>
											<input type="hidden" name="hidden_id" id="hidden_id" value="<?php if(isset($fetch_values['prediction_id']) && $fetch_values['prediction_id'] != "") { echo $fetch_values['prediction_id'];} ?>" />		
										</div>
										<p id="date_error" style="color:#F00"></p>
									</div>
								</div>
								
<div class="qns"><label class="question">Zodiac Name<span style="color:#F00">*</span></label></div>
<div class="ans w2ui-field">
<select id="raasi_name" name="raasi_name" class="controls">
<option value="">- Select -</option>
 <?php
	  foreach($raasi_lists as $key => $raasi_list)
	  {
		
	  ?>
		<option value="<?php echo $raasi_list['Section_id'];?>" <?php if(isset($fetch_values['section_id']) && $fetch_values['section_id'] != "") { if($raasi_list['Section_id'] == $fetch_values['section_id']) { echo 'selected'; } } ?> ><?php echo $raasi_list['Sectionname'];?></option>
		<?php
	  }
	  ?>
  
</select>
<p id="already_error" style="color:#F00"></p>
</div>

								
								
									<div class="panchangam-section1">
										<h4 class="ColorTheme">Prediction Details<span id="mandatory">*</span></h4>
										<textarea class="ckeditor" name="txtPredictionDetails" id="txtPredictionDetails"><?php if(isset($fetch_values['prediction_details']) && $fetch_values['prediction_details'] != "") echo $fetch_values['prediction_details']; ?></textarea>
										
										<p id="predictiondetails_error" style="color:#F00"></p>
									</div>
							
							</div>
							
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<!--preview-->
<div class="remodal" id="preview_article_popup" data-remodal-id="preview_article_popup" data-remodal-options="hashTracking: false" style="position:relative;">
 <div id="preview_article_popup_loading">
 
 </div>
 <div id="preview_article_popup_container"  class="container" style="display:none;">
 
 </div>
 </div>
<!--preview end-->

    <script type="text/javascript" src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.remodal.js"></script>
	<!-- Include Required Prerequisites -->	
	<script type="text/javascript" src="http://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
	<link rel="stylesheet" type="text/css" href="http://cdn.jsdelivr.net/bootstrap/latest/css/bootstrap.css" />	
	
	 <script language="javascript">
        $(document).ready(function () {
			/*$('#to_date').datepicker({
				dateFormat: 'dd-mm-yy', 
			});*/
            $("#prediction_date").datepicker({
                minDate: -7,
				dateFormat: 'dd-mm-yy',
				beforeShowDay: function(date){ 
				  return [date.getDay() == 0];
				},
				onSelect: function(selected, evnt) {
					return updateAb(selected);
				}
            });
			
			 $(".ui-icon").addClass("fa fa-chevron-right");
             $(".ui-icon").addClass("fa fa-chevron-left");
        });
		$("#preview_id").click(function() {
			
			body_text = encodeURIComponent(CKEDITOR.instances.txtPredictionDetails.getData());
			
			
			$("#preview_article_popup_container").hide();
			$("#preview_article_popup_loading").hide();
			
			
			$("#preview_article_popup_loading").html('<img style="width:40px; height:40px;" src="'+base_url+'images/admin/loadingroundimage.gif">')
			$('.remodal-close').hide();
			$("#preview_article_popup_loading").show();
			
			var date =$("#prediction_date").val();
			//var raasi_name =$("#raasi_name").val();	
			var raasi_name=$('#raasi_name').find('option:selected').text();
			//console.log(date);console.log(raasi_name);
			body_text = encodeURIComponent(CKEDITOR.instances.txtPredictionDetails.getData());
			
			
			var postdata = {"body_text" :body_text,"date": date,"rasi_name":raasi_name,"menu_name":'<?php echo 'Astrology Weekly Prediction'; ?>'};
			$.ajax({
			//url: base_url+folder_name+"/article_manager/get_astrology_preview_popup",
			url: "<?php echo base_url(folder_name); ?>/article_manager/get_astrology_preview_popup",  // Url to which the request is send
			type: "POST",             // Type of request to be send, called as method
			data:  postdata,
			dataType: "HTML",
			async: false, 
			success: function(data)   // A function to be called if request succeeds
			{
				
		setTimeout(function(){
			
		//$('link[rel=stylesheet][href~="'+base_url+'css/admin/dashboard-style.css"]').remove();
		$("#contents_css").remove();

		$(".previewcontainer").append($('<link rel="stylesheet" href="'+base_url+'css/FrontEnd/css/style.css" type="text/css">')); 
		//$(".previewcontainer").append($("<script type='text/javascript' src='"+base_url+folder_name+"js/article-pagination.js'>"));
	
	},1000);
	
	setTimeout(function(){
		$('.remodal-close').show();
	$("#preview_article_popup_container").html(data);	

	$("#preview_article_popup_loading").hide();
	$("#preview_article_popup_container").show();
	

		},1000);
		
		
			},
		});
	
			}); 
			
			
			$(document).on('close', '#preview_article_popup', function () {  
	 
		$(".css_and_js_files").append($('<link rel="stylesheet" href="'+base_url+'includes/ckeditor/contents.css" type="text/css"  id="contents_css">'));  
		
		//$("script[src='"+base_url+folder_name+"js/article-pagination.js']").remove();
		
		$('link[rel=stylesheet][href~="'+base_url+'css/FrontEnd/css/style.css"]').remove();
	
 
});
		
		function updateAb(selected)
		{
		
			var date = selected;
			if(date !='')
			{
				$.ajax({
					type: "POST",
					data: {"selected_date":date},
					url: base_url+folder_name+"/astro_weekly_prediction/change_dateFormat",
					success: function(data)
					{
						document.getElementById('prediction_end_date').value = data;	
					}
					
				});
			}
			else{
				document.getElementById('prediction_end_date').value = '';
			}			
		}
    </script>