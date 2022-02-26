<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class PermissionModel extends MY_Model {

    public function __construct() {
        parent::__construct();
    }
    
    public function getAllPermissionArray()
    {
        $result = array();
        $this->db->select('id,permissionName');
        $query = $this->db->get('permission');
        $result = $query->result_array();
        return $result;
        
    }
    
    public function getAllPermission($pagingParams = array()) {

        $this->db->select('id,permissionName,aliasName,alwaysAllow,path');
        $this->db->from('permission');
        if (isset($pagingParams['sSearch'])) {
            $search = $pagingParams['sSearch'];
            if (!empty($search)) {
                $this->db->like('permissionName', $search);
            }
        }
        if (!empty($pagingParams['iSortCol_0'])) {
            if (empty($pagingParams['sSortDir_0'])) {
                $pagingParams['sSortDir_0'] = 'desc';
            }
            $column_arr = array('id', 'permissionName', 'aliasName','alwaysAllow','path');
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

    public function getPermissionCount() {
        $count = '0';
        $this->db->select('count("id") as count');
        $query = $this->db->get('permission')->row();
        $count = $query->count;
        return $count;
    }
    
    public function getPermissionById($permissionId)
    {
        $result = Null;
        if (!empty($permissionId)) {
            $this->db->select('*');
            $this->db->where('id', $permissionId);
            $this->db->from('permission');
            $result = $this->db->get()->row();
        }
        return $result;
    }

    public function savePermission($dataValues) {
        $return = null;
        if (count($dataValues) > 0) {
            if (array_key_exists('id', $dataValues)) {
                $this->db->where('id', $dataValues['id']);
                $this->db->update('permission', $dataValues);

                $return = $dataValues['id'];
            } else {
                $this->db->insert('permission', $dataValues);
                $return = $this->db->insert_id();
            }
        }
        return $return;
    }
    
     public function deletePermissionById($permissionId)
        {  
            $this->db->delete('permission', array('id' => $permissionId));
        }
}