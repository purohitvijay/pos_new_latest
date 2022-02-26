<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * Default template
 */
require_once 'template.php';

class LoginTemplate extends Template
{

    public function __construct()
    {
        parent::__construct();

        $this->_CI =& get_instance();
        
        $this->viewPath = "templates/login/";
    }

    public function render($view, array $data = array())
    {
        $return_val = $this->CI->load->viewPartial($view, $data);
        $data['template_content'] = $return_val;

        $return_val = $this->CI->load->viewPartial($this->viewPath . $this->masterTemplate, $data);
        return $return_val;
    }

}
