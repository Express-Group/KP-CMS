<?php
class printdesk extends CI_Controller{
	
	public function __construct(){
		parent::__construct();
		$this->load->model('admin/printdesk_model');
		$this->load->helper('form');
	}
	
	public function index(){
		if($this->input->get('action')==''){
			$details=['title'=>'Printdesk Article Manager','template'=>'printdesk_view'];
			$this->load->view('admin_template',$details);
		}
		if($this->input->get('action')=='pager'){
			$Limit = ($this->input->post('limit')=='' || $this->input->post('limit')==null)? 0 : $this->input->post('limit');
			$PerPage = $this->input->post('per_page');
			$search=false;
			$queryresult=0;
			$S=$this->input->post('query');
			$where='';
			if($this->input->post('query')!=''){ $search=true; $queryresult=1;	}
			$queryStart = "AND (";
			$queryEnd = " ) ";
			if($this->input->post('c')=='false' && $this->input->post('s')=='false' && $this->input->post('t')=='false'){
				$QueryString= " title LIKE '%".$S."%'";
			}
			if($this->input->post('c')=='true' && $this->input->post('s')=='false' && $this->input->post('t')=='false'){
				$QueryString= " desk_id='".$S."'";
			}
			if($this->input->post('c')=='true' && $this->input->post('s')=='true' && $this->input->post('t')=='false'){
				$QueryString= " desk_id='".$S."' OR section_name LIKE '%".$S."%'";
			}
			if($this->input->post('c')=='true' && $this->input->post('s')=='true' && $this->input->post('t')=='true'){
				$QueryString= " desk_id='".$S."' OR section_name LIKE '%".$S."%' OR title LIKE '%".$S."%'";
			}				
			if($this->input->post('c')=='false' && $this->input->post('s')=='true' && $this->input->post('t')=='true'){
				$QueryString= " section_name LIKE '%".$S."%' OR title LIKE '%".$S."%'";
			}
			if($this->input->post('c')=='false' && $this->input->post('s')=='false' && $this->input->post('t')=='true'){
				$QueryString= " title LIKE '%".$S."%'";
			}
			if($this->input->post('c')=='true' && $this->input->post('s')=='false' && $this->input->post('t')=='true'){
				$QueryString= " desk_id='".$S."' OR title LIKE '%".$S."%'";
			}
			if($this->input->post('c')=='false' && $this->input->post('s')=='true' && $this->input->post('t')=='false'){
				$QueryString= " section_name LIKE '%".$S."%'";
			}
			$fullQuery= $queryStart.$QueryString.$queryEnd;
			$_GET['per_page'] = $Limit;
			$TotalRows = $this->printdesk_model->GetPrintDeskArticles(1,['search'=>$search,'query'=>$fullQuery]);
			$config['base_url'] = '';
			$config['total_rows']= $TotalRows;
			$config['per_page'] = $PerPage;
			//$config['num_links'] = 5;
			$config['use_page_numbers'] = TRUE;
			$config['page_query_string'] = TRUE;
			$this->pagination->initialize($config);
			$Result = $this->printdesk_model->GetPrintDeskArticles(2,['per_page'=>$PerPage,'limit'=>$Limit,'search'=>$search,'query'=>$fullQuery]);
			$Rows = [];
			$SubRows = [];
			$Response = [];
			$j=1;
			 foreach($Result as $ResultObject):
				$Rows['contentid'] = $ResultObject->desk_id;
				$Rows['title'] = $ResultObject->title;
				$Rows['sectionname'] = $ResultObject->section_name;
				$Rows['storycode'] = $ResultObject->story_code;
				$Rows['authorname'] = $ResultObject->auth_name;
				$Rows['createdon'] = $ResultObject->created_on;
				$Rows['status'] = $ResultObject->status;
				/* if($queryresult==0){
					$GetChildNodes=$this->printdesk_model->GetChildNodes($ResultObject->story_code,$ResultObject->desk_id);
					 foreach($GetChildNodes as $ChildNodes):
						$SubRows['contentid'] = $ChildNodes->desk_id;
						$SubRows['title'] = $ChildNodes->title;
						$SubRows['sectionname'] = $ChildNodes->section_name;
						$SubRows['storycode'] = $ChildNodes->story_code;
						$SubRows['authorname'] = $ChildNodes->auth_name;
						$SubRows['createdon'] = $ChildNodes->created_on;
						$SubRows['status'] = $ChildNodes->status;
						$Rows['more'][]=$SubRows;
					endforeach;
				} */
				$Response['records'][]=$Rows;
				unset($Rows['more']);
				$j++;
				
			endforeach;
			$Response['pagination']=$this->pagination->create_links();
			$Response['searchtype']=$queryresult;
			$Response['totalRows']=$TotalRows;
			echo json_encode($Response);
		}
		if($this->input->get('action')=='subtree'){
			$GetChildNodes=$this->printdesk_model->GetChildNodes($this->input->post('story_code'),$this->input->post('desk_id'));
			$Rows['more']=[];
			foreach($GetChildNodes as $ChildNodes):
				$SubRows['contentid'] = $ChildNodes->desk_id;
				$SubRows['title'] = $ChildNodes->title;
				$SubRows['sectionname'] = $ChildNodes->section_name;
				$SubRows['storycode'] = $ChildNodes->story_code;
				$SubRows['authorname'] = $ChildNodes->auth_name;
				$SubRows['createdon'] = $ChildNodes->created_on;
				$SubRows['status'] = $ChildNodes->status;
				$Rows['more'][]=$SubRows;
			endforeach;
			echo json_encode($Rows);
			
		}
		if($this->input->get('action')=='table'){
		?>
			<table ng-style="table" id="table" class=" table table-condensed">
				<tr>
					<th ng-style="th">EXPAND</th>
					<th ng-style="th">CONTENT ID</th>
					<th ng-style="th">STORY CODE</th>
					<th ng-style="th">SECTION NAME</th>
					<th ng-style="th">TITLE</th>
					<th ng-style="th">AUTHOR NAME</th>
					<th ng-style="th">CREATED ON</th>
					<th ng-style="th">ACTION</th>
				</tr>
				<tbody  ng-repeat="details in articleDetails">
					<tr data-node="treetable-{{$index + 1}}" ng-mouseenter="getrow($index,details.storycode,details.contentid)">
						<td ng-style="td" ng-if="details.more.length > 0 && searchresult==0"><i style="cursor:pointer;" class="fa fa-plus" aria-hidden="true" ng-click="showrow($index + 1,$event)"></i></td>
						<td ng-style="td" ng-if="!details.more.length || searchresult==1 "></td><td ng-style="td">{{ details.contentid}}</td>
						<td ng-style="td">{{ details.storycode}}</td>
						<td ng-style="td">{{ details.sectionname}}</td>
						<td ng-style="td" ng-bind-html="details.title"></td>
						<td ng-style="td">{{ details.authorname}}</td>
						<td ng-style="td">{{ details.createdon}}</td>
						<td ng-style="td" ng-if="details.status==0"><md-button class="md-raised md-primary"  ng-click="move(details.contentid,$index)">post to cms</md-button></td>
						<td ng-style="td" ng-if="details.status==1"><md-button class="md-raised md-warn">Posted</md-button></td>
					</tr>
					<tr  class="treetable-{{$parent.$index +1}}" ng-repeat="subdetails in details.more" style="display:none;background:#fff;" data-show="false">
						<td ng-style="td"></td><td ng-style="td1">{{ subdetails.contentid}}</td>
						<td ng-style="td">{{ subdetails.storycode}}</td>
						<td ng-style="td1">{{ subdetails.sectionname}}</td>
						<td ng-style="td1" ng-bind-html="subdetails.title"></td>
						<td ng-style="td1">{{ subdetails.authorname}}</td>
						<td ng-style="td1">{{ subdetails.createdon}}</td>
						<td ng-style="td1" ng-if="subdetails.status==0"><md-button class="md-raised md-primary" ng-click="move(subdetails.contentid,$index)">post to cms</md-button></td>
						<td ng-style="td1" ng-if="subdetails.status==1"><md-button class="md-raised md-warn">Posted</md-button></td>
					</tr>
				</tbody>
				<tr ng-if="norecordcount == 0">
						<td ng-style="td" colspan="8"> NO RECORDS FOUND</td>
				</tr>
			</table>
			<div ng-style="pager" pagination class="ang-pager" ng-bind-html="pagination"></div>
		<?php
		}
		if($this->input->get('action')=='editpage'){
			$GetSectionDetails=$this->printdesk_model->SectionDetails(1);
		?>
			<!-- Modal -->
			<div class="modal fade" id="imagemodal" role="dialog" ng-style="{'top':'9%'}">
				<div class="modal-dialog modal-lg">
					<div class="modal-content" ng-style="{'float':'left','width':'100%'}">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title text-center">Select Article  Image</h4>
						</div>
						<div class="modal-body" ng-style="{'float':'left','width':'100%'}">
							<ul id="simpletab" class="nav nav-tabs">
								<li class="active"><span  data-toggle="tab" href="#printdesk" ng-click="actions(1)">Print Desk Image</span></li>
								<li><span data-toggle="tab" href="#localimage" ng-click="actions(2)">From Local</span></li>
								<li><span data-toggle="tab" href="#imagelibrary" ng-click="actions(3)">Image Library</span></li>
							</ul>
							<div class="tab-content">
								<div id="printdesk" class="tab-pane fade in active">
								  <div compile ng-bind-html="imagepopup | unsafe" ng-style="{'width':'100%'}" >
									<ul ng-style="{'width':'100%','float':'left'}">
										
									</ul>
								  </div>
								</div>
								<div id="localimage" class="tab-pane fade">
									<div class="form-group" ng-style="{'width':'100%','margin-top':'10px','float':'left'}">
										<form method="post" action="" enctype="multipart/form-data" id="uploadnewimage" >
											<label ng-style="{'float':'left','width':'100%'}">Uplaod Image  <span ng-style="{'color':'red'}"> | jpg only | limit less than 3.5 MB</span></label>
											<input  ng-style="{'float':'left','width':'100%'}" type="file" name="uploadnewimg" id="uploadnewimg">
										</form>
									</div>
								</div>
								<div id="imagelibrary" class="tab-pane fade">
								  <div class="imagesplitcontainer" ng-style="{'width':'100%'}">
									<div class="form-group" ng-style="{'width':'100%','margin-top':'10px','float':'left'}">
										<input type="text"  ng-keyup="imagesearchcustom()" ng-model="imgmastersearchquery" placeholder="Search here">
									</div>
									<ul ng-style="{'width':'100%','float':'left'}">
										<li ng-repeat="mimage in moreloadedimage" ng-style="{'width':'147px','float':'left','margin-left':'12px','margin-right':'12px','margin-bottom':'10px','overflow':'hidden'}">
										<label class="JqueryOpacity" ng-style="{'cursor':'pointer'}">
										<input type="radio" name="simageloaded"  class="simageloaded" value="{{mimage.imageid}}" ng-style="{'margin-bottom':'3px','border':'none'}">
										<img src="<?php print image_url.imagelibrary_image_path ?>{{mimage.imagename}}" class=" imgs" ng-style="{'height': '95px','width':'147px'};">
										</label>
										</li>
									</ul>
									</div>
										<input type="hidden" ng-model="uploadnewimage">
								</div>
							</div>
						</div>
						<div class="modal-footer" ng-style="{'float':'left','width':'100%'}">
							<button ng-click="saveimage()" class="btn btn-default" ng-hide="printdeskimghide">Save PrintDesk Image</button>
							<button  ng-click="uploadnewimageform()"  name="submitsimage" value="submit" class="btn btn-default" ng-hide="localimghide" >submit</button>
							<button type="button" class="btn btn-default" ng-click="moreimage()" ng-hide="libmorehide" >Load More Image</button>
							<button type="button" class="btn btn-default" ng-click="moreimagesave()" ng-hide="libmorehide" >Save Library Image</button>
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</div>
			<!--end-->
			<edit ng-style="{'width':'96%','float':'left','margin':'1% 2% 0'}">
					<editpageheading ng-style="{'width':'100%','float':'left','text-align':'center','margin-top':'0.5%','font-size':'20px'}"> 
					<md-button class="md-raised md-primary" ng-style="{'float':'right','cursor':'pointer','font-size':'12px','color':'#fff'}" ng-click="publish($event)">PUBLISH</md-button>
					<!--<button ng-style="{'float':'right','background':'#fff','border':'none','padding':'9px','border-bottom':'2px solid #3c8dbc','cursor':'pointer','margin-right':'6px','font-size':'12px'}" ng-click="draft()">DRAFT</button>-->
					<md-button class="md-raised md-primary" ng-style="{'float':'right','cursor':'pointer','margin-right':'6px','font-size':'12px','color':'#fff'}" ng-click="goback($event)">GO BACK</md-button>
					</editpageheading>
					<!--left-->
					<div ng-style="{'float':'left','width':'100%'}">
					<editdetails ng-style="{'width':'49.5%','float':'left','background':'#fff','margin-top':'2%','margin-right':'0.5%','padding':'10px',' border-left':'2px solid rgb(60, 141, 188)','box-shadow':'rgba(0, 0, 0, 0.15) 2px 2px 2px'}">
						<field ng-style="{'width':'100%','float':'left','margin':'5px 0 10px'}">
							<md-input-container class="md-block">
							<label ng-style="{'width':'100%','float':'left'}">Article Page Headline</label>
							<input type="text" ng-model="etitle" ng-style="{'width':'100%','float':'left','margin-top':'5px','border-radius':'0'}">
							</md-input-container>
						</field>
					</editdetails>
					<!--end-->

					<!--right-->
					<editdetails ng-style="{'width':'49.5%','float':'left','background':'#fff','margin-top':'2%','margin-left':'0.5%','padding':'10px',' border-left':'2px solid rgb(60, 141, 188)','box-shadow':'rgba(0, 0, 0, 0.15) 2px 2px 2px'}">
						<field ng-style="{'width':'100%','float':'left','margin':'5px 0 10px'}">
						<md-input-container class="md-block">
							<label ng-style="{'width':'100%','float':'left'}">PrintDesk Section Name</label>
							<input type="text" ng-model="eprintsectionname" ng-style="{'width':'100%','float':'left','margin-top':'5px','border-radius':'0'}">
						</md-input-container>
						</field>
					</editdetails>
					<!--end-->
					</div>
					<div ng-style="{'float':'left','width':'100%'}">
					<!--left-->
					<editdetails ng-style="{'width':'49.5%','float':'left','background':'#fff','margin-top':'1%','margin-right':'0.5%','padding':'10px',' border-left':'2px solid rgb(60, 141, 188)','box-shadow':'rgba(0, 0, 0, 0.15) 2px 2px 2px'}">
											
						<field ng-style="{'width':'100%','float':'left','margin':'5px 0 10px'}">
							<md-input-container class="md-block">
							<label ng-style="{'width':'100%','float':'left'}">Meta title</label>
							<input type="text" ng-model="emetatitle" ng-style="{'width':'100%','float':'left','margin-top':'5px','border-radius':'0'}">
							</md-input-container>
						</field>
					</editdetails>
					<!--end-->
					
					<!--right-->
					<editdetails ng-style="{'width':'49.5%','float':'left','background':'#fff','margin-top':'1%','margin-left':'0.5%','padding':'10px',' border-left':'2px solid rgb(60, 141, 188)','box-shadow':'rgba(0, 0, 0, 0.15) 2px 2px 2px'}">
						<field ng-style="{'width':'100%','float':'left','margin':'5px 0 10px'}">
							<md-input-container class="md-block">
							<label ng-style="{'width':'100%','float':'left'}">Select Section</label>
							<md-select  ng-model="sectionname" id="sectionname" ng-style="{'width':'100%','float':'left','margin-top':'5px','border-radius':'0','border':'none'}">
								<option>Please Select Section</option>
								<?php 
									foreach($GetSectionDetails as $SectionName):
										if($SectionName->Sectionname!='Home'):
										print '<md-option  value="'.$SectionName->Section_id.'" style="color:blue;font-weight:bold;">'.$SectionName->Sectionname.'</md-option >';
											if($SectionName->Section_id!=''){
												$SubScetion=$this->printdesk_model->SectionDetails(2,$SectionName->Section_id);
												foreach($SubScetion as $SubSectionName):
													print '<md-option  value="'.$SubSectionName->Section_id.'" >&nbsp&nbsp'.$SubSectionName->Sectionname.'</md-option >';
													$SubScetionLast=$this->printdesk_model->SectionDetails(2,$SubSectionName->Section_id);
													foreach($SubScetionLast as $SubSectionNameLast):
														print '<md-option  value="'.$SubSectionNameLast->Section_id.'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp'.$SubSectionNameLast->Sectionname.'</md-option >';
													endforeach;
												endforeach;
											}
										endif;
									endforeach;
								?>
							</md-select>
							</md-input-container>
						</field>
					</editdetails>
					<!--end-->
					</div>
					<div ng-style="{'float':'left','width':'100%'}">
					<!--left-->
					<editdetails ng-style="{'width':'49.5%','float':'left','background':'#fff','margin-top':'1%','margin-right':'0.5%','padding':'10px',' border-left':'2px solid rgb(60, 141, 188)','box-shadow':'rgba(0, 0, 0, 0.15) 2px 2px 2px'}">
						<field ng-style="{'width':'100%','float':'left','margin':'5px 0 10px'}">
						<md-input-container class="md-block">
							<label ng-style="{'width':'100%','float':'left'}">Meta Description</label>
							<input type="text"  ng-model="emetadescription" ng-style="{'width':'100%','float':'left','margin-top':'5px','border-radius':'0'}">
						</md-input-container>
						</field>
					</editdetails>
					<!--end-->
					
					<!--right-->
					<editdetails ng-style="{'width':'49.5%','float':'left','background':'#fff','margin-top':'1%','margin-left':'0.5%','padding':'10px',' border-left':'2px solid rgb(60, 141, 188)','box-shadow':'rgba(0, 0, 0, 0.15) 2px 2px 2px'}">
						<field ng-style="{'width':'100%','float':'left','margin':'5px 0 10px'}">
							<label ng-style="{'width':'100%','float':'left'}">Select Image</label>
							<!--<input type="button" ng-model="selectImage" ng-style="{'width':'100%','float':'left','margin-top':'5px','border-radius':'0','border':'none','border-bottom':'2px solid #3c8dbc','background':'#eee','cursor':'pointer'}" value="Select Image" ng-click="showimage()">-->
							<md-button class="md-raised md-primary"  ng-style="{'width':'97%','float':'left','margin-top':'5px','cursor':'pointer'}" ng-click="showmodalimage()">select Image </md-button>
							<!--<md-button class="md-raised md-primary"  ng-style="{'width':'47%','float':'left','margin-top':'5px','cursor':'pointer'}" ng-click="showpreview($event)">Preview Selected Image </md-button>-->
						</field>
					</editdetails>
					<!--end-->
					</div>
					<div ng-style="{'float':'left','width':'100%'}">
					<!--left-->
					<editdetails ng-style="{'width':'100%','float':'left','background':'#fff','margin-top':'1%','padding':'10px',' border-left':'2px solid rgb(60, 141, 188)','box-shadow':'rgba(0, 0, 0, 0.15) 2px 2px 2px'}">
						<field ng-style="{'width':'100%','float':'left','margin':'5px 0 10px'}">
							<label ng-style="{'width':'100%','float':'left'}">Summary</label>
							<textarea ckeditor="options_summary" data-ck-editor  ng-model="esummary" ng-style="{'width':'100%','float':'left','margin-top':'5px','border-radius':'0','border':'none','border-bottom':'2px solid #3c8dbc'}">
							</textarea>
						</field>
					</editdetails>
					<!--end-->
					
					<!--right-->
					<editdetails ng-style="{'width':'100%','float':'left','background':'#fff','margin-top':'1%','padding':'10px',' border-left':'2px solid rgb(60, 141, 188)','box-shadow':'rgba(0, 0, 0, 0.15) 2px 2px 2px'}">
						<field ng-style="{'width':'100%','float':'left','margin':'5px 0 10px'}">
							<label ng-style="{'width':'100%','float':'left'}">Body Text</label>
							<textarea ckeditor="options" data-ck-editor type="text"  ng-model="econtent" ng-style="{'width':'100%','float':'left','margin-top':'5px','border-radius':'0','border':'none','border-bottom':'2px solid #3c8dbc','height':'100px'}">
							</textarea>
						</field>
					</editdetails>
					<!--end-->
					</div>
					
					<div ng-style="{'float':'left','width':'100%'}">
					<!--left-->
					<editdetails ng-style="{'width':'49.5%','float':'left','background':'#fff','margin-top':'1%','margin-right':'0.5%','padding':'10px',' border-left':'2px solid rgb(60, 141, 188)','box-shadow':'rgba(0, 0, 0, 0.15) 2px 2px 2px'}">
						<field ng-style="{'width':'100%','float':'left','margin':'5px 0 10px'}">
						<md-input-container class="md-block">
							<label ng-style="{'width':'100%','float':'left'}">Agency</label>
							<input type="text"  ng-model="eagency" ng-style="{'width':'100%','float':'left','margin-top':'5px','border-radius':'0'}">
						</md-input-container>
						</field>
					</editdetails>
					<!--end-->
					
					<!--right-->
					<editdetails ng-style="{'width':'49.5%','float':'left','background':'#fff','margin-top':'1%','margin-left':'0.5%','padding':'10px',' border-left':'2px solid rgb(60, 141, 188)','box-shadow':'rgba(0, 0, 0, 0.15) 2px 2px 2px'}">
						<field ng-style="{'width':'100%','float':'left','margin':'5px 0 10px'}">
						<md-input-container class="md-block">
							<label ng-style="{'width':'100%','float':'left'}">Byline</label>
							<input type="text" ng-model="ebyline" ng-style="{'width':'100%','float':'left','margin-top':'5px','border-radius':'0'}">
						</md-input-container>
						</field>
					</editdetails>
					<!--end-->
					</div>
					
					<div ng-style="{'float':'left','width':'100%'}">
					<!--left-->
					<editdetails ng-style="{'width':'49.5%','float':'left','background':'#fff','margin-top':'1%','margin-right':'0.5%','padding':'10px',' border-left':'2px solid rgb(60, 141, 188)','box-shadow':'rgba(0, 0, 0, 0.15) 2px 2px 2px'}">
						<field ng-style="{'width':'100%','float':'left','margin':'5px 0 10px'}">
							<!--<label><input type="checkbox" ng-model="comment">Allow Comments</label>
							<label><input type="checkbox" ng-model="allowpagination">Allow Pagination</label>
							<label><input type="checkbox" ng-model="noindex"> No Index</label>
							<label><input type="checkbox" ng-model="nofollow">No Follow</label>-->
						<label><md-checkbox ng-model="comment"  aria-label="Checkbox 1" class="md-primary" flex>Allow Comments</label>
						<label><md-checkbox ng-model="allowpagination"  aria-label="Checkbox 2" class="md-primary" flex>Allow Pagination</label>
						<label><md-checkbox ng-model="noindex"  aria-label="Checkbox 3"  class="md-primary" flex>No Index</label>
						<label><md-checkbox ng-model="nofollow"  aria-label="Checkbox 4" class="md-primary" flex>No Follow</label>
						</field>
					</editdetails>
					<!--end-->
					
					<!--right-->
					<editdetails ng-style="{'width':'49.5%','float':'left','background':'#fff','margin-top':'1%','margin-left':'0.5%','padding':'10px',' border-left':'2px solid rgb(60, 141, 188)','box-shadow':'rgba(0, 0, 0, 0.15) 2px 2px 2px'}">
						<field ng-style="{'width':'100%','float':'left','margin':'5px 0 10px'}">
							<md-input-container class="md-block">
							<label ng-style="{'width':'100%','float':'left'}">Tags</label>
							<input type="text" ng-model="etags" ng-list="," ng-style="{'width':'100%','float':'left','margin-top':'5px','border-radius':'0'}">
							</md-input-container>
							<tags ng-style="{'width':'100%','float':'left','margin-top':'9px'}">{{etags}}</tags>
						</field>
					</editdetails>
					<!--end-->
					</div>
					<div ng-style="{'float':'left','width':'100%'}">
						<!--left-->
						<editdetails ng-style="{'width':'100%','float':'left','background':'#fff','margin-top':'1%','padding':'10px',' border-left':'2px solid rgb(60, 141, 188)','box-shadow':'rgba(0, 0, 0, 0.15) 2px 2px 2px'}">
							<field ng-style="{'width':'100%','float':'left','margin':'5px 0 10px'}">
								<label ng-style="{'margin-left':'1%','padding-right':'9px','margin-top':'10px','float':'left'}">country</label>
								<md-select ng-model="ecountry" aria-label="countrymd" ng-style="{'width':'29%','float':'left','margin-top':'5px'}" ng-change="getstate($event)">
								 <md-option ng-value="country.countryID" ng-repeat="country in countrydata">{{country.countryName}}</md-option>
									
								</md-select>
								<label ng-style="{'margin-left':'1%','padding-right':'9px','margin-top':'10px','float':'left'}">State</label>
								<md-select   ng-model="estate" aria-label="statemd" ng-style="{'width':'29%','float':'left','margin-top':'5px'}" ng-change="getcity()">
								<md-option ng-value="states.stateID" ng-repeat="states in statedata">{{states.stateName}}</md-option>
								</md-select>
								<label ng-style="{'margin-left':'1%','padding-right':'9px','margin-top':'10px','float':'left'}">City</label>
								<md-select   ng-model="ecity" aria-label="citymd" ng-style="{'width':'29%','float':'left','margin-top':'5px'}" >
								<md-option ng-value="cities.cityID" ng-repeat="cities in citydata">{{cities.cityName}}</md-option>
								</md-select>
							</field>
						</editdetails>
					<!--end-->
					</div>
					
					
					
			</edit>
		<?php
		}
		
		if($this->input->get('action')=='editresult'){
			$ContentID=$this->input->post('contentID');
			$Result=$this->printdesk_model->ArticleEditDetails($ContentID);
			$Rows = [];
			$CountryRows = [];
			$Response = [];
			foreach($Result['print'] as $ResultData):
				$Rows['contentid'] = $ResultData->desk_id;
				$Rows['story_code'] = $ResultData->story_code;
				$Rows['title'] = $ResultData->title;
				$Rows['summary'] = $ResultData->summary;
				$Rows['content'] = $ResultData->content;
				$Rows['tags'] = $ResultData->tags;
				$Rows['image_path'] = $ResultData->image_path;
				$Rows['sectionname'] = $ResultData->section_name;
				$Rows['agency_name'] = $ResultData->agency_name;
				$Rows['authorname'] = $ResultData->auth_name;
				$Rows['approved_by'] = $ResultData->approved_by;
				$Rows['created_by'] = $ResultData->created_by;
				$Rows['createdon'] = $ResultData->created_on;
				$Rows['modified_on'] = $ResultData->modified_on;
				$Rows['status'] = $ResultData->status;
				$Response['records'][]=$Rows;
			endforeach;
			foreach($Result['country'] as $CountryData):
				$CountryRows['countryID']=$CountryData->Country_id;
				$CountryRows['countryName']=$CountryData->CountryName;
				$Response['country'][]=$CountryRows;
			endforeach;
			echo json_encode($Response);
		}
		if($this->input->get('action')=='state'){
			$CountryID=$this->input->post('CountryID');
			$Result=$this->printdesk_model->GetStateAndCityDetails($CountryID,1);
			$Rows = [];
			$Response = [];
			foreach($Result as $StateData):
				$Rows['stateID']=$StateData->State_Id;
				$Rows['stateName']=$StateData->StateName;
				$Response['state'][]=$Rows;
			endforeach;
			echo json_encode($Response);
		}
		if($this->input->get('action')=='city'){
			$StateID=$this->input->post('StateID');
			$Result=$this->printdesk_model->GetStateAndCityDetails($StateID,2);
			$Rows = [];
			$Response = [];
			foreach($Result as $CityData):
				$Rows['cityID']=$CityData->City_id;
				$Rows['cityName']=$CityData->CityName;
				$Response['city'][]=$Rows;
			endforeach;
			echo json_encode($Response);
		}
		
		if($this->input->get('action')=='imagemaster'){
			$Limit=$this->input->post('limit');
			$Query=$this->input->post('query');
			$Result=$this->printdesk_model->LoadMoreImage($Limit,$Query);
			$Rows = [];
			$Response = [];
			foreach($Result as $ImageData):
				$Rows['imagename']=$ImageData->ImagePhysicalPath;
				$Rows['imageid']=$ImageData->content_id;
				$Response['image'][]=$Rows;
			endforeach;
			$newlimit=(int)$Limit;
			$newlimit=$newlimit + 20 ;
			$Response['limit']=$newlimit;
			echo json_encode($Response);
		}
		
		if($this->input->get('action')=='imageupload'){
			$fileName=$_FILES["uploadnewimg"]["name"];
			$ImageYear=date('Y');
			$ImageMonth=date('n');
			$ImageDate=date('d');
			$folderArray=['/original','/w100X65','/w150X150','/w600X300','/w600X390'];
			$DestinationUrl=destination_base_path.imagelibrary_image_path;
			if(!is_dir($DestinationUrl.$ImageYear)){	mkdir($DestinationUrl.$ImageYear); chmod($DestinationUrl.$ImageYear , 0777); }	
			if(!is_dir($DestinationUrl.$ImageYear.'/'.$ImageMonth)){	mkdir($DestinationUrl.$ImageYear.'/'.$ImageMonth);chmod($DestinationUrl.$ImageYear.'/'.$ImageMonth , 0777);	}
			if(!is_dir($DestinationUrl.$ImageYear.'/'.$ImageMonth.'/'.$ImageDate)){ mkdir($DestinationUrl.$ImageYear.'/'.$ImageMonth.'/'.$ImageDate); chmod($DestinationUrl.$ImageYear.'/'.$ImageMonth.'/'.$ImageDate, 0777);	}
			for($i=0;$i<count($folderArray);$i++):
				if(!is_dir($DestinationUrl.$ImageYear.'/'.$ImageMonth.'/'.$ImageDate.$folderArray[$i])){
					mkdir($DestinationUrl.$ImageYear.'/'.$ImageMonth.'/'.$ImageDate.$folderArray[$i]);
					chmod($DestinationUrl.$ImageYear.'/'.$ImageMonth.'/'.$ImageDate.$folderArray[$i], 0777);
				}
			endfor;
			$ImagePath=$DestinationUrl.$ImageYear.'/'.$ImageMonth.'/'.$ImageDate.$folderArray[0].'/'.$fileName;
			if (move_uploaded_file($_FILES["uploadnewimg"]["tmp_name"], $ImagePath)){
				$imagesizes=array();
				$imagesizes[0]=[0,0];
				$imagesizes[1]=[100,65];
				$imagesizes[2]=[150,150];
				$imagesizes[3]=[600,300];
				$imagesizes[4]=[600,390];
				$this->load->library('image_lib');
				for($j=1;$j<count($folderArray);$j++){
					copy($DestinationUrl.$ImageYear.'/'.$ImageMonth.'/'.$ImageDate.$folderArray[0].'/'.$fileName,$DestinationUrl.$ImageYear.'/'.$ImageMonth.'/'.$ImageDate.$folderArray[$j].'/'.$fileName);
					chmod($DestinationUrl.$ImageYear.'/'.$ImageMonth.'/'.$ImageDate.$folderArray[$j].'/'.$fileName,0777);
					$config['image_library'] = 'gd2';
					$config['source_image'] = $DestinationUrl.$ImageYear.'/'.$ImageMonth.'/'.$ImageDate.$folderArray[$j].'/'.$fileName;
					$config['create_thumb'] = FALSE;
					$config['maintain_ratio'] = FALSE;
					$config['width']         = $imagesizes[$j][0];
					$config['height']       = $imagesizes[$j][1];
					$this->image_lib->clear();
					$this->image_lib->initialize($config);
					$this->image_lib->resize();
				}
				$return=$this->printdesk_model->InsertImage($ImageYear.'/'.$ImageMonth.'/'.$ImageDate.$folderArray[0].'/'.$fileName);
				echo json_encode($return);
			}else{
				echo 0;
			}
		}
		if($this->input->get('action')=='article_upload'){
			$DeskId=$this->input->post('desk_id');
			$Title=$this->input->post('title');
			$Summary=$this->input->post('summary');
			$content=$this->input->post('content');
			$sectionID=$this->input->post('sectionID');
			$metatitle=strip_tags($this->input->post('metatitle'));
			$metadescription=strip_tags($this->input->post('metadescription'));
			$image=$this->input->post('image');
			$CustomImage=$this->input->post('customimage');
			if($image!='' && $CustomImage==0 ){
				$imgresponse=$this->processImage($image);
			}elseif($CustomImage!=0){
				$imgresponse['id']=$CustomImage;
				$imgresponse['imagename']=$this->printdesk_model->getCustomImageName($CustomImage);
			}else{
				$imgresponse['id']=null;
				$imgresponse['imagename']='';
			}
			$agency=trim(ucwords($this->input->post('agency')));
			$byline=trim(ucwords($this->input->post('byline')));
			$tags=$this->input->post('tags');
			$createdon=$this->input->post('createdon');
			$allowcomment=($this->input->post('allowcomment')=='' || $this->input->post('allowcomment')=='true')? 1 : 0 ;
			$allowpagination=($this->input->post('allowpagination')=='' || $this->input->post('allowpagination')=='true')? 1 : 0 ;
			$noindex=($this->input->post('noindex')=='' || $this->input->post('noindex')=='false')? 0 : 1 ;
			$nofollow=($this->input->post('nofollow')=='' || $this->input->post('nofollow')=='false')? 0 : 1 ;
			$country=($this->input->post('country')=='' || $this->input->post('country')==null) ? null : $this->input->post('country');
			$state=($this->input->post('state')=='' || $this->input->post('state')==null) ? null : $this->input->post('state');
			$city=($this->input->post('city')=='' || $this->input->post('city')==null)? null : $this->input->post('city') ;
			$GetSectionDetails=$this->printdesk_model->ArticleDetails($sectionID,1);
			$GetAgencyDetails=$this->printdesk_model->ArticleDetails($agency,2);
			$GetAuthorDetails=$this->printdesk_model->ArticleDetails($byline,3,$GetSectionDetails);
			if($GetAuthorDetails['authortype']==2){ $linktocolumnlist = 1; }else{ $linktocolumnlist = 0; }
			$setTags=$this->printdesk_model->SetTags($tags);
			$CountryName=$this->printdesk_model->getCountryStateCityDetails($country ,1);
			$StateName=$this->printdesk_model->getCountryStateCityDetails($state ,2);
			$CityName=$this->printdesk_model->getCountryStateCityDetails($city ,3);
			$GetParentSectionDetails=$this->printdesk_model->ParentSectionDetails($GetSectionDetails[0]->ParentSectionID);
			$CmsTag = implode(',',$setTags['cmstag']);
			$LiveTag = implode(',',$setTags['livetag']);
			if($CmsTag == null){ $CmsTag=''; }
			if($LiveTag == null){ $LiveTag=''; }
			
			$url= RemoveSpecialCharacters($Title);
			$url= mb_strtolower(join( "-",( explode(" ",$url))));
			$url= join( "-",( explode("&nbsp;",htmlentities($url))));
			$url=strtolower($url);
			$month= date('M' , strtotime($createdon));
			$day= date('d' , strtotime($createdon));
			$year= date('Y' , strtotime($createdon));
			$FormUrl=@$GetSectionDetails[0]->URLSectionStructure;
			$FormUrl .='/'.$year.'/'.$month.'/'.$day.'/'.$url;
			
			//CMS DATA
			
			$ArticleMaster = ['url_title' => $url , 'title' => $Title , 'summaryHTML' => $Summary , 'ArticlePageContentHTML' => $content ,'publish_start_date' => $createdon , 'Tags' => $CmsTag , 'MetaTitle'=> $metatitle ,'MetaDescription' => $metadescription , 'Noindexed' => $noindex , 'Nofollow' => $nofollow , 'Allowcomments' => $allowcomment , 'allow_pagination' => $allowpagination , 'status' => 'P' , 'Createdby' => USERID , 'Createdon' => date('Y-m-d h:i:s') ,'draft_on' => '0000-00-00 00:00:00','Canonicalurl'=>'' ,'publish_end_date' => '0000-00-00 00:00:00'];
			
			$ArticleRelatedData = ['Section_id' => @$GetSectionDetails[0]->Section_id ,'Agency_ID'=>$GetAgencyDetails['agencyid'] ,'Author_ID' => $GetAuthorDetails['authorid'] ,'Country_ID' => $country ,'State_ID' =>$state ,'City_ID' => $city , 'homepageimageid' => null , 'Sectionpageimageid' => null ,'articlepageimageid' => $imgresponse['id']];
			
			$ArticleSectionMapping = ['Section_ID' => @$GetSectionDetails[0]->Section_id];
			
			//LIVE DATA
			
			$Article = ['section_id' => @$GetSectionDetails[0]->Section_id , 'section_name' => @$GetSectionDetails[0]->Sectionname ,'parent_section_id' => (@$GetSectionDetails[0]->ParentSectionID=='' || @$GetSectionDetails[0]->ParentSectionID==null )? null : @$GetSectionDetails[0]->ParentSectionID ,'parent_section_name'=>$GetParentSectionDetails['parentsectionname'],'grant_section_id' => $GetParentSectionDetails['grantparentsectionid'] ,'grant_parent_section_name'=>$GetParentSectionDetails['grantparentsectionname'],'linked_to_columnist' => $linktocolumnlist , 'publish_start_date' =>  $createdon , 'last_updated_on' => $createdon , 'title' =>  $Title , 'summary_html' => $Summary ,'article_page_content_html' => $content, 'tags' => $LiveTag ,'allow_comments' => $allowcomment ,'allow_pagination' => $allowpagination ,'agency_name' => $GetAgencyDetails['agencyname'] ,'author_name' => $GetAuthorDetails['authorname'] ,'author_image_path'=>$GetAuthorDetails['imagepath'] , 'author_image_title' =>  $GetAuthorDetails['imagecaption'] , 'author_image_alt' => $GetAuthorDetails['imagealt'] , 'country_name' => $CountryName , 'state_name' => $StateName ,'city_name' => $CityName , 'no_indexed' => $noindex , 'no_follow' => $nofollow ,'meta_Title' => $metatitle , 'meta_description' => $metadescription , 'section_promotion' => 0 , 'link_to_resource' => 0 , 'status' => 'P' ,'hits' => 0 ,'canonical_url' => '' , 'column_name' => '' ,'home_page_image_title' => '' ,'home_page_image_alt' => '' ,'section_page_image_alt' => '' ,'section_page_image_title' => '' ,'article_page_image_title' => '' , 'article_page_image_alt' => '' ,'article_page_image_path'=>$imgresponse['imagename'] ]; 
			
			$ArticleResponse = $this->printdesk_model->InsertArticleToCmsAndLive($ArticleMaster,$ArticleRelatedData,$ArticleSectionMapping,$Article,$FormUrl); 
			if($ArticleResponse==1){
				echo $this->printdesk_model->ChangePrintdeskArticleStatus($DeskId);
			}else{
				echo 0;
			}
		}
	}
	
