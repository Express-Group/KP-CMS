<span class="css_and_js_files">
<link href="<?php echo image_url; ?>css/admin/video-up.css" rel="stylesheet" type="text/css">
<link href="<?php echo image_url ?>css/admin/tabcontent.css" rel="stylesheet" type="text/css" />	
<link href="<?php echo image_url ?>css/admin/bootstrap.min_3_3_4.css" rel="stylesheet" type="text/css">	


<link href="<?php echo image_url ?>includes/ckeditor/contents.css" rel="stylesheet" type="text/css" id="contents_css" />
<link href="<?php echo image_url ?>css/admin/jquery.dataTables.css" rel="stylesheet" type="text/css" />

<link href="<?php echo image_url ?>css/admin/jquery-ui-autocomplete.css" rel="stylesheet" type="text/css" /> 
<link rel="stylesheet" href="<?php echo image_url ?>css/admin/jquery-ui-custom.css">
<link href="<?php echo image_url ?>css/admin/jquery.datetimepicker.css" rel="stylesheet" type="text/css" />
<style>
.btnsuccess{color: #fff;background: green; padding: 6px; border-radius: 4px;}
.btnerror{color: #fff;background: red; padding: 6px; border-radius: 4px;}
.modal-backdrop.in{opacity:0;}
.modal-dialog{margin: 5% auto;}
</style>
</span>
<div class="Container">
	<div class="BodyWhiteBG">
		<div class="BodyHeadBg Overflow clear">
			<div class="FloatLeft  BreadCrumbsWrapper PollResult">
				<div class="breadcrumbs"><a href="#">Dashboard</a> > <a href="#">widget Manager</a></div>
				<h2>widget Manager</h2>
			</div>
		</div>
		<div class="Overflow DropDownWrapper">
			<table id="example" class="display dataTable no-footer">
				<thead>
					<tr>
					<th>Widget id</th>
					<th>Widget name</th>
					<th>Widget Image</th>
					<th>Widget status</th>
					<th>Action</th>
					</tr>
				</thead>
				<tbody>
				<?php
					foreach($widgets as $widgetdata):
						$imagepos = strrpos($widgetdata->widgetfilePath,'/');
						$imagepath = image_url.'images/admin/template_design/images/widget_images/'.substr($widgetdata->widgetfilePath,($imagepos + 1)).'-small.jpg';
						echo '<tr>';
						echo '<td>'.$widgetdata->widgetId.'</td>';
						echo '<td>'.$widgetdata->widgetName.'</td>';
					    ?>
						<td onclick="view_image('<?php echo $imagepath ?>')"><i class="fa fa-image"></i></td>
						<?php
						if($widgetdata->status==1){
							echo '<td style="color:green;font-weight:700;">Active</td>';
						}else{
							echo '<td style="color:red;font-weight:700;">Inactive</td>';
						}
						if($widgetdata->status==1){
							echo '<td><a class="btnerror" href="widget_master/action/1/'.$widgetdata->widgetId.'"><i style="color:#fff;" class="fa fa-times-circle"></i></a></td>';
						}else{
							echo '<td><a class="btnsuccess" href="widget_master/action/2/'.$widgetdata->widgetId.'"><i style="color:#fff;" class="fa fa-check"></i></a></td>';
						}
						
						echo '</tr>';
					endforeach;
				?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<!-- Modal -->
<div class="modal fade" id="image" role="dialog">
	<div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Image Preview</h4>
        </div>
        <div class="modal-body">
          <img src="" id="imgpreview">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo image_url ?>js/bootstrap/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo image_url ?>js/bootstrap/bootstrap-hover-dropdown.min.js"></script>
<script type="text/javascript" src="<?php echo image_url ?>js/jquery.dataTables.js"></script> 
<script>
function view_image($image_path){
	$("#image").modal();
	$('#imgpreview').attr('src',$image_path).css({'width':'100%'});
}
</script>