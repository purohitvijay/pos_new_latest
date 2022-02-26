<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation
{
    private $_CI;

	public function __construct()
	{
	    parent::__construct();

        $this->_CI =& get_instance();
	}



    public function validate_file_upload($controlName, $module)
    {        
        $this->_CI->load->config('report_config');
        $myconfig = $this->_CI->config->item($module);
        
		$this->_CI->load->library('upload', $myconfig); 
               
        $message = '';

        if ( ! $this->_CI->upload->dryRunUpload($controlName))
        {           
            $message = $this->_CI->upload->display_errors();
            $this->_error_array[] =  $message;
        }
       // p($myconfig);
        return empty($message);
    }  
   
    
	// --------------------------------------------------------------------
public function unique($str, $field)
	{
         $str = addslashes($str);
        $arr = explode('.', $field);

		$table = $arr[0];
		$column = $arr[1];

		$exclusionField =  $exclusionFieldValue =  $extraCondition = '';

		if (count($arr) >= 4)
		{
			$exclusionField = $arr[2];
			$exclusionFieldValue = $arr[3];                        
		}
                if(count($arr) > 4)
                {
                    $extrafield = $arr[4];
                    $extrafieldVal = $arr[5];
                    $condition = "AND $extrafield = '$extrafieldVal'";
                   
                }

		$this->_CI->form_validation->set_message('unique', '%s already exists.');

		if (!empty($exclusionField) && !empty($exclusionFieldValue))
		{
			$extraCondition = " AND $exclusionField <> '$exclusionFieldValue'";
		}
                if(!empty($condition))
                {
                    $extraCondition .= $condition;
                }
		$query = $this->_CI->db->query("SELECT COUNT(*) AS duplicate FROM $table WHERE $column = '$str' $extraCondition");
		$row = $query->row();
//             echo $this->_CI->db->last_query();exit;
		return ($row->duplicate > 0) ? FALSE : TRUE;
	}
}
?>
