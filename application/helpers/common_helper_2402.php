<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    //log msg
    function logMsg($data) {
        $CI = & get_instance();
        $log_enabed = $CI->config->item('log_enabled');
        if ($log_enabed === TRUE) {
            log_message('info', $data, $php_error = FALSE);
        }
    }

    //set language data
    function mlLang($line, $id = '') {
        $CI = & get_instance();
        $caption = $line;
        $line = $CI->lang->line($line);

        if (!empty($CI->session->userdata['logged_in'])) {
            //fetch session language
            $siteLang = 'english';
        } else {
//            $siteUrlIdentity = getSiteUrlIdentity();
//            $siteLang = getSiteLanguage($siteUrlIdentity);
            $siteLang = 'english';
        }
      logMsg('site Lang');
        //fetch default lang
        $defaultLang = $CI->config->item('language');
        logMsg('default Lang');
        if (empty($line)) {                   
            //load default language file           
            $CI->lang->load('site', $defaultLang);
           
            $line = $CI->lang->line($caption);
            if (empty($line)) {
                $line = $caption;
            }
            
            //load session language file
            $CI->lang->load('site', strtolower($siteLang));
        }

        if ($id != '') {
            $line = '<label for="' . $id . '">' . $line . "</label>";
        }

        return $line;
    }

    function getSiteUrlIdentity() {
        $CI = & get_instance();
        
        $siteUrlIdentity = $CI->uri->segment(1);
        
        //check siteurl in DB exist or not
        if (isset($siteUrlIdentity) && $siteUrlIdentity != 'admin') {
            
            $siteUrlExist = $CI->commonlibrary->checkSiteIdentityExist($siteUrlIdentity);           
            if ($siteUrlExist == "0") {
                redirect('/', 'refresh');
            }
        } else {
            $siteUrlIdentity = "admin";
        }

        return $siteUrlIdentity;
    }

    function getSiteLanguage($siteUrlIdentity) {
        $CI = & get_instance();
        $CI->load->model('admin/languageModel');
        $siteLang = $CI->languageModel->getSiteLanguage($siteUrlIdentity);
        return $siteLang;
    }
    
    function getBusinessId($siteIdentity)
    {
        $businessId = "";
        $CI = & get_instance();
        $CI->load->model('admin/aclModel');
        $businessRecord = $CI->aclModel->getBusinessDataByIdentity($siteIdentity);
        if(!empty($businessRecord))
        {
            $businessId = $businessRecord->id;
        }
        
        return $businessId;
    }
    
    function getMaxOrderNumber($agent_id=NULL)
    {
        $orderNumber = '';
        
        $CI = & get_instance();
        
        
        $CI->load->model('admin/mastersModel');
        
        $order_number_agent_id = 0;
        if (!empty($agent_id))
        {
            $agent = (array) $CI->mastersModel->getAgentById($agent_id);
            if ($agent['own_running_number'] == 'yes')
            {
                $order_number_agent_id = $agent_id;
            }
        }
        
        $CI->load->model('admin/ordersModel');
        $raw_order_number = $CI->ordersModel->getMaxOrderNumber($order_number_agent_id);
        
        $order_number_config = $CI->config->item('order_number_config');
        
        if ($raw_order_number == 1)
        {
            if (empty($agent_id))
            {
                $raw_order_number = $order_number_config['order_number_start'];
            }
            else
            {
                if (empty($agent['order_number_start']))
                {
                    $raw_order_number = 1;
                }
                else
                {
                    $raw_order_number = $agent['order_number_start'];
                }
            }
        }
        
        if (empty($agent_id))
        {
            $order_number_separator = $order_number_config['order_number_separator'];
            $order_number_prefix = $order_number_config['order_number_prefix'];
        }
        else
        {
            $order_number_separator = $agent['order_number_separator'];
            $order_number_prefix = $agent['order_number_prefix'];
        }
        
        
        $order_number =  $order_number_prefix . $order_number_separator. str_pad($raw_order_number, $order_number_config['order_number_size_in_digits'], '0', STR_PAD_LEFT);

        $return = array(
            'order_number' => $order_number,
            'raw_order_number' => $raw_order_number,
        );
        
        return $return;
    }
    
    function generateBarCode($order_id, $order_number,$use_background_image=false)
    {
        $CI = & get_instance();
        $info = "$order_id#$order_number";
        if ($use_background_image)
        {
            $logo = $CI->config->item('qr_code_bg_image_path');
            $QR = imagecreatefrompng("https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=$info&choe=UTF-8");
            $logo = imagecreatefromstring(file_get_contents($logo));
            $QR_width = imagesx($QR);
            $QR_height = imagesy($QR);
            $logo_width = imagesx($logo);
            $logo_height = imagesy($logo);
            $logo_qr_width = $QR_width/3;
            $scale = $logo_width/$logo_qr_width;
            $logo_qr_height = $logo_height/$scale;
            imagecopyresampled($QR, $logo, $QR_width/3, $QR_height/3, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
            $savePath = getcwd().DIRECTORY_SEPARATOR."assets".DIRECTORY_SEPARATOR."dynamic".DIRECTORY_SEPARATOR."bar_codes".DIRECTORY_SEPARATOR.$order_id.".png";
            imagepng($QR, $savePath);
        }
        else
        {
        $bar_code = file_get_contents("https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=$info&choe=UTF-8");

        $file_name = getcwd().DIRECTORY_SEPARATOR."assets".DIRECTORY_SEPARATOR."dynamic".DIRECTORY_SEPARATOR."bar_codes".DIRECTORY_SEPARATOR.$order_id.".png";


        $file_handle = fopen($file_name, 'wb');
        fwrite($file_handle, $bar_code);
        }
    }
    
    
    function generateOrderNoImage($order_id, $order_number=null)
    {
        
        if (empty($order_number))
        {
            $CI = & get_instance();
            $CI->load->model('admin/ordersModel');

            $order = $CI->load->ordersModel->getOrderDetails($order_id);
            $order_number = $order['order_number'];
        }
        
        
        // Create the image
        $im = imagecreatetruecolor(420, 1250);

        // Create some colors
        $white = imagecolorallocate($im, 255, 255, 255);
        $grey = imagecolorallocate($im, 128, 128, 128);
        $black = imagecolorallocate($im, 0, 0, 0);
        imagefilledrectangle($im, 0, 0, 419,  1249, $white);

        // The text to draw
        $text = $order_number;
        // Replace path by your own font path
        $font = getcwd().DIRECTORY_SEPARATOR."assets".DIRECTORY_SEPARATOR."fonts".DIRECTORY_SEPARATOR."bebas.ttf";

        // Add some shadow to the text
        //~ imagettftext($im, 20, 90, 11, 21, $grey, $font, $text);

        // Add the text
        imagettftext($im, 270, 270, 7, 10, $black, $font, $text);

        $file_name = getcwd().DIRECTORY_SEPARATOR."assets".DIRECTORY_SEPARATOR."dynamic".DIRECTORY_SEPARATOR."order_nos".DIRECTORY_SEPARATOR.$order_id.".png";
        
        // Using imagepng() results in clearer text compared with imagejpeg()
        imagepng($im, $file_name);
        imagedestroy($im);
    }
    
    function orderImageExists($order_id, $type='both')
    {
        $return = false;
        
        if (!empty($order_id))
        {
            if ($type == 'both')
            {
                if (file_exists(getcwd()."/assets/dynamic/order_nos/$order_id.png") && file_exists(getcwd()."/assets/dynamic/bar_codes/$order_id.png"))
                {
                    if (filesize(getcwd()."/assets/dynamic/order_nos/$order_id.png") > 0 && filesize(getcwd()."/assets/dynamic/bar_codes/$order_id.png") > 0)
                    {
                        $return = true;
                    }
                }
            }
        }
        
        return $return;
    }
    
    function calculateDistanceBetweenCoordinates($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
    {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);
            
        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;
        
        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) + cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return $angle * $earthRadius;
    }
    
    function getOrderOutstanding($order_id)
    {
        $CI = & get_instance();
        $CI->load->model('admin/ordersModel');
        $order = $CI->ordersModel->getOrderOutstanding($order_id);
        
        $order['outstanding'] = (float) ($order['nett_total'] - $order['cash_collected'] - $order['voucher_cash']);
        return $order;
    }
    
    function validateCollectionStatus($status_record)
    { 
        $return = array('status' => 'success');
        
        if (empty($status_record['collection_date']) || $status_record['collection_date'] == '0000-00-00 00:00:00')
        {
            $return = array(
                'status' => 'error',
                'message' => "Collection date empty for {$status_record['order_number']}.",
                'code' => 472,
            );
        }
        else
        {
            $CI = & get_instance();
            
            $roles_config = $CI->config->item('roles');
            $role = strtolower(getUserRoleByUserId($status_record['employee_id']));
            $expected_role = strtolower($roles_config['driver']['role']);

            if ($role !== $expected_role)
            {
                $return = array(
                    'status' => 'error',
                    'message' => "Expected role $expected_role, $role encountered.",
                    'code' => 474,
                );
            }
            else
            {
                if (date('Y-m-d', strtotime($status_record['collection_date'])) == date('Y-m-d', strtotime($status_record['delivery_date'])))
                {
                    $status_scan_cool_off_time = $CI->config->item('status_scan_cool_off_time_in_mins_when_same_day');
                }
                else
                {
                    $status_scan_cool_off_time = $CI->config->item('status_scan_cool_off_time_in_mins');
                }

                $diff = time() - strtotime($status_record['status_update_date']);

                $years = floor($diff / (365*60*60*24));
                $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
                $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
                $hours = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24)/ (60*60));
                $minutes = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60);

                if ($minutes < $status_scan_cool_off_time)
                {
                    $return = array(
                        'status' => 'error',
                        'message' => "Cooling off period for consecutive scan is $status_scan_cool_off_time mins, but $minutes mins encountered.",
                        'code' => 473,
                    );
                }
            }
        }
        
        //Send message When the order status is collection assigned
        if($return['status'] == 'success')
        {
            $return = sendMsgToCustomerWhenCollectionAssigned($status_record);
        }  
        
        return $return;
    }
    
    function validateCollectionaAtWatehouseStatus($record)
    {
        $return = array('status' => 'success');
        
        $CI = & get_instance();
        $roles_config = $CI->config->item('roles');
        $role = strtolower(getUserRoleByUserId($record['employee_id']));
        $expected_role = strtolower($roles_config['warehousemanager']['role']);
        
        if ($role !== $expected_role)
        {
            $return = array(
                'status' => 'error',
                'message' => "Expected role $expected_role, $role encountered.",
                'code' => 474,
            );
        }
        
        return $return;
    }
    
    function validateDriverStatutes($record)
    {
        $return = array('status' => 'success');
        
        $CI = & get_instance();
        $roles_config = $CI->config->item('roles');
        $role = strtolower(getUserRoleByUserId($record['employee_id']));
        $expected_role = strtolower($roles_config['driver']['role']);
        
        if ($role !== $expected_role)
        {
            $return = array(
                'status' => 'error',
                'message' => "Expected role $expected_role, $role encountered.",
                'code' => 474,
            );
        }
        else if (($record['status'] == 'box_delivered' || $record['status'] == 'box_collected'))
        { 
            if($record['booked_by'] !== $record['employee_id'])
            {
                $CI->load->model('usermodel');
                $row_booked = $CI->usermodel->getUserById($record['booked_by']);
                $row_current = $CI->usermodel->getUserById($record['employee_id']);

                $return = array(
                    'status' => 'error',
                    'message' => "Scanned by driver {$row_current->username}, but booked by {$row_booked->username}.",
                    'code' => 477,
                );
            }
            else
            {
                $return['responsibility_completed'] = true;
            }
        }
        
        return $return;
    }
    
    function getUserRoleByUserId($user_id)
    {
        $CI = & get_instance();
        $CI->load->model('usermodel');
        $user = $CI->usermodel->getUserById($user_id);
        
        return $user->roleName;
    }
    
    function getEmployeesByRole($role, $geo_type = array('singapore', 'all'))
    {
        $CI = & get_instance();
        $CI->load->model('usermodel');
        $return = $CI->usermodel->getUsersByRole($role, $geo_type);
        
        $column = array_column($return, "name");
        array_multisort($column, SORT_ASC, $return);
        
        return $return;
    }
    

    function extractSearchParams($pagingParams, $type = 'default')
    {
        $return = array();

        switch ($type)
        {
            case 'order_listing':
                if (!empty($_GET['search_jkt_image_status']))
                {
                    $return['jkt_image_status'] = $_GET['search_jkt_image_status'];
                }
                if (!empty($_GET['search_customer_id']))
                {
                    $return['customer_id'] = $_GET['search_customer_id'];
                }
                if (!empty($_GET['search_order_number']))
                {
                    $return['order_number'] = $_GET['search_order_number'];
                }
                if (!empty($_GET['search_current_status']))
                {
                    $return['status'] = $_GET['search_current_status'];
                }
                if (!empty($_GET['search_picture_receive_date']))
                {
                    $return['picture_receive_date'] = $_GET['search_picture_receive_date'];
                }
                if (!empty($_GET['search_delivery_date']))
                {
                    $return['delivery_date'] = $_GET['search_delivery_date'];
                }
                if (!empty($_GET['search_collection_date']))
                {
                    $return['collection_date'] = $_GET['search_collection_date'];
                }
                if (!empty($_GET['search_phone']))
                {
                    $return['phone'] = $_GET['search_phone'];
                }
                if (!empty($_GET['search_shipment_batch_id']))
                {
                    $return['shipment_batch_id'] = $_GET['search_shipment_batch_id'];
                }
                if(!empty($_GET['search_order_date_from']))
                {
                    $return['order_date_from'] = $_GET['search_order_date_from'];
                }
                if(!empty($_GET['search_order_date_to']))
                {
                    $return['order_date_to'] = $_GET['search_order_date_to'];
                }
                if(!empty($_GET['driver_ids']))
                {
                    $return['driver_ids'] = explode(",", $_GET['driver_ids'][0]);
                }
                else
                {
                   if (!empty($_GET['search_order_date_from']))
                    {                    
                        $return['order_date_to'] = $_GET['search_order_date_to'];
                    }                 
                }
                break;

            case 'customer_listing':
                if (!empty($_GET['search_name']))
                {
                    $return['name'] = $_GET['search_name'];
                }
                if (!empty($_GET['search_mobile']))
                {
                    $return['mobile'] = $_GET['search_mobile'];
                }
                if (!empty($_GET['search_phone']))
                {
                    $return['residence_phone'] = $_GET['search_phone'];
                }
                if (!empty($_GET['search_pin']))
                {
                    $return['pin'] = $_GET['search_pin'];
                }
                if (!empty($_GET['search_address']))
                {
                    $return['address'] = $_GET['search_address'];
                }
                break;

            case 'agent_commission_listing':
                if (!empty($_GET['search_agent_id']))
                {
                    $return['agent_id'] = $_GET['search_agent_id'];
                }
                if (!empty($_GET['search_collection_date_from']))
                {
                    $return['collection_date_from'] = $_GET['search_collection_date_from'];
                }
                if (!empty($_GET['search_collection_date_to']))
                {
                    $return['collection_date_to'] = $_GET['search_collection_date_to'];
                }
                break;
        }

        if (!empty($return))
        {
            $pagingParams['search_params'] = $return;
        }

        return $pagingParams;
    }
    
    
    
    function determineResponsbilityCompleted(array $data)
    {
        $return = empty($data['statuses'][$data['status']]['responsibility_completed']) ? false : $data['statuses'][$data['status']]['responsibility_completed'];
        
        if ($return === true)
        {
            $collection_date = date('Y-m-d', strtotime($data['collection_date']));
            
            if ($data['status'] == 'box_delivered' && $collection_date === date('Y-m-d'))
            {
                $return = false;
            }
        }
        
        return is_bool($return) ?  ($return == true ? 'yes' : 'no') : $return;
    }
    
    function updateReceivingBatchStatus()
    {
        $CI = & get_instance();
        
        $CI->load->model('admin/receivingBatchesModel');
        $receivingBatchesArr = $CI->receivingBatchesModel->getAllReceivingBatchesOrders();
        
        if (empty($receivingBatchesArr))
        {
            $results = $CI->receivingBatchesModel->getAllReceivingBatches();
            
            if (!empty($results))
            {
                foreach ($results as $index => $row)
                {
                    $data = array(
                        'id' => $row['id'],
                        'status' => 'closed',
                    );
                    
                    $CI->receivingBatchesModel->saveReceivingBatch($data);
                }
            }
        }
        
    }
    
    function determineEODStatus($data, $type = 'count', $selectedDate = '')
    {
        $where = array(
                'order_status_trans.active' => 'yes',
                'order_status_trans.employee_id' => $data['employee_id'],
                'date(order_status_trans.updated_at)' => date('Y-m-d'),
            );
        
        if(!empty($selectedDate))
        {
            $where['date(order_status_trans.updated_at)'] = date('Y-m-d', strtotime($selectedDate));
        }
        
        $CI = & get_instance();
        $CI->load->model('ordersmodel');
        $result  = $CI->ordersmodel->getOrderStatusDetailsByCondition($where);

        if($type == 'count')
        {
            $return = count($result) > 0 ? 'no' : 'ready';
        }
        else
        {
            $return = $result;
        }

        return $return;
    }
    
    
    // $type = possible values can be normal(paid DB) or google (Google API)
    function getLatLongByPinCode($pincode, $type='google') 
    {
        $pincode = trim($pincode);
        $return = array(
            'lattitude' => 0,
            'longitude' => 0,
        );
        
        if (!empty($pincode))
        {
            switch ($type)
            {
                case 'google':
                    $url = 'http://maps.googleapis.com/maps/api/geocode/json?address='.$pincode."&sensor=true"; 
                    
                    $json = file_get_contents($url);
                    $array = json_decode($json);

                    if (!empty($array->results[0]))
                    {
                        $lat = $array->results[0]->geometry->location->lat;
                        $lng = $array->results[0]->geometry->location->lng;

                        if (!empty($lat))
                        {
                            $return['lattitude'] = $lat;
                        }

                        if (!empty($lng))
                        {
                            $return['longitude'] = $lng;
                        }
                    }
            }
        }
        
        return $return;
    }
    
    function getAllShipmentBatches()
    {
        $CI = & get_instance();
        $CI->load->model('admin/mastersModel');
        $result = $CI->mastersModel->getAllShipmentBatches();
        
        return $result;
    }
    
    function getAllReceivingBatches()
    {
        $CI = & get_instance();
        $CI->load->model('admin/receivingBatchesModel');
        $result = $CI->receivingBatchesModel->getAllReceivingBatches();
        
        return $result;
    }
    
    function canPerformAction($action, $userId)
    {
        $CI = & get_instance();
        $access_layer = $CI->config->item("access_layer");
        $action_allows_userIds = $access_layer[$action];
        $result = in_array($userId, $action_allows_userIds) ? true : false;
        return $result;
    }
    
    function canPerformEditOrder($order_status)
    {
        $result = false;
        $CI = & get_instance();
        $order_completed_lock_status = $CI->config->item("order_completed_lock_status");
        if(in_array($order_status, $order_completed_lock_status))
        {
            $result = true;
        }
        else
        {        
            $user_id = $CI->session->userdata['id'];
            $canPerFormEditAction = canPerformAction('order_edit_status', $user_id);
            if($canPerFormEditAction === true)
            {
                  $result = false;
            }
            else
            {
                $order_edit_lock_status = $CI->config->item("order_edit_lock_status");
                $result = in_array($order_status, $order_edit_lock_status) ? true : false; 
            }
        }
        return $result;
    } 
    
    function getStatusesByStatus($status,$direction = 'top',$inclusive = true)
    {  
        $result = array();
        $CI = & get_instance();
        $consolidated_statuses = $CI->config->item("consolidated_statuses");
        $statuses = array_keys($consolidated_statuses);
        $array_key_search = array_search($status,$statuses);
        $return_arr = array();
        if($direction == 'top')
        {
            if($inclusive === false)
            {
                $array_key_search += 1;
            }
            $return_arr = array_slice($statuses, $array_key_search);
        }
        else
        {
            if($inclusive === false)
            {
               $array_key_search += 1;
            }
            $return_arr = array_slice($statuses, 0, $array_key_search);
        }
        return $return_arr;
    }
    
    function getCurrentShipmentBatchId($order_id, $order_number)
    {
        $return = array(
            'error' => false,
            'message' => '',
            'data' => null,
        );
        
        $CI = & get_instance();
        
        //First pull out all boxes associated with order
        $order_boxes_info = getBoxesQuantitiesByOrderId($order_id);
        $order_box_ids = $order_boxes_info ['order_box_ids'];
        $order_box_quantities = $order_boxes_info ['order_box_quantities'];
        $order_box_names = $order_boxes_info ['order_box_names'];
        
        $CI->load->model('admin/mastersModel');
        $shipment_batch_row = $CI->mastersModel->getCurrentShipmentBatchDetails();
        
        //Then pull out all boxes defined for shipment batch
        $shipment_batch_box_ids = $shipment_batch_row['box_ids'];
       
        if (empty($shipment_batch_box_ids))
        {
            $return['error'] = true;
            $return['message'] = "Box(es) capacity not defined for {$shipment_batch_row['batch_name']}. Kindly report this incidence to Admin.";
        }
        else
        {
            $shipment_batch_box_ids = explode('@@##@@', $shipment_batch_box_ids);
            $box_names = explode('@@##@@', $shipment_batch_row['box_names']);
                
            $mapping_ids_quantities = array();
            
            //Check if any of the box in order is not defined for shipment batch, then its an error
            $missing_boxes = array_diff($order_box_ids, $shipment_batch_box_ids);

            if (empty($missing_boxes))
            {
                $quantities = explode('@@##@@', $shipment_batch_row['quantities']);
                $scanned_quantities = explode('@@##@@', $shipment_batch_row['scanned_quantities']);
                $mapping_ids = explode('@@##@@', $shipment_batch_row['mapping_ids']);
                
                foreach ($order_box_ids as $index => $box_id)
                {
                    $key = array_search($box_id, $shipment_batch_box_ids);
                    
                    $updated_scanned_quantity = $order_box_quantities[$index] + $scanned_quantities[$key];
                    $mapping_ids_quantities[$mapping_ids[$key]] = $updated_scanned_quantity;
                    
                    if ($updated_scanned_quantity > $quantities[$key])
                    {
                        $return['error'] = true;
                        $return['message'] = "Capacity exhaused for Order $order_number for Batch {$shipment_batch_row['batch_name']} => Box {$order_box_names[$index]}";
                        break;
                    }
                }
            }
            else
            {
                $tmp = array();
                foreach ($missing_boxes as $index => $box_id)
                {
                    $tmp[] = $order_box_names[$index];
                }
                $tmp = implode(', ', $tmp);
                
                $return['error'] = true;
                $return['message'] = "Seems some boxes ($tmp) for Order $order_number are not defined in {$shipment_batch_row['batch_name']} batch. Kindly report this incidence to Admin.";
            }
        }
        
        if (empty($return['error']))
        {
            $return['data'] = array(
                                    'shipment_batch' => $shipment_batch_row['batch_name'],
                                    'shipment_batch_id' => $shipment_batch_row['id'],
                                    'mapping_ids' => $mapping_ids_quantities,
                            );
        }
        
        return $return;
    }

    function getCurrentShipmentAndBoxCapacities()
    {
        $return = array(
            'shipment_batch' => null,
            'boxes' => array(),
        );
        
        $CI = & get_instance();
        
        $CI->load->model('admin/mastersModel');
        $shipment_batch_row = $CI->mastersModel->getCurrentShipmentBatchDetails();
        
        if (!empty($shipment_batch_row['batch_name']))
        {
            $return['shipment_batch'] = $shipment_batch_row['batch_name'];

            //Then pull out all boxes defined for shipment batch
            $shipment_box_names = $shipment_batch_row['box_names'];

            if (!empty($shipment_box_names))
            {
                $shipment_box_names = explode('@@##@@', $shipment_box_names);
                $quantities = explode('@@##@@', $shipment_batch_row['quantities']);
                $scanned_quantities = explode('@@##@@', $shipment_batch_row['scanned_quantities']);

                foreach ($shipment_box_names as $index => $shipment_batch)
                {
                    $return['boxes'][] = array(
                                                'name' => $shipment_batch,
                                                'capacity' => $quantities[$index], 
                                                'scanned' => $scanned_quantities[$index]
                                        );
                }
            }
        }
        
        return $return;
    }
    
    function getBoxesQuantitiesByOrderId($order_id)
    {
        $return = array('order_box_ids' => null, 'order_box_quantities' => null, 'order_box_names' => null);
     
        $CI = & get_instance();
        
        $CI->load->model('admin/ordersModel');
        $order_boxes_info = $CI->ordersModel->getOrderTransDetails($order_id);
        if (!empty($order_boxes_info))
        {
            foreach ($order_boxes_info as $index => $row)
            {
                $return['order_box_ids'][] = $row['box_id'];
                $return['order_box_quantities'][] = $row['quantity'];
                $return['order_box_names'][] = $row['box'];
            }
        }
        
        return $return;
    }
    
    function updateShipmentBatchStatusById($shipment_batch_id)
    {
        $CI = & get_instance();
        
        $CI->load->model('admin/mastersModel');
        $shipment_batch_row = $CI->mastersModel->getShipmentBatchById($shipment_batch_id);
        $shipment_batch_results = $CI->mastersModel->getShipmentBatchStatusByScannedCount($shipment_batch_id);
        
        //It means shipment batch is still active
        if (empty($shipment_batch_results))
        {
            $new_status = 'no';
        }
        else  //otherwise shipment batch is supposed to be closed
        {
            $new_status = 'yes';
        }
        
        if ($shipment_batch_row->status <> $new_status)
        {
            $data = array(
                'id' => $shipment_batch_id,
                'status' => $new_status,
            );
            $CI->mastersModel->saveShipmentBatch($data);
        }
    }
    
    function updateShipmentBatchBoxCountsByOrderId($order_id)
    {
        $CI = & get_instance();
        
        $CI->load->model('admin/ordersModel');
        $order_details = $CI->ordersModel->getOrderDetails($order_id);
        $order_boxes = $CI->ordersModel->getOrderTransDetails($order_id);
        
        $shipment_batch_id = $order_details['shipment_batch_id'];
        
        $CI->load->model('admin/mastersModel');
        $shipment_batch_row = $CI->mastersModel->getShipmentBatchById($shipment_batch_id, true);
        
        if (!empty($order_boxes))
        {
            $shipment_batch_row = (array) $shipment_batch_row;
            
            $shipment_batch_scanned_quantities = explode('@@##@@', $shipment_batch_row['scanned_quantities']);
            $shipment_batch_box_ids = explode('@@##@@', $shipment_batch_row['box_ids']);
            $shipment_batch_mapping_ids = explode('@@##@@', $shipment_batch_row['mapping_ids']);
            
            foreach ($order_boxes as $index => $row)
            {
                $order_box_id = $row['box_id'];
                $order_quantity = $row['quantity'];
                
                $key = array_search($order_box_id, $shipment_batch_box_ids);
                
                $data = array(
                    'id' => $shipment_batch_mapping_ids[$key],
                    'scanned_quantity' => $shipment_batch_scanned_quantities[$key] - $order_quantity,
                );
                $CI->mastersModel->saveShipmentBatchBoxMapping($data);
            }
        }
    }
    function sendMessageToCustomer($record)
    {       
    
        $time = date('d/m/Y');
        $CI = & get_instance();
        $CI->config->load('message_config');
        
        $Curlopt_Returntransfer = $CI->config->item('CURLOPT_RETURNTRANSFER');
        $Curlopt_Url = $CI->config->item('CURLOPT_URL');
        $Curlopt_Useragent = $CI->config->item('CURLOPT_USERAGENT');
        $Curlopt_Post = $CI->config->item('CURLOPT_POST');
        $senderid = $CI->config->item('senderid');
        
        $user = $CI->config->item('user');
        $password = $CI->config->item('password');
        $option = $CI->config->item('option');
        $luar_jawa_location_name = $CI->config->item('luar_jawa_location_name');
        
        
        $to     = $record['customer_number'];
        $customer_name  = $record['customer_name'];
        $order_number   = $record['order_number'];
        $recipient_name = $record['recipient_name'];
        $order_id = $record['order_id'];
        $order_location_name = $record['order_location_name'];
        
                
        if($order_location_name == $luar_jawa_location_name)
        {
            $msg_text  = $CI->config->item('msg_for_luar_jawa');
        }
        else
        {
            $msg_text  = $CI->config->item('msg_for_other_dc');
        }

        $select_to_replace = array("<CUSTOMER_NAME>", "<ORDER_NUMBER>", "<RECEIPENT_NAME>", "<TIME>");
        $replace_with   = array("$customer_name", "$order_number", "$recipient_name" , "$time");

        $msg = str_replace($select_to_replace, $replace_with, $msg_text);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => $Curlopt_Returntransfer,
            CURLOPT_URL => $Curlopt_Url,
            CURLOPT_USERAGENT => $Curlopt_Useragent,
            CURLOPT_POST => $Curlopt_Post,
            CURLOPT_POSTFIELDS => array(
            'user' => $user,
            'pwd' => $password,
            'option' => $option,
            'to'  => $to,
            'msg' => $msg,
            'senderid' => $senderid    
            )
        ));
        
        $response = curl_exec($curl);
         
        if (strpos($response, "OK") !== false) 
        {
            curl_close($curl);
            $save_time = date('Y-m-d h:i:s');
            $data = array(
                      "mobile_number" => $to,
                      "created_at" =>  $save_time,
                      "order_id" => $order_id,
                       "message" => $msg);
            $return = array('status' => 'success',
                            'data' => $data);
            return $return;
        }
    }
    
    function sendMessageShipOnboardToCustomer($record)
    {
        $time = date('d/m/Y');
        $CI = & get_instance();
        $CI->config->load('message_config');
        
        $Curlopt_Returntransfer = $CI->config->item('CURLOPT_RETURNTRANSFER');
        $Curlopt_Url = $CI->config->item('CURLOPT_URL');
        $Curlopt_Useragent = $CI->config->item('CURLOPT_USERAGENT');
        $Curlopt_Post = $CI->config->item('CURLOPT_POST');
        $senderid = $CI->config->item('senderid');
        
        $user = $CI->config->item('user');
        $password = $CI->config->item('password');
        $option = $CI->config->item('option');
        
        $msg  = $CI->config->item('msg_for_ship_onboard');
//        p($msg,0);
        $to = $record['customer_number'];
        $customer_name  = $record['customer_name'];
        $to = '+919079687855';
        
//        $select_to_replace = array("<CUSTOMER_NAME>", "<ORDER_NUMBER>", "<RECEIPENT_NAME>", "<TIME>");
//        $replace_with   = array("$customer_name", "$order_number", "$recipient_name" , "$time");

//        $msg = str_replace($select_to_replace, $replace_with, $msg_text);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => $Curlopt_Returntransfer,
            CURLOPT_URL => $Curlopt_Url,
            CURLOPT_USERAGENT => $Curlopt_Useragent,
            CURLOPT_POST => $Curlopt_Post,
            CURLOPT_POSTFIELDS => array(
            'user' => $user,
            'pwd' => $password,
            'option' => $option,
            'to'  => $to,
            'msg' => $msg,
            'senderid' => $senderid    
            )
        ));
        
        $response = curl_exec($curl);
