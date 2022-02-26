<?php
    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class LanguageModel extends My_Model
    {

        public function __construct()
        {
            parent::__construct();
        }
        
        public function getSiteLanguage($siteUrlIdentity)                
        {
           $siteLanguage = "English";
           $this->db->select('language');
           $this->db->from('user as u');
//           $this->db->join('businessMaster as bm','u.businessId = bm.id','left');
           $this->db->join('language as l','u.languageId = l.id','left');
           $this->db->where('siteIdentity',$siteUrlIdentity);
           $query = $this->db->get();
           $result = $query->result_array();
           if(!empty($result))
           {          
            $siteLanguage = $result['0']['language'];
           }
           return $siteLanguage;
        }
        
        public function getLanguageArr()
        {
            $result = array();
            $this->db->select('id,language');
            $query = $this->db->get('language');
            $result = $query->result_array();
           
            return $result;
        }
    }