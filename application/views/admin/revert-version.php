<link href="<?php echo image_url; ?>css/admin/bootstrap.min_3_3_4.css" rel="stylesheet" type="text/css">	
<link href="<?php echo image_url; ?>css/admin/jquery.datetimepicker.css" rel="stylesheet" type="text/css" />
<link href="<?php echo image_url; ?>css/admin/jquery.dataTables.css" rel="stylesheet" type="text/css" />
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo image_url; ?>js/jquery.dataTables.js"></script>
<style>
.btn-custom {padding: 10px !important;}
.dataTables_info,.dataTables_paginate,.dataTables_length{display:none;}
#example{margin-top:1%;}
.pagination{text-align:center;}
.pagination a,.pagination strong{background: #3c8dbc;padding: 3px 7px 3px;color: #fff;}
.pagination strong{background:#795548;}
#delete_version{float:left;width:41%; left: 30%;}
.delete_label{width: 41%;font-weight: bold !important;font-size: 17px !important;margin-right:30px;}
#delete_version button{margin-right: 4px !important;margin-left: 8px;}
.hd,.hd:hover,.hd:focus,.hd:active{background: #f00 !important;}
.sd,.sd:hover,.sd:focus,.sd:active{background: #8BC34A !important;}
.loader{font-size: 23px;float: left;width:100%;color:#8bc34a;display:none;padding-bottom: 9px;}
.modal{top:10%;}
#delete_modal .modal-body {text-align:center;}
#preview_content{width: 100%;float: left;overflow: scroll; height: 300px;}

</style>
<div class="Container">
	<div class="BodyWhiteBG">
		<div class="BodyHeadBg Overflow clear">
			<div class="FloatLeft BreadCrumbsWrapper">
				<div class="breadcrumbs"><a href="javascript:void(0);">Dashboard</a> > <a href="javascript:void(0);"><?php echo $title; ?></a></div>
				<h2 class="FloatLeft"><?php echo $title; ?></h2>
			</div>
			<p class="FloatRight save-back save_margin article_save">
				<a class="back-top FloatLeft" href="<?php echo base_url().folder_name; ?>" title="Go Back"><i class="fa fa-chevron-circle-left fa-2x"></i></a>
			</p>
		</div>
		<div class="Overflow DropDownWrapper" id="main_content">
		
			<div class="FloatLeft w2ui-field">
				<select id="section_name" class="controls">
					<option value="">section: All</option>
					<?php

						foreach($menu_id as $sectionName):
							if(@$_GET['menu_id']==$sectionName->Section_id){
								print '<option selected value="'.$sectionName->Section_id.'">'.$sectionName->Sectionname.'</option>';
							}else{
								print '<option value="'.$sectionName->Section_id.'">'.$sectionName->Sectionname.'</option>';
							}
							
						endforeach;
					?>
				</select>	
			</div>
			
			<div class="FloatLeft w2ui-field">
				<select id="section_type" class="controls">
					<option value="">section: All</option>
					<option value="1" <?php if(@$_GET['page_type']==1){ print 'selected' ;} ?>>Section</option>
					<option value="2" <?php if(@$_GET['page_type']==2){ print 'selected' ;} ?>>Article</option>
				</select>	
			</div>
			<div class="FloatLeft w2ui-field">
				<select id="action_type" class="controls">
					<option value="">Select Action</option>
					<option value="1">Hard Delete</option>
					<option value="2">Soft Delete</option>
				</select>	
			</div>
			<button class="btn btn-primary btn-custom" type="button" id="search">Search</button>
			<button class="btn btn-primary btn-custom" id="clear_search">Clear Search</button>
			<table id="example" class="display" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th>version Name</th>
						<th>Section Name</th>
						<th>Page Type</th>
						<th>Created on</th>
						<th>Version Id</th>
						<th>Action <input style="margin-top: 3px !important;position: absolute;margin-left: 37px !important;" type="checkbox"  class="select_field"></th>
					</tr>
				</thead>
				<tbody>
					<?php
						
						foreach($data as $value):
							print '<tr class="tr_'.$value->vid.'">';
							print '<td>'.$value->version_name.'</td>';
							print '<td>'.$value->section_name.'</td>';
							if($value->page_type==1){
								$type="Section Page";
							}else if($value->page_type==2){
								$type="Article Page";
							}
							print '<td>'.$type.'</td>';
								print '<td>'.$value->created_on.'</td>';
								print '<td>'.$value->version_id.'</td>';
								?>
								<td>
									<button class="btn btn-primary preview" title="xml code" vid="<?php print $value->vid; ?>" ><i class="fa fa-code" aria-hidden="true"></i> </button>
									<button class="btn btn-primary revert" vid="<?php print $value->vid; ?>" title="Revert"><i class="fa fa-file-code-o" aria-hidden="true"></i></button>
									<button class="btn btn-primary delete_version_row" vid="<?php print $value->vid; ?>"  title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> </button>
									<input type="checkbox" value="<?php print $value->vid; ?>" class="multiaction">
								</td>
								<?php
							print '</tr>';
						endforeach;
					?>
				</tbody>
			</table>
			<div class="pagination">
			<?php
				print $pagination;
			?>
			</div>
		</div>
	</div>
</div>

<!--Delete Bootstrap Modal-->
<div class="modal fade" id="delete_modal" role="dialog">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Are You Sure Want to Delete?</h4>
      </div>
      <div class="modal-body">
		<span class="loader"><i class="fa fa-renren fa-spin" aria-hidden="true"></i></span>
        <input type="hidden" value="" id="vid_delete">
		<button class="btn btn-primary hd deleted"  role="H">Hard Delete</button>	
		<button class="btn btn-primary sd deleted"  role="S" >Soft Delete</button>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
       </div>
    </div>
  </div>
</div>
<!--END-->

<!--Code preview Bootstrap Modal-->
<div class="modal fade" id="preview_modal" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-center">PREVIEW</h4>
      </div>
      <div class="modal-body">
		<pre lang="xml" id="preview_content">
		</pre>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
       </div>
    </div>
  </div>
</div>
<!--END-->

<!--Code action Bootstrap Modal-->
<div class="modal fade" id="action_modal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-center">Action</h4>
      </div>
      <div class="modal-body text-center">
		<input type="hidden" value="" id="vid_action">
		<button class="btn btn-primary sd select_action" action="reset">Reset Vsersion</button>
		<button class="btn btn-primary sd select_action" action="publish">Reset Vsersion & Make as Published</button>
		<div class="form-group text-center" style="width:100%;margin-top:1%;margin-bottom:0;display:none;">
			<p class="message" style="margin:0;color: #8bc34a;"></p>
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
       </div>
    </div>
  </div>
</div>
<!--END-->

<script>
$(document).ready(function() {
    $('#example').DataTable();
	
	$('.delete_version_row').click(function(){
		$('#vid_delete').val($(this).attr('vid'));
		$("#delete_modal").modal({backdrop: 'static',keyboard: false});
	});
	$('.preview').on('click',function(){
		var vid=$(this).attr('vid');
		$.ajax({
			type:'post',
			cache:false,
			url:'<?php print HOMEURL.folder_name ?>/revert_version/preview_version',
			data:{'version_id':vid},
			success:function(data){
				$('#preview_content').html(data);
				$("#preview_modal").modal({backdrop: 'static',keyboard: false});
			},
			error:function(code,status){
				alert(code.status);
			}
		});
		
	});
	$('.revert').on('click',function(){
		$('#vid_action').val($(this).attr('vid'));
		$('.message').html('');
		$("#action_modal").modal({backdrop: 'static',keyboard: false});
	});
	$('.select_action').on('click',function(){
		var action=$(this).attr('action');
		var vid=$('#vid_action').val();
		$.ajax({
			type:'post',
			url:'<?php print HOMEURL.folder_name ?>/revert_version/action',
			data:{'version_id':vid,'type':action}, 
			cache:false,
			success:function(result){
				if(result==1 && action=='reset'){
					$('#action_modal').find('.form-group').show();
					$('.message').html('Template Was Reverted Successfully.Please Publish the Version').css('color','#8bc34a');
				}else if(result==1 && action=='publish'){
					$('#action_modal').find('.form-group').show();
					$('.message').html('Template Was Reverted & Published  Successfully.').css('color','#8bc34a');
				}else{
					$('#action_modal').find('.form-group').show();
					$('.message').html('Something went wrong.please try again').css('color','#f00');
				}
			},
			error:function(status,message){
				alert(message);
			}
		});
	
	});
	
	$('.deleted').on('click',function(){
		$('.loader').show();
		var role=$(this).attr('role');
		var vid=$('#vid_delete').val();
		$.ajax({
			type:'post',
			cache:false,
			url:'<?php print HOMEURL.folder_name ?>/revert_version/delete_version',
			data:{'role':role,'version_id':vid},
			success:function(data){
				$('.loader').hide();
				$('#delete_modal').modal('hide'); 
				$('.tr_'+vid).remove();
			},
			error:function(code,status){
				alert(code.status);
			}
		});
	});
	$('.select_field').on('click',function(){
	
		if($(this).is(":checked")){
			$('.multiaction').prop('checked',true);
		}else{
			$('.multiaction').prop('checked',false);
		}
	});
	
	$('#action_type').on('change',function(){
		var value=$(this).val();
		if(value==1 || value==2){
			var type=(value==1)?'HARD':'SOFT';
			var ajax_type=(value==1)?'H':'S';
			var c=confirm("Are you sure want to delete("+type+")?");
			if(c==true){
				var vid=[];
				$('.multiaction').each(function(){
					if($(this).is(':checked')){
						vid.push($(this).val());
					}
				});
				if(vid.length==0){
					alert('Please Select Some Version');
				}else{
					for(var i=0;i<vid.length;i++){
						$.ajax({
						type:'post',
						cache:false,
						url:'<?php print HOMEURL.folder_name ?>/revert_version/delete_version',
						data:{'role':ajax_type,'version_id':vid[i]},
						success:function(data){
							$('.tr_'+vid[i]).remove();
						},
						error:function(code,status){
						alert(code.status);
						}
						});
					}
				
				}
			}
		}
	});
} );
document.getElementById("search").addEventListener("click",function(){
	var section_id=document.getElementById("section_name").value;
	var section_type=document.getElementById("section_type").value;
	window.location.href="<?php print HOMEURL.folder_name ?>/revert_version?menu_id="+section_id+'&page_type='+section_type;
});

document.getElementById("clear_search").addEventListener("click",function(){
	window.location.href="<?php print HOMEURL.folder_name ?>/revert_version";
});
</script>