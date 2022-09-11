<?php
class gallery_migration extends CI_Controller{
	
	public function __construct(){
		parent::__construct();
		$CI = &get_instance();
		$this->load->database();
		$this->kp_migration = $this->load->database('kp_migration' , TRUE);
		$this->kp_archive = $this->load->database('archive_db' , TRUE);
		$this->live_db = $this->load->database('live_db' , TRUE);
	}
	
	public function index(){
		$gallery = $this->kp_migration->query("SELECT GalleryID , CaptionName , GalleryGroupID , CreatedDate , ModifiedBy , ModifiedDate , ThumbImage , seo , GalDescription FROM ExpressBuzz_Gallery ORDER BY GalleryID ASC LIMIT 1")->result_array();
		$currentYear = date('Y');
		foreach($gallery as $gallerydata){
			$temp =[];
			$publishedYear = date('Y' , strtotime($gallerydata['CreatedDate']));
			if($publishedYear < $currentYear){
				$this->create_table($publishedYear);
			}
			
		}
	}
	
	public function create_table($year){
		$tableName = 'gallery_'.$year;
		$sectionMappingTable = 'gallery_section_mapping_'.$year;
		$galleryRealtedTable = 'gallery_related_images_'.$year;
		if(!$this->kp_archive->table_exists($tableName)){
			$this->kp_archive->query('SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO"');
			$this->kp_archive->query('SET time_zone = "+00:00"');
			$this->kp_archive->query("CREATE TABLE {$tableName} (
				  `content_id` mediumint(9) UNSIGNED NOT NULL,
				  `ecenic_id` mediumint(9) DEFAULT NULL,
				  `section_id` smallint(6) DEFAULT NULL,
				  `tag_ids` varchar(255) NOT NULL,
				  `agency_id` tinyint(4) UNSIGNED DEFAULT NULL,
				  `author_id` smallint(6) UNSIGNED DEFAULT NULL,
				  `country_id` smallint(6) UNSIGNED DEFAULT NULL,
				  `state_id` smallint(6) UNSIGNED DEFAULT NULL,
				  `city_id` smallint(6) UNSIGNED DEFAULT NULL,
				  `section_name` varchar(50) CHARACTER SET utf8 NOT NULL,
				  `parent_section_id` smallint(6) DEFAULT NULL,
				  `parent_section_name` varchar(50) CHARACTER SET utf8 NOT NULL,
				  `grant_section_id` smallint(6) DEFAULT NULL,
				  `grant_parent_section_name` varchar(50) CHARACTER SET utf8 NOT NULL,
				  `publish_start_date` datetime NOT NULL,
				  `last_updated_on` datetime NOT NULL,
				  `title` varchar(255) CHARACTER SET utf8 NOT NULL,
				  `url` varchar(255) CHARACTER SET utf8 NOT NULL,
				  `summary_html` text CHARACTER SET utf8 NOT NULL,
				  `first_image_path` varchar(255) NOT NULL,
				  `first_image_title` text CHARACTER SET utf8 NOT NULL,
				  `first_image_alt` text CHARACTER SET utf8 NOT NULL,
				  `hits` mediumint(9) NOT NULL,
				  `tags` varchar(255) CHARACTER SET utf8 NOT NULL,
				  `allow_comments` tinyint(1) NOT NULL,
				  `agency_name` varchar(50) CHARACTER SET utf8 NOT NULL,
				  `author_name` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
				  `country_name` varchar(100) CHARACTER SET utf8 NOT NULL,
				  `state_name` varchar(100) CHARACTER SET utf8 NOT NULL,
				  `city_name` varchar(100) CHARACTER SET utf8 NOT NULL,
				  `no_indexed` tinyint(1) NOT NULL,
				  `no_follow` tinyint(1) NOT NULL,
				  `canonical_url` varchar(255) CHARACTER SET utf8 NOT NULL,
				  `meta_Title` varchar(255) CHARACTER SET utf8 NOT NULL,
				  `meta_description` varchar(255) CHARACTER SET utf8 NOT NULL,
				  `status` char(1) NOT NULL COMMENT 'P - Published, U - Unpublished',
				  `created_by` varchar(250) CHARACTER SET utf8 NOT NULL,
				  `created_on` datetime NOT NULL,
				  `modified_by` varchar(250) CHARACTER SET utf8 NOT NULL,
				  `modified_on` datetime NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=latin1");
			$this->kp_archive->query("ALTER TABLE {$tableName}
				  ADD PRIMARY KEY (`content_id`),
				  ADD KEY `section_id` (`section_id`),
				  ADD KEY `parent_section_id` (`parent_section_id`),
				  ADD KEY `section_id_2` (`section_id`),
				  ADD KEY `grant_section_id` (`grant_section_id`),
				  ADD KEY `publish_start_date` (`publish_start_date`),
				  ADD KEY `ecenic_id` (`ecenic_id`),
				  ADD KEY `agency_id` (`agency_id`,`country_id`,`state_id`,`city_id`),
				  ADD KEY `author_id` (`author_id`)");
		}
		
		if(!$this->kp_archive->table_exists($sectionMappingTable)){
			$this->kp_archive->query('SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO"');
			$this->kp_archive->query('SET time_zone = "+00:00"');
			$this->kp_archive->query("CREATE TABLE {$sectionMappingTable} (
				  `content_id` mediumint(8) UNSIGNED NOT NULL,
				  `section_id` smallint(6) NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=latin1");
			$this->kp_archive->query("ALTER TABLE {$sectionMappingTable}
				  ADD KEY `content_id` (`content_id`,`section_id`),
				  ADD KEY `section_id` (`section_id`);");
			$this->kp_archive->query("ALTER TABLE {$sectionMappingTable}
				  ADD CONSTRAINT `gallery_section_mapping_ibfk_1` FOREIGN KEY (`content_id`) REFERENCES {$tableName} (`content_id`);");
		}
		if(!$this->kp_archive->table_exists($galleryRealtedTable)){
			$this->kp_archive->query('SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO"');
			$this->kp_archive->query('SET time_zone = "+00:00"');
			$this->kp_archive->query("CREATE TABLE {$galleryRealtedTable} (
				  `PrimaryId` int(11) NOT NULL,
				  `content_id` mediumint(8) UNSIGNED NOT NULL,
				  `image_id` mediumint(9) UNSIGNED DEFAULT NULL,
				  `gallery_image_path` varchar(255) NOT NULL,
				  `gallery_image_title` text CHARACTER SET utf8 NOT NULL,
				  `gallery_image_alt` text CHARACTER SET utf8 NOT NULL,
				  `display_order` tinyint(4) NOT NULL
				) ENGINE=InnoDB DEFAULT CHARSET=latin1");
			$this->kp_archive->query("ALTER TABLE {$galleryRealtedTable}
				  ADD PRIMARY KEY (`PrimaryId`),
				  ADD UNIQUE KEY `PrimaryId` (`PrimaryId`),
				  ADD KEY `content_id` (`content_id`),
				  ADD KEY `content_id_2` (`content_id`),
				  ADD KEY `image_id` (`image_id`)");
			$this->kp_archive->query("ALTER TABLE {$galleryRealtedTable}
					MODIFY `PrimaryId` int(11) NOT NULL AUTO_INCREMENT");
			$this->kp_archive->query("ALTER TABLE {$galleryRealtedTable}
					ADD CONSTRAINT `gallery_related_images_{$year}_ibfk_1` FOREIGN KEY (`content_id`) REFERENCES {$tableName} (`content_id`);");
		}
	}
}
?> 