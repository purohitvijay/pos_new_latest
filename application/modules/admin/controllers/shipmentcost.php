<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Shipmentcost extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('commonlibrary');
        $this->load->library('shipmentcostLib');
        $this->load->library('adminlib');

        $this->load->model('admin/shipmentcostModel');
        
        $this->config->load('shipment_cost_config');
    }

    public function shipmentReferenceList()
    {

        $this->load->view('shipmentcost/shipmentReferenceList');
    }

    public function shipmentCostingEntry()
    {
        $master_data = $this->shipmentcostlib->getShipmentCostData();
                
        $cost_config = $this->config->item("cost_config");
        $costing = array(
            'costing_data' => $cost_config,
            'masters_data' => $master_data
        );
        
        $this->load->view('shipmentcost/shipmentCostingMasterEntry', $costing);
    }

    public function SaveShipmentCostingMaster()
    {
        $text = $this->input->post('text');
        $geographical_type = $this->input->post('geographical_type');
        $section = $this->input->post('section');
        $scheme = $this->input->post('scheme');
        $item = $this->input->post('item');
        $costing = $this->input->post('costing');
        $type = $this->input->post('type');
        $container_type = $this->input->post('container_type');
        $currency = $this->input->post('currency');
        
        if (!empty($costing))
        {
            $created_at = date('Y-m-d H:i:s');
            
            foreach ($costing as $index => $value)
            {           
                $data = array(
                    'text' => $text[$index],
                    'geographical_type' => $geographical_type[$index],
                    'section' => $section[$index],
                    'scheme' => $scheme[$index],
                    'item' => $item[$index],
                    'type' => $type[$index],
                    'container_type' => $container_type[$index],
                    'currency' => $currency[$index],
                    
                );
                $this->shipmentcostModel->deleteShipmentCostingMasterData($data);
                
                $data['created_at'] = $created_at;
                $data['created_by'] = $this->_user_id;
                $data['costing'] = $value;
                
                $this->shipmentcostModel->saveShipmentCostingMaster($data);
            }
        }
        
        redirect('admin/shipmentcost/shipmentCostingEntry');
    }

    public function shipmentPaymentProcessingList()
    {
        $message = $this->session->flashdata('operationMessage');
        $dataArray['message'] = $message;
        //load css
        $dataArray['local_css'] = array(
            'datatable'
        );
        //load js
        $dataArray['local_js'] = array(
            'datatable');

        $this->load->view('shipmentcost/shipmentPaymentProcessingList', $dataArray);
    }

    public function shipmentCostingList()
    {
        $message = $this->session->flashdata('operationMessage');
        $dataArray['message'] = $message;
        //load css
        $dataArray['local_css'] = array(
            'datatable'
        );
        //load js
        $dataArray['local_js'] = array(
            'datatable');

        $this->load->view('shipmentcost/shipmentCostingList', $dataArray);
    }

    public function getShipmentPaymentData()
    {
        $paginparam = $_GET;

        $total = $this->shipmentcostModel->getShipmentPaymentCostCount($paginparam);
        $paymentReferenceData = $this->shipmentcostModel->getAllShipmentPaymentCosts($paginparam);
        $dataArray = array();

        foreach ($paymentReferenceData as $idx => $val)
        {
            $date = $val['date'];
            list($year, $month, $day) = explode('-', $date);
            $date = "$day/$month/$year";
            
            $paymentReferenceData[$idx]['date'] = $date;
            $paymentReferenceData[$idx]['delete'] = "<a title='Delete' href='" . base_url() . "admin/shipmentcost/shipmentCostPaymentRefDelete/" . $val['id'] . "' onClick=\"javascript:return confirm('Are you sure you want to delete?');\"'><i class='fa fa-times'></i></a>";
            $paymentReferenceData[$idx]['edit'] = "<a title='Edit' href='" . base_url() . "admin/shipmentcost/shipmentCostPaymentRefEdit/" . $val['id'] . "'><i class='fa fa-edit'></i></a>";
            $paymentReferenceData[$idx]['report'] = "<a title='View Line Items' href='" . base_url() . "admin/shipmentcost/viewPaymentRefLineItems/" . $val['id'] . "'><i class='fa fa-eye'></i></a> | "
                                            . "<a title='Download Line Items CSV' href='" . base_url() . "admin/shipmentcost/downloadShipmentCostLineItems/" . $val['id'] . "'><i class='fa fa-file'></i></a> ";
        }

        $dataArray['iTotalRecords'] = $total;
        $dataArray['iTotalDisplayRecords'] = $total;
        $dataArray['sEcho'] = $paginparam['sEcho'];
        $dataArray['aData'] = $paymentReferenceData;

        echo json_encode($dataArray);
    }

    public function shipmentPaymentCost()
    {
        $shipment_batch_id = $this->input->post('shipment_batch_id');
        $exchange_rate = $this->input->post('exchange_rate');
        $payment_reference = $this->input->post('payment_reference');
        $date = $this->input->post('date');
        $dataArray['custom_field_exchange_rate'] = $exchange_rate;
        
        if (!empty($shipment_batch_id) && !empty($exchange_rate) && !empty($payment_reference) && !empty($date))
        {
            
            $payment_reference_rec = $this->shipmentcostModel->checkPaymentReference($payment_reference);

            if (empty($payment_reference_rec))
            {
                $data = array(
                        'shipment_batch_id' => $shipment_batch_id,
                        'exchange_rate' => $exchange_rate,
                        'shipment_cost_eligible_boxes' => $this->config->item('shipment_cost_eligible_boxes'),
                        'shipment_cost_eligible_locations' => $this->config->item('shipment_cost_eligible_locations')
                    );
                
                $records = $this->shipmentcostlib->getShipmentCost($data);
                $location_master_data = $this->shipmentcostlib->getShipmentCostMastersData('location');
                $special_pack_data = $this->shipmentcostlib->getShipmentCostMastersData('Special Pack');
                
                
                $this->load->model('admin/mastersModel');
                $shipment_record = $this->mastersModel->getShipmentBatchById($shipment_batch_id);
                $shipment_record = (array) $shipment_record;
                $dataArray['shipment_record'] = $shipment_record;
                
                
                $container_type = $shipment_record['container_type'] == '40HC' ? 'shipment_container_40' : 'shipment_container_20';
                $where = array('geographical_type' => 'overseas', 'section' => $container_type);
                $freight_data = $this->shipmentcostlib->getShipmentCostMastersData('freight', $where);
                
                $dataArray['data'] = $records;
                $dataArray['masters_data']['location'] = $location_master_data;
                $dataArray['masters_data']['special_pack'] = $special_pack_data;
                $dataArray['masters_data']['freight'] = $freight_data;
                
                list($day, $month, $year) = explode('/', $date);
                
                $dataArray['formatted_date'] = "$year-$month-$day";
            }
            else
            {
                $dataArray['message'] = "Entered Payment Reference <a href='".base_url() . "admin/shipmentcost/viewPaymentRefLineItems/" . $payment_reference_rec['id']. "'>{$payment_reference_rec['payment_reference']}</a> already exists.</a>.";
            }
        }
        else
        {
            $date = date('d/m/Y');
            
            $dataArray['message'] = "Please enter parameters to generate listing.";
        }
        
        $dataArray['date'] = $date;
        $dataArray['shipment_batch_id'] = $shipment_batch_id;
        $dataArray['exchange_rate'] = $exchange_rate;
        $dataArray['payment_reference'] = $payment_reference;
        
        //load css
        $dataArray['local_css'] = array(
            'datatable', 'bootstrap_date_picker'
        );
        //load js
        $dataArray['local_js'] = array(
            'datatable', 'bootstrap_date_picker'
        );
        
        $dataArray['shipment_batches'] = $this->shipmentcostModel->getAvailableShipmentBatches();
        
        $this->load->view('shipmentcost/shipmentCosting', $dataArray);
    }

    public function shipmentCostPaymentRefEdit($shipment_cost_id)
    {
        if (!empty($shipment_cost_id))
        {
            $data['shipment_batch'] = $this->shipmentcostModel->getShipmentCostMasterById($shipment_cost_id);
            $data['data'] = $this->shipmentcostModel->getShipmentCostLineItems($shipment_cost_id);
            $shipment_batch_id = $data['shipment_batch']['shipment_batch_id'];
            $exchange_rate = $data['shipment_batch']['exchange_rate'];
            $payment_reference = $data['shipment_batch']['payment_reference'];
            $date = $data['shipment_batch']['date'];
            $dataArray['custom_field_exchange_rate'] = $exchange_rate;
//            $section = array();
//            foreach ($data['data'] as $key => $value) {
//                    $section[$value['section']][] = $value; 
//            }
//            p($section);
            if (!empty($shipment_batch_id) && !empty($exchange_rate) && !empty($payment_reference) && !empty($date))
            {
//                $data = array(
//                        'shipment_batch_id' => $shipment_batch_id,
//                        'exchange_rate' => $exchange_rate,
//                        'shipment_cost_eligible_boxes' => $this->config->item('shipment_cost_eligible_boxes'),
//                        'shipment_cost_eligible_locations' => $this->config->item('shipment_cost_eligible_locations')
//                    );
//
//                $records = $this->shipmentcostlib->getShipmentCost($data);
                $location_master_data = $this->shipmentcostlib->getShipmentCostMastersData('location');
                $special_pack_data = $this->shipmentcostlib->getShipmentCostMastersData('Special Pack');


                $this->load->model('admin/mastersModel');
                $shipment_record = $this->mastersModel->getShipmentBatchById($shipment_batch_id);
                $shipment_record = (array) $shipment_record;
                $dataArray['shipment_record'] = $shipment_record;


                $container_type = $shipment_record['container_type'] == '40HC' ? 'shipment_container_40' : 'shipment_container_20';
                $where = array('geographical_type' => 'overseas', 'section' => $container_type);
                $freight_data = $this->shipmentcostlib->getShipmentCostMastersData('freight', $where);

                $dataArray['data'] = $data['data'];
                $dataArray['masters_data']['location'] = $location_master_data;
                $dataArray['masters_data']['special_pack'] = $special_pack_data;
                $dataArray['masters_data']['freight'] = $freight_data;

                list($year, $month,$day ) = explode('-', $date);

                $dataArray['formatted_date'] = "$day/$month/$year";
                }
            else
            {
                $date = date('d/m/Y');

                $dataArray['message'] = "Please enter parameters to generate listing.";
            }

            $dataArray['date'] = $date;
            $dataArray['shipment_batch_id'] = $shipment_batch_id;
            $dataArray['exchange_rate'] = $exchange_rate;
            $dataArray['payment_reference'] = $payment_reference;
            $dataArray['shipment_cost_master_id'] = $shipment_cost_id;

            //load css
            $dataArray['local_css'] = array(
                'datatable', 'bootstrap_date_picker'
            );
            //load js
            $dataArray['local_js'] = array(
                'datatable', 'bootstrap_date_picker'
            );

            $dataArray['shipment_batches'] = $this->shipmentcostModel->getAvailableShipmentBatches();
              
            $this->load->view('shipmentcost/shipmentCostingEdit', $dataArray);
        }
    }
    
    function saveShipmentBatchCost()
    {
        $shipment_batch_id = $this->input->post('shipment_batch_id');
        $exchange_rate = $this->input->post('exchange_rate');
        $data = $this->input->post('data');
        $date = $this->input->post('date');
        $payment_reference = $this->input->post('payment_reference');
        $shipment_cost_line_item_id = $this->input->post('id');

        $line_item = $this->input->post('line_item');
        $description = $this->input->post('description');
        $count = $this->input->post('count');
        $local_currency_amount = $this->input->post('local_currency_amount');
        $foreign_currency_amount = $this->input->post('foreign_currency_amount');
        $master_data_reference = $this->input->post('master_data_reference');
        $section = $this->input->post('section');
        $type = $this->input->post('type');
        $shipment_cost_master_id = $this->input->post('shipment_cost_master_id');
        
        if (!empty($shipment_batch_id) && !empty($exchange_rate) && !empty($payment_reference))
        {
            if(!empty($shipment_cost_master_id))
            {
                 $data = array(
                    'shipment_batch_id' => $shipment_batch_id,
                    'exchange_rate' => $exchange_rate,
                    'data' => $data,
                    'date' => $date,
                    'payment_reference' => $payment_reference,
                    'created_by' => $this->_user_id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'id' => $shipment_cost_master_id,
                );
            }
            else
            {
                list($day, $month,$year ) = explode('/', $date);
                $date = "$year-$month-$day";

                $data = array(
                    'shipment_batch_id' => $shipment_batch_id,
                    'exchange_rate' => $exchange_rate,
                    'data' => $data,
                    'date' => $date,
                    'payment_reference' => $payment_reference,
                    'created_by' => $this->_user_id,
                    'created_at' => date('Y-m-d H:i:s'),
                );
            }
            $master_id = $this->shipmentcostModel->saveShipmentCostMaster($data);
        
            if (empty($master_id))
            {
                
            }
            else
            {
                
                $line_item = $this->input->post('line_item');
                $description = $this->input->post('description');
                $count = $this->input->post('count');
                $local_currency_amount = $this->input->post('local_currency_amount');
                $foreign_currency_amount = $this->input->post('foreign_currency_amount');
                $master_data_reference = $this->input->post('master_data_reference');
                $section = $this->input->post('section');
               
                if (!empty($line_item))
                {
                    if(!empty($shipment_cost_master_id))
                    {
                         $data = array(
                        'shipment_cost_master_id' => $shipment_cost_master_id,
                    ); 
                    }
                else {
                    $data = array(
                        'shipment_cost_master_id' => $master_id
                    );      
                    }      
                    foreach ($line_item as $index => $value)
                    {
                        $data['line_item'] = $value;
                        $data['section'] = $section[$index];
                        $data['type'] = $type[$index];
                        $data['description'] = $description[$index];
                        $data['count'] = $count[$index];
                        $data['local_currency_amount'] = $local_currency_amount[$index];
                        $data['foreign_currency_amount'] = isset($foreign_currency_amount[$index]) ? $foreign_currency_amount[$index] : '';
                        $data['master_data_reference'] = $master_data_reference[$index];
                        
                        if (!empty($shipment_cost_line_item_id)) {
                            $data['id'] = $shipment_cost_line_item_id[$index]; // in case update add id
                        }

                        $this->shipmentcostModel->saveShipmentCostLineItem($data);
                    }
                }
            }
        }
        else
        {
            
        }
    }
    
    public function viewPaymentRefLineItems($shipment_cost_id)
    {
        if (!empty($shipment_cost_id))
        {
            $data['shipment_batch'] = $this->shipmentcostModel->getShipmentCostMasterById($shipment_cost_id);
            $data['data'] = $this->shipmentcostModel->getShipmentCostLineItems($shipment_cost_id);
            
            $this->load->view('shipmentcost/viewShipmentCostLineItems', $data);
        }
    }
    
    public function downloadShipmentCostLineItems($shipment_cost_id)
    {
        if (!empty($shipment_cost_id))
        {
            $data['shipment_batch'] = $this->shipmentcostModel->getShipmentCostMasterById($shipment_cost_id);
            $data['data'] = $this->shipmentcostModel->getShipmentCostLineItems($shipment_cost_id);
            
            header("Content-Type: application/xls");
            header("Content-Disposition: attachment; filename=$shipment_cost_id.xls");
            header("Pragma: no-cache");
            header("Expires: 0");

            $this->load->setTemplate('blank');

            $csv = $this->load->view('shipmentcost/downloadShipmentCostLineItems', $data, false);
        }
    }

    public function shipmentCostPaymentRefDelete($id)
    {
        $this->load->model('commissionModel');
        $this->shipmentcostModel->deleteShipmentCostReference($id); 
        $this->shipmentcostModel->deleteShipmentCostReferenceLineItems($id);
        $this->session->set_flashdata('operationMessage', 'Shipment costing reference deleted successfully.');
        redirect('admin/shipmentcost/shipmentPaymentProcessingList');
    }
    
    public function shipmentCostingReportList()
    {
        $message = $this->session->flashdata('operationMessage');
        $dataArray['message'] = $message;
        //load css
        $dataArray['local_css'] = array(
            'datatable'
        );
        //load js
        $dataArray['local_js'] = array(
            'datatable');

        $this->load->view('shipmentcost/shipmentCostingReportList', $dataArray);
    }

    public function getShipmentCostingReportData()
    {
        $paginparam = $_GET;

        $total = $this->shipmentcostModel->getShipmentCostingReportCount($paginparam);
        $shipment_referenceData = $this->shipmentcostModel->getAllShipmentCostingReports($paginparam);
        $dataArray = array();

        foreach ($shipment_referenceData as $idx => $val)
        {
            $date = $val['date'];
            list($year, $month, $day) = explode('-', $date);
            $date = "$day/$month/$year";
            
            $shipment_referenceData[$idx]['date'] = $date;
            $shipment_referenceData[$idx]['delete'] = "<a title='Delete' href='" . base_url() . "admin/shipmentcost/shipmentCostShipmentRefDelete/" . $val['id'] . "' onClick=\"javascript:return confirm('Are you sure you want to delete?');\"'><i class='fa fa-times'></i></a>";
            $shipment_referenceData[$idx]['edit'] = "<a title='Edit' href='" . base_url() . "admin/shipmentcost/shipmentCostingReportEdit/" . $val['id'] . "'><i class='fa fa-edit'></i></a>";
            $shipment_referenceData[$idx]['report'] = "<a title='View Line Items' href='" . base_url() . "admin/shipmentcost/viewShipmentReportLineReportItems/" . $val['id'] . "'><i class='fa fa-eye'></i></a> | "
                                            . "<a title='Download Line Items CSV' href='" . base_url() . "admin/shipmentcost/downloadShipmentCostLineReportItems/" . $val['id'] . "'><i class='fa fa-file'></i></a> ";
        }
        
        $dataArray['iTotalRecords'] = $total;
        $dataArray['iTotalDisplayRecords'] = $total;
        $dataArray['sEcho'] = $paginparam['sEcho'];
        $dataArray['aData'] = $shipment_referenceData;

        echo json_encode($dataArray);
    }

    public function shipmentCostingReport()
    {
        $shipment_batch_id = $this->input->post('shipment_batch_id');
        $exchange_rate = $this->input->post('exchange_rate');
        $shipment_reference = $this->input->post('shipment_reference');
        $date = $this->input->post('date');
        $dataArray['custom_field_exchange_rate'] = $exchange_rate;
        $container_type = '';
        $total_seles = 0;
        $Saftri_total_seles = 0;
        $discount = 0;
        $records_count = 0;
        $Saftri_records_count = 0;
        $materials_data = array();
        $locationBox = array();
        $locationBox_sum = array();
        $agents_name = array();
        $Saftri_total_seles_order = array();
        $total_seles_order = array();
        $discount_order = array();
        $commission_orders = array();
        $commission_Special_orders = array();
        $Luar_Jawa = 0;
        if (!empty($shipment_batch_id) && !empty($exchange_rate) && !empty($shipment_reference) && !empty($date))
        {            
            $shipment_reference_rec = $this->shipmentcostModel->checkCostingReport($shipment_reference);

            if (empty($shipment_reference_rec))
            {                
                $this->load->model('admin/mastersModel');
                $data_record = array(
                        'shipment_batch_id' => $shipment_batch_id,
                        'exchange_rate' => $exchange_rate,
//                        'shipment_cost_eligible_boxes' => $this->config->item('shipment_cost_report_eligible_boxes'),
                        'shipment_cost_eligible_boxes' => array(),
                        'shipment_cost_eligible_locations' => array()
                    );
                $SaftriBox = $this->mastersModel->getSaftriBox();                
                $data_Saftri = array(
                        'shipment_batch_id' => $shipment_batch_id,
                        'exchange_rate' => $exchange_rate,
                        'shipment_cost_eligible_boxes' => $SaftriBox,
                        'shipment_cost_eligible_locations' => array()
                    );
                $locations_box = $this->shipmentcostlib->getShipmentCost($data_record);
                $locationBoxPrice = $this->adminlib->getlocationBoxPriceMapping();
                foreach ($locations_box["location_box_count"] as $key => $value) 
                {
                    foreach ($value["boxes"] as $box_key => $box) 
                    {       
                        $locationBox[$value["location"]][$box_key] = (isset($locationBoxPrice[$value["location_id"]][$box_key]))?($locationBoxPrice[$value["location_id"]][$box_key] * $box["quantity"]) : 0;
                    }
                }        
                $records = $this->shipmentcostlib->getShipmentCostReport($data_record);
                $orders = $this->shipmentcostModel->getOrdersShipmentBatch($data_record);
                if($orders)
                {
                    foreach ($orders as $key => $value) 
                    {
                        if($value["location"] == "Luar Jawa" )
                            $Luar_Jawa += $value["quantity"];                        
                    }                    
                }
                $SpecialBoxes = $this->shipmentcostModel->getShipmentBatchOrderWiseSpecialBoxes($data_record);
                if($SpecialBoxes)                    
                    $commission_Special_orders = array_sum(array_column($SpecialBoxes,"commission_orders"));
                
                if($records)
                {
                    $total_seles_order = array_column($records,"total_seles");
                    $discount_order = array_column($records,"discount");
                    $commission_orders = array_sum(array_column($records,"commission_orders"));
                }
                $Saftri_records = $this->shipmentcostlib->getShipmentCostReport($data_Saftri);
                if($Saftri_records)
                    $Saftri_total_seles_order = array_column($Saftri_records,"total_seles");
                $records_count = $this->shipmentcostlib->getShipmentCostReport($data_record,true);
                $Saftri_records_count = $this->shipmentcostlib->getShipmentCostReport($data_Saftri,true);
                $shipment_record = $this->mastersModel->getShipmentBatchById($shipment_batch_id);
                $shipment_record = (array) $shipment_record;
                $dataArray['shipment_record'] = $shipment_record;
                
                $container_type = $shipment_record['container_type'] == '40HC' ? 'shipment_container_40' : 'shipment_container_20';
                $materials = $this->shipmentcostlib->getShipmentCostMastersData("", array('geographical_type' => 'domestic', 'section' => "box_material_costing"));
                $distribution_local_data = $this->shipmentcostlib->getShipmentCostMastersData('location');
                $distribution_overseas_data = $this->shipmentcostlib->getShipmentCostMastersData('location_overseas');
                $freight_local_data= $this->shipmentcostlib->getShipmentCostMastersData('freight_local', array('geographical_type' => 'domestic', 'section' => $container_type));
                $freight_overseas_data = $this->shipmentcostlib->getShipmentCostMastersData('freight', array('geographical_type' => 'overseas', 'section' => $container_type));
                foreach ($orders as $key => $value) 
                {
                    if($value["agents_name"])
                    {
                        if(isset($agents_name[$value["agents_name"]]))
                            $agents_name[$value["agents_name"]] += $value["commission"];
                        else
                            $agents_name[$value["agents_name"]] = $value["commission"];
                    }
                }
                foreach ($total_seles_order as $key => $value) 
                {
                    $total_seles += $value;
                    $discount += $discount_order[$key];
                }
                foreach ($Saftri_total_seles_order as $key => $value) 
                {
                    $Saftri_total_seles += $value;
                }
                
                foreach ($materials as $key => $value) 
                {
                    $materials_data[ucfirst(strtolower(trim(preg_replace("/\([^)]+\)/","",$value["text"]))))] = $value["costing"];
                }
                $locationBox = array();
                foreach ($locations_box["location_box_count"] as $key => $value) 
                {
                    foreach ($value["boxes"] as $box_key => $box) 
                    {       
                        $locationBox[$value["location"]][$box_key] = (isset($locationBox[$value["location"]][$box_key])) ? $box["quantity"] : $box["quantity"];
                    }
                } 
//                foreach ($distribution_local_data as $location => $value) 
//                {       
//                    if(isset($locationBox[$location]))
//                        $locationBox_sum[$location] = array_sum($locationBox[$location]) * $distribution_local_data[$location];
//                    else
//                        $locationBox_sum[$location] = 0;
//                }   
                foreach ($locationBox as $location => $value) 
                {       
                    if(isset($distribution_local_data[$location]))
                        $locationBox_sum[$location] = array_sum($locationBox[$location]) * $distribution_local_data[$location];
                    else
                        $locationBox_sum[$location] = 0;
                } 
                $dataArray['masters_data']['records'] = $records;
                $dataArray['masters_data']['total_seles'] = $total_seles;
                $dataArray['masters_data']['Saftri_total_seles'] = $Saftri_total_seles;
                $dataArray['masters_data']['discount'] = $discount;
                $dataArray['masters_data']['agents_name'] = $agents_name;
                $dataArray['masters_data']['records_count'] = ($records_count-$Saftri_records_count);
                $dataArray['masters_data']['Saftri_records_count'] = $Saftri_records_count;
                $dataArray['masters_data']['total_records_count'] = $records_count;
                $dataArray['masters_data']['materials'] = $materials_data;
                $dataArray['masters_data']['distribution_local'] = $locationBox_sum;
                $dataArray['masters_data']['distribution_overseas'] = $distribution_overseas_data;
                $dataArray['masters_data']['freight_local'] = $freight_local_data;
                $dataArray['masters_data']['freight_overseas'] = $freight_overseas_data;
                $dataArray['masters_data']['commission_orders'] = $commission_orders;
                $dataArray['masters_data']['commission_Special_orders'] = $commission_Special_orders;
                $dataArray['masters_data']['Luar_Jawa'] = $Luar_Jawa;
                
                list($day, $month, $year) = explode('/', $date);
                
                $dataArray['formatted_date'] = "$year-$month-$day";
            }
            else
            {
                $dataArray['message'] = "Entered Payment Reference <a href='".base_url() . "admin/shipmentcost/viewPaymentRefLineReportItems/" . $shipment_reference_rec['id']. "'>{$shipment_reference_rec['shipment_reference']}</a> already exists.</a>.";
            }
        }
        else
        {
            $date = date('d/m/Y');
            
            $dataArray['message'] = "Please enter parameters to generate listing.";
        }
        
        $dataArray['date'] = $date;
        $dataArray['shipment_batch_id'] = $shipment_batch_id;
        $dataArray['exchange_rate'] = $exchange_rate;
        $dataArray['shipment_reference'] = $shipment_reference;
        $dataArray['container_type'] = $container_type;
        
        //load css
        $dataArray['local_css'] = array(
            'datatable', 'bootstrap_date_picker'
        );
        //load js
        $dataArray['local_js'] = array(
            'datatable', 'bootstrap_date_picker'
        );
        $dataArray['shipment_batches'] = $this->shipmentcostModel->getAvailableShipmentBatches();
        $this->load->view('shipmentcost/shipmentCostingReport', $dataArray);
    }

    public function shipmentCostingReportEdit($shipment_cost_id)
    {
        if (!empty($shipment_cost_id))
        {
            $data['shipment_batch'] = $this->shipmentcostModel->getShipmentCostReportMasterById($shipment_cost_id);
            $data['data'] = $this->shipmentcostModel->getShipmentCostReportLineItems($shipment_cost_id);
            $shipment_batch_id = $data['shipment_batch']['shipment_batch_id'];
            $exchange_rate = $data['shipment_batch']['exchange_rate'];
            $shipment_reference = $data['shipment_batch']['shipment_reference'];
            $date = $data['shipment_batch']['date'];
            $dataArray['custom_field_exchange_rate'] = $exchange_rate;
            $container_type = '';
            $total_seles = 0;
            $Saftri_total_seles = 0;
            $discount = 0;
            $records_count = 0;
            $Saftri_records_count = 0;
            $materials_data = array();
            $locationBox = array();
            $locationBox_sum = array();
            $agents_name = array();
            $Saftri_total_seles_order = array();
            $total_seles_order = array();
            $discount_order = array();
            $commission_orders = array();
            $commission_Special_orders = array();
            $Luar_Jawa = 0;
            if (!empty($shipment_batch_id) && !empty($exchange_rate) && !empty($shipment_reference) && !empty($date))
            {        
                $this->load->model('admin/mastersModel');
                $data_record = array(
                        'shipment_batch_id' => $shipment_batch_id,
                        'exchange_rate' => $exchange_rate,
//                        'shipment_cost_eligible_boxes' => $this->config->item('shipment_cost_report_eligible_boxes'),
                        'shipment_cost_eligible_boxes' => array(),
                        'shipment_cost_eligible_locations' => array()
                    );
                $SaftriBox = $this->mastersModel->getSaftriBox();                
                $data_Saftri = array(
                        'shipment_batch_id' => $shipment_batch_id,
                        'exchange_rate' => $exchange_rate,
                        'shipment_cost_eligible_boxes' => $SaftriBox,
                        'shipment_cost_eligible_locations' => array()
                    );
                $locations_box = $this->shipmentcostlib->getShipmentCost($data_record);
                $locationBoxPrice = $this->adminlib->getlocationBoxPriceMapping();
                foreach ($locations_box["location_box_count"] as $key => $value) 
                {
                    foreach ($value["boxes"] as $box_key => $box) 
                    {       
                        $locationBox[$value["location"]][$box_key] = (isset($locationBoxPrice[$value["location_id"]][$box_key]))?($locationBoxPrice[$value["location_id"]][$box_key] * $box["quantity"]) : 0;
                    }
                }        
                $records = $this->shipmentcostlib->getShipmentCostReport($data_record);
                $orders = $this->shipmentcostModel->getOrdersShipmentBatch($data_record);
                if($orders)
                {
                    foreach ($orders as $key => $value) 
                    {
                        if($value["location"] == "Luar Jawa" )
                            $Luar_Jawa += $value["quantity"];                        
                    }                    
                }
                
                $SpecialBoxes = $this->shipmentcostModel->getShipmentBatchOrderWiseSpecialBoxes($data_record);
                if($SpecialBoxes)                    
                    $commission_Special_orders = array_sum(array_column($SpecialBoxes,"commission_orders"));
                if($records)
                {
                    $total_seles_order = array_column($records,"total_seles");
                    $discount_order = array_column($records,"discount");
                    $commission_orders = array_sum(array_column($records,"commission_orders"));
                }
                $Saftri_records = $this->shipmentcostlib->getShipmentCostReport($data_Saftri);
                if($Saftri_records)
                    $Saftri_total_seles_order = array_column($Saftri_records,"total_seles");
                
                $records_count = $this->shipmentcostlib->getShipmentCostReport($data_record,true);
                $Saftri_records_count = $this->shipmentcostlib->getShipmentCostReport($data_Saftri,true);
                $shipment_record = $this->mastersModel->getShipmentBatchById($shipment_batch_id);
                $shipment_record = (array) $shipment_record;
                $dataArray['shipment_record'] = $shipment_record;
                
                $container_type = $shipment_record['container_type'] == '40HC' ? 'shipment_container_40' : 'shipment_container_20';
                $materials = $this->shipmentcostlib->getShipmentCostMastersData("", array('geographical_type' => 'domestic', 'section' => "box_material_costing"));
                $distribution_local_data = $this->shipmentcostlib->getShipmentCostMastersData('location');
                $distribution_overseas_data = $this->shipmentcostlib->getShipmentCostMastersData('location_overseas');
                $freight_local_data= $this->shipmentcostlib->getShipmentCostMastersData('freight_local', array('geographical_type' => 'domestic', 'section' => $container_type));
                $freight_overseas_data = $this->shipmentcostlib->getShipmentCostMastersData('freight', array('geographical_type' => 'overseas', 'section' => $container_type));
                foreach ($orders as $key => $value) 
                {
                    if($value["agents_name"])
                    {
                        if(isset($agents_name[$value["agents_name"]]))
                            $agents_name[$value["agents_name"]] += $value["commission"];
                        else
                            $agents_name[$value["agents_name"]] = $value["commission"];
                    }
                }
                foreach ($total_seles_order as $key => $value) 
                {
                    $total_seles += $value;
                    $discount += $discount_order[$key];
                }
                foreach ($Saftri_total_seles_order as $key => $value) 
                {
                    $Saftri_total_seles += $value;
                }
                
                foreach ($materials as $key => $value) 
                {
                    $materials_data[ucfirst(strtolower(trim(preg_replace("/\([^)]+\)/","",$value["text"]))))] = $value["costing"];
                } 
                $locationBox = array();
                foreach ($locations_box["location_box_count"] as $key => $value) 
                {
                    foreach ($value["boxes"] as $box_key => $box) 
                    {       
                        $locationBox[$value["location"]][$box_key] = (isset($locationBox[$value["location"]][$box_key])) ? $box["quantity"] : $box["quantity"];
                    }
                } 
//                foreach ($distribution_local_data as $location => $value) 
//                {       
//                    if(isset($locationBox[$location]))
//                        $locationBox_sum[$location] = array_sum($locationBox[$location]) * $distribution_local_data[$location];
//                    else
//                        $locationBox_sum[$location] = 0;
//                } 
                foreach ($locationBox as $location => $value) 
                {       
                    if(isset($distribution_local_data[$location]))
                        $locationBox_sum[$location] = array_sum($locationBox[$location]) * $distribution_local_data[$location];
                    else
                        $locationBox_sum[$location] = 0;
                } 
                $dataArray['masters_data']['records'] = $records;
                $dataArray['masters_data']['total_seles'] = $total_seles;
                $dataArray['masters_data']['Saftri_total_seles'] = $Saftri_total_seles;
                $dataArray['masters_data']['discount'] = $discount;
                $dataArray['masters_data']['agents_name'] = $agents_name;
                $dataArray['masters_data']['records_count'] = ($records_count-$Saftri_records_count);
                $dataArray['masters_data']['Saftri_records_count'] = $Saftri_records_count;
                $dataArray['masters_data']['total_records_count'] = $records_count;
                $dataArray['masters_data']['materials'] = $materials_data;
                $dataArray['masters_data']['distribution_local'] = $locationBox_sum;
                $dataArray['masters_data']['distribution_overseas'] = $distribution_overseas_data;
                $dataArray['masters_data']['freight_local'] = $freight_local_data;
                $dataArray['masters_data']['freight_overseas'] = $freight_overseas_data;
                $dataArray['masters_data']['commission_orders'] = $commission_orders;
                $dataArray['masters_data']['commission_Special_orders'] = $commission_Special_orders;
                $dataArray['masters_data']['Luar_Jawa'] = $Luar_Jawa;
                
                list($year, $month,$day ) = explode('-', $date);

                $dataArray['formatted_date'] = "$day/$month/$year";
                }
            else
            {
                $date = date('d/m/Y');

                $dataArray['message'] = "Please enter parameters to generate listing.";
            }

            $dataArray['date'] = $date;
            $dataArray['shipment_batch_id'] = $shipment_batch_id;
            $dataArray['exchange_rate'] = $exchange_rate;
            $dataArray['shipment_reference'] = $shipment_reference;
            $dataArray['shipment_cost_report_master_id'] = $shipment_cost_id;
            $dataArray['container_type'] = $container_type;

            //load css
            $dataArray['local_css'] = array(
                'datatable', 'bootstrap_date_picker'
            );
            //load js
            $dataArray['local_js'] = array(
                'datatable', 'bootstrap_date_picker'
            );

            $dataArray['shipment_batches'] = $this->shipmentcostModel->getAvailableShipmentBatches();
              
            $this->load->view('shipmentcost/shipmentCostingReportEdit', $dataArray);
        }
    }
    
    function saveShipmentBatchCostingReport()
    {
        $shipment_batch_id = $this->input->post('shipment_batch_id');
        $exchange_rate = $this->input->post('exchange_rate');
        $data = $this->input->post('data');
        $date = $this->input->post('date');
        $total_seles = $this->input->post('total_seles');
        $discount = $this->input->post('discount');
        $records_count = $this->input->post('records_count');
        $Saftri_records_count = $this->input->post('Saftri_records_count');
        $total_records_count = $this->input->post('total_records_count');
        $shipment_reference = $this->input->post('shipment_reference');
        $shipment_cost_line_item_id = $this->input->post('id');

        $line_item = $this->input->post('line_item');
        $description = $this->input->post('description');
        $count = $this->input->post('count');
        $local_currency_amount = $this->input->post('local_currency_amount');
        $master_data_reference = $this->input->post('master_data_reference');
        $section = $this->input->post('section');
        $quantity = $this->input->post('quantity');
        $type = $this->input->post('type');
        $shipment_cost_report_master_id = $this->input->post('shipment_cost_report_master_id');
        
        if (!empty($shipment_batch_id) && !empty($exchange_rate) && !empty($shipment_reference))
        {
            if(!empty($shipment_cost_report_master_id))
            {
                 $data = array(
                    'shipment_batch_id' => $shipment_batch_id,
                    'exchange_rate' => $exchange_rate,
                    'data' => $data,
                    'date' => $date,
                    'total_seles' => $total_seles,
                    'discount' => $discount,
                    'records_count' => $records_count,
                    'Saftri_records_count' => $Saftri_records_count,
                    'total_records_count' => $total_records_count,
                    'shipment_reference' => $shipment_reference,
                    'created_by' => $this->_user_id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'id' => $shipment_cost_report_master_id,
                );
            }
            else
            {
                list($day, $month,$year ) = explode('/', $date);
                $date = "$year-$month-$day";

                $data = array(
                    'shipment_batch_id' => $shipment_batch_id,
                    'exchange_rate' => $exchange_rate,
                    'data' => $data,
                    'date' => $date,
                    'total_seles' => $total_seles,
                    'discount' => $discount,
                    'records_count' => $records_count,
                    'Saftri_records_count' => $Saftri_records_count,
                    'total_records_count' => $total_records_count,
                    'shipment_reference' => $shipment_reference,
                    'created_by' => $this->_user_id,
                    'created_at' => date('Y-m-d H:i:s'),
                );
            }
            $master_id = $this->shipmentcostModel->saveShipmentCostReportMaster($data);
        
            if (empty($master_id))
            {
                
            }
            else
            {
                
                $line_item = $this->input->post('line_item');
                $description = $this->input->post('description');
                $count = $this->input->post('count');
                $local_currency_amount = $this->input->post('local_currency_amount');
                $master_data_reference = $this->input->post('master_data_reference');
                $section = $this->input->post('section');
                $quantity = $this->input->post('quantity');
               
                if (!empty($line_item))
                {
                    if(!empty($shipment_cost_report_master_id))
                    {
                         $data = array(
                            'shipment_cost_report_master_id' => $shipment_cost_report_master_id,
                        ); 
                    }
                    else 
                    {
                        $data = array(
                            'shipment_cost_report_master_id' => $master_id
                        );      
                    }      
                    
                    $this->shipmentcostModel->deleteShipmentCostLineReportItem($data["shipment_cost_report_master_id"]);
                    foreach ($line_item as $index => $value)
                    {
                        $data['line_item'] = $value;
                        $data['section'] = $section[$index];
                        $data['type'] = $type[$index];
                        $data['quantity'] = $quantity[$index];
                        $data['description'] = $description[$index];
                        $data['count'] = $count[$index];
                        $data['local_currency_amount'] = $local_currency_amount[$index];
                        $data['master_data_reference'] = $master_data_reference[$index];
                        
                        if (!empty($shipment_cost_line_item_id)) 
                        {
                            $data['id'] = $shipment_cost_line_item_id[$index]; // in case update add id
                        }

                        $this->shipmentcostModel->saveShipmentCostLineReportItem($data);
                    }
                }
            }
        }
        else
        {
            
        }
    }
    
    public function viewShipmentReportLineReportItems($shipment_cost_id)
    {
        if (!empty($shipment_cost_id))
        {
            $data['shipment_batch'] = $this->shipmentcostModel->getShipmentCostReportMasterById($shipment_cost_id);
            $data['data'] = $this->shipmentcostModel->getShipmentCostLineReportItems($shipment_cost_id);
            
            $this->load->view('shipmentcost/viewShipmentCostLineReportItems', $data);
        }
    }
    
    public function downloadShipmentCostLineReportItems($shipment_cost_id)
    {
        if (!empty($shipment_cost_id))
        {
            $data['shipment_batch'] = $this->shipmentcostModel->getShipmentCostReportMasterById($shipment_cost_id);
            $data['data'] = $this->shipmentcostModel->getShipmentCostLineReportItems($shipment_cost_id);
            
            header("Content-Type: application/xls");
            header("Content-Disposition: attachment; filename=Shipment_report_$shipment_cost_id.xls");
            header("Pragma: no-cache");
            header("Expires: 0");

            $this->load->setTemplate('blank');

            $csv = $this->load->view('shipmentcost/downloadShipmentCostLineReportItems', $data, false);
        }
    }

    public function shipmentCostShipmentRefDelete($id)
    {
        $this->load->model('commissionModel');
        $this->shipmentcostModel->deleteShipmentCostReferenceReport($id); 
        $this->shipmentcostModel->deleteShipmentCostReferenceLineItemsReport($id);
        $this->session->set_flashdata('operationMessage', 'Shipment costing reference deleted successfully.');
        redirect('admin/shipmentcost/shipmentCostingReportList');
    }
}