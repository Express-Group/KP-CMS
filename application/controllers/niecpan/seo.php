<?php
class seo extends CI_Controller{
	
	
	public function __construct(){
		parent::__construct();
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->model('admin/seoadmin');
		
	}
	
	public function index(){
		$data['template']='seoindex';
		$this->load->view('admin_template',$data);
	}
	
	public function article(){
		if($this->uri->segment(4)):
			$Type = ($this->uri->segment(4)) ;
		else:
			$Type= 'article';
		endif;
		$TotalRows=$this->seoadmin->GetArticleCount($Type);
		$this->load->library('pagination');
		$config['base_url']=site_url(folder_name.'/seo/article/'.$Type);
		$config['total_rows'] = $TotalRows;
		$config["per_page"] = 25;
		$config['num_links'] = 5;
		$config['use_page_numbers'] = TRUE;
		$config['uri_segment'] = 5;
		$config['cur_tag_open'] = '<li class="active"><a>';
		$config['cur_tag_close'] = '</a></li>';
		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul">';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['next_link'] = 'next';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['prev_link'] = 'previous';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		if($this->uri->segment(5)):
			$page = ($this->uri->segment(5)) ;
		else:
			$page = 0;
		endif;
		
		$data['data']=$this->seoadmin->GetArticleContent($config["per_page"],$page,$Type);
		$data['pagination']=$this->pagination->create_links();
		$data['controller']=$this;
		$data['template']='seoview';
		$data['title']=$Type;
		$this->load->view('admin_template',$data);
	}
	
	
	public function EditDetails(){
		$ContentID=$this->input->post('content_id');
		$Type=$this->input->post('type');
		$Data=$this->seoadmin->GetEditContent($ContentID,$Type);
		$Template='';
		$Template .='<div class="form-group">';
		$Template .='<label>Meta Title</label>';
		$Template .='<textarea id="metatitle" class="form-control">'.$Data[0]->MetaTitle.'</textarea>';
		$Template .='</div>';
		$Template .='<div class="form-group">';
		$Template .='<label>Meta Description</label>';
		$Template .='<textarea id="metadescription" class="form-control">'.$Data[0]->MetaDescription.'</textarea>';
		$Template .='</div>';
		$Template .='<div class="form-group">';
		$Template .='<label>Tags</label>';
		$Template .='</div>';
		$j=1;
		if($Data[0]->Tags !=''){
			$Tags=str_replace('IE','',$Data[0]->Tags);
			$Tags=explode(',',$Tags);
			$Template .='<input type="hidden" value="'.count($Tags).'" id="tag_count">';
			for($i=0;$i<count($Tags);$i++){
				$GetTagName=$this->seoadmin->TagName($Tags[$i]);
				$Template .='<div class="tag_body_'.$j.'" style="width:100%;margin-bottom:10px">';
				$Template .='<input type="text" name="tag" id="tag_'.$j.'" class="form-control" value="'.$GetTagName.'">';
				$Template .='</div>';
				$j++;
			}
		}else{
			$Template .='<input type="hidden" value="1" id="tag_count">';
			$Template .='<div class="tag_body_1" style="width:100%;margin-bottom:10px">';
			$Template .='<input type="text" name="tag" id="tag_'.$j.'" class="form-control" value="">';
			$Template .='</div>';
		}
		
		$Template .='</div>';
		$Template .='<div class="form-group text-center" style="margin-top:9%;">';
		$Template .='<input type="hidden" value="'.$j.'" id="tag_count">';
		$Template .='<button class="btn btn-primary" onclick="addtags();">add Tags</button>';
		$Template .='<button class="btn btn-primary" onclick="removetags();">Remove Tags</button>';
		$Template .='<button class="btn btn-primary" onclick="saveContent('.$ContentID.');">save</button>';
		$Template .='</div>';
		echo $Template;
	}
	
