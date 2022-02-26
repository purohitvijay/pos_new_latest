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
class PublicTemplate extends Template {
    
    private $_disableLeftBarActions = array(
        'cmsContent',
        'BuyUser'
    );
    
	public function __construct(){
		parent::__construct();

        $this->_CI =& get_instance();
        $this->viewPath = "templates/public/";
	}
	
	public function render($view, array $data = array()) {
		$return_val	= $this->CI->load->viewPartial($view, $data);

		$data['template_content']			= $return_val;
		
		$css_tags                           = $this->collectCss("public",isset($data['local_css']) ? $data['local_css'] : array());
		$data['template_css'] 				= implode("\n",$css_tags);
		$script_tags 						= $this->collectJs("public", isset($data['local_js']) ? $data['local_js'] : array());
		 
		$data['template_js'] 				= implode("\n",$script_tags);
		
		$data['template_title'] 			= 'Player-on';
		
		$this->CI->load->library('session');

        $publicloggedIn = $this->CI->session->userdata('public_logged_in');
       
        if (empty($publicloggedIn))
        {
            $data['template_header']  			= $this->CI->load->viewPartial($this->viewPath . 'header-not-loggedin', $data);
            $data['template_footer']  			= $this->CI->load->viewPartial($this->viewPath . 'footer', $data);
            $data['template_leftbar']          = '';
        }
        else
        {
            $user_id = $this->CI->session->userdata('registration_id');
            $data['rightbar_data'] = getRightBarData($user_id);
            //$data['notification_data'] = getNotificationData($user_id);
            $notification_data = getNotificationData($user_id);
            
            if(empty($notification_data['msg_count']))
                $msg_count_text = '';
            else
                $msg_count_text = '<div class="noti_bubble">'.$notification_data['msg_count'].'</div>';
            
            if(empty($notification_data['friend']))
                $friend_count_text = '';
            else
                $friend_count_text = '<div class="noti_bubble">'.$notification_data['friend'].'</div>';
            
            if(empty($notification_data['comment_count']))
                $comment_count_text = '';
            else
                $comment_count_text = '<div class="noti_bubble">'.$notification_data['comment_count'].'</div>';
            
            $data['notification_data'] = array(
                "msg_count_text" => $msg_count_text,
                "friend_count_text" => $friend_count_text,
                "comment_count_text" => $comment_count_text
            );
            
            $data['session_data']               = $this->CI->session->userdata;
            $data['template_header']  			= $this->CI->load->viewPartial($this->viewPath . 'header', $data);
            $data['template_footer']  			= $this->CI->load->viewPartial($this->viewPath . 'footer', $data);
//            $data['help_content']               = $this->_parseRightBarContent();
            
            $action = $this->CI->router->fetch_method();
            if (!in_array($action, $this->_disableLeftBarActions))
            {
                $data['template_leftbar']          = $this->CI->load->viewPartial($this->viewPath . 'left', $data);
            }
            else
            {
                
                $data['template_leftbar']          = null;
            }
        }
        
		$return_val = $this->CI->load->viewPartial($this->viewPath . $this->masterTemplate, $data);
		return $return_val;
	}
//    
//    private function _parseRightBarContent()
//    {
//        $return = null;
//        $class = $this->CI->router->fetch_class();
//       // $class = 'overview';
//        
//        switch ($class)
//        {
//            default:
//                $this->CI->load->model('contentmodel');
//                $row = $this->CI->contentmodel->getHelpContentByArea($class);
//                $return = empty($row) ? $return : $row;
//                break;
//        }
//        
//        return $return;
//    }
}