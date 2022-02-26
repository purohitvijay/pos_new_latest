<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MastersModel extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getAllBoxes($pagingParams = array())
    {

        $this->db->select('id, name, short_name, description, volume, collection_commission');
        $this->db->from('boxes');

        if (isset($pagingParams['sSearch']))
        {
            $search = $pagingParams['sSearch'];
            if (!empty($search))
            {
                $this->db->like('name', $search);
                $this->db->or_like('short_name', $search);
                $this->db->or_like('description', $search);
            }
        }
        if (!empty($pagingParams['iSortCol_0']))
        {
            if (empty($pagingParams['sSortDir_0']))
            {
                $pagingParams['sSortDir_0'] = 'desc';
            }
            $column_arr = array('name', 'short_name', 'description');
            switch ($pagingParams['sSortDir_0'])
            {
                default:
                    $this->db->order_by($column_arr[$pagingParams['iSortCol_0'] + 1], $pagingParams['sSortDir_0']);
                    break;
            }
        }
        else
        {
            $this->db->order_by('order_id');
        }
        if (isset($pagingParams['iDisplayStart']) && $pagingParams['iDisplayLength'] != '-1')
        {
            $start = $pagingParams['iDisplayStart'];
            $offset = $pagingParams['iDisplayLength'];
            $this->db->limit($offset, $start);
        }
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function getBoxCount()
    {
        $count = '0';
        $this->db->select('count("id") as count');
        $query = $this->db->get('boxes')->row();
        $count = $query->count;
        return $count;
    }

    public function getBoxById($id)
    {
        $result = Null;
        if (!empty($id))
        {
            $this->db->select('*');
            $this->db->where('id', $id);
            $this->db->from('boxes');
            $result = $this->db->get()->row();
        }
        return $result;
    }
    
    public function getSaftriBox()
    {
        $result = array();
        $this->db->select('*');
        $this->db->like('description', "Saftri");
        $this->db->from('boxes');
        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $key => $value)
            {                
                $result[$value["id"]] =  $value["id"];
            }
        }
        return $result;
    }

    public function saveBox($dataValues)
    {
        $return = null;
        if (count($dataValues) > 0)
        {
            if (array_key_exists('id', $dataValues))
            {
                $this->db->where('id', $dataValues['id']);
                $this->db->update('boxes', $dataValues);

                $return = $dataValues['id'];
            }
            else
            {
                $this->db->insert('boxes', $dataValues);
                $return = $this->db->insert_id();
            }
        }
        return $return;
    }

    public function deleteBoxById($boxId)
    {
        $this->db->delete('boxes', array('id' => $boxId));
    }

    public function deleteLocationBoxPriceMapping($where)
    {
        $this->db->where($where);
        $this->db->delete('box_location_prices');
    }
    
    
    public function savelocationBoxPriceMapping($dataValues)
    {
        $return = null;
        if (count($dataValues) > 0)
        {
            $this->db->insert('box_location_prices', $dataValues);
            $return = $this->db->insert_id();
        }
        return $return;
    }

    public function getAllLocations($pagingParams = array())
    {

        $this->db->select('id, name, capture_weight');
        $this->db->from('locations');

        if (isset($pagingParams['sSearch']))
        {
            $search = $pagingParams['sSearch'];
            if (!empty($search))
            {
                $this->db->like('name', $search);
            }
        }
        if (!empty($pagingParams['iSortCol_0']))
        {
            if (empty($pagingParams['sSortDir_0']))
            {
                $pagingParams['sSortDir_0'] = 'desc';
            }
            $column_arr = array('name');
            switch ($pagingParams['sSortDir_0'])
            {
                default:
                    $this->db->order_by($column_arr[$pagingParams['iSortCol_0'] + 1], $pagingParams['sSortDir_0']);
                    break;
            }
        }
        else
        {
            $this->db->order_by('order_id');
        }
        if (isset($pagingParams['iDisplayStart']) && $pagingParams['iDisplayLength'] != '-1')
        {
            $start = $pagingParams['iDisplayStart'];
            $offset = $pagingParams['iDisplayLength'];
            $this->db->limit($offset, $start);
        }
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }
    
     public function getAllLocations_order_trans($received,$shipment_batch_ids,$temp_date_from,$temp_date_to)
    {

        $this->db->select('locations.id,locations.name, capture_weight');
        $this->db->from('orders');
        $this->db->join('order_trans', 'orders.id = order_trans.order_id');
        $this->db->join('kabupatens', 'kabupatens.id = order_trans.kabupaten_id');
        $this->db->join('order_status_trans', 'order_status_trans.order_id = orders.id');
        $this->db->join('locations', 'locations.id= order_trans.location_id');
        $this->db->join('user', 'user.id= order_status_trans.employee_id');
        $this->db->join('boxes', 'boxes.id= order_trans.box_id');
        $this->db->join('shipment_batches', 'shipment_batches.id= orders.shipment_batch_id');
        
        if(!empty($temp_date_from) && !empty($temp_date_to))
        {
            $this->db->where("(date(orders.delivery_date) >= '$temp_date_from' and  date(orders.delivery_date) <= '$temp_date_to')");
        }
        else
        {
           $this->db->where("(date(orders.delivery_date) >= NOW() - INTERVAL 3 MONTH)");
        }
        
        if(!empty($shipment_batch_ids))
        {
            $this->db->where('shipment_batches.receiving_batch_id',$shipment_batch_ids);
        }
        
        if(!empty($received))
        {
            switch ($received)
            {
                case 'Checked':
                    $this->db->where_in('order_status_trans.status', array("delivered_at_jkt_picture_taken",'delivered_at_jkt_picture_not_taken'));            
                   break;
                case 'Unchecked':
                    $this->db->where_not_in('order_status_trans.status', array("delivered_at_jkt_picture_taken",'delivered_at_jkt_picture_not_taken'));            
                   break;
                case 'indeterminate':
                                
                   break;
            }    
        }
        $this->db->where('orders.status', 'active');
        $this->db->where('order_status_trans.active', 'yes');
        $this->db->group_by('name');
        
        $query = $this->db->get();
//        p($this->db->last_query());
        $result = $query->result_array();
        return $result;
    }
    
    public function checkOrderStatusReadyForJKT($shipment_batch_id)
    {
        $this->db->select('orders.id, order_status_trans.status');
        $this->db->from('orders');
        $this->db->join('order_status_trans', 'order_status_trans.order_id = orders.id', 'left');
        
        $this->db->where('orders.shipment_batch_id',$shipment_batch_id);
        $this->db->where('order_status_trans.status !=', "ready_for_receiving_at_jakarta");            
        
        $this->db->group_by('orders.id');
        
        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            return FALSE;
        }
        return TRUE;
    }
    
    public function updateOrderStatusReceivedAtJKT($shipment_batch_id)
    {
        $this->db->select('orders.id, order_status_trans.status');
        $this->db->from('orders');
        $this->db->join('order_status_trans', 'order_status_trans.order_id = orders.id', 'left');
        
        $this->db->where('orders.shipment_batch_id',$shipment_batch_id);
        $this->db->where('order_status_trans.status !=', "ready_for_receiving_at_jakarta");            
        
        $this->db->group_by('orders.id');
        
        $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            foreach ($query->result_array() as $data)
            {                
                $this->db->where('order_id', $data['id']);
                $this->db->update('order_status_trans', array('status' => 'received_at_jakarta_warehouse'));
            }
        }
        
        return TRUE;
    }

    public function getLocationCount()
    {
        $count = '0';
        $this->db->select('count("id") as count');
        $query = $this->db->get('locations')->row();
        $count = $query->count;
        return $count;
    }

    public function getLocationById($id)
    {
        $result = Null;
        if (!empty($id))
        {
            $this->db->select('*');
            $this->db->where('id', $id);
            $this->db->from('locations');
            $result = $this->db->get()->row();
        }
        return $result;
    }

    public function saveLocation($dataValues)
    {
        $return = null;
        if (count($dataValues) > 0)
        {
            if (array_key_exists('id', $dataValues))
            {
                $this->db->where('id', $dataValues['id']);
                $this->db->update('locations', $dataValues);

                $return = $dataValues['id'];
            }
            else
            {
                $this->db->insert('locations', $dataValues);
                $return = $this->db->insert_id();
            }
        }
        return $return;
    }

    public function deleteLocationById($boxId)
    {
        $this->db->delete('locations', array('id' => $boxId));
    }
  
    public function getAllAgents($pagingParams = array())
    {

        $this->db->select('id, name, email, mobile, phone, address, joining_date, active, commission, can_update_total, order_no_type, is_excluded_in_lucky_draw');
        $this->db->from('agents');

        if (isset($pagingParams['sSearch']))
        {
            $search = $pagingParams['sSearch'];
            if (!empty($search))
            {
                $this->db->like('name', $search);
                $this->db->or_like('email', $search);
                $this->db->or_like('mobile', $search);
            }
        }
        if (!empty($pagingParams['iSortCol_0']))
        {
            if (empty($pagingParams['sSortDir_0']))
            {
                $pagingParams['sSortDir_0'] = 'desc';
            }
            $column_arr = array('name', 'email', 'mobile', 'active');
            switch ($pagingParams['sSortDir_0'])
            {
                default:
                    $this->db->order_by($column_arr[$pagingParams['iSortCol_0'] + 1], $pagingParams['sSortDir_0']);
                    break;
            }
        }
        else
        {
            $this->db->order_by('name');
        }
        if (isset($pagingParams['iDisplayStart']) && $pagingParams['iDisplayLength'] != '-1')
        {
            $start = $pagingParams['iDisplayStart'];
            $offset = $pagingParams['iDisplayLength'];
            $this->db->limit($offset, $start);
        }
        $query = $this->db->get();
        
        $result = $query->result_array();
        return $result;
    }

    public function getAgentCount()
    {
        $count = '0';
        $this->db->select('count("id") as count');
        $query = $this->db->get('agents')->row();
        $count = $query->count;
        return $count;
    }

    public function getAgentById($id)
    {
        $result = Null;
        if (!empty($id))
        {
            $this->db->select('*');
            $this->db->where('id', $id);
            $this->db->from('agents');
            $result = $this->db->get()->row();
        }
        return $result;
    }

    public function saveAgent($dataValues)
    {
        $return = null;
        if (count($dataValues) > 0)
        {
            if (array_key_exists('id', $dataValues))
            {
                $this->db->where('id', $dataValues['id']);
                $this->db->update('agents', $dataValues);

                $return = $dataValues['id'];
            }
            else
            {
                $this->db->insert('agents', $dataValues);
                $return = $this->db->insert_id();
            }
        }
        return $return;
    }

    public function deleteAgentById($boxId)
    {
        $this->db->delete('agents', array('id' => $boxId));
    }
    
    public function getlocationBoxMapping()
    {
        $this->db->select('*');
        $this->db->from('box_location_prices');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }
    
    public function getAllCodes($pagingParams = array())
    {

        $this->db->select('code, codes.id, codes.description, locations.name as location, '
                . 'GROUP_CONCAT(boxes.name SEPARATOR "<br/>") AS details', false);
        $this->db->from('codes');
        $this->db->join('code_box_mapping', 'code_box_mapping.code_id = codes.id', 'left');
        $this->db->join('locations', 'locations.id = codes.location_id', 'left');
        $this->db->join('boxes', 'boxes.id = code_box_mapping.box_id', 'left');
        $this->db->group_by('codes.id');

        if (isset($pagingParams['sSearch']))
        {
            $search = $pagingParams['sSearch'];
            if (!empty($search))
            {
                $this->db->like('code', $search);
                $this->db->or_like('codes.description', $search);
            }
        }
        if (!empty($pagingParams['iSortCol_0']))
        {
            if (empty($pagingParams['sSortDir_0']))
            {
                $pagingParams['sSortDir_0'] = 'desc';
            }
            $column_arr = array('code', 'description');
            switch ($pagingParams['sSortDir_0'])
            {
                default:
                    $this->db->order_by($column_arr[$pagingParams['iSortCol_0'] + 1], $pagingParams['sSortDir_0']);
                    break;
            }
        }
        else
        {
            $this->db->order_by('code');
        }
        if (isset($pagingParams['iDisplayStart']) && $pagingParams['iDisplayLength'] != '-1')
        {
            $start = $pagingParams['iDisplayStart'];
            $offset = $pagingParams['iDisplayLength'];
            $this->db->limit($offset, $start);
        }
        $query = $this->db->get();
        //p($this->db->last_query());
        $result = $query->result_array();
        return $result;
    }
    
    public function getCodeCount()
    {
        $count = '0';
        $this->db->select('count("codes.id") as count');
        $this->db->from('codes');
        $this->db->join('code_box_mapping','code_box_mapping.code_id = codes.id', 'left');
        $query = $this->db->get()->row();
        
        $count = $query->count;
        return $count;
    }
    
    public function getCodeDetailsById($code_id)
    {
        $query = $this->db->get('code_box_location_qty_mapping');
        $result = $query->result_array();
        return $result;
    }
    
    public function getCodeBoxesDetails($code_id)
    {
        $this->db->select('code, codes.location_id, locations.name as location_name, boxes.name as box_name, box_location_prices.price, '
                . 'code_box_mapping.box_id, capture_weight');
        $this->db->from('codes');
        $this->db->join('code_box_mapping', 'code_box_mapping.code_id = codes.id');
        $this->db->join('boxes', 'boxes.id = code_box_mapping.box_id');
        $this->db->join('locations', 'locations.id = codes.location_id');
        $this->db->join('box_location_prices', 'box_location_prices.box_id = code_box_mapping.box_id and box_location_prices.location_id = codes.location_id', 'left');
        $this->db->where('codes.id', $code_id);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }
    
    public function getOrderCodeBoxesDetails($where_data)
    {
        $this->db->select('code, codes.location_id, locations.name as location_name, capture_weight, '
                . 'boxes.name as box_name, price_per_unit as price, kabupatens.name as kabupaten, kabupaten_id, '
                . 'quantity, box_id,order_trans.promocode_id');
        $this->db->from('codes');
        $this->db->join('order_trans', 'order_trans.code_id = codes.id');
        $this->db->join('boxes', 'boxes.id = order_trans.box_id');
        $this->db->join('locations', 'locations.id = order_trans.location_id');
        $this->db->join('kabupatens', 'kabupatens.id = order_trans.kabupaten_id', 'left');
        $this->db->where($where_data);
        $query = $this->db->get();
        $result = $query->result_array();
        
        return $result;
    }
    
    public function getCodeById($id)
    {
        $result = Null;
        if (!empty($id))
        {
            $this->db->select('*');
            $this->db->where('id', $id);
            $this->db->from('codes');
            $result = $this->db->get()->row();
        }
        return $result;
    }

    public function deleteCode($code_id)
    {
        $this->db->delete('codes', array('code' => $code_id));
    }    

    public function deleteCodeDetails($code_id)
    {
        $this->db->delete('code_box_location_qty_mapping', array('code_id' => $code_id));
    }    

    public function deleteCodeBoxes($code_id)
    {
        $this->db->delete('code_box_mapping', array('code_id' => $code_id));
    }    
    
    public function saveCodeDetails($dataValues)
    {
        $return = null;
        if (count($dataValues) > 0)
        {
            $this->db->insert('code_box_location_qty_mapping', $dataValues);
        }
        return $return;
    }
    
    public function saveCodeBoxes($dataValues)
    {
        $return = null;
        if (count($dataValues) > 0)
        {
            $this->db->insert('code_box_mapping', $dataValues);
        }
        return $return;
    }
    
    public function saveCode($dataValues)
    {
        $return = null;
        if (count($dataValues) > 0)
        {
            if (array_key_exists('id', $dataValues))
            {
                $this->db->where('id', $dataValues['id']);
                $this->db->update('codes', $dataValues);

                $return = $dataValues['id'];
            }
            else
            {
                $this->db->insert('codes', $dataValues);
                $return = $this->db->insert_id();
            }
        }
        return $return;
    }
    
    
    public function getAllCodesAutoSuggest($term, $box_id = null, $code_ids_arr= array())
    {
        $this->db->select('codes.id, code, codes.description');
        $this->db->from('codes');
        
        $this->db->join('code_box_mapping', 'code_box_mapping.code_id = codes.id');
        $this->db->join('locations', 'locations.id = codes.location_id');
        $this->db->join('boxes', 'boxes.id = code_box_mapping.box_id');
        $this->db->group_by('codes.id');
        
        
        $this->db->where("(locations.name like '%$term%' or code like '%$term%' )");
        $this->db->order_by('code');
        
        if(!empty($box_id))
        {
            $this->db->join('code_box_location_qty_mapping', 'code_box_location_qty_mapping.code_id = codes.id');
            $this->db->where('box_id', $box_id);
        }
        
        if(!empty($code_ids_arr))
        {
            $this->db->where_not_in('codes.id', $code_ids_arr);
        }
        
        $query = $this->db->get();
        $result = $query->result_array();
        
        return $result;
    }
    
    
    public function getAllCodesByBoxLocation($location_id, $box_id)
    {
        $this->db->select('codes.id, code, description');
        $this->db->from('codes');
        $this->db->join('code_box_mapping', 'code_box_mapping.code_id = codes.id');
        $this->db->where("code_box_mapping.box_id", $box_id);
        $this->db->where("codes.location_id", $location_id);
        $this->db->order_by('code');
        
        $query = $this->db->get();
        $result = $query->result_array();
        //p($this->db->last_query());
        return $result;
    }
    
    
    public function getKabupatensByLocationId($location_id)
    {
        $this->db->select('*');
        $this->db->from('kabupatens');
        $this->db->where("kabupatens.location_id", $location_id);
        $this->db->order_by('name');
        
        $query = $this->db->get();
        $result = $query->result_array();
        
        return $result;
    }
    
    public function getAllKabupatens($location_id, $pagingParams = array())
    {

        $this->db->select('id, name');
        $this->db->from('kabupatens');
        $this->db->where('location_id', $location_id);

        if (isset($pagingParams['sSearch']))
        {
            $search = $pagingParams['sSearch'];
            if (!empty($search))
            {
                $this->db->like('name', $search);
            }
        }
        if (!empty($pagingParams['iSortCol_0']))
        {
            if (empty($pagingParams['sSortDir_0']))
            {
                $pagingParams['sSortDir_0'] = 'desc';
            }
            $column_arr = array('name');
            switch ($pagingParams['sSortDir_0'])
            {
                default:
                    $this->db->order_by($column_arr[$pagingParams['iSortCol_0'] + 1], $pagingParams['sSortDir_0']);
                    break;
            }
        }
        else
        {
            $this->db->order_by('name');
        }
        if (isset($pagingParams['iDisplayStart']) && $pagingParams['iDisplayLength'] != '-1')
        {
            $start = $pagingParams['iDisplayStart'];
            $offset = $pagingParams['iDisplayLength'];
            $this->db->limit($offset, $start);
        }
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function getKabupatenCount($location_id)
    {
        $count = '0';
        $this->db->select('count("id") as count');
        $this->db->where('location_id', $location_id);
        $query = $this->db->get('kabupatens')->row();
        $count = $query->count;
        return $count;
    }

    public function getKabupatenById($id)
    {
        $result = Null;
        if (!empty($id))
        {
            $this->db->select('*');
            $this->db->where('id', $id);
            $this->db->from('kabupatens');
            $result = $this->db->get()->row();
        }
        return $result;
    }

    public function getShipmentBatchById($id, $box_mapping_info = false)
    {
        $result = Null;
        if (!empty($id))
        {
            $this->db->select('*');
            $this->db->where('shipment_batches.id', $id);
            $this->db->from('shipment_batches');
            
            
            if (!empty($box_mapping_info))
            {
                $this->db->select('group_concat(boxes.name ORDER BY shipment_batch_box_mapping.id SEPARATOR "@@##@@") as box_names, '
                . 'group_concat(boxes.id ORDER BY shipment_batch_box_mapping.id SEPARATOR "@@##@@") as box_ids, '
                . 'group_concat(scanned_quantity ORDER BY shipment_batch_box_mapping.id SEPARATOR "@@##@@") as scanned_quantities, '
                . 'group_concat(shipment_batch_box_mapping.id ORDER BY shipment_batch_box_mapping.id SEPARATOR "@@##@@") as mapping_ids, '
                . 'group_concat(shipment_batch_box_mapping.quantity ORDER BY shipment_batch_box_mapping.id SEPARATOR "@@##@@") as quantities');
                $this->db->join('shipment_batch_box_mapping', 'shipment_batch_box_mapping.shipment_batch_id = shipment_batches.id', 'left');
                $this->db->join('boxes', 'shipment_batch_box_mapping.box_id = boxes.id', 'left');
                $this->db->order_by('batch_name');
                $this->db->group_by('shipment_batches.id');
            }
            
            $result = $this->db->get()->row();
        }
        return $result;
    }

    public function getCurrentShipmentBatchDetails()
    {
        $this->db->_protect_identifiers = false;

        $this->db->select('shipment_batches.id, batch_name, '
                . 'group_concat(boxes.name ORDER BY shipment_batch_box_mapping.id ASC SEPARATOR "@@##@@") as box_names, '
                . 'group_concat(shipment_batch_box_mapping.box_id ORDER BY shipment_batch_box_mapping.id ASC SEPARATOR "@@##@@") as box_ids, '
                . 'group_concat(scanned_quantity ORDER BY shipment_batch_box_mapping.id ASC SEPARATOR "@@##@@") as scanned_quantities, '
                . 'group_concat(shipment_batch_box_mapping.id ORDER BY shipment_batch_box_mapping.id ASC SEPARATOR "@@##@@") as mapping_ids, '
                . 'group_concat(shipment_batch_box_mapping.id ORDER BY shipment_batch_box_mapping.id ASC SEPARATOR "@@##@@") as mapping_ids, '
                . 'group_concat(shipment_batch_box_mapping.quantity ORDER BY shipment_batch_box_mapping.id ASC SEPARATOR "@@##@@") as quantities');
        $this->db->where('status', 'yes');
        $this->db->from('shipment_batches');
        $this->db->join('shipment_batch_box_mapping', 'shipment_batch_box_mapping.shipment_batch_id = shipment_batches.id', 'left');
        $this->db->join('boxes', 'shipment_batch_box_mapping.box_id = boxes.id', 'left');
        $this->db->order_by('CONVERT(batch_name, DECIMAL) asc');
        $this->db->group_by('shipment_batches.id');
        $this->db->limit(1);
        $result = $this->db->get()->row_array();

        return $result;
    }

    public function saveKabupaten($dataValues)
    {
        $return = null;
        if (count($dataValues) > 0)
        {
            if (array_key_exists('id', $dataValues))
            {
                $this->db->where('id', $dataValues['id']);
                $this->db->update('kabupatens', $dataValues);

                $return = $dataValues['id'];
            }
            else
            {
                $this->db->insert('kabupatens', $dataValues);
                $return = $this->db->insert_id();
            }
        }
        return $return;
    }

    public function saveShipmentBatch($dataValues)
    {
        $return = null;
        if (count($dataValues) > 0)
        {
            if (array_key_exists('id', $dataValues))
            {
                $this->db->where('id', $dataValues['id']);
                $this->db->update('shipment_batches', $dataValues);

                $return = $dataValues['id'];
            }
            else
            {
                $this->db->insert('shipment_batches', $dataValues);
                $return = $this->db->insert_id();
            }
        }
        return $return;
    }

    public function deleteShipmentBatchById($shipmentBatchId)
    {
        $this->db->delete('shipment_batches', array('id' => $shipmentBatchId));
    }
    

    public function deleteKabupatenById($id)
    {
        $this->db->delete('kabupatens', array('id' => $id));
    }
    
    public function getAllShipmentBatches($pagingParams = array())
    {
        $this->db->select("shipment_batches.*, shipment_batches.batch_name as batch, count(DISTINCT orders.id) as orders_count, "
                . "sum(order_trans.quantity) as boxes_count"
                . "", FALSE);
        $this->db->from('shipment_batches');
        $this->db->join('orders', 'shipment_batches.id = orders.shipment_batch_id', 'left');
        $this->db->join('order_trans', 'orders.id = order_trans.order_id', 'left');
        $this->db->group_by('shipment_batches.id');
        
        if (!empty($pagingParams['search_shipment_batch_status']))
        {
            $this->db->where('shipment_batches.status', $pagingParams['search_shipment_batch_status']);
        }
        else
        {
            // hacck , to be corrected
            $this->db->where('shipment_batches.status', 'yes');
        }
        
        if (isset($pagingParams['sSearch']))
        {
            $search = $pagingParams['sSearch'];
            if (!empty($search))
            {
                $this->db->like('shipment_batches.batch_name', $search);
            }
        }
        
        if (isset($pagingParams['iSortCol_0']))
        {
//            echo $pagingParams['sSortDir_0'];
            if (empty($pagingParams['sSortDir_0']))
            {
                $pagingParams['sSortDir_0'] = 'asc';
            }
           
            $column_arr = array('load_date','batch_name','booking_confirmation','container_type','quantity','vessel_name','voyage_number','eta','ship_onboard','bl_number','orders_count');
            switch ($pagingParams['sSortDir_0'])
            {
                default:
                    $this->db->order_by($column_arr[$pagingParams['iSortCol_0']],$pagingParams['sSortDir_0']);
                    break;
            }
        }
        else
        {
            $this->db->order_by('shipment_batches.id  desc');
        }
        
        if (isset($pagingParams['iDisplayStart']) && $pagingParams['iDisplayLength'] != '-1')
        {
            $start = $pagingParams['iDisplayStart'];
            $offset = $pagingParams['iDisplayLength'];
           
            $return = $this->getWithCount(null, $offset, $start);
//            p($this->db->last_query());
            return $return;
        }
        else
        {
            $return = $this->db->get();
            return $return->result_array();
        }
    }
    
    
      public function getAllUsers($pagingParams = array())
    {

        $this->db->select('user.id, name, username, email, RoleName');
        $this->db->from('user');
        $this->db->join('role','user.roleId = role.id','left');

        if (isset($pagingParams['sSearch']))
        {
            $search = $pagingParams['sSearch'];
            if (!empty($search))
            {
                $this->db->like('username', $search);
                $this->db->or_like('email', $search);
                $this->db->or_like('RoleName', $search);
            }
        }
        if (!empty($pagingParams['iSortCol_0']))
        {
            if (empty($pagingParams['sSortDir_0']))
            {
                $pagingParams['sSortDir_0'] = 'desc';
            }
            $column_arr = array('username', 'email', 'RoleName');
            switch ($pagingParams['sSortDir_0'])
            {
                default:
                    $this->db->order_by($column_arr[$pagingParams['iSortCol_0'] + 1], $pagingParams['sSortDir_0']);
                    break;
            }
        }
        else
        {
            $this->db->order_by('username');
        }
        if (isset($pagingParams['iDisplayStart']) && $pagingParams['iDisplayLength'] != '-1')
        {
            $start = $pagingParams['iDisplayStart'];
            $offset = $pagingParams['iDisplayLength'];
            $this->db->limit($offset, $start);
        }
        $this->db->where('active','yes');
        $this->db->where('roleId !=','7');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function getUserCount()
    {
        $count = '0';
        $this->db->select('count("id") as count');
         $this->db->where('active','yes');
        $this->db->where('roleId !=','7');
        $query = $this->db->get('user')->row();
        $count = $query->count;
        return $count;
    }

    public function getUserById($id)
    {
        $result = Null;
        if (!empty($id))
        {
            $this->db->select('user.*,role.RoleName');
            $this->db->where('user.id', $id);
            $this->db->from('user');
            $this->db->join('role','user.roleId = role.id','left');
            $result = $this->db->get()->row();
        }
        return $result;
    }

    public function saveUser($dataValues)
    {
        $return = null;
        if (count($dataValues) > 0)
        {
            if (array_key_exists('id', $dataValues))
            {
                $this->db->where('id', $dataValues['id']);
                $this->db->update('user', $dataValues);

                $return = $dataValues['id'];
            }
            else
            {
                $this->db->insert('user', $dataValues);
                $return = $this->db->insert_id();
            }
        }
        return $return;
    }

    public function deleteUserById($boxId)
    {
        $this->db->delete('user', array('id' => $boxId));
    }
    
    public function getAllRoles()
    {       
        $this->db->select('id,RoleName');
        $this->db->from('role');
        $this->db->where('id != ','7');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }
    
    public function getAllBoxesArr()
    {
        $result = array();
        $this->db->select('orders.id,box_id,boxes.name ,SUM(quantity) AS total');
        $this->db->from('orders');
        $this->db->join('order_trans','orders.id = order_trans.order_id','left');
        $this->db->join('boxes','boxes.id = order_trans.box_id');
        $this->db->where('shipment_batch_id is null');
        $this->db->where('boxes.favourite','yes');
        $this->db->group_by('box_id');
        $query = $this->db->get();
//        p($this->db->last_query());
        $result = $query->result_array();
        return $result;
    }
    
    public function getAllNotFavouriteBoxes($favourite_flag = 'no')
    {
        $result = array();
        $this->db->select('id,name');
        $this->db->where('favourite',$favourite_flag);
        $query = $this->db->get('boxes');
        $result = $query->result_array();
        return $result;
    }
    
    public function saveShipmentBatchBoxMapping($dataValues)
    {
        $return = null;
        if (count($dataValues) > 0)
        {
            if (array_key_exists('id', $dataValues))
            {
                $this->db->where('id', $dataValues['id']);
                $this->db->update('shipment_batch_box_mapping', $dataValues);

                $return = $dataValues['id'];
            }
            else
            {
                $this->db->insert('shipment_batch_box_mapping', $dataValues);
                $return = $this->db->insert_id();
            }
        }
//        echo $this->db->last_query();exit;
        return $return;
    }
    
    public function isShipmentBatchBoxAMapping($shipment_batch_id)
    {
        $return = false;
        $this->db->select('count(id) as cnt');
        $this->db->where('shipment_batch_id',$shipment_batch_id);
        $query = $this->db->get('shipment_batch_box_mapping')->row();
        $cnt = $query->cnt;
        if($cnt > 0)
        {
            $return = true;
        }
        return $return;
        
    }
    
    public function getAllShipmentBoxMapping($shipment_batch_id)
    {
        $return = array();
        
        $this->db->select('b.name,shipment_box.quantity as total,box_id,scanned_quantity');
        $this->db->from('shipment_batch_box_mapping as shipment_box');
        $this->db->join('boxes as b','shipment_box.box_id = b.id','left');
        $this->db->where('shipment_batch_id',$shipment_batch_id);
        
        $query = $this->db->get();
        $return = $query->result_array();
        
        return $return;
    }
    
    public function getShipmentBoxMappingRecord($box_id, $shipment_batch_id)
    {
        $result = null;
        
        $this->db->select('*');
        $this->db->where('box_id', $box_id);
        $this->db->where('shipment_batch_id', $shipment_batch_id);
        $result = $this->db->get('shipment_batch_box_mapping')->row();
        
        return $result;
    }
    
    public function getShipmentBatchStatusByScannedCount($shipment_batch_id)
    {
        $this->db->from('shipment_batch_box_mapping');
        $this->db->where('shipment_batch_id', $shipment_batch_id);
        $this->db->where('scanned_quantity < shipment_batch_box_mapping.quantity');
        
        $query = $this->db->get();
        $return = $query->result_array();
        
        return $return;
    }
    
    public function getAllOrderFollowupComments()
    {
        $return = array();
        $this->db->select('comments');
        $this->db->order_by('order_no');
        $query = $this->db->get('order_followup_comments');
        $return = $query->result_array();
        return $return;
        
    }
    
    public function getShipmentBatchArr($order_direction = 'asc')
    {
        $return = array();
        $this->db->select('shipment_batches.`id` AS shipment_id, shipment_batches.batch_name');
//        $this->db->where('status','yes');
        $this->db->order_by("created_at $order_direction");
        $query = $this->db->get('shipment_batches');
        $return = $query->result_array();
        return $return;        
    }
    
    public function getPromotionBoxesByBoxId($box_id)
    { 
        $current_date = date("Y-m-d");
        $return = array();
        $this->db->select("promotion.*,GROUP_CONCAT('',`promotion_box_trans`.box_id` ) as box_id,GROUP_CONCAT('', `boxes.name` ) as box_name,promotion.name"); 
        $this->db->join('boxes', 'boxes.id = promotion_box_trans.box_id','left');
        $this->db->join('promotion', 'promotion.id = promotion_box_trans.promotion_id','left');
        $this->db->where_in('boxes.id', $box_id);
        $this->db->where('is_active','yes'); 
        $this->db->where('promotion.date_from <=',"$current_date");
        $this->db->where('promotion.date_to >=',"$current_date"); 
        $this->db->group_by("promotion.id");
        $this->db->order_by("created_at");
        $query = $this->db->get('promotion_box_trans');
        $return = $query->result_array();
        return $return;      
    }
    
    public function getAllPromoBoxesByBoxid($promotion_id)
    {
        $current_date = date("Y-m-d");
        $return = array();
        $this->db->select("promotion.*,GROUP_CONCAT('',`promotion_box_trans`.box_id` ) as box_id,GROUP_CONCAT('', `boxes.name` ) as box_name,promotion.name"); 
        $this->db->join('promotion_box_trans', 'promotion_box_trans.promotion_id = promotion.id','left');
        $this->db->join('boxes', 'boxes.id = promotion_box_trans.box_id','left');
        $this->db->where('promotion.id', $promotion_id);
        $this->db->where('promotion.date_from <=',"$current_date");
        $this->db->where('promotion.date_to >=',"$current_date");
        $query = $this->db->get('promotion');
        $return = $query->row_array();
        return $return;
    }
    
    public function getAllPromoCodes()
    { 
        $this->db->select('*');
        $this->db->from('promotion');
        $this->db->order_by('name');
        $query = $this->db->get(); 
        $result = $query->result_array();
        return $result;
    }
    //pass type
    public function getPassTypeCount()
    {
        $count = '0';
        $this->db->select('count("pass_type_id") as count');
        $query = $this->db->get('pass_type')->row();
        $count = $query->count;
        return $count;
    }
    
    
    public function getAllPassType($pagingParams = array())
    {

        $this->db->select('*');
        $this->db->from('pass_type');

        if (isset($pagingParams['sSearch']))
        {
            $search = $pagingParams['sSearch'];
            if (!empty($search))
            {
                $this->db->like('pass_type', $search);
            }
        }
        if (!empty($pagingParams['iSortCol_0']))
        {
            if (empty($pagingParams['sSortDir_0']))
            {
                $pagingParams['sSortDir_0'] = 'desc';
            }
            $column_arr = array('pass_type');
            switch ($pagingParams['sSortDir_0'])
            {
                default:
                    $this->db->order_by($column_arr[$pagingParams['iSortCol_0'] + 1], $pagingParams['sSortDir_0']);
                    break;
            }
        }
        else
        {
            $this->db->order_by('pass_type');
        }
        if (isset($pagingParams['iDisplayStart']) && $pagingParams['iDisplayLength'] != '-1')
        {
            $start = $pagingParams['iDisplayStart'];
            $offset = $pagingParams['iDisplayLength'];
            $this->db->limit($offset, $start);
        }
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

     public function getPassTypeById($id)
    {
        $result = Null;
        if (!empty($id))
        {
            $this->db->select('*');
            $this->db->where('pass_type_id', $id);
            $this->db->from('pass_type');
            $result = $this->db->get()->row();
        }
        return $result;
    }
    
     public function savePassType($dataValues)
    {
        $return = null;
        if (count($dataValues) > 0)
        {
            if (array_key_exists('pass_type_id', $dataValues))
            {
                $this->db->where('pass_type_id', $dataValues['pass_type_id']);
                $this->db->update('pass_type', $dataValues);

                $return = $dataValues['pass_type_id'];
            }
            else
            {
                $this->db->insert('pass_type', $dataValues);
                $return = $this->db->insert_id();
            }
        }
        return $return;
    }
    
//    public function getPassDeleteById($id)
//    {
//        $result = Null;
//        if (!empty($id))
//        {
//            $this->db->select('*');
//            $this->db->where('pass_type_id', $id);
//            $this->db->from('customer_type');
//            $result = $this->db->get()->row();
//        }
//        return $result;
//    }
    
    public function deletePassTypeById($id)
    {
       $this->db->delete('pass_type', array('pass_type_id' => $id));
    }
    
    //customer type 
     public function getCustomerTypeCount()
    {
        $count = '0';
        $this->db->select('count("customer_type_id") as count');
        $query = $this->db->get('customer_type')->row();
        $count = $query->count;
        return $count;
    }
    
    public function getAllCustomerType($pagingParams = array())
    {

        $this->db->select('*,pass_type.pass_type');
        $this->db->from('customer_type'); 
        $this->db->join('pass_type','pass_type.pass_type_id = customer_type.pass_type_id','left');

        if (isset($pagingParams['sSearch']))
        {
            $search = $pagingParams['sSearch'];
            if (!empty($search))
            {
                $this->db->like('pass_type.pass_type', $search);
                $this->db->like('customer_type.customer_type', $search);
            }
        }
        if (!empty($pagingParams['iSortCol_0']))
        {
            if (empty($pagingParams['sSortDir_0']))
            {
                $pagingParams['sSortDir_0'] = 'desc';
            }
            $column_arr = array('pass_type');
            switch ($pagingParams['sSortDir_0'])
            {
                default:
                    $this->db->order_by($column_arr[$pagingParams['iSortCol_0'] + 1], $pagingParams['sSortDir_0']);
                    break;
            }
        }
        else
        {
            $this->db->order_by('customer_type');
        }
        if (isset($pagingParams['iDisplayStart']) && $pagingParams['iDisplayLength'] != '-1')
        {
            $start = $pagingParams['iDisplayStart'];
            $offset = $pagingParams['iDisplayLength'];
            $this->db->limit($offset, $start);
        }
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }
    
     public function saveCustomerType($dataValues)
    { 
        $return = null;
        if (count($dataValues) > 0)
        {
            if (array_key_exists('customer_type_id', $dataValues))
            {
                $this->db->where('customer_type_id', $dataValues['customer_type_id']);
                $this->db->update('customer_type', $dataValues);
                $return = $dataValues['customer_type_id'];
            }
            else
            {
                $this->db->insert('customer_type', $dataValues);
                $return = $this->db->insert_id();
            }
        }
        return $return;
    }
    
     public function getCustomerTypeById($id)
    {
        $result = Null;
        if (!empty($id))
        {
            $this->db->select('customer_type.*, pass_type.pass_type, pass_type.pass_type_id');
            $this->db->from('customer_type'); 
            $this->db->join('pass_type','pass_type.pass_type_id = customer_type.pass_type_id','left');
            $this->db->where('customer_type.customer_type_id', $id);
            $result = $this->db->get()->row();
        }
        return $result;
    }
    
     public function deleteCustomerTypeById($boxId)
    {
        $this->db->delete('customer_type', array('customer_type_id' => $boxId));
    }
    
    
    //Categories 
    public function getCategoriesCount()
    {
        $count = '0';
        $this->db->select('count("category_id") as count');
        $query = $this->db->get('categories')->row();
        $count = $query->count;
        return $count;
    }
    
    
    public function getAllCategories($pagingParams = array())
    {

        $this->db->select('*');
        $this->db->from('categories');

        if (isset($pagingParams['sSearch']))
        {
            $search = $pagingParams['sSearch'];
            if (!empty($search))
            {
                $this->db->like('category', $search);
            }
        }
        if (!empty($pagingParams['iSortCol_0']))
        {
            if (empty($pagingParams['sSortDir_0']))
            {
                $pagingParams['sSortDir_0'] = 'desc';
            }
            $column_arr = array('pass_type');
            switch ($pagingParams['sSortDir_0'])
            {
                default:
                    $this->db->order_by($column_arr[$pagingParams['iSortCol_0'] + 1], $pagingParams['sSortDir_0']);
                    break;
            }
        }
        else
        {
            $this->db->order_by('category');
        }
        if (isset($pagingParams['iDisplayStart']) && $pagingParams['iDisplayLength'] != '-1')
        {
            $start = $pagingParams['iDisplayStart'];
            $offset = $pagingParams['iDisplayLength'];
            $this->db->limit($offset, $start);
        }
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

     public function getCategoryById($id)
    {
        $result = Null;
        if (!empty($id))
        {
            $this->db->select('*');
            $this->db->where('category_id', $id);
            $this->db->from('categories');
            $result = $this->db->get()->row();
        }
        return $result;
    }
    
     public function saveCategory($dataValues)
    {
        $return = null;
        if (count($dataValues) > 0)
        {
            if (array_key_exists('category_id', $dataValues))
            {
                $this->db->where('category_id', $dataValues['category_id']);
                $this->db->update('categories', $dataValues);

                $return = $dataValues['category_id'];
            }
            else
            {
                $this->db->insert('categories', $dataValues);
                $return = $this->db->insert_id();
            }
        }
        return $return;
    }
    
     public function deleteCategoryById($boxId)
    {
        $this->db->delete('categories', array('category_id' => $boxId));
    }
    
    //media type 
     public function getMediaTypeCount()
    {
        $count = '0';
        $this->db->select('count("media_type_id ") as count');
        $query = $this->db->get('media_type')->row();
        $count = $query->count;
        return $count;
    }
    
    public function getAllMediaType($pagingParams = array())
    {
        $this->db->select('*,categories.category');
        $this->db->from('media_type'); 
        $this->db->join('categories','categories.category_id = media_type.category_id','left');

        if (isset($pagingParams['sSearch']))
        {
            $search = $pagingParams['sSearch'];
            if (!empty($search))
            {
                $this->db->like('categories.category', $search);
                $this->db->like('media_type.media_type', $search);
            }
        }
        if (!empty($pagingParams['iSortCol_0']))
        {
            if (empty($pagingParams['sSortDir_0']))
            {
                $pagingParams['sSortDir_0'] = 'desc';
            }
            $column_arr = array('category');
            switch ($pagingParams['sSortDir_0'])
            {
                default:
                    $this->db->order_by($column_arr[$pagingParams['iSortCol_0'] + 1], $pagingParams['sSortDir_0']);
                    break;
            }
        }
        else
        {
            $this->db->order_by('media_type');
        }
        if (isset($pagingParams['iDisplayStart']) && $pagingParams['iDisplayLength'] != '-1')
        {
            $start = $pagingParams['iDisplayStart'];
            $offset = $pagingParams['iDisplayLength'];
            $this->db->limit($offset, $start);
        }
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }
    
     public function saveMediaType($dataValues)
    { 
        $return = null;
          if (count($dataValues) > 0)
        {
            if (array_key_exists('media_type_id', $dataValues))
            {
                $this->db->where('media_type_id', $dataValues['media_type_id']);
                $this->db->update('media_type', $dataValues);
                $return = $dataValues['media_type_id'];
            }
            else
            {
                $this->db->insert('media_type', $dataValues);
                $return = $this->db->insert_id();
            }
        } //p($dataValues);
        return $return;
    }
    
     public function getMediaTypeById($id)
    {
        $result = Null;
        if (!empty($id))
        {
            $this->db->select('media_type.*, categories.category, categories.category_id');
            $this->db->from('media_type'); 
            $this->db->join('categories','categories.category_id = media_type.category_id','left');
            $this->db->where('media_type.media_type_id', $id);
            $result = $this->db->get()->row();
        }
        
        return $result;
    }
    
     public function deleteMediaTypeById($id)
    {
        $this->db->delete('media_type', array('media_type_id' => $id));
    }
}

?>
