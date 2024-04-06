<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'login';
$route['404_override'] = 'errors';


$route['translate_uri_dashes'] = FALSE;
/*
| -------------------------------------------------------------------------
| Web App Development Routes
| -------------------------------------------------------------------------
*/
$route['login']   = "login";
// $route['profile'] = "panel/profile";

// $route['contact'] = "main/contact";
// $route['news'] = "main/news";

// $route['main'] = "china/study_in_china";

// $route['study-in-china'] = "china/study_in_china";
// $route['study-in-china/news'] = "china/study_in_china/news";
// $route['study-in-china/contact'] = "china/study_in_china/contact";
// $route['study-in-china/courses/(:any)'] = "china/study_in_china/courses/$1";
// $route['study-in-china/universities/(:any)'] = "china/study_in_china/universities/$1";
// $route['study-in-china/scholarships/(:any)'] = "china/study_in_china/scholarships/$1";
// $route['study-in-china/features/(:any)'] = "china/study_in_china/features/$1";
// $route['study-in-china/download'] = "china/study_in_china/download";
// $route['study-in-china/apply'] = "china/study_in_china/apply";


// $route['tesol-tefl'] = "tesol/tesol_study";
// $route['tesol-tefl/news'] = "tesol/tesol_study/news";
// $route['tesol-tefl/contact'] = "tesol/tesol_study/contact";
// $route['tesol-tefl/apply'] = "tesol/tesol_study/apply";
// $route['tesol-tefl/apply/(:any)'] = "tesol/tesol_study/apply/$1";
// $route['tesol-tefl/apply_form'] = "tesol/tesol_study/apply_form";
// $route['tesol-tefl/purchase'] = "tesol/tesol_study/purchase";
// $route['tesol-tefl/paynow'] = "tesol/tesol_study/paynow";
// $route['tesol-tefl/thankyou'] = "tesol/tesol_study/thank";
// $route['tesol-tefl/cancel'] = "tesol/tesol_study/cancel";