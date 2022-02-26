<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class AdminLib
{

    private $_CI;
    private $_user_id;
    
    const ORDER_INITIAL_STATUS = 'order_booked';
    
    public function __construct()
    {
        $this->_CI = & get_instance();
        $this->_user_id = $this->_CI->session->userdata('id');
    }

    public function getlocationBoxPriceMapping()
    {
        $result = array();
        
        $this->_CI->load->model('admin/mastersModel');
        $return = $this->_CI->mastersModel->getlocationBoxMapping();

        if (!empty($return))
        {
            foreach ($return as $index => $row)
            {
                $result[$row['location_id']][$row['box_id']] = $row['price'];
            }
        }

        return $result;
    }

    public function savelocationBoxPriceMapping(array $prices = array())
    {
        $result = array();
        
        $this->_CI->load->model('admin/mastersModel');
        
        if (!empty($prices))
        {            
            foreach ($prices as $index => $price)
            {
                list ($location_id, $box_id) = explode('_#_', $index);
                
                $array = array(
                    'location_id' => $location_id, 
                    'box_id' => $box_id,
                );
                
                $this->_CI->mastersModel->deleteLocationBoxPriceMapping($array);
                
                $array['price'] = $price;
                
                $return = $this->_CI->mastersModel->savelocationBoxPriceMapping($array);
            }
        }

        return $result;
    }

    public function saveOrders($array)
    {
        $result = array();
        
        $this->_CI->load->model('admin/ordersModel');
        
        if (empty($array['order_id']))
        {
            if (empty($array['manual_order_number']))
            {
                $order_number_arr = getMaxOrderNumber($array['agent_id']);

                $order_number = $order_number_arr['order_number'];
                $raw_order_number = $order_number_arr['raw_order_number'];
            }
            else
            {
                $order_number = $array['manual_order_number'];
                $raw_order_number = 0;
            }
        }
        else
        {
            $order_number = $array['manual_order_number'];
        }
        
        if (!empty($array['boxes']))
        {
            $order_array = array(
                'customer_id' => $array['customer_id'],
                'discount' => $array['discount'],
                'discount_type' => $array['discount_type'], 
                'grand_total' => $array['grand_total'],
                'nett_total' => $array['nett_total'],
                'pin' => $array['pin'],
                'street' => $array['street'],
                'building' => $array['building'],
                'block' => $array['block'],
                'unit' => $array['unit'],
                'agent_id' => $array['agent_id'],
                'comments' => $array['comments'],
                'collection_notes' => $array['collection_notes'],
                'status' => 'active',
                
                'weight' => $array['weight'],
                
                'delivery_date' => $array['delivery_date'],
                'collection_date' => $array['collection_date'],
                'order_date' => $array['order_date'],
                'picture_receive_date' => $array['picture_receive_date'],
                
                'lattitude' => $array['lattitude'],
                'longitude' => $array['longitude'],
                
                'google_lat' => $array['google_lat'],
                'google_lon' => $array['google_lon'],
            
                'recipient_address' => $array['recipient_address'],
                'recipient_name' => $array['recipient_name'],
                'recipient_mobile' => $array['recipient_mobile'],
                'recipient_item_list' => $array['recipient_item_list'],
                
                'jkt_weight' => $array['jkt_weight'],
                'jkt_reference_no' => $array['jkt_reference_no'],
                'jkt_received_date' => $array['jkt_received_date'],
                'jkt_receiver' => $array['jkt_receiver'],
                'memo' => $array['memo'],
                
                'updated_at' => date('Y-m-d H:i:s'),
            );
            
            if (empty($array['order_id']))
            {
                $mode = 'add';
                
                $order_array['order_number'] = $order_number;
                
                $order_array['raw_order_number'] = $raw_order_number;
                $order_array['created_at'] = date('Y-m-d H:i:s');
            }
            else
            {
                $mode = 'edit';
                
                $order_info = $this->_CI->ordersModel->getOrderDetails($array['order_id']);
                $old_delivery_date = explode(' ', $order_info['delivery_date']);
                        
                $old_collection_date = $order_info['collection_date'];
                if (!empty($old_collection_date))
                {
                    $old_collection_date = explode(' ', $old_collection_date);
                    $old_collection_date = $old_collection_date[0];
                }
               
                $order_status_row = $this->_CI->ordersModel->getOrderStatusDetails($array['order_id']);
                
                if ($array['collection_date_wo_time'] != $old_collection_date)
                {
                    
                    if ($order_status_row['status'] == 'box_delivered')
                    {
                        if ($array['collection_date_wo_time'] == date('Y-m-d'))
                        {
                            $responsibility_completed = 'no';
                        }
                        else
                        {
                            $responsibility_completed = 'yes';
                        }
                    }
                    
                    if ($order_status_row['responsibility_completed'] !== $responsibility_completed)
                    {
                        $this->_CI->ordersModel->saveOrderStatus(array('id' => $order_status_row['id'], 'responsibility_completed' => $responsibility_completed));
                    }
                }
                
                /* Auto deletion of dangling statuses (booking_attended_by_driver and collection_attended_by_driver) is to be confirmed.
                 * 
                //If delivery date is changed and meanwhile some driver scanned the order, take corrective measures
                if ($array['delivery_date_wo_time'] !== $old_delivery_date[0] || $array['collection_date_wo_time'] != $old_collection_date)
                {
                    if ($order_status_row['status'] == 'booking_attended_by_driver' || $order_status_row['status'] === 'collection_attended_by_driver')
                    {
                        if ($order_status_row['responsibility_completed'] == 'no')
                        {
                            $this->_CI->ordersModel->removeOrderStatusesForOrderCancellation($order_status_row);
                            
                            //Now have to make status active for previous status
                            $order_status_row = $this->_CI->ordersModel->getOrderStatusDetails($array['order_id']);
                            $this->_CI->ordersModel->saveOrderStatus(array('id' => $order_status_row['id'], 'active' => 'yes'));
                        }
                    }
                }
                 */
                
                $order_array['id'] = $array['order_id'];
                $order_array['updated_by'] = $this->_user_id;
                
                if(!empty($array['save_redelivery_data']))
                {
                    if(!empty($array['redel_orig_box_qty']))
                    {
                        foreach ($array['redel_orig_box_qty'] as $redel_box_id => $redel_info_str)
                        {
                            list($redel_quantity, $redel_code_id, $redel_location_id, $redel_kabupaten_id, 
                                    $redel_price_per_unit, $redel_total_price) = explode('@@##@@', $redel_info_str);
                            $redelArray = array(
                                'box_id' => $redel_box_id,
                                'quantity' => $redel_quantity,
                                'code_id' => $redel_code_id,
                                'location_id' => $redel_location_id,
                                'kabupaten_id' => $redel_kabupaten_id,
                                'price_per_unit' => $redel_price_per_unit,
                                'total_price' => $redel_total_price,
                                'order_id' => $array['order_id'],
                            );
                            $this->_CI->ordersModel->saveRedelOrigQuantity($redelArray);
                        }
                    }
                    
                }
            }
            
            $order_save_status = 'success';
            
            $order_id = $this->_CI->ordersModel->saveOrder($order_array);
            
            if($order_id === 'error')
            {
                $order_save_status = 'error';
            }
            else
            {
                
                //update promo code usage left
                if(isset($array['promocode_id']))
                {
                    $promocode_id = $array['promocode_id'];
                    
                    $getOrderPromoCodeId = $this->_CI->ordersModel->getOrderPromoCodeId($order_id);
                    if($getOrderPromoCodeId)
                    {
                        $order_promocode_id = $getOrderPromoCodeId['promocode_id'];
                    } 
                    
                    $update_usage_left = "yes";
                    if(isset($order_promocode_id) && $order_promocode_id != '0')
                    {
                        if($order_promocode_id != $promocode_id)
                        {
                            $update_usage_left = "yes";
                        }
                        else
                        {
                            $update_usage_left = "no";
                        }    
                    } 
                    
                    if($update_usage_left == "yes")
                    {
                        $promoCodeDetailsById = $this->_CI->ordersModel->getPromotionById($promocode_id);  
                        if($promoCodeDetailsById)
                        {
                        
                            if(isset($promoCodeDetailsById['usage_left']) && $promoCodeDetailsById['usage_left'] !='-1')
                            {
                                $updateUsageLeft =  $promoCodeDetailsById['usage_left'] - 1; 

                                $promoCodeUsageLeftArr = array(
                                    'id' => $promocode_id,
                                    'usage_left' => $updateUsageLeft,
                                );
                                
                                //update promo active status by multiple usage
                                $multiple_usage = $promoCodeDetailsById['multiple_usage'];
                                 
                                
                                
                                $updateUsageLeft = $this->_CI->ordersModel->updateUsageLeft($promoCodeUsageLeftArr, $multiple_usage);
                            }
                        }
                    }
                }
                
                if (empty($array['order_id']))
                {
                    $order_status_trans = array(
                        'status' => self::ORDER_INITIAL_STATUS,
                        'order_id' => $order_id,
                        'active' => 'yes',
                        'responsibility_completed' => 'no',
                        'employee_id' => $this->_user_id,
                        'updated_at' => date('Y-m-d H:i:s'),
                        'created_at' => date('Y-m-d H:i:s'),
                    );
                    $this->_CI->ordersModel->saveOrderStatus($order_status_trans);
                }
                else
                {
                    $delete_condition = array(
                        'order_id' => $order_id
                    );
                    $this->_CI->ordersModel->deleteOrderDetails($delete_condition);
                    $this->_CI->ordersModel->deleteOrderCodeDetails($delete_condition);
                }

                $order_details_array = $order_code_details  = array(
                                                                    'order_id' => $order_id
                        );
  
                foreach ($array['code_items_count'] as $index => $count)
                { 
                    list ($location_id, $location) = explode('_#_', $array['locations_selected'][$index]);
                    $kabupaten_id = $array['kabupatens_selected'][$index];
                        
                    for ($i = 0; $i < $count; $i++)
                    {
                        $box = array_shift($array['boxes']);
                        $quantity = array_shift($array['quantity']);
                        $price_per_unit = array_shift($array['prices']);

                        list ($box_id, $box) = explode('_#_', $box);
                        
                        if(isset($array['promocode_id']))
                        {
                            if(isset($array['promoBoxesArr']))
                            {
                                $promoBoxes = explode(',', $array['promoBoxesArr']);
                                if(in_array($box_id, $promoBoxes))
                                {
                                    $order_details_array['promocode_id'] = $array['promocode_id'];
                                }
                                else
                                { 
                                    $order_details_array['promocode_id'] = null;
                                }
                            }
                        }
                        
                        $order_details_array['location_id'] = $location_id;
                        $order_details_array['kabupaten_id'] = $kabupaten_id;
                        $order_details_array['box_id'] = $box_id;
                        $order_details_array['price_per_unit'] = $price_per_unit;
                        $order_details_array['quantity'] = $quantity;
                        $order_details_array['total_price'] = $quantity * $price_per_unit;
                        $order_details_array['code_id'] = $array['codes'][$index];

                        $this->_CI->ordersModel->saveOrderDetails($order_details_array);
                    }

                    $order_code_details['code_id'] = $array['codes'][$index];
                    $order_code_details['kabupaten_id'] = $kabupaten_id;
                    $order_code_details['location_id'] = $location_id;
                    $this->_CI->ordersModel->saveOrderCodeDetails($order_code_details );
                }
            }
        }

        $return = array(
            'order_id' => $order_id,
            'order_number' => $order_number,
            'anticipated_order_number' => isset($order_array['order_number']) ? $order_array['order_number'] : $order_number,
            'status' => $order_save_status,
            'mode' => $mode,
        );
        return $return;
    }

    public function getCodes()
    {
        $result = array();
        
        $this->_CI->load->model('admin/ordersModel');
        $result = $this->_CI->ordersModel->getAllCodes();
        
        return $result;
    }
    
    public function getOrderStatusesByDate($where)
    {
        $return = array();
        
        $this->_CI->load->model('admin/ordersModel');
        $result = $this->_CI->ordersModel->getOrderStatusesByDate($where);
        
        $statuses = $this->_CI->config->item('consolidated_statuses');
            
        if (!empty($statuses))
        {
            foreach ($statuses as $status => $row)
            {
                $return[$status]= 0;
            }
        }

        if (!empty($result))
        {
            $old_order_id = 0;
            
            foreach ($result as $index => $row)
            {
                if (empty($where['cnt_criteria']))
                {
                    $return[$row['status']] ++;
                }
                else
                {
                    $return[$row['status']] += $row['total_boxes'];
                }

                $old_order_id = $row['order_id'];
            }
        }
        
        return $return;
    }

    public function getOrderDetails($order_id)
    {
        $result = array();
        
        $this->_CI->load->model('admin/ordersModel');
        
        $result['order'] = $this->_CI->ordersModel->getOrderDetails($order_id);
        $result['order_trans'] = $this->_CI->ordersModel->getOrderTransDetails($order_id);
        $result['order_code_trans'] = $this->_CI->ordersModel->getOrderCodeTransDetails($order_id);
        
        foreach($result['order_trans'] as $idx => $val)
        {
            if(isset($val['promocode_id']) && $val['promocode_id'] > 0)
            {
                $promocode_id = $val['promocode_id'];
            }
        }
        
        if(isset($promocode_id))
        {
            $promoCodeDetailsById = $this->_CI->ordersModel->getPromotionById($promocode_id); 
            $result['promocode_data'] = $promoCodeDetailsById;
        } 
        return $result;
    }

    public function getOrderCodeDetails($dataArray)
    {
        $data = array();
        if (!empty($dataArray['order_details']['order_code_trans']))
        { 
            $this->_CI->load->model('admin/mastersModel');

            $where_data = array(
                'order_trans.order_id' => $dataArray['order_id']
            );
            foreach ($dataArray['order_details']['order_code_trans'] as $index => $row)
            {
                $where_data['code_id'] = $row['code_id'];
                $code_arr[] = array(
                                'code_id' => $row['code_id'],
                                'code' => $row['code'],
                                'kabupatens' => $this->_CI->mastersModel->getKabupatensByLocationId($row['location_id']),
                                'results' => $this->_CI->mastersModel->getOrderCodeBoxesDetails($where_data)
                        );
            } 
            
            $data['codes_arr'] = $code_arr;
            $data['boxes'] = $this->_CI->mastersModel->getAllBoxes();
            $data['locations'] = $this->_CI->mastersModel->getAllLocations();
        } 
        
        return $data;
    }

    public function getBatchOrderDetails($delivery_date)
    {
        $result = array();
        
        $this->_CI->load->model('admin/ordersModel');
        
        $results = $this->_CI->ordersModel->getBatchOrderDetails($delivery_date);
        if (!empty($results))
        {
            foreach ($results as $index => $row)
            {
                $result[] = array(
                    'order' => $row,
                    'order_trans' => $this->_CI->ordersModel->getOrderTransDetails($row['id']),
                    'order_code_trans' => $this->_CI->ordersModel->getOrderCodeTransDetails($row['id'])
                );
            }
        }
//        p($result);
        return $result;
    }

    public function getBatchOrderForms($order_ids)
    {
        $result = array();
        
        $this->_CI->load->model('admin/ordersModel');
        
        $results = $this->_CI->ordersModel->getBatchOrderDetails(null, $order_ids);
        if (!empty($results))
        {
            foreach ($results as $index => $row)
            {
                $result[] = array(
                    'order' => $row,
                    'order_trans' => $this->_CI->ordersModel->getOrderTransDetails($row['id']),
                    'order_code_trans' => $this->_CI->ordersModel->getOrderCodeTransDetails($row['id'])
                );
            }
        }
//        p($result);
        return $result;
    }
    
    public function getAllRoles()
    {
        $result = array();
        
        $this->_CI->load->model('admin/mastersModel');
        
        $results = $this->_CI->mastersModel->getAllRoles();
        $result['0'] = 'Select Role';
        if (!empty($results))
        {
            foreach ($results as $index => $row)
            {
                   $result[$row['id']] = $row['RoleName'];              
            }
        }
//        p($result);
        return $result;
    }
    
     public function getOrderStatusesByDateJkt($where=null)
    {
        $return = array();

        $statuses = $this->_CI->config->item('jakarta_statuses');
        $jakarta_statuses = array_keys($statuses);
        
        $this->_CI->load->model('admin/ordersModel');
        $result = $this->_CI->ordersModel->getOrderStatusesByDateJkt($where, $jakarta_statuses);
            
        if (!empty($statuses))
        {
            foreach ($statuses as $status => $row)
            {
                $return[$status]= 0;
            }
        }

        if (!empty($result))
        {
            $old_order_id = 0;
            
            foreach ($result as $index => $row)
            {
                if ($old_order_id !== $row['order_id'])
                {
                    if(empty($where['cnt_criteria']))
                    {
                       $return[$row['status']]++;
                    }
                    else
                    {
                       $return[$row['status']] += $row['total_boxes'];
                    }
                }
                
                $old_order_id = $row['order_id'];
            }
        }
        
        return $return;
    }
}
