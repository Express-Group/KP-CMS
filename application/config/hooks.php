<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/


$hook['pre_controller'][] 	= array(
                                'class'    => 'Check_Login',
                                'function' => 'IsLoggin',
                                'filename' => 'check_login.php',
                                'filepath' => 'hooks',
                                'params'   => ''
                             );
								
$hook['pre_system'] 		= array(
								'class' 	=> 'PHPFatalError',
								'function'	=> 'setHandler',
								'filename' 	=> 'PHPFatalError.php',
								'filepath' 	=> 'hooks'
							);	
$hook['display_override'] = array(
  'class'    => 'Strip_slasses',
  'function' => 'common',
  'filename' => 'strip_slasses.php',
  'filepath' => 'hooks'
);								
/* End of file hooks.php */
/* Location: ./application/config/hooks.php */