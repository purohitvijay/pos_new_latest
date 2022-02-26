<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    /**
     * Common library function goes here
     */
    class CommonLibrary {

        private $_CI;    // CodeIgniter instance        

        public function __construct() {
            $this->_CI = & get_instance();
        }

        public function getMenu($roleId) {
            $menuData = array();
            $this->_CI->load->model('admin/aclModel');
            $menuArr = $this->_CI->aclModel->getMenu($roleId);
            if (!empty($menuArr)) {
                foreach ($menuArr as $idx => $row) {
                    $menuData[$row['id']]['menuName'] = $row['menuName'];
                    $menuData[$row['id']]['path'] = $row['path'];
                }
            }
            return $menuData;
        }

        public function getSubMenu($roleId) {
            $this->_CI->load->model('admin/aclModel');
            $subMenuArr = $this->_CI->aclModel->getSubMenu($roleId);

            $arr = array();
            if (!empty($subMenuArr)) {
                foreach ($subMenuArr as $idx => $rec) {
                    $arr[$rec['menuId']][$rec['id']] = array(
                        'path' => $rec['path'],
                        'subMenuName' => $rec['subMenuName']
                    );
                }
            }
            return $arr;
        }

        public function getNavMenu($roleId) {
            
            $topmenu = $this->getMenu($roleId);
            $subMenu = $this->getSubMenu($roleId);
            
            $topNav = array();
            if (!empty($topmenu)) {
                foreach ($topmenu as $idx => $rec) {
                    $topNav[$idx]['menuName'] = $rec['menuName'];
                    $topNav[$idx]['path'] = $rec['path'];

                    if (!empty($subMenu[$idx])) {

                        foreach ($subMenu[$idx] as $id => $row) {
                            $topNav[$idx]['subMenu'][$id] = array(
                                'path' =>  $row['path'],
                                'subMenuName' => $row['subMenuName']
                            );
                        }
                    }
                }
            }
            return $topNav;
        }

        public function getPermissionArray() {
            $permissionArray = array(
                '0' => '--Select Permission--'
            );
            $this->_CI->load->model('admin/permissionModel');
            $permissionArr = $this->_CI->permissionModel->getAllPermissionArray();
            if (!empty($permissionArr)) {
                foreach ($permissionArr as $idx => $row) {
                    $permissionArray[$row['id']] = $row['permissionName'];
                }
            }
            return $permissionArray;
        }

        public function getMenuArray() {
            $menuArray = array(
                '0' => '--Select Menu--'
            );
            $this->_CI->load->model('admin/menuModel');
            $menuArr = $this->_CI->menuModel->getAllMenuArray();
            if (!empty($menuArr)) {
                foreach ($menuArr as $idx => $row) {
                    $menuArray[$row['id']] = $row['menuName'];
                }
            }
            return $menuArray;
        }

        public function getAllRole() {
            $this->_CI->load->model('admin/aclModel');
            $roleArr = $this->_CI->aclModel->getAllRole();
            return $roleArr;
        }

        public function getAllPermission() {
            $this->_CI->load->model('admin/permissionModel');
            $permissionArr = $this->_CI->permissionModel->getAllPermissionArray();
            return $permissionArr;
        }

        public function savePermissionTrans($permission) {
            $this->_CI->load->model('admin/aclModel');
            if (!empty($permission)) {
                foreach ($permission as $idx => $rec) {

                    if (!empty($rec)) {
                        foreach ($rec as $id => $row) {
                            $dataValues['permissionId'] = $idx;
                            $dataValues['roleId'] = $id;
                            //save premission Trans
                            $this->_CI->aclModel->savePermissionTrans($dataValues);
                        }
                    }
                }
            }
        }

        public function getAllPermissionRoleTrans() {
            $permission = array();
            $this->_CI->load->model('admin/aclModel');
            $permissionRoleMappingArr = $this->_CI->aclModel->getAllPermissionRoleTrans();
            if (!empty($permissionRoleMappingArr)) {
                foreach ($permissionRoleMappingArr as $idx => $row) {
                    $permission[$row['permissionId']][$row['roleId']] = "checked";
                }
            }

            return $permission;
        }

        public function checkAlwaysAllowPermission($path) {
            $allowedArr = array();
            $arr = array();
            $this->_CI->load->model('admin/aclModel');
            $allowedArr = $this->_CI->aclModel->checkAlwaysAllowed($path);
            if (!empty($allowedArr)) {
                $arr['alwaysAllow'] = $allowedArr->alwaysAllow;
                $arr['permissionId'] = $allowedArr->id;
            }
            return $arr;
        }

        public function checkPermission($permissionId, $roleId) {
            $isAllowed = Null;
            $this->_CI->load->model('admin/aclModel');
            $isAllowed = $this->_CI->aclModel->checkIsAllow($permissionId, $roleId);
            return $isAllowed;
        }

        public function checkSiteIdentityExist($siteUrlIdentity) {
            $this->_CI->load->model('admin/aclModel');
            $siteUrlExist = $this->_CI->aclModel->checkSiteIdentityExist($siteUrlIdentity);
            return $siteUrlExist;
        }
        
        public function saveUser($dataValues)
        {
            $this->_CI->load->model('admin/userModel');
            $userId = $this->_CI->userModel->saveUser($dataValues);
            return $userId;            
        }

    }