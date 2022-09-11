
<script type="text/javascript" src="<?php print HOMEURL ?>js/jquery.dataTables.js"></script>
<script type="text/javascript" src="<?php print HOMEURL ?>js/w2ui-fields-1.0.min.js"></script>
<link href="<?php print HOMEURL ?>css/admin/jquery.dataTables.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="<?php print HOMEURL ?>css/admin/w2ui-fields-1.0.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<style>
.BodyHeadBg{position:absolute !important;margin-bottom:5%;margin-left: 7%;float: left;width: 87%;}
.del,.del:hover,.del:active,.del:focus{background: #F44336!important;border-color:#F44336!important;}
.warn,.warn:hover,.warn:active,.warn:focus{background: #FFC107!important;border-color:#FFC107!important;}
.success,.success:hover,.success:active,.success:focus{background: #4CAF50!important;border-color:#4CAF50!important;}
#livenow_paginate a{background: #eee;cursor: pointer;padding: 2px 7px;color: #fff;border-radius: 5px;margin-right: 2px;border: 1px solid #ccc;color: #ccc;}
#livenow_paginate {margin-top: 8px;}
#livenow_paginate a:hover, #livenow_paginate a.current{background: #158bb7;color: #fff;border-radius: 5px;border: 1px solid #1daede;}
.modal{top:45px;}
.form-control{border: 1px solid #bbb !important;}
label{font-weight:700 !important;}
.form-group{width: 86%;margin-left: 7%;}
.add_article_title{text-align:center;font-weight:700;}
tr{text-align:center;}
td{text-align:center;}
#cke_1_contents{height:94px !important;}
#cke_add_content{width:100% !important;}
</style>
<div class="BodyHeadBg Overflow clear" style="margin-left:7%;">
	<div class="FloatLeft BreadCrumbsWrapper ">
		<div class="breadcrumbs">Dashboard &gt; Livenow</div>
 		<h2>Livenow</h2>
	</div>
	<div class="FloatRight SaveBackTop PubUnpublish"><button id="clearsearch" class="btn btn-primary">clear search</button></div>
	<div class="FloatRight SaveBackTop PubUnpublish"><button id="searchlivenow" class="btn btn-primary">search</button></div>
	<div class="FloatRight SaveBackTop PubUnpublish"><input placeholder=" Content Id / Title" id="searchid" style="height: 39px;width: 200px;" class="SearchInput" type="text"></div>
	<p class="FloatRight SaveBackTop remoda1-bg"><!--<a href="<?php print HOMEURL.'niecpan/livenow/add' ?>" type="button" class="btn-primary btn"><i class="fa fa-plus-circle"></i>Add New</a>--></p>
	<div class="container-fluid" style="margin-top:8%;">
	<div class="row-fluid">
		<div class="col-md-12">
			<table class="livenow" id="livenow">
				 <thead>
            <tr>
                <th>ArticleID</th>
                <th>Article Title</th>
                <th>Add</th>
                <th>Edit & Delete</th>
            </tr>
        </thead>
			</table>
		</div>
	</div>
</div>		             
</div>
 <!--ADD  LIVE NEWS -->
  <div class="modal fade" id="add-livenew" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" style="text-align:center;">ADD Livenow Content</h4>
        </div>
        <div class="modal-body">
			<div class="form-group add_article_title">
				
			</div>
			<div class="form-group">
				<label>Title</label>
				<input type="hidden" id="add_article_id" value="0">
				<input type="text" class="form-control" id="add_title">
			</div>
			<div class="form-group">
				<label>Content <sup>*</sup></label>
				<?php echo $this->fckeditor->Create(); ?> 
				<!--<textarea class="form-control ckeditor" name="add_content" id="add_content"></textarea>-->
			</div>
        </div>
        <div class="modal-footer">
		  <label style="margin-right: 3%;font-size: 14px;color: #686868;cursor:pointer;"><input id="pinto" style="float: left;margin-top: 3px !important;" type="checkbox" >Pin to top</label>
          <button type="button" class="btn btn-default success" onclick="add_new_data();"><i class="fa fa-floppy-o" aria-hidden="true"></i></button>
          <button type="button" class="btn btn-default del" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
        </div>
      </div>
    </div>
  </div>
  
  <!--end-->
  
  
  <!--EDIT  LIVE NEWS -->
  <div class="modal fade" id="edit-livenew" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" style="text-align:center;">Edit Livenow Content</h4>
        </div>
        <div class="modal-body edit_live_news_custom">
	
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default del" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i></button>
        </div>
      </div>
    </div>
  </div>
  
  <!--end-->


  <script type="text/javascript" src="<?php print HOMEURL ?>FCKeditor/fckeditor.js"></script>
<script>



		var tbl = $('#livenow').dataTable({
                 "processing": true,
				 "ajax": {
					"url": "<?php print HOMEURL ?>/niecpan/livenow/GetData",
					"type": "POST",
					'data':{'search' : $('#searchid').val() }
				 },
				  "order": [[ 0, "desc" ]],
                 "aoColumns": [
                        { mData: 'articleid' } ,
                        { mData: 'articletitle' },
                        { mData: 'add' },
                        { mData: 'edit' }
                ]
        });  
		$('#searchlivenow').on('click' ,function(e){
			$("#livenow").dataTable().fnDestroy();
			$('#livenow').dataTable({
                 "processing": true,
				 "ajax": {
					"url": "<?php print HOMEURL ?>/niecpan/livenow/GetData",
					"type": "POST",
					'data':{'search' : $('#searchid').val() }
				 },
				  "order": [[ 0, "desc" ]],
                 "aoColumns": [
                        { mData: 'articleid' } ,
                        { mData: 'articletitle' },
                        { mData: 'add' },
                        { mData: 'edit' }
                ]
			}); 
		});
		
		$('#clearsearch').on('click' ,function(e){
			$('#searchid').val('');
			$("#livenow").dataTable().fnDestroy();
			$('#livenow').dataTable({
                 "processing": true,
				 "ajax": {
					"url": "<?php print HOMEURL ?>/niecpan/livenow/GetData",
					"type": "POST",
					'data':{'search' : $('#searchid').val() }
				 },
				  "order": [[ 0, "desc" ]],
                 "aoColumns": [
                        { mData: 'articleid' } ,
                        { mData: 'articletitle' },
                        { mData: 'add' },
                        { mData: 'edit' }
                ]
			}); 
		});
		$('#searchid').on('keyup',function(e){
			if(e.which===13){
				$('#searchlivenow').click();
			}
		})



function livenow_add(article_id){
	var title=$('.art_title_'+article_id).html();
	$('.add_article_title').html(title);
	$('#add_article_id').val(article_id);
	$("#add-livenew").modal({backdrop: 'static',keyboard: false});
}

function add_new_data(){
	//var content = CKEDITOR.instances.add_content.getData();
	//var content = fckeditor.instances['add_content'].getData()
	var oEditor = FCKeditorAPI.GetInstance('add_content') ;
	var content = oEditor.GetXHTML( true );
	content=content.replace('/FCKeditor/','http://cms.kannadaprabha.com/FCKeditor/');
	var article_id=$('#add_article_id').val();
	var title=$('#add_title').val();
	if($('#pinto').is(":checked")){
		var pinto = 1;
	}else{
		var pinto = 0;
	}
	//var content=$('#add_content').val();
	if(content==''){
		toastr["error"]('Please Enter the Content');
	}else{
		$.ajax({
			type:'post',
			url:'<?php print HOMEURL.folder_name?>/livenow/addDetails',
			data:{'article_id':article_id,'title':title,'content':content, 'pinto' : pinto},
			success:function(result){
				if(result==1){
					$('#add_title').val('');
					$('#add_content').val('');
					//var oEditor = FCKeditorAPI.GetInstance('add_content') ;
					FCKeditorAPI.GetInstance('add_content').SetData(''); 
					//$("#add-livenew").modal("hide");
					toastr["success"]('Content Added Successfully');
				}
			},
			error:function(code,status){
				toastr["error"]('Connection Lost..Please Try again');
			}
		});
	}
}


function livenow_edit(article_id){
	$("#edit-livenew").modal({backdrop: 'static',keyboard: false});
	$.ajax({
			type:'post',
			url:'<?php print HOMEURL.folder_name?>/livenow/EditDetails',
			data:{'article_id':article_id},
			success:function(result){
				if(result!='EMPTY'){
					$('.edit_live_news_custom').html(result);
				}else{
					$('.edit_live_news_custom').html(result);
					toastr["warning"]('No datas found');
					$("#edit-livenew").modal("hide");
				}
			},
			error:function(code,status){
				toastr["error"]('Connection Lost..Please Try again');
			}
		});

}

function save_content(lid){
	var title=$('#edit_input'+lid).val();
	var content=$('#edit_txtarea'+lid).val();
	if(content.trim()==''){
		toastr["error"]('Please fill the content field');
	}else{
		$.ajax({
			type:'post',
			url:'<?php print HOMEURL.folder_name?>/livenow/AddEditDetails',
			data:{'title':title,'content':content,'lid':lid},
			success:function(result){
				if(result==1){
					$('#edit_input'+lid).val(title);
					$('#edit_txtarea'+lid).val(content);
					toastr["success"]('Content edited successfully');
				}
			},
			error:function(code,status){
				toastr["error"]('Connection Lost..Please Try again');
			}
		});
	}

}



function delete_content(lid){
	var check=confirm("Are you sure want to delete?");
	if(check==true){
		$('#delete_'+lid).val(0).next('button').html('DELETED').attr('disabled',true);
		toastr["success"]('Content Deleted Successfully');
	}

}

function edit_content_save(article_id){
	var total_count=$('#total_count').val();
	var Json='';
	for(var i=0;i<total_count;i++){
		var title=$('#edit_input'+i).val();
		var content=$('#edit_txtarea'+i).val();
		var date=$('#date_'+i).val();
		var status=$('#delete_'+i).val();
		if($('#pinto_'+i).is(':checked')){
			var pinto=1;
		}else{
			var pinto=0;
		}
		//Json +=title+'!-!-!'+content+'!-!-!'+date+'!-!-!'+status+'~~!';
		Json +=title+'!-!-!'+content+'!-!-!'+date+'!-!-!'+pinto+'!-!-!'+status+'~~!';
	}
	$.ajax({
		type:'post',
		url:'<?php print HOMEURL.folder_name?>/livenow/AddEditDetails',
		data:{'article_id':article_id,'content':Json},
		success:function(result){
			if(result==1){
				toastr["success"]('Content Saved Successfully');
				$("#edit-livenew").modal("hide");
			}else{
				toastr["error"]('Something went wrong..please try again');
			}
		},
		error:function(status,code){
			toastr["error"]('Connection Lost..Please Try again');
		}
	});

}

</script>