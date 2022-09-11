<?php
ini_set('memory_limit', '-1');
class kannadaprabha_migration extends CI_Controller{
	
	
	private $PDO;
	private $hostName = '10.50.1.84';
	private $databaseName = 'kannadaprabha';
	private $userName = 'livekannadaprabha';
	private $password = 'Ch124af#123live';
	private $KPDB;
	
	public function __construct(){
		parent::__construct();
		$this->PDO = new PDO("dblib:version=7.0;host=$this->hostName;dbname=$this->databaseName;port=1433;charset=UTF-8", $this->userName, $this->password);
		$this->KPDB = $this->load->database('kp_migration', TRUE);
	}
	
	
	public function index(){
		echo '<p style="margin:0;">METHODS LIST</p>';
		echo '<p style="margin:0;">----------------------</p>';
        echo $this->method_filter();
	}
	
	public function update_user(int $userid){
		//$this->PDO->query('UPDATE ExpressBuzz_User SET RoleID=1 WHERE UserID="'.$userid.'"');
		
		$sql = "UPDATE ExpressBuzz_User SET RoleID=1 WHERE UserID=:userid";
		$stmt = $this->PDO->prepare($sql);
		$stmt->bindParam(':userid', $userid, PDO::PARAM_INT);   
		$stmt->execute();
		$query= $this->PDO->query('SELECT * FROM ExpressBuzz_User');
		print_r($query->fetchAll(PDO::FETCH_ASSOC));
	}
	
	public function method_filter(){
		$response = [];
		$methods = get_class_methods ( $this );
		$j=1;
		for($i=0;$i<count($methods);$i++):
			if($methods[$i]!='__construct' && $methods[$i]!='index' && $methods[$i]!='get_instance' && $methods[$i]!='mysql_date' && $methods[$i]!='method_filter'){
				array_push($response , '<p>'.($j).') '.$methods[$i].'</p>');
				$j++;
			}
				
		endfor;
		return implode('',$response);
	}
	
	public function mysql_date($date){
		return date('Y-m-d h:i:s' , strtotime($date));
	}
	
	
	//be_Categories migration
	public function categories($truncate=0){
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE be_Categories");
		endif;
		$data = [];
		$query = $this->PDO->query('select * from  be_Categories');
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array('CategoryID' => $result['CategoryID'] , 'CategoryName' => $result['CategoryName'] , 'Description' => $result['Description']);
			array_push($data , $temp);
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('be_Categories' ,$data);
			echo 'Response : '.$response;
	}
	
	
	//be_DataStoreSettings table migration
	public function dataStoreSettings($truncate=0){
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE be_DataStoreSettings");
		endif;
		$data = [];
		$query = $this->PDO->query('select * from  be_DataStoreSettings');
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array('ExtensionType' => $result['ExtensionType'] , 'ExtensionId' => $result['ExtensionId'] , 'Settings' => $result['Settings']);
			array_push($data , $temp);
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('be_DataStoreSettings' ,$data);
			echo 'Response : '.$response;
	}
	
	//be_Pages table migration
	public function pages($truncate=0){
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE be_Pages");
		endif;
		$data = [];
		$query = $this->PDO->query('select * from  be_Pages');		
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array('PageID' => $result['PageID'] , 'Title' => $result['Title'] , 'Description' => $result['Description'] , 'PageContent' => $result['PageContent'] , 'Keywords' => $result['Keywords'] , 'DateCreated' => $this->mysql_date($result['DateCreated']) , 'DateModified' => $this->mysql_date($result['DateModified']) , 'IsPublished' => $result['IsPublished'] , 'IsFrontPage' => $result['IsFrontPage'] , 'Parent' => $result['Parent'] , 'ShowInList' => $result['ShowInList']);
			array_push($data , $temp);
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('be_Pages' ,$data);
			echo 'Response : '.$response;
	}
	
	
	//be_PingService table migration
	public function pingService($truncate=0){
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE be_PingService");
		endif;
		$data = [];
		$query = $this->PDO->query('select * from  be_PingService');	
		//echo count($query->fetchAll(PDO::FETCH_ASSOC));		
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array('PingServiceID' => $result['PingServiceID'] , 'Link' => $result['Link']);
			array_push($data , $temp);
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('be_PingService' ,$data);
			echo 'Response : '.$response;
	}
	
	//be_PostCategory table migration
	public function postCategory($truncate=0){
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE be_PostCategory");
		endif;
		$data = [];
		$query = $this->PDO->query('select * from  be_PostCategory');	
		// echo count($query->fetchAll(PDO::FETCH_ASSOC));		
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array('PostCategoryID' => $result['PostCategoryID'] , 'PostID' => $result['PostID'] , 'CategoryID' => $result['CategoryID']);
			array_push($data , $temp);
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('be_PostCategory' ,$data);
			echo 'Response : '.$response;
	}
	
	//be_PostComment table migration
	public function postComment($truncate=0){
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE be_PostComment");
		endif;
		$data = [];
		$query = $this->PDO->query('select * from  be_PostComment');	
		// echo count($query->fetchAll(PDO::FETCH_ASSOC));		
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array('PostCommentID' => $result['PostCommentID'] , 'PostID' => $result['PostID'] , 'CommentDate' => $this->mysql_date($result['CommentDate']) , 'Author' => $result['Author'] , 'Email' => $result['Email'] , 'Website' => $result['Website'] , 'Comment' => $result['Comment'] , 'Country' => $result['Country'] , 'Ip' => $result['Ip'] , 'IsApproved' => $result['IsApproved']);
			array_push($data , $temp);
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('be_PostComment' ,$data);
			echo 'Response : '.$response;
	}
	
	//be_PostNotify table migration
	public function postNotify($truncate=0){
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE be_PostNotify");
		endif;
		$data = [];
		$query = $this->PDO->query('select * from  be_PostNotify');	
		// echo count($query->fetchAll(PDO::FETCH_ASSOC));		
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array('PostNotifyID' => $result['PostNotifyID'] , 'PostID' => $result['PostID'] , 'NotifyAddress' => $result['NotifyAddress']);
			array_push($data , $temp);
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('be_PostNotify' ,$data);
			echo 'Response : '.$response;
	}
	
	//be_Posts table migration
	public function posts($truncate=0){
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE be_Posts");
		endif;
		$data = [];
		$query = $this->PDO->query('select * from  be_Posts');	
		// echo count($query->fetchAll(PDO::FETCH_ASSOC));		
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array('PostID' => $result['PostID'] , 'Title' => $result['Title'] , 'Description' => $result['Description'] , 'PostContent' => $result['PostContent'] , 'DateCreated' => $this->mysql_date($result['DateCreated']) , 'DateModified' => $this->mysql_date($result['DateModified']) , 'Author' => $result['Author'] , 'IsPublished' => $result['IsPublished'] , 'IsCommentEnabled' => $result['IsCommentEnabled'] , 'Raters' => $result['Raters'] , 'Rating' => $result['Rating'] , 'Slug' => $result['Slug']);
			array_push($data , $temp);
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('be_Posts' ,$data);
			echo 'Response : '.$response;
	}
	
	//be_PostTag table migration
	public function postTag($truncate=0){
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE be_PostTag");
		endif;
		$data = [];
		$query = $this->PDO->query('select * from  be_PostTag');	
		// echo count($query->fetchAll(PDO::FETCH_ASSOC));		
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array('PostTagID' => $result['PostTagID'] , 'PostID' => $result['PostID'] , 'Tag' => $result['Tag'] );
			array_push($data , $temp);
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('be_PostTag' ,$data);
			echo 'Response : '.$response;
	}
	
	//be_Profiles table migration
	public function profiles($truncate=0){
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE be_Profiles");
		endif;
		$data = [];
		$query = $this->PDO->query('select * from  be_Profiles');	
		 // echo count($query->fetchAll(PDO::FETCH_ASSOC));		
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array('ProfileID' => $result['ProfileID'] , 'UserName' => $result['UserName'] , 'SettingName' => $result['SettingName'] , 'SettingValue' => $result['SettingValue'] );
			array_push($data , $temp);
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('be_Profiles' ,$data);
			echo 'Response : '.$response;
	}
	
	//be_Settings table migration
	public function settings($truncate=0){
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE be_Settings");
		endif;
		$data = [];
		$query = $this->PDO->query('select * from  be_Settings');	
		 // echo count($query->fetchAll(PDO::FETCH_ASSOC));		
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array('SettingName' => $result['SettingName'] , 'SettingValue' => $result['SettingValue']);
			array_push($data , $temp);
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('be_Settings' ,$data);
			echo 'Response : '.$response;
	}
	