	public function processImage($ImageName){
		$ImageYear=date('Y');
		$ImageMonth=date('n');
		$ImageDate=date('d');
		$folderArray=['/original','/w100X65','/w150X150','/w600X300','/w600X390'];
		$SourceUrl='http://printimg.newindianexpress.com/nie_image/';
		$DestinationUrl=destination_base_path.imagelibrary_image_path;
		$imagesizes=array();
		$imagesizes[0]=[0,0];
		$imagesizes[1]=[100,65];
		$imagesizes[2]=[150,150];
		$imagesizes[3]=[600,300];
		$imagesizes[4]=[600,390];
		if(!is_dir($DestinationUrl.$ImageYear)){	mkdir($DestinationUrl.$ImageYear); chmod($DestinationUrl.$ImageYear , 0777); }	
		if(!is_dir($DestinationUrl.$ImageYear.'/'.$ImageMonth)){	mkdir($DestinationUrl.$ImageYear.'/'.$ImageMonth);chmod($DestinationUrl.$ImageYear.'/'.$ImageMonth , 0777);	}
		if(!is_dir($DestinationUrl.$ImageYear.'/'.$ImageMonth.'/'.$ImageDate)){ mkdir($DestinationUrl.$ImageYear.'/'.$ImageMonth.'/'.$ImageDate); chmod($DestinationUrl.$ImageYear.'/'.$ImageMonth.'/'.$ImageDate, 0777);	}
		for($i=0;$i<count($folderArray);$i++):
			if(!is_dir($DestinationUrl.$ImageYear.'/'.$ImageMonth.'/'.$ImageDate.$folderArray[$i])){
				mkdir($DestinationUrl.$ImageYear.'/'.$ImageMonth.'/'.$ImageDate.$folderArray[$i]);
				chmod($DestinationUrl.$ImageYear.'/'.$ImageMonth.'/'.$ImageDate.$folderArray[$i], 0777);
			}
		endfor;
		$ImageNamePosition=strrpos($ImageName,'/');
		$NewImageName=substr($ImageName,$ImageNamePosition + 1);
		copy($SourceUrl.$ImageName,$DestinationUrl.$ImageYear.'/'.$ImageMonth.'/'.$ImageDate.$folderArray[0].'/'.$NewImageName);
		chmod($DestinationUrl.$ImageYear.'/'.$ImageMonth.'/'.$ImageDate.$folderArray[0].'/'.$NewImageName, 0777);
		$this->load->library('image_lib'); 
		for($j=1;$j<count($folderArray);$j++){
			copy($DestinationUrl.$ImageYear.'/'.$ImageMonth.'/'.$ImageDate.$folderArray[0].'/'.$NewImageName,$DestinationUrl.$ImageYear.'/'.$ImageMonth.'/'.$ImageDate.$folderArray[$j].'/'.$NewImageName);
			chmod($DestinationUrl.$ImageYear.'/'.$ImageMonth.'/'.$ImageDate.$folderArray[$j].'/'.$NewImageName,0777);
			$config['image_library'] = 'gd2';
			$config['source_image'] = $DestinationUrl.$ImageYear.'/'.$ImageMonth.'/'.$ImageDate.$folderArray[$j].'/'.$NewImageName;
			$config['create_thumb'] = FALSE;
			$config['maintain_ratio'] = FALSE;
			$config['width']         = $imagesizes[$j][0];
			$config['height']       = $imagesizes[$j][1];
			$this->image_lib->clear();
			$this->image_lib->initialize($config);
			$this->image_lib->resize();
		}
		$return=$this->printdesk_model->InsertImage($ImageYear.'/'.$ImageMonth.'/'.$ImageDate.$folderArray[0].'/'.$NewImageName);
		return $return;
		
	}
	
	public function getlatestarticle(){
		$DeskId=@$this->input->post('deskid');
		$result=$this->printdesk_model->articlelatest($DeskId);
		echo json_encode($result);
	}
	

}
?>