<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class AclModel extends MY_Model {

        public function __construct() {
            parent::__construct();
        }

        public function getMenu($roleId) {
            $result = array();
            $this->db->select('m.menuName,p.path,m.id');
            $this->db->from('menu as m');
            $this->db->join('permission as p', 'm.permissionId = p.id', 'left');
            $this->db->join('rolePermissionTrans as rpt', 'rpt.permissionId = p.id', 'left');
            $this->db->where('rpt.roleId', $roleId);
            $this->db->order_by('m.orderId');
            $query = $this->db->get();
            $result = $query->result_array();
            return $result;
        }

        public function getSubMenu($roleId) {
            $result = array();
            $this->db->select('s.id,s.subMenuName,p.path,s.menuId');
            $this->db->from('subMenu as s');
            $this->db->join('permission as p', 's.permissionId = p.id', 'left');
            $this->db->join('rolePermissionTrans as rpt', 'rpt.permissionId = p.id', 'left');
            $this->db->where('rpt.roleId', $roleId);
            $this->db->order_by('s.orderId');
            $query = $this->db->get();
            $result = $query->result_array();
            return $result;
        }

        public function getAllRole() {
            $result = array();
            $this->db->select('*');
            $query = $this->db->get('role');
            $result = $query->result_array();
            return $result;
        }

        public function deletePermissionTrans() {
            $this->db->truncate('rolePermissionTrans');
        }

        public function savePermissionTrans($dataValues) {
            $this->db->insert('rolePermissionTrans', $dataValues);
            $return = $this->db->insert_id();
            return $return;
        }

        public function getAllPermissionRoleTrans() {
            $result = array();
            $this->db->select('*');
            $query = $this->db->get('rolePermissionTrans');
            $result = $query->result_array();
            return $result;
        }

        public function checkAlwaysAllowed($path) {
            $result = NULL;
            $this->db->select('*');
            $this->db->where('path', $path);
            $result = $this->db->get('permission')->row();
            return $result;
        }

        public function checkIsAllow($permissionId, $roleId) {
            $this->db->select('id');
            $this->db->where('permissionId', $permissionId);
            $this->db->where('roleId', $roleId);
            $num_row = $this->db->get('rolePermissionTrans')->num_rows();
            return $num_row;
        }
        
        public function checkSiteIdentityExist($siteUrl)
        {
            $numRow = "0";
            $this->db->select('id');
            $this->db->where('siteIdentity',$siteUrl);
            $numRow = $this->db->get('businessMaster')->num_rows();
            return $numRow;
        }
        
        public function getBusinessDataByIdentity($siteIdentity)
        {
            $businessRow= NULL;
            $this->db->select('id');
            $this->db->where('siteIdentity',$siteIdentity);
            $businessRow = $this->db->get('businessMaster')->row();
            return $businessRow;
        }

    }

    