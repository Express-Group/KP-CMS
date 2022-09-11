<link href="<?php echo image_url; ?>css/admin/bootstrap.min_3_3_4.css" rel="stylesheet" type="text/css">	
<link href="<?php echo image_url; ?>css/admin/jquery.datetimepicker.css" rel="stylesheet" type="text/css" />
<link href="<?php echo image_url; ?>css/admin/jquery.dataTables.css" rel="stylesheet" type="text/css" />
<style>
.DropDownWrapper{margin: 143px 15px 15px;}
.form-group{width:100%;}
.form-group label{width: 100%;font-weight: 700 !important;font-size: 17px;text-transform: uppercase;margin-bottom: 10px;}
.form-group textarea{border: 1px solid #ddd !important;min-height: 150px;max-height: 150px;height: 150px;width: 100%;min-width: 100%;max-width: 100%;}
</style>
<div class="Container">
	<form method="post" action="<?php echo base_url(folder_name.'/amp_advertisements/save_content'); ?>"> 
		<div class="BodyWhiteBG">
			<div class="BodyHeadBg Overflow clear">
				<div class="FloatLeft  BreadCrumbsWrapper PollResult">
					<div class="breadcrumbs"><a href="#">Dashboard</a> > <a href="#"><?php echo $title; ?></a></div>
					<h2><?php echo $title; ?></h2>
				</div> 
				<p class="FloatRight SaveBackTop"><button class="btn btn-primary FloatRight i_button "  title="Save"><i class="fa fa-floppy-o" id="delete_button"></i> Save</button></p>
			</div>
			<div class="Overflow DropDownWrapper">
				<div class="col-md-4 col-lg-4">
					<div class="form-group">
						<label>AFTER TITLE</label>
						<textarea name="after_title" class="form-control"><?php echo @$adv['after_title']; ?></textarea>
					</div>
					<div class="form-group">
						<label>AFTER TAGS</label>
						<textarea name="after_tags" class="form-control"><?php echo @$adv['after_tags']; ?></textarea>
					</div>
				</div>
				<div class="col-md-4 col-lg-4">
					<div class="form-group">
						<label>BETWEEN ARTICLE CONTENT</label>
						<textarea name="between_article" class="form-control"><?php echo @$adv['between_article']; ?></textarea>
					</div>
					<div class="form-group">
						<label>AFTER MORE FROM THE SECTION</label>
						<textarea name="between_msection" class="form-control"><?php echo @$adv['between_msection']; ?></textarea>
					</div>
				</div>
				<div class="col-md-4 col-lg-4">
					<div class="form-group">
						<label>INBETWEEN GALLERY IMAGES (seperate adv with comma[,])</label>
						<textarea name="between_gallery_images" style="max-height: 325px;height: 325px;" class="form-control"><?php echo @$adv['between_gallery_images']; ?></textarea>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>