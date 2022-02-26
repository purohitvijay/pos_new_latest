<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
         
        <script>
            
        var source = 'THE SOURCE';
         
        function start_task()
        {
            $('#results').modal('show');
            source = new EventSource('<?php echo base_url()."admin/miscellaneous/cleanImageData"?>');

            source.addEventListener('message' , function(e) 
            { 
                var result = JSON.parse( e.data );
                add_log(result.message);
                 
                document.getElementById('progressor').style.width = result.progress + "%";
                if(e.data.search('TERMINATE') != -1)
                {
                    add_log('Received TERMINATE closing');
                    source.close();
                }
            });
             
            source.addEventListener('error' , function(e)
            { 
               $('#results').modal('hide');
               source.close();
            });
        }
         
        function stop_task()
        { 
            source.close();
            add_log('Interrupted');
        }
         
        function add_log(message)
        {    
          var results_display = document.getElementById('results_display');
          results_display.innerHTML +="<b><span style='font-size:16px;'>" +message + '</span></b><br>';  
          results_display.scrollTop = results_display.scrollHeight;
          
          var result_popup = document.getElementById('result_data');
          result_popup.innerHTML = "<b><span style='font-size:16px;'>" +message + '</span></b><br>'; 
        } 
        </script>
    </head>
    <body>
        <div id="dsmain" class="page-content main_container_padding">
        <div class="row">
            <div class="col-sm-12">
            <div class="box box-color box-bordered">
            <div class="box-title"><h3>Deleting image files from database older than 6 months.</h3><br/><br/></div>
        
        <br/> 
        <div class="input-group">
            <input  class="btn-primary btn" type="button" onclick="start_task();"  value="Delete image data" /> 
            <input type="button" class="btn-primary btn" onclick="stop_task();"  value="Stop Deleting files" />
            <br />
            <br />
         </div> 
            <br />
            <div id="results_display"></div>
            
            
            <div id="results" class="modal fade bs-document-sign-modal-lg" tabindex="-1" role="dialog" aria-labelledby="ChangeRequestAskPasswordLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <div id ="result_data" class="modal-body">
                           
                        </div>
                      </div>
                </div>
            </div>
            <br />
         
            <div style="border:1px solid #ccc; width:300px; height:20px; overflow:auto; background:#eee;">
            <div id="progressor" style="background:#07c; width:0%; height:100%;"></div>
            </div>
            
            </div>
            </div>
        </div>
        </div>
    </body>
</html> 