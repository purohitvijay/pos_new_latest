<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Imglog extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        
        $languages = array("en" => "english", "id" => "bahasa");

        $lang = $this->uri->segment(2);
        
        if (isset($languages[$lang]))
            $this->lang->load('site', $languages[$lang]);
        else
            $this->lang->load('site', $this->config->item("language"));
    }
    
    public function login()
    {
        $this->load->library('form_validation');
        $this->load->setTemplate('blank');
        $this->load->view('/imglog');
    }

    public function logout()
    {
        $this->load->library('Simplelogin');
        $this->load->helper('url');
        $this->simplelogin->logout();
        redirect('imglog', 'refresh');
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
        
        if ($this->simplelogin->login($this->input->post('username'), $this->input->post('password'),"imglog_login"))
        {
            redirect('/customer');
        }
        else
        {

            $dataArray['login_error_message'] = "Invalid Username and Password";
            redirect('imglog');
        }
    }
}
