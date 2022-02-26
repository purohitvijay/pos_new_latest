<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Miscellaneous extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('url');
        $this->load->library('commonlibrary');
        ini_set("memory_limit", -1);
        ini_set("max_execution_time", 0);
    }

    public function postalCodeList()            
    {
        $message = $this->session->flashdata('miscellaneousOperationMessage');
        $dataArray['message'] = $message;
        //load css
        $dataArray['local_css'] = array(
            'datatable'
        );
        //load js
        $dataArray['local_js'] = array(
            'datatable');

        $this->load->view('miscellaneous/postalCodeList', $dataArray);
    }

    public function getPostalCodeData()
    {
        $this->load->model('admin/miscellaneousModel');
        $paginparam = $_GET;

        $total = $this->miscellaneousModel->getPostalCodeCount();
        $postalCodeData = $this->miscellaneousModel->getAllPostalCode($paginparam);
        $dataArray = array();

        foreach ($postalCodeData as $idx => $val)
        {
            $postalCodeData[$idx]['edit'] = "<a href='" . base_url() . "admin/miscellaneous/addPostalCode/" . $val['id'] . "'><i class='fa fa-edit'></i></a>";
        }

        $dataArray['iTotalRecords'] = $total;
        $dataArray['iTotalDisplayRecords'] = $total;
        $dataArray['sEcho'] = $paginparam['sEcho'];
        $dataArray['aData'] = $postalCodeData;

        echo json_encode($dataArray);
    }

    public function addPostalCode($id = null)
    {
        $dataArray = array();
        if (empty($id))
            $id = $this->input->post('id');

        //post value 
        if (!empty($_POST))
        {
            $dataArray = $this->input->post(NULL, TRUE);
        }

        $dataArray['local_css'] = array(
            'jquery.tokeninput',
            'jquery.tokeninput.facebook'
        );
        //load js
        $dataArray['local_js'] = array(
            'jquery.tokeninput',
        );
        $this->load->library('form_validation');
        $this->form_validation->set_rules('postal_code', 'PostalCode', 'required|trim|unique[postalcodes.postalcode.id.' . $this->input->post('id') . ']');

        $this->load->model('admin/miscellaneousModel');

        if ($this->form_validation->run() == FALSE)
        {
            $dataArray['form_caption'] = "Add Postal Code";

            if (!empty($id))
            {
                $postalCodeRecord = $this->miscellaneousModel->getPostalCodeById($id);
                $dataArray['id'] = $id;
                $dataArray['postal_code'] = $postalCodeRecord->postalcode;
                $dataArray['building'] = $postalCodeRecord->building;
                $dataArray['block'] = $postalCodeRecord->block;
                $dataArray['street'] = $postalCodeRecord->street;
                $dataArray['building_type'] = $postalCodeRecord->building_type;
                $dataArray['longitude'] = $postalCodeRecord->longitude;
                $dataArray['latitude'] = $postalCodeRecord->lattitude;
                $dataArray['form_caption'] = "Edit Postal Code";
            }
            $this->load->view('miscellaneous/postalCodeForm', $dataArray);
        }
        else
        {
            $id = $this->input->post('id');
            $dataValues = array(
                'postalcode' => $this->input->post('postal_code'),
                'building' => $this->input->post('building'),
                'block' => $this->input->post('block'),
                'street' => $this->input->post('street'),
                'building_type' => $this->input->post('building_type'),
                'longitude' => $this->input->post('longitude'),
                'lattitude' => $this->input->post('latitude')
            );
            $this->session->set_flashdata('miscellaneousOperationMessage', 'Added Successfully.');
            if (!empty($id))
            {
                $dataValues['id'] = $id;
                $this->session->set_flashdata('miscellaneousOperationMessage', 'Updated successfully.');
            }
            $id = $this->miscellaneousModel->savePostalCode($dataValues);
            redirect('admin/miscellaneous/postalCodeList');
        }
    }

    public function getLatLong()
    {
        $postal_code = $_GET['postalcode'];
        $data = getLatLongByPinCode($postal_code);
        echo json_encode($data);
    }

    public function getBuildingType()
    {
        $searchTerm = $_GET['q'];
        $this->load->model('miscellaneousModel');
        $building_type_arr = $this->miscellaneousModel->getbuildingType($searchTerm);
        if (!empty($building_type_arr))
        {
            $row = array();
            $data = array();
            foreach ($building_type_arr as $idx => $val)
            {
                $row['name'] = $val['building_type'];
                $row['id'] = $val['building_type'];
                $data[] = $row;
            }
            $building_type_arr = $data;
        }
        echo json_encode($building_type_arr);
    }
    public function cleanImage()
    {     
        //load css
        $dataArray['local_css'] = array(
            'datatable', 'multiselect', 'multiselect_filter','bootstrap','bootstrap.min','bootstrap-responsive.min'
        );

        $dataArray['local_js'] = array(
            'datatable','bootstrap','bootstrap.min.js', 'bootbox','bootstrap_date_picker','multiselect','multiselect_filter', 'jquery-ui-1.11.2'
        );

        $this->load->view('miscellaneous/cleanImagePage', $dataArray);
    }

    public function cleanImageData()
    { 
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');

        ini_set("memory_limit", -1);
        ini_set("max_execution_time", 0);

        $currentDateTime = date("Y-m-d h:i:s");
        $days = $this->config->item('get_oldest_image_data_in_days');
        $this->load->model('admin/ordersModel');
        $data = $this->ordersModel->getOrderImagesMasterid($days, $currentDateTime);

        $image_path = $this->config->item('image_upload');
        $assets_archive_folder_path = $image_path['upload_dir'];
        $assets_extract_zip_folder_path = $image_path['extraction_dir'];
        foreach($data as $idx => $val)
        {
            $id = $val['id'];

            //deletes zip file from the assets folder
            $zipFilePath = $assets_archive_folder_path."/$id.zip";
            if(file_exists($zipFilePath))
            { 
                unlink($zipFilePath);
            }

            //deletes extract zip files from the extract folder 
            $extractFilePath = $assets_extract_zip_folder_path."/".$id;

            $files = glob($extractFilePath . '/*');
            if($files)
            {
              foreach($files as $file)
              {
                  if(is_file($file))
                  {
                        unlink($file);
                    }
                }
            }

            if(is_dir($extractFilePath)) 
            {
                rmdir($extractFilePath);
            }

            $this->ordersModel->deleteFromMasterTable($id);
            $this->ordersModel->deleteFromTransTable($id);
            $message = "Successfully Delete zipfile and it's folder: " .$val['original_archive'];
            $result_data = array('message' => $message , 'progress' => $id);

            echo "id: $id" . PHP_EOL;
            echo "data: " . json_encode($result_data) . PHP_EOL;
            echo PHP_EOL;

            ob_flush();
            flush();
        }
    }
    public function customerCleanup()
    {
        //load css
        $dataArray['local_css'] = array(
            'datatable', 'multiselect', 'multiselect_filter','bootstrap','bootstrap.min','bootstrap-responsive.min'
        );

        $dataArray['local_js'] = array(
            'datatable','bootstrap','bootstrap.min.js', 'bootbox','bootstrap_date_picker','multiselect','multiselect_filter', 'jquery-ui-1.11.2'
        );

        $this->load->model('admin/ordersModel');
        $duplicate_customer_data = $this->ordersModel->getDuplicateCustomers();

        $dataArray['duplicate_customer_data'] = $duplicate_customer_data;

        $this->load->view('miscellaneous/customerCleanupPage', $dataArray);
    }

    public function deleteDuplicateCustomers()
    {  
        $this->load->model('admin/ordersModel');
        $duplicate_customer_data = $this->ordersModel->getDuplicateCustomers();

        foreach($duplicate_customer_data as $idx => $val)
        {
             $arr[$val['id']]= explode('@#',$val['duplicate_customer_id']);
        }

        foreach($arr as $records => $result)
        {     
            $new_customer_id = $result[0];
            $old_customer_id = array_slice($result, 1);

            foreach($old_customer_id as $id => $old_customer_val)
            {  
                $old_customer_id = $old_customer_val;
                $getCustomerName[]  = $this->ordersModel->getCustomerNameById($old_customer_id);
                $dataArray['customerName'] = $getCustomerName;
                $this->ordersModel->updateCustomerId($new_customer_id, $old_customer_id);
                $this->ordersModel->deleteOldCustomers($old_customer_id);
            }
        }

        $this->load->view('miscellaneous/oldCustomersNames',$dataArray);
    }

    public function cleanData()
    { 
        //load css
        $dataArray['local_css'] = array(
            'datatable', 'multiselect', 'multiselect_filter','bootstrap','bootstrap.min','bootstrap-responsive.min'
        );

        $dataArray['local_js'] = array(
            'datatable','bootstrap','bootstrap.min.js', 'bootbox','bootstrap_date_picker','multiselect','multiselect_filter', 'jquery-ui-1.11.2'
        );

        $this->load->model('admin/ordersModel');
        $get_date_wise_orders = $this->config->item('get_orders_older_data_by_date');
        $old_orders_status = $this->config->item('get_old_orders_active_status');
        $currentDateTime = date("Y-m-d h:i:s");
        $get_old_orders = $this->ordersModel->getOldOrders($currentDateTime, $get_date_wise_orders, $old_orders_status);
        $dataArray['orders_data'] = $get_old_orders;
        $this->load->view('miscellaneous/oldOrdersViewPage', $dataArray);
    }

    public function dbBackupGenerate()
    {    
        $user = $this->config->item('database_user');
        $password = $this->config->item('database_password');
        $host = $this->config->item('database_host');
        $db_name = $this->config->item('database_name');
        $sql_zip_file_backup_path = $this->config->item('sql_zip_file_backup_path');
        $sql_file_name = $db_name."_".date('Y_m_d_H_i_s'); 
        $file_name_with_path = $sql_zip_file_backup_path.$sql_file_name;

        $command = "mysqldump   --skip-lock-tables  --single-transaction  --quick  --user=$user --password=$password --host=$host $db_name  | gzip > $file_name_with_path.sql.gz";
        exec($command, $output = 1);
        if($output == 1)
        {
            echo "success";
        }
    }

    public function oldOrdersData()
    { 
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');

        $this->load->model('admin/ordersModel');
        $get_date_wise_orders = $this->config->item('get_orders_older_data_by_date');
        $currentDateTime = date("Y-m-d h:i:s");
        $old_orders_status = $this->config->item('get_old_orders_active_status');
        $get_old_orders = $this->ordersModel->getOldOrders($currentDateTime, $get_date_wise_orders, $old_orders_status);

        if($get_old_orders)
        {
            $this->db->trans_start();
            foreach($get_old_orders as $idx => $result)
            { 
                $order_id = $result['order_id'];
                $this->ordersModel->deleteRecordFromOrderTable($order_id);
                $this->ordersModel->deleteRecordFromOrderTransTable($order_id);
                $this->ordersModel->deleteRecordFromOrderStatusTransTable($order_id);

                $message = "Successfully Delete Order Number: " .$result['order_number'];
                $result_data = array('message' => $message , 'progress' => $order_id , 'order_number' => $result['order_number']);
                echo "id: $order_id" . PHP_EOL;
                echo "data: " . json_encode($result_data) . PHP_EOL;
                echo PHP_EOL;

                ob_flush();
                flush();
            }

            $this->db->trans_complete(); # Completing transaction
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                return FALSE;
            } 
            else 
            {
                $this->db->trans_commit();
                return TRUE;
            }
        }
    }
    public function deleteBarCodeOrderNoImage()
    {
        $this->load->model('admin/ordersModel');
        $get_date_wise_orders = $this->config->item('get_orders_older_data_by_date');
        $old_orders_status = $this->config->item('get_old_orders_active_status');
        $check_shipment_receiving_data_in_days = $this->config->item('check_shipment_receiving_data_in_days');
        $currentDateTime = date("Y-m-d h:i:s");
        $get_old_orders = $this->ordersModel->getOldOrders($currentDateTime, $get_date_wise_orders, $old_orders_status);

        $bar_code_order_nos_img_path = $this->config->item('bar_code_order_no_img_path');
        $bar_code_img_path = $bar_code_order_nos_img_path['bar_codes'];
        $order_nos_img_path = $bar_code_order_nos_img_path['order_nos'];

        if($get_old_orders)
        {
            foreach($get_old_orders as $ids => $val)
            { 
                $order_id = $val['order_id'];

                //Delete Image of Bar code and Order Nos.
                if(is_file($bar_code_img_path."/$order_id.png"))
                {
                    unlink($bar_code_img_path."/$order_id.png");
                }

                if(is_file($order_nos_img_path."/$order_id.png"))
                {
                    unlink($order_nos_img_path."/$order_id.png");
                }

                //Delete Shipment And Receiving Batch
                $shipment_id = isset($val['shipment_batch_id']) ? $val['shipment_batch_id'] : "";
                $receiving_batch_id = isset($val['receiving_batch_id']) ? $val['receiving_batch_id'] : "";

                $shipment_created_days = $val['shipment_created_days'];
                $receiving_created_days = $val['receiving_batch_created_days'];
                if($shipment_created_days > $check_shipment_receiving_data_in_days)
                { 
                    $this->ordersModel->deleteShipmentBatch($shipment_id);
                    $this->ordersModel->deleteShipmentBatchBoxMapping($shipment_id);
                }

                if($receiving_created_days > $check_shipment_receiving_data_in_days)
                {
                    $this->ordersModel->deleteReceivingBatches($receiving_batch_id);
                }
            }
        }
    }

    public function promotion() 
    {
        $message = $this->session->flashdata('promotionOperationMessage');
        $dataArray['message'] = $message;

        //load css
        $dataArray['local_css'] = array(
            'datatable', 'multiselect', 'multiselect_filter', 'bootstrap', 'bootstrap.min', 'bootstrap-responsive.min'
        );
        
        //load js
        $dataArray['local_js'] = array(
            'datatable', 'bootstrap', 'bootstrap.min.js', 'bootbox', 'bootstrap_date_picker', 'multiselect', 'multiselect_filter', 'jquery-ui-1.11.2'
        );
        $this->load->view('miscellaneous/promotion', $dataArray);
    }

    public function addPromotion($id = null) 
    {
        $message = $this->session->flashdata('promotionOperationMessage');
        $dataArray['message'] = $message;
        
        if (empty($id))
            $id = $this->input->post('id');

        if (!empty($id)) {
            $this->load->model('admin/miscellaneousModel');
            $where = array('promotion.id' => $id);
            $promotionData = $this->miscellaneousModel->getAllPromotionData($pagingParams = array(), $where);

            $date_from = $promotionData['date_from'];
            list($year, $month, $day) = explode('-', $date_from);
            $promotionData['date_from'] = "$day/$month/$year";

            $date_to = $promotionData['date_to'];
            list($year, $month, $day) = explode('-', $date_to);
            $promotionData['date_to'] = "$day/$month/$year";

            $dataArray['form_caption'] = "Edit Promotion";
            $dataArray['promotionData'] = $promotionData;
        } else {
            $dataArray['form_caption'] = "Add Promotion";
        }
        //load css
        $dataArray['local_css'] = array(
            'datatable', 'multiselect', 'multiselect_filter', 'bootstrap', 'bootstrap.min', 'bootstrap-responsive.min', 'multiselect', 'multiselect_filter'
        );

        $dataArray['local_js'] = array(
            'datatable', 'bootstrap', 'bootstrap.min.js', 'bootbox', 'bootstrap_date_picker', 'multiselect', 'multiselect_filter', 'jquery-ui-1.11.2', 'multiselect', 'multiselect_filter'
        );

        $this->load->model('mastersModel');
        $dataArray['boxes'] = $this->mastersModel->getAllBoxes();
        $this->load->view('miscellaneous/promotionForm', $dataArray);
    }

    public function savePromotion($id = null) 
    {
        $this->load->model('miscellaneousModel');

        $dataArray = array();
        if (empty($id))
            $id = $this->input->post('id');
 
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('promo_name', 'promo_name', 'required|trim|unique[promotion.name.id.' . $this->input->post('id') . ']');
         
        $this->form_validation->set_rules('date_from', 'date_from', 'required');
        $this->form_validation->set_rules('date_to', 'date_to', 'required');
        $this->form_validation->set_rules('box_ids', 'box_ids', 'required');
        $this->form_validation->set_rules('amount', 'amount', 'required');
        $this->form_validation->set_rules('multiple_usage', 'multiple_usage', 'required');
        $this->form_validation->set_rules('max_capping', 'max_capping', 'required');
           
        if ($this->form_validation->run() == FALSE)
        {    
            $this->session->set_flashdata('promotionOperationMessage', 'Promo Name,Date From,Date to,Boxes,Amount,Multiple Usage,Map Capping Field is required.');
            redirect('admin/miscellaneous/addPromotion');
        } 
        else
        {
            $this->load->model('admin/mastersModel');

            $promo_name = $this->input->post('promo_name');

            $date_from = $this->input->post('date_from');
            list($day, $month, $year) = explode('/', $date_from);
            $date_from = "$year-$month-$day";

            $date_to = $this->input->post('date_to');
            list($day, $month, $year) = explode('/', $date_to);
            $date_to = "$year-$month-$day";
  
            $box_ids = $this->input->post('box_ids');
            $number_input = $this->input->post('amount');
            $multiple_usage = $this->input->post('multiple_usage');
            $max_capping = $this->input->post('max_capping');
            $promotion_trans_id = $this->input->post('promotion_trans_id');

            if (!isset($promotion_trans_id)) {
                $promotion_trans_id = "0";
            }


            $current_date = date('Y-m-d');

            if ($date_to < $current_date) {
                $is_active = "no";
            } else {
                $is_active = "yes";
            }
            
                
            if (!empty($id)) 
            {
                $dataValues = array(
                    'id' => $id,
                    'name' => $promo_name,
                    'date_from' => $date_from,
                    'date_to' => $date_to,
                    'amount' => $number_input,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'multiple_usage' => $multiple_usage,
                    'is_active' => $is_active,
                    'quantity_count' => $max_capping
                );

                $old_max_capping = $this->input->post('old_max_capping');
                if ($old_max_capping != $max_capping) { 
                    $usage_left = $this->input->post('usage_left');
                    $diff_max_cpping_data = $max_capping - $old_max_capping;

                    if ($diff_max_cpping_data > 0) {
                        $update_usage_left = $usage_left + $diff_max_cpping_data;
                    } else {
                        $update_usage_left = $usage_left - abs($diff_max_cpping_data);
                        if ($update_usage_left > 0) {
                            $update_usage_left = $update_usage_left;
                        } else {
                            $update_usage_left = '0';
                        }
                    }
                    $dataValues['usage_left'] = $update_usage_left;
                }

                $update_boxes_data = "no";
                $check_boxes_change = $this->miscellaneousModel->getPromotionBoxesDataByPromoId($id);

                if ($check_boxes_change) {
                    foreach ($check_boxes_change as $idx => $box_ids_data) {
                        $arr[] =$box_ids_data['box_id'];  
                    }
                }

                $check_update_case = array_diff($box_ids,$arr);
                if($check_update_case)
                {
                    $update_boxes_data = "yes";
                }  
                
                //save data to the promotion table
                $promotionId = $this->miscellaneousModel->savePromotion($dataValues);

                //delete promo trans data from promo_tans_table
                if ($update_boxes_data == "yes") {
                    $this->miscellaneousModel->deletePromotionBoxesDataByPromoId($id);
                    //save data to promotion_box_trans table
                    foreach ($box_ids as $idx => $box_id) {
                        $data = array('promotion_id' => $promotionId,
                            'box_id' => $box_id
                        );

                        $this->miscellaneousModel->savePromotionBoxTrans($data);
                    }
                }
            } 
            else 
            {    
                $dataValues = array(
                    'name' => $promo_name,
                    'date_from' => $date_from,
                    'date_to' => $date_to,
                    'amount' => $number_input,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_by' => $this->_user_id,
                    'multiple_usage' => $multiple_usage,
                    'is_active' => $is_active,
                    'quantity_count' => $max_capping,
                    'usage_left' => $max_capping
                );

                //save data to the promotion table
                $promotionId = $this->miscellaneousModel->savePromotion($dataValues);


                //save data to promotion_box_trans table
                foreach ($box_ids as $idx => $box_id) {
                    $data = array('promotion_id' => $promotionId,
                        'box_id' => $box_id
                    );

                    $this->miscellaneousModel->savePromotionBoxTrans($data);
                }
            }

            $this->session->set_flashdata('promotionOperationMessage', 'Added Successfully.');

            if (!empty($id)) {
                $this->session->set_flashdata('promotionOperationMessage', 'Updated successfully.');
            }
            redirect('admin/miscellaneous/promotion');
        }
    }

    public function getPromotionData() 
    {
        $this->load->model('admin/miscellaneousModel');
        $paginparam = $_GET;
 
        $boxData = $this->miscellaneousModel->getAllPromotionData($paginparam);

        $total = count($boxData);
        if ($boxData) {
            foreach ($boxData as $idx => $val) {
                list($year, $month, $day) = explode('-', $val['date_from']);
                $val['date_from'] = "$day/$month/$year";

                list($year, $month, $day) = explode('-', $val['date_to']);
                $val['date_to'] = "$day/$month/$year";

                $boxData[$idx]['name'] = $val['name'];
                $boxData[$idx]['date_from'] = $val['date_from'];
                $boxData[$idx]['date_to'] = $val['date_to'];
                $boxData[$idx]['amount'] = $val['amount'];
                $boxData[$idx]['boxes'] = str_replace(',', "<br>", $val['boxes_name']);
            }

            $dataArray = array();

            foreach ($boxData as $idx => $val) {
                $boxData[$idx]['delete'] = "<a href='" . base_url() . "admin/miscellaneous/promotionDelete/" . $val['id'] . "' onClick=\"javascript:return confirm('Are you sure you want to delete?');\"'><i class='fa fa-times'></i></a>";
                $boxData[$idx]['edit'] = "<a href='" . base_url() . "admin/miscellaneous/addpromotion/" . $val['id'] . "'><i class='fa fa-edit'></i></a>";
            }
        }

        $dataArray['iTotalRecords'] = $total;
        $dataArray['iTotalDisplayRecords'] = $total;
        $dataArray['sEcho'] = $paginparam['sEcho'];
        $dataArray['aData'] = $boxData;

        echo json_encode($dataArray);
    }

    public function promotionDelete($id) 
    {
        $this->load->model('miscellaneousModel');
        $this->miscellaneousModel->deletePromotionById($id);
        $this->session->set_flashdata('promotionOperationMessage', 'Deleted successfully.');
        redirect('admin/miscellaneous/promotion');
    }

}
