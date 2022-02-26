<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Restapilib
{

    private $_CI;

    public function __construct()
    {
        $this->_CI = & get_instance();
    }

    public function validateLogin($username, $password)
    {
        $return = array();

        if (empty($username) || empty($password))
        {
            $return = array(
                'status' => 'error',
                'code' => '467',
                'message' => 'Username/Password can not be empty.',
            );
        }
        else
        {
            $this->_CI->load->model('restapimodel');
            $data = $this->_CI->restapimodel->validateLogin($username, $password);
            if (empty($data))
            {
                $return = array(
                    'status' => 'error',
                    'code' => '468',
                    'message' => 'Invalid username/password.',
                );
            }
            else
            {
                if ($data['active'] == 'yes')
                {
                    $role = strtolower($data['role']);
                    
                    $roles = $this->_CI->config->item('roles');
                    $eod_required = isset($roles[$role]['eod_required']) ? $roles[$role]['eod_required'] : false;
                    
                    $extra_data_arr = array();
                    
                    if (!empty($eod_required))
                    {
                        $temp_data = array(
                            'employee_id' => $data['id'],
                            'date' => date('Y-m-d'),
                        );
                        $status = $this->_getEODStatus($temp_data);
                        $extra_data_arr['current_eod_status'] =  empty($status) ? 'no' : $status;
                    }
                    
                    if ($role == "warehousemanager")
                    {
                        // in case of warehousemanager at Singapore side, we have to display shipment batch
                        // and box information as well
                        $shipment_batch_boxes = getCurrentShipmentAndBoxCapacities();
                        
                        if (empty($shipment_batch_boxes['shipment_batch']))
                        {
                            $return = array(
                                'status' => 'error',
                                'code' => '492',
                                'message' => 'No active Shipment Batch found. Kindly report this incidence to Admin.',
                            );
                        }
                        else if (empty($shipment_batch_boxes['boxes']))
                        {
                            $return = array(
                                'status' => 'error',
                                'code' => '493',
                                'message' => "No boxes defined for Shipment Batch {$shipment_batch_boxes['shipment_batch']}. Kindly report this incidence to Admin.",
                            );
                        }
                        else
                        {
                            $extra_data_arr['shipment_batch'] = $shipment_batch_boxes['shipment_batch'];
                            $extra_data_arr['boxes'] = $shipment_batch_boxes['boxes'];
                        }
                    }
                    
                    
                    if (empty($return))
                    {
                        $return = array(
                            'status' => 'success',
                            'code' => '200',
                            'data' => array(
                                            'user_id' => $data['id'], 
                                            'role' => $role, 
                                            'name' => $data['name'],
                                            'eod_required' => $eod_required
                                    )
                        );
                        
                        $return['data'] = array_merge($return['data'], $extra_data_arr);
                    }
                }
                else
                {
                    $return = array(
                        'status' => 'error',
                        'code' => '469',
                        'message' => 'Your account is blocked. Kindly contact admin.',
                    );
                }
            }
        }
        return $return;
    }

    public function validateLoginJakarta($username, $password)
    { 
        $return = array();

        if (empty($username) || empty($password))
        {
            $return = array(
                'status' => 'error',
                'code' => '467',
                'message' => 'Username/Password can not be empty.',
            );
        }
        else
        {
            $this->_CI->load->model('restapimodel');
            $data = $this->_CI->restapimodel->validateLogin($username, $password);
            if (empty($data))
            {
                $return = array(
                    'status' => 'error',
                    'code' => '468',
                    'message' => 'Invalid username/password.',
                );
            }
            else
            {
                if ($data['geo_type'] !== 'jakarta')
                {
                    $return = array(
                        'status' => 'error',
                        'code' => '489',
                        'message' => 'Illegal operation performed. Trying to login into Jakarta module with other credentials.',
                    );
                }
                else
                {
                    if ($data['active'] == 'yes')
                    {
                        $role = strtolower($data['role']);
                        switch($role)
                        {
                         case "warehousemanager":
                           $return = array(
                                'status' => 'success',
                                'code' => '200',
                                'data' => array(
                                                'user_id' => $data['id'], 
                                                'role' => $role, 
                                                'name' => $data['name'],                                )
                            );

                            $this->_CI->load->model('admin/ReceivingBatchesModel');
                            $receiving_batches = $this->_CI->ReceivingBatchesModel->getAllReceivingBatches();

                            if (empty($receiving_batches))
                            {
                                $receiving_batches = array();
                            }
                            
                            $return['data']['receiving_batches'] =  $receiving_batches;
                            
                            $receiving_batch_orders = $this->_CI->ReceivingBatchesModel->getAllReceivingBatchesOrders();
                            if (empty($receiving_batch_orders))
                            {
                                $orders = array();
                            }
                            else
                            {
                                foreach ($receiving_batch_orders as $index => $row)
                                {
                                    $orders[] = array(
                                        'id' => $row['id'],
                                        'order_number' => $row['order_number'],
                                        'boxes_count' => $row['boxes_count'],
                                    );
                                }
                            }
                            
                            $return['data']['receiving_batch_orders'] =  $orders;
                            break;
                            
                            
                         case "driver":
                            //get all the DistributionCenters to display center name when login a validate user 
                            $this->_CI->load->model('admin/restapimodel');
                            $distribution_centers = $this->_CI->restapimodel->getAllDistributionCenters();
                            $jkt_status = $this->_CI->config->item('jakarta_statuses');
                            if(!empty($distribution_centers))
                            { 
                                foreach($distribution_centers as $idx => $val)
                                {
                                    $distribution_centers[$idx]['header_name'] = $val['dc_center_name'];
                                    if($val['is_location_display'] == "yes")
                                    {
                                        $distribution_centers[$idx]['locations_name'] = str_replace('@@##@@', ',', $val['locations_name']);
                                    }
                                    else
                                    {
                                        $distribution_centers[$idx]['locations_name'] = "";
                                    }
                                }
                                $return = array(
                                    'status' => 'success',
                                    'code' => '200',
                                    'data' => array(
                                                'user_id' => $data['id'], 
                                                'role' => $role, 
                                                'name' => $data['name'],                                )
                                );
                                $return['data']['Distribution_centers'] =  $distribution_centers;
                                $return['data']['jkt_status'] =  $jkt_status;
                            }
                            else
                            {
                                 $return = array(
                                    'status' => 'error',
                                    'code' => '467',
                                    'message' => 'distribution centers empty'
                                );
                                $return;
                            }
                            break;
                            
                            
                         default:
                            $return = array(
                                'status' => 'error',
                                'code' => '490',
                                'message' => 'Illegal operation performed. Only Warehouse Manager and Driver type of users can login to this App.',
                            );
                        }
                    }
                    else
                    {
                        $return = array(
                            'status' => 'error',
                            'code' => '469',
                            'message' => 'Your account is blocked. Kindly contact admin.',
                        );
                    }
                }
            }
        }
        return $return;
    }

    public function updateStatus($status_array)
    {   
        $order_id = $status_array['order_id'];
        $employee_id = $status_array['employee_id'];
        $lattitude = $status_array['lattitude'];
        $longitude = $status_array['longitude'];
        $shipment_batch_id = $status_array['shipment_batch_id'];
        $metadata = $status_array['metadata'];
         
        $this->_CI->load->model('ordersmodel');
        
        if (empty($order_id) || empty($lattitude) || empty($longitude) || empty($employee_id))
        { 
            $return = array(
                'status' => 'error',
                'code' => '470',
                'message' => 'Order Id/Lat-Long/Employee Id can not be empty.',
            );
        }
        else
        { 
            $this->_CI->load->model('UserModel');
            $employee = (array) $this->_CI->UserModel->getUserById($employee_id);

            if (empty($employee) || $employee['geo_type'] === 'jakarta')
            { 
                $return = array(
                    'status' => 'error',
                    'code' => '489',
                    'message' => 'Illegal operation performed. Trying to login into Singapore module with other credentials.',
                );
            }
            else
            { 
                if ($order_id == 'http://www.postki.mobi')
                { 
                    $return = array(
                        'status' => 'error',
                        'code' => '487',
                        'message' => "Hold your horses! You are scanning old QR Codes of POS TKI on pre printed stationery. Try scanning with one generated with Software.",
                    );
                }
                else
                { 
                    $order = $this->_CI->ordersmodel->getOrderDetails($order_id);
                    
                    if (empty($order))
                    { 
                        $return = array(
                            'status' => 'error',
                            'code' => '488',
                            'message' => "Seems you are scanning wrong QR Code. It doesn't belong to your operations.",
                        );
                    }
                    else
                    { 
                        $order_status = $this->_CI->ordersmodel->getOrderStatusDetails($order_id);
                        $status = $order_status['status'];
                         
                        $statuses = $this->_CI->config->item('statuses');
    //                    p($statuses);
                        $next_status = isset($statuses[$status]['next']) ? $statuses[$status]['next'] : NULL;
                         
                        if ($order['status'] == 'cancelled')
                        { 
                            $return = array(
                                                'status' => 'error',
                                                'code' => '482',
                                                'message' => "Can not scan as {$order['order_number']} is marked as canceled."
                                            );
                        }
                        else
                        { 
                            if ($order['kiv_status'] == 'yes')
                            { 
                                $return = array(
                                                    'status' => 'error',
                                                    'code' => '483',
                                                    'message' => "Can not scan as {$order['order_number']} is marked as KIV."
                                                );
                            }
                            else
                            { 
                                //Check EOD is already done for this employee for today?
                                $data = array(
                                    'employee_id' => $employee_id,
                                    'date' => date('Y-m-d'),
                                    'status' => 'yes',
                                );
                                $eod_record = $this->_CI->ordersmodel->getRow('eod', $data);

                                if (!empty($eod_record))
                                { 
                                    $return = array(
                                                    'status' => 'error',
                                                    'code' => '481',
                                                    'message' => "EOD already done."
                                                );
                                }
                                else
                                { 
                                    if (0 && is_integer($order_id) == false)
                                    { 
                                        $return = array(
                                                    'status' => 'error',
                                                    'code' => '486',
                                                    'message' => "Please make sure you are scanning correct QR Code. Seems like this QR Code doesn't belong to us."
                                                );
                                    }
                                    else
                                    { 
                                        $check_proximity = $this->_CI->config->item('check_proximity');

                                        if (isset($statuses[$next_status]))
                                        { 
                                            $text = $statuses[$next_status]['display_text'];
                                        }

                                        if (empty($next_status))
                                        { 
                                            $return = array(
                                                        'status' => 'error',
                                                        'code' => '475',
                                                        'message' => "Order cycle already completed for {$order['order_number']}"
                                                    );
                                        }
                                        else if ($next_status == 'collected_at_warehouse')
                                        { 
                                            $shipment_batch_row = getCurrentShipmentBatchId($order_id, $order['order_number']);

                                            if (empty($shipment_batch_row['error']))
                                            { 
                                                $shipment_batch_id = $shipment_batch_row['data']['shipment_batch_id'];
                                                $mapping_ids = $shipment_batch_row['data']['mapping_ids'];
                                            }
                                            else
                                            { 
                                                $return = array(
                                                    'status' => 'error',
                                                    'code' => '491',
                                                    'message' => $shipment_batch_row['message']
                                                );
                                            }
                                        }

                                        if (empty($return))
                                        { 
                                            $cash_collection = $statuses[$next_status]['cash_collection'];
                                            $voucher_cash = $statuses[$next_status]['voucher_cash'];
                                            $display_text = $statuses[$next_status]['display_text'];
                                            $status_check_proximity = $statuses[$next_status]['check_proximity'];


                                            if (!empty($check_proximity) && !empty($status_check_proximity))
                                            { 
                                                $proximity = (int) $this->_CI->config->item('proximity_in_meters');
                                                $coordinates_switch_proximity = (int) $this->_CI->config->item('coordinates_switch_proximity_in_meters');

                                                $dest_lat = $order['lattitude'];
                                                $dest_lon = $order['longitude'];

                                                $google_lat = $order['google_lat'];
                                                $google_lon = $order['google_lon'];

                                                if ($dest_lat ===  null || $dest_lat ===  '' ||  $dest_lon === null || $dest_lon === '')
                                                { 
                                                    $return = array(
                                                            'status' => 'error',
                                                            'code' => '478',
                                                            'message' => "Lat/Long empty for {$order['order_number']}."
                                                        );
                                                }
                                                else
                                                {

                                                    $distance = (int) ceil(calculateDistanceBetweenCoordinates($lattitude, $longitude, $dest_lat, $dest_lon));

                                                    //If criteria of switching co-ordinates meet
                                                    if ($distance > $coordinates_switch_proximity)
                                                    {

                                                        if (!empty($google_lat) && !empty($google_lon))
                                                        { 
                                                            $normal_distance = $distance;

                                                            $distance = (int) ceil(calculateDistanceBetweenCoordinates($lattitude, $longitude, $google_lat, $google_lon));

                                                            if ($proximity < $distance)
                                                            { 
                                                                $return = array(
                                                                    'status' => 'error',
                                                                    'code' => '485',
                                                                    'message' => "Proximity limit reached. Google API also attempted. Defined proximity is $proximity(M) and scanned ones are Google - $distance(M), Normal - $normal_distance(M) "
                                                                );
                                                            }
                                                            else
                                                            { 
                                                                $coordinates_type = 'google';
                                                            }
                                                        }
                                                        else
                                                        { 
                                                            $return = array(
                                                                'status' => 'error',
                                                                'code' => '471',
                                                                'message' => "Proximity limit reached. Defined proximity is $proximity(M) and scanned one is $distance(M)"
                                                            );
                                                        }
                                                    }
                                                    else if ($proximity < $distance)
                                                    { 
                                                        $return = array(
                                                            'status' => 'error',
                                                            'code' => '471',
                                                            'message' => "Proximity limit reached. Defined proximity is $proximity(M) and scanned one is $distance(M)"
                                                        );
                                                    }
                                                }
                                            }
                         
                                          if($order_status['status'] == $next_status  && $order_status['active'] == "yes")
                                          {
                                              $display_err_status = $order_status['status'];
                                              $order_display_text = $statuses[$display_err_status]['display_text'];
                                              $return = array(
                                                            'status' => 'error',
                                                            'code' => '11',
                                                            'message' => "The Order Has Already $order_display_text"
                                                        );
                                          }  

                                            if (empty($return) && !empty($statuses[$next_status]['callback']))
                                            {  
                                                $customer_name   = $order['customer_name'];
                                                $customer_number = $order['customer_mobile'];
                                                
                                                $status_check_array = array(
                                                    'status' => $next_status,
                                                    'collection_date' => $order['collection_date'],
                                                    'delivery_date' => $order['delivery_date'],
                                                    'status_update_date' => $order_status['updated_at'],
                                                    'booked_by' => $order_status['employee_id'],
                                                    'order_number' => $order['order_number'],
                                                    'employee_id' => $employee_id,
                                                    'customer_name' => $customer_name,
                                                    'customer_number' => $customer_number,
                                                    'order_id' => $order_id
                                                );

                                                $result = call_user_func($statuses[$next_status]['callback'], $status_check_array);
                                                 
                                                if ($result['status'] == 'error')
                                                { 
                                                    $return = array(
                                                        'status' => 'error',
                                                        'code' => $result['code'],
                                                        'message' => $result['message'],
                                                    );
                                                }
                                                //Add send msg to the msg table
                                                else 
                                                {
                                                    if(isset($result['data']))
                                                    {                                                                                                           $this->_CI->load->model('admin/restapimodel');
                                                        $this->_CI->restapimodel->save_sms_triggered($result);
                                                    }
                                                }
                                                
                                            }
                                        }

                                        if (empty($return))
                                        { 
                                            $outstanding = getOrderOutstanding($order_id);

                                            $time = date('Y-m-d H:i:s');

                                            $this->_CI->ordersmodel->saveOrderStatus(array('id' => $order_status['id'], 'active' => 'no', 'updated_at' => $time));

                                            $resposibilityCalcData = array(
                                                'employee_id' => $employee_id,
                                                'order_id' => $order_id,
                                                'status' => $next_status,
                                                'delivery_date' => $order['delivery_date'],
                                                'collection_date' => $order['collection_date'],
                                                'statuses' => $statuses,
                                            );

                                            $order_status = array(
                                                'status' => $next_status,
                                                'employee_id' => $employee_id,
                                                'order_id' => $order_id,
                                                'metadata' => $metadata,
                                                'active' => 'yes',
                                                'coordinates_type' => empty($coordinates_type) ? 'normal' : $coordinates_type,
                                                'reassigned_stage' => 'no',
                                                'responsibility_completed' => determineResponsbilityCompleted($resposibilityCalcData),
                                                'created_at' => $time,
                                                'updated_at' => $time,
                                                'qr_manual_entry' => $status_array['qr_manual_entry']
                                            );

                                            $order_status = $this->_CI->ordersmodel->saveOrderStatus($order_status);

                                            $shipment_batch_box_row = null;

                                            if ($next_status == 'collected_at_warehouse')
                                            {
                                                $dataValues = array(
                                                    'shipment_batch_id' => $shipment_batch_id,
                                                    'updated_at' => date('Y-m-d H:i:s'),
                                                    'id' => $order_id
                                                );
                                                $this->_CI->ordersmodel->saveOrder($dataValues);

                                                /******* NOTE **************/
                                                // Below is hack, have to convet this, I am invoking the same function twice for some reason
                                                
                                                $shipment_batch_row_new = getCurrentShipmentBatchId($order_id, $order['order_number']);
                                                $mapping_ids = $shipment_batch_row_new['data']['mapping_ids'];
                                                //Update scanned quantity count in shipment_batch_box_mapping table
                                                if (!empty($mapping_ids))
                                                {
                                                    foreach($mapping_ids as $shipment_batch_box_mapping_id => $scanned_quantity)
                                                    {
                                                        $scanned_data = array('id' => $shipment_batch_box_mapping_id, 'scanned_quantity' => $scanned_quantity);
                                                        $this->_CI->mastersModel->saveShipmentBatchBoxMapping($scanned_data);
                                                    }
                                                }

                                                // in case of warehousemanager at Singapore side, we have to display shipment batch
                                                // and box information as well. It would be injected in response at end.
                                                $shipment_batch_boxes = getCurrentShipmentAndBoxCapacities();

                                                $shipment_batch_box_row['shipment_batch'] = $shipment_batch_boxes['shipment_batch'];
                                                $shipment_batch_box_row['boxes'] = $shipment_batch_boxes['boxes'];
                                                
                                                //It will auto close shipment batch once capacity is exhausted.
                                                updateShipmentBatchStatusById($shipment_batch_id);
                                            }


                                            $eod_array = array(
                                                'employee_id' => $employee_id,
                                            );

                                            $eod_status = determineEODStatus($eod_array);

                                            $eod_array['date'] = date('Y-m-d');
                                            $eod_array['status'] = $eod_status;

                                            $this->_saveEODData($eod_array);

                                            $return = array(
                                                    'status' => 'success',
                                                    'code' => '200',
                                                    'data' => array(
                                                        'id' => $order_status,
                                                        'status' => $display_text,
                                                        'outstanding_amount' => $outstanding['outstanding'],
                                                        'cash_collection' => $cash_collection,
                                                        'order_id' => $order_id,
                                                        'voucher_cash' => $voucher_cash,
                                                        'eod_status' => $eod_status
                                                    )
                                                );

                                            //In case of warehousemanager we have captured some info which needs to be sent i.e. shipment batch and box capacities
                                            if ($shipment_batch_box_row !== null)
                                            {
                                                $return['data'] = array_merge($return['data'], $shipment_batch_box_row);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        
        if ($return['status'] == 'error')
        {
            $data = array(
                'order_id' => $order_id,
                'status' => empty($next_status) ? null : $next_status,
                'message' => $return['message'],
                'log' => serialize($_REQUEST),
                'employee_id' => $employee_id,
                'created_at' => date('Y-m-d H:i:s'),
            );
            $this->_CI->ordersmodel->saveFailedScan($data);
        }
        
        return $return;
    }

    public function updateStatusJakarta($status_array)
    { 
        $order_id = $status_array['order_id'];
        $employee_id = $status_array['employee_id'];
        $lattitude = $status_array['lattitude'];
        $longitude = $status_array['longitude'];
        $metadata = $status_array['metadata'];
        $dc_lattitude = $status_array['dc_lattitude'];
        $dc_longitude = $status_array['dc_longitude'];
        $jkt_receiver = $status_array['jkt_receiver'];
        $status_escalation_type = $status_array['status_escalation_type'];
        $this->_CI->load->model('ordersmodel');
        
        if (empty($order_id) || empty($employee_id))
        {
            $return = array(
                'status' => 'error',
                'code' => '470',
                'message' => 'Order/Lat-Long/Employee can not be empty.',
            );
        }
        else
        {
            $this->_CI->load->model('UserModel');
            $employee = (array) $this->_CI->UserModel->getUserById($employee_id);
                         
            if (empty($employee) || (($employee['geo_type'] != 'jakarta') && ($employee['geo_type'] != 'all')))
            { 
                $return = array(
                    'status' => 'error',
                    'code' => '489',
                    'message' => 'Illegal operation performed. Trying to login into Jakarta module with other credentials.',
                );
            }
            else
            { 
                $order = $this->_CI->ordersmodel->getOrderDetails($order_id);
                $order_number = $order['order_number'];
                if (empty($order))
                {
                    $return = array(
                        'status' => 'error',
                        'code' => '488',
                        'message' => "Seems you are scanning wrong QR Code. It doesn't belong to your operations.",
                    );
                }
                else
                {
                    if (strtolower($employee['roleName']) == "warehousemanager")
                    {
                        $order_status = $this->_CI->ordersmodel->getOrderStatusDetails($order_id);
                        $status = $order_status['status'];
                        
                        $statuses = $this->_CI->config->item('jakarta_statuses');

                        if (isset($statuses[$status]))
                        {
                            $next_status = "received_at_jakarta_warehouse";
                        }
                        else
                        {
                            $next_status = 'ready_for_receiving_at_jakarta';
                        }

                        if ($order['status'] == 'cancelled')
                        {
                            $return = array(
                                                'status' => 'error',
                                                'code' => '482',
                                                'message' => "Can not scan as {$order['order_number']} is marked as canceled."
                                            );
                        }
                        else
                        {
                            if ($order['kiv_status'] == 'yes')
                            {
                                $return = array(
                                                    'status' => 'error',
                                                    'code' => '483',
                                                    'message' => "Can not scan as {$order['order_number']} is marked as KIV."
                                                );
                            }
                            else
                            {
                                //Check EOD is already done for this employee for today?
                                $data = array(
                                    'employee_id' => $employee_id,
                                    'date' => date('Y-m-d'),
                                    'status' => 'yes',
                                );
                                $eod_record = $this->_CI->ordersmodel->getRow('eod', $data);

                                if (!empty($eod_record))
                                {
                                    $return = array(
                                                    'status' => 'error',
                                                    'code' => '481',
                                                    'message' => "EOD already done."
                                                );
                                }
                                else
                                {
                                    if (0 && is_integer($order_id) == false)
                                    {
                                        $return = array(
                                                    'status' => 'error',
                                                    'code' => '486',
                                                    'message' => "Please make sure you are scanning correct QR Code. Seems like this QR Code doesn't belong to us."
                                                );
                                    }
                                    else
                                    {
                                        $check_proximity = $this->_CI->config->item('check_proximity');

                                        if (isset($statuses[$next_status]))
                                        {
                                            $text = $statuses[$next_status]['display_text'];
                                        }

                                        if (empty($next_status))
                                        {
                                            $return = array(
                                                        'status' => 'error',
                                                        'code' => '475',
                                                        'message' => "Order cycle already completed for {$order['order_number']}"
                                                    );
                                        }

                                        if (empty($return))
                                        {
                                            $cash_collection = $statuses[$next_status]['cash_collection'];
                                            $voucher_cash = $statuses[$next_status]['voucher_cash'];
                                            $display_text = $statuses[$next_status]['display_text'];
                                            $status_check_proximity = $statuses[$next_status]['check_proximity'];


                                            if (!empty($check_proximity) && !empty($status_check_proximity))
                                            {
                                                $proximity = (int) $this->_CI->config->item('proximity_in_meters');
                                                $coordinates_switch_proximity = (int) $this->_CI->config->item('coordinates_switch_proximity_in_meters');

                                                $dest_lat = $order['lattitude'];
                                                $dest_lon = $order['longitude'];

                                                $google_lat = $order['google_lat'];
                                                $google_lon = $order['google_lon'];

                                                if ($dest_lat ===  null || $dest_lat ===  '' ||  $dest_lon === null || $dest_lon === '')
                                                {
                                                    $return = array(
                                                            'status' => 'error',
                                                            'code' => '478',
                                                            'message' => "Lat/Long empty for {$order['order_number']}."
                                                        );
                                                }
                                                else
                                                {

                                                    $distance = (int) ceil(calculateDistanceBetweenCoordinates($lattitude, $longitude, $dest_lat, $dest_lon));

                                                    //If criteria of switching co-ordinates meet
                                                    if ($distance > $coordinates_switch_proximity)
                                                    {

                                                        if (!empty($google_lat) && !empty($google_lon))
                                                        {
                                                            $normal_distance = $distance;

                                                            $distance = (int) ceil(calculateDistanceBetweenCoordinates($lattitude, $longitude, $google_lat, $google_lon));

                                                            if ($proximity < $distance)
                                                            {
                                                                $return = array(
                                                                    'status' => 'error',
                                                                    'code' => '485',
                                                                    'message' => "Proximity limit reached. Google API also attempted. Defined proximity is $proximity(M) and scanned ones are Google - $distance(M), Normal - $normal_distance(M) "
                                                                );
                                                            }
                                                            else
                                                            {
                                                                $coordinates_type = 'google';
                                                            }
                                                        }
                                                        else
                                                        {
                                                            $return = array(
                                                                'status' => 'error',
                                                                'code' => '471',
                                                                'message' => "Proximity limit reached. Defined proximity is $proximity(M) and scanned one is $distance(M)"
                                                            );
                                                        }
                                                    }
                                                    else if ($proximity < $distance)
                                                    {
                                                        $return = array(
                                                            'status' => 'error',
                                                            'code' => '471',
                                                            'message' => "Proximity limit reached. Defined proximity is $proximity(M) and scanned one is $distance(M)"
                                                        );
                                                    }
                                                }
                                            }

                                            if (empty($return) && !empty($statuses[$next_status]['callback']))
                                            {
                                                $status_check_array = array(
                                                    'status' => $next_status,
                                                    'collection_date' => $order['collection_date'],
                                                    'delivery_date' => $order['delivery_date'],
                                                    'status_update_date' => $order_status['updated_at'],
                                                    'booked_by' => $order_status['employee_id'],
                                                    'order_number' => $order['order_number'],
                                                    'employee_id' => $employee_id,
                                                );

                                                $result = call_user_func($statuses[$next_status]['callback'], $status_check_array);

                                                if ($result['status'] == 'error')
                                                {
                                                    $return = array(
                                                        'status' => 'error',
                                                        'code' => $result['code'],
                                                        'message' => $result['message'],
                                                    );
                                                }
                                            }
                                        }
                                        if($order_status['status'] == $next_status  && $order_status['active'] == "yes")
                                        {
                                              $display_err_status = $order_status['status'];
                                              $order_display_text = $statuses[$display_err_status]['display_text'];
                                              $return = array(
                                                            'status' => 'error',
                                                            'code' => '12',
                                                            'message' => "The Order Has Already $order_display_text"
                                                        );
                                        }
                                        if (empty($return))
                                        {
                                            $time = date('Y-m-d H:i:s');

                                            $this->_CI->ordersmodel->saveOrderStatus(array('id' => $order_status['id'], 'active' => 'no', 'updated_at' => $time));

                                            $order_status = array(
                                                'status' => $next_status,
                                                'employee_id' => $employee_id,
                                                'order_id' => $order_id,
                                                'metadata' => $metadata,
                                                'active' => 'yes',
                                                'coordinates_type' => empty($coordinates_type) ? 'normal' : $coordinates_type,
                                                'reassigned_stage' => 'no',
                                                'responsibility_completed' => $statuses[$next_status]['responsibility_completed'],
                                                'created_at' => $time,
                                                'updated_at' => $time,
                                            );

                                            $order_status = $this->_CI->ordersmodel->saveOrderStatus($order_status);

                                            $eod_array = array(
                                                'employee_id' => $employee_id,
                                            );

                                            $eod_status = determineEODStatus($eod_array);

                                            $eod_array['date'] = date('Y-m-d');
                                            $eod_array['status'] = $eod_status;

                                            $this->_saveEODData($eod_array);

                                            updateReceivingBatchStatus();

                                            $return = array(
                                                    'status' => 'success',
                                                    'code' => '200',
                                                    'data' => array(
                                                        'id' => $order_status,
                                                        'status' => $display_text,
                                                        'order_id' => $order_id,
                                                        'eod_status' => $eod_status
                                                    )
                                                );
                                        }
                                    }
                                }
                            }
                        }
                    }
                    
                    elseif(strtolower($employee['roleName']) == "driver" || strtolower($employee['roleName']) == "admin")
                    {   
                        $order_status = $this->_CI->ordersmodel->getOrderStatusDetails($order_id);
                        $status = $order_status['status'];
                        if ($order['status'] == 'cancelled')
                        {
                            $return = array(
                                        'status' => 'error',
                                        'code' => '482',
                                        'message' => "Can not scan as {$order['order_number']} is marked as canceled."
                                   );
                        }
                        elseif (0 && is_integer($order_id) == false)
                        {
                            $return = array(
                                        'status' => 'error',
                                        'code' => '486',
                                        'message' => "Please make sure you are scanning correct QR Code. Seems like this QR Code doesn't belong to us."
                                   );
                        }
                        else
                        { 
                            $check_proximity = $this->_CI->config->item('check_proximity_jkt');
                            if (!empty($check_proximity))
                            {
                                $proximity = (int) $this->_CI->config->item('proximity_in_meters_jkt');
                                $coordinates_switch_proximity = (int) $this->_CI->config->item('coordinates_switch_proximity_in_meters_jkt');
                                
                                $dest_lat = $dc_lattitude;
                                $dest_lon = $dc_longitude;
                                if ($dest_lat ==  '' || $dest_lon == '' || $lattitude =='' || $longitude =='')
                                {
                                    $return = array(
                                            'status' => 'error',
                                            'code' => '478',
                                            'message' => "Lat/Long empty for {$order['order_number']}."
                                        );
                                }
                                else
                                { 
                                    $distance = (int) ceil(calculateDistanceBetweenCoordinates($lattitude, $longitude, $dest_lat, $dest_lon));
                         
                                    if ($distance > $coordinates_switch_proximity)
                                    {  
                                       $return = array(
                                            'status' => 'error',
                                            'code' => '471',
                                            'message' => "Proximity limit reached. Defined proximity is $proximity(M) and scanned one is $distance(M)"
                                        );
                                            $normal_distance = $distance;
                                            if ($distance > $proximity)
                                            { 
                                                $return = array(
                                                    'status' => 'error',
                                                    'code' => '485',
                                                    'message' => "Proximity limit reached. Google API also attempted. Defined proximity is $proximity(M) and scanned ones are Google - $distance(M), Normal - $normal_distance(M) "
                                                );
                                            }
                                    }
                                }
                            }
                        $statuses = $this->_CI->config->item('jakarta_statuses');

                        if (isset($statuses[$status]))
                        {
                            $next_status = $statuses[$status]['next'];
                        }
                        else
                        {
                            $next_status = '';
                        }
                         
                          
                        if (empty($next_status))
                        {
                            $return = array(
                                        'status' => 'error',
                                        'code' => '475',
                                        'message' => "Order cycle already completed for {$order['order_number']}"
                                    );
                        }
                        else
                        {
                            $display_text = $statuses[$next_status]['display_text']; 
                        }
                         
                        if (empty($return) && !empty($statuses[$next_status]['callback']))
                        {     
                            $customer_id = $order['customer_id'];
                            if(!empty($customer_id))
                            {   
                               $this->_CI->load->model('admin/restapimodel');
                               $recipient_name = $order['jkt_receiver'];
                               $customer_name   = $order['customer_name'];
                               $customer_number = $order['customer_mobile'];
                               $order_location_name = $status_array['order_location_name']; 

  
                               $record = array(
                                    'status' => $next_status,
                                    'customer_name' => $customer_name,
                                    'customer_number' => $customer_number,
                                    'order_number' => $order['order_number'],
                                    'employee_id' => $employee_id,
                                    'recipient_name' => $recipient_name,
                                    'order_id' => $order_id,
                                    'order_location_name'=> $order_location_name
                                );
                                
                                $result = call_user_func($statuses[$next_status]['callback'], $record);
                                
                                if(!empty($result['data']))
                                {
                                    $save_sms_triggered_data = $this->_CI->restapimodel->save_sms_triggered($result);
                                }
                            }
                        }                  
                         
                        $time = date('Y-m-d H:i:s');
                        if(empty($return))
                        {  
                            $this->_CI->load->model('admin/ordersmodel');
                            $this->_CI->ordersmodel->saveOrderStatus(array('id' => $order_status['id'], 'active' => 'no', 'updated_at' => $time));
                            $order_status = array(
                                            'status' => $next_status,
                                            'employee_id' => $employee_id,
                                            'order_id' => $order_id,
                                            'metadata' => $metadata,
                                            'active' => 'yes',
                                            'coordinates_type' => empty($coordinates_type) ? 'normal' : $coordinates_type,
                                            'reassigned_stage' => 'no',
                                            'responsibility_completed' => $statuses[$next_status]['responsibility_completed'],
                                            'created_at' => $time,
                                            'updated_at' => $time,
                                            'qr_manual_entry' => $status_array['qr_manual_entry'],
                                            'status_escalation_type' => $status_escalation_type 
                                            );
                         
                            $order_status = $this->_CI->ordersmodel->saveOrderStatus($order_status);
                            
                            //save jkt_received_date when order status is delivered_at_jkt_picture_not_taken 
                            if($status =='received_at_jakarta_warehouse')
                            {
                                $jkt_date = $time;
                                $dataValues = array(
                                                'jkt_received_date' => $jkt_date,
                                                'updated_at' => $jkt_date,
                                                'id' => $order_id
                                                );
                                $save_jkt_receive_date = $this->_CI->ordersmodel->saveOrder($dataValues);
                            }
                            
                            //save jkt_receiver name when a user enter receiver name
                            if(!empty($jkt_receiver))
                            {
                                $jkt_date = $time;
                                $dataValues = array(
                                                'jkt_receiver' => $jkt_receiver,
                                                'updated_at' => $jkt_date,
                                                'id' => $order_id
                                                );
                                $save_jkt_receiver = $this->_CI->ordersmodel->saveOrder($dataValues);;
                            }

                            
                            $return = array(
                                    'status' => 'success',
                                    'code' => '200',
                                    'data' => array(
                                        'id' => $order_status,
                                        'status' => $display_text,
                                        'order_id' => $order_id,
                                        'order_number' => $status_array['order_number'] 
                                    )
                                );
                        }
                     }
                   }
                    else
                    {
                        $return = array(
                                'status' => 'error',
                                'code' => '490',
                                'message' => 'Illegal operation performed. Only Warehouse Manager And Driver type of users can login to this App.',
                            );
                    }
                }
            }
        }
        
        if ($return['status'] == 'error')
        {
            $data = array(
                'order_id' => $order_id,
                'status' => empty($next_status) ? null : $next_status,
                'message' => $return['message'],
                'log' => serialize($_REQUEST),
                'employee_id' => $employee_id,
                'created_at' => date('Y-m-d H:i:s'),
            );
            $this->_CI->ordersmodel->saveFailedScan($data);
        }
        
        return $return;
    }

    public function updateEODStatus($status_array)
    {
        $employee_id = $status_array['employee_id'];
        $status = $status_array['status'];
        $metadata = $status_array['metadata'];
        $date = $status_array['date'];
        $time = $status_array['time'];
        $currentTime = $date ." ".$time;        
        $checkTime = $date .EOD_TIME_THRESHOLD;

        if (empty($employee_id))
        {
            $return = array(
                'status' => 'error',
                'code' => '480',
                'message' => 'Employee Id can not be empty.',
            );
        }
        else
        {
           if($currentTime < $checkTime)
           {
               $date = date('Y-m-d', strtotime($date . "-1 days"));
           }
           
            $data = array(
                'employee_id' => $employee_id,
                'date' => $date,
                'status' => $status,
            );
            
            $this->_CI->load->model('ordersmodel');
                         
            $where = array(
                'order_status_trans.active' => 'yes',
                'order_status_trans.employee_id' => $data['employee_id'],
                'date(order_status_trans.updated_at)' => date('Y-m-d'),
            );
            $CI = & get_instance();
            $CheckEmpOrderStatus  = $CI->ordersmodel->getOrderStatusDetailsByCondition($where);
            
            if($CheckEmpOrderStatus)
            {
                foreach($CheckEmpOrderStatus as $idx => $val)
                {
                    $pending_orders_no[] = $val['order_number'];
                }
                $seperate_pending_orders = implode(", ",$pending_orders_no);
                
                $return = array(
                    'status' => 'error',
                    'code' => '481',
                    'message' => "Can not perform EOD as there are some orders for which responsibility is not completed yet $seperate_pending_orders.",
                );
            }
            else
            {
                $order = $this->_CI->ordersmodel->getRow('eod', $data);
                if (empty($order))
                {
                    $search_data = array(
                        'employee_id' => $employee_id,
                        'date' => $date,
                    );
                    $order = $this->_CI->ordersmodel->getRow('eod', $search_data);
                    

                    $this->_CI->db->delete('employee_order_ordering', $search_data);

                    $data = array(
                        'metadata' => $metadata,
                        'id' => $order['id'],
                        'status' => $status,
                    );

                    $this->_CI->ordersmodel->saveEOD($data);

                    $return = array(
                            'status' => 'success',
                            'code' => '200',
                            'data' => array(
                                'status' => $status
                            ),
                        );
                }
                else
                {
                    $return = array(
                                'status' => 'error',
                                'code' => '481',
                                'message' => "EOD already done."
                            );
                }
            }
        }
        
        return $return;
    }

    public function updateCashCollectionDetails($array)
    {
        $id = $array['id'];
        $cash_collected = $array['cash_collected'];
        
        if (empty($id) || trim($cash_collected) === '')
        {
            $return = array(
                'status' => 'error',
                'code' => '471',
                'message' => 'Id/Cash Collected can not be empty.',
            );
        }
        else
        {
            $this->_CI->load->model('ordersmodel');
            $this->_CI->ordersmodel->saveOrderStatus($array);

            $return = array(
                    'status' => 'success',
                    'code' => '200'
                );
        }
        
        
        return $return;
    }

    public function updateEmployeeOrderOrdering($array)
    {
        if (empty($array['order_ids']) || empty($array['order_nos']) || empty($array['employee_id']) || empty($array['type']))
        {
            $return = array(
                'status' => 'error',
                'code' => '484',
                'message' => 'Order Ids/Order Nos/Employee Id/Type can not be empty.',
            );
        }
        else
        {
            $this->_CI->load->model('ordersmodel');

            $data = array(
                'employee_id' => $array['employee_id'],
                'type' => $array['type'],
                'date' => date('Y-m-d'),
            );
            
            foreach ($array['order_ids'] as $index => $order_id)
            {
                unset($data['order']);
                
                $order = $array['order_nos'][$index];
                $data['order_id'] = $order_id;
                
                $this->_CI->db->delete('employee_order_ordering', $data);
                                
                $data['order'] = $order;
                $this->_CI->db->insert('employee_order_ordering', $data);
            }
            
            $return = array(
                    'status' => 'success',
                    'code' => '200'
                );
        }
        
        
        return $return;
    }
    
    public function getDateWiseTaskListingByEmployee($array)
    {
        $employee_id = $array['employee_id'];
        $date = $array['date'];
        
        if (empty($employee_id))
        {
            $return = array(
                'status' => 'error',
                'code' => '480',
                'message' => 'Employee Id can not be empty.',
            );
        }
        else
        {
            $this->_CI->load->model('reportsmodel');
            $records = $this->_CI->reportsmodel->getDateWiseTaskListingByEmployee($array);

//            $records = empty($records) ? array() : $records;
            
            $collection = $delivery = array();
            
            if (!empty($records))
            {
                $status_type_group = $this->_CI->config->item('status_type_group');
                
                foreach ($records as $index => $row)
                {
                    if (in_array($row['status'], $status_type_group['delivery']))
                    {
                        $delivery[] = $row;
                    }
                    else if (in_array($row['status'], $status_type_group['collection']))
                    {
                        $collection[] = $row; 
                    }
                }
            }
            
            $return = array(
                    'status' => 'success',
                    'code' => '200',
                    'data' => array(
                        'collection' => $collection,
                        'delivery' => $delivery,
                    ),
                );
        }
        
        
        return $return;
    }

//    private function _determineEODStatus($data)
//    {
//        $where = array(
//            'order_status_trans.active' => 'yes',
//            'order_status_trans.employee_id' => $data['employee_id'],
//            'date(order_status_trans.updated_at)' => date('Y-m-d'),
//            'order_status_trans.responsibility_completed' => 'no',
//        );
//        
//        $this->_CI->load->model('ordersmodel');
//        $result  = $this->_CI->ordersmodel->getOrderStatusDetailsByCondition($where);
//        
//        $return = count($result) > 0 ? 'no' : 'ready';
//        
//        return $return;
//    }

    public function getEODStatus($data)
    {
        if (empty($data['employee_id']))
        {
            $return = array(
                'status' => 'error',
                'code' => '480',
                'message' => 'Employee Id can not be empty.',
            );
        }
        else
        {
            //Special check was performed for one sake, when there is only one order and order states are deleted
            //but EOD status is not changed. Upon refresh these checks are performed
            //Special check block starts here
            $eod_array = array(
                            'employee_id' => $data['employee_id'],
            );

            $current_status = $this->_getEODStatus($data);
            
            if ($current_status !== 'yes')
            {
                $eod_status = determineEODStatus($eod_array);
              
                $eod_array['date'] = date('Y-m-d');
                $eod_array['status'] = $eod_status;

                $this->_saveEODData($eod_array);
                //Special check block ends here
                
                $return = $this->_getEODStatus($data);
                $return = empty($return) ? new stdClass() : array('status' => $return);
            }
            else
            {
                $return = array('status' => 'yes');
            }
            
            $return = array(
                        'status' => 'success',
                        'code' => '200',
                        'data' => $return
                );
        }
        
        return $return;
    }
    
    private function _getEODStatus($data)
    {
        $this->_CI->load->model('ordersmodel');
        $return  = $this->_CI->ordersmodel->getRow('eod', $data);
        if (empty($return))
        {
            $return = null;
        }
        else
        {
            $return =  $return['status'];
        }
        
        return $return;
    }
    
    private function _saveEODData($data)
    {
        $this->_CI->load->model('ordersmodel');
        
        $where = array(
            'employee_id' => $data['employee_id'],
            'date' => date('Y-m-d')
        );
        
        $row = $this->_CI->ordersmodel->getRow('eod', $where);
        
        if (empty($row))
        {
            $data['created_at'] = date('Y-m-d H:i:s');
        }
        else
        {
            $data['id'] = $row['id'];
        }
        
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        $return = $this->_CI->ordersmodel->saveEOD($data);
        
        return $return;
    }
    public function getOrdersListingJkt($center_id)
    {
        $return = array();
        $this->_CI->load->model('admin/restapimodel');
        $orders_listing = $this->_CI->restapimodel->getAllOrdersReceived_jkt($center_id);
        $return = array(
            'status' => 'success',
            'code' => '200',
        );
        $return['data']['Orders_listing'] =  $orders_listing;
        return $return;
    }
    
    public function saveOrderImageJkt($data,$order_data)
    {  
        if(!empty($data))
        {
            $this->_CI->load->model('admin/restapimodel');
            //check images is already save according to a order no. or not

            $saveZipToDb = $this->_CI->restapimodel->saveZipToDb_jkt($data);
            $upload_path = $this->_CI->config->item('image_upload');
            $zipFilename = $data['image'];
            $target_path = $upload_path['upload_dir']. "/$zipFilename";
            $source_zipPath = $data['tmp_name']; 
            $extract_zippath = $upload_path['extraction_dir'] ."/$saveZipToDb";
            if(move_uploaded_file($source_zipPath, $target_path)) 
            {   
                $zip = new ZipArchive();
                $open_zip = $zip->open($target_path);
                if ($open_zip === true) {
                    $zip->extractTo($extract_zippath);  
                    $zip->close();
                    //**************change Zip File Name**********//
                    $original_zipName = $data['image'];
                    $original_zipName_arr = explode('.', $original_zipName);
                    $extension = array_pop($original_zipName_arr);
                    $rename_zipFile = "$saveZipToDb.$extension";
                    $source_dir = $upload_path['upload_dir'] . "/{$data['image']}";
                    $destination_dir = $upload_path['upload_dir'] . "/$rename_zipFile";
                    if (rename($source_dir, $destination_dir))
                    {
                        $data = array(
                            'renamed_archive' => $rename_zipFile,
                            'id' => $saveZipToDb,
                            );
                         
                        $result = array('status' => 'success', 'data' => $saveZipToDb);
                    }
                    else
                    {
                        $result = array('status' => 'error', 'message' => 'Some error took place while renaming uploaded compressed file.');
                    }
                        //**************change Zip File Name**********//
                }
                else
                {
                    $return = array(
                        'status' => 'error',
                        'code' => 'Problem in opening zip file',
                       );
                }
            } 
            else 
            {	
                $return = array(
                    'status' => 'error',
                    'code' => 'Problem when uploading zip file',
                    );
            }
            $scandir = scandir($extract_zippath);
            $files = array_slice(scandir($extract_zippath), 2);
            $updateRenameZipfile = $this->_CI->restapimodel->updateRenameZipfile_jkt($saveZipToDb);  
            $saveImageToDb = $this->_CI->restapimodel->saveImageToDb_jkt($files,$order_data,$saveZipToDb);
            $return = array(
                        'status' => 'success',
                        'message' => 'Image saved successfully',
                       );
        }
       return $return;
    }
}
