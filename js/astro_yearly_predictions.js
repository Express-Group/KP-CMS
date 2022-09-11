$(document).ready(function()
{
	
	$("#yearlyPredictions_form").validate(
	{
		ignore:[],
		debug: false,
		rules:
		{
			txtPredictionDetails:
			{
			  required: function() { CKEDITOR.instances.txtPredictionDetails.updateElement();}
			},
			raasi_name: { required: true},
			prediction_date: { required: true },	
		},
		messages: 
		{
			txtPredictionDetails: { required: "Please enter General Predictions",},
			raasi_name: { required: "Please select Raasi Name",},
			prediction_date: { required: "Please enter date" },
		},
		errorPlacement: function (error, element)
		{
			if(element.attr("name") == 'txtPredictionDetails')
			{ 
				error.insertAfter($("#predictiondetails_error"));
			}			
			else if(element.attr("name") == 'prediction_date')
			{ 
				error.insertAfter($("#date_error"));
			}
			else
			{
				error.insertAfter($("#"+element.attr("name")));
			}
		}
	});
	$("#btnYearlyPrediction").click(function() 
	{
		if($("#yearlyPredictions_form").valid())
		{
			var raasi_id = ($("#raasi_name").val() !='') ? $("#raasi_name").val() : "";
			var prediction_id = ($("#hidden_id").val() !='') ? $("#hidden_id").val() : "";
			var date = $("#prediction_date").val();
			$.ajax({
				type: "POST",
				data: {"raasi_id":raasi_id, "schd_date":date, "prediction_id":prediction_id},
				url: base_url+folder_name+"/astro_yearly_prediction/check_alreadyExists",
				success: function(data)
				{
					//alert(data);
					if(data == "exists")
					{
						$("#already_error").html("Astrology-Yearly Predictions already exists for this Year!");
						return false;
					}
					else
					{
						$("#already_error").html("");
						$("#date_error").html("");
						
						var confirm_msg = $("#prediction_id").val();
						if(confirm_msg !="")
							var confirm_status = confirm("Are you sure you want to update the Astrology-Yearly Predictions?");
						else
							var confirm_status = confirm("Are you sure you want to add the Astrology-Yearly Predictions?");
						if(confirm_status==true)
						{
							$("#yearlyPredictions_form").submit();
						}
						
					}
				}
			});
			
			
			
		}
	});	
	
	
	$(document).on('close', '#preview_article_popup', function () {  
	
		$("head").append($('<link rel="stylesheet" href="'+base_url+'css/admin/dashboard-style.css" type="text/css">'));   
		$(".css_and_js_files").append($('<link rel="stylesheet" href="'+base_url+'includes/ckeditor/contents.css" type="text/css">'));  
		
		$('link[rel=stylesheet][href~="'+base_url+'css/FrontEnd/css/style.css"]').remove();
		$('link[rel=stylesheet][href~="'+base_url+'css/FrontEnd/css/easy-responsive-tabs.css"]').remove();
		$("script[src='"+base_url+"js/FrontEnd/js/easyResponsiveTabs.js']").remove();
		$("script[src='"+base_url+"js/FrontEnd/js/jquery-ui.js']").remove();
 
	}); 
	
	$("#drop-area").on('dragenter', function (e){
	e.preventDefault();
	$(this).css('background', '#BBD5B8');
	});

	$("#drop-area").on('dragover', function (e){
	e.preventDefault();
	});

	$("#drop-area").on('drop', function (e){
		$("#LoadingSelectImageLocal").show();
		$(this).css('background', '#D8F9D3');
		e.preventDefault();
		var image = e.originalEvent.dataTransfer.files;
			setTimeout(function(){
				createFormData(image);
			},1000);
	});
	
	$("#imagelibrary").change(function() {
		$("#LoadingSelectImageLocal").show();
		var ext = $('#imagelibrary').val().split('.').pop().toLowerCase();
		if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1) {
			alert('Invalid Extension!');
		} else {
			
			var formImage = new FormData();
			formImage.append('imagelibrary',document.getElementById("imagelibrary").files[0]);
			formImage.append('popuptype',$("#current_image_popup").val());
			
			setTimeout(function(){
			uploadFormData(formImage);
			},1000);
		}
	});
	
	
	$(document.body).on('click', '#image_lists_images_id', function(event) {
		
		var ImageDetails = $(this).data();
		
			$("#textarea_alt").text(ImageDetails.image_alt);
			$("#textarea_caption").text(ImageDetails.image_caption);
			$("#image_name").html(ImageDetails.image_caption);
			$("#height_width").html(ImageDetails.image_height+" X "+ImageDetails.image_width);
			$("#image_size").html(ImageDetails.image_size+" Kb");
			$("#image_date").html(ImageDetails.image_date);
			$("#image_path").attr('src',ImageDetails.image_source);
			
			$("#browse_image_id").val(ImageDetails.content_id);
			console.log(ImageDetails.content_id);
			
			$("#browse_image_id").data("image_source",ImageDetails.image_source);
			$("#browse_image_id").data("content_id",ImageDetails.content_id);
			$("#browse_image_id").data("image_alt",ImageDetails.image_alt);
			$("#browse_image_id").data("image_caption",ImageDetails.image_caption);
			$("#browse_image_id").data("image_size",ImageDetails.image_size);
			$("#browse_image_id").data("image_date",ImageDetails.image_date);
			$("#browse_image_id").data("image_width",ImageDetails.image_width);
			$("#browse_image_id").data("image_height",ImageDetails.image_height);
			$("#browse_image_id").data("image_path",ImageDetails.image_path);
			console.log($("#browse_image_id").data());
			$("#image_lists_id img").removeClass('active');
	 		$(this).addClass('active')
			
	});
	
	$(document.body).on('click',"#browse_image_insert",function() {
		
		$("#LoadingSelectImageLibrary").show();
		var popuptype	 = $("#current_image_popup").val();
		if(popuptype == 'bodytext') {
			
			if($("#browse_image_id").val() != '' && $("#browse_image_id").val() != 0 ) 
			{		
				ImageData = "content_id="+$("#browse_image_id").val()+"&type=1";		
				$.ajax({
					url		: base_url+folder_name+"/common/get_image_by_content_ajax",
					type	: "POST",
					data	: ImageData,
					dataType: "HTML",
					async	: false, 	
					success	: function(data) {		
						CKEDITOR.instances.txtPredictionDetails.insertHtml('<img src="'+data+'" />');
						$("#LoadingSelectImageLibrary").hide(); 
					}
				});
			}
		} 
		else 
		{		
			if($("#browse_image_id").val() != '' && $("#browse_image_id").val() != 0 ) 
			{
				if($("#browse_image_id").data('image_source')) 
				{
					var ImageDetails = $("#browse_image_id").data();	
					ImageData = "alt="+ImageDetails.image_alt+"&caption="+ImageDetails.image_caption+"&date="+ImageDetails.image_date+"&height="+ImageDetails.image_height+"&width="+ImageDetails.image_width+"&size="+ImageDetails.image_size+"&path="+ImageDetails.image_path+"&content_id="+ImageDetails.content_id+"&popuptype="+popuptype;		
					$.ajax({
						url		: base_url+folder_name+"/astro_image/Insert_temp_from_image_library",
						type	: "POST",
						data	: ImageData,
						dataType: "json",
						async	: false, 	
						success	: function(data) {
							console.log(data);						
							$('#section_image_gallery_id').val(data.image_id);
							$('#section_image_gallery_id').attr('rel',data.imagecontent_id);
							$("#section_uploaded_image").html('Image Set');
							$("#section_image_src").attr('src',$("#image_path").attr('src'));
							$('#section_image_container').css("visibility", "visible");
							$("#section_image_set").next().show();
							$("#section_image_set").next().next().show();
							$("#section_image_set").html('Change Image');
							$("#section_image_set").removeClass('BorderRadius3');
							$("#section_image_caption").val(data.caption);
							var physical_name = data.physical_name;
							physical_name = physical_name.replace(/[^a-zA-Z0-9_-]/g,'');						
							$("#section_physical_name").val(physical_name);
							$("#section_physical_name").attr('physical_extension',data.physical_extension);
							$("#section_image_alt").val(data.alt);						
							CheckImageContainer();						
							$("#LoadingSelectImageLibrary").hide(); 
						}
					}); 
				}
			}
		}
	});
	
	
});

