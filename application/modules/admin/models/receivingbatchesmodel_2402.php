<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ReceivingBatchesModel extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function getAllReceivingBatches($pagingParams = array(), $type = 'open')
    {
        $this->db->select('receiving_batches.*,GROUP_CONCAT(DISTINCT batch_name SEPARATOR "<br/>") AS shipment_batches,
                count(DISTINCT orders.id) as orders_count,
                sum(order_trans.quantity) as boxes_count',false);
        $this->db->from('receiving_batches');
        $this->db->join('shipment_batches','receiving_batches.id = shipment_batches.receiving_batch_id','left');
        $this->db->join('orders', 'shipment_batches.id = orders.shipment_batch_id', 'left');
        $this->db->join('order_trans', 'orders.id = order_trans.order_id', 'left');

        if (isset($pagingParams['sSearch']))
        {
            $search = $pagingParams['sSearch'];
            if (!empty($search))
            {
                $this->db->like('name', $search);
            }
        }
        if (!empty($pagingParams['iSortCol_0']))
        {
            if (empty($pagingParams['sSortDir_0']))
            {
                $pagingParams['sSortDir_0'] = 'desc';
            }
            $column_arr = array('name','status');
            switch ($pagingParams['sSortDir_0'])
            {
                default:
                    $this->db->order_by($column_arr[$pagingParams['iSortCol_0']], $pagingParams['sSortDir_0']);
                    break;
            }
        }
        else
        {
            $this->db->order_by('name desc');
        }
        
        if ($type == 'open')
        {
            $this->db->where('receiving_batches.status','open');
        }
        
        $this->db->group_by('receiving_batches.id');
        
        if (isset($pagingParams['iDisplayStart']) && $pagingParams['iDisplayLength'] != '-1')
        {
            $start = $pagingParams['iDisplayStart'];
            $offset = $pagingParams['iDisplayLength'];
            $return = $this->getWithCount(null, $offset, $start); 
            return $return;
        }
        else
        {
            $return = $this->db->get();
            return $return->result_array();
        }
        
    }
    
    public function getActiveShipmentBatches($receiving_batch_id = null)
    {
        $result = array();
        $this->db->select('id, batch_name');       
        if(!empty($receiving_batch_id))
        {
//            $this->db->where('status', 'no');
            $this->db->where('receiving_batch_id', $receiving_batch_id);
        }
        else
        {
            $this->db->where('status', 'yes');
            $this->db->where('receiving_batch_id', null);
        }
        $query = $this->db->get('shipment_batches');
        if(!empty($query))
        {
            $result = $query->result_array();
        }
       
        return $result;
    }
    
    public function saveReceivingBatch($dataValues)
    {
        $return = null;
        if (count($dataValues) > 0)
        {
            if (array_key_exists('id', $dataValues))
            {
                $this->db->where('id', $dataValues['id']);
                $this->db->update('receiving_batches', $dataValues);

                $return = $dataValues['id'];
            }
            else
            {
                $this->db->insert('receiving_batches', $dataValues);
                $return = $this->db->insert_id();
            }
        }
        return $return;
    }
    
    public function getReceivingBatchById($id)
    {
        $result = array();
        $this->db->select('receiving_batches.*,GROUP_CONCAT(batch_name) AS shipment_batches,GROUP_CONCAT(shipment_batches.id) AS shipment_batches_id',false);
        $this->db->from('receiving_batches');
        $this->db->join('shipment_batches','receiving_batches.id = shipment_batches.receiving_batch_id','left');
        $this->db->where('receiving_batches.id',$id);
        $this->db->group_by('receiving_batches.id');
        $query = $this->db->get();
        
//        p($this->db->last_query());
        
        $result = $query->row_array();
        return $result;
        
    }
    
    public function updateRecevingBatchInShipment($receiving_batch_id)
    {
        $this->db->where('receiving_batch_id', $receiving_batch_id);
        $this->db->update('shipment_batches', array('receiving_batch_id' => null));
        $return = $receiving_batch_id;
        return $return;
    }
    
    public function getAllReceivingBatchesOrders()
    {
        $result = array();
        $this->db->select('orders.id, orders.order_number, SUM(order_trans.`quantity`) as boxes_count');
        $this->db->from('orders');
        $this->db->join('shipment_batches', 'shipment_batches.id = orders.shipment_batch_id');
        $this->db->join('receiving_batches', 'receiving_batches.id = shipment_batches.receiving_batch_id');
        $this->db->join('order_trans', 'order_trans.order_id = orders.id');
        $this->db->join('order_status_trans', 'order_status_trans.order_id = orders.id and order_status_trans.status = "ready_for_receiving_at_jakarta" and order_status_trans.active = "yes"');
        $this->db->where('receiving_batches.status', 'open');
        $this->db->group_by('orders.id');
        $query = $this->db->get();
        if(!empty($query))
        {
            $result = $query->result_array();
        }
        
//        p($this->db->last_query());exit;
        
        return $result;
    }
    
    public function getReceivingBatchPendingOrders($receiving_batch_id)
    {
        $return = array();
         $this->db->select("orders.id, order_number, orders.status AS order_status, orders.building, orders.street, orders.unit, orders.block, orders.pin, 
                            customers.name AS customer_name, recipient_name, recipient_item_list, mobile, residence_phone, receiving_batches.name AS batch,
                            order_status_trans.status");                
        $this->db->from('orders');
        $this->db->join('customers', 'customers.id = orders.customer_id');
        $this->db->join("order_status_trans", "orders.id = order_status_trans.order_id and order_status_trans.active = 'yes'");
        $this->db->join("shipment_batches", "shipment_batches.id = orders.shipment_batch_id", "left");
        $this->db->join("receiving_batches", "receiving_batches.id = shipment_batches.receiving_batch_id", "left");
        $this->db->join("order_trans", "orders.id = order_trans.order_id");       

        $this->db->group_by('orders.id');
        $this->db->where('shipment_batches.receiving_batch_id', $receiving_batch_id);
        $this->db->where('order_status_trans.status','ready_for_receiving_at_jakarta');              
        $this->db->order_by('orders.order_number');
       
        $return = $this->db->get()->result_array();
        return $return;
    }
    
    public function getReceivingBatchArr()
    {
        $this->db->_protect_identifiers = false;
        
        $result = array();
        $this->db->select('id, name, status');
        $this->db->order_by('CONVERT(name,UNSIGNED INTEGER)', 'desc');
        $query = $this->db->get('receiving_batches');
        $result = $query->result_array();
        return $result;        
    }  
    
    public function getOrdersDataAtJakartaSide($pagingParams = array(), $receiving_batch_id = null, $order_number = null, $weight_discrepancy = null, $box_not_received = null,$shipment_batch_id = null,$location_id = null,$kabupaten_id = null,$box_received = null)
    {
        
        $this->db->select("orders.id, order_date, delivery_date, collection_date, order_number, orders.status as order_status, "
                . "orders.building, orders.street, orders.unit, orders.block, orders.pin, kiv_status, printed_instruments, "
                . "orders.nett_total, orders.grand_total, orders.discount,weight,jkt_weight,jkt_received_date,jkt_receiver,jkt_reference_no, "
                . " customers.name as customer_name,recipient_name,recipient_item_list,mobile, residence_phone, receiving_batches.name as batch, "
                . "order_status_trans.status,"
                . "GROUP_CONCAT(boxes.`name` ORDER BY order_trans.id SEPARATOR '<br>' ) AS boxes,"
                . "GROUP_CONCAT(locations.`name` ORDER BY order_trans.id SEPARATOR '<br>' ) AS locations,"
                . "GROUP_CONCAT(kabupatens.`name` ORDER BY order_trans.id SEPARATOR '<br>' ) AS kabupatens,"
                . "GROUP_CONCAT(order_trans.`quantity` ORDER BY order_trans.id SEPARATOR '<br>' ) AS quantities,"
                . "(select concat(group_concat(status order by ost.id), '<br>', group_concat(u.name order by ost.id))"
                . " from order_status_trans ost join user u on (u.id = ost.employee_id) where ost.order_id = orders.id) as statuses ,"
                . "sum(order_trans.quantity) as count", FALSE);
        $this->db->from('orders');
        $this->db->join('customers', 'customers.id = orders.customer_id');
        $this->db->join("order_status_trans", "orders.id = order_status_trans.order_id and order_status_trans.active = 'yes'");
        $this->db->join("shipment_batches", "shipment_batches.id = orders.shipment_batch_id", "left");
        $this->db->join("receiving_batches", "receiving_batches.id = shipment_batches.receiving_batch_id", "left");
        $this->db->join("order_trans", "orders.id = order_trans.order_id");
        $this->db->join("boxes", "boxes.id = order_trans.box_id");
        $this->db->join("locations", "locations.id = order_trans.location_id");
        $this->db->join("kabupatens", "kabupatens.id = order_trans.kabupaten_id", "left");
        
        if (!empty($box_not_received))
        {
            $this->db->where('(jkt_received_date is null or jkt_received_date = ""  or jkt_received_date = "0000-00-00") ');
        }
        
        if (!empty($box_received))
        {
            $this->db->where('(jkt_received_date is not null and jkt_received_date != ""  and jkt_received_date != "0000-00-00") ');
        }

        $this->db->group_by('orders.id');
        
        if (!empty($receiving_batch_id))
        {
            $this->db->where('shipment_batches.receiving_batch_id', $receiving_batch_id);
        }
        
        if (!empty($order_number))
        {
            $this->db->like('order_number', $order_number);
        }
        
         if (!empty($shipment_batch_id))
        {
            $this->db->where('shipment_batches.id', $shipment_batch_id);
        }
        
        if (!empty($location_id))
        {
            $this->db->where('order_trans.location_id', $location_id);
        }
        
        if (!empty($kabupaten_id))
        {
            $this->db->where('order_trans.kabupaten_id', $kabupaten_id);
        }
        
        if(!empty($weight_discrepancy))
        {
            if($weight_discrepancy == "yes")
            {
                $this->db->where('weight != jkt_weight');
                $this->db->where('capture_weight','yes');
            }
            else
            {
                $this->db->where('weight = jkt_weight');
            }
        }
                       
        $this->db->order_by('count desc');
      
        if (!empty($pagingParams['iSortCol_0']))
        {
            if (empty($pagingParams['sSortDir_0']))
            {
                $pagingParams['sSortDir_0'] = 'desc';
            }
            $column_arr = array('order_number');
            switch ($pagingParams['sSortDir_0'])
            {
                default:
                    $this->db->order_by($column_arr[$pagingParams['iSortCol_0'] + 1], $pagingParams['sSortDir_0']);
                    break;
            }
        }
        else
        {
            $this->db->order_by('order_number');
        }
        
        $this->db->group_by('receiving_batches.id');
        
        if (isset($pagingParams['iDisplayStart']) && $pagingParams['iDisplayLength'] != '-1')
        {
            $start = $pagingParams['iDisplayStart'];
            $offset = $pagingParams['iDisplayLength'];
            $return = $this->getWithCount(null, $offset, $start); 
//            echo $this->db->last_query();exit;
            return $return;
        }
        else
        {
            $return = $this->db->get();
            return $return->result_array();
        }
    }
    
    public function getOrdersDataAtJakartaSideLocation($receiving_batch_id = null, $weight_discrepancy = null, $box_not_received = null,$shipment_batch_id = null,$box_received = null)
    {
        $this->db->select("locations.`name` AS location_name,locations.`id` AS location_id,"
                . "sum(order_trans.quantity) as count", FALSE);
        $this->db->from('orders');
        $this->db->join('customers', 'customers.id = orders.customer_id');
        $this->db->join("order_status_trans", "orders.id = order_status_trans.order_id and order_status_trans.active = 'yes'");
        $this->db->join("shipment_batches", "shipment_batches.id = orders.shipment_batch_id", "left");
        $this->db->join("receiving_batches", "receiving_batches.id = shipment_batches.receiving_batch_id", "left");
        $this->db->join("order_trans", "orders.id = order_trans.order_id");
        $this->db->join("boxes", "boxes.id = order_trans.box_id");
        $this->db->join("locations", "locations.id = order_trans.location_id");
        $this->db->join("kabupatens", "kabupatens.id = order_trans.kabupaten_id", "left");
        
        if (!empty($box_not_received))
        {
            $this->db->where('(jkt_received_date is null or jkt_received_date = ""  or jkt_received_date = "0000-00-00") ');
        }
        
        if (!empty($box_received))
        {
            $this->db->where('(jkt_received_date is not null and jkt_received_date != ""  and jkt_received_date != "0000-00-00") ');
        }

        if (!empty($receiving_batch_id))
        {
            $this->db->where('shipment_batches.receiving_batch_id', $receiving_batch_id);
        }
        
        if (!empty($order_number))
        {
            $this->db->like('order_number', $order_number);
        }
        
         if (!empty($shipment_batch_id))
        {
            $this->db->where('shipment_batches.id', $shipment_batch_id);
        }
        if(!empty($weight_discrepancy))
        {
            if($weight_discrepancy == "yes")
            {
                $this->db->where('weight != jkt_weight');
                $this->db->where('capture_weight','yes');
            }
            else
            {
                $this->db->where('weight = jkt_weight');
            }
        }
                       
        $this->db->order_by('locations.id');        
        $this->db->group_by('locations.id');
        $return = $this->db->get();
        return $return->result_array();
    }
     
    
    public function getAllShipmentBatches($order_direction = 'asc')
    {
        $result = array();
        $this->db->select('id, batch_name');
        $this->db->order_by("batch_name $order_direction");
        $query = $this->db->get('shipment_batches');
        
        if(!empty($query))
        {
            $result = $query->result_array();
        }
        return $result;
    }
    
    
    public function getShipmentOrdersPhoto($pagingParams = array(), $search_shipment_batch_id = null, $is_available = null)
    {
        
        $this->db->select("orders.id, orders.order_number, orders.delivery_date, orders.jkt_received_date, order_image_trans.updated_at as date_uploaded, order_image_trans.status as image_status", FALSE);
        $this->db->from('orders');
        $this->db->join('order_image_trans', 'order_image_trans.order_id = orders.id', "left");
        $this->db->join("order_image_master", "order_image_trans.order_image_master_id = order_image_master.id", "left");
//        $this->db->join("shipment_batches", "shipment_batches.id = orders.shipment_batch_id", "left");
        
        if (!empty($search_shipment_batch_id))
        {
            $this->db->where('orders.shipment_batch_id', $search_shipment_batch_id);
        }
        
        if (!empty($is_available))
        {
            $this->db->where('order_image_trans.status', 'available');
        }
        else
        {
            $this->db->where('order_image_trans.status !=', 'available');
        }
                
//        $this->db->order_by('orders.id desc');
      
        if (!empty($pagingParams['iSortCol_0']))
        {
            if (empty($pagingParams['sSortDir_0']))
            {
                $pagingParams['sSortDir_0'] = 'desc';
            }
            $column_arr = array('order_number');
            switch ($pagingParams['sSortDir_0'])
            {
                default:
                    $this->db->order_by($column_arr[$pagingParams['iSortCol_0'] + 1], $pagingParams['sSortDir_0']);
                    break;
            }
        }
        else
        {
            $this->db->order_by('order_number');
        }
        
        $this->db->group_by('orders.id');
        
        if (isset($pagingParams['iDisplayStart']) && $pagingParams['iDisplayLength'] != '-1')
        {
            $start = $pagingParams['iDisplayStart'];
            $offset = $pagingParams['iDisplayLength'];
            $return = $this->getWithCount(null, $offset, $start); 
//            echo $this->db->last_query();exit;
            return $return;
        }
        else
        {
            $return = $this->db->get();
            return $return->result_array();
        }
    }
    
    public function getOrdersPhoto($order_direction = 'asc')
    {
        $result = array();
        $this->db->select('id, batch_name');
        $this->db->order_by("batch_name $order_direction");
        $query = $this->db->get('shipment_batches');
        
        if(!empty($query))
        {
            $result = $query->result_array();
        }
        return $result;
    }

}
