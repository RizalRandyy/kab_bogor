<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/

$hook['post_controller_constructor'][] = array(
    'class'    => 'Privilege_hook',
    'function' => 'Privilege_check',
    'filename' => 'Privilege_hook.php',
    'filepath' => 'hooks',
    'params'   => array()
);

$hook['post_controller_constructor'][] = array(
	'class' => 'Logusers',
	'function' => 'tracking_user_method',
	'filename' => 'Logusers.php',
	'filepath' => 'hooks'
);