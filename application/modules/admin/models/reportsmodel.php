<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ReportsModel extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getDeliveryRunSheet($date_from, $date_to, $driver_ids)
    {
        $this->db->_protect_identifiers=false;
        $this->db->select("orders.*, GROUP_CONCAT(CONCAT(locations.name, '(',  kabupatens.name, ')')  ORDER BY order_trans.id SEPARATOR '@@##@@') AS location,
                            GROUP_CONCAT(quantity ORDER BY order_trans.id SEPARATOR '@@##@@' ) AS quantity,
                            GROUP_CONCAT(boxes.name ORDER BY order_trans.id SEPARATOR '@@##@@') AS box,
                            customers.name as customer, customers.mobile, customers.residence_phone,
                            date(delivery_date) as delivery_date,
                            count(orders.id) as count", FALSE);
        $this->db->from('orders');
        $this->db->join('customers', 'customers.id = orders.customer_id');
        $this->db->join('order_status_trans', 'orders.id = order_status_trans.order_id');
        $this->db->join('user', 'user.id = order_status_trans.employee_id', 'left');
        $this->db->join('order_trans', 'orders.id = order_trans.order_id');
        $this->db->join('locations', 'locations.id= order_trans.location_id');
        $this->db->join('boxes', 'boxes.id= order_trans.box_id');
        $this->db->join('kabupatens', 'locations.id= kabupatens.location_id and order_trans.kabupaten_id = kabupatens.id', 'left');
        $this->db->order_by('CONVERT(orders.order_number, DECIMAL) asc');
        $this->db->group_by('orders.id');
        $this->db->where("(date(delivery_date) >= '$date_from' and  date(delivery_date) <= '$date_to')");
        $this->db->where('orders.status', 'active');
        $this->db->where('orders.kiv_status', 'no');
           
        if (empty($driver_ids))
        {
            $this->db->where('order_status_trans.active', 'yes');
            $this->db->select("(SELECT GROUP_CONCAT(ost.`employee_id`, '@@##@@', ost.`status` ) FROM order_status_trans ost WHERE ost.order_id  = orders.id)
                                AS username");
        }
        else
        {
            $this->db->select('user.name as username');
            $this->db->where_in('order_status_trans.employee_id', $driver_ids);
            $this->db->where_in('order_status_trans.status', array('booking_attended_by_driver'));
        }

        
        $query = $this->db->get();
        
//        p($this->db->last_query(),0);
        $result = $query->result_array();
        return $result;
    }

    public function getDeliveredBoxesReports($date_from, $date_to, $shipment_batch_ids, $exclude_boxes_id, $exclude_location_id)
    { 
        $this->db->_protect_identifiers=false;
        $this->db->select("orders.id,orders.order_number,user.name AS driver_name,shipment_batches.batch_name,kabupatens.name  AS kabupatens_name, locations.name AS location_name,order_trans.quantity", FALSE);
        $this->db->from('orders');
        $this->db->join('order_trans', 'orders.id = order_trans.order_id');
        $this->db->join('kabupatens', 'kabupatens.id = order_trans.kabupaten_id');
        $this->db->join('order_status_trans', 'order_status_trans.order_id = orders.id');
        $this->db->join('locations', 'locations.id= order_trans.location_id');
        $this->db->join('user', 'user.id= order_status_trans.employee_id');
        $this->db->join('shipment_batches', 'shipment_batches.id= orders.shipment_batch_id');
        
        if(!empty($shipment_batch_ids))
        {
            $this->db->where_in('shipment_batches.id',$shipment_batch_ids);
        }
        
        if(!empty($date_from) && !empty($date_to))
        {
            $this->db->where("(date(order_status_trans.updated_at) >= '$date_from' and  date(order_status_trans.updated_at) <= '$date_to')");
        }
        
        $this->db->where('orders.status', 'active');
        $this->db->where('order_status_trans.status', 'delivered_at_jkt_picture_not_taken');
        $this->db->where('order_status_trans.active', 'yes');
        $this->db->where_not_in('order_trans.box_id', $exclude_boxes_id);
        $this->db->where_not_in('order_trans.location_id', $exclude_location_id);
        $this->db->order_by('locations.name');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }
    
    public function getdeliveredReports($received,$receiving_batch_id,$search_by_destination_id,$box_no,$temp_date_from, $temp_date_to)
    { 
        $this->db->_protect_identifiers=false;
        $this->db->select("orders.id,orders.order_number,orders.collection_date as collection_date,user.username,shipment_batches.ship_onboard,orders.jkt_received_date, orders.delivery_date, orders.memo,user.name AS driver_name,shipment_batches.batch_name,kabupatens.name  AS kabupatens_name, locations.name AS location_name,
                            SUM(order_trans.quantity) AS quantity,
                            GROUP_CONCAT(boxes.name ORDER BY order_trans.id SEPARATOR ',') AS box,", FALSE);
        $this->db->from('orders');
        $this->db->join('order_trans', 'orders.id = order_trans.order_id');
        $this->db->join('kabupatens', 'kabupatens.id = order_trans.kabupaten_id');
        $this->db->join('order_status_trans', 'order_status_trans.order_id = orders.id');
        $this->db->join('locations', 'locations.id= order_trans.location_id');
        $this->db->join('user', 'user.id= order_status_trans.employee_id');
        $this->db->join('boxes', 'boxes.id= order_trans.box_id');
        $this->db->join('shipment_batches', 'shipment_batches.id= orders.shipment_batch_id');
        
        if(!empty($receiving_batch_id))
        {
            $this->db->where('shipment_batches.receiving_batch_id',$receiving_batch_id);
        }
        if(!empty($search_by_destination_id))
        {
            $this->db->where('locations.id',$search_by_destination_id);
        }
        
        if(!empty($temp_date_from) && !empty($temp_date_to))
        {
            $this->db->where("(date(orders.delivery_date) >= '$temp_date_from' and  date(orders.delivery_date) <= '$temp_date_to')");
        }
        else if(empty($box_no)) 
        {
           $this->db->where("(date(orders.delivery_date) >= NOW() - INTERVAL 3 MONTH)");
        }
        
        if(!empty($received))
        {
            switch ($received)
            {
                case 'Checked':
                    $this->db->where_in('order_status_trans.status', array("delivered_at_jkt_picture_taken",'delivered_at_jkt_picture_not_taken'));            
                   break;
                case 'Unchecked':
                    $this->db->where_not_in('order_status_trans.status', array("delivered_at_jkt_picture_taken",'delivered_at_jkt_picture_not_taken'));            
                   break;
                case 'indeterminate':
                                
                   break;
            }    
        }
        
        if(!empty($box_no))
        {
            $this->db->like('orders.order_number', $box_no);            
        }
        $this->db->where('orders.status', 'active');
        $this->db->where('order_status_trans.active', 'yes');
        $this->db->group_by('orders.id');        
        $this->db->order_by('shipment_batches.batch_name','desc');
//        $this->db->limit(20);
        
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }  
     
    public function get_status_date_by_order_id($id,$status)
    {
        $this->db->select("order_status_trans.created_at as status_status_created_at");
        $this->db->from('order_status_trans');
        $this->db->where("order_status_trans.status ",$status);
        $this->db->where("order_status_trans.order_id ",$id);
        
        $query = $this->db->get();
        $result = $query->result_array();
        
        if ($query->num_rows() > 0)
        {
            $data = $query->row_array();
            
            return $data['status_status_created_at'];
        }

        return FALSE;
    }
    public function getWarehouseTallySheet($where)
    {
        $this->db->select("orders.order_number, order_date, user.name as driver_name, 
                            sum(quantity) as quantity, orders.weight,
                            GROUP_CONCAT((boxes.volume * quantity) ORDER BY order_trans.id SEPARATOR ',<br/>') AS volume,
                            GROUP_CONCAT(quantity ORDER BY order_trans.id SEPARATOR '@@##@@') AS individual_quantity,
                            GROUP_CONCAT(boxes.name ORDER BY order_trans.id SEPARATOR ',<br/>') AS box_name,
                            GROUP_CONCAT(boxes.name ORDER BY order_trans.id SEPARATOR '@@##@@') AS box_name_ws,
                            GROUP_CONCAT(locations.name ORDER BY order_trans.id SEPARATOR ',<br/>') AS locations,
                            GROUP_CONCAT(kabupatens.name ORDER BY order_trans.id SEPARATOR ',<br/>') AS kabupatens,
                            sum(boxes.volume * quantity) as total_volume, order_status_trans.status as order_status,
                            order_status_trans.comments", FALSE);
        $this->db->from('eod');
        $this->db->join('order_status_trans', 'eod.employee_id = order_status_trans.employee_id');
        $this->db->join('user', 'user.id = order_status_trans.employee_id');
        $this->db->join('orders', 'orders.id = order_status_trans.order_id');
        $this->db->join('order_trans', 'orders.id = order_trans.order_id');
        $this->db->join('boxes', 'boxes.id= order_trans.box_id');
        
        $this->db->join('locations', 'locations.id= order_trans.location_id');
        $this->db->join('kabupatens', 'locations.id= kabupatens.location_id and order_trans.kabupaten_id = kabupatens.id', 'left');
        
        $this->db->order_by('orders.id');
        $this->db->group_by('orders.id');
        
//        $this->db->where('responsibility_completed', 'yes');
//        $this->db->where('order_status_trans.active', 'yes');
        $this->db->where("(order_status_trans.status = 'box_collected')");
        $this->db->where("date(orders.collection_date)", $where['date']);
        $this->db->where('eod.status', 'yes');
        $this->db->where('eod.date', $where['date']);
        $this->db->where('eod.employee_id', $where['employee_id']);
        
        $query = $this->db->get();
        $result = $query->result_array();
//        p($this->db->last_query(),0);
        return $result;
    }
    

    public function getCashReport($where)
    {
        $this->db->select("orders.id as orderid, orders.order_number, orders.status as order_status, order_date, 
                            IF(FIND_IN_SET('box_collected', GROUP_CONCAT(order_status_trans.status)) = 0, 'box_delivered', 'box_collected') AS status,
                            customers.building, customers.unit, customers.block, customers.street, customers.pin,
                            customers.name as customer_name, customers.mobile, customers.residence_phone,
                            user.name as driver_name,  orders.nett_total, orders.grand_total, orders.discount,
                            sum(order_trans.quantity) as quantity,
                            if(delivery_date = collection_date, 1, 0) as dnc,
                            group_concat(boxes.name ORDER BY order_trans.id SEPARATOR '<br>') as boxes,
                            group_concat(order_trans.quantity ORDER BY order_trans.id SEPARATOR '<br>') as quantities,
                            group_concat(locations.name ORDER BY order_trans.id SEPARATOR '<br>') as locations,
                            (select cash_collected from order_status_trans od where od.order_id = orders.id and status='box_delivered') as cash_collected_delivery,
                            (select cash_collected from order_status_trans oc where oc.order_id = orders.id and status='box_collected') as cash_collected_collection,
                            (select voucher_cash from order_status_trans o1 where o1.order_id = orders.id and status='box_delivered') as voucher_cash_delivery,
                            (select voucher_cash from order_status_trans o2 where o2.order_id = orders.id and status='box_collected') as voucher_cash_collection,
                            order_status_trans.comments,
                            ", FALSE);
        $this->db->from('eod');
        $this->db->join('order_status_trans', 'eod.employee_id = order_status_trans.employee_id');
        $this->db->join('user', 'user.id = order_status_trans.employee_id');
        $this->db->join('orders', 'orders.id = order_status_trans.order_id');
        $this->db->join('order_trans', 'orders.id = order_trans.order_id');
        $this->db->join('boxes', 'order_trans.box_id = boxes.id');
        $this->db->join('locations', 'order_trans.location_id = locations.id');
        $this->db->join('customers', 'orders.customer_id = customers.id');
        $this->db->order_by('orders.id');
        $this->db->group_by('orders.id, order_status_trans.status');
        
        $this->db->where("(date(orders.delivery_date) = '{$where['date']}' or date(orders.collection_date) = '{$where['date']}')");
//        $this->db->where('responsibility_completed', 'yes');
//        $this->db->where('order_status_trans.active', 'yes');
        $this->db->where("(order_status_trans.status = 'box_collected' or order_status_trans.status = 'box_delivered')");
        $this->db->where("(date(order_status_trans.created_at) = '{$where['date']}')");
        
        $this->db->where('eod.status', 'yes');
        $this->db->where('eod.date', $where['date']);
        $this->db->where('eod.employee_id', $where['employee_id']);
        
        $query = $this->db->get();
        $result = $query->result_array();
        
//        p($this->db->last_query(),0);
        
        return $result;
    }
    
    public function getCollectionRunSheet($date_from, $date_to, $driver_ids)
    {
        $this->db->_protect_identifiers=false;
        $this->db->select("orders.*, GROUP_CONCAT(CONCAT(locations.name, '(',  kabupatens.name, ')')  ORDER BY order_trans.id SEPARATOR '@@##@@') AS location,
                            GROUP_CONCAT(quantity ORDER BY order_trans.id SEPARATOR '@@##@@' ) AS quantity,
                            GROUP_CONCAT(boxes.name ORDER BY order_trans.id SEPARATOR '@@##@@') AS box,
                            GROUP_CONCAT(total_price ORDER BY order_trans.id SEPARATOR '@@##@@') AS total_price,
                            customers.name as customer, customers.mobile, customers.residence_phone,
                            count(orders.id) as count", FALSE);
        $this->db->from('orders');
        $this->db->join('order_status_trans', 'orders.id = order_status_trans.order_id');
        $this->db->join('user', 'user.id = order_status_trans.employee_id', 'left');
        $this->db->join('customers', 'customers.id = orders.customer_id', 'left');
        $this->db->join('order_trans', 'orders.id = order_trans.order_id');
        $this->db->join('locations', 'locations.id= order_trans.location_id');
        $this->db->join('boxes', 'boxes.id= order_trans.box_id');
        $this->db->join('kabupatens', 'locations.id= kabupatens.location_id and order_trans.kabupaten_id = kabupatens.id', 'left');
        $this->db->order_by('CONVERT(orders.order_number, DECIMAL) asc');
        $this->db->group_by('orders.id');
//        $this->db->where('date(collection_date)', $date);
        $this->db->where("(date(collection_date) >= '$date_from' and  date(collection_date) <= '$date_to')");
        $this->db->where('orders.status', 'active');
        $this->db->where('orders.kiv_status', 'no');
        
        if (empty($driver_ids))
        {
            $this->db->where('order_status_trans.active', 'yes');
            $this->db->select("(SELECT GROUP_CONCAT(ost.`employee_id`, '@@##@@', ost.`status` ) FROM order_status_trans ost WHERE ost.order_id  = orders.id)
                                AS username");
        }
        else
        {
            $this->db->select('user.name as username');
            $this->db->where_in('order_status_trans.employee_id', $driver_ids);
            $this->db->where_in('order_status_trans.status', array('collection_attended_by_driver'));
        }
        
        $query = $this->db->get();
      
        $result = $query->result_array();
        return $result;
    }
    
    public function getLiveFeeds($where, $ordering_criteria_flag)
    {
        $this->db->_protect_identifiers=false;

        $this->db->select("order_status_trans.qr_manual_entry,order_status_trans.id, orders.status as order_status, orders.id as order_id, order_number, customers.name AS customer_name,
                            customers.mobile, customers.residence_phone, order_status_trans.employee_id,
                            order_status_trans.status, order_status_trans.coordinates_type, user.name AS driver, orders.lattitude, orders.longitude,
                            orders.building, orders.block, orders.unit, orders.street, orders.pin, orders.google_lat, orders.google_lon,
                            if(date(delivery_date) = date(collection_date), 1, 0) as d_and_c, order_status_trans.status_escalation_type,
                            order_status_trans.updated_at,
                            (SELECT username.name FROM user username WHERE username.id = orders.`updated_by`) as updatedby", FALSE);
        $this->db->from('orders');
        $this->db->join('customers', 'customers.id = orders.customer_id');
        $this->db->join('order_status_trans', 'orders.id = order_status_trans.order_id and order_status_trans.active = "yes"');
        $this->db->join('user', 'user.id = order_status_trans.employee_id');

        $this->db->order_by($ordering_criteria_flag.' asc, order_status_trans.id desc');
        
        if (!empty($where))
        {
            if (!empty($where['employee_id']))
            {
                $this->db->where('employee_id', $where['employee_id']);
            }
            
            if (!empty($where['collection_date_from']) && !empty($where['delivery_date_from']) && !empty($where['collection_date_to']) && !empty($where['delivery_date_to']))
            {
                 $this->db->where("((date(collection_date) >= '{$where['collection_date_from']}' and  date(collection_date) <= '{$where['collection_date_to']}')"
                 . " or (date(delivery_date) >= '{$where['delivery_date_from']}' and  date(delivery_date) <= '{$where['delivery_date_to']}'))");
//                $this->db->where("(date(collection_date) >= '{$where['collection_date_from']}' and  date(collection_date) <= '{$where['collection_date_to']}')");
//                $this->db->or_where("(date(delivery_date) >= '{$where['delivery_date_from']}' and  date(delivery_date) <= '{$where['delivery_date_to']}')");
//                $this->db->where("(date(collection_date) = '{$where['collection_date_from']}' or date(delivery_date) = '{$where['delivery_date_from']}')");
            }
            else
            {
                if (!empty($where['collection_date_from']) && !empty($where['collection_date_to']))
                {
//                    $this->db->where("date(collection_date) = '{$where['collection_date_from']}'");
                    $this->db->where("(date(collection_date) >= '{$where['collection_date_from']}' and  date(collection_date) <= '{$where['collection_date_to']}')");
                }
                if (!empty($where['delivery_date_from']) && !empty($where['delivery_date_to']))
                {
                    $this->db->where("(date(delivery_date) >= '{$where['delivery_date_from']}' and  date(delivery_date) <= '{$where['delivery_date_to']}')");
//                    $this->db->where("date(delivery_date) = '{$where['delivery_date_from']}'");
                }
            }
        }
        
        
        $query = $this->db->get();
//        echo $this->db->last_query();
        $result = $query->result_array();
        return $result;
    }
    
    public function getCollectionCallData($days, $pagingParams = array())
    {
        $current_date = date('Y-m-d');
        
        $this->db->select("orders.*, orders.`id` AS order_id_no,customers.name, mobile, residence_phone,"
                ."(SELECT  GROUP_CONCAT(order_followup.created_at ORDER BY order_followup.id SEPARATOR '@@##@@')"
                ." FROM order_followup WHERE order_followup.order_id = order_id_no AND TYPE = 'collection') AS followedup_time,"
                ." (SELECT GROUP_CONCAT(order_followup.comments ORDER BY order_followup.id SEPARATOR '@@##@@')"
                ." FROM order_followup WHERE order_followup.order_id = order_id_no AND TYPE = 'collection') AS remarks , "
//                . "group_concat(order_followup.created_at ORDER BY order_followup.id SEPARATOR '@@##@@') AS followedup_time,"
//                . "group_concat(order_followup.comments ORDER BY order_followup.id SEPARATOR '@@##@@') AS remarks,"
                . "GROUP_CONCAT(boxes.`name` ORDER BY order_trans.id SEPARATOR ',<br>' ) AS boxes,"
                . "GROUP_CONCAT(locations.`name` ORDER BY order_trans.id SEPARATOR ',<br>' ) AS locations,"
                . "GROUP_CONCAT(kabupatens.`name` ORDER BY order_trans.id SEPARATOR ',<br>' ) AS kabupatens,"
                . "GROUP_CONCAT(order_trans.`quantity` ORDER BY order_trans.id SEPARATOR ',<br>' ) AS quantities"
                . ",(select sum(cash_collected) from order_status_trans where order_status_trans.order_id = orders.id) as total_deposit"
                . "", FALSE);
        $this->db->from('orders');
        $this->db->join('customers', 'customers.id = orders.customer_id');
//        $this->db->join("order_followup", "orders.id = order_followup.order_id and type = 'collection'", 'left');
//        $this->db->join('user', 'user.id = order_followup.employee_id', 'left');
        
        $this->db->join("order_trans", "orders.id = order_trans.order_id", 'left');
        $this->db->join("boxes", "boxes.id = order_trans.box_id", 'left');
        $this->db->join("locations", "locations.id = order_trans.location_id", 'left');
        $this->db->join("kabupatens", "kabupatens.id = order_trans.kabupaten_id", 'left');
        
//        $this->db->order_by('orders.delivery_date');
        $this->db->where("(collection_date = '0000-00-00 00:00:00' or collection_date = '' or collection_date IS NULL)");
//        $this->db->where("DATEDIFF('$current_date', DATE(delivery_date)) <= $days");
        
        switch ($days) 
        {
            case "7":
                $this->db->where("DATE_SUB('$current_date', INTERVAL 7 DAY) > DATE(delivery_date)");
                $this->db->where("DATE_SUB('$current_date', INTERVAL 29 DAY) <= DATE(delivery_date)");
                break;
            case "30":
                $this->db->where("DATE_SUB('$current_date', INTERVAL 30 DAY) > DATE(delivery_date)");
                $this->db->where("DATE_SUB('$current_date', INTERVAL 60 DAY) <= DATE(delivery_date)");
                break;
            case "60":
                $this->db->where("DATE_SUB('$current_date', INTERVAL 60 DAY) > DATE(delivery_date)");
                $this->db->where("DATE_SUB('$current_date', INTERVAL 365 DAY) <= DATE(delivery_date)");
                break;
            case "365":
                $this->db->where("DATE_SUB('$current_date', INTERVAL 365 DAY) > DATE(delivery_date)");
                break;

            default:
                break;
        }
        
        $this->db->where('orders.status', 'active');
        $this->db->where('orders.kiv_status', 'no');
        
        $this->db->group_by('orders.id');
        
        
        if (isset($pagingParams['sSearch']))
        {
            $search = $pagingParams['sSearch'];
            if (!empty($search))
            {
                $this->db->like('order_number', $search);
            }
        }
        if (!empty($pagingParams['iSortCol_0']))
        {
            if (empty($pagingParams['sSortDir_0']))
            {
                $pagingParams['sSortDir_0'] = 'desc';
            }
            $column_arr = array("order_number",'name','mobile','amount','delivery_date','address','boxes','quantities','location','kabupatens','remarks','operations');
            switch ($pagingParams['sSortDir_0'])
            {
                default:
                    $this->db->order_by($column_arr[$pagingParams['iSortCol_0']], $pagingParams['sSortDir_0']);
                    break;
            }
        }
        else
        {
            $this->db->order_by('orders.delivery_date');
        }
        
        if (isset($pagingParams['iDisplayStart']) && $pagingParams['iDisplayLength'] != '-1')
        {
            $start = $pagingParams['iDisplayStart'];
            $offset = $pagingParams['iDisplayLength'];
           
            $return = $this->getWithCount(null, $offset, $start);
//            p($this->db->last_query());
            return $return;
        }
    }
    
    public function getEODReportsData($date, $pagingParams = array())
    {
        $this->db->select("eod.*, user.name", FALSE);
        $this->db->from('eod');
        $this->db->join('user', 'user.id = eod.employee_id');
        $this->db->order_by('eod.id');
        $this->db->where('eod.date', $date);
        
        if (isset($pagingParams['sSearch']))
        {
            $search = $pagingParams['sSearch'];
            if (!empty($search))
            {
                $this->db->like('user.name', $search);
            }
        }
        if (!empty($pagingParams['iSortCol_0']))
        {
            if (empty($pagingParams['sSortDir_0']))
            {
                $pagingParams['sSortDir_0'] = 'desc';
            }
            $column_arr = array('name');
            switch ($pagingParams['sSortDir_0'])
            {
                default:
                    $this->db->order_by($column_arr[$pagingParams['iSortCol_0'] + 1], $pagingParams['sSortDir_0']);
                    break;
            }
        }
        else
        {
            $this->db->order_by('eod.id');
        }
        
        if (isset($pagingParams['iDisplayStart']) && $pagingParams['iDisplayLength'] != '-1')
        {
            $start = $pagingParams['iDisplayStart'];
            $offset = $pagingParams['iDisplayLength'];
           
            $return = $this->getWithCount(null, $offset, $start);
            //p($this->db->last_query());
            return $return;
        }
    }
    
    public function updateCallFollowUpStatus($order_id)
    {
        $this->db->where('order_id', $order_id);
        $this->db->update('order_followup', array('active' => 'no'));
    }
    
    public function saveCallFollowUp($data)
    {
        $this->db->insert('order_followup', $data);
        $return = $this->db->insert_id();
        return $return;
    }

    public function getDateWiseTaskListingByEmployee($array)
    {
        $this->db->select("order_status_trans.id, orders.id as order_id, order_number, customers.name AS customer_name,
                            customers.mobile, customers.residence_phone, order_status_trans.employee_id,
                            order_status_trans.status, user.username AS driver,
                            order_status_trans.updated_at, orders.lattitude, orders.longitude", FALSE);
        $this->db->from('orders');
        $this->db->join('customers', 'customers.id = orders.customer_id');
        $this->db->join('order_status_trans', 'orders.id = order_status_trans.order_id');
        $this->db->join('user', 'user.id = order_status_trans.employee_id');
        $this->db->join('employee_order_ordering', "employee_order_ordering.order_id = orders.id and employee_order_ordering.employee_id = '{$array['employee_id']}'", "left");
        
        $this->db->where('orders.status', 'active');
        $this->db->where('order_status_trans.active', 'yes');
        $this->db->where('order_status_trans.employee_id', $array['employee_id']);
        $this->db->where("(date(orders.delivery_date) = '{$array['date']}' or date(orders.collection_date) = '{$array['date']}')");
        
        $this->db->order_by('(CASE WHEN employee_order_ordering.order  IS NULL then 1000000 ELSE employee_order_ordering.order END) asc, orders.id asc, order_status_trans.id desc');
//        $this->db->order_by('orders.id asc, order_status_trans.id desc');
        
        $query = $this->db->get();
//        p($this->db->last_query(),0);
        $result = $query->result_array();
        return $result;
    }  
    
    public function getShipmentBatchOrderData($shipment_batch_id)
    {
        $this->db->_protect_identifiers = false;

        $this->db->select("orders.id, order_date, delivery_date, collection_date, order_number, orders.status as order_status, "
                . "orders.building, orders.street, orders.unit, orders.block,orders.pin, kiv_status, printed_instruments, "
                . "orders.nett_total, orders.grand_total, orders.discount,orders.recipient_mobile,"
                . " customers.name as customer_name, customers.passport_id_number as customer_passport_id_number,customers.mobile as customer_mobile,customers.building as customer_building ,customers.street as customer_street ,"
                . "recipient_name,recipient_item_list,recipient_address,jkt_receiver, mobile, residence_phone, shipment_batches.batch_name as batch,  "
                . "order_status_trans.status, shipment_batches.consignee_order_id,shipment_batches.ship_onboard,shipment_batches.container_number,shipment_batches.seal_number,shipment_batches.bl_number,"
                . "GROUP_CONCAT(boxes.`name` ORDER BY order_trans.id SEPARATOR '@@##@@' ) AS boxes,"
                . "GROUP_CONCAT(locations.`name` ORDER BY order_trans.id SEPARATOR '@@##@@' ) AS locations,"
                . "GROUP_CONCAT(kabupatens.`name` ORDER BY order_trans.id SEPARATOR '@@##@@' ) AS kabupatens,"
                . "GROUP_CONCAT(order_trans.`quantity` ORDER BY order_trans.id SEPARATOR '@@##@@' ) AS quantities,"
                . "(select concat(group_concat(status order by ost.id), '@@##@@', group_concat(u.name order by ost.id))"
                . " from order_status_trans ost join user u on (u.id = ost.employee_id) where ost.order_id = orders.id) as statuses ,"
                . "count(orders.id) as count", FALSE);
        $this->db->from('orders');
        $this->db->join('customers', 'customers.id = orders.customer_id');
        $this->db->join("order_status_trans", "orders.id = order_status_trans.order_id and order_status_trans.active = 'yes'");
        $this->db->join("shipment_batches", "shipment_batches.id = orders.shipment_batch_id", "left");
        $this->db->join("order_trans", "orders.id = order_trans.order_id");
        $this->db->join("boxes", "boxes.id = order_trans.box_id");
        $this->db->join("locations", "locations.id = order_trans.location_id");
        $this->db->join("kabupatens", "kabupatens.id = order_trans.kabupaten_id", "left");

        $this->db->group_by('orders.id');
        $this->db->where('orders.shipment_batch_id', $shipment_batch_id);
                       
//        $this->db->order_by('CONVERT(orders.order_number, DECIMAL) asc');
        $this->db->order_by('CASE WHEN (orders.order_number = shipment_batches.`consignee_order_id`) THEN 0 ELSE 1 END, 
CONVERT(orders.order_number, DECIMAL) ASC
');
        $return = $this->db->get()->result_array();
        return $return;
    } 
    
    public function getLiveFeedsJkt($where, $ordering_criteria_flag, $status=null)
    {
        $this->db->_protect_identifiers = false;

        $this->db->select("order_status_trans.id, orders.status as order_status, orders.id as order_id, order_number, customers.name AS customer_name,
                            customers.mobile, customers.residence_phone, order_status_trans.employee_id,
                            order_status_trans.status, order_status_trans.coordinates_type, user.name AS driver, orders.lattitude, orders.longitude,
                            orders.building, orders.block, orders.unit, orders.street, orders.pin, orders.google_lat, orders.google_lon,
                            order_status_trans.status_escalation_type,
                            order_status_trans.updated_at,
                            SUM(order_trans.quantity) AS total_boxes,
                            (SELECT username.name FROM user username WHERE username.id = orders.`updated_by`) as updatedby", FALSE);
        $this->db->from('orders');
        $this->db->join('customers', 'customers.id = orders.customer_id');
        $this->db->join('order_status_trans', 'orders.id = order_status_trans.order_id and order_status_trans.active = "yes"');
        $this->db->join('order_trans', 'orders.id = order_trans.order_id');
        $this->db->join('user', 'user.id = order_status_trans.employee_id');
        $this->db->join('shipment_batches', 'shipment_batches.id = orders.shipment_batch_id');
        $this->db->join('receiving_batches', 'receiving_batches.id = shipment_batches.receiving_batch_id');
        
        if (!empty($status))
        {
            $this->db->where_in('order_status_trans.status', $status);
        }
        
        $this->db->group_by('orders.id');

        $this->db->order_by($ordering_criteria_flag);
        
        if (!empty($where))
        {
            if (empty($where['receiving_batch_id']))
            {
                $this->db->where('receiving_batches.status', 'open');
            }
            else
            {
                $this->db->where('receiving_batches.id', $where['receiving_batch_id']);
            }
        }
        
        $query = $this->db->get();
        
//        echo $this->db->last_query();exit;
        $result = $query->result_array();
        return $result;
    }
    
    public function getReceivingBatchOrderData($receiving_batch_id)
    {
        
        $this->db->select("orders.id, order_date, delivery_date, collection_date, order_number, orders.status as order_status, "
                . "orders.building, orders.street, orders.unit, orders.block, orders.pin, kiv_status, printed_instruments, "
                . "orders.nett_total, orders.grand_total, orders.discount, "
                . " customers.name as customer_name,recipient_name,recipient_item_list,mobile, residence_phone, receiving_batches.name as batch, "
                . "order_status_trans.status,"
                . "GROUP_CONCAT(boxes.`name` ORDER BY order_trans.id SEPARATOR '@@##@@' ) AS boxes,"
                . "GROUP_CONCAT(locations.`name` ORDER BY order_trans.id SEPARATOR '@@##@@' ) AS locations,"
                . "GROUP_CONCAT(kabupatens.`name` ORDER BY order_trans.id SEPARATOR '@@##@@' ) AS kabupatens,"
                . "GROUP_CONCAT(order_trans.`quantity` ORDER BY order_trans.id SEPARATOR '@@##@@' ) AS quantities,"
                . "(select concat(group_concat(status order by ost.id), '@@##@@', group_concat(u.name order by ost.id))"
                . " from order_status_trans ost join user u on (u.id = ost.employee_id) where ost.order_id = orders.id) as statuses ,"
                . "count(orders.id) as count", FALSE);
        $this->db->from('orders');
        $this->db->join('customers', 'customers.id = orders.customer_id');
        $this->db->join("order_status_trans", "orders.id = order_status_trans.order_id and order_status_trans.active = 'yes'");
        $this->db->join("shipment_batches", "shipment_batches.id = orders.shipment_batch_id", "left");
        $this->db->join("receiving_batches", "receiving_batches.id = shipment_batches.receiving_batch_id", "left");
        $this->db->join("order_trans", "orders.id = order_trans.order_id");
        $this->db->join("boxes", "boxes.id = order_trans.box_id");
        $this->db->join("locations", "locations.id = order_trans.location_id");
        $this->db->join("kabupatens", "kabupatens.id = order_trans.kabupaten_id", "left");

        $this->db->group_by('orders.id');
        $this->db->where('shipment_batches.receiving_batch_id', $receiving_batch_id);
                       
        $this->db->order_by('order_trans.quantity desc');
       
        $return = $this->db->get()->result_array();
        return $return;
    } 
    
    
    
    
    
    
        public function getDriverCollectionSheet($date_from, $date_to, $driver_ids)
    {
        $this->db->_protect_identifiers = false;
        $this->db->select("user.id, `user`.name AS username, DATE(order_status_trans.created_at) AS `collection_date`, SUM(quantity) AS `count`, 
                           boxes.name as box", FALSE);
        $this->db->from('orders');
        $this->db->join('order_status_trans', 'orders.id = order_status_trans.order_id');
        $this->db->join('user', 'user.id = order_status_trans.employee_id', 'left');
        $this->db->join('order_trans', 'orders.id = order_trans.order_id');
        $this->db->join('boxes', 'boxes.id= order_trans.box_id');
        $this->db->group_by('boxes.`id`, username, DATE(order_status_trans.created_at)');
        $this->db->where("(date(order_status_trans.created_at) >= '$date_from' and  date(order_status_trans.created_at) <= '$date_to')");
        $this->db->where('orders.status', 'active');
        $this->db->order_by('`user`.name');
        if (empty($driver_ids))
        {
            $this->db->where_in('order_status_trans.status', array('box_collected'));
        
        }
        else
        {
            $this->db->where_in('order_status_trans.employee_id', $driver_ids);
            $this->db->where_in('order_status_trans.status', array('box_collected'));
        }
        
        $query = $this->db->get();
//          p($this->db->last_query());
        $result = $query->result_array();
        return $result;
    }

     public function getDestBoxesReports($date_from, $date_to, $shipment_ids)
    {
        $return = array();
        $this->db->select('boxes.name AS box_name,boxes.id AS box_id, `shipment_batches`.id AS shipment_batch_id , container_type,shipment_batches.batch_name AS batch, COUNT(DISTINCT
        orders.id) AS orders_count, SUM(order_trans.quantity) AS boxes_count');
        $this->db->from('shipment_batches');
        $this->db->join('orders','shipment_batches.id = orders.shipment_batch_id','left');
        $this->db->join('order_trans','orders.id = order_trans.order_id','left');
        $this->db->join('boxes','boxes.id = order_trans.box_id','left');
        if(!empty($shipment_ids))
        {
            $this->db->where_in('shipment_batch_id',$shipment_ids);
        }
        if(!empty($date_from) && !empty($date_to))
        {
        $this->db->or_where("(date(shipment_batches.load_date) >= '$date_from' and  date(shipment_batches.load_date) <= '$date_to')");
        }
        $this->db->group_by('boxes.id,shipment_batches.id');
        $this->db->order_by('batch_name DESC');
        $return = $this->db->get()->result_array();
        return $return;        
        
    }
    
    
    
    public function getDestBoxesLocationReports($date_from, $date_to, $shipment_ids)
    {
        $return = array();
        $this->db->select('boxes.name AS box_name,boxes.id AS box_id, locations.name AS location_name,
        locations.id AS location_id, COUNT(DISTINCT
        orders.id) AS orders_count, SUM(order_trans.quantity) AS boxes_count');
        $this->db->from('shipment_batches');
        $this->db->join('orders','shipment_batches.id = orders.shipment_batch_id','left');
        $this->db->join('order_trans','orders.id = order_trans.order_id','left');
        $this->db->join('boxes','boxes.id = order_trans.box_id','left');
        $this->db->join('locations','locations.id = order_trans.location_id','left');
        if(!empty($shipment_ids))
        {
            $this->db->where_in('shipment_batch_id',$shipment_ids);
        }
        if(!empty($date_from) && !empty($date_to))
        {
        $this->db->or_where("(date(shipment_batches.load_date) >= '$date_from' and  date(shipment_batches.load_date) <= '$date_to')");
        }
        $this->db->group_by('boxes.id,locations.id,shipment_batches.id');
        $this->db->order_by('batch_name DESC');
        $return = $this->db->get()->result_array();
        
        return $return;        
        
    }
    
    public function getDestLocationReports($date_from, $date_to, $shipment_ids)
    {
        $return = array();
        $this->db->select('locations.name AS location_name,
        locations.id AS location_id, shipment_batches.id AS shipment_batch_id , 
        container_type,shipment_batches.batch_name AS batch, COUNT(DISTINCT
        orders.id) AS orders_count, SUM(order_trans.quantity) AS boxes_count');
        $this->db->from('shipment_batches');
        $this->db->join('orders','shipment_batches.id = orders.shipment_batch_id','left');
        $this->db->join('order_trans','orders.id = order_trans.order_id','left');
        $this->db->join('locations','locations.id = order_trans.location_id','left');
        if(!empty($shipment_ids))
        {
            $this->db->where_in('shipment_batch_id',$shipment_ids);
        }
        $this->db->or_where("(date(shipment_batches.load_date) >= '$date_from' and  date(shipment_batches.load_date) <= '$date_to')");
        $this->db->group_by('locations.id,shipment_batches.id');
        $this->db->order_by('batch_name DESC');
        $return = $this->db->get()->result_array();
//        echo $this->db->last_query();exit;
        return $return;     
    }
    
    public function getWeeklyCollectionReportsData($date_from , $date_to)
    {
        $return = array();
        $this->db->_protect_identifiers = false;
        
//        $this->db->select('orders.id, SUM(quantity) AS box_count, boxes.name AS box,date(order_status_trans.created_at) as collection_date,boxes.id as box_id', false);
//        $this->db->from('orders');
//        $this->db->join('order_status_trans', 'orders.id = order_status_trans.order_id');
//        $this->db->join('user', 'user.id = order_status_trans.employee_id', 'left');
//        $this->db->join('order_trans', 'orders.id = order_trans.order_id');
//        $this->db->join('boxes', 'boxes.id= order_trans.box_id');
//        $this->db->group_by('date(order_status_trans.created_at), boxes.id');
//        $this->db->where("(date(order_status_trans.created_at) >= '$date_from' and  date(order_status_trans.created_at) <= '$date_to')");
//        $this->db->where('orders.status', 'active');
//        $this->db->order_by('`user`.name');
//        $this->db->where_in('order_status_trans.status', array('box_collected'));
        
        $this->db->select('orders.id, SUM(quantity) AS box_count, boxes.name AS box, date(collection_date) as collection_date, boxes.id as box_id', false);
        $this->db->from('orders');
        $this->db->join('order_trans', 'orders.id = order_trans.order_id');
        $this->db->join('boxes', 'boxes.id= order_trans.box_id');
        $this->db->group_by('date(collection_date), boxes.id');
        $this->db->where("(date(collection_date) >= '$date_from' and date(collection_date) <= '$date_to')");
        $this->db->where('orders.status', 'active');        

        $query = $this->db->get();
        $return = $query->result_array();
//       echo '<pre>'.$this->db->last_query();exit;
        return $return;
    }
    
    
    public function getDriverDeliverySheet($date_from, $date_to, $driver_ids)
    {
        $this->db->_protect_identifiers = false;
        $this->db->select("user.id, `user`.name AS username, DATE(order_status_trans.created_at) AS `delivery_date`, SUM(quantity) AS `count`, 
                           boxes.name as box", FALSE);
        $this->db->from('orders');
        $this->db->join('order_status_trans', 'orders.id = order_status_trans.order_id');
        $this->db->join('user', 'user.id = order_status_trans.employee_id', 'left');
        $this->db->join('order_trans', 'orders.id = order_trans.order_id');
        $this->db->join('boxes', 'boxes.id= order_trans.box_id');
        $this->db->group_by('boxes.`id`, username, DATE(order_status_trans.created_at)');
        $this->db->where("(date(order_status_trans.created_at) >= '$date_from' and  date(order_status_trans.created_at) <= '$date_to')");
        $this->db->where('orders.status', 'active');
        $this->db->order_by('`user`.name');
        if (empty($driver_ids))
        {
            $this->db->where_in('order_status_trans.status', array('box_delivered'));
        
        }
        else
        {
            $this->db->where_in('order_status_trans.employee_id', $driver_ids);
            $this->db->where_in('order_status_trans.status', array('box_delivered'));
        }
        
        $query = $this->db->get();
//          p($this->db->last_query());
        $result = $query->result_array();
        return $result;
    }
    
    public function getDepositsUncollectedReports($date_from, $date_to, $drivers_ids)
    {
        $this->db->_protect_identifiers=false;
        $result = array();
        $this->db->select('orders.*,orders.id as order_id,user.id, user.name AS username, DATE(order_status_trans.updated_at) AS collection_date,
                SUM(quantity) AS count, boxes.name AS box,
                cash_collected , voucher_cash , (SUM(quantity)  * 10) AS box_amount ,
                (SUM(quantity)  * 10) - (cash_collected + voucher_cash) AS uncollected_amount,order_status_trans.comments as remarks',false);
        $this->db->from('orders');
        $this->db->join('order_status_trans','orders.id = order_status_trans.order_id','left');
        $this->db->join('user','order_status_trans.employee_id = user.id','left');
        $this->db->join('order_trans','orders.id = order_trans.order_id','left');
        $this->db->join('boxes','boxes.id= order_trans.box_id','left');
        $this->db->where("(date(order_status_trans.created_at) >= '$date_from' and  date(order_status_trans.created_at) <= '$date_to')");
        $this->db->where('orders.status', 'active');
        
        if (empty($drivers_ids))
        {
            $this->db->where_in('order_status_trans.status', array('box_delivered'));
        }
        else
        {
            $this->db->where_in('order_status_trans.employee_id', $drivers_ids);
            $this->db->where_in('order_status_trans.status', array('box_delivered'));
        }
        $this->db->group_by('orders.id');
        $this->db->having('(SUM(quantity)  * 10) - (cash_collected + voucher_cash) > 0');
        
        $this->db->order_by('CONVERT(orders.order_number, DECIMAL)');
        
        $query = $this->db->get();
        
//        echo $this->db->last_query();exit;
        $result = $query->result_array();
        return $result;
    }
    
    public function getDeliveryPerformanceJkt($shipment_batch_ids)
    {
        $result = array();
        $this->db->select('orders.id AS order_id,shipment_batches.batch_name,shipment_batches.id AS shipment_id,locations.name AS location,locations.id AS location_id,
                    MIN(DATEDIFF(jkt_received_date, eta_jakarta )) AS dsdr_min,
                    MAX(DATEDIFF(jkt_received_date, eta_jakarta )) AS dsdr_max,
                    MIN(DATEDIFF(jkt_received_date, collection_date )) AS dcdr_min,
                    MAX(DATEDIFF(jkt_received_date, collection_date )) AS dcdr_max');
        $this->db->from('orders');
        $this->db->join('order_status_trans','order_status_trans.order_id = orders.id','left');
        $this->db->join('order_trans','order_trans.order_id = orders.id','left');
        $this->db->join('locations','locations.id = order_trans.location_id','left');
        $this->db->join('shipment_batches','shipment_batches.id = orders.shipment_batch_id','left');
        if(!empty($shipment_batch_ids))
        {
            $this->db->where_in('shipment_batch_id',$shipment_batch_ids);
        }
        $this->db->group_by('locations.id,shipment_batches.id');
         $this->db->order_by('shipment_batches.id');  
        $result = $this->db->get()->result_array();
        return $result;
        
        
    }
    
    public function getShipmentOrdersDataAtJakartaSide($shipment_batch_ids = null)
    {
        $this->db->select("shipment_batches.`batch_name`,orders.id, order_number, "
                . "weight,jkt_weight,jkt_received_date,jkt_receiver,jkt_reference_no, "
                . " customers.name as customer_name,"
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
        $this->db->group_by('orders.id');
        if(!empty($shipment_batch_ids))
        {
            $this->db->where_in('shipment_batch_id',$shipment_batch_ids);
        }
        $this->db->where('capture_weight','yes');
        $this->db->order_by('shipment_batch_id,order_number');
        $this->db->group_by('shipment_batches.id');
        $return = $this->db->get();
        
//        p($this->db->last_query());
        return $return->result_array();
    }
    
    public function getBoxCollectionDateReport($startDate, $endDate)
    {
        $result = array();
        $this->db->select('o.*,c.name as customer, c.mobile as contacts');
        $this->db->from('orders as o');
        $this->db->join("customers AS c" ,"o.customer_id = c.id", "left");
        $this->db->where('order_date >=', $startDate." 00:00:00"); 
        $this->db->where('order_date <=', $endDate." 23:59:00");
        $this->db->where('collection_date IS NULL');
        $return = $this->db->get();
//        p($this->db->last_query());
        return $return->result_array();
    }
}

?>