<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/
$route['default_controller'] = folder_name."/clog";

//$route['default_controller'] = 'user/home_controller';
$route[folder_name] = folder_name."/common";
$route['user/commonwidget/(:any)'] = "user/commonwidget/$1";
$route['api/app_api/(:any)'] = "api/app_api/$1";
$route['api/app_api'] = "api/app_api";

//$route['Columns'] = folder_name.'/template_designer/load_saved_template/$1';
$route['404_override'] = '';

$route[folder_name.'/video'] = folder_name.'/audio_video/create_audio_video/4';
$route[folder_name.'/audio'] = folder_name.'/audio_video/create_audio_video/5';

$route[folder_name.'/edit_video/(:any)'] = folder_name."/audio_video_manager/4/$1";
$route[folder_name.'/edit_audio/(:any)'] = folder_name."/audio_video_manager/5/$1";

$route[folder_name.'/edit_article/(:any)'] = folder_name."/article_manager/edit_article/$1";
$route[folder_name.'/edit_archive_article/(:any)'] = folder_name."/article_manager/edit_archive_article/$1";
$route[folder_name.'/edit_gallery/(:any)'] = folder_name."/gallery_manager/edit_gallery/$1";
$route[folder_name.'/edit_archive_gallery/(:any)'] = folder_name."/gallery_manager/edit_archive_gallery/$1";
$route[folder_name.'/edit_video/(:any)'] = folder_name."/audio_video_manager/edit_audio_video/4/$1";
$route[folder_name.'/edit_audio/(:any)'] = folder_name."/audio_video_manager/edit_audio_video/5/$1";

$route[folder_name.'/edit_archive_video/(:any)'] = folder_name."/audio_video_manager/edit_archive_audio_video/4/$1";
$route[folder_name.'/edit_archive_audio/(:any)'] = folder_name."/audio_video_manager/edit_archive_audio_video/5/$1";

$route[folder_name.'/edit_image/(:any)'] 	= folder_name."/image_manager/edit_image/$1";
$route[folder_name.'/edit_resources/(:any)'] 	= folder_name."/resources_manager/edit_resources/$1";

$route[folder_name.'/edit_archive_resources/(:any)'] 	= folder_name."/resources_manager/edit_archive_resources/$1";

//$route[folder_name.'/common/(:any)'] 			= folder_name."/common/$1";
//$route[folder_name.'/common/(:any)/(:any)'] 	= folder_name."/common/$1/$2";


$route[folder_name.'/video_manager'] 		= folder_name."/audio_video_manager/audio_video/4";
$route[folder_name.'/audio_manager'] 		= folder_name."/audio_video_manager/audio_video/5";


$route[folder_name.'/byliner_manager'] 		= folder_name."/author_manager/common_author/$1";
$route[folder_name.'/columnist_manager'] 		= folder_name."/author_manager/common_author/$1";

$route[folder_name.'/jumbo_widget_articles'] 		= folder_name."/section_widget_articles";
$route[folder_name.'/editor_pick_articles'] 		= folder_name."/section_widget_articles";
$route[folder_name.'/trending_now_articles'] 		= folder_name."/section_widget_articles";
$route[folder_name.'/related_articles'] 			= folder_name."/section_widget_articles";

$route[folder_name.'/article_image_processing/(:any)'] = folder_name."/image/common_image_processing/article/$1";
$route[folder_name.'/gallery_image_processing/(:any)'] = folder_name."/image/common_image_processing/gallery/$1";
$route[folder_name.'/video_image_processing/(:any)'] = folder_name."/image/common_image_processing/video/$1";
$route[folder_name.'/audio_image_processing/(:any)'] = folder_name."/image/common_image_processing/audio/$1";
$route[folder_name.'/image_processing/(:any)'] = folder_name."/image/common_image_processing/image/$1";
$route[folder_name.'/resource_image_processing/(:any)'] = folder_name."/image/common_image_processing/resource/$1";


$route[folder_name.'/template_designer/(:num)'] = folder_name."/template_designer/index/$1";
$route[folder_name.'/template_designer'] 		 = folder_name."/template_designer";
$route[folder_name.'/template_designer/(:any)'] = folder_name."/template_designer/$1/$2/$3/$4";

$route[folder_name.'/clog'] = folder_name."/clog";
$route[folder_name.'/clog/(:any)'] = folder_name."/clog/$1";

$route[folder_name.'/(:any)'] 	= folder_name."/$1";
$route['cronjob/(:any)'] 	= "cronjob/$1";
$route['uploads/(:any)'] 	= "uploads/$1";
$route['images/(:any)'] 	= "images/$1";

////////////////////////////

/////  Live site Routing  ////

$route['(:any)/rssfeed']  = 'user/rss_controller';

$route['sitemap.xml'] 		= "user/rss_controller/sitemap";
$route['(:any).xml'] 		= "user/rss_controller/section_year_sitemap";
//$route['(:any).ece']            = 'user/article_controller';
//$route['(:any)/article(:any).ece(:any)']      = 'user/article_controller';

/* old url to new url redirection */
$route['photo-gallery/(:any)/(:num).html'] = 'user/redirect_controller/gallery';
$route['video/player/(:any)/(:num).html'] = 'user/redirect_controller/video';
$route['(:any)/(:any)/(:any)/(:num).html'] = 'user/redirect_controller';
$route['(:any)/(:any)/(:num).html'] = 'user/redirect_controller';

$route['(:any).html']     = 'user/article_controller';
$route['(:any).amp']     = 'user/amp_controller';
$route['(:any)'] 		  = 'user/section_controller';




/* End of file routes.php */
/* Location: ./application/config/routes.php */