<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class OrdersModel extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getAllCodes()
    {

        $this->db->select('codes.id, code, location_id, locations.name as location, description');
        $this->db->from('codes');
        $this->db->join('locations', 'codes.location_id = locations.id');
        $this->db->order_by('code');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function getRedeliveryHistory($order_id)
    {
        $this->db->select('user.name as driver, order_redelivery_trans.*');
        $this->db->from('order_redelivery_trans');
        $this->db->join('user', 'order_redelivery_trans.employee_id = user.id');
        $this->db->where('order_redelivery_trans.order_id', $order_id);
        $this->db->order_by('order_redelivery_trans.id');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function getOrderRedelOrigBoxQty($order_id)
    {
        $this->db->select('order_redel_orig_qty.*, boxes.name as box');
        $this->db->from('order_redel_orig_qty');
        $this->db->join('boxes', 'order_redel_orig_qty.box_id = boxes.id');
        $this->db->where('order_redel_orig_qty.order_id', $order_id);
        $this->db->order_by('order_redel_orig_qty.id');
        $query = $this->db->get();
        $result = $query->result_array();
        
        return $result;
    }

    public function getAllOrdersDataTable($pagingParams = array())
    {
        $this->db->select("orders.id, order_date, delivery_date, collection_date, order_number, orders.status as order_status, "
                . "orders.building, orders.street, orders.unit, orders.block, orders.pin, kiv_status, printed_instruments, "
                . "orders.nett_total, orders.grand_total, orders.discount,, customers.passport_id_number ,customers.passport_img, "
                . " customers.name as customer_name, mobile, residence_phone, shipment_batches.batch_name as batch, shipment_batches.ship_onboard,shipment_batches.eta_jakarta,"
                . "order_status_trans.status, if(order_image_trans.status IS NULL, '', order_image_trans.status) as jakarta_image_status, "
                ."(SELECT cash_collected FROM order_status_trans ost WHERE ost.order_id = orders.id and ost.status = 'box_delivered') AS deposit_collected,"
                ."(SELECT SUM(cash_collected) FROM order_status_trans ost WHERE ost.order_id = orders.id) AS cash_collected,"
                ."(SELECT SUM(voucher_cash) FROM order_status_trans ost WHERE ost.order_id = orders.id) AS voucher_cash,"
                . "GROUP_CONCAT(boxes.`name` ORDER BY order_trans.id SEPARATOR '@@##@@' ) AS boxes,"
                . "GROUP_CONCAT(locations.`name` ORDER BY order_trans.id SEPARATOR '@@##@@' ) AS locations,"
                . "GROUP_CONCAT(kabupatens.`name` ORDER BY order_trans.id SEPARATOR '@@##@@' ) AS kabupatens,"
                . "GROUP_CONCAT(order_trans.`quantity` ORDER BY order_trans.id SEPARATOR '@@##@@' ) AS quantities,"
                . "(select concat(group_concat(status order by ost.id), '@@##@@', group_concat(u.name order by ost.id))"
                . " from order_status_trans ost join user u on (u.id = ost.employee_id) where ost.order_id = orders.id) as statuses"
                . "", FALSE);
        $this->db->from('orders');
        $this->db->join('customers', 'customers.id = orders.customer_id');
        $this->db->join("order_status_trans", "orders.id = order_status_trans.order_id and order_status_trans.active = 'yes'");
        $this->db->join("shipment_batches", "shipment_batches.id = orders.shipment_batch_id", "left");
        $this->db->join("order_trans", "orders.id = order_trans.order_id", "left");
        $this->db->join("boxes", "boxes.id = order_trans.box_id", "left");
        $this->db->join("locations", "locations.id = order_trans.location_id", "left");
        $this->db->join("kabupatens", "kabupatens.id = order_trans.kabupaten_id", "left");
        $this->db->join("order_image_trans", "orders.id = order_image_trans.order_id", "left");

        $this->db->group_by('orders.id');


        $search = empty($pagingParams['search_params']) ? null : $pagingParams['search_params'];
        if (!empty($search))
        {
            foreach ($search as $search_param => $search_val)
            {
                switch ($search_param)
                {
                    case 'jkt_image_status':
                        $this->db->where('order_image_trans.status', $search_val);
                        break;
                    
                    case 'customer_id':
                        $this->db->where('customers.id', $search_val);
                        break;

                    case 'order_number':
                        $this->db->like('order_number', $search_val);
                        break;

                    case 'status':
                        $this->db->where('order_status_trans.status', $search_val);
                        break;

                    case 'shipment_batch_id':
                        $this->db->where('orders.shipment_batch_id', $search_val);
                        break;

                    case 'response_status_code':
                        $this->db->where('payment_ad_cash_requests_billed.response_status_code', $search_val);
                        break;

                    case 'phone':
                        $this->db->where("(customers.mobile like '%$search_val%' or customers.residence_phone like '%$search_val%')");
                        break;

                    case 'delivery_date':
                        list($day, $month, $year) = explode('/', $search_val);
                        $search_val = "$year-$month-$day";

                        $this->db->where("date(delivery_date)", $search_val);
                        break;

                    case 'picture_receive_date':
                        list($day, $month, $year) = explode('/', $search_val);
                        $search_val = "$year-$month-$day";

                        $this->db->where("picture_receive_date", $search_val);
                        break;

                    case 'collection_date':
                        list($day, $month, $year) = explode('/', $search_val);
                        $search_val = "$year-$month-$day";

                        $this->db->where("date(collection_date)", $search_val);
                        break;
                    
                    case 'order_date_to':
                        list($day, $month, $year) = explode('/', $search_val);
                        $search_val = "$year-$month-$day";
                        $this->db->where('date(order_date) <=', $search_val);                        
                        break;
                    
                     case 'order_date_from':
                        list($day, $month, $year) = explode('/', $search_val);
                        $search_val = "$year-$month-$day";
                        $this->db->where('date(order_date) >=', $search_val);                        
                        break;
                     case 'driver_ids':
                         if(array_filter($search_val))
                            $this->db->where_in('order_status_trans.employee_id', $search_val);                      
                        break;
                }
            }
        }

        
        if (array_key_exists('iSortCol_0', $pagingParams))
        {
            if (empty($pagingParams['sSortDir_0']))
            {
                $pagingParams['sSortDir_0'] = 'desc';
            }
            $column_arr = array('orders.order_number', 'dates', 'customers.name');
            
            switch ($pagingParams['sSortDir_0'])
            {
                default:
                    $this->db->order_by($column_arr[$pagingParams['iSortCol_0']], $pagingParams['sSortDir_0']);
                    break;
            }
        }
        else
        {
            $this->db->order_by('orders.id');
        }

        if (isset($pagingParams['iDisplayStart']))
        {
            if ($pagingParams['iDisplayLength'] != '-1')
            {
                $start = $pagingParams['iDisplayStart'];
                $offset = $pagingParams['iDisplayLength'];
            }
            else
            {
                $offset = $start = null;
            }

            $return = $this->getWithCount(null, $offset, $start);
//            p($this->db->last_query());
            return $return;
        }
    }

    public function getAllBatchPrintDataTable($pagingParams = array())
    {
        $this->db->select("orders.id, order_date, delivery_date, collection_date, order_number, orders.status as order_status, "
                . "orders.building, orders.street, orders.unit, orders.block, orders.pin, kiv_status, printed_instruments, "
                . "orders.nett_total, orders.grand_total, orders.discount,, customers.passport_id_number ,customers.passport_img, "
                . " customers.name as customer_name, mobile, residence_phone, shipment_batches.batch_name as batch, shipment_batches.ship_onboard,shipment_batches.eta_jakarta,"
                . "order_status_trans.status, if(order_image_trans.status IS NULL, '', order_image_trans.status) as jakarta_image_status, "
                ."(SELECT cash_collected FROM order_status_trans ost WHERE ost.order_id = orders.id and ost.status = 'box_delivered') AS deposit_collected,"
                ."(SELECT SUM(cash_collected) FROM order_status_trans ost WHERE ost.order_id = orders.id) AS cash_collected,"
                ."(SELECT SUM(voucher_cash) FROM order_status_trans ost WHERE ost.order_id = orders.id) AS voucher_cash,"
                . "GROUP_CONCAT(boxes.`name` ORDER BY order_trans.id SEPARATOR '@@##@@' ) AS boxes,"
                . "GROUP_CONCAT(locations.`name` ORDER BY order_trans.id SEPARATOR '@@##@@' ) AS locations,"
                . "GROUP_CONCAT(kabupatens.`name` ORDER BY order_trans.id SEPARATOR '@@##@@' ) AS kabupatens,"
                . "GROUP_CONCAT(order_trans.`quantity` ORDER BY order_trans.id SEPARATOR '@@##@@' ) AS quantities,"
                . "(select concat(group_concat(status order by ost.id), '@@##@@', group_concat(u.name order by ost.id))"
                . " from order_status_trans ost join user u on (u.id = ost.employee_id) where ost.order_id = orders.id) as statuses"
                . "", FALSE);
        $this->db->from('orders');
        $this->db->join('customers', 'customers.id = orders.customer_id');
        $this->db->join("order_status_trans", "orders.id = order_status_trans.order_id");
        $this->db->join("shipment_batches", "shipment_batches.id = orders.shipment_batch_id", "left");
        $this->db->join("order_trans", "orders.id = order_trans.order_id", "left");
        $this->db->join("boxes", "boxes.id = order_trans.box_id", "left");
        $this->db->join("locations", "locations.id = order_trans.location_id", "left");
        $this->db->join("kabupatens", "kabupatens.id = order_trans.kabupaten_id", "left");
        $this->db->join("order_image_trans", "orders.id = order_image_trans.order_id", "left");

        $this->db->group_by('orders.id');


        $search = empty($pagingParams['search_params']) ? null : $pagingParams['search_params'];
        if (!empty($search))
        {
            foreach ($search as $search_param => $search_val)
            {
                switch ($search_param)
                {
                    case 'jkt_image_status':
                        $this->db->where('order_image_trans.status', $search_val);
                        break;
                    
                    case 'customer_id':
                        $this->db->where('customers.id', $search_val);
                        break;

                    case 'order_number':
                        $this->db->like('order_number', $search_val);
                        break;

                    case 'status':
                        $this->db->where('order_status_trans.status', $search_val);
                        break;

                    case 'shipment_batch_id':
                        $this->db->where('orders.shipment_batch_id', $search_val);
                        break;

                    case 'response_status_code':
                        $this->db->where('payment_ad_cash_requests_billed.response_status_code', $search_val);
                        break;

                    case 'phone':
                        $this->db->where("(customers.mobile like '%$search_val%' or customers.residence_phone like '%$search_val%')");
                        break;

                    case 'delivery_date':
                        list($day, $month, $year) = explode('/', $search_val);
                        $search_val = "$year-$month-$day";

                        $this->db->where("date(delivery_date)", $search_val);
                        break;

                    case 'picture_receive_date':
                        list($day, $month, $year) = explode('/', $search_val);
                        $search_val = "$year-$month-$day";

                        $this->db->where("picture_receive_date", $search_val);
                        break;

                    case 'collection_date':
                        list($day, $month, $year) = explode('/', $search_val);
                        $search_val = "$year-$month-$day";

                        $this->db->where("date(collection_date)", $search_val);
                        break;
                    
                    case 'order_date_to':
                        list($day, $month, $year) = explode('/', $search_val);
                        $search_val = "$year-$month-$day";
                        $this->db->where('date(order_date) <=', $search_val);                        
                        break;
                    
                     case 'order_date_from':
                        list($day, $month, $year) = explode('/', $search_val);
                        $search_val = "$year-$month-$day";
                        $this->db->where('date(order_date) >=', $search_val);                        
                        break;
                     case 'driver_ids':
                         if(array_filter($search_val))
                            $this->db->where_in('order_status_trans.employee_id', $search_val);                      
                        break;
                }
            }
        }

        
        if (array_key_exists('iSortCol_0', $pagingParams))
        {
            if (empty($pagingParams['sSortDir_0']))
            {
                $pagingParams['sSortDir_0'] = 'desc';
            }
            $column_arr = array('orders.order_number', 'dates', 'customers.name');
            
            switch ($pagingParams['sSortDir_0'])
            {
                default:
                    $this->db->order_by($column_arr[$pagingParams['iSortCol_0']], $pagingParams['sSortDir_0']);
                    break;
            }
        }
        else
        {
            $this->db->order_by('orders.id');
        }

        if (isset($pagingParams['iDisplayStart']))
        {
            if ($pagingParams['iDisplayLength'] != '-1')
            {
                $start = $pagingParams['iDisplayStart'];
                $offset = $pagingParams['iDisplayLength'];
            }
            else
            {
                $offset = $start = null;
            }

            $return = $this->getWithCount(null, $offset, $start);
//            p($this->db->last_query());
            return $return;
        }
    }

    public function getAllCustomers($pagingParams = array())
    {
        $this->db->select("customers.*, b.customer_id as blacklistCustomer, b.b_comment", FALSE);
        $this->db->from('customers');

        $search = empty($pagingParams['search_params']) ? null : $pagingParams['search_params'];
        $this->db->join('blacklist_customer as b','customers.id = b.customer_id', 'left');
//        if(isset($search['blacklistCustomer']))
//        {
//            $this->db->join('blacklist_customer as b','customers.id = b.customer_id', 'left');
//        }
        if (!empty($search))
        {            
            foreach ($search as $search_param => $search_val)
            {
                switch ($search_param)
                {
                    case 'name':
                        $this->db->like('customers.name', $search_val);
                        break;

                    case 'mobile':
                        $this->db->like('customers.mobile', $search_val);
                        break;

                    case 'residence_phone':
                        $this->db->like('customers.residence_phone', $search_val);
                        break;

                    case 'pin':
                        $this->db->like('customers.pin', $search_val);
                        break;

                    case 'search_address':
                        $this->db->where("(unit like '%$search_val' or block like '%$search_val' or building like '%$search_val' or street like '%$search_val')");
                        break;                    
                    
                    case 'blacklistCustomer':
                        $this->db->where('b.id is NOT NULL', NULL, FALSE);
                        break;
                }
            }
        }
        
//            p($pagingParams);
        if (!empty($pagingParams['iSortCol_0']))
        {
            if (empty($pagingParams['sSortDir_0']))
            {
                $pagingParams['sSortDir_0'] = 'desc';
            }
            $column_arr = array('name', 'mobile', 'residence_phone',  'pin', 'unit', 'block', 'building', 'street');
            switch ($pagingParams['sSortDir_0'])
            {
                default:
                    $this->db->order_by($column_arr[$pagingParams['iSortCol_0']], $pagingParams['sSortDir_0']);
                    break;
            }
        }
        else
        {
            $this->db->order_by('name', $pagingParams['sSortDir_0']);
        }
        
        if (isset($pagingParams['iDisplayStart']))
        {
            if ($pagingParams['iDisplayLength'] != '-1')
            {
                $start = $pagingParams['iDisplayStart'];
                $offset = $pagingParams['iDisplayLength'];
            }
            else
            {
                $offset = $start = null;
            }

            $return = $this->getWithCount(null, $offset, $start);
//            p($this->db->last_query());
            return $return;
        }

    }

    public function getAllOrders($filters = array(), $order_ids = array())
    {

        $this->db->select("orders.*,customers.name, customers.building, customers.block, customers.unit, customers.street, customers.pin, GROUP_CONCAT(CONCAT_WS(' ',locations.name,kabupatens.name ) SEPARATOR '@#@#' ) as destination_kabupaten");
        $this->db->from('orders');
        $this->db->join('customers','customers.id = orders.customer_id','left');
        $this->db->join('order_trans','order_trans.order_id = orders.id','left');
        $this->db->join('kabupatens','kabupatens.id = order_trans.kabupaten_id','left');
        $this->db->join('locations','locations.id = order_trans.location_id','left');
        $this->db->order_by('orders.id');
        $this->db->group_by('orders.id');
        if (!empty($order_ids))
        {
            $this->db->where_in('orders.id', $order_ids);
        }
        
        if (!empty($filters))
        {
            foreach ($filters as $column => $value)
            {
                $this->db->where($column, $value);
            }
        }

        $query = $this->db->get();  

        $result = $query->result_array();
        return $result;
    }

    
    public function searchCustomers($search_query)
    {
        $this->db->select('customers.*, count(orders.id) as repeated_customer,'
                . ' GROUP_CONCAT(distinct customer_type_mapping.customer_type_id SEPARATOR "@#@#" ) as customer_type_id,'
                . ' GROUP_CONCAT(distinct customer_media_type_mapping.media_type_id SEPARATOR "@#@#" ) as media_type_id');
        $this->db->from('customers');
        $this->db->join('orders', 'orders.customer_id = customers.id', 'left');
        $this->db->join('customer_type_mapping', 'customer_type_mapping.customer_id = customers.id', 'left');
        $this->db->join('customer_media_type_mapping', 'customer_media_type_mapping.customer_id = customers.id', 'left');
        $this->db->group_by('customers.id');

        if (!empty($search_query))
        {
            foreach ($search_query as $index => $row)
            {
                $this->db->or_like("$index", "$row");
            }
        }
        $this->db->order_by('name');
        $query = $this->db->get();

//        /p($this->db->last_query(),0);

        $result = $query->result_array();
        return $result;
    }

    public function searchStreet($search_query)
    {
        $this->db->select('distinct(street) as street_name');
        $this->db->from('postalcodes');

        if (!empty($search_query))
        {
            foreach ($search_query as $index => $row)
            {
                $this->db->or_like("$index", "$row");
            }
        }
        $this->db->order_by('street');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function getOrderHistory($customer_id, $pagingParams = array())
    {
        $this->db->select("orders.*, GROUP_CONCAT(user.name ORDER BY order_status_trans.id SEPARATOR '@@##@@' ) AS users,
                        GROUP_CONCAT(order_status_trans.`status` ORDER BY order_status_trans.id SEPARATOR '@@##@@' ) AS `employee_order_status`,
                        GROUP_CONCAT(order_status_trans.`comments` ORDER BY order_status_trans.id SEPARATOR '@@##@@' ) AS comments,
                        GROUP_CONCAT(order_status_trans.`cash_collected` ORDER BY order_status_trans.id SEPARATOR '@@##@@' ) AS cash_collected,
                        GROUP_CONCAT(order_status_trans.`voucher_cash` ORDER BY order_status_trans.id SEPARATOR '@@##@@' ) AS voucher_cash,
                        GROUP_CONCAT(order_status_trans.`created_at` ORDER BY order_status_trans.id SEPARATOR '@@##@@' ) AS status_update_time,
                        GROUP_CONCAT(order_status_trans.`reassigned_stage` ORDER BY order_status_trans.id SEPARATOR '@@##@@' ) AS reassigned_stage,
                        GROUP_CONCAT(IF(`ru`.`name` IS NULL, '', `ru`.`name`) ORDER BY order_status_trans.id SEPARATOR '@@##@@' )  AS reassigned_from,
                        cu.name as cancelled_by", false);
        $this->db->from('orders');
        $this->db->join('order_status_trans', 'order_status_trans.order_id = orders.id');
        $this->db->join('user', 'user.id = order_status_trans.employee_id');
        $this->db->join('user as cu', 'cu.id = orders.status_updated_by', 'left');
        $this->db->join('user as ru', 'ru.id = order_status_trans.old_employee_id', 'left');
        $this->db->where('customer_id', $customer_id);
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
            $this->db->order_by('orders.id desc');
        }
        if (isset($pagingParams['iDisplayStart']) && $pagingParams['iDisplayLength'] != '-1')
        {
            $start = $pagingParams['iDisplayStart'];
            $offset = $pagingParams['iDisplayLength'];
            $this->db->limit($offset, $start);
        }

        $query = $this->db->get();
//        p($this->db->last_query());
        $result = $query->result_array();
        return $result;
    }
    
    public function getAgentCommissionDataTotal($params = array())
    {
        $this->db->select("(sum(quantity) * commission) as total_commission", false);
        $this->db->from('orders');
        $this->db->join("order_trans", "orders.id = order_trans.order_id");
        $this->db->join("order_status_trans", "orders.id = order_status_trans.order_id");
        $this->db->join('agents', 'orders.agent_id = agents.id');
        $this->db->where("order_status_trans.active", 'yes');
        $this->db->where('order_trans.box_id', GIANT_BOX_ID);
        $this->db->where("order_status_trans.status in ('collected_at_warehouse', 'ready_for_receiving_at_jakarta', 'received_at_jakarta_warehouse' , 'delivered_at_jkt_picture_not_taken', 'delivered_at_jkt_picture_taken')");

        $this->db->group_by('agents.id');
        
        $params = $params['search_params'];
        
        foreach ($params as $search_param => $search_val)
        {
            switch ($search_param)
            {
                case 'agent_id':
                    $this->db->where('agent_id', $search_val);
                    break;

                case 'collection_date_to':
                    list($month, $day, $year) = explode('/', $search_val);
                    $search_val = "$year-$month-$day";
                    $this->db->where('date(collection_date) <=', $search_val);                        
                    break;

                 case 'collection_date_from':
                    list($month, $day, $year) = explode('/', $search_val);
                    $search_val = "$year-$month-$day";
                    $this->db->where('date(collection_date) >=', $search_val);                        
                    break;
            }
        }
        
        $query = $this->db->get()->row();
        $count = empty($query->total_commission) ? 0.0 : $query->total_commission;
//        p($this->db->last_query());
        return $count;
    }

    public function getAgentCommissionData($pagingParams = array())
    {
        $this->db->select("orders.order_number, date_format(date(order_date), '%d/%m/%Y') as order_date,date_format(date(collection_date), '%d/%m/%Y') as collection_date, orders.id, sum(quantity) as total_boxes,"
                . "orders.grand_total, orders.discount, orders.nett_total, (sum(quantity) * commission) as total_commission", false);
        $this->db->from('orders');
        $this->db->join("order_trans", "orders.id = order_trans.order_id");
        $this->db->join("order_status_trans", "orders.id = order_status_trans.order_id");
        $this->db->join('agents', 'orders.agent_id = agents.id');
        $this->db->where("order_status_trans.status in ('collected_at_warehouse', 'ready_for_receiving_at_jakarta', 'received_at_jakarta_warehouse', 'delivered_at_jkt_picture_not_taken', 'delivered_at_jkt_picture_taken')");
        $this->db->where("order_status_trans.active", 'yes');
        $this->db->where('order_trans.box_id', GIANT_BOX_ID);
        $this->db->group_by('orders.id');

        $search = empty($pagingParams['search_params']) ? null : $pagingParams['search_params'];
        if (!empty($search))
        {
            foreach ($search as $search_param => $search_val)
            {
                switch ($search_param)
                {
                    case 'agent_id':
                        $this->db->where('agent_id', $search_val);
                        break;
                    
                    case 'collection_date_to':
                        list($month, $day, $year) = explode('/', $search_val);
                        $search_val = "$year-$month-$day";
                        $this->db->where('date(collection_date) <=', $search_val);                        
                        break;
                    
                     case 'collection_date_from':
                        list($month, $day, $year) = explode('/', $search_val);
                        $search_val = "$year-$month-$day";
                        $this->db->where('date(collection_date) >=', $search_val);                        
                        break;
                }
            }
        }
        
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
            $this->db->order_by('orders.id desc');
        }
        
        if (isset($pagingParams['iDisplayStart']))
        {
            if ($pagingParams['iDisplayLength'] != '-1')
            {
                $start = $pagingParams['iDisplayStart'];
                $offset = $pagingParams['iDisplayLength'];
            }
            else
            {
                $offset = $start = null;
            }

            $return = $this->getWithCount(null, $offset, $start);
//            p($this->db->last_query());
            return $return;
        }
    }
    
    public function getCustomerLoyaltyReportData($year, $months, $order_count_min,$order_count_max)
    {
        if(empty($year) || empty($months) || empty($order_count_min) || empty($order_count_max))
            return FALSE;
        
        $this->db->select("count(orders.order_number) as orders_count, date_format(date(order_date), '%d/%m/%Y') as order_date,date_format(date(order_date), '%Y') as order_date_year,"
                . "customers.name, customers.mobile, customers.street, customers.block,customers.unit,orders.customer_id", false);
        $this->db->from('orders');
        $this->db->join('customers', 'customers.id = orders.customer_id');    
        $this->db->where('year(orders.order_date)',$year);
        $this->db->having('count(orders.order_number) >=',$order_count_min);
        $this->db->having('count(orders.order_number) <=',$order_count_max);
        $this->db->where_in('month(orders.order_date)',$months);    
        $this->db->group_by('orders.customer_id');

        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }
    
    public function getStartYearOrders()
    {        
        $this->db->select("date_format(date(order_date), '%Y') as order_date_year", false);
        $this->db->from('orders');    
        $this->db->group_by('order_date');

        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }
    
    public function getCustomerLoyaltyOrderData($pagingParams)
    {
        if(empty($pagingParams))
            return FALSE;
        
        $year = $pagingParams["year"];
        $months = explode(",", $pagingParams["months"][0]);
        $customer_id = $pagingParams["search_customer_id"];
        
        $this->db->select("order_trans.box_id,order_trans.quantity,orders.order_number, date_format(date(collection_date), '%d/%m/%Y') as collection_date", false);
        $this->db->from('orders');
        $this->db->join("order_trans", "orders.id = order_trans.order_id");
        $this->db->join("order_status_trans", "orders.id = order_status_trans.order_id");
        $this->db->join('customers', 'customers.id = orders.customer_id');    
        $this->db->where_in('month(orders.order_date)',$months);  
        $this->db->where('year(orders.order_date)',$year);  
        $this->db->where('orders.customer_id',$customer_id);    
        $this->db->group_by('orders.id');

        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function getOrderHistoryCount($customer_id)
    {
        $count = '0';
        $this->db->select('count("id") as count');
        $this->db->where('customer_id', $customer_id);
        $query = $this->db->get('orders')->row();
        $count = $query->count;
        return $count;
    }

    public function getCodeDetails($code_id)
    {
        $this->db->select('quantity, box_id, boxes.name as box_name, price, codes.location_id, code');
        $this->db->from('code_box_location_qty_mapping');
        $this->db->join('codes', 'code_box_location_qty_mapping.code_id = codes.id');
        $this->db->join('boxes', 'boxes.id = code_box_location_qty_mapping.box_id');
        $this->db->where('code_id', $code_id);

        $this->db->order_by('code_box_location_qty_mapping.id');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function getOrderDetails($order_id)
    {
        $this->db->_protect_identifiers = false;

        $this->db->select("orders.*, customers.name as customer_name,customers.mobile as customer_mobile, customers.residence_phone"
                . " as customer_phone, customers.pin as customer_pin, customers.email as customer_email,"
                . "customers.lattitude as customer_lattitude, customers.longitude as customer_longitude,"
                . "customers.unit as customer_unit, customers.block as customer_block, customers.street as customer_street,"
                . "customers.building as customer_building, customers.email, customers.passport_id_number ,customers.passport_img,"
                . "agents.can_update_total, SUM(cash_collected) as cash_collected,"
                . "(SELECT cash_collected FROM order_status_trans ost WHERE ost.order_id = orders.id and ost.status = 'box_delivered') AS deposit_collected, "
                . " (SELECT COUNT(*) FROM orders o WHERE o.customer_id = orders.customer_id and o.id <> '$order_id') AS repeated_customer ,"
                . "DATEDIFF(jkt_received_date, eta_jakarta) AS dsdr,
                    DATEDIFF(jkt_received_date,collection_date ) AS dcdr");
        $this->db->from('orders');
        $this->db->join('order_status_trans', 'order_status_trans.order_id = orders.id', 'left');       
        $this->db->join('customers', 'customers.id = orders.customer_id');
        $this->db->join('agents', 'agents.id = orders.agent_id', 'left');
        $this->db->join('shipment_batches', 'shipment_batches.id = orders.shipment_batch_id', 'left');

        $this->db->where('orders.id', $order_id);
        
        $this->db->group_by('orders.id');

        $query = $this->db->get();
        $result = $query->row_array();
//        p($this->db->last_query());
        return $result;
    }

    public function getOrderStatusesByDate($where)
    {

        $this->db->select('order_status_trans.status, order_status_trans.order_id,SUM(order_trans.quantity) AS total_boxes');
        
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
        
        
        $this->db->from('order_status_trans');
        $this->db->join('orders', 'orders.id = order_status_trans.order_id');
        $this->db->join('order_trans', 'orders.id = order_trans.order_id');
        $this->db->where("order_status_trans.active", 'yes');
         $this->db->group_by('order_status_trans.order_id');
        $this->db->order_by('order_status_trans.order_id asc, order_status_trans.id desc');

        $query = $this->db->get();
        $result = $query->result_array();
//        echo $this->db->last_query();exit;
        return $result;
    }

    public function getCustomerDetails($customer_id)
    {
        $this->db->select('customers.*');
        $this->db->from('customers');
        $this->db->where('id', $customer_id);

        $query = $this->db->get();
        $result = $query->row_array();
        return $result;
    }

    public function getBatchOrderDetails($delivery_date=null, $order_ids=array())
    {
        $this->db->select('orders.*, '
                . "GROUP_CONCAT(order_status_trans.`status` ORDER BY order_status_trans.id SEPARATOR '@@##@@' ) AS statuses,"
                . "GROUP_CONCAT(order_status_trans.`cash_collected` ORDER BY order_status_trans.id SEPARATOR '@@##@@' ) AS cash_collections,"
                . "GROUP_CONCAT(order_status_trans.`voucher_cash` ORDER BY order_status_trans.id SEPARATOR '@@##@@' ) AS voucher_cash,"
                . 'customers.name as customer_name, customers.passport_id_number,customers.passport_img, customers.mobile, customers.residence_phone');
        $this->db->from('orders');
        $this->db->join('customers', 'customers.id = orders.customer_id');
        $this->db->join('order_status_trans', 'orders.id = order_status_trans.order_id');
        $this->db->group_by('orders.id');

        if (!empty($delivery_date))
        {
            $this->db->where('date(delivery_date)', $delivery_date);
        }

        if (!empty($order_ids))
        {
            $this->db->where_in('orders.id', $order_ids);
        }

        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function getOrderStatusDetails($order_id)
    {
        $this->db->select('order_status_trans.*, user.name as employee_name');
        $this->db->from('order_status_trans');
        $this->db->join('user', 'user.id = order_status_trans.employee_id', 'left');
        $this->db->where('order_id', $order_id);
        $this->db->order_by('id desc');
        $this->db->limit(1);

        $query = $this->db->get();
        $result = $query->row_array();
        return $result;
    }

    public function removeOrderStatusesForOrderCancellation($where)
    {
        $this->db->where('id', $where['id']);
        $this->db->delete('order_status_trans');
    }

    public function deleteOrderImageTrans($where)
    {
        $this->db->where($where);
        $this->db->delete('order_image_trans');
    }

    public function deleteRedeliveryHistory($where)
    {
        $this->db->where($where);
        $this->db->delete('order_redelivery_trans');
    }

    public function getOrderStatusDetailsByCondition($where, $check_responsibility_completed=1)
    {
        $this->db->from('order_status_trans');
        $this->db->join('orders', 'orders.id = order_status_trans.order_id and '
                . '             (orders.status = "active" or orders.status is null or orders.status = "") and '
                . '             (orders.kiv_status = "no")');
        
        if (!empty($check_responsibility_completed))
        {
            $this->db->where("(order_status_trans.responsibility_completed = 'no' or order_status_trans.responsibility_completed  is null)");
        }
        
        $this->db->where($where);
        $this->db->order_by('order_status_trans.id desc');

        $query = $this->db->get();

        $result = $query->result_array();
        return $result;
    }

    public function getOrderOutstanding($order_id)
    {
        $this->db->select('grand_total, discount, nett_total, sum(cash_collected) as cash_collected, sum(voucher_cash) as voucher_cash', false);
        $this->db->from('orders');
        $this->db->join('order_status_trans', 'order_status_trans.order_id = orders.id');
        $this->db->where('orders.id', $order_id);
        $this->db->group_by('orders.id');


        $query = $this->db->get();
        $result = $query->row_array();
        return $result;
    }

    public function getOrderTransDetails($order_id)
    {
        $this->db->select('box_id, boxes.name as box, order_trans.location_id, locations.name as location, locations.capture_weight,'
                . 'kabupaten_id, kabupatens.name as kabupaten, quantity, price_per_unit, total_price,order_trans.promocode_id');
        $this->db->from('order_trans');
        $this->db->join('locations', 'locations.id = order_trans.location_id');
        $this->db->join('boxes', 'boxes.id = order_trans.box_id');
        $this->db->join('kabupatens', 'kabupatens.id = order_trans.kabupaten_id', 'left');

        $this->db->where('order_trans.order_id', $order_id);

        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function getOrderCodeTransDetails($order_id)
    {
        $this->db->select('code_id, codes.code, order_code_trans.location_id, locations.name as location,'
                . 'kabupaten_id, kabupatens.name as kabupaten');
        $this->db->from('order_code_trans');
        $this->db->join('locations', 'locations.id = order_code_trans.location_id');
        $this->db->join('kabupatens', 'kabupatens.id = order_code_trans.kabupaten_id', 'left');
        $this->db->join('codes', 'codes.id = order_code_trans.code_id');

        $this->db->where('order_code_trans.order_id', $order_id);

        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function getPriceByLocationBox($location_id, $box_id)
    {
        $this->db->select('price');
        $this->db->from('box_location_prices');
        $this->db->where('location_id', $location_id);
        $this->db->where('box_id', $box_id);

        $query = $this->db->get()->row();

        return $query;
    }

    public function getAddressByPinCode($pincode)
    {
        $this->db->where('postalcode', $pincode);

        $query = $this->db->get('postalcodes')->row();

        return $query;
    }

    public function getPinCodeByAddress($where)
    {
        $this->db->where($where);

        $query = $this->db->get('postalcodes')->row();

        return $query;
    }

    public function getKabupatenByLocation($location_id, $name=null)
    {
        $this->db->select('id, name');
        $this->db->from('kabupatens');
        $this->db->where('location_id', $location_id);
        
        if (!empty($name))
        {
            $this->db->like('name', $name);
        }
        
        $this->db->order_by('name');

        $query = $this->db->get()->result_array();

        return $query;
    }

    public function orderStatusHistory($order_id)
    {
        $this->db->select('*');
        $this->db->from('order_status_trans');
        $this->db->where('order_id', $order_id);
        $this->db->order_by('id desc');

        $query = $this->db->get()->result_array();

        return $query;
    }

    public function getFollowUpCallHistory($order_id)
    {
        $this->db->select('order_followup.*, user.username as name');
        $this->db->from('order_followup');
        $this->db->join('user', 'user.id = order_followup.employee_id');
        $this->db->where('order_id', $order_id);
        $this->db->order_by('id desc');


        $query = $this->db->get()->result_array();

        //p($this->db->last_query());
        return $query;
    }

    public function saveCustomer($dataValues)
    {
        $return = null;
        if (count($dataValues) > 0)
        {
            if (array_key_exists('id', $dataValues))
            {
                $this->db->where('id', $dataValues['id']);
                $this->db->update('customers', $dataValues);

                $return = $dataValues['id'];
            }
            else
            {
                $this->db->insert('customers', $dataValues);
                $return = $this->db->insert_id();
            }
        }
        return $return;
    }

    public function saveRedelOrigQuantity($dataValues)
    {
        $return = null;
        if (count($dataValues) > 0)
        {
            if (array_key_exists('id', $dataValues))
            {
                $this->db->where('id', $dataValues['id']);
                $this->db->update('order_redel_orig_qty', $dataValues);

                $return = $dataValues['id'];
            }
            else
            {
                $this->db->insert('order_redel_orig_qty', $dataValues);
                $return = $this->db->insert_id();
            }
        }
        return $return;
    }

    public function saveOrderStatus($dataValues, $where = array())
    {
        $return = null;
        
        if (count($dataValues) > 0)
        {
            // made changes here against protocols to pass data from controller/lib to log user agent and IP because of weird employee_id
            // logged in order_status_trans

            $dataValues['user_ip'] = empty($_SERVER['REMOTE_ADDR']) ? '' : $_SERVER['REMOTE_ADDR'];
            $dataValues['user_agent'] = empty($_SERVER['HTTP_USER_AGENT']) ? ''  : $_SERVER['HTTP_USER_AGENT'];

            if (empty($where))
            {
                if (array_key_exists('id', $dataValues))
                {
                    $this->db->where('id', $dataValues['id']);
                    $this->db->update('order_status_trans', $dataValues);

                    $return = $dataValues['id'];
                }
                else
                {
                    $this->db->insert('order_status_trans', $dataValues);
                    $return = $this->db->insert_id();
                }
            }
            else
            {
                $this->db->where($where);
                $this->db->update('order_status_trans', $dataValues);

                $return = $dataValues['order_id'];
            }
        }
        
        return $return;
    }

    public function saveEOD($dataValues)
    {
        $return = null;
        if (count($dataValues) > 0)
        {
            if (array_key_exists('id', $dataValues))
            {
                $this->db->where('id', $dataValues['id']);
                $this->db->update('eod', $dataValues);

                $return = $dataValues['id'];
            }
            else
            {
                $this->db->insert('eod', $dataValues);
                $return = $this->db->insert_id();
            }
        }
        return $return;
    }

    public function saveOrderDetails($dataValues)
    {
        $return = null;
        if (count($dataValues) > 0)
        {
            $this->db->insert('order_trans', $dataValues);
            $return = $this->db->insert_id();
        }
        return $return;
    }

    public function saveFailedScan($dataValues)
    {
        $return = null;
        if (count($dataValues) > 0)
        {
            $this->db->insert('failed_scans', $dataValues);
            $return = $this->db->insert_id();
        }
        return $return;
    }

    public function saveOrderCodeDetails($dataValues)
    {
        $return = null;
        if (count($dataValues) > 0)
        {
            $this->db->insert('order_code_trans', $dataValues);
            $return = $this->db->insert_id();
        }
        return $return;
    }

    public function getMaxOrderNumber($agent_id)
    {
        $this->db->select('max(raw_order_number) as raw_order_number');

        if (!empty($agent_id))
        {
            $this->db->where('agent_id', $agent_id);
        }

        $return = $this->db->get('orders')->row_array();
        
        if (!empty($return))
        {
            $return = $return['raw_order_number'] + 1;
        }
        else
        {
            $return = 1;
        }

        return $return;
    }

    public function saveOrder($dataValues, $where=array())
    {
        $return = null;
        if (count($dataValues) > 0)
        {
            if (empty($where))
            {
                if (array_key_exists('id', $dataValues))
                {
                    $this->db->where('id', $dataValues['id']);
                    $this->db->update('orders', $dataValues);

                    $return = $dataValues['id'];
                }
                else
                {
                    $this->db->insert('orders', $dataValues);
                    $return = $this->db->insert_id();
                }
            }
            else
            {
                $this->db->where($where);
                $this->db->update('orders', $dataValues);
            }
        }        
        $error_number = $this->db->_error_number();
        $return = empty($error_number) ? $return : 'error';
        
        return $return;
    }

    public function saveOrderKIVStatus($dataValues)
    {
        $return = null;
        if (count($dataValues) > 0)
        {
            if (array_key_exists('id', $dataValues))
            {
                $this->db->where('id', $dataValues['id']);
                $this->db->update('order_kiv_trans', $dataValues);

                $return = $dataValues['id'];
            }
            else
            {
                $this->db->insert('order_kiv_trans', $dataValues);
                $return = $this->db->insert_id();
            }
        }
        return $return;
    }

    public function getOrderStatusDataByOrder($order_id)
    {
        $this->db->select("order_status_trans.*, user.name as employee, user.id as user_id", FALSE);
        $this->db->from('orders');
        $this->db->join("order_status_trans", "orders.id = order_status_trans.order_id");
        $this->db->join('user', 'user.id = order_status_trans.employee_id', 'left');
        $this->db->order_by('order_status_trans.id');
        $this->db->where('orders.id', $order_id);

        $return = $this->getWithCount(null);
        //p($this->db->last_query());
        return $return;
    }

    public function deleteOrderStatus($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('order_status_trans');
    }

    public function deleteOrderDetails($where)
    {
        $this->db->where($where);
        $this->db->delete('order_trans');
    }

    public function deleteOrderCodeDetails($where)
    {
        $this->db->where($where);
        $this->db->delete('order_code_trans');
    }
    
    public function getOrderStatus($order_number)
    {
        $this->db->select('order_status_trans.id,order_status_trans.order_id,order_status_trans.status,orders.order_number,'
                . 'orders.picture_receive_date,order_status_trans.employee_id, order_status_trans.created_at');
        $this->db->from('orders');
         $this->db->join("order_status_trans", "orders.id = order_status_trans.order_id");
        $this->db->where('order_number', $order_number);
        $this->db->order_by('id desc');
        $this->db->limit(1);

        $query = $this->db->get();
        $result = $query->row_array();
        return $result;
    }
    
    public function getOrderStatusesByDateJkt($where, $jakarta_statuses=null)
    {

        $this->db->select('order_status_trans.status, order_status_trans.order_id,SUM(order_trans.quantity) AS total_boxes');
        
        $this->db->where("order_status_trans.active", 'yes');
        $this->db->from('order_status_trans');
        $this->db->join('orders', 'orders.id = order_status_trans.order_id');
        $this->db->join('order_trans', 'orders.id = order_trans.order_id');
        $this->db->join('shipment_batches', 'shipment_batches.id = orders.shipment_batch_id');
        $this->db->join('receiving_batches', 'receiving_batches.id = shipment_batches.receiving_batch_id');
        $this->db->group_by('order_status_trans.order_id');
        $this->db->order_by('order_status_trans.order_id asc, order_status_trans.id desc');
        
        if (!empty($jakarta_statuses))
        {
            $this->db->where_in('order_status_trans.status', $jakarta_statuses);
        }
        
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

    public function getOrderMaxDayRcvdATJktByShipmentBatchId($shipment_batch_id)
    {
        $this->db->_protect_identifiers = false;

        $this->db->select('orders.order_number, orders.id as order_id, DATEDIFF(CURDATE(), date(order_status_trans.created_at)) as max_days');
        $this->db->from('orders');
        $this->db->join('order_status_trans', 'orders.id = order_status_trans.order_id');
        $this->db->where('shipment_batch_id', $shipment_batch_id);
        $this->db->where('order_status_trans.status', 'received_at_jakarta_warehouse');
        $this->db->order_by('order_status_trans.created_at asc');
        $this->db->limit(1);

        $query = $this->db->get();
        
        $result = $query->row_array();
        return $result;
    }
    
    public function saveOrderImageMaster($dataValues)
    {
        $return = null;
        if (count($dataValues) > 0)
        {
            if (array_key_exists('id', $dataValues))
            {
                $this->db->where('id', $dataValues['id']);
                $this->db->update('order_image_master', $dataValues);

                $return = $dataValues['id'];
            }
            else
            {
                $this->db->insert('order_image_master', $dataValues);
                $return = $this->db->insert_id();
            }
        }
        return $return;
    }
    
    public function saveOrderImageTrans($dataValues)
    {
        $return = null;
        if (count($dataValues) > 0)
        {
            if (array_key_exists('id', $dataValues))
            {
                $this->db->where('id', $dataValues['id']);
                $this->db->update('order_image_trans', $dataValues);

                $return = $dataValues['id'];
            }
            else
            {
                $this->db->insert('order_image_trans', $dataValues);
                $return = $this->db->insert_id();
            }
        }
        return $return;
    }
    
    public function saveOrderRedelivery($dataValues)
    {
        $return = null;
        if (count($dataValues) > 0)
        {
            if (array_key_exists('id', $dataValues))
            {
                $this->db->where('id', $dataValues['id']);
                $this->db->update('order_redelivery_trans', $dataValues);

                $return = $dataValues['id'];
            }
            else
            {
                $this->db->insert('order_redelivery_trans', $dataValues);
                $return = $this->db->insert_id();
            }
        }
        return $return;
    }
    
    public function getOrderImageMaster($master_id)
    {
        $this->db->where('id', $master_id);

        $query = $this->db->get('order_image_master');
        $result = $query->row_array();
        return $result;
    }
    
    public function searchOrderByParams($params = array())
    {
        $result = array();
        
        if (!empty($params))
        {
            $this->db->where($params);

            $query = $this->db->get('orders');
            $result = $query->row_array();
        }
        
        return $result;
    }
    
    
    public function getAllOrderImageTrans($filters = array(), $single_row = false)
    {

        $this->db->select('*');
        $this->db->from('order_image_trans');
        
        foreach ($filters as $column => $value)
        {
            $this->db->where($column, $value);
        }

        $query = $this->db->get();
        
        if (empty($single_row))
        {
            $result = $query->result_array();
        }
        else
        {
            $result = $query->row_array();
        }
        
        return $result;
    }
    
    
    public function getImageForReceivedAtJakarta($order_id)
    {
        $this->db->select('*');
        $this->db->from('order_image_trans');
        $this->db->where('order_id', $order_id);
        
        $result = $this->db->get()->result_array();
        return $result;
    }
    
    
    public function saveOrderImageTransUserDownloadedAt($dataValues)
    {
        $return = null;
        if (count($dataValues) > 0)
        {
            if (array_key_exists('id', $dataValues))
            {
                $this->db->where('id', $dataValues['id']);
                $this->db->update('order_image_trans', $dataValues);

                $return = $dataValues['id'];
            }
            else
            {
                $this->db->insert('order_image_trans', $dataValues);
                $return = $this->db->insert_id();
            }
        }
        return $return;
    }
    
        public function getOrderStatusDataByOrderForPublic($order_id)
    {
        $this->db->select("order_status_trans.*, user.name as employee, user.id as user_id, orders.jkt_received_date, jkt_reference_no, jkt_receiver, orders.shipment_batch_id", FALSE);
        $this->db->from('orders');
        $this->db->join("order_status_trans", "orders.id = order_status_trans.order_id");
        $this->db->join('user', 'user.id = order_status_trans.employee_id', 'left');
//        $this->db->join('shipment_batches', 'orders.shipment_batch_id = shipment_batches.id','left');
        $this->db->order_by('order_status_trans.id');
        $this->db->where('orders.id', $order_id);

        $return = $this->getWithCount(null);
        
        return $return;
    }
    
    
    public function getOrderId($order_no)
    {
        $this->db->select('id');
        $this->db->from('orders');
        $this->db->where('order_number', $order_no);
        $result =  $this->db->get()->row();
        
        return $result->id;
    }

    public function getDriverWiseRedelHistoryByDateRange($driver_id, $from_date, $to_date, $fetch_only_initial_delivery=false)
    {
        $this->db->select('order_redelivery_trans.*, sum(order_trans.quantity) as quantity, '
                . 'group_concat(boxes.name ORDER BY boxes.id SEPARATOR "<br>") as boxes,'
                . 'group_concat(boxes.id ORDER BY boxes.id SEPARATOR "<br>") as box_ids,'
                . 'group_concat(boxes.delivery_commission ORDER BY boxes.id SEPARATOR "<br>") as delivery_commissions,
                   group_concat(boxes.collection_commission ORDER BY boxes.id SEPARATOR "<br>") as collection_commissions,'
                . 'group_concat(order_trans.quantity ORDER BY boxes.id SEPARATOR "<br>") as quantities,'
                . 'orders.id as orderid, orders.order_number');
        $this->db->from('order_redelivery_trans');
        $this->db->join('orders', 'orders.id = order_redelivery_trans.order_id');
        $this->db->join('order_trans', 'orders.id = order_trans.order_id');
        $this->db->join('boxes', 'order_trans.box_id = boxes.id');
        $this->db->where('order_redelivery_trans.employee_id', $driver_id);
        
        if ($fetch_only_initial_delivery == false)
        {
            $this->db->where('order_redelivery_trans.initial_delivery', 'no');
            $this->db->where('order_redelivery_trans.paid_to_driver', 'yes');
        }
        else
        {
            $this->db->where('order_redelivery_trans.initial_delivery', 'yes');
            
        }
        
        $this->db->where('boxes.drop_from_commission', 'no');
        $this->db->where("date(order_redelivery_trans.created_at) between '$from_date' and '$to_date'");
        $this->db->order_by('order_redelivery_trans.id');
        $this->db->group_by('order_redelivery_trans.id');
        $query = $this->db->get();
        $result = $query->result_array();
        
        return $result;
    }
    
     public function getDriverWiseRedelNewHistoryByDateRange($driver_id, $from_date, $to_date, $fetch_only_initial_delivery=false)
    {
        $this->db->select('order_redelivery_trans.*, sum(order_trans.quantity) as quantity, '
                . 'group_concat(boxes.name ORDER BY boxes.id SEPARATOR "<br>") as boxes,'
                . 'group_concat(boxes.id ORDER BY boxes.id SEPARATOR "<br>") as box_ids,'
                . 'group_concat(boxes.delivery_commission ORDER BY boxes.id SEPARATOR "<br>") as delivery_commissions,
                   group_concat(boxes.collection_commission ORDER BY boxes.id SEPARATOR "<br>") as collection_commissions,'
                . 'group_concat(order_trans.quantity ORDER BY boxes.id SEPARATOR "<br>") as quantities,'
                . 'orders.id as orderid, orders.order_number');
        $this->db->from('order_redelivery_trans');
        $this->db->join('orders', 'orders.id = order_redelivery_trans.order_id');
        $this->db->join('order_trans', 'orders.id = order_trans.order_id');
        $this->db->join('boxes', 'order_trans.box_id = boxes.id');
        $this->db->where('order_redelivery_trans.employee_id', $driver_id);
        $this->db->where('order_redelivery_trans.initial_delivery', 'yes');
        $this->db->where("date(order_redelivery_trans.created_at) between '$from_date' and '$to_date'");
        $this->db->order_by('order_redelivery_trans.id');
        $this->db->group_by('order_redelivery_trans.id');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }
                    
    public function getOriginalOrderQty($driver_id, $from_date, $to_date)
    {
        $this->db->select('order_redelivery_trans.*, sum(order_redel_orig_qty.quantity) as quantity,'
                . 'group_concat(boxes.name ORDER BY boxes.id SEPARATOR "<br>") as boxes,'
                . 'group_concat(boxes.id ORDER BY boxes.id SEPARATOR "<br>") as box_ids,'
                . 'group_concat(boxes.delivery_commission ORDER BY boxes.id SEPARATOR "<br>") as delivery_commissions,
                  group_concat(boxes.collection_commission ORDER BY boxes.id SEPARATOR "<br>") as collection_commissions,'
                . 'group_concat(order_redel_orig_qty.quantity ORDER BY boxes.id SEPARATOR "<br>") as quantities,' 
                . 'orders.id as orderid, orders.order_number');
        $this->db->from('order_redelivery_trans');
        $this->db->join('orders', 'orders.id = order_redelivery_trans.order_id');
        $this->db->join('order_redel_orig_qty','order_redel_orig_qty.order_id = order_redelivery_trans.order_id');
        $this->db->join('boxes','order_redel_orig_qty.box_id = boxes.id');
        $this->db->where('order_redelivery_trans.employee_id',$driver_id);
        $this->db->where('order_redelivery_trans.initial_delivery','yes');
        $this->db->where("date(order_redelivery_trans.created_at) between '$from_date' and '$to_date'");                       
        $this->db->order_by('order_redelivery_trans.id');
        $this->db->group_by('order_redelivery_trans.id');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }
    public function getDriverWiseCollectionHistoryByDateRange($driver_id, $from_date, $to_date)
     { 
        $this->db->select('quantity as quantities,order_trans.order_id as orderid,employee_id,order_trans.box_id as box_ids,boxes.name as boxes,boxes.collection_commission as collection_commissions,boxes.delivery_commission as delivery_commissions,orders.order_number');
         $this->db->from('order_trans');
         $this->db->join('orders', 'order_trans.order_id = orders.id');
         $this->db->join('order_status_trans', 'order_status_trans.order_id = order_trans.order_id');
         $this->db->join('boxes', 'order_trans.box_id = boxes.id');
         $this->db->where('order_status_trans.employee_id', $driver_id);
         $this->db->where("date(order_status_trans.created_at) between '$from_date' and '$to_date'");
         $this->db->where('order_status_trans.status','box_collected');
         $this->db->group_by('order_trans.id');
         $query = $this->db->get();
         $result = $query->result_array();
         return $result;
     }
        public function getDriverWisedelHistoryByDateRange($driver_id, $from_date, $to_date)
     {
         $this->db->select('quantity as quantities,order_trans.order_id as orderid,order_status_trans.employee_id,order_trans.box_id as box_ids,boxes.name as boxes,boxes.collection_commission as collection_commissions,boxes.delivery_commission as delivery_commissions,orders.order_number');
         $this->db->from('order_trans');
         $this->db->join('orders', 'order_trans.order_id = orders.id');
         $this->db->join('order_status_trans', 'order_status_trans.order_id = order_trans.order_id');
         $this->db->join('boxes', 'order_trans.box_id = boxes.id');
         $this->db->where('order_status_trans.employee_id', $driver_id);
         $this->db->where("date(order_status_trans.created_at) between '$from_date' and '$to_date'");
         $this->db->where('order_status_trans.status','box_delivered');
         $this->db->where('`order_status_trans`.`order_id` NOT IN (SELECT `order_id` FROM `order_redelivery_trans`)', NULL, FALSE);
         $this->db->group_by('order_trans.id');
         $query = $this->db->get();
         $result = $query->result_array();
         return $result;
     }
     public function getDriverWiseInitialdelHistoryByDateRange($driver_id, $from_date, $to_date)
     {
         $this->db->select('quantity as quantities,order_trans.order_id as orderid,order_status_trans.employee_id,order_trans.box_id as box_ids,boxes.name as boxes,boxes.collection_commission as collection_commissions,boxes.delivery_commission as delivery_commissions,orders.order_number');
         $this->db->from('order_trans');
         $this->db->join('orders', 'order_trans.order_id = orders.id');
         $this->db->join('order_status_trans', 'order_status_trans.order_id = order_trans.order_id');
         $this->db->join('boxes', 'order_trans.box_id = boxes.id');
         $this->db->where('order_status_trans.employee_id', $driver_id);
         $this->db->where("date(order_status_trans.created_at) between '$from_date' and '$to_date'");
         $this->db->where('order_status_trans.status','box_delivered');
         $this->db->where('`order_status_trans`.`order_id` NOT IN (SELECT `order_id` FROM `order_redelivery_trans` WHERE `order_redelivery_trans`.`initial_delivery`="yes")', NULL, FALSE); 
         $this->db->group_by('order_trans.id');
         $query = $this->db->get();
         $result = $query->result_array();
         return $result;
    }
    public function getPaymentReferenceDetailsByOrderId($order_id)
     {
        $result = Null;
        
        if (!empty($order_id))
        {
            $this->db->select('commission_master.*');
            $this->db->where('commission_orders_trans.order_id', $order_id);
            $this->db->from('commission_master');
            $this->db->join('commission_orders_trans', 'commission_master.id = commission_orders_trans.commission_master_id');
            $result = $this->db->get()->row_array();
        }
        return $result;
    }
   
    public function getAllOrdersByRange($start_order_no, $end_order_no)     
    {         
        $this->db->select('orders.id as order_id, orders.order_number');         
        $this->db->from('orders');         
        $this->db->order_by('orders.id');         
        $this->db->where("orders.id >= $start_order_no");
//        $this->db->where('order_status_trans.status','received_at_jakarta_warehouse');
//        $this->db->where('order_status_trans.active','yes');
//        $this->db->join('order_status_trans','order_status_trans.order_id=orders.id ','left');
        if (!empty($end_order_no))         
        {             
            $this->db->where("orders.id <= $end_order_no");         
            
        }         
        $query = $this->db->get();    
        $result = $query->result_array();
        return $result;     
    }
                    
    public function getOrderNumberById($order_id)
    {
        $this->db->select('order_number');
        $this->db->from('orders');
        $this->db->where('orders.id',$order_id);
        $query = $this->db->get();
        $result = $query->row_array();         
        return $result; 
    }
    
    public function getShipmentData($shipment_batch_ids, $order_number)
    {
        if(!empty($shipment_batch_ids))
        {
            $this->db->select("batch_name, load_date, ship_onboard, eta_jakarta", FALSE);
            $this->db->from('shipment_batches');
            $this->db->where_in('shipment_batches.id',$shipment_batch_ids);
            $query = $this->db->get();
            $result = $query->result_array();
            return $result;
        }
        elseif(!empty($order_number))
        {
            $this->db->select("orders.order_number, orders.id, orders.collection_date, orders.shipment_batch_id, shipment_batches.batch_name, shipment_batches.load_date, shipment_batches.ship_onboard,shipment_batches.eta_jakarta, order_status_trans.status, order_status_trans.updated_at as order_stauts_date", FALSE);
            $this->db->from('orders'); 
            $this->db->join('shipment_batches', 'shipment_batches.id = orders.shipment_batch_id');
            $this->db->join('order_status_trans', 'order_status_trans.order_id = orders.id');
            $this->db->where('orders.order_number', $order_number);
            $this->db->where('order_status_trans.active', 'yes');
            $this->db->where('orders.status', 'active');
            $query = $this->db->get();
            $result = $query->result_array();
            return $result;
        } 
    }
    public function getOutstandingOrderPayment($date, $days, $pagingParams = array(), $statutes)
    {
        $this->db->select("orders.id, orders.order_number, orders.order_date,orders.collection_date,orders.discount, orders.nett_total, orders.grand_total,   
                           GROUP_CONCAT((order_trans.quantity) SEPARATOR ' <br>') AS boxes_quantity,
                           GROUP_CONCAT((boxes.name) SEPARATOR ' <br>') AS boxes_name,
                           GROUP_CONCAT((locations.name) SEPARATOR ', <br>') AS locations,
                           GROUP_CONCAT((kabupatens.name) SEPARATOR ' <br>') AS kabupaten, 
                           customers.name, customers.mobile,(select sum(ost.cash_collected) from order_status_trans as ost where orders.id = ost.order_id) AS total_cash_collected, (select sum(order_status_trans.voucher_cash) + total_cash_collected + discount from order_status_trans WHERE orders.id = order_status_trans.order_id) AS total_cash_deposit,(select sum(ost.cash_collected) + SUM(ost.voucher_cash) from order_status_trans as ost where orders.id = ost.order_id) as tot_voucher_tot_cash_collected,
 (select grand_total - total_cash_deposit) AS outstanding_order_payment ", FALSE);
        $this->db->from('orders'); 
        $this->db->join('customers', 'customers.id = orders.customer_id');
        $this->db->join('order_trans', 'order_trans.order_id = orders.id');
        $this->db->join('boxes', 'boxes.id = order_trans.box_id');
        $this->db->join('locations', 'locations.id = order_trans.location_id'); 
        $this->db->join('kabupatens', 'kabupatens.id = order_trans.kabupaten_id');
        $this->db->join('order_status_trans', 'order_status_trans.order_id = orders.id');
        $this->db->where_in('order_status_trans.status', $statutes);
        $this->db->where('order_status_trans.active','yes');
        $this->db->where('orders.status','active');
        if($days != 'all')
        {
            $this->db->where("DATEDIFF('$date', DATE(collection_date)) <= $days");
        }
        $this->db->order_by('orders.collection_date desc');
        $this->db->having('outstanding_order_payment > 0');
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
            $column_arr = array('order_number','collection_date','name','mobile','boxes_name','boxes_quantity','kabupaten','grand_total','discount','total_cash_deposit','outstanding_amount');
            switch ($pagingParams['sSortDir_0'])
            {
                default:
                    $this->db->order_by($column_arr[$pagingParams['iSortCol_0'] + 1], $pagingParams['sSortDir_0']);
                    break;
            }
        }
        
        if (isset($pagingParams['iDisplayStart']) && $pagingParams['iDisplayLength'] != '-1')
        {
            $start = $pagingParams['iDisplayStart'];
            $offset = $pagingParams['iDisplayLength'];
           
            $return = $this->getWithCount(null, $offset, $start);
//            p($this->db->last_Query());
            return $return;
        }
        if(isset($pagingParams['return_arr']))
        {
            $query = $this->db->get();
            $result = $query->result_array();
            return $result;
        }
    }
    
    public function getOrderImagesMasterid($days, $currentDateTime)
    {
        $this->db->select("*");
        $this->db->from("order_image_master");
        $this->db->where("datediff('$currentDateTime',order_image_master.updated_at) >", $days);
        $query = $this->db->get();   
        $result = $query->result_array();
        return $result; 
    }
    
    public function deleteFromMasterTable($id)
    {
        $this->db->where("id", $id);
        $this->db->delete('order_image_master');
    }
    
    public function deleteFromTransTable($id)
    {
        $this->db->where("order_image_master_id", $id);
        $this->db->delete('order_image_trans');
    }
    
                    
    public function checkDuplicatiorCustomer($mobile, $phone, $postal_code, $deliveryDate)
    { 
        $seperateDeliveryDate = explode("/","$deliveryDate");
        $year = $seperateDeliveryDate[2];
        $month = $seperateDeliveryDate[1];
        $date = $seperateDeliveryDate[0];
                    
        $this->db->select("*,orders.id as order_id");
        $this->db->from("customers");
        $this->db->join("orders","orders.customer_id = customers.id","left");
        
        if(!empty($mobile) && !empty($phone))
        {
            $this->db->where("(customers.mobile='$mobile' OR customers.residence_phone='$phone' OR customers.pin='$postal_code')"); 
        }
        else if(!empty($mobile))
        {
            $this->db->where("(customers.mobile='$mobile' OR customers.pin='$postal_code')"); 
        }
        else if(!empty($phone))
        {
            $this->db->where("(customers.residence_phone='$phone' OR customers.pin='$postal_code')");
        } 
        $this->db->where('date(orders.delivery_date)', "$year-$month-$date");
        $query = $this->db->get();   
        $result = $query->result_array();
        return $result;
    }
    
    public function getDuplicateCustomers()
    {
        $this->db->select("count(*),GROUP_CONCAT(customers.id ORDER BY customers.id DESC  SEPARATOR '@#') as duplicate_customer_id,customers.*");
        $this->db->from("customers");
        $this->db->group_by('pin,unit,block,building,street,mobile,residence_phone');
        $this->db->having('count(*) > 1');
        $query = $this->db->get();  
        $result = $query->result_array();
        return $result;
    }
    
    public function updateCustomerId($new_customer_id, $old_customer_id)
    {
        $data = array("customer_id" => $new_customer_id);
        $this->db->where("customer_id", $old_customer_id);
        $this->db->update('orders', $data);
    }
    
    public function deleteOldCustomers($customer_id)
    {
        $this->db->where("customers.id", $customer_id);
        $this->db->delete('customers');
    }
                    
    public function getCustomerNameById($old_customer_id)
    { 
        $this->db->select("*");
        $this->db->from("customers");
        $this->db->where("customers.id", $old_customer_id);
        $query = $this->db->get();
        $result = $query->row_array();
        return $result;
    } 
                    
    public function getOldOrders($currentDateTime, $get_date_wise_orders, $old_orders_status)
    {
        $this->db->select("*, DATEDIFF('$currentDateTime',shipment_batches.created_at ) AS shipment_created_days, DATEDIFF('$currentDateTime',receiving_batches.created_at ) AS receiving_batch_created_days");
        $this->db->from("orders");
        $this->db->join("order_status_trans","order_status_trans.order_id = orders.id","left");
        $this->db->join("shipment_batches","shipment_batches.id = orders.shipment_batch_id","left");
        $this->db->join("receiving_batches","receiving_batches.id = shipment_batches.receiving_batch_id","left");
        $this->db->where_in("order_status_trans.status", $old_orders_status);
        $this->db->where("order_status_trans.active", "yes");
        $this->db->where("orders.order_date < ", $get_date_wise_orders); 
        $query = $this->db->get();  
        $result = $query->result_array();
        return $result;    
    }
                    
    public function deleteShipmentBatch($shipment_id)
    {
        $this->db->where("id", $shipment_id);
        $this->db->delete('shipment_batches');
    }
    
    public function deleteShipmentBatchBoxMapping($shipment_id)
    {
        $this->db->where("shipment_batch_box_mapping.shipment_batch_id", $shipment_id);
        $this->db->delete('shipment_batch_box_mapping');
    }
    
    public function deleteReceivingBatches($receiving_batch_id)
    {
        $this->db->where("receiving_batches.id", $receiving_batch_id);
        $this->db->delete('receiving_batches');
    }               
    
    public function deleteRecordFromOrderTable($order_id)
    {
        $this->db->where("id", $order_id);
        $this->db->delete('orders');
    }
    public function deleteRecordFromOrderTransTable($order_id)
    {
        $this->db->where("order_trans.order_id", $order_id);
        $this->db->delete('order_trans');
    }
    public function deleteRecordFromOrderStatusTransTable($order_id)
    {
        $this->db->where("order_status_trans.order_id", $order_id);
        $this->db->delete('order_status_trans');
    }               
    public function getPromotionById($promocode_id)
    {
       $this->db->select("*");
       $this->db->from("promotion");
       $this->db->where("promotion.id", $promocode_id); 
       $query = $this->db->get();  
       $result = $query->row_array();
       return $result;
    }
    public function updateUsageLeft($promoCodeUsageLeftArr, $multiple_usage)
    {
        if(isset($multiple_usage) && $multiple_usage == "no")
        {
            $data = array("usage_left" => $promoCodeUsageLeftArr['usage_left'], 'is_active' => "no");
        }
        else
        {
            $data = array("usage_left" => $promoCodeUsageLeftArr['usage_left']);
        }
        $this->db->where("id", $promoCodeUsageLeftArr['id']);
        $this->db->update('promotion', $data);
    }
    
    public function getOrderPromoCodeId($order_id)
    {
       $this->db->select("*");
       $this->db->from("order_trans");
       $this->db->where("order_trans.order_id", $order_id); 
       $query = $this->db->get();  
       $result = $query->row_array();
       return $result;
    }
    
    public function getPromoCodeData($pagingParams, $returnData=null)
    {  
        $this->db->select("promotion.id AS promoCodeId, promotion.name AS promoCodeName,order_trans.order_id,orders.order_number, orders.order_date,orders.collection_date, orders.grand_total, orders.discount,orders.nett_total,customers.id,customers.name as customer_name,customers.mobile as customer_mobile, boxes.name as box_name,kabupatens.name as kabupaten_name, order_trans.quantity AS box_quantity",FALSE);
        $this->db->from('order_trans');
         $this->db->join("promotion", "promotion.id = order_trans.promocode_id",'left');
        $this->db->join("orders", "orders.id = order_trans.order_id"); 
        $this->db->join("customers", "customers.id = orders.customer_id");
        $this->db->join("boxes", "boxes.id = order_trans.box_id");
        $this->db->join("kabupatens", "kabupatens.id = order_trans.kabupaten_id", 'left');        
        $this->db->where("order_trans.order_id IN (SELECT DISTINCT order_trans.order_id FROM order_trans WHERE order_trans.promocode_id IS NOT NULL AND order_trans.promocode_id =".$pagingParams['search_promo_id'].')', NULL, FALSE);
        
        
        if($pagingParams['search_collection_date_from'])
        { 
            list($month, $day, $year) = explode('/', $pagingParams['search_collection_date_from']);
            $collection_date_from = "$year-$month-$day";
            $this->db->where('date(orders.collection_date) >=', $collection_date_from);                         }
        
        
        if($pagingParams['search_collection_date_to'])
        {
            list($month, $day, $year) = explode('/', $pagingParams['search_collection_date_to']);
            $collection_date_to = "$year-$month-$day";
            $this->db->where('date(orders.collection_date) <=', $collection_date_to);  
        }
                    
        if($returnData == "true")
        {
            $this->db->order_by('orders.id desc');
            $query = $this->db->get();  
            $result = $query->result_array(); 
            return $result;
        }
        
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
            $this->db->order_by('orders.id desc');
        } 
        if (isset($pagingParams['iDisplayStart']))
        {
            if ($pagingParams['iDisplayLength'] != '-1')
            {
                $start = $pagingParams['iDisplayStart'];
                $offset = $pagingParams['iDisplayLength'];
            }
            else
            {
                $offset = $start = null;
            }

            $return = $this->getWithCount(null, $offset, $start);
            if($return && $return['resultSet'])
            {
                foreach($return['resultSet'] as $idx => $resultVal)
                {
                    if(empty($resultVal['promoCodeId']))
                    {
                        $return['resultSet'][$idx]['discount'] = "0";
                    }
                }
            } 
            return $return;
        }
    }
    
    public function get_customer_by_mobile_number($mobile_number)
    {
        if(empty($mobile_number))
            return array();
            
        $this->db->select("*");
        $this->db->from("customers");
       $this->db->where("customers.mobile", $mobile_number); 
        $query = $this->db->get();  
        $result = $query->result_array();
        return $result;
    }    
    
    public function updateCustomerId_by_CustomerId($customer_id,$data)
    {
        $this->db->where("id", $customer_id);
        $this->db->update('customers', $data);
    }
    
    
    public function saveCustomerType($dataValues, $customer_id)
    {
        $return = null;
        if (count($dataValues) > 0)
        {
            if ($customer_id)
            {
                $this->db->where("customer_id", $customer_id);
                $this->db->delete('customer_type_mapping');
            }
                  
            foreach ($dataValues as $key => $customer_type_id) 
            {
                $array_customer_type = array(
                    'customer_id' => $customer_id,
                    'customer_type_id' => $customer_type_id,
                );  
                $this->db->insert('customer_type_mapping', $array_customer_type);
                $return = $this->db->insert_id();
            }
        }
        return $return;
    }
    
    public function saveMediaType($dataValues, $customer_id)
    {
        $return = null;
        if (count($dataValues) > 0)
        {
            if ($customer_id)
            {
                $this->db->where("customer_id", $customer_id);
                $this->db->delete('customer_media_type_mapping');
            }
            
            foreach ($dataValues as $key => $media_type_id) 
            {
                $array_media_type = array(
                    'customer_id' => $customer_id,
                    'media_type_id' => $media_type_id,
                );  
                $this->db->insert('customer_media_type_mapping', $array_media_type);
                $return = $this->db->insert_id();
            }
        }
        return $return;
    }
   
    public function getAllCustomerType()
    {
        $return = array();
        $this->db->select("customer_type.*,pass_type.pass_type"); 
        $this->db->join('pass_type', 'pass_type.pass_type_id = customer_type.pass_type_id','left');
        $this->db->order_by('customer_type.customer_type_id' ,"asc");
        $query = $this->db->get('customer_type');
        $return = $query->result_array();
        return $return;
    }
    
    public function getAllCustomerTypeById($customer_id)
    {
        $return = array();
        $this->db->select("customer_type.*,pass_type.pass_type"); 
        $this->db->join('customer_type', 'customer_type.customer_type_id = customer_type_mapping.customer_type_id','left');
        $this->db->join('pass_type', 'pass_type.pass_type_id = customer_type.pass_type_id','left');
        $this->db->where('customer_type_mapping.customer_id',$customer_id);
        $this->db->order_by('customer_type.customer_type_id' ,"asc");
        $query = $this->db->get('customer_type_mapping');
        
        if($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $key => $value) 
            {
                $return[$value["customer_type_id"]] = $value["customer_type_id"];
            }
        }
        return $return;
    }
    
    public function getAllMediaType()
    {
        $return = array();
        $this->db->select("media_type.*,categories.category"); 
        $this->db->join('categories', 'categories.category_id = media_type.category_id','left');
        $this->db->where('categories.type',"media_type");
        $this->db->order_by('media_type.media_type_id');
        $query = $this->db->get('media_type');
        $return = $query->result_array();
        return $return;
    }
    
    public function getAllMediaTypeById($customer_id)
    {
        $return = array();
        $this->db->select("media_type.*,categories.category"); 
        $this->db->join('media_type', 'media_type.media_type_id = customer_media_type_mapping.media_type_id','left');
        $this->db->join('categories', 'categories.category_id = media_type.category_id','left');
        $this->db->where('customer_media_type_mapping.customer_id',$customer_id);
        $this->db->order_by('media_type.media_type_id' ,"asc");
        $query = $this->db->get('customer_media_type_mapping');
        if($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $key => $value) 
            {
                $return[$value["media_type_id"]] = $value["media_type_id"];
            }
        }
        return $return;
    }
    
    public function CustomerTypeReportData($date_from, $date_to)
    {
        $results = array();
        if(empty($date_from) || empty($date_to))
            return $results;
        
        $this->db->select("customer_type.customer_type,count(orders.order_number) as orders_count,", false);
        $this->db->from('customer_type');
        $this->db->join('customer_type_mapping', 'customer_type_mapping.customer_type_id = customer_type.customer_type_id','left');
        $this->db->join('customers', 'customers.id = customer_type_mapping.customer_id');    
        $this->db->join('orders', 'orders.customer_id = customers.id'); 
        $this->db->where("(date(orders.order_date) >= '$date_from' and  date(orders.order_date) <= '$date_to')");   
        $this->db->group_by('customer_type_mapping.`customer_type_id`');

        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
           $sum_orders_count =  array_sum(array_map(function($item) { 
                return $item['orders_count']; 
            }, $query->result_array()));
            
            $getAllCustomerType = $this->getAllCustomerType();
            foreach ($getAllCustomerType as $key => $value) 
            {
                $result = array();
                $orders_count = $pecentage = 0;
                foreach ($query->result_array() as $CustomerTypeReportData) 
                {
                    if($CustomerTypeReportData["customer_type"] == $value["customer_type"])
                    {
                        $orders_count = $CustomerTypeReportData["orders_count"];
                        $pecentage = ($CustomerTypeReportData["orders_count"]/ $sum_orders_count) * 100;                        
                    }                    
                }
                $result["customer_type"] = $value["customer_type"];
                $result["orders_count"] = $orders_count;
                $result["pecentage"] = number_format((float)$pecentage, 2, '.', '')."%";
                $results[] = $result;

            }
        }
        return $results;
    }
   
    public function MediaTypeReportData($date_from, $date_to)
    {
        $results = array();
        if(empty($date_from) || empty($date_to))
            return $results;
        
        $this->db->select("media_type.media_type,count(orders.order_number) as orders_count,", false);
        $this->db->from('media_type');
        $this->db->join('customer_media_type_mapping', 'customer_media_type_mapping.media_type_id = media_type.media_type_id','left');
        $this->db->join('customers', 'customers.id = customer_media_type_mapping.customer_id');    
        $this->db->join('orders', 'orders.customer_id = customers.id');    
        $this->db->where("(date(orders.order_date) >= '$date_from' and  date(orders.order_date) <= '$date_to')");
        $this->db->group_by('customer_media_type_mapping.`media_type_id`');

        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
           $sum_orders_count =  array_sum(array_map(function($item) { 
                return $item['orders_count']; 
            }, $query->result_array()));
            
            $getAllMediaType = $this->getAllMediaType();
            foreach ($getAllMediaType as $key => $value) 
            {
                $result = array();
                $orders_count = $pecentage = 0;
                foreach ($query->result_array() as $MediaTypeReportData) 
                {
                    if($MediaTypeReportData["media_type"] == $value["media_type"])
                    {
                        $orders_count = $MediaTypeReportData["orders_count"];
                        $pecentage = ($MediaTypeReportData["orders_count"]/ $sum_orders_count) * 100;
                    }                    
                }
                $result["media_type"] = $value["media_type"];
                $result["orders_count"] = $orders_count;
                $result["pecentage"] = number_format((float)$pecentage, 2, '.', '')."%";
                $results[] = $result;

            }
        }
        return $results;
    }
    
    public function getOrdersByShipmentBatchId($shipment_batch_id)
    {
        $this->db->select('orders.order_number, orders.id as order_id');
        $this->db->from('orders');
        $this->db->where('shipment_batch_id', $shipment_batch_id);
        $query = $this->db->get();
        
        $result = $query->result_array();
        return $result;
    }
    
    public function saveBlacklistCustomer($dataValues)
    {
        $return = null;
        if (count($dataValues) > 0)
        {
            if (array_key_exists('id', $dataValues))
            {
                $this->db->where('id', $dataValues['id']);
                $this->db->update('blacklist_customer', $dataValues);

                $return = $dataValues['id'];
            }
            else
            {
                $this->db->insert('blacklist_customer', $dataValues);
                $return = $this->db->insert_id();
            }
        }
        return $return;
    }

    public function checkCustomerBlacklistOrNot($search_query, $condition = "", $extra = "")
    {
        $result = array();
        $this->db->select('*');
        $this->db->from('blacklist_customer');

        if (!empty($search_query))
        {
            if(array_key_exists('mobile', $search_query))
            {
                $this->db->where('mobile', $search_query['mobile']);
                if($condition == "" && $extra == "")
                {
                    $this->db->where('residence_phone', $search_query['phone']);
                }
                else
                {
                    $this->db->or_where('residence_phone', $search_query['phone']);
                }
            }
            
            if(array_key_exists('pin', $search_query))
            {
                $this->db->where('pin', $search_query['pin']);
            }
            if(array_key_exists('pin', $search_query) && $extra != "")
            {
                $this->db->or_where('pin', $search_query['pin']);
            }
        }
        $query = $this->db->get();
        if((array_key_exists('pin', $search_query) || $condition != "") && $extra == "")
        {
            $result = $query->result_array();
        }
        else
        {
            $result = $query->row_array();
        }
        
        return $result;
    }
    
    public function deleteBlackListCustomer($blacklistCustomerId)
    {
        $this->db->where('id', $blacklistCustomerId);
        $this->db->delete('blacklist_customer');
    }
}

?>
