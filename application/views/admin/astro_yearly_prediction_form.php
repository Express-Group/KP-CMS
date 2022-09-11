<span class="css_and_js_files">
<link href="<?php echo base_url(); ?>css/admin/prabu-styles.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>css/admin/bootstrap.min.css" type="text/css">
<link href="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/css/bootstrap-combined.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>css/admin/jquery_panchangam-ui.css" rel="stylesheet" type="text/css" />>
<link rel="stylesheet" type="text/css" media="screen" href="http://tarruda.github.com/bootstrap-datetimepicker/assets/css/bootstrap-datetimepicker.min.css">
<link href="<?php echo base_url(); ?>includes/ckeditor/contents.css" rel="stylesheet" type="text/css" />
</span>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-1.8.3.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>includes/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/additional-methods.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/astro_yearly_predictions.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.remodal.js"></script>
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
.rasi-embed {
    left: 497px;
    position: absolute;
    top: -3px;
}
.panchangam-section1 {
 position:relative;
}

</style>
<script type="text/javascript"> var base_url = '<?php echo base_url(); ?>'; </script>
<script>
$(document).ready(function()
{
	
	CKEDITOR.replace( 'txtPredictionDetails',
    {
        toolbar : [ { name: 'basicstyles', items: [ 'Bold', 'Italic', 'TextColor','FontSize','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock' ] },  {items: ['Link','Unlink','Image']} ]
		
    });	
	
	/*CKEDITOR.replace( 'txtPredictionDetails', {
		  toolbar : [ 
		  {items: [ 'TextColor','BGColor','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','Bold','Italic','Underline'] },
			
		  {items: ['Cut', 'Copy','Paste','PasteText','PasteFromWord','Undo','Redo','Find','Replace','SelectAll']},
		  {items : [ 'Format']},
		  {items: ['Link','Unlink','Image']},
		  {items: [ 'Source','Strike','Subscript','Superscript','NumberedList','BulletedList','Outdent','Indent','Blockquote','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe'] }
		  ],
		    extraPlugins: 'autogrow', 
			removePlugins : 'magicline',
			autoGrow_maxHeight : 1000
			
    });*/
	
	//txtPredictionDetails = encodeURIComponent(CKEDITOR.instances.txtPredictionDetails.getData());
});
</script>

