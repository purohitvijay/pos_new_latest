<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class PermissionSetup extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('commonlibrary');
    }

    public function index() {
        $message = $this->session->flashdata('permissionSetupOperationMessage');
        $dataArray['message'] = $message;
        //get ALl role
        $roleArr = $this->commonlibrary->getAllRole();
        $permissionArr = $this->commonlibrary->getAllPermission();
        //get All permission role mapping Data
        $permissionRoleTransArr = $this->commonlibrary->getAllPermissionRoleTrans();
        $dataArray['roleArr'] = $roleArr;
        $dataArray['permissionArr'] = $permissionArr;
        $dataArray['permissionRoleTrans'] = $permissionRoleTransArr;

        $this->load->view('permissionSetup', $dataArray);
    }

    public function savePermissionAsRole() {
        $this->load->model('aclModel');
        $this->aclModel->deletePermissionTrans();
        $permissionTrans = $this->input->post('permission');
        $this->commonlibrary->savePermissionTrans($permissionTrans);
        $this->session->set_flashdata('permissionSetupOperationMessage', 'Permission Updates successfully.');
        redirect('admin/permissionSetup');
    }

}