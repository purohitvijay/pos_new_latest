<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Users extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
    }

    public function index()
    {
        $message = $this->session->flashdata('message');
        $error = $this->session->flashdata('error');
        $dataArray['message'] = $message;
        $dataArray['error'] = $error;
      
        //load js
        $dataArray['local_js'] = array(
            'sparklines');
        $geo_type = $this->session->userdata('geo_type');
        if ($geo_type == 'singapore' || $geo_type == 'all')
        {
            $dataArray['statuses'] = $this->config->item('statuses');
            $this->load->library('adminlib');
            $date = date('Y-m-d');

            $where = array(
                'delivery_date_from' => $date,
                'delivery_date_to' => $date,
                'collection_date_from' => $date,
                'collection_date_to' => $date,
                'cnt_criteria' => 'on'
            );
            $dataArray['status_count'] = $this->adminlib->getOrderStatusesByDate($where);
            
            
            $jakarta_statuses = $this->config->item('jakarta_statuses');
            $where = array(
                'receiving_batch_created_date' => $date,
                'received_date' => $date,
                'cnt_criteria' => 'on'
            );
            $dataArray['statuses'] = array_merge($dataArray['statuses'], $jakarta_statuses);
        }
        else
        {
            $dataArray['statuses'] = $this->config->item('jakarta_statuses');
            $this->load->library('adminlib');
            $date = date('Y-m-d');

            $where = array(
                'delivery_date_from' => $date,
                'delivery_date_to' => $date,
                'collection_date_from' => $date,
                'collection_date_to' => $date,
                'cnt_criteria' => 'on'
            );
            $dataArray['status_count'] = $this->adminlib->getOrderStatusesByDate($where);
        }
        $this->load->view('/dashboard', $dataArray);
    }

    public function login()
    {
        $this->load->library('form_validation');
        $this->load->view('/login', array(), false, 'login');
    }

    public function logout()
    {
//            $siteIdentity = $this->session->userdata['siteIdentity'];
        $this->load->library('Simplelogin');
        $this->load->helper('url');
        $this->simplelogin->logout();
//            if($siteIdentity == "admin")
        {
            redirect('admin/users/login', 'refresh');
        }
//            else
//            {
//                redirect($siteIdentity.'/admin/users/login', 'refresh');
//            }
    }

    public function validate()
    {
        //Load
        $this->load->helper('url');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
//
        $this->load->library('Simplelogin');

        if ($this->simplelogin->login($this->input->post('username'), $this->input->post('password')))
        {
            redirect('/admin/users/index');
        }
        else
        {

            $dataArray['login_error_message'] = "Invalid Username and Password";
            redirect('/admin/users/login');
        }
    }

}
