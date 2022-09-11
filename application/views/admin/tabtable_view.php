<link href="<?php echo image_url ?>css/admin/bootstrap.min_3_3_4.css" rel="stylesheet" type="text/css">
<style>
.tab-sec{width:100%;float:left;margin-top:3%;}
.margin-top-10{margin-top:25px;font-weight:bold !important;}
.table-wrapper{width:40%;float:left;}
.table-wrapper th{text-align:center;background: #3c8dbc;color: #fff;}
.bs-example input[type="password"], input[type="text"], select{width:auto;}
</style>
<div class="Container">
	<div class="BodyWhiteBG">
		<div class="BodyHeadBg Overflow clear">
			<div class="FloatLeft  BreadCrumbsWrapper PollResult">
					<div class="breadcrumbs"><a href="#">Dashboard</a> > <a href="#">Content</a> > <a href="#">Tab & Table Master</a></div>
					<h2>Tab & Table Master</h2>
			</div> 
			<p class="FloatRight SaveBackTop" style="margin-left: 6px;"><a id="publish_tab" class="btn-primary btn"><i class="fa fa-plus-circle"></i> &nbsp;Publish</a></p>
			<p class="FloatRight SaveBackTop"><a id="add_tab" class="btn-primary btn"><i class="fa fa-plus-circle"></i> &nbsp;New Tab</a></p>
			<p class="FloatRight SaveBackTop"><input style="margin-right: 12px;margin-top: 0;padding: 10px;" placeholder="Enter Tab name" type="text" id="tab_name"></p>
		</div>
		<div class="Overflow DropDownWrapper">
			<div class="tab-sec">
				<ul class="nav nav-tabs">
					<?php
						$tabdata = json_decode($tabs,true);
						for($i=0;$i<count($tabdata);$i++):
							echo '<li class="'.(($i==0)?'active':'').'"><a data-toggle="tab" href="#tab-'.$i.'">'.$tabdata[$i][1].'</a></li>';
						endfor;
					?>
				</ul>
				<div class="tab-content">
					<?php
						$tabdata = json_decode($tabs,true);
						for($i=0;$i<count($tabdata);$i++):
							echo '<div id="tab-'.$i.'" class="tab-pane fade '.(($i==0)?' in active ' : '').' ">';
							echo '<h4 class="margin-top-10">ENTER TABLE DETAILS</h4>';
							echo '<div class="table-wrapper">';
							echo '<table id="tbl-'.$i.'" class="table table-bordered">';
							echo '<thead><tr><th>NO</th><th>TEAM</th><th>P</th><th>W</th><th>GD</th><th>PTS</th></tr></thead><tbody>';
							$content = $tabdata[$i][0];
							for($j=0;$j<count($content);$j++):
								echo '<tr>';
								echo '<td><input value="'.$content[$j][0].'" type="text"></td>';
								echo '<td><input value="'.$content[$j][1].'" type="text"></td>';
								echo '<td><input value="'.$content[$j][2].'" type="text"></td>';
								echo '<td><input value="'.$content[$j][3].'" type="text"></td>';
								echo '<td><input value="'.$content[$j][4].'" type="text"></td>';
								echo '<td><input value="'.$content[$j][5].'" type="text"></td>';
											
								
								echo '</tr>';
							endfor;
							echo '</tbody></table>';
							echo '<button data-tabid="'.$i.'" class="btn btn-default add">+</button><button data-tabid="'.$i.'" class="btn btn-default minus">-</button><button data-tabid="'.$i.'" class="btn btn-default delete_tab">Delete tab</button>';
							echo '</div>';
							echo '</div>';
						endfor;
					?>
				</div>
			</div>
		</div>
	</div>
	<input type="hidden" id="tab_count" value="<?php if($tabs!=''){ echo count($tabdata);}  ?>">
</div> 
 <link type="text/css" rel="stylesheet" href="<?php echo image_url ?>css/admin/datepicker.min.css" />
<script type="text/javascript" src="<?php echo image_url ?>js/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="<?php echo image_url ?>js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="<?php echo image_url ?>js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo image_url ?>js/moment.min.js"></script>
<script type="text/javascript" src="<?php echo image_url ?>js/bootstrap-datetimepicker.min.4.14.30.js"></script>
<script src="<?php echo image_url ?>js/jquery-1.7.2.min.js" type="text/javascript"></script>
<script src="<?php echo image_url ?>js/jquery-ui.min-1.8.18.js" type="text/javascript"></script>
<script>
$(document).ready(function(e){
	$('#add_tab').on('click',function(e){
		e.preventDefault();
		var tab_name  = $('#tab_name').val().trim();
		var tab_count = $('#tab_count').val();
		if(tab_name!='' && tab_name!=undefined){
			var menutemplate ='';
			var contenttemplate ='';
			if(tab_count!=''){
				var newtabcount = parseInt(tab_count) + 1;
			}else{
				var newtabcount = 0;
			}
			contenttemplate +='<div id="tab-'+newtabcount+'" class="tab-pane fade '+((tab_count=='')?' in active ' : '')+' ">';
			contenttemplate +='<h4 class="margin-top-10">ENTER TABLE DETAILS</h4>';
			contenttemplate +='<div class="table-wrapper">';
			contenttemplate +='<table id="tbl-'+newtabcount+'" class="table table-bordered">';
			contenttemplate +='<thead><tr><th>NO</th><th>TEAM</th><th>P</th><th>W</th><th>GD</th><th>PTS</th></tr></thead><tbody>';
			contenttemplate +='</tbody></table>';
			contenttemplate +='<button data-tabid="'+newtabcount+'" class="btn btn-default add">+</button><button data-tabid="'+newtabcount+'" class="btn btn-default minus">-</button><button data-tabid="'+newtabcount+'" class="btn btn-default delete_tab">Delete tab</button>';
			contenttemplate +='</div>';
			contenttemplate +='</div>';
			menutemplate +='<li class="'+((tab_count=='')?'active' : '')+'"><a data-toggle="tab" href="#tab-'+newtabcount+'">'+tab_name+'</a></li>';
			$('.tab-sec').find('.nav-tabs').append(menutemplate);
			$('.tab-sec').find('.tab-content').append(contenttemplate);
			$('#tab_count').val(newtabcount);
			$('#tab_name').val('');
		
		}else{
			toastr.error("Enter valid tab name");
		}
	});
	
	$(document).on('click','.add',function(e){
		var tab_id = $(this).attr('data-tabid');
		var $template  ='';
		$template += '<tr>';
		$template += '<td><input type="text"></td>';
		$template += '<td><input type="text"></td>';
		$template += '<td><input type="text"></td>';
		$template += '<td><input type="text"></td>';
		$template += '<td><input type="text"></td>';
		$template += '<td><input type="text"></td>';
		$template += '</tr>';
		$('#tbl-'+tab_id).find('tbody').append($template);
	
	});
	$(document).on('click','.minus',function(e){
		var tab_id = $(this).attr('data-tabid');
		$($('#tbl-'+tab_id).find('tbody')).find('tr:last-child').remove();
	
	});
	$(document).on('click','.delete_tab',function(e){
	var cnf = confirm("Are you sure want to delete tab ? ");
		if(cnf==true){
			var tab_id = $(this).attr('data-tabid');
			$("[href=#tab-"+tab_id+"]").parent('li').remove();
			$("#tab-"+tab_id).remove();
			$('.tab-sec').find('li').each(function(index){
				if(index==0){
					$(this).removeClass('active').addClass('active');
					$('.tab-pane').eq(0).removeClass('active').addClass('active');
					$('.tab-pane').eq(0).removeClass('in').addClass('in');
				}
			});
		}		
	});
	$('#publish_tab').on('click',function(e){
		var tab_count = $('#tab_count').val();
		
		if(tab_count!=''){
			var result = [];
			$(document).find('.tab-pane').each(function(index){
				var input = [];
				$($($(this)).find('tbody')).find('tr').each(function(index){
					input[index] = [$(this).find('input').eq(0).val() , $(this).find('input').eq(1).val(), $(this).find('input').eq(2).val(), $(this).find('input').eq(3).val() ,$(this).find('input').eq(4).val(),$(this).find('input').eq(5).val()];
				});
				var tab_name = $(this).attr('id');
				
				result[index] =[input , $("[href=#"+tab_name+"]").text()] ;
				
			});
			$.ajax({
				type:'post',
				cache:false,
				data:{'json':result},
				url :'<?php echo base_url().folder_name ?>/tabtable_master/update',
				success: function(result){
					if(result==1){
						toastr.success("tabs updated successfully");
					}
				},
				error:function(err, errcode){
					toastr.error("Something went wrong..please try again");
				}
			})
		}else{
			toastr.error("No tabs found");
		}
	});
});
</script>