<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Report extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('commonlibrary');

        $this->load->library('reportlib');
    }

    public function photoReports()
    {
        $this->load->model('mastersModel');
        //load css
        $dataArray['local_css'] = array(
            'datatable', 'bootstrap_date_picker', 'multiselect', 'multiselect_filter'
        );
        //load js
        $dataArray['local_js'] = array(
            'datatable', 'bootstrap_date_picker', 'multiselect', 'multiselect_filter'
        );
        
        $shipmentBatchesArr = $this->mastersModel->getShipmentBatchArr('desc');
        $dataArray['shipmentBatchesArr'] = $shipmentBatchesArr;
        
//        p($dataArray['shipmentBatchesArr']);
        $this->load->view('reports/photoReports', $dataArray);
    }
    
    public function getPhotoReportsData()
    {
        $this->load->model('receivingBatchesModel');
        
        $pagingParams = $_GET;
        $search_shipment_batch_id = $_GET['search_shipment_batch_id'];
        $is_available = $_GET['is_available'];
        $total = 0;
//        p($search_shipment_batch_id,0);
//        p($is_available);
        
        $this->load->model('admin/ordersModel');
        $receivingBatchOrderData = array();
        
        if (!empty($search_shipment_batch_id))
        {
            $result = $this->receivingBatchesModel->getShipmentOrdersPhoto($pagingParams, $search_shipment_batch_id, $is_available);
//            p($result);
            $total = $result['foundRows'];
            $receivingBatchOrderData = $result['resultSet'];
            if (!empty($receivingBatchOrderData))
            {
                foreach ($receivingBatchOrderData as $index => $orderDetails)
                {
                    $image_status = trim($orderDetails['image_status']);
                    if($image_status != 'available')
                    {
                        $image_status = "N/A";
                    }
                    else
                    {
                        $image_status = "Available";
                    }
                    
                    $date_uploaded = $orderDetails['date_uploaded'];
                    
                    $delivery_date = $orderDetails['jkt_received_date'];
                    
//                    p($date_uploaded,0);
//                    p($delivery_date,0);
                    
                    $to_jkt_received_date_diff = date_diff(date_create($date_uploaded),date_create($delivery_date));
//                    p($to_jkt_received_date_diff->days,0);
                    
                    $receivingBatchOrderData[$index]['date_uploaded'] = $date_uploaded;
                    $receivingBatchOrderData[$index]['image_status'] = $image_status;
                    $receivingBatchOrderData[$index]['date_uploaded_to_date_delivered'] = $to_jkt_received_date_diff->days;
                }
            }
            else
            {
                $receivingBatchOrderData = array();
            }
            
        }

        $dataArray['iTotalRecords'] = $total;
        $dataArray['iTotalDisplayRecords'] = $total;
        $dataArray['sEcho'] = $pagingParams['sEcho'];
        $dataArray['aData'] = $receivingBatchOrderData;

        echo json_encode($dataArray);
    }
    
    
    public function deliveryRunSheet()
    {
        //load css
        $dataArray['local_css'] = array(
            'datatable', 'bootstrap_date_picker', 'multiselect', 'multiselect_filter'
        );
        //load js
        $dataArray['local_js'] = array(
            'datatable', 'bootstrap_date_picker', 'multiselect', 'multiselect_filter'
        );

        $delivery_date_from = $this->input->post('delivery_date_from');
        $delivery_date_to = $this->input->post('delivery_date_to');

        $driver_ids = $this->input->post('driver_ids');

        if (empty($delivery_date_from))
        {
            $delivery_date_from = date('d/m/Y');
            $temp_date_from = date('Y-m-d');
        }
        else
        {
            list($day, $month, $year) = explode('/', $delivery_date_from);
            $temp_date_from = "$year-$month-$day";
        }

        if (empty($delivery_date_to))
        {
            $delivery_date_to = date('d/m/Y');
            $temp_date_to = date('Y-m-d');
        }
        else
        {
            list($day, $month, $year) = explode('/', $delivery_date_to);
            $temp_date_to = "$year-$month-$day";
        }

        $dataArray['records'] = $this->reportlib->getDeliveryRunSheet($temp_date_from, $temp_date_to, $driver_ids);
        $dataArray['delivery_date_from'] = $delivery_date_from;
        $dataArray['delivery_date_to'] = $delivery_date_to;
        $dataArray['drivers'] = getEmployeesByRole('driver');
        $dataArray['drivers_selected'] = $driver_ids;

        $this->load->view('reports/deliveryRunSheet', $dataArray);
    }

    public function agentCommission()
    {
        //load css
        $dataArray['local_css'] = array(
            'datatable', 'bootstrap_date_picker'
        );
        //load js
        $dataArray['local_js'] = array(
            'datatable', 'bootstrap_date_picker'
        );

        $search_agent_id = $this->input->post('search_agent_id');
        $search_order_date_from = $this->input->post('search_order_date_from');
        $search_order_date_to = $this->input->post('search_order_date_to');

        $this->load->model('admin/mastersModel');
        $dataArray['agents'] = $this->mastersModel->getAllAgents();

        $this->load->library('form_validation');
        $this->form_validation->set_rules('search_order_date_from', 'From Date', 'required');
        $this->form_validation->set_rules('search_agent_id', 'Agent Id', 'required');

        $this->load->model('admin/mastersModel');

        if ($this->form_validation->run() == FALSE)
        {
            $dataArray['search_agent_id'] = $search_agent_id;
            $dataArray['search_order_date_from'] = $search_order_date_from;
            $dataArray['search_order_date_to'] = $search_order_date_to;
        } 
        
        $this->load->view('reports/agentCommission', $dataArray);
    }

    public function getAgentCommissionData()
    {
        $pagingParams = $_GET;
        $pagingParams = extractSearchParams($pagingParams, 'agent_commission_listing');

        $this->load->model('admin/ordersModel');
        $totalAmount = $this->ordersModel->getAgentCommissionDataTotal($pagingParams);

        $data = $this->ordersModel->getAgentCommissionData($pagingParams);

        $dataArray = array();

        if ($data['foundRows'] > 0)
        {
            foreach ($data['resultSet'] as $idx => $val)
            {
                $data['resultSet'][$idx]['order_number'] = "<a target='_new' href='" . base_url() . "admin/order/orderBookingForm/" . $val['id'] . "'>{$val['order_number']}</a>";
            }
        }

        $dataArray['iTotalRecords'] = $data['foundRows'];
        $dataArray['iTotalDisplayRecords'] = $data['foundRows'];
        $dataArray['sEcho'] = $pagingParams['sEcho'];
        $dataArray['aData'] = $data['resultSet'] == null ? array() : $data['resultSet'];
        $dataArray['totalCommissionAmount'] = "Total Commission : $ <b>" . number_format($totalAmount, 2) . '</b>';

        echo json_encode($dataArray);
    }

    public function getCollectionCall()
    {
        //load css
        $dataArray['local_css'] = array(
            'datatable', 'clock-picker', 'bootstrap_date_picker'
        );
        //load js
        $dataArray['local_js'] = array(
            'datatable', 'clock-picker', 'bootstrap_date_picker'
        );

        $this->load->model('admin/mastersModel');
        $result = $this->mastersModel->getAllShipmentBatches();

        $order_followup_comments_arr = $this->mastersModel->getAllOrderFollowupComments();

        $dataArray['shipment_batches'] = $result;
        $dataArray['order_followup_comments'] = $order_followup_comments_arr;

        $this->load->view('reports/collectionCall', $dataArray);
    }

    public function CustomerLoyaltyReport()
    {
        //load css
        $dataArray['local_css'] = array(
            'datatable', 'clock-picker', 'bootstrap_date_picker', 'multiselect', 'multiselect_filter'
        );
        //load js
        $dataArray['local_js'] = array(
            'datatable', 'clock-picker', 'bootstrap_date_picker', 'multiselect', 'multiselect_filter'
        );

        $pagingParams = $_GET;   
        
        $this->load->model('admin/ordersModel');
        $this->load->model('admin/mastersModel');
        
        
        $year = $this->input->post('year');
        $months = $this->input->post('months');
        $order_count_min = $this->input->post('order_count_min');
        $order_count_max = $this->input->post('order_count_max');
        
        $records = $this->ordersModel->getStartYearOrders();
        $start_year = array_unique(array_column($records, "order_date_year"));       
        sort($start_year);
        $dataArray['records'] = $this->ordersModel->getCustomerLoyaltyReportData($year, $months, $order_count_min,$order_count_max);
        $dataArray['start_year'] = $start_year;
        $dataArray['year_selected'] = $year;
        $dataArray['months_selected'] = $months;
        $dataArray['order_count_min_selected'] = $order_count_min;
        $dataArray['order_count_max_selected'] = $order_count_max;
        
        $months_data = array(1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec');
        
        $dataArray['months'] = $months_data;
        $this->load->view('reports/CustomerLoyaltyReport', $dataArray);
    }
    
    public function getCustomerLoyaltyOrder()
    {
        $pagingParams = $_GET;   
        
        $this->load->model('admin/ordersModel');
        
        $records = $this->ordersModel->getCustomerLoyaltyOrderData($pagingParams);
        
        foreach ($records as $key => $value) 
        { 
            $data[] = $value;
        }
        
        $results = [
                    "sEcho" => 1,
                    "iTotalRecords" => count($data),
                    "iTotalDisplayRecords" => count($data),
                    "aaData" => $data 
                   ];

        echo json_encode($results);
    }
    
    public function downloadCustomerLoyaltyXlsReport()
    {
        $year = $this->input->get('year');
        $months = $this->input->get('months');
        $order_count = $this->input->get('order_count');
        $months = explode(",", $months);
        $this->load->model('admin/ordersModel');
        $records = $this->ordersModel->getCustomerLoyaltyReportData($year, $months, $order_count);
        
        ini_set('memory_limit', '-1');

        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename=CustomerLoyalty_".time().".xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        echo "Customer name" . "\t";
        echo "Contact" . "\t";
        echo "address" . "\t";
        echo "Frequency" . "\t";
        print("\n");

            foreach($records as $idx => $value)
            {
                echo $value['name']. "\t";
                echo $value['mobile']. "\t";
                echo $value["block"].' '.$value["street"].' '.$value["unit"]. "\t";
                echo $value['orders_count']. "\t";
                print("\n");
            }
    }
    
    public function getReport()
    {
        //load css
        $dataArray['local_css'] = array(
            'datatable', 'clock-picker', 'bootstrap_date_picker'
        );
        //load js
        $dataArray['local_js'] = array(
            'datatable', 'clock-picker', 'bootstrap_date_picker'
        );

        $delivery_date = $this->input->post('delivery_date');
        $type = $this->input->post('type');

        if (empty($delivery_date))
        {
            $delivery_date = date('Y-m-d');
        }
        else
        {
            list($day, $month, $year) = explode('/', $delivery_date);
            $delivery_date = "$year-$month-$day";

            if ($type == 'labels')
            {
                $dataArray['records'] = $this->reportlib->getQRCodes($delivery_date);
            }
            else
            {
                $this->load->library('adminlib');
                $this->load->model('admin/mastersModel');

                $dataArray['records'] = $this->adminlib->getBatchOrderDetails($delivery_date);
                $dataArray['locations'] = $this->mastersModel->getAllLocations();
                $dataArray['boxes'] = $this->mastersModel->getAllBoxes();
            }

            $dataArray['type'] = $type;
        }

        $dataArray['delivery_date'] = $delivery_date;
        $this->load->view('reports/qrcodes', $dataArray);
    }

    public function getBatchPrintReport()
    {
        //load css
        $dataArray['local_css'] = array(
            'datatable', 'clock-picker', 'bootstrap_date_picker'
        );
        //load js
        $dataArray['local_js'] = array(
            'datatable', 'clock-picker', 'bootstrap_date_picker'
        );

        $type = $this->input->post('type');
        $order_ids = $this->input->post('order_ids');

        $dataArray['order_ids'] = $order_ids;

        $order_ids = explode(',', $order_ids);

        if ($type == 'labels')
        {

            $reportType = 'batchPrintLabels';
            $dataArray['records'] = $this->reportlib->getBatchQRCodes($order_ids);
        }
        else if ($type == 'forms')
        {
            $reportType = 'batchPrintForms';
            $this->load->library('adminlib');
            $this->load->model('admin/mastersModel');

            $dataArray['records'] = $this->adminlib->getBatchOrderForms($order_ids);
            $dataArray['locations'] = $this->mastersModel->getAllLocations();
            $dataArray['boxes'] = $this->mastersModel->getAllBoxes();
        }
        else if ($type == 'picture_receive_labels')
        {
            $reportType = 'batchPrintPictureReceiveLabels';
            $this->load->library('adminlib');
            $this->load->model('admin/mastersModel');

            $dataArray['records'] = $this->adminlib->getBatchOrderForms($order_ids);
        }
        else if ($type == 'passport')
        {
            $reportType = 'batchpassport';
            $this->load->library('adminlib');
            $this->load->model('admin/mastersModel');

            $dataArray['records'] = $this->adminlib->getBatchOrderForms($order_ids);
        }
        else
        {
            $reportType = 'batchPrintReceipts';
            $this->load->library('adminlib');
            $this->load->model('admin/mastersModel');

            $dataArray['records'] = $this->adminlib->getBatchOrderForms($order_ids);
            $dataArray['locations'] = $this->mastersModel->getAllLocations();
            $dataArray['boxes'] = $this->mastersModel->getAllBoxes();
        }
        
        $this->load->view('reports/' . $reportType, $dataArray);
    }

    public function getCollectionCallData()
    {
        $days = $this->input->get('days');
        $days = empty($days) ? 7 : $days;

        $records = $this->reportlib->getCollectionCallData($days);
        echo json_encode($records);
    }

    public function collectionRunSheet()
    {
        //load css
        $dataArray['local_css'] = array(
            'datatable', 'bootstrap_date_picker', 'multiselect', 'multiselect_filter'
        );
        //load js
        $dataArray['local_js'] = array(
            'datatable', 'bootstrap_date_picker', 'multiselect', 'multiselect_filter'
        );

        $collection_date_from = $this->input->post('collection_date_from');
        $collection_date_to = $this->input->post('collection_date_to');


        $driver_ids = $this->input->post('driver_ids');

        if (empty($collection_date_from))
        {
            $collection_date_from = date('d/m/Y');
            $temp_date_from = date('Y-m-d');
        }
        else
        {
            list($day, $month, $year) = explode('/', $collection_date_from);
            $temp_date_from = "$year-$month-$day";
        }

        if (empty($collection_date_to))
        {
            $collection_date_to = date('d/m/Y');
            $temp_date_to = date('Y-m-d');
        }
        else
        {
            list($day, $month, $year) = explode('/', $collection_date_to);
            $temp_date_to = "$year-$month-$day";
        }


        $dataArray['drivers'] = getEmployeesByRole('driver');
        $dataArray['drivers_selected'] = $driver_ids;
        $dataArray['records'] = $this->reportlib->getCollectionRunSheet($temp_date_from, $temp_date_to, $driver_ids);
        $dataArray['collection_date_from'] = $collection_date_from;
        $dataArray['collection_date_to'] = $collection_date_to;


        $this->load->view('reports/collectionRunSheet', $dataArray);
    }

    public function getLiveFeeds($showLatLong = false)
    {
        $delivery_date_from = $this->input->post('delivery_date_from');
        $collection_date_from = $this->input->post('collection_date_from');

        $delivery_date_to = $this->input->post('delivery_date_to');
        $collection_date_to = $this->input->post('collection_date_to');

        if (empty($delivery_date_from))
        {
            $delivery_date_from = date('d/m/Y');
            $temp_delivery_date = date('Y-m-d');
        }
        else
        {
            list($day, $month, $year) = explode('/', $delivery_date_from);
            $temp_delivery_date = "$year-$month-$day";
        }

        if (empty($delivery_date_to))
        {
            $delivery_date_to = date('d/m/Y');
            $temp_delivery_date_to = date('Y-m-d');
        }
        else
        {
            list($day, $month, $year) = explode('/', $delivery_date_to);
            $temp_delivery_date_to = "$year-$month-$day";
        }


        if (empty($collection_date_from))
        {
            $collection_date_from = date('d/m/Y');
            $temp_collection_date = date('Y-m-d');
        }
        else
        {
            list($day, $month, $year) = explode('/', $collection_date_from);
            $temp_collection_date = "$year-$month-$day";
        }

        if (empty($collection_date_to))
        {
            $collection_date_to = date('d/m/Y');
            $temp_collection_date_to = date('Y-m-d');
        }
        else
        {
            list($day, $month, $year) = explode('/', $collection_date_to);
            $temp_collection_date_to = "$year-$month-$day";
        }

        $where = array(
            'delivery_date_from' => $temp_delivery_date,
            'delivery_date_to' => $temp_delivery_date_to,
            'collection_date_from' => $temp_collection_date,
            'collection_date_to' => $temp_collection_date_to,
            'cnt_criteria' => 'on'
        );

        $this->load->library('adminlib');
        $dataArray['status_count'] = $this->adminlib->getOrderStatusesByDate($where);

        $where_jkt = array(
            'cnt_criteria' => 'on'
        );
        $jakarta_statuses = $this->config->item('jakarta_statuses');
        $jakarta_status_count = $this->adminlib->getOrderStatusesByDateJkt($where_jkt);

        $dataArray['status_count'] = array_merge($dataArray['status_count'], $jakarta_status_count);

        //load css
        $dataArray['local_css'] = array(
            'datatable', 'bootstrap_date_picker', 'toggle-switch'
        );
        //load js
        $dataArray['local_js'] = array(
            'datatable', 'bootstrap_date_picker', 'toggle-switch'
        );

        $dataArray['collection_date_from'] = $collection_date_from;
        $dataArray['collection_date_to'] = $collection_date_to;
        $dataArray['delivery_date_from'] = $delivery_date_from;
        $dataArray['delivery_date_to'] = $delivery_date_to;
        $dataArray['role'] = $this->_session['role'];
        $dataArray['statuses'] = $this->config->item('consolidated_statuses');
        $dataArray['show_lat_long'] = $showLatLong;

        $dataArray['drivers'] = getEmployeesByRole('driver');
        $this->load->view('reports/liveFeeds', $dataArray);
    }

    public function getLiveFeedsData($showLatLong = false)
    {
        $delivery_date_from = $this->input->post('delivery_date_from');
        $collection_date_from = $this->input->post('collection_date_from');
        $delivery_date_to = $this->input->post('delivery_date_to');
        $collection_date_to = $this->input->post('collection_date_to');
        $employee_id = $this->input->post('employee_id');
        $ordering_criteria_flag = $this->input->post('ordering_criteria_flag');
        $ordering_criteria_flag = $ordering_criteria_flag == 'false' ? 'order_status_trans.updated_at' : ' CONVERT(orders.order_number, DECIMAL)  ';
        $cnt_criteria = $this->input->post('my-cnt-checkbox');

        if (!empty($delivery_date_from))
        {
            list($day, $month, $year) = explode('/', $delivery_date_from);
            $delivery_date_from = "$year-$month-$day";
        }

        if (!empty($delivery_date_to))
        {
            list($day, $month, $year) = explode('/', $delivery_date_to);
            $delivery_date_to = "$year-$month-$day";
        }

        if (!empty($collection_date_to))
        {
            list($day, $month, $year) = explode('/', $collection_date_to);
            $collection_date_to = "$year-$month-$day";
        }

        if (!empty($collection_date_from))
        {
            list($day, $month, $year) = explode('/', $collection_date_from);
            $collection_date_from = "$year-$month-$day";
        }

        $where = array(
            'collection_date_from' => $collection_date_from,
            'delivery_date_from' => $delivery_date_from,
            'collection_date_to' => $collection_date_to,
            'delivery_date_to' => $delivery_date_to,
            'cnt_criteria' => $cnt_criteria
        );

        if (!empty($employee_id))
        {
            $where['employee_id'] = $employee_id;
        }

        $dataArray['records'] = $this->reportlib->getLiveFeeds($where, $ordering_criteria_flag);

        $this->load->library('adminlib');
        $status_count = $this->adminlib->getOrderStatusesByDate($where);

        $dataArray['role'] = $this->_session['role'];
        $dataArray['show_lat_long'] = $showLatLong;

        $this->load->setTemplate('blank');
        $return = $this->load->view('reports/liveFeedsData', $dataArray, true);

        $result = array(
            'html' => $return,
            'statuses' => $status_count,
        );

        echo json_encode($result);
    }

    public function EODReports()
    {
        $date = $this->input->post('date');
        $date = empty($date) ? date('d/m/Y') : $date;

        //load css
        $dataArray['local_css'] = array(
            'datatable', 'bootstrap_date_picker'
        );
        //load js
        $dataArray['local_js'] = array(
            'datatable', 'jquery-ui-1.11.2', 'bootstrap_date_picker'
        );

        list($day, $month, $year) = explode('/', $date);
        $dataArray['date'] = $date;
        $dataArray['formatted_date'] = "$year-$month-$day";

        $message = $this->session->flashdata('eodOperationMessage');
        $dataArray['message'] = $message;

        $this->load->view('reports/EODReports', $dataArray);
    }

    public function getEODReportsData($date)
    {
        list($year, $month, $day) = explode('-', $date);

        $date = "$year-$month-$day";

        $records = $this->reportlib->getEODReportsData($date);
        echo json_encode($records);
    }

    public function getWareHouseTallySheet($driver_id, $date)
    {
        if (empty($driver_id) || empty($date))
        {
            
        }
        else
        {
            if (empty($date))
            {
                $date = date('d/m/Y');
                $formatted_date = date('Y-m-d');
            }
            else
            {
                list($year, $month, $day) = explode('-', $date);
                $date = "$day/$month/$year";
                $formatted_date = "$year-$month-$day";
            }

            $data = array(
                'date' => $formatted_date,
                'employee_id' => $driver_id,
            );

            $this->load->model('admin/mastersModel');
            $row = $this->mastersModel->getRow('eod', $data);

            //load css
            $dataArray['local_css'] = array(
                'datatable', 'bootstrap_date_picker'
            );
            //load js
            $dataArray['local_js'] = array(
                'datatable', 'bootstrap_date_picker'
            );

            if (empty($row))
            {
                $dataArray['message'] = 'Oops! No record found for selected driver and date.';
            }
            else
            {
                if ($row['status'] === 'yes')
                {
                    $dataArray['date'] = $date;

                    $records = $this->reportlib->getWarehouseTallySheet($data);

                    $dataArray['records'] = $records;
                }
                else
                {
                    $dataArray['message'] = "Oops! Seems EOD not done as EOD status is {$row['status']}";
                }
            }
        }

        $this->load->view('reports/getWareHouseTallySheet', $dataArray);
    }

    public function getCashReport($driver_id, $date)
    {
        if (empty($driver_id) || empty($date))
        {
            
        }
        else
        {
            if (empty($date))
            {
                $date = date('d/m/Y');
                $formatted_date = date('Y-m-d');
            }
            else
            {
                list($year, $month, $day) = explode('-', $date);
                $date = "$day/$month/$year";
                $formatted_date = "$year-$month-$day";
            }

            $data = array(
                'date' => $formatted_date,
                'employee_id' => $driver_id,
            );

            $records = $this->reportlib->getCashReport($data);
            //load css
            $dataArray['local_css'] = array(
                'datatable', 'bootstrap_date_picker'
            );
            //load js
            $dataArray['local_js'] = array(
                'datatable', 'bootstrap_date_picker'
            );

            $dataArray['date'] = $date;
            $dataArray['records'] = $records;
        }

        $this->load->view('reports/getCashReport', $dataArray);
    }

    public function fetchCallFollowUp()
    {
        $order_id = $this->input->post('order_id');
        $dataArray = array(
        );

        $this->load->model('ordersmodel');
        $dataArray['results'] = $this->ordersmodel->getFollowUpCallHistory($order_id);

        $this->load->setTemplate('blank');
        $return = $this->load->view('reports/followUpCallHistoryList', $dataArray, false);

        echo $return;
    }

    public function saveCallFollowUp()
    {
        $order_id = $this->input->post('order_id');
        $comments = $this->input->post('comments');


        $followupcall_date = $this->input->post('followupcall_date');
        list ($day, $month, $year) = explode('/', $followupcall_date);
        $followupcall_date = "$year-$month-$day";

        $followupcall_time = $this->input->post('followupcall_time');
        $followupcall_time = empty($followupcall_time) ? '00:01:00' : $followupcall_time . ':00';

        $dataArray = array(
            'order_id' => $order_id,
            'comments' => $comments,
            'type' => 'collection',
            'employee_id' => $this->_user_id,
            'active' => 'yes',
            'followup_datetime' => "$followupcall_date $followupcall_time",
            'created_at' => date('Y-m-d H:i:s'),
        );

        $return = $this->reportlib->saveCallFollowUp($dataArray);
        $return = array(
            'id' => $return
        );
        echo json_encode($return);
    }

    public function saveCollectionDate()
    {
        $order_id = $this->input->post('order_id');
        $comments = $this->input->post('comments');
        $shipment_batch_id = $this->input->post('shipment_batch_id');


        $collection_date = $this->input->post('collection_date');
        list ($day, $month, $year) = explode('/', $collection_date);
        $collection_date = "$year-$month-$day";

        $collection_time = $this->input->post('collection_time');
        $collection_time = empty($collection_time) ? '00:01:00' : $collection_time . ':00';

        $dataArray = array(
            'id' => $order_id,
            'shipment_batch_id' => $shipment_batch_id,
            'collection_notes' => $comments,
            'updated_by' => $this->_user_id,
            'collection_date' => "$collection_date $collection_time",
            'updated_at' => date('Y-m-d H:i:s'),
        );

        $return = $this->reportlib->saveCollectionDate($dataArray);
        $return = array(
            'id' => $return
        );
        echo json_encode($return);
    }

    public function getShipmentBatchReport($shipment_id)
    {
        $data = $this->reportlib->getShipmentBatchData($shipment_id);
        if (!empty($data))
        {
            $dataArray = array('records' => $data['records'],
                'batch' => $data['batch'],
                'ship_onboard' => $data['ship_onboard'],
                'container_number' => $data['container_number'],
                'bl_number' => $data['bl_number'],
                'seal_number' => $data['seal_number']);
        }
        else
        {
            $dataArray = array();
        }
        
        $this->load->view('reports/shipmentReports', $dataArray);
    }

    public function getEODReportOrder($employee_id, $date)
    {
        $this->load->library('reportlib');
        $records = $this->reportlib->getEODOrderDetails($employee_id, $date);
        $dataArray = array('records' => $records);
        $this->load->setTemplate('blank');
        $return = $this->load->view('reports/EODOrderDetails', $dataArray, true);
        echo $return;
    }

    public function dawnloadShipmentBatchReport($shipment_id)
    {
        ini_set('memory_limit', '-1');

        $data = $this->reportsModel->getShipmentBatchOrderData($shipment_id);
        
        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename=$shipment_id.xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        echo "NO" . "\t";
        echo "Nomor Kemasan" . "\t";
        echo "SIZE" . "\t";
        echo "URAIAN BARANG" . "\t";
        echo "No Paspor (PENGIRIM)" . "\t";
        echo "Nama (PENGIRIM)" . "\t";
        echo "Alamat (PENGIRIM)" . "\t";
        echo "Tel/HP (PENGIRIM)" . "\t";
        echo "QTY." . "\t";
        echo "DESTINATION" . "\t";
        echo "Nama (PENERIMA)" . "\t";
        echo "Alamat (PENERIMA)" . "\t";
        echo "Kota Propinsi (PENERIMA)" . "\t";
        echo "Tel/HP (PENERIMA)" . "\t";
        print("\n");

        foreach ($data as $idx => $value)
        {

            echo trim($idx + 1) . "\t";
            echo trim($value['order_number']) . "\t";
            echo trim(str_replace(',', ' | ', $value['boxes'])) . "\t";
            echo trim(str_replace(',', ' | ', $value['recipient_item_list'])) . "\t";
            echo trim(str_replace(',', ' | ', $value['customer_passport_id_number'])) . "\t";
            echo trim(str_replace(',', ' | ', $value['customer_name'])) . "\t";
            echo trim(str_replace(',', ' | ', $value['customer_building']." ".$value['customer_street'])) . "\t";
            echo trim(str_replace(',', ' | ', $value['customer_mobile'])) . "\t";
            echo trim(str_replace(',', ' | ', $value['quantities'])) . "\t";
            echo trim(str_replace(',', ' | ', $value['locations'])) . "\t";
            echo trim(str_replace(',', ' | ', $value['recipient_name'])) . "\t";
            echo trim(str_replace(',', ' | ', $value['recipient_address'])) . "\t";
            echo trim(str_replace(',', ' | ', $value['kabupatens'])) . "\t";
            echo trim(str_replace(',', ' | ', $value['recipient_mobile'])) . "\t";
            print("\n");
        }
    }

    public function getLiveFeedsJkt($showLatLong = false)
    {
        $receiving_batch_id = $this->input->post('receiving_batch_id');
        $cnt_criteria = $this->input->post('my-cnt-checkbox');

        $where = array(
            'receiving_batch_id' => $receiving_batch_id,
            'cnt_criteria' => $cnt_criteria
        );

        $this->load->library('adminlib');
        $dataArray['status_count'] = $this->adminlib->getOrderStatusesByDateJkt($where);

        //load css
        $dataArray['local_css'] = array(
            'datatable', 'bootstrap_date_picker', 'toggle-switch'
        );
        //load js
        $dataArray['local_js'] = array(
            'datatable', 'bootstrap_date_picker', 'toggle-switch'
        );

        $dataArray['receiving_batch_id'] = $receiving_batch_id;
        $dataArray['receiving_batches'] = getAllReceivingBatches();
        $dataArray['role'] = $this->_session['role'];
        $dataArray['statuses'] = $this->config->item('jakarta_statuses');
        $dataArray['show_lat_long'] = $showLatLong;

        $this->load->view('reports/liveFeedsJakarta', $dataArray);
    }

    public function getLiveFeedsDataJkt($showLatLong = false)
    {
        $ordering_criteria_flag = $this->input->post('ordering_criteria_flag');
        $ordering_criteria_flag = $ordering_criteria_flag == 'false' ? 'order_status_trans.updated_at DESC' : 'total_boxes';

        $receiving_batch_id = $this->input->post('receiving_batch_id');
        $cnt_criteria = $this->input->post('my-cnt-checkbox');

        $where = array(
            'receiving_batch_id' => $receiving_batch_id,
            'cnt_criteria' => $cnt_criteria
        );

        $dataArray['records'] = $this->reportlib->getLiveFeedsJkt($where, $ordering_criteria_flag);

        $this->load->library('adminlib');
        $status_count = $this->adminlib->getOrderStatusesByDateJkt($where);

        $dataArray['role'] = $this->_session['role'];
        $dataArray['show_lat_long'] = $showLatLong;

        $this->load->setTemplate('blank');
        $return = $this->load->view('reports/liveFeedsDataJakarta', $dataArray, true);

        $result = array(
            'html' => $return,
            'statuses' => $status_count,
        );

        echo json_encode($result);
    }

    public function restoreEODStatus($id, $employeeName)
    {
        $id = $id;
        $dataArray = array(
            'id' => $id,
            'status' => 'no'
        );
        $this->load->model('ordersModel');
        $this->ordersModel->saveEOD($dataArray);

        $this->session->set_flashdata('eodOperationMessage', "Whoa! EOD reverted successfully for <b>$employeeName</b>.");
        redirect('admin/report/EODReports');
    }

    
    
    
    
    public function driverCollectionSheet()
    {
        
        //load css
        $dataArray['local_css'] = array(
            'datatable', 'bootstrap_date_picker', 'multiselect', 'multiselect_filter'
        );
        //load js
        $dataArray['local_js'] = array(
            'datatable', 'bootstrap_date_picker', 'multiselect', 'multiselect_filter'
        );

        $collection_date_from = $this->input->post('collection_date_from');
        $collection_date_to = $this->input->post('collection_date_to');

        $driver_ids = $this->input->post('driver_ids');

        if (empty($collection_date_from))
        {
            $collection_date_from = date('d/m/Y');
            $temp_date_from = date('Y-m-d');
        }
        else
        {
            list($day, $month, $year) = explode('/', $collection_date_from);
            $temp_date_from = "$year-$month-$day";
        }

        if (empty($collection_date_to))
        {
            $collection_date_to = date('d/m/Y');
            $temp_date_to = date('Y-m-d');
        }
        else
        {
            list($day, $month, $year) = explode('/', $collection_date_to);
            $temp_date_to = "$year-$month-$day";
        }

        $dataArray['records'] = $this->reportlib->getDriverCollectionSheet($temp_date_from, $temp_date_to, $driver_ids);
//       p($dataArray['records']);
        $dataArray['collection_date_from'] = $collection_date_from;
        $dataArray['collection_date_to'] = $collection_date_to;
        $dataArray['drivers'] = getEmployeesByRole('driver');
        $dataArray['drivers_selected'] = $driver_ids;
        $this->load->view('reports/driverCollectionSheet', $dataArray);
    }

     public function destBoxesReports()
    {
//        p($_POST);
        //load css
        $dataArray['local_css'] = array(
            'datatable', 'bootstrap_date_picker', 'multiselect', 'multiselect_filter'
        );
        //load js
        $dataArray['local_js'] = array(
            'datatable', 'bootstrap_date_picker', 'multiselect', 'multiselect_filter'
            );
        
        $shipment_date_from = $this->input->post('shipment_date_from');
        $shipment_date_to = $this->input->post('shipment_date_to');
       
        $shipment_batch_ids = $this->input->post('shipment_batch_ids');
        $temp_date_from = $temp_date_to = "";
        
       
        if(empty($shipment_batch_ids) && empty($shipment_date_to) && empty($shipment_date_from))
        {
         $shipment_date_from   = $shipment_date_to =date('d/m/Y');
         $temp_date_from = $temp_date_to = date('Y-m-d');     
        }
        
        if(!empty($shipment_date_from))
        {
            list($day, $month, $year)  = explode('/', $shipment_date_from);
            $temp_date_from = "$year-$month-$day";
        }
        
        if(!empty($shipment_date_to))
        {
            list($day, $month, $year)  = explode('/', $shipment_date_to);
            $temp_date_to = "$year-$month-$day";
        }
        
        
        $this->load->model('mastersModel');
        $shipmentBatchesArr = $this->mastersModel->getShipmentBatchArr();
        
        $shipmentBatchesData = array();
        if (!empty($shipmentBatchesArr))
        {
            foreach ($shipmentBatchesArr as $id => $rec)
            {
                $shipmentBatchesData[$id]['id'] = $rec['shipment_id'];
                $shipmentBatchesData[$id]['name'] = $rec['batch_name'];
            }
        }
        $dataArray['shipment_batches'] = $shipmentBatchesData;
        $dataArray['shipment_batch_selected'] = $shipment_batch_ids;
        $dataArray['records'] = $this->reportlib->getDestBoxesReports($temp_date_from, $temp_date_to, $shipment_batch_ids);
        
        
        $dataArray['shipment_date_from'] = $shipment_date_from;
        $dataArray['shipment_date_to'] = $shipment_date_to;        
        
        $this->load->view('reports/destBoxesReports', $dataArray);
    }
    
    public function deliveredBoxesReports()
    {
        //load css
        $dataArray['local_css'] = array(
            'datatable', 'bootstrap_date_picker', 'multiselect', 'multiselect_filter'
        );
        //load js
        $dataArray['local_js'] = array(
            'datatable', 'bootstrap_date_picker', 'multiselect', 'multiselect_filter'
        );
        
        $date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');
        $shipment_batch_ids = $this->input->post('shipment_batch_ids');
  
        $temp_date_from = $temp_date_to = "";
        
        if(empty($shipment_batch_ids) && empty($date_to) && empty($date_from))
        {
            $date_from   = $date_to =date('d/m/Y');
            $temp_date_from = $temp_date_to = date('Y-m-d');     
        }
        
        if(!empty($date_from))
        {
            list($day, $month, $year)  = explode('/', $date_from);
            $temp_date_from = "$year-$month-$day";
        }
            
        if(!empty($date_to))
        {
            list($day, $month, $year)  = explode('/', $date_to);
            $temp_date_to = "$year-$month-$day";
        } 
        
        $this->load->model('mastersModel');
        $shipmentBatchesArr = $this->mastersModel->getShipmentBatchArr('desc');
         
        $shipmentBatchesData = array();
        if (!empty($shipmentBatchesArr))
        {
            foreach ($shipmentBatchesArr as $id => $rec)
            {
                $shipmentBatchesData[$id]['id'] = $rec['shipment_id'];
                $shipmentBatchesData[$id]['name'] = $rec['batch_name'];
            }
        }
        $dataArray['shipment_batches'] = $shipmentBatchesData;
        $dataArray['shipment_batch_selected'] = $shipment_batch_ids;
        
        $exclude_boxes_id = $this->config->item('exclude_boxes_id');
        $exclude_location_id = $this->config->item('exclude_location_id');
        $dataArray['records'] = $this->reportlib->getDeliveredBoxesReports($temp_date_from, $temp_date_to, $shipment_batch_ids, $exclude_boxes_id, $exclude_location_id);
        
        $dataArray['date_from'] = $date_from;
        $dataArray['date_to'] = $date_to;        
        
        $this->load->view('reports/deliveredBoxesReports', $dataArray);
    }
    
    public function deliveredReports()
    {        
        $this->load->model('receivingBatchesModel');
        //load css
        $dataArray['local_css'] = array(
            'datatable', 'bootstrap_date_picker', 'multiselect', 'multiselect_filter'
        );
        //load js
        $dataArray['local_js'] = array(
            'datatable', 'bootstrap_date_picker', 'multiselect', 'multiselect_filter'
        );
        
        $received = $this->input->post('received_input');
        $shipment_batch_ids = $this->input->post('shipment_batch_ids');
        $search_by_destination_id = $this->input->post('search_by_destination_id');
        $box_no = $this->input->post('box_no');
        $date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');
        
        $temp_date_from = $temp_date_to = "";
//        if(empty($date_to) && empty($date_from))
//        {
//            $temp_date_from  = date('Y-m-d');  
//            $temp_date_to   = date('Y-m-d');  
//        }
        
        if(!empty($date_from))
        {
            list($day, $month, $year)  = explode('/', $date_from);
            $temp_date_from = "$year-$month-$day";
        }
            
        if(!empty($date_to))
        {
            list($day, $month, $year)  = explode('/', $date_to);
            $temp_date_to = "$year-$month-$day";
        } 
        
        $this->load->model('mastersModel');
        $shipment_batches = $this->receivingBatchesModel->getAllReceivingBatches(array(), 'all');
        $shipmentBatchesData = array();
        if (!empty($shipment_batches))
        {
            foreach ($shipment_batches as $index => $shipment_batch)
            {
                $shipmentBatchesData[$index]['id'] = $shipment_batch['id'];
                $shipmentBatchesData[$index]['name'] = $shipment_batch['name'];
            }
        }
        arsort($shipmentBatchesData);
        $dataArray['shipment_batches'] = $shipmentBatchesData;
        $dataArray['shipment_batch_selected'] = $shipment_batch_ids;
        $dataArray['records'] = $this->reportlib->getdeliveredReports($received,$shipment_batch_ids,$search_by_destination_id,$box_no,$temp_date_from, $temp_date_to);
//        p($dataArray['records']);
        $dataArray['locations'] = $this->mastersModel->getAllLocations_order_trans($received,$shipment_batch_ids,$temp_date_from,$temp_date_to); 
        $dataArray['received'] = $received;
        $dataArray['search_by_destination_id'] = $search_by_destination_id;
        $dataArray['box_no'] = $box_no;
        $dataArray['date_from'] = $date_from;
        $dataArray['date_to'] = $date_to;  
        $this->load->view('reports/deliveredReports', $dataArray);
    }
      
    public function get_destination()
    {
        $this->load->model('mastersModel');
        $received = $this->input->post('received_input');
        $shipment_batch_ids = $this->input->post('shipment_batch_ids');
        $box_no = $this->input->post('box_no');
        $date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');
        
        $temp_date_from = $temp_date_to = "";
        
        if(!empty($date_from))
        {
            list($day, $month, $year)  = explode('/', $date_from);
            $temp_date_from = "$year-$month-$day";
        }
            
        if(!empty($date_to))
        {
            list($day, $month, $year)  = explode('/', $date_to);
            $temp_date_to = "$year-$month-$day";
        } 
        $locations = $this->mastersModel->getAllLocations_order_trans($received,$shipment_batch_ids,$temp_date_from,$temp_date_to); 
        
        echo json_encode($locations);exit;
    }
    
    public function downloadDeliveredXlsReport()
    {
        $received = $this->input->post('received_input');
        $shipment_batch_ids = $this->input->get('shipment_batch_ids');
        $search_by_destination_id = $this->input->get('search_by_destination_id');
        $box_no = $this->input->get('box_no');
        $date_from = $this->input->get('date_from');
        $date_to = $this->input->get('date_to');
        $current_datetime = $this->input->get('current_datetime');
        
        $temp_date_from = $temp_date_to = "";
        if(!empty($date_from))
        {
            list($day, $month, $year)  = explode('/', $date_from);
            $temp_date_from = "$year-$month-$day";
        }
            
        if(!empty($date_to))
        {
            list($day, $month, $year)  = explode('/', $date_to);
            $temp_date_to = "$year-$month-$day";
        } 
            $records = $this->reportlib->getdeliveredReports($received,$shipment_batch_ids,$search_by_destination_id,$box_no,$temp_date_from, $temp_date_to);
 
            ini_set('memory_limit', '-1');
             
            header("Content-Type: application/xls");
            header("Content-Disposition: attachment; filename=DeliveredMdiReport_".$current_datetime.".xls");
            header("Pragma: no-cache");
            header("Expires: 0");

            echo "ORDER#" . "\t";
            echo "Sh Batch" . "\t";
            echo "Box" . "\t";
            echo "Qty" . "\t";
            echo "Destination." . "\t"; 
            echo "Kabupaten." . "\t"; 
            echo "Coll date." . "\t"; 
            echo "To Date." . "\t"; 
            echo "SOB." . "\t"; 
            echo "To Date." . "\t"; 
            echo "Recd@jkt." . "\t"; 
            echo "To Date." . "\t"; 
            echo "delivery@JKT Date." . "\t"; 
            echo "To Date." . "\t"; 
            echo "Driver." . "\t"; 
            echo "Remarks." . "\t"; 
            print("\n");
            
                foreach($records['box_data'] as $idx => $value)
                {
                        $received_at_jakarta_warehouse_date =  $this->reportsModel->get_status_date_by_order_id($value["id"],"received_at_jakarta_warehouse");
                        $delivery_date =  $this->reportsModel->get_status_date_by_order_id($value["id"],"delivered_at_jkt_picture_not_taken");
               
                        $to_collection_date_diff = date_diff(date_create($value['collection_date']),date_create($delivery_date));
                        $to_ship_onboard_diff = date_diff(date_create($value['ship_onboard']),date_create($delivery_date));
                        $to_jkt_received_date_diff = date_diff(date_create($received_at_jakarta_warehouse_date),date_create($delivery_date));
                        $to_delivery_date_diff = date_diff(date_create($delivery_date),date_create($value['collection_date']));

                        $collection_date    = (!empty($value['collection_date'])) ? date("d/m/Y", strtotime($value['collection_date'])): '';
                        $ship_onboard       = (!empty($value['ship_onboard'])) ? date("d/m/Y", strtotime($value['ship_onboard'])): '';
                        $jkt_received_date  = (!empty($received_at_jakarta_warehouse_date)) ? date("d/m/Y", strtotime($received_at_jakarta_warehouse_date)): '';
                        $jkt_delivery_date  = (!empty($delivery_date)) ? date("d/m/Y", strtotime($delivery_date)): '';
                        
                          echo $value['order_number']. "\t";
                          echo $value['batch_name']. "\t";
                          echo $value['box']. "\t";
                          echo $value['quantity']. "\t";
                          echo $value['location_name']. "\t";
                          echo $value['kabupatens_name']. "\t";
                          echo $collection_date. "\t";
                          echo $to_collection_date_diff->format("%a"). "\t";
                          echo $ship_onboard. "\t";
                          echo $to_ship_onboard_diff->format("%a"). "\t";
                          echo $jkt_received_date. "\t";
                          echo $to_jkt_received_date_diff->format("%a"). "\t";
                          echo $jkt_delivery_date. "\t";
                          echo $to_delivery_date_diff->format("%a"). "\t";
                          echo $value['username']. "\t";
                          echo $value['memo']. "\t";
                          print("\n");
                }
        } 
    
    public function downloadDeliveredBoxesXlsReport()
    {
        $date_from = $this->input->get('date_from');
        $date_to = $this->input->get('date_to');
        $shipment_batch_ids = $this->input->get('shipment_batch_ids');
        $current_datetime = $this->input->get('current_datetime');
        
        if(!empty($shipment_batch_ids))
        {
            $shipment_batch_seperate_ids = explode(",",$shipment_batch_ids);
        }
        else
        {
            $shipment_batch_seperate_ids = "";
        }
        
        $temp_date_from = $temp_date_to = "";
        if(!empty($date_from))
        {
            list($day, $month, $year)  = explode('/', $date_from);
            $temp_date_from = "$year-$month-$day";
        }
            
        if(!empty($date_to))
        {
            list($day, $month, $year)  = explode('/', $date_to);
            $temp_date_to = "$year-$month-$day";
        } 
        $exclude_boxes_id = $this->config->item('exclude_boxes_id');
        $exclude_location_id = $this->config->item('exclude_location_id');
        $records = $this->reportlib->getDeliveredBoxesReports($temp_date_from, $temp_date_to, $shipment_batch_seperate_ids,  $exclude_boxes_id, $exclude_location_id);
         
        if(!empty($records['box_data']))
        {  
            ini_set('memory_limit', '-1');
             
            header("Content-Type: application/xls");
            header("Content-Disposition: attachment; filename=DeliveredBoxesReport_".$current_datetime.".xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            
            $previous_location_name = "";
            $location_qty_arr = array();
            foreach($records['box_data'] as $idx => $value)
            {
                if(empty($previous_location_name) || $previous_location_name != $value['location_name'])
                {
                    echo "ORDER#" . "\t";
                    echo "Destination" . "\t";
                    echo "Kabupaten" . "\t";
                    echo "Shipment Batch" . "\t";
                    echo "Driver Name." . "\t"; 
                    echo "Qty." . "\t"; 
                    print("\n");
                    echo $value['location_name']. "\n";
                }
                $value['location_name'] = preg_replace('#<br\s*/?>#', " ", $value['location_name']);
                $value['kabupatens_name'] = preg_replace('#<br\s*/?>#i', " ", $value['kabupatens_name']);
                $location_names = explode('@@##@@', $value['location_name']);
                $quantity = explode('@@##@@', $value['quantity']);   

                foreach($location_names as $idx => $location_names)
                {
                  if (empty($location_qty_arr[$location_names]))
                  {
                       $location_qty_arr[$location_names] = $quantity[$idx];
                  }
                  else
                  {
                       $location_qty_arr[$location_names] += $quantity[$idx];
                  }
                }
                
                echo trim($value['order_number']) . "\t";
                echo trim($value['location_name']) . "\t";
                echo trim($value['kabupatens_name']) . "\t";
                echo trim($value['batch_name']) . "\t";
                echo trim($value['driver_name']) . "\t";
                echo trim($value['quantity']) . "\t";
                print("\n");
                $previous_location_name = $value['location_name'];
            }
                echo "Summary \n";
                $total_quantity = 0;
                $i = 0;
                foreach ($location_qty_arr as $location => $count)
                {  
                    $total_quantity += $count;
                    echo $location."\t".$count;
                    print("\n");
                }
                    echo "Collection Total"."\t".$total_quantity;
        ?>
        <?php
        } 
    }
    
    public function weeklyCollectionReports()
    {
        $dataArray['local_css'] = array(
            'datatable', 'bootstrap_date_picker'
        );
        //load js
        $dataArray['local_js'] = array(
            'datatable', 'bootstrap_date_picker'
            );
        
        $collection_date_from = $this->input->post('collection_date_from');
        $collection_date_to = $this->input->post('collection_date_to');
              
        
         if(empty($collection_date_from))
        {
            $collection_date_from = date('d/m/Y');
            $temp_date_from = date('Y-m-d');
            //monday of the current week
            $temp_date_from = date('Y-m-d',strtotime(date('o-\WW')));
        }
        else
        {
            list($day, $month, $year)  = explode('/', $collection_date_from);
            $temp_date_from = "$year-$month-$day";
        }
        
        if(empty($collection_date_to))
        {
            $collection_date_to = date('d/m/Y');
            $temp_date_to = date('Y-m-d');
        }
        else
        {
            list($day, $month, $year)  = explode('/', $collection_date_to);
            $temp_date_to = "$year-$month-$day";
        }
        
        $dataArray['records'] = $this->reportlib->getWeeklyCollectionReports($temp_date_from, $temp_date_to);
        
        $dataArray['collection_date_from'] = $collection_date_from;
        $dataArray['collection_date_to'] = $collection_date_to;        
        
        $this->load->view('reports/weeklyCollectionReports', $dataArray);
    }
    
    public function depositsUncollectedReports()
    {
        //load css
        $dataArray['local_css'] = array(
            'datatable', 'bootstrap_date_picker', 'multiselect', 'multiselect_filter'
        );
        //load js
        $dataArray['local_js'] = array(
            'datatable', 'bootstrap_date_picker', 'multiselect', 'multiselect_filter'
            );
        
        $collection_date_from = $this->input->post('collection_date_from');
        $collection_date_to = $this->input->post('collection_date_to');
       
        
        $drivers_ids = $this->input->post('driver_ids');
        
         if(empty($collection_date_from))
        {
            $collection_date_from = date('d/m/Y');
            $temp_date_from = date('Y-m-d');
        }
        else
        {
            list($day, $month, $year)  = explode('/', $collection_date_from);
            $temp_date_from = "$year-$month-$day";
        }
        
        if(empty($collection_date_to))
        {
            $collection_date_to = date('d/m/Y');
            $temp_date_to = date('Y-m-d');
        }
        else
        {
            list($day, $month, $year)  = explode('/', $collection_date_to);
            $temp_date_to = "$year-$month-$day";
        }
        
        $dataArray['drivers'] = getEmployeesByRole('driver');
        $dataArray['drivers_selected'] = $drivers_ids;
        $dataArray['records'] = $this->reportlib->getDepositsUncollectedReports($temp_date_from, $temp_date_to, $drivers_ids);
        
        $dataArray['collection_date_from'] = $collection_date_from;
        $dataArray['collection_date_to'] = $collection_date_to;        
        
        $this->load->view('reports/depositUncollectedReports', $dataArray);
    }
 
    public function driverDeliverySheet()
    {
        
        //load css
        $dataArray['local_css'] = array(
            'datatable', 'bootstrap_date_picker', 'multiselect', 'multiselect_filter'
        );
        //load js
        $dataArray['local_js'] = array(
            'datatable', 'bootstrap_date_picker', 'multiselect', 'multiselect_filter'
        );

        $delivery_date_from = $this->input->post('delivery_date_from');
        $delivery_date_to = $this->input->post('delivery_date_to');

        $driver_ids = $this->input->post('driver_ids');

        if (empty($delivery_date_from))
        {
            $delivery_date_from = date('d/m/Y');
            $temp_date_from = date('Y-m-d');
        }
        else
        {
            list($day, $month, $year) = explode('/', $delivery_date_from);
            $temp_date_from = "$year-$month-$day";
        }

        if (empty($delivery_date_to))
        {
            $delivery_date_to = date('d/m/Y');
            $temp_date_to = date('Y-m-d');
        }
        else
        {
            list($day, $month, $year) = explode('/', $delivery_date_to);
            $temp_date_to = "$year-$month-$day";
        }

        $dataArray['records'] = $this->reportlib->getDriverDeliverySheet($temp_date_from, $temp_date_to, $driver_ids);
//       p($dataArray['records']);
        $dataArray['delivery_date_from'] = $delivery_date_from;
        $dataArray['delivery_date_to'] = $delivery_date_to;
        $dataArray['drivers'] = getEmployeesByRole('driver');
        $dataArray['drivers_selected'] = $driver_ids;
        $this->load->view('reports/driverDeliverySheet', $dataArray);
    }
    
    public function deliveryPerformanceJakarta()
    {
        //load css
        $dataArray['local_css'] = array(
            'multiselect', 'multiselect_filter'
        );
        //load js
        $dataArray['local_js'] = array(
            'multiselect', 'multiselect_filter'
        );
        $shipment_batch_ids = $this->input->post('shipment_batch_ids');
        $this->load->model('mastersModel');
        $shipmentBatchesArr = $this->mastersModel->getShipmentBatchArr();


        $shipmentBatchesData = array();
        if (!empty($shipmentBatchesArr))
        {
            foreach ($shipmentBatchesArr as $id => $rec)
            {
                $shipmentBatchesData[$id]['id'] = $rec['shipment_id'];
                $shipmentBatchesData[$id]['name'] = $rec['batch_name'];
            }
        }
        
        $dataArray['shipment_batches'] = $shipmentBatchesData;
        $dataArray['shipment_batch_selected'] = $shipment_batch_ids;
        $dataArray['records'] = $this->reportlib->getDeliveryPerformanceJkt($shipment_batch_ids);
//        p($dataArray['records']);
        $this->load->view('reports/deliveryPerformanceJkt', $dataArray);
    }
    
    
    public function shipmentWeightListing()
    {
        $dataArray = array();
        $this->load->model('receivingBatchesModel');
        
        //load css
        $dataArray['local_css'] = array(
            'multiselect', 'multiselect_filter'
        );
        
        //load js
        $dataArray['local_js'] = array(
            'multiselect', 'multiselect_filter'
        );
        $shipment_batch_ids = $this->input->post('shipment_batch_ids');
        $shipment_batches = $this->receivingBatchesModel->getAllShipmentBatches('desc');
        $shipmentBatchesArr = array();
        if (!empty($shipment_batches))
        {
            foreach ($shipment_batches as $index => $shipment_batch)
            {
                $shipmentBatchesArr[$index]['id'] = $shipment_batch['id'];
                $shipmentBatchesArr[$index]['name'] = $shipment_batch['batch_name'];
            }
        }
        $dataArray['records'] = $this->reportlib->getShipmentOrdersWeighReportsJkt($shipment_batch_ids);
        $dataArray['shipmentBatchesArr'] = $shipmentBatchesArr;
        $dataArray['shipment_selected'] = $shipment_batch_ids;
     
        $this->load->view('reports/shipmentWeightListing', $dataArray);
    }
    
    public function promoReport()
    { 
        $dataArray['local_css'] = array(
            'datatable', 'bootstrap_date_picker'
        );
      
        $dataArray['local_js'] = array(
            'datatable', 'bootstrap_date_picker'
        );
 
        $this->load->model('admin/mastersModel');
        $dataArray['promoCodes'] = $this->mastersModel->getAllPromoCodes();
   
        $this->load->view('reports/promoCodeDiscount', $dataArray);
    }
    
    public function getPromoDiscountData()
    {
        $pagingParams = $_GET; 
 
        $this->load->model('admin/ordersModel'); 
        $data = $this->ordersModel->getPromoCodeData($pagingParams);

        $dataArray = array();

        if ($data['foundRows'] > 0)
        {
            foreach ($data['resultSet'] as $idx => $val)
            {
                $data['resultSet'][$idx]['order_number'] = "<a target='_new' href='" . base_url() . "admin/order/orderBookingForm/" . $val['order_id'] . "'>{$val['order_number']}</a>";
            }
            $dataArray['downloadXlsReportHolder'] = "displayXlsButton";
        }

        $dataArray['iTotalRecords'] = $data['foundRows'];
        $dataArray['iTotalDisplayRecords'] = $data['foundRows'];
        $dataArray['sEcho'] = $pagingParams['sEcho'];
        $dataArray['aData'] = $data['resultSet'] == null ? array() : $data['resultSet'];
 
        echo json_encode($dataArray);
    }
    
    
    public function downloadPromoCodeDataXlsReport()
    {
        $collection_date_from = $this->input->get('collection_date_from');
        $collection_date_to = $this->input->get('collection_date_to');
        $promo_id = $this->input->get('promo_id');

        $current_datetime = $this->input->get('current_datetime');
         
        $pagingParams = array("search_collection_date_from" => $collection_date_from,
                              "search_collection_date_to" => $collection_date_to,
                              "search_promo_id" => "$promo_id");
        
        $this->load->model('admin/ordersModel');
        $promoCodeData = $this->ordersModel->getPromoCodeData($pagingParams,$returnData = true);
         
        if(!empty($promoCodeData))
        {  
            $promoCodeName = $promoCodeData[0]['promoCodeName'];
            ini_set('memory_limit', '-1');
            $xlsFileName = 'PromoCodeReport_'.$promoCodeName.'_'.$current_datetime.'.xls';       

            header("Content-Type: application/xls");
            header("Content-Disposition: attachment; filename=\"".$xlsFileName."\"");
            header("Pragma: no-cache");
            header("Expires: 0");
             
            echo "ORDER#" . "\t";
            echo "Collection Date" . "\t";
            echo "Boxes" . "\t";
            
            echo "Customer Name" . "\t";
            echo "Contact Number" . "\t";
            echo "Size" . "\t";
            echo "Destination" . "\t";
             
            echo "Order Total" . "\t";
            echo "Discount" . "\t"; 
            echo "Nett Total" . "\t"; 
            print("\n"); 
                
            $total_boxes = 0;
            
            $discountTotal = array ();
            $orderTotal = array();
            
            foreach($promoCodeData as $idx => $value)
            {    
                
                if (isset($discountTotal[$value['order_id']]))
                {
                    $discountTotal[$value['order_id']] = $value['discount'];
                    $orderTotal[$value['order_id']] = $value['grand_total'];
                }
                else
                {
                    $discountTotal[$value['order_id']] = $value['discount'];
                    $orderTotal[$value['order_id']] = $value['grand_total'];
                }
                
                $total_boxes = $total_boxes + $value['box_quantity'];
                 
                if(empty($value['promoCodeId']))
                {
                   $value['discount'] = "0";
                }
                
                echo trim($value['order_number']) . "\t";
                echo trim($value['collection_date']) . "\t";
                echo trim($value['box_quantity']) . "\t";
                
                echo trim($value['customer_name']) . "\t";
                echo trim($value['customer_mobile']) . "\t";
                echo trim($value['box_name']) . "\t";
                echo trim($value['kabupaten_name']) . "\t";
                 
                echo trim($value['grand_total']) . "\t";
                echo trim($value['discount']) . "\t";
                echo trim($value['nett_total']) . "\t";
                print("\n"); 
            }   
            
            $order_total = array_sum($orderTotal);
            $discount_total = array_sum($discountTotal);

            echo "Summary \n"; 

            echo "Total Discont"."\t".$discount_total;
            print("\n");
 
            echo "Order Total"."\t".$order_total;
            print("\n");

            echo "Total Boxes"."\t".$total_boxes;

        ?>
        <?php
        } 
    }
    
    public function CustomerProfilingReport()
    {
        //load css
        $dataArray['local_css'] = array(
            'datatable', 'clock-picker', 'bootstrap_date_picker', 'multiselect', 'multiselect_filter'
        );
        //load js
        $dataArray['local_js'] = array(
            'datatable', 'clock-picker', 'bootstrap_date_picker', 'multiselect', 'multiselect_filter'
        );

        $pagingParams = $_GET;   
        
        $this->load->model('admin/ordersModel');
        $this->load->model('admin/mastersModel');
        
        $type = $this->input->post('type');
        $from = $this->input->post('from');
        $to = $this->input->post('to');
        

        if (empty($from))
        {
            $from = date('d/m/Y');
            $temp_date_from = date('Y-m-d');
        }
        else
        {
            list($day, $month, $year) = explode('/', $from);
            $temp_date_from = "$year-$month-$day";
        }

        if (empty($to))
        {
            $to = date('d/m/Y');
            $temp_date_to = date('Y-m-d');
        }
        else
        {
            list($day, $month, $year) = explode('/', $to);
            $temp_date_to = "$year-$month-$day";
        }
        
        $records = $this->ordersModel->getStartYearOrders();
        $dataArray['types_selected'] = $type;
        $dataArray['from'] = $from;
        $dataArray['to'] = $to;
        $dataArray['CustomerType'] = $this->ordersModel->CustomerTypeReportData($temp_date_from, $temp_date_to);
        $dataArray['MediaType'] = $this->ordersModel->MediaTypeReportData($temp_date_from, $temp_date_to);
        
        $months_data = array(1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec');
        
        $dataArray['months'] = $months_data;
        $this->load->view('reports/CustomerProfilingReport', $dataArray);      
    }
    
    
    public function downloadCustomerTypeXlsReport()
    {
        $from = $this->input->get('from');
        $to = $this->input->get('to');
        

        if (empty($from))
        {
            $from = date('d/m/Y');
            $temp_date_from = date('Y-m-d');
        }
        else
        {
            list($day, $month, $year) = explode('/', $from);
            $temp_date_from = "$year-$month-$day";
        }

        if (empty($to))
        {
            $to = date('d/m/Y');
            $temp_date_to = date('Y-m-d');
        }
        else
        {
            list($day, $month, $year) = explode('/', $to);
            $temp_date_to = "$year-$month-$day";
        }
        
        $this->load->model('admin/ordersModel');
        $CustomerType = $this->ordersModel->CustomerTypeReportData($temp_date_from, $temp_date_to);
         
        if(!empty($CustomerType))
        {  
            ini_set('memory_limit', '-1');
            $xlsFileName = 'CustomerType.xls';       

            header("Content-Type: application/xls");
            header("Content-Disposition: attachment; filename=\"".$xlsFileName."\"");
            header("Pragma: no-cache");
            header("Expires: 0");
             
            echo "Customer Type" . "\t";
            echo "Order Count" . "\t";
            echo "Pecentage" . "\t";
            print("\n"); 
            
            foreach($CustomerType as $idx => $value)
            {                   
                echo trim($value['customer_type']) . "\t";
                echo trim($value['orders_count']) . "\t";
                echo trim($value['pecentage']) . "\t";
                print("\n"); 
            }  
        } 
    }
    
    
    public function downloadMediaTypeXlsReport()
    {
        $from = $this->input->get('from');
        $to = $this->input->get('to');
        

        if (empty($from))
        {
            $from = date('d/m/Y');
            $temp_date_from = date('Y-m-d');
        }
        else
        {
            list($day, $month, $year) = explode('/', $from);
            $temp_date_from = "$year-$month-$day";
        }

        if (empty($to))
        {
            $to = date('d/m/Y');
            $temp_date_to = date('Y-m-d');
        }
        else
        {
            list($day, $month, $year) = explode('/', $to);
            $temp_date_to = "$year-$month-$day";
        }
        
        $this->load->model('admin/ordersModel');
        $MediaType = $this->ordersModel->MediaTypeReportData($temp_date_from, $temp_date_to);
         
        if(!empty($MediaType))
        {  
            ini_set('memory_limit', '-1');
            $xlsFileName = 'MediaType.xls';       

            header("Content-Type: application/xls");
            header("Content-Disposition: attachment; filename=\"".$xlsFileName."\"");
            header("Pragma: no-cache");
            header("Expires: 0");
             
            echo "Customer Type" . "\t";
            echo "Order Count" . "\t";
            echo "Pecentage" . "\t";
            print("\n"); 
            
            foreach($MediaType as $idx => $value)
            {                   
                echo trim($value['media_type']) . "\t";
                echo trim($value['orders_count']) . "\t";
                echo trim($value['pecentage']) . "\t";
                print("\n"); 
            }  
        } 
    }
}