	public function SaveDetails(){
		$ContentID=$this->input->post('content_id');
		$MetaTitle=$this->input->post('metatitle');
		$MetaDescription=$this->input->post('metadescription');
		$Type=$this->input->post('type');
		$Tags=$this->input->post('tags');
		$TagValue=array();
		$TagID=array();
		$t='';
		for($i=0;$i<count($Tags);$i++){
			if($Tags[$i]!=''){
				$ID=$this->seoadmin->GetID($Tags[$i]);
				$TagID[]='IE'.$ID.'IE';
				$TagValue[]=$Tags[$i];
			}
		}
		$TagID=implode(',',$TagID);
		$TagValue=implode(',',$TagValue);
		if($TagID==''){ $TagID=''; }
		if($TagValue==''){ $TagValue=''; }
			echo $this->seoadmin->SaveContent($ContentID,$MetaTitle,$MetaDescription,$TagID,$TagValue,$Type);
	}
	
	public function SearchDetails(){
		$ContentID=$this->input->post('content_id');
		$Type=$this->input->post('type');
		$CheckContentIdAvail=$this->seoadmin->checkID($ContentID,$Type);
		$Template='';
		if($CheckContentIdAvail==1){
			$Template .='<div class="row">';
			$Template .='<div class="col-md-12 text-center">';
			$Template .='<h2>Article Not Found</h2>';
			$Template .='</div>';
			$Template .='</div>';
		}else if($CheckContentIdAvail==2){
			$Template .='<div class="row">';
			$Template .='<div class="col-md-12 text-center">';
			$Template .='<h2>Article Not Published</h2>';
			$Template .='</div>';
			$Template .='</div>';
		
		}else if($CheckContentIdAvail==0){
			$Data=$this->seoadmin->GetEditContent($ContentID,$Type);
			$Template .='<div class="form-group">';
			$Template .='<label>Meta Title</label>';
			$Template .='<textarea id="metatitle" class="form-control">'.$Data[0]->MetaTitle.'</textarea>';
			$Template .='</div>';
			$Template .='<div class="form-group">';
			$Template .='<label>Meta Description</label>';
			$Template .='<textarea id="metadescription" class="form-control">'.$Data[0]->MetaDescription.'</textarea>';
			$Template .='</div>';
			$Template .='<div class="form-group">';
			$Template .='<label>Tags</label>';
			$Template .='</div>';
			$j=1;
			if($Data[0]->Tags !=''){
				$Tags=str_replace('IE','',$Data[0]->Tags);
				$Tags=explode(',',$Tags);
				$Template .='<input type="hidden" value="'.count($Tags).'" id="tag_count">';
				for($i=0;$i<count($Tags);$i++){
					$GetTagName=$this->seoadmin->TagName($Tags[$i]);
					$Template .='<div class="tag_body_'.$j.'" style="width:100%;margin-bottom:10px">';
					$Template .='<input type="text" name="tag" id="tag_'.$j.'" class="form-control" value="'.$GetTagName.'">';
					$Template .='</div>';
					$j++;
				}
			}else{
				$Template .='<input type="hidden" value="1" id="tag_count">';
				$Template .='<div class="tag_body_1" style="width:100%;margin-bottom:10px">';
				$Template .='<input type="text" name="tag" id="tag_'.$j.'" class="form-control" value="">';
				$Template .='</div>';
			}
		
			$Template .='</div>';
			$Template .='<div class="form-group text-center" style="margin-top:9%;">';
			$Template .='<input type="hidden" value="'.$j.'" id="tag_count">';
			$Template .='<button class="btn btn-primary" onclick="addtags();">add Tags</button>';
			$Template .='<button class="btn btn-primary" onclick="removetags();">Remove Tags</button>';
			$Template .='<button class="btn btn-primary" onclick="saveContent('.$ContentID.');">save</button>';
			$Template .='</div>';
		}
		
		echo $Template;
	}

}
?>