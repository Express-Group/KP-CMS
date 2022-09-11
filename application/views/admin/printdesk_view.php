<?php $PrintDeskFolderPath = image_url.'printdesk_assets/'; ?>
<link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">
<link rel="stylesheet" href="<?php print $PrintDeskFolderPath ?>bootstrap.min.css">
<!--ng-material-->
<link rel="stylesheet" href="<?php print $PrintDeskFolderPath ?>angular-material.min.css">
<!--end-->
<!--<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script>-->
<script src="<?php print $PrintDeskFolderPath ?>angular.min.js"></script>
<script src="<?php print $PrintDeskFolderPath ?>angular-route.js"></script>
<script src="<?php print $PrintDeskFolderPath ?>angular-sanitize.js"></script>
<!--ng-material-->
<script src="<?php print $PrintDeskFolderPath ?>angular-animate.min.js"></script>
<script src="<?php print $PrintDeskFolderPath ?>angular-aria.min.js"></script>
<script src="<?php print $PrintDeskFolderPath ?>angular-messages.min.js"></script>
<script src="<?php print $PrintDeskFolderPath ?>angular-material.min.js"></script>
<!--end-->
<script src="//cdn.ckeditor.com/4.6.0/full/ckeditor.js"></script>
 <script src="<?php print $PrintDeskFolderPath ?>bootstrap.min.js"></script>
