<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_Status extends CI_Migration {
        public $_table_name = 'order_status_trans';
        public function up()
        {
p("5100 in");
//            ###################LATEST CODE START###################33
            $this->db->select('*');
            $this->db->from('orders');
            $this->db->join('order_status_trans','orders.id = order_status_trans.order_id','inner');
            $this->db->where('order_status_trans.status','received_at_jakarta_warehouse');
//            $this->db->where("order_status_trans.updated_at < '2017-02-01 00:00:00'");
            $this->db->where('order_status_trans.active','yes');
        
            $query = $this->db->get();
            while($order_row = $query->_fetch_object())
            {
              $time = date('Y-m-d H:i:s');
              $orders_data = array(
              'created_at' => $order_row->created_at,
              'order_id' => $order_row->order_id
              );
              $time = date('Y-m-d H:i:s');
              $order_id   = $orders_data['order_id'];
              $created_at = $orders_data['created_at'];

              $Pic_not_taken = 'delivered_at_jkt_picture_not_taken';
              $addPic_not_taken = array('status' => $Pic_not_taken,'order_id' => $order_id,'active' => 'yes','employee_id' => '2','responsibility_completed' => 'no','created_at' => $created_at,'updated_at' => $time);
              $this->db->insert($this->_table_name,$addPic_not_taken);

              $this->db->where('status','received_at_jakarta_warehouse');
              $this->db->where('order_id',$order_id);
              $update_inactive_status = array('active' =>'no','responsibility_completed'=>'yes');
              $this->db->update($this->_table_name,$update_inactive_status);


              $this->db->select('*');
              $this->db->from('order_image_trans');
              $this->db->where('order_id',$order_id);
              $this->db->group_by('order_id');
              $this->db->order_by('order_id');
              $query_img = $this->db->get();
         
              $img_row = $query_img->row_array();

              if($img_row)
              {
                $time = date('Y-m-d H:i:s');
                $img_orderid = $img_row['order_id'];
                if($order_id == $img_orderid)
                {
                  $Pic_taken = 'delivered_at_jkt_picture_taken';
                  $addPic_taken = array('status' => $Pic_taken, 'order_id' => $order_id,'active' => 'yes','employee_id' => '2','responsibility_completed' => 'no','created_at' =>$created_at, 'updated_at' => $time);
                  $this->db->insert($this->_table_name,$addPic_taken);

                  $this->db->where('status','delivered_at_jkt_picture_not_taken');
                  $this->db->where('order_id',$order_id);
                  $update_active_status = array('active' =>'no','responsibility_completed'=>'yes');
                  $this->db->update($this->_table_name,$update_active_status);
                }
               }
            }
   


            ###################LATEST CODE END###################33 
              
    }
 
}
 
