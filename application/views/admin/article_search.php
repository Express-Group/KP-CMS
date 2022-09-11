<link href="<?php echo image_url; ?>css/admin/bootstrap.min_3_3_4.css" rel="stylesheet" type="text/css">	
<link href="<?php echo image_url; ?>css/admin/jquery.datetimepicker.css" rel="stylesheet" type="text/css" />
<link href="<?php echo image_url; ?>css/admin/jquery.dataTables.css" rel="stylesheet" type="text/css" />
<div class="Container">
	<div class="BodyWhiteBG">
		<div class="BodyHeadBg Overflow clear">
			<div class="FloatLeft  BreadCrumbsWrapper PollResult">
				<div class="breadcrumbs"><a href="#">Dashboard</a> &gt; <a href="#">Article Search</a></div>
				<h2>Article Search</h2>
			</div>
			<p class="FloatRight SaveBackTop"><a href="javascript:void(0)" onclick="window.close()" class="btn-primary btn">Close</a></p>
		</div>
		<div class="Overflow DropDownWrapper">
			<div class="FloatLeft TableColumn" style="margin-bottom: 10px;">
				<div class="FloatLeft TableColumnSearch"><input type="search" placeholder="Content ID / Title" value="<?php echo $this->input->get('contentid'); ?>" class="SearchInput" id="search_id"></div>
				<button class="btn btn-primary" type="button" id="article_search">Search</button>
				<button class="btn btn-primary" id="clear_articlesearch">Clear Search</button>
			</div>
			<div id="example_wrapper">
				<table id="example" class="display dataTable no-footer" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>Content ID</th>
							<th>HEADLINE</th>
							<th>BREADCRUMB </th>
							<th>Image</th>
							<th>Author</th>
							<th>Created By</th>
							<th>Modified By</th>
							<th>Modified On</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody><?php echo $articles; ?></tbody>
				</table>
				<div style="margin:0  0 5px" class="dataTables_paginate paging_simple_numbers" id="example_paginate"><?php echo $pagination; ?></div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="<?php echo image_url; ?>js/jquery.datetimepicker.js"></script>
<script type="text/javascript" src="<?php echo image_url; ?>js/jquery.dataTables.js"></script>
<script>
	$(document).ready(function(e){
		$('#example').dataTable({
			"bPaginate": false,
			"ordering": false,
			"deferRender": true
		});
		$('#article_search').on('click',function(e){
			var content_id = $('#search_id').val().trim();
			if(content_id==''){
				toastr.error('please enter either content Id or title');
			}else{
				window.location.href="<?php echo base_url().folder_name?>/article_search?contentid="+content_id;
			}
		});
		$('#clear_articlesearch').on('click',function(e){
			window.location.href="<?php echo base_url().folder_name?>/article_search";
		});
		$('#search_id').on('keyup',function(e){
			if(e.which===13){
				$('#article_search').trigger('click');
			}
		});
	});
</script>