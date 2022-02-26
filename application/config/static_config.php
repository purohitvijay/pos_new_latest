<?php
if (!defined('BASEPATH'))
        exit('No direct script access allowed');
$config['static_server_1'] = '';

    $config['admin_default_css'] = array(
        'bootstrap' => array('name' => 'assets/css/bootstrap.min.css'),
        'bootstrap-responsive' => array('name' => 'assets/css/bootstrap-responsive.min.css'),
        'jquery-ui' => array('name' => 'assets/css/plugins/jquery-ui/smoothness/jquery-ui.css'),
        'jquery-ui-theme' => array('name' => 'assets/css/plugins/jquery-ui/smoothness/jquery.ui.theme.css'),
        'style' => array('name' => 'assets/css/style.css'),
        'theme' => array('name' => 'assets/css/themes.css'),
        'postki' => array('name' => 'assets/css/postki.css'),
        );
  
   
$config['admin_default_js'] = array(
   'jquery' => array('name' => 'assets/js/jquery.min.js'),
   'jquery-scroll' => array('name' => 'assets/js/plugins/nicescroll/jquery.nicescroll.min.js'),
   'jquery-ui' => array('name' => 'assets/js/plugins/jquery-ui/jquery.ui.core.min.js'),
   'jquery-ui-widget' => array('name' => 'assets/js/plugins/jquery-ui/jquery.ui.widget.min.js'),
   'jquery-ui-mouse' => array('name' => 'assets/js/plugins/jquery-ui/jquery.ui.mouse.min.js'),
   'jquery-ui-resize' => array('name' => 'assets/js/plugins/jquery-ui/jquery.ui.resizable.min.js'),
   'jquery-ui-sortable' => array('name' => 'assets/js/plugins/jquery-ui/jquery.ui.sortable.min.js'),
   'jquery-ui-slimscroll' => array('name' => 'assets/js/plugins/slimscroll/jquery.slimscroll.min.js'),
  
   'bootstrap' => array('name' => 'assets/js/bootstrap.min.js'),
   'jquery-form' => array('name' => 'assets/js/plugins/form/jquery.form.min.js'),
   'postki' => array('name' => 'assets/js/postki.js'),
   'jspdf' => array('name' => 'assets/js/plugins/jsPDF-master/jspdf.js'),
   'jsPdfHtml' => array('name' => 'assets/js/plugins/jsPDF-master/plugins/from_html.js'),
   'jsPdfTextSize' => array('name' => 'assets/js/plugins/jsPDF-master/plugins/split_text_to_size.js'),
   'jsPdfFont' => array('name' => 'assets/js/plugins/jsPDF-master/plugins/standard_fonts_metrics.js'),
   'jsCell' => array('name' => 'assets/js/plugins/jsPDF-master/plugins/cell.js'),
   'jsTable' => array('name' => 'assets/js/plugins/jsPDF-master/plugins/jspdf.plugin.autotable.js'),
   'jsTableMin' => array('name' => 'assets/js/plugins/jsPDF-master/plugins/jspdf.plugin.autotable.min.js'),
   'jsTableSrc' => array('name' => 'assets/js/plugins/jsPDF-master/plugins/jspdf.plugin.autotable.src.js'),
//   'eakroko' => array('name' => 'assets/js/eakroko.min.js'),
//   'application' => array('name' => 'assets/js/application.min.js'),
//   'demo-js' => array('name' => 'assets/js/demonstration.min.js'),
        
        );
	



$config['css_arr'] = array(
        'datatable' => array('name' => 'assets/css/plugins/datatable/jquery.dataTables.css'), 
        'multiselect' => array('name' => 'assets/css/jquery.multiselect.css'), 
        'multiselect_filter' => array('name' => 'assets/css/jquery.multiselect.filter.css'), 
        'bootstrap_date_picker' => array('name' => 'assets/css/plugins/datepicker/datepicker.css'),
        
        //Two column multiselect
        'select2' => array('name' => 'assets/css/plugins/select2/select2.css'), 
    
        'multiselect_slide' => array('name' => 'assets/css/plugins/multiselect/multi-select.css'),
    
        //icheck
        'icheck' => array('name' => 'assets/css/plugins/icheck/all.css'),
    
        //clock picker
        'clock-picker' => array('name' => 'assets/css/plugins/clock-picker/bootstrap-clockpicker.min.css'),  
    
        //toggle-switch
        'toggle-switch' => array('name' => 'assets/css/bootstrap-switch.min.css'),  
        // autocomplete token input
        'jquery.tokeninput' => array('name' => 'assets/css/token-input.css'),
        'jquery.tokeninput.facebook' => array('name' => 'assets/css/token-input-facebook.css'),
    );


$config['js_arr'] = array(
       'datatable' => array('name' => 'assets/js/plugins/datatable/jquery.dataTables.min.js'), 
       'multiselect' => array('name' => 'assets/js/jquery.multiselect.min.js'), 
       'multiselect_filter' => array('name' => 'assets/js/jquery.multiselect.filter.min.js'), 
       'counter_up' => array('name' => 'assets/js/jquery.counterup.min.js'), 
       'bootbox' => array('name' => 'assets/js/plugins/bootbox/jquery.bootbox.js'), 
       'bootstrap_date_picker' => array('name' => 'assets/js/plugins/datepicker/bootstrap-datepicker.js'), 
    
       'jquery_form' => array('name' => 'assets/js/plugins/form/jquery.form.min.js'), 
       'form_wizard' => array('name' => 'assets/js/plugins/wizard/jquery.form.wizard.min.js'), 
       'mockjax' => array('name' => 'assets/js/plugins/mockjax/jquery.mockjax.js'), 
       
        //sliding form
        'eakroko' => array('name' => 'assets/js/eakroko.min.js'), 
    
        //Two column multiselect
        'select2' => array('name' => 'assets/js/plugins/select2/select2.min.js'), 
    
        'multiselect_slide' => array('name' => 'assets/js/plugins/multiselect/jquery.multi-select.js'), 
    
        //validation
        'validation' => array('name' => 'assets/js/plugins/validation/jquery.validate.min.js'), 
        'validation_additional_methods' => array('name' => 'assets/js/plugins/validation/additional-methods.min.js'),     
    
        //icheck
        'icheck' => array('name' => 'assets/js/plugins/icheck/jquery.icheck.min.js'),     
    
        //jquery-ui-jquery-ui-1.11.2
        'jquery-ui-1.11.2' => array('name' => 'assets/js/jquery-ui-1.11.2.js'),     
    
        //clock picker
        'clock-picker' => array('name' => 'assets/js/plugins/clock-picker/bootstrap-clockpicker.min.js'),     
    
        //sparklines
        'sparklines' => array('name' => 'assets/js/plugins/sparklines/jquery.sparklines.min.js'),     

        //sparklines
        'hotkeys' => array('name' => 'assets/js/plugins/hotkeys/jquery.hotkeys.js'),     

        //toggle switch
        'toggle-switch' => array('name' => 'assets/js/bootstrap-switch.min.js'),    
        //autocomplete
         'jquery.tokeninput' => array('name' => 'assets/js/jquery.tokeninput.js'),
        
    );