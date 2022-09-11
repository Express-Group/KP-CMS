<style>
	.article-log,.article-log-header,.article-log-body,.search,.article-log-table{width:100%;float:left;}
	.article-log{margin-top:4%;margin-bottom:4%;}
	.article-log-header h1{text-align:center;padding-top: 6px;font-weight: bold !important;}
	.search{margin-top:2%;}
	.article-log-table{width: 90%; margin: 0 5%;}
	.pager{width:100%;float:left;text-align: center;margin-top: 1%;}
	.pager a{padding: 3px 11px 5px;background: #3c8dbc;color: #fff;margin-right: 4px;}
	.pager a.active{padding: 3px 11px 5px;background: #fff;color: #000;margin-right: 0px;border: 1px solid #3c8dbc;}
	.button-primary{padding: 4px;background: #3c8dbc;border: none;color: #fff;width: 92%;cursor:pointer;}
	.button-up{background: #fff;color: #3c8dbc;border: 1px solid #3c8dbc;}
	.modal{top:10% !important;}
	.modal-content{border-radius: 0 !important;border: 1px solid rgba(60, 141, 188, 0.32) !important;}
	.modal-header{background: #3c8dbc;color: #fff;}
</style>
<link rel="stylesheet" href="<?php echo image_url ?>css/admin/jquery.dataTables.css">
<script src="<?php echo image_url ?>js/jquery.dataTables.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<div class="article-log">
	<div class="article-log-header">
		<h1>ARTICLE LOG</h1>
	</div>
	<div class="article-log-body">
		<div class="search">
		</div>
		<div class="article-log-table">
			<table id="articlelog" class="table">
				<thead>
					<tr>
					<th>Article Id</th>
					<th>Title</th>
					<th>Ver</th>
					<th>Created By</th>
					<th>Created On</th>
					<th>Action</th>
					</tr>
				</thead>
				<tbody>
				<?php
				$CI = &get_instance();
				foreach($records as $logdata):
					$rawdata = json_decode($logdata->log,true);
					$details = $rawdata['details'];
					$CI = &get_instance();
					$versionCount = $CI->getversioncount($logdata->article_id);
					echo '<tr>';
					echo '<td>'.$logdata->article_id.'</td>';
					echo '<td>'.$details['LiveArticleDetails']['title'].'</td>';
						echo '<td>'.$versionCount.'</td>';
					echo '<td>'.$CI->getusernamebyid($logdata->created_by).'</td>';
					echo '<td>'.$logdata->created_on.'</td>';
					echo '<td><button class="button-primary" onclick="viewlogarticles('.$logdata->article_log_id.','.$logdata->article_id.')">View</button><br><button onclick="comparearticles('.$logdata->article_log_id.','.$logdata->article_id.')" class="button-primary button-up">Compare</button></td>';
					echo '</tr>';
				endforeach;
				?></tbody>
			</table>
			<div class="pager"><?php echo $pagination;?> </div>
		</div>
	</div>
</div>
<!-- Modal -->
<div class="modal fade" id="compare" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" style="opacity: .7;color:#fff;">&times;</button>
          <h4 class="modal-title">Select Article Version</h4>
        </div>
        <div class="modal-body">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
</div>
<!--end-->
<script>
$("#articlelog").dataTable({"bPaginate": false, "bInfo": false});
function viewlogarticles(logid,articleid){
$.ajax({
		type:'post',
		cache:false,
		data:{'logid':logid,'articleid':articleid},
		url:'<?php echo base_url().folder_name ?>/article_log/getrelavantversionforview',
		success:function(result){
			$('#compare').find('.modal-body').html(result);
			$('#compare').modal();
		},
		error:function(errcode,errstatus){
			alert(errstatus);
		}
	})
}
function comparearticles(logid,articleid){
	$.ajax({
		type:'post',
		cache:false,
		data:{'logid':logid,'articleid':articleid},
		url:'<?php echo base_url().folder_name ?>/article_log/getrelavantversion',
		success:function(result){
			$('#compare').find('.modal-body').html(result);
			$('#compare').modal();
		},
		error:function(errcode,errstatus){
			alert(errstatus);
		}
	})
	
}

function compareit(oldlogid,newlogid){
	window.open("<?php echo base_url().folder_name ?>/article_log/viewlog/"+oldlogid+"/"+newlogid,"_blank");
}
function viewit(oldlogid,newlogid){
	window.open("<?php echo base_url().folder_name ?>/article_log/viewlog/"+newlogid,"_blank");
}
</script>