<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class SubMenuModel extends MY_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getAllSubMenu($pagingParams = array()) {

        $this->db->select('s.id,menuId,subMenuName,permissionName,menuName,s.orderId');
        $this->db->from('subMenu as s');
        $this->db->join('menu as m','s.menuId = m.id','left');
        $this->db->join('permission as p', 's.permissionId = p.id', 'left');

        if (isset($pagingParams['sSearch'])) {
            $search = $pagingParams['sSearch'];
            if (!empty($search)) {
                $this->db->like('subMenuName', $search);
            }
        }
        if (!empty($pagingParams['iSortCol_0'])) {
            if (empty($pagingParams['sSortDir_0'])) {
                $pagingParams['sSortDir_0'] = 'desc';
            }
            $column_arr = array('id', 'subMenuName', 'permissionName','MenuName','orderId');
            switch ($pagingParams['sSortDir_0']) {
                default:
                    $this->db->order_by($column_arr[$pagingParams['iSortCol_0'] + 1], $pagingParams['sSortDir_0']);
                    break;
            }
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

    public function getSubMenuCount() {
        $count = '0';
        $this->db->select('count("id") as count');
        $query = $this->db->get('subMenu')->row();
        $count = $query->count;
        return $count;
    }

    public function getSubMenuById($subMenuId) {
        $result = Null;
        if (!empty($subMenuId)) {
            $this->db->select('*');
            $this->db->where('id', $subMenuId);
            $this->db->from('subMenu');
            $result = $this->db->get()->row();
        }
        return $result;
    }

    public function saveSubMenu($dataValues) {
        $return = null;
        if (count($dataValues) > 0) {
            if (array_key_exists('id', $dataValues)) {
                $this->db->where('id', $dataValues['id']);
                $this->db->update('subMenu', $dataValues);

                $return = $dataValues['id'];
            } else {
                $this->db->insert('subMenu', $dataValues);
                $return = $this->db->insert_id();
            }
        }
        return $return;
    }
    
     public function deleteSubMenuById($subMenuId)
        {  
            $this->db->delete('subMenu', array('id' => $subMenuId));
        }
}

?>