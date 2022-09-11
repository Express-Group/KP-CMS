<!DOCTYPE html>
<html lang="en">
<head>
  <title><?php echo $title; ?></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="<?php echo image_url ?>images/FrontEnd/images/favicon.ico" type="image/x-icon" />
  <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
   <link rel="stylesheet" type="text/css" href="<?php echo image_url ?>includes/ckeditor/skins/moono/editor.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="<?php echo image_url ?>includes/ckeditor/ckeditor.js"></script>
  <link href="<?php echo image_url ?>css/admin/toastr.css" rel="stylesheet" type="text/css" />
<script src="<?php echo image_url ?>js/toastr.js"></script>


  <style>
	body,html{font-family: 'Roboto', sans-serif;background:#eee;}
	.cke_editor_add_content{width:100% !important;}
	.flash-title{background: #fff;margin: 0;padding: 10px;margin-bottom: 2%;    text-transform: uppercase;font-weight: bold;}
	.flash-content{width: 100%;float: left;padding: 10px;border-radius: 6px;
    box-shadow: 1px 1px 1px #9c9696;background:#fff;margin-bottom: 2%;}
	.flashnews_content{overflow-y:scroll; height: 550px;}
	#save,#edit_save,#publish,#reset{width:auto !important;color:#fff !important;}
  </style>
</head>
<body>
	<div class="container-fluid">
		<div class="row">
			<h3 class="text-center flash-title"><span id="goback" style="float: left;font-size: 16px;color: blue;cursor:pointer;">Go Back</span>Flash News</h3>
		</div>
		<div class="row">
			<div class="col-md-6 col-lg-6" style="border-right: 2px solid #e3e4e3;">
				<div class="form-group" style="width:100%;">
					<label for="flash news">Enter Flash News: </label>
					<textarea class="ckeditor" id="add_content" class="form-controll"></textarea>
				</div>
				<div class="form-group pull-right" style="width:100%;text-align:right;">
					<input type="button" value="save" id="save" class="btn btn-success">
					<input type="button" content="" value="save" id="edit_save" class="btn btn-success" style="display:none;">
					<input type="button" value="reset" id="reset" class="btn btn-success">
					<input type="button" value="publish" id="publish" class="btn btn-success">
				</div>
			</div>
			<div class="col-md-6 col-lg-6 flashnews_content">
				<?php
				if($contents!='EMPTY'):
					$contents = $contents['response'];
					for($i=0;$i<count($contents);$i++):
						$randomid = rand(10000,1000000);
						echo '<div class="flash-content">';
						echo '<div class="content" contentid="'.$i.'" style="width:100%;float:left;">';
						echo $contents[$i];
						echo '</div>';
						echo '<div style="width:100%;float:left;text-align:right;padding-top: 1%;border-top: 1px solid #dedbdb;">';
						echo '<a class="edit" style="padding-right: 8px;color:blue;cursor:pointer;text-decoration:none;"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
						echo '<a  class="delete" style="color:blue;cursor:pointer;text-decoration:none;"><i class="fa fa-trash-o" aria-hidden="true"></i></a>';
						echo '</div></div>';
					endfor;
				endif;
				?>
			</div>
		</div>
	</div>
<script>
var image_url = "<?php echo image_url ?>";
CKEDITOR.replace( 'add_content', {
	toolbar : [ 
		{items: [ 'TextColor','BGColor','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','Bold','Italic','Underline','Embed','Html5audio'] },
			
		{items: ['Cut', 'Copy','Paste','PasteText','PasteFromWord','Undo','Redo','Find','Replace']},
		{items : [ 'Format']},
		{items: ['Link','Unlink','Image']},
		 {items: [ 'Source','Strike','Subscript','Superscript','NumberedList','BulletedList','Outdent','Indent','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe','FontAwesome'] }
	],  
	extraPlugins: 'autogrow,html5audio,embed,colordialog,fontAwesome', 
	contentsCss : image_url+'includes/ckeditor/plugins/fontAwesome/css/font-awesome.min.css',
	allowedContent : true,
	removePlugins : 'magicline',
	extraAllowedContent : 'audio(*){*}[*];img(*){*}[*];object(*){*}[*];embed(*){*}[*];param(*){*}[*];script(*){*}[*];blockquote(*){*}[*];p(*){*}[*]'
});

$(document).ready(function(){
	$('#save').on('click',function(e){
		var content = CKEDITOR.instances.add_content.getData();
		var contenttext = $(content).text().trim();
		if(content!='' && contenttext!='' && contenttext!=undefined && content!=undefined){
			var template ='';
			template +='<div class="flash-content">';
			template +='<div class="content" contentid="'+$('.content').length+'" style="width:100%;float:left;">';
			template +=content;
			template +='</div>';
			template +='<div style="width:100%;float:left;text-align:right;padding-top: 1%;    border-top: 1px solid #dedbdb;">';
			template +='<a class="edit" style="padding-right: 8px;color:blue;cursor:pointer;text-decoration:none;"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
			template +='<a class="delete"  style="color:blue;cursor:pointer;text-decoration:none;"><i class="fa fa-trash-o" aria-hidden="true"></i></a>';
			template +='</div>';
			template +='</div>';
			$('.flashnews_content').append(template);
			
		}else{
			toastr.error('Please Enter Valid flash news');
		}
	
	});
	$('#reset').on('click',function(e){
		CKEDITOR.instances.add_content.setData('');
	});
	$('#publish').on('click',function(e){
		var content_count = $('.content').length;
		if(content_count!=0){
			var data =[];
			$('.content').each(function(index){
				data.push($(this).html());
			});
			$.ajax({
				type:'post',
				cache:false,
				url:'<?php echo HOMEURL.folder_name ?>/flash_news/insertnews',
				data:{'data':data},
				success:function(result){
					if(result==1){
						toastr.success("Flash news published successfully");
					}else{
						toastr.error("Error:Invalid content");
					}
				},
				error:function(err,errcode){
					toastr.error('Something wentwrong..please try again');
				}
			});
		}else{
			toastr.error('Please Enter Valid flash news');
		}
	});
	
	$(document).on('click','.delete',function(e){
		e.preventDefault();
		var conf = confirm("Are you sure want to delete?");
		if(conf==true){
			$(this).parents('.flash-content').remove();
			$('.content').each(function(index){
				$(this).attr('contentid',index);
			});
		}
	});
	$(document).on('click','.edit',function(e){
		e.preventDefault();
		var contentid = $(this).parents('.flash-content').find('.content').attr('contentid');
		var content = $(this).parents('.flash-content').find('.content').html();
		CKEDITOR.instances.add_content.setData(content);
		$('#edit_save').attr('content',contentid).show();
		$('#save').hide();
	});
	$('#edit_save').on('click',function(){
		var contentid = $(this).attr('content');
		var content = CKEDITOR.instances.add_content.getData();
		$('.content').each(function(index){
			var conid = $(this).attr('contentid');
			if(conid == contentid){
				$(this).html(content);
				CKEDITOR.instances.add_content.setData('');
				$('#edit_save').attr('content','').hide();
				$('#save').show();
				toastr.success("flash news updated successfully");
				return false;
			}
		});
		
		
		
		
	});
	$('#goback').on('click',function(){
		window.location.href= "<?php echo HOMEURL.folder_name ?>";
	})
});
</script>
</body>
</html>
 