//         p($response);
        if (strpos($response, "OK") !== false) 
        {
            curl_close($curl);
            $save_time = date('Y-m-d h:i:s');
            $data = array(
                      "mobile_number" => $to,
                      "created_at" =>  $save_time,
                       "message" => $msg);
            $return = array('status' => 'success',
                            'data' => $data);
            return $return;
        }
    }

    
    //Send message When the order status is collection assigned
    function sendMsgToCustomerWhenCollectionAssigned($record)
    {       
        $current_date = date('d-m-Y');
        $CI = & get_instance();
        $CI->config->load('message_config');

        $Curlopt_Returntransfer = $CI->config->item('CURLOPT_RETURNTRANSFER');
        $Curlopt_Url = $CI->config->item('CURLOPT_URL');
        $Curlopt_Useragent = $CI->config->item('CURLOPT_USERAGENT');
        $Curlopt_Post = $CI->config->item('CURLOPT_POST');
        $senderid = $CI->config->item('senderid');

        $user = $CI->config->item('user');
        $password = $CI->config->item('password');
        $option = $CI->config->item('option'); 

        $to     = $record['customer_number'];
        $customer_name  = $record['customer_name'];
        $order_number   = $record['order_number'];  
        $order_id   = $record['order_id']; 
        $msg_text  = $CI->config->item('msg_for_order_collection_assigned');


        $select_to_replace = array("<CUSTOMER_NAME>", "<ORDER_NUMBER>", "<DATE>" );
        $replace_with   = array("$customer_name", "$order_number", "$current_date");

        $msg = str_replace($select_to_replace, $replace_with, $msg_text);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => $Curlopt_Returntransfer,
            CURLOPT_URL => $Curlopt_Url,
            CURLOPT_USERAGENT => $Curlopt_Useragent,
            CURLOPT_POST => $Curlopt_Post,
            CURLOPT_POSTFIELDS => array(
            'user' => $user,
            'pwd' => $password,
            'option' => $option,
            'to'  => $to,
            'msg' => $msg,
            'senderid' => $senderid    
            )
        ));

        $response = curl_exec($curl); 

        if (strpos($response, "OK") !== false) 
        {
            curl_close($curl);
            $save_time = date('Y-m-d h:i:s');
            $data = array(
                      "mobile_number" => $to,
                      "created_at" =>  $save_time,
                      "order_id" => $order_id,
                       "message" => $msg);
            $return = array('status' => 'success',
                            'data' => $data);
            return $return;
        }
    }

if (!function_exists('set_locale_money_format')) 
{
    function set_locale_money_format($amount)
    {
        $ci = & get_instance();
        if(!empty($amount))
        {
            $formatter = new NumberFormatter("zh_sg",  NumberFormatter::CURRENCY);
            $output = preg_replace( '/[^0-9,"."]/', '', $formatter->formatCurrency($amount, "SDG"));
            return $output;
        }
        else
        {
            return false;
        }
    }
}