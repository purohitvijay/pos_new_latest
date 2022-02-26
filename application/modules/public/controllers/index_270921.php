<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Index extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');

        $languages = array("en" => "english", "id" => "bahasa");


        $lang = $this->uri->segment(2);

        if (isset($languages[$lang]))
            $this->lang->load('site', $languages[$lang]);
        else
            $this->lang->load('site', $this->config->item("language"));
    }

    public function index()
    {
        $this->load->setTemplate('blank');
        $this->load->view('/dashboard', array());       
    }
    
    public function index1()
    {
        $this->load->setTemplate('blank');
//        $this->load->view('/dashboard', array());
        $this->load->view('/header', array());
        $this->load->view('/dashboardTracking', array());
       
    }
    
    public function about()
    {
        $this->load->setTemplate('blank');
        $this->load->view('/header', array());
        $this->load->view('/about', array());
     
    }
    
     public function promo()
    {
        $this->load->setTemplate('blank');
        $this->load->view('/header', array());
        $this->load->view('/promo', array());
    }

    public function smstracking()
    {
        $this->load->setTemplate('blank');
        $this->load->view('/smsdashboard', array());
    }
    
     public function ordertracking()
    {      
        
        $phone = $this->input->post('phone_input');
        $order_input = $this->input->post('order_input');
        $checkDate = $this->uri->segment(3);
        $previousDate = "";
//        if($checkDate)
//        {
//            $phone = "84238190";
//            $order_input = "101190";
//            $previousDate = $checkDate;
//        }
        
        //Load
        $this->load->helper('url');
        $this->load->library('form_validation');

        $this->load->library('publiclogin');

        if ($this->publiclogin->loginpublic($phone, $order_input))
        {
            $this->load->model('admin/ordersmodel');
            $order_no = $order_input;
           
//            $order_no = $this->session->userdata('order_number');
            $order_id = $this->ordersmodel->getOrderId($order_no);
//  ***Get Order Status By Order Id***
            $order_data = $this->ordersmodel->getOrderStatusDataByOrderForPublic($order_id);
//            p($order_data,0);
            
            if (!empty($order_data))
            {
                $array_index = $order_data['resultSet'];
                $array_index_last = end(array_keys($order_data['resultSet']));
//                $order_data['resultSet'][$array_index_last]['jkt_received_date'] = "";
                $received_date = $order_data['resultSet'][$array_index_last]['jkt_received_date'];
//                p($order_data);
                //check for shipped
                if (isset($order_data['resultSet']))
                {
                    $shipped = 0;
                    foreach ($order_data['resultSet'] as $idx => $val)
                    {
                        if($val['status'] == "ready_for_receiving_at_jakarta")
                        {
                            $shipped++;
                        }
                    }
                }
//  *** In The Else Part Current Status Is Last Indexing Of Array ($order_data['resultSet']) ***                 
                    $current_status = "";

                    if (isset($order_data['resultSet'][$array_index_last]))
                    {
                        $current_status = $order_data['resultSet'][$array_index_last]['status'];
//                        if($current_status == "received_at_jakarta_warehouse")
//                        {
//                            $current_date = $order_data['resultSet'][$array_index_last]['jkt_received_date'] . " 14:00:00";
//                        }
//                        else
//                        {
                            $current_date = $order_data['resultSet'][$array_index_last]['updated_at'];
//                            if($previousDate != "")
//                            {
//                                $current_date = $previousDate;
//                            }
//                        }
                        $current_status_array[] = array('status' => $current_status, "date" => $current_date);
                    }
                    $ship_on_board = "";
                    $shipmentBatchId = $order_data['resultSet'][$array_index_last]['shipment_batch_id'];
                    $shipmentBatchData = $this->ordersmodel->getShipmentData($shipmentBatchId, $order_no);                    
                    if(!empty($shipmentBatchData))
                    {
                        $ship_on_board = $shipmentBatchData[0]['ship_onboard'];
                        if ($ship_on_board != '0000-00-00' && $ship_on_board != null)
                        {
                            $ship_on_board = date('Y-m-d', strtotime($ship_on_board));
                            $shipped = 1;
                        }
                        else
                        {
                            $ship_on_board = "";
                        }
                    }
                    
                    $array_count = count($array_index) - 2;
                    $previous_status = array();
                    if (isset($order_data['resultSet']))
                    {
                        for ($i = 0; $i <= $array_count; $i++)
                        {                            
                            $previous_status[] = array("status" => $array_index[$i]['status'], "date" => $array_index[$i]['updated_at']);
                        }
                        if($current_status == "collected_at_warehouse" && $ship_on_board != "")
                        {
                            $previous_status[] = array("status" => "ready_for_receiving_at_jakarta", "date" => $array_index[$i]['updated_at']);
                        }
                        if($current_status == "collected_at_warehouse" && $ship_on_board == "")
                        {
                            $shipped = 1;
                        }
                    }
                    
                    
            
//                    p($current_status,0);
//                    p($previous_status);
//                }
//  *** Load Config (tracking_status) ***  
                 
               if($shipped == 1)
               {
                  $config_status = array(
                    'box_collected',
                    'collected_at_warehouse',
                    'ready_for_receiving_at_jakarta',
                    'received_at_jakarta_warehouse',
                    'delivered_at_jkt_picture_not_taken',
                    'delivered_at_jkt_picture_taken'
                //    'recipient_received'
                );
               }
               else
               {
                   $config_status = array(
                        'box_collected',
                        'ready_for_receiving_at_jakarta',
                        'received_at_jakarta_warehouse',
                        'delivered_at_jkt_picture_not_taken',
                        'delivered_at_jkt_picture_taken'
                    //    'recipient_received'
                    );
               }
               
//               p($order_data);
//  *** Filter Current Status From Define In Config (tracking_status)              
                $current_filter_status = array();
                foreach ($current_status_array as $key => $value)
                {
                    if (in_array($value['status'], $config_status))
                    {
                        $current_filter_status[] = array("status" => $value['status'], "date" => $value['date']);
                    }
                }
                
//  *** Filter Previous Status From Define In Config (tracking_status)    
                foreach ($previous_status as $key => $value)
                {
                    if (in_array($value['status'], $config_status))
                    {
                        $previous_filter_status[] = array("status" => $value['status'], "date" => $value['date']);
                    }
                }
//  *** If In Array ($order_data['resultSet']) Get jkt_reference_no. Add Static Array In (previous_filter_status)                
                $reference_no = $order_data['resultSet'][$array_index_last]['jkt_reference_no'];
                if (!empty($reference_no))
                {
                    $previous_filter_status[] = array('status' => 'destination_luar_jawa', 'date' => '');
                }
                $receiver_name = $order_data['resultSet'][$array_index_last]['jkt_receiver'];
//  *** Add Receiver Name ***              
                if (!empty($receiver_name))
                {
                    $jkt_receiver_name = $receiver_name;
                }
            }
//  *** Get Image For Received At Jakarta by Order Id           
            $image_status = $this->ordersmodel->getImageForReceivedAtJakarta($order_id);            
            if (!empty($image_status))
            {
                foreach ($image_status as $idx => $row)
                {

                    $path = 'assets/dynamic/jkt_images/extracted_images/' . $row['order_image_master_id'] . "/" . $row['name'];
//                    $path = 'assets/dynamic/jkt_images/extracted_images/16549/272852.jpg';
                    if (file_exists($path))
                    {

                        $image_id[] = array("id" => $row['id'], "name" => $row['name'], "master_id" => $row['order_image_master_id'], "image_path" => $path);
                    }
                }
            }
            
            
//            p($order_data);
            //check for shipped status
            if(!empty($previous_filter_status))
            {
                $i = 1;
                foreach($previous_filter_status as $idx => $statusRec)
                {
                    if($statusRec['status'] == 'ready_for_receiving_at_jakarta' && $i == 2)
                    {
                        $previous_filter_status[$idx]['status'] = "Shipped"; 
                        if($ship_on_board != "")
                        {
                            $previous_filter_status[$idx]['date'] =  $ship_on_board;                        
                        }
                    }
                    if($statusRec['status'] == 'ready_for_receiving_at_jakarta')
                    {                        
                        $i++;
                    }
                    if($statusRec['status'] == 'collected_at_warehouse')
                    { 
                        $previous_filter_status[$idx]['status'] = "collected_at_warehouse"; 
                        $i++;
                    }                    
                }
            }
            
//            p($order_data,0);
//            echo $ship_on_board;exit;
            $nextStatus = array();
            //for
            if(!empty($current_filter_status))
            {
                $i = 1;
                foreach($current_filter_status as $idx => $statusRec)
                {
                    if($statusRec['status'] == 'box_collected')
                    {
                        $nextStatus = array("Singapore Warehouse","shipped",'Jakarta Warehouse',"Received", "Jakarta Image Link");
                        $current_filter_status[$idx]['status'] = "Collected";
                        $current_filter_status[$idx]['next_status'] = "Singapore Warehouse";
                    }
                    if($statusRec['status'] == 'collected_at_warehouse')
                    {                        
                        $i++;
                        $nextStatus = array("Shipped",'Jakarta Warehouse',"Received", "Jakarta Image Link");
                        $current_filter_status[$idx]['status'] = "Singapore Warehouse";
                        $current_filter_status[$idx]['next_status'] = "Ship on Board";
                        if($ship_on_board != "")
                         {
                            $current_filter_status[$idx]['date'] = $ship_on_board;
                         }
                    }
                    
                    if($statusRec['status'] == 'collected_at_warehouse' && $ship_on_board != "")
                    {                        
                        $i++;
                        $nextStatus = array('Jakarta Warehouse',"Received", "Jakarta Image Link");
                        $current_filter_status[$idx]['status'] = "Ship on Board";
                        
                         if($ship_on_board != "")
                         {
                            $current_filter_status[$idx]['date'] = $ship_on_board;
                         }
                         $current_filter_status[$idx]['next_status'] = "Jakarta Warehouse";
                    }
                    if($statusRec['status'] == 'ready_for_receiving_at_jakarta')
                    {
                        $current_filter_status[$idx]['next_status'] = "Jakarta Warehouse";
                        $nextStatus = array("Jakarta Warehouse","Received", "Jakarta Image Link");
                         $current_filter_status[$idx]['status'] = "Ship on Board";
                         if($ship_on_board != "")
                         {
                            $current_filter_status[$idx]['date'] = $ship_on_board;
                         }
                    }
                    
                    if($statusRec['status'] == 'ready_for_receiving_at_jakarta' && $i == 2)
                    {
                        $current_filter_status[$idx]['next_status'] = "Jakarta Warehouse";
                        $nextStatus = array("Jakarta Warehouse","Received", "Jakarta Image Link");
                         $current_filter_status[$idx]['status'] = "Ship on Board";
                         if($ship_on_board != "")
                         {
                            $current_filter_status[$idx]['date'] = $ship_on_board;
                         }
                    }
                    
                    if($statusRec['status'] == 'received_at_jakarta_warehouse' )
                    {
                        $current_filter_status[$idx]['next_status'] = "Received";
                        $nextStatus = array("Received", "Jakarta Image Link");
                        $current_filter_status[$idx]['status'] = "Jakarta Warehouse";
                    }
                    
                    if($statusRec['status'] == 'delivered_at_jkt_picture_not_taken' )
                    {
                        $nextStatus = array("Jakarta Image Link");
                        $current_filter_status[$idx]['status'] = "Received";
                    }
                    
                    if($statusRec['status'] == 'delivered_at_jkt_picture_taken' )
                    {
                        $nextStatus = array();
                        $current_filter_status[$idx]['status'] = "Jakarta Image Link";
                    }
                }
                $current_filter_status[$idx]['currentDate'] = date('Y-m-d');
            }
//            p($current_filter_status);
            $order_result = array(
                'order_number' => $order_no,
                'current_status' => isset($current_filter_status) ? $current_filter_status : false,
                'previous_status' => isset($previous_filter_status) ? $previous_filter_status : false,
                'next_status' => isset($nextStatus) ? $nextStatus : false,
                'image_id' => isset($image_id) ? $image_id : false,
                'receiver' => isset($jkt_receiver_name) ? $jkt_receiver_name : false,
            );
//p($order_result);
            $this->load->setTemplate('blank');
            $this->load->view('header');
            $this->load->view('/trackingresult', $order_result);
        }
        else
        {
            $error_msg = lang("Phone_number_and_Order_Number_not_valid");
            echo $error_msg;
//            $result = array(
//                'status' => 'error',
//                'msg' => $error_msg,
//            );
        }
//        echo json_encode($result);
    }
    public function result()
    { 
        $this->load->setTemplate('blank');

        $this->load->view('/result', array());
    }

    public function validate()
    {
        //Load
        $this->load->helper('url');
        $this->load->library('form_validation');

        $this->load->library('publiclogin');

        if ($this->publiclogin->loginpublic($this->input->post('phone_input'), $this->input->post('order_input')))
        {
            $this->load->model('admin/ordersmodel');
            $order_no = $this->input->post('order_input');
//            $order_no = $this->session->userdata('order_number');
            $order_id = $this->ordersmodel->getOrderId($order_no);
//  ***Get Order Status By Order Id***
            $order_data = $this->ordersmodel->getOrderStatusDataByOrderForPublic($order_id);
            if (!empty($order_data))
            {
                $array_index = $order_data['resultSet'];
                $array_index_last = end(array_keys($order_data['resultSet']));
                $received_date = $order_data['resultSet'][$array_index_last]['jkt_received_date'];
//  *** If jkt_received_date Field IN Order Table Is Not Equal TO (0000-00-00) Then Create A Static Status "recipient_received" ***           
                if ($received_date != '0000-00-00' && $received_date != null)
                {
                    $status_array = array('current_status' => 'recipient_received', 'previous_status' => 'received_at_jakarta_warehouse');
                    $merge = array_merge($order_data['resultSet'], $status_array);
                    $current_status = "";
                    if (isset($order_data['resultSet'][$array_index_last]))
                    {
                        $current_status = $merge['current_status'];
                        $current_date = $order_data['resultSet'][$array_index_last]['jkt_received_date'] . " 14:00:00";
                        $current_status_array[] = array('status' => $current_status, "date" => $current_date);
                    }
                    $previous_status = "";
                    if (isset($order_data['resultSet']))
                    {
                        foreach ($array_index as $idx => $rec)
                        {
                            $previous_status[] = array("status" => $rec['status'], "date" => $rec['updated_at']);
                        }
                    }
                }
                else
                {
//  *** In The Else Part Current Status Is Last Indexing Of Array ($order_data['resultSet']) ***                 
                    $current_status = "";

                    if (isset($order_data['resultSet'][$array_index_last]))
                    {
                        $current_status = $order_data['resultSet'][$array_index_last]['status'];
                        $current_date = $order_data['resultSet'][$array_index_last]['updated_at'];
                        $current_status_array[] = array('status' => $current_status, "date" => $current_date);
                    }

                    $array_count = count($array_index) - 2;
                    $previous_status = "";
                    if (isset($order_data['resultSet']))
                    {
                        for ($i = 0; $i <= $array_count; $i++)
                        {
                            $previous_status[] = array("status" => $array_index[$i]['status'], "date" => $array_index[$i]['updated_at']);
                        }
                    }
                }
//  *** Load Config (tracking_status) ***              
                $config_status = $this->config->item("tracking_status");
//  *** Filter Current Status From Define In Config (tracking_status)              
                $current_filter_status = array();
                foreach ($current_status_array as $key => $value)
                {
                    if (in_array($value['status'], $config_status))
                    {
                        $current_filter_status[] = array("status" => $value['status'], "date" => $value['date']);
                    }
                }
//  *** Filter Previous Status From Define In Config (tracking_status)    
                foreach ($previous_status as $key => $value)
                {
                    if (in_array($value['status'], $config_status))
                    {
                        $previous_filter_status[] = array("status" => $value['status'], "date" => $value['date']);
                    }
                }
//  *** If In Array ($order_data['resultSet']) Get jkt_reference_no. Add Static Array In (previous_filter_status)                
                $reference_no = $order_data['resultSet'][$array_index_last]['jkt_reference_no'];
                if (!empty($reference_no))
                {
                    $previous_filter_status[] = array('status' => 'destination_luar_jawa', 'date' => '');
                }
                $receiver_name = $order_data['resultSet'][$array_index_last]['jkt_receiver'];
//  *** Add Receiver Name ***              
                if (!empty($receiver_name))
                {
                    $jkt_receiver_name = $receiver_name;
                }
            }
//  *** Get Image For Received At Jakarta by Order Id           
            $image_status = $this->ordersmodel->getImageForReceivedAtJakarta($order_id);
            if (!empty($image_status))
            {
                foreach ($image_status as $idx => $row)
                {

                    $path = 'assets/dynamic/jkt_images/extracted_images/' . $row['order_image_master_id'] . "/" . $row['name'];

                    if (file_exists($path))
                    {

                        $image_id[] = array("id" => $row['id'], "name" => $row['name'], "master_id" => $row['order_image_master_id'], "image_path" => $path);
                    }
                }
            }

            $order_result = array(
                'order_number' => $order_no,
                'current_status' => isset($current_filter_status) ? $current_filter_status : false,
                'previous_status' => isset($previous_filter_status) ? $previous_filter_status : false,
                'image_id' => isset($image_id) ? $image_id : false,
                'receiver' => isset($jkt_receiver_name) ? $jkt_receiver_name : false,
            );

            $this->load->setTemplate('blank');
            $view = $this->load->view('/result', $order_result, true);
            $msg = lang("Login_successfully");
            $result = array(
                'status' => 'success',
                'msg' => $msg,
                'view' => $view
            );
        }
        else
        {
            $error_msg = lang("Phone_number_and_Order_Number_not_valid");
            $result = array(
                'status' => 'error',
                'msg' => $error_msg,
            );
        }
        echo json_encode($result);
    }
    
    public function showsmsresult()
    {  
                //Load
        $this->load->helper('url');
        $this->load->library('form_validation');

        $this->load->library('publiclogin');

        if ($this->publiclogin->loginpublic($this->input->post('phone_input'), $this->input->post('order_input')))
        {
            $this->load->model('admin/ordersmodel');
            $order_no = $this->input->post('order_input');
//            $order_no = $this->session->userdata('order_number');
            $order_id = $this->ordersmodel->getOrderId($order_no);
//  ***Get Order Status By Order Id***
            $order_data = $this->ordersmodel->getOrderStatusDataByOrderForPublic($order_id);
            if (!empty($order_data))
            {
                $array_index = $order_data['resultSet'];
                $array_index_last = end(array_keys($order_data['resultSet']));
                $received_date = $order_data['resultSet'][$array_index_last]['jkt_received_date'];
//  *** If jkt_received_date Field IN Order Table Is Not Equal TO (0000-00-00) Then Create A Static Status "recipient_received" ***           
                if ($received_date != '0000-00-00' && $received_date != null)
                {
                    $status_array = array('current_status' => 'recipient_received', 'previous_status' => 'received_at_jakarta_warehouse');
                    $merge = array_merge($order_data['resultSet'], $status_array);
                    $current_status = "";
                    if (isset($order_data['resultSet'][$array_index_last]))
                    {
                        $current_status = $merge['current_status'];
                        $current_date = $order_data['resultSet'][$array_index_last]['jkt_received_date'] . " 14:00:00";
                        $current_status_array[] = array('status' => $current_status, "date" => $current_date);
                    }
                    $previous_status = "";
                    if (isset($order_data['resultSet']))
                    {
                        foreach ($array_index as $idx => $rec)
                        {
                            $previous_status[] = array("status" => $rec['status'], "date" => $rec['updated_at']);
                        }
                    }
                }
                else
                {
//  *** In The Else Part Current Status Is Last Indexing Of Array ($order_data['resultSet']) ***                 
                    $current_status = "";

                    if (isset($order_data['resultSet'][$array_index_last]))
                    {
                        $current_status = $order_data['resultSet'][$array_index_last]['status'];
                        $current_date = $order_data['resultSet'][$array_index_last]['updated_at'];
                        $current_status_array[] = array('status' => $current_status, "date" => $current_date);
                    }

                    $array_count = count($array_index) - 2;
                    $previous_status = "";
                    if (isset($order_data['resultSet']))
                    {
                        for ($i = 0; $i <= $array_count; $i++)
                        {
                            $previous_status[] = array("status" => $array_index[$i]['status'], "date" => $array_index[$i]['updated_at']);
                        }
                    }
                }
//  *** Load Config (tracking_status) ***              
                $config_status = $this->config->item("tracking_status");
//  *** Filter Current Status From Define In Config (tracking_status)              
                $current_filter_status = array();
                foreach ($current_status_array as $key => $value)
                {
                    if (in_array($value['status'], $config_status))
                    {
                        $current_filter_status[] = array("status" => $value['status'], "date" => $value['date']);
                    }
                }
//  *** Filter Previous Status From Define In Config (tracking_status)    
                foreach ($previous_status as $key => $value)
                {
                    if (in_array($value['status'], $config_status))
                    {
                        $previous_filter_status[] = array("status" => $value['status'], "date" => $value['date']);
                    }
                }
//  *** If In Array ($order_data['resultSet']) Get jkt_reference_no. Add Static Array In (previous_filter_status)                
                $reference_no = $order_data['resultSet'][$array_index_last]['jkt_reference_no'];
                if (!empty($reference_no))
                {
                    $previous_filter_status[] = array('status' => 'destination_luar_jawa', 'date' => '');
                }
                $receiver_name = $order_data['resultSet'][$array_index_last]['jkt_receiver'];
//  *** Add Receiver Name ***              
                if (!empty($receiver_name))
                {
                    $jkt_receiver_name = $receiver_name;
                }
            }
//  *** Get Image For Received At Jakarta by Order Id           
            $image_status = $this->ordersmodel->getImageForReceivedAtJakarta($order_id);
            if (!empty($image_status))
            {
                foreach ($image_status as $idx => $row)
                {

                    $path = 'assets/dynamic/jkt_images/extracted_images/' . $row['order_image_master_id'] . "/" . $row['name'];

                    if (file_exists($path))
                    {

                        $image_id[] = array("id" => $row['id'], "name" => $row['name'], "master_id" => $row['order_image_master_id'], "image_path" => $path);
                    }
                }
            }

            $order_result = array(
                'order_number' => $order_no,
                'current_status' => isset($current_filter_status) ? $current_filter_status : false,
                'previous_status' => isset($previous_filter_status) ? $previous_filter_status : false,
                'image_id' => isset($image_id) ? $image_id : false,
                'receiver' => isset($jkt_receiver_name) ? $jkt_receiver_name : false,
            );

            $this->load->setTemplate('blank');
            $view = $this->load->view('/smsresult', $order_result, true);
            $msg = lang("Login_successfully");
            $result = array(
                'status' => 'success',
                'msg' => $msg,
                'view' => $view
            );
        }
        else
        {
            $error_msg = lang("Phone_number_and_Order_Number_not_valid");
            $result = array(
                'status' => 'error',
                'msg' => $error_msg,
            );
        }
        echo json_encode($result);
    }    
    public function downloadjakartaimage($image_id)
    {
        if (empty($image_id))
        {
            $return = array(
                'status' => 'error',
                'message' => 'Image id can not be empty',
            );
        }
        else
        {

            $this->load->model('admin/ordersModel');
            $row = $this->ordersModel->getAllOrderImageTrans(array('id' => $image_id), $single_row = true);

            if (empty($row))
            {
                $return = array(
                    'status' => 'error',
                    'message' => 'Record not found.',
                );
            }
            else
            {
                $dataValues = array(
                    'id' => $image_id,
                    'status' => 'downloaded',
                    'downloaded_by' => $this->_user_id,
                    'user_downloaded_at' => date('Y-m-d H:i:s'),
                );
                $this->ordersModel->saveOrderImageTransUserDownloadedAt($dataValues);

                $config_arr = $this->config->item("image_upload");
                $extraction_dir = $config_arr['extraction_dir'];

                $filePath = $extraction_dir . "/{$row['order_image_master_id']}/{$row['name']}";
                
                if (file_exists($filePath))
                {
                    $fileName = basename($filePath);
                    $fileSize = filesize($filePath);

                    // Output headers.
                    header("Cache-Control: private");
                    
                    header("Content-Length: " . $fileSize);
                    header("Content-Disposition: attachment; filename=" . $fileName);
                    
                    header('Content-type: image/*');
                    // Output file.
                    readfile($filePath);
                    exit();
                }
                else
                {
                    die('The provided file path is not valid.');
                }

                $return = array(
                    'status' => 'success',
                    'data' => $data,
                );
            }
        }

        echo json_encode($return);
    }

}
