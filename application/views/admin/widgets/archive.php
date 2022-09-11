<?php
$widget_bg_color     = $content['widget_bg_color'];
$widget_custom_title = $content['widget_title'];
if($widget_custom_title==''){ $widget_custom_title ='Archive articles';}
$widget_instance_id  =  $content['widget_values']['data-widgetinstanceid'];
$main_sction_id      = "";
$is_home             = $content['is_home_page'];
$is_summary_required = $content['widget_values']['cdata-showSummary'];
$widget_section_url  = $content['widget_section_url'];
$view_mode           = $content['mode'];
$max_article         = $content['show_max_article'];
$render_mode         = $content['RenderingMode'];
$domain_name = base_url();
$model = false;
if($max_article==0){ $max_article =15; }
$yearRange =  range(2009,date('Y') - 1);
if(count($_GET) == 0){ $model = true; }
?>
<style>
.archivelist tr td:nth-of-type(2),.archivelist tr td:nth-of-type(13){ background: #eee;}
.archivelist tr td:nth-of-type(3),.archivelist tr td:nth-of-type(12){ background: #dcdbdb;}
.archivelist tr td:nth-of-type(4),.archivelist tr td:nth-of-type(10){ background: #ccc9c9;}
.archivelist tr td:nth-of-type(5),.archivelist tr td:nth-of-type(9){ background: #bdb9b9;}
.archivelist tr td:nth-of-type(6),.archivelist tr td:nth-of-type(8){ background: #afaaaa;}
.archivelist tr td:nth-of-type(7){ background: #9a9797;}
.archivelist tr td:nth-of-type(11){ background: #d2d2d2;}
.archivehead{background:#00456e;color:#fff;}
.archivelist tr td:nth-of-type(5) a, .archivelist tr td:nth-of-type(9) a,.archivelist tr td:nth-of-type(6) a,.archivelist tr td:nth-of-type(8) a,.archivelist tr td:nth-of-type(7) a{ color:#fff;}
.archive-active{background: #e0dddd;}
.archive-daylist td{text-align:center;}
.archive-article{ padding-top: 2%; border-top: 3px solid #337ab7; margin-top: 2%;}
.archive-list{width: 100%; border-bottom: 1px solid #ccc; padding-bottom: 10px;background: whitesmoke;padding: 5px;}
.archive-summary{color: #bcbcbc;padding: 3px 12px 4px;margin: 0;}
</style>
<?php if($model==true): ?>
<div class="row">
	<div class="col-md-12 col-lg-12 col-sm-12">
		<fieldset class="FieldTopic margin-bottom-10"> <legend  class="topic"><a title="Click here to view archive articles" href="<?php echo $widget_section_url ?>"><?php echo $widget_custom_title; ?></a></legend></fieldset>
		<table class="table">
			<thead><tr>
				<th class="text-center archivehead">Year</th>
				<th colspan="12" class="text-center archivehead">Months</th>
			</tr></thead>
			<tbody class="archivelist">
				<?php
					foreach($yearRange as $year):
						echo '<tr>';
						echo '<td style="font-weight:bold;color: #00456e;">'.$year.'</td>';
						echo '<td><a href="?year='.$year.'&mon=01">Jan</a></td>';
						echo '<td><a href="?year='.$year.'&mon=02">Feb</a></td>';
						echo '<td><a href="?year='.$year.'&mon=03">Mar</a></td>';
						echo '<td><a href="?year='.$year.'&mon=04">Apr</a></td>';
						echo '<td><a href="?year='.$year.'&mon=05">May</a></td>';
						echo '<td><a href="?year='.$year.'&mon=06">Jun</a></td>';
						echo '<td><a href="?year='.$year.'&mon=07">Jul</a></td>';
						echo '<td><a href="?year='.$year.'&mon=08">Aug</a></td>';
						echo '<td><a href="?year='.$year.'&mon=09">Sep</a></td>';
						echo '<td><a href="?year='.$year.'&mon=10">Oct</a></td>';
						echo '<td><a href="?year='.$year.'&mon=11">Nov</a></td>';
						echo '<td><a href="?year='.$year.'&mon=12">Dec</a></td>';
						echo '</tr>';
					endforeach;
				?>
			</tbody>
		</table>
	</div>
</div>
<?php endif; ?>
<?php if(isset($_GET['year']) && isset($_GET['mon'])  && $_GET['year']!='' && $_GET['mon']!=''):
if(isset($_GET['date']) && $_GET['date']!=''){
	$date = $_GET['date'];
}else{
	$date=1;
}
$monthName =  date("F", strtotime($_GET['year'].'-'.$_GET['mon']));
$dayCount =  cal_days_in_month(CAL_GREGORIAN, $_GET['mon'], $_GET['year']);
if(strlen($date)==1){ $datenew = '0'.$date; }else { $datenew = $date;  }
$startDate = $_GET['year'].'-'.$_GET['mon'].'-'.$datenew.' 00:00:00';
$endDate = $_GET['year'].'-'.$_GET['mon'].'-'.$datenew.' 23:59:59';
$CI = &get_instance();
$this->archive_db = $CI->load->database('archive_db',true);
$tablename = 'article_'.$_GET['year'];
if($this->archive_db->table_exists($tablename)){
	$content = true;
	$totalrows = $this->archive_db->query("SELECT content_id FROM ".$tablename." WHERE status='P' AND publish_start_date BETWEEN '".$startDate."' AND '".$endDate."' ")->num_rows();
	$row=(isset($_GET['per_page']) && $_GET['per_page'])?$_GET['per_page']:0;
	$perpage = $max_article;
	$config=['base_url'=>$widget_section_url,'total_rows'=>$totalrows,'per_page'=>$perpage,'num_links'=>5,'page_query_string'=>TRUE,'reuse_query_string'=>FALSE,'suffix'=>'&year='.@$_GET['year'].'&mon='.@$_GET['mon'].'&date='.@$date,'cur_tag_open'=>'<a class="active">','cur_tag_close'=>'</a>','use_page_numbers'=>TRUE,'first_url'=>$widget_section_url.'?year='.@$_GET['year'].'&mon='.@$_GET['mon'].'&date='.@$date,'first_link'=>FALSE,'last_link'=>FALSE];
	$this->pagination->initialize($config);
	$pagination=$this->pagination->create_links();
	$articleDetails = $this->archive_db->query("SELECT content_id,title,summary_html,url,publish_start_date,tags FROM ".$tablename." WHERE status='P' AND publish_start_date BETWEEN '".$startDate."' AND '".$endDate."' ORDER BY publish_start_date ASC LIMIT ".$row." , ".$perpage." ")->result();
	
}else{
	$content = false;
}
 ?>
<div class="row">
	<div class="col-md-12 col-lg-12 col-sm-12 margin-bottom-10">
		<fieldset class="FieldTopic margin-bottom-10"> <legend class="topic"><a href="<?php echo $widget_section_url ?>"><?php echo $widget_custom_title; ?></a></legend></fieldset>
		<table class="table table-bordered">
			<thead><tr>
				<th class="text-left archivehead" colspan="10"><?php echo $monthName; ?> <span class="text-right pull-right"><?php echo 'Year : '.$_GET['year']; ?></span></th>
			</tr></thead>
			<tbody class="archive-daylist">
			<?php
				$j=1;
				for($i=1;$i<=$dayCount;$i++){
					if($j==1){ echo '<tr>';}
					if($date==$i){
						echo '<td class="archive-active">'.$i.'</td>';
					}else{
						echo '<td><a href="?year='.$_GET['year'].'&mon='.$_GET['mon'].'&date='.$i.'">'.$i.'</a></td>';
					}
					
					if($j==10){ echo '</tr>'; $j=1;}else{ $j++; }
				
				}
				if($j==8 || $j==9 || $j==2){
					echo '</tr>';
				}
			?>
			</tbody>
		</table>
	</div>
	<div class="col-md-12 col-lg-12 col-sm-12 margin-bottom-10">
		<?php
			if($content==true){
				$baseurl = base_url();
				echo '<div class="archive-article">';
				foreach($articleDetails as $details):
					$title = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$details->title);
					$publisheddate    = date('jS F Y', strtotime($details->publish_start_date));
					$summary  = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1',$details->summary_html);
					echo '<div class="result-section archive-list">';
					echo '<h4><a href="'.$baseurl.$details->url.'"><span><i class="fa fa-angle-right" aria-hidden="true"></i></span> '.$title.'</a></h4>';
					echo '<h6 class="archive-summary">'.$summary.'</h6>';
					echo '<date style="padding-left:12px;">'.$publisheddate.'</date>';
					echo '</div>';
				endforeach;
				echo '<div class="pagina">'.$pagination.'</div>';
				echo '</div>';
				if(count($articleDetails)==0){ echo '<h4 class="text-center">No Articles Found</h4>'; }
			}else{
				echo '<h4 class="text-center">No Articles Found</h4>';
			}
		?>
	</div>
</div>
<?php endif; ?>
