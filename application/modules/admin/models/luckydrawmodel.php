<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class LuckydrawModel extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getLuckyDrawById($id)
    {
        //change databse
        $luckyDrawDb = LUCKYDRAWDBGROUP;
        $CI = new stdClass();
        $CI->userdb = $this->load->database($luckyDrawDb, TRUE);
        $this->userdb = $CI->userdb;
        
        $result = Null;
        if (!empty($id))
        {
            $this->userdb->select('*');
            $this->userdb->where('id', $id);
            $this->userdb->from('lucky_draw_master');
            $result = $this->userdb->get()->row_array();
        }
        return $result;
    }

    public function getLuckyDrawParticipantCount($shipment_date_from, $shipment_date_to, $excluded_agent_id)
    {
        $count = 0;
        $this->db->select('count(orders.id) as count');
        $this->db->from('shipment_batches');
        $this->db->join('orders','orders.shipment_batch_id = shipment_batches.id','left');
        $this->db->where("load_date Between '" .$shipment_date_from. "' And '".$shipment_date_to ."'");
        
        if (!empty($excluded_agent_id))
        {
            $this->db->where_not_in("agent_id",$excluded_agent_id);
        }
        
        $query = $this->db->get()->row();
        $count = $query->count;
        return $count;
    }
    
    public function getLuckyDrawParticipantData($shipment_date_from, $shipment_date_to,$excluded_agent_id)
    {
        $return =array();
        $this->db->select('orders.order_number,orders.block,orders.unit,orders.building,orders.pin,orders.street,orders.customer_id,
                            customers.mobile,customers.residence_phone,customers.name');
        $this->db->from('shipment_batches');
        $this->db->join('orders','orders.shipment_batch_id = shipment_batches.id','left');
        $this->db->join('customers','orders.customer_id = customers.id','left');
        $this->db->where("load_date Between '" .$shipment_date_from. "' And '".$shipment_date_to ."'");
        $this->db->where("orders.order_number !=", '');
          if (!empty($excluded_agent_id))
        {
            $this->db->where_not_in("agent_id",$excluded_agent_id);
        }
        $query = $this->db->get();
       
        $return = $query->result_array();
        
        return $return;
    }
    
    public function saveLuckyDrawMaster($dataValues)
    {
        //change databse
        $luckyDrawDb = LUCKYDRAWDBGROUP;
        $CI = new stdClass();
        $CI->userdb = $this->load->database($luckyDrawDb, TRUE);
        $this->userdb = $CI->userdb;
        
        $return = null;
        if (count($dataValues) > 0)
        {
            if (array_key_exists('id', $dataValues))
            {
                $this->userdb->where('id', $dataValues['id']);
                $this->userdb->update('lucky_draw_master', $dataValues);

                $return = $dataValues['id'];
            }
            else
            {
                $this->userdb->insert('lucky_draw_master', $dataValues);
                $return = $this->userdb->insert_id();
            }
        }
        return $return;
    }
    
    public function checkOrderAlreadyExist($order_number)
    {
        //change database
        $luckyDrawDb = LUCKYDRAWDBGROUP;
        $CI = new stdClass();
        $CI->userdb = $this->load->database($luckyDrawDb, TRUE);
        $this->userdb = $CI->userdb;
        
        $count = 0;
        $this->userdb->select('count(id) as count');
        $this->userdb->where('order_number',$order_number);
        $query = $this->userdb->get('lucky_draw_participant')->row();
        $count = $query->count;
        return $count;
    }
    
    public function saveLuckyDrawParticipant($dataValues)
    {
        //change database
        $luckyDrawDb = LUCKYDRAWDBGROUP;
        $CI = new stdClass();
        $CI->userdb = $this->load->database($luckyDrawDb, TRUE);
        $this->userdb = $CI->userdb;
        
        $return = null;
        if (count($dataValues) > 0)
        {
            if (array_key_exists('id', $dataValues))
            {
                $this->userdb->where('id', $dataValues['id']);
                $this->userdb->update('lucky_draw_participant', $dataValues);

                $return = $dataValues['id'];
            }
            else
            {
                $this->userdb->insert('lucky_draw_participant', $dataValues);
                $return = $this->userdb->insert_id();
            }
        }
        return $return;
    }
    
    public function deleteLuckyDrawMaster($luckyDrawId)
    {
        //change database
        $luckyDrawDb = LUCKYDRAWDBGROUP;
        $CI = new stdClass();
        $CI->userdb = $this->load->database($luckyDrawDb, TRUE);
        $this->userdb = $CI->userdb;
        
        $this->userdb->where('id', $luckyDrawId);
        $this->userdb->delete('lucky_draw_master');
    }
    
    public function getIsDrawAwardedCount($luckyDrawId=0)
    {
        //change database
        $luckyDrawDb = LUCKYDRAWDBGROUP;
        $CI = new stdClass();
        $CI->userdb = $this->load->database($luckyDrawDb, TRUE);
        $this->userdb = $CI->userdb;
        
        $count = 0;
        $this->userdb->select('count(id) as count');
        $this->userdb->where('is_draw_awarded','no');
        
        if(!empty($luckyDrawId))
        {
            $this->userdb->where("id <> $luckyDrawId");
        }
        
        
        $query = $this->userdb->get('lucky_draw_master')->row();
        $count = $query->count;
        return $count;
    }
    
    public function getLuckyDrawCount()
    {
        //change database
        $luckyDrawDb = LUCKYDRAWDBGROUP;
        $CI = new stdClass();
        $CI->userdb = $this->load->database($luckyDrawDb, TRUE);
        $this->userdb = $CI->userdb;
        
        $count = '0';
        $this->userdb->select('count("id") as count');
        $query = $this->userdb->get('lucky_draw_master')->row();
        $count = $query->count;
        return $count;
    }
    
    public function getAllLuckyDraw($pagingParams = array())
    {
        //change database
        $luckyDrawDb = LUCKYDRAWDBGROUP;
        $CI = new stdClass();
        $CI->userdb = $this->load->database($luckyDrawDb, TRUE);
        $this->userdb = $CI->userdb;
        
        $this->userdb->select('lucky_draw_master.id, lucky_draw_master.name, created_by, created_at,excluded_agent_id,is_draw_awarded,no_of_prizes,'
                .'GROUP_CONCAT(winner_order_number SEPARATOR "<br/>") AS winner_order_number, '
                . 'date_from, date_to', false);        
        $this->userdb->from('lucky_draw_master');
        $this->userdb->join('lucky_draw_winners','lucky_draw_master.id = lucky_draw_winners.lucky_draw_master_id','left');
        $this->userdb->group_by('lucky_draw_master.id');
        if (isset($pagingParams['sSearch']))
        {
            $search = $pagingParams['sSearch'];
            if (!empty($search))
            {
                $this->userdb->like('winner_order_number', $search);               
            }
        }
        if (!empty($pagingParams['iSortCol_0']))
        {
            if (empty($pagingParams['sSortDir_0']))
            {
                $pagingParams['sSortDir_0'] = 'desc';
            }
            $column_arr = array('id','created_at','winner_order_number');
            switch ($pagingParams['sSortDir_0'])
            {
                default:
                    $this->userdb->order_by($column_arr[$pagingParams['iSortCol_0'] + 1], $pagingParams['sSortDir_0']);
                    break;
            }
        }
        else
        {
            $this->userdb->order_by('created_at');
        }
        if (isset($pagingParams['iDisplayStart']) && $pagingParams['iDisplayLength'] != '-1')
        {
            $start = $pagingParams['iDisplayStart'];
            $offset = $pagingParams['iDisplayLength'];
            $this->userdb->limit($offset, $start);
        }
        $query = $this->userdb->get();
        $result = $query->result_array();
        return $result;
    }
    
    public function getLuckyDrawParticipantCountById($lucky_draw_id)
    {
        //change database
        $luckyDrawDb = LUCKYDRAWDBGROUP;
        $CI = new stdClass();
        $CI->userdb = $this->load->database($luckyDrawDb, TRUE);
        $this->userdb = $CI->userdb;
        
        $count = '0';
        $this->userdb->select('count("id") as count');
        $this->userdb->where('lucky_draw_id',$lucky_draw_id);
        $query = $this->userdb->get('lucky_draw_participant')->row();
        $count = $query->count;
        return $count;
    }
    
    public function getAllLuckyDrawParticipantById($pagingParams = array(),$lucky_draw_id)
    {
            //change database
        $luckyDrawDb = LUCKYDRAWDBGROUP;
        $CI = new stdClass();
        $CI->userdb = $this->load->database($luckyDrawDb, TRUE);
        $this->userdb = $CI->userdb;
        
        $this->userdb->select('*');
        $this->userdb->from('lucky_draw_participant');

        if (isset($pagingParams['sSearch']))
        {
            $search = $pagingParams['sSearch'];
            if (!empty($search))
            {
                $this->userdb->like('order_number', $search);               
                $this->userdb->or_like('customer_name', $search);               
            }
        }
        if (!empty($pagingParams['iSortCol_0']))
        {
            if (empty($pagingParams['sSortDir_0']))
            {
                $pagingParams['sSortDir_0'] = 'desc';
            }
            $column_arr = array('id','order_number','customer_name');
            switch ($pagingParams['sSortDir_0'])
            {
                default:
                    $this->userdb->order_by($column_arr[$pagingParams['iSortCol_0'] + 1], $pagingParams['sSortDir_0']);
                    break;
            }
        }
        else
        {
            $this->userdb->order_by('order_number');
        }
        if (isset($pagingParams['iDisplayStart']) && $pagingParams['iDisplayLength'] != '-1')
        {
            $start = $pagingParams['iDisplayStart'];
            $offset = $pagingParams['iDisplayLength'];
            $this->userdb->limit($offset, $start);
        }
        $this->userdb->where('lucky_draw_id',$lucky_draw_id);
        $query = $this->userdb->get();
        $result = $query->result_array();
//        echo $this->userdb->last_query();exit;
        return $result;
    } 
    
    public function deleteLuckyDrawWinner($luckyDrawId)
    {
        //change database
        $luckyDrawDb = LUCKYDRAWDBGROUP;
        $CI = new stdClass();
        $CI->userdb = $this->load->database($luckyDrawDb, TRUE);
        $this->userdb = $CI->userdb;
        
        $this->userdb->where('lucky_draw_master_id', $luckyDrawId);
        $this->userdb->delete('lucky_draw_winners');
    }
    
    public function deleteLuckyDrawParticipants($luckyDrawId)
    {
        //change database
        $luckyDrawDb = LUCKYDRAWDBGROUP;
        $CI = new stdClass();
        $CI->userdb = $this->load->database($luckyDrawDb, TRUE);
        $this->userdb = $CI->userdb;
        
        $this->userdb->where('lucky_draw_id', $luckyDrawId);
        $this->userdb->delete('lucky_draw_participant');
    }
    
    /**
     * Get Luckydraw winner count
     * 
     * @param type $luckyDrawId
     */
    public function getLuckyDrawWinnerCount($luckyDrawId)
    {
        //change database
        $luckyDrawDb = LUCKYDRAWDBGROUP;
        $CI = new stdClass();
        $CI->userdb = $this->load->database($luckyDrawDb, TRUE);
        $this->userdb = $CI->userdb;
        
        $count = '0';
        $this->userdb->select('count("id") as count');
        $this->userdb->where('lucky_draw_master_id', $luckyDrawId);
        $query = $this->userdb->get('lucky_draw_winners')->row();
        $count = $query->count;
        return $count;
    }
}