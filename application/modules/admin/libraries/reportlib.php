<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ReportLib
{

    private $_CI;
    
    public function __construct()
    {
        $this->_CI = & get_instance();
        $this->_CI->load->model('admin/reportsModel');
    }

    public function getDeliveryRunSheet($date_from, $date_to, $driver_ids)
    {
        $result = array();
        
        $result = $this->_CI->reportsModel->getDeliveryRunSheet($date_from, $date_to, $driver_ids);

        if (!empty($result))
        {
            foreach ($result as $index => $row)
            {
                $row['username'] = trim($row['username']);
                if (empty($driver_ids))
                {
                    if (!empty($row['username']))
                    {
                        
                        $this->_CI->load->model('UserModel');
                        
                        $statuses = explode(',', $row['username']);
                        
                        $row['username'] = '';
                        
                        foreach ($statuses as $status)
                        {
                            list($temp_empid, $temp_status) = explode('@@##@@', $status);
                            
                            if ($temp_status == 'booking_attended_by_driver')
                            {
                                $userRow = $this->_CI->UserModel->getUserById($temp_empid);
                                $row['username'] = $userRow->username;
                                break;
                            }
                        }
                    }
                }
                
                $delivery_date = $row['delivery_date'];
                list($year, $month, $day) = explode('-', $delivery_date);
                $row['delivery_date'] = "$day/$month/$year";
                
                $row['building'] = trim($row['building']);
                $row['unit'] = trim($row['unit']);
                $building = empty($row['building']) ? '' : "{$row['building']}, ";
                $unit = empty($row['unit']) ? '' : "{$row['unit']}, ";
                
                $row['address'] = $row['block']. ' | '. $row['street'].  '<br>'.   $unit. $building. $row['pin'];
                
                $row['contacts'] = $row['mobile'].  "<br/>{$row['residence_phone']}";
                $result[$index] = $row;
            }
        }

        return $result;
    }

    public function getCollectionRunSheet($date_from, $date_to, $driver_ids)
    {
         $result = array();
        
        $result = $this->_CI->reportsModel->getCollectionRunSheet($date_from, $date_to, $driver_ids);
        
        if (!empty($result))
        {
            foreach ($result as $index => $row)
            {   
                $row['username'] = trim($row['username']);
                if (empty($driver_ids))
                {
                    if (!empty($row['username']))
                    {
                        
                        $this->_CI->load->model('UserModel');
                        
                        $statuses = explode(',', $row['username']);
                        
                        $row['username'] = '';
                        
                        foreach ($statuses as $status)
                        {
                            list($temp_empid, $temp_status) = explode('@@##@@', $status);
                            
                            if ($temp_status == 'collection_attended_by_driver')
                            {
                                $userRow = $this->_CI->UserModel->getUserById($temp_empid);
                                $row['username'] = $userRow->username;
                                break;
                            }
                        }
                    }
                }
                
                
                $row['building'] = trim($row['building']);
                $row['unit'] = trim($row['unit']);
                $building = empty($row['building']) ? '' : "{$row['building']}, ";
                $unit = empty($row['unit']) ? '' : "{$row['unit']}, ";
                
                $row['address'] = $row['block']. ' | '. $row['street'].  '<br>'.   $unit. $building. $row['pin'];
                
                $row['contacts'] = $row['mobile'].  "<br/>{$row['residence_phone']}";
                $result[$index] = $row;
            }
        }
        return $result;
    }

    public function getLiveFeeds($where, $ordering_criteria_flag)
    {
        $return = array();
        
        $result = $this->_CI->reportsModel->getLiveFeeds($where, $ordering_criteria_flag);
//        p($result, 0);
        if (!empty($result))
        {
            $statuses = $this->_CI->config->item('consolidated_statuses');
                
            $previous_order_id = 0;
            
            foreach ($result as $index => $row)
            {
               if ($previous_order_id != $row['order_id'])
               {
                    $status = $row['status'];
                    $row['color'] = $statuses[$status]['label_color'];
                    $row['font_color'] = $statuses[$status]['font_color'];
                    $row['display_text'] = $statuses[$status]['display_text'];

                    $building = empty($row['building']) ? '' : "{$row['building']} <br>";
                    $address = "$building{$row['block']}, {$row['unit']} <br>{$row['street']} <br>{$row['pin']}";

                    $row['address'] = $address;

                    if (!empty($row['d_and_c']))
                    {
                        $row['order_number'] = "{$row['order_number']}<br/><br/><span class='blink_me'><b>D&C</b></span>";
                    }

                    if ($row['status_escalation_type'] == 'manual')
                    {
                        $row['display_text'] = "{$row['display_text']}<br/><br/><span class='blink_me'><b>Manual Esc.</b></span>";
                    }
                   
                    if ($row['coordinates_type'] == 'google')
                    {
                        $row['driver'] = "{$row['driver']}<br/><br/><span class='blink_me'><b>Google Coords<br>Used</b></span>";
                    }
                    
                    if ($row['qr_manual_entry'] == true)
                    {
                        $row['driver'] = "{$row['driver']}<br/><br/><span class='blink_me'><b>Entered Order # Manually</b></span>";
                    }
                    $return[] = $row;
               }
               
               $previous_order_id = $row['order_id'];
            }
        }

//        p($return);
        
        return $return;
    }

    public function getQRCodes($date)
    {
        $return = array();
        
        $where = array(
            'DATE(delivery_date)' => $date
        );

        $this->_CI->load->model('admin/ordersModel');
        $result = $this->_CI->ordersModel->getAllOrders($where);
        
        if (!empty($result))
        {
            foreach ($result as $index => $row)
            {
                $return[$index] = array(
                    'qrcode' => base_url(). "assets/dynamic/bar_codes/". "{$row['id']}.png",
                    'order_number' => $row['order_number']
                );
            }
        }

        return $return;
    }

    public function getBatchQRCodes($order_ids)
    {
        $return = array();
        
        $where = array();

        $this->_CI->load->model('admin/ordersModel');
        $result = $this->_CI->ordersModel->getAllOrders($where, $order_ids);

        if (!empty($result))
        {
            foreach ($result as $index => $row)
            {
                $return[$index] = array(
                    'qrcode' => base_url(). "assets/dynamic/bar_codes/". "{$row['id']}.png",
                    'orderno_image' => base_url(). "assets/dynamic/order_nos/". "{$row['id']}.png",
                    'order_number' => $row['order_number'],
                    'customer_name' => $row['name'],
                    'building' => $row['building'],
                    'block' => $row['block'],
                    'unit' => $row['unit'],
                    'street' => $row['street'],
                    'pin' => $row['pin'], 
                    'orders_destination_kabupaten' => $row['destination_kabupaten'],
                );
            } 
        }  
        
        return $return;
    }

    public function getCollectionCallData($days)
    {
        $return = $dataArray = array();
        $paginparam = $_GET;

        $result = $this->_CI->reportsModel->getCollectionCallData($days, $paginparam);
        
        if($result['foundRows'] > 0)
        {
            foreach ($result['resultSet'] as $idx => $val)
            {
                if (empty($val['followedup_time']))
                {
                    $history_link = '';
                }
                else
                {
                    $history_link = "<a data-toggle='modal' data-target='#followUpHistoryModal' class='fake-followup-history-class' href='#' title='{$val['order_number']}' rel='{$val['id']}'><i class='fa  glyphicon-history'></i></a>";
                }

                $dataArray[$idx]['order_number'] = $val['order_number'];
                $dataArray[$idx]['amount'] = $val['nett_total'].' (T)<br>'.$val['total_deposit']. ' (D)';
                $dataArray[$idx]['name'] = $val['name'];
                $val['residence_phone'] = empty($val['residence_phone']) ? 'N/P' : '';
                $dataArray[$idx]['mobile'] = $val['mobile']." / ".$val['residence_phone'];

                $dataArray[$idx]['boxes'] = $val['boxes'];
                $dataArray[$idx]['quantities'] = $val['quantities'];
                $dataArray[$idx]['locations'] = $val['locations'];
                $dataArray[$idx]['kabupatens'] = $val['kabupatens'];

                $remarks = $val['remarks'];
                $remarks = explode('@@##@@', $val['remarks']);
                $remarks = array_pop($remarks);

                $dataArray[$idx]['residence_phone'] = $val['residence_phone'];
                $dataArray[$idx]['remarks'] = $remarks;

                if (!empty($val['delivery_date']))
                {
                    $delivery_date = explode(' ', $val['delivery_date']);
                    list($year, $month, $day) = explode('-', $delivery_date[0]);
                    $delivery_date = "$day/$month/$year";
                }
                else
                {
                    $delivery_date = "";
                }

                $dataArray[$idx]['delivery_date'] = $delivery_date;
                $dataArray[$idx]['address'] = $val['block']. ' '. $val['unit']. ' '. $val['building']. ' '. $val['street'];
                $dataArray[$idx]['operations'] = "
                            <a data-toggle='modal' data-target='#myModal' class='fake-followup-class' href='#' title='{$val['order_number']}' rel='{$val['id']}'><i class='fa  fa-phone'></i></a>
                            <a data-toggle='modal' data-target='#collectionDateModal' class='fake-followup-class' href='#' title='{$val['order_number']}' rel='{$val['id']}'><i class='fa  glyphicon-adress_book'></i></a>
                            $history_link
                            ";

            }
        }

        $return['iTotalRecords'] = $result['foundRows'];
        $return['iTotalDisplayRecords'] = $result['foundRows'];
        $return['sEcho'] = $paginparam['sEcho'];
        $return['aData'] = $dataArray;
        //p($return);
        return $return;
    }

    public function getOutStandingOrderPaymentData($days, $statutes)
    {
        $return = $dataArray = array();
        $paginparam = $_GET;

        $date = date("Y-m-d");
        $result = $this->_CI->ordersModel->getOutstandingOrderPayment($date, $days, $paginparam, $statutes);
        
        if($result['foundRows'] > 0)
        {
            foreach ($result['resultSet'] as $idx => $val)
            {        
                $total_cash_deposit = $val['tot_voucher_tot_cash_collected'];
                $outstanding_amount = $val['outstanding_order_payment'];
                                     
                $dataArray[$idx]['order_number'] = $val['order_number'];
                $dataArray[$idx]['collection_date'] = date('d-m-Y', strtotime($val['collection_date']));
                $dataArray[$idx]['name'] = $val['name'];
                $dataArray[$idx]['mobile'] = $val['mobile'];

                $dataArray[$idx]['boxes_name'] = $val['boxes_name'];
                $dataArray[$idx]['boxes_quantity'] = $val['boxes_quantity'];

                $dataArray[$idx]['kabupaten'] = $val['kabupaten'];

                $dataArray[$idx]['grand_total'] = $val['grand_total'];
                $dataArray[$idx]['discount'] = $val['discount'];
                $dataArray[$idx]['total_cash_collected'] = $val['total_cash_collected'];
                $dataArray[$idx]['total_cash_deposit'] = $total_cash_deposit;
                $dataArray[$idx]['outstanding_amount'] = $outstanding_amount;
            }
        } 
         
        $return['iTotalRecords'] = $result['foundRows'];
        $return['iTotalDisplayRecords'] = $result['foundRows'];
        $return['sEcho'] = $paginparam['sEcho'];
        $return['aData'] = array_values($dataArray);
        return $return;
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
                'unit' => $array['unit'],
                'agent_id' => $array['agent_id'],
                'comments' => $array['comments'],
                'order_number' => $order_number,
                'raw_order_number' => $raw_order_number,
                
                'delivery_date' => $array['delivery_date'],
                'collection_date' => $array['collection_date'],
                'order_date' => $array['order_date'],
                
                'delivery_pin' => $array['delivery_pin'],
                'delivery_block' => $array['delivery_block'],
                'delivery_street' => $array['delivery_street'],
                'delivery_unit' => $array['delivery_unit'],
                'delivery_building' => $array['delivery_building'],
                'delivery_recipient_name' => $array['delivery_recipient_name'],
                'delivery_recipient_mobile' => $array['delivery_recipient_mobile'],
                'delivery_recipient_phone' => $array['delivery_recipient_phone'],
                
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            );
            $order_id = $this->_CI->ordersModel->saveOrder($order_array);
            
            $order_status_trans = array(
                'status' => self::ORDER_INITIAL_STATUS,
                'order_id' => $order_id,
                'employee_id' => 2,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            );
            $this->_CI->ordersModel->saveOrderStatus($order_status_trans);
            
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
                    
                    $order_details_array['location_id'] = $location_id;
                    $order_details_array['kabupaten_id'] = $kabupaten_id;
                    $order_details_array['box_id'] = $box_id;
                    $order_details_array['price_per_unit'] = $price_per_unit;
                    $order_details_array['quantity'] = $quantity;
                    $order_details_array['total_price'] = $quantity * $price_per_unit;
                    
                    $this->_CI->ordersModel->saveOrderDetails($order_details_array);
                }
                
                $order_code_details['code_id'] = $array['codes'][$index];
                $order_code_details['kabupaten_id'] = $kabupaten_id;
                $order_code_details['location_id'] = $location_id;
                $this->_CI->ordersModel->saveOrderCodeDetails($order_code_details );
            }
        }

        $return = array(
            'raw_order_number' => $raw_order_number,
            'order_id' => $order_id,
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

    public function getOrderDetails($order_id)
    {
        $result = array();
        
        $this->_CI->load->model('admin/ordersModel');
        
        $result['order'] = $this->_CI->ordersModel->getOrderDetails($order_id);
        $result['order_trans'] = $this->_CI->ordersModel->getOrderTransDetails($order_id);
        $result['order_code_trans'] = $this->_CI->ordersModel->getOrderCodeTransDetails($order_id);
        
        return $result;
    }
    
    public function saveCallFollowUp($dataArray)
    {
        $this->_CI->reportsModel->updateCallFollowUpStatus($dataArray['order_id']);

        $result = $this->_CI->reportsModel->saveCallFollowUp($dataArray);
        
        return $result;
    }
    
    public function saveCollectionDate($dataArray)
    {
        $this->_CI->load->model('admin/ordersModel');
        $result = $this->_CI->ordersModel->saveOrder($dataArray);

        return $result;
    }
    
    public function getEODReportsData($date)
    {
        $current_date = date('Y-m-d');
        $return = $dataArray = array();
        $paginparam = $_GET;

        $user_id = $this->_CI->session->userdata['id'];
        
        $result = $this->_CI->reportsModel->getEODReportsData($date, $paginparam );

        if (!empty($result['resultSet']))
        {
            foreach ($result['resultSet'] as $idx => $val)
            {

                $data = array(
                    'date' => $date,
                    'employee_id' => $val["employee_id"],
                );
                $getCashReport = $this->getCashReport($data);  
                
                $deposit_collected = $deposit_collected_delivery = 0;
                if(!empty($getCashReport))
                {
                    foreach ($getCashReport as $getCashReport_idx => $getCashReport_val)
                    {                        
                        if (strtolower($getCashReport_idx) == 'delivery')
                        {
                            foreach ($getCashReport_val as $index => $value)
                            {
                                $deposit_collected += $value['cash_collected_delivery'];
                            }
                        }
                        else
                        {
                            foreach ($getCashReport_val as $index => $value)
                            {
                                $deposit_collected += $value['cash_collected_collection'];
                            }
                        }
                    }
                    
                    $deposit_collected = number_format($deposit_collected, 2);
                }
                
                $restore_eod_status = "";
                if ($val['status'] == 'yes')
                {
                    $cash_report = "<button class='fake-cash-report-button btn btn-primary' rel='{$val['employee_id']}'>
                                        <i class='fa glyphicon-usd'></i>Cash Report</button>";
                    $warehouse_tally_sheet = "<button class='fake-warehouse-button btn btn-primary' rel='{$val['employee_id']}'>
                                        <i class='fa glyphicon-security_camera'></i>Warehouse Tally Sheet</button>";
                    $order_details = "--";
                    
                    if(strtotime($current_date) == strtotime($date))
                    {
                        $canPerform = canPerformAction('restore_EOD_status',$user_id);
                        if($canPerform === TRUE)
                        {
                          $restore_eod_status = "<a href='".base_url()."admin/report/restoreEODStatus/".$val['id']."/".$val['name']."' onClick=\"javascript:return confirm('Are you sure you want to revert EOD status?');\">
                                              <button class='btn btn-primary' ><i class='fa glyphicon glyphicon-remove'></i></button></a>";
                        }
                    }
                    
                            
                }
                else
                {
                    $warehouse_tally_sheet = '--';
                    $cash_report = '--';
                    $order_details = "<button class='order-details btn btn-primary' rel='{$val['employee_id']}'>
                                        <i class='fa glyphicon-list'></i></button>";
                    
                }
                
                $dataArray[$idx]['s_no'] = $idx+1;
                $dataArray[$idx]['driver_name'] = $val['name'];
                $dataArray[$idx]['eod_status'] = ucwords($val['status'])." ".$restore_eod_status;
                $dataArray[$idx]['order_details'] = $order_details;
                $dataArray[$idx]['warehouse_tally_sheet'] = $warehouse_tally_sheet;
                $dataArray[$idx]['deposit_collected'] = $deposit_collected;
                $dataArray[$idx]['cash_report'] = $cash_report;
            }
        }
        else
        {
            $result['resultSet'] = array();
        }

        $return['iTotalRecords'] = $result['foundRows'];
        $return['iTotalDisplayRecords'] = $result['foundRows'];
        $return['sEcho'] = $paginparam['sEcho'];
        $return['aData'] = $dataArray;
        //p($return);
        return $return;
    }
    public function getWarehouseTallySheet($where)
    {
        $result = $this->_CI->reportsModel->getWarehouseTallySheet($where);
        
        $return = array();
        
        if (!empty($result))
        {
            $status_type_group = $this->_CI->config->item('status_type_group');

            foreach ($result as $index => $row)
            {
                $type = null;
                    
                if (in_array ($row['order_status'], $status_type_group['delivery']))
                {
                    $type = 'delivery';
                }
                else if (in_array ($row['order_status'], $status_type_group['collection']))
                {
                    $type = 'collection';
                }
                
                if (!empty($type))
                {
                    $return[$type][] = $row;
                }
            }
        }
        return $return;
    }
    
    public function getCashReport($where)
    {
        $return = array();
        
        $result = $this->_CI->reportsModel->getCashReport($where);
        
        if (!empty($result))
        {
            $statuses = $this->_CI->config->item('statuses');
            $status_type_group = $this->_CI->config->item('status_type_group');
            
            foreach ($result as $index => $row)
            {
                if (in_array ($row['status'], $status_type_group['delivery']))
                {
                    $type = 'delivery';
                }
                else if (in_array ($row['status'], $status_type_group['collection']))
                {
                    $type = 'collection';
                }

//                $type = $row['dnc'] == 1 ? 'collection' : $type;

                $row['status'] = $statuses[$row['status']]['display_text'];

                $return[$type][] = $row;
            }
            
            krsort( $return);
        }
        
        return $return;
    }
    
    public function getShipmentBatchData($shipment_id)
    {
        $this->_CI->load->model('admin/reportsModel');
        $data = $this->_CI->reportsModel->getShipmentBatchOrderData($shipment_id);
        
        if(!empty($data))
        {
            $shipmentData = array();
            foreach($data as $idx => $rec)
            {
                $shipmentData[$idx]['count'] = $rec['count'];
                $shipmentData[$idx]['order_number'] = $rec['order_number'];
                $shipmentData[$idx]['passport_id_number'] = $rec['customer_passport_id_number'];
                $shipmentData[$idx]['customer_name'] = $rec['customer_name'];
                $shipmentData[$idx]['customer_building_street'] = $rec['customer_building']." ".$rec['customer_street'];
                $shipmentData[$idx]['customer_mobile'] = $rec['customer_mobile'];
                $shipmentData[$idx]['box'] = $rec['boxes'];
                $shipmentData[$idx]['destination'] = $rec['locations'];
                $shipmentData[$idx]['quantity'] = $rec['quantities'];
                $shipmentData[$idx]['location'] = $rec['kabupatens'];
//                $shipmentData[$idx]['recipient_name'] = $rec['jkt_receiver'];
                $shipmentData[$idx]['recipient_name'] = $rec['recipient_name'];
                $shipmentData[$idx]['recipient_item_list'] = $rec['recipient_item_list'];
                $building_street = preg_split('/[\s,]+/',$rec['recipient_address'], 2);
                $shipmentData[$idx]['building_street'] =  $rec['recipient_address'];
                $shipmentData[$idx]['recipient_mobile'] = $rec['recipient_mobile'];
                $batch = $rec['batch'];
                $ship_onboard = $rec['ship_onboard'];
                $container_number = $rec['container_number'];
                $seal_number = $rec['seal_number'];
                $bl_number = $rec['bl_number'];
            }
            
            $shipmentData = array('records' => $shipmentData, 'batch' => $batch ,'ship_onboard' => $ship_onboard ,"container_number" => $container_number,"seal_number" => $seal_number, "bl_number" => $bl_number);
        }
        else
        {
            $shipmentData = array();
        }
        return $shipmentData;
    } 
    
    public function getEODOrderDetails($employee_id,$date)
    {
        $data = array('employee_id' => $employee_id);
        $resultData = determineEODStatus($data, 'resultset', $date);
        $this->_CI->load->model('admin/ordersModel');
        if(!empty($resultData))
        {
            $return = array();
            foreach($resultData as $idx => $rec)
            {
                $orderDetails = $this->_CI->ordersModel->getOrderDetails($rec['order_id']);                
                if(!empty($orderDetails))
                {
                  $return[$idx]['order_number'] = $orderDetails['order_number'];
                  $return[$idx]['customer_name'] = $orderDetails['customer_name'];
                  $return[$idx]['address'] = $orderDetails['block'].",". $orderDetails['unit']."<br>,". $orderDetails['street']. $orderDetails['customer_pin'];
                 
                }
            }
        }
        else
        {
            $return = array();
        }
        return $return;
    }
    
    public function getAgentCommission(array $params)
    {
        $this->_CI->load->model('admin/ordersModel');
        $resultData = $this->_CI->ordersModel->getAgentCommission($params);
        
        if(!empty($resultData))
        {
            $return = array();
            foreach($resultData as $idx => $rec)
            {
                $orderDetails = $this->_CI->ordersModel->getOrderDetails($rec['order_id']);                
                if(!empty($orderDetails))
                {
                  $return[$idx]['order_number'] = $orderDetails['order_number'];
                  $return[$idx]['customer_name'] = $orderDetails['customer_name'];
                  $return[$idx]['address'] = $orderDetails['block'].",". $orderDetails['unit']."<br>,". $orderDetails['street']. $orderDetails['customer_pin'];
                 
                }
            }
        }
        else
        {
            $return = array();
        }
        return $return;
    }
    
   public function getLiveFeedsJkt($where, $ordering_criteria_flag)
    {
        $return = array();
                
        $statuses = $this->_CI->config->item('jakarta_statuses');
        $jakarta_statuses = array_keys($statuses);
        
        $result = $this->_CI->reportsModel->getLiveFeedsJkt($where, $ordering_criteria_flag, $jakarta_statuses);
//        p($result, 0);
        if (!empty($result))
        {
                
            $previous_order_id = 0;
            
            foreach ($result as $index => $row)
            {
               if ($previous_order_id != $row['order_id'])
               {
                    $status = $row['status'];
                    $row['color'] = $statuses[$status]['label_color'];
                    $row['font_color'] = $statuses[$status]['font_color'];
                    $row['display_text'] = $statuses[$status]['display_text'];

                    $building = empty($row['building']) ? '' : "{$row['building']} <br>";
                    $address = "$building{$row['block']}, {$row['unit']} <br>{$row['street']} <br>{$row['pin']}";

                    $row['address'] = $address;
                   
                    $return[] = $row;
               }
//               p($row);
               $previous_order_id = $row['order_id'];
            }
        }

//        p($return);
        
        return $return;
    } 
    
    public function getReceivingBatchData($receiving_batch_id)
    {
        $this->_CI->load->model('admin/reportsModel');
        $data = $this->_CI->reportsModel->getReceivingBatchOrderData($receiving_batch_id);
        
        if(!empty($data))
        {
            $shipmentData = array();
            foreach($data as $idx => $rec)
            {
                $shipmentData[$idx]['count'] = $rec['count'];
                $shipmentData[$idx]['order_number'] = $rec['order_number'];
                $shipmentData[$idx]['customer_name'] = $rec['customer_name'];
                $shipmentData[$idx]['box'] = $rec['boxes'];
                $shipmentData[$idx]['quantity'] = $rec['quantities'];
                $shipmentData[$idx]['location'] = $rec['kabupatens'];
                $shipmentData[$idx]['recipient_name'] = $rec['recipient_name'];
                $shipmentData[$idx]['recipient_item_list'] = $rec['recipient_item_list'];
                $batch = $rec['batch'];
            }
            
            $shipmentData = array('records' => $shipmentData, 'batch' => $batch);
        }
        else
        {
            $shipmentData = array();
        }
        return $shipmentData;
    } 
    
    
    
    public function getDriverCollectionSheet($date_from, $date_to, $driver_ids)
    {
        $result = array();
        
        $result = $this->_CI->reportsModel->getDriverCollectionSheet($date_from, $date_to, $driver_ids);
//        p($result,0);
        $headerArr = array();
        $dateArr = array();
        $boxArr = array();
        $responseArr = array();
        if (!empty($result))
        {
            foreach ($result as $index => $row)
            {
                $collection_date = $row['collection_date'];
                list($year, $month, $day) = explode('-', $collection_date);
                $row['collection_date'] = "$day/$month/$year";
                $date = "$day/$month/$year";
               // header arr 
                $headerArr[$row['id']]['name'] = $row['username'];
                // date arr
                if(isset($dateArr[$date][$row['id']]['box'][$row['box']]))
                {
                    $dateArr[$date][$row['id']]['box'][$row['box']] += $row['count'];
                }
                else
                {
                    $dateArr[$date][$row['id']]['box'][$row['box']] = $row['count'];
                }
                    
                //box Arr
                if(isset($boxArr[$row['box']][$row['id']]))
                $boxArr[$row['box']][$row['id']] += $row['count'];
                else
                $boxArr[$row['box']][$row['id']] = $row['count'];
                    
            }
            
        }
//        p($headerArr,0);
//        p($boxArr,0);
//        p($dateArr);
        ksort($dateArr);
        ksort($boxArr);
        $responseArr['header'] = $headerArr;
        $responseArr['boxData'] = $boxArr;
        $responseArr['dateData'] = $dateArr;
        return $responseArr;
    }
    
    
      public function getDestBoxesReports($date_from, $date_to, $shipment_ids)
    {
        $result = array();
        
        $box_data = $this->_CI->reportsModel->getDestBoxesReports($date_from, $date_to, $shipment_ids);
        if(!empty($box_data))
        {
            $box_header = array();
            foreach($box_data as $idx => $value)
            {
                $box_header[$value['box_id']] = $value['box_name'];
                $shipment_data_box[$value['shipment_batch_id']][$value['box_id']] = $value;
                $shipment_data_box[$value['shipment_batch_id']]['batch_name'] = $value['batch'];
                $shipment_data_box[$value['shipment_batch_id']]['container_type'] = $value['container_type'];
                
            }
            
            $box_data['box_header'] = $box_header;
            $box_data['shipment_data_box'] = $shipment_data_box;
            
        }
        
        $box_location_data = array();
        
        $box_location_rec = $this->_CI->reportsModel->getDestBoxesLocationReports($date_from, $date_to, $shipment_ids);
        if(!empty($box_location_rec))
        {
            foreach($box_location_rec as $idx => $value)
            {
                if (empty($box_location_data[$value['location_id']][$value['box_id']]))
                {
                    $box_location_data[$value['location_id']][$value['box_id']] = $value['boxes_count'];
                }
                else
                {
                    $box_location_data[$value['location_id']][$value['box_id']] += $value['boxes_count'];
                }
            }            
        }
        
        $location_data = $this->_CI->reportsModel->getDestLocationReports($date_from, $date_to, $shipment_ids);
        if(!empty($box_data))
        {
            $location_header = array();
            foreach($location_data as $idx => $value)
            {
                $location_header[$value['location_id']] = $value['location_name'];
                $shipment_data_location[$value['shipment_batch_id']][$value['location_id']] = $value;
                $shipment_data_location[$value['shipment_batch_id']]['batch_name'] = $value['batch'];
                $shipment_data_location[$value['shipment_batch_id']]['container_type'] = $value['container_type'];
                
            }
            
            $location_data['location_header'] = $location_header;
            $location_data['shipment_location_data'] = $shipment_data_location;
            
        }
        
        $result['box_data'] = $box_data;
        $result['location_data'] = $location_data;
        $result['box_location_data'] = $box_location_data;
        return $result;
        
    }
    
    public function getDeliveredBoxesReports($date_from, $date_to, $shipment_batch_ids, $exclude_boxes_id, $exclude_location_id)
    {
        $result = array();
        $box_data = $this->_CI->reportsModel->getDeliveredBoxesReports($date_from, $date_to, $shipment_batch_ids, $exclude_boxes_id, $exclude_location_id);
        $result['box_data'] = $box_data;
        
        return $result;
    }
    
    public function getdeliveredReports($received,$receiving_batch_id,$search_by_destination_id,$box_no,$temp_date_from, $temp_date_to)
    {
        $result = array();
        $box_data = $this->_CI->reportsModel->getdeliveredReports($received,$receiving_batch_id,$search_by_destination_id,$box_no,$temp_date_from, $temp_date_to);
        $result['box_data'] = $box_data;
        
        return $result;
    }
    
    public function getWeeklyCollectionReports($date_from, $date_to)
    {
        $result = array();
        $result = $this->_CI->reportsModel->getWeeklyCollectionReportsData($date_from, $date_to);
        $data = array();
        $box_header = array();
        if(!empty($result))
        {
            foreach($result as $idx => $row)
            {
                $box_header[$row['box_id']] = $row['box'];
                $data[$row['collection_date']][$row['box_id']] = $row['box_count'];                
            }
            $result['data'] = $data; 
            $result['box_header'] = $box_header;
        }        
        return $result;
    }
    
    public function getDepositsUncollectedReports($date_from, $date_to, $driver_ids)
    {
        $result = array();
        $result = $this->_CI->reportsModel->getDepositsUncollectedReports($date_from, $date_to, $driver_ids);
        if(!empty($result))
        {
            foreach($result as $index => $row)
            {
                $row['building'] = trim($row['building']);
                $row['unit'] = trim($row['unit']);
                $building = empty($row['building']) ? '' : "{$row['building']}, ";
                $unit = empty($row['unit']) ? '' : "{$row['unit']}, ";
                
                $row['address'] = $row['block']. ' | '. $row['street'].  '<br>'.   $unit. $building. $row['pin'];
                $result[$index] = $row;
            }
        }
        return $result;
    }
    
    
    public function getDriverDeliverySheet($date_from, $date_to, $driver_ids)
    {
        $result = array();
        $result = $this->_CI->reportsModel->getDriverDeliverySheet($date_from, $date_to, $driver_ids);
        $headerArr = array();
        $dateArr = array();
        $boxArr = array();
        $responseArr = array();
        if (!empty($result))
        {
            foreach ($result as $index => $row)
            {
                $delivery_date = $row['delivery_date'];
                list($year, $month, $day) = explode('-', $delivery_date);
                $row['delivery_date'] = "$day/$month/$year";
                $date = "$day/$month/$year";
               // header arr 
                $headerArr[$row['id']]['name'] = $row['username'];
                // date arr
                $dateArr[$date][$row['id']]['box'][$row['box']] = $row['count'];
                //box Arr
                if(isset($boxArr[$row['box']][$row['id']]))
                $boxArr[$row['box']][$row['id']] += $row['count'];
                else
                $boxArr[$row['box']][$row['id']] = $row['count'];
                    
            }
            
        }
//        p($dateArr,0);
        ksort($dateArr);
        ksort($boxArr);
//        p($dateArr);
        $responseArr['header'] = $headerArr;
        $responseArr['boxData'] = $boxArr;
        $responseArr['dateData'] = $dateArr;
        return $responseArr;
    }
    
    public function getDeliveryPerformanceJkt($shipment_batch_ids)
    {
        $result = array();
        $result = $this->_CI->reportsModel->getDeliveryPerformanceJkt($shipment_batch_ids);
        $location_header = array();
        $shipment_data = array();
        if(!empty($result))
        {
        foreach($result as $idx => $value)
        {
            if(!empty($value['location_id']))
            {
                $location_header[$value['location_id']] = $value['location'];
            }    
            
            if(!empty($value['shipment_id']))
            {
                $shipment_data[$value['shipment_id']]['shipment_batch_name'] = $value['batch_name'];
                $shipment_data[$value['shipment_id']][$value['location_id']] = $value;
                
            }
            $data['location_header'] = $location_header;
            $data['shipment_data'] = $shipment_data;
            $result = $data;
        }
            
        }
        return $result;
    }
    
    
     public function getShipmentOrdersWeighReportsJkt($shipment_batch_ids)
    {
        $shipmentBatchOrderData = array();
        if (!empty($shipment_batch_ids))
        {
            $result = $this->_CI->reportsModel->getShipmentOrdersDataAtJakartaSide($shipment_batch_ids);
          
            if (!empty($result))
            {
                foreach ($result as $index => $orderDetails)
                {
                    $received_date = $orderDetails['jkt_received_date'];
                    $jkt_weight = $orderDetails['jkt_weight'];
                    $weight = $orderDetails['weight'];
                    
                    if($received_date == "0000-00-00")
                    {
                        $received_date = "N/A";
                    }
                    $shipmentBatchOrderData[$index]['batch_name'] = $orderDetails['batch_name'];
                    $shipmentBatchOrderData[$index]['order_number'] = $orderDetails['order_number'];
                    $shipmentBatchOrderData[$index]['quantities'] = $orderDetails['quantities'];
                    $shipmentBatchOrderData[$index]['customer_name'] = $orderDetails['customer_name'];
                    $shipmentBatchOrderData[$index]['locations'] = $orderDetails['locations'];
                    $shipmentBatchOrderData[$index]['kabupatens'] = $orderDetails['kabupatens'];
                    $shipmentBatchOrderData[$index]['weight'] = empty($weight) ? 0.00 : $weight;
                    $shipmentBatchOrderData[$index]['jkt_weight'] = empty($jkt_weight) ? 0.00 : $jkt_weight;
                    $shipmentBatchOrderData[$index]['discrepany'] = $orderDetails['weight'] - $orderDetails['jkt_weight'];
                    $shipmentBatchOrderData[$index]['received_date'] = $received_date;
                    $shipmentBatchOrderData[$index]['jkt_receiver'] = $orderDetails['jkt_receiver'];
                }
            }
            else
            {
                $shipmentBatchOrderData = array();
            }
            
        }
//        p($shipmentBatchOrderData);
        return $shipmentBatchOrderData;
    }
    
    public function getBoxCollectionDateReport($date_from, $date_to)
    {
         $result = array();
        
        $result = $this->_CI->reportsModel->getBoxCollectionDateReport($date_from, $date_to);
        
        if (!empty($result))
        {
            foreach ($result as $index => $row)
            {  
                $result[$index] = $row;
            }
        }
        return $result;
    }
    
}
