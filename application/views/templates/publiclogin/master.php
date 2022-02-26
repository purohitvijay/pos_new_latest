<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<!DOCTYPE html>
<!-- 
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.0
Version: 1.5
Author: KeenThemes
Website: http://www.keenthemes.com/
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
-->
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
   <meta charset="utf-8" />
   <title><?php echo $template_title;?></title>
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta content="width=device-width, initial-scale=1.0" name="viewport" />
   <meta content="" name="description" />
   <meta content="" name="author" />
   <meta name="MobileOptimized" content="320">
   <!-- BEGIN GLOBAL MANDATORY STYLES -->          
   <link href="<?php echo base_url();?>assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
   <link href="<?php echo base_url();?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
   <link href="<?php echo base_url();?>assets/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
   <link href="<?php echo base_url();?>assets/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.css" rel="stylesheet" type="text/css"/>
   
   <!-- END PAGE LEVEL PLUGIN STYLES -->
   <!-- BEGIN THEME STYLES --> 
   <link href="<?php echo base_url();?>assets/css/style-metronic.css" rel="stylesheet" type="text/css"/>
   <link href="<?php echo base_url();?>assets/css/style.css" rel="stylesheet" type="text/css"/>
   <link href="<?php echo base_url();?>assets/css/style-responsive.css" rel="stylesheet" type="text/css"/>
   <link href="<?php echo base_url();?>assets/css/plugins.css" rel="stylesheet" type="text/css"/>
   <link href="<?php echo base_url();?>assets/css/pages/tasks.css" rel="stylesheet" type="text/css"/>
   <link href="<?php echo base_url();?>assets/css/themes/default.css" rel="stylesheet" type="text/css" id="style_color"/>
   <link href="<?php echo base_url();?>assets/css/custom.css" rel="stylesheet" type="text/css"/>
   <!-- END THEME STYLES -->
   <link rel="shortcut icon" href="favicon.ico" />
   
    
   <script src="<?php echo base_url();?>assets/plugins/jquery-1.10.2.min.js" type="text/javascript"></script>
   
   <script src="<?php echo base_url();?>assets/plugins/uniform/jquery.uniform.min.js" type="text/javascript" ></script>
   <script src="<?php echo base_url();?>assets/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
   <script src="<?php echo base_url();?>assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
   <script src="<?php echo base_url();?>assets/plugins/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js" type="text/javascript" ></script>
   <script src="<?php echo base_url();?>assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
   <script src="<?php echo base_url();?>assets/plugins/jquery.blockui.min.js" type="text/javascript"></script>  
   <script src="<?php echo base_url();?>assets/plugins/jquery.cookie.min.js" type="text/javascript"></script>
   
   
   <script src="<?php echo base_url();?>assets/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>   
   <!-- IMPORTANT! Load jquery-ui-1.10.3.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
   
  
   <!-- END PAGE LEVEL PLUGINS -->
   <!-- BEGIN PAGE LEVEL SCRIPTS -->
   <script src="<?php echo base_url();?>assets/scripts/app.js" type="text/javascript"></script>
   <script src="<?php echo base_url();?>assets/scripts/tasks.js" type="text/javascript"></script>        
   <!-- END PAGE LEVEL SCRIPTS -->  
   <script>
      jQuery(document).ready(function() {    
         App.init(); // initlayout and core plugins
      });
   </script>
</head>
<!-- END HEAD -->

<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="page-header-fixed">
    <?php echo $template_header; ?>
    <?php echo $template_sidebar; ?>
    <?php echo $template_content; ?>
    <?php echo $template_footer; ?>
</html>