<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Submenu extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('commonlibrary');
    }
    
    public function index()
    {
        $message = $this->session->flashdata('subMenuOperationMessage');
        $dataArray['message'] = $message;
        $dataArray['local_css'] = array(
                'datatable'
            );
            //load js
            $dataArray['local_js'] = array(
                'datatable',
                'datatable-bootstrap');
        $this->load->view('subMenuList',$dataArray);
    }
    
    public function getSubMenuData() {
        $this->load->model('admin/subMenuModel');
        $paginparam = $_GET;
        $total = $this->subMenuModel->getSubMenuCount();
        $menuData = $this->subMenuModel->getAllSubMenu($paginparam);
        $dataArray = array();

        foreach ($menuData as $idx => $val) {
            $menuData[$idx]['delete'] = "<a href='" . base_url() . "admin/submenu/subMenuDelete/" . $val['id'] . "' onClick=\"javascript:return confirm('Are you sure you want to delete?');\"'><i class='icon-trash'></i></a>";
            $menuData[$idx]['edit'] = "<a href='" . base_url() . "admin/submenu/addSubMenu/" . $val['id'] . "'><i class='icon-edit'></i></a>";
        }

        $dataArray['iTotalRecords'] = $total;
        $dataArray['iTotalDisplayRecords'] = $total;
        $dataArray['sEcho'] = $paginparam['sEcho'];
        $dataArray['aData'] = $menuData;
        echo json_encode($dataArray);
    }
    
    public function addSubMenu($subMenuId = null)
        {
         $dataArray = array();
        if (empty($subMenuId))
            $subMenuId = $this->input->post('subMenuId');

        //post value 
        if (!empty($_POST)) {
            $dataArray = $this->input->post(NULL, TRUE);
        }
        
        //get permission array
        $permissionArr = $this->commonlibrary->getPermissionArray();        
        $dataArray['permissionArr'] = $permissionArr;
        
        //get Menu Array
        //get permission array
        $menuArr = $this->commonlibrary->getMenuArray();        
        $dataArray['menuArr'] = $menuArr;
        
        $this->load->library('form_validation');
        $this->form_validation->set_rules('subMenuName', 'Submenu Name', 'required|trim|unique[subMenu.subMenuName.id.' . $this->input->post('subMenuId') . ']');

        $this->load->model('admin/subMenuModel');
        
        
        if ($this->form_validation->run() == FALSE) {
            $dataArray['form_caption'] = "Add SubMenu";

            if (!empty($subMenuId)) {
                $submenuRecord = $this->subMenuModel->getSubMenuById($subMenuId);
                $dataArray['subMenuId'] = $subMenuId;
                $dataArray['subMenuName'] = $submenuRecord->subMenuName;
                $dataArray['menuId'] = $submenuRecord->menuId;
                $dataArray['permissionId'] = $submenuRecord->permissionId;
                $dataArray['orderId'] = $submenuRecord->orderId;
                $dataArray['form_caption'] = "Edit SubMenu";
            }
            $this->load->view('/subMenuForm', $dataArray);
        } else {
            $subMenuId = $this->input->post('subMenuId');
            $dataValues = array(
               'subMenuName' => $this->input->post('subMenuName'),
                'menuId' => $this->input->post('menuId'),
                'permissionId' => $this->input->post('permissionId'),
                'orderId' => $this->input->post('orderId')
            );
            $this->session->set_flashdata('subMenuOperationMessage', 'SubMenu Added successfully.');
            if (!empty($subMenuId)) {
                $dataValues['id'] = $subMenuId;
                $this->session->set_flashdata('subMenuOperationMessage', 'SubMenu Updated successfully.');
            }
            $subMenuId = $this->subMenuModel->saveSubMenu($dataValues);
            redirect('admin/submenu');
        }
        }
        
        public function subMenuDelete($subMenuId)
        {
            $this->load->model('admin/subMenuModel');
            $this->subMenuModel->deletesubMenuById($subMenuId);
            $this->session->set_flashdata('suenuOperationMessage', 'SubMenu Deleted successfully.');
            redirect('admin/submenu');       
        }
}
