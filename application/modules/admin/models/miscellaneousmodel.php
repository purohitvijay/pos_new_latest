<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MiscellaneousModel extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getAllPostalCode($pagingParams = array())
    {

        $this->db->select('id,postalcode,building,block,street,building_type,longitude,lattitude');
        $this->db->from('postalcodes');

        if (isset($pagingParams['sSearch']))
        {
            $search = $pagingParams['sSearch'];
            if (!empty($search))
            {
                $this->db->like('postalcode', $search);
                $this->db->or_like('building', $search);
                $this->db->or_like('block', $search);
            }
        }
        if (!empty($pagingParams['iSortCol_0']))
        {
            if (empty($pagingParams['sSortDir_0']))
            {
                $pagingParams['sSortDir_0'] = 'desc';
            }
            $column_arr = array('id','postalcode', 'building', 'block','street','building_type');
            switch ($pagingParams['sSortDir_0'])
            {
                default:
                    $this->db->order_by($column_arr[$pagingParams['iSortCol_0'] + 1], $pagingParams['sSortDir_0']);
                    break;
            }
        }
        else
        {
            $this->db->order_by('postalcode');
        }
        if (isset($pagingParams['iDisplayStart']) && $pagingParams['iDisplayLength'] != '-1')
        {
            $start = $pagingParams['iDisplayStart'];
            $offset = $pagingParams['iDisplayLength'];
            $this->db->limit($offset, $start);
        }
        $query = $this->db->get();
//        echo $this->db->last_query();exit;
        $result = $query->result_array();
        return $result;
    }

    public function getPostalCodeCount()
    {
        $count = '0';
        $this->db->select('count("postalcode") as count');
        $query = $this->db->get('postalcodes')->row();
        $count = $query->count;
        return $count;
    }
    
    public function savePostalCode($dataValues)
    {
        $return = null;
        if (count($dataValues) > 0)
        {
            if (array_key_exists('id', $dataValues))
            {
                $this->db->where('id', $dataValues['id']);
                $this->db->update('postalcodes', $dataValues);

                $return = $dataValues['id'];
            }
            else
            {
                $this->db->insert('postalcodes', $dataValues);
                $return = $this->db->insert_id();
            }
        }       
        return $return;
    }

     public function getPostalCodeById($id)
    {
        $result = Null;
        if (!empty($id))
        {
            $this->db->select('*');
            $this->db->where('id', $id);
            $this->db->from('postalcodes');
            $result = $this->db->get()->row();
        }
        return $result;
    }
    
    public function getbuildingType($searchterm)
    {
        $result =array();
        $this->db->distinct();
        $this->db->select('building_type');
        $this->db->like('building_type',$searchterm);
        $query = $this->db->get('postalcodes');
        $result = $query->result_array();

        return $result;
    }
    
    public function savePromotion($dataValues)
    {
        $return = null;
        if (count($dataValues) > 0)
        {
            if (array_key_exists('id', $dataValues))
            {
                $this->db->where('id', $dataValues['id']);
                $this->db->update('promotion', $dataValues);
                $return = $dataValues['id'];
            }
            else
            { 
                $this->db->insert('promotion', $dataValues);
                $return = $this->db->insert_id();
            }
        }       
        return $return;
    }
    
    public function savePromotionBoxTrans($data)
    {
        $return = null;
        if (count($data) > 0)
        { 
                $this->db->insert('promotion_box_trans', $data);
                $return = $this->db->insert_id();
        }       
        return $return;
    }
    
    public function getPromotionCount()
    {
        $count = '0';
        $this->db->select('count("id") as count');
        $query = $this->db->get('promotion')->row();
        $count = $query->count;
        return $count;
    }
     
    public function getAllPromotionData($pagingParams = array(),$where = null)
    {
        $this->db->select("promotion.*,GROUP_CONCAT('',`boxes`.`name` ) AS boxes_name, GROUP_CONCAT('',`boxes`.`id` ) AS boxes_ids, promotion_box_trans.id AS promotion_trans_id");
        $this->db->from('promotion');
        $this->db->join('promotion_box_trans', 'promotion_box_trans.promotion_id = promotion.id','left');
        $this->db->join('boxes', 'boxes.id = promotion_box_trans.box_id','left');
        $this->db->group_by('promotion_box_trans.promotion_id');
        if($where)
        {
            $this->db->where($where);
            $query = $this->db->get(); 
            $result = $query->row_array();
        }
        else
        {
            if (!empty($pagingParams['search_promotion_status']))
            {
                $this->db->where('promotion.is_active', $pagingParams['search_promotion_status']);
            } 
            
            if (isset($pagingParams['sSearch']))
            {
                $search = $pagingParams['sSearch'];
                if (!empty($search))
                {
                    $this->db->like('promotion.name', $search);
                }
            }
            if (!empty($pagingParams['iSortCol_0']))
            {
                if (empty($pagingParams['sSortDir_0']))
                {
                    $pagingParams['sSortDir_0'] = 'desc';
                }
                $column_arr = array('name');
                switch ($pagingParams['sSortDir_0'])
                {
                    default:
                        $this->db->order_by($column_arr[$pagingParams['iSortCol_0'] + 1], $pagingParams['sSortDir_0']);
                        break;
                }
            }
            else
            {
                $this->db->order_by('created_at');
            }
            if (isset($pagingParams['iDisplayStart']) && $pagingParams['iDisplayLength'] != '-1')
            {
                $start = $pagingParams['iDisplayStart'];
                $offset = $pagingParams['iDisplayLength'];
                $this->db->limit($offset, $start);
            }
            $query = $this->db->get(); 
            $result = $query->result_array();
        }
        return $result;
    }
    
    public function deletePromotionById($id)
    {
        $this->db->delete('promotion', array('id' => $id));
        $this->db->delete('promotion_box_trans', array('promotion_id' => $id));
    }
    
    public function getPromotionBoxesDataByPromoId($id)
    {
        $result = Null;
        if (!empty($id))
        {
            $this->db->select('*');
            $this->db->where('promotion_id', $id);
            $this->db->from('promotion_box_trans');
            $query = $this->db->get(); 
            $result = $query->result_array();
        }
        return $result;
    }
    
    public function deletePromotionBoxesDataByPromoId($id)
    { 
       $this->db->delete('promotion_box_trans', array('promotion_id' => $id));
    }
}
