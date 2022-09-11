<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <style>
	th{background-color:#84c7ea;text-align:center;}
	.table{margin-top:7%;}
	.modal{top:75px;}
	.form-group{width:100%;}
	.form-group input[type="text"]{border:none;border-radius:0;border-bottom:2px solid #3c8dbc;width:100%;}
	.form-group input[type="text"]:hover,.form-group input[type="text"]:active,.form-group input[type="text"]:focus{border-bottom:2px solid #4CAF50;}
	.success{background-color:#009688 !important;}
	.success:hover,.success:active,.success:focus{background-color:#0d9689 !important;}
	.error{background-color:#F44336 !important;}
	.error:hover,.error:active,.error:focus{background-color:#e42315  !important;}
	.error_message{width:100%;float:left;color:#e42315;}
	.table tr:nth-of-type(even){background-color: #dff5ff}
	.table tr:nth-of-type(even):hover{background-color: #cce9f6}
	.BodyHeadBg{position:relative;top: 34px;}
	label{font-weight: 700 !important;font-size: 17px;}
	.modal-content{border-radius:0px;}
	.modal-title{text-align: center;color: #3c8dbc;font-weight: 700 !important;}
	.btn-primary , .btn-warning{border-radius:0px !important;}
	.delete_title{margin:0;text-align:center;color:red;}
	table tr td p img{width:auto;}
	#add_news .modal-dialog { width: 960px;}
	#ddsection{ height:305px; }
	.section{ float: left;margin: 0px 28px 0px 22px; }
  </style>
<div class="Container">
	<div class="BodyHeadBg Overflow clear">
		<div class="FloatLeft  BreadCrumbsWrapper PollResult">
			<div class="breadcrumbs">Dashboard > Scrolling News Manager</div>
			<h2>Scrolling News Manager</h2>
		</div>
		<p class="FloatRight SaveBackTop article_save">
			<a href="javascript:add_scroll_news();" class="btn-primary btn"><i class="fa fa-file-text-o"></i> &nbsp;Add Scrolling News</a>
		</p>
		<p class="FloatRight SaveBackTop article_save" style="margin-right:1%;">
			<a style="padding:10px;" href="javascript:embed_section();" class="btn-warning btn"><i class="fa fa-link"></i> &nbsp;Embed Section Hightlights</a>
		</p>
		<p class="FloatRight SaveBackTop article_save" style="width: 220px;">
			<select name="section_list" class="form-control" style="border: 1px solid #ddd !important;padding: 10px!Important;    border-top-right-radius: 0;border-bottom-right-radius: 0;height: 40px;">
				<option value="">Select any one</option>
				<?php 
					if(isset($section_mapping)) { 
					foreach($section_mapping as $mapping) {   
						?>
						<option style="color:#933;font-size:18px;" value="<?php echo $mapping['Section_id']; ?>"><?php echo strip_tags($mapping['Sectionname']); ?></option>
						<?php if(!(empty($mapping['sub_section'])) ) { ?>
						<?php foreach($mapping['sub_section'] as $sub_mapping) { ?>
								<option value="<?php echo $sub_mapping['Section_id']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo strip_tags($sub_mapping['Sectionname']); ?></option>
									<?php if(!(empty($sub_mapping['sub_sub_section']))) { ?>
									<?php foreach($sub_mapping['sub_sub_section'] as $sub_sub_mapping) { ?>
									<option value="<?php echo $sub_sub_mapping['Section_id']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo strip_tags($sub_sub_mapping['Sectionname']); ?></option>
									<?php } } ?>
						<?php  } } ?>
					<?php  } } ?>
			</select>
		</p>
		<p class="FloatRight SaveBackTop article_save" style="margin-right:1%;">
			<a style="padding:10px;" href="javascript:search_section();" class="btn-primary btn">Search</a>
		</p>
		<p class="FloatRight SaveBackTop article_save text-center" style="width: 220px;">
			<select name="section_list1" class="form-control" style="border: 1px solid #ddd !important;padding: 10px!Important;    border-top-right-radius: 0;border-bottom-right-radius: 0;height: 40px;">
				<option value="">Select any one</option>
				<?php 
					if(isset($section_mapping)) { 
					foreach($section_mapping as $mapping) {   
						?>
						<option <?php if(@$_GET['id']==$mapping['Section_id']){ echo 'selected' ;} ?> style="color:#933;font-size:18px;" value="<?php echo $mapping['Section_id']; ?>"><?php echo strip_tags($mapping['Sectionname']); ?></option>
						<?php if(!(empty($mapping['sub_section'])) ) { ?>
						<?php foreach($mapping['sub_section'] as $sub_mapping) { ?>
								<option <?php if(@$_GET['id']==$sub_mapping['Section_id']){ echo 'selected' ;} ?> value="<?php echo $sub_mapping['Section_id']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo strip_tags($sub_mapping['Sectionname']); ?></option>
									<?php if(!(empty($sub_mapping['sub_sub_section']))) { ?>
									<?php foreach($sub_mapping['sub_sub_section'] as $sub_sub_mapping) { ?>
									<option <?php if(@$_GET['id']==$sub_sub_mapping['Section_id']){ echo 'selected' ;} ?> value="<?php echo $sub_sub_mapping['Section_id']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo strip_tags($sub_sub_mapping['Sectionname']); ?></option>
									<?php } } ?>
						<?php  } } ?>
					<?php  } } ?>
			</select>
			<a href="<?php echo base_url(folder_name.'/scrolling_news') ?>">Clear search</a>
		</p>
		<textarea style="display:none;"  id="frames"></textarea>
		<table class="table table-bordered" id="table-jquery">
			<tr>
				<th>ID</th>
				<th>Content</th>
				<th>Edit</th>
				<th>Embed</th>
				<th>Delete</th>
			</tr>
			<?php
			$i=1;
			foreach($content as $data){
			print '<tr class="scroll_'.$data->sid.' text-center">';
			print '<td>'.$i.'</td>';
			print '<td class="content_'.$data->sid.'">'.$data->content.'</td>';
			print '<td><button class="btn btn-primary" onclick="edit_news('.$data->sid.');" data-toggle="tooltip" data-placement="right" title="edit"><i class="fa fa-pencil" aria-hidden="true"></i></button></td>';
			print '<td><button class="btn btn-warning" onclick="embed('.$data->sid.');" data-toggle="tooltip" data-placement="top" title="Embed"><i class="fa fa-link" aria-hidden="true"></i></button></td>';
			print '<td><button class="btn btn-primary error" onclick="delete_news('.$data->sid.');" data-toggle="tooltip" data-placement="right" title="delete"><i class="fa fa-trash-o" aria-hidden="true"></i></button></td>';
			print '</tr>';
			$i++;
			}
			?>
		</table>
	</div>
		<div class="modal fade" id="add_news" role="dialog">
			<div class="modal-dialog">
			  <div class="modal-content">
				<div class="modal-header">
				  <h4 class="modal-title">Add Scrolling News</h4>
				</div>
				<div class="modal-body">
					<div class="section">
						<div class="form-group">
							<label style="display:block;">Section : </label>
							<select id="ddsection" class="controls" multiple>
									<?php if(isset($section_mapping)) { 
									foreach($section_mapping as $mapping) {   
									?>
								<option id="MainSectionOption" style="color:#933;font-size:18px;" class="blog_option"  value="<?php echo $mapping['Section_id']; ?>" section_name="<?php echo strip_tags($mapping['Sectionname']); ?>" ><?php echo strip_tags($mapping['Sectionname']); ?></option>
								<?php if(!(empty($mapping['sub_section'])) ) { ?>
								<?php foreach($mapping['sub_section'] as $sub_mapping) { ?>
								<option  id="MainSectionOption"  value="<?php echo $sub_mapping['Section_id']; ?>" section_name="<?php echo strip_tags($sub_mapping['Sectionname']); ?>" >&nbsp;&nbsp;&nbsp;&nbsp;<?php echo strip_tags($sub_mapping['Sectionname']); ?></option>
								<?php if(!(empty($sub_mapping['sub_sub_section']))) { ?>
								<?php foreach($sub_mapping['sub_sub_section'] as $sub_sub_mapping) { ?>
								<option id="MainSectionOption"  value="<?php echo $sub_sub_mapping['Section_id']; ?>"   section_name="<?php echo strip_tags($sub_sub_mapping['Sectionname']); ?>" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo strip_tags($sub_sub_mapping['Sectionname']); ?></option>
								<?php } } ?>
								<?php  } } ?>
								<?php  } } ?>
							</select>
						</div>
					</div>
					<div class="pull-left">
						<div class="form-group">
							<label>Enter Scrolling News:</label>
							<!--<input type="text" name="news" id="news" class="">-->
							<textarea class="ckeditor" id="scroll_text"  name="scroll_text"></textarea>
							<span class="error_message" id="news_error"></span>
							<input type="hidden" id="edit_new_id" value="">
						</div>
					</div>
				</div>
				<div class="modal-footer">
				  <button type="button" class="btn btn-primary success main-btn" onclick="save_news();"><i class="fa fa-floppy-o" aria-hidden="true"></i></button>
				  <button type="button" class="btn btn-primary error" data-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true"></i></button>
				</div>
			  </div>
			</div>
	  </div>
	  <div class="modal fade" id="delete_news" role="dialog">
			<div class="modal-dialog modal-sm">
			  <div class="modal-content">
				<div class="modal-header">
				  <h4 class="modal-title">Are you sure want to delete?</h4>
				  <p class="delete_title"></p>
				</div>
				<div class="modal-body">
					<div class="form-group" style="text-align:center;">
						<button type="button" class="btn btn-primary success main-btn" id="delete-news-modal" ><i class="fa fa-check-circle" aria-hidden="true" ></i> Ok</button>
						<button type="button" class="btn btn-primary error" data-dismiss="modal"><i class="fa fa-times-circle-o" aria-hidden="true"></i> Cancel</button>
					</div>
				</div>
			  </div>
			</div>
	  </div>
</div>
<script type="text/javascript" src="<?php echo image_url ?>includes/ckeditor/ckeditor.js?ver=1.1"></script>
<script>
 $('[data-toggle="tooltip"]').tooltip(); 
function add_scroll_news(){
	CKEDITOR.instances['scroll_text'].setData('');
	$('.main-btn').attr('onclick','save_news()');
	$('#news').val('');
	$("#add_news").modal({backdrop: 'static', keyboard: false});
	$('.modal-title').html('Add Scrolling News');
	$('input[type="text"]').focus();
}

function save_news(){
	var news=CKEDITOR.instances.scroll_text.getData();
	var section_name='';
	var section_id ='';
	var status =1;
	$('#ddsection option:selected').each(function(){
		if(section_name !=''){
			section_name += ',' + $(this).attr('section_name') ;
		}else{
			section_name += $(this).attr('section_name') ;
		}
		 
		 if(section_id !=''){
			 section_id += ',' + $(this).attr('value') ;
		 }else{
		 section_id += $(this).attr('value') ; }
	});
	if($(news).text().trim() ==''){
		toastr["error"]("Please Enter News");
		status = 0;
	}
	if(section_id == ''){
		toastr["error"]("Please Select Any Section");
		status = 0;
	}
	if(status == 1){
		var ajax=$.ajax({
			type:'post',
			cache:false,
			url:'<?php print base_url().folder_name ?>/scrolling_news/save_news',
			data:{'news':news, 'section_id':section_id , 'section_name' :section_name}
			});
		ajax.success(function(result){
			if(result==1){
				toastr["success"]("Scrolling News Created Successfully");
				CKEDITOR.instances['scroll_text'].setData('');
				$("#add_news").modal("hide");
				location.reload();
			}else{
				toastr["error"]("Something went wrong..try again");
			}
		});
	}
}

function edit_news(sid){
	$.ajax({
		type: "POST",
		cache:false,
		url: "<?php echo base_url().folder_name ?>/scrolling_news/fetch_edit_news/",
		data:{'sid':sid},
		dataType:'json',
		success: function(result){
			if(result.id!=''){
				var section_id_split = result.id.split(',');
				for(var i=0; i <= section_id_split.length;i++){
					$("#ddsection option[value=" + section_id_split[i] +"]").prop("selected",true);
				}
			}else{
				$("#ddsection option:selected").removeAttr("selected");
			}
			CKEDITOR.instances['scroll_text'].setData(result.content);
			$('#edit_new_id').val(sid);
			$('.main-btn').attr('onclick','save_edit_news()');
			$("#add_news").modal({backdrop: 'static', keyboard: false});
			$('.modal-title').html('Edit Scrolling News');
		}
	});
}

function save_edit_news(){
	var sid=$('#edit_new_id').val();
	var news=CKEDITOR.instances.scroll_text.getData();
	var section_name='';
	var section_id ='';
	var status =1;
	$('#ddsection option:selected').each(function(){
		if(section_name !=''){
			section_name += ',' + $(this).attr('section_name') ;
		}else{
			section_name += $(this).attr('section_name') ;
		}
		 
		 if(section_id !=''){
			 section_id += ',' + $(this).attr('value') ;
		 }else{
		 section_id += $(this).attr('value') ; }
	});
	if($(news).text().trim() ==''){
		toastr["error"]("Please Enter News");
		status = 0;
	}
	if(section_id == ''){
		toastr["error"]("Please Select Any Section");
		status = 0;
	}
	
	if(status==1){
		var ajax=$.ajax({
			type:'post',
			cache:false,
			url:'<?php print base_url().folder_name?>/scrolling_news/save_edit_news',
			data:{'news':news,'sid':sid, 'section_id':section_id , 'section_name' :section_name}
		});
		ajax.success(function(result){
			if(result==1){
				$('.content_'+sid).html(news);
				toastr["success"]("Scrolling News updated Successfully");
				$('#news').val('');
				$("#add_news").modal("hide");
			}else{
				toastr["error"]("Something went wrong..try again");
			}
		});
	}
}


function delete_news(sid){
$('.modal-title').html('Are you sure want to delete?');
$("#delete_news").modal({backdrop: 'static', keyboard: false});
$('#delete-news-modal').attr('onclick','del_news('+sid+')');
$('.delete_title').html($('.content_'+sid).text());
}

function del_news(sid){
	var ajax=$.ajax({
		type:'post',
		cache:false,
		url:'<?php print base_url()?>niecpan/scrolling_news/delete_news/',
		data:{'sid':sid}
		});
		
		ajax.success(function(result){
		if(result==1){
			$("#delete_news").modal("hide");
			toastr["success"]("Scrolling News deleted Successfully");
				$('.scroll_'+sid).hide(1000);
		}else{
			toastr["error"]("Something went wrong..try again");
			$("#delete_news").modal("hide");
		}
		});

}
function embed($hid){
	toastr.remove();
	var template = '';
	template +='<iframe style="width:100%;" frameborder="0" scrolling="no" src="<?php echo BASEURL ?>embed/highlight/'+btoa($hid)+'"></iframe>';
	$('#frames').html(template).show();
	$('#frames').select();
	document.execCommand("copy");
	$('#frames').hide();
	toastr.success('copied');
}

function embed_section(){
	toastr.remove();
	var sectionID = $('select[name="section_list"]').val();
	if(sectionID!=''){
		var template = '';
	template +='<iframe width="300" height="200" frameborder="0" scrolling="no" src="<?php echo BASEURL ?>embed/highlights/'+btoa(sectionID)+'"></iframe>';
	$('#frames').html(template).show();
	$('#frames').select();
	document.execCommand("copy");
	$('#frames').hide();
	toastr.success('copied');
	}else
		toastr.error('Select valid section');
}


function search_section(){
	toastr.remove();
	var sectionID = $('select[name="section_list1"]').val();
	if(sectionID!=''){
		window.location.href = "<?php echo base_url().folder_name ?>/scrolling_news?id="+sectionID;
	}else{
		toastr.error('Select valid section');
	}
}

$(document).ready(function(){
		var config={		
			customConfig: 'config2.js',
			toolbar : [ 
			  {items: [ 'TextColor','BGColor','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','Bold','Italic','Underline','Embed'] },
				
			  {items: ['Cut', 'Copy','Paste','PasteText','Undo','Redo','Find','Replace']},
			  {items : [ 'Format']},
			  {items: [ 'Source','Strike','Subscript','Superscript','NumberedList','BulletedList','Outdent','Indent','Smiley','SpecialChar','PageBreak','Iframe','FontAwesome','SImage'] }
			  ],
			extraPlugins: 'autogrow,html5audio,embed,colordialog,fontAwesome,wordcount,notification,simage', 
			contentsCss : image_url+'includes/ckeditor/plugins/fontAwesome/css/font-awesome.min.css',
			allowedContent : true,
			removePlugins : 'magicline',
			autoGrow_maxHeight : 1000,
			extraAllowedContent : 'img(*){*}[*];object(*){*}[*];embed(*){*}[*];param(*){*}[*];script(*){*}[*];blockquote(*){*}[*];p(*){*}[*]',
			width : '563px'
		};
  		CKEDITOR.replace('scroll_text',config );

});
</script>
