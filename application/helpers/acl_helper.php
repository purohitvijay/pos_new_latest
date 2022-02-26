<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    function getLink($linkArray) {
        $path = $linkArray['path'];
        $type = $linkArray['type'];
        $uniqueId = "";
        if(isset($linkArray['uniqueId']))
        {
            $uniqueId = $linkArray['uniqueId'];
        }
        //load CI instance
        $_CI = & get_instance();
        //get site identity
        $siteIdentity = $_CI->session->userdata['siteIdentity'];
        //check path
        $isAllowed = checkPathAllowed($path);
        
        if(!empty($isAllowed))
        {
            $linkIcon =  _parseLinkCaption($type);
            $class = _parseLinkClass($type);
            //set link href
            if(!empty($uniqueId))
            {
                $path = str_replace('$1', $uniqueId, $path);
            }
            
            $linkHref = base_url().$siteIdentity."/".$path;           
           
            //check if delete link set confirm box
            if($type == "DELETE")
            {
                $return  = "<a href='".$linkHref."' class='". $class."' onClick=\"javascript:return confirm('Are you sure you want to delete?');\" >".$linkIcon."</a>";
            }
            else
            {
                $return = "<a href='".$linkHref."' class='".$class."'>".$linkIcon."</a>";
            }
        }
        else
        {
            $return = "";
        }
        return $return;
        
    } 
    
    function checkPathAllowed($path)
    {
        $isAllowed = NULL;
        $_CI = & get_instance();
        //load common library
        $_CI->load->library('commonlibrary');
        $pathModule = "admin/" . $path;
        //loggedin role id
        $roleId = $_CI->session->userdata['roleId'];        
        
        
        //check path in permission
        //get permission Id on path based
        $permissionArr = $_CI->commonlibrary->checkAlwaysAllowPermission($pathModule);
        if (!empty($permissionArr)) {
            $permissionId = $permissionArr['permissionId'];
            //check permission for role
            $isAllowed = $_CI->commonlibrary->checkPermission($permissionId,$roleId);
            
        }
        
        return $isAllowed;
    }
    
    function _parseLinkCaption($type)
    {
        $operation = $type;

            switch ($operation)
            {
                case 'VIEW':
                    $return = 'VIEW';
                    break;
                 case 'VIEWLIST':
                    $return = "<i class='icon-list'></i>";
                    break;
                case 'EDIT':
                    $return = "<i class='fa fa-edit'></i>"; 
                    break;
                case 'DELETE':
                    $return = "<i class='icon-trash'></i>";
                    break; 
                case 'ADD' :
                    $return = mlLang('lblAddNewBtn');
                    break;
            }

            return $return;
    }
    
    function _parseLinkClass($type)
    {
        $operation = $type;

            switch ($operation)
            {
                case 'VIEW':
                    $return = 'VIEW';
                    break;
                case 'VIEWLIST':
                    $return = 'LIST';
                    break;
                case 'EDIT':
                    $return = ""; 
                    break;
                case 'DELETE':
                    $return = "";
                    break; 
                case 'ADD' :
                    $return = "btn default";
                    break;
            }

            return $return;
    }