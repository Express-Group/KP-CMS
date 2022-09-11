<?php $script_url = image_url;
$CI = &get_instance(); ?>
<link href="<?php echo $script_url; ?>css/admin/bootstrap.min_3_3_4.css" rel="stylesheet" type="text/css">	
<link href="<?php echo $script_url; ?>css/admin/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $script_url; ?>css/admin/jquery.datetimepicker.css" rel="stylesheet" type="text/css" />
<script src="<?php echo $script_url; ?>js/jquery-1.11.3.min.js" type="text/javascript"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo $script_url; ?>js/moment-with-locales.js"></script>
<script type="text/javascript" src="<?php echo $script_url; ?>js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="<?php echo $script_url; ?>js/jquery.datetimepicker.js"></script>
<script type="text/javascript" src="<?php echo $script_url; ?>js/jquery.dataTables.js"></script>
<script type="text/javascript" src="<?php echo $script_url; ?>js/w2ui-fields-1.0.min.js"></script>
<link href="<?php echo $script_url; ?>css/admin/jquery.dataTables.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="<?php echo $script_url; ?>css/admin/w2ui-fields-1.0.min.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<style>
.pagina{float: left;width: 100%; margin: 3% 0 2%;}
.pagina a{color: #337ab7;text-decoration: none; padding: 6px 10px 6px; border: 1px solid #c8e8f7;float: left; border-right-width: 0;}
.pagina a:last-child{border-right-width: 1px;}
.pagina a.active{background: #dff5ff;}
.table th{background: #5e7b8a; color: #fff;text-align:center;}
.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th{ padding: 13px; }
</style>
<div class="Container">
	<div class="BodyWhiteBG">
		<div class="BodyHeadBg Overflow clear">
			<div class="FloatLeft BreadCrumbsWrapper"><div class="breadcrumbs">Dashboard > <?php echo $title; ?></div><h2><?php echo $title; ?></h2></div>
			<?php if($role['publish']==1): ?>
			<p class="FloatRight SaveBackTop remoda1-bg"><a  type="button" class="btn-primary btn publish_button"><i class="fa fa fa-flag"></i> Publish</a></p>
			<?php endif; ?>
		</div>
		<div class="Overflow DropDownWrapper">
			<div class="FloatLeft w2ui-field">
				<select id="search_by" class="controls">
					<option value="">Select Section</option>
					<?php
						
						foreach($CI->getsections(1,0) as $sections):
							$selected ='';
							$getsubsection = $CI->getsections(2,$sections->Section_id);
							if($this->input->get('section')== $sections->Section_id ){ $selected ='selected'; }
							echo '<option value="'.$sections->Section_id.'" style="color:blue;font-weight:bold;" '.$selected.'>'.$sections->Sectionname.'</option>';
							if(count($getsubsection) > 0){
								foreach($getsubsection as $subsection):
									$subsectionselected ='';
									if($this->input->get('section')== $subsection->Section_id ){ $subsectionselected ='selected'; }
									echo '<option value="'.$subsection->Section_id.'" '.$subsectionselected.'>&nbsp;&nbsp;&nbsp;&nbsp;'.$subsection->Sectionname.'</option>';
								endforeach;
							}
						endforeach;
					?>
				</select>
			</div>
			<div class="FloatLeft"><input type="search" value="<?php if($this->input->get('query')!=''){ echo $this->input->get('query');} ?>" placeholder="Search" class="SearchInput" name="txtSearch" id="txtSearch"></div>
			<button class="btn btn-primary" type="button" id="sponsor_search">Search</button>
			<button class="btn btn-primary" type="button" id="clear_search">Clear Search</button>
			<div class="display" style="width:100%;float:left;margin-top:1%;"><div class="display" style="width:50%; float:left;">
				<table id="example" class="table table-bordered" cellspacing="0" width="100%">
					<thead>
						<tr>
						<th style="width: 16%;">Content ID</th>
						<th>Article HeadLine</th>
						<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$i=0;
							foreach($articlelist as $article):
								if( $i%2 == 0){ $classname ='info'; }else{ $classname ='warning'; }
								echo '<tr class="'.$classname.'">';
								echo '<td>'.$article->content_id.'</td>';
								echo '<td style="font-size:13px;">'.strip_tags($article->title).'</td>';
								if($role['add']==1) : 
									echo '<td><a class="button tick add_article" rights="1" data-toggle="tooltip" title="Add"> <i class="fa fa-plus"></i></a></td>';
								else:
									echo '<td><a class="button tick add_article" rights="0" data-toggle="tooltip" title="Add"> <i class="fa fa-plus"></i></a></td>';
								endif;
								echo '</tr>';
								$i++;
							endforeach;
						?>
					</tbody>
				</table>
				<div class="pagina"><?php echo $pagination; ?></div>
			</div>
			<div class="display" style="width:50%; float:left; padding-left: 1%;">
				<table id="example1" class="table-bordered table" cellspacing="0" width="100%">
					<thead>
						<tr>
						<th style="width:17%;">Content ID</th>
						<th>Title</th>
						<th >Expiry</th>
						<th class="ex">Expiry Date</th>
						<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$sponsorarticle = $CI->sponsor_article();
						$j=0;
						echo '<style>.ex{width:17%;text-align:center;}</style>';
						foreach($sponsorarticle as $sponart):
							if( $j%2 == 0){ $classname ='info'; }else{ $classname ='warning'; }
							$title = $CI->get_article_title($sponart->content_id);
								$date = '<td class="text-center"><a class="button tick exp" sid="'.$sponart->sid.'" rights="'.$role['edit'].'"   data-toggle="tooltip" title="Expiry date"> <i class="fa fa-calendar-check-o"></i></a></td>';
							echo '<tr sid="'.$sponart->sid.'"  class="contentid-'.$sponart->content_id.' '.$classname.'"><td>'.$sponart->content_id.'</td><td style="font-size: 12px;">'.$title.'</td>'.$date.'<td>'.$sponart->expiry_date.'</td><td><a class="button tick del " rights="'.$role['delete'].'" sid="'.$sponart->sid.'"  data-toggle="tooltip" title="Delete"> <i class="fa fa-trash"></i></a></td></tr>';
							$j++;
						endforeach;
						?>
					</tbody>
				</table>
			</div><div>
		</div>
	</div>
</div>
<div class="modal fade" id="expiry_date" role="dialog" style="top:20%;z-index: 9999;">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">SELECT DATE</h4>
		  <input type="hidden" id="sid_temp">
        </div>
        <div class="modal-body">
          <input size="16" type="text" value="<?php echo date('Y/m/d H:i'); ?>" readonly class="form_datetime" style="width:100%;">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" onclick="save_date()">Save</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
</div>
<script>
var delete_role = "<?php echo $role['delete'] ?>";
var edit_role = "<?php echo $role['edit'] ?>";
$('[data-toggle="tooltip"]').tooltip(); 
$(".form_datetime").datetimepicker();
$('.add_article').on('click',function(e){
	e.preventDefault();
	var status = $(this).attr('rights');
	if(status=='0'){
		toastr.error("Access denied.");
	}else{
		var content_id = $(this).parents('tr').find('td').eq(0).html(); 
		var title = $(this).parents('tr').find('td').eq(1).html(); 
		var template ='';
		var classcount = $('.contentid-'+content_id).length;
		if($('#example1').find('tr:last').hasClass('info')){
			var classname = 'warning';
		}
		if($('#example1').find('tr:last').hasClass('warning')){
			var classname = 'info';
		}
		if($('#example1').find('tr').length==1){  var classname = 'info'; }
		if(classcount == 0){
			$.ajax({
				type:'post',
				url:'<?php echo HOMEURL.folder_name ?>/sponsor_article/updatesponsorarticle',
				data:{'content_id':content_id},
				dataType:'json',
				success:function(result){
					if(result.id!=0){
						template +='<tr sid="'+result.id+'" class="contentid-'+content_id+' '+classname+'"><td>'+content_id+'</td><td style="font-size: 12px;">'+title+'</td><td class="text-center"><a rights="'+edit_role+'" class="button tick exp" sid="'+result.id+'"  data-toggle="tooltip" title="Expiry date"> <i class="fa fa-calendar-check-o"></i></a></td><td>'+result.date+'</td><td><a class="button tick del" rights="'+delete_role+'" sid="'+result.id+'"  data-toggle="tooltip" title="Delete"> <i class="fa fa-trash"></i></a></td></tr>';
						$('#example1').find('tbody').append(template);
						toastr.success("Article added successfully");
					}else{
						toastr.error("Something went wrong..please try again");
					}
				},
				error:function(err,errcode){
					toastr.error("Something went wrong..please try again");
				}
			});
		}else{
			toastr.error("Already added");
		}
		

	}
});

$('.publish_button').on('click',function(e){
	var cnf = confirm("Are you sure want to publish?");
	if(cnf==true){
		var response = [];
		$($('#example1').find('tbody')).find('tr').each(function(index){
			response.push({sid : $(this).attr('sid'), order : (index + 1)});
		
		});
		console.log(response);
		
		$.ajax({
			type:'post',
			url:'<?php echo HOMEURL.folder_name ?>/sponsor_article/publish',
			cache:false,
			data : {'data':response},
			success:function(result){
				//alert(result);
				toastr.success("Sponsor articles published successfully");
			},
			error:function(err,errcode){
				toastr.error("Something went wrong..please try again");
			}
		});
	}
	

});

$(document).on('click','.exp',function(){
	var access = $(this).attr('rights');
	if(access=='0'){ toastr.error('Access denied'); return false;}
	$('#sid_temp').val($(this).attr('sid'));
	$('#expiry_date').modal();
	$('.form_datetime').trigger('click');
});
function save_date(){
var sid = $('#sid_temp').val();
var exp_date = $('.form_datetime').val();
if(exp_date!=''){

	$.ajax({
		type:'post',
		url:'<?php echo HOMEURL.folder_name ?>/sponsor_article/update_expiry_date',
		cache:false,
		data:{'expiry_date':exp_date,'sid':sid},
		success:function(result){
			$($('#example1').find('tbody')).find('tr').each(function(){
				if($(this).attr('sid') == sid){
					$(this).find('td').eq(3).html(result);
					$('.ex').css('width','17%');
					$('#expiry_date').modal('hide');
					toastr.success("Expiry date updated successfully");
					return false;
				}
			});
			
		},
		error:function(err,errcode){
			toastr.error("Something went wrong");
		}
	});
	

}else{
	toastr.error("Please enter a valid date");
}

}

$(document).on('click','.del',function(e){
	e.preventDefault();
	var access = $(this).attr('rights');
	if(access=='0'){ toastr.error('Access denied'); return false;}
	var conf = confirm("Are You sure want to delete?");
	if(conf==true){
	var sid = $(this).attr('sid');
		$.ajax({
			type:'post',
			url:'<?php echo HOMEURL.folder_name ?>/sponsor_article/delete_sponsor_article',
			cache:false,
			data:{'sid':$(this).attr('sid')},
			success:function(result){
					$(this).parents('tr').remove();
					$($('#example1').find('tbody')).find('tr').each(function(index){
						if($(this).attr('sid') == sid){ $(this).remove(); }
					});
					$($('#example1').find('tbody')).find('tr').each(function(index){
						if(index %2 == 0 ){
							var classname = 'info';
						}else{
							var classname = 'warning';
						}
						$(this).removeClass('info').removeClass('warning').addClass(classname);
					});
				toastr.success("Article deleted successfully");
			},
			error:function(err,errcode){
				toastr.error("Something went wrong");
			}
		});
	
	}
});
$('#sponsor_search').on('click',function(){
	var type = $('#ddFilterBy').val();
	var query = $('#txtSearch').val();
	var search_by = $('#search_by').val();
	if(type=='' || type == undefined || type==null ){ type = '2';}
	if(query.trim()=='' && search_by.trim()==''){
		toastr.error("Please enter valid query");
	}else{
		window.location.href = "<?php echo HOMEURL.folder_name ?>/sponsor_article?query="+query+"&section="+search_by;
	}
});
$('#clear_search').on('click',function(){
	window.location.href = "<?php echo HOMEURL.folder_name ?>/sponsor_article";
});
$( function(){
	$('#example1').find('tbody').sortable({
    update: function( event, ui ){
		$($('#example1').find('tbody')).find('tr').each(function(index){
			if(index %2 == 0 ){
				var classname = 'info';
			}else{
				var classname = 'warning';
			}
			$(this).removeClass('info').removeClass('warning').addClass(classname);
		});
	}
	});
	
});
</script>