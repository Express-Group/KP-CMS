<style>
	.article-log,.article-log-header,.article-log-body,.search,.article-log-table{width:100%;float:left;}
	.article-log{margin-top:4%;}
	.article-log-header h1{text-align:center;padding-top: 6px;font-weight: bold !important;}
	.search{margin-top:2%;}
	.article-log-table{width: 80%; margin: 0 9%;}
	.pager{width:100%;float:left;text-align: center;margin-top: 2%;}
	.pager a{padding: 3px 11px 5px;background: #3c8dbc;color: #fff;margin-right: 4px;}
	.pager a.active{padding: 3px 11px 5px;background: #fff;color: #000;margin-right: 0px;border: 1px solid #3c8dbc;}
	.button-primary{padding: 4px;background: #3c8dbc;border: none;color: #fff;width: 75%;cursor:pointer;}
	.button-up{background: #fff;color: #3c8dbc;border: 1px solid #3c8dbc;}
	.article-log-head{font-size: 21px;color: black;}
	.fields{font-weight:bold;}
	.article-log{margin-bottom:10%;}
	#image_details{margin-top:3%;}
	#other_details{margin-top:3%;}
</style>
<?php
function get_decorated_diff($old, $new){
    $from_start = strspn($old ^ $new, "\0");        
    $from_end = strspn(strrev($old) ^ strrev($new), "\0");

    $old_end = strlen($old) - $from_end;
    $new_end = strlen($new) - $from_end;

    $start = substr($new, 0, $from_start);
    $end = substr($new, $new_end);
    $new_diff = substr($new, $from_start, $new_end - $from_start);  
    $old_diff = substr($old, $from_start, $old_end - $from_start);

    $new = "$start<ins style='background-color:#ccffcc'>$new_diff</ins>$end";
    $old = "$start<del style='background-color:#ffcccc'>$old_diff</del>$end";
    return array("old"=>$old, "new"=>$new);
}
$CI = &get_instance();
?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="<?php echo image_url ?>css/admin/jquery.dataTables.css">
<script src="<?php echo image_url ?>js/jquery.dataTables.js"></script>
<div class="article-log">
	<div class="article-log-body">
		<div class="search"></div>
		<div class="article-log-table">
			<a href="<?php echo base_url().folder_name ?>/article_log" style="float: right;margin-bottom: 2%;padding: 11px;background: #3c8dbc;border: none;color: #fff;cursor: pointer;">GO BACK</a>
			<?php if($type=='view'): ?>
			<table id="articlelog" class="">
				<thead>
					<tr>
					<th colspan="2"><b class="article-log-head">Article Details</b></th>
					</tr>
					<tr>
					<th>Fields</th>
					<th>Values</th>
					</tr>
				</thead>
				<tbody><?php
			//	print_r($details[0]->log);
				$res = $details;
					$details = json_decode($details[0]->log,true);
					$details = $details['details'];
					?>
						<tr><td>ArticleID</td><td><?php echo $res[0]->article_id ?></td></tr>
						<tr><td>Artilce Log id</td><td><?php echo $res[0]->article_log_id  ?></td></tr>
						<tr><td>Created By</td><td><?php echo $CI->getusernamebyid($res[0]->created_by) ?></td></tr>
						<tr><td>Published Date</td><td><?php echo $details['PublishStartDate'] ?></td></tr>
						<tr><td>Created On</td><td><?php echo $details['createdon'] ?></td></tr>
						<tr><td>Modified On</td><td><?php echo $details['modifiedon'] ?></td></tr>
					<?php
					echo '<tr><td class="fields">Article Page Headline</td><td>'.$details['LiveArticleDetails']['title'].'</td></tr>';
					echo '<tr><td class="fields">URL Title</td><td>'.$details['UrlTitle'].'</td></tr>';
					echo '<tr><td class="fields">URL</td><td>'.$details['url'].'</td></tr>';
					echo '<tr><td class="fields">Main Section</td><td>'.$details['LiveArticleDetails']['section_name'].'</td></tr>';
					echo '<tr><td class="fields">Summary</td><td>'.$details['LiveArticleDetails']['summary_html'].'</td></tr>';
					echo '<tr><td class="fields">Body Text</td><td>'.$details['LiveArticleDetails']['article_page_content_html'].'</td></tr>';
				?></tbody>
			</table>
			
			<table id="image_details" class="">
				<thead>
					<tr>
					<th colspan="2"><b class="article-log-head">Image Details</b></th>
					</tr>
					<tr>
					<th>Fields</th>
					<th>Values</th>
					</tr>
				</thead>
				<tbody><?php
			//	print_r($details[0]->log);
					echo '<tr><td class="fields">Home Page Image</td><td>'.$details['LiveArticleDetails']['home_page_image_path'].'</td></tr>';
					echo '<tr><td class="fields">Home Page Image Caption</td><td>'.$details['LiveArticleDetails']['home_page_image_title'].'</td></tr>';
					echo '<tr><td class="fields">Home Page Image Alt</td><td>'.$details['LiveArticleDetails']['home_page_image_alt'].'</td></tr>';
					echo '<tr><td class="fields">Section Page Image</td><td>'.$details['LiveArticleDetails']['section_page_image_path'].'</td></tr>';
					echo '<tr><td class="fields">Section Page Image Caption</td><td>'.$details['LiveArticleDetails']['section_page_image_title'].'</td></tr>';
					echo '<tr><td class="fields">Section Page Image Alt</td><td>'.$details['LiveArticleDetails']['section_page_image_alt'].'</td></tr>';
					echo '<tr><td class="fields">Article Page Image </td><td>'.$details['LiveArticleDetails']['article_page_image_path'].'</td></tr>';
					echo '<tr><td class="fields">Article Page Image Caption</td><td>'.$details['LiveArticleDetails']['article_page_image_title'].'</td></tr>';
					echo '<tr><td class="fields">Article Page Image Alt</td><td>'.$details['LiveArticleDetails']['article_page_image_alt'].'</td></tr>';
					
				?></tbody>
			</table>
			
			<table id="other_details" class="">
				<thead>
					<tr>
					<th colspan="2"><b class="article-log-head">Other Details</b></th>
					</tr>
					<tr>
					<th>Fields</th>
					<th>Values</th>
					</tr>
				</thead>
				<tbody><?php
			//	print_r($details[0]->log);
					echo '<tr><td class="fields">Meta Title</td><td>'.$details['LiveArticleDetails']['meta_Title'].'</td></tr>';
					echo '<tr><td class="fields">Meta Description</td><td>'.$details['LiveArticleDetails']['meta_description'].'</td></tr>';
					echo '<tr><td class="fields">Agency</td><td>'.$details['LiveArticleDetails']['agency_name'].'</td></tr>';
					echo '<tr><td class="fields">Byline</td><td>'.$details['LiveArticleDetails']['author_name'].'</td></tr>';
					echo '<tr><td class="fields">Country</td><td>'.$details['LiveArticleDetails']['country_name'].'</td></tr>';
					echo '<tr><td class="fields">State</td><td>'.$details['LiveArticleDetails']['state_name'].'</td></tr>';
					echo '<tr><td class="fields">City</td><td>'.$details['LiveArticleDetails']['city_name'].'</td></tr>';
					echo '<tr><td class="fields">Allow Pagination</td><td>'.$details['LiveArticleDetails']['allow_pagination'].'</td></tr>';
					echo '<tr><td class="fields">Allow Comments</td><td>'.$details['LiveArticleDetails']['allow_comments'].'</td></tr>';
					echo '<tr><td class="fields">Schedule Date</td><td>'.$details['LiveArticleDetails']['scheduled_article'].'</td></tr>';
					echo '<tr><td class="fields">Tags</td><td>'.$details['LiveArticleDetails']['tags'].'</td></tr>';
					
					
				?></tbody>
			</table>
		</div>
	<?php endif; ?>
	<?php if($type=='compare'):
	$old = json_decode($details['old'][0]->log,true);
	$old = $old['details'];
	$new = json_decode($details['new'][0]->log,true);
	$new = $new['details'];
	$oldusername = $CI->getusernamebyid($details['old'][0]->created_by);
	$newusaername = $CI->getusernamebyid($details['new'][0]->created_by);
	$title = get_decorated_diff($old['LiveArticleDetails']['title'], $new['LiveArticleDetails']['title'].' ');
	$content_id = get_decorated_diff($old['LiveArticleDetails']['content_id'], $new['LiveArticleDetails']['content_id']);
	$UrlTitle = get_decorated_diff($old['UrlTitle'], $new['UrlTitle']);
	$PublishStartDate = get_decorated_diff($old['PublishStartDate'], $new['PublishStartDate']);
	$modifiedon = get_decorated_diff($old['modifiedon'], $new['modifiedon']);
	$createdon = get_decorated_diff($old['createdon'], $new['createdon']);
	$url = get_decorated_diff($old['url'], $new['url']);
	$section_name = get_decorated_diff($old['LiveArticleDetails']['section_name'], $new['LiveArticleDetails']['section_name'].' ');
	$summary_html = get_decorated_diff(htmlentities($old['LiveArticleDetails']['summary_html']), htmlentities($new['LiveArticleDetails']['summary_html']));
	$article_page_content_html = get_decorated_diff(htmlentities($old['LiveArticleDetails']['article_page_content_html']), htmlentities($new['LiveArticleDetails']['article_page_content_html']));
	$home_page_image_path = get_decorated_diff($old['LiveArticleDetails']['home_page_image_path'], $new['LiveArticleDetails']['home_page_image_path']);
	$home_page_image_title = get_decorated_diff($old['LiveArticleDetails']['home_page_image_title'], $new['LiveArticleDetails']['home_page_image_title']);
	$home_page_image_alt = get_decorated_diff($old['LiveArticleDetails']['home_page_image_alt'], $new['LiveArticleDetails']['home_page_image_alt']);
	$section_page_image_path = get_decorated_diff($old['LiveArticleDetails']['section_page_image_path'], $new['LiveArticleDetails']['section_page_image_path']);
	$section_page_image_title = get_decorated_diff($old['LiveArticleDetails']['section_page_image_title'], $new['LiveArticleDetails']['section_page_image_title']);
	$section_page_image_alt = get_decorated_diff($old['LiveArticleDetails']['section_page_image_alt'], $new['LiveArticleDetails']['section_page_image_alt']);
	$article_page_image_path = get_decorated_diff($old['LiveArticleDetails']['article_page_image_path'], $new['LiveArticleDetails']['article_page_image_path']);
	$article_page_image_title = get_decorated_diff($old['LiveArticleDetails']['article_page_image_title'], $new['LiveArticleDetails']['article_page_image_title']);
	$article_page_image_alt = get_decorated_diff($old['LiveArticleDetails']['article_page_image_alt'], $new['LiveArticleDetails']['article_page_image_alt']);
	$meta_Title = get_decorated_diff($old['LiveArticleDetails']['meta_Title'], $new['LiveArticleDetails']['meta_Title']);
	$meta_description = get_decorated_diff($old['LiveArticleDetails']['meta_description'], $new['LiveArticleDetails']['meta_description']);
	$agency_name = get_decorated_diff($old['LiveArticleDetails']['agency_name'], $new['LiveArticleDetails']['agency_name'].' ');
	$author_name = get_decorated_diff($old['LiveArticleDetails']['author_name'], $new['LiveArticleDetails']['author_name']);
	$country_name = get_decorated_diff($old['LiveArticleDetails']['country_name'], $new['LiveArticleDetails']['country_name'].' ');
	$state_name = get_decorated_diff($old['LiveArticleDetails']['state_name'], $new['LiveArticleDetails']['state_name']);
	$city_name = get_decorated_diff($old['LiveArticleDetails']['city_name'], $new['LiveArticleDetails']['city_name']);
	$allow_pagination = get_decorated_diff($old['LiveArticleDetails']['allow_pagination'], $new['LiveArticleDetails']['allow_pagination'].' ');
	$allow_comments = get_decorated_diff($old['LiveArticleDetails']['allow_comments'], $new['allow_comments']['allow_pagination']);
	$tags = get_decorated_diff($old['LiveArticleDetails']['tags'], $new['LiveArticleDetails']['tags']);
	$scheduled_article = get_decorated_diff($old['LiveArticleDetails']['scheduled_article'], $new['LiveArticleDetails']['scheduled_article']);
	?>
		<div class="container" style="float:left;">
			<div class="col-md-6 col-lg-6 table-responsive">
				<table id="articlelog" class="table table-bordered">
					<thead>
						<tr>
						<th colspan="2"><b class="article-log-head">Article Details</b></th>
						</tr>
						<tr>
						<th>Fields</th>
						<th>Values</th>
						</tr>
					</thead>
					<tbody>
						<tr><td>ArticleID</th><th><?php echo $details['old'][0]->article_id ?></td></tr>
						<tr><td>Artilce Log id</th><th><?php echo $details['old'][0]->article_log_id  ?></td></tr>
						<tr><td>Created By</th><th><?php echo $oldusername ?></td></tr>
						<tr><td>Published Date</th><th><?php echo $PublishStartDate['old'] ?></td></tr>
						<tr><td>Created On</th><th><?php echo $createdon['old'] ?></td></tr>
						<tr><td>Modified On</th><th><?php echo $modifiedon['old'] ?></td></tr>
					<?php
						echo '<tr><td class="fields">Article Page Headline</td><td>'.$title['old'].'</td></tr>';
						echo '<tr><td class="fields">URL Title</td><td>'.$UrlTitle['old'].'</td></tr>';
						echo '<tr><td class="fields">URL</td><td>'.$url['old'].'</td></tr>';
						echo '<tr><td class="fields">Main Section</td><td>'.$section_name['old'].	'</td></tr>';
						echo '<tr><td class="fields">Summary</td><td>'.$summary_html['old'].'</td></tr>';
						echo '<tr><td class="fields">Body Text</td><td>'.$article_page_content_html['old'].'</td></tr>';
						echo '<tr><td class="fields">Home Page Image</td><td>'.$home_page_image_path['old'].'</td></tr>';
						echo '<tr><td class="fields">Home Page Image Caption</td><td>'.$home_page_image_title['old'].'</td></tr>';
						echo '<tr><td class="fields">Home Page Image Alt</td><td>'.$home_page_image_alt['old'].'</td></tr>';
						echo '<tr><td class="fields">Section Page Image</td><td>'.$section_page_image_path['old'].'</td></tr>';
						echo '<tr><td class="fields">Section Page Image Caption</td><td>'.$section_page_image_title['old'].'</td></tr>';
						echo '<tr><td class="fields">Section Page Image Alt</td><td>'.$section_page_image_alt['old'].'</td></tr>';
						echo '<tr><td class="fields">Article Page Image</td><td>'.$article_page_image_path['old'].'</td></tr>';
						echo '<tr><td class="fields">Article Page Image Title</td><td>'.$article_page_image_title['old'].'</td></tr>';
						echo '<tr><td class="fields">Article Page Image Alt</td><td>'.$article_page_image_alt['old'].'</td></tr>';
						echo '<tr><td class="fields">Meta Title</td><td>'.$meta_Title['old'].'</td></tr>';
						echo '<tr><td class="fields">Meta Description</td><td>'.$meta_description['old'].'</td></tr>';
						echo '<tr><td class="fields">Agency</td><td>'.$agency_name['old'].'</td></tr>';
						echo '<tr><td class="fields">Byline</td><td>'.$author_name['old'].'</td></tr>';
						echo '<tr><td class="fields">Country</td><td>'.$country_name['old'].'</td></tr>';
						echo '<tr><td class="fields">State</td><td>'.$state_name['old'].'</td></tr>';
						echo '<tr><td class="fields">City</td><td>'.$city_name['old'].'</td></tr>';
						echo '<tr><td class="fields">Allow Pagination</td><td>'.$allow_pagination['old'].'</td></tr>';
						echo '<tr><td class="fields">Allow Comments</td><td>'.$allow_comments['old'].'</td></tr>';
						echo '<tr><td class="fields">Schedule Date</td><td>'.$scheduled_article['old'].'</td></tr>';
						echo '<tr><td class="fields">Tags</td><td>'.$tags['old'].'</td></tr>';
					?>
					</tbody>
				</table>
			
			</div>
			<div class="col-md-6 col-lg-6 table-responsive">
				<table id="articlelog1" class="table table-bordered">
					<thead>
						<tr>
						<th colspan="2"><b class="article-log-head">Article Details</b></th>
						</tr>
						<tr>
						<th>Fields</th>
						<th>Values</th>
						</tr>
					</thead>
					<tbody>
						<tr><td>ArticleID</th><th><?php echo $details['new'][0]->article_id  ?></td></tr>
						<tr><td>Artilce Log id</th><th><?php echo $details['new'][0]->article_log_id  ?></td></tr>
						<tr><td>Created By</th><th><?php echo $newusaername ?></td></tr>
						<tr><td>Published Date</th><th><?php echo $PublishStartDate['new'] ?></td></tr>
						<tr><td>Created On</th><th><?php echo $createdon['new'] ?></td></tr>
						<tr><td>Modified On</th><th><?php echo $modifiedon['new'] ?></td></tr>
					<?php
						echo '<tr><td class="fields">Article Page Headline</td><td>'.$title['new'].'</td></tr>';
						echo '<tr><td class="fields">URL Title</td><td>'.$UrlTitle['new'].'</td></tr>';
						echo '<tr><td class="fields">URL</td><td>'.$url['new'].'</td></tr>';
						echo '<tr><td class="fields">Main Section</td><td>'.$section_name['new'].	'</td></tr>';
						echo '<tr><td class="fields">Summary</td><td>'.$summary_html['new'].'</td></tr>';
						echo '<tr><td class="fields">Body Text</td><td>'.$article_page_content_html['new'].'</td></tr>';
						echo '<tr><td class="fields">Home Page Image</td><td>'.$home_page_image_path['new'].'</td></tr>';
						echo '<tr><td class="fields">Home Page Image Caption</td><td>'.$home_page_image_title['new'].'</td></tr>';
						echo '<tr><td class="fields">Home Page Image Alt</td><td>'.$home_page_image_alt['new'].'</td></tr>';
						echo '<tr><td class="fields">Section Page Image</td><td>'.$section_page_image_path['new'].'</td></tr>';
						echo '<tr><td class="fields">Section Page Image Caption</td><td>'.$section_page_image_title['new'].'</td></tr>';
						echo '<tr><td class="fields">Section Page Image Alt</td><td>'.$section_page_image_alt['new'].'</td></tr>';
						echo '<tr><td class="fields">Article Page Image</td><td>'.$article_page_image_path['new'].'</td></tr>';
						echo '<tr><td class="fields">Article Page Image Title</td><td>'.$article_page_image_title['new'].'</td></tr>';
						echo '<tr><td class="fields">Article Page Image Alt</td><td>'.$article_page_image_alt['new'].'</td></tr>';
						echo '<tr><td class="fields">Meta Title</td><td>'.$meta_Title['new'].'</td></tr>';
						echo '<tr><td class="fields">Meta Description</td><td>'.$meta_description['new'].'</td></tr>';
						echo '<tr><td class="fields">Agency</td><td>'.$agency_name['new'].'</td></tr>';
						echo '<tr><td class="fields">Byline</td><td>'.$author_name['new'].'</td></tr>';
						echo '<tr><td class="fields">Country</td><td>'.$country_name['new'].'</td></tr>';
						echo '<tr><td class="fields">State</td><td>'.$state_name['new'].'</td></tr>';
						echo '<tr><td class="fields">City</td><td>'.$city_name['new'].'</td></tr>';
						echo '<tr><td class="fields">Allow Pagination</td><td>'.$allow_pagination['new'].'</td></tr>';
						echo '<tr><td class="fields">Allow Comments</td><td>'.$allow_comments['new'].'</td></tr>';
						echo '<tr><td class="fields">Schedule Date</td><td>'.$scheduled_article['new'].'</td></tr>';
						echo '<tr><td class="fields">Tags</td><td>'.$tags['new'].'</td></tr>';
					?>
					</tbody>
				</table>
			</div>
		</div>
	<?php endif; ?>
	</div>
</div>
<script>
$("#articlelog").dataTable({"bPaginate": false, "bInfo": false ,"ordering": false});
$("#articlelog1").dataTable({"bPaginate": false, "bInfo": false ,"ordering": false});
$("#image_details").dataTable({"bPaginate": false, "bInfo": false ,"ordering": false});
$("#other_details").dataTable({"bPaginate": false, "bInfo": false ,"ordering": false});
</script>