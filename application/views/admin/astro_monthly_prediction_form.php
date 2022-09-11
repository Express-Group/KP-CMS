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
<script type="text/javascript" src="<?php echo base_url();?>js/astro_monthly_predictions.js"></script>
<span class="previewcontainer">

</span>
</head><style>
@font-face {
 font-family: 'Dinamani-font';
 src: url('fonts/tamil-font/Dinamani-font.eot?#iefix') format('embedded-opentype'), url('fonts/tamil-font/Dinamani-font.woff') format('woff'), url('fonts/tamil-font/Dinamani-font.ttf') format('truetype'), url('fonts/tamil-font/Dinamani-font.svg#AksharUnicodeRegular') format('svg');
 font-weight: normal;
 font-style: normal;
}
label.error {
	color:#F00;
	display: block;
}
#mandatory
{
	color:red;	
}
</style>
<script type="text/javascript"> var base_url = '<?php echo base_url(); ?>'; 
var base_lang = '<?php echo $language; ?>';	
</script>
<script>
$(document).ready(function()
{
	
	CKEDITOR.replace( 'txtPredictionDetails',
    {
        toolbar : [ { name: 'basicstyles', items: [ 'Bold', 'Italic', 'TextColor','FontSize','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock' ] } ]
		
    });	
	
	
});
var StaicMonthArray = [];
var SelectMonth = '';
var SelectYear ='';
<?php
if(isset($fetch_values['prediction_month']) && $fetch_values['prediction_month'] != "") { ?>
	 var SelectMonth = "<?php echo $fetch_values['prediction_month']; ?>";
<?php } ?>
<?php
if(isset($fetch_values['prediction_date']) && $fetch_values['prediction_date'] != "") { ?>
	 var SelectYear= "<?php echo $fetch_values['prediction_date']; ?>";
<?php } ?>
</script>

