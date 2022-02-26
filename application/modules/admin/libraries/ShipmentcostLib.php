<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ShipmentcostLib
{

    private $_CI;
    
    public function __construct()
    {
        $this->_CI = & get_instance();
        $this->_CI->load->helper('shipment_cost');
        $this->_CI->load->model('admin/shipmentcostModel');
        $this->_CI->load->model('admin/ordersModel');
    }

    public function getShipmentCost($where)
    {
        $location_box_count = $this->_CI->shipmentcostModel->getShipmentBatchLocationOrderBoxes($where);
        if (!empty($location_box_count))
        {
            $temp_location_records = $boxes = array();
            $previous_location_id = 0;
            
            foreach ($location_box_count as $row)
            {
                if (!empty($previous_location_id) && $previous_location_id != $row['location_id'])
                {
                    $temp_location_records[] = array(
                        'location' => $previous_location,
                        'location_id' => $previous_location_id,
                        'boxes' => $boxes
                    );
                    
                    $boxes = array();
                }
                
                $boxes[$row['box_id']]['name'] = $row['box'];
                
                if (empty($boxes[$row['box_id']]['quantity']))
                {
                    $boxes[$row['box_id']]['quantity'] = $row['quantity'];
                }
                else
                {
                    $boxes[$row['box_id']]['quantity'] += $row['quantity'];
                }
                
                $previous_location_id = $row['location_id'];
                $previous_location = $row['location'];
            }
            
            $temp_location_records[] = array(
                        'location' => $previous_location,
                        'location_id' => $previous_location_id,
                        'boxes' => $boxes
                    );
            
            $location_box_count = $temp_location_records;
        }
        
        
        $special_boxes = $this->_CI->shipmentcostModel->getShipmentBatchOrderWiseSpecialBoxes($where);
     
        $return = array(
            'location_box_count' => $location_box_count,
            'special_boxes' => $special_boxes,
        );
        
        return $return;
    }
    
    public function getShipmentCostReport($where,$count = false)
    {
        $box_count = $this->_CI->shipmentcostModel->getOrdersShipmentBatch($where);
        $orders_count = $this->_CI->shipmentcostModel->getOrdersShipmentBatchOrders($where);
        $boxes = 0;
        if (!empty($box_count) && !empty($orders_count))
        {
            $temp_location_records = $boxes =  array();
            $quantity = 0;
            $previous_location_id = 0;
            $total_seles = 0;            
            foreach ($box_count as $row)
            {             
                $boxes[$row['box_id']]['name'] = $row['box'];
                
                if(isset($boxes[$row['box_id']]['quantity']))
                {
                    $boxes[$row['box_id']]["total_seles"] += $row["nett_total"];
                    $boxes[$row['box_id']]['quantity'] += $row["quantity"];
                    $boxes[$row['box_id']]['grand_total'] += $row['grand_total'];
                    $boxes[$row['box_id']]['discount'] += $row['discount'];
                }
                else
                {
                    $boxes[$row['box_id']]["total_seles"] = $row["nett_total"];
                    $boxes[$row['box_id']]["commission_orders"] = 0;
                    $boxes[$row['box_id']]['quantity'] = $row['quantity'];
                    $boxes[$row['box_id']]['grand_total'] = $row['grand_total'];
                    $boxes[$row['box_id']]['discount'] = $row['discount'];
                }
            }
            foreach ($orders_count as $row)
            {                           
                if(isset($boxes[$row['box_id']]['quantity']))
                {
                    $boxes[$row['box_id']]["commission_orders"] += $row["commission_orders"];
                }
            }
            if($count)
            {
                foreach ($box_count as $row)
                {             
                    $boxes[$row['box_id']]['name'] = $row['box'];
                    $quantity += $row["quantity"];
                    $total_seles += $row["nett_total"];
                }
                $boxes = $quantity;
                
            }
        }     
        
        return $boxes;
    }
    
    public function getShipmentCostMastersData($type, $where=array())
    {
        $return = getShipmentCostMasterValuesByField($type, $where);
        
        return  $return;
    }
    
    public function getShipmentCostData()
    {
        $data = $this->_CI->shipmentcostModel->getShipmentCostData();
        
        if (empty($data))
        {
            $return = array();
        }
        else
        {
            foreach ($data as $row)
            {
                $return[$row['section']][$row['text']] = $row['costing'];
            }
        }
        
        return  $return;
    }
}