	//be_StopWords table migration
	public function stopWords($truncate=0){
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE be_StopWords");
		endif;
		$data = [];
		$query = $this->PDO->query('select * from  be_StopWords');	
		 // echo count($query->fetchAll(PDO::FETCH_ASSOC));		
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array('StopWord' => $result['StopWord']);
			array_push($data , $temp);
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('be_StopWords' ,$data);
			echo 'Response : '.$response;
	}
	
	
	//ExpressBuzz_Advertiser table migration
	public function advertiser($truncate=0){
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE ExpressBuzz_Advertiser");
		endif;
		$data = [];
		$query = $this->PDO->query('select * from  ExpressBuzz_Advertiser');	
		 // echo count($query->fetchAll(PDO::FETCH_ASSOC));		
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array('AdvertiserID' => $result['AdvertiserID'] , 'CompanyName' => $result['CompanyName'] , 'Status' => $result['Status'] , 'CreatedBy' => $result['CreatedBy'] , 'CreatedDate' => $this->mysql_date($result['CreatedDate']) , 'ModifiedBy' => $result['ModifiedBy'] , 'ModifiedDate' => $this->mysql_date($result['ModifiedDate']) );
			array_push($data , $temp);
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('ExpressBuzz_Advertiser' ,$data);
			echo 'Response : '.$response;
	} 
	
	//ExpressBuzz_Article table migration
	public function article($truncate=0){
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE ExpressBuzz_Article");
		endif;
		$data = [];
		$query = $this->PDO->query("select * from  ExpressBuzz_Article");	
		  // echo count($query->fetchAll(PDO::FETCH_ASSOC));		
		 foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = [];
			$temp['ArticleID'] = $result['ArticleID'];
			$temp['NewsID'] = $result['NewsID'];
			$temp['SectionID'] = $result['SectionID'];
			$temp['Status'] = $result['Status'];
			$temp['Title'] = $result['Title'];
			$temp['HeadLine30'] = $result['HeadLine30'];
			$temp['HeadLine1'] = $result['HeadLine1'];
			$temp['HeadLine2'] = $result['HeadLine2'];
			$temp['DetailNews'] = $result['DetailNews'];
			$temp['PublishDate'] = $this->mysql_date($result['PublishDate']);
			$temp['ExpiryDate'] = $this->mysql_date($result['ExpiryDate']);
			$temp['ImpressionCount'] = $result['ImpressionCount'];
			$temp['ReadCount'] = $result['ReadCount'];
			$temp['PrintCount'] = $result['PrintCount'];
			$temp['BookmarkCount'] = $result['BookmarkCount'];
			$temp['Emailcount'] = $result['Emailcount'];
			$temp['AuthorID'] = $result['AuthorID'];
			$temp['ApproverID'] = $result['ApproverID'];
			$temp['ThumbnailPhoto'] = $result['ThumbnailPhoto'];
			$temp['LargePhoto'] = $result['LargePhoto'];
			$temp['FlashVsVideo'] = $result['FlashVsVideo'];
			$temp['GalleryID'] = $result['GalleryID'];
			$temp['AuthorBannerValid'] = $result['AuthorBannerValid'];
			$temp['RelatedArticles'] = $result['RelatedArticles'];
			$temp['SEOKeywords'] = $result['SEOKeywords'];
			$temp['CreatedBy'] = $result['CreatedBy'];
			$temp['CreatedDate'] =  $this->mysql_date($result['CreatedDate']);
			$temp['ModifiedBy'] = $result['ModifiedBy'];
			$temp['ModifiedDate'] =  $this->mysql_date($result['ModifiedDate']);
			$temp['XmlSourcePath'] = $result['XmlSourcePath'];
			$temp['multipleSection'] = $result['multipleSection'];
			$temp['PhotoCaption'] = $result['PhotoCaption'];
			$temp['CommentEnabled'] = $result['CommentEnabled'];
			$temp['Agency'] = $result['Agency'];
			$temp['SortPriority'] = $result['SortPriority'];
			$temp['RelatedTitle'] = $result['RelatedTitle'];
			$temp['VideoCode'] = $result['VideoCode'];
			$temp['SeoSummary'] = $result['SeoSummary'];
			$temp['PhotoAlt'] = $result['PhotoAlt'];
			$temp['URI'] = $result['URI'];
			array_push($data , $temp);
		endforeach; 
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('ExpressBuzz_Article' ,$data);
			echo 'Response : '.$response;
	} 
	
	//ExpressBuzz_Articletest table migration
	public function articletest($truncate=0){
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE ExpressBuzz_Articletest");
		endif;
		$data = [];
		$query = $this->PDO->query("select * from  ExpressBuzz_Articletest");	
		  // echo count($query->fetchAll(PDO::FETCH_ASSOC));		
		 foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = [];
			$temp['ArticleID'] = $result['ArticleID'];
			$temp['NewsID'] = $result['NewsID'];
			$temp['SectionID'] = $result['SectionID'];
			$temp['Status'] = $result['Status'];
			$temp['Title'] = $result['Title'];
			$temp['HeadLine30'] = $result['HeadLine30'];
			$temp['HeadLine1'] = $result['HeadLine1'];
			$temp['HeadLine2'] = $result['HeadLine2'];
			$temp['DetailNews'] = $result['DetailNews'];
			$temp['PublishDate'] = $this->mysql_date($result['PublishDate']);
			$temp['ExpiryDate'] = $this->mysql_date($result['ExpiryDate']);
			$temp['ImpressionCount'] = $result['ImpressionCount'];
			$temp['ReadCount'] = $result['ReadCount'];
			$temp['PrintCount'] = $result['PrintCount'];
			$temp['BookmarkCount'] = $result['BookmarkCount'];
			$temp['Emailcount'] = $result['Emailcount'];
			$temp['AuthorID'] = $result['AuthorID'];
			$temp['ApproverID'] = $result['ApproverID'];
			$temp['ThumbnailPhoto'] = $result['ThumbnailPhoto'];
			$temp['LargePhoto'] = $result['LargePhoto'];
			$temp['FlashVsVideo'] = $result['FlashVsVideo'];
			$temp['GalleryID'] = $result['GalleryID'];
			$temp['AuthorBannerValid'] = $result['AuthorBannerValid'];
			$temp['RelatedArticles'] = $result['RelatedArticles'];
			$temp['SEOKeywords'] = $result['SEOKeywords'];
			$temp['CreatedBy'] = $result['CreatedBy'];
			$temp['CreatedDate'] =  $this->mysql_date($result['CreatedDate']);
			$temp['ModifiedBy'] = $result['ModifiedBy'];
			$temp['ModifiedDate'] =  $this->mysql_date($result['ModifiedDate']);
			$temp['XmlSourcePath'] = $result['XmlSourcePath'];
			$temp['multipleSection'] = $result['multipleSection'];
			$temp['PhotoCaption'] = $result['PhotoCaption'];
			$temp['CommentEnabled'] = $result['CommentEnabled'];
			array_push($data , $temp);
		endforeach; 
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('ExpressBuzz_Articletest' ,$data);
			echo 'Response : '.$response;
	}
	
	
	//ExpressBuzz_ArticlevsSection migration
	public function articlevsSection($truncate=0){
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE ExpressBuzz_ArticlevsSection");
		endif;
		$data = [];
		$query = $this->PDO->query('select * from  ExpressBuzz_ArticlevsSection');
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array('ArticleID' => $result['ArticleID'] , 'SectionID' => $result['SectionID']);
			array_push($data , $temp);
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('ExpressBuzz_ArticlevsSection' ,$data);
			echo 'Response : '.$response;
	}
	
	
	//ExpressBuzz_Astrologer migration
	public function astrologer($truncate=0){
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE ExpressBuzz_Astrologer");
		endif;
		$data = [];
		$query = $this->PDO->query('select * from  ExpressBuzz_Astrologer');
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array('AstrologerID' => $result['AstrologerID'] , 'AstrologerName' => $result['AstrologerName'] , 'Active' => $result['Active']);
			array_push($data , $temp);
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('ExpressBuzz_Astrologer' ,$data);
			echo 'Response : '.$response;
	}
	
	
	//ExpressBuzz_AstrologerForecast migration
	public function astrologerForecast($truncate=0){
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE ExpressBuzz_AstrologerForecast");
		endif;
		$data = [];
		$query = $this->PDO->query('select * from  ExpressBuzz_AstrologerForecast');
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array('ForecastID' => $result['ForecastID'] , 'AstrologerID' => $result['AstrologerID'] , 'AstrologerStarID' => $result['AstrologerStarID'] , 'ForcastFrom' => $this->mysql_date($result['ForcastFrom']) , 'ForcastTo' => $this->mysql_date($result['ForcastTo']) , 'Forecast' => $result['Forecast']);
			array_push($data , $temp);
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('ExpressBuzz_AstrologerForecast' ,$data);
			echo 'Response : '.$response;
	}
	
	
	//ExpressBuzz_AstrologerStars migration
	public function astrologerStars($truncate=0){
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE ExpressBuzz_AstrologerStars");
		endif;
		$data = [];
		$query = $this->PDO->query('select * from  ExpressBuzz_AstrologerStars');
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array('StarID' => $result['StarID'] , 'StarName' => $result['StarName'] , 'Banner' => $result['Banner'] , 'StarName_Kan' => $result['StarName_Kan'] , 'Sani' => $result['Sani'] , 'Guru' => $result['Guru'] , 'Newyear' => $result['Newyear']);
			array_push($data , $temp);
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('ExpressBuzz_AstrologerStars' ,$data);
			echo 'Response : '.$response;
	}
	
