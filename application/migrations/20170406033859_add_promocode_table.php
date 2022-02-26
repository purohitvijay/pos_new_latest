<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_promocode_table extends CI_Migration {
        
        public $_promotion_table_name = 'promotion_old';
        public $_promotion_box_trans_table_name = 'promotion_box_trans';
        public $_order_trans_table_name = 'order_trans';
        public $_orders_table_name = 'orders';
        
        public function up()
        { p("promo-add");
            ini_set('memory_limit','-1');
            ini_set('max_execution_time', '-1');
          
            //Add promotion table
            $this->dbforge->add_field(array(
                'id' => array('type' => 'int', 'constraint' => 11, 'null' => false, 'auto_increment' => 'true',  'unsigned' => TRUE),
                'name' =>  array('type' => 'varchar', 'constraint' => 255, 'null' => false ),
                'date_from' =>  array('type' => 'date', 'null' => false ),
                'date_to' =>  array('type' => 'date', 'null' => false ),
                'amount' =>  array('type' => 'float()', 'constraint' => '10,2' , 'null' => false ),
                'is_active' =>  array('type' => 'ENUM("yes","no")', 'default' => 'yes'),
                'created_at' =>  array('type' => 'datetime', 'null' => false ),
                'updated_at' =>  array('type' => 'datetime', 'null' => false ),
                'created_by' =>  array('type' => 'varchar', 'constraint' => 255 , 'null' => false ),
                'multiple_usage' =>  array('type' => 'ENUM("yes","no")', 'default' => 'no'),
                'quantity_count' =>  array('type' => 'int', 'constraint' => 11,'null' => false ),
                'usage_left' =>  array('type' => 'int', 'constraint' => 11,'null' => false ),
             )); 
            
            $this->dbforge->add_key('id', TRUE); 
            $this->dbforge->create_table($this->_promotion_table_name);
  
          
            
            
            //Add promotion trans table
            $this->dbforge->add_field(array(
                 'id' => array('type' => 'int', 'constraint' => 11, 'null' => false, 'auto_increment' => 'true'),
                 'promotion_id' => array('type' => 'int', 'constraint' => 11, 'null' => false ),
                 'box_id' => array('type' => 'int', 'constraint' => 11, 'null' => false ),
             )); 
            $this->dbforge->add_key('id', TRUE); 
            $this->dbforge->create_table($this->_promotion_box_trans_table_name);
            
            
            //Add field to order trans table
            $promoIdFieldToOrderTransTbl = array( 
                 'promocode_id' => array('type' => 'int', 'constraint' => 11) 
             ); 
            $this->dbforge->add_column($this->_order_trans_table_name, $promoIdFieldToOrderTransTbl);
            
             
           //Modify the order discount type field
            $promoDiscoutnTypeFieldToOrdersTbl = array(
                'discount_type' => array(
                    'type' => "enum",
                    'constraint' => "'repeated_customer','agent','migration','promocode_discount'",
                )
            );
            $this->dbforge->modify_column($this->_orders_table_name, $promoDiscoutnTypeFieldToOrdersTbl);
             
            
        }
        public function down()
        { p("p-down");
            ini_set('memory_limit','-1');
            ini_set('max_execution_time', '-1');
            //Drop field from and table which is uses in promotion  
            $this->dbforge->drop_table($this->_promotion_table_name);
             
            $this->dbforge->drop_table($this->_promotion_box_trans_table_name);
            
            $dropPromocodeIdField = 'promocode_id';
            $this->dbforge->drop_column($this->_order_trans_table_name, $dropPromocodeIdField);
            
            //Modify the order discount type field
            $promoDiscoutnTypeFieldToOrdersTbl = array(
                'discount_type' => array(
                    'type' => "enum",
                    'constraint' => "'repeated_customer','agent','migration'",
                )
            );
            $this->dbforge->modify_column($this->_orders_table_name, $promoDiscoutnTypeFieldToOrdersTbl);
            
        }
}
 
