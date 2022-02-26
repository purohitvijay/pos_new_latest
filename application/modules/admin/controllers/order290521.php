<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Order extends MY_Controller
{

    const PER_BOX_DISCOUNT = 10;
    const AGENT_DISCOUNT = 20;

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('url');
        $this->load->library('commonlibrary');
        $this->load->library('reportlib');
        
        $this->load->model('admin/ordersModel');
    }

    public function index($shipment_batch_id = null)
    {
        
        $dataArray['can_edit_status'] = canPerformAction('edit_status', $this->_user_id);

        //load css
        $dataArray['local_css'] = array(
            'datatable', 'bootstrap_date_picker'
        );
        //load js
        $dataArray['local_js'] = array(
            'datatable', 'bootstrap_date_picker', 'jquery-ui-1.11.2', 'hotkeys');

        $dataArray['statutes'] = $this->config->item('consolidated_statuses');
        $dataArray['delivery_date'] = $dataArray['collection_date'] = date('d/m/Y');
        $message = $this->session->flashdata('orderOperationMessage');
        $dataArray['message'] = $message;

        $this->load->model('admin/mastersModel');
        $result = $this->mastersModel->getAllShipmentBatches();

        $dataArray['shipment_batches'] = $result;
        $dataArray['shipment_batch_id'] = $shipment_batch_id;

        $this->load->view('orders/list', $dataArray);
    }

    public function batchPrint($shipment_batch_id = null)
    {
        //load css
        $dataArray['local_css'] = array(
            'datatable', 'bootstrap_date_picker', 'multiselect', 'multiselect_filter'
        );
        //load js
        $dataArray['local_js'] = array(
            'datatable', 'bootstrap_date_picker', 'jquery-ui-1.11.2', 'hotkeys', 'multiselect', 'multiselect_filter');

        $dataArray['statutes'] = $this->config->item('consolidated_statuses');
        $dataArray['delivery_date'] = $dataArray['collection_date'] = date('d/m/Y');

        $message = $this->session->flashdata('orderOperationMessage');
        $dataArray['message'] = $message;

        $this->load->model('admin/mastersModel');
        $result = $this->mastersModel->getAllShipmentBatches();

        $dataArray['shipment_batches'] = $result;
        $dataArray['shipment_batch_id'] = $shipment_batch_id;

        $message = $this->session->flashdata('orderOperationMessage');
        $dataArray['message'] = $message;
        $dataArray['drivers'] = getEmployeesByRole('driver');

        $this->load->view('orders/batchPrint', $dataArray);
    }

    public function getOrderData()
    {
        $pagingParams = $_GET;
        $pagingParams = extractSearchParams($pagingParams, 'order_listing');

        $data = $this->ordersModel->getAllOrdersDataTable($pagingParams);
        $dataArray = array();
//        p($data);
        $statutes = $this->config->item('consolidated_statuses');

        if ($data['foundRows'] > 0)
        {
            foreach ($data['resultSet'] as $idx => $val)
            {
                
                if($data['resultSet'][$idx]['ship_onboard'] == "0000-00-00" || $data['resultSet'][$idx]['ship_onboard'] =="")
                {
                    $data['resultSet'][$idx]['ship_onboard'] = "";
                }
                else 
                {
                    $data['resultSet'][$idx]['ship_onboard'] = date('d/m/Y', strtotime($val['ship_onboard']));
                }
                
                if($data['resultSet'][$idx]['eta_jakarta'] == "0000-00-00" || $data['resultSet'][$idx]['eta_jakarta'] =="")
                {
                    $data['resultSet'][$idx]['eta_jakarta'] = "";
                }
                else 
                {
                    $data['resultSet'][$idx]['eta_jakarta'] = date('d/m/Y h:i:s', strtotime($val['eta_jakarta']));
                }                
                $status = $val['status'];
                $current_status = $statutes[$status]['display_text'];

                $order_status = $val['order_status'] == '' ? 'active' : $val['order_status'];
                $order_status = ucwords($val['order_status']);

                $kiv_status = ucwords($val['kiv_status']);

                $statuses = "$order_status <b>(Order)</b><br>$current_status <b>(Current)</b><br>$kiv_status <b>(KIV)</b>";
                $passport_img = "";
                if($val['passport_img'])
                        $passport_img = '<a href="#" path="'.$val['passport_img'].'" class="passport_img_show_model_link"> ('.$val['passport_id_number'].' )</a>';
                
                $data['resultSet'][$idx]['customer_name'] = $val['customer_name']." (". $val['mobile'] .") (". $val['residence_phone'] .")".$passport_img;
                $data['resultSet'][$idx]['statuses'] = $statuses;

                $data['resultSet'][$idx]['boxes'] = str_replace('@@##@@', '<br>', $val['boxes']);
                $data['resultSet'][$idx]['quantities'] = str_replace('@@##@@', '<br>', $val['quantities']);
                $data['resultSet'][$idx]['locations'] = str_replace('@@##@@', '<br>', $val['locations']);
                $data['resultSet'][$idx]['kabupatens'] = str_replace('@@##@@', '<br>', $val['kabupatens']);
                $data['resultSet'][$idx]['balance'] = $val['grand_total'] - $val['discount'] - ($val['cash_collected'] + $val['voucher_cash']);

                $building = empty($val['building']) ? '' : "{$val['building']}, <br/>";

                $data['resultSet'][$idx]['address'] = $val['block'] . ', ' . $val['unit'] . '<br>' . $building . $val['street'] . '<br>' . $val['pin'];

                $order_date = date('d/m/Y', strtotime($val['order_date']));
                $delivery_date = date('d/m/Y', strtotime($val['delivery_date']));

                $statuses = $val['statuses'];

                if (strstr($statuses, '@@##@@') === false)
                {
                    $statuses_arr = array();
                    $employees_arr = array();
                }
                else
                {
                    list($statuses_arr, $employees_arr) = explode('@@##@@', $statuses);
                    $statuses_arr = explode(',', $statuses_arr);
                    $employees_arr = explode(',', $employees_arr);
                }

                $order_emp = in_array('order_booked', $statuses_arr) ? $employees_arr[array_search('order_booked', $statuses_arr)] : '';
                $delivery_emp = in_array('box_delivered', $statuses_arr) ? $employees_arr[array_search('box_delivered', $statuses_arr)] : '';
                $collection_emp = in_array('box_collected', $statuses_arr) ? $employees_arr[array_search('box_collected', $statuses_arr)] : '';

                if (!empty($val['collection_date']))
                {
                    $collection_date = '<br/>' . date('d/m/Y', strtotime($val['collection_date'])) . '<br/><b>(Collection ' . $collection_emp . ')</b>';
                }
                else
                {
                    $collection_date = '';
                }

                $dates = "$order_date<br/><b>(Order $order_emp)</b><br/>$delivery_date<br/><b>(Delivery $delivery_emp)</b>$collection_date";


                $data['resultSet'][$idx]['dates'] = $dates;
                
                $redel_data = $this->ordersModel->getOrderRedelOrigBoxQty($val['id']);;
                if (empty($redel_data))
                {
                    $redel_icon = '';
                }
                else
                {
                    $tmp = array();
                    foreach ($redel_data as $tmp_row)
                    {
                        $tmp[] = $tmp_row['box']. " ({$tmp_row['quantity']}) ";
                    }
                    $redel_box_quantity = implode (', ', $tmp);
                    
                    $redel_icon = "<a title='$redel_box_quantity' href='javascript:void(0);'><i class='fa glyphicon-posterous_spaces'></i></a>";
                }
                
                if (strtolower($data['resultSet'][$idx]['order_status']) == 'active' || empty($data['resultSet'][$idx]['order_status']))
                {
                    $data['resultSet'][$idx]['edit'] = "<a href='" . base_url() . "admin/order/orderBookingForm/" . $val['id'] . "'><i class='fa fa-edit'></i></a>";
                    $data['resultSet'][$idx]['edit_status'] = "<a href='" . base_url() . "admin/order/orderStatusListing/" . $val['id'] . "'><i class='fa fa-truck'></i></a> "
                            . "                                 <br>".$redel_icon;
                }
                else
                {
                    $data['resultSet'][$idx]['edit'] = "--";
                    $data['resultSet'][$idx]['edit_status'] = "--";
                }
            }
        }

        $dataArray['iTotalRecords'] = $data['foundRows'];
        $dataArray['iTotalDisplayRecords'] = $data['foundRows'];
        $dataArray['sEcho'] = $pagingParams['sEcho'];
        $dataArray['aData'] = $data['resultSet'] == null ? array() : $data['resultSet'];

        echo json_encode($dataArray);
    }

    public function getBatchPrintData()
    {
        $this->load->model('admin/ordersModel');
        $pagingParams = $_GET;
        $pagingParams = extractSearchParams($pagingParams, 'order_listing');
        
        $data = $this->ordersModel->getAllBatchPrintDataTable($pagingParams);
        $dataArray = array();
//        p($data);
        $statutes = $this->config->item('consolidated_statuses');

        if ($data['foundRows'] > 0)
        {
            foreach ($data['resultSet'] as $idx => $val)
            {
                $s_start = $val['order_status'] !== 'active' || $val['kiv_status'] == 'yes' ? '<s>' : '';
                $s_end = $val['order_status'] !== 'active' || $val['kiv_status'] == 'yes' ? '</s>' : '';

                $order_date = date('d/m/Y', strtotime($val['order_date']));
                $delivery_date = date('d/m/Y', strtotime($val['delivery_date']));

                if (!empty($val['collection_date']))
                {
                    $collection_date = date('d/m/Y', strtotime($val['collection_date']));
                }
                else
                {
                    $collection_date = '';
                }

                $printed_instruments = explode(',', $val['printed_instruments']);
                $regenerateImages = base_url() . "admin/utility/updateMassOrderNoImageAndQRCode/" . $val['id'] . '/' . $val['order_number'];

                if (0 && forderImageExists($val['id']))
                {
                    $regenrateImageLink = '';
                }
                else
                {
                    $regenrateImageLink = "&nbsp;<a title='Regenerate Images' onClick=\"return confirm('Are you sure you want to regenerate images?')\"  href='$regenerateImages'><i class='fa glyphicon-circle_question_mark'></i></a>";
                }

                if (empty($val['jakarta_image_status']))
                {
                    $jktImagePrintLabelLink = '';
                }
                else
                {
                    $filters = array(
                        'type' => 'jakarta',
                        'order_id' => $val['id'],
                    );
                    $images = $this->ordersModel->getAllOrderImageTrans($filters);
                    $jktImagePrintLabelLink = "&nbsp;<a class='picture-receive-label-fake-class' rel='{$val['id']}' title='Print Picture Received Label' href='#'><i class='fa glyphicon-picture'></i></a>";

                    if (!empty($images))
                    {
                        foreach ($images as $row)
                        {
                            $filePath = base_url() . "admin/order/downloadJakartaImage/{$row['id']}";

                            $jktImagePrintLabelLink .= "&nbsp;<a rel='{$row['id']}' title='Download Jakarta Image {$row['name']}' href='$filePath'><i class='fa glyphicon-download'></i></a>";
                        }
                    }
                }

                $data['resultSet'][$idx]['order_number'] = $s_start . $val['order_number'] . $s_end;
                $data['resultSet'][$idx]['grand_total'] = $s_start . $val['grand_total'] . $s_end;
                $data['resultSet'][$idx]['discount'] = $s_start . $val['discount'] . $s_end;
                $data['resultSet'][$idx]['nett_total'] = $s_start . $val['nett_total'] . $s_end;

                $data['resultSet'][$idx]['printed_instruments'] = ucwords(str_replace(array(',', '_'), array('<br> ', ' '), $data['resultSet'][$idx]['printed_instruments']));

                $data['resultSet'][$idx]['order_date'] = $s_start . $order_date . $s_end;
                $data['resultSet'][$idx]['delivery_date'] = $s_start . $delivery_date . $s_end;
                $data['resultSet'][$idx]['collection_date'] = $s_start . $collection_date . $s_end;
                $data['resultSet'][$idx]['checkbox'] = "<input type='checkbox' value='{$val['id']}' class='fake-checkall-checkbox'>";
                $data['resultSet'][$idx]['operations'] = "<a class='labels-fake-class' title='Print QR Code' rel='{$val['id']}' href='#'><i class='fa fa-qrcode'></i></a>"
                        . "&nbsp;<a class='forms-fake-class' rel='{$val['id']}' title='Print Form' href='#'><i class='fa fa-paperclip'></i></a>"
                        . "&nbsp;<a class='receipt-fake-class' rel='{$val['id']}' title='Print Receipt' href='#'><i class='fa glyphicon-pen'></i></a>"
                        . "&nbsp;<a class='passport-fake-class' rel='{$val['id']}' title='Passport' href='#'><i class='fa fa-globe'></i></a>"
                        . "$jktImagePrintLabelLink"
                        . "$regenrateImageLink";
            }
        }

        $dataArray['iTotalRecords'] = $data['foundRows'];
        $dataArray['iTotalDisplayRecords'] = $data['foundRows'];
        $dataArray['sEcho'] = $pagingParams['sEcho'];
        $dataArray['aData'] = $data['resultSet'] == null ? array() : $data['resultSet'];

        echo json_encode($dataArray);
    }

    public function customerList()
    {
        //load css
        $dataArray['local_css'] = array(
            'datatable'
        );
        //load js
        $dataArray['local_js'] = array(
            'datatable');

        $this->load->view('orders/customerList', $dataArray);
    }

    public function getCustomerListData()
    {
        $this->load->model('admin/ordersModel');
        $pagingParams = $_GET;
        $pagingParams = extractSearchParams($pagingParams, 'customer_listing');

        $data = $this->ordersModel->getAllCustomers($pagingParams);
        $dataArray = array();
//        p($data);
        $statutes = $this->config->item('consolidated_statuses');

        if ($data['foundRows'] > 0)
        {
            foreach ($data['resultSet'] as $idx => $val)
            {
                $history = "<a href='#' title='{$val['name']}' class='fake-customer-class' rel='{$val['id']}'><i class='glyphicon-history'></i></a>";
                $data['resultSet'][$idx]['edit'] = "<a href='" . base_url() . "admin/order/editCustomer/" . $val['id'] . "'><i class='fa fa-edit'></i></a>";
                $data['resultSet'][$idx]['order_history'] = $history;
            }
        }

        $dataArray['iTotalRecords'] = $data['foundRows'];
        $dataArray['iTotalDisplayRecords'] = $data['foundRows'];
        $dataArray['sEcho'] = $pagingParams['sEcho'];
        $dataArray['aData'] = $data['resultSet'] == null ? array() : $data['resultSet'];

        echo json_encode($dataArray);
    }

    public function form()
    {
        $message = $this->session->flashdata('boxOperationMessage');
        $dataArray['message'] = $message;
        //load css
        $dataArray['local_css'] = array(
            'datatable', 'bootstrap_date_picker'
        );
        //load js
        $dataArray['local_js'] = array(
            'datatable', 'bootbox', 'counter_up', 'bootstrap_date_picker'
        );

        $this->load->library('adminlib');
        $dataArray['codes'] = $this->adminlib->getCodes();

        $this->load->model('admin/mastersModel');

        $dataArray['locations'] = $this->mastersModel->getAllLocations();
        $dataArray['agents'] = $this->mastersModel->getAllAgents();
        $dataArray['boxes'] = $this->mastersModel->getAllBoxes();
        $dataArray['PER_BOX_DISCOUNT'] = self::PER_BOX_DISCOUNT;
        $dataArray['AGENT_DISCOUNT'] = self::AGENT_DISCOUNT;

        $this->load->view('orders/form', $dataArray);
    }

    public function editCustomer($customer_id)
    {
        $message = $this->session->flashdata('message');
        $dataArray['message'] = $message;

        $this->load->model('admin/ordersModel');
        $dataArray['data'] = $this->ordersModel->getCustomerDetails($customer_id);
        $dataArray['customer_id'] = $customer_id;
        
        //load css
        $dataArray['local_css'] = array(  'multiselect', 'multiselect_filter');
        
        //load js
        $dataArray['local_js'] = array( 'multiselect', 'multiselect_filter');
        $this->load->model('admin/ordersmodel');
        $dataArray['customer_type'] = $this->ordersmodel->getAllCustomerType();
        $dataArray['media_type'] = $this->ordersmodel->getAllMediaType();
        if($customer_id)
        {
            $customer_type_selected = $this->ordersmodel->getAllCustomerTypeById($customer_id);
            $media_type_selected = $this->ordersmodel->getAllMediaTypeById($customer_id);
        }
        $dataArray['customer_type_selected'] = $customer_type_selected;
        $dataArray['media_type_selected'] = $media_type_selected;
        
        $this->load->view('orders/customerForm', $dataArray);
    }

    public function orderBookingForm($order_id = null, $order_number = null)
    {    
        $this->load->library('adminlib');
        $message = $this->session->flashdata('boxOperationMessage');
        $dataArray['message'] = $message;
        $dataArray['can_cancel_order'] = canPerformAction('cancel_order', $this->_user_id);

        //load css
        $dataArray['local_css'] = array(
            'datatable', 'multiselect', 'multiselect_filter'
        );
        //load js
        $dataArray['local_js'] = array(
            'datatable', 'bootbox', 'counter_up', 'bootstrap_date_picker', 'jquery_form', 'form_wizard', 'mockjax',
            'eakroko', 'validation', 'validation_additional_methods', 'jquery-ui-1.11.2', 'multiselect', 'multiselect_filter'
        );

        $this->load->model('admin/ordersmodel');
        $dataArray['order_id'] = null;
        $dataArray['customer_type'] = $this->ordersmodel->getAllCustomerType();
        $dataArray['media_type'] = $this->ordersmodel->getAllMediaType();
        if (!empty($order_id))
        {
            $dataArray['order_id'] = $order_id;
            $dataArray['order_details'] = $this->adminlib->getOrderDetails($order_id);

            $order_number = $dataArray['order_details']['order']['order_number'];
            $customer_id = $dataArray['order_details']['order']['customer_id'];
            $dataArray['customer_type_selected'] = $this->ordersmodel->getAllCustomerTypeById($customer_id);
            $dataArray['media_type_selected'] = $this->ordersmodel->getAllMediaTypeById($customer_id);
            $order_status_rec = $this->ordersModel->getOrderStatus($order_number);
            $dataArray['can_perform_edit'] = false;
                    
            $order_redel_orig_box_qty = $this->ordersModel->getOrderRedelOrigBoxQty($order_id);
            $dataArray['monitor_redelivery_case'] = empty($order_redel_orig_box_qty) ? 1 : 0;
            
            if (!empty($order_status_rec))
            {
                $order_status = $order_status_rec['status'];
                $dataArray['can_perform_edit'] = canPerformEditOrder($order_status);
                 
                $pramissible_status = $this->config->item('order_box_edit_permissible_status');
                $getOrderStatusByStatus = getStatusesByStatus($order_status,'bottom');
                $statuses = $this->config->item('consolidated_statuses');
                
                $current_status = $statuses[$order_status]['display_text'];
                $Pramissible_status_by_config = $statuses[$pramissible_status]['display_text'];
                $dataArray['Current_Status'] =$current_status;
                $dataArray['current_order_status'] = $order_status;
                $dataArray['Pramissable_Status'] =$Pramissible_status_by_config;
                if(in_array($pramissible_status, $getOrderStatusByStatus) || $pramissible_status == $order_status){
                   $dataArray['Disable_Order'] = true;
                }
            }
            
            $data = $this->adminlib->getOrderCodeDetails($dataArray);
            $data = array_merge($data, $dataArray['order_details']['order']);
            if(!empty($dataArray['Disable_Order']))
            {
               $Disable_status_array = array('Disable_Order' => true );
               $data = array_merge($data, $Disable_status_array);
            }
//            p($data);

            if(isset($dataArray['order_details']['promocode_data']))
            {  
                $data = array_merge($data, array('promocode_data' => $dataArray['order_details']['promocode_data'])); 
            }   
            $this->load->setTemplate('blank');
            $dataArray['code_box_html'] = $this->load->view('orders/getOrderCodeDetails', $data, true);
            $this->load->setTemplate('default');

            $view = 'orderBookingFormEdit';
        }
        else
        {
            $dataArray['order_number'] = $order_number;
            $view = 'orderBookingForm';
        }

        $dataArray['codes'] = $this->adminlib->getCodes();

        $this->load->model('admin/mastersModel');

        $dataArray['locations'] = $this->mastersModel->getAllLocations();
        $dataArray['agents'] = $this->mastersModel->getAllAgents();
        $dataArray['boxes'] = $this->mastersModel->getAllBoxes();
        
        $dataArray['PER_BOX_DISCOUNT'] = self::PER_BOX_DISCOUNT;
        $dataArray['AGENT_DISCOUNT'] = self::AGENT_DISCOUNT;

        
        $this->load->view("orders/$view", $dataArray);
    }

    public function searchUser()
    {
        $pin = $this->input->post('pin');
        $name = $this->input->post('name');
//        $mobile = $this->input->post('mobile');
        $phone = $this->input->post('phone');

        if (!empty($pin))
        {
            $search_query['customers.pin'] = $pin;
            $search_query['customers.unit'] = $pin;
            $search_query['customers.block'] = $pin;
            $search_query['customers.building'] = $pin;
            $search_query['customers.street'] = $pin;
        }

        if (!empty($name))
        {
            $search_query['name'] = $name;
        }

//        if (!empty($mobile))
//        {
//            $search_query['mobile'] = $mobile;
//        }

        if (!empty($phone))
        {
            $search_query['residence_phone'] = $phone;
            $search_query['mobile'] = $phone;
        }
        
        if (!empty($search_query))
        {
            $this->load->model('admin/ordersmodel');
            $data['results'] = $this->ordersmodel->searchCustomers($search_query);
            
            $this->load->setTemplate('blank');
            $return = $this->load->view('orders/searchUser', $data, false);
        }
        else
        {
            $return = "Please enter search criteria";
        }
        echo $return;
    }

    public function getCodeDetails()
    {
        $code_id = $this->input->post('code_id');

        if (!empty($code_id))
        {
            $this->load->model('admin/ordersmodel');
            $this->load->model('admin/mastersmodel');

            $data['code_id'] = $code_id;
            $data['results'] = $this->mastersmodel->getCodeBoxesDetails($code_id);
            $code = $this->mastersmodel->getCodeById($code_id);

            $data['kabupatens'] = $this->mastersmodel->getKabupatensByLocationId($code->location_id);
            $data['boxes'] = $this->mastersmodel->getAllBoxes();
            $data['locations'] = $this->mastersmodel->getAllLocations();
                    
            $this->load->setTemplate('blank');
            $return = $this->load->view('orders/getCodeDetails', $data, false);
        }
    }

    public function fetchPriceByLocationBox()
    {
        $location_id = $this->input->post('location_id');
        $box_id = $this->input->post('box_id');

        $return = array();

        if (!empty($box_id) && !empty($location_id))
        {
            $this->load->model('admin/ordersmodel');
            $return = $this->ordersmodel->getPriceByLocationBox($location_id, $box_id);
        }

        $this->load->setTemplate('blank');
        echo json_encode($return);
    }

    public function fetchKabupatensByLocation()
    {
        $return = '';
        $locationId = $this->input->post('locationId');

        $this->load->setTemplate('blank');

        if (!empty($locationId))
        {
            $this->load->model('admin/ordersmodel');
            $data['results'] = $this->ordersmodel->getKabupatenByLocation($locationId);
            $return = $this->load->view('orders/kabupatensOptions', $data, false);
        }

        echo $return;
    }

    public function fetchKabupatenAutoSuggestion($locationId)
    {
        $return = '';

        $term = $this->input->get('term');

        $this->load->setTemplate('blank');

        if (!empty($locationId))
        {
            $this->load->model('admin/ordersmodel');
            $return = $this->ordersmodel->getKabupatenByLocation($locationId, $term);
        }

        echo json_encode($return);
    }

    public function fetchCodeByLocationBox()
    {
        $term = $this->input->get('term');
        $box_id = $this->input->get('box_id');
        $location_id = $this->input->get('location_id');
        $code_ids = $this->input->get('code_ids');


        if (empty($code_ids))
        {
            $code_ids = array();
        }
        else
        {
            $code_ids = explode(',', $code_ids);
        }

        $return = array();

        if (!empty($term))
        {
            $this->load->model('admin/mastersmodel');
            $return = $this->mastersmodel->getAllCodesAutoSuggest($term, $box_id, $code_ids);
        }
        else
        {
            $this->load->model('admin/mastersmodel');
            $return = $this->mastersmodel->getAllCodesByBoxLocation($location_id, $box_id);
        }

        $this->load->setTemplate('blank');
        echo json_encode($return);
    }

    public function fetchCustomerAutoSuggestion()
    {
        $term = $this->input->get('term');
        $return = array();

        if (!empty($term))
        {
            $search_query = array(
                'name' => $term,
                'customers.block' => $term,
                'customers.unit' => $term,
                'customers.street' => $term,
            );
            $this->load->model('admin/ordersmodel');
            $return = $this->ordersmodel->searchCustomers($search_query);
        }

        $this->load->setTemplate('blank');
        echo json_encode($return);
    }

    public function fetchStreetAutoSuggestion()
    {
        $term = $this->input->get('term');
        $return = array();

        if (!empty($term))
        {
            $search_query = array(
                'street' => $term
            );
            $this->load->model('admin/ordersmodel');
            $return = $this->ordersmodel->searchStreet($search_query);
        }

        $this->load->setTemplate('blank');
        echo json_encode($return);
    }

    public function showCustomerOrderHistory($customer_id)
    {
        if (!empty($customer_id))
        {
            $this->load->model('admin/ordersmodel');

            $data = $this->ordersmodel->getOrderHistory($customer_id);

            $dataArray = array();

            $dataArray['results'] = $data;
            $dataArray['global_status'] = $this->config->item('consolidated_statuses');

            $this->load->setTemplate('blank');
            $return = $this->load->view('orders/orderHistoryList', $dataArray, false);
        }
        echo $return;
    }

    public function orderStatusHistory($order_id)
    {
        if (!empty($order_id))
        {
            $this->load->model('admin/ordersmodel');

            $data = $this->ordersmodel->orderStatusHistory($order_id);

            if (!empty($data))
            {
                PermissionSetup($data);
            }
        }
    }

    public function updateMassBarCode($order_id = null)
    {
        $this->load->model('admin/ordersmodel');

        if (empty($order_id))
        {
            $orders = $this->ordersmodel->getAllOrders($order_id);
        }
        else
        {
            $orders = $this->ordersmodel->getAllOrders(array(), array($order_id));
        }

        if (!empty($orders))
        {
            foreach ($orders as $index => $row)
            {
                echo "Processing for {$row['id']}, {$row['order_number']} <br/>";
                generateBarCode($row['id'], $row['order_number']);
            }
        }
    }

    public function updateMassOrderNoImage()
    {
        $this->load->model('admin/ordersmodel');
        $orders = $this->ordersmodel->getAllOrders();

        if (!empty($orders))
        {
            foreach ($orders as $index => $row)
            {
                if ($row['order_number'] >= 90000 && $row['order_number'] <= 90005)
                {
                    echo "Processing for {$row['id']}, {$row['order_number']} <br/>";
                    generateOrderNoImage($row['id'], $row['order_number']);
                }
            }
        }
    }
                    
    public function getAllBarCodes()
    {
        $this->load->model('admin/ordersmodel');
        $orders = $this->ordersmodel->getAllOrders();

        if (!empty($orders))
        {
            foreach ($orders as $index => $row)
            {
                echo "Bar code for {$row['id']}, {$row['order_number']} <br/>";
                $img = base_url() . "/assets/dynamic/bar_codes/{$row['id']}.png";
                echo "<img src='$img'><br/>";
            }
        }
    }

    public function generateBarCode($order_number, $order_id)
    {
        if(empty($order_number) && empty($order_id))
        {
            $order_number = $this->input->post('order_number');
            $order_id = $this->input->post('order_id');
        }

        generateBarCode($order_id, $order_number);
        generateOrderNoImage($order_id);
    }
                        
    public function saveFullOrder()
    {    
        $customer_id = $this->saveCustomer();
        $return = $this->saveOrder($customer_id);

        if ($return['status'] == 'error')
        {
            $this->session->set_flashdata('boxOperationMessage', "Oops! Anticipated to generate <b>{$return['anticipated_order_number']}</b> but it already exists.");
        }
        else if ($return['mode'] == 'edit')
        {
            $this->session->set_flashdata('boxOperationMessage', "Order number <b>{$return['anticipated_order_number']}</b> updated successfully.");
        }

        $this->load->setTemplate('blank');
        echo json_encode($return);
    }

    public function saveOrder($customer_id = null)
    {  
        
        if (empty($customer_id))
        {
            $customer_id = $this->input->post('customer_id');
        }
        
        $order_id = $this->input->post('order_id');
 
        $pin = $this->input->post('pin_order');
        $unit = $this->input->post('unit_order');
        $street = $this->input->post('street_order');
        $building = $this->input->post('building_order');
        $block = $this->input->post('block_order');

        $boxes = $this->input->post('boxes');
        $quantity = $this->input->post('quantity');
        $prices = $this->input->post('prices');
        $locations_selected = $this->input->post('locations_selected');
        $agent_id = $this->input->post('agent_id');
        $comments = $this->input->post('comments');
        $memo = $this->input->post('memo');
        $collection_notes = $this->input->post('collection_notes');
        $discount_type = $this->input->post('discount_type');
        $discount = $this->input->post('total_discount');
        $promocode_id = $this->input->post('promocode_id');
        
        //get promo-code id and it's expiry date.
        if($promocode_id)
        {
            $this->load->model('admin/ordersmodel');
            $promoCodeDetailsById = $this->ordersModel->getPromotionById($promocode_id);  
            if($promoCodeDetailsById)
            {
                $promo_date_to = $promoCodeDetailsById['date_to'];
            }
            
            //Get selected promo-code boxes arr.
            $promocodeIdDetails = $this->input->post('selectedPromoIdDetails');
            if($promocodeIdDetails)
            {  
                $removeSpecailCharInPromoArr = explode( '@#', $promocodeIdDetails);
                $promotionBoxesArr = $removeSpecailCharInPromoArr['2'];
            } 
        }
        else
        {
            $promocode_id = null;
            $promo_date_to = null;
            $promotionBoxesArr = null;
        } 
        
        $nett_total = $this->input->post('nett_total');
        $grand_total = $this->input->post('total_price');

        $recipient_address = $this->input->post('recipient_address');
        $recipient_name = $this->input->post('recipient_name');
        $recipient_mobile = $this->input->post('recipient_mobile');
        $recipient_item_list = $this->input->post('recipient_item_list');

        $recipient_address = empty($recipient_address) ? '' : $recipient_address;
        $recipient_name = empty($recipient_name) ? '' : $recipient_name;
        $recipient_mobile = empty($recipient_mobile) ? '' : $recipient_mobile;
        $recipient_item_list = empty($recipient_item_list) ? '' : $recipient_item_list;

        $lattitude = $this->input->post('lattitude');
        $longitude = $this->input->post('longitude');
        
        $save_redelivery_data = $this->input->post('save_redelivery_data');
        $redel_orig_box_qty = $this->input->post('redel_orig_box_qty');

        $manual_order_number = $this->input->post('manual_order_number');
      
        $codes = $this->input->post('codes');

        $code_items_count = $this->input->post('code_items_count');
        $kabupatens_selected = $this->input->post('kabupatens_selected');

        $delivery_date = $this->input->post('delivery_date');
        $collection_date = $this->input->post('collection_date');
        
                    
        if($promo_date_to && $collection_date)
        {
            list($day, $month, $year) = explode('/', $collection_date);
            $get_collection_date = "$year-$month-$day";
           
            if($get_collection_date > $promo_date_to)
            {
                $promotionBoxesArr = null;
                $promocode_id = null; 
            } 
        }
        
        $order_date = $this->input->post('order_date');
        $picture_receive_date = $this->input->post('picture_receive_date');

        $jkt_weight = $this->input->post('jkt_weight');
        $jkt_reference_no = $this->input->post('jkt_reference_no');
        $jkt_received_date = $this->input->post('jkt_received_date');
        $jkt_receiver = $this->input->post('jkt_receiver');
        
        if (empty($jkt_received_date))
        {
            $jkt_received_date = null;
        }
        else
        { 
            
            $this->load->model('admin/ordersmodel');
 	    $get_order_stauts = $this->ordersmodel->getOrderStatusDetails($order_id);
            
            if($get_order_stauts['status'] == "received_at_jakarta_warehouse")
            {
                $get_order_number = $this->ordersmodel->getOrderNumberById($order_id);

                list($day, $month, $year) = explode('/', $jkt_received_date);
                $jkt_received_date = "$year-$month-$day";

            
                $CI = & get_instance();
                $manually_escalated_api = $CI->config->item('manually_escalated_api');
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
                    
        $weight = $this->input->post('weight');

        list($day, $month, $year) = explode('/', $delivery_date);
        $delivery_date = "$year-$month-$day";

        $delivery_date_wo_time = $delivery_date;
        $delivery_date .= ' 00:00:01';


        if (empty($collection_date))
        {
            $collection_date = null;
            $collection_date_wo_time = null;
        }
        else
        {
            list($day, $month, $year) = explode('/', $collection_date);
            $collection_date = "$year-$month-$day";

            $collection_date_wo_time = $collection_date;

            $collection_date .= ' 00:00:01';
        }

        if (empty($picture_receive_date))
        {
            $picture_receive_date = null;
        }
        else
        {
            list($day, $month, $year) = explode('/', $picture_receive_date);
            $picture_receive_date = "$year-$month-$day";
        }

        list($day, $month, $year) = explode('/', $order_date);
        $order_date = "$year-$month-$day";
        $order_date .= ' 00:00:01';

        // Fetch lat-long from Google API for fail over strategy in case if paid DB have any problem.
        $google_coordinates = getLatLongByPinCode($pin);


        $array = array(
            'boxes' => $boxes,
            'quantity' => $quantity,
            'prices' => $prices,
            'locations_selected' => $locations_selected,
            'customer_id' => $customer_id,
            'discount' => $discount,
            'discount_type' => $discount_type,
            'promocode_id' => $promocode_id,
            'promoBoxesArr' => $promotionBoxesArr,
            'grand_total' => $grand_total,
            'nett_total' => $nett_total,
            'pin' => $pin,
            'street' => $street,
            'building' => $building,
            'block' => $block,
            'unit' => $unit,
            'agent_id' => $agent_id,
            'comments' => $comments,
            'collection_notes' => $collection_notes,
            'discount_type' => $discount_type,
            'delivery_date' => $delivery_date,
            'collection_date' => $collection_date,
            'order_date' => $order_date,
            'recipient_address' => $recipient_address,
            'recipient_name' => $recipient_name,
            'recipient_mobile' => $recipient_mobile,
            'recipient_item_list' => $recipient_item_list,
            'manual_order_number' => $manual_order_number,
            'lattitude' => $lattitude,
            'longitude' => $longitude,
            'google_lat' => $google_coordinates['lattitude'],
            'google_lon' => $google_coordinates['longitude'],
            'codes' => $codes,
            'code_items_count' => $code_items_count,
            'kabupatens_selected' => $kabupatens_selected,
            'weight' => $weight,
            'order_id' => $order_id,
            'delivery_date_wo_time' => $delivery_date_wo_time,
            'collection_date_wo_time' => $collection_date_wo_time,
            'picture_receive_date' => $picture_receive_date,
            'jkt_weight' => $jkt_weight,
            'jkt_reference_no' => $jkt_reference_no,
            'jkt_received_date' => $jkt_received_date,
            'jkt_receiver' => $jkt_receiver,
            'memo' => $memo,
            
            'save_redelivery_data' => $save_redelivery_data,
            'redel_orig_box_qty' => $redel_orig_box_qty
        ); 
        $this->load->library('adminlib');
        $return = $this->adminlib->saveOrders($array);
        
        return $return;
    }

    public function saveCustomer($returnJson = false)
    {
        $customer_id = $this->input->post('customer_id');
        $name = $this->input->post('customer_name');
        $email = $this->input->post('email');
        $phone = ($this->input->post('phone')) ? $this->input->post('phone') : $this->input->post('residence_phone');
        $pin = $this->input->post('pin');
        $unit = $this->input->post('unit');
        $mobile = $this->input->post('mobile');
        $street = $this->input->post('street');
        $building = $this->input->post('building');
        $block = $this->input->post('block');
        $lattitude = $this->input->post('lattitude');
        $longitude = $this->input->post('longitude');
        $repeated_customer = $this->input->post('repeated_customer'); 
        $is_repeated_customer = $this->input->post('customer_repeated');
        $customer_type = $this->input->post('customer_type');
        $media_type = $this->input->post('media_type');
        if($is_repeated_customer != "no")
        {
           $is_repeated_customer = "yes";
        }  
        $array = array(
            'name' => $name,
            'email' => $email,
            'mobile' => $mobile,
            'residence_phone' => $phone,
            'pin' => $pin,
            'street' => $street,
            'building' => $building,
            'unit' => $unit,
            'block' => $block,
            'longitude' => $longitude,
            'lattitude' => $lattitude,
            'updated_at' => date('Y-m-d H:i:s'),
            'is_repeated_customer' => $is_repeated_customer
        );  
        
        if (!empty($customer_id))
        {
            $array['id'] = $customer_id;
        }
        else
        {
            $array['created_at'] = date('Y-m-d H:i:s');
        }
        
        $this->load->model('admin/ordersmodel');
        $customer_id = $this->ordersmodel->saveCustomer($array);
        
        if($customer_type)
        {          
            $customer_customer_type_id = $this->ordersmodel->saveCustomerType($customer_type, $customer_id);
        }
        if($media_type)
        {         
            $customer_media_type_id = $this->ordersmodel->saveMediaType($media_type, $customer_id);
        }

        if (empty($returnJson))
        {
            return $customer_id;
        }
        else
        {
            $return = array(
                'status' => 'success',
                'customer_id' => $customer_id
            ); 
            echo json_encode($return);
        }
    }

    public function getAddressByPinCodeFromDB()
    {

        $pincode = $this->input->post('pincode');

        $this->load->model('admin/ordersmodel');
        $result = $this->ordersmodel->getAddressByPinCode($pincode);

        echo json_encode($result);
    }

    public function getPinCodeByAddressFromDB()
    {

        $street = $this->input->post('street');
        $block = $this->input->post('block');

        if (!empty($street))
        {
            $where['street'] = $street;
        }

        if (!empty($block))
        {
            $where['block'] = $block;
        }

        $result = array();

        if (!empty($where))
        {
            $this->load->model('admin/ordersmodel');
            $result = $this->ordersmodel->getPinCodeByAddress($where);
        }

        echo json_encode($result);
    }

    public function getAddressByPinCode()
    {
        $pincode = $this->input->post('pincode');
        $url = 'http://maps.googleapis.com/maps/api/geocode/json?address=' . $pincode . "&sensor=true";

        $json = file_get_contents($url);

        $return = array(
            'street' => '',
            'building' => '',
        );

        $array = json_decode($json);

        $address = array();

        if (!empty($array->results[0]))
        {
            $lat = $array->results[0]->geometry->location->lat;
            $lng = $array->results[0]->geometry->location->lng;

            if (!empty($lat) && !empty($lng))
            {
                $url = 'http://maps.googleapis.com/maps/api/geocode/json?address=' . $lat . ",$lng&sensor=true";
                $json_results = file_get_contents($url);

                $array = json_decode($json_results);

                if (!empty($array->results[0]->address_components))
                {
                    foreach ($array->results[0]->address_components as $index => $row)
                    {
                        if (!empty($row->types[0]) && $row->types[0] == 'street_number')
                        {
                            $return['street'] = $row->long_name;
                        }

                        if (!empty($row->types[0]) && $row->types[0] == 'route')
                        {
                            $return['building'] = $row->long_name;
                        }
                    }
                }
            }
        }

        $return = json_encode($return);
        echo $return;
    }

    public function printNow($order_id)
    {
        if (empty($order_id))
        {
            die('Illegal operation performed!');
        }

        $this->load->library('adminlib');
        $this->load->model('admin/mastersModel');

        $data['order_id'] = $order_id;
        $data['order'] = $this->adminlib->getOrderDetails($order_id);

        $data['locations'] = $this->mastersModel->getAllLocations();
        $data['boxes'] = $this->mastersModel->getAllBoxes();

        $this->load->view('orders/printOrder', $data);
    }

    public function reassignDriver()
    {
        $order_id = $this->input->post('order_id');
        $old_employee_id = $this->input->post('old_employee_id');
        $employee_id = $this->input->post('employee_id');
        $id = $this->input->post('id');

        if (empty($employee_id) || empty($old_employee_id) || empty($order_id) || empty($id))
        {
            $return = array(
                'status' => 'error',
                'message' => 'Employee Id/ Old Employee Id/ Order Id / Id can not be blank',
            );
        }
        else
        {
            $where = array(
                'order_id' => $order_id,
                'status <> ' => 'order_booked'
            );

            $order_status = array(
                'order_id' => $order_id,
                'employee_id' => $employee_id,
                'updated_at' => date('Y-m-d H:i:s'),
            );

            $this->load->model('ordersmodel');
            $order_status = $this->ordersmodel->saveOrderStatus($order_status, $where);



            $order_status = array(
                'id' => $id,
                'reassigned_stage' => 'yes',
                'old_employee_id' => $old_employee_id,
            );
            $this->load->model('ordersmodel');
            $order_status = $this->ordersmodel->saveOrderStatus($order_status);


            if (empty($order_status))
            {
                $return = array(
                    'status' => 'error',
                    'message' => 'Some error occured while saving data',
                );
            }
            else
            {
                $return = array(
                    'status' => 'success'
                );
            }
        }

        echo json_encode($return);
    }

    public function manualStatusEscalation()
    {
        $return = array(
            'status' => 'success',
        );

        $order_id = $this->input->post('order_id');
        $order_number = $this->input->post('order_number');
        $employee_id = $this->input->post('employee_id');
        $status = $this->input->post('status');
        $cash_collected = $this->input->post('cash_collected');
        $voucher_cash = $this->input->post('voucher_cash');
        $comments = $this->input->post('comments');
//        $shipment_batch_id = $this->input->post('shipment_batch_id');
                    
        if ($cash_collected == "")
        {
            $cash_collected = '';
        }

        if (empty($employee_id) || empty($order_id) || empty($status))
        {
            $return = array(
                'status' => 'error',
            );

            $this->session->set_flashdata('orderOperationMessage', "Employee Id/ Order Id/ Status can not be blank.");
        }
        else
        {
            $order_status = $this->ordersModel->getOrderStatusDetails($order_id);
            $latest_status = $order_status['status'];

            $order = $this->ordersModel->getOrderDetails($order_id);
            $statuses = $this->config->item('consolidated_statuses');

            //It means app also trigered same status, it is error, yes big error.
            if ($latest_status == $status)
            {
                $this->session->set_flashdata('orderOperationMessage', "Hold on! <b>{$order['order_number']}</b> is already having <b>{$statuses[$status]['display_text']}</b> status.");

                $return = array(
                    'status' => 'error'
                );
            }
            else
            {
                $success_msg_extra_info = null;

                if ($status == 'collected_at_warehouse')
                {
                    $shipment_batch_row = getCurrentShipmentBatchId($order_id, $order_number);
                    if (empty($shipment_batch_row['error']))
                    {
                        $shipment_batch_id = $shipment_batch_row['data']['shipment_batch_id'];
                        $success_msg_extra_info = " under Batch (<b>{$shipment_batch_row['data']['shipment_batch']}</b>)";
                    }
                    else
                    {
                        $this->session->set_flashdata('orderOperationMessage', $shipment_batch_row['message']);
                        $return = array(
                            'status' => 'error',
                        );
                    }

                    if (empty($shipment_batch_id))
                    {
                        $this->session->set_flashdata('orderOperationMessage', 'Please select shipment batch id in order to escalate to ' . $statuses[$status]['display_text'] . '.');
                        $return = array(
                            'status' => 'error',
                        );
                    }
                }

                if ($return['status'] !== 'error')
                {
                    $time = date('Y-m-d H:i:s');

                    $this->ordersModel->saveOrderStatus(array('order_id' => $order_id, 'active' => 'no', 'updated_at' => $time), array('order_id' => $order_id));

                    $resposibilityCalcData = array(
                        'employee_id' => $employee_id,
                        'order_id' => $order_id,
                        'status' => $status,
                        'delivery_date' => $order['delivery_date'],
                        'collection_date' => $order['collection_date'],
                        'statuses' => $statuses,
                    );
                    
                    if ($status == 'collected_at_warehouse')
                    {
                        $order_data = array(
                            'shipment_batch_id' => $shipment_batch_id,
                            'id' => $order_id,
                        );
                        $this->ordersModel->saveOrder($order_data);

                        $mapping_ids = $shipment_batch_row['data']['mapping_ids'];
                        //Update scanned quantity count in shipment_batch_box_mapping table
                        if (!empty($mapping_ids))
                        {
                            $this->load->model('admin/mastersModel');

                            foreach ($mapping_ids as $shipment_batch_box_mapping_id => $scanned_quantity)
                            {
                                $scanned_data = array('id' => $shipment_batch_box_mapping_id, 'scanned_quantity' => $scanned_quantity);
                                $this->mastersModel->saveShipmentBatchBoxMapping($scanned_data);
                            }
                        }

                        //Shipment batch auto closure takes place here
                        updateShipmentBatchStatusById($shipment_batch_id);

                        $responsibility_completed = 'yes';
                    }
                    else
                    {
                        $responsibility_completed = determineResponsbilityCompleted($resposibilityCalcData);
                    }
//                    if($status == "received_at_jakarta_warehouse" || $status == "delivered_at_jkt_picture_taken")
//                    {
//                        $jkt_date = $time;
//                        $save_jkt_receive_date = $this->ordersModel->saveJktReceiveDate($order_id,$jkt_date);
//                    }
                    if ($return['status'] !== 'error' && !empty($statuses[$status]['callback']))
                    {
                        if($status == "collection_attended_by_driver")
                        { 
                            $this->load->model('admin/restapimodel'); 
                            $customer_name   = $order['customer_name'];
                            $customer_number = $order['customer_mobile'];

                            $record = array(  
                                 'customer_name' => $customer_name,
                                 'customer_number' => $customer_number,
                                 'order_number' => $order['order_number'],
                                 'order_id' => $order_id
                            );
                            
                             $result = call_user_func($statuses[$status]['callback'], $record);

                             if(isset($result['data']))
                             {
                                 $save_sms_triggered_data = $this->restapimodel->save_sms_triggered($result);
                             }
                            
                        }
                    }
                    
                    $order_status = array(
                        'order_id' => $order_id,
                        'employee_id' => $employee_id,
                        'status' => $status,
                        'cash_collected' => $cash_collected,
                        'voucher_cash' => $voucher_cash,
                        'comments' => $comments,
                        'active' => 'yes',
                        'reassigned_stage' => 'no',
                        'responsibility_completed' => $responsibility_completed,
                        'status_escalation_type' => 'manual',
                        'updated_at' => $time,
                        'created_at' => $time,
                    );

                    $order_status = $this->ordersModel->saveOrderStatus($order_status);
                    
                    $this->session->set_flashdata('orderOperationMessage', "<b>{$order['order_number']}</b> escalated to <b>{$statuses[$status]['display_text']}</b>$success_msg_extra_info.");
                }
            }
        }

        echo json_encode($return);
    }

    public function orderStatusListing($order_id, $error = null)
    {
        //load css
        $dataArray['local_css'] = array(
            'datatable'
        );
        //load js
        $dataArray['local_js'] = array(
            'datatable');

        $this->load->model('admin/ordersModel');
        $order_details = $this->ordersModel->getOrderDetails($order_id);

        $order_status_details = $this->ordersModel->getOrderStatusDetails($order_id);
                    
        $payment_reference_details = $this->ordersModel->getPaymentReferenceDetailsByOrderId($order_id);
        
        $dataArray['order_id'] = $order_details['id'];
        $dataArray['order_number'] = $order_details['order_number'];
        $dataArray['order_status_details'] = $order_status_details;

        $status = $order_status_details['status'];
        
        $statuses = $this->config->item('consolidated_statuses');
        $next_status = $statuses[$status]['next'];
        
        $manual_esc_not_possible = '1';
        $non_escalated_status = 'received_at_jakarta_warehouse';
        $res = getStatusesByStatus($non_escalated_status);
        if (in_array($status, $res))
        {
         $next_status ="";
         if($status == $non_escalated_status)
         {
            $manual_esc_not_possible="";
         }
        }
        
        if (!empty($next_status))
        {
            $show_driver_drop_down_in_escalation = $statuses[$next_status]['show_driver_drop_down_in_escalation'];
        }
        $dataArray['next_raw_status'] = $next_status;

        if (!empty($next_status))
        {
            $geo_type = array('all');

            if ($next_status === 'collected_at_warehouse')
            {
                $type = 'warehousemanager';
                $geo_type[] = 'singapore';
                $dataArray['shipment_batches'] = getAllShipmentBatches();

                $this->load->model('admin/mastersModel');
                $shipment_batch_row = $this->mastersModel->getCurrentShipmentBatchDetails();

                if (empty($shipment_batch_row))
                {
                    $message = 'No active Shipment Batch found. Kindly report this incidence to Admin.';
                    $error = true;
                    $extra_error = true;
                }
            }
            else if ($next_status === 'received_at_jakarta_warehouse')
            {
                $geo_type[] = 'jakarta';
                $type = 'warehousemanager';
            }
            else if ($next_status === 'ready_for_receiving_at_jakarta')
            {
                $geo_type[] = 'singapore';
                $type = 'admin';
            }
            else
            {
                $geo_type[] = 'singapore';
                $type = 'driver';
            }

            $dataArray['employees'] = getEmployeesByRole($type, $geo_type);


            $dataArray['next_status'] = empty($extra_error) ? $statuses[$next_status] : '';
        }
        
        $dataArray['show_driver_drop_down_in_escalation'] = isset($show_driver_drop_down_in_escalation) ? $show_driver_drop_down_in_escalation : false;
        $message = empty($message) ? $this->session->flashdata('orderOperationMessage') : $message;
        $dataArray['message'] = $message;
        $dataArray['manual_esc_not_possible'] = $manual_esc_not_possible;
        $dataArray['error'] = $error;
        $dataArray['drivers'] = getEmployeesByRole('driver');
        
        $dataArray['redelivery_amount'] = $this->config->item('redelivery_amount');
        $dataArray['redelivery_history'] = $this->ordersModel->getRedeliveryHistory($order_id);
        $dataArray['first_redelivery'] = count($dataArray['redelivery_history']) > 0 ? 0 : 1;
        
        $dataArray['payment_reference_details'] = $payment_reference_details;

        $this->load->view('orders/orderStatusList', $dataArray);
    }

    public function getOrderStatusData($order_id)
    {
        $this->load->model('admin/ordersModel');
        $pagingParams = $_GET;

        $data = $this->ordersModel->getOrderStatusDataByOrder($order_id);
        $dataArray = array();
        
        $statutes = $this->config->item('consolidated_statuses');

        if ($data['foundRows'] > 0)
        {
            $total_status = count($data['resultSet']);
            
            $redelivery_history = $this->ordersModel->getRedeliveryHistory($order_id);
            
            foreach ($data['resultSet'] as $idx => $val)
            {
                $data['resultSet'][$idx]['serial_no'] = $idx + 1;
                $data['resultSet'][$idx]['status'] = $statutes[$val['status']]['display_text'];
                $data['resultSet'][$idx]['updated_at'] = date('d/m/Y, H:m:i', strtotime($val['updated_at']));
                $data['resultSet'][$idx]['redelivery'] = '';
                
                switch ($val['status'])
                {
                    case 'box_delivered':
                        $cash_collected = "<input step='0.1' type='number' value='" . str_replace(',', '', number_format($val['cash_collected'], 2)) . "' name='cash_collected[{$val['id']}]'/>";
                        $voucher_cash = '--';
                
                        
                        if (!empty($redelivery_history))
                        {
                            $data['resultSet'][$idx]['redelivery'] .= "<a href='javascript:void(0)' rel='{$val['id']}' title='View redelivery history' class='fake-redelivery-history-class'><i class='fa glyphicon-history'></i></a>&nbsp;";
                        }
                        
                        //For redelivery : We have to make sure if last status is delivered then only show redelivery interface
                        if (($total_status - 1) ==  $idx)
                        {
                            $data['resultSet'][$idx]['redelivery'] .= "<a href='javascript:void(0)' rel='{$val['id']}' title='Perform redelivery entry' class='fake-redelivery-class'><i class='fa fa-repeat'></i></a>";
                        }
                        
                        $data['resultSet'][$idx]['redelivery'] = empty($data['resultSet'][$idx]['redelivery']) ? '--' : $data['resultSet'][$idx]['redelivery'];
                        break;

                    case 'box_collected':
                        $cash_collected = "<input step='0.1' type='number' value='" . str_replace(',', '', number_format($val['cash_collected'], 2)) . "' name='cash_collected[{$val['id']}]'/>";
                        $voucher_cash = "<input step='0.1' type='number' value='" . str_replace(',', '', number_format($val['voucher_cash'], 2)) . "' name='voucher_cash[{$val['id']}]'/>";
                        break;

                    default:
                        $cash_collected = '--';
                        $voucher_cash = '--';
                }
                $manual_entry = $data['resultSet'][$idx]['qr_manual_entry'] = $val['qr_manual_entry'];
                if($manual_entry == true)
                {
                    $qr_manual_entry = "Yes";
                }
                else
                {
                    $qr_manual_entry = "No";
                }
                $data['resultSet'][$idx]['qr_manual_entry'] = $qr_manual_entry;
                $data['resultSet'][$idx]['cash_collected'] = $cash_collected;
                $data['resultSet'][$idx]['voucher_cash'] = $voucher_cash;

                $data['resultSet'][$idx]['edit'] = "<a href='" . base_url() . "admin/order/edit/" . $val['id'] . "'><i class='fa fa-edit'></i></a>";

                $delete = (($idx == ($data['foundRows'] - 1)) && ($idx !== 0)) ? "<a onClick=\"return confirm('Are you sure you want to delete?')\" href='" . base_url() . "admin/order/deleteOrderStatus/$order_id/" . $val['id'] . "'><i class='fa glyphicon-circle_remove'></i></a>" : '--';

                $data['resultSet'][$idx]['delete'] = $delete;
            }
        }

        $dataArray['iTotalRecords'] = $data['foundRows'];
        $dataArray['iTotalDisplayRecords'] = $data['foundRows'];
        $dataArray['sEcho'] = $pagingParams['sEcho'];
        $dataArray['aData'] = $data['resultSet'] == null ? array() : $data['resultSet'];

        echo json_encode($dataArray);
    }

    public function cancelOrder($order_id)
    {
        if (!empty($order_id))
        {
            $order_array['id'] = $order_id;
            $order_array['status'] = 'cancelled';
            $order_array['updated_at'] = date('Y-m-d H:i:s');

            $this->load->model('admin/ordersModel');
            $order_id = $this->ordersModel->saveOrder($order_array);

            $order = $this->ordersModel->getOrderDetails($order_id);
            $this->session->set_flashdata('orderOperationMessage', "{$order['order_number']} cancelled successfully.");
        }
        else
        {
            $this->session->set_flashdata('orderOperationMessage', 'Order Id is mandatory for order cancellation.');
        }

        redirect("admin/order/index");
    }

    public function updateOrderKIVStatus($order_id, $kiv_status)
    {
        if (!empty($order_id) && !empty($kiv_status))
        {
            $order_array['id'] = $order_id;
            $order_array['kiv_status'] = $kiv_status;
            $order_array['updated_at'] = date('Y-m-d H:i:s');

            $this->load->model('admin/ordersModel');
            $order_id = $this->ordersModel->saveOrder($order_array);

            $kiv_array = array(
                'order_id' => $order_id,
                'status' => $kiv_status,
                'created_by' => $this->_user_id,
                'created_at' => date('Y-m-d H:i:s'),
            );
            $kiv_status_id = $this->ordersModel->saveOrderKIVStatus($kiv_array);

            $order = $this->ordersModel->getOrderDetails($order_id);
            $this->session->set_flashdata('orderOperationMessage', "KIV status successfully for {$order['order_number']}.");
        }
        else
        {
            $this->session->set_flashdata('orderOperationMessage', 'Order Id & KIV Status is mandatory for updating KIV Status.');
        }

        redirect("admin/order/index");
    }

    public function deleteOrderStatus($order_id, $id)
    {
        $this->load->model('admin/ordersModel');

        $status_data = $this->ordersModel->getOrderStatusDataByOrder($order_id);
        $status_data = array_pop($status_data['resultSet']);

        $order = $this->ordersModel->getOrderDetails($order_id);

        
        switch ($status_data['status'])
        {
            case 'collected_at_warehouse':
                $shipment_batch_id = $order['shipment_batch_id'];

                //First decrement the scanned quantities in shipment_batch_mapping
                updateShipmentBatchBoxCountsByOrderId($order_id);

                //And then calculate whether shipment batch can be opened again.
                updateShipmentBatchStatusById($shipment_batch_id);

                $batch_id_data = array(
                    'id' => $order_id,
                    'shipment_batch_id' => null,
                );
                $this->ordersModel->saveOrder($batch_id_data);
                break;
            
            //NOTE - special hanling would be required because of redelivery module
            case 'box_delivered':
                break;
        }

        $data = $this->ordersModel->deleteOrderStatus($id);

        $status_data = $this->ordersModel->getOrderStatusDataByOrder($order_id);
        $status_data = array_pop($status_data['resultSet']);

        $statuses = $this->config->item('consolidated_statuses');

        $resposibilityCalcData = array(
            'employee_id' => $status_data['employee_id'],
            'order_id' => $order_id,
            'status' => $status_data['status'],
            'delivery_date' => $order['delivery_date'],
            'collection_date' => $order['collection_date'],
            'statuses' => $statuses,
        );
        $responsibility_completed = determineResponsbilityCompleted($resposibilityCalcData);

        $data = array(
            'id' => $status_data['id'],
            'active' => 'yes',
            'responsibility_completed' => $responsibility_completed
        );
        $this->ordersModel->saveOrderStatus($data);

        $this->session->set_flashdata('orderOperationMessage', 'Order status deleted successfully.');
        redirect("admin/order/orderStatusListing/$order_id");
    }

    public function saveOrderPrintStatus()
    {
        $type = $this->input->post('type');
        $order_ids = $this->input->post('order_ids');
        $order_ids = explode(',', $order_ids);

        if (!empty($order_ids))
        {
            $this->load->model('admin/ordersModel');

            foreach ($order_ids as $index => $order_id)
            {
                $data = array('id' => $order_id);

                $row = $this->ordersModel->getRow('orders', $data);

                $printed_instruments = $row['printed_instruments'];

                if (!empty($printed_instruments))
                {
                    $printed_instruments = explode(',', $printed_instruments);
                    if (!in_array($type, $printed_instruments))
                    {
                        $printed_instruments[] = $type;
                    }
                    $printed_instruments = implode(',', $printed_instruments);
                }
                else
                {
                    $printed_instruments = $type;
                }

                $data['printed_instruments'] = $printed_instruments;

                $this->ordersModel->saveOrder($data);
            }
        }
    }

    public function saveCashVoucherCollectionDetails()
    {
        $cash_collected = $this->input->post('cash_collected');
        $voucher_cash = $this->input->post('voucher_cash');
        $order_id = $this->input->post('order_id');

        $this->load->model('admin/ordersModel');
        $order = $this->ordersModel->getOrderDetails($order_id);

        if (!empty($cash_collected))
        {
            foreach ($cash_collected as $index => $cash_collected_item)
            {
                $data = array('id' => $index, 'updated_by' => $this->_user_id, 'cash_collected' => $cash_collected_item);
                $this->ordersModel->saveOrderStatus($data);
            }
        }

        if (!empty($voucher_cash))
        {
            foreach ($voucher_cash as $index => $voucher_cash_item)
            {
                $data = array('id' => $index, 'updated_by' => $this->_user_id, 'voucher_cash' => $voucher_cash_item);
                $this->ordersModel->saveOrderStatus($data);
            }
        }

        $this->session->set_flashdata('orderOperationMessage', "{$order['order_number']} updated successfully.");
        echo json_encode(array('code' => 200));
    }

    public function pictureDateInput()
    {
        $message = $this->session->flashdata('pictureDateOperationMessage');
        $dataArray['message'] = $message;
        $dataArray['local_css'] = array(
            'bootstrap_date_picker'
        );
        //load js
        $dataArray['local_js'] = array(
            'bootstrap_date_picker',
            'jquery-ui-1.11.2',
            'validation'
        );
        $order_number_config = $this->config->item("order_number_config");
        $dataArray['order_number_size_in_digits'] = $order_number_config['order_number_size_in_digits'];
        $this->load->view('orders/pictureDateInputForm', $dataArray);
    }

    public function imageUploadJkt()
    {
        $config_arr = $this->config->item("image_upload");

        // initialize and check uploaded file
        $config['upload_path'] = $config_arr['upload_dir'];
        $config['allowed_types'] = $config_arr['allowed_extensions'];

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('zip_file'))
        {
            $filedata = $this->upload->data();
            $errors['upload_file'] = $this->upload->display_errors('', '') . "({$filedata['file_name']})";
            
            // return validation error
            $result = array('status' => false, 'message' => $errors);
        }
        else
        {
            $filedata = $this->upload->data();

            $data = array(
                'original_archive' => $filedata['file_name'],
                'updated_by' => $this->_user_id,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
            $id = $this->ordersModel->saveOrderImageMaster($data);

            $original_dir = $filedata['file_name'];
            $original_dir_arr = explode('.', $original_dir);
            $extension = array_pop($original_dir_arr);

            $new_file = "$id.$extension";

            $source_dir = $config_arr['upload_dir'] . "/{$filedata['file_name']}";
            $destination_dir = $config_arr['upload_dir'] . "/$new_file";

            if (rename($source_dir, $destination_dir))
            {
                $data = array(
                    'renamed_archive' => $new_file,
                    'id' => $id,
                );
                $this->ordersModel->saveOrderImageMaster($data);

                $result = array('status' => true, 'data' => $id);
            }
            else
            {
                $result = array('status' => false, 'message' => 'Some error took place while renaming uploaded compressed file.');
            }
        }

        echo json_encode($result);
    }

    public function extractZipAndProcessImage($master_id)
    {
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');

        $status = false;
        $type = 'error';
        $message_id = $files_processed_count = 1;

        $row = $this->ordersModel->getOrderImageMaster($master_id);
        if (empty($row['renamed_archive']))
        {
            $message = 'Arhive does not exist';
        }
        else
        {
            $config_arr = $this->config->item("image_upload");

            $source_dir = $config_arr['upload_dir'] . "/{$row['renamed_archive']}";

            if (is_file($source_dir))
            {
                $destination_dir = $config_arr['extraction_dir'];

                $zip = new ZipArchive;

                if ($zip->open($source_dir) === TRUE)
                {
                    $zip->extractTo($destination_dir . "/$master_id");
                    $zip->close();

                    $filecount = 0;
                    $files = glob($destination_dir . "/$master_id/" . "*");

                    if ($files)
                    {
                        $filecount = count($files);
                    }

                    $dh = opendir($destination_dir . "/$master_id");
                    while (false !== ($filename = readdir($dh)))
                    {
//                        sleep(2);
                        $type = 'error';
                        if ($filename != '.' && $filename != '..')
                        {
                            $status = false;
                            $full_filename = $destination_dir . "/$master_id/$filename";

                            if (@is_array(getimagesize($full_filename)))
                            {
                                $filename_arr = explode('.', $filename);
                                $order_number = $filename_arr[0];

                                //Typecasting as there can be multiple images related to same order like this
                                // 87193a.png 87193b.png
                                $order_number = (int) $order_number;

                                $row = $this->ordersModel->searchOrderByParams(array('order_number' => $order_number));
                                if (!empty($row))
                                {
                                    $data = array(
                                        'name' => $filename
                                    );
                                    $this->ordersModel->deleteOrderImageTrans($data);
                                    
                                    $data = array(
                                        'order_id' => $row['id'],
                                        'order_image_master_id' => $master_id,
                                        'name' => $filename,
                                        'type' => 'jakarta',
                                        'status' => 'available',
                                        'updated_by' => $this->_user_id,
                                        'updated_at' => date('Y-m-d H:i:s'),
                                    );
                                    
                                    $get_order_stauts = $this->ordersModel->getOrderStatusDetails($data['order_id']);
                                    if($get_order_stauts['status'] == "delivered_at_jkt_picture_not_taken" && $get_order_stauts['active'] == "yes")
                                    {
                                        $CI = & get_instance();
                                        $get_order_number = $this->ordersModel->getOrderNumberById($data['order_id']);
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
                                        
                                        $curl_second = curl_init();
                                        curl_setopt_array($curl_second, array(
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
                                        
                                        $response_second = curl_exec($curl_second);
                                    }
                                    
                                    $tmp_id = $this->ordersModel->saveOrderImageTrans($data);
                                    if (empty($tmp_id))
                                    {
                                        $message = 'Some error occured while updating order image status ' . $filename;
                                    }
                                    else
                                    {

                                        $message = "$files_processed_count / $filecount processed. Now processing ($filename)";
                                        $type = 'success';
                                        $status = true;

                                        $files_processed_count++;
                                    }
                                }
                                else
                                {
                                    $message = "Order number $order_number not found.";
                                }
                            }
                            else
                            {
                                $message = 'Not a valid image file ' . $filename;
                            }

                            $data = array('message' => $message, 'type' => $type);
                            $this->send_message( ++$message_id, $data);
                        }
                    }

                    $data = array('message' => 'Finished', 'type' => 'success');
                    $this->send_message('CLOSE', $data);
                }
                else
                {
                    $message = 'Unable to open archive';
                }
            }
            else
            {
                $message = 'Renamed arhive does not exist';
            }
        }

        if ($status == false && $filecount <= 0)
        {
            $data = array('message' => $message, 'type' => $type);
            $this->send_message( ++$message_id, $data);

            $data = array('message' => 'Finished', 'type' => $type);
            $this->send_message('CLOSE', $message);
        }
    }

    public function send_message($id, $message)
    {
        $d = array('data' => $message);

        echo "id: $id" . PHP_EOL;
        echo "data: " . json_encode($d) . PHP_EOL;
        echo PHP_EOL;

        ob_flush();
        flush();
    }

    public function imageUploadJktForm()
    {
        $message = $this->session->flashdata('pictureDateOperationMessage');
        $dataArray['message'] = $message;
        $dataArray['local_css'] = array(
            'bootstrap_date_picker'
        );
        //load js
        $dataArray['local_js'] = array(
            'bootstrap_date_picker',
            'jquery-ui-1.11.2',
            'validation'
        );

        $order_number_config = $this->config->item("order_number_config");
        $dataArray['order_number_size_in_digits'] = $order_number_config['order_number_size_in_digits'];
        $this->load->view('orders/zipUploadForm', $dataArray);
    }

    public function checkOrderNumberExistAndStatus()
    {
        $order_number = $this->input->post('order_number');
        $check_only_order_number = $this->input->post('check_only_order_number');

        $this->load->model('ordersModel');
        $orderRec = $this->ordersModel->getOrderStatus($order_number);
        if (!empty($orderRec))
        {
            if (empty($check_only_order_number))
            {
                $status = $orderRec['status'];
                $picture_receiving_status = $this->config->item("picture_receiving_date_status");

                $status_arr = getStatusesByStatus($picture_receiving_status);

                if (!empty($orderRec['picture_receive_date']))
                {
                    $result = array(
                        'status' => 'error',
                        'errorMsg' => 'Picture receive date already entered.'
                    );
                }
                else
                {
                    if (in_array($status, $status_arr))
                    {
                        $result = array(
                            'status' => 'success'
                        );
                    }
                    else
                    {
                        $statusArr = $this->config->item("consolidated_statuses");

                        $statusDisplayText = $statusArr[$status]['display_text'];
                        $result = array(
                            'status' => 'error',
                            'errorMsg' => 'Order status : ' . $statusDisplayText
                        );
                    }
                }
            }
            else
            {
                $result = array(
                    'status' => 'success'
                );
            }
        }
        else
        {
            $result = array(
                'status' => 'error',
                'errorMsg' => "Order number doesn't Exist"
            );
        }
        echo json_encode($result);
    }

    public function savePictureDateInput()
    {
        $this->load->model('ordersModel');
        $pictureRecieveDate = date('Y-m-d', strtotime($this->input->post('pictureReceiveDate')));
        $orderNoArr = $this->input->post('orderNo');

        $orderEditLinks = array();

        if (!empty($orderNoArr))
        {
            foreach ($orderNoArr as $idx => $val)
            {
                if (!empty($val))
                {

                    $orderRec = $this->ordersModel->getOrderStatus($val);
                    if (!empty($orderRec))
                    {
                        $order_id = $orderRec['order_id'];
                        $dataVal = array(
                            'picture_receive_date' => $pictureRecieveDate,
                            'id' => $order_id
                        );
                        $this->ordersModel->saveOrder($dataVal);

                        $orderEditLinks[] = "<a style='color:green;font-weight:bold'  target='_new' href='" . base_url() . 'admin/order/orderBookingForm/' . $orderRec['order_id'] . "'>$val</a>";
                    }
                }
            }
        }

        $this->session->set_flashdata('pictureDateOperationMessage', 'Data updated successfully (' . implode(', ', $orderEditLinks) . ').');
        redirect('admin/order/pictureDateInput');
    }

    public function getMaxOrderJktStatusDaysByShipmentBatch($shipment_batch_id)
    {
        $response = 'Order(s) not found for selected shipment batch.';

        $this->load->model('admin/ordersModel');
        $data = $this->ordersModel->getOrderMaxDayRcvdATJktByShipmentBatchId($shipment_batch_id);
        if (!empty($data))
        {
//            $link = "<a target='_new' style='font-weight:bold;color:#31708f' href='" . base_url() . "admin/order/orderBookingForm/" . $data['order_id']. "'>{$data['order_number']}</a>";
//            $response = "<strong>{$data['max_days']}</strong> days fetched from $link";
            $days = $data['max_days'] > 1 ? 'days' : 'day';
            $response = "<strong>{$data['max_days']}</strong> $days.";
        }

        echo $response;
    }

    public function downloadJakartaImage($image_id)
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
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                $this->ordersModel->saveOrderImageTrans($dataValues);

                $config_arr = $this->config->item("image_upload");
                $extraction_dir = $config_arr['extraction_dir'];

                $filePath = $extraction_dir . "/{$row['order_image_master_id']}/{$row['name']}";

                if (file_exists($filePath))
                {
                    $fileName = basename($filePath);
                    $fileSize = filesize($filePath);

                    // Output headers.
                    header("Cache-Control: private");
                    header("Content-Type: application/stream");
                    header("Content-Length: " . $fileSize);
                    header("Content-Disposition: attachment; filename=" . $fileName);

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

    public function saveRedeliveryData()
    {
        $order_status_trans_id = $this->input->post('order_status_trans_id');
        $order_id = $this->input->post('order_id');
        $order_number = $this->input->post('order_number');
        $employee_id = $this->input->post('employee_id');
        $paid_to_driver = $this->input->post('paid_to_driver');
        $first_redelivery = $this->input->post('first_redelivery');        
        $commission_amount = $this->input->post('commission_amount');        
        
        $date_time = date('Y-m-d H:i:s');
        
        if ($first_redelivery)
        {
            //First save existing driver in driver redelivery history
            $this->load->model('ordersModel');
            $order_status_rec = $this->ordersModel->getOrderStatus($order_number);

            $redelivery_data = array(
                'employee_id' => $order_status_rec['employee_id'],
                'initial_delivery' => 'yes',
                'order_id' => $order_id,
                'updated_by' => $this->_user_id,
                'updated_at' => $date_time,
                'created_at' => $order_status_rec['created_at'],
            );
            $this->ordersModel->saveOrderRedelivery($redelivery_data);
        }
        
        $order_status = array(
            'id' => $order_status_trans_id,
            'employee_id' => $employee_id,
            'updated_at' => $date_time,
            'updated_by' => $this->_user_id,
        );
        $order_status = $this->ordersModel->saveOrderStatus($order_status);
        
        $redelivery_data = array(
            'employee_id' => $employee_id,
            'initial_delivery' => 'no',
            'paid_to_driver' => $paid_to_driver,
            'order_id' => $order_id,
            'commission_amount' => $commission_amount,
            'updated_by' => $this->_user_id,
            'updated_at' => $date_time,
            'created_at' => $date_time,
        );
        $this->ordersModel->saveOrderRedelivery($redelivery_data);
    }

    public function deleteRedeliveryHistory()
    {
        $update_driver_id_in_status = $this->input->post('update_driver_id_in_status');
        $redelivery_trans_id = $this->input->post('redelivery_trans_id');
        
        $this->load->model('admin/ordersModel');

        if ($update_driver_id_in_status)
        {
            $order_status_trans_id = $this->input->post('order_status_trans_id');
            $previous_driver_id = $this->input->post('previous_driver_id');
            
            $order_status = array(
                'id' => $order_status_trans_id,
                'employee_id' => $previous_driver_id,
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => $this->_user_id,
            );
            $order_status = $this->ordersModel->saveOrderStatus($order_status);
        }
        
        $this->ordersModel->deleteRedeliveryHistory(array('id' => $redelivery_trans_id));
    }

    public function updateMassOrderNoAndQRCode($start_order_no=null, $end_order_no=null)
    {
        ini_set('memory_limit','-1');
        ini_set('max_execution_time','-1');
        if(empty($start_order_no) && empty($end_order_no))
        {
            $start_order_no = $this->input->get('start_order_no');
            $end_order_no = $this->input->get('end_order_no');
        }
        if (empty($start_order_no))
        {
            return;
        }
        
        $this->load->model('admin/ordersmodel');
        $orders = $this->ordersmodel->getAllOrdersByRange($start_order_no, $end_order_no);

        if (!empty($orders))
        {
            foreach ($orders as $index => $row)
            {
                echo "Processing for {$row['order_id']}, {$row['order_number']} <br/>";
                generateOrderNoImage($row['order_id'], $row['order_number']);
                generateBarCode($row['order_id'], $row['order_number']);
            }
        }
    }
    
    public function shipmentInQuiry()
    {
        //load css
        $dataArray['local_css'] = array(
            'datatable', 'bootstrap_date_picker', 'multiselect', 'multiselect_filter'
        );
        //load js
        $dataArray['local_js'] = array(
            'datatable', 'bootstrap_date_picker', 'multiselect', 'multiselect_filter'
        );
            
        $order_number = $this->input->post('order_number');
        $shipment_batch_ids = $this->input->post('shipment_batch_ids');
            
        $dataArray['statutes'] = $this->config->item('consolidated_statuses');
        $dataArray['records'] = $this->ordersModel->getShipmentData($shipment_batch_ids, $order_number);            
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
            
        $dataArray['order_number'] = $order_number;        
        $this->load->view('orders/shipmentInquiryReports', $dataArray);
    }
    
    public function outStandingOrderPayment()
    { 
        //load css
        $dataArray['local_css'] = array(
            'datatable' ,'clock-picker', 'bootstrap_date_picker'
        );
        //load js
        $dataArray['local_js'] = array(
            'datatable', 'clock-picker', 'bootstrap_date_picker');
        $this->load->view('orders/outStandingOrderPayment', $dataArray);
    }
    
    public function getOutStandingOrderPaymentData()
    {
        $days = $this->input->get('days');
        $days = empty($days) ? 7 : $days;

        $statutes = $this->config->item('outstanding_statuses');
            
        $records = $this->reportlib->getOutStandingOrderPaymentData($days, $statutes);
        echo json_encode($records);
    }
    
    public function downloadOustStandingOrderPayment()
    { 
        $current_datetime = $this->input->get('current_datetime');
        $days = $this->input->get('days');
        
        $date = date("Y-m-d");
        $paginparam = array('return_arr' => '1');
        $statutes = $this->config->item('outstanding_statuses');
        $records = $this->ordersModel->getOutstandingOrderPayment($date, $days, $paginparam, $statutes);

        if(!empty($records))
        {   
            ini_set('memory_limit', '-1');
             
            header("Content-Type: application/xls"); 
            header("Content-Disposition: attachment; filename=OutstandingOrderPayment_".$current_datetime.".xls");
            
            header("Pragma: no-cache");
            header("Expires: 0");
            echo "ORDER#" . "\t";
            echo "Date" . "\t";
            echo "Name" . "\t";
            echo "Phone Number" . "\t";
            echo "Boxes" . "\t"; 
            echo "Qty" . "\t";
            echo "Destination" . "\t";
            echo "Order Total" . "\t";
            echo "Discount" . "\t";
            echo "Deposit" . "\t"; 
            echo "Amt Outstanding" . "\t"; 
            print("\n");
            
            foreach($records as $idx => $val)
            { 
                $val['order_date'] = date('d/m/Y', strtotime($val['order_date']));                 
                $val['boxes_name'] = isset($val['boxes_name']) ? $val['boxes_name'] : '--';
                $val['boxes_quantity'] = isset($val['boxes_quantity']) ? $val['boxes_quantity'] : '--';
                $val['kabupaten'] = isset($val['kabupaten']) ? $val['kabupaten'] : '--';
                $val['grand_total'] = isset($val['grand_total']) ? $val['grand_total'] : '--';
                $val['discount'] = isset($val['discount']) ? $val['discount'] : '--';        
                $val['tot_voucher_tot_cash_collected'] = isset($val['tot_voucher_tot_cash_collected']) ? $val['tot_voucher_tot_cash_collected'] : '0';
                $val['outstanding_order_payment'] = isset($val['outstanding_order_payment']) ? $val['outstanding_order_payment'] : '0';
                $val['boxes_name'] = preg_replace('#<br\s*/?>#', " ", $val['boxes_name']);
                $val['boxes_quantity'] = preg_replace('#<br\s*/?>#i', " ", $val['boxes_quantity']);
                $val['kabupaten'] = preg_replace('#<br\s*/?>#i', " ", $val['kabupaten']);
            
                if($val['outstanding_order_payment'] > 0)
                {
                    echo trim($val['order_number']) . "\t";
                    echo trim($val['order_date']) . "\t";
                    echo trim($val['name']) . "\t";
                    echo trim($val['mobile']) . "\t";
                    echo trim($val['boxes_name']). "\t";
                    echo trim($val['boxes_quantity']) . "\t";
                    echo trim($val['kabupaten']) . "\t";
                    echo trim($val['grand_total']) . "\t";
                    echo trim($val['discount']) . "\t";
                    echo trim($val['tot_voucher_tot_cash_collected']) . "\t";
                    echo trim($val['outstanding_order_payment']) . "\t";
                    print("\n");
                } 
            }   
        } 
    } 
    
    public function checkDuplicateCustomer()
    { 
        //load css
        $dataArray['local_css'] = array(
            'datatable' ,'clock-picker', 'bootstrap_date_picker'
        );
        //load js
         $dataArray['local_js'] = array(
            'datatable', 'bootstrap_date_picker', 'jquery-ui-1.11.2', 'hotkeys');
 
        $mobile = $this->input->post('mobile');
        $phone = $this->input->post('phone');
        $postal_code = $this->input->post('postal_code');
        $isRepeatedCustomer = $this->input->post('isRepeatedCustomer');
        $deliveryDate = $this->input->post('deliveryDate');
        
        if(empty($isRepeatedCustomer)  || $isRepeatedCustomer =='yes')
        {
            $duplicatCustomerOrdersData = $this->ordersModel->checkDuplicatiorCustomer($mobile, $phone, $postal_code, $deliveryDate);
        }
        else 
        {
            $duplicatCustomerOrdersData = "";
        }
        $dataArray['duplicatCustomerOrdersData'] = $duplicatCustomerOrdersData;
        $this->load->view('orders/duplicateCustomerOrderData',$dataArray);
    }
    
    public function getPromotionBoxes()
    {
        $this->load->model('admin/mastersmodel');
        $box_id = $this->input->post('box_id');
        $data['promotion_boxes'] = $this->mastersmodel->getPromotionBoxesByBoxId($box_id);
        if($data['promotion_boxes'])
        { 
            $this->load->setTemplate('blank');
            $return = $this->load->view('orders/promotionBoxesLists', $data, false);
        }
        else
        {
            $return = 0;
        }
        echo $return;
    }
    
    public function getAllPromoBoxesByBoxid()
    {
        $this->load->model('admin/mastersmodel');
        $promotion_id= $this->input->post('promotion_id');
        $data = $this->mastersmodel->getAllPromoBoxesByBoxid($promotion_id);
        $promocode_details = $data['id']."@#".$data['amount']."@#".$data['box_id']."@#".$data['name'];
        if($data)
        { 
            echo $promocode_details;
        }
        else
        {
            $return = 0;
            echo $return;
        }
    }
    
    public function getPromoCodeByid()
    {
        $this->load->model('admin/ordersModel');
        $promotion_id= $this->input->post('promotion_id'); 
        $promocode_details = $this->ordersModel->getPromotionById($promotion_id);
        if($promocode_details)
        { 
           $return = json_encode($promocode_details);
        }
        else
        {
            $return = 0;
        }
         echo $return;
    }
    
    public function customer_passport_update()
    {
        $post_data = $this->input->post();
        if(!isset($post_data["customer_id"]))
        {             
            $result = array( 'status' => 'error', 'msg' => "Mobile Invalid" );
            echo json_encode($result);exit;
        }
        
        foreach ($post_data["customer_id"] as $key => $value)
        {     
            if(!empty($_FILES["passport"]["name"][$key]))
            {
                $ext = pathinfo($_FILES["passport"]["name"][$key], PATHINFO_EXTENSION);
                $passport = "customer_passport_".$value.".".$ext;      

                if(file_exists('./assets/img/customer_passport/'.$passport))
                {
                    unlink('./assets/img/customer_passport/'.$passport);
                }

                $_FILES['file']['name'] = $_FILES['passport']['name'][$key];
                $_FILES['file']['type'] = $_FILES['passport']['type'][$key];
                $_FILES['file']['tmp_name'] = $_FILES['passport']['tmp_name'][$key];
                $_FILES['file']['error'] = $_FILES['passport']['error'][$key];
                $_FILES['file']['size'] = $_FILES['passport']['size'][$key];

                $configUpload['upload_path'] = './assets/img/customer_passport'; #the folder placed in the root of project
                $configUpload['allowed_types'] = '*'; #allowed types description
//                $configUpload['max_size'] = '1000'; #max size
//                $configUpload['max_width'] = '2048'; #max width
//                $configUpload['max_height'] = '1468'; #max height
    //            $configUpload['overwrite'] = true; #overwrite name of the uploaded file
                $configUpload['file_name'] = $passport; # save file name of the uploaded file
                $this->load->library('upload', $configUpload); #init the upload class

                if(!$this->upload->do_upload('file'))
                {
                    $uploadedDetails = $this->upload->display_errors(); 
                    $result = array( 'status' => 'error', 'msg' => $uploadedDetails );
                    echo json_encode($result);exit;
                }
                else
                {
                    $uploadedDetails = $this->upload->data(); 
                }

                $data["passport_img"] = $uploadedDetails["file_name"];
            }
            $this->load->model('admin/ordersmodel');

            $data["passport_id_number"] = $post_data["id_number"][$key];

            $customer = $this->ordersmodel->updateCustomerId_by_CustomerId($post_data["customer_id"][$key],$data);
        }
        
        $result = array( 'status' => 'success', 'msg' => "Update Successfully" , 'data' => $customer );
        echo json_encode($result);
    }
    
    public function delete_passport_img_by_customer_id()
    {
        $post_data = $this->input->post();
        
        if(!isset($post_data["customer_id"]))
        {             
            $result = array( 'status' => 'error', 'msg' => "Not Found" );
            echo json_encode($result);exit;
        }
        $this->load->model('admin/ordersmodel');
        
        if(file_exists('./assets/img/customer_passport/'.$post_data["passport_img"]))
        {
            unlink('./assets/img/customer_passport/'.$post_data["passport_img"]);
        }
        
        $data["passport_img"] = "";

        $customer = $this->ordersmodel->updateCustomerId_by_CustomerId($post_data["customer_id"],$data);
        
        
        $result = array( 'status' => 'success', 'msg' => "Deleted Passport Image Successfully" , 'data' => $customer );
        echo json_encode($result);
    }
}           