<?php
$language = ($language=='tamil')? $language: '';
if(isset($fetch_values['prediction_id']) && $fetch_values['prediction_id'] != '' )
{
	$title1 = 'Astrology '.ucfirst($language).' Monthly predictions - Edit';
}else{ 
$title1 = 'Astrology '.ucfirst($language).' Monthly predictions - Create';
}
$qry = ($language=='tamil')? '/'.$language: '';
?>
<body>
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
		<form name="monthlyPredictions_form" id="monthlyPredictions_form" action="<?php echo base_url(folder_name); ?>/astro_monthly_prediction/add_monthly_predictions" method="post" enctype="multipart/form-data">
			<div class="BodyHeadBg Overflow clear">
				<div class="FloatLeft BreadCrumbsWrapper">
					<div class="breadcrumbs"><a href="<?php echo base_url().folder_name; ?>">Dashboard ></a><a href="#">Astrology -</a>  <a href="#"><?php echo $title; ?></a></div>
					<h2 class="FloatLeft"><?php echo $title1; ?></h2>
				</div>
				<!--<div class="FloatLeft Error">Error Message</div>-->
				<p class="FloatRight save-back save_margin article_save"> 
				 <a class="FloatLeft back-top" href="<?php echo base_url().folder_name; ?>/astro_monthly_prediction<?php echo $qry;?>"><i class="fa fa-reply fa-2x"></i></a>
				 <a class="back-top FloatLeft top_iborder" href="#" data-remodal-target="preview_article_popup" title="Preview" id="preview_id" ><i class="fa fa-desktop i_extra">
				</i></a>
					<button class="btn-primary btn" type="button" name="btnMonthlyPrediction" id="btnMonthlyPrediction"><i class="fa fa-file-text-o"></i> &nbsp;Save</button>
				</p>
			</div>
		
			<div class="panchangam-time">
				<div class="section_content  ">
					<div class="section_form">
			
							<div class="panchangam-sec1">
							<?php if($language!='tamil'){ ?>
							    <div class="panchangam-date1">
									<!--<div class="qns">
										<label class="question" style="width: 104px;">From Date<span id="mandatory" >*</span></label>
										<label class="question" style="width: 104px;">To Date<span id="mandatory">*</span></label>
									</div>-->
									
									<div class="ans panchangam-date">
										<div id="datetimepicker1" class="input-append date panchangam-kalam">
<?php
// set the month array
$formattedMonthArray = array(
                    "1" => "January", "2" => "February", "3" => "March", "4" => "April",
                    "5" => "May", "6" => "June", "7" => "July", "8" => "August",
                    "9" => "September", "10" => "October", "11" => "November", "12" => "December",
                );
?>
<!-- displaying the dropdown list -->

<div class="rasiqns">
										<label style="text-align:left;" class="question">Month<span id="mandatory" >*</span></label>
										<select name="prediction_month" id="prediction_month" class="controls"  >
<option value="">Select</option>
    <?php
	$curr_month =  date('m');
	//$curr_year = date('Y');

	$bool = true;
	
    foreach ($formattedMonthArray as $key => $month) {
        // if you want to select a particular month
		
		if($key >= $curr_month ) {
        $fetch_month='';
		if(isset($fetch_values['prediction_month']) && $fetch_values['prediction_month'] != "") { $fetch_month = $fetch_values['prediction_month'];}
		//echo $fetch_values['prediction_month'];
		 if($fetch_month == $month) {
			 $selected =  'selected';
			 $bool = false;
		 } else {
			 $selected =  '';
		 }
			 ?>
			<script type="text/javascript">
	StaicMonthArray.push("<?php echo $month; ?>");
			</script>
			<?php 
        echo '<option '.$selected.' value="'.$month.'">'.$month.'</option>';
		
		
		}
		  
    }
	
		if($bool ==  true && isset($fetch_values['prediction_month'])  && $fetch_values['prediction_month'] != "" ) {
					 echo '<option selected value="'.$fetch_values['prediction_month'].'">'.$fetch_values['prediction_month'].'</option>'; ?>
			<script type="text/javascript">
				StaicMonthArray.push("<?php echo $fetch_values['prediction_month']; ?>");
			</script>
	<?php 	}
    ?>
</select>
										<p id="date_error" style="color:#F00"></p>
</div>
<div class="rasiqns">
				<label class="question" style="text-align:left;">Year<span id="mandatory">*</span></label>						
										<?php
$previous_year = date('Y');
$next_year = date('Y')+5;
$year_range = range($previous_year, $next_year);
?>
<select name="prediction_date" id="prediction_date" class="controls" onChange="chan(this.value)" >
												<option value="">Select</option>
												<?php
												 $boolyear = true;
												foreach ($year_range as $year) {
													//$date_year = date('d-m').'-'.$year;
													$date_year = $year;
													$fetch_year = '';
													if(isset($fetch_values['prediction_date']) && $fetch_values['prediction_date'] != "") 
														$fetch_year = date('Y', strtotime($fetch_values['prediction_date']));
													//$fetch_year =$fetch_values['prediction_date'];
													$current_year = date('Y');
													
													//$selected_year = ($fetch_year == $year) ? 'selected' : '';
													if($fetch_year == $year)
													{ 
														$selected_year = 'selected';
														$boolyear = false;
													}
													/*elseif($current_year == $year) 
														$selected_year = 'selected';*/
														else
														{
															$selected_year = '';
														}

													echo '<option '.$selected_year.' value="'.$date_year.'">'.$year.'</option>';
												}
												if($boolyear ==  true && isset($fetch_values['prediction_date'])  && $fetch_values['prediction_date'] != "" ) {
													$fetch_year = date('Y', strtotime($fetch_values['prediction_date']));
					 echo '<option selected value="'.$fetch_year.'">'.$fetch_year.'</option>'; }
												?>
											</select>
										<p id="date_error1" style="color:#F00"></p>
</div>
									
											<input type="hidden" name="hidden_id" id="hidden_id" value="<?php if(isset($fetch_values['prediction_id']) && $fetch_values['prediction_id'] != "") { echo $fetch_values['prediction_id'];} ?>" />		
											 </div>
										<!--<p id="date_error" style="color:#F00"></p>-->
									</div>
								</div>
	<?php }else{ ?>
<div class="panchangam-date1">
		<div class="qns">
			<label class="question" style="width: 104px;">From Date<span id="mandatory">*</span></label>
			<label class="question" style="width: 104px;">To Date<span id="mandatory">*</span></label>
		</div>
		<div class="ans panchangam-date">
			<div id="datetimepicker1" class="input-append date panchangam-kalam">
				<input data-format="dd-MM-yyyy " type="text" name="prediction_start_date" id="prediction_start_date" value="<?php if(isset($fetch_values['prediction_date']) && $fetch_values['prediction_date'] != "") { echo date('d-m-Y', strtotime($fetch_values['prediction_date']));} ?>" >
				<span style="padding-left:20px;"><input type="text" name="prediction_end_date" id="prediction_end_date" value="<?php if(isset($fetch_values['prediction_end_date']) && $fetch_values['prediction_end_date'] != "") { echo date('d-m-Y', strtotime($fetch_values['prediction_end_date']));} ?>" >	</span>	
			</div>
			<p id="prediction_start_date_error" style="color:#F00"></p>
			<p id="prediction_end_date_error" style="color:#F00"></p>
		</div>
		<input type="hidden" name="hidden_id" id="hidden_id" value="<?php if(isset($fetch_values['prediction_id']) && $fetch_values['prediction_id'] != "") { echo $fetch_values['prediction_id'];} ?>" />
	</div>
<?php } ?>							
<div class="qns"><label style="text-align:left;" class="question">Zodiac Name<span style="color:#F00">*</span></label></div>
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
										<h4  style="text-align:left;" class="ColorTheme">Prediction Details<span id="mandatory">*</span></h4>
										<textarea class="ckeditor" name="txtPredictionDetails" id="txtPredictionDetails"><?php if(isset($fetch_values['prediction_details']) && $fetch_values['prediction_details'] != "") echo $fetch_values['prediction_details']; ?></textarea>
										
										<p id="predictiondetails_error" style="color:#F00"></p>
									</div>
							<input type="hidden" name="language" id="language" value="<?php echo $language; ?>" />
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
 </body>