<?php
if(isset($fetch_values['prediction_id']) && $fetch_values['prediction_id'] != '' )
{
	$title1 = 'Astrology Yearly predictions - Edit';
}else{ $title1 = 'Astrology Yearly predictions - Create';}

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
		<form name="yearlyPredictions_form" id="yearlyPredictions_form" action="<?php echo base_url(folder_name); ?>/astro_yearly_prediction/add_yearly_predictions" method="post" enctype="multipart/form-data">
			<div class="BodyHeadBg Overflow clear">
				<div class="FloatLeft BreadCrumbsWrapper">
					<div class="breadcrumbs"><a href="<?php echo base_url(folder_name); ?>">Dashboard ></a><a href="#">Astrology -</a>  <a href="#"><?php echo $title; ?></a></div>
					<h2 class="FloatLeft"><?php echo $title1; ?></h2>
				</div>
				<!--<div class="FloatLeft Error">Error Message</div>-->
				<p class="FloatRight save-back save_margin article_save">  
				<a class="FloatLeft back-top" href="<?php echo base_url(folder_name); ?>/astro_yearly_prediction"><i class="fa fa-reply fa-2x"></i></a>
				<a class="back-top FloatLeft top_iborder" href="#" data-remodal-target="preview_article_popup" title="Preview" id="preview_id" ><i class="fa fa-desktop i_extra">
				</i></a>
					<button class="btn-primary btn" type="button" name="btnYearlyPrediction" id="btnYearlyPrediction"><i class="fa fa-file-text-o"></i> &nbsp;Save</button>
				</p>
			</div>
		
			<div class="panchangam-time">
				<div class="section_content  ">
					<div class="section_form">
							
							<div class="panchangam-sec1">
							    <div class="panchangam-date1">
									<div class="qns">
										<label style="text-align:left;" class="question">Year<span id="mandatory">*</span></label>
									</div>
									<div class="ans panchangam-date">
										<!--<div id="datetimepicker1" class="input-append date panchangam-kalam">-->
										<div class="ans w2ui-field">
											
											<!--<input data-format="yyyy" autocomplete="off" type="text" name="prediction_date" id="prediction_date" value="<?php //if(isset($fetch_values['prediction_date']) && $fetch_values['prediction_date'] != "") { echo $fetch_values['prediction_date'];} ?>" />-->
											
											<?php
											$previous_year = date('Y')-10;
											$next_year = date('Y')+10;
											$year_range = range($previous_year, $next_year);
											?>
											<select name="prediction_date" id="prediction_date" class="controls">
												<option value="">Select</option>
												<?php
												foreach ($year_range as $year) {
													$date_year = date('d-m').'-'.$year;
													$fetch_year = '';
													if(isset($fetch_values['prediction_date']) && $fetch_values['prediction_date'] != "") 
														$fetch_year = date('Y', strtotime($fetch_values['prediction_date']));
													
													$current_year = date('Y');
													
													$selected_year = ($fetch_year == $year) ? 'selected' : '';
													if($fetch_year == $year) 
														$selected_year = 'selected';
													elseif($current_year == $year) 
														$selected_year = 'selected';

													echo '<option '.$selected_year.' value="'.$date_year.'">'.$year.'</option>';
												}
												?>
											</select>

											<input type="hidden" name="hidden_id" id="hidden_id" value="<?php if(isset($fetch_values['prediction_id']) && $fetch_values['prediction_id'] != "") { echo $fetch_values['prediction_id'];} ?>" />		
											 </div>
										<p id="date_error" style="color:#F00"></p>
									</div>
								</div>
								
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
										<h4 class="ColorTheme">Prediction Details<span id="mandatory">*</span></h4>
										<div class="rasi-embed">
											 <a class="btn-primary btn FloatRight EmbedImage" href="#modal1"  onclick="ChangePopup('bodytext')" >Embed Image</a>
										 </div>
										<textarea class="ckeditor" name="txtPredictionDetails" id="txtPredictionDetails"><?php if(isset($fetch_values['prediction_details']) && $fetch_values['prediction_details'] != "") echo $fetch_values['prediction_details']; ?></textarea>
										
										<p id="predictiondetails_error" style="color:#F00"></p>
									</div>
							
							</div>
							
					</div>
				</div>
			</div>
		</form>
		
		<!-- #Remodal --- popup for image upload in text editor starts here -->
		<input type="hidden" name="hide_external_link" id="hide_external_link_id" value="" />
<input type="hidden" id="current_image_popup" value="" />
		<div class="remodal" data-remodal-id="modal1" style="position:relative;">
            <div class="article_popup GalleryPopup ArticlePopup" style="height: 467px;">
            <div class="article_popup1">
            <ul class="article_popup_tabs">
            <li onclick="articleUpload()" class="active img_upload">From Local System</li>
            <li onclick="articleBrowse()" class="img_browse">From Library</li>
            </ul>
            </div>
            <div class="article_popup2">
            <div class="article_upload">
       
          <form name="ImageForm" id="ImageForm" action="<?php echo base_url(); ?>dmcpan/astro_image/image_upload" method="POST" enctype="multipart/form-data">
              <div class="popup_addfiles">
                <div class="fileUpload btn btn-primary WidthAuto">
                    <span>+ Select Image</span>
                    <input type="file" id="imagelibrary" name="imagelibrary" accept="image/*" class="upload" style="width:100%;">
            </div>
            
             <div id="LoadingSelectImageLocal" style="display:none;"><img src="<?php echo base_url();?>images/admin/loadingimage.gif" style="border:none; width:23px; height:23px;" /><br />
			Please wait while image is being uploaded
            </div>
            
        </form>
          </div>
            <div class="GalleryDrag"  id="drop-area">
Drop files anywhere here to upload or click on the "Select Image" button above</div>
            </div>
            
                <div class="article_browse">
            <h3>Pick the item to insert</h3>
            <div class="article_browse1">
            <div class="article_browse_drop">
            <div class="w2ui-field FloatLeft">

  <select name="ddMainSectionSearch"  class="controls" id="image_section_search">
   <option value="">Section: All</option>
  
 <?php if(isset($section_mapping)) { 
				 foreach($section_mapping as $mapping) {  ?>

<option style="color:#933;font-size:18px;"  class="blog_option" value="<?php echo $mapping['Section_id']; ?>"><?php echo $mapping['Sectionname']; ?> 
  <?php if(!(empty($mapping['sub_section']))) { ?>

  <?php foreach($mapping['sub_section'] as $sub_mapping) { ?>
    <option  value="<?php echo $sub_mapping['Section_id']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $sub_mapping['Sectionname']; ?></option>
    	<?php } ?>
  <?php   } ?>
 </option>

  <?php } } ?>