<script src="<?php print $PrintDeskFolderPath ?>ng-ckeditor.min.js"></script>
<!--body{background:#eee;font-family: 'Oswald', sans-serif !important;}-->
<style>body{background:#eee;}
.ang-pager a{background:#fff;padding: 6px 10px 6px;border-bottom: 2px solid #3c8dbc;}
.ang-pager strong{background:#eee;padding: 6px 10px 6px;border-bottom: 2px solid #3c8dbc;}
#cke_editor1,#cke_editor3,#cke_editor2,#cke_editor4,.cke_browser_webkit{float: left;margin-top: 5px;}
.cke_top{background: rgba(60, 141, 188, 0.38) !important;}
.nav-tabs>li.active>span, .nav-tabs>li.active>span:focus, .nav-tabs>li.active>span:hover { color: #3c8dbc;cursor: default;   background-color: #fff;border: 1px solid #ddd; border-bottom-color: transparent;}
.nav-tabs>li>span {margin-right: 2px;line-height: 1.42857143;border: 1px solid transparent;border-radius: 4px 4px 0 0;}
.nav>li>span { position: relative;display: block;padding: 10px 15px;cursor:pointer;}
ul span { float: left;}
.simageloaded,.img-selected{ margin:0;padding:0;-webkit-appearance:none; -moz-appearance:none;  appearance:none;}
button.md-primary{background:#3c8dbc !important;color:#fff !important;}
button.md-warn{background-color:rgb(26, 128, 67) !important;color:#fff !important;}
</style>
<body-content ng-app="printdesk" ng-cloak  ng-controller="printdesk_controller" ng-style="{'width':'100%','float':'left','margin-top':'6%','background':'#eee','padding-bottom':'7%'}">
	<!--loader-image-->
		<loader ng-style="{'width':'100%','float':'left','font-size':'87pt','position':'absolute','text-align':'center','color':'#00006f'}" ng-hide="loaderfa"><md-progress-circular  md-diameter="96" ng-style="{'margin-top':'16%','left':'47%'}"></md-progress-circular></loader>
	<!--loader-image--end-->
	<!--header of the page-->
	<heading ng-style="{'width':'100%','float':'left','text-align':'center','font-size':'27px','font-weight':'bold','margin-bottom':'10px'}" ng-bind="headingtext">PRINTDESK ARTICLE MANAGER</heading>
	
	<!--status-->
	<status ng-style="{'width':'29%','float':'right','position':'absolute','right':'0','top':'9%'}"> 
		<md-input-container class="md-block">
		<input type="search" placeholder="Search here  | Default : Title" ng-style="{'width' : '67%'}"  ng-disabled="serach" ng-model="querykeyword">
		<md-button class="md-raised md-primary" ng-style="{'margin-top':'-1%'}" ng-disabled="serach" ng-click="querysearch()">Search</md-button>
		</md-input-container>
	</status>
	
	<!--search parameters-->
	<searchquery ng-hide="searchquery" ng-style="{'width' : '96%','float':'right','margin':'1% 2% 0','text-align':'right'}" >
		<!--<label ng-style="{'margin-right':'3px !important'}"><input type="checkbox" ng-model="querycontentid" id="sc_contentid" ng-click="triggersearch('sc_contentid')" > Content Id</label>-->
		<!--<label ng-style="{'margin-right':'3px !important'}"><input type="checkbox" ng-model="querysectionname" id="sc_sectionname" ng-click="triggersearch('sc_sectionname')"> Section Name</label>-->
		<!--<label><input type="checkbox" ng-model="querytitle" id="sc_title" ng-click="triggersearch('sc_title')"> Title</label>-->
		<label ng-style="{'margin-right':'40px'}"><md-checkbox ng-model="querycontentid" id="sc_contentid"  aria-label="Checkbox 5" class="md-primary" flex>Content Id</label>
		<label ng-style="{'margin-right':'40px'}"><md-checkbox ng-model="querysectionname" id="sc_sectionname"  aria-label="Checkbox 6" class="md-primary" flex>Section Name</label>
		<label><md-checkbox ng-model="querytitle" id="sc_title"  aria-label="Checkbox 7" class="md-primary" flex>Title</label>
	</searchquery>
	
	
	<!--article details table-->
	<ng-view ng-style="arrest" autoscroll="true"></ng-view>
	
	
</body-content>
<script>
	var printdesk=angular.module('printdesk',['ngRoute','ngSanitize','ngCkeditor','ngMaterial']);
	printdesk.filter('unsafe',function($sce){
		return  $sce.trustAsHtml;
	});
	printdesk.run(function($rootScope) {
		$rootScope.search = true;
		$rootScope.searchquery = false;
		$rootScope.LiveContentId=0;
		$rootScope.currentIndex=0;
		$rootScope.querykeyword='';
		$rootScope.querycontentid=false;
		$rootScope.querysectionname=false;
		$rootScope.querytitle=false;
		$rootScope.articleDetails={};
		$rootScope.norecordcount=0;
		$rootScope.pagination='';
		$rootScope.headingtext='PRINTDESK ARTICLE MANAGER';
		$rootScope.loaderfa=false;
		$rootScope.arrest={'pointer-events':'none'};
		$rootScope.searchresult=0;
	});
	printdesk.controller('printdesk_controller',function($scope,$location,$rootScope,$http,$mdDialog){
		$scope.th={'padding':'10px','background':'rgb(60, 141, 188)','text-align':'center','color':'#fff'};
		$scope.table={'margin':'1% 2% 2%','float':'left','width':'96%','border-collapse':'collapse'};
		$scope.td={'padding':'15px','text-align':'center','background':'#fff'};
		$scope.td1={'padding':'15px','text-align':'center','padding-left': '43px','background':'#fff'};
		$scope.pager={'width':'100%','float':'left','text-align':'center'};
		$scope.button={'padding' : '8px 12px 7px' , 'background' : 'rgb(60, 141, 188)' , 'border' : '1px solid rgb(60, 141, 188)' , 'cursor' : 'pointer' , 'text-transform' : 'uppercase' , 'font-size' : '10px' , 'font-weight' : 'bold' ,'color':'#fff' };
		$scope.buttoncompleted={ 'text-transform' : 'uppercase' , 'font-size' : '13px' , 'font-weight' : 'bold' ,'color':'#0f520f'};
	//	$location.path("/main");
		$scope.move=function($contentId,$index){
			$rootScope.LiveContentId=$contentId;
			$rootScope.currentIndex=$index;
			$rootScope.loaderfa=false;
			$rootScope.arrest={'pointer-events':'none'};
			$location.path("/process-article/"+$rootScope.LiveContentId+"/edit.html");
			
		}
		$scope.goback=function(ev){
			/* var c=confirm('Are you sure want to go back?');
			if(c==true){
				$location.path("/");
				$rootScope.loaderfa=false;
				$rootScope.arrest={'pointer-events':'none'};
			} */
			 var confirm = $mdDialog.confirm().title('Are you sure want to go back?').ariaLabel('backbutton').targetEvent(ev).ok('Ok').cancel('Cancel');
			$mdDialog.show(confirm).then(function() {
				$location.path("/");
				$rootScope.loaderfa=false;
				$rootScope.arrest={'pointer-events':'none'};
			}, function() {
			});
		}
		$scope.querysearch=function(){
			$rootScope.loaderfa=false;
			$rootScope.arrest={'pointer-events':'none'};
			$rootScope.querykeyword=$scope.querykeyword;
			$rootScope.querycontentid=$scope.querycontentid;
			$rootScope.querysectionname=$scope.querysectionname;
			$rootScope.querytitle=$scope.querytitle;
			var post=$.param({per_page:15,limit:0,query:$rootScope.querykeyword,c:$rootScope.querycontentid,s:$rootScope.querysectionname,t:$rootScope.querytitle});
			$http({
				url :'<?php print HOMEURL.folder_name ?>/printdesk?action=pager',
				method : 'POST',
				data : post,
				headers : { 'Content-Type' : 'application/x-www-form-urlencoded'}		
			}).then(function success(response){
				//console.log(response.data.records);
				$rootScope.articleDetails=response.data.records;
				if(angular.isUndefined(response.data.records) == true){
					$rootScope.norecordcount = 0;
				}else{
					$rootScope.norecordcount = response.data.records.length; 
				}
				$rootScope.pagination=response.data.pagination;
				$rootScope.searchresult=response.data.searchtype;
				$rootScope.loaderfa=true;
				$rootScope.arrest={'pointer-events':'visible'};
			},function failure(response){
				console.log(response);
			});
		}
		
	});
	printdesk.controller('printdesk_manager',function($scope,$http,$rootScope,$location,$mdDialog){
		$rootScope.serach=false;
		$rootScope.searchquery = false;
		$rootScope.headingtext='PRINTDESK ARTICLE MANAGER';
		var post=$.param({per_page:15,limit:0,query:$rootScope.querykeyword,c:$rootScope.querycontentid,s:$rootScope.querysectionname,t:$rootScope.querytitle});
		$http({
			url :'<?php print HOMEURL.folder_name ?>/printdesk?action=pager',
			method : 'POST',
			data : post,
			headers : { 'Content-Type' : 'application/x-www-form-urlencoded'}			
		}).then(function success(response){
			$rootScope.articleDetails=response.data.records;
			if(angular.isUndefined(response.data.records) == true){
				$rootScope.norecordcount = 0;
			}else{
				$rootScope.norecordcount = response.data.records.length; 
			}
			$rootScope.pagination=response.data.pagination;
			$rootScope.searchresult=response.data.searchtype;
			$rootScope.loaderfa=true;
			$rootScope.arrest={'pointer-events':'visible'};
		},function failure(response){
			console.log(response);
		});	
		$scope.showrow=function($index,$event){
			$('.treetable-'+$index).toggle();
			var attr=$('.treetable'+$index).attr('style');
			if($event.target.className=="fa fa-plus"){
				$event.target.className="fa fa-minus";
			}else{
				$event.target.className="fa fa-plus";
			}
			
		};
		$scope.getrow=function($index,$story_code,$desk_id){
			if($rootScope.articleDetails[$index].more == undefined && $scope.querykeyword==''){
				var inputs=$.param({story_code:$story_code,desk_id:$desk_id});
				$http({
				method:'POST',
				url:'<?php print HOMEURL.folder_name ?>/printdesk?action=subtree',
				data:inputs,
				headers:{'Content-Type' : 'application/x-www-form-urlencoded'}
				}).then(function success(response){
					if(response.data.more.length > 0 ){
						$scope.msg="'" + $rootScope.articleDetails[$index].title + "' Has Children";
						toastr.success($scope.msg);
						
					}
					$rootScope.articleDetails[$index].more=response.data.more;
				},function error(response){
					$location.path("/");
				});
			}
		}
	});
	printdesk.controller('process_article',function($scope,$http,$rootScope,$compile,$location,$routeParams,$mdDialog){
		$rootScope.serach=true;
		$rootScope.searchquery = true;
		$rootScope.headingtext='POST TO CMS';
		$scope.comment=true;
		$scope.allowpagination=true;
		$rootScope.LiveContentId=$routeParams.id;
		if($routeParams.id==0 || $routeParams.id=='' ){
			$location.path("/");
		}
		var inputs=$.param({contentID:$rootScope.LiveContentId});
		$http({
			method:'POST',
			url:'<?php print HOMEURL.folder_name ?>/printdesk?action=editresult',
			data:inputs,
			headers:{'Content-Type' : 'application/x-www-form-urlencoded'}
		}).then(function success(response){
			$scope.esummary=response.data.records[0].summary;
			$scope.econtent=response.data.records[0].content;
			$scope.printdeskid=response.data.records[0].desk_id;
			$scope.etitle=response.data.records[0].title;
			$scope.eprintsectionname=response.data.records[0].sectionname;
			$scope.emetatitle=response.data.records[0].title;
			$scope.emetadescription=response.data.records[0].title;
			$scope.eagency=response.data.records[0].agency_name;
			$scope.ebyline=response.data.records[0].authorname;
			$scope.etags=response.data.records[0].tags;
			$scope.hiddenimage=response.data.records[0].image_path;
			$scope.createdon=response.data.records[0].createdon;
			$scope.uploadnewimage=0;
			$scope.countrydata=response.data.country;
			$rootScope.loaderfa=true;
			$rootScope.arrest={'pointer-events':'visible'};
			$scope.esummary=response.data.records[0].summary;
			$scope.econtent=response.data.records[0].content;
			$scope.ssimage=0;
			if($scope.hiddenimage=='' || $scope.hiddenimage==undefined){
				$scope.imagepopup='<h4>IMAGE NOT FOUND</h4>';
				$scope.printdeskhasimage=false;
			}else{
				var i,template;
				template='';
				$scope.printdeskhasimage=true;
				var imageCount=$scope.hiddenimage.split('~');
				for(i=0;i<imageCount.length;i++){
					$ImageCustomUrl='http://printimg.newindianexpress.com/nie_image/';
					if(imageCount[i]!=''){
						template +='<li  style="width:147px;float:left;margin-left:12px;margin-right:12px;margin-bottom:10px;overflow:hidden;">';
						template +='<label class="JqueryOpacity" style="cursor:pointer;">';
						template +='<input type="radio" name="img_selected"  class="img-selected" value="'+imageCount[i]+'" style="margin-bottom:3px;border:none;">';
						template +='<img src="'+$ImageCustomUrl+imageCount[i]+'" class=" imgs" ng-style="height:95px;width:147px;">';
						template +='</label>';
						template +='</li>';
					}
				}
				$scope.imagepopup=template;
			}
			$scope.sectionname=	'Please Select Section';
			$('#sectionname md-option').each(function(index){
				var $option = $(this).text().trim().toLowerCase();
				var $sectionName = response.data.records[0].sectionname.toLowerCase().trim();
				if($sectionName==$option){
					$scope.sectionname = $(this).attr('value');
					$(this).attr('selected',true);
				}
			});
			if(response.data.records[0].status==1){
				toastr.error("ARTICLE INVALID (OR) ALREADY SUBMITED");
				$rootScope.loaderfa=false;
				$rootScope.arrest={'pointer-events':'none'};
				$location.path("/");
			}
		},function error(response){
			$location.path("/");
		});
		//alert($rootScope.LiveContentId);
		//alert($rootScope.currentIndex);
		$scope.options = {
			language: 'en',
			allowedContent: true,
			entities: false,
			height :220
		};
		
		$scope.options_summary = {
			language: 'en',
			allowedContent: true,
			entities: false,
			height :80
		};
		$scope.getstate=function($event){
			var inputs=$.param({CountryID:$scope.ecountry});
			$http({
				method:'POST',
				url:'<?php print HOMEURL.folder_name ?>/printdesk?action=state',
				data:inputs,
				headers:{'Content-Type' : 'application/x-www-form-urlencoded'}
			}).then(function success(response){
				$scope.statedata=response.data.state;
			},function error(response){
				console.log(response);
			});
			
		}
		$scope.getcity=function($event){
			var inputs=$.param({StateID:$scope.estate});
			$http({
				method:'POST',
				url:'<?php print HOMEURL.folder_name ?>/printdesk?action=city',
				data:inputs,
				headers:{'Content-Type' : 'application/x-www-form-urlencoded'}
			}).then(function success(response){
				$scope.citydata=response.data.city;
			},function error(response){
				console.log(response);
			});
			
		}
		$scope.showmodalimage=function(){
			$scope.localimghide=true;
			$scope.libmorehide=true;
			$scope.printdeskimghide=false;
			if($scope.printdeskhasimage==false){ $scope.printdeskimghide=true;	}
			$('#simpletab span:first').tab('show');
			$('#imagemodal').modal();	
			$scope.actions(1);
		}
		$scope.moreimage=function(){
			var inputs=$.param({limit:$scope.uploadnewimage,query:$scope.imgmastersearchquery});
			$http({
				method:'POST',
				url:'<?php print HOMEURL.folder_name ?>/printdesk?action=imagemaster',
				data:inputs,
				headers:{'Content-Type' : 'application/x-www-form-urlencoded'}
			}).then(function success(response){
				$scope.moreloadedimage=response.data.image;
				$scope.uploadnewimage=response.data.limit; 
			},function error(response){
				console.log(response);
			});
		}
		$scope.uploadnewimageform=function(){
			var img=$('#uploadnewimg').val();
			if(img=='undefined' || img==''){
				toastr.error("Please Select a Valid Image");
			}else{
				$imglastindex=img.lastIndexOf(".");
				$CheckName=img.substr($imglastindex + 1).toLowerCase();
				$size= $( '#uploadnewimg' )[0].files[0].size;
				if(($CheckName=='jpg' || $CheckName=='jpeg') && $size < 3471248){
					var formData = new FormData();
					formData.append( 'uploadnewimg', $( '#uploadnewimg' )[0].files[0] );
					console.log(formData);
					$.ajax({
						type:'POST',
						url:'<?php print HOMEURL.folder_name ?>/printdesk?action=imageupload',
						data:formData,
						cache:false,
						contentType: false,
						processData: false,
						dataType: 'json',
						success:function(data){
							if(data!=0){
								$scope.ssimage=data.id;
								toastr.success("Image uploaded successfully and Selected Default");
								$('#imagemodal').modal("hide");
							}else{
								toastr.error("Something Went Wrong Try Again");
							}
						},
						error: function(data){
							console.log("error");
							console.log(data);
						}
					});
				}else{
					
					toastr.error("Please Select a Valid Image (or) Upload image lesser than 3.5MB");
				}
			}
		}
		$scope.moreimagesave=function(){
			$('.simageloaded').each(function(){
				if($(this).is(":checked")){
					$scope.ssimage=$(this).val();
				}
			});
			if($scope.ssimage==0){
				toastr.error("Image not selected");
			}else{
				toastr.success("Image Selected Successfully");
				$('#imagemodal').modal("hide");
				
			}
			
		}
		$scope.saveimage=function(){
			$scope.selimage='';
			$('.img-selected').each(function(){
				if($(this).is(":checked")){
					$scope.selimage=$(this).val();
				}
			});
			if($scope.selimage==''){
				toastr.warning("Image not selected");
			}else{
				var checkimage=$scope.selimage.lastIndexOf('.');
				var imgextension=$scope.selimage.substr(checkimage + 1).toLowerCase();
				if(imgextension!='jpg'){
					toastr.warning("Select Only jpg image");
					$scope.selimage='';
				}else{
					$('#imagemodal').modal("hide");
					toastr.success("Image Selected Successfully");
				}
			}
		}
		$scope.imagesearchcustom=function(){
			$scope.uploadnewimage=0;
			var inputs=$.param({limit:$scope.uploadnewimage,query:$scope.imgmastersearchquery});
				$http({
					method:'POST',
					url:'<?php print HOMEURL.folder_name ?>/printdesk?action=imagemaster',
					data:inputs,
					headers:{'Content-Type' : 'application/x-www-form-urlencoded'}
				}).then(function success(response){
					$scope.moreloadedimage=response.data.image;
					$scope.uploadnewimage=response.data.limit;
					$('#imagemodal').modal();
				},function error(response){
					console.log(response);
				});
		}
		$scope.actions=function($param){
			if($param==1){
				$scope.localimghide=true;
				$scope.libmorehide=true;
				$scope.printdeskimghide=false;
				if($scope.printdeskhasimage==false){ $scope.printdeskimghide=true;	}
			}
			if($param==2){
				$scope.localimghide=false;
				$scope.libmorehide=true;
				$scope.printdeskimghide=true;
			}
			if($param==3){
				$scope.localimghide=true;
				$scope.libmorehide=false;
				$scope.printdeskimghide=true;
				var inputs=$.param({limit:$scope.uploadnewimage,query:$scope.imgmastersearchquery});
				$http({
					method:'POST',
					url:'<?php print HOMEURL.folder_name ?>/printdesk?action=imagemaster',
					data:inputs,
					headers:{'Content-Type' : 'application/x-www-form-urlencoded'}
				}).then(function success(response){
					$scope.moreloadedimage=response.data.image;
					$scope.uploadnewimage=response.data.limit;
					$('#imagemodal').modal();
				},function error(response){
					console.log(response);
				});
			}
		}
		$scope.selectimg=function($ev){
			console.log($ev);
		}
		$scope.publish=function(ev){
			$scope.count=0;
			if($scope.etitle== ''){	$scope.count++;	toastr.error("Enter valid article page headline");	}
			if($scope.emetatitle== ''){	$scope.count++;	toastr.error("Enter valid Metatitle");	}
			if($scope.sectionname == undefined || $scope.sectionname == '' || $scope.sectionname == 'Please Select Section' ){	$scope.count++;	toastr.error("Select a  valid section name");	}
			if($scope.esummary== null){ $scope.esummary='';}
			if($scope.econtent== null){ $scope.econtent='';}
			var unique_content=$scope.econtent.replace(/\&nbsp;/g, '');
			unique_content=$(unique_content).text().trim();
			var unique_summary=$scope.esummary.replace(/\&nbsp;/g, '');
			unique_summary=$(unique_summary).text().trim();
			if($scope.esummary== ' ' || $scope.esummary== null || unique_summary=='' || unique_summary==null || unique_summary==undefined){	$scope.count++;	toastr.error("Enter valid Summary");	}
			if($scope.econtent== ' ' || $scope.econtent== null || unique_content=='' || unique_content==null || unique_content==undefined){	$scope.count++;	toastr.error("Enter valid Content");	}
			 if($scope.count==0){
				$scope.confirmaction=0;
				var confirm = $mdDialog.confirm().title('Are you sure want to go Publish the article?').ariaLabel('backbutton').targetEvent(ev).ok('Ok').cancel('Cancel');
				$mdDialog.show(confirm).then(function() {
					$rootScope.loaderfa=false;
					$rootScope.arrest={'pointer-events':'none'};
					inputs=$.param({ title : $scope.etitle, summary : $scope.esummary , content : $scope.econtent ,sectionID : $scope.sectionname, metatitle : $scope.emetatitle , metadescription : $scope.emetadescription , image : $scope.selimage ,agency : $scope.eagency , byline : $scope.ebyline , tags : $scope.etags , allowcomment : $scope.comment ,allowpagination : $scope.allowpagination, noindex : $scope.noindex ,nofollow : $scope.nofollow , country : $scope.ecountry , state : $scope.estate, city : $scope.ecity ,createdon :$scope.createdon ,desk_id : $rootScope.LiveContentId ,customimage : $scope.ssimage });
					$http({
						method:'POST',
						url: '<?php print HOMEURL.folder_name ?>/printdesk?action=article_upload',
						data:inputs,
						headers :{ 'Content-Type' : 'application/x-www-form-urlencoded'}
					}).then(function success(response){
					console.log(response.data);
						if(response.data==1){
							toastr.success("Article Updated Successfully");
							$rootScope.loaderfa=false;
							$rootScope.arrest={'pointer-events':'none'};
							$location.path('/');
						}else if(response.data=='CMSERROR'){
							toastr.error("Article not Updated");
							$rootScope.loaderfa=true;
							$rootScope.arrest={'pointer-events':'visible'};
						}else{
							toastr.error("Something went wrong.please try again");
							$rootScope.loaderfa=true;
							$rootScope.arrest={'pointer-events':'visible'};
						}
					
					},function error(response){
					
					});
				}, function() {
					$scope.confirmaction=0;
				});

			}  
		}
	});
	printdesk.config(function($routeProvider){
			$routeProvider.when('/' ,{
				templateUrl:'<?php print HOMEURL.folder_name ?>/printdesk?action=table',
				controller:'printdesk_manager'
			}).when('/process-article/:id/edit.html',{
				templateUrl:'<?php print HOMEURL.folder_name ?>/printdesk?action=editpage',
				controller:"process_article"
			
			}).otherwise({
				template: '<h5 style="float: left;width: 100%;text-align: center;margin-top: 6%;font-size: 51px;color: red;font-weight: bold !important;">PAGE NOT FOUND</h5>',
			});
	});
	
	printdesk.directive("pagination",function($http,$rootScope){
		return{
			restrict:'A',
			link:function(scope,element,attr,ctrl){
				element.bind("click",function(e){
					e.preventDefault();
					if(e.target.href!=undefined){
						$rootScope.loaderfa=false;
						$rootScope.arrest={'pointer-events':'none'};
						window.scrollTo(0, 0);
						var limit = e.target.href.split('?');
						limit=limit[1].split('=');
						limit=limit[1];
						var post=$.param({per_page:15,limit:limit,query:$rootScope.querykeyword,c:$rootScope.querycontentid,s:$rootScope.querysectionname,t:$rootScope.querytitle});
						$http({
							url :'<?php print HOMEURL.folder_name ?>/printdesk?action=pager',
							method : 'POST',
							data : post,
							headers : { 'Content-Type' : 'application/x-www-form-urlencoded'}			
						}).then(function success(response){
							$rootScope.articleDetails=response.data.records;
							$rootScope.pagination=response.data.pagination;
							$rootScope.searchresult=response.data.searchtype;
							$rootScope.loaderfa=true;
							$rootScope.arrest={'pointer-events':'visible'};
						},function failure(response){
								console.log(response);
						});	
					}
				});
			
			}
		
		}
	
	});

	$(document).ready(function(){
		$(document).on('click','.JqueryOpacity',function(){
			$('.imgs').css('opacity','1');
			$(this).find('.imgs').css('opacity','0.4');
		});
	})
	


	
</script>