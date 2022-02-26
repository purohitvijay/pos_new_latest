<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class RestapiModel extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function validateLogin($username, $password)
    {
        $return = array();
        $this->db->select('user.*, role.RoleName as role');
        $this->db->from('user');
        $this->db->join('role', 'role.id = user.roleId');
        $this->db->where('username', $username);
        $this->db->where('password', md5($password));
        $query = $this->db->get();
        //p($this->db->last_query());
        $return = $query->row_array();


        return $return;
    }
    public function getAllDistributionCenters($dist_center_id=null)
    {
        $this->db->select("*,distribution_centers.`name` AS dc_center_name,GROUP_CONCAT(locations.`name` SEPARATOR '@@##@@') as locations_name,locations.id as location_id");
        $this->db->from('distribution_centers');
        $this->db->join('dc_location_trans','dc_location_trans.distribution_center_id = distribution_centers.id','left');
        $this->db->join('locations','locations.id = dc_location_trans.location_id','left');
        if(!empty($dist_center_id))
        {
            $this->db->where('distribution_center_id',$dist_center_id);
        }
        $this->db->group_by('distribution_centers.id,distribution_centers.name');
        $this->db->order_by('distribution_centers.id');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }
    public function getAllOrdersReceived_jkt($center_id)
    {
        $this->db->select('dc_location_trans.`distribution_center_id`,order_trans.`location_id` AS orders_location_id,orders.`order_number`,order_status_trans.status, order_status_trans.active, order_status_trans.order_id, orders.recipient_name, 
                           orders.recipient_address,orders.recipient_mobile,
                           orders.recipient_item_list,order_trans.box_id,order_trans.quantity,
                           customers.id as customer_id,customers.name as customer_name,customers.mobile as customer_mobile,customers.residence_phone as customer_phone,
                           customers.pin as customer_pin ,customers.unit as customer_unit,customers.block as customer_block,customers.building as customer_building');
        $this->db->from('order_status_trans');
        $this->db->join('orders','orders.id = order_status_trans.order_id','left');
        $this->db->join('order_trans','order_trans.order_id = order_status_trans.order_id','left');
        $this->db->join('customers','customers.id = orders.customer_id','left');
        $this->db->join('dc_location_trans','dc_location_trans.location_id = order_trans.location_id','left');
        $this->db->where('order_status_trans.status','received_at_jakarta_warehouse');
        $this->db->where('order_status_trans.active','yes');
        $this->db->where('dc_location_trans.distribution_center_id',$center_id);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }
    
    public function saveImageToDb_jkt($files,$order_data,$saveZipToDb)
    { 
        foreach($files as $idx => $res)
        {
          $time = date('Y-m-d H:i:s');
          $arr = array(
              'name'=>$files[$idx],
              'type'=>'jakarta',
              'status'=>'available',
              'updated_by'=>'2',
              'updated_at'=>$time,
              'order_image_master_id'=>$saveZipToDb
          );
          $arr = array_merge($arr,$order_data);
          $this->db->insert('order_image_trans',$arr);
          $return = $this->db->insert_id();
        }
        //return $return;
    }
    
    public function saveZipToDb_jkt($data)
    {
        $time = date('Y-m-d H:i:s');
        $dataValues = array(
              'original_archive'=>$data['image'],
              'renamed_archive'=>$data['image'],
              'updated_by'=>'2',
              'created_at'=>$time,
              'updated_at'=>$time
          );
        $this->db->insert('order_image_master',$dataValues);
        $return = $this->db->insert_id();
        return $return;
    }
    public function updateRenameZipfile_jkt($saveZipToDb)
    {
        $update_zipName = array(
          'renamed_archive' => "$saveZipToDb.zip"  
        );
        $this->db->where('id', $saveZipToDb);
        $this->db->update('order_image_master',$update_zipName);
    }
    public function getOrderId($order_id)
    { 
        $result = Null;
        if (!empty($order_id))
        {
            $this->db->select('id');
            $this->db->where('orders.order_number', $order_id);
            $this->db->from('orders');
            $result = $this->db->get()->row_array();
        }
        return $result;
    }
    public function checkCenterLocations($center_id)
    {
        $this->db->select('locations.id,locations.name,dc_location_trans.distribution_center_id');
        $this->db->from('locations');
        $this->db->join('dc_location_trans','dc_location_trans.location_id = locations.id','left');
        $this->db->where('distribution_center_id',$center_id);
        $query = $this->db->get();
        $result = $query->row_array();
        return $result;
    }
    public function getOrderNumber($order_id)
    {
        $this->db->select('*');
        $this->db->from('orders');
        $this->db->where('orders.id',$order_id);
        $query = $this->db->get();
        $result = $query->row_array();
        return $result;
    }
    public function getLocations($order_id)
    {
        $this->db->select('*');
        $this->db->from('order_trans');
        $this->db->join('locations','order_trans.location_id = locations.id','left');
        $this->db->where('order_trans.order_id',$order_id);
        $query = $this->db->get();
        $result = $query->row_array();
        return $result;
        
    }
    public function CheckLocationsExists($order_id,$dist_center_id,$order_location_id)
    {
        $this->db->select('*');
        $this->db->from('order_trans');
        $this->db->join('dc_location_trans','dc_location_trans.location_id = order_trans.location_id','left');
        $this->db->where('order_trans.location_id',$order_location_id);
        $this->db->where('order_trans.order_id',$order_id);
        $this->db->where('distribution_center_id',$dist_center_id);
        $query = $this->db->get();
        $result = $query->row_array();
        return $result;
    }
    public function save_sms_triggered($result)
    {
        $this->db->insert('sms_trigger',$result['data']);
        $return = $this->db->insert_id();
        return $return;
    }
    public function getEmployeeRole($employee_id)
    {
        $this->db->select('role.RoleName');
        $this->db->from('user');
        $this->db->join('role','role.id = user.roleId','left');
        $this->db->where('user.id', $employee_id);
        $this->db->limit(1);
        $query = $this->db->get();
        $result = $query->row_array();
        return $result;
    }
}