function ChangePopup(popup_name) {
	$("#current_image_popup").val(popup_name);
}

function articleUpload() {
	$('.article_upload').css({"display" : "block"});
	$('.article_browse').css({"display" : "none"});
	$('.img_upload').addClass('active');
	$('.img_browse').removeClass('active');
}
function articleBrowse() {
	$('.article_upload').css({"display" : "none"});
	$('.article_browse').css({"display" : "block"});
	$('.img_browse').addClass('active');
	$('.img_upload').removeClass('active');
}

function createFormData(image) {

	var formImage = new FormData();
	$("#LoadingSelectImageLocal").show();
	formImage.append('imagelibrary', image[0]);
	formImage.append('popuptype',$("#current_image_popup").val());
	uploadFormData(formImage);
}

function uploadFormData(formData) {

	$.ajax({
		url: base_url+folder_name+"/astro_image/image_upload",
		type: "POST",
		data: formData,
		contentType:false,
		cache: false,
		processData: false,
		dataType: "json",
		success: function(data){		
			CKEDITOR.instances.txtPredictionDetails.insertHtml('<img src="'+data.image+'" />');
			CheckImageContainer();
			$("#LoadingSelectImageLocal").hide();
			var inst = $.remodal.lookup[$('[data-remodal-id=modal1]').data('remodal')];
			if(!inst) {
				$('[data-remodal-id=modal1]').remodal().close();
			 } else{
				  inst.close();
			  }
			$("#imagelibrary").val('');
			
		}
	});
	
}

function CheckImageContainer() {
	if(	$("#section_image_gallery_id").val() == '' && $("#article_image_gallery_id").val() == '' && $("#home_image_gallery_id").val() == '') {
		$("#ArticleImageContainerId").hide();
	} else {
		$("#ArticleImageContainerId").show();
	}
}
