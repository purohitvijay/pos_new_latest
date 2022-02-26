<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Permission extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('commonlibrary');
    }

    public function index() {
         $message = $this->session->flashdata('permissionOperationMessage');
            $dataArray['message'] = $message;
            //load css
            $dataArray['local_css'] = array(
                'datatable'
            );
            //load js
            $dataArray['local_js'] = array(
                'datatable',
                'datatable-bootstrap');
        $this->load->view('permissionList',$dataArray);
    }

    public function getPermissionData() {
        $this->load->model('admin/permissionModel');
        $paginparam = $_GET;
        $total = $this->permissionModel->getpermissionCount();
        $menuData = $this->permissionModel->getAllPermission($paginparam);
        $dataArray = array();

        foreach ($menuData as $idx => $val) {
            $menuData[$idx]['delete'] = "<a href='" . base_url() . "admin/permission/permisssionDelete/" . $val['id'] . "' onClick=\"javascript:return confirm('Are you sure you want to delete?');\"'><i class='icon-trash'></i></a>";
            $menuData[$idx]['edit'] = "<a href='" . base_url() . "admin/permission/addPermission/" . $val['id'] . "'><i class='icon-edit'></i></a>";
        }

        $dataArray['iTotalRecords'] = $total;
        $dataArray['iTotalDisplayRecords'] = $total;
        $dataArray['sEcho'] = $paginparam['sEcho'];
        $dataArray['aData'] = $menuData;
        echo json_encode($dataArray);
    }
    
    public function addPermission($permissionId = null)
        {
         $dataArray = array();
        if (empty($permissionId))
            $permissionId = $this->input->post('permissionId');

        //post value 
        if (!empty($_POST)) {
            $dataArray = $this->input->post(NULL, TRUE);
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('permissionName', 'Permission Name', 'required|trim|unique[permission.permissionName.id.' . $this->input->post('permissionId') . ']');

        $this->load->model('admin/permissionModel');
       
        if ($this->form_validation->run() == FALSE) {
            $dataArray['form_caption'] = "Add Permission";

            if (!empty($permissionId)) {
                $permissionRecord = $this->permissionModel->getPermissionById($permissionId);
                $dataArray['permissionId'] = $permissionId;
                $dataArray['permissionName'] = $permissionRecord->permissionName;
                $dataArray['aliasName'] = $permissionRecord->aliasName;
                $dataArray['path'] = $permissionRecord->path;
                $dataArray['alwaysAllow'] = $permissionRecord->alwaysAllow;                        
                $dataArray['form_caption'] = "Edit Permission";
            }
            $this->load->view('/permissionForm', $dataArray);
        } else {
            $permissionId = $this->input->post('permissionId');
            $dataValues = array(
                'permissionName' => $this->input->post('permissionName'),
                'aliasName' => $this->input->post('aliasName'),
                'path' => $this->input->post('path'),
                'alwaysAllow' => $this->input->post('alwaysAllow')
            );
            $this->session->set_flashdata('permissionOperationMessage', 'Permission Added successfully.');
            if (!empty($permissionId)) {
                $dataValues['id'] = $permissionId;
                $this->session->set_flashdata('permissionOperationMessage', 'Permission Updated successfully.');
            }
            $permissionId = $this->permissionModel->savePermission($dataValues);
            redirect('admin/permission');
        }
        }
        
        public function permissionDelete($permissionId)
        {
            $this->load->model('permissionModel');
            $this->permissionModel->deletePermissionById($permissionId);
            $this->session->set_flashdata('permissionOperationMessage', 'Permission Deleted successfully.');
            redirect('admin/permission');       
        }

}