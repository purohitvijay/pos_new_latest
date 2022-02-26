<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Publiclogin Class
 *
 * Makes authentication simple
 *
 * Publiclogin is released to the public domain
 * (use it however you want to)
 * 
 * Simplelogin expects this database setup
 * (if you are not using this setup you may
 * need to do some tweaking)
 * 
 *
 */

class Publiclogin
{
	var $CI;
	var $user_table = 'orders';
	var $phone_field = 'mobile';
	var $order_field = 'order_number';
        var $customer_table = 'customers';
        var $customer_id_customer = 'id';
        var $customer_id_user = 'customer_id';




        /**
	 * Login and sets session variables
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @return	bool
	 */
        
        /**
	 * Constructor
	 */
	public function __construct()
	{
        
		$this->CI =& get_instance();		
	}

        public function loginpublic($phone = '', $order = '') {
        
        
                //Make sure login info was sent
                if($phone == '' OR $order == '') {
                        return false;
                }

                //Check against user table
                $this->CI->db->from($this->user_table);
                $this->CI->db->join($this->customer_table,"$this->customer_table.$this->customer_id_customer = $this->user_table.$this->customer_id_user",'left'); 
                $this->CI->db->where($this->phone_field, $phone); 
                $this->CI->db->where($this->order_field, $order); 
                

                $query = $this->CI->db->get('');
                if ($query->num_rows() > 0) {
                $row = $query->row_array();

                //Check against password
                if($order != $row[$this->order_field]) {
                        return false;
                        }

                        unset($row['order_input']);

                        //Login was successful			
                        return true;
                     } 
                else {
                        //No database result found
                        return false;
                     }	

    }
}
?>