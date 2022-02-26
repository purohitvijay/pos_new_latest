<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Masters extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('commonlibrary');
    }

    public function boxList()
    {
        $message = $this->session->flashdata('boxOperationMessage');
        $dataArray['message'] = $message;
        //load css
        $dataArray['local_css'] = array(
            'datatable'
        );
        //load js
        $dataArray['local_js'] = array(
            'datatable');

        $this->load->view('masters/boxList', $dataArray);
    }

    public function getBoxData()
    {

        $this->load->model('admin/mastersModel');
        $paginparam = $_GET;

        $total = $this->mastersModel->getBoxCount();
        $boxData = $this->mastersModel->getAllBoxes($paginparam);
        $dataArray = array();

        foreach ($boxData as $idx => $val)
        {
            $boxData[$idx]['delete'] = "<a href='" . base_url() . "admin/masters/boxDelete/" . $val['id'] . "' onClick=\"javascript:return confirm('Are you sure you want to delete?');\"'><i class='fa fa-times'></i></a>";
            $boxData[$idx]['edit'] = "<a href='" . base_url() . "admin/masters/addBox/" . $val['id'] . "'><i class='fa fa-edit'></i></a>";
        }

        $dataArray['iTotalRecords'] = $total;
        $dataArray['iTotalDisplayRecords'] = $total;
        $dataArray['sEcho'] = $paginparam['sEcho'];
        $dataArray['aData'] = $boxData;

        echo json_encode($dataArray);
    }

    public function addBox($id = null)
    {
        $dataArray = array();
        if (empty($id))
            $id = $this->input->post('id');

        //post value 
        if (!empty($_POST))
        {
            $dataArray = $this->input->post(NULL, TRUE);
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'required|trim|unique[boxes.name.id.' . $this->input->post('id') . ']');
        $this->form_validation->set_rules('short_name', 'Short Name', 'required|trim|unique[boxes.short_name.id.' . $this->input->post('id') . ']');

        $this->load->model('admin/mastersModel');

        if ($this->form_validation->run() == FALSE)
        {
            $delivery_commission_base_amount = $this->config->item("delivery_commission_base_amount");
            
            $dataArray['form_caption'] = "Add Box";
            
            if (empty($id))
            {
                $dataArray['delivery_commission'] = $delivery_commission_base_amount;
            }
            else
            {
                $boxRecord = $this->mastersModel->getBoxById($id);
                $dataArray['id'] = $id;
                $dataArray['name'] = $boxRecord->name;
                $dataArray['short_name'] = $boxRecord->short_name;
                $dataArray['description'] = $boxRecord->description;
                $dataArray['volume'] = $boxRecord->volume;
                $dataArray['order_id'] = $boxRecord->order_id;
                $dataArray['collection_commission'] = $boxRecord->collection_commission;
                $dataArray['delivery_commission'] = $boxRecord->delivery_commission;
                $dataArray['form_caption'] = "Edit Box";
            }
            $this->load->view('masters/boxForm', $dataArray);
        }
        else
        {
            $id = $this->input->post('id');
            $dataValues = array(
                'name' => $this->input->post('name'),
                'short_name' => $this->input->post('short_name'),
                'description' => $this->input->post('description'),
                'volume' => $this->input->post('volume'),
                'order_id' => $this->input->post('order_id'),
                'delivery_commission' => $this->input->post('delivery_commission'),
                'collection_commission' => $this->input->post('collection_commission')
            );
            $this->session->set_flashdata('boxOperationMessage', 'Added Successfully.');
            if (!empty($id))
            {
                $dataValues['id'] = $id;
                $this->session->set_flashdata('boxOperationMessage', 'Updated successfully.');
            }
                $id = $this->mastersModel->saveBox($dataValues);
            redirect('admin/masters/boxList');
        }
    }

    public function boxDelete($id)
    {
        $this->load->model('mastersModel');
        $this->mastersModel->deleteBoxById($id);
        $this->session->set_flashdata('boxOperationMessage', 'Box Deleted successfully.');
        redirect('admin/masters/boxList');
    }

    
    public function locationList()
    {
        $message = $this->session->flashdata('locationOperationMessage');
        $dataArray['message'] = $message;
        //load css
        $dataArray['local_css'] = array(
            'datatable'
        );
        //load js
        $dataArray['local_js'] = array(
            'datatable');

        $this->load->view('masters/locationList', $dataArray);
    }

    public function getLocationData()
    {

        $this->load->model('admin/mastersModel');
        $paginparam = $_GET;

        $total = $this->mastersModel->getLocationCount();
        $locationData = $this->mastersModel->getAllLocations($paginparam);
        $dataArray = array();

        foreach ($locationData as $idx => $val)
        {
            $locationData[$idx]['delete'] = "<a href='" . base_url() . "admin/masters/locationDelete/" . $val['id'] . "' onClick=\"javascript:return confirm('Are you sure you want to delete?');\"'><i class='fa fa-times'></i></a>";
            $locationData[$idx]['edit'] = "<a href='" . base_url() . "admin/masters/addLocation/" . $val['id'] . "'><i class='fa fa-edit'></i></a>";
            $locationData[$idx]['kabupaten'] = "<a href='" . base_url() . "admin/masters/kabupatenList/" . $val['id'] . "'><i class='fa fa-map-marker'></i></a>";
        }

        $dataArray['iTotalRecords'] = $total;
        $dataArray['iTotalDisplayRecords'] = $total;
        $dataArray['sEcho'] = $paginparam['sEcho'];
        $dataArray['aData'] = $locationData;

        echo json_encode($dataArray);
    }

    public function addLocation($id = null)
    {
        $dataArray = array();
        if (empty($id))
            $id = $this->input->post('id');

        //post value 
        if (!empty($_POST))
        {
            $dataArray = $this->input->post(NULL, TRUE);
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'required|trim|unique[locations.name.id.' . $this->input->post('id') . ']');

        $this->load->model('admin/mastersModel');

        if ($this->form_validation->run() == FALSE)
        {
            $dataArray['form_caption'] = "Add Location";

            if (!empty($id))
            {
                $locationRecord = $this->mastersModel->getLocationById($id);
                $dataArray['id'] = $id;
                $dataArray['name'] = $locationRecord->name;
                $dataArray['order_id'] = $locationRecord->order_id;
                $dataArray['form_caption'] = "Edit Location";
            }
            $this->load->view('masters/locationForm', $dataArray);
        }
        else
        {
            $id = $this->input->post('id');
            $dataValues = array(
                'name' => $this->input->post('name'),
                'order_id' => $this->input->post('order_id'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
            $this->session->set_flashdata('locationOperationMessage', 'Added Successfully.');
            if (!empty($id))
            {
                $dataValues['id'] = $id;
                $this->session->set_flashdata('locationOperationMessage', 'Updated successfully.');
            }
            else
            {
                $dataValues['created_at'] = date('Y-m-d H:i:s');
            }
            
            
            $id = $this->mastersModel->saveLocation($dataValues);
            redirect('admin/masters/locationList');
        }
    }

    public function locationDelete($id)
    {
        $this->load->model('mastersModel');
        $this->mastersModel->deleteLocationById($id);
        $this->session->set_flashdata('locationOperationMessage', 'Location Deleted successfully.');
        redirect('admin/masters/locationList');
    }

    public function agentList()
    {
        $message = $this->session->flashdata('agentOperationMessage');
        $dataArray['message'] = $message;
        //load css
        $dataArray['local_css'] = array(
            'datatable'
        );
        //load js
        $dataArray['local_js'] = array(
            'datatable');

        $this->load->view('masters/agentList', $dataArray);
    }

    public function getAgentData()
    {

        $this->load->model('admin/mastersModel');
        $paginparam = $_GET;

        $total = $this->mastersModel->getAgentCount();
        $agentData = $this->mastersModel->getAllAgents($paginparam);
        $dataArray = array();

        foreach ($agentData as $idx => $val)
        {
            $joining_date = $val['joining_date'];
            if (!empty($joining_date))
            {
                list($year, $month, $day) = explode('-', $joining_date);
                $joining_date = "$day/$month/$year";
            }
            else
            {
                $joining_date = '';
            }

            $agentData[$idx]['joining_date'] = $joining_date;
            $agentData[$idx]['delete'] = "<a href='" . base_url() . "admin/masters/agentDelete/" . $val['id'] . "' onClick=\"javascript:return confirm('Are you sure you want to delete?');\"'><i class='fa fa-times'></i></a>";
            $agentData[$idx]['edit'] = "<a href='" . base_url() . "admin/masters/addAgent/" . $val['id'] . "'><i class='fa fa-edit'></i></a>";
        }

        $dataArray['iTotalRecords'] = $total;
        $dataArray['iTotalDisplayRecords'] = $total;
        $dataArray['sEcho'] = $paginparam['sEcho'];
        $dataArray['aData'] = $agentData;

        echo json_encode($dataArray);
    }

    public function addAgent($id = null)
    {
        $dataArray = array();
        if (empty($id))
            $id = $this->input->post('id');

        //post value 
        if (!empty($_POST))
        {
            $dataArray = $this->input->post(NULL, TRUE);
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'required|trim|unique[agents.name.id.' . $this->input->post('id') . ']');

        $this->load->model('admin/mastersModel');

        if ($this->form_validation->run() == FALSE)
        {
            $dataArray['form_caption'] = "Add Agent";

            if (!empty($id))
            {
                $agentRecord = $this->mastersModel->getAgentById($id);
                $dataArray['id'] = $id;
                $dataArray['name'] = $agentRecord->name;
                $dataArray['email'] = $agentRecord->email;
                $dataArray['mobile'] = $agentRecord->mobile;
                
                $dataArray['phone'] = $agentRecord->phone;
                $dataArray['address'] = $agentRecord->address;
                $dataArray['commission'] = $agentRecord->commission;
                $joining_date = $agentRecord->joining_date;
                
                if (!empty($joining_date))
                {
                    list($year, $month, $day) = explode('-', $joining_date);
                    $joining_date = "$day/$month/$year";
                }
                else
                {
                    $joining_date = '';
                }
                
                $dataArray['joining_date'] = $joining_date;
                
                
                $dataArray['form_caption'] = "Edit Agent";
            }
            
            //load js
            $dataArray['local_js'] = array(
                'bootstrap_date_picker', 'jquery-ui-1.11.2'
            );

            $this->load->view('masters/agentForm', $dataArray);
        }
        else
        {
            $id = $this->input->post('id');
            $joining_date = $this->input->post('joining_date');
            
            if (!empty($joining_date))
            {
                list($day, $month, $year) = explode('/', $joining_date);
                $joining_date = "$year-$month-$day";
            }
            
            $dataValues = array(
                'name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'mobile' => $this->input->post('mobile'),
                'phone' => $this->input->post('phone'),
                'address' => $this->input->post('address'),
                'commission' => $this->input->post('commission'),
                'joining_date' => $joining_date,
                'created_at' => date('Y-m-d H:i:s')
            );
            
            $this->session->set_flashdata('agentOperationMessage', 'Added Successfully.');
            if (!empty($id))
            {
                $dataValues['id'] = $id;
                $this->session->set_flashdata('agentOperationMessage', 'Updated successfully.');
            }
            else
            {
                $dataValues['updated_at'] = date('Y-m-d H:i:s');
            }
            
            
            $id = $this->mastersModel->saveAgent($dataValues);
            redirect('admin/masters/agentList');
        }
    }

    public function agentDelete($id)
    {
        $this->load->model('mastersModel');
        $this->mastersModel->deleteAgentById($id);
        $this->session->set_flashdata('agentOperationMessage', 'Agent Deleted successfully.');
        redirect('admin/masters/agentList');
    }
    
    public function locationBoxMapping()
    {
        $data = array();
        
        $this->load->library('adminlib');
        
        $this->load->model('admin/mastersModel');
        $data['boxes'] = $this->mastersModel->getAllBoxes();
        $data['locations'] = $this->mastersModel->getAllLocations();
        
        
        //load js
        $data['local_js'] = array(
                'multiselect', 'multiselect_filter'
            );
        //load css
        $data['local_css'] = array(
                'multiselect', 'multiselect_filter'
            );
        
        //post value 
        if (!empty($_POST))
        {
            $searchForm = $this->input->post('searchForm');
            
            $locations_selected = $this->input->post('locations_selected');
            $locations_selected_name = $this->input->post('locations_selected_name');
            $boxes_selected = $this->input->post('boxes_selected');
            $boxes_selected_name = $this->input->post('boxes_selected_name');

            $locations_names = $boxes_names = array();
            if (!empty($locations_selected))
            {
                foreach ($locations_selected as $index => $location_id)
                {
                    $locations_selected[$index] = $location_id;
                    $locations_names[$index] = $locations_selected_name[$location_id];
                }
            }

            if (!empty($boxes_selected))
            {
                foreach ($boxes_selected as $index => $box_id)
                {
                    $boxes_selected[$index] = $box_id;
                    $boxes_names[$index] = $boxes_selected_name[$box_id];
                }
            }

            $data['locations_selected'] = $locations_selected;
            $data['boxes_selected'] = $boxes_selected;
            $data['locations_names'] = $locations_names;
            $data['boxes_names'] = $boxes_names;
            
            if (empty($searchForm))
            {
                $price = $this->input->post('prices');
                $this->adminlib->savelocationBoxPriceMapping($price);
                
                $this->session->set_flashdata('message', 'Price Updated successfully.');
            }
            
            $data['records'] = $this->adminlib->getlocationBoxPriceMapping();
        }
        
        $this->load->view('masters/locationBoxMappingForm', $data);
    }
    
    public function codeList()
    {
        $message = $this->session->flashdata('codeOperationMessage');
        $dataArray['message'] = $message;
        //load css
        $dataArray['local_css'] = array(
            'datatable'
        );
        //load js
        $dataArray['local_js'] = array(
            'datatable');

        $this->load->view('masters/codeList', $dataArray);
    }

    public function getCodeData()
    {

        $this->load->model('admin/mastersModel');
        $paginparam = $_GET;

        $total = $this->mastersModel->getCodeCount();
        
        $codeData = $this->mastersModel->getAllCodes($paginparam);
        $dataArray = array();

        foreach ($codeData as $idx => $val)
        {
            $codeData[$idx]['delete'] = "<a href='" . base_url() . "admin/masters/codeDelete/" . $val['id'] . "' onClick=\"javascript:return confirm('Are you sure you want to delete?');\"'><i class='fa fa-times'></i></a>";
            $codeData[$idx]['edit'] = "<a href='" . base_url() . "admin/masters/addCode/" . $val['id'] . "'><i class='fa fa-edit'></i></a>";
        }

        $dataArray['iTotalRecords'] = $total;
        $dataArray['iTotalDisplayRecords'] = $total;
        $dataArray['sEcho'] = $paginparam['sEcho'];
        $dataArray['aData'] = $codeData;

        echo json_encode($dataArray);
    }

    public function addCode($id = null)
    {
        $dataArray = array();
        if (empty($id))
            $id = $this->input->post('id');

        //post value 
        if (!empty($_POST))
        {
            $dataArray = $this->input->post(NULL, TRUE);
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('code', 'Code', 'required|trim|unique[codes.code.id.' . $this->input->post('id') . ']');
        $this->form_validation->set_rules('location_id', 'Location', 'required');

        $this->load->model('admin/mastersModel');
        
        $dataArray['locations'] = $this->mastersModel->getAllLocations();
        $dataArray['boxes'] = $this->mastersModel->getAllBoxes();

        if ($this->form_validation->run() == FALSE)
        {
            $dataArray['form_caption'] = "Add Code";

            if (!empty($id))
            {
                $codeRecord = $this->mastersModel->getCodeById($id);
                $dataArray['id'] = $id;
                $dataArray['code'] = $codeRecord->code;
                $dataArray['location_id'] = $codeRecord->location_id;
                $dataArray['description'] = $codeRecord->description;
                
                $codeDetails = $this->mastersModel->getCodeBoxesDetails($id);
                
                $array = array();
                
                if (!empty($codeDetails))
                {    
                    foreach ($codeDetails as $index => $row )
                    {
                        $array[] = $row['box_id'];
                    }
                }
                
                $dataArray['box_ids'] = $array;
                $dataArray['form_caption'] = "Edit Code";
            }
            $dataArray['local_js'] = array(
                'icheck'
            );
            $dataArray['local_css'] = array(
                'icheck'
            );
            $this->load->view('masters/codeForm', $dataArray);
        }
        else
        {
            $id = $this->input->post('id');
            $prices = $this->input->post('prices');
            $quantity = $this->input->post('quantity');
            $box_ids = $this->input->post('box_ids');
            
            $dataValues = array(
                'code' => $this->input->post('code'),
                'location_id' => $this->input->post('location_id'),
                'description' => $this->input->post('description')
            );
            $this->session->set_flashdata('codeOperationMessage', 'Added Successfully.');
            if (!empty($id))
            {
                $dataValues['id'] = $id;
                $this->session->set_flashdata('codeOperationMessage', 'Updated successfully.');
                $id = $this->mastersModel->saveCode($dataValues);
                
                $this->mastersModel->deleteCodeBoxes($id);
            }
            else
            {
                $id = $this->mastersModel->saveCode($dataValues);
            }
            
            if (!empty($box_ids))
            {
                $array = array(
                    'code_id' => $id
                );
                foreach ($box_ids as $index => $box_id)
                {
                    $array['box_id'] = $box_id;

                    $this->mastersModel->saveCodeBoxes($array);
                }
            }
            
            redirect('admin/masters/codeList');
        }
    }

    public function codeDelete($id)
    {
        $this->load->model('mastersModel');
        $this->mastersModel->deleteCode($code_id);
        $this->mastersModel->deleteCodeBoxes($code_id);
        $this->session->set_flashdata('codeOperationMessage', 'Code Deleted successfully.');
        redirect('admin/masters/codeList');
    }
    
    public function kabupatenList($location_id)
    {
        $message = $this->session->flashdata('kabupatenOperationMessage');
        $dataArray['message'] = $message;
        $dataArray['location_id'] = $location_id;
        //load css
        $dataArray['local_css'] = array(
            'datatable'
        );
        //load js
        $dataArray['local_js'] = array(
            'datatable');

        $this->load->view('masters/kabupatenList', $dataArray);
    }

    public function getKabupatenData($location_id)
    {

        $this->load->model('admin/mastersModel');
        $paginparam = $_GET;

        $total = $this->mastersModel->getKabupatenCount($location_id);
        $kabupatenData = $this->mastersModel->getAllKabupatens($location_id, $paginparam);
        $dataArray = array();

        foreach ($kabupatenData as $idx => $val)
        {
            $kabupatenData[$idx]['delete'] = "<a href='" . base_url() . "admin/masters/kabupatenDelete/$location_id/" . $val['id'] . "' onClick=\"javascript:return confirm('Are you sure you want to delete?');\"'><i class='fa fa-times'></i></a>";
            $kabupatenData[$idx]['edit'] = "<a href='" . base_url() . "admin/masters/addKabupaten/$location_id/" . $val['id'] . "'><i class='fa fa-edit'></i></a>";
        }

        $dataArray['iTotalRecords'] = $total;
        $dataArray['iTotalDisplayRecords'] = $total;
        $dataArray['sEcho'] = $paginparam['sEcho'];
        $dataArray['aData'] = $kabupatenData;

        echo json_encode($dataArray);
    }

    public function addKabupaten($location_id, $id = null)
    {
        $dataArray = array();
        if (empty($id))
            $id = $this->input->post('id');

        //post value 
        if (!empty($_POST))
        {
            $dataArray = $this->input->post(NULL, TRUE);
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'required|trim|unique[kabupatens.name.id.' . $this->input->post('id') . ']');

        $this->load->model('admin/mastersModel');

        if ($this->form_validation->run() == FALSE)
        {
            $dataArray['form_caption'] = "Add Kabupaten";
            $dataArray['locations'] = $this->mastersModel->getAllLocations();
            $dataArray['location_id'] = $location_id;

            if (!empty($id))
            {
                $kabupatenRecord = $this->mastersModel->getKabupatenById($id);
                $dataArray['id'] = $id;
                $dataArray['name'] = $kabupatenRecord->name;
                $dataArray['location_id'] = $kabupatenRecord->location_id;
                $dataArray['form_caption'] = "Edit Kabupaten";
            }
            $this->load->view('masters/kabupatenForm', $dataArray);
        }
        else
        {
            $id = $this->input->post('id');
            $location_id = $this->input->post('location_id');
            $dataValues = array(
                'name' => $this->input->post('name'),
                'location_id' => $location_id,
                'updated_at' => date('Y-m-d H:i:s'),
            );
            $this->session->set_flashdata('kabupatenOperationMessage', 'Added Successfully.');
            if (!empty($id))
            {
                $dataValues['id'] = $id;
                $this->session->set_flashdata('kabupatenOperationMessage', 'Updated successfully.');
            }
            else
            {
                $dataValues['created_at'] = date('Y-m-d H:i:s');
            }
            
            $id = $this->mastersModel->saveKabupaten($dataValues);
            redirect('admin/masters/kabupatenList/'.$location_id);
        }
    }

    public function kabupatenDelete($location_id, $id)
    {
        $this->load->model('mastersModel');
        $this->mastersModel->deleteKabupatenById($id);
        $this->session->set_flashdata('kabupatenOperationMessage', 'Kabupaten Deleted successfully.');
        redirect('admin/masters/kabupatenList/'.$location_id);
    }
    
    
    public function shipmentBatchList()
    {
        $this->load->model('admin/mastersModel');
        
        $message = $this->session->flashdata('shipmentBatchOperationMessage');
        $dataArray['message'] = $message;
        $dataArray['geo_type'] = $this->_geo_type;
        //load css
        $dataArray['local_css'] = array(
            'datatable'
        );
        //load js
        $dataArray['local_js'] = array(
            'datatable');
        
        $shipmentBatchesArr = $this->mastersModel->getShipmentBatchArr('desc');
        $dataArray['shipmentBatchesArr'] = $shipmentBatchesArr;
        $this->load->view('masters/shipmentBatchList', $dataArray);
    }

    public function getShipmentBatchData()
    {

        $this->load->model('admin/mastersModel');
        $paginparam = $_GET;
        
        $result = $this->mastersModel->getAllShipmentBatches($paginparam);
        
        $total = $result['foundRows'];
        $shipmentBatchData = $result['resultSet'];
        
        $dataArray = array();

        if (!empty($total))
        {
            foreach ($shipmentBatchData as $idx => $val)
            {
                $eta_singapore = date('d/m/Y', strtotime($val['eta_singapore']));
                $eta_jakarta = date('d/m/Y', strtotime($val['eta_jakarta']));
                $eta_postki = date('d/m/Y', strtotime($val['eta_postki']));
                
                list($year, $month, $day) = explode('-', $val['load_date']);
                $load_date  = "$day/$month/$year";
                
                $ship_onboard = '';
                if(!empty($val['ship_onboard']))
                {
                    list($year, $month, $day) = explode('-', $val['ship_onboard']);
                    $ship_onboard = "$day/$month/$year";
                }
                        
                $boxes_count = empty($val['boxes_count']) ? 0 : $val['boxes_count'];
                $orders_count = empty($val['orders_count']) ? 0 : $val['orders_count'];
                        
                $shipmentBatchData[$idx]['count'] = "$boxes_count <b>(Boxes)</b><br>$orders_count <b>(Orders)</b>";
                
                $shipmentBatchData[$idx]['load_date'] = $load_date;
                $shipmentBatchData[$idx]['ship_onboard'] = $ship_onboard;
                $shipmentBatchData[$idx]['eta'] = "$eta_singapore (SIN)<br>$eta_jakarta (JKT)<br>$eta_postki (POS)";
              
                if ($this->_geo_type == 'jakarta')
                {
                    if ($val['status'] == 'yes')
                    {
                        $shipmentBatchData[$idx]['jakarta_operation'] = "-";
                    }
                    else
                    {
                        $shipmentBatchData[$idx]['jakarta_operation'] = "<a href='" . base_url() . "admin/report/getShipmentBatchReport/" . $val['id'] . "' target='_new'><i class='fa glyphicon-list'></i></a>";
                        $shipmentBatchData[$idx]['jakarta_operation'] .= "</br><a href='" . base_url() . "admin/report/dawnloadShipmentBatchReport/" . $val['id'] . "' target='_new'><i class='fa glyphicon-download'></i></a>";
                    }
                }
                else
                {
                    if ($val['orders_count'] > 0)
                    {
                        $shipmentBatchData[$idx]['operation'] = "--";
    //                    $shipmentBatchData[$idx]['delete'] = "--";
                        $shipmentBatchData[$idx]['view_orders'] = "<a href='" . base_url() . "admin/order/index/" . $val['id'] . "' target='_new'><i class='fa glyphicon-eye_open'></i></a>";

                     }
                    else
                    {
                        $shipmentBatchData[$idx]['operation'] = "<a href='" . base_url() . "admin/masters/shipmentBatchDelete/" . $val['id'] . "' onClick=\"javascript:return confirm('Are you sure you want to delete?');\"'><i class='fa fa-times'></i></a>";
                        $shipmentBatchData[$idx]['view_orders'] = "--";
                        $shipmentBatchData[$idx]['operation'] .= "</br>--";
                        $shipmentBatchData[$idx]['operation'] .= "</br>--";
                    }

                    if ($val['status'] == 'yes')
                    {
                        $shipmentBatchBoxMapping = $this->mastersModel->isShipmentBatchBoxAMapping($val['id']);
                        if($shipmentBatchBoxMapping === true)
                        {
                            $userId = $this->session->userdata['id'];
                            $canEditAccess = canPerformAction('shipment_batch_load_plan_edit', $userId);
                            if($canEditAccess === TRUE)
                            {
                              $shipmentBatchData[$idx]['operation'] .= "</br><a href='" . base_url() . "admin/masters/shipmentBoxMapping/".$val['id']."/true'><i class='fa glyphicon-dumbbell'></i></a>";
                            }
                        }
                        else
                        {
                            $shipmentBatchData[$idx]['operation'] .= "</br><a href='" . base_url() . "admin/masters/shipmentBoxMapping/".$val['id']."'><i class='fa glyphicon-dumbbell'></i></a>";
                        }
                        $shipmentBatchData[$idx]['update_status'] = "<a href='" . base_url() . "admin/masters/updateShipmentBatchStatus/" . $val['id'] . "/no' onClick=\"javascript:return confirm('Are you sure you want to update status?');\"'>Deactivate</i></a>";
                    }
                    else
                    {


                        $shipmentBatchData[$idx]['operation'] .= "</br><a href='" . base_url() . "admin/report/getShipmentBatchReport/" . $val['id'] . "' target='_new'><i class='fa glyphicon-list'></i></a>";
                        $shipmentBatchData[$idx]['operation'] .= "</br><a href='" . base_url() . "admin/report/dawnloadShipmentBatchReport/" . $val['id'] . "' target='_new'><i class='fa glyphicon-download'></i></a>";

                        $shipmentBatchData[$idx]['update_status'] = "<a href='" . base_url() . "admin/masters/updateShipmentBatchStatus/" . $val['id'] . "/yes' onClick=\"javascript:return confirm('Are you sure you want to update status?');\"'>Activate</i></a>";
                    }

                    $shipmentBatchData[$idx]['operation']  .= "</br><a href='" . base_url() . "admin/masters/addShipmentBatch/" . $val['id'] . "'><i class='fa fa-edit'></i></a>";
                    
                    $user_id = $this->session->userdata['id'];
                    $canPerform = canPerformAction('mass_order_status_update',$user_id);
                    if($canPerform === TRUE)
                    {
                        $shipmentBatchData[$idx]['operation']  .= "</br><a href='javaScript:void(0);' class='bulkUpdateShipmentBatchStatus'><i class='fa fa-check-square'></i></a>";
                    }
                }
            }
        }
        else
        {
            $shipmentBatchData = array();
        }
        
        $dataArray['iTotalRecords'] = $total;
        $dataArray['iTotalDisplayRecords'] = $total;
        $dataArray['sEcho'] = $paginparam['sEcho'];
        $dataArray['aData'] = $shipmentBatchData;

        echo json_encode($dataArray);
    }
    
    public function bulkUpdateShipmentBatchStatus()
    {
        $this->load->model('admin/mastersModel');
        $shipment_batch_id = $this->input->post('shipment_batch_id');
        $isOrderStatusReadyForJKT = $this->mastersModel->checkOrderStatusReadyForJKT($shipment_batch_id);
        if($isOrderStatusReadyForJKT)
        {
            // update status ReceivedAtJKT
            $this->mastersModel->updateOrderStatusReceivedAtJKT($shipment_batch_id);
            
            $return = array('status' => TRUE);
        }
        else
        {
            $return = array('status' => FALSE);
        }
        
        echo json_encode($return);
    }
    
    public function addShipmentBatch($id = null)
    {
        $dataArray = array();
        if (empty($id))
            $id = $this->input->post('id');

        //post value 
        if (!empty($_POST))
        {
            $dataArray = $this->input->post(NULL, TRUE);
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('batch_name', 'Batch Name/Id', 'required|trim|unique[shipment_batches.batch_name.id.' . $this->input->post('id') . ']');
        $this->form_validation->set_rules('booking_confirmation', 'Booking Confirmation', 'required|trim');
        $this->form_validation->set_rules('container_type', 'Container Type', 'required|trim');
        $this->form_validation->set_rules('vessel_name', 'Vessel Name', 'required|trim');
        $this->form_validation->set_rules('voyage_number', 'Voyage Number', 'required|trim');
        $this->form_validation->set_rules('eta_singapore', 'ETA Singapore', 'required|trim');
        $this->form_validation->set_rules('eta_jakarta', 'ETA Jakarta', 'required|trim');
        $this->form_validation->set_rules('eta_postki', 'ETA POSTKI', 'required|trim');
        $this->form_validation->set_rules('load_date', 'Load Date', 'required|trim');
        $this->form_validation->set_rules('consignee_order_id', 'Consignee Order Id', 'required|trim');
        
        //load css
        $dataArray['local_css'] = array('bootstrap_date_picker');
        //load js
        $dataArray['local_js'] = array('bootstrap_date_picker', 'jquery-ui-1.11.2');
        
        $order_number_config = $this->config->item("order_number_config");
        $dataArray['order_number_size_in_digits'] = $order_number_config['order_number_size_in_digits'];

        $this->load->model('admin/mastersModel');

        if ($this->form_validation->run() == FALSE)
        {
            $dataArray['container_types'] = $this->config->item('container_types');
            
            $dataArray['configurable_password'] = $this->config->item('configurable_password');
            
            $dataArray['form_caption'] = "Add Shipment Batch";

            if (!empty($id))
            {
                $shipmentBatchRecord = $this->mastersModel->getShipmentBatchById($id);
                
                $eta_singapore = date('d/m/Y', strtotime($shipmentBatchRecord->eta_singapore));
                $eta_jakarta = date('d/m/Y', strtotime($shipmentBatchRecord->eta_jakarta));
                $eta_postki = date('d/m/Y', strtotime($shipmentBatchRecord->eta_postki));
                
                list($year, $month, $day) = explode('-', $shipmentBatchRecord->load_date);
                $load_date  = "$day/$month/$year";
                
                $ship_onboard = '';
                if(!empty($shipmentBatchRecord->ship_onboard))
                {
                    list($year, $month, $day) = explode('-', $shipmentBatchRecord->ship_onboard);
                    $ship_onboard = "$day/$month/$year";
                }
            
                $dataArray['id'] = $id;
                $dataArray['batch_name'] = $shipmentBatchRecord->batch_name;
                $dataArray['booking_confirmation'] = $shipmentBatchRecord->booking_confirmation;
                $dataArray['container_type'] = $shipmentBatchRecord->container_type;
                $dataArray['vessel_name'] = $shipmentBatchRecord->vessel_name;
                $dataArray['voyage_number'] = $shipmentBatchRecord->voyage_number;
                $dataArray['eta_singapore'] = $eta_singapore;
                $dataArray['ship_onboard'] = $ship_onboard;
                $dataArray['eta_jakarta'] = $eta_jakarta;
                $dataArray['eta_postki'] = $eta_postki;
                $dataArray['bl_number'] = $shipmentBatchRecord->bl_number;
                $dataArray['load_date'] = $load_date;
                $dataArray['quantity'] = $shipmentBatchRecord->quantity;
                $dataArray['container_number'] = $shipmentBatchRecord->container_number;
                $dataArray['seal_number'] = $shipmentBatchRecord->seal_number;
                $dataArray['consignee_order_id'] = $shipmentBatchRecord->consignee_order_id;
                
                
                $dataArray['form_caption'] = "Edit ShipmentBatch";
            }
            $this->load->view('masters/shipmentBatchForm', $dataArray);
        }
        else
        {
            $id = $this->input->post('id');
            
            $eta_singapore = $this->input->post('eta_singapore');
            list($day, $month, $year) = explode('/', $eta_singapore);
            $eta_singapore  = "$year-$month-$day 00:00:01";
            
            $eta_jakarta = $this->input->post('eta_jakarta');
            list($day, $month, $year) = explode('/', $eta_jakarta);
            $eta_jakarta  = "$year-$month-$day 00:00:01";
            
            $eta_postki = $this->input->post('eta_postki');
            list($day, $month, $year) = explode('/', $eta_postki);
            $eta_postki  = "$year-$month-$day 00:00:01";
            
            $load_date = $this->input->post('load_date');
            list($day, $month, $year) = explode('/', $load_date);
            $load_date = "$year-$month-$day";
            $ship_onboard = $this->input->post('ship_onboard');
            
            if(!empty($ship_onboard))
            {
                list($day, $month, $year) = explode('/', $ship_onboard);
                $ship_onboard = "$year-$month-$day";
            }
            
            $ship_onboard_sms = $this->input->post('ship_onboard_sms');
            
            $dataValues = array(
                'batch_name' => $this->input->post('batch_name'),
                'booking_confirmation' => $this->input->post('booking_confirmation'),
                'container_type' => $this->input->post('container_type'),
                'vessel_name' => $this->input->post('vessel_name'),
                'voyage_number' => $this->input->post('voyage_number'),
                'eta_singapore' => $eta_singapore,
                'ship_onboard' => $ship_onboard,
                'eta_jakarta' => $eta_jakarta,
                'eta_postki' => $eta_postki,
                'bl_number' => $this->input->post('bl_number'),
                'load_date' => $load_date,
                'quantity' => $this->input->post('quantity'),
                'container_number' => $this->input->post('container_number'),
                'seal_number' => $this->input->post('seal_number'),
                'consignee_order_id' => $this->input->post('consignee_order_id'),
            );
            $this->session->set_flashdata('shipmentBatchOperationMessage', 'Added Successfully.');
            if (!empty($id))
            {
                $dataValues['id'] = $id;
                $this->session->set_flashdata('shipmentBatchOperationMessage', 'Updated successfully.');
            }
            else
            {
                $dataValues['created_at'] = date('Y-m-d H:i:s');
                $dataValues['created_by'] = $this->_user_id;
            }
            
            
            $dataValues['updated_at'] = date('Y-m-d H:i:s');
            
            $id = $this->mastersModel->saveShipmentBatch($dataValues);
            
            if($ship_onboard_sms == 1)
            {
                $record = array();
                $record['customer_name'] = $dataValues['vessel_name'];
                $record['customer_number'] = $dataValues['voyage_number'];
//                p($record);
                sendMessageShipOnboardToCustomer($record);
            }
            
            redirect('admin/masters/shipmentBatchList');
        }
    }

    public function shipmentBatchDelete($id)
    {
        $this->load->model('mastersModel');
        $this->mastersModel->deleteShipmentBatchById($id);
        $this->session->set_flashdata('shipmentBatchOperationMessage', 'ShipmentBatch Deleted successfully.');
        redirect('admin/masters/shipmentBatchList');
    }

    public function updateShipmentBatchStatus($id, $status)
    {
        $dataValues = array(
            'id' => $id,
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s'),
        );
        
        $this->load->model('mastersModel');
        $this->mastersModel->saveShipmentBatch($dataValues);
        
        $this->session->set_flashdata('shipmentBatchOperationMessage', 'ShipmentBatch Batch status updated successfully.');
        redirect('admin/masters/shipmentBatchList');
    } 
    
    public function userList()
    {
        $message = $this->session->flashdata('userOperationMessage');
        $dataArray['message'] = $message;
        //load css
        $dataArray['local_css'] = array(
            'datatable'
        );
        //load js
        $dataArray['local_js'] = array(
            'datatable');

        $this->load->view('masters/userList', $dataArray);
    }

    public function getUserData()
    {

        $this->load->model('admin/mastersModel');
        $paginparam = $_GET;

        $total = $this->mastersModel->getUserCount();
        $userData = $this->mastersModel->getAllUsers($paginparam);
        $dataArray = array();

        foreach ($userData as $idx => $val)
        {
            $userData[$idx]['delete'] = "<a href='" . base_url() . "admin/masters/userDelete/" . $val['id'] . "' onClick=\"javascript:return confirm('Are you sure you want to delete?');\"'><i class='fa fa-times'></i></a>";
            $userData[$idx]['edit'] = "<a href='" . base_url() . "admin/masters/addUser/" . $val['id'] . "'><i class='fa fa-edit'></i></a>";
        }

        $dataArray['iTotalRecords'] = $total;
        $dataArray['iTotalDisplayRecords'] = $total;
        $dataArray['sEcho'] = $paginparam['sEcho'];
        $dataArray['aData'] = $userData;

        echo json_encode($dataArray);
    }

    public function addUser($id = null)
    {
        $this->load->library('adminlib');        
        $dataArray = array();
        if (empty($id))
            $id = $this->input->post('id');
       
        //post value 
        if (!empty($_POST))
        {
            $dataArray = $this->input->post(NULL, TRUE);
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('username', 'Username', 'required|trim|unique[user.username.id.' . $this->input->post('id') . ']');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|unique[user.email.id.' . $this->input->post('id') . ']');
       

        $this->load->model('admin/mastersModel');

        if ($this->form_validation->run() == FALSE)
        {
            $dataArray['form_caption'] = "Add User";
            
            $dataArray['roleArr'] = $this->adminlib->getAllRoles();
            $dataArray['activeArr'] = array('0' => 'Select','yes'=>'Yes','no' => 'No');
            $dataArray['geoTypeArr'] = $this->config->item("geo_type");
            if (!empty($id))
            {
                $userRecord = $this->mastersModel->getUserById($id);
                $dataArray['id'] = $id;
                $dataArray['name'] = $userRecord->name;
                $dataArray['username'] = $userRecord->username;
                $dataArray['roleId'] = $userRecord->roleId;
                $dataArray['email'] = $userRecord->email;
                $dataArray['active'] = $userRecord->active;
                $dataArray['password'] = $userRecord->password;
                $dataArray['geo_type'] = $userRecord->geo_type;
                $dataArray['form_caption'] = "Edit User";
            }
            $this->load->view('masters/userForm', $dataArray);
        }
        else
        { 
            $id = $this->input->post('id');
            $dataValues = array(
                'name' => $this->input->post('name'),
                'username' => $this->input->post('username'),                
                'email' => $this->input->post('email'),
                'roleId' => $this->input->post('roleId'),
                'active' => $this->input->post('active'),
                'geo_type' => $this->input->post('geo_type')
            );
            $this->session->set_flashdata('userOperationMessage', 'Added Successfully.');
            if (!empty($id))
            {
                $dataValues['id'] = $id;
                
                 $userRecord = $this->mastersModel->getUserById($id);
                 $old_password = $userRecord->password;
                 $new_password = $this->input->post('password');
                 
                 
                 if($old_password != $new_password)
                 {
                     $dataValues['password'] = md5($this->input->post('password'));
                 }
                 
                $this->session->set_flashdata('userOperationMessage', 'Updated successfully.');
            }
            else
            {
                $dataValues['password'] = md5($this->input->post('password'));
            }
            
                $id = $this->mastersModel->saveUser($dataValues);
            redirect('admin/masters/userList');
        }
    }

    public function userDelete($id)
    {
        $this->load->model('mastersModel');
        $this->mastersModel->deleteUserById($id);
        $this->session->set_flashdata('userOperationMessage', 'User Deleted successfully.');
        redirect('admin/masters/userList');
    }
    
    public function shipmentBoxMapping($shipment_batch_id,$edit = false)
    { 
        $this->load->model('mastersModel');
       
         //get shipment batch
        $shipment_batch_rec = $this->mastersModel->getShipmentBatchById($shipment_batch_id);
        $shipment_batch_name = $shipment_batch_rec->batch_name;
        
        if($edit == "true")
        {
            $boxes = $this->mastersModel->getAllShipmentBoxMapping($shipment_batch_id);
            $dataArray = array(
                'shipment_batch_id' => $shipment_batch_id,
                'box_arr' => $boxes,
                'load_plan_status' => 'edit',
                'shipment_batch_name' => $shipment_batch_name
            );
        }
        else
        {
            $boxes = $this->mastersModel->getAllBoxesArr();
            $dataArray = array(
                'box_arr' => $boxes,
                'shipment_batch_id' => $shipment_batch_id,
                'load_plan_status' => 'add',
                'shipment_batch_name' => $shipment_batch_name
            );
        }
        $this->load->view('masters/shipmentBatchMapping', $dataArray);
    }
    
    public function saveShipmentBatchMapping()
    {
        $shipment_batch_id = $this->input->post('shipment_batch_id');
        $box_id_arr = $this->input->post('box_id');
        $box_quantity = $this->input->post('box_quantity');
        $load_plan_status = $this->input->post('load_plan_status');
        
        $this->load->model('mastersModel');
       
        
        if (!empty($box_id_arr))
        {
            foreach ($box_id_arr as $idx => $boxes)
            {
                $already_exists = false;
                $quantity = $box_quantity[$idx];
                if ($quantity >= 0)
                {
                    if ($load_plan_status == "edit")
                    {
                        $box_id = $boxes;
                        $shipment_box_mapping_rec = $this->mastersModel->getShipmentBoxMappingRecord($box_id, $shipment_batch_id);
                        if (!empty($shipment_box_mapping_rec))
                        {
//                            $old_quantity = $shipment_box_mapping_rec->quantity;
                            $old_scanned_quantity = $shipment_box_mapping_rec->scanned_quantity;
                            $new_quantity = $box_quantity[$idx];

//                            $quantity_diff = $new_quantity - $old_quantity;
                            $quantity = $new_quantity;
                            $scanned_quantity = $old_scanned_quantity;


                            $already_exists = true;
                            $shipment_box_mapping_id = $shipment_box_mapping_rec->id;
                        }
                        else
                        {
                            $quantity = $box_quantity[$idx];
                            $scanned_quantity = 0;
                        }
                    }
                    else
                    {
                        $quantity = $box_quantity[$idx];
                        $scanned_quantity = 0;
                    }
                    $dataArray = array(
                        'shipment_batch_id' => $shipment_batch_id,
                        'box_id' => $boxes,
                        'quantity' => $quantity,
                        'scanned_quantity' => $scanned_quantity,
                    );
                    if ($already_exists === true)
                    {
                        $dataArray['id'] = $shipment_box_mapping_id;
                    }
                    else
                    {
                        $dataArray['created_at'] = date('Y-m-d H:i:s');
                    }
                    $this->mastersModel->saveShipmentBatchBoxMapping($dataArray);
                }
            }
        }
        $this->session->set_flashdata('shipmentBatchOperationMessage', 'Shipment Batch Boxes Added Successfully.');
        redirect('admin/masters/shipmentBatchList');
    }

    public function addShipmentBatchBox()
    {
        $this->load->model('mastersModel');
        $boxes_arr = $this->mastersModel->getAllNotFavouriteBoxes();
        $dataArray = array(
            'box_arr' => $boxes_arr
        );
        $data = $this->load->view('masters/shipmentBatchBoxMapping', $dataArray,false);
        echo $data;        
    }
    
    public function checkShipmentBoxMappingQuantity()
    {
        $this->load->model('mastersModel');

        $box_id = $this->input->post('box_id');
        $shipment_batch_id = $this->input->post('shipment_batch_id');
        $new_quantity = $this->input->post('quantity');

        $shipment_box_mapping_rec = $this->mastersModel->getShipmentBoxMappingRecord($box_id, $shipment_batch_id);
        if (!empty($shipment_box_mapping_rec))
        {
            $old_scanned_quantity = $shipment_box_mapping_rec->scanned_quantity;            
            $quantity = $new_quantity;            
            
            if($quantity < $old_scanned_quantity)
            {
                   $return = array(
                       'status' => 'error',
                       'msg' => 'Can not update quantity as scanned quantity ('.$old_scanned_quantity.') exceeds beyond defined quantity('.$quantity.').');
            }
            else
            {
                $return = array(
                       'status' => 'success');
            }
            
        }
        else
        {
            $return = array(
                       'status' => 'success');
        }
        echo json_encode($return);exit;
    }
    
     //Pass type 
    public function pass_typeList()
    {        
        $message = $this->session->flashdata('locationOperationMessage');
        $dataArray['message'] = $message;
        //load css
        $dataArray['local_css'] = array(
            'datatable'
        );
        //load js
        $dataArray['local_js'] = array(
            'datatable');

        $this->load->view('masters/pass_typeList', $dataArray);
    }
     
     public function get_pass_type_data()
    {

        $this->load->model('admin/mastersModel');
        $paginparam = $_GET;

        $total = $this->mastersModel->getPassTypeCount();
        $passtypeData = $this->mastersModel->getAllPassType($paginparam);
        $dataArray = array();

        foreach ($passtypeData as $idx => $val)
        {
            $passtypeData[$idx]['delete'] = "<a href='" . base_url() . "admin/masters/pass_typeDelete/" . $val['pass_type_id'] . "' onClick=\"javascript:return confirm('Are you sure you want to delete?');\"'><i class='fa fa-times'></i></a>";
            $passtypeData[$idx]['edit'] = "<a href='" . base_url() . "admin/masters/add_pass_type/" . $val['pass_type_id'] . "'><i class='fa fa-edit'></i></a>";
            
        }

        $dataArray['iTotalRecords'] = $total;
        $dataArray['iTotalDisplayRecords'] = $total;
        $dataArray['sEcho'] = $paginparam['sEcho'];
        $dataArray['aData'] = $passtypeData;

        echo json_encode($dataArray);
    }

    public function add_pass_type($id = null)
    {  
        $dataArray = array();
        if (empty($id))
            $id = $this->input->post('pass_type_id');

        //post value 
        if (!empty($_POST))
        {
            $dataArray = $this->input->post(NULL, TRUE);
        }
        
        $this->load->library('form_validation');
        //$this->form_validation->set_rules('pass_type', 'assType', 'required|trim|unique[locations.name.id.' . $this->input->post('pass_type_id') . ']');
        $this->form_validation->set_rules('pass_type', 'Pass Type', 'required|trim|unique[pass_type.pass_type.pass_type_id.' . $this->input->post('pass_type_id') . ']');

        $this->load->model('admin/mastersModel');

        if ($this->form_validation->run() == FALSE)
        {
            $dataArray['form_caption'] = "Add Pass Type";

            if (!empty($id))
            {
                $PassTypeRecord = $this->mastersModel->getPassTypeById($id);
                $dataArray['pass_type_id'] = $id;
                $dataArray['pass_type'] = $PassTypeRecord->pass_type;
                $dataArray['form_caption'] = "Edit Pass Type";
            }
            $this->load->view('masters/PassTypeForm', $dataArray);
        }
        else
        {
            $id = $this->input->post('pass_type_id');
            $dataValues = array(
                'pass_type' => $this->input->post('pass_type'),
                'updated_at' => date('Y-m-d H:i:s'),
            ); 
            $this->session->set_flashdata('locationOperationMessage', 'Added Successfully.');
            if (!empty($id))
            {
                $dataValues['pass_type_id'] = $id;
                $this->session->set_flashdata('locationOperationMessage', 'Updated successfully.');
            }
            else
            {
                $dataValues['created_at'] = date('Y-m-d H:i:s');
            }
            
            
            $id = $this->mastersModel->savePassType($dataValues);
            redirect('admin/masters/pass_typeList');
        }
    }

    public function pass_typeDelete($id)
    {
        $this->load->model('mastersModel');
        $this->mastersModel->deletePassTypeById($id);
        $this->session->set_flashdata('locationOperationMessage', 'Pass type deleted successfully.');
        redirect('admin/masters/pass_typeList');
    }
    
    
    // Customer Type
      public function customer_typeList()
    {
        $message = $this->session->flashdata('userOperationMessage');
        $dataArray['message'] = $message;
        //load css
        $dataArray['local_css'] = array(
            'datatable'
        );
        //load js
        $dataArray['local_js'] = array(
            'datatable');

        $this->load->view('masters/customer_typeList', $dataArray);
    }

    public function get_customer_type_data()
    {

        $this->load->model('admin/mastersModel');
        $paginparam = $_GET;

        $total = $this->mastersModel->getCustomerTypeCount();
        $userData = $this->mastersModel->getAllCustomerType($paginparam);
        //p($userData);
        $dataArray = array();

        foreach ($userData as $idx => $val)
        {
            $userData[$idx]['delete'] = "<a href='" . base_url() . "admin/masters/deleteCustomerTypeById/" . $val['customer_type_id'] . "' onClick=\"javascript:return confirm('Are you sure you want to delete?');\"'><i class='fa fa-times'></i></a>";
            $userData[$idx]['edit'] = "<a href='" . base_url() . "admin/masters/add_customer_type/" . $val['customer_type_id'] . "'><i class='fa fa-edit'></i></a>";
        }

        $dataArray['iTotalRecords'] = $total;
        $dataArray['iTotalDisplayRecords'] = $total;
        $dataArray['sEcho'] = $paginparam['sEcho'];
        $dataArray['aData'] = $userData;

        echo json_encode($dataArray);
    }

    public function add_customer_type($id = null)
    {
       $dataArray = array();
        if (empty($id))
            $id = $this->input->post('customer_type_id');
        
        //post value 
        if (!empty($_POST))
        {
            $dataArray = $this->input->post(NULL, TRUE);
        }
        
        $this->load->library('form_validation');
        //$this->form_validation->set_rules('pass_type', 'assType', 'required|trim|unique[locations.name.id.' . $this->input->post('pass_type_id') . ']');
        $this->form_validation->set_rules('customer_type', 'Customer Type', 'required|trim|unique[customer_type.customer_type.customer_type_id.' . $this->input->post('customer_type_id') . ']');
        $this->form_validation->set_rules('pass_type_id', 'Pass Type', 'required|trim');

        $this->load->model('admin/mastersModel');

        $dataArray['pass_types'] = $this->mastersModel->getAllPassType();
        if ($this->form_validation->run() == FALSE)
        {
            $dataArray['form_caption'] = "Add Customer Type";

            if (!empty($id))
            {
                $Record = $this->mastersModel->getCustomerTypeById($id);
               
                $dataArray['customer_type_id'] = $id;
                $dataArray['customer_type'] = $Record->customer_type;
                $dataArray['pass_type_id'] = $Record->pass_type_id;
                $dataArray['form_caption'] = "Edit Customer Type";
            }
            $this->load->view('masters/customerTypeForm', $dataArray);
        }
        else
        {
            $id = $this->input->post('customer_type_id');
            $dataValues = array(
                'customer_type' => $this->input->post('customer_type'),
                'pass_type_id' =>  $this->input->post('pass_type_id'),
                'updated_at' => date('Y-m-d H:i:s'),
            ); 
            $this->session->set_flashdata('locationOperationMessage', 'Added Successfully.');
            
            if (!empty($id))
            { 
                $dataValues['customer_type_id'] = $id;
                $this->session->set_flashdata('locationOperationMessage', 'Updated successfully.');
            }
            else
            {
                $dataValues['created_at'] = date('Y-m-d H:i:s');
            }
            $id = $this->mastersModel->saveCustomerType($dataValues);
            redirect('admin/masters/customer_typeList');
        }
    }

   public function deleteCustomerTypeById($id)
    {
        $this->load->model('mastersModel');
        $this->mastersModel->deleteCustomerTypeById($id);
        $this->session->set_flashdata('locationOperationMessage', 'Customer type deleted successfully.');
        redirect('admin/masters/customer_typeList');
    }
    
     //Categories 
    public function categoriesList()
    {        
        $message = $this->session->flashdata('locationOperationMessage');
        $dataArray['message'] = $message;
        //load css
        $dataArray['local_css'] = array(
            'datatable'
        );
        //load js
        $dataArray['local_js'] = array(
            'datatable');

        $this->load->view('masters/categoriesList', $dataArray);
    }
     
     public function get_categories_data()
    {

        $this->load->model('admin/mastersModel');
        $paginparam = $_GET;

        $total = $this->mastersModel->getCategoriesCount();
        $categoriesData = $this->mastersModel->getAllCategories($paginparam);
        $dataArray = array();

        foreach ($categoriesData as $idx => $val)
        {
            $categoriesData[$idx]['delete'] = "<a href='" . base_url() . "admin/masters/categoryDelete/" . $val['category_id'] . "' onClick=\"javascript:return confirm('Are you sure you want to delete?');\"'><i class='fa fa-times'></i></a>";
            $categoriesData[$idx]['edit'] = "<a href='" . base_url() . "admin/masters/add_category/" . $val['category_id'] . "'><i class='fa fa-edit'></i></a>";
            
        }

        $dataArray['iTotalRecords'] = $total;
        $dataArray['iTotalDisplayRecords'] = $total;
        $dataArray['sEcho'] = $paginparam['sEcho'];
        $dataArray['aData'] = $categoriesData;

        echo json_encode($dataArray);
    }

    public function add_category($id = null)
    {  
        $dataArray = array();
        if (empty($id))
            $id = $this->input->post('category_id');

        //post value 
        if (!empty($_POST))
        {
            $dataArray = $this->input->post(NULL, TRUE);
        }
        
        $this->load->library('form_validation');
        //$this->form_validation->set_rules('category', 'assType', 'required|trim|unique[locations.name.id.' . $this->input->post('pass_type_id') . ']');
        $this->form_validation->set_rules('category', 'Category', 'required|trim|unique[categories.category.category_id.' . $this->input->post('category_id') . ']');

        $this->load->model('admin/mastersModel');

        if ($this->form_validation->run() == FALSE)
        {
            $dataArray['form_caption'] = "Add Category";

            if (!empty($id))
            {
                $CategoryRecord = $this->mastersModel->getCategoryById($id);
                $dataArray['category_id'] = $id;
                $dataArray['category'] = $CategoryRecord->category;
                $dataArray['form_caption'] = "Edit Category";
            }
            $this->load->view('masters/categoryForm', $dataArray);
        }
        else
        {
            $id = $this->input->post('category_id');
            $dataValues = array(
                'category' => $this->input->post('category'),
                'updated_at' => date('Y-m-d H:i:s'),
            ); 
            $this->session->set_flashdata('locationOperationMessage', 'Added Successfully.');
            if (!empty($id))
            {
                $dataValues['category_id'] = $id;
                $this->session->set_flashdata('locationOperationMessage', 'Updated successfully.');
            }
            else
            {
                $dataValues['created_at'] = date('Y-m-d H:i:s');
            }
            
            
            $id = $this->mastersModel->saveCategory($dataValues);
            redirect('admin/masters/categoriesList');
        }
    }

    public function categoryDelete($id)
    {
        $this->load->model('mastersModel');
        $this->mastersModel->deleteCategoryById($id);
        $this->session->set_flashdata('locationOperationMessage', 'Category deleted successfully.');
        redirect('admin/masters/categoriesList');
    }
    
    //Media Type
    
     // Customer Type
      public function media_typeList()
    {
        $message = $this->session->flashdata('userOperationMessage');
        $dataArray['message'] = $message;
        //load css
        $dataArray['local_css'] = array(
            'datatable'
        );
        //load js
        $dataArray['local_js'] = array(
            'datatable');

        $this->load->view('masters/media_typeList', $dataArray);
    }

    public function get_media_type_data()
    {

        $this->load->model('admin/mastersModel');
        $paginparam = $_GET;

        $total = $this->mastersModel->getMediaTypeCount();
        $userData = $this->mastersModel->getAllMediaType($paginparam);
        //p($userData);
        $dataArray = array();

        foreach ($userData as $idx => $val)
        {
            $userData[$idx]['delete'] = "<a href='" . base_url() . "admin/masters/deleteMediaTypeById/" . $val['media_type_id'] . "' onClick=\"javascript:return confirm('Are you sure you want to delete?');\"'><i class='fa fa-times'></i></a>";
            $userData[$idx]['edit'] = "<a href='" . base_url() . "admin/masters/add_media_type/" . $val['media_type_id'] . "'><i class='fa fa-edit'></i></a>";
        }

        $dataArray['iTotalRecords'] = $total;
        $dataArray['iTotalDisplayRecords'] = $total;
        $dataArray['sEcho'] = $paginparam['sEcho'];
        $dataArray['aData'] = $userData;

        echo json_encode($dataArray);
    }

    public function add_media_type($id = null)
    {
        $dataArray = array();
        if (empty($id))
            $id = $this->input->post('media_type_id');
        
        //post value 
        if (!empty($_POST))
        {
            $dataArray = $this->input->post(NULL, TRUE);
        }
        
        $this->load->library('form_validation');
        $this->form_validation->set_rules('media_type', 'Media Type', 'required|trim|unique[media_type.media_type.media_type_id.' . $this->input->post('media_type_id') . ']');
       // $this->form_validation->set_rules('category_id', ' Category', 'required|trim');

        $this->load->model('admin/mastersModel');

        $dataArray['categories'] = $this->mastersModel->getAllCategories(); 
        if ($this->form_validation->run() == FALSE)
        {
            $dataArray['form_caption'] = "Add MediaTYpe ";
            $dataArray['category_id'] = '';
            
            if (!empty($id))
            { 
                $Record = $this->mastersModel->getMediaTypeById($id);
                $dataArray['media_type_id'] = $id;
                $dataArray['media_type'] = $Record->media_type;
                $dataArray['category_id'] = $Record->category_id;
                $dataArray['form_caption'] = "Edit Media ";
            }
            
            $this->load->view('masters/mediaTypeForm', $dataArray);
        }
        else
        {
            $id = $this->input->post('media_type_id');
            $dataValues = array(
                'media_type' => $this->input->post('media_type'),
                'category_id' =>  $this->input->post('category_id'),
                'updated_at' => date('Y-m-d H:i:s'),
            ); 
            $this->session->set_flashdata('locationOperationMessage', 'Added Successfully.');
            
            if (!empty($id))
            { 
                $dataValues['media_type_id'] = $id;
                $this->session->set_flashdata('locationOperationMessage', 'Updated successfully.');
            }
            else
            {
                $dataValues['created_at'] = date('Y-m-d H:i:s');
            }
            
            $id = $this->mastersModel->saveMediaType($dataValues);
            redirect('admin/masters/media_typeList');
        }
    }

   public function deleteMediaTypeById($id)
    {
        $this->load->model('mastersModel');
        $this->mastersModel->deleteMediaTypeById($id);
        
        $this->session->set_flashdata('locationOperationMessage', 'Media type deleted successfully.');
        redirect('admin/masters/media_typeList');
    }
    
    
}
