<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    
    function getShipmentCostMasterValuesByField($field=null, $where=array())
    {
        $return = array();
        
        $CI = & get_instance();
        $CI->load->config('shipment_cost_config');
        
        switch($field)
        {
            case 'location':
                $values = $CI->config->item('master_locations_line_items');
                $db_field = 'item';
                break;
            
            case 'freight':
                $values = $CI->config->item('master_freight_line_items');
                $db_field = 'item';
                break;
            
            case 'Special Pack':
                $values = $CI->config->item('master_special_pack_line_items');
                $db_field = 'item';
                break;
            
            case 'location_local':
                $values = $CI->config->item('master_locations_costing_line_items');
                $db_field = 'item';
                break;
            
            case 'location_overseas':
                $values = $CI->config->item('master_locations_local_costing_line_items');
                $db_field = 'item';
                break;
            
            case 'freight_local':
                $values = $CI->config->item('master_freight_local_costing_line_items');
                $db_field = 'item';
                break;
            
            default:
                $db_field = null;
                $values = array();
                break;
        }
        
        $CI->load->model('admin/shipmentcostModel');
        $master_records = $CI->shipmentcostModel->getShipmentCostMasterValues($db_field, $values, $where);
        
        if (empty($field))
        {
            $return = $master_records;
        }
        else
        {
            if (!empty($master_records))
            {
                foreach ($master_records as $row)
                {
                    $return[$row['item']] = $row['costing'];
                }
            }
        }
        
        return $return;
    }