	//ExpressBuzz_Author migration
	public function author($truncate=0){
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE ExpressBuzz_Author");
		endif;
		$data = [];
		$query = $this->PDO->query('select * from  ExpressBuzz_Author');
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array('AuthorID' => $result['AuthorID'] , 'RoleID' => $result['RoleID'] , 'AliasName' => $result['AliasName'] , 'Birthday' => $this->mysql_date($result['Birthday']) , 'LoginEmail' => $result['LoginEmail'] , 'AuthorPhoto' => $result['AuthorPhoto'] , 'Password' => $result['Password'] , 'AuthorBanner' => $result['AuthorBanner'] , 'CreatedBy' => $result['CreatedBy'] ,  'CreatedDate' => $this->mysql_date($result['CreatedDate'])  , 'ModifiedBy' => $result['ModifiedBy'] , 'ModifiedDate' => $this->mysql_date($result['ModifiedDate']) , 'Source' => $result['Source'] , 'Biography' => $result['Biography'] , 'Status' => $result['Status']);
			array_push($data , $temp);
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('ExpressBuzz_Author' ,$data);
			echo 'Response : '.$response;
	}
	
	
	// ExpressBuzz_Banner migration
	public function banner($truncate=0){
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE ExpressBuzz_Banner");
		endif;
		$data = [];
		$query = $this->PDO->query('select * from  ExpressBuzz_Banner');
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array('BannerID' => $result['BannerID'] , 'Path' => $result['Path'] , 'URL' => $result['URL'] , 'AlternativeText' => $result['AlternativeText'] , 'ClickCount' => $result['ClickCount'] , 'ImpressionCount' => $result['ImpressionCount'] , 'BlockID' => $result['BlockID'] , 'Status' => $result['Status'] , 'AdvertiserID' => $result['AdvertiserID'] ,  'CampaignFrom' => $this->mysql_date($result['CampaignFrom'])  ,  'CampaignTill' => $this->mysql_date($result['CampaignTill'])  , 'CreatedBy' => $result['CreatedBy'] , 'CreatedDate' => $this->mysql_date($result['CreatedDate']) , 'ModifiedBy' => $result['ModifiedBy'] , 'ModifiedDate' => $this->mysql_date($result['ModifiedDate']) , 'CampaignImpressions' => $result['CampaignImpressions'] , 'scriptflag' => $result['scriptflag'] , 'sectionid' => $result['sectionid'] , 'Sectionname' => $result['Sectionname']);
			array_push($data , $temp);
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('ExpressBuzz_Banner' ,$data);
			echo 'Response : '.$response;
	}
	
	
	// ExpressBuzz_BannerBlock migration
	public function bannerBlock($truncate=0){
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE ExpressBuzz_BannerBlock");
		endif;
		$data = [];
		$query = $this->PDO->query('select * from  ExpressBuzz_BannerBlock');
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array('BannerBlockID' => $result['BannerBlockID'] , 'BlockName' => $result['BlockName'] , 'DefaultBanner' => $result['DefaultBanner'] , 'Width' => $result['Width'] , 'Height' => $result['Height'] , 'CreatedBy' => $result['CreatedBy'] , 'CreatedDate' => $this->mysql_date($result['CreatedDate']) , 'ModifiedBy' => $result['ModifiedBy'] , 'ModifiedDate' => $this->mysql_date($result['ModifiedDate']) ,  'status' => $result['status']);
			array_push($data , $temp);
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('ExpressBuzz_BannerBlock' ,$data);
			echo 'Response : '.$response;
	}
	
	
	// ExpressBuzz_BreakingNews migration
	public function breakingNews($truncate=0){
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE ExpressBuzz_BreakingNews");
		endif;
		$data = [];
		$query = $this->PDO->query('select * from  ExpressBuzz_BreakingNews');
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array('BreakingNewsID' => $result['BreakingNewsID'] , 'BreakingNews' => $result['BreakingNews'] , 'ArticleID' => $result['ArticleID'] , 'ValidFrom' => $this->mysql_date($result['ValidFrom']) , 'ValidTill' => $this->mysql_date($result['ValidTill']) , 'Status' => $result['Status'] , 'Sortorder' => $result['Sortorder'] , 'CreatedBy' => $result['CreatedBy'] , 'CreatedDate' => $this->mysql_date($result['CreatedDate']));
			array_push($data , $temp);
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('ExpressBuzz_BreakingNews' ,$data);
			echo 'Response : '.$response;
	}
	
	
	// ExpressBuzz_Comments migration
	public function comments($truncate=0){
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE ExpressBuzz_Comments");
		endif;
		$data = [];
		$query = $this->PDO->query('select * from  ExpressBuzz_Comments');
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array('CommentID' => $result['CommentID'] , 'ParentID' => $result['ParentID'] , 'UserID' => $result['UserID'] , 'Description' => $result['Description'] , 'ArticleID' => $result['ArticleID'] , 'ApprovalStatus' => $result['ApprovalStatus'] , 'IPAddress' => $result['IPAddress'] , 'CommentatorMailID' => $result['CommentatorMailID'] , 'CommentatorName' => $result['CommentatorName'] , 'PostedDate' => $this->mysql_date($result['PostedDate'])  , 'priority' => $result['priority']);
			array_push($data , $temp);
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('ExpressBuzz_Comments' ,$data);
			echo 'Response : '.$response;
	}
	
	
	// ExpressBuzz_ContentPage migration
	public function contentPage($truncate=0){
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE ExpressBuzz_ContentPage");
		endif;
		$data = [];
		$query = $this->PDO->query('select * from  ExpressBuzz_ContentPage');
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array('ContentPageID' => $result['ContentPageID'] , 'Title' => $result['Title'] , 'Description' => $result['Description'] , 'Status' => $result['Status'] , 'CreatedBy' => $result['CreatedBy'] , 'CreatedDate' => $this->mysql_date($result['CreatedDate']) , 'ModifiedBy' => $result['ModifiedBy'] , 'ModifiedDate' => $this->mysql_date($result['ModifiedDate']) , 'Keywords' => $result['Keywords']);
			array_push($data , $temp);
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('ExpressBuzz_ContentPage' ,$data);
			echo 'Response : '.$response;
	}
	
