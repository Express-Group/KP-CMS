		<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-sanitize.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<style>
			body{background:rgba(158, 158, 158, 0.12);}
			.textcenter{text-align:center;}.floatleft{float:left;}.width{width:100%;}
			td,th{padding:15px;border: 1px solid rgba(0, 0, 0, 0.05);}
			td{font-size: 16px;}
			.goback{color: #fff;padding: 8px;border: 1px solid rgb(0, 0, 0);border-radius: 7px;font-size: 13px;font-weight: normal;cursor: pointer;background: rgb(0, 0, 0);outline:none;}
			.pager_num{padding: 4px 10px 4px;color: #000;font-weight: normal;border: 1px solid #000;border-radius: 6px;text-decoration:none;}
			.pager strong{padding: 4px 10px 4px;color: #fff;font-weight: normal;border: 1px solid #000;border-radius: 6px;background:#000;}
			.search{float: right;padding: 11px;width: 18%;border: 1px solid rgba(0, 0, 0, 0.26);border-radius: 6px;}
		</style>
	</head>
	<body ng-app="amp" ng-controller="article" ng-style="{'margin':'0','float':'left','width':'98.5%','background':'rgba(158, 158, 158, 0.12)','margin-top':'5%'}">
		<h1 class="textcenter floatleft width"><span ng-style="goback" ng-click="goto('main')">Go Back</span> Accelerated Mobile Pages(AMP) Articles</h1>
		<input type="number" ng-keyup="search()" ng-model="searchparameter" placeholder="Enter ContentID" class="search">
		<table ng-style="{'border-collapse': 'collapse','width': '100%','margin-top':'35px','float':'left'}">
			<thead>
				<tr>
				<th>ARTICLE ID</th>
				<th>ARTICLE TITLE</th>
				<th>SECTION NAME</th>
				<th>UPDATED ON</th>
				<th>ACTION</th>
				</tr>
			</thead>
			<tbody>
				<tr ng-repeat="content in details" ng-bind-html-unsafe="content.html" ng-style="{'text-align':'center'}">
					<td>{{content.content_id}}</td>
					<td ng-bind-html="content.title"></td>
					<td>{{content.section_name}}</td>
					<td>{{content.last_updated_on}}</td>
					<td ng-if="content.amp_status==0"><button class="goback" ng-click="amp(content.content_id,$index)"><i class="fa fa-bolt" aria-hidden="true"></i> AMP</button></td>
					<td ng-if="content.amp_status==1"><span class="goback" style="border:1px solid #000;background:#fff;color:#000;"><i class="fa fa-bolt" aria-hidden="true"></i></span></td>
				</tr>
			</tbody>
		</table>
		<div pagination ng-bind-html="pagination |unsafe" ng-style="{'width':'100%','margin':'3% 0 7%','float':'left','text-align':'center'}" class="pager">
	</div>
	</body>
</html>
<script>
var amp=angular.module('amp',['ngSanitize']);
	amp.filter('unsafe', function($sce) { return $sce.trustAsHtml; });
	amp.controller('article',function($scope,$location,$window,$http){
		$scope.goback={'color':'#000','text-align':'center','float':'left','padding':'8px','border':'1px solid #000','border-radius':'7px','font-size':'13px','font-weight':'normal','margin-left':'21px','cursor':'pointer'};
		$scope.goto=function($paras){
			if($paras=='main'){
				$window.location.href ="<?php print HOMEURL.folder_name ?>";
			}
		}
		$scope.amp=function($paras,$index){
			if($paras!=''){
				var dialogbox=confirm("Are you sure want to publish the article as AMP");
				if(dialogbox==true){
					var parameter=$.param({"content_id":$paras});
					$http({
						method:'POST',
						url:'<?php print HOMEURL.folder_name ?>/amp/save',
						headers:{ 'Content-Type' : 'application/x-www-form-urlencoded'},
						data:parameter,
					}).then(function success(response){
								if(response.data==1){
									$scope.details[$index].amp_status=1;
									alert('Updated successfully');
								}else{
									alert('something went wrong.try again');
								}
					},function error(response){
								console.log(response);
					});
				}
				
			}
		}
		$scope.search=function(){
			var parameter=$.param({"per_page":10,"limit":0,"search_term":$scope.searchparameter});
			$http({
				method:'POST',
				url:'<?php print HOMEURL.folder_name ?>/amp/page',
				headers:{ 'Content-Type' : 'application/x-www-form-urlencoded'},
				data:parameter,
			}).then(function success(response){
				$scope.details=response.data.records;
				$scope.pagination=response.data.pagination;
			},function error(response){
				console.log(response);
			});
		}
		var parameter=$.param({"per_page":10,"limit":0,"search_term":$scope.searchparameter});
		$http({
			method:'POST',
			url:'<?php print HOMEURL.folder_name ?>/amp/page',
			headers:{ 'Content-Type' : 'application/x-www-form-urlencoded'},
			data:parameter,
		}).then(function success(response){
			$scope.details=response.data.records;
			$scope.pagination=response.data.pagination;
		},function error(response){
			console.log(response);
		});
	});
	amp.directive('pagination', function($http){ 
		return {
			restrict: 'A', //attribute only
			link: function(scope, elem, attr, ctrl) {
				elem.bind('click',function(e){
					e.preventDefault();
					if(e.target.href !=undefined){
						var href=e.target.href.split('?');
						href=href[1].split('=');
						href=href[1];
						var parameter=$.param({"per_page":10,"limit":href,"search_term":scope.searchparameter});
						$http({
							method:'POST',
							url:'<?php print HOMEURL.folder_name ?>/amp/page',
							data:parameter,
							headers:{ 'Content-Type' : 'application/x-www-form-urlencoded'}
						})
						.then(function success(response){
							scope.details=response.data.records;
							scope.pagination=response.data.pagination;	
							
						},function error(response){
							
						});
					}
				});
			}
		};
	});
</script>