</select>
</div>
<input type="text" placeholder="Search" id="search_caption" name="txtBrowserSearch"  class="box-shad1 FloatLeft BrowseInput" />
<i id="image_search_id" class="fa fa-search FloatLeft BrowseSearch"></i>
<a  class="btn btn-primary margin-left-10" id="clear_search" href="javascript:void(0);" style="display:none;">Clear Search</a>
            </div>
            <div class="popup_images transitions-enabled infinite-scroll clearfix"  id="image_lists_id">
            </div>
			 <?php if(isset($image_library)) { 
							$count = 0;
							
							foreach($image_library as $image) {
								$active_class = "";
								if($count==0) {
									$first_image_caption 	= $image->Title;
									$first_image_alt 		= $image->ImageAlt;
									$first_image_dimension	= $image->Height." X ".$image->Width;
									$first_image_size 		= $image->image_size;
									$first_image_date 		= $image->Modifiedon;
									$first_image_url 		= $image->ImageBinaryData2;
									$first_image_pathname	= $image->ImagePhysicalPath;
									$first_image_id 		= $image->content_id;
									$first_image_width 		= $image->Width;
									$first_image_height		= $image->Height;
								
									$active_class 			= "active";
								}
		 ++$count; } } ?>
			<nav id="page-nav">
			  <a href="<?php echo base_url(); ?>dmcpan/article_image/search_image_library_scroll/2"></a>
			</nav>
            </div>
            <div class="article_browse2">
            <?php if(isset($image_library)) {  ?>
            <h4>Image Details</h4>
            <?php  if(isset($first_image_pathname)) { ?>
            <img id="image_path" src="<?php echo image_url.imagelibrary_image_path.$first_image_pathname; ?>" />
            <h4 id="image_name"><?php echo $first_image_caption; ?></h4>
            <p>Dimentions:<span id="height_width"><?php echo $first_image_dimension; ?></span></p>
            <p>Size:<span id="image_size"><?php echo $first_image_size." Kb"; ?></span></p>
            <p>Date:<span id="image_date"><?php echo $first_image_date; ?></span></p>
            <input type="hidden" value="<?php echo $first_image_id; ?>" data-content_id="<?php echo $first_image_id; ?>" data-image_alt="<?php echo $first_image_alt; ?>" data-image_caption="<?php echo $first_image_caption; ?>" data-image_size="<?php echo $first_image_size; ?>" data-image_date="<?php echo $first_image_date; ?>" data-image_width="<?php echo $first_image_width; ?>" data-image_height="<?php echo $first_image_height; ?>" data-image_source="<?php echo image_url.imagelibrary_image_path.$first_image_pathname; ?>" data-image_path="<?php echo  image_url.imagelibrary_image_path.$first_image_pathname; ?>" id="browse_image_id" name="browse_image_id" />
            <div class="article_browse2_input">
            <label>Image Alt</label>
            <h5 id="textarea_alt"><?php echo $first_image_alt; ?></h5>
            <label>Caption</label>
            <h5  id="textarea_caption"><?php echo $first_image_caption; ?></h5>
              <?php } ?>
            
            </div>
    
            </div>
            <?php if(isset($first_image_pathname)) { ?>
            <div class="FloatRight popup_insert insert-fixed">
       <button type="button" class="btn btn-primary remodal-confirm"id="browse_image_insert"  >Insert</button>
            </div>
			 <?php } } ?>
            </div>
			
            </div>
            </div>
            </div>
		
		<!-- #Remodal --- popup for image upload in text editor ends here -->
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
	<!-- Mansory & Infinite Scroll Script -->
<script src="<?php echo base_url(); ?>js/jquery-1.7.1.min.js"></script>
<script src="<?php echo base_url(); ?>js/jquery.masonry.min.js"></script>
<script src="<?php echo base_url(); ?>js/jquery.infinitescroll.min.js"></script>
<style type='text/css'>
  /* Style to hide Dates / Months */
  .ui-datepicker-calendar,.ui-datepicker-month { display: none; }​
