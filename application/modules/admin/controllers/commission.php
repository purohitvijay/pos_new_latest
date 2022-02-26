<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Commission extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('commonlibrary');

        $this->load->library('CommissionLib');
        $this->load->model('admin/commissionModel');
    }
    
    public function paymentReferenceList()
    {
        $message = $this->session->flashdata('paymentReferenceOperationMessage');
        $dataArray['message'] = $message;
        //load css
        $dataArray['local_css'] = array(
            'datatable'
        );
        //load js
        $dataArray['local_js'] = array(
            'datatable');

        $this->load->view('commission/paymentReferenceList', $dataArray);
    }

    public function viewPaymentRefLineItems($payment_reference_id)
    {
        //load css
        $dataArray['local_css'] = array(
            'datatable'
        );
        //load js
        $dataArray['local_js'] = array(
            'datatable');

        $lineItemsData = $this->commissionModel->getPaymentReferenceLineItems($payment_reference_id);
        $lineItemsSumData = $this->commissionModel->getPaymentReferenceLineItems($payment_reference_id, $boxWiseTotal=true);
        $paymentReferenceData = $this->commissionModel->getPaymentReferenceData($payment_reference_id);
                                            
        $dataArray['data'] = $paymentReferenceData;
        $dataArray['line_items_data'] = $lineItemsData;
        $dataArray['line_items_sum_data'] = $lineItemsSumData;
        $this->load->view('commission/lineItems', $dataArray);
    }

    public function downloadPaymentRefLineItems($payment_reference_id)
    {
        $lineItemsData = $this->commissionModel->getPaymentReferenceLineItems($payment_reference_id);
        $paymentReferenceData = $this->commissionModel->getPaymentReferenceData($payment_reference_id);
        $paymentReferenceOrdersData = $this->commissionModel->getPaymentReferenceOrdersData($payment_reference_id);
        $lineItemsSumData = $this->commissionModel->getPaymentReferenceLineItems($payment_reference_id, $boxWiseTotal=true);
        
        $dataArray['data'] = $paymentReferenceData;
        $dataArray['line_items_data'] = $lineItemsData;
        $dataArray['line_items_order_data'] = $paymentReferenceOrdersData;
        $dataArray['line_items_sum_data'] = $lineItemsSumData;
        
        ini_set('memory_limit', '-1');

        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename={$paymentReferenceData['payment_reference']}.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        
        $this->load->setTemplate('blank');
        
        $csv = $this->load->view('commission/lineItemsCSV', $dataArray, false);
    }

    public function viewPaymentRefLineItemsDetailed($payment_reference_id)
    {
        //load css
        $dataArray['local_css'] = array(
            'datatable'
        );
         //load js
        $dataArray['local_js'] = array(
            'datatable');

        $lineItemsData = $this->commissionModel->getPaymentReferenceLineItems($payment_reference_id);
        $paymentReferenceData = $this->commissionModel->getPaymentReferenceData($payment_reference_id);
        $paymentReferenceOrdersData = $this->commissionModel->getPaymentReferenceOrdersData($payment_reference_id);
                                            
        $dataArray['data'] = $paymentReferenceData;
        $dataArray['line_items_data'] = $lineItemsData;
        $dataArray['line_items_order_data'] = $paymentReferenceOrdersData;
                                            
        $this->load->view('commission/lineItemsDetailed', $dataArray);
    }
    
    public function downloadPaymentRefLineItemsDetailed($payment_reference_id)
    {
        //load css
        $dataArray['local_css'] = array(
            'datatable'
        );
        //load js
        $dataArray['local_js'] = array(
            'datatable');

        $lineItemsData = $this->commissionModel->getPaymentReferenceLineItems($payment_reference_id);
        $paymentReferenceData = $this->commissionModel->getPaymentReferenceData($payment_reference_id);
        $paymentReferenceOrdersData = $this->commissionModel->getPaymentReferenceOrdersData($payment_reference_id);
        $dataArray['data'] = $paymentReferenceData;
        $dataArray['line_items_data'] = $lineItemsData;
        $dataArray['line_items_order_data'] = $paymentReferenceOrdersData;
        
        ini_set('memory_limit', '-1');

        header("Content-Type: application/xls");
        header("Content-Disposition: attachment; filename={$paymentReferenceData['payment_reference']}_detailed.xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        $this->load->setTemplate('blank');

        $csv = $this->load->view('commission/lineItemsDetailedCSV', $dataArray, false);

    }

    public function getPaymentReferenceData()
    {
        $paginparam = $_GET;

        $total = $this->commissionModel->getPaymentReferenceCount($paginparam);
        $paymentReferenceData = $this->commissionModel->getAllPaymentReferences($paginparam);
        $dataArray = array();
                                            
        foreach ($paymentReferenceData as $idx => $val)
        {
            $paymentReferenceData[$idx]['delete'] = "<a title='Delete' href='" . base_url() . "admin/commission/paymentReferenceDelete/" . $val['id'] . "' onClick=\"javascript:return confirm('Are you sure you want to delete?');\"'><i class='fa fa-times'></i></a>";
            $paymentReferenceData[$idx]['summary_report'] = "<a title='View Line Items' href='" . base_url() . "admin/commission/viewPaymentRefLineItems/" . $val['id'] . "'><i class='fa fa-eye'></i></a> | "
                                            . "<a title='Download Line Items CSV' href='" . base_url() . "admin/commission/downloadPaymentRefLineItems/" . $val['id'] . "'><i class='fa fa-file'></i></a> ";
            $paymentReferenceData[$idx]['detailed_report'] = "<a title='View Line Items Detailed Report' href='" . base_url() . "admin/commission/viewPaymentRefLineItemsDetailed/" . $val['id'] . "'><i class='fa fa-eye'></i></a> | "
                                            . "<a title='Download Line Items Detailed CSV' href='" . base_url() . "admin/commission/downloadPaymentRefLineItemsDetailed/" . $val['id'] . "'><i class='fa fa-file'></i></a> ";
        }
                                            
        $dataArray['iTotalRecords'] = $total;
        $dataArray['iTotalDisplayRecords'] = $total;
        $dataArray['sEcho'] = $paginparam['sEcho'];
        $dataArray['aData'] = $paymentReferenceData;

        echo json_encode($dataArray);
    }

    public function addBox($id = null)
    {
        $dataArray = array();
        if (empty($id))
            $id = $this->input->post('id');

        //post value 
        if (!empty($_POST))
        {
            $dataArray = $this->input->post(NULL, TRUE);
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'required|trim|unique[boxes.name.id.' . $this->input->post('id') . ']');
        $this->form_validation->set_rules('short_name', 'Short Name', 'required|trim|unique[boxes.short_name.id.' . $this->input->post('id') . ']');

        $this->load->model('admin/mastersModel');

        if ($this->form_validation->run() == FALSE)
        {
            $dataArray['form_caption'] = "Add Box";

            if (!empty($id))
            {
                $boxRecord = $this->mastersModel->getBoxById($id);
                $dataArray['id'] = $id;
                $dataArray['name'] = $boxRecord->name;
                $dataArray['short_name'] = $boxRecord->short_name;
                $dataArray['description'] = $boxRecord->description;
                $dataArray['volume'] = $boxRecord->volume;
                $dataArray['order_id'] = $boxRecord->order_id;
                $dataArray['form_caption'] = "Edit Box";
            }
            $this->load->view('masters/boxForm', $dataArray);
        }
        else
        {
            $id = $this->input->post('id');
            $dataValues = array(
                'name' => $this->input->post('name'),
                'short_name' => $this->input->post('short_name'),
                'description' => $this->input->post('description'),
                'volume' => $this->input->post('volume'),
                'order_id' => $this->input->post('order_id')
            );
            $this->session->set_flashdata('boxOperationMessage', 'Added Successfully.');
            if (!empty($id))
            {
                $dataValues['id'] = $id;
                $this->session->set_flashdata('boxOperationMessage', 'Updated successfully.');
            }
                $id = $this->mastersModel->saveBox($dataValues);
            redirect('admin/masters/boxList');
        }
    }

    public function paymentReferenceDelete($id)
    {
        $this->load->model('commissionModel');
        $this->commissionModel->deletePaymentReference($id);
        $this->commissionModel->deletePaymentReferenceLineItems($id);
        $this->commissionModel->deletePaymentReferenceOrders($id);
        $this->session->set_flashdata('paymentReferenceOperationMessage', 'Payment reference deleted successfully.');
        redirect('admin/commission/paymentReferenceList');
    }

    public function driverCommission()
    {
        //load css
        $dataArray['local_css'] = array(
            'datatable', 'bootstrap_date_picker'
        );
        //load js
        $dataArray['local_js'] = array(
            'datatable', 'bootstrap_date_picker'
        );
     
        $date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');
        $driver_id = $this->input->post('driver_id');
        $payment_reference = $this->input->post('payment_reference');
        
        if (!empty($date_from) && !empty($date_to) && !empty($driver_id) && !empty($payment_reference))
        {
            list($day, $month, $year) = explode('/', $date_from);
            $date = "$day/$month/$year";
            $formatted_date_from = "$year-$month-$day";
            
            list($day, $month, $year) = explode('/', $date_to);
            $date = "$day/$month/$year";
            $formatted_date_to = "$year-$month-$day";
            
            $payment_reference_rec = $this->commissionModel->checkPaymentReference($payment_reference);
                                            
            if (empty($payment_reference_rec))
            {    
                $dataArray['formatted_date_from'] = $formatted_date_from;
                $dataArray['formatted_date_to'] = $formatted_date_to;

                $driver_commision_rec = $this->commissionModel->checkDriverIsPaidForDateRange($driver_id, $formatted_date_from, $formatted_date_to);
                if (empty($driver_commision_rec))
                {
                    $data = array(
                        'date_from' => $formatted_date_from,
                        'date_to' => $formatted_date_to,
                        'employee_id' => $driver_id,
                    );

                    $records = $this->commissionlib->getDriverCommission($data);
                    $dataArray['data'] = $records;
                    
                    if (empty($records))
                    {
                        $dataArray['message'] = "No records found.";
                    }
                }
                else
                {
                    $dataArray['message'] = "Driver is already paid in <a href='".base_url() . "admin/commission/viewPaymentRefLineItems/" . $driver_commision_rec['id']. "'>{$driver_commision_rec['payment_reference']}</a> for range (<b>{$driver_commision_rec['date_from']} - {$driver_commision_rec['date_to']}</b>).";
                }
            }
            else
            {
                $dataArray['message'] = "Entered Payment Reference <a href='".base_url() . "admin/commission/viewPaymentRefLineItems/" . $payment_reference_rec['id']. "'>{$payment_reference_rec['payment_reference']}</a> already exists.</a>.";
            }
        }
        else
        {
            $dataArray['message'] = "Please enter parameters to generate listing.";
        }
        
        $dataArray['date_from'] = $date_from;
        $dataArray['date_to'] = $date_to;
        $dataArray['driver_id'] = $driver_id;
        $dataArray['payment_reference'] = $payment_reference;
        
        $dataArray['drivers'] = getEmployeesByRole('driver');
        $dataArray['redelivery_amount'] = $this->config->item('redelivery_amount');
        
        $this->load->view('commission/driverCommission', $dataArray);
    }

    public function saveCommission()
    {
        $date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');
        $employee_id = $this->input->post('employee_id');
        $payment_reference = $this->input->post('payment_reference');
        $grand_total_commission_amount = $this->input->post('grand_total_commission_amount');
        $total_boxes = $this->input->post('total_boxes');
        $commission_base_amount = $this->input->post('commission_base_amount');
        $commission_records_data = $this->input->post('data');
        $line_item_orders_mapping = $this->input->post('line_item_orders_mapping');
        $commission_records_data = unserialize(base64_decode($commission_records_data));
                                            
        //remove comma from string
        $grand_total_commission_amount = str_replace( ',', '', $grand_total_commission_amount );
        
        if (!empty($commission_records_data) && !empty($date_from) && !empty($date_to) && !empty($employee_id) && !empty($payment_reference) && ($grand_total_commission_amount>=0))
        {
            $data = array(
                'date_from' => $date_from,
                'date_to' => $date_to,
                'employee_id' => $employee_id,
                'payment_reference' => $payment_reference,
                'grand_total' => $grand_total_commission_amount,
                'total_boxes' => $total_boxes,
                'created_by' => $this->_user_id,
                'created_at' => date('Y-m-d H:i:s'),
            );
            $commision_master_id = $this->commissionModel->saveCommissionMaster($data);
            
            if (!empty($commision_master_id))
            {
                $base_line_item = $this->input->post('base_line_item');
                $base_line_item_operation_count = $this->input->post('base_line_item_operation_count');
                $base_line_item_operation = $this->input->post('base_line_item_operation');
                $base_line_item_commission = $this->input->post('base_line_item_commission');
                $base_line_item_id = $this->input->post('base_line_item_id');
                $base_line_base_commission = $this->input->post('base_line_base_commission');
                                            
                $line_item = $this->input->post('line_item');
                $line_item_amount = $this->input->post('line_item_amount');
                
                $mapping_array = array();
                
                if (!empty($base_line_item))
                {
                    foreach ($base_line_item as $index => $base_line_item_name)
                    {
                        $data = array(
                            'commission_master_id' => $commision_master_id,
                            'line_item' => $base_line_item_name,
                            'item_id' => $base_line_item_id[$index],
                            'count' => $base_line_item_operation_count[$index],
                            'amount' => $base_line_item_commission[$index],
                            'operation' => $base_line_item_operation[$index],
                            'base_commission' => $base_line_base_commission[$index],
                            'type' => 'system',
                        );
                        $mapping_array[$index] = $this->commissionModel->saveCommissionLineItem($data);
                    }
                }
                
                if (!empty($line_item))
                {
                    foreach ($line_item as $index => $line_item_name)
                    {
                        $data = array(
                            'commission_master_id' => $commision_master_id,
                            'line_item' => $line_item_name,
                            'amount' => $line_item_amount[$index],
                            'type' => 'custom',
                        );
                        $this->commissionModel->saveCommissionLineItem($data);
                    }
                }
                
                if (!empty($commission_records_data))
                {  
                    foreach ($commission_records_data as $box => $type_records)
                    {    
                        list($box, $collection_commission, $delivery_commission) = explode('@@##@@', $box);
                        
                        if (!empty($type_records))
                        {
                            foreach ($type_records as $type => $records)
                            {
                                if (!empty($records))
                                {
                                    foreach ($records as $row)
                                    {   
                                        switch ($type)
                                        {
                                            case 'delivery':
                                                $amount = $delivery_commission * $row['quantity'];
                                                break;
                                            
                                            case 'collection':
                                                $amount = $collection_commission * $row['quantity'];
                                                break;
                                            
                                            case 'redelivery':
                                                $amount = $row['commission_amount'];
                                                break;
                                        }
                                        
                                            
                                        $temp_index = "{$row['order_number']}##$type##{$row['box_ids']}";
                                        $temp_index = $line_item_orders_mapping[$temp_index];
                                        
                                        $data = array(
                                            'commission_master_id' => $commision_master_id,
                                            'commission_line_item_id' => $mapping_array[$temp_index],
                                            'order_id' => $row['order_id'],
                                            'order_number' => $row['order_number'],
                                            'box_id' => $row['box_ids'],
                                            'box' => $box,
                                            'type' => $type,
                                            'quantity' => $row['quantity'],
                                            'amount' => $amount,
                                        );
                                         $this->commissionModel->saveCommissionOrderInfo($data);
                                    }
                                }
                            }
                        }
                    }
                }
            }
            else
            {
                
            }
        }
    }
}
