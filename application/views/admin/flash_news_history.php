<!DOCTYPE html>
<html lang="en">
<head>
  <title><?php echo $title; ?></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="<?php echo image_url ?>images/FrontEnd/images/favicon.ico" type="image/x-icon" />
  <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
   <link rel="stylesheet" type="text/css" href="<?php echo image_url ?>includes/ckeditor/skins/moono/editor.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="<?php echo image_url ?>includes/ckeditor/ckeditor.js"></script>
  <link href="<?php echo image_url ?>css/admin/toastr.css" rel="stylesheet" type="text/css" />
<script src="<?php echo image_url ?>js/toastr.js"></script>


  <style>
	body,html{font-family: 'Roboto', sans-serif;background:#eee;}
	.cke_editor_add_content{width:100% !important;}
	.flash-title{background: #fff;margin: 0;padding: 10px;margin-bottom: 2%;    text-transform: uppercase;font-weight: bold;}
	.flash-content{width: 100%;float: left;padding: 10px;border-radius: 6px;
    box-shadow: 1px 1px 1px #9c9696;background:#fff;margin-bottom: 2%;}
	.flashnews_content{overflow-y:scroll; height: 550px;}
	#save,#edit_save,#publish,#reset{width:auto !important;color:#fff !important;}
	.floating-div{background: #fff;padding: 10px;border-radius: 5px;box-shadow: 1px 1px 1px #d0caca;    margin-bottom: 1%;float: left;width: 100%;}
	.floating-div-content{float: left;width: 100%;height: 100px;overflow-y: scroll;margin-bottom: 1%;}
	.floating-bottom{border-top: 1px solid #d2cece;padding-top: 1%;float: left;width: 100%;}
	.pagination{margin-bottom: 5%; background: #ffffff;text-align: center;}
	.pagination a,.pagination strong{font-size: 17px;color: #0000ff;}
	.FooterWrapper {position: relative;}
  </style>
</head>
<body>
	<div class="container-fluid">
		<div class="row">
			<h3 class="text-center flash-title"><span id="goback" style="float: left;font-size: 16px;color: blue;cursor:pointer;">Go Back</span>Flash News</h3>
		</div>
		<div class="row">
			<div class="col-md-8 col-lg-8 col-md-offset-2">
				<h3 class="text-center">Flash News History</h3>
				<?php
				$CI = &get_instance();
				foreach($result as $history):
					$content = json_decode($history->history,true);
					$content = $content['response'];
					$username = $CI->get_username($history->user_id);
					echo '<div class="floating-div">';
					echo '<div class="floating-div-content">';
					echo '<label style="color: #a7a7a7;width: 100%;">Content: </label>';
					for($i=0;$i<count($content);$i++):
						echo '<p>'.($i + 1).'. '.strip_tags($content[$i]).'</p>';
					endfor;
					echo '</div>';
					echo '<div class="floating-bottom">';
					echo '<span style="color:blue;float: left;">'.$history->created_on.'</span>';
					echo '<span style="color:blue;float: right;text-align:right;">'.$username.'</span>';
					echo '</div>';
					echo '</div>';
				endforeach;
				?>
				<div class="pagination"><?php echo $pagination; ?></div>
				
			</div>
		</div>
	</div>
</body>
</html>
  