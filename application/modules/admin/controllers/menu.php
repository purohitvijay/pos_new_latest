<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class Menu extends MY_Controller {

        public function __construct() {
            parent::__construct();
            $this->load->helper('url');
            $this->load->library('commonlibrary');
        }

        public function index() {
            $message = $this->session->flashdata('menuOperationMessage');
            $dataArray['message'] = $message;
            //load css
            $dataArray['local_css'] = array(
                'datatable'
            );
            //load js
            $dataArray['local_js'] = array(
                'datatable');

            $this->load->view('menuList', $dataArray);
        }

        public function getMenuData() {
            
            $this->load->model('admin/menuModel');
            $paginparam = $_GET;
           
            $total = $this->menuModel->getMenuCount();
            $menuData = $this->menuModel->getAllMenu($paginparam);
            $dataArray = array();

            foreach ($menuData as $idx => $val) {
                $menuData[$idx]['delete'] = "<a href='" . base_url() . "admin/menu/menuDelete/" . $val['id'] . "' onClick=\"javascript:return confirm('Are you sure you want to delete?');\"'><i class='fa fa-times'></i></a>";
                $menuData[$idx]['edit'] = "<a href='" . base_url() . "admin/menu/addMenu/" . $val['id'] . "'><i class='fa fa-edit'></i></a>";
            }

            $dataArray['iTotalRecords'] = $total;
            $dataArray['iTotalDisplayRecords'] = $total;
            $dataArray['sEcho'] = $paginparam['sEcho'];
            $dataArray['aData'] = $menuData;
           
            echo json_encode($dataArray);
        }

        public function addMenu($menuId = null) {
            $dataArray = array();
            if (empty($menuId))
                $menuId = $this->input->post('menuId');

            //post value 
            if (!empty($_POST)) {
                $dataArray = $this->input->post(NULL, TRUE);
            }
            $this->load->library('form_validation');
            $this->form_validation->set_rules('menuName', 'Menu Name', 'required|trim|unique[menu.menuName.id.' . $this->input->post('menuId') . ']');

            $this->load->model('admin/menuModel');

            //get permission array
            $permissionArr = $this->commonlibrary->getPermissionArray();
            $dataArray['permissionArr'] = $permissionArr;

            if ($this->form_validation->run() == FALSE) {
                $dataArray['form_caption'] = "Add Menu";

                if (!empty($menuId)) {
                    $menuRecord = $this->menuModel->getMenuById($menuId);
                    $dataArray['menuId'] = $menuId;
                    $dataArray['menuName'] = $menuRecord->menuName;
                    $dataArray['permissionId'] = $menuRecord->permissionId;
                    $dataArray['orderId'] = $menuRecord->orderId;
                    $dataArray['form_caption'] = "Edit Menu";
                }
                $this->load->view('/menuForm', $dataArray);
            } else {
                $menuId = $this->input->post('menuId');
                $dataValues = array(
                    'menuName' => $this->input->post('menuName'),
                    'permissionId' => $this->input->post('permissionId'),
                    'orderId' => $this->input->post('orderId')
                );
                $this->session->set_flashdata('menuOperationMessage', 'Menu Added successfully.');
                if (!empty($menuId)) {
                    $dataValues['id'] = $menuId;
                    $this->session->set_flashdata('menuOperationMessage', 'Menu Updated successfully.');
                }
                $menuId = $this->menuModel->saveMenu($dataValues);
                redirect('admin/menu');
            }
        }

        public function menuDelete($menuId) {
            $this->load->model('menuModel');
            $this->menuModel->deleteMenuById($menuId);
            $this->session->set_flashdata('menuOperationMessage', 'Menu Deleted successfully.');
            redirect('admin/menu');
        }

    }