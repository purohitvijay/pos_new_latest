<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ShipmentCostModel extends MY_Model
{
   
    public function saveShipmentCostingMaster($dataValues)
    {
        $return = null;
        if (count($dataValues) > 0)
        {
            $this->db->insert('costing_line_items', $dataValues);
            $return = $this->db->insert_id();
//            p($this->db->last_query());
        }
        return $return;
    }

    public function getAllShipmentPaymentCosts($pagingParams = array())
    {
        $this->db->select('shipment_cost_master.*, shipment_batches.batch_name as shipment_batch');
        $this->db->from('shipment_cost_master');
        $this->db->join('shipment_batches', 'shipment_batches.id = shipment_cost_master.shipment_batch_id');
        
        if (isset($pagingParams['sSearch']))
        {
            $search = $pagingParams['sSearch'];
            if (!empty($search))
            {
                $this->db->like('payment_reference', $search);
            }
        }
        
        if (!empty($pagingParams['iSortCol_0']))
        {
            if (empty($pagingParams['sSortDir_0']))
            {
                $pagingParams['sSortDir_0'] = 'desc';
            }
            $column_arr = array('shipment_batch', 'payment_reference', 'exchange_rate');
            switch ($pagingParams['sSortDir_0'])
            {
                default:
                    $this->db->order_by($column_arr[$pagingParams['iSortCol_0'] + 1], $pagingParams['sSortDir_0']);
                    break;
            }
        }
        else
        {
            $this->db->order_by('shipment_batch desc');
        }
        
        if (isset($pagingParams['iDisplayStart']) && $pagingParams['iDisplayLength'] != '-1')
        {
            $start = $pagingParams['iDisplayStart'];
            $offset = $pagingParams['iDisplayLength'];
            $this->db->limit($offset, $start);
        }
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }
    
    public function getShipmentPaymentCostCount($pagingParams = array())
    {
        $count = '0';
        
        if (isset($pagingParams['sSearch']))
        {
            $search = $pagingParams['sSearch'];
            if (!empty($search))
            {
                $this->db->like('payment_reference', $search);
            }
        }
        
        $this->db->select('count("commission_master.id") as count');
        $this->db->from('shipment_cost_master');
        $this->db->select('count("id") as count');
        $query = $this->db->get()->row();
        $count = $query->count;
        return $count;
    }

    public function getAvailableShipmentBatches()
    {
        $this->db->select('shipment_batches.id, shipment_batches.batch_name as shipment_batch');
        $this->db->from('shipment_batches');
        $this->db->join('shipment_cost_master', 'shipment_cost_master.shipment_batch_id = shipment_batches.id', 'left');
        $this->db->where('shipment_cost_master.id IS NULL');
//        $this->db->where('shipment_batches.status', 'yes');
        $this->db->order_by('shipment_batches.id desc');
        
        $query = $this->db->get();
        $result = $query->result_array();
        
        return $result;
    }

    public function getShipmentBatchLocationOrderBoxes($where)
    {
        $this->db->select('sum(order_trans.quantity) AS quantity, boxes.name AS box, boxes.id AS box_id, locations.name AS location, locations.id AS location_id');
        $this->db->from('orders');
        $this->db->join('order_trans', 'order_trans.order_id = orders.id');
        $this->db->join('boxes', 'order_trans.box_id = boxes.id');
        $this->db->join('agents', 'orders.agent_id = agents.id', 'left');
        $this->db->join('locations', 'order_trans.location_id = locations.id');
        $this->db->where('orders.shipment_batch_id', $where['shipment_batch_id']);
//        $this->db->where('locations.capture_weight', 'no');
        $this->db->where('(agents.own_running_number  is null or agents.own_running_number  = "no")');
        if($where['shipment_cost_eligible_boxes'])$this->db->where_in('boxes.id', $where['shipment_cost_eligible_boxes']);
        if($where['shipment_cost_eligible_locations']) $this->db->where_in('locations.id', $where['shipment_cost_eligible_locations']);
        $this->db->group_by('order_trans.`box_id`, order_trans.`location_id`');
        $this->db->order_by('locations.order_id');
        
        $query = $this->db->get();
        $result = $query->result_array();
        
//        p($this->db->last_query());
        
        return $result;
    }
    
    public function getOrdersShipmentBatch($where)
    {
        $this->db->select('sum(order_trans.quantity) AS quantity, boxes.name AS box, agents.name as agents_name, sum(agents.commission) as commission, sum(orders.nett_total) as nett_total , sum(orders.grand_total) as grand_total, sum(orders.discount) as discount, boxes.id AS box_id, locations.name AS location, locations.id AS location_id');
        $this->db->from('orders');
        $this->db->join('order_trans', 'order_trans.order_id = orders.id', 'left');
        $this->db->join('boxes', 'order_trans.box_id = boxes.id', 'left');
        $this->db->join('agents', 'orders.agent_id = agents.id', 'left');
        $this->db->join('locations', 'order_trans.location_id = locations.id', 'left');
//        $this->db->join('commission_orders_trans', 'commission_orders_trans.order_id = orders.id', 'left');
        $this->db->where('orders.shipment_batch_id', $where['shipment_batch_id']);
        $this->db->where('(agents.own_running_number  is null or agents.own_running_number  = "no")');
        if($where['shipment_cost_eligible_boxes'])$this->db->where_in('boxes.id', $where['shipment_cost_eligible_boxes']);
        if($where['shipment_cost_eligible_locations']) $this->db->where_in('locations.id', $where['shipment_cost_eligible_locations']);
        $this->db->group_by('boxes.id,locations.id,orders.shipment_batch_id');
        
        $query = $this->db->get();
        $result = $query->result_array();
        
//        p($this->db->last_query());
        
        return $result;
    }
    
    public function getOrdersShipmentBatchOrders($where)
    {
        $this->db->select('sum(order_trans.quantity) AS quantity,  SUM(commission_orders_trans.amount) AS commission_orders, boxes.name AS box, agents.name as agents_name, sum(agents.commission) as commission, sum(orders.nett_total) as nett_total , sum(orders.grand_total) as grand_total, sum(orders.discount) as discount, boxes.id AS box_id, locations.name AS location, locations.id AS location_id');
        $this->db->from('orders');
        $this->db->join('order_trans', 'order_trans.order_id = orders.id', 'left');
        $this->db->join('boxes', 'order_trans.box_id = boxes.id', 'left');
        $this->db->join('agents', 'orders.agent_id = agents.id', 'left');
        $this->db->join('locations', 'order_trans.location_id = locations.id', 'left');
        $this->db->join('commission_orders_trans', 'commission_orders_trans.order_id = orders.id', 'left');
        $this->db->where('orders.shipment_batch_id', $where['shipment_batch_id']);
        $this->db->where('(agents.own_running_number  is null or agents.own_running_number  = "no")');
        if($where['shipment_cost_eligible_boxes'])$this->db->where_in('boxes.id', $where['shipment_cost_eligible_boxes']);
        if($where['shipment_cost_eligible_locations']) $this->db->where_in('locations.id', $where['shipment_cost_eligible_locations']);
        $this->db->group_by('orders.id');
        
        $query = $this->db->get();
        $result = $query->result_array();
        
//        p($this->db->last_query());
        
        return $result;
    }

    public function getShipmentBatchOrderWiseSpecialBoxes($where)
    {
        $this->db->select('orders.order_number, boxes.name as box_name, order_trans.quantity, commission_orders_trans.amount AS commission_orders');
        $this->db->from('orders');
        $this->db->join('order_trans', 'order_trans.order_id = orders.id');
        $this->db->join('commission_orders_trans', 'commission_orders_trans.order_id = orders.id', 'left');
        $this->db->join('boxes', 'order_trans.box_id = boxes.id');
        $this->db->where('orders.shipment_batch_id', $where['shipment_batch_id']);
        $this->db->where('boxes.shipping_costing_inclusion', 'yes');
        $this->db->group_by('orders.id');
        
        $query = $this->db->get();
        $result = $query->result_array();
        
        return $result;
    }

    public function getShipmentCostMasterValues($field=null, $values=array(), $where=array())
    {
        $this->db->from('costing_line_items');
        if (!empty($field) && !empty($values))
        {
            $this->db->where_in($field, $values);
        }
        
        if (!empty($where))
        {
            $this->db->where($where);
        }
        
        $query = $this->db->get();
        $result = $query->result_array();
        
//        p($this->db->last_query(), 0);
        
        return $result;
    }
    
    public function checkPaymentReference($payment_reference)
    {
        $this->db->from('shipment_cost_master');
        $this->db->where('payment_reference', $payment_reference);
        
        $query = $this->db->get();
        $result = $query->row_array();
        
        return $result;
    }
    
    public function saveShipmentCostMaster($dataValues)
    {
        $return = null;
        if (count($dataValues) > 0)
        {
            if (array_key_exists('id', $dataValues))
            {
                $this->db->where('id', $dataValues['id']);
                $this->db->update('shipment_cost_master', $dataValues);

                $return = $dataValues['id'];
            }
            else
            {
                $this->db->insert('shipment_cost_master', $dataValues);
                $return = $this->db->insert_id();
            }
        }
//        p($this->db->last_query(),0);
        return $return;
    }
    
    public function saveShipmentCostLineItem($dataValues)
    {
        $return = null;
        if (count($dataValues) > 0)
        {
            if (array_key_exists('id', $dataValues))
            {
                $this->db->where('id', $dataValues['id']);
                $this->db->update('shipment_cost_line_items', $dataValues);

                $return = $dataValues['id'];
            }
            else
            {
                $this->db->insert('shipment_cost_line_items', $dataValues);
                $return = $this->db->insert_id();
            }
        }
        return $return;
    }
    
    public function getShipmentCostMasterById($id)
    {
        $result = null;
        
        if (!empty($id))
        {
            $this->db->select('*, shipment_batches.batch_name');
            $this->db->where('shipment_cost_master.id', $id);
            $this->db->from('shipment_cost_master');
            $this->db->join('shipment_batches', 'shipment_batches.id = shipment_cost_master.shipment_batch_id');
            
            $result = $this->db->get()->row_array();
        }
        return $result;
    }
    
    public function getShipmentCostLineItems($id)
    {
        $result = null;
        
        if (!empty($id))
        {
            $this->db->select('*');
            $this->db->where('shipment_cost_line_items.shipment_cost_master_id`', $id);
            $this->db->from('shipment_cost_line_items');
            $this->db->order_by('shipment_cost_line_items.id');
            
            $result = $this->db->get()->result_array();
        }
        return $result;
    }
    
    public function getShipmentCostData()
    {
        $result = null;
        
        $this->db->select('*');
        $this->db->from('costing_line_items');

        $result = $this->db->get()->result_array();

        return $result;
    }
    
    public function deleteShipmentCostReference($id)
    {
        $this->db->where('shipment_cost_master.id', $id);
        $this->db->delete('shipment_cost_master');
    }
    
    public function deleteShipmentCostReferenceLineItems($id)
    {
        $this->db->where('shipment_cost_line_items.shipment_cost_master_id', $id);
        $this->db->delete('shipment_cost_line_items');
    }
    
    public function deleteShipmentCostingMasterData($where)
    {
        $this->db->where($where);
        $this->db->delete('costing_line_items');
    }
    
    public function deleteShipmentCostReferenceReport($id)
    {
        $this->db->where('shipment_cost_report_master.id', $id);
        $this->db->delete('shipment_cost_report_master');
    }
    
    public function deleteShipmentCostReferenceLineItemsReport($id)
    {
        $this->db->where('shipment_cost_line_report_items.shipment_cost_report_master_id', $id);
        $this->db->delete('shipment_cost_line_report_items');
    }
    
    public function deleteShipmentCostingReportMasterData($where)
    {
        $this->db->where($where);
        $this->db->delete('costing_line_report_items');
    }
    
    
    public function getShipmentCostReportLineItems($id)
    {
        $result = null;
        
        if (!empty($id))
        {
            $this->db->select('*');
            $this->db->where('shipment_cost_line_report_items.shipment_cost_report_master_id`', $id);
            $this->db->from('shipment_cost_line_report_items');
            $this->db->order_by('shipment_cost_line_report_items.id');
            
            $result = $this->db->get()->result_array();
        }
        return $result;
    }
    
    
    public function checkCostingReport($shipment_reference)
    {
        $this->db->from('shipment_cost_report_master');
        $this->db->where('shipment_reference', $shipment_reference);
        
        $query = $this->db->get();
        $result = $query->row_array();
        
        return $result;
    }
    
    
    public function saveShipmentCostReportMaster($dataValues)
    {
        $return = null;
        if (count($dataValues) > 0)
        {
            if (array_key_exists('id', $dataValues))
            {
                $id = $dataValues['id'];
                unset($dataValues["id"]);
                $this->db->where('id', $id);
                $this->db->update('shipment_cost_report_master', $dataValues);

                $return = $id;
            }
            else
            {
                $this->db->insert('shipment_cost_report_master', $dataValues);
                $return = $this->db->insert_id();
            }
        }
//        p($this->db->last_query(),0);
        return $return;
    }
    
    public function saveShipmentCostLineReportItem($dataValues)
    {
        $return = null;
        if (count($dataValues) > 0)
        {
            $this->db->insert('shipment_cost_line_report_items', $dataValues);
            $return = $this->db->insert_id();
        }
        return $return;
    }
    
    public function deleteShipmentCostLineReportItem($shipment_cost_report_master_id)
    {
        return $this->db->delete('shipment_cost_line_report_items', array('shipment_cost_report_master_id' => $shipment_cost_report_master_id));

    }
    
    

    public function getAllShipmentCostingReports($pagingParams = array())
    {
        $this->db->select('shipment_cost_report_master.*, shipment_batches.batch_name as shipment_batch');
        $this->db->from('shipment_cost_report_master');
        $this->db->join('shipment_batches', 'shipment_batches.id = shipment_cost_report_master.shipment_batch_id');
        
        if (isset($pagingParams['sSearch']))
        {
            $search = $pagingParams['sSearch'];
            if (!empty($search))
            {
                $this->db->like('shipment_reference', $search);
            }
        }
        
        if (!empty($pagingParams['iSortCol_0']))
        {
            if (empty($pagingParams['sSortDir_0']))
            {
                $pagingParams['sSortDir_0'] = 'desc';
            }
            $column_arr = array('shipment_batch', 'shipment_reference', 'exchange_rate');
            switch ($pagingParams['sSortDir_0'])
            {
                default:
                    $this->db->order_by($column_arr[$pagingParams['iSortCol_0'] + 1], $pagingParams['sSortDir_0']);
                    break;
            }
        }
        else
        {
            $this->db->order_by('shipment_batch desc');
        }
        
        if (isset($pagingParams['iDisplayStart']) && $pagingParams['iDisplayLength'] != '-1')
        {
            $start = $pagingParams['iDisplayStart'];
            $offset = $pagingParams['iDisplayLength'];
            $this->db->limit($offset, $start);
        }
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }
    
    public function getShipmentCostingReportCount($pagingParams = array())
    {
        $count = '0';
        
        if (isset($pagingParams['sSearch']))
        {
            $search = $pagingParams['sSearch'];
            if (!empty($search))
            {
                $this->db->like('shipment_reference', $search);
            }
        }
        
        $this->db->select('count("commission_master.id") as count');
        $this->db->from('shipment_cost_report_master');
        $this->db->select('count("id") as count');
        $query = $this->db->get()->row();
        $count = $query->count;
        return $count;
    }
    
     public function getShipmentCostReportMasterById($id)
    {
        $result = null;
        
        if (!empty($id))
        {
            $this->db->select('*, shipment_batches.batch_name');
            $this->db->where('shipment_cost_report_master.id', $id);
            $this->db->from('shipment_cost_report_master');
            $this->db->join('shipment_batches', 'shipment_batches.id = shipment_cost_report_master.shipment_batch_id');
            
            $result = $this->db->get()->row_array();
        }
        return $result;
    }
    
    
    
    public function getShipmentCostLineReportItems($id)
    {
        $result = null;
        
        if (!empty($id))
        {
            $this->db->select('*');
            $this->db->where('shipment_cost_line_report_items.shipment_cost_report_master_id`', $id);
            $this->db->from('shipment_cost_line_report_items');
            $this->db->order_by('shipment_cost_line_report_items.id');
            
            $result = $this->db->get()->result_array();
        }
        return $result;
    }
}