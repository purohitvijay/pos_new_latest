<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MenuModel extends MY_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getAllMenu($pagingParams = array()) {

        $this->db->select('m.id,menuName,permissionName,permissionId,orderId');
        $this->db->from('menu as m');
        $this->db->join('permission as p', 'm.permissionId = p.id', 'left');

        if (isset($pagingParams['sSearch'])) {
            $search = $pagingParams['sSearch'];
            if (!empty($search)) {
                $this->db->like('menuName', $search);
            }
        }
        if (!empty($pagingParams['iSortCol_0'])) {
            if (empty($pagingParams['sSortDir_0'])) {
                $pagingParams['sSortDir_0'] = 'desc';
            }
            $column_arr = array('id', 'menuName', 'permissionName');
            switch ($pagingParams['sSortDir_0']) {
                default:
                    $this->db->order_by($column_arr[$pagingParams['iSortCol_0'] + 1], $pagingParams['sSortDir_0']);
                    break;
            }
        } else {
            $this->db->order_by('orderId');
        }
        if (isset($pagingParams['iDisplayStart']) && $pagingParams['iDisplayLength'] != '-1') {
            $start = $pagingParams['iDisplayStart'];
            $offset = $pagingParams['iDisplayLength'];
            $this->db->limit($offset, $start);
        }
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function getMenuCount() {
        $count = '0';
        $this->db->select('count("id") as count');
        $query = $this->db->get('menu')->row();
        $count = $query->count;
        return $count;
    }

    public function getMenuById($menuId) {
        $result = Null;
        if (!empty($menuId)) {
            $this->db->select('*');
            $this->db->where('id', $menuId);
            $this->db->from('menu');
            $result = $this->db->get()->row();
        }
        return $result;
    }

    public function saveMenu($dataValues) {
        $return = null;
        if (count($dataValues) > 0) {
            if (array_key_exists('id', $dataValues)) {
                $this->db->where('id', $dataValues['id']);
                $this->db->update('menu', $dataValues);

                $return = $dataValues['id'];
            } else {
                $this->db->insert('menu', $dataValues);
                $return = $this->db->insert_id();
            }
        }
        return $return;
    }
    
     public function deleteMenuById($menuId)
        {  
            $this->db->delete('menu', array('id' => $menuId));
        }
        
    public function getAllMenuArray()
    {  
        $result = array();
        $this->db->select('id,menuName');
        $query = $this->db->get('menu');
        $result = $query->result_array();
        return $result;
    }

}

?>