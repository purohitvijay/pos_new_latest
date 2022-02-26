<div class="container-fluid">
    
    
    <div class="page-header">
        
        <div class="row" >
            <form action="<?=base_url()?>admin/report/boxCollectionDateReport" method="post">
                <div class="pull-left form-group">
                        <label for="collection_date_from" class="control-label pull-left">
                            Start Date
                        </label>
                        <div class="pull-left" style="padding-left:10px">
                            <div class='input-group date'>
                                <input type="text" name="collection_date_from" id="collection_date_from" class="form-control big datepick2" required value='<?=$collection_date_from?>'>
                            </div>    
                        </div>
                        <label for="collection_date_to" class="control-label pull-left">
                            &nbsp;&nbsp;End Date
                        </label>
                        <div class="pull-left" style="padding-left:10px">
                            <div class='input-group date'>
                                <input type="text" name="collection_date_to" id="collection_date_to" class="form-control big datepick2" required value='<?=$collection_date_to?>'>
                            </div>    
                        </div>
                       &nbsp;&nbsp;&nbsp;&nbsp;
                        <div class="pull-left" style="margin-left: 20px;">
                            <button type="submit" class="btn btn-primary" >Report</button>
                        </div>
                </div>

                <div class="pull-right">
                    <?php
                    if (!empty($records))
                    {
                    ?>
                    <!--<button id="btnPrint" class="btn btn-primary" type="submit" style="margin-right: 20px;">-->
                            <a class="btn btn-primary" href="<?php echo base_url();?>admin/report/downloadBoxCollectionDateReport/<?php echo date('Y-m-d', strtotime($temp_date_from));?>/<?php echo date('Y-m-d', strtotime($temp_date_to));?>" target='_new'><i class='fa glyphicon-download'></i> Export</a>
                        <!--</button>-->
                    <?php             
                    }
                    ?>
                </div>
            </form>
        </div>
        
        <div class="row" id="reportContainer">
            <div class="col-sm-12">
                <div class="box box-color box-bordered">
                    <div>
                        <h3>
                            <i class="fa fa-table"></i>
                            Box Collection Date Report As Of <?=$collection_date_from?> -  <?=$collection_date_to?>
                        </h3>
                    </div>
                    <?php
                    if (!empty($records))
                    {
                    ?>
                    <!--<div class="box-content nopadding">-->
                    <table class="table table-hover table-nomargin dataTable table-bordered dataTable-scroll-y "  style="page-break-after: always" id="menuTable">
                            <thead>
                                    <tr>
                                        <th>Order Number</th>
                                        <th>Customer Name</th>
                                        <th>Contact Number</th>
                                    </tr>
                            </thead>
                            
                            <tbody>
                            <?php
                                
                                foreach ($records as $index => $row)
                                {?>
                                    
                                <tr>
                                            
                                            <td><?= $row['order_number'] ?></td>
                                            <td><?= ucwords($row['customer']) ?></td>
                                            <td><?= ucwords($row['contacts']) ?></td>
                                        </tr>
                            <?php
                                }
                            ?>
                            </tbody>
                    </table>
                   
                    <?php
                        }
                        else
                        {
                        ?>
                            <div class="alert alert-warning alert-dismissable">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    <strong>Oops! </strong>No records Found.
                            </div>
                        <?php
                        }
                    ?>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function () {
        $('.datepick2').datepicker({
            format: "dd/mm/yyyy"
        })
        <?php
        if (!empty($records))
        {
        ?>              
        $("#btnPrint").click (function() {
            printElement(document.getElementById("reportContainer"));
            window.print();
        });
        <?php
        }
        ?>
})
</script>