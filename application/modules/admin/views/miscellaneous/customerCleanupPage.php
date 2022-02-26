<style type="text/css">
#remove_dupicate_customers
{
    text-align:center;
}
.modal-footer
{
    display:none;
}
</style>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
         
         
    </head>
    <body>
        <div id="dsmain" class="page-content main_container_padding">
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-color box-bordered">
                    <div class="box-title"><h3>Duplicate Customers Records</h3><br/><br/>
                    </div>
                    <br/>
                </div>
                
                
                <div class="">
                    <?php 
                    if(!empty($duplicate_customer_data))
                    {
                    ?>
                    <div id="div_table_data">
                       <table id="menuTable" class="table_data table table-hover table-nomargin dataTable table-bordered dataTable-scroll-y" style="page-break-after: always">
                        <thead>
                            <tr>
                            <th>Name</th>
                            <th>E-mail</th>
                            <th>Mobile</th>
                            <th>Phone</th>
                            <th>Pin</th>
                            <th>Unit</th>
                            <th>Block</th>
                            <th>Building</th>
                            <th>Street</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach($duplicate_customer_data as $idx => $val)
                        {
                        ?>
                         <tr>
                             <td><?= isset($val['name']) ? $val['name'] : '';?></td>
                             <td><?= isset($val['email']) ? $val['email'] : ''; ?></td>
                             <td><?= isset($val['mobile']) ? $val['mobile'] : ''; ?></td>
                             <td><?= isset($val['residence_phone']) ? $val['residence_phone'] : ''; ?></td>
                             <td><?= isset($val['pin']) ? $val['pin'] : ''; ?></td>
                             <td><?= isset($val['unit']) ? $val['unit'] : ''; ?></td>
                             <td><?= isset($val['block']) ? $val['block'] : ''; ?></td>
                             <td><?= isset($val['building']) ? $val['building'] : ''; ?></td>
                             <td><?= isset($val['street']) ? $val['street'] : ''; ?></td>
                         </tr>
                    <?php
                        } ?>
                         </tbody>
                         </table>
                </div>
                    
                        <div class="input-group">
                            <input id="remove_dupicate_customers" class="btn-primary btn" type="button"   value="Remove Duplicate Customer" />  
                            <br />
                            <br />
                        </div>
                    <?php } ?>
            </div>
            
        </div>
        </div>
        </div>
           <div id="loadingDiv_bakgrnd">
    <span id="img_load_chart">Processing...</span>
</div>

 
    </body>
</html> 

<script>
    $(document).ready(function (){ 
        $("#remove_dupicate_customers").click(function()
        {
            $('#loadingDiv_bakgrnd').show();
            $("#remove_dupicate_customers").addClass("disabled");

           $.ajax
           ({ 
               url : "<?=base_url()?>admin/miscellaneous/deleteDuplicateCustomers",
               type : "POST"        
           }).
           done(function( response )  
           {  
               var old_customers_names = $(response).find(".old_customer_name").html();
               
               bootbox.alert(old_customers_names, function() {
                    });
                   
               $('#loadingDiv_bakgrnd').hide();
               $("#remove_dupicate_customers").removeClass("disabled");
               $(".ok").click(function()
               {  
                window.location.href='<?=base_url()?>admin/miscellaneous/customerCleanup'   
               });
           });
        });
    });
</script>