<!--preview end-->

    <script type="text/javascript" src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.remodal.js"></script>
	 <script language="javascript">
	 $(document).ready(function() {
		  $("#prediction_start_date").datepicker({
                dateFormat: 'dd-mm-yy',
				//minDate: 0,
				onClose: function( selectedDate ) {
				$( "#prediction_end_date" ).datepicker( "option", "minDate", selectedDate );
				}
            });
		$("#prediction_end_date").datepicker({
                dateFormat: 'dd-mm-yy',
				onClose: function( selectedDate ) {
				$( "#prediction_start_date" ).datepicker( "option", "maxDate", selectedDate );
				}
            });
		 
		 
		 var hidden=$('#hidden_id').val();
		 var today = new Date();
		var cur_year = today.getFullYear();
		//alert('ddd');
		var year= $('#prediction_date').val();
		if(hidden !='')
		{
				if(year < cur_year )
				{
				$('#prediction_month')
				.find('option')
				.remove()
				.end()
				.append('<option value="">Select</option>')
				.val('');
				jQuery('<option/>', {
				value: SelectMonth,
				html: SelectMonth
				}).appendTo('#prediction_month');
				$('#prediction_month').val(SelectMonth); 
				
				}
				
				
				var formattedMonthArray1 = [ "January", "February", "March",  "April",
                     "May", "June",  "July", "August",
                    "September",  "October",  "November",  "December" ]
				
					if(year > cur_year )
					{
					
					$('#prediction_month')
					.find('option')
					.remove()
					.end()
					.append('<option value="">Select</option>')
					.val('')
					;
					
					
					for(var i=0; i< formattedMonthArray1.length;i++)
					{
					
					//creates option tag
					jQuery('<option/>', {
					value: formattedMonthArray1[i],
					html: formattedMonthArray1[i]
					}).appendTo('#prediction_month'); //appends to select if parent div has id dropdown
					}
					
					
					$('#prediction_month').val(SelectMonth);
					}
					
				
				
				
				
			
		}
		 
		 
		 
		 
		 });
		 
		 
		 
		 
		 
	function chan(val)
	{
		
		
		//alert(val);
		var select_year = val;
		//alert(select_year);
		var today = new Date();
		var cur_year = today.getFullYear();
		//alert(yyyy);
		//$('form select #prediction_month').val("");
		$('#prediction_month')
    .find('option')
    .remove()
    .end()
    .append('<option value="">Select</option>')
    .val('')
;

var formattedMonthArray = [ "January", "February", "March",  "April",
                     "May", "June",  "July", "August",
                    "September",  "October",  "November",  "December" ]
               
			   if(val == cur_year) {
			 	formattedMonthArray =  StaicMonthArray;
			   }
			   
for(var i=0; i< formattedMonthArray.length;i++)
{
//creates option tag
  jQuery('<option/>', {
        value: formattedMonthArray[i],
        html: formattedMonthArray[i]
        }).appendTo('#prediction_month'); //appends to select if parent div has id dropdown
}


var sel = new Date(SelectYear);
		var se =sel.getFullYear();
		//alert(se);
 if(val == se) {
	 if(se<cur_year)
	 {
		 //alert('fff');
		 $('#prediction_month')
    .find('option')
    .remove()
    .end()
    .append('<option value="">Select</option>')
    .val('')
	
	jQuery('<option/>', {
        value: SelectMonth,
        html: SelectMonth
        }).appendTo('#prediction_month'); 
	 }
	 
		 $('#prediction_month').val(SelectMonth);
	 }
	 
	 var hidden=$('#hidden_id').val();
		
		if(hidden !='')
		{
			if(se !=cur_year)
			{
				if(val==cur_year)
				{
					//alert('ddd');
					dropdownElement = $("#prediction_month");
					dropdownElement.find('option[value='+SelectMonth+']').remove();
				}
			}
			
			
			
		}
	 
	 
	 
	 
	}
	
	/*function myFunction1()
	{
		var formattedMonthArray1 = [ "January", "February", "March",  "April",
                     "May", "June",  "July", "August",
                    "September",  "October",  "November",  "December" ]
		var today = new Date();
		var cur_year = today.getFullYear();
		//alert('ddd');
		var year= $('#prediction_date').val();
		if(year > cur_year )
		{
			
			$('#prediction_month')
    .find('option')
    .remove()
    .end()
    .append('<option value="">Select</option>')
    .val('')
;
			
			
for(var i=0; i< formattedMonthArray1.length;i++)
{
	
//creates option tag
  jQuery('<option/>', {
        value: formattedMonthArray1[i],
        html: formattedMonthArray1[i]
        }).appendTo('#prediction_month'); //appends to select if parent div has id dropdown
}


$('#prediction_month').val(SelectMonth);
		}
		//alert(year);
		
		
		
		if(year < cur_year )
		{
			$('#prediction_month')
    .find('option')
    .remove()
    .end()
    .append('<option value="">Select</option>')
    .val('')
;
		jQuery('<option/>', {
        value: SelectMonth,
        html: SelectMonth
        }).appendTo('#prediction_month');
		$('#prediction_month').val(SelectMonth); 
	 	
		}
	var l_year=	$('#prediction_date').val();
	var today = new Date();
		var cur_year = today.getFullYear();
		if(l_year<cur_year)
		{
			$('#prediction_month')
    .find('option')
    .remove()
    .end()
    .append('<option value="">Select</option>')
    .val('')
;
			
		}
		var sel = new Date(SelectYear);
		var se =sel.getFullYear();
		//alert(se);
 if(val == se) {
	 if(se<cur_year)
	 {
		 alert('fff');
		 $('#prediction_month')
    .find('option')
    .remove()
    .end()
    .append('<option value="">Select</option>')
    .val('')
	
	jQuery('<option/>', {
        value: SelectMonth,
        html: SelectMonth
        }).appendTo('#prediction_month'); 
	 }
	 
		 $('#prediction_month').val(SelectMonth);
	 }
		
	}*/
	
	
	
        /*$(document).ready(function () {
		
			var today = new Date();
			var dd = today.getDate();
			var mm = today.getMonth()+1; //January is 0!
			var yyyy = today.getFullYear();
			var day_count = daysInMonth(mm,yyyy);
            $("#prediction_date").datepicker({
                minDate: - day_count,
				dateFormat: 'dd-mm-yy',
				beforeShowDay: function(date){ 
				  if(date.getDate() == 1) { return [true, '']; } 
                  else { return [false, '', 'Unavailable']; }
				},
				onSelect: function(selected, evnt) {
					return updateAb(selected);
				}
            });
			 $(".ui-icon").addClass("fa fa-chevron-right");
             $(".ui-icon").addClass("fa fa-chevron-left");
        });*/
		/*$('#prediction_date').change(function(){
  $('#prediction_month').val( $('#prediction_month').prop('defaultSelected') );
});*/


		/*$(document).ready(function () {
		
			
		$('#prediction_date').change(function(){
    $('#prediction_month').prop('selectedIndex',0);
});
	
			
			
		
		 });*/
		
		
		
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
			
			
			var postdata = {"body_text" :body_text,"date": date,"rasi_name":raasi_name,"menu_name":'<?php echo 'Astrology Monthly Prediction'; ?>'};
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
			document.getElementById('prediction_end_date').value = '';
			var date = selected;
			if(date !='')
			{
				$.ajax({
					type: "POST",
					data: {"selected_date":date},
					url: base_url+folder_name+"/astro_monthly_prediction/change_dateFormat",
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
	
		function daysInMonth(month,year) {
			return new Date(year, month, 0).getDate();
		}
    </script>