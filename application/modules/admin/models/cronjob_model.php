<?php
class Cronjob_model extends CI_Model
{
    function __construct()
    { 
      parent::__construct();    
    }

    function GetPromotionData()
    {
       $this->db->select("*");
       $this->db->from('promotion');
       $query = $this->db->get();
       $result = $query->result_array();
       return $result;
    }

    function updatePromotionStatus($dataArray)
    {        
       $this->db->where('id', $dataArray['id']);
       $this->db->update('promotion', $dataArray); 
    }

    function getExpiryPromoOrdersData()
    {
       $this->db->select("*,customers.id AS customer_id, customers.mobile AS customer_mobile,
customers.residence_phone AS customer_residence_phone,customers.pin AS customer_pin");
       $this->db->from("orders");
       $this->db->join("order_trans","order_trans.order_id = orders.id","left"); 
       $this->db->join("customers","customers.id = orders.customer_id","left"); 
       $this->db->where("order_trans.promocode_id !=", ""); 
       $this->db->where("orders.status", "active"); 
       $this->db->where("orders.collection_date !=", ""); 
       $query = $this->db->get();   
       $result = $query->result_array();
       return $result;
    }

    function updatePromoCodeFromOrder($dataArray)
    { 
       $data = array("promocode_id" => "");
       $this->db->where("order_id", $dataArray['order_id']);
       $this->db->update('order_trans', $data); 
    }

    function updateOrderNettTotalDiscount($order_id, $updateOrderData)
    {   
       $this->db->where("id", $order_id);
       $this->db->update('orders', $updateOrderData); 
    }

   public function getAgentDetailsDataById($agent_id)
   {
      $this->db->select("*");
      $this->db->from("agents");
      $this->db->where("agents.id", $agent_id); 
      $query = $this->db->get();  
      $result = $query->row_array();
      return $result;
   }

   public function getOrderTotalBoxQuantity($order_id)
   {
      $this->db->select("sum(quantity) as total_boxes");
      $this->db->from("order_trans");
      $this->db->where("order_trans.order_id", $order_id); 
      $query = $this->db->get();  
      $result = $query->row_array();
      return $result;
   }
        
}
