<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class CommissionLib
{

    private $_CI;
    
    public function __construct()
    {
        $this->_CI = & get_instance();
        $this->_CI->load->model('admin/commissionModel');
        $this->_CI->load->model('admin/ordersModel');
    }

    public function getDriverCommission($where)
    {
        $return = $orders = array();
        
        $redelivery_amount = $this->_CI->config->item('redelivery_amount');

        $redel_result = $this->_CI->ordersModel->getDriverWiseRedelHistoryByDateRange($where['employee_id'], $where['date_from'], $where['date_to']);
        $chk_orignal_order_qty =  $this->_CI->ordersModel->getOriginalOrderQty($where['employee_id'], $where['date_from'], $where['date_to']);
        $collection_query = $this->_CI->ordersModel->getDriverWiseCollectionHistoryByDateRange($where['employee_id'], $where['date_from'], $where['date_to']);
        if (empty($collection_query))
        {
//                $return['redelivery'] = array();
        }
        else
        {  
            foreach ($collection_query as $index => $row)
            {  
                $boxes = explode('<br>', $row['boxes']);
                $box_ids = explode('<br>', $row['box_ids']);
                $quantities = explode('<br>', $row['quantities']);
                $collection_commissions = explode('<br>', $row['collection_commissions']);
                $delivery_commissions = explode('<br>', $row['delivery_commissions']);
                 
                foreach ($boxes as $box_index => $box)
                {
                     $data = array(
                        'order_id' => $row['orderid'],
                        'order_number' => $row['order_number'],
                        'quantity' => in_array($row['order_number'], $orders) ? 0 : $quantities[$box_index],
                        'original_quantity' => $quantities[$box_index],
                        'box_ids' => $box_ids[$box_index],
                        'collection_commission' => $collection_commissions[$box_index],
                        'delivery_commission' => $delivery_commissions[$box_index],
                );
                    $tmp_index = "$box@@##@@{$collection_commissions[$box_index]}@@##@@{$delivery_commissions[$box_index]}";
                    $return[$tmp_index]['collection'][] = $data;
                 }
                }
          }
        $initial_del  = $this->_CI->ordersModel->getDriverWiseRedelNewHistoryByDateRange($where['employee_id'], $where['date_from'], $where['date_to'], $initial_delivery=true);
        
        if(!empty($initial_del))
         {  
            $initial_del_result = $this->_CI->ordersModel->getDriverWisedelHistoryByDateRange($where['employee_id'], $where['date_from'], $where['date_to']);          
         }
        else
         { 
             $initial_del_result = $this->_CI->ordersModel->getDriverWiseInitialdelHistoryByDateRange($where['employee_id'], $where['date_from'], $where['date_to']);          
         }
       if(empty($chk_orignal_order_qty))
        {
        if (empty($initial_del_result))
        {
//                $return['redelivery'] = array();
        }
        else
        {
            foreach ($initial_del_result as $index => $row)
            {
                $boxes = explode('<br>', $row['boxes']);
                $box_ids = explode('<br>', $row['box_ids']);
                $quantities = explode('<br>', $row['quantities']);
                $collection_commissions = explode('<br>', $row['collection_commissions']);
                $delivery_commissions = explode('<br>', $row['delivery_commissions']);
                   
                foreach ($boxes as $box_index => $box)
                {
                    $data = array(
                        'order_id' => $row['orderid'],
                        'order_number' => $row['order_number'],
                        'quantity' => in_array($row['order_number'], $orders) ? 0 : $quantities[$box_index],
                        'original_quantity' => $quantities[$box_index],
                        'box_ids' => $box_ids[$box_index],
                        'collection_commission' => $collection_commissions[$box_index],
                        'delivery_commission' => $delivery_commissions[$box_index],
                    );
                    $tmp_index = "$box@@##@@{$collection_commissions[$box_index]}@@##@@{$delivery_commissions[$box_index]}";
                    $return[$tmp_index]['delivery'][] = $data;
                }
            }
        }
        }
        else
        {  
           foreach ($chk_orignal_order_qty as $index => $row)
            {
                $boxes = explode('<br>', $row['boxes']);
                $box_ids = explode('<br>', $row['box_ids']);
                $quantities = explode('<br>', $row['quantities']);
                $collection_commissions = explode('<br>', $row['collection_commissions']);
                $delivery_commissions = explode('<br>', $row['delivery_commissions']);
               foreach ($boxes as $box_index => $box)
                {
                    $data = array(
                        'order_id' => $row['orderid'],
                        'order_number' => $row['order_number'],
                        'quantity' => in_array($row['order_number'], $orders) ? 0 : $quantities[$box_index],
                        'original_quantity' => $quantities[$box_index],
                        'box_ids' => $box_ids[$box_index],
                        'collection_commission' => $collection_commissions[$box_index],
                        'delivery_commission' => $delivery_commissions[$box_index],
                    );
                    $tmp_index = "$box@@##@@{$collection_commissions[$box_index]}@@##@@{$delivery_commissions[$box_index]}";
                    $return[$tmp_index]['delivery'][] = $data;
                }
             }
            foreach ($initial_del_result as $index => $row)
            {  
                $boxes = explode('<br>', $row['boxes']);
                $box_ids = explode('<br>', $row['box_ids']);
                $quantities = explode('<br>', $row['quantities']);
                $collection_commissions = explode('<br>', $row['collection_commissions']);
                $delivery_commissions = explode('<br>', $row['delivery_commissions']);
                $chk_orders = false;
                foreach ($chk_orignal_order_qty as $index => $initial_row)
                    {
                    if($row['order_number'] == $initial_row['order_number'])
                    {
                      $chk_orders = true;       
                    }
                   }
                   if($chk_orders == false) 
                     { 
                      foreach ($boxes as $box_index => $box)
                      {
                       $data = array(
                        'order_id' => $row['orderid'],
                        'order_number' => $row['order_number'],
                        'quantity' => in_array($row['order_number'], $orders) ? 0 : $quantities[$box_index],
                        'original_quantity' => $quantities[$box_index],
                        'box_ids' => $box_ids[$box_index],
                        'collection_commission' => $collection_commissions[$box_index],
                        'delivery_commission' => $delivery_commissions[$box_index],
                    );
                    $tmp_index = "$box@@##@@{$collection_commissions[$box_index]}@@##@@{$delivery_commissions[$box_index]}";
                    $return[$tmp_index]['delivery'][] = $data;
                }
            }
            }
        }
        if (empty($redel_result))
        {
//                $return['redelivery'] = array();
        }
        else
        {
            foreach ($redel_result as $index => $row)
            {  
                $boxes = explode('<br>', $row['boxes']);
                $box_ids = explode('<br>', $row['box_ids']);
                $quantities = explode('<br>', $row['quantities']);
                $collection_commissions = explode('<br>', $row['collection_commissions']);
                $delivery_commissions = explode('<br>', $row['delivery_commissions']);
                foreach ($boxes as $box_index => $box)
                {
                }
                    $data = array(
                        'order_id' => $row['orderid'],
                        'order_number' => $row['order_number'],
                        'quantity' => in_array($row['order_number'], $orders) ? 0 : $quantities[$box_index],
                        'original_quantity' => $quantities[$box_index],
                        'box_ids' => $box_ids[$box_index],
                        'collection_commission' => $collection_commissions[$box_index],
                        'delivery_commission' => $delivery_commissions[$box_index],
                        'commission_amount' => $row['commission_amount']
                    );
                     $tmp_index = "redelivery@@##@@$row[orderid]@@##@@redelivery@@##@@$redelivery_amount@@##@@$redelivery_amount";
                     $return[$tmp_index]['redelivery'][] = $data;
                }
          }
//            krsort( $return);
     return $return;
    }
}
