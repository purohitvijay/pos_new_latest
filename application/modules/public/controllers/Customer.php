<?php if (!defined('BASEPATH'))  exit('No direct script access allowed');

class Customer extends MY_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->helper('url');

        $languages = array("en" => "english", "id" => "bahasa");

        if(empty($this->session->userdata["imglog_login"]))
        {
            redirect('imglog', 'refresh');
        }

        $lang = $this->uri->segment(2);

        if (isset($languages[$lang]))
            $this->lang->load('site', $languages[$lang]);
        else
            $this->lang->load('site', $this->config->item("language"));
    }

    public function index()
    {
        $temp["lang"] = $this->config->item("language");
        $this->load->setTemplate('blank');
        $this->load->view('/customer', $temp);
    }
    
    public function get_customer_by_mobile()
    {
        $get_data = $this->input->get();
        $this->load->model('admin/ordersmodel');
        
        $customer_data = $this->ordersmodel->get_customer_by_mobile_number($get_data["mobile"]);
        
        $result = array( 'status' => 'success', 'data' => $customer_data );
        echo json_encode($result);
    }
    
    public function customer_passport_update()
    {
        $post_data = $this->input->post();
        if(!isset($post_data["customer_id"]))
        {             
            $result = array( 'status' => 'error', 'msg' => "Mobile Invalid" );
            echo json_encode($result);exit;
        }
        
        $ext = pathinfo($_FILES["passport"]["name"], PATHINFO_EXTENSION);
        $passport = "customer_passport_".$post_data["customer_id"].".".$ext;
        
        if(file_exists('./assets/img/customer_passport/'.$passport))
        {
            unlink('./assets/img/customer_passport/'.$passport);
        }
        
        $configUpload['upload_path'] = './assets/img/customer_passport'; #the folder placed in the root of project
        $configUpload['allowed_types'] = '*'; #allowed types description
//        $configUpload['max_size'] = '1000'; #max size
//        $configUpload['max_width'] = '2048'; #max width
//        $configUpload['max_height'] = '1468'; #max height
        $configUpload['overwrite'] = true; #overwrite name of the uploaded file
        $configUpload['file_name'] = $passport; # save file name of the uploaded file
        $this->load->library('upload', $configUpload); #init the upload class
        
        if(!$this->upload->do_upload('passport'))
        {
            $uploadedDetails = $this->upload->display_errors();  
            $result = array( 'status' => 'error', 'msg' => $uploadedDetails );
            echo json_encode($result);exit;
        }
        else
        {
            $uploadedDetails = $this->upload->data(); 
        }
            $this->load->model('admin/ordersmodel');

            $data["passport_id_number"] = $post_data["id_number"];
            $data["passport_img"] = $passport;

            $customer = $this->ordersmodel->updateCustomerId_by_CustomerId($post_data["customer_id"],$data);

            $result = array( 'status' => 'success', 'msg' => "Update Successfully" , 'data' => $customer );
            echo json_encode($result);
    }
}
