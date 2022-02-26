<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class CommissionModel extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getDriverCommission($where)
    {
        $this->db->select("orders.id as orderid, date(order_status_trans.created_at) as date, orders.order_number, orders.status as order_status, order_date, 
                            IF(FIND_IN_SET('box_collected', GROUP_CONCAT(order_status_trans.status)) = 0, 'box_delivered', 'box_collected') AS status,
                            customers.building, customers.unit, customers.block, customers.street, customers.pin,
                            customers.name as customer_name, customers.mobile, customers.residence_phone,
                            user.name as driver_name,  orders.nett_total, orders.grand_total, orders.discount,
                            sum(order_trans.quantity) as quantity,
                            if(delivery_date = collection_date, 1, 0) as dnc,
                            group_concat(boxes.name ORDER BY boxes.id SEPARATOR '<br>') as boxes,
                            group_concat(boxes.id ORDER BY boxes.id SEPARATOR '<br>') as box_ids,
                            group_concat(boxes.delivery_commission ORDER BY boxes.id SEPARATOR '<br>') as delivery_commissions,
                            group_concat(boxes.collection_commission ORDER BY boxes.id SEPARATOR '<br>') as collection_commissions,
                            group_concat(order_trans.quantity ORDER BY boxes.id SEPARATOR '<br>') as quantities,
                            group_concat(locations.name ORDER BY boxes.id SEPARATOR '<br>') as locations,
                            (select cash_collected from order_status_trans od where od.order_id = orders.id and status='box_delivered') as cash_collected_delivery,
                            (select cash_collected from order_status_trans oc where oc.order_id = orders.id and status='box_collected') as cash_collected_collection,
                            (select voucher_cash from order_status_trans o1 where o1.order_id = orders.id and status='box_delivered') as voucher_cash_delivery,
                            (select voucher_cash from order_status_trans o2 where o2.order_id = orders.id and status='box_collected') as voucher_cash_collection,
                            order_status_trans.comments,
                            ", FALSE);
        $this->db->from('orders');
        $this->db->join('order_status_trans', 'orders.id = order_status_trans.order_id');
        $this->db->join('eod', 'eod.employee_id = order_status_trans.employee_id AND eod.date = DATE(order_status_trans.created_at)');
        $this->db->join('user', 'user.id = order_status_trans.employee_id');
        $this->db->join('order_trans', 'orders.id = order_trans.order_id');
        $this->db->join('boxes', 'order_trans.box_id = boxes.id');
        $this->db->join('locations', 'order_trans.location_id = locations.id');
        $this->db->join('customers', 'orders.customer_id = customers.id');
//        $this->db->join('order_redelivery_trans', 'order_redelivery_trans.order_id = orders.id and '
//                . 'order_status_trans.employee_id = order_redelivery_trans.employee_id and order_redelivery_trans.created_at = order_status_trans.created_at', 'left');
        $this->db->order_by('orders.id');
        $this->db->group_by('orders.id, order_status_trans.status');
        
        $this->db->where("(date(orders.delivery_date) between '{$where['date_from']}' and '{$where['date_to']}' or "
        . "             date(orders.collection_date) between '{$where['date_from']}' and '{$where['date_to']}')");
        $this->db->where("(order_status_trans.status = 'box_collected' or order_status_trans.status = 'box_delivered')");
        $this->db->where("(date(order_status_trans.created_at) between '{$where['date_from']}' and '{$where['date_to']}')");
        
        $this->db->where('eod.status', 'yes');
//        $this->db->where("(order_redelivery_trans.id IS NULL  OR order_status_trans.employee_id = (
//                        SELECT employee_id FROM order_redelivery_trans AS ort WHERE ort.order_id = orders.id AND initial_delivery = 'yes'))");
  
        $this->db->where('boxes.drop_from_commission', 'no');
        $this->db->where("(eod.date between '{$where['date_from']}' and '{$where['date_to']}')");
        $this->db->where('eod.employee_id', $where['employee_id']);
        
        $query = $this->db->get();
        $result = $query->result_array();
 
        //        p($this->db->last_query(),0);
        
        return $result;
    }
    
    public function checkDriverIsPaidForDateRange($employee_id, $date_from, $date_to)
    {
        $this->db->from('commission_master');
        $this->db->where("((date_from >= '$date_from' and date_to <= '$date_to')  "
                . "OR (date_from <= '$date_from' and date_to >= '$date_to') "
                . "OR ('$date_from' between date_from and date_to) "
                . "OR ('$date_to' between date_from and date_to))");
        $this->db->where('employee_id', $employee_id);
        
        $query = $this->db->get();
        $result = $query->row_array();
        
        //        p($this->db->last_query());
        
         return $result;
    }
    
    public function checkPaymentReference($payment_reference)
    {
        $this->db->from('commission_master');
        $this->db->where('payment_reference', $payment_reference);
        
        $query = $this->db->get();
        $result = $query->row_array();
        
        return $result;
    }
    
    public function saveCommissionMaster($dataValues)
    {
        $return = null;
        if (count($dataValues) > 0)
        {
            if (array_key_exists('id', $dataValues))
            {
                $this->db->where('id', $dataValues['id']);
                $this->db->update('commission_master', $dataValues);

                $return = $dataValues['id'];
            }
            else
            {
                $this->db->insert('commission_master', $dataValues);
                $return = $this->db->insert_id();
            }
        }
        return $return;
    }
    
    public function saveCommissionLineItem($dataValues)
    {
        $return = null;
        if (count($dataValues) > 0)
        {
            if (array_key_exists('id', $dataValues))
            {
                $this->db->where('id', $dataValues['id']);
                $this->db->update('commission_line_items', $dataValues);

                $return = $dataValues['id'];
            }
            else
            {
                $this->db->insert('commission_line_items', $dataValues);
                $return = $this->db->insert_id();
            }
        }
    return $return;
    }
    
    public function getAllPaymentReferences($pagingParams = array())
    {
        $this->db->_protect_identifiers = false;

        $this->db->select('commission_master.id, payment_reference, date_format(date_from, "%d/%m/%Y") as date_from, '
                . 'date_format(date_to, "%d/%m/%Y") as date_to, employee_id, user.name as driver, grand_total, '
                . 'total_boxes');
        $this->db->from('commission_master');
        $this->db->join('user', 'user.id = commission_master.employee_id');

        if (isset($pagingParams['sSearch']))
        {
            $search = $pagingParams['sSearch'];
            if (!empty($search))
            {
                $this->db->like('user.name', $search);
                $this->db->or_like('payment_reference', $search);
            }
        }
        
        if (!empty($pagingParams['iSortCol_0']))
        {
            if (empty($pagingParams['sSortDir_0']))
            {
                $pagingParams['sSortDir_0'] = 'desc';
            }
            $column_arr = array('payment_reference', 'date_from', 'date_to', 'driver', 'grand_total');
            switch ($pagingParams['sSortDir_0'])
            {
                default:
                    $this->db->order_by($column_arr[$pagingParams['iSortCol_0'] + 1], $pagingParams['sSortDir_0']);
                    break;
            }
        }
        else
        {
            $this->db->order_by('commission_master.id desc');
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

    public function getPaymentReferenceCount($pagingParams = array())
    {
        $count = '0';
        
        if (isset($pagingParams['sSearch']))
        {
            $search = $pagingParams['sSearch'];
            if (!empty($search))
            {
                $this->db->like('user.name', $search);
                $this->db->or_like('payment_reference', $search);
            }
        }
        
        $this->db->select('count("commission_master.id") as count');
        $this->db->from('commission_master');
        $this->db->join('user', 'user.id = commission_master.employee_id');
        
        $this->db->select('count("id") as count');
        $query = $this->db->get()->row();
        $count = $query->count;
        return $count;
    }

    public function getBoxById($id)
    {
        $result = Null;
        if (!empty($id))
        {
            $this->db->select('*');
            $this->db->where('id', $id);
            $this->db->from('boxes');
            $result = $this->db->get()->row();
        }
        return $result;
    }

    public function getPaymentReferenceLineItems($payment_reference_id, $boxWiseTotal=false)
    {
        $result = Null;
        
        if (!empty($payment_reference_id))
        {
            if (empty($boxWiseTotal))
            {
                $this->db->select('commission_line_items.*,orders.order_number, boxes.name as box');
            }
            else
            {
                $this->db->select('sum(amount) as total_amount, line_item, count(commission_line_items.id) as total_boxes');
                $this->db->where('commission_line_items.type', 'system');
                $this->db->group_by('line_item');
            }
            
            $this->db->where('commission_line_items.commission_master_id', $payment_reference_id);
            $this->db->from('commission_line_items');
            $this->db->join('boxes', 'boxes.id = commission_line_items.item_id', 'left');
            $this->db->join('orders', 'commission_line_items.line_item = orders.id', 'left');
            $this->db->order_by('operation');
            $result = $this->db->get();
   
            $result = $result->result_array();
        }
          return $result;
    }

    public function getPaymentReferenceOrdersData($payment_reference_id)
    {
        $result = Null;
        
        if (!empty($payment_reference_id))
        {
            $this->db->select('commission_orders_trans.*, boxes.name as box, commission_line_items.base_commission');
            $this->db->from('commission_orders_trans');
            $this->db->join('boxes', 'boxes.id = commission_orders_trans.box_id', 'left');
            $this->db->join('commission_line_items', 'commission_line_items.id = commission_orders_trans.commission_line_item_id', 'left');
            $this->db->where('commission_orders_trans.commission_master_id', $payment_reference_id);
            $this->db->order_by('type, order_number');
            $result = $this->db->get();
       
            $result = $result->result_array();
     }
      return $result;
    }

    public function getPaymentReferenceData($payment_reference_id)
    {
        $result = Null;
        
        if (!empty($payment_reference_id))
        {
            $this->db->_protect_identifiers = false;
            $this->db->select('commission_master.*, date_format(date_from, "%d/%m/%Y") as date_from, '
                . 'date_format(date_to, "%d/%m/%Y") as date_to,user.name as driver');
            $this->db->where('commission_master.id', $payment_reference_id);
            $this->db->from('commission_master');
            $this->db->join('user', 'user.id = commission_master.employee_id');
            $result = $this->db->get()->row_array();
        }
        return $result;
    }
      
    public function deletePaymentReference($id)
    {
        $this->db->where('commission_master.id', $id);
        $this->db->delete('commission_master');
    }
    
    public function deletePaymentReferenceLineItems($id)
    {
        $this->db->where('commission_line_items.commission_master_id', $id);
        $this->db->delete('commission_line_items');
    }
    
    public function deletePaymentReferenceOrders($id)
    {
        $this->db->where('commission_orders_trans.commission_master_id', $id);
        $this->db->delete('commission_orders_trans');
    }
    
    public function saveCommissionOrderInfo($dataValues)
    {
        $return = null;
        if (count($dataValues) > 0)
        {
            if (array_key_exists('id', $dataValues))
            {
                $this->db->where('id', $dataValues['id']);
                $this->db->update('commission_orders_trans', $dataValues);

                $return = $dataValues['id'];
            }
            else
            {
                $this->db->insert('commission_orders_trans', $dataValues);
                $return = $this->db->insert_id();
            }
        }
  return $return;
    }
}

?>