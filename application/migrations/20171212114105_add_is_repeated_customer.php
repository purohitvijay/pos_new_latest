<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_is_repeated_customer extends CI_Migration {
        public $_cusotmer_table_name = 'customers';
        public $_orders_table_name = 'orders';
        public function up()
        {
            ini_set('memory_limit','-1');
            ini_set('max_execution_time', '-1');
            //Add is_repeated_cistomer field to customer table
            $addIsRepeatedCustomerFieldInCustomerTable = array(
            'is_repeated_customer' => array('type' => 'ENUM("yes","no")',
                                            'null' => true,),
             ); 
            $this->dbforge->add_column($this->_cusotmer_table_name, $addIsRepeatedCustomerFieldInCustomerTable);
            
            
            $this->db->select('*');
            $this->db->from($this->_cusotmer_table_name);
            $this->db->join($this->_orders_table_name , "$this->_orders_table_name.customer_id = $this->_cusotmer_table_name.id", "inner");  
            $this->db->group_by("customer_id"); 
            $query = $this->db->get(); 
            while($row = $query->_fetch_object())
            { 
               if($row)
               { 
                    $this->db->where("$this->_cusotmer_table_name.id", $row->customer_id);
                    $update_is_repeated_customer = array('is_repeated_customer' => 'yes');
                    $this->db->update($this->_cusotmer_table_name, $update_is_repeated_customer);
               } 
            }
        }
        public function down()
        { p("4015 -down");
            ini_set('memory_limit','-1');
            ini_set('max_execution_time', '-1');
            //Drop is_repeated_customer field from Customers table 
            $dropPlanMasterPlanDurationTransIdField = 'is_repeated_customer';
            $this->dbforge->drop_column($this->_cusotmer_table_name, $dropPlanMasterPlanDurationTransIdField);
        }
}
 