	// ExpressBuzz_DisplayArticle migration
	public function displayArticle($truncate=0){
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE ExpressBuzz_DisplayArticle");
		endif;
		$data = [];
		$query = $this->PDO->query('select * from  ExpressBuzz_DisplayArticle');
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array('DisplayID' => $result['DisplayID'] , 'DisplayName' => $result['DisplayName'] , 'TabID' => $result['TabID'] , 'ArticleID' => $result['ArticleID'] , 'Status' => $result['Status'] ,  'LastModifiedBy' => $result['LastModifiedBy'] , 'LastModifiedDate' => $this->mysql_date($result['LastModifiedDate']) , 'SortOrder' => $result['SortOrder']  , 'AliasName' => $result['AliasName']  , 'NoofRelatedArticles' => $result['NoofRelatedArticles']   , 'title' => $result['title']);
			array_push($data , $temp);
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('ExpressBuzz_DisplayArticle' ,$data);
			echo 'Response : '.$response;
	}
	
	
	// ExpressBuzz_Emailed migration
	public function emailed($truncate=0){
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE ExpressBuzz_Emailed");
		endif;
		$data = [];
		$query = $this->PDO->query('select * from  ExpressBuzz_Emailed');
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array('EmailedID' => $result['EmailedID'] , 'SenderName' => $result['SenderName'] , 'SenderEmail' => $result['SenderEmail'] , 'ReceiverName' => $result['ReceiverName'] , 'ReceiverEmail' => $result['ReceiverEmail'] , 'EmailedDate' => $this->mysql_date($result['EmailedDate']) , 'IPAddress' => $result['IPAddress']  , 'ArticleID' => $result['ArticleID']);
			array_push($data , $temp);
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('ExpressBuzz_Emailed' ,$data);
			echo 'Response : '.$response;
	}
	
	
	// ExpressBuzz_Gallery migration
	public function gallery($truncate=0){
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE ExpressBuzz_Gallery");
		endif;
		$data = [];
		$query = $this->PDO->query('select * from  ExpressBuzz_Gallery');
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array();
			$temp['GalleryID'] = $result['GalleryID'];
			$temp['CaptionName'] = $result['CaptionName'];
			$temp['SortOrder'] = $result['SortOrder'];
			$temp['IsHomePageSet'] = $result['IsHomePageSet'];
			$temp['GalleryGroupID'] = $result['GalleryGroupID'];
			$temp['Status'] = $result['Status'];
			$temp['CreatedBy'] = $result['CreatedBy'];
			$temp['CreatedDate'] = $this->mysql_date($result['CreatedDate']);
			$temp['ModifiedBy'] = $result['ModifiedBy'];
			$temp['ModifiedDate'] = $this->mysql_date($result['ModifiedDate']);
			$temp['Count'] = $result['Count'];
			$temp['ThumbImage'] = $result['ThumbImage'];
			$temp['IsGalleryHomePageSet'] = $result['IsGalleryHomePageSet'];
			$temp['FileGallery'] = $result['FileGallery'];
			$temp['IsInsidePageSet'] = $result['IsInsidePageSet'];
			$temp['seo'] = $result['seo'];
			$temp['GalDescription'] = $result['GalDescription'];
			$temp['Genre'] = $result['Genre'];
			array_push($data , $temp);
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('ExpressBuzz_Gallery' ,$data);
			echo 'Response : '.$response;
	}
	
	
	// ExpressBuzz_GalleryGroup migration
	public function galleryGroup($truncate=0){
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE ExpressBuzz_GalleryGroup");
		endif;
		$data = [];
		$query = $this->PDO->query('select * from  ExpressBuzz_GalleryGroup');
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array('GalleryGroupID' => $result['GalleryGroupID'] , 'GalleryGroupName' => $result['GalleryGroupName'] , 'SortOrder' => $result['SortOrder'] , 'Status' => $result['Status'] , 'CreatedBy' => $result['CreatedBy'] , 'CreatedDate' => $this->mysql_date($result['CreatedDate']) , 'ModifiedBy' => $result['ModifiedBy']  , 'ModifiedDate' => $this->mysql_date($result['ModifiedDate']) , 'GalGroupDescription' => $result['GalGroupDescription']);
			array_push($data , $temp);
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('ExpressBuzz_GalleryGroup' ,$data);
			echo 'Response : '.$response;
	}
	
	
	// ExpressBuzz_GalleryPhotoRelationship migration
	public function galleryPhotoRelationship($truncate=0){
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE ExpressBuzz_GalleryPhotoRelationship");
		endif;
		$data = [];
		$query = $this->PDO->query('select * from  ExpressBuzz_GalleryPhotoRelationship');
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array('GalleryPhotoID' => $result['GalleryPhotoID'] , 'GalleryID' => $result['GalleryID'] , 'PhotoID' => $result['PhotoID'] , 'Status' => $result['Status'] , 'CreatedBy' => $result['CreatedBy'] , 'CreatedDate' => $this->mysql_date($result['CreatedDate']) , 'ModifiedBy' => $result['ModifiedBy']  , 'ModifiedDate' => $this->mysql_date($result['ModifiedDate']) , 'SortOrder' => $result['SortOrder']);
			array_push($data , $temp);
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('ExpressBuzz_GalleryPhotoRelationship' ,$data);
			echo 'Response : '.$response;
	}
	
	
	// ExpressBuzz_NewsBlock migration
	public function newsBlock($truncate=0){
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE ExpressBuzz_NewsBlock");
		endif;
		$data = [];
		$query = $this->PDO->query('select * from  ExpressBuzz_NewsBlock');
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array('NewsBlockID' => $result['NewsBlockID'] , 'NewsBlockName' => $result['NewsBlockName'] , 'Status' => $result['Status']);
			array_push($data , $temp);
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('ExpressBuzz_NewsBlock' ,$data);
			echo 'Response : '.$response;
	}
	
	
	// ExpressBuzz_NewsBlockTabRelationship migration
	public function newsBlockTabRelationship($truncate=0){
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE ExpressBuzz_NewsBlockTabRelationship");
		endif;
		$data = [];
		$query = $this->PDO->query('select * from  ExpressBuzz_NewsBlockTabRelationship');
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array('NewsBlockTabRelationshipID' => $result['NewsBlockTabRelationshipID'] , 'NewsBlockID' => $result['NewsBlockID'] , 'TabID' => $result['TabID'] , 'Status' => $result['Status']);
			array_push($data , $temp);
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('ExpressBuzz_NewsBlockTabRelationship' ,$data);
			echo 'Response : '.$response;
	}
	
	
	// ExpressBuzz_Photo migration
	public function photo($truncate=0){
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE ExpressBuzz_Photo");
		endif;
		$data = [];
		$query = $this->PDO->query('select * from  ExpressBuzz_Photo');
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array('PhotoID' => $result['PhotoID'] , 'CaptionName' => $result['CaptionName'] , 'Path' => $result['Path'] , 'Status' => $result['Status'] , 'CreatedBy' => $result['CreatedBy'] , 'CreatedDate' => $this->mysql_date($result['CreatedDate']) , 'ModifiedBy' => $result['ModifiedBy'] , 'ModifiedDate' => $this->mysql_date($result['ModifiedDate'])  , 'SeoTitle' => $result['SeoTitle'] , 'SeoSummary' => $result['SeoSummary']);
			array_push($data , $temp);
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('ExpressBuzz_Photo' ,$data);
			echo 'Response : '.$response;
	}
	
	
	// ExpressBuzz_PhotoHeadlines migration
	public function photoHeadlines($truncate=0){
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE ExpressBuzz_PhotoHeadlines");
		endif;
		$data = [];
		$query = $this->PDO->query('select * from  ExpressBuzz_PhotoHeadlines');
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array('GalleryID' => $result['GalleryID'] , 'GalleryCaption' => $result['GalleryCaption'] , 'Path' => $result['Path'] , 'ArticleID' => $result['ArticleID'] , 'Status' => $result['Status'] , 'Priority' => $result['Priority'] , 'storylink' => $result['storylink']);
			array_push($data , $temp);
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('ExpressBuzz_PhotoHeadlines' ,$data);
			echo 'Response : '.$response;
	}
	
	
	// ExpressBuzz_Role migration
	public function role($truncate=0){
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE ExpressBuzz_Role");
		endif;
		$data = [];
		$query = $this->PDO->query('select * from  ExpressBuzz_Role');
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array('RoleID' => $result['RoleID'] , 'RoleName' => $result['RoleName'] , 'Status' => $result['Status']);
			array_push($data , $temp);
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('ExpressBuzz_Role' ,$data);
			echo 'Response : '.$response;
	}
	
	
	// ExpressBuzz_RssFeed migration
	public function rssFeed($truncate=0){
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE ExpressBuzz_RssFeed");
		endif;
		$data = [];
		$query = $this->PDO->query('select * from  ExpressBuzz_RssFeed');
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array('RssFeedID' => $result['RssFeedID'] , 'URL' => $result['URL'] , 'DateTime' => $result['DateTime'] , 'Status' => $result['Status'] , 'CreatedBy' => $result['CreatedBy'] , 'CreatedDate' => $this->mysql_date($result['CreatedDate']) , 'ModifiedBy' => $result['ModifiedBy'] , 'ModifiedDate' => $this->mysql_date($result['ModifiedDate']));
			array_push($data , $temp);
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('ExpressBuzz_RssFeed' ,$data);
			echo 'Response : '.$response;
	}
	
	
	// ExpressBuzz_RssSource migration
	public function rssSource($truncate=0){
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE ExpressBuzz_RssSource");
		endif;
		$data = [];
		$query = $this->PDO->query('select * from  ExpressBuzz_RssSource');
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array('RssSourceID' => $result['RssSourceID'] , 'URLSource' => $result['URLSource'] , 'DateTime' => $result['DateTime'] , 'Status' => $result['Status'] , 'CreatedBy' => $result['CreatedBy'] , 'CreatedDate' => $this->mysql_date($result['CreatedDate']) , 'ModifiedBy' => $result['ModifiedBy'] , 'ModifiedDate' => $this->mysql_date($result['ModifiedDate']));
			array_push($data , $temp);
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('ExpressBuzz_RssSource' ,$data);
			echo 'Response : '.$response;
	}
	
	
	// ExpressBuzz_Section migration
	public function section($truncate=0){
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE ExpressBuzz_Section");
		endif;
		$data = [];
		$query = $this->PDO->query('select * from  ExpressBuzz_Section');
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array();
			$temp['SectionID'] = $result['SectionID'];
			$temp['SectionName'] = $result['SectionName'];
			$temp['SectionParentID'] = $result['SectionParentID'];
			$temp['BannerBlockID'] = $result['BannerBlockID'];
			$temp['ContrastingSectionID'] = $result['ContrastingSectionID'];
			$temp['Status'] = $result['Status'];
			$temp['SortOrder'] = $result['SortOrder'];
			$temp['TemplatePath'] = $result['TemplatePath'];
			$temp['ArticlesActive'] = $result['ArticlesActive'];
			$temp['TabEnabled'] = $result['TabEnabled'];
			$temp['CreatedBy'] = $result['CreatedBy'];
			$temp['CreatedDate'] = $this->mysql_date($result['CreatedDate']);
			$temp['ModifiedBy'] = $result['ModifiedBy'];
			$temp['ModifiedDate'] = $this->mysql_date($result['ModifiedDate']);
			$temp['Description'] = $result['Description'];
			$temp['sectioncount'] = $result['sectioncount'];
			$temp['MetaTitle'] = $result['MetaTitle'];
			$temp['MetaDescription'] = $result['MetaDescription'];
			$temp['MetaTags'] = $result['MetaTags'];
			$temp['Mode'] = $result['Mode'];
			array_push($data , $temp);
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('ExpressBuzz_Section' ,$data);
			echo 'Response : '.$response;
	}
	
	
	// ExpressBuzz_Session migration
	public function session($truncate=0){
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE ExpressBuzz_Session");
		endif;
		$data = [];
		$query = $this->PDO->query('select * from  ExpressBuzz_Session');
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array('SessionID' => $result['SessionID'] , 'IPaddress' => $result['IPaddress'] , 'SessionHost' => $result['SessionHost'] , 'HTTPReferer' => $result['HTTPReferer'] , 'RequestedURL' => $result['RequestedURL'] , 'HTTPUserAgent' => $result['HTTPUserAgent'] , 'SessionStartTime' => $this->mysql_date($result['SessionStartTime']) , 'SessionEndTime' => $this->mysql_date($result['SessionEndTime']));
			array_push($data , $temp);
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('ExpressBuzz_Session' ,$data);
			echo 'Response : '.$response;
	}
	
	
	// ExpressBuzz_Settings migration
	public function settings1($truncate=0){
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE ExpressBuzz_Settings");
		endif;
		$data = [];
		$query = $this->PDO->query('select * from  ExpressBuzz_Settings');
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array();
			$temp['SettingsID'] = $result['SettingsID'];
			$temp['GalleryInterval'] = $result['GalleryInterval'];
			$temp['PhotoGalleryInterval'] = $result['PhotoGalleryInterval'];
			$temp['TitleSize'] = $result['TitleSize'];
			$temp['TitleFont'] = $result['TitleFont'];
			$temp['HeadLine1Size'] = $result['HeadLine1Size'];
			$temp['HeadLine1Font'] = $result['HeadLine1Font'];
			$temp['HeadLine2Size'] = $result['HeadLine2Size'];
			$temp['HeadLine2Font'] = $result['HeadLine2Font'];
			$temp['DetailSize'] = $result['DetailSize'];
			$temp['DetailFont'] = $result['DetailFont'];
			$temp['AuthorNameSize'] = $result['AuthorNameSize'];
			$temp['AuthorNameFont'] = $result['AuthorNameFont'];
			$temp['PublishDateSize'] = $result['PublishDateSize'];
			$temp['PublishDateFont'] = $result['PublishDateFont'];
			$temp['ModifiedBy'] = $result['ModifiedBy'];
			$temp['ModifiedDate'] = $this->mysql_date($result['ModifiedDate']);
		
			array_push($data , $temp);
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('ExpressBuzz_Settings' ,$data);
			echo 'Response : '.$response;
	}
	
	// ExpressBuzz_Tab migration
	public function tab($truncate=0){
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE ExpressBuzz_Tab");
		endif;
		$data = [];
		$query = $this->PDO->query('select * from  ExpressBuzz_Tab');
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array();
			$temp['TabID'] = $result['TabID'];
			$temp['TabName'] = $result['TabName'];
			$temp['Status'] = $result['Status'];	
			array_push($data , $temp);
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('ExpressBuzz_Tab' ,$data);
			echo 'Response : '.$response;
	}
	
	
	// ExpressBuzz_TabSectionRelationship migration
	public function tabSectionRelationship($truncate=0){
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE ExpressBuzz_TabSectionRelationship");
		endif;
		$data = [];
		$query = $this->PDO->query('select * from  ExpressBuzz_TabSectionRelationship');
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array();
			$temp['TabSectionRelationship'] = $result['TabSectionRelationship'];
			$temp['TabID'] = $result['TabID'];
			$temp['SectionID'] = $result['SectionID'];	
			array_push($data , $temp);
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('ExpressBuzz_TabSectionRelationship' ,$data);
			echo 'Response : '.$response;
	}
	
	
	// ExpressBuzz_Theme migration
	public function theme($truncate=0){
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE ExpressBuzz_Theme");
		endif;
		$data = [];
		$query = $this->PDO->query('select * from  ExpressBuzz_Theme');
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array();
			$temp['ThemeID'] = $result['ThemeID'];
			$temp['ThemeName'] = $result['ThemeName'];
			$temp['ModifiedBy'] = $result['ModifiedBy'];	
			$temp['ModifiedDate'] =$this->mysql_date($result['ModifiedDate']);	
			array_push($data , $temp);
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('ExpressBuzz_Theme' ,$data);
			echo 'Response : '.$response;
	}
	
	
	// ExpressBuzz_User migration
	public function user($truncate=0){
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE ExpressBuzz_User");
		endif;
		$data = [];
		$query = $this->PDO->query('select * from  ExpressBuzz_User');
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array();
			$temp['UserID'] = $result['UserID'];
			$temp['FirstName'] = $result['FirstName'];
			$temp['SecondName'] = $result['SecondName'];	
			$temp['LoginEmail'] = $result['LoginEmail'];	
			$temp['Password'] = $result['Password'];	
			$temp['BirthDay'] = $result['BirthDay'];	
			$temp['IPAddress'] = $result['IPAddress'];	
			$temp['RoleID'] = $result['RoleID'];	
			$temp['Status'] = $result['Status'];	
			$temp['CreatedDate'] =$this->mysql_date($result['CreatedDate']);	
			$temp['ModifiedDate'] =$this->mysql_date($result['ModifiedDate']);	
			array_push($data , $temp);
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('ExpressBuzz_User' ,$data);
			echo 'Response : '.$response;
	}
	
	
	// kp_article_archive_2013 migration
	public function article_archive_2013($truncate=0){
		//335990
		$this->KPDB->query('SET GLOBAL connect_timeout=28800');
		$this->KPDB->query('SET GLOBAL wait_timeout=28800');
		$this->KPDB->query('SET GLOBAL interactive_timeout=28800');
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE kp_article_archive_2013");
		endif;
		$data = [];
		$query = $this->PDO->prepare('SELECT * FROM
  (SELECT ROW_NUMBER() OVER (ORDER BY ArticleID) AS MyRowNumber, *
  FROM kp_article_archive_2013) tbl
WHERE MyRowNumber BETWEEN 320001 AND 360000');  
		$query->execute();
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array();
			$temp['ArticleID'] = $result['ArticleID'];
			$temp['NewsID'] = $result['NewsID'];
			$temp['SectionID'] = $result['SectionID'];	
			$temp['Status'] = $result['Status'];	
			$temp['Title'] = $result['Title'];	
			$temp['HeadLine30'] = $result['HeadLine30'];	
			$temp['HeadLine1'] = $result['HeadLine1'];	
			$temp['HeadLine2'] = $result['HeadLine2'];	
			$temp['DetailNews'] = $result['DetailNews'];	
			$temp['PublishDate'] = $this->mysql_date($result['PublishDate']);
			$temp['ExpiryDate'] = $this->mysql_date($result['ExpiryDate']);
			$temp['ImpressionCount'] = $result['ImpressionCount'];
			$temp['ReadCount'] = $result['ReadCount'];
			$temp['PrintCount'] = $result['PrintCount'];
			$temp['BookmarkCount'] = $result['BookmarkCount'];
			$temp['Emailcount'] = $result['Emailcount'];
			$temp['AuthorID'] = $result['AuthorID'];
			$temp['ApproverID'] = $result['ApproverID'];
			$temp['ThumbnailPhoto'] = $result['ThumbnailPhoto'];
			$temp['LargePhoto'] = $result['LargePhoto'];
			$temp['FlashVsVideo'] = $result['FlashVsVideo'];
			$temp['GalleryID'] = $result['GalleryID'];
			$temp['AuthorBannerValid'] = $result['AuthorBannerValid'];
			$temp['RelatedArticles'] = $result['RelatedArticles'];
			$temp['SEOKeywords'] = $result['SEOKeywords'];
			$temp['CreatedBy'] = $result['CreatedBy'];
			$temp['CreatedDate'] = $this->mysql_date($result['CreatedDate']);
			$temp['ModifiedBy'] = $result['ModifiedBy'];
			$temp['ModifiedDate'] = $this->mysql_date($result['ModifiedDate']);
			$temp['XmlSourcePath'] = $result['XmlSourcePath'];
			$temp['multipleSection'] = $result['multipleSection'];
			$temp['PhotoCaption'] = $result['PhotoCaption'];
			$temp['CommentEnabled'] = $result['CommentEnabled'];
			$temp['Agency'] = $result['Agency'];
			$temp['SortPriority'] = $result['SortPriority'];
			$temp['RelatedTitle'] = $result['RelatedTitle'];
			$temp['VideoCode'] = $result['VideoCode'];
			$temp['SeoSummary'] = $result['SeoSummary'];
			$temp['PhotoAlt'] = $result['PhotoAlt'];
			$temp['URI'] = $result['URI'];
			array_push($data , $temp);
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('kp_article_archive_2013' ,$data);
			echo 'Response : '.$response;
	}
	
	
	// kp_article_archive_2014 migration
	public function article_archive_2014($truncate=0){
		//96994
		$this->KPDB->query('SET GLOBAL connect_timeout=28800');
		$this->KPDB->query('SET GLOBAL wait_timeout=28800');
		$this->KPDB->query('SET GLOBAL interactive_timeout=28800');
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE kp_article_archive_2014");
		endif;
		$data = [];
		$query = $this->PDO->prepare('SELECT * FROM
  (SELECT ROW_NUMBER() OVER (ORDER BY ArticleID) AS MyRowNumber, *
  FROM kp_article_archive_2014) tbl
WHERE MyRowNumber BETWEEN 80001 AND 120000'); 
		$query->execute();
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array();
			$temp['ArticleID'] = $result['ArticleID'];
			$temp['NewsID'] = $result['NewsID'];
			$temp['SectionID'] = $result['SectionID'];	
			$temp['Status'] = $result['Status'];	
			$temp['Title'] = $result['Title'];	
			$temp['HeadLine30'] = $result['HeadLine30'];	
			$temp['HeadLine1'] = $result['HeadLine1'];	
			$temp['HeadLine2'] = $result['HeadLine2'];	
			$temp['DetailNews'] = $result['DetailNews'];	
			$temp['PublishDate'] = $this->mysql_date($result['PublishDate']);
			$temp['ExpiryDate'] = $this->mysql_date($result['ExpiryDate']);
			$temp['ImpressionCount'] = $result['ImpressionCount'];
			$temp['ReadCount'] = $result['ReadCount'];
			$temp['PrintCount'] = $result['PrintCount'];
			$temp['BookmarkCount'] = $result['BookmarkCount'];
			$temp['Emailcount'] = $result['Emailcount'];
			$temp['AuthorID'] = $result['AuthorID'];
			$temp['ApproverID'] = $result['ApproverID'];
			$temp['ThumbnailPhoto'] = $result['ThumbnailPhoto'];
			$temp['LargePhoto'] = $result['LargePhoto'];
			$temp['FlashVsVideo'] = $result['FlashVsVideo'];
			$temp['GalleryID'] = $result['GalleryID'];
			$temp['AuthorBannerValid'] = $result['AuthorBannerValid'];
			$temp['RelatedArticles'] = $result['RelatedArticles'];
			$temp['SEOKeywords'] = $result['SEOKeywords'];
			$temp['CreatedBy'] = $result['CreatedBy'];
			$temp['CreatedDate'] = $this->mysql_date($result['CreatedDate']);
			$temp['ModifiedBy'] = $result['ModifiedBy'];
			$temp['ModifiedDate'] = $this->mysql_date($result['ModifiedDate']);
			$temp['XmlSourcePath'] = $result['XmlSourcePath'];
			$temp['multipleSection'] = $result['multipleSection'];
			$temp['PhotoCaption'] = $result['PhotoCaption'];
			$temp['CommentEnabled'] = $result['CommentEnabled'];
			$temp['Agency'] = $result['Agency'];
			$temp['SortPriority'] = $result['SortPriority'];
			$temp['RelatedTitle'] = $result['RelatedTitle'];
			$temp['VideoCode'] = $result['VideoCode'];
			$temp['SeoSummary'] = $result['SeoSummary'];
			$temp['PhotoAlt'] = $result['PhotoAlt'];
			$temp['URI'] = $result['URI'];
			array_push($data , $temp);
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('kp_article_archive_2014' ,$data);
			echo 'Response : '.$response; 
	}
	
	
	// kp_article_archive_temp migration
	public function article_archive_temp($truncate=0){
		//21999
		$this->KPDB->query('SET GLOBAL connect_timeout=28800');
		$this->KPDB->query('SET GLOBAL wait_timeout=28800');
		$this->KPDB->query('SET GLOBAL interactive_timeout=28800');
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE kp_article_archive_temp");
		endif;
		$data = [];
		$query = $this->PDO->prepare('SELECT * FROM
  (SELECT ROW_NUMBER() OVER (ORDER BY ArticleID) AS MyRowNumber, *
  FROM kp_article_archive_temp) tbl
WHERE MyRowNumber BETWEEN 0 AND 40000');
		$query->execute();
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array();
			$temp['ArticleID'] = $result['ArticleID'];
			$temp['NewsID'] = $result['NewsID'];
			$temp['SectionID'] = $result['SectionID'];	
			$temp['Status'] = $result['Status'];	
			$temp['Title'] = $result['Title'];	
			$temp['HeadLine30'] = $result['HeadLine30'];	
			$temp['HeadLine1'] = $result['HeadLine1'];	
			$temp['HeadLine2'] = $result['HeadLine2'];	
			$temp['DetailNews'] = $result['DetailNews'];	
			$temp['PublishDate'] = $this->mysql_date($result['PublishDate']);
			$temp['ExpiryDate'] = $this->mysql_date($result['ExpiryDate']);
			$temp['ImpressionCount'] = $result['ImpressionCount'];
			$temp['ReadCount'] = $result['ReadCount'];
			$temp['PrintCount'] = $result['PrintCount'];
			$temp['BookmarkCount'] = $result['BookmarkCount'];
			$temp['Emailcount'] = $result['Emailcount'];
			$temp['AuthorID'] = $result['AuthorID'];
			$temp['ApproverID'] = $result['ApproverID'];
			$temp['ThumbnailPhoto'] = $result['ThumbnailPhoto'];
			$temp['LargePhoto'] = $result['LargePhoto'];
			$temp['FlashVsVideo'] = $result['FlashVsVideo'];
			$temp['GalleryID'] = $result['GalleryID'];
			$temp['AuthorBannerValid'] = $result['AuthorBannerValid'];
			$temp['RelatedArticles'] = $result['RelatedArticles'];
			$temp['SEOKeywords'] = $result['SEOKeywords'];
			$temp['CreatedBy'] = $result['CreatedBy'];
			$temp['CreatedDate'] = $this->mysql_date($result['CreatedDate']);
			$temp['ModifiedBy'] = $result['ModifiedBy'];
			$temp['ModifiedDate'] = $this->mysql_date($result['ModifiedDate']);
			$temp['XmlSourcePath'] = $result['XmlSourcePath'];
			$temp['multipleSection'] = $result['multipleSection'];
			$temp['PhotoCaption'] = $result['PhotoCaption'];
			$temp['CommentEnabled'] = $result['CommentEnabled'];
			$temp['Agency'] = $result['Agency'];
			$temp['SortPriority'] = $result['SortPriority'];
			$temp['RelatedTitle'] = $result['RelatedTitle'];
			$temp['VideoCode'] = $result['VideoCode'];
			$temp['SeoSummary'] = $result['SeoSummary'];
			$temp['PhotoAlt'] = $result['PhotoAlt'];
			$temp['URI'] = $result['URI'];
			array_push($data , $temp);
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('kp_article_archive_temp' ,$data);
			echo 'Response : '.$response;  
	}
	
	
	// kp_article_special migration
	public function article_special($truncate=0){
		
		$this->KPDB->query('SET GLOBAL connect_timeout=28800');
		$this->KPDB->query('SET GLOBAL wait_timeout=28800');
		$this->KPDB->query('SET GLOBAL interactive_timeout=28800');
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE kp_article_special");
		endif;
		$data = [];
		$query = $this->PDO->prepare('SELECT * FROM
  (SELECT ROW_NUMBER() OVER (ORDER BY ArticleID) AS MyRowNumber, *
  FROM kp_article_special) tbl
WHERE MyRowNumber BETWEEN 0 AND 20000');
		$query->execute();
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array();
			$temp['ArticleID'] = $result['ArticleID'];
			$temp['NewsID'] = $result['NewsID'];
			$temp['SectionID'] = $result['SectionID'];	
			$temp['Status'] = $result['Status'];	
			$temp['Title'] = $result['Title'];	
			$temp['HeadLine30'] = $result['HeadLine30'];	
			$temp['HeadLine1'] = $result['HeadLine1'];	
			$temp['HeadLine2'] = $result['HeadLine2'];	
			$temp['DetailNews'] = $result['DetailNews'];	
			$temp['PublishDate'] = $this->mysql_date($result['PublishDate']);
			$temp['ExpiryDate'] = $this->mysql_date($result['ExpiryDate']);
			$temp['ImpressionCount'] = $result['ImpressionCount'];
			$temp['ReadCount'] = $result['ReadCount'];
			$temp['PrintCount'] = $result['PrintCount'];
			$temp['BookmarkCount'] = $result['BookmarkCount'];
			$temp['Emailcount'] = $result['Emailcount'];
			$temp['AuthorID'] = $result['AuthorID'];
			$temp['ApproverID'] = $result['ApproverID'];
			$temp['ThumbnailPhoto'] = $result['ThumbnailPhoto'];
			$temp['LargePhoto'] = $result['LargePhoto'];
			$temp['FlashVsVideo'] = $result['FlashVsVideo'];
			$temp['GalleryID'] = $result['GalleryID'];
			$temp['AuthorBannerValid'] = $result['AuthorBannerValid'];
			$temp['RelatedArticles'] = $result['RelatedArticles'];
			$temp['SEOKeywords'] = $result['SEOKeywords'];
			$temp['CreatedBy'] = $result['CreatedBy'];
			$temp['CreatedDate'] = $this->mysql_date($result['CreatedDate']);
			$temp['ModifiedBy'] = $result['ModifiedBy'];
			$temp['ModifiedDate'] = $this->mysql_date($result['ModifiedDate']);
			$temp['XmlSourcePath'] = $result['XmlSourcePath'];
			$temp['multipleSection'] = $result['multipleSection'];
			$temp['PhotoCaption'] = $result['PhotoCaption'];
			$temp['CommentEnabled'] = $result['CommentEnabled'];
			$temp['Agency'] = $result['Agency'];
			$temp['SortPriority'] = $result['SortPriority'];
			$temp['RelatedTitle'] = $result['RelatedTitle'];
			$temp['VideoCode'] = $result['VideoCode'];
			$temp['SeoSummary'] = $result['SeoSummary'];
			$temp['PhotoAlt'] = $result['PhotoAlt'];
			$temp['URI'] = $result['URI'];
			array_push($data , $temp);
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('kp_article_special' ,$data);
			echo 'Response : '.$response;  
	}
	
	
	// kp_assemblypoll migration
	public function assemblypoll($truncate=0){
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE kp_assemblypoll");
		endif;
		$data = [];
		$query = $this->PDO->query('SELECT * FROM  kp_assemblypoll'); 
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array();
			$temp['aid'] = $result['aid'];
			$temp['name1'] = $result['name1'];
			$temp['lead1'] = $result['lead1'];	
			$temp['win1'] =  $result['win1'];	
			$temp['name2'] = $result['name2'];	
			$temp['lead2'] = $result['lead2'];	
			$temp['win2'] =  $result['win2'];	
			$temp['name3'] = $result['name3'];	
			$temp['lead3'] = $result['lead3'];	
			$temp['win3'] =  $result['win3'];
			$temp['name4'] = $result['name4'];
			$temp['lead4'] = $result['lead4'];
			$temp['win4'] =  $result['win4'];
			$temp['name5'] = $result['name5'];
			$temp['lead5'] = $result['lead5'];
			$temp['win5'] =  $result['win5'];
			$temp['name6'] = $result['name6'];
			$temp['lead6'] = $result['lead6'];
			$temp['win6'] =  $result['win6'];
			$temp['name7'] = $result['name7'];
			$temp['lead7'] = $result['lead7'];
			$temp['win7'] =  $result['win7'];
			$temp['name8'] = $result['name8'];
			$temp['lead8'] = $result['lead8'];
			$temp['win8'] =  $result['win8'];
			$temp['name9'] = $result['name9'];
			$temp['lead9'] = $result['lead9'];
			$temp['win9'] =  $result['win9'];
			$temp['name10'] =$result['name10'];
			$temp['lead10'] =$result['lead10'];
			$temp['win10'] = $result['win10'];
			array_push($data , $temp);
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('kp_assemblypoll' ,$data);
			echo 'Response : '.$response;  
	}
	
	
	// kp_loksabhapoll migration
	public function loksabhapoll($truncate=0){
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE kp_loksabhapoll");
		endif;
		$data = [];
		$query = $this->PDO->query('SELECT * FROM  kp_loksabhapoll'); 
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array();
			$temp['id'] = $result['id'];
			$temp['w1'] = $result['w1'];
			$temp['a1'] = $result['a1'];	
			$temp['w2'] =  $result['w2'];	
			$temp['a2'] = $result['a2'];	
			$temp['w3'] = $result['w3'];	
			$temp['a3'] =  $result['a3'];	
			$temp['w4'] = $result['w4'];	
			$temp['a4'] = $result['a4'];	
			$temp['w5'] =  $result['w5'];
			$temp['a5'] = $result['a5'];
			$temp['w6'] = $result['w6'];
			$temp['a6'] =  $result['a6'];
			$temp['w7'] = $result['w7'];
			$temp['a7'] = $result['a7'];
			$temp['w8'] =  $result['w8'];
			$temp['a8'] = $result['a8'];
			$temp['w9'] = $result['w9'];
			$temp['a9'] =  $result['a9'];
			$temp['w10'] = $result['w10'];
			$temp['a10'] = $result['a10'];
			$temp['w11'] =  $result['w11'];
			$temp['a11'] = $result['a11'];
			$temp['w12'] = $result['w12'];
			$temp['a12'] =  $result['a12'];
			$temp['w13'] = $result['w13'];
			$temp['a13'] = $result['a13'];
			$temp['w14'] =  $result['w14'];
			$temp['a14'] =$result['a14'];
			$temp['a15'] =$result['a15'];
			$temp['w16'] = $result['w16'];
			$temp['a16'] = $result['a16'];
			array_push($data , $temp);
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('kp_loksabhapoll' ,$data);
			echo 'Response : '.$response;  
	}
	
	
	// kp_loksabhapollnew migration
	public function loksabhapollnew($truncate=0){
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE kp_loksabhapollnew");
		endif;
		$data = [];
		$query = $this->PDO->query('SELECT * FROM  kp_loksabhapollnew'); 
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array();
			$temp['pid'] = $result['pid'];
			$temp['name1'] = $result['name1'];
			$temp['lead1'] = $result['lead1'];	
			$temp['win1'] =  $result['win1'];	
			$temp['name2'] = $result['name2'];	
			$temp['lead2'] = $result['lead2'];	
			$temp['win2'] =  $result['win2'];	
			$temp['name3'] = $result['name3'];	
			$temp['lead3'] = $result['lead3'];	
			$temp['win3'] =  $result['win3'];
			$temp['name4'] = $result['name4'];
			$temp['lead4'] = $result['lead4'];
			$temp['win4'] =  $result['win4'];
			$temp['name5'] = $result['name5'];
			$temp['lead5'] = $result['lead5'];
			$temp['win5'] =  $result['win5'];
			$temp['name6'] = $result['name6'];
			$temp['lead6'] = $result['lead6'];
			$temp['win6'] =  $result['win6'];
			$temp['name7'] = $result['name7'];
			$temp['lead7'] = $result['lead7'];
			$temp['win7'] =  $result['win7'];
			$temp['name8'] = $result['name8'];
			$temp['lead8'] = $result['lead8'];
			$temp['win8'] =  $result['win8'];
			$temp['name9'] = $result['name9'];
			$temp['lead9'] = $result['lead9'];
			$temp['win9'] =  $result['win9'];
			$temp['name10'] =$result['name10'];
			$temp['lead10'] =$result['lead10'];
			$temp['win10'] = $result['win10'];
			$temp['name11'] = $result['name11'];
			$temp['lead11'] = $result['lead11'];
			$temp['win11'] = $result['win11'];
			$temp['name12'] = $result['name12'];
			$temp['lead12'] = $result['lead12'];
			$temp['win12'] = $result['win12'];
			$temp['name13'] = $result['name13'];
			$temp['lead13'] = $result['lead13'];
			$temp['win13'] = $result['win13'];
			$temp['name14'] = $result['name14'];
			$temp['lead14'] = $result['lead14'];
			$temp['win14'] = $result['win14'];
			$temp['name15'] = $result['name15'];
			$temp['lead15'] = $result['lead15'];
			$temp['win15'] = $result['win15'];
			$temp['name16'] = $result['name16'];
			$temp['lead16'] = $result['lead16'];
			$temp['win16'] = $result['win16'];
			$temp['name17'] = $result['name17'];
			$temp['lead17'] = $result['lead17'];
			$temp['win17'] = $result['win17'];
			$temp['name18'] = $result['name18'];
			$temp['lead18'] = $result['lead18'];
			$temp['win18'] = $result['win18'];
			$temp['name19'] = $result['name19'];
			$temp['lead19'] = $result['lead19'];
			$temp['win19'] = $result['win19'];
			$temp['name20'] = $result['name20'];
			$temp['lead20'] = $result['lead20'];
			$temp['win20'] = $result['win20'];
			$temp['name21'] = $result['name21'];
			$temp['lead21'] = $result['lead21'];
			$temp['win21'] = $result['win21'];
			$temp['name22'] = $result['name22'];
			$temp['lead22'] = $result['lead22'];
			$temp['win22'] = $result['win22'];
			$temp['name23'] = $result['name23'];
			$temp['lead23'] = $result['lead23'];
			$temp['win23'] = $result['win23'];
			$temp['name24'] = $result['name24'];
			$temp['lead24'] = $result['lead24'];
			$temp['win24'] = $result['win24'];
			$temp['name25'] = $result['name25'];
			$temp['lead25'] = $result['lead25'];
			$temp['win25'] = $result['win25'];
			$temp['name26'] = $result['name26'];
			$temp['lead26'] = $result['lead26'];
			$temp['win26'] = $result['win26'];
			$temp['name27'] = $result['name27'];
			$temp['lead27'] = $result['lead27'];
			$temp['win27'] = $result['win27'];
			$temp['name28'] = $result['name28'];
			$temp['lead28'] = $result['lead28'];
			$temp['win28'] = $result['win28'];
			$temp['name29'] = $result['name29'];
			$temp['lead29'] = $result['lead29'];
			$temp['win29'] = $result['win29'];
			$temp['name30'] = $result['name30'];
			$temp['lead30'] = $result['lead30'];
			$temp['win30'] = $result['win30'];
			array_push($data , $temp); 
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('kp_loksabhapollnew' ,$data);
			echo 'Response : '.$response;  
	}
	
	
	
	// kp_opinionpoll migration
	public function opinionpoll($truncate=0){
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE kp_opinionpoll");
		endif;
		$data = [];
		$query = $this->PDO->query('SELECT * FROM  kp_opinionpoll'); 
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array();
			$temp['PollID'] = $result['PollID'];
			$temp['QuestionEng'] = $result['QuestionEng'];
			$temp['Question'] = $result['Question'];	
			$temp['Option1'] =  $result['Option1'];	
			$temp['Option2'] = $result['Option2'];	
			$temp['Option3'] = $result['Option3'];	
			$temp['Polled1'] =  $result['Polled1'];	
			$temp['Polled2'] = $result['Polled2'];	
			$temp['Polled3'] = $result['Polled3'];	
			$temp['PollImage'] =  $result['PollImage'];
			$temp['Status'] = $result['Status'];
			$temp['SeoSummary'] = $result['SeoSummary'];
			$temp['PublishDate'] =  $this->mysql_date($result['PublishDate']);
			array_push($data , $temp); 
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('kp_opinionpoll' ,$data);
			echo 'Response : '.$response;  
	}
	
	
	// kp_scroll migration
	public function scroll($truncate=0){
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE kp_scroll");
		endif;
		$data = [];
		$query = $this->PDO->query('SELECT * FROM  kp_scroll'); 
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array();
			$temp['scrollid'] = $result['scrollid'];
			$temp['scrolltext'] = $result['scrolltext'];
			$temp['scrollurl'] = $result['scrollurl'];	
			$temp['status'] =  $result['status'];	
			$temp['priority'] = $result['priority'];	
			$temp['scrolltype'] = $result['scrolltype'];	
			$temp['publishdate'] =  $this->mysql_date($result['publishdate']);
			array_push($data , $temp); 
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('kp_scroll' ,$data);
			echo 'Response : '.$response;  
	}
	
	
	// kp_temp migration
	public function temp($truncate=0){
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE kp_temp");
		endif;
		$data = [];
		$query = $this->PDO->query('SELECT * FROM  kp_temp'); 
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array();
			$temp['ArticleID'] = $result['ArticleID'];
			$temp['NewsID'] = $result['NewsID'];
			$temp['SectionID'] = $result['SectionID'];	
			$temp['Status'] =  $result['Status'];	
			$temp['Title'] = $result['Title'];	
			$temp['HeadLine30'] = $result['HeadLine30'];	
			$temp['HeadLine1'] = $result['HeadLine1'];	
			$temp['HeadLine2'] = $result['HeadLine2'];	
			$temp['DetailNews'] = $result['DetailNews'];	
			$temp['PublishDate'] =  $this->mysql_date($result['PublishDate']);
			$temp['ExpiryDate'] =  $this->mysql_date($result['ExpiryDate']);
			$temp['ImpressionCount'] = $result['ImpressionCount'];
			$temp['ReadCount'] = $result['ReadCount'];
			$temp['PrintCount'] = $result['PrintCount'];
			$temp['BookmarkCount'] = $result['BookmarkCount'];
			$temp['Emailcount'] = $result['Emailcount'];
			$temp['AuthorID'] = $result['AuthorID'];
			$temp['ApproverID'] = $result['ApproverID'];
			$temp['ThumbnailPhoto'] = $result['ThumbnailPhoto'];
			$temp['LargePhoto'] = $result['LargePhoto'];
			$temp['FlashVsVideo'] = $result['FlashVsVideo'];
			$temp['GalleryID'] = $result['GalleryID'];
			$temp['AuthorBannerValid'] = $result['AuthorBannerValid'];
			$temp['RelatedArticles'] = $result['RelatedArticles'];
			$temp['SEOKeywords'] = $result['SEOKeywords'];
			$temp['CreatedBy'] = $result['CreatedBy'];
			$temp['CreatedDate'] = $this->mysql_date($result['CreatedDate']);
			$temp['ModifiedBy'] = $result['ModifiedBy'];
			$temp['ModifiedDate'] = $this->mysql_date($result['ModifiedDate']);
			$temp['XmlSourcePath'] = $result['XmlSourcePath'];
			$temp['multipleSection'] = $result['multipleSection'];
			$temp['PhotoCaption'] = $result['PhotoCaption'];
			$temp['CommentEnabled'] = $result['CommentEnabled'];
			$temp['Agency'] = $result['Agency'];
			$temp['SortPriority'] = $result['SortPriority'];
			array_push($data , $temp); 
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('kp_temp' ,$data);
			echo 'Response : '.$response;  
	}
	
	
	// kp_ticker migration
	public function ticker($truncate=0){
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE kp_ticker");
		endif;
		$data = [];
		$query = $this->PDO->query('SELECT * FROM  kp_ticker'); 
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array();
			$temp['TickerID'] = $result['TickerID'];
			$temp['TickerText'] = $result['TickerText'];
			$temp['TickerURL'] = $result['TickerURL'];	
			$temp['Status'] =  $result['Status'];	
			$temp['Priority'] = $result['Priority'];	
			$temp['ImagePath'] = $result['ImagePath'];	
			array_push($data , $temp); 
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('kp_ticker' ,$data);
			echo 'Response : '.$response;  
	}
	
	
	// opinion_poll migration
	public function opinion_poll($truncate=0){
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE opinion_poll");
		endif;
		$data = [];
		$query = $this->PDO->query('SELECT * FROM  opinion_poll'); 
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array();
			$temp['id'] = $result['id'];
			$temp['posteddate'] = $this->mysql_date($result['posteddate']);
			$temp['question'] = $result['question'];	
			$temp['option1'] =  $result['option1'];	
			$temp['option2'] = $result['option2'];	
			$temp['option3'] = $result['option3'];	
			$temp['polled1'] = $result['polled1'];	
			$temp['polled2'] = $result['polled2'];	
			$temp['polled3'] = $result['polled3'];	
			$temp['latest'] = $result['latest'];	
			$temp['questioneng'] = $result['questioneng'];	
			$temp['pollimage'] = $result['pollimage'];	
			array_push($data , $temp); 
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('opinion_poll' ,$data);
			echo 'Response : '.$response;  
	}
	
	// Expressbuzz_Video migration
	public function expressbuzz_video($truncate=0){
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE Expressbuzz_Video");
		endif;
		$data = [];
		$query = $this->PDO->query('SELECT * FROM  newsindia.Expressbuzz_Video'); 
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array();
			$temp['VideoID'] = $result['VideoID'];
			$temp['CatID'] = $result['CatID'];
			$temp['CatName'] = $result['CatName'];	
			$temp['SubCatID'] =  $result['SubCatID'];	
			$temp['SubCatName'] = $result['SubCatName'];	
			$temp['Title'] = $result['Title'];	
			$temp['Headline1'] =  $result['Headline1'];
			$temp['Headline2'] =  $result['Headline2'];
			$temp['Byline'] =  $result['Byline'];
			$temp['SortOrder'] =  $result['SortOrder'];
			$temp['VideoCode'] =  $result['VideoCode'];
			$temp['VideoCaption'] =  $result['VideoCaption'];
			$temp['PublishedDate'] =  $this->mysql_date($result['PublishedDate']);
			$temp['ExpiryDate'] =  $this->mysql_date($result['ExpiryDate']);
			$temp['ThumbImage'] =  $result['ThumbImage'];
			$temp['Keywords'] =  $result['Keywords'];
			$temp['Status'] =  $result['Status'];
			$temp['Genre'] =  $result['Genre'];
			array_push($data , $temp); 
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('Expressbuzz_Video' ,$data);
			echo 'Response : '.$response;  
	}
	
	// Expressbuzz_VideoGroup migration
	public function expressbuzz_videogroup($truncate=0){
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE Expressbuzz_VideoGroup");
		endif;
		$data = [];
		$query = $this->PDO->query('SELECT * FROM  newsindia.Expressbuzz_VideoGroup'); 
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array();
			$temp['ID'] = $result['ID'];
			$temp['Name'] = $result['Name'];
			$temp['SortOrder'] =  $result['SortOrder'];	
			$temp['Status'] = $result['Status'];
			$temp['CreatedDate'] =  $this->mysql_date($result['CreatedDate']);			
			$temp['Description'] = $result['Description'];	
			array_push($data , $temp); 
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('Expressbuzz_VideoGroup' ,$data);
			echo 'Response : '.$response;  
	}
	
	
	// Expressbuzz_VideoSubGroup migration
	public function expressbuzz_videosubgroup($truncate=0){
		if($truncate==1):
			$this->KPDB->query("TRUNCATE TABLE Expressbuzz_VideoSubGroup");
		endif;
		$data = [];
		$query = $this->PDO->query('SELECT * FROM  newsindia.Expressbuzz_VideoSubGroup'); 
		foreach($query->fetchAll(PDO::FETCH_ASSOC) as $result):
			$temp = array();
			$temp['ID'] = $result['ID'];
			$temp['Name'] = $result['Name'];
			$temp['SortOrder'] =  $result['SortOrder'];	
			$temp['Status'] = $result['Status'];
			$temp['CreatedDate'] =  $this->mysql_date($result['CreatedDate']);			
			$temp['Description'] = $result['Description'];	
			$temp['ParentCatID'] = $result['ParentCatID'];	
			array_push($data , $temp); 
		endforeach;
		if(count($data) > 0)
			$response = $this->KPDB->insert_batch('Expressbuzz_VideoSubGroup' ,$data);
			echo 'Response : '.$response;  
	}
	
	
	
	
	
}
?> 