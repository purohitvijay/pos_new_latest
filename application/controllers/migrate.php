<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class migrate extends CI_Controller {
    public function index()
    {
          
        // load migration library
        $this->load->library('migration');
         
        if ( ! $this->migration->current())
        {
            echo 'Error' . $this->migration->error_string();
        } else {
            echo 'Migrations ran successfully!';
        }   
    }    
}