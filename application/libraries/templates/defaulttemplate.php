<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Default template
 */
require_once 'template.php';

/**
 * Default template implementation.
 * 
 * It is the default renderer of all the pages if any other renderer is not used.
 */
class DefaultTemplate extends Template {
	public function __construct(){
		parent::__construct();

        $this->_CI =& get_instance();
	}
	
	public function render($view, array $data = array()) {
		$return_val	= $this->CI->load->viewPartial($view, $data);
                
		 $loggedIn = $this->CI->session->userdata('logged_in');

		$data['template_content']			= $return_val;
		
		$data['template_title'] 			= 'Admin Panel';        
		//css
                $css_tags               = $this->collectCss("admin", isset($data['local_css']) ? $data['local_css'] : array());
                
		$data['template_css'] 				= implode("\n",$css_tags);
                //js
       $script_tags		= $this->collectJs("admin", isset($data['local_js']) ? $data['local_js'] : array());
              
		
		$data['template_js'] 				= implode("\n",$script_tags);
                
		$this->CI->load->library('session');
		$data['template_username']			= $this->CI->session->userdata('username');
		$data['template_useremail']			= $this->CI->session->userdata('email');
		$data['template_name']			= $this->CI->session->userdata('name');
		$data['geo_type']			= $this->CI->session->userdata('geo_type');
        
		$data['roleId'] = $this->CI->session->userdata('roleId');
		$data['topMenu']					= $this->CI->session->userdata('topMenu');
		
                $data['controller']                 = $this->CI->router->fetch_class();
                $data['action']  = $this->CI->router->fetch_method();

                $data['template_top']   = $this->CI->load->viewPartial($this->viewPath . 'top',$data);
              
                $data['template_header']  			= $this->CI->load->viewPartial($this->viewPath . 'header', $data);
                
                $data['template_sidebar'] = $this->CI->load->viewPartial($this->viewPath . 'sidebar', $data);
               
		$data['template_footer']  			= $this->CI->load->viewPartial($this->viewPath . 'footer',$data);
		
		$return_val = $this->CI->load->viewPartial($this->viewPath . $this->masterTemplate, $data);
		return $return_val;
	}
}