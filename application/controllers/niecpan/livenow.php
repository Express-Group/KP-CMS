<?php
class livenow extends CI_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->helper(array('url','form'));
		$this->load->model('admin/livenow_model');
		session_start();
	}
	public function index(){
		$Template['template']='live_now';
		$fckeditorConfig = array(
		'instanceName' => 'add_content',
		'BasePath' => base_url().'FCKeditor/',
		'ToolbarSet' => 'custom',
		'Width' => '100%',
		'Height' => '200',
		'Value' => ''
		);
		$this->load->library('fckeditor', $fckeditorConfig);
		$this->load->view('admin_template',$Template);
	}
	
	public function GetData(){
		$Data=$this->livenow_model->GetArticleData(161 , $this->input->post('search'));
		$Content=array();
		$Fullcontent=array();
		foreach($Data as $Datavalue):
			$Article_title='<span class="art_title_'.$Datavalue->content_id.'">'.$Datavalue->title.'</span>';
			$Add='<button class="btn btn-primary" onclick="livenow_add('.$Datavalue->content_id.')"><i class="fa fa-plus-square" aria-hidden="true"></i></button>';
			$Edit='<button class="btn btn-primary warn" onclick="livenow_edit('.$Datavalue->content_id.')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></i></button>';
			//$Delete='<button class="btn btn-primary del" onclick="livenow_delete('.$Datavalue->content_id.')"><i class="fa fa-trash-o" aria-hidden="true"></i></button>';
			$Fullcontent[]=array("articleid"=>$Datavalue->content_id,"articletitle"=>$Article_title,"add"=>$Add,"edit"=>$Edit);
		endforeach;
		$Result=array("sEcho" => 1,"iTotalRecords" => count($Fullcontent),"iTotalDisplayRecords" => count($Fullcontent),"aaData"=>$Fullcontent);
		echo json_encode($Result);
	}
	
	public function addDetails(){
		$articleId=$this->input->post('article_id');
		$Title=$this->input->post('title');
		$Content=$this->input->post('content');
		$pinto=$this->input->post('pinto');
		$FileName= $articleId.'.json';
		$path=FCPATH.'application/views/LIVENOW/';
		if(file_exists($path.$FileName)){
		
			$Json=file_get_contents($path.$FileName);
			$Json=json_decode($Json,true);
			$Data['title']=$Title;
			$Data['content']=$Content;
			$Data['date']=date('Y-m-d h:i:s');
			$Data['pin']=$pinto;
			$Data['status']=1;
			$Json['details'][]=$Data;
			file_put_contents($path.$FileName,json_encode($Json));
			$this->PutFile($FileName,json_encode($Json));
		}else{
			$Data['title']=$Title;
			$Data['content']=$Content;
			$Data['date']=date('Y-m-d h:i:s');
			$Data['pin']=$pinto;
			$Data['status']=1;
			$Json['details'][]=$Data;
			file_put_contents($path.$FileName,json_encode($Json));
			$this->PutFile($FileName,json_encode($Json));
		}
		echo 1;
	}
	
	public function EditDetails(){
		$articleId=$this->input->post('article_id');
		$FileName= $articleId.'.json';
		$path=FCPATH.'application/views/LIVENOW/';
		if(!file_exists($path.$FileName)){
			echo 'EMPTY';
		}else{
			$File=file_get_contents($path.$FileName);
			$File=json_decode($File,true);
			$Data=$File['details'];
			$template='<table class="table table-bordered">';
			$template .='<tr><th>title</th><th>content</th><th>Pin to top</th><th>Action</th></tr>';
			$i=0;
			foreach($Data as $DataValue){
				$template .='<tr class="remove">';
				$template .='<td>';
				$template .='<input type="text" id="edit_input'.$i.'" value="'.$DataValue['title'].'" class="form-control">';
				$template .='</td>';
				$template .='<td>';
				$template .='<textarea  id="edit_txtarea'.$i.'"  style="min-height: 50px;" class="form-control">'.$DataValue['content'].'</textarea>';
				$template .='</td>';
				$template .='<td>';
				$template .='<label style="margin-right: 3%;font-size: 14px;color: #686868;cursor:pointer;"><input '.((isset($DataValue['pin']) && $DataValue['pin']=='1') ? ' checked ' : '' ).' id="pinto_'.$i.'" style="float: left;margin-top: 4px !important;" type="checkbox" >Pin to top</label>';
				$template .='</td>';
				$template .='<td>';
				$template .='<input type="hidden"  id="date_'.$i.'" value="'.$DataValue['date'].'" >';
				$template .='<input type="hidden"  id="delete_'.$i.'" value="'.$DataValue['status'].'" >';
				if($DataValue['status']==1){
					$template .='<button class="btn btn-primary del" onclick="delete_content('.$i.')"><i class="fa fa-trash" aria-hidden="true"></i></button>';
				}else{
					$template .='<button class="btn btn-primary del" disabled onclick="delete_content('.$i.')">DELETED</button>';
				}
				
				$template .='</td>';
				$template .='</tr>';
				$i++;
			}
			$template .='</table>';
			$template .='<div class="form-group text-center">';
			$template .='<input type="hidden" id="total_count" value="'.($i).'">';
			$template .='<button class="btn btn-primary success" onclick="edit_content_save('.$articleId.')"><i class="fa fa-floppy-o" aria-hidden="true"></i> PUBLISH</button>';
			$Template .='</div>';
			echo $template;
		}
	}
	
	public function AddEditDetails(){
	
		$ArticleID=$this->input->post('article_id');
		$Content=$this->input->post('content');
		$Content=explode('~~!',$Content);
		$Content=array_filter($Content);
		for($i=0;$i<count($Content);$i++){
			$Element=explode('!-!-!',$Content[$i]);
			$Data['title']=@$Element[0];
			$Data['content']=@$Element[1];
			$Data['date']=@$Element[2];
			$Data['pin']=@$Element[3];
			$Data['status']=@$Element[4];
			$Json['details'][]=$Data;
		
		}
		$FileName= $ArticleID.'.json';
		$path=FCPATH.'application/views/LIVENOW/';
		file_put_contents($path.$FileName,json_encode($Json));
		$this->PutFile($FileName,json_encode($Json));
		echo 1;
	
	}
	
	public function DeleteContent(){
		$lid=$this->input->post('lid');
		echo $this->livenow_model->DeleteLiveNowContent($lid);
	
	}
	
	public function PutFile($FilePath,$content){
		$post_data = array('file_name' => $FilePath,'file_contents'=> $content);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, BASEURL.'user/commonwidget/Content_file_intimation');
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		$result=curl_exec ($ch);
		curl_close ($ch);
	}
	
}
?>