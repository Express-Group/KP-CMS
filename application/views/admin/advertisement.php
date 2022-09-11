<link href="<?php echo image_url ?>css/admin/bootstrap.min_3_3_4.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo image_url ?>js/jquery-1.11.3.min.js"></script>
<script type="text/javascript" src="<?php echo image_url ?>js/bootstrap/bootstrap.js"></script>
<style>
textarea{height:500px;min-height:500px;max-height:500px;}
</style>
<div class="Container">
	<div class="BodyWhiteBG">
		<div class="BodyHeadBg Overflow clear">
			<div class="FloatLeft  BreadCrumbsWrapper PollResult">
				<div class="breadcrumbs"><a href="#">Dashboard</a> &gt; <a href="#"><?php echo $title; ?></a></div>
				<h2><?php echo $title; ?></h2>
			</div>
			<p class="FloatRight SaveBackTop PubUnpublish">
				<button class="btn btn-primary FloatRight" title="Publish">Publish</button>
			</p>
			<p class="FloatRight SaveBackTop PubUnpublish">
				<button class="btn btn-primary FloatRight" title="save">Save</button>
			</p>
		</div>
		<div class="Overflow DropDownWrapper">
			<textarea style="width:100%;">
				<?php
					$filepath = FCPATH.'ads.txt';
					if(file_exists($filepath)){
						echo file_get_contents($filepath);
					}else{
						echo 'unable to load ads.txt file.please refresh the page';
					}
				?>
			</textarea>
		</div>
	</div>
</div> 