<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class LuckyDrawlib
{

    private $_CI;
    

    public function __construct()
    {
        $this->_CI = & get_instance();       
    }
    
    public function getLuckyDrawParticipantCount($shipment_date_from, $shipment_date_to, $excluded_agent_id=null)
    {
        $this->_CI->load->model('admin/luckydrawModel');
        //change date format
        list($day, $month, $year) = explode('/', $shipment_date_from);
        $shipment_date_from = "$year-$month-$day";
        //change date format
        list($day, $month, $year) = explode('/', $shipment_date_to);
        $shipment_date_to = "$year-$month-$day";
        
        $orderCount = $this->_CI->luckydrawModel->getLuckyDrawParticipantCount($shipment_date_from, $shipment_date_to, $excluded_agent_id);
        return $orderCount;
    }
    
    public function importDataToLuckyDrawDB($shipment_date_from, $shipment_date_to, $name, $excluded_agent_id=null ,$no_of_prize = null)
    {
        $result = array();
        $this->_CI->load->model('admin/luckydrawModel');
        //change date format
        list($day, $month, $year) = explode('/', $shipment_date_from);
        $shipment_date_from = "$year-$month-$day";
        //change date format
        list($day, $month, $year) = explode('/', $shipment_date_to);
        $shipment_date_to = "$year-$month-$day";
        
        $luckyDrawData = $this->_CI->luckydrawModel->getLuckyDrawParticipantData($shipment_date_from,$shipment_date_to,$excluded_agent_id);
       
        
        if(!empty($luckyDrawData))
        {
            //loggedIn user id
            $userId = $this->_CI->session->userdata['id'];
            //insert into lucky draw master
            $dataVal = array(
                'excluded_agent_id' => implode(",", $excluded_agent_id),
                'no_of_prizes' => $no_of_prize,
                'name' => $name,
                'date_from' => $shipment_date_from,
                'date_to' => $shipment_date_to,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $userId
            );
            
            $luckyDrawId = $this->_CI->luckydrawModel->saveLuckyDrawMaster($dataVal);
            
            $alreadyExistLuckDrawParticipant = 0;  
            $totalParticipant = 0;
            
            foreach($luckyDrawData as $idx => $val)
            {
               
                //check order no already exist in lucky draw participant's
                $order_number = $val['order_number'];
//                $orderAlreadyExist = $this->_CI->luckydrawModel->checkOrderAlreadyExist($order_number);
//                if($orderAlreadyExist > 0)
//                {
//                    $alreadyExistLuckDrawParticipant++;                   
//                }
//                else 
//                {
                    $dataValues = array(
                        'lucky_draw_id' => $luckyDrawId,
                        'order_number' => $val['order_number'],
                        'customer_id' => $val['customer_id'],
                        'customer_name' => $val['name'],
                        'block' => $val['block'],
                        'unit' => $val['unit'],
                        'street' => $val['street'],
                        'building' => $val['building'],
                        'pin' => $val['pin'],
                        'mobile' => $val['mobile'],
                        'residence_phone' => $val['residence_phone']
                    );
                    
                    $luckyDrawParticipant = $this->_CI->luckydrawModel->saveLuckyDrawParticipant($dataValues);
                    $totalParticipant++;
//                }
            }
            
            if($totalParticipant == 0)
            {
                $this->_CI->luckydrawModel->deleteLuckyDrawMaster($luckyDrawId);
            }
            
            $result = array(
                'alreadyExistParticipant' => $alreadyExistLuckDrawParticipant,
                'totalParticipant' => $totalParticipant,
                'luckyDrawId' => $luckyDrawId,
                );
        }
        
        return $result;
    }
}