<link href="<?php print image_url; ?>css/admin/jquery.dataTables.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php print image_url; ?>js/jquery.dataTables.js"></script>
<style>
.dataTables_info,#standouttable_paginate,#standouttable_length,#livemaster_length,#livemaster_paginate{display:none;}
.searchterm{width: 100%;float: left;margin-top: 2%;text-align: center;margin-bottom: 2%;}
.standout_btn{background: #3c8dbc;padding: 8px;color: #fff;border: none;border-radius: 6px;}
.standout_btn_success{background: #8BC34A;padding: 8px;color: #fff;border: none;border-radius: 6px;}
#livemaster{margin-bottom:3px;}
</style>

<div class="Container">
	<div class="BodyWhiteBG">
		<div class="BodyHeadBg Overflow clear">
			<div class="FloatLeft  BreadCrumbsWrapper PollResult">
				<div class="breadcrumbs"><a href="#">Dashboard</a> &gt; <a href="#">Content</a></div>
				<h2>Standout article manager</h2>
			</div> 
		</div>
		<div class="Overflow DropDownWrapper">
			<div class="searchterm">
				<input type="number" id="contentid" value="<?php print @$_GET['contentid'] ?>" placeholder="Enter Content ID">
				<input type="text" id="title" value="<?php print @$_GET['title'] ?>" placeholder="Enter Title">
				<button type="button" id="searchbtn" class="standout_btn">search</button>
				<button type="button" id="clearsearch" class="standout_btn"> clear search</button>
			</div>
			<?php
				if(count($livearticle > 0)){
					print '<table id="livemaster">';
					print '<thead><tr><th>CONTENT ID</th><th>TITLE</th><th>ACTION</th></tr></thead>';
					foreach($livearticle as $article){
						print '<tr>';
						print '<td>'.$article->content_id.'</td>';
						print '<td>'.$article->title.'</td>';
						if(@$response[0]->content_id== $article->content_id){
							print '<td><button class="standout_btn_success" content_id="'.$article->content_id.'" disabled>STANDOUT ARTICLE</button></td>';
						}else{
							print '<td><button class="standout_btn clickbtn" content_id="'.$article->content_id.'">Make as standout article</button></td>';
						}
						print '</tr>';
					}
					print '</table>';
				}
			?>
			<table id="standouttable">
				<thead>
					<tr>
						<th>CONTENT ID</th>
						<th>TITLE</th>
						<th>ACTION</th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach($response as $result):
							print '<tr>';
							print '<td>'.$result->content_id.'</td>';
							$articleresponse=$controller->standoutarticle($result->content_id);
							print '<td>'.$articleresponse[0]->title.'</td>';
							print '<td><button class="standout_btn_success" content_id="'.$result->content_id.'" disabled>STANDOUT ARTICLE</button></td>';
							print '</tr>';
						endforeach;
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
$(document).ready(function(){
    $('#standouttable').DataTable({
		pageLength: 20
	});

	$('#livemaster').DataTable({
		pageLength: 20
	});
	$('.clickbtn').on('click',function(){
		var contentId=$(this).attr('content_id');
		var btn=$(this);
		var cm=confirm("Are you sure want to article as standout article ?");
		if(cm==true){
			$.ajax({
				type:'POST',
				url :'<?php print HOMEURL.folder_name ?>/standout/save',
				cache:false,
				data:{'contentid':contentId},
				success:function(response){
					if(response=='1'){
						alert('success');
						location.reload();
					}else{
						alert('failure..tryagain');
					}
				},
				error:function(response){
					alert('failure..tryagain');
				}
			});
		}
	});
	
	$('#searchbtn').click(function(){
		var contentId=$('#contentid').val();
		var title=$('#title').val();
		if(contentId==''  && title==''){
			alert('Enter valid Details');
		}else{
			window.location.href="<?php print HOMEURL.folder_name?>/standout?title="+title+"&contentid="+contentId;
		}
	
	});
	$('#clearsearch').on('click',function(){
		window.location.href="<?php print HOMEURL.folder_name?>/standout";
	});
});
</script>