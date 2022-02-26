<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class My_Controller extends CI_Controller {

        /**
         * $ajaxRequest : this is the variable which contains the requested page is via ajax call or not. by default it is false and will be set as false and will be set as true in constructor after validating the request type.
         *
         */
        public $ajaxRequest = false;
        public $angularRequest = false;
        public $template = NULL;
        public $_user_id = NULL;
        public $_geo_type = NULL;
        public $_session = NULL;
        private $_public_whitelisted = array(
            'index_index',
            'index_index1',
            'index_ordertracking',
            'index_about',
            'index_promo',
            'imglog_login',
            'imglog_validate',
            'customer_index',
            'customer_get_customer_by_mobile',
            'customer_customer_passport_update',
            'index_smstracking',
            'index_showsmsresult',
            'index_result',
            'index_validate',
            'index_downloadjakartaimage',
        );
        
        public function __construct() {
            parent::__construct();

            $this->_session = $this->session->all_userdata();
            /**
             * validating the request type is ajax or not and setting up the $ajaxRequest variable as true/false.
             *
             */
            $requestType = $this->input->server('HTTP_X_REQUESTED_WITH');
            $this->ajaxRequest = strtolower($requestType) == 'xmlhttprequest';
           
            //check for angular
            $arr = getallheaders();
            if(isset($arr['Authorization']))
            {
                $this->angularRequest = TRUE;
                logMsg($this->angularRequest);
            }
            /**
             * set the default template as blank when the request type is ajax
             */
            if ($this->ajaxRequest === true) {
                $this->load->setTemplate('blank');
            }

            $module = $this->router->fetch_module();
            switch ($module) {
                case 'public':
                    $this->load->setTemplate('public');
                    break;
            }
            
            $this->_user_id = $this->session->userdata('id');
            $this->_geo_type = $this->session->userdata('geo_type');
        }

        public function validateMe() {
            echo 'i have been called';
        }

        /**
         * Get paging parameters from GET/config vars.
         *
         * Creates an array of four elements that we can use to send paging/sorting parameter to BL.
         *
         * @param array $sortColumns is an array of grid columns that can be used for sorting.
         *
         * @return array contaning the following elements.
         *
         * offset
         * records_per_page
         * order_by
         * order_direction
         *
         */
        public function _dremap($method) {
            $this->load->library('session');
            $this->load->helper('url');
            if ($this->session->userdata('logged_in') == '') {
                if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == "XMLHttpRequest") {
                    header('X-CI-LoggedOut: 1');
                } else {
                    redirect('admin/users/login');
                }
                return (false);
            }

            $params = array_slice($this->uri->segment_array(), 2);
            if (method_exists($this, $method)) {
                call_user_func_array(array($this, $method), $params);
            } else {
                show_404();
            }
        }

        public function _remap($method, $params = array()) {
           
            $this->load->helper('url');
            $this->load->library('commonlibrary');
            $this->load->library('session');

            //fetch module
            $module = $this->router->fetch_module();

            $controller = $this->router->fetch_class();
            $action = $this->router->fetch_method();

            $allowed = false;
            //check permission and acl model
            $path = $module . "/" . $controller . "/" . $action;

            if (!empty($params)) {
                $path = $module . "/" . $controller . "/" . $action . "/$1";
            }
//            echo $path;
            $arrAllowed = $this->commonlibrary->checkAlwaysAllowPermission($path);
//            p($arrAllowed);
            if (empty($arrAllowed)) {
                $allowed = false;
            } else {
                //check permission in always allow
                $alwaysAllow = $arrAllowed['alwaysAllow'];
                if ($alwaysAllow == "Yes") {
                    $allowed = TRUE;
                }
            }

            //check for admin module
            if ($module == 'admin') {
                
                //check if loggedin
                if (!empty($this->session->userdata['logged_in'])) {
                    //set language in session
//                    $this->session->set_userdata('siteLang', $siteLang);
                    //set menu according to role in admin panel
                    $roleId = $this->session->userdata['roleId'];
                    $topMenu = $this->commonlibrary->getNavMenu($roleId);
                    $this->session->set_userdata(array('topMenu' => $topMenu));

                    if ($roleId == DEVELOPERROLEID || $roleId == ADMINROLEID) {                       
                        $allowed = true;
                    } else {                       
//                         $arrAllowed = $this->commonlibrary->checkAlwaysAllowPermission($path);
                        if(!empty($arrAllowed))
                        {
                        if ($alwaysAllow == "Yes") {
                            $allowed = TRUE;
                        } else {                            
                            $permissionId = $arrAllowed['permissionId'];                           
                           
                            $isAllow = $this->commonlibrary->checkPermission($permissionId, $roleId);                           
                            if (!$isAllow) {
                                $allowed = false;
                            } else {
                                $allowed = true;
                            }
                            
                            
                        }
                        }
                    }
                }

                if ($allowed) {
                    if (method_exists($this, $action)) {
                        call_user_func_array(array($controller, $action), $params);
                    } else {
                        show_404();
                    }
                } else {
                    if (isset($this->session->userdata['logged_in'])) {
                        $errorIllegalMsg = mlLang('msgErrorIllegal');
                        $this->session->set_flashdata('error', $errorIllegalMsg);
                       
                                            
                            redirect('admin/users', 'refresh');
                       
                    } else {
                       
                           redirect('admin/users/login', 'refresh');
                       
                    }
                }
            }
            if ($module == 'public') {
                $controller = $this->router->fetch_class();
                $action = $this->router->fetch_method();

                $controller_action = strtolower($controller . '_' . $action);
                
                $redirectToLogin = false;

                if (in_array($controller_action, $this->_public_whitelisted)) {
                    $redirectToLogin = true;
                }

                $this->load->library('session');
                $this->load->helper('url');
                if ($redirectToLogin == false) {
                    if ($this->session->userdata('public_logged_in') == '') {
                        redirect('public/index/index');
                    }
                } else {
                    if (method_exists($this, $method)) {
                        call_user_func_array(array($this, $method), $params);
                    } else {
                        show_404();
                    }
                }
            }
        }

    }

    