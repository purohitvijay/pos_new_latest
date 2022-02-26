<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class API extends REST_Controller
{

    public function __construct()
    {
       parent::__construct();
        
        $encode = $this->input->get('encode');
        if (!empty($encode))
        {
            ob_start('ob_gzhandler');
        }
    }

    public function validateLogin_post()
    {
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        
        $this->load->library('Restapilib');
        $data = $this->restapilib->validateLogin($username, $password);
        $this->response($data);
    }

    public function validateLoginJakarta_post()
    { 
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        
        $this->load->library('Restapilib');
        $data = $this->restapilib->validateLoginJakarta($username, $password);
        $this->response($data);
    }

    public function updateStatus_post()
    {  
        $order_id = $this->input->post('order_id');
        $employee_id = $this->input->post('employee_id');
        $shipment_batch_id = $this->input->post('shipment_batch_id');
        
        $lattitude = $this->input->post('lattitude');
        $longitude = $this->input->post('longitude');
        
        $metadata = $this->input->post('metadata');
        $metadata = empty($metadata) ? '' : serialize(json_decode($metadata));
        
        $manual_entry = $this->input->post('manual_entry');
        if($manual_entry == true)
        {  
            $this->load->model('restapimodel');
            $orders_data = $this->restapimodel->getOrderId($order_id);
            if(empty($orders_data))
            {
                $data = array(
                    "status" => "error",
                    'code' => '467',
                    "message" => "Please enter correct order number"
                );
                $this->response($data);
            }
            else
            {
                $order_id = $orders_data['id']; 
            }
        }
        $array = array(
            'order_id' => trim($order_id),
            'employee_id' => $employee_id,
            'lattitude' => $lattitude,
            'longitude' => $longitude,
            'shipment_batch_id' => $shipment_batch_id,
            'metadata' => $metadata,
            'qr_manual_entry' => $manual_entry
         );
        
        $this->load->library('Restapilib');
        $data = $this->restapilib->updateStatus($array);
        $this->response($data);
    }

    public function updateStatusJakarta_post()
    { 
        $this->load->model('restapimodel');
        $dist_center_id = $this->input->post('dist_center_id');
        $jkt_receiver = $this->input->post('jkt_receiver');
        $order_id = $this->input->post('order_id');
        $employee_id = $this->input->post('employee_id');
        
        $get_employee_role = $this->restapimodel->getEmployeeRole($employee_id);
        $status_escalation_type = $this->input->post('status_escalation_type');
        if(empty($status_escalation_type))
        {
            $status_escalation_type = 'app';
        }
        
        $lattitude = $this->input->post('lattitude');
        $longitude = $this->input->post('longitude');
        
        $dc_lattitude = $this->input->post('dc_lattitude');
        $dc_longitude = $this->input->post('dc_longitude');
        $metadata = $this->input->post('metadata');
        $metadata = empty($metadata) ? '' : serialize(json_decode($metadata));
        $manual_entry = $this->input->post('manual_entry');
        
        if($manual_entry == true)
        {  
            $order_number = $order_id;
            $orders_data = $this->restapimodel->getOrderId($order_id);
            if(empty($orders_data))
            {
                $data = array(
                    "status" => "error",
                    'code' => '467',
                    "message" => "Please enter correct order number"
                );
                $this->response($data);
            }
            else
            {
                $order_id = $orders_data['id'];
            }
        }
        else
        {
            $orders_data = $this->restapimodel->getOrderNumber($order_id);
            if(!empty($orders_data))
            {
                $order_number = $orders_data['order_number'];
            }
        }
        
        $get_locations_id =  $this->restapimodel->getLocations($order_id);
        $order_location_id = $get_locations_id['location_id'];
        $order_location_name = $get_locations_id['name'];

        //set dist_center_id value is -1 when order is escalated manually via order edit and in edit order under the update order
        if($get_employee_role['RoleName'] == "Driver")
        {
           $distributon_centers_name = $this->restapimodel->getAllDistributionCenters($dist_center_id);
           $location_name =  $distributon_centers_name[0]['locations_name'] = str_replace('@@##@@', ',', $distributon_centers_name[0]['locations_name']);
        
            if(!empty($get_locations_id))
            { 
                //check distribution center location and order location is same or not if not same it's display errors.
                $chk_location_exists =  $this->restapimodel->CheckLocationsExists($order_id, $dist_center_id, $order_location_id);
                if(empty($chk_location_exists))
                {
                    $data = array(
                        "status" => "error",
                        'code' => '467',
                        "message" => "The order number $order_number does not belongs to Distribution center {$location_name}"
                    );
                    $this->response($data);   
                }
            }
        }
        
        $array = array(
            'order_id' => trim($order_id),
            'order_number' => $order_number,
            'employee_id' => $employee_id,
            'lattitude' => $lattitude,
            'longitude' => $longitude,
            'metadata' => $metadata,
            'dc_lattitude' => $dc_lattitude,
            'dc_longitude' => $dc_longitude,
            'qr_manual_entry' => $manual_entry,
            'jkt_receiver' => $jkt_receiver,
            'order_location_name' => $order_location_name,
            'status_escalation_type' => $status_escalation_type
        );
         
        $this->load->library('Restapilib');
        $data = $this->restapilib->updateStatusJakarta($array);
        $this->response($data);
    }

    public function updateEODStatus_post()
    {
        $employee_id = $this->input->post('employee_id');
        
        $metadata = $this->input->post('metadata');
        $metadata = empty($metadata) ? '' : serialize($metadata);
        
        $array = array(
            'employee_id' => $employee_id,
            'date' => date('Y-m-d'),
            'time' => date('H:i:s'),
            'status' => 'yes',
            'metadata' => $metadata,
        );
        
        $this->load->library('Restapilib');
        $data = $this->restapilib->updateEODStatus($array);
        $this->response($data);
    }

    public function getEODStatus_get()
    {
        $employee_id = $this->input->get('employee_id');
        $date = $this->input->get('date');
        
        if (empty($date))
        {
            $date = date('Y-m-d');
        }
        
        $array = array(
            'employee_id' => $employee_id,
            'date' => $date
        );
        
        $this->load->library('Restapilib');
        $data = $this->restapilib->getEODStatus($array);
        $this->response($data);
    }

    public function updateCashCollectionDetails_post()
    {
        $id = $this->input->post('id');
        $cash_collected = $this->input->post('cash_collected');
        $voucher_cash = $this->input->post('voucher_cash');
        $comments = $this->input->post('comments');
        
        $array = array(
            'id' => $id,
            'cash_collected' => $cash_collected,
            'voucher_cash' => $voucher_cash,
            'comments' => $comments,
        );
        
        $this->load->library('Restapilib');
        $data = $this->restapilib->updateCashCollectionDetails($array);
        $this->response($data);
    }

    public function updateEmployeeOrderOrdering_post()
    {
        $order_ids = $this->input->post('order_ids');
        $order_nos = $this->input->post('order_nos');
        $employee_id = $this->input->post('employee_id');
        $type = $this->input->post('type');
        
        $array = array(
            'order_ids' => $order_ids,
            'order_nos' => $order_nos,
            'employee_id' => $employee_id,
            'type' => $type,
            'date' => date('Y-m-d'),
        );
        
        $this->load->library('Restapilib');
        $data = $this->restapilib->updateEmployeeOrderOrdering($array);
        $this->response($data);
    }

    public function getDateWiseTaskListingByEmployee_get()
    {
        $employee_id = $this->input->get('employee_id');
        $date = $this->input->get('date');
        $date = empty($date) ? date('Y-m-d') : $date;
        
        $array = array(
            'employee_id' => $employee_id,
            'date' => $date,
        );
        
        $this->load->library('Restapilib');
        $data = $this->restapilib->getDateWiseTaskListingByEmployee($array);
        
        $this->response($data);
    }
    
    public function getCurrentShipmentAndBoxCapacities_get()
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
            $data['shipment_batch'] = $shipment_batch_boxes['shipment_batch'];
            $data['boxes'] = $shipment_batch_boxes['boxes'];
            
            $return = array(
                'status' => 'success',
                'code' => '200',
                'data' => $data
            );
        }
        
        $this->response($return);
    }
    
    public function getURLs_get()
    {
        $data = array(
                        'status' => 'success',
                        'code' => '200',
                        'data' => array(
                                        'base_url' => 'http://allgotit.com/', 
                                        'product_url' => 'http://allgotit.com/', 
                                )
                    );
        
        $this->response($data);
    }
    
    public function pingTest_get()
    {
        $data = array(
                        'status' => 'success',
                        'code' => '200',
                        'data' => 'I am alive'
                    );
        
        $this->response($data);
    }
    public function getOrderListingJkt_get()
    {   
        $center_id = $this->input->get('center_id');
        if (empty($center_id))
        {
            $result_data = array(
                'status' => 'error',
                'code' => '467',
                'message' => 'Center cannot be empty',
            );
            $this->response($result_data);
        }
        else
        {
            $this->load->model('restapimodel');
            $getOrdersListing = $this->restapimodel->checkCenterLocations($center_id);
            if(empty($getOrdersListing))
            {
                $result_data = array(
                    'status' => 'error',
                    'code' => '468',
                    'message' => 'Please enter a valid center',
                );
                $this->response($result_data);
            }
            else
            {
                $this->load->library('Restapilib');
                $getOrdersListing = $this->restapilib->getOrdersListingJkt($center_id);
                $this->response($getOrdersListing);
            }
        }
    }
    public function updateOrderImageJkt_post()
    {    
        $this->load->library('Restapilib');
        $order_id = $this->input->post('order_id');
        $employee_id = $this->input->post('employee_id');
        
        if (empty($order_id) || empty($employee_id) || empty($_FILES))
        {
            $result_data = array(
                'status' => 'error',
                'code' => '467',
                'message' => 'order/employee/image cannot be empty',
            );
        }
        else
        {
            $manual_entry = $this->input->post('manual_entry');
            if($manual_entry == true)
            {  
                $this->load->model('restapimodel');
                $orders_data = $this->restapimodel->getOrderId($order_id);
                if(!empty($orders_data))
                {
                 $order_id = $orders_data['id'];
                }
            }
            $name = $_FILES['image']['name'];
            $fileMetaData = pathinfo($name);
            if($fileMetaData['extension'] != 'zip') 
            {
                $result_data = array(
                    'status' => 'error',
                    'message' => 'Please upload a valid zip file'
                    );
            }
            else
            {
                $zipfile = $_FILES['image']['name'];
                $tmp_name = $_FILES['image']['tmp_name'];
                $img_error = $_FILES['image']['error'];
                if($img_error != 0)
                {
                    $result_data = array(
                    'status' => 'Error',
                    'message' => 'something went wrong'
                    );
                }
                else
                {
                    $result_data = array(
                    'status' => 'success',
                    );
                }
            }
        }
        if($result_data['status'] == "success" )
        {
            $data = array(
                    'image' => $zipfile,
                    'tmp_name' => $tmp_name,
                    'order_id' => $order_id
                );
            
            $order_data = array(
                        'order_id' => $order_id
                    );
            $result_data = $this->restapilib->saveOrderImageJkt($data,$order_data);
        } 
        $this->response($result_data);
    }
}
