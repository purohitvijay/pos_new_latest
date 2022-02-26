<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class LuckyDraw extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('commonlibrary');
    }

    public function index($draw_id = null)
    {
        $dataArray = array();
        $message = $this->session->flashdata('luckyDrawOperationMessage');
        $dataArray['message'] = $message;
        $this->load->library('form_validation');
        $dataArray['local_css'] = array(
            'bootstrap_date_picker','multiselect', 'multiselect_filter'
        );
        //load js
        $dataArray['local_js'] = array(
            'bootstrap_date_picker',
            'jquery-ui-1.11.2',
            'validation',
            'multiselect', 
            'multiselect_filter'
        );

        $this->load->model('admin/mastersModel');
        
        if (!empty($draw_id))
        {
            $this->load->model('luckydrawModel');
            $data = $this->luckydrawModel->getLuckyDrawById($draw_id);
            
            $dataArray['id'] = $draw_id;
            $dataArray['name'] = $data['name'];
            $dataArray['number_of_prize'] = $data['no_of_prizes'];
        }
        else
        {
            $dataArray['agents'] = $this->mastersModel->getAllAgents();
        }

        $this->load->view('luckyDraw/luckyDrawForm', $dataArray);
    }

    public function getLuckuDrawParticipantCount()
    {
        if (!empty($_POST['shipment_date_from']))
        {
            $shipment_date_from = $_POST['shipment_date_from'];
        }
        if (!empty($_POST['shipment_date_to']))
        {
            $shipment_date_to = $_POST['shipment_date_to'];
        }
        else
        {
            if (!empty($_POST['shipment_date_from']))
            {
                $shipment_date_to = $_POST['shipment_date_from'];
            }
        }

        $excluded_agent_id = $_POST['excluded_agent_id'];
        
        $this->load->library('luckydrawlib');
        $orderCount = $this->luckydrawlib->getLuckyDrawParticipantCount($shipment_date_from, $shipment_date_to, $excluded_agent_id);

        echo $orderCount;
    }

    public function importDataToLuckyDrawDB()
    {
        $result = array();

        if (!empty($_POST['shipment_date_from']))
        {
            $shipment_date_from = $_POST['shipment_date_from'];
        }
        if (!empty($_POST['shipment_date_to']))
        {
            $shipment_date_to = $_POST['shipment_date_to'];
        }
        else
        {
            if (!empty($_POST['shipment_date_from']))
            {
                $shipment_date_to = $_POST['shipment_date_from'];
            }
        }

        $excluded_agent_id = $_POST['excluded_agent_id'];
        $no_of_prize = $_POST['number_of_prize'];
        $name = $_POST['name'];
        $this->load->library('luckydrawlib');
        $data = $this->luckydrawlib->importDataToLuckyDrawDB($shipment_date_from, $shipment_date_to, $name, $excluded_agent_id, $no_of_prize);
        if (!empty($data))
        {
            $result['status'] = "success";
            $result['total'] = $data['totalParticipant'];
            $result['alreadyExist'] = $data['alreadyExistParticipant'];
            $result['luckyDrawId'] = $data['luckyDrawId'];
        }
        echo json_encode($result);
    }

    public function checkIsDrawAlreadyExist($luckyDrawId=0)
    {
        $result = array();

        $this->load->model('luckydrawModel');
        $isDrawAwardedCount = $this->luckydrawModel->getIsDrawAwardedCount($luckyDrawId);
        if ($isDrawAwardedCount > 0)
        {
            $result['status'] = "error";
            $result['msg'] = "";
        }
        else
        {
            $result['status'] = "success";
        }
        echo json_encode($result);
    }

    public function luckyDrawList()
    {
        $message = $this->session->flashdata('luckyDrawOperationMessage');
        $dataArray['message'] = $message;
        //load css
        $dataArray['local_css'] = array(
            'datatable'
        );
        //load js
        $dataArray['local_js'] = array(
            'datatable');
        $this->load->model('luckydrawModel');
        $isDrawAwardedCount = $this->luckydrawModel->getIsDrawAwardedCount();
        if ($isDrawAwardedCount > 0)
        $add_new = false;
        else
        $add_new = true;
        $dataArray['add_new'] = $add_new;
        $this->load->view('luckyDraw/luckyDrawList', $dataArray);
    }

    public function getLuckyDrawData()
    {
        $this->load->model('admin/luckydrawModel');
        $this->load->model('admin/userModel');
        $this->load->model('admin/mastersModel');
        $paginparam = $_GET;

        $total = $this->luckydrawModel->getLuckyDrawCount();
        $luckydrawData = $this->luckydrawModel->getAllLuckyDraw($paginparam);
        $dataArray = array();
        if (!empty($luckydrawData))
        {
            foreach ($luckydrawData as $idx => $val)
            {
                //creatd by user data
                $userData = $this->userModel->getUserById($val['created_by']);
                if (!empty($userData))
                {
                    $luckydrawData[$idx]['created_by_username'] = ucfirst($userData->username);
                }

                $luckydrawData[$idx]['excluded_agent_name'] = '--';
                //excluded agent data
                if (!empty($val['excluded_agent_id']))
                {
                    $excluded_agent_ids_arr = explode(",", $val['excluded_agent_id']);
                    $agents_name = "";
                    foreach ($excluded_agent_ids_arr as $key => $agent_id)
                    {
                       $agentData = $this->mastersModel->getAgentById($agent_id);
                       if (!empty($agentData))
                       {
                            if(empty($agents_name))
                             $agents_name =  ucfirst($agentData->name);
                            else
                             $agents_name = $agents_name.", ".ucfirst($agentData->name);
                                
                       }
                    }        
                    
        
                        $luckydrawData[$idx]['excluded_agent_name'] = $agents_name;
                    
                }

                $luckydrawData[$idx]['is_draw_awarded'] = ucfirst($val['is_draw_awarded']);
                if(empty($val['date_from']) && empty($val['date_to']))
                {
                    $luckydrawData[$idx]['name'] = ucfirst($val['name']);
                }
                else
                {
                    $luckydrawData[$idx]['name'] = ucfirst($val['name'])."<br>({$val['date_from']} - {$val['date_to']})";
                }

                $luckydrawData[$idx]['operation'] = "<a href='" . base_url() . "admin/luckyDraw/getLuckyDrawParticipant/" . $val['id'] . "'><i class='fa fa-list'></i></a>";
                $luckydrawData[$idx]['operation'] .= "<br/><a href='" . base_url() . "admin/luckyDraw/deleteWinnerRecord/" . $val['id'] . "' onClick=\"javascript:return confirm('This action will clear winner listing (if any). Do you want to proceed?');\"' title='Clear Winner List'><i class='fa fa-eraser'></i></a>";
                $luckydrawData[$idx]['operation'] .= "<br/><a href='" . base_url() . "admin/luckyDraw/index/" . $val['id'] . "'  title='Edit'><i class='fa fa-edit'></i></a>";
                $luckydrawData[$idx]['operation'] .= "<br/><a href='" . base_url() . "admin/luckyDraw/delete/" . $val['id'] . "' onClick=\"javascript:return confirm('Are you sure you want to delete this lucky draw?');\"'   title='Delete'><i class='fa fa-times'></i></a>";
            }
        }
//        /p($luckydrawData);
        $dataArray['iTotalRecords'] = $total;
        $dataArray['iTotalDisplayRecords'] = $total;
        $dataArray['sEcho'] = $paginparam['sEcho'];
        $dataArray['aData'] = $luckydrawData;

        echo json_encode($dataArray);
    }

    public function getLuckyDrawParticipant($lucky_draw_id)
    {
        $message = $this->session->flashdata('luckyDrawOperationMessage');
        $dataArray['message'] = $message;
        //load css
        $dataArray['local_css'] = array(
            'datatable'
        );
        //load js
        $dataArray['local_js'] = array(
            'datatable');
        $dataArray['lucky_draw_id'] = $lucky_draw_id;
        $this->load->view('luckyDraw/luckyDrawParticipantList', $dataArray);
    }

    public function getLuckyDrawParticipantData($lucky_draw_id)
    {
        $this->load->model('admin/luckydrawModel');

        $paginparam = $_GET;

        $total = $this->luckydrawModel->getLuckyDrawParticipantCountById($lucky_draw_id);
        $luckydrawData = $this->luckydrawModel->getAllLuckyDrawParticipantById($paginparam, $lucky_draw_id);
        $dataArray = array();

        $dataArray['iTotalRecords'] = $total;
        $dataArray['iTotalDisplayRecords'] = $total;
        $dataArray['sEcho'] = $paginparam['sEcho'];
        $dataArray['aData'] = $luckydrawData;

//        p($luckydrawData);
        echo json_encode($dataArray);
    }
    
    public function deleteWinnerRecord($lucky_draw_id)
    {
        $this->load->model('admin/luckydrawModel');
        //update luckydrawMaster
        $dataVal = array(
            'id' => $lucky_draw_id,
            'is_draw_awarded' => 'no');
        $this->luckydrawModel->saveLuckyDrawMaster($dataVal);
        
        //delete Winners order number records
        $this->luckydrawModel->deleteLuckyDrawWinner($lucky_draw_id);
        redirect('admin/luckyDraw/luckyDrawList');
    }
    
    public function delete($lucky_draw_id)
    {
        $this->load->model('admin/luckydrawModel');

        //delete Winners order number records
        $this->luckydrawModel->deleteLuckyDrawWinner($lucky_draw_id);
        
        //delete participants
        $this->luckydrawModel->deleteLuckyDrawParticipants($lucky_draw_id);
        
        //delete luckydrawMaster
        $this->luckydrawModel->deleteLuckyDrawMaster($lucky_draw_id);
        
        redirect('admin/luckyDraw/luckyDrawList');
    }
    
    public function updateLuckyDraw()
    {
        $no_of_prize = $this->input->post('number_of_prize');
        $name = $this->input->post('name');
        $id = $this->input->post('id');
        $this->load->model('luckydrawModel');
        // get declared prize 
        $winner_count = $this->luckydrawModel->getLuckyDrawWinnerCount($id);
       
        // check $no_of_prize is greater than declared prize 
        if($no_of_prize > $winner_count)
        {
        $dataVal = array(
            'id' => $id,
            'name' => $name,
            'no_of_prizes' => $no_of_prize,
        );
       
        $this->luckydrawModel->saveLuckyDrawMaster($dataVal);
        
        $result = array('status' => 'success');
        }
        else
        {
          $result = array('status' => 'error','msg' =>"Updated prize count should be > declared ($winner_count) prize count.");
        }
        echo json_encode($result);
    }
}
