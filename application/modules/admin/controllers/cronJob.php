<?php
class CronJob extends CI_Controller{

 function __construct()
 {
  parent::__construct();
  //load the model
  $this->load->model('cronjob_model','',TRUE);
 }

 function updatePromotionStatus()
 { 
    $current_date = date('Y-m-d');
    $promotionData  = $this->cronjob_model->GetPromotionData();
    foreach($promotionData as $idx => $val)
    { 
        if($val['date_to'] < $current_date ||  $val['usage_left'] == '0')
        {
            $dataArray =array('id' => $val['id'],'is_active' => 'no');
            $this->cronjob_model->updatePromotionStatus($dataArray);
            echo "Promotion Id: ".$val['id']." Promotion Updated Successfully.<br/>";
        }
    }
 }
 
 //Remove promocode id,from order trans when the order collection date is expired.
 function removePromoCodeFromOrder()
 {
    $this->load->model('ordersModel');
    $getExpiryPromoOrdersData = $this->cronjob_model->getExpiryPromoOrdersData();
    if($getExpiryPromoOrdersData)
    {
        //get promocode id and get order collection date is smaller than promo code date
        foreach($getExpiryPromoOrdersData as $idx => $val)
        {
           $promocode_data =  $this->ordersModel->getPromotionById($val['promocode_id']);
           $promoCodeExpiryDate = $promocode_data['date_to'];
           $orderCollectionDate = $val['collection_date'];
           $orderCollectionDate = date('Y-m-d', strtotime(date($orderCollectionDate)));
           
           if($orderCollectionDate > $promoCodeExpiryDate)
           {  
               //remove promo-code from order
               $order_id  = $val['order_id'];
               $dataArray  = array("promocode_id" => null, "order_id" => $order_id);
               $this->cronjob_model->updatePromoCodeFromOrder($dataArray);
              
               if($val['agent_id'])
               {
                   $get_agent_commisson = $this->cronjob_model->getAgentDetailsDataById($val['agent_id']);
                   $agent_commission    = $get_agent_commisson['commission'];
                   $get_total_box_quantity  = $this->cronjob_model->getOrderTotalBoxQuantity($order_id);
                   if($get_total_box_quantity)
                   {
                        $total_boxes = $get_total_box_quantity['total_boxes'];
                        $agent_total_commission  = $total_boxes * $agent_commission;
                        
                        $grandTotal = $val['grand_total'];
                        $updateNettTotal =  $grandTotal - $agent_total_commission;
                        $updateOrderData =  array("nett_total" => $updateNettTotal, "discount" => $agent_total_commission , "discount_type" => "agent");
                        $this->cronjob_model->updateOrderNettTotalDiscount($order_id ,$updateOrderData);
                   }
               } 
               else 
               {
                   //chk repeated cust. discount 
                   $customer_mobile = $val['customer_mobile'];
                   $customer_residence_phone = $val['customer_residence_phone'];
                   $customer_pin = $val['customer_pin'];
                   $delivery_date = $val['delivery_date'];
                   
                   $is_repeated_customer = "no";
                   if($delivery_date)
                   {
                       $delivery_date = date("d/m/Y", strtotime($delivery_date)); 
                       $checkRepeatedCustomer =  $this->ordersModel->checkDuplicatiorCustomer($customer_mobile, $customer_residence_phone, $customer_pin, $delivery_date);
                       $total_customers = count($checkRepeatedCustomer);
                       
                       if($total_customers > 1)
                       {
                           $is_repeated_customer = "yes";
                       }
                   }
                   
                   if($is_repeated_customer == "yes")
                   {
                          //customer is repeated customer
                           $get_total_box_quantity  = $this->cronjob_model->getOrderTotalBoxQuantity($order_id);
                           if($get_total_box_quantity)
                           {
                                $total_boxes = $get_total_box_quantity['total_boxes'];
                                $repeated_customer_total_commission  = $total_boxes * 10;
 
                                $grandTotal = $val['grand_total'];
                                $updateNettTotal =  $grandTotal - $repeated_customer_total_commission;
                                $updateOrderData =  array("nett_total" => $updateNettTotal, "discount" => $repeated_customer_total_commission , "discount_type" => "repeated_customer");
                               
                                $this->cronjob_model->updateOrderNettTotalDiscount($order_id ,$updateOrderData);
         
                            }
                   }
                   else
                   {
                        //remove discount and update nett_total
                        if($val['discount_type'] == "promocode_discount")
                        { 
                             $grandTotal = $val['grand_total'];
                             $updateOrderData =  array("nett_total" => $grandTotal, "discount" => "0" , "discount_type" => null);
                             $this->cronjob_model->updateOrderNettTotalDiscount($order_id ,$updateOrderData);
                        }
                        
                   }
                   
               }
                 
                echo "Order Id: ".$val['order_id']." Updated Successfully.<br/>";
           } 
            
        }
    }
    
 }
 
}