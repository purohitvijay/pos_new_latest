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
$route['default_controller'] = "admin/users/login";
$route['404_override'] = '';

//Module Routing
$route['admin'] = "admin/users/login";

//$route['public'] = "public/index";
$route['tracking'] = "public/index";
$route['smstracking'] = "public/index/smstracking";
$route['public/en'] = "public/index";
$route['public/id'] = "public/index";
$route['(:any)/index/validate'] = "public/index/validate";
$route['(:any)/index/showsmsresult'] = "public/index/showsmsresult";

$route['tracking1'] = "public/index/index1";
$route['tracking/about'] = "public/index/about";
$route['tracking/promo'] = "public/index/promo";
$route['orderTracking'] = "public/index/orderTracking";
$route['orderTracking/en'] = "public/index/orderTracking";
$route['orderTracking/id'] = "public/index/orderTracking";
//$route['orderTracking/id/(:any)'] = "public/index/orderTracking";



$route['imglog'] = "public/Imglog/login";
$route['imglog/validate'] = "public/Imglog/validate";
$route['customer'] = "public/Customer/index";
$route['(:any)/customer/get_customer_by_mobile'] = "public/Customer/get_customer_by_mobile";
$route['(:any)/customer/customer_passport_update'] = "public/Customer/customer_passport_update";

//Admin Module Routing
$route['(:any)/admin'] = "admin/users/login";
//user login and validate routing
$route['(:any)/users'] = "admin/users/index";
$route['(:any)/users/index'] = "admin/users/index";
$route['(:any)/users/logout'] = "admin/users/logout";
$route['(:any)/users/login'] = "admin/users/login";
$route['(:any)/users/login/redirectForcefully'] = "admin/users/login";
$route['(:any)/users/validate'] = "admin/users/validate";


/* End of file routes.php */
/* Location: ./application/config/routes.php */