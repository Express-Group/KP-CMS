<!DOCTYPE html>
<html>
<head>
	<title>Push Notification</title>
	<link href="<?php print base_url('css/admin/bootstrap.min.css'); ?>" rel="stylesheet">
	<link href="<?php print base_url('css/admin/font-awesome.min.css'); ?>" rel="stylesheet">
	<script src="<?php print base_url('js/jquery-1.11.3.min.js'); ?>"></script>
	<script src="<?php print base_url('js/bootstrap.min.js'); ?>"></script>
	<style>
		 body{font-family:Calibri, Arial !important;}
		.header{background: #3c8dbc;height: 52px;}
		.header span{line-height:48px;color:#fff;font-size:29px;}
		.margin-header{margin-top:2%;}
		 th{background-color:#84c7ea;}
		 table-td{background-color:#cce9f6;}
		.table-td:hover{background-color:#bce2f3;}
		.well #well-heading{text-align:center;color: #3c8dbc;font-size: 23px;font-weight: bold;text-transform: uppercase}
		.well{background-color:#fff;padding:7px;}
		.toast {width:300px; height:20px;height:auto;position:absolute;left:50%; margin-left:-100px; bottom:40%;background-color: #009900;color: #fff;    font-family: Calibri;font-size: 20px;padding:10px;text-align:center;border-radius: 2px;-webkit-box-shadow: 0px 0px 24px -1px rgba(56, 56, 56, 1);    -moz-box-shadow: 0px 0px 24px -1px rgba(56, 56, 56, 1);box-shadow: 0px 0px 24px -1px rgba(56, 56, 56, 1);}
		.panel-primary>.panel-heading {background-color: #3c8dbc;border-color: #3c8dbc;}
		.pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover{background-color: #3c8dbc; border-color: #3c8dbc;}
		.fb-custom{background-color:#3b5998 !important;border-color:#3b5998 !important;}
		.tw-custom{background-color:#1dcaff !important;border-color:#3b5998 !1dcaff;}
		.fb-custom:hover{background-color:#3b5998 !important;border-color:#3b5998 !important;}
		.tw-custom:hover{background-color:#1dcaff !important;border-color:#3b5998 !1dcaff;}
		.f{z-index:11;}
		.type-active{background: #337ab7;color: #fff;padding: 10px;}
	</style>
</head>
<body>
	<div class="loader" style="display:none;width: 100%;position: fixed;top: 0;z-index: 9999999;height: 100%;background: #eee;"><span style="margin-top: 22%;float: left;margin-left: 50%;font-size: 65px;color: #221a1a;"><i class="fa fa-refresh fa-spin" aria-hidden="true"></i></span></div>
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="col-md-6 col-sm-12 header">
				<span>ENPL - CMS </span>
			</div>
			<div class="col-md-6 col-sm-12 header">
				<!--<a href="<?php site_url('niecpan/clog/logout')?>" class="is-template-version-saved"><i class="fa fa-sign-out"></i></a>-->
			</div>
		</div>
		</br>&nbsp;
		<div class="row-fluid">
			<div class="col-md-10 col-md-offset-1">
				<div class="panel panel-primary">
					<div class="panel-heading text-center">SET NOTIFICATION</div>
					<div class="panel-body">
						<div class="col-md-12 text-right" style="margin-bottom: 8px;">
							<a <?php if($this->uri->segment(6)==''){echo 'class="type-active"';} ?> style="margin-right: 3%;" href="<?php echo base_url().folder_name ?>/Push_notification/notify">Article</a><a <?php if($this->uri->segment(6)=='1'){echo 'class="type-active"';} ?> style="margin-right: 3%;" href="<?php echo base_url().folder_name ?>/Push_notification/notify/0/null/1">Gallery</a><a <?php if($this->uri->segment(6)=='2'){echo 'class="type-active"';} ?> style="margin-right: 3%;" href="<?php echo base_url().folder_name ?>/Push_notification/notify/0/null/2">Video</a>
							<input type="search" placeholder="Enter content id / title" class="SearchInput" style="width: 250px;border-radius: 0 !important;" value="<?php  if(rawurldecode($this->uri->segment(5))!='null'){ echo rawurldecode($this->uri->segment(5)); } ?>">
							<button style="margin-top: -3px;margin-left: -33px;border-radius: 0 !important;" class="btn btn-primary" id="search_notification">Search</button>
							<button  onclick="window.location.href='<?php echo base_url().folder_name ?>/Push_notification/notify'" style="margin-top: -3px;margin-left: -3px;border-radius: 0 !important;" class="btn btn-primary" id="clear_notification">Clear Search</button>
						</div>
						<table class="table table-bordered">
							<tr>
								<th>Content ID</th>
								<th>TITLE</th>
								<th>ACTION</th>
								<th>SHARE</th>
							</tr>
							<?php
							if($this->uri->segment(4)==''):
								$i=1;
							else:
								$i=$this->uri->segment(4)+1;
							endif;
								
								foreach($data as $article_row){
									$notification_status=$controller->check_notification($article_row->content_id);
									if($i%2==0):
										print '<tr class="table-td" id="'.$article_row->content_id.'">';
									else:
										print '<tr id="'.$article_row->content_id.'">';
									endif;
									print '<td>'.$article_row->content_id.'</td>';

									//$title_ulr		= str_ireplace("<p>",'',$article_row->title);
									//$title_ulr		= str_ireplace("</p>",'',$article_row->title);
									//$title_ulr = preg_replace('/<p\b[^>]*>(.*?)<\/p>/i', '', $article_row->title);
									$title_ulr = strip_tags($article_row->title);
								
									print '<td class="t-'.$article_row->content_id.'">'.$title_ulr.'</td>';
									if($notification_status==0):
										print '<td class="text-center">';
										?>
										<button class="btn btn-primary f b-<?=$article_row->content_id ?>" title="set notification" onclick="set_notification('<?=$article_row->content_id ?>');"><i class="fa fa-bell-o" aria-hidden="true"></i></button>
										<input type="hidden" class="u-<?=$article_row->content_id ?>" value="<?=$article_row->url?>">
										<input type="hidden" class="i-<?=$article_row->content_id ?>" value="<?=$article_row->article_page_image_path?>">
										<?php
										print '</td>';
									else:
										print '<td class="text-center">';
										?>
										<button class="btn btn-danger f b-<?=$article_row->content_id ?>" title="notified" onclick="set_notification('<?=$article_row->content_id ?>');" disabled><i class="fa fa-lock" aria-hidden="true"></i></button>
										<input type="hidden" class="u-<?=$article_row->content_id ?>" value="<?=$article_row->url?>">
										<input type="hidden" class="i-<?=$article_row->content_id ?>" value="<?=$article_row->article_page_image_path?>">
										<?php
										print '</td>';
									
									endif;
									print'<td><button value="'.$article_row->content_id.'" onclick="share(1,'.$article_row->content_id.')" class="btn btn-primary fb-custom"><span><i class="fa fa-facebook-square" aria-hidden="true"></i></span></button><button value="'.$article_row->content_id.'" onclick="share(2,'.$article_row->content_id.')" class="btn btn-primary tw-custom"><span><i class="fa fa-twitter-square" aria-hidden="true"></i></span></button><button value="'.$article_row->content_id.'" onclick="newsletter('.$article_row->content_id.')" class="btn btn-primary" style="background-color:#00E676 !important;"><span><i class="fa fa-whatsapp" aria-hidden="true"></i></span></button><button  value="'.$article_row->content_id.'" onclick="newsletter1('.$article_row->content_id.')" class="btn btn-primary" style="background-color:#00E676 !important;"><span><i class="fa fa-whatsapp" aria-hidden="true"></i> Test </span></button></td>';
									
									$i++;
									
								}
							?>
							
						</table>
						<div class="col-md-12 text-center">
							<?php print $pagination; ?>
						</div>
						
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class='toast' style='display:none'>toast</div>
	<script>
	function set_notification(content_id){
	
		var app=confirm('Are you sure want to set notification?');
		if(app==true){
			var title=$('.t-'+content_id).text();
			var a_url=$('.u-'+content_id).val();
			$.ajax({
				type:'post',
				cache:false,
                                data:{'c':content_id,'t':title,'u':a_url},
				url:'<?php print site_url()?>'+'niecpan/Push_notification/send_notification',
				success:function(result){
					console.log(result);
					if(result==1){
						$('.b-'+content_id).html('<i class="fa fa-lock" aria-hidden="true"></i>').prop('disabled',true).addClass('btn-danger').removeClass('btn-primary');
						$('.toast').html('Notification sent successfully').fadeIn(400).delay(2000).fadeOut(400);
					}else{
						$('.toast').html('Something went wrong.please try again').css('background-color','#cc3300').fadeIn(400).delay(2000).fadeOut(400);
						
					}
				}
			});
		}
		
	
	}
	
	function share(type,id){
		var url_content=$('.u-'+id).val();
		var title=$('.t-'+id).text();
		url_content='<?php print BASEURL ?>'+url_content;
		if(type==1){
			var fb_url='https://www.facebook.com/sharer/sharer.php?u='+url_content;
			var facebook = window.open(fb_url, "", "width=800,height=400");
		}else{
			$.ajax({
			type:'post',
			cache:false,
			url:'<?php print HOMEURL?>user/commonwidget/get_shorten_url',
			data:{'article_url':url_content},
			dataType : 'json',
			success:function(result){
				console.log(result.id);
				var fb_url='https://twitter.com/intent/tweet?original_referer='+result.id+'&text='+title+'&url='+result.id+'&via=NewIndianXpress';
				window.open(fb_url, "", "width=800,height=400");
			}
			});
		}	
		
	}
	function newsletter(id){
		var url_content=$('.u-'+id).val();
		var title=$('.t-'+id).text();
		var img=$('.i-'+id).val();
		var im = "<?php echo image_url. imagelibrary_image_path ?>logo/nie_logo_600X390.jpg";
		if(img!=''){
			im = "<?php echo image_url. imagelibrary_image_path ?>"+img;
		}
		var p = prompt('Title',title);
		if(p!=null && p!=''){
			url_content  =p +"\r\n"+"<?php echo BASEURL ?>"+url_content+'?frm=wn';
			//url_content  ="<?php echo BASEURL ?>"+url_content+'?frm=wn';
			$(".loader").find('span').hide();
			$(".loader").css('width','0').show(300, function(){
				$(".loader").animate({width: '100%'},'slow',function(){
					$(".loader").find('span').show();
				});
			});
			$.ajax({
				type:'POST',
				cache:false,
				data:{'apikey' : 'b854bbba86b7581379117c3d57cbb355_11296_11bda86d933838bbcfc3fcb92' ,'message' :url_content},
				url : 'https://rest.messengerpeople.com/api/v1/newsletter',
				dataType:'json',
				beforeSend:function(){
					
				},
				success:function(result){
					alert('success! Response code : '+result.code+' Broadcast Id : '+result.broadcast_id);
					console.log(result);
					$(".loader").find('span').hide();
					$(".loader").css('width','100%').animate({width: '0'},'slow', function(){
						$(".loader").hide();
					});
				},
				error:function(err,errcode){
					var t =err.responseJSON;
					alert('error! Response code : '+t.code+' Reason : '+t.reason);
					console.clear();
					$(".loader").find('span').hide();
					$(".loader").css('width','100%').animate({width: '0'},'slow', function(){
						$(".loader").hide();
					});
				}
			});
		}
	}
	
	function newsletter1(id){
		var url_content=$('.u-'+id).val();
		var title=$('.t-'+id).text();
		var img=$('.i-'+id).val();
		var im = "<?php echo image_url. imagelibrary_image_path ?>logo/nie_logo_600X390.jpg";
		if(img!=''){
			im = "<?php echo image_url. imagelibrary_image_path ?>"+img;
		}
		im ='';
		var p = prompt('Title',title);
		if(p!=null && p!=''){
			url_content  =p +"\r\n"+"<?php echo BASEURL ?>"+url_content+'?frm=wn';
			//url_content  ="<?php echo BASEURL ?>"+url_content+'?frm=wn';
			$(".loader").find('span').hide();
			$(".loader").css('width','0').show(300, function(){
				$(".loader").animate({width: '100%'},'slow',function(){
					$(".loader").find('span').show();
				});
			});
			$.ajax({
				type:'POST',
				cache:false,
				data:{'apikey' : 'b854bbba86b7581379117c3d57cbb355_11296_11bda86d933838bbcfc3fcb92' ,'message' :url_content,'attachment' :  im , 'targeting_id':5982},
				url : 'https://rest.messengerpeople.com/api/v1/newsletter',
				dataType:'json',
				beforeSend:function(){
					
				},
				success:function(result){
					alert('success! Response code : '+result.code+' Broadcast Id : '+result.broadcast_id);
					console.log(result);
					$(".loader").find('span').hide();
					$(".loader").css('width','100%').animate({width: '0'},'slow', function(){
						$(".loader").hide();
					});
				},
				error:function(err,errcode){
					var t =err.responseJSON;
					console.log(err);
					alert('error! Response code : '+t.code+' Reason : '+t.reason);
					//console.clear();
					console.log(t);
					$(".loader").find('span').hide();
					$(".loader").css('width','100%').animate({width: '0'},'slow', function(){
						$(".loader").hide();
					});
				}
			});
		}
	}
	$(document).ready(function(){
		$('#search_notification').on('click' , function(){
			var query = $('input[type="search"]').val();
			if(query!='' && query!=undefined){
				window.location.href="<?php echo base_url().folder_name ?>/Push_notification/notify/0/"+query+'/<?php echo $this->uri->segment(6) ?>';
			}else{
				alert('Enter valid title/content id');
			}
			
		});
		$('input[type="search"]').on('keyup',function(e){
			if(e.which===13){
				$('#search_notification').click();
			}
		});
	});
	</script>
</body>
</html>
