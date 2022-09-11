<link href="<?php echo image_url ?>css/admin/bootstrap.min_3_3_4.css" rel="stylesheet" type="text/css">	
<link href="<?php echo image_url ?>css/admin/jquery.dataTables.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo image_url ?>js/jscolor.js"></script>
<style>
input[type=search], .SearchInput{width: 200px;}
.text-center span{border-radius:5px;}
span .fa-pencil{margin-right:6px;}
.not_published,.not_published1{width: auto;float: right;color: red;font-weight: bold;margin-top: 7px;font-size: 18px;}
</style>
<div class="Container">
	<div class="BodyWhiteBG">
		<div class="BodyHeadBg Overflow clear">
			<div class="FloatLeft BreadCrumbsWrapper">
				<div class="breadcrumbs">Dashboard > Pie Chart Manager</div><h2>Pie Chart Manager</h2>
			</div>
			<p class="FloatRight SaveBackTop">
				<a href="" id="publish" type="button" class="btn-primary btn"><i class="fa fa fa-flag"></i> Publish</a>
			</p>
		</div>
		<div class="Overflow DropDownWrapper">
			<div class="FloatLeft TableColumn" style="margin-bottom:10px;">
				<div class="FloatLeft TableColumnSearch"><input type="text" placeholder="Enter the name" class="SearchInput" id="pie_name"></div>
				<div class="FloatLeft TableColumnSearch"><input type="number" placeholder="Enter the value" class="SearchInput" id="pie_value"></div>
				<div class="FloatLeft TableColumnSearch"><input type="text" placeholder="Enter the color" class="SearchInput jscolor" id="pie_color"></div>
				<button class="btn btn-primary" id="pie_submit">Save</button>
				<button style="display:none;" class="btn btn-primary" id="pie_edit_save">update</button>
				<button style="display:none;" class="btn btn-primary" id="save_cancel">cancel edit</button>
				<p class="not_published1" style="display:none;">Changes are not published</p>
				<?php
					if($content!=$livecontent){
						echo '<p class="not_published">Changes are not published</p>';
					}
				?>
			</div>
			<table class="dataTable" id="pie_table">
				<thead>
					<tr>
						<th>name</th>
						<th>value</th>
						<th>color</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
					if($hasfile==true && $content!=''){
						for($i=0;$i<count($content);$i++):
							$id = rand(10,10000000000);
							$class = ($i%2==0)?'even' : 'odd';
							print '<tr id="'.$id.'" class="'.$class.'">';
							print '<td>'.$content[$i]['name'].'</td>';
							print '<td>'.$content[$i]['value'].'</td>';
							print '<td class="text-center"><span style="background:#'.$content[$i]['color'].';padding:7px">'.$content[$i]['color'].'</span> </td>';
							print '<td><span style="color: #3c8dbc;cursor:pointer;" onclick="edit_row('.$id.');"><i class="fa fa-pencil"></i></span> <span style="color: #3c8dbc;cursor:pointer;" onclick="delete_row('.$id.');"><i class="fa fa-trash"></i></span></td>';
							print '</tr>';
						endfor;
					
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
</div> 
<script src="<?php echo image_url ?>js/jquery-1.7.2.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo image_url ?>js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo image_url ?>js/jquery.dataTables.js"></script>
<script>
$(document).ready(function(){
	$('#pie_submit').on('click',function(e){
		var piename = $('#pie_name').val();
		var pievalue = $('#pie_value').val();
		var piecolor = $('#pie_color').val(); 
		if(piename.trim()!='' && pievalue.trim()!=''){
			var id = Math.floor((Math.random() * 10000000000) + 1);
			var last_class = $($('#pie_table').find('tbody')).find('tr:last-child').attr('class');
			var clsname =  'odd';
			if(last_class==undefined || last_class=='odd'){
				var clsname =  'even';
			}
			$template = '';
			$template +='<tr class="'+clsname+'" id="'+id+'"><td>'+piename+'</td><td>'+pievalue+'</td><td class="text-center"><span style="background:#'+piecolor+';padding:7px">'+piecolor+'</span> </td><td><span style="color: #3c8dbc;cursor:pointer;" onclick="edit_row('+id+');"><i class="fa fa-pencil"></i></span> <span style="color: #3c8dbc;cursor:pointer;" onclick="delete_row('+id+');"><i class="fa fa-trash"></i></span></td></tr>';
			$('#pie_table').find('tbody').append($template);
			$('#pie_name').val('');
			$('#pie_value').val('');
			$('#pie_color').val('');
			save_chart_details();
			$('.not_published1').show();
			$('.not_published').remove();
			
		}else{
			toastr.error("Enter Valid Details");
		}

	});
	
	$('#save_cancel').on('click',function(e){
		$('#pie_submit').show();
		$('#pie_edit_save').hide().removeAttr('edit_id');
		$(this).hide();
		$('#pie_name').val('');
		$('#pie_value').val('');
		$('#pie_color').val('');
	});
	
	$('#pie_edit_save').on('click',function(e){
		var id = $(this).attr('edit_id')
		var piename = $('#pie_name').val();
		var pievalue = $('#pie_value').val();
		var piecolor = $('#pie_color').val(); 
		if(piename.trim()!='' && pievalue.trim()!=''){
			$template ='<td>'+piename+'</td><td>'+pievalue+'</td><td class="text-center"><span style="background:#'+piecolor+';padding:7px">'+piecolor+'</span> </td><td><span style="color: #3c8dbc;cursor:pointer;" onclick="edit_row('+id+');"><i class="fa fa-pencil"></i></span> <span style="color: #3c8dbc;cursor:pointer;" onclick="delete_row('+id+');"><i class="fa fa-trash"></i></span></td>';
			$('#'+id).html($template);
			$('#pie_name').val('');
			$('#pie_value').val('');
			$('#pie_color').val('');
			$('#pie_edit_save').hide().removeAttr('edit_id');
			$('#save_cancel').hide();
			$('#pie_submit').show();
			save_chart_details();
			toastr.success("Row updated successfully");
			$('.not_published1').show();
			$('.not_published').remove();
		}
	});
	
	$('#publish').on('click',function(e){
		e.preventDefault();
		save_chart_details(1);
		
	});
});

function delete_row($id){
	$('#'+$id).remove();
	toastr.success("Row removed successfully");
	save_chart_details();
	$('.not_published1').show();
	$('.not_published').remove();
}

function edit_row($id){
	$('#pie_name').val($('#'+$id).find('td').eq(0).html());
	$('#pie_value').val($('#'+$id).find('td').eq(1).html());
	$('#pie_color').val($($('#'+$id).find('td').eq(2)).find('span').html());
	$('#pie_submit').hide();
	$('#pie_edit_save').show().attr('edit_id',$id);
	$('#save_cancel').show();
}
function save_chart_details($type=0){
	var datas = [];
	var tmpdata;
	$($('#pie_table').find('tbody')).find('tr').each(function(index){
			tmpdata ={};
			tmpdata['name'] = $(this).find('td').eq(0).html().trim();
			tmpdata['value'] = $(this).find('td').eq(1).html().trim();
			tmpdata['color'] = $($(this).find('td').eq(2)).find('span').html().trim();
			datas.push(tmpdata);
	});
	$.ajax({
		type:'post',
		cache:false,
		url:'<?php echo base_url().folder_name?>/pie_chart/save_details',
		data:{'data':datas,'type':$type},
		success:function(result){
			if(result==1 && $type==1){
				toastr.success("Content published successfully");
				$('.not_published1').hide();
				$('.not_published').remove();
			}else if(result==1 && $type==0){
				toastr.success("Updated successfully");
			}else{
				toastr.error("Somenting went wrong..please try again");
			}
		},
		error:function(err,errcode){
			toastr.error("Somenting went wrong..please try again");
		}
	});

}
</script>