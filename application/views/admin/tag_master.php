<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-sanitize.js"></script>
<style>.pager a{padding:9px 12px 9px;color:#fff;background:#3c8dbc;}.pager strong{padding:9px 12px 9px;color:#fff;background:rgba(60, 141, 188, 0.58);}</style>
<section ng-app="tagmaster" ng-controller="control"  ng-style="section_master">
	<h1 ng-style="tag_heading">TAGMASTER</h1>
	<div ng-style="{'width': '100%','margin-top': '2%','text-align': 'right'}">
		<span ng-style="{'color': '#4CAF50','font-size': '22px','float': 'left'}" ng-if="(search_query.length >= 1)">Search Query : {{search_query}}</span>
		<input type="text" placeholder="Serach Here" ng-model="search_query" ng-keyup="search()">
	</div>
	<table ng-style="{'margin':'2% 0 0','border-collapse':'collapse','width':'100%'}">
		<tr>
			<th ng-style="th">TAGNAME</th>
			<th ng-style="th">META TITLE</th>
			<th ng-style="th">META DESCRIPTION</th>
			<th ng-style="th">ACTION</th>
		</tr>
		<tr ng-repeat="values in result" tag_id="{{values.tag_id}}"  class="tr_{{$index}}">
			<td ng-style="th">{{values.tag_name}}</td>
			<td ng-style="th" ><input type="text"  id="meta_name_{{$index}}" value="{{values.meta_name}}"></td>
			<td ng-style="th" ><input type="text" id="meta_description_{{$index}}" value="{{values.meta_description}}"></td>
			<td ng-style="th"><button ng-style="btn" ng-click="save($event)">save</button></td>
		</tr>
	</table>
	<div pagination ng-bind-html="pagination |unsafe" ng-style="{'width':'100%','margin-top':'2%'}" class="pager">
	</div>
</section>
<script>
	var app=angular.module("tagmaster",['ngSanitize']);
	app.filter('unsafe', function($sce) { return $sce.trustAsHtml; });
	app.controller("control",function($scope,$http){
		$scope.search_query="";
		$scope.section_master={"margin":"5% 3% 6%","padding":"14px","text-align":"center","background":"rgba(238, 238, 238, 0.44)","box-shadow":"2px 2px 2px 2px rgba(0, 0, 0, 0.08)"};
		$scope.tag_heading={"color":"#3c8dbc","font-weight":"bold"};
		$scope.th={"border":"1px solid rgb(60, 141, 188)","padding":"8px"};
		$scope.btn={"border": "none","padding": "7px","color": "#fff","background": "#3c8dbc","cursor":"pointer"};
		$scope.data_metaname=0;
		var parameter=$.param({"per_page":12,"limit":0,"search_term":$scope.search_query});
		$http({
			method:'POST',
			url:'<?php print HOMEURL.folder_name ?>/tagmaster/tags',
			data:parameter,
			headers:{ 'Content-Type' : 'application/x-www-form-urlencoded'}
		}).then(function success(response){
			$scope.result=response.data.records;
			$scope.pagination=response.data.pagination;
			
		},function error(response){
		
		});
		$scope.save=function($event){
			var Class=$($event.target).parents('tr').attr('class').split('_');
			var tag_id=$($event.target).parents('tr').attr('tag_id');
			Class =Class[1];
			var meta_name=document.querySelector('#meta_name_'+Class).value;
			var meta_description=document.querySelector('#meta_description_'+Class).value;
			if(meta_name=='' || meta_description==''){
				alert('Please enter the valid details');
				return false;
			}
			var parameter=$.param({"meta_name":meta_name,"meta_description":meta_description,"tag_id":tag_id});
			$http({
				method:'POST',
				url:'<?php print HOMEURL.folder_name ?>/tagmaster/update_tags',
				data:parameter,
				headers:{ 'Content-Type' : 'application/x-www-form-urlencoded'}
			}).then(function success(response){
				if(response.data==1){
					alert('Meta Details updated successfully');
				}else{
					alert('Something went wrong.try again');
				}
				
				
			},function error(response){
		
			});

		}
		$scope.search=function(){
			var parameter=$.param({"per_page":12,"limit":0,"search_term":$scope.search_query});
			$http({
				method:'POST',
				url:'<?php print HOMEURL.folder_name ?>/tagmaster/tags',
				data:parameter,
				headers:{ 'Content-Type' : 'application/x-www-form-urlencoded'}
			}).then(function success(response){
				$scope.result=response.data.records;
				$scope.pagination=response.data.pagination;
				
			},function error(response){
		
			});
		}
	});
	app.directive('pagination', function($http){ 
		return {
			restrict: 'A', //attribute only
			link: function(scope, elem, attr, ctrl) {
				elem.bind('click',function(e){
					e.preventDefault();
					if(e.target.href !=undefined){
						var href=e.target.href.split('?');
						href=href[1].split('=');
						href=href[1];
						var parameter=$.param({"per_page":12,"limit":href,"search_term":scope.search_query});
						$http({
							method:'POST',
							url:'<?php print HOMEURL.folder_name ?>/tagmaster/tags',
							data:parameter,
							headers:{ 'Content-Type' : 'application/x-www-form-urlencoded'}
						})
						.then(function success(response){
							scope.result=response.data.records;
							scope.pagination=response.data.pagination;	
							
						},function error(response){
							
						});
					}
				});
			}
		};
	}); 
</script>