<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class UserModel extends MY_Model {

    public function __construct() {
        parent::__construct();
    }
    
     public function saveUser($dataValues) {
        $return = null;
        if (count($dataValues) > 0) {
            if (array_key_exists('id', $dataValues)) {
                $this->db->where('id', $dataValues['id']);
                $this->db->update('user', $dataValues);

                $return = $dataValues['id'];
            } else {
                $this->db->insert('user', $dataValues);
                $return = $this->db->insert_id();
            }
        }
        return $return;
    }
    
    public function getUserById($userId)
    {
        $return = null;
        $this->db->select('u.email, username, r.roleName, u.geo_type');
        $this->db->from('user as u');
        $this->db->join('role as r','u.roleId = r.id','left');
        $this->db->where('u.id',$userId);
        $return = $this->db->get()->row();
        return $return;
        
    }
    
    public function getUserData($userId,$tbl)
    {
        $return = null;
        $this->db->select('firstName,lastName,contactNo');
        $return = $this->db->get($tbl)->row();
        return $return;
    }
    
    public function getUsersByRole($role, $geo_type = array('singapore', 'all'))
    {
        $return = null;
        $this->db->select('user.*');
        $this->db->from('user');
        $this->db->join('role', 'role.id = user.roleId');
        $this->db->where('RoleName', $role);
        $this->db->where_in('geo_type', $geo_type);
        $return = $this->db->get()->result_array();
        return $return;
    }
}