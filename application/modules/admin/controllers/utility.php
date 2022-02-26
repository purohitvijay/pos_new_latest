<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Utility extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('utilitylib');
    }

    public function importCustomerData_x()
    {
        $this->utilitylib->importCustomerData();
    }

    public function importCustomersAndOrdersData()
    {
        $this->utilitylib->importCustomerData();
    }

    public function massUpdateGoogleLatlong()
    {
        $this->utilitylib->massUpdateGoogleLatlong();
    }

    public function updateMassOrderNoImageAndQRCode($order_id = null, $order_number = null)
    {
        ini_set('max_execution_time', 0);
        set_time_limit(0);
        if (empty($order_id) && empty($order_number))
        {
            $this->load->model('admin/ordersmodel');
            $orders = $this->ordersmodel->getAllOrders();

            if (!empty($orders))
            {
                foreach ($orders as $index => $row)
                {
                    if ($row['id'] > 2194 && $row['id'])
                    {
                        echo "Processing for {$row['id']}, {$row['order_number']} <br/>";
                        generateOrderNoImage($row['id'], $row['order_number']);
                        generateBarCode($row['id'], $row['order_number']);
                    }
                }
            }
        }
        else
        {
            generateOrderNoImage($order_id, $order_number);
            generateBarCode($order_id, $order_number);
            $this->session->set_flashdata('orderOperationMessage', "Whoa! Images regenerated successfully for <b>$order_number</b>.");
            redirect("admin/order/batchPrint?haveSideBar=0");
        }
    }

    public function databaseBackup()
    {
        $this->load->dbutil();

        $prefs = array(
            'format' => 'zip',
            'filename' => 'my_db_backup.sql'
        );

        $backup = & $this->dbutil->backup($prefs);

        $db_name = 'backup-on-' . date("Y-m-d-H-i-s") . '.zip';
        $save = 'assets/db_backup_files/' . $db_name;

        $this->load->helper('file');
        write_file($save, $backup);
    }

}
