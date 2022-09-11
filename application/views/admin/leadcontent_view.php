<link href="<?php echo image_url ?>css/admin/bootstrap.min_3_3_4.css" rel="stylesheet" type="text/css">
<link href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet" type="text/css">
<style>
.tab-content , .tab-content .tab-pane{width:100%;float:left;}
.btn-custom{border-radius: 0 !important;box-shadow: 0px 1px 3px #00000069;}
.modal.in .modal-dialog{z-index: 999999;top:13%;}
.modal-content{border-radius:0;}
.modal-body input , .modal-body textarea{border: 1px solid #ddd !important;}
sup{color:red;}
#leadnews_list thead th , #set_leadnews thead th{background: #807f7f;color: #fff;text-align: center;}
#leadnews_list tbody , #set_leadnews tbody{background: #fff;}
#leadnews_list tbody td ,#set_leadnews tbody td{text-align:center;}
#section_list{border: 1px solid #ddd !important;background: #fff !important;width: 25%;float: left;}
.sort-ui{color: #0029ff;font-weight: bold;}
.dy th{background: #2aac0b;color: #fff;text-align: center;}
.dy td{text-align: center;}
</style>
<div class="Container">
	<div class="BodyWhiteBG">
		<div class="BodyHeadBg Overflow clear">
			<div class="FloatLeft  BreadCrumbsWrapper PollResult">
				<div class="breadcrumbs"><a href="#">Dashboard</a> &gt; <a href="#">Lead Content Manager</a></div>
				<h2>Lead Content Manager</h2>
			</div>
		</div>
		<div class="Overflow DropDownWrapper">
			<div class="leadcontent">
				<ul class="nav nav-tabs">
					<li class="active"><a data-toggle="tab" href="#addlead">Add Lead News</a></li>
					<li><a data-toggle="tab" href="#setlead">Set Lead News</a></li>
				</ul>
				 <div class="tab-content" style="background: #f1fcff94;padding: 1%;">
					<div id="addlead" class="tab-pane fade in active">
					  <button data-toggle="modal" data-target="#add_modal" data-backdrop="static"  class="btn btn-primary btn-custom pull-right" id="add_leadnews">ADD LEAD NEWS</button>
					  <table class="table table-bordered" style="margin-top:5%;" id="leadnews_list">
							<thead>
								<tr>
									<th>Id</th>
									<th>Title</th>
									<th>Description</th>
									<th>Result</th>
									<th>Image</th>
									<th>Modified On</th>
									<th>Status</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach($data as $news):
									echo '<tr>';
									echo '<td>'.$news->lead_id.'</td>';
									echo '<td>'.$news->title.'</td>';
									echo '<td>'.$news->description.'</td>';
									echo '<td>'.$news->result.'</td>';
									echo '<td><img src="'.image_url.'images/leadcontent/'.$news->imagepath.'" style="width: 75px;"></td>';
									echo '<td>'.$news->curr_date.'</td>';
									if($news->status==1){
										echo '<td id="del-'.$news->lead_id.'" style="color:green;">ACTIVE</td>';
									}else{
										echo '<td id="del-'.$news->lead_id.'" style="color:red;">INACTIVE</td>';
									}
									echo '<td>';
									if($news->status==1){
										echo '<button onclick="edit_leadnews('.$news->lead_id.')" style="background: #807f7f !important;" class="btn btn-primary btn-custom"><i class="fa fa-pencil" aria-hidden="true"></i></button><button onclick="delete_news('.$news->lead_id.')" style="background: #807f7f !important;" class="btn btn-primary btn-custom"><i class="fa fa-trash-o" aria-hidden="true"></i></button>';
									}else{
										echo '---';
									}
									echo '</td>';
									echo '</tr>';
								endforeach;
								?>
							</tbody>
					  </table>
					</div>
					<div id="setlead" class="tab-pane fade">
					  <h4>
						<select class="form-control" id="section_list">
							<?php
								echo '<option value="">Select any one</option>';
								echo '<option style="font-weight:bold;" value="169">Home</option>';
								foreach($section_mapping as $mapping){
									echo '<option style="font-weight:bold;" value="'.$mapping['Section_id'].'">'.strip_tags($mapping['Sectionname']).'</option>';
									foreach($mapping['sub_section'] as $sub_mapping) { 
										echo '<option value="'.$sub_mapping['Section_id'].'">&nbsp;&nbsp;&nbsp;&nbsp;'.strip_tags($sub_mapping['Sectionname']).'</option>';
										foreach($sub_mapping['sub_sub_section'] as $sub_sub_mapping) {
											echo '<option style="color:lightblue;" value="'.$sub_sub_mapping['Section_id'].'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.strip_tags($sub_sub_mapping['Sectionname']).'</option>';
										}
									}
								}									
							?>
						</select>
						<button id="add" style="display:none;" class="btn btn-primary btn-custom pull-right"> Add news</button>
					  </h4>
					  <table class="table table-bordered" style="margin-top:5%;" id="set_leadnews">
							<thead>
								<tr>
									<th>Id</th>
									<th>Section Name</th>
									<th>Title</th>
									<th>Order Id</th>
									<th>Modified On</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<tr><td colspan="7" style="text-align:center;">No rows found</td></tr>
							</tbody>
					  </table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!--add lead news modal-->
<div class="modal fade" id="add_modal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title text-center" style="color: #ff0021;text-transform:uppercase;">Add Lead News</h4>
        </div>
        <div class="modal-body">
		  <form method="post" action="" enctype="multipart/form-data" id="add_news">
          <label>Enter Title <sup>*</sup></label>
		  <input type="text" name="title" class="form-control">
		  <label style="margin-top:10px;">Enter Description <sup>*</sup></label>
		  <textarea class="form-control" name="description" ></textarea>
		  <label style="margin-top:10px;">Enter Result <sup>*</sup></label>
		  <input type="text" class="form-control" name="result">
		  <label style="margin-top:10px;">Upload Image <sup>*</sup></label>
		  <input type="file" class="form-control" name="upload_image">
		  </form>
        </div>
        <div class="modal-footer">
          <button id="save_leadnews" type="button" class="btn btn-primary btn-custom"   style="background: #2aac0b !important"><i class="fa fa-floppy-o" aria-hidden="true" style="color:#fff;"></i> Submit</button>
          <button type="button" class="btn btn-primary btn-custom" data-dismiss="modal"  style="background: #ed0e0e !important;"><i class="fa fa-times" aria-hidden="true" style="color:#fff;"></i> Close</button>
        </div>
      </div>
    </div>
</div>
<!--add lead news modal ends-->

<!--edit lead news modal-->
<div class="modal fade" id="edit_modal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title text-center" style="color: #ff0021;text-transform:uppercase;">Edit Lead News</h4>
        </div>
        <div class="modal-body">
		  <form method="post" action="" enctype="multipart/form-data" id="add_news">
		  <input type="hidden" id="eid">
          <label>Enter Title <sup>*</sup></label>
		  <input type="text" name="etitle" class="form-control">
		  <label style="margin-top:10px;">Enter Description <sup>*</sup></label>
		  <textarea class="form-control" name="edescription" ></textarea>
		  <label style="margin-top:10px;">Enter Result <sup>*</sup></label>
		  <input type="text" class="form-control" name="eresult">
		   <img src="" style="width:75px;margin:10px 0 0;display:block;" id="img_preview">
		  <label style="margin-top:10px;">Upload Image <sup>*</sup></label>
		  <input type="file" class="form-control" name="eupload_image">
		  </form>
        </div>
        <div class="modal-footer">
          <button id="update_leadnews" type="button" class="btn btn-primary btn-custom"   style="background: #2aac0b !important"><i class="fa fa-floppy-o" aria-hidden="true" style="color:#fff;"></i> Update</button>
          <button type="button" class="btn btn-primary btn-custom" data-dismiss="modal"  style="background: #ed0e0e !important;"><i class="fa fa-times" aria-hidden="true" style="color:#fff;"></i> Close</button>
        </div>
      </div>
    </div>
</div>
<!--edit lead news modal ends-->

<!--mapping news modal-->
<div class="modal fade" id="map_modal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title text-center" style="color: #ff0021;text-transform:uppercase;"></h4>
        </div>
        <div class="modal-body">
        </div>
        <div class="modal-footer">
          <button id="save_map" type="button" class="btn btn-primary btn-custom"   style="background: #2aac0b !important"><i class="fa fa-floppy-o" aria-hidden="true" style="color:#fff;"></i> Save</button>
          <button type="button" class="btn btn-primary btn-custom" data-dismiss="modal"  style="background: #ed0e0e !important;"><i class="fa fa-times" aria-hidden="true" style="color:#fff;"></i> Close</button>
        </div>
      </div>
    </div>
</div>
<!--mapping news modal ends-->
<script type="text/javascript" src="<?php echo image_url ?>js/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="<?php echo image_url ?>js/bootstrap/bootstrap.js"></script>
<script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
$(document).ready(function(e){
	$('#save_leadnews').on('click' , function(e){
		toastr.clear();
		var error = false;
		var title = $('input[name="title"]').val();
		var description = $('textarea[name="description"]').val();
		var result = $('input[name="result"]').val();
		var image = $('input[name="upload_image"]').val();
		if(title=='' || title==undefined){
			toastr.error('Enter valid title');
			error=true;
		}
		if(description=='' || description==undefined){
			toastr.error('Enter valid description');
			error=true;
		}
		if(result=='' || result==undefined){
			toastr.error('Enter valid result');
			error=true;
		}
		if(image=='' || image==undefined){
			toastr.error('Upload valid image');
			error=true;
		}
		if(image!=''){
			var extension = image.substr(image.lastIndexOf('.') + 1 , image.length).toLowerCase();
			if(extension!='jpg' && extension!='png' && extension!='gif'){
				toastr.error('Suports only jpg|png|gif');
				error=true;
			}
			
		}
		if(!error){
			var formdata  = new FormData;
			formdata.append('title' ,title);
			formdata.append('description' ,description);
			formdata.append('result' ,result);
			formdata.append('image' ,$('input[name="upload_image"]').get(0).files[0]);
			$.ajax({
				type:'post',
				url:'<?php echo base_url().folder_name ?>/leadcontent/add_news',
				cache:false,
				data:formdata,
				processData: false,
				contentType: false,
				success:function(result){
					result = JSON.parse(result);
					if(result.return=='1'){
						toastr.success('Lead News added successfully');
						$('#add_news').get(0).reset();
						$('#add_modal').modal('toggle');
						location.reload();
					}else{
						toastr.error(result.error);
					}
				},
				error:function(err,errcode){
					toastr.error(err.statusText);
				}
				
			})
		}
	});
	
	$('#section_list').on('change' ,function(e){
		if($(this).val()==''){
			$('#publish ,#add').hide();
		}else{
			$('#publish ,#add').show();
			list($(this).val());
		}
	});
	
	$('#add').on('click',function(e){
		$('#map_modal').find('.modal-title').html('Map news to section ('+$( "#section_list option:selected" ).text()+')');
		$.ajax({
			type:'post',
			cache:false,
			data:{'sectionid' : $("#section_list").val()},
			url:'<?php echo base_url().folder_name ?>/leadcontent/mappinglist',
			success:function(result){
				$('#map_modal').find('.modal-body').html(result);
				$('#map_modal').modal({backdrop: 'static', keyboard: false});
			},
			error:function(err,errcode){
				toastr.error(err.statusText);
			}
		})
	});
	
	$('#save_map').on('click',function(e){
		var ids = [];
		var section_id = $('#hidden_id').val();
		$('.ckeck_sum').each(function(index){
			if($(this).is(':checked')){
				ids.push($(this).val());
			}
		});
		if(ids.length > 0){
			$.ajax({
				type:'post',
				cache:false,
				data:{'id':ids,'sectionid':section_id},
				url:'<?php echo base_url().folder_name ?>/leadcontent/update_maplist',
				success:function(result){
					if(result==1){
						list(section_id);
						toastr.success('News mapped successfully');
						$('#map_modal').modal('toggle');
					}
				},
				error:function(err,errcode){
					toastr.error(err.statusText);
				}
			})
		}else{
			toastr.error('Please select any one');
		}
	});
	
	$('#update_leadnews').on('click' , function(e){
		toastr.clear();
		var error = false;
		var title = $('input[name="etitle"]').val();
		var description = $('textarea[name="edescription"]').val();
		var result = $('input[name="eresult"]').val();
		var image = $('input[name="eupload_image"]').val();
		if(title=='' || title==undefined){
			toastr.error('Enter valid title');
			error=true;
		}
		if(description=='' || description==undefined){
			toastr.error('Enter valid description');
			error=true;
		}
		if(result=='' || result==undefined){
			toastr.error('Enter valid result');
			error=true;
		}
		if(image!=''){
			var extension = image.substr(image.lastIndexOf('.') + 1 , image.length).toLowerCase();
			if(extension!='jpg' && extension!='png' && extension!='gif'){
				toastr.error('Suports only jpg|png|gif');
				error=true;
			}
			
		}
		if(!error){
			var formdata  = new FormData;
			formdata.append('title' ,title);
			formdata.append('description' ,description);
			formdata.append('result' ,result);
			formdata.append('image' ,$('input[name="eupload_image"]').get(0).files[0]);
			formdata.append('lead' ,$('#eid').val());
			$.ajax({
				type:'post',
				url:'<?php echo base_url().folder_name ?>/leadcontent/update_news',
				cache:false,
				data:formdata,
				processData: false,
				contentType: false,
				success:function(result){
					result = JSON.parse(result);
					if(result.return=='1'){
						toastr.success('Lead News updated successfully');
						$('#edit_modal').modal('toggle');
						location.reload();
					}else{
						toastr.error(result.error);
					}
				},
				error:function(err,errcode){
					toastr.error(err.statusText);
				}
				
			})
		}
	});
});
$(function(){
    $("#set_leadnews").find('tbody').sortable({
		update: function(event, ui) {
			order();
			publish();
		}
	});
    $("#set_leadnews").find('tbody').disableSelection();
});

function delete_mapping(mid){
	var cfm = confirm('Are you sure want to delete?');
	if(cfm==true){
		$.ajax({
			type:'post',
			url:'<?php echo base_url().folder_name ?>/leadcontent/delete_mapping',
			cache:false,
			data:{'mid' : mid},
			success:function(result){
				if(result==1){
					toastr.success('Row deleted successfully');
					$($("#set_leadnews").find('tbody')).find('tr').each(function(index){
						if($(this).attr('mid')==mid){ $(this).remove(); }	
					});
					order();
					publish(2);
				}
			},
			error:function(err,errcode){
				toastr.error(err.statusText);
			}
		})
	}
}

function order(){
	$($("#set_leadnews").find('tbody')).find('tr').each(function(index){
		$(this).attr('order',index + 1);
		$(this).find('.sort-ui').html(index + 1);
	});
}

function publish($type=1){
	var arr = [];
	$($("#set_leadnews").find('tbody')).find('tr').each(function(index){
		 arr.push({ mid: $(this).attr('mid'),lead: $(this).attr('lead'),order: $(this).attr('order')});
	});
	$.ajax({
		type:'post',
		cache:false,
		data:{'data':arr ,'type':$type},
		url:'<?php echo base_url().folder_name ?>/leadcontent/publish',
		success:function(result){
			toastr.success('published successfully');
		},
		error:function(err,errcode){
			toastr.error(err.statusText);
		}
	})
}

function list(id){
	$.ajax({
		type:'post',
		cache:false,
		url:'<?php echo base_url().folder_name ?>/leadcontent/get_news',
		data:{'sectionid' :id },
		success:function(result){
			if(result!=' '){
				$('#set_leadnews').find('tbody').html(result);
			}else{
				toastr.warning("no records Found");
				$('#set_leadnews').find('tbody').html('<tr><td colspan="7" style="text-align:center;">No rows found</td></tr>');
			}
		},
		error:function(err,errcode){
			toastr.error(err.statusText);
		}
	});
}

function delete_news(id){
	var cnf = confirm('Are you sure want to delete?');
	if(cnf==true){
		$.ajax({
			type:'post',
			cache:false,
			data:{'lead_id' : id},
			url:'<?php echo base_url().folder_name ?>/leadcontent/delete_leadnews',
			success:function(result){
				if(result==1){
					toastr.success('deleted successfully');
					location.reload();
				}else{
					toastr.error('something went wrong..please try again');
				}
			},
			error:function(err,errcode){
				toastr.error(err.statusText);
			}
		});
	}
	
}

function edit_leadnews(lead){
	$.ajax({
		type:'post',
		cache:false,
		data:{'lead_id' : lead},
		url:'<?php echo base_url().folder_name ?>/leadcontent/edit_leadnews',
		dataType:'json',
		success:function(result){
			$('input[name="etitle"]').val(result.title);
			$('textarea[name="edescription"]').val(result.description);
			$('input[name="eresult"]').val(result.result);
			$('#img_preview').attr('src',result.imagepath);
			$('#eid').val(lead);
			$('#edit_modal').modal({backdrop: 'static', keyboard: false});
			
		},
		error:function(err,errcode){
			toastr.error(err.statusText);
		}
	})
}
</script>