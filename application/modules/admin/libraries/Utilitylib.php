<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Utilitylib
{

    private $_CI;
    private $_order_id = null;
    private $_customer_id = null;
    
    private $_grand_total = 0;
    private $_nett_total = 0;
    private $_discount_total = 0;
    private $_deposit_total = 0;
    
    private $_order_status_id = null;

    public function __construct()
    {
        $this->_CI = & get_instance();
        $this->_CI->load->config('migration_config');
    }

    public function importCustomerData()
    {
        $this->_CI->load->model('utilitymodel');
        $complete_data = $this->_CI->utilitymodel->getAllCustomers(array('is_migrated' => 'no'));

        if (!empty($complete_data))
        {
            $this->_CI->load->model('admin/ordersmodel');
            
            foreach ($complete_data as $outer_index => $outer_row)
            {
                $this->_grand_total = $this->_nett_total = $this->_discount_total = $this->_deposit_total = 0.0;
                $this->_order_status_id = null;

//                if ($outer_row['do'] == 35861 || $outer_row['do'] == 87294)
                {
                $order_data = $this->_CI->utilitymodel->getAllCustomers(array('do' => $outer_row['do'], 'is_migrated' => 'no'));
                
                foreach ($order_data as $index => $row)
                {
                    if ($index == 0)
                    {
                        echo "Processing record for {$row['sender']} <br/>";
                        
                        $customer_exists_data = array(
                            'name' => $row['sender'],
                            'mobile' => $row['contact_1_1'],
                            'pin' => $row['postal_code'],
                        );
                        $customer_row = $this->_CI->utilitymodel->getRow('customers', $customer_exists_data);
                        
                        if (empty($customer_row))
                        {
                            $coords = $this->_CI->ordersmodel->getAddressByPinCode($row['postal_code']);
//                            $google_coords = getLatLongByPinCode($row['postal_code']);

                            $data = array(
                                'is_migrated' => 'yes',
                                'migrated_id' => $row['id'],


                                'name' => $row['sender'],
                                'email' => '',
                                'mobile' => $row['contact_1_1'],
                                'residence_phone' => $row['contact_2'],
                                'pin' => $row['postal_code'],
                                'unit' => $row['unit'],
                                'block' => $row['block'],
                                'building' => $row['building'],
                                'street' => $row['street'],

                                'lattitude' => empty($coords->lattitude) ? '' : $coords->lattitude,
                                'longitude' => empty($coords->longitude) ? '' : $coords->longitude,

//                                'google_lat' => empty($google_coords['lattitude']) ? '' : $google_coords['lattitude'],
//                                'google_lon' => empty($google_coords['longitude']) ? '' : $google_coords['longitude'],

                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s'),
                            );

                            $this->_customer_id = $this->_CI->ordersmodel->saveCustomer($data);
                        }
                        else
                        {
                            $this->_customer_id = $customer_row['id'];
                        }
                        
                        $this->_CI->utilitymodel->saveCustomer(array('id' => $row['id'], 'is_migrated' => 'yes'));

                        $row['customer_id'] = $this->_customer_id;
                        $row['google_lat'] = $data['google_lat'];
                        $row['google_lon'] = $data['google_lon'];
                        $row['lattitude'] = $data['lattitude'];
                        $row['longitude'] = $data['longitude'];

                        
                    }
                    
                    $this->_saveOrdersInfo($row, $index);
                }
               
                $grand_total_data = array(
                    'grand_total' => $this->_grand_total,
                    'nett_total' => $this->_nett_total,
                    'discount' => $this->_discount_total,
                    'id' => $this->_order_id,
                    'discount_type' => $this->_discount_total > 0 ? 'migration' : null 
                );
                $this->_CI->ordersmodel->saveOrder($grand_total_data);
               
                $order_status_data = array(
                    'cash_collected' => $this->_deposit_total,
                    'id' => $this->_order_status_id,
                );
                $this->_CI->ordersmodel->saveOrderStatus($order_status_data);
               
                $customer_mig_data = array(
                    'is_migrated' => 'yes',
                    'id' => $row['id'],
                );
                $this->_CI->utilitymodel->saveCustomer($customer_mig_data);
                
                echo "Processing done for {$row['sender']} <br/>";
                
                
//                p($grand_total_data);
                }
            }
        }
    }
    
    private function _saveOrdersInfo($row, $index)
    {
        if ($index == 0)
        {
            $this->_order_id = $this->_saveCoreOrder($row);
        }
        
        $migration_order_cso_id = $this->_CI->config->item('migration_order_cso_id');
        $driver_config = $this->_CI->config->item('migration_driver_code_mapping');
        $migration_default_driver_id = $this->_CI->config->item('migration_default_driver_id');
        
        $driver_code = trim($row['d2']);
        
        if(empty($driver_code) || empty($driver_config[$driver_code]))
        {
            $driver_id = $migration_default_driver_id;
        }
        else
        {
            $driver_id = $driver_config[$driver_code]['driver_id'];
        }
        
        if ($index == 0)
        {
            $this->_saveOrderStatus($row, $migration_order_cso_id, 'order_booked', 'no', 'no');
            $this->_saveOrderStatus($row, $driver_id, 'booking_attended_by_driver', 'no', 'no');
            $this->_saveOrderStatus($row, $driver_id, 'box_delivered', 'yes', 'yes');
        }
        
        $code = trim($row['box']);
        $code_row = $this->_CI->ordersmodel->getRow('codes', array('code' => $code));
        
        if (!empty($code_row))
        {
            $this->_saveOrderCodeTrans($row, $code_row);
            $this->_saveOrderTrans($row, $code_row);
        }
    }
    
    private function _saveOrderTrans($row, $code_row)
    {
        $code_id = $code_row['id'];
        $location_id = $code_row['location_id'];
        
        $datetime = date('Y-m-d H:i:s');
        
        $this->_CI->load->model('admin/mastersModel');
        $details = $this->_CI->mastersModel->getCodeBoxesDetails($code_id);
        
        if (!empty($details))
        {
            $data = array(
                'code_id' => $code_id,
                'order_id' => $this->_order_id,
                'location_id' => $location_id,
            );
            
            $kabupaten = trim($row['kabupaten']);
            $kabupaten_row = $this->_CI->ordersmodel->getRow('kabupatens', array('name' => $kabupaten, 'location_id' => $location_id));

            foreach ($details as $index => $detail_row)
            {
                $grand_total = $this->_sanitizeAmount($row['price_1']);
                $discount = $this->_sanitizeAmount($row['discount']);
                $deposit = $this->_sanitizeAmount($row['deposit']);
                $nett_total = $grand_total - $discount;

                $this->_grand_total += $grand_total;
                $this->_discount_total += $discount;
                $this->_nett_total += $nett_total;
                $this->_deposit_total += $deposit;

                $data['box_id'] = $detail_row['box_id'];
                $data['kabupaten_id'] = $kabupaten_row['id'];
                $data['price_per_unit'] = $detail_row['price'];
                $data['quantity'] = $row['quantity'];
                $data['total_price'] = $row['quantity'] * $detail_row['price'];
                
                $this->_CI->ordersmodel->saveOrderDetails($data);
            }
        }
    }
    
    private function _saveOrderCodeTrans($row, $code_row)
    {
        $datetime = date('Y-m-d H:i:s');
        
        $code_id = $code_row['id'];
        $location_id = $code_row['location_id'];
        $kabupaten = trim($row['kabupaten']);
        
        $kabupaten_row = $this->_CI->ordersmodel->getRow('kabupatens', array('name' => $kabupaten, 'location_id' => $location_id));
        
        $data = array(
            'location_id' => $location_id,
            'kabupaten_id' => empty($kabupaten_row['id']) ? null : $kabupaten_row['id'],
            'code_id' => $code_id,
            'order_id' => $this->_order_id,
        );
        
        $this->_CI->ordersmodel->saveOrderCodeDetails($data);
    }
    
    private function _saveOrderStatus($row, $employee_id, $status, $active, $responsibility_completed)
    {
        $datetime = date('Y-m-d H:i:s');
        
        if ($status == 'box_delivered')
        {
            $cash_collected = $this->_sanitizeAmount($row['deposit']);
        }
        else
        {
            $cash_collected = 0.0;
        }
        
        $data = array(
            'order_id' => $this->_order_id,
            'status' => $status,
            'employee_id' => $employee_id,
            'comments' => '',
            'cash_collected' => $cash_collected,
            'voucher_cash' => 0.00,
            'active' => $active,
            'responsibility_completed' => $responsibility_completed,
            'reassigned_stage' => 'no',
            'status_escalation_type' => 'migration',
            'coordinates_type' => 'normal',
            'created_at' => $datetime,
            'updated_at' => $datetime,
        );
        $order_status_id = $this->_CI->ordersmodel->saveOrderStatus($data);
        
        if ($status == 'box_delivered')
        {
            $this->_order_status_id = $order_status_id;
        }
    }
    
    private function _saveCoreOrder($row)
    {
        $datetime = date('Y-m-d H:i:s');
        
        $order_date = $row['order_date'].' 00:00:01';
        
        if (empty($row['delivery_date']))
        {
            $delivery_date = '';
        }
        else
        {
            $delivery_ts = strtotime($row['delivery_date']);
            $delivery_date = date('Y-m-d H:i:s', $delivery_ts);
        }
        
        if (empty($row['collection_date']))
        {
            $collection_date = '';
        }
        else
        {
            $collection_ts = strtotime($row['collection_date']);
            $collection_date = date('Y-m-d H:i:s', $collection_ts);
        }
        
        $migration_agent_id = $this->_CI->config->item('migration_agent_id');
        
        $collection_notes = '';
        
        $order_data = array(
            'order_number' => $row['do'],
            'raw_order_number' => $row['do'],
            'order_date' => $order_date,
            'delivery_date' => $delivery_date,
            'status' => 'active',
            'pin' => $row['postal_code'],
            'customer_id' => $row['customer_id'],
            'grand_total' => 0.0,
            'agent_id' => $migration_agent_id,
            'discount' => 0.0,
            'nett_total' => 0.0,
            'discount_type' => null,
            'block' => $row['block'],
            'street' => $row['street'],
            'unit' => $row['unit'],
            'building' => $row['building'],
            'longitude' => $row['longitude'],
            'lattitude' => $row['lattitude'],
            'google_lat' => $row['google_lat'],
            'google_lon' => $row['google_lon'],
            'comments' => $row['comments'],
            'collection_notes' => $collection_notes,
            'recipient_item_list' => $row['uraian'],
            'recipient_address' => $row['consignee_address'],
            'recipient_name' => $row['consignee'],
            'recipient_mobile' => $row['contact_1'],
            'kiv_status' => 'no',
            'updated_by' => null,
            'status_updated_by' => null,
            'batch_id' => null,
            'shipment_batch_id' => null,
            'weight' => null,
            'printed_instruments' => null,
            'migration_id' => $row['id'],
            'created_at' => $datetime,
            'updated_at' => $datetime,
        );
        
        if (!empty($collection_date))
        {
            $row['collection_date'] = $collection_date; 
        }
        
        $order_id = $this->_CI->ordersmodel->saveOrder($order_data);
        
        return $order_id;
    }
    
    private function _sanitizeAmount($amount)
    {
        $search_array = array('$', '-', '(', ')');
        $replace_array = array('', '', '', '');
        
        $amount = str_replace($search_array, $replace_array, $amount);
        
        return $amount;
    }
    
    public function massUpdateGoogleLatlong()
    {
        $this->_CI->load->model('utilitymodel');
        $complete_data = $this->_CI->utilitymodel->getAllNativeCustomers();
        
        if (!empty($complete_data))
        {
            $this->_CI->load->model('admin/ordersmodel');

            foreach ($complete_data as $index => $row)
            {
                $google_coords = getLatLongByPinCode($row['pin']);
                
                $row['google_lat'] = empty($google_coords['lattitude']) ? '' : $google_coords['lattitude'];
                $row['google_lon'] = empty($google_coords['longitude']) ? '' : $google_coords['longitude'];
                
                $order_where_data = array(
                    'customer_id' => $row['id']
                );
                
                $order_data = array(
                    'google_lat' => $row['google_lat'],
                    'google_lon' => $row['google_lon'],
                );
                
                $this->_CI->ordersmodel->saveCustomer($row);
                $this->_CI->ordersmodel->saveOrder($order_data, $order_where_data);
            }
        }
    }
}
