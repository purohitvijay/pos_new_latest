<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class API extends REST_Controller {
    
    
    public function __construct() {
        parent::__construct();   
        $encode = $this->input->get('encode');
        if(!empty($encode))
        {
            ob_start('ob_gzhandler');
        }
    }
    
   public function getCategoryListing_get() {
        $this->load->library('Restapilib');
        $data = $this->restapilib->getCategoryListing();
        $this->response($data);
    }
    
     public function getHomeImage_get() {
        $this->load->library('Restapilib');
        $data = $this->restapilib->getHomeImage();
        $this->response($data);
    }
    
    public function getLocationListing_get() {
        $this->load->library('Restapilib');
        $data = $this->restapilib->getLocationListing();
        
        $this->response($data);
    }
    
    public function getEventListing_get() {
        $category_id = $this->input->get('category_id');
        $paging = $this->input->get('paging');
        $pageIndex = $this->input->get('pageIndex');
        $pageSize = $this->input->get('pageSize');
        $this->load->library('Restapilib');
        $data = $this->restapilib->getEventListing($paging,$pageIndex,$pageSize,$category_id);
        $this->response($data);
    }
    
    public function getEventInfoById_get()
    {
        $event_id = $this->input->get('event_id');
        $this->load->library('Restapilib');
        $data = $this->restapilib->getEventInfoById($event_id);
        $this->response($data);
    } 
    
    public function getEventDayListing_get()
    {
        $this->load->library('Restapilib');
        $data = $this->restapilib->getEventDayListing();
        $this->response($data);
    }
    
    public function getAdTypeListing_get()
    {
        $this->load->library('Restapilib');
        $data = $this->restapilib->getAdTypeListing();
        $this->response($data);
    } 
    
    public function getAdListing_get()
    {
        $this->load->library('Restapilib');
        $data = $this->restapilib->getAdListing();
        $this->response($data);
    }
 
        
    public function getEventListingByDateRange_get() {
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        
        $this->load->library('Restapilib');
        $data = $this->restapilib->getEventListingByDateRange($start_date, $end_date);
        $this->response($data);
    }
    
    public function getAdData_get(){
        $this->load->library('Restapilib');
        $data = $this->restapilib->getAdData();
        $this->response($data);
    }
     
    public function getTokenListing_get() {
        $this->load->library('Restapilib');
        $data = $this->restapilib->getTokenList();
        $this->response($data);
    }
    public function  applePushNotification_post()
    {      
      
      $token =$this->input->post('token'); 
     
      $this->load->library('Restapilib');
      $data = $this->restapilib->applePushNotification($token);
      $this->response($data);  
    }
 
    
}