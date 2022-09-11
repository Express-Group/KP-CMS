<?php
class dynamic_table extends CI_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->helper(array('url','form'));
		$this->load->model('admin/dynamic_table_model');
	}
	
	
	public function index(){
		$Template['table']=$this->dynamic_table_model->GetTable();
		$Template['template']='tablemanager';
		$this->load->view('admin_template',$Template);

	}
	
	public function addtable(){
		$TableName=$this->input->post('table_name');
		$Total=$this->input->post('total');
		echo $this->dynamic_table_model->add_table($TableName,$Total);
	}
	
	public function Add_Parameter(){
		$tid=$this->input->post('tid');
		$total_count=$this->input->post('total_count');
		$field1=$this->input->post('field1');
		$field2=$this->input->post('field2');
		$field3=$this->input->post('field3');
		$field4=$this->input->post('field4');
		$FullArray=array();
		$Data['data']=array();
		for($i=0;$i<count($field1);$i++){
			$Data['data'][]=array("field1"=>$field1[$i],"field2"=>$field2[$i],"field3"=>$field3[$i],"field4"=>$field4[$i]);
			
		}
		if(isset($_POST['total'])){ $total=$_POST['total'];}else{ $total =0 ;}
		echo $this->dynamic_table_model->add_parameter_details(json_encode($Data),$tid,$total);
	}
	
	public function preview(){
		$result=$this->dynamic_table_model->preview_data($this->input->post('tid'));
		$Data=json_decode($result[0]->table_properties,true);
		$Data=$Data['data'];
		$Template='<table class="table table-bordered">';
		$Template .='<tr>';
		$Template .='<td colspan="2" style="background:#000000;color:#fff;border: none !important;">'.$result[0]->table_name.'</td>';
		$Template .='<td colspan="2" style="text-align:right;background:#000000;color:#fff;border: none !important;">Total - '.$result[0]->total.'</td>';
		$Template .='</tr>';
		$Template .='<tr>';
		$Template .='<td style="background: #7a0024;color: #fff;font-weight: bold;text-align: center;">Party</td>';
		$Template .='<td style="background: #7a0024;color: #fff;font-weight: bold;text-align: center;">Lead</td>';
		$Template .='<td style="background: #7a0024;color: #fff;font-weight: bold;text-align: center;">Won</td>';
		$Template .='<td style="background: #7a0024;color: #fff;font-weight: bold;text-align: center;">Total</td>';
		$Template .='</tr>';
		for($i=0;$i<count($Data);$i++){
			$numcount = strlen($result[0]->total);
			$total= $Data[$i]['field2'] + $Data[$i]['field3'];
			$totalCount = strlen($total);
			if($totalCount < $numcount){
				$prependString = str_repeat("0",$numcount- $totalCount);
				$total = $prependString.$total;
			}
			$Template .='<tr>';
			$Template .='<td style="background:#ebebeb;text-align:center;">'.$Data[$i]['field1'].'</td>';
			$Template .='<td style="background:#ebebeb;text-align:center;">'.$Data[$i]['field2'].'</td>';
			$Template .='<td style="background:#ebebeb;text-align:center;">'.$Data[$i]['field3'].'</td>';
			$Template .='<td style="background:#ebebeb;text-align:center;">'.$total.'</td>';
			$Template .='</tr>';
		}
		$Template .='</table>';
		print_R($Template);
	
	}
	
	public function edit_table(){
		$result=$this->dynamic_table_model->preview_data($this->input->post('tid'));
		$Json=json_decode($result[0]->table_properties,true);
		$Json=$Json['data'];
		$template='';
		$j=1;
		$template .='<div class="form-group">';
		$template .='<label>Total : </label>';
		$template .='<input type="text" class="form-control" value="'.$result[0]->total.'" id="total_edit">';
		$template .='</div>';
		for($i=0;$i<count($Json);$i++){
			$template .='<div class="form-group" id="adds_'.$j.'">';
			$template .='<div class="row">';
			$template .='<div class="col-md-4"><input type="text" class="form-control" value="'.$Json[$i]['field1'].'" id="add_field1_'.$j.'"></div>';
			$template .='<div class="col-md-4"><input type="text" class="form-control" value="'.$Json[$i]['field2'].'" id="add_field2_'.$j.'"></div>';
			$template .='<div class="col-md-4"><input type="text" class="form-control" value="'.$Json[$i]['field3'].'" id="add_field3_'.$j.'"></div>';
			$template .='<div style="display:none;" class="col-md-3"><input type="text" class="form-control" value="'.$Json[$i]['field4'].'" id="add_field4_'.$j.'"></div>';
			$template .='</div>';
			$template .='</div>';
			$j++;
		}
		$template .='<input type="hidden" id="temp_edit_count" value="'.count($Json).'">';
		echo $template;
	}
	
	public function delete(){
	
		$tid=$this->input->post('tid');
		echo $this->dynamic_table_model->table_delete($tid);
	}
	
	public function inserttablename(){
		$tid=$this->input->post('tid');
		$tablename=$this->input->post('tablename');
		echo $this->dynamic_table_model->tablename($tid,$tablename);
	
	}
}
?>