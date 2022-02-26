<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class UtilityModel extends MY_Model
{
    private $_migration_base_table = 'customer_migration_feb';
            
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllCustomers($where=array())
    {
        if (!empty($where))
        {
            $this->db->where($where);
        }
        
        $return = $this->db->get($this->_migration_base_table);
        $return = $return->result_array();
        return $return;
    }
    
    public function saveCustomer($dataValues)
    {
        $return = null;
        if (count($dataValues) > 0)
        {
            if (array_key_exists('id', $dataValues))
            {
                $this->db->where('id', $dataValues['id']);
                $this->db->update($this->_migration_base_table, $dataValues);

                $return = $dataValues['id'];
            }
            else
            {
                $this->db->insert($this->_migration_base_table, $dataValues);
                $return = $this->db->insert_id();
            }
        }
        return $return;
    }
    
    public function getAllNativeCustomers()
    {
        $this->db->where('id > 0');

        $return = $this->db->get('customers');
        $return = $return->result_array();
        return $return;
    }
}

?>