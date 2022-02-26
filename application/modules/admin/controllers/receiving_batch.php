<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Receiving_batch extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('commonlibrary');
    }

    public function index()
    {
        $message = $this->session->flashdata('receivingBatchOperationMessage');
        $dataArray['message'] = $message;
        //load css
        $dataArray['local_css'] = array(
            'datatable', 'multiselect', 'multiselect_filter'
        );
        //load js
        $dataArray['local_js'] = array(
            'datatable', 'multiselect', 'multiselect_filter');

        $this->load->model('admin/receivingBatchesModel');
        $receivingBatchesArr = $this->receivingBatchesModel->getAllReceivingBatchesOrders();

        $shipmentBatchesArr = $this->receivingBatchesModel->getActiveShipmentBatches();


        $shipmentBatchesData = array();
        if (!empty($shipmentBatchesArr))
        {
            foreach ($shipmentBatchesArr as $id => $rec)
            {
                $shipmentBatchesData[$id]['id'] = $rec['id'];
                $shipmentBatchesData[$id]['name'] = $rec['batch_name'];
            }
        }
        $dataArray['shipmentBatchesArr'] = $shipmentBatchesData;
        $dataArray['receivingBatchesArr'] = $receivingBatchesArr;

        $this->load->view('receiving_batches/index', $dataArray);
    }

    public function getReceivingBatchesData()
    {
        $this->load->model('admin/receivingBatchesModel');
        $paginparam = $_GET;


        $result = $this->receivingBatchesModel->getAllReceivingBatches_r($paginparam, 'all');
        if(isset($result['foundRows']))
        {
            $total = $result['foundRows'];
            $receivingBatchesData = $result['resultSet'];
        }
        else
        {
            $total = count($result);
            $receivingBatchesData = $result;
        }

        $dataArray = array();
        if (!empty($receivingBatchesData))
        {
            foreach ($receivingBatchesData as $idx => $val)
            {
                if(!empty($val['shipment_batches_id']))
                {
                    $orderCountVal = $this->receivingBatchesModel->getAllReceivingBatches_box_order_count($val['shipment_batches_id']);
    //                p($orderCountVal);
                    $val['orders_count'] = $orderCountVal[0]['orders_count'];
                    $val['boxes_count'] = $orderCountVal[0]['boxes_count'];
                }
                else
                {
                     $val['orders_count'] = 0;
                    $val['boxes_count'] = 0;
                }
                $boxes_count = empty($val['boxes_count']) ? 0 : $val['boxes_count'];
                $orders_count = empty($val['orders_count']) ? 0 : $val['orders_count'];

                $receivingBatchesData[$idx]['count'] = "$boxes_count <b>(Boxes)</b><br>$orders_count <b>(Orders)</b>";

                $receivingBatchesData[$idx]['edit'] = "<a href='#' data-toggle='modal' data-target='#receivingBatchModal' class='fake-receiving-batch-class' rel='" . $val['id'] . "'><i class='fa fa-edit'></i></a>";
                if ($val['orders_count'] > 0)
                {
                    $receivingBatchesData[$idx]['pending_orders'] = "<a href='#' data-toggle='modal' data-target='#pendingOrderModal' class='fake-pending-orders-class' rel='" . $val['id'] . "'><i class='fa glyphicon-list'></i></a>";
//                    $receivingBatchesData[$idx]['view'] = "<a href='" . base_url() . "admin/order/index/0/" . $val['id'] . "' target='_new'><i class='fa glyphicon-eye_open'></i></a>"; 
                    if($val['status'] == "closed")
                    {
                        $receivingBatchesData[$idx]['report'] = "<a href='" . base_url() . "admin/receiving_batch/getReceivingBatchReport/" . $val['id'] . "' target='_new'><i class='fa glyphicon-list'></i></a>";
                        $receivingBatchesData[$idx]['download'] = "<a href='" . base_url() . "admin/receiving_batch/downloadReceivingBatchReport/" . $val['id'] . "' target='_new'><i class='fa glyphicon-download'></i></a>";
                    }
                    else
                    {
                         $receivingBatchesData[$idx]['report'] = "--";
                        $receivingBatchesData[$idx]['download'] = "--";
                    }
                }
                else
                {
                    $receivingBatchesData[$idx]['pending_orders'] = "--";
//                    $receivingBatchesData[$idx]['view'] = "--";
                    $receivingBatchesData[$idx]['report'] = "--";
                    $receivingBatchesData[$idx]['download'] = "--";
                }
            }
        }
        else
        {
            $receivingBatchesData = array();
        }

        $dataArray['iTotalRecords'] = $total;
        $dataArray['iTotalDisplayRecords'] = $total;
        $dataArray['sEcho'] = $paginparam['sEcho'];
        $dataArray['aData'] = $receivingBatchesData;

        echo json_encode($dataArray);
    }

    public function saveReceivingBatch()
    {
        $name = $this->input->post('name');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'required|trim|unique[receiving_batches.name.id.' . $this->input->post('receiving_batch_id') . ']');
        if ($this->form_validation->run() == FALSE)
        {
            $return = array(
                'status' => 'error',
                'msg' => 'Receiving Batch name already exists'
            );
        }
        else
        {
            $this->load->model('receivingBatchesModel');
            $this->load->model('mastersModel');
            $receiving_batch_id = $this->input->post('receiving_batch_id');

            if (!empty($receiving_batch_id))
            {
                //$this->receivingBatchesModel->updateRecevingBatchInShipment($receiving_batch_id);
            }

            $dataArray = array(
                'name' => $name,
            );

            $operation = 'add';

            if (!empty($receiving_batch_id))
            {
                $operation = 'update';

                $dataArray['id'] = $receiving_batch_id;
                $dataArray['updated_at'] = date('Y-m-d H:i:s');
                $this->session->set_flashdata('receivingBatchOperationMessage', 'Receiving Batch Updated Successfully.');
            }
            else
            {
                $dataArray['created_by'] = $this->_user_id;
                $dataArray['status'] = 'open';
                $dataArray['created_at'] = date('Y-m-d H:i:s');
                $this->session->set_flashdata('receivingBatchOperationMessage', 'Receiving Batch Added Successfully.');
            }


            $receiving_batch_id = $this->receivingBatchesModel->saveReceivingBatch($dataArray);


            $shipment_batches_id = $this->input->post('shipment_batches_selected');
            if (!empty($shipment_batches_id))
            {
                $statuses = $this->config->item('jakarta_statuses');
                $responsibility_completed = $statuses['ready_for_receiving_at_jakarta']['responsibility_completed'];

                $time = date('Y-m-d H:i:s');

                $order_status_data = array(
                    'responsibility_completed' => $responsibility_completed,
                    'created_at' => $time,
                    'updated_at' => $time,
                    'employee_id' => $this->_user_id,
                    'reassigned_stage' => 'no',
                    'active' => 'yes',
                    'status_escalation_type' => 'app',
                    'status' => 'ready_for_receiving_at_jakarta',
                );


                $order_ids = array();

                $this->load->model('ordersmodel');
                foreach ($shipment_batches_id as $idx => $rec)
                {
                    $shipment_batch_row = $this->mastersModel->getShipmentBatchById($rec);
                    $shipment_batch_row = (array) $shipment_batch_row;
                    //Process only those shipment batches for whom there is no receiving batch id defined yet
                    // IT was done for EDIT mode, only in case when new shipment batch is assigned
                    if (empty($shipment_batch_row['receiving_batch_id']))
                    {
                        $dataVal = array(
                            'id' => $rec,
                            'receiving_batch_id' => $receiving_batch_id
                        );
                        $this->mastersModel->saveShipmentBatch($dataVal);

                        //Commenting  below code as order status would be escalated in both EDIT and ADD mode as new shipment batch id
                        //can be added in EDIT mode
//                        if ($operation == 'add')
                        {
                            //Updating order statuses to next
                            $where = array(
                                'shipment_batch_id' => $rec
                            );
                            $orders = $this->ordersmodel->getAllOrders($where);

                            if (!empty($orders))
                            {
                                foreach ($orders as $index => $row)
                                {
                                    $order_status = $this->ordersmodel->getOrderStatusDetails($row['id']);
                                    $this->ordersmodel->saveOrderStatus(array('id' => $order_status['id'], 'active' => 'no', 'updated_at' => $time));

                                    $order_status_data['order_id'] = $row['id'];
                                    $this->ordersmodel->saveOrderStatus($order_status_data);

                                    $order_ids[] = $row['order_number'];
                                }
                            }
                        }
                    }
                }
            }

            $return = array(
                'status' => 'success',
//                'total_orders' => count($order_ids),
            );
        }
        echo json_encode($return);
    }

    public function receivingBatchEditForm()
    {
        $this->load->model('receivingBatchesModel');
        $receiving_batch_id = $this->input->post('receiving_batch_id');
        $shipmentBatchesArr = $this->receivingBatchesModel->getActiveShipmentBatches();
        $shipmentBatchesData = array();
        if (!empty($shipmentBatchesArr))
        {
            foreach ($shipmentBatchesArr as $id => $rec)
            {
                $shipmentBatchesData[$id]['id'] = $rec['id'];
                $shipmentBatchesData[$id]['name'] = $rec['batch_name'];
            }
        }
        $dataArray['shipmentBatchesArr'] = $shipmentBatchesData;
        //get receiving batch 
        $receiving_batch_rec = $this->receivingBatchesModel->getReceivingBatchById($receiving_batch_id);

        $dataArray['receiving_batch_id'] = $receiving_batch_id;

        if (!empty($receiving_batch_rec))
        {
            $dataArray['name'] = $receiving_batch_rec['name'];
            $dataArray['selected_shipment_batch_name'] = explode(",", $receiving_batch_rec['shipment_batches']);
            $dataArray['selected_shipment_batch_id'] = explode(",", $receiving_batch_rec['shipment_batches_id']);
        }
//        p($dataArray);
        $this->load->setTemplate('blank');
        $return = $this->load->view('receiving_batches/receivingBatchForm', $dataArray, false);

        echo $return;
    }

    public function getReceivingBatchReport($receiving_batch_id)
    {
        $this->load->library('reportlib');
        $data = $this->reportlib->getReceivingBatchData($receiving_batch_id);
        if (!empty($data))
        {
            $dataArray = array('records' => $data['records'],
                'batch' => $data['batch']);
        }
        else
        {
            $dataArray = array();
        }
//        p($data);
        $this->load->view('receiving_batches/receivingBatchReport', $dataArray);
    }

    public function downloadReceivingBatchReport($receiving_batch_id)
    {
        ini_set('memory_limit', '-1');

        $this->load->library('reportlib');
        $data = $this->reportlib->getReceivingBatchData($receiving_batch_id);

        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename=$receiving_batch_id.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo "S.NO" . "\t";
        echo "BOX" . "\t";
        echo "PENGIRIM" . "\t";
        echo "SIZE" . "\t";
        echo "QTY." . "\t";
        echo "TUJUAN" . "\t";
        echo "PENERIMA" . "\t";
        echo "URAIAN BARANG" . "\t";
        print("\n");

        foreach ($data['records'] as $idx => $value)
        {

            echo trim($idx + 1) . "\t";
            echo trim($value['order_number']) . "\t";
            echo trim($value['customer_name']) . "\t";
            echo trim(str_replace('@@##@@', ' | ', $value['box'])) . "\t";
            echo trim(str_replace('@@##@@', ' | ', $value['quantity'])) . "\t";
            echo trim(str_replace('@@##@@', ' | ', $value['location'])) . "\t";
            echo trim(str_replace(',', ' | ',$value['recipient_name'])) . "\t";
            echo trim(str_replace(',', ' | ',$value['recipient_item_list'])) . "\t";
            print("\n");
        }
    }

    public function getReceivingBatchPendingOrders()
    {
        $this->load->model('receivingBatchesModel');
        $receiving_batch_id = $this->input->post('receiving_batch_id');
        $dataArray = array();
        $receiving_batch_penging_data = $this->receivingBatchesModel->getReceivingBatchPendingOrders($receiving_batch_id);

        $dataArray['receiving_batch_pending_orders'] = $receiving_batch_penging_data;

        $this->load->setTemplate('blank');
        $return = $this->load->view('receiving_batches/receivingBatchPendingOrderDetails', $dataArray, false);
        echo $return;
    }

    public function getOrdersListingAtJakarta($receiving_batch_id = null)
    {
        $this->load->model('receivingBatchesModel');
        $receiving_batches = $this->receivingBatchesModel->getReceivingBatchArr();
        $dataArray = array();
        $receiving_batch_arr = array();
        if (!empty($receiving_batches))
        {
            foreach ($receiving_batches as $index => $receiving_batch)
            {
                $receiving_batch_arr[$index]['id'] = $receiving_batch['id'];
                $receiving_batch_arr[$index]['name'] = $receiving_batch['name'];
                $receiving_batch_arr[$index]['status'] = $receiving_batch['status'];
            }
        }

        $dataArray['receiving_batches'] = $receiving_batch_arr;
        //load css
        $dataArray['local_css'] = array(
            'datatable', 'bootstrap_date_picker',
        );
        //load js
        $dataArray['local_js'] = array(
            'datatable', 'bootstrap_date_picker','validation');

        $this->load->view('receiving_batches/updateOrderDetailsAtJakartaSide', $dataArray);
    }

    public function getOrdersDataAtJakartaSide()
    {
        $this->load->model('receivingBatchesModel');
        $receiving_batch_id = empty($_GET['search_receiving_batch_id']) ? '' : $_GET['search_receiving_batch_id'];
        $order_number = empty($_GET['search_order_number']) ? '' : $_GET['search_order_number'];
        $paginparam = $_GET;
        $total = 0;
        if (!empty($receiving_batch_id) || !empty($order_number))
        {
            $result = $this->receivingBatchesModel->getOrdersDataAtJakartaSide($paginparam, $receiving_batch_id, $order_number);
            $total = $result['foundRows'];
            $receivingBatchOrderData = $result['resultSet'];
            if (!empty($receivingBatchOrderData))
            {
                foreach ($receivingBatchOrderData as $index => $orderDetails)
                {
                    $receivingBatchOrderData[$index]['edit'] = "<a title='{$orderDetails['order_number']}' href='#' data-toggle='modal' data-target='#receivingBatchModal' class='fake-receiving-batch-class' rel='" . $orderDetails['id'] . "'><i class='fa fa-edit'></i></a>";
                }
            }
        }
        else
        {
            $receivingBatchOrderData = array();
        }


        $dataArray['iTotalRecords'] = $total;
        $dataArray['iTotalDisplayRecords'] = $total;
        $dataArray['sEcho'] = $paginparam['sEcho'];
        $dataArray['aData'] = $receivingBatchOrderData;

        echo json_encode($dataArray);
    }

    public function getOrderDetailsByIdJkt()
    {
        $this->load->model('ordersModel');
        $order_id = $this->input->post('order_id');
        $order_number = $this->input->post('order_number');
        $orderData = $this->ordersModel->getOrderDetails($order_id);
        $order_status_data = $this->ordersModel->getOrderStatus($order_number);
        $order_status = $order_status_data['status'];
 
        $orderTransDetails = $this->ordersModel->getOrderTransDetails($order_id);
        $capture_weight = "no";
        $statuses = $this->config->item('consolidated_statuses');
        $display_status_text = $statuses[$order_status]['display_text'];
        if (!empty($orderTransDetails))
        {
            foreach ($orderTransDetails as $index => $orderDetails)
            {
                if ($orderDetails['capture_weight'] == "yes")
                {
                    $capture_weight = "yes";
                }
            }
        }

        if (!empty($orderData))
        {
            $received_date = $orderData['jkt_received_date'];
            if ($received_date == "0000-00-00" || empty($received_date))
            {
                $received_date = "";
            }
            else
            {
                $received_date = date('d/m/Y', strtotime($orderData['jkt_received_date']));
            }
        }
        
        $dataArray = array(
            'order_id' => $order_id,
            'jkt_weight' => $orderData['jkt_weight'],
            'weight' => $orderData['weight'],
            'jkt_reference_no' => $orderData['jkt_reference_no'],
            'jkt_received_date' => $received_date,
            'is_weight_capture' => $capture_weight,
            'jkt_receiver' => $orderData['jkt_receiver'],
            'order_status' => $order_status,
            'display_status_text' => $display_status_text
        );
        //load css
        $dataArray['local_css'] = array(
            'bootstrap_date_picker',
        );
        //load js
        $dataArray['local_js'] = array(
            'bootstrap_date_picker',);

        $this->load->setTemplate('blank');
        $return = $this->load->view('receiving_batches/orderUpdateFormJkt', $dataArray, false);
        echo $return;
    }

    public function updateOrderDetails()
    {
        $is_weight_capture = $this->input->post('is_weight_capture');
        $jkt_received_date = $this->input->post('received_date');
        
        
        if (empty($jkt_received_date))
        {
            $jkt_received_date = NULL;
        }
        else
        {
            list($day, $month, $year)  = explode('/', $jkt_received_date);
            $jkt_received_date = "$year-$month-$day";
        }
       
        $dataValues = array(
            'jkt_received_date' => $jkt_received_date,
            'jkt_receiver' => $this->input->post('jkt_receiver'),
            'id' => $this->input->post('order_id')
        );
        if ($is_weight_capture == "yes")
        {
            $dataValues['jkt_weight'] = $this->input->post('jkt_weight');
            $dataValues['jkt_reference_no'] = $this->input->post('jkt_reference_no');
        }
            
           $this->load->model('ordersModel');
  
           if($jkt_received_date)
           {
            $order_id = $this->input->post('order_id');
            $this->load->model('admin/ordersmodel');
 	    $get_order_stauts = $this->ordersmodel->getOrderStatusDetails($order_id);
            
            if($get_order_stauts['status'] == "received_at_jakarta_warehouse")
            {   
               
                $CI = & get_instance();
                $order_id = $this->input->post('order_id');   

                $get_order_number = $this->ordersModel->getOrderNumberById($order_id);
                $manually_escalated_api = $this->config->item('manually_escalated_api');
                $employee_id = $this->session->userdata['id'];

                $Curlopt_Returntransfer = $CI->config->item('CURLOPT_RETURNTRANSFER');
                $Curlopt_Url = base_url().$manually_escalated_api;
                $Curlopt_Useragent = $CI->config->item('CURLOPT_USERAGENT');
                $Curlopt_Post = $CI->config->item('CURLOPT_POST');

                $manually_order_lattitude = $CI->config->item('manually_order_lattitude');
                $manually_order_longitude = $CI->config->item('manually_order_longitude');
                $manually_order_dc_lattitude = $CI->config->item('manually_order_dc_lattitude');
                $manually_order_dc_longitude = $CI->config->item('manually_order_dc_longitude');
                $order_manual_entry = $CI->config->item('order_manual_entry');
                $distribution_center_id = $CI->config->item('distribution_center_id');
                $jkt_receiver = $this->input->post('jkt_receiver');
                $status_escalation_type = $CI->config->item('status_escalation_type');   

                $curl = curl_init();
                curl_setopt_array($curl, array(
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_RETURNTRANSFER => $Curlopt_Returntransfer,
                CURLOPT_URL => $Curlopt_Url,
                CURLOPT_USERAGENT => $Curlopt_Useragent,
                CURLOPT_POST => $Curlopt_Post,
                CURLOPT_POSTFIELDS => array(
                'order_id' => $get_order_number['order_number'],
                'employee_id' =>  $employee_id,
                'lattitude' => $manually_order_lattitude,
                'longitude' =>  $manually_order_longitude,
                'dc_lattitude' =>  $manually_order_dc_lattitude,
                'dc_longitude' =>  $manually_order_dc_longitude,
                'manual_entry' =>  $order_manual_entry,
                'dist_center_id' => $distribution_center_id,
                'jkt_receiver' => $jkt_receiver,
                'status_escalation_type' => $status_escalation_type
                )
                ));
                $response = curl_exec($curl); 
            }
           }
        
        $data = $this->ordersModel->saveOrder($dataValues);
        
        $return = array(
            'status' => 'success'
        );
        echo json_encode($return);
    }
    
    public function getOrderWeightListingJkt()
    {
        $this->load->model('receivingBatchesModel');
        $this->load->model('mastersModel');
        $receiving_batches = $this->receivingBatchesModel->getReceivingBatchArr();
        $dataArray = array();
        $receiving_batch_arr = array();
        if (!empty($receiving_batches))
        {
            foreach ($receiving_batches as $index => $receiving_batch)
            {
                $receiving_batch_arr[$index]['id'] = $receiving_batch['id'];
                $receiving_batch_arr[$index]['name'] = $receiving_batch['name'];
                $receiving_batch_arr[$index]['status'] = $receiving_batch['status'];
            }
        }
        $dataArray['locations'] = $this->mastersModel->getAllLocations();        
        $dataArray['receiving_batches'] = $receiving_batch_arr;
        //load css
        $dataArray['local_css'] = array(
            'datatable'
        );
        //load js
        $dataArray['local_js'] = array(
            'datatable');

        $this->load->view('receiving_batches/weightReportsJkt', $dataArray);
    }
    
    public function getOrdersWeighReportsJkt()
    {
        
        $this->load->model('receivingBatchesModel');
        $receiving_batch_id = $_GET['search_receiving_batch_id'];
        $weight_discrepancy = $_GET['weight_discrepancy'];
        $box_not_received = $_GET['box_not_received'];
        $box_received = $_GET['box_received'];
        $shipment_batch_id = $_GET['search_shipment_batch_id'];
        $location_id = $_GET['search_locations_id'];
        $kabupaten_id = $_GET['search_kabupaten_id'];
        $order_number = empty($_GET['search_order_number']) ? '' : $_GET['search_order_number'];
        
        $paginparam = $_GET;
        $total = 0;
        
        $receivingBatchOrderData = array();
        
        if (!empty($receiving_batch_id))
        {
            $result = $this->receivingBatchesModel->getOrdersDataAtJakartaSide($paginparam, $receiving_batch_id, $order_number, $weight_discrepancy, $box_not_received,$shipment_batch_id,$location_id,$kabupaten_id,$box_received);
            $total = $result['foundRows'];
            $receivingBatchOrderData = $result['resultSet'];
            if (!empty($receivingBatchOrderData))
            {
                foreach ($receivingBatchOrderData as $index => $orderDetails)
                {
                    $received_date = $orderDetails['jkt_received_date'];
                    $jkt_weight = $orderDetails['jkt_weight'];
                    
                    if($received_date == "0000-00-00")
                    {
                        $received_date = "N/A";
                    }
                    $receivingBatchOrderData[$index]['jkt_received_date'] = $received_date;
                    $receivingBatchOrderData[$index]['jkt_weight'] = empty($jkt_weight) ? 0.00 : $jkt_weight;
                }
            }
            else
            {
                $receivingBatchOrderData = array();
            }
            
        }


        $dataArray['iTotalRecords'] = $total;
        $dataArray['iTotalDisplayRecords'] = $total;
        $dataArray['sEcho'] = $paginparam['sEcho'];
        $dataArray['aData'] = $receivingBatchOrderData;

        echo json_encode($dataArray);
    }

    public function getShipmentBatchesJkt($order_direction = null)
    {
        $this->load->model('receivingBatchesModel');
        $receiving_batch = $this->input->post('receiving_batch');
        $shipmentBatchesArr = $this->receivingBatchesModel->getActiveShipmentBatches($receiving_batch, $order_direction);
        $html = "";
        if(!empty($shipmentBatchesArr))
        {
            foreach($shipmentBatchesArr as $idx => $shipmentRecord)
            {
                $html .= "<option value='".$shipmentRecord['id']."'>".$shipmentRecord['batch_name']."</option>";
            }
        }
        
        echo $html;
    }
    
    public function getKabupatenByLocationId()
    {
        $this->load->model('mastersModel');
        $location_id = $this->input->post('location_id');
        $kabupaten_arr = $this->mastersModel->getKabupatensByLocationId($location_id);
        $html = "";
        if(!empty($kabupaten_arr))
        {
            foreach($kabupaten_arr as $idx => $record)
            {
                $html .= "<option value='".$record['id']."'>".$record['name']."</option>";
            }
        }
        echo $html;
                
    }
    
    public function getLocationDropdown()
    {
        $this->load->model('receivingBatchesModel');
        $receiving_batch_id = $_POST['search_receiving_batch_id'];
        $weight_discrepancy = $_POST['weight_discrepancy'];
        $box_not_received = $_POST['box_not_received'];
        $box_received = $_POST['box_received'];
        $shipment_batch_id = $_POST['search_shipment_batch_id']; 
              
        
        $receivingBatchOrderData = array();
        $html = "";
        if (!empty($receiving_batch_id))
        {
            $location_arr = $this->receivingBatchesModel->getOrdersDataAtJakartaSideLocation($receiving_batch_id, $weight_discrepancy, $box_not_received,$shipment_batch_id,$box_received);
            if(!empty($location_arr))
            {
                foreach($location_arr as $idx => $record)
                {
                    $html .= "<option value='".$record['location_id']."'>".$record['location_name']."</option>";
                }
            }
        
        }
        echo $html;
    }
    
    
   
}
