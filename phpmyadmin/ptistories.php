<?php

class ptistories{

	public  $ReadableDirectory = '/var/www/html/dev_nie/pti_xml/';

	public function index(){
		$i=1;
		$Response['folderpath'] = 'pti_xml';
		$Response['fullpath'] = '/var/www/html/dev_nie/pti_xml/';
		$Response['folderurl'] = 'http://dev.newindianexpress.com/'; 
		$Response['folders']=[];
		if(is_dir($this->ReadableDirectory)){
			if ($ReadFile = opendir($this->ReadableDirectory)){
					while (($FileName = readdir($ReadFile)) !== false){
						if($i > 50){
							break;
						}
						if($FileName !='.' || $FileName !='..' || $FileName !=''){
							$Response['folders'][] = $FileName;
							$i++;
						}
					}
			}
		}
		header('Content-Type: application/json');
		echo json_encode($Response);
	}

	public function getfile($FileName){
		$return = file_get_contents('/var/www/html/dev_nie/pti_xml/'.$FileName);
		unlink('/var/www/html/dev_nie/pti_xml/'.$FileName);
		echo $return;
	}

}

$object = new ptistories();
if($_GET['process'] == 'init'){
	$object->index();
}else if($_GET['process'] == 'del'){
	echo unlink('/var/www/html/dev_nie/pti_xml/'.$_POST['filename']); 
}else{
	$object->getfile($_REQUEST['filename']);
}

?>