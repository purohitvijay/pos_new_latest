<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
         
        <script>
            
        var source = 'THE SOURCE';
         
        function start_task()
        { 
            $('#results').modal('show'); 
            $('#result_data').html("<b><span style='font-size:16px;'>Database Backup Generation.</span></b><br>");
            
            $.ajax({
                url: "<?=base_url()?>admin/miscellaneous/dbBackupGenerate",
                type: "GET"
            })
            .done(function( response ) {   
                if(response == "success")
                { 
                    $('#result_data').html("<b><span style='font-size:16px;'>Delete BarCodes & Orderno images and Shipment batches and Receiving batches.</span></b><br>");
                    $.ajax({
                        url: "<?=base_url()?>admin/miscellaneous/deleteBarCodeOrderNoImage",
                        type:'GET', 
                        })
                        .done(function() {
                            $('#result_data').html("<b><span style='font-size:16px;'>Deletes Orders data from database.</span></b><br>");                   
                            source = new EventSource('<?php echo base_url()."admin/miscellaneous/oldOrdersData"?>');
                            $('#results').modal('hide');
                        
                            source.addEventListener('message' , function(e) 
                            { 
                                var result = JSON.parse( e.data ); 

                                var completed_order_number = result.order_number;
                                add_log(result.message, completed_order_number);

                                document.getElementById('progressor').style.width = result.progress + "%";
                                if(e.data.search('TERMINATE') != -1)
                                {
                                     add_log('Received TERMINATE closing');
                                     source.close();
                                }
                            });

                            source.addEventListener('error' , function(e)
                            { 
                                source.close();
                            });
                        });
                }
                else
                {
                    alert("There is a problem in Database backup generation.");
                }
          });
        }
         
        function stop_task()
        { 
            source.close();
            add_log('Interrupted');
        }
         
        function add_log(message, completed_order_number)
        {    
            $("."+completed_order_number+"_is_processed").html("yes");
        }  
        </script>
    </head>
    <body> 
        <div id="dsmain" class="page-content main_container_padding">
        <div class="row">
            <div class="col-sm-12">
            <div class="box box-color box-bordered">
            <div class="box-title"><h3>Delete Old Orders From Database</h3><br/><br/></div>
        
        <br/> 
        <div class="input-group">
            <input  class="btn-primary btn" type="button" onclick="start_task();"  value="Delete old orders" /> 
            <input type="button" class="btn-primary btn" onclick="stop_task();"  value="Stop Deleting files" />
            <br />
            <br />
            <?php
            if($orders_data)
            { ?>
                <div id="div_table_data">
                    <table id="menuTable" class="table_data table table-hover table-nomargin dataTable table-bordered dataTable-scroll-y" style="page-break-after: always">
                        <thead>
                            <tr>
                            <th>Order Number</th>
                            <th>Order Date</th>
                            <th>Pin</th>
                            <th>Unit</th>
                            <th>Block</th>
                            <th>Building</th>
                            <th>Street</th>
                            <th>Is Processed</th>
                            </tr>
                        </thead>
                        <tbody>    
                        <?php
                        foreach($orders_data as $idx => $val)
                        {
                        ?>
                         <tr>
                             <td class="order_number"><?= isset($val['order_number']) ? $val['order_number'] : '';?></td>
                             <td><?= isset($val['order_date']) ? date("d/m/Y h:i:s", strtotime($val['order_date'])) : ''; ?></td>
                             <td><?= isset($val['pin']) ? $val['pin'] : ''; ?></td>
                             <td><?= isset($val['unit']) ? $val['unit'] : ''; ?></td>
                             <td><?= isset($val['block']) ? $val['block'] : ''; ?></td>
                             <td><?= isset($val['building']) ? $val['building'] : ''; ?></td>
                             <td><?= isset($val['street']) ? $val['street'] : ''; ?></td>
                             <td class=<?=$val['order_number']."_is_processed";?>></td>
                         </tr>
                    <?php
                        } ?>
                         </tbody>
                         </table>
        </div> 
            <?php } ?>
            <br />
            <div id="results_display"></div>
            
            
            <div id="results" class="modal fade bs-document-sign-modal-lg" tabindex="-1" role="dialog" aria-labelledby="ChangeRequestAskPasswordLabel" aria-hidden="true">
                <div class="modal-dialog"  style="width:300px;">
                    <div class="modal-content">
                        <img src="<?php echo base_url();?>assets/img/loading_apple.gif" width="200px;" height="170px;" style="margin-left:50px;margin-top:70px;">
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