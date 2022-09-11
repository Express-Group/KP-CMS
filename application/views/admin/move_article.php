<?php
$movecount = [1 , 100 , 500 , 1000 , 2000 , 5000 , 10000 , 50000]
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title><?php echo $title; ?></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <style>
	.margin-top-3{margin-top:5%;}
	.margin-bottom-0{margin-bottom:0;}
  </style>
</head>
<body>
<div class="container">
  <div class="row margin-top-3">
	<div class="col-md-4 col-md-offset-4">
		<div class="panel panel-primary">
			<div class="panel-heading text-center">Live Articles </div>
			<div class="panel-body">Article Count  <span class="pull-right"><?php echo $articleCount; ?></span></div>
			<div class="panel-footer">
				<div class="row">
					<div class="col-md-8">
						<div class="form-group margin-bottom-0">
							<select class="form-control" id="livearticle_list">
								<option value="">Please select any one</option>
								<?php
									for($i=0;$i<count($movecount);$i++):
										echo '<option value="'.$movecount[$i].'">'.$movecount[$i].'</option>';
									endfor;
								?>
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<button class="btn btn-primary" id="live_article">Move</button>
					</div>
				</div>
			</div>
		</div>
	</div>
  </div>
  <div class="row margin-top-3">
	<div class="col-md-4 col-md-offset-4">
		<div class="panel panel-primary">
			<div class="panel-heading text-center">Archive Articles 2013</div>
			<div class="panel-body">Article Count  <span class="pull-right"><?php echo $archivearticleCount3; ?></span></div>
			<div class="panel-footer">
				<div class="row">
					<div class="col-md-8">
						<div class="form-group margin-bottom-0">
							<select class="form-control" id="archivearticle_list3">
								<option value="">Please select any one</option>
								<?php
									for($i=0;$i<count($movecount);$i++):
										echo '<option value="'.$movecount[$i].'">'.$movecount[$i].'</option>';
									endfor;
								?>
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<button class="btn btn-primary" id="archive_article3">Move</button>
					</div>
				</div>
			</div>
		</div>
	</div>
  </div>
  <div class="row margin-top-3">
	<div class="col-md-4 col-md-offset-4">
		<div class="panel panel-primary">
			<div class="panel-heading text-center">Archive Articles 2014</div>
			<div class="panel-body">Article Count  <span class="pull-right"><?php echo $archivearticleCount4; ?></span></div>
			<div class="panel-footer">
				<div class="row">
					<div class="col-md-8">
						<div class="form-group margin-bottom-0">
							<select class="form-control" id="archivearticle_list4">
								<option value="">Please select any one</option>
								<?php
									for($i=0;$i<count($movecount);$i++):
										echo '<option value="'.$movecount[$i].'">'.$movecount[$i].'</option>';
									endfor;
								?>
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<button class="btn btn-primary" id="archive_article4">Move</button>
					</div>
				</div>
			</div>
		</div>
	</div>
  </div>
</div>
<script>
$(document).ready(function(e){
	$('#archive_article3').on('click' , function(e){
		var count = $('#archivearticle_list3').val();
		if(count!=''){
			window.location.href="<?php echo base_url(folder_name.'/kp_migration/movearticle/2') ?>/"+count;
		}else{
			alert('Select valid number');
		}
	});
	$('#archive_article4').on('click' , function(e){
		var count = $('#archivearticle_list4').val();
		if(count!=''){
			window.location.href="<?php echo base_url(folder_name.'/kp_migration/movearticle/3') ?>/"+count;
		}else{
			alert('Select valid number');
		}
	});
	$('#live_article').on('click' , function(e){
		var count = $('#livearticle_list').val();
		if(count!=''){
			window.location.href="<?php echo base_url(folder_name.'/kp_migration/movearticle/1') ?>/"+count;
		}else{
			alert('Select valid number');
		}
	});
});
</script>
</body>
</html>