</style>
	 <script language="javascript">
        $(document).ready(function () {
            $("#prediction_date11").datepicker({
               // minDate: 0,
				//dateFormat: 'dd-mm-yy'
				
				changeMonth: false,
				changeYear: true,
				showButtonPanel: true,
				yearRange: '1950:2013', // Optional Year Range
				dateFormat: 'yy',
            });
			
			$("#prediction_date111").datepicker({
				
				/*beforeShow: function(input, inst) {
				   $('#ui-datepicker-div').removeClass(function() {
					   return $('input').get(0).id; 
				   });
				   $('#ui-datepicker-div').addClass(this.id);
			   },*/
   
				changeMonth: false,
				changeYear: true,
				showButtonPanel: true,
				//yearRange: '1950:2013', // Optional Year Range
				dateFormat: 'yy',
				onClose: function(dateText, inst) { 
				var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
				$(this).datepicker('setDate', new Date(year, 0, 1));
			}});
		
		
			 $(".ui-icon").addClass("fa fa-chevron-right");
             $(".ui-icon").addClass("fa fa-chevron-left");
        });	
		var jqis = $.noConflict();
		call_infinite_scroll();
		function call_mansory() {
	
	var $container = jqis('.popup_images');
	
		$container.imagesLoaded(function(){
				  $container.masonry({
					itemSelector: '#image_lists_images_id',
					columnWidth: 1
				  });
				}); 
				
}

function call_infinite_scroll() {
	
	 var $container = jqis('.popup_images');
		
	 $container.infinitescroll({
      navSelector  : '#page-nav',    // selector for the paged navigation 
      nextSelector : '#page-nav a',  // selector for the NEXT link (to page 2)
      itemSelector : '#image_lists_images_id',
	   binder :  $container ,
	  debug : true,
		  // selector for all items you'll retrieve
      loading: {
		  
          finishedMsg: 'No more images to load.',
          img: '<?php echo base_url(); ?>images/admin/loadingimage.gif',
		  msgText: "<em>Loading the next set of images...</em>"
        },
		state: { isDone:false }
      },
      // trigger Masonry as a callback
      function( newElements ) {
        // hide new items while they are loading
        var $newElems = jqis( newElements ).css({ opacity: 0 });
        // ensure that images load before adding to masonry layout
        $newElems.imagesLoaded(function(){
          // show elems now they're ready
          $newElems.animate({ opacity: 1 });
		  console.log("container add");
			$container.masonry( 'appended', $newElems, true );	
        });
      }
    );
    
}
		
		jqis(".set_image, .EmbedImage").click(function(){
		
 var $container = jqis('.popup_images');
	
	if(jqis.trim($container.html()) == '') {
		
	$container.html('<div id="LoadingSelectImageLocal" style="display: block;"><img src="<?php echo base_url(); ?>images/admin/loadingimage.gif" style="border:none; width:23px; height:23px;text-align: center;float: none;margin: 0px;padding: 0px;"><br>Loading ...</div>');
	
			$.ajax({
			url: base_url+folder_name+"/astro_image/get_image_library_scroll/1", // Url to which the request is send
			type: "POST",             // Type of request to be send, called as method
			dataType: "HTML",
			success: function(data)   // A function to be called if request succeeds
			{
				
				$container.html(data);
				setTimeout(function(){
				//call_mansory();
				},1000);
			}
		});
		
	} else {
		//console.log("test");
		//call_mansory();
	}
});


var show_clear_link =  jqis("#search_caption").val();
if(show_clear_link!=''){
jqis("#clear_search").show();
}
	jqis("#clear_search").click(function() {
		jqis("#search_caption").val('');
 var $container = jqis('.popup_images');
		 $container.empty();
	if(jqis.trim($container.html()) == '') {
		
	$container.html('<div id="LoadingSelectImageLocal" style="display: block;"><img src="<?php echo base_url(); ?>images/admin/loadingimage.gif" style="border:none; width:23px; height:23px;text-align: center;float: none;margin: 0px;padding: 0px;"><br>Loading ...</div>');
	
			$.ajax({
			url: base_url+folder_name+"/astro_image/get_image_library_scroll/1", // Url to which the request is send
			type: "POST",             // Type of request to be send, called as method
			dataType: "HTML",
			success: function(data)   // A function to be called if request succeeds
			{
				
				$container.html(data);
				jqis("#clear_search").hide();
				setTimeout(function(){
				//call_mansory();
				},1000);
			}
		});
		
	} else {
		//console.log("test");
		//call_mansory();
	}
});
	
	function Image_Search() {
		
		 var $container = jqis('.popup_images');
		 $container.empty();
	if(jqis.trim($container.html()) == '') {
	$container.html('<div id="LoadingSelectImageLocal" style="display: block;"><img src="<?php echo base_url(); ?>images/admin/loadingimage.gif" style="border:none; width:23px; height:23px;text-align: center;float: none;margin: 0px;padding: 0px;"><br>Loading ...</div>');
		}	
			var Caption = jqis("#search_caption").val();
			var Section	= jqis("#image_section_search").val();
			
			postdata = "Caption="+Caption+"&Section="+Section;
			jqis.ajax({
				url: base_url+folder_name+"/astro_image/search_image_library",
				type: "POST",
				data: postdata,
				dataType: "json",
				success: function(data){
					var Content = '';
					var Count 	= 0;
					var Image_URL = "<?php echo image_url.imagelibrary_image_path
					;?>";
					
					jqis.each(data, function(i, item) {
						var active_class = "";
						if(Count == 0) {
								
							jqis("#textarea_alt").text(item.ImageAlt);
							jqis("#textarea_caption").text(item.Title);
							jqis("#image_name").html(item.Title);
							jqis("#height_width").html(item.Height+" X "+item.Width);
							jqis("#image_size").html(item.image_size+" Kb");
							jqis("#image_date").html(item.Modifiedon);
							jqis("#image_path").attr('src',Image_URL+item.ImagePhysicalPath);
							jqis("#browse_image_id").val(item.content_id);
							
							jqis("#browse_image_id").val(jqis(this).attr('rel'));
							
							jqis("#browse_image_id").data("image_source",Image_URL+item.ImagePhysicalPath);
							jqis("#browse_image_id").data("content_id",item.content_id);
							jqis("#browse_image_id").data("image_alt",item.ImageAlt);
							jqis("#browse_image_id").data("image_caption",item.Title);
							jqis("#browse_image_id").data("image_size",item.image_size);
							jqis("#browse_image_id").data("image_date",item.Modifiedon);
							jqis("#browse_image_id").data("image_width",item.Width);
							jqis("#browse_image_id").data("image_height",item.Height);
							jqis("#browse_image_id").data("image_path",Image_URL+item.ImagePhysicalPath);
							
							active_class = 'active';		
						}
						
						
						
					Content +='<img id="image_lists_images_id" data-content_id="'+item.content_id+'" data-image_height="'+item.Height+'" data-image_width="'+item.Width+'" data-image_caption="'+item.Title+'" data-image_alt="'+item.ImageAlt+'" data-image_size="'+item.image_size+'" data-image_date="'+item.Modifiedon+'" data-image_source="'+Image_URL+item.ImagePhysicalPath+'"  src="'+Image_URL+item.ImagePhysicalPath+'" />';
						Count++;
					});
					if(Content != "") {
					jqis('.popup_images').html(Content);
					} else {
					jqis("#image_lists_id").html("No Data");
					}
					
					jqis('.popup_images').masonry('reload');
					jqis('.popup_images').infinitescroll('destroy'); // Destroy
					
					// Undestroy
					jqis('.popup_images').infinitescroll({ 				
						state: {                                              
								isDestroyed: false,
								isDone: false                           
						}
					});
					console.log("destory");	
					jqis('.popup_images').infinitescroll('bind');
					jqis('.popup_images').infinitescroll('retrieve');
			        jqis("#clear_search").show(); 
				}
			});
		
		
	}
	
	
	jqis("#image_search_id").click(function() {
		Image_Search();
	});
	
	jqis("#search_caption").keyup(function(e){
	    if(e.keyCode == 13){
			Image_Search();
		  }
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
			
			
			var postdata = {"body_text" :body_text,"date": date,"rasi_name":raasi_name,"menu_name":'<?php echo 'Astrology Yearly Prediction'; ?>'};
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
    </script>