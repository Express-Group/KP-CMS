<style>
body{background-color: #f2f2f2 !important;}
.h2{text-align: center;font-size: 27px;}
.text-center{text-align:center;}
.margin{margin-top:1%;}
.border-none,.modal-content{border-radius:0px !important;}
button.success,button.success:hover,button.success:active,button.success:focus{background: #4CAF50 none repeat scroll 0 0 !important;}
button.error,button.error:hover,button.error:active,button.error:focus{background: #F44336  none repeat scroll 0 0 !important;}
button.warn,button.warn:hover,button.warn:active,button.warn:focus{background: #FFC107  none repeat scroll 0 0 !important;}
button.preview,button.preview:hover,button.preview:active,button.preview:focus{background: #607D8B   none repeat scroll 0 0 !important;}
button.embed,button.embed:hover,button.embed:active,button.embed:focus{background: #f14602   none repeat scroll 0 0 !important;}
.modal{top:47px !important;}
.form-group{width:100%;}
#tbl_name,#tbl_total{border: 2px solid #009688 !important;border-radius: 0px;}
.err{color:#F44336;margin-top:2px;}
label{font-weight:700 !important;}
.tbl_box{box-shadow: 0px 1px 4px 0px #00000073;height: 125px;margin-right: 37px; margin-top: 3%;border-radius: 11px;background:#fff;}
.tbl_title{width: 100%;float: left;padding: 12px 0 12px;font-size: 22px;text-align: center;color: #3c8dbc;text-transform: uppercase;border-bottom: 2px solid #4caf50;}
.tbl_group{margin-top: 11%;text-align: center;margin-left: 12%;}
.add-dynamic-parameter .form-control {border: 2px solid #009688 !important;border-radius: 0px;}
.edit-dynamic-parameter .form-control {border: 2px solid #009688 !important;border-radius: 0px;}
.tbl_del{font-size: 23px;color: red;position: absolute;top: 9px;left: 85%;cursor: pointer;}
.edit-pencil{font-size: 17px;margin-left: 15px;color:#009688;cursor:pointer;}
.tbl_n{width: 56%; float: left; margin: 0;font-size: 14px;}
input[id^="add_field4_"]{display:none;}
</style>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<div class="container" style="margin-top:3%;">
	<div class="row">
		<div class="col-md-12 text-center">
			<h2 class="h2">Table Manager</h2>
			<textarea id="frames" style="display:none;"></textarea>
			<button class="btn btn-primary margin border-none success" onclick="add_table();">ADD TABLE</button>
		</div>
	</div>
	<div class="row home-append" style="padding-left: 14%;margin-bottom:2%;">
		<?php
		
			foreach($table as $Data){
					print '<div class="col-md-3 tbl_box" id="container-temp-'.$Data->tid.'">';
					print '<div class="tbl_title" value="tit_'.$Data->tid.'">';
					print  '<h5 id="d_'.$Data->tid.'" class="tbl_n">'.$Data->table_name.'</h5>';
					print  '<input type="text" value="'.$Data->table_name.'" id="e_'.$Data->tid.'" style="width:55%;display:none;">';
					print  '<span class="edit-pencil" id="p_'.$Data->tid.'" onclick="edit_pencil('.$Data->tid.')"><i class="fa fa-pencil" aria-hidden="true"></i></span>';
					print '</div>';
					print '<div class="btn-group tbl_group">';
					if($Data->table_properties==null || $Data->table_properties==' '):
						print '<button data-toggle="tooltip" title="Add data" type="button" id="savebtn_'.$Data->tid.'" class="btn btn-primary border-none success" onclick="add_parameter('.$Data->tid.');"><i class="fa fa-plus-circle" aria-hidden="true"></i></button>';
					else:
						print '<button data-toggle="tooltip" title="Add data" type="button" id="savebtn_'.$Data->tid.'" class="btn btn-primary border-none success" onclick="add_parameter('.$Data->tid.');" disabled><i class="fa fa-plus-circle" aria-hidden="true"></i></button>';
					endif;
					if(!empty($Data->table_properties)):
						print '<button data-toggle="tooltip" title="Edit data" type="button"  id="editbtn_'.$Data->tid.'" class="btn btn-primary border-none warn" onclick="edit_parameter('.$Data->tid.')"><i class="fa fa-pencil" aria-hidden="true"></i></button>';
					else:
						print '<button data-toggle="tooltip" title="Edit data" type="button" id="editbtn_'.$Data->tid.'" class="btn btn-primary border-none warn" disabled><i class="fa fa-pencil" aria-hidden="true"></i></button>';
					endif;
					print '<button data-toggle="tooltip" title="Preview" type="button" class="btn btn-primary border-none preview" onclick="preview('.$Data->tid.')"><i class="fa fa-television" aria-hidden="true"></i></button>';
					print '<button data-toggle="tooltip" title="Embed code" type="button" class="btn btn-primary border-none embed" onclick="embed('.$Data->tid.')"><i class="fa fa-external-link" aria-hidden="true"></i></button>';
					print '</div>';
					print '<span class="tbl_del" onclick="deletes('.$Data->tid.')"><i class="fa fa-times" aria-hidden="true"></i></span>';
					print '</div>';
			}
		?>
	</div>
</div>

<!---model table starts here-->

	<!--to add table-->
  <div class="modal fade" id="add-table" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title text-center">Add Table</h4>
        </div>
        <div class="modal-body">
			<div class="form-group">
				<label>Enter the table mame :</label>
				<input type="text" class="form-control" id="tbl_name">
				<p class="tbl_name_error err"></p>
			</div>
			<div class="form-group">
				<label>Enter the Total :</label>
				<input type="text" class="form-control" id="tbl_total">
			</div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary border-none success" onclick="save_table();" >Save & Exit</button>
          <button type="button" class="btn btn-primary border-none error" data-dismiss="modal">Close</button>
        </div>
     </div>
    </div>
  </div>
  <!--end-->
  
 	<!--to add table-->
  <div class="modal fade" id="add-parameter" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title text-center">Add Parameter</h4>
        </div>
        <div class="modal-body">
			<div class="form-group">
				<div class="row">
					<div class="col-md-4">
						<label>Party</label>
					</div>
					<div class="col-md-4">
						<label>Lead</label>
					</div>
					<div class="col-md-4">
						<label>Won</label>
					</div>
					<!--<div class="col-md-3">
						<label>Total</label>
					</div>-->
				</div>
			</div>
			<div class="add-dynamic-parameter">
			</div>
			<div class="form-group text-center">
				<input type="hidden" value="0" id="add_total_count">
				<input type="hidden" value="" id="tid">
				<button class="btn btn-primary border-none success" onclick="add_f();"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add Field</button>
				<button class="btn btn-primary border-none error" onclick="remove_f();"><i class="fa fa-times" style="color:#fff;" aria-hidden="true"></i>  Remove Field</button>
			</div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary border-none success" onclick="publish_table();" >Save & Exit</button>
          <button type="button" class="btn btn-primary border-none error" data-dismiss="modal" >Close</button>
        </div>
     </div>
    </div>
  </div>
  <!--end-->
  
  	<!--tpreview-->
  <div class="modal fade" id="preview" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title text-center">Preview</h4>
        </div>
        <div class="modal-body preview-data">
        </div>
        <!--<div class="modal-footer">
          <button type="button" class="btn btn-primary border-none error" data-dismiss="modal">Close</button>
        </div>-->
     </div>
    </div>
  </div>
  <!--end-->
  
  <!--to edit table-->
  <div class="modal fade" id="edit-parameter" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title text-center">Edit Parameter</h4>
        </div>
        <div class="modal-body">
			<div class="form-group">
				<div class="row">
					<div class="col-md-4">
						<label>Party</label>
					</div>
					<div class="col-md-4">
						<label>Lead</label>
					</div>
					<div class="col-md-4">
						<label>Won</label>
					</div>
					<!--<div class="col-md-3">
						<label>Total</label>
					</div>-->
				</div>
			</div>
			<div class="edit-dynamic-parameter">
			</div>
			<div class="form-group text-center">
				<input type="hidden" value="0" id="edit_total_count">
				<input type="hidden" value="" id="edit_tid">
				<button class="btn btn-primary border-none success" onclick="edit_add_f();"><i class="fa fa-plus-circle" aria-hidden="true"></i> Add Field</button>
				<button class="btn btn-primary border-none error" onclick="edit_remove_f();"><i class="fa fa-times" style="color:#fff;" aria-hidden="true"></i>  Remove Field</button>
			</div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary border-none success" onclick="edit_publish_table();" >Save & Exit</button>
          <button type="button" class="btn btn-primary border-none error" data-dismiss="modal">Close</button>
        </div>
     </div>
    </div>
  </div>
  <!--end-->
  
  <!--to add table-->
  <div class="modal fade" id="sort_table" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title text-center">Sort Table</h4>
        </div>
        <div class="modal-body">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary border-none success" onclick="save_sort();" >Save & Exit</button>
          <button type="button" class="btn btn-primary border-none error" data-dismiss="modal">Close</button>
        </div>
     </div>
    </div>
  </div>
  <!--end-->
  
  
<script>
$('[data-toggle="tooltip"]').tooltip();
function add_table(){
$("#add-table").modal({backdrop: 'static',keyboard: false});
}
function save_table(){
	var tbl_name=$('#tbl_name').val();
	var tbl_total=$('#tbl_total').val();
	if(tbl_name.trim()==''){
		$('.tbl_name_error').html('Table Name required');
	}else{
		$.ajax({
			type:'post',
			cache:false,
			url:'<?php print HOMEURL.folder_name?>/dynamic_table/addtable',
			data:{'table_name':tbl_name.trim(),'total':tbl_total},
			success:function(result){
					toastr["success"]("Table Added successfully..");
					$('#tbl_name').val();
					$('.tbl_name_error').html();
					$('#add-table').modal('hide');
					var template='';
					template +='<div class="col-md-3 tbl_box" id="container-temp-'+result+'">';
					template +='<div class="tbl_title" value="tit_'+result+'">';
					template +='<h5 id="d_'+result+'" class="tbl_n">'+tbl_name+'</h5>';
					template +='<input type="text" value="'+tbl_name+'" id="e_'+result+'" style="width:55%;display:none;">';
					template +='<span class="edit-pencil" id="p_'+result+'" onclick="edit_pencil('+result+')"><i class="fa fa-pencil" aria-hidden="true"></i></span>';
					template +='</div>';
					template +='<div class="btn-group tbl_group">';
					template +='<button type="button" data-toggle="tooltip" title="Add Data" id="savebtn_'+result+'" class="btn btn-primary border-none success" onclick="add_parameter('+result+');"><i class="fa fa-plus-circle" aria-hidden="true"></i></button>';
					template +='<button type="button" data-toggle="tooltip" title="Edit Data" id="editbtn_'+result+'" class="btn btn-primary border-none warn" onclick="edit_parameter('+result+')" disabled ><i class="fa fa-pencil" aria-hidden="true"></i></button>';
					template +='<button type="button" data-toggle="tooltip" title="Preview" class="btn btn-primary border-none preview" onclick="preview('+result+')"><i class="fa fa-television" aria-hidden="true"></i></button>';
					template +='<button type="button" data-toggle="tooltip" title="Embed code" class="btn btn-primary border-none embed" onclick="embed('+result+')"><i class="fa fa-external-link" aria-hidden="true"></i></button>';
					template +='</div>';
					template +='<span class="tbl_del" onclick="deletes('+result+')"><i class="fa fa-times" aria-hidden="true"></i></span>';
					template +='</div>';
					$('.home-append').append(template);
				
			},
			error:function(status,code){
				$('.tbl_name_error').html(code);
			}
		});
	}
}


function add_parameter(tid){
	$('#tid').val(tid);
	$("#add-parameter").modal({backdrop: 'static',keyboard: false});
	
}

function add_f(){
	var total=$('#add_total_count').val();
	var total=parseInt(total) + 1;
	var template='';
	template +='<div class="form-group" id="adds_'+total+'">';
	template +='<div class="row">';
	template +='<div class="col-md-4"><input type="text" class="form-control" id="add_field1_'+total+'"></div>';
	template +='<div class="col-md-4"><input type="text" class="form-control" id="add_field2_'+total+'"></div>';
	template +='<div class="col-md-4"><input type="text" class="form-control" id="add_field3_'+total+'"></div>';
	template +='<div style="display:none;" class="col-md-3"><input type="text" class="form-control" id="add_field4_'+total+'"></div>';
	template +='</div>';
	template +='</div>';
	$('#add_total_count').val(total);
	$('.add-dynamic-parameter').append(template);
}
function remove_f(){
	var total=$('#add_total_count').val();
	var total=parseInt(total);
	if(total!=0){
	$('#adds_'+total).remove();
	var total=total-1;
	$('#add_total_count').val(total);
	}
}

function publish_table(){
	var tid=$('#tid').val();
	var total_count=$('#add_total_count').val();
	if(total_count!=0){
	var field1=[];
	var field2=[];
	var field3=[];
	var field4=[];
	for(var i=1;i<=total_count;i++){
		field1.push($('#add_field1_'+i).val())
		field2.push($('#add_field2_'+i).val())
		field3.push($('#add_field3_'+i).val())
		field4.push($('#add_field4_'+i).val())
	}
	$.ajax({
		type:'post',
			cache:false,
			url:'<?php print HOMEURL.folder_name?>/dynamic_table/Add_Parameter',
			data:{'tid':tid,'total_count':total_count,'field1':field1,'field2':field2,'field3':field3,'field4':field4},
			success:function(result){
				if(result==1){
					$('#savebtn_'+tid).prop('disabled',true);
					$('#editbtn_'+tid).attr('onclick','edit_parameter('+tid+');');
					$('#editbtn_'+tid).prop('disabled',false);
					$('#tid').val('');
					$('#add_total_count').val(0);
					$('.add-dynamic-parameter').html('');
					toastr["success"]("Parameter Successfully Added");
					$('#add-parameter').modal('hide');
					
				}
			}
	});
	}else{
		toastr["error"]("Add Some Data Before Save");
	}

}

function preview(tid){
	$("#preview").modal({backdrop: 'static',keyboard: false});
	$.ajax({
		type:'post',
			cache:false,
			url:'<?php print HOMEURL.folder_name?>/dynamic_table/preview',
			data:{'tid':tid},
			success:function(result){
				$('.preview-data').html(result);
			}
	});
}

function edit_parameter(tid){
	$.ajax({
		type:'post',
			cache:false,
			url:'<?php print HOMEURL.folder_name?>/dynamic_table/edit_table',
			data:{'tid':tid},
			success:function(result){
				$('.edit-dynamic-parameter').html(result);
				var temp_count=$('#temp_edit_count').val();
				$('#edit_total_count').val(temp_count);
				$('#edit_tid').val(tid);
				$("#edit-parameter").modal({backdrop: 'static',keyboard: false});
				
			}
	});

}

function edit_add_f(){
	var total=$('#edit_total_count').val();
	var total=parseInt(total) + 1;
	var template='';
	template +='<div class="form-group" id="adds_'+total+'">';
	template +='<div class="row">';
	template +='<div class="col-md-4"><input type="text" class="form-control" id="add_field1_'+total+'"></div>';
	template +='<div class="col-md-4"><input type="text" class="form-control" id="add_field2_'+total+'"></div>';
	template +='<div class="col-md-4"><input type="text" class="form-control" id="add_field3_'+total+'"></div>';
	template +='<div style="display:none;" class="col-md-4"><input type="text" class="form-control" id="add_field4_'+total+'"></div>';
	template +='</div>';
	template +='</div>';
	$('#edit_total_count').val(total);
	$('.edit-dynamic-parameter').append(template);
}
function edit_remove_f(){
	var total=$('#edit_total_count').val();
	var total=parseInt(total);
	if(total!=0){
	$('#adds_'+total).remove();
	var total=total-1;
	$('#edit_total_count').val(total);
	}
}

function edit_publish_table(){
var tid=$('#edit_tid').val();
	var total_count=$('#edit_total_count').val();
	if(total_count!=0){
	var field1=[];
	var field2=[];
	var field3=[];
	var field4=[];
	for(var i=1;i<=total_count;i++){
		field1.push($('#add_field1_'+i).val())
		field2.push($('#add_field2_'+i).val())
		field3.push($('#add_field3_'+i).val())
		field4.push($('#add_field4_'+i).val())
	}
	var total_edit=$('#total_edit').val();
	$.ajax({
		type:'post',
			cache:false,
			url:'<?php print HOMEURL.folder_name?>/dynamic_table/Add_Parameter',
			data:{'tid':tid,'total_count':total_count,'field1':field1,'field2':field2,'field3':field3,'field4':field4,'total':total_edit},
			success:function(result){
				if(result==1){
					$('#edit_tid').val('');
					$('#edit_total_count').val(0);
					$('.edit-dynamic-parameter').html('');
					toastr["success"]("Parameter Edited Successfully");
					$('#edit-parameter').modal('hide');
					
				}
			}
	});
	}else{
		toastr["error"]("Add Some Data Before Save");
	}


}
$(document).ready(function(){
	$("#add-parameter").on("hidden.bs.modal", function () {
		$('#add-dynamic-parameter').html('');
		$('#tid').val(0);
		$('#tid').val(0);
	});
});
function deletes(tid){
var t=confirm('Are you sure want to delete?');
if(t ==true){
	$.ajax({
		type:'post',
		cache:false,
		url:'<?php print HOMEURL.folder_name?>/dynamic_table/delete',
		data:{'tid':tid},
		success:function(result){
			if(result==1){
				$('#container-temp-'+tid).remove();
			}
		}
		
	});

}
}

function edit_pencil(tid){
$('#e_'+tid).show();
$('#d_'+tid).hide();
$('#p_'+tid).attr('onclick','save_pencil('+tid+')').html('<i class="fa fa-floppy-o" aria-hidden="true"></i>');
}

function save_pencil(tid){
	var tbl=$('#e_'+tid).val();
	if(tbl.trim()==''){
		toastr["error"]("Add Table Name");
	}else{
		$.ajax({
		type:'post',
		cache:false,
		url:'<?php print HOMEURL.folder_name?>/dynamic_table/inserttablename',
		data:{'tid':tid,'tablename':tbl},
		success:function(result){
			if(result==1){
				toastr["success"]("table name edited successfully");
				$('#p_'+tid).attr('onclick','edit_pencil('+tid+')').html('<i class="fa fa-pencil" aria-hidden="true"></i>');
				$('#e_'+tid).val(tbl);
				$('#e_'+tid).hide();
				$('#d_'+tid).show().html(tbl);
			}
		}
		
	});
	}
}

function embed(tid){
	var template ='<iframe width="300" height="250" frameborder="0" scrolling="no" src="<?php echo BASEURL ?>embed/table/'+btoa(tid)+'"></iframe>';
	$('#frames').html(template).show();
	$('#frames').select();
	document.execCommand("copy");
	$('#frames').hide();
	toastr["success"]('copied');
}

function sort_table(){
	$('#sort_table').modal({backdrop: 'static',keyboard: false});
}
</script>