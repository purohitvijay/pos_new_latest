<style type="text/css">
.label-info {
    background-color: #eee;
    border: 1px solid dimgray;
    color: dimgray;
}
.label {
    font-size: 14px;
}

.tiles.tiles-small > li > a span {
    font-size: 30px;
    padding-top: 12px;
}

.tiles.tiles-small > li > a {
    height: 55px;
    width: 55px;
}
.tiles.tiles-small > li > a span {
    font-size: 26px;
}

table { border-collapse: collapse; empty-cells: show; }

td { position: relative; }

tr.strikeout td:before {
  content: " ";
  position: absolute;
  top: 50%;
  left: 0;
  border-bottom: 1px solid #111;
  width: 100%;
}

tr.strikeout td:after {
  content: "\00B7";
  font-size: 1px;
}
</style>


<div class="container-fluid">
    

    
    
    
    <div class="page-header">
        
        <div class="row" >
            <form action="<?=base_url()?>admin/report/getLiveFeeds" method="post" id="searchForm">
                <input id="ordering_criteria_flag" name="ordering_criteria_flag" type="hidden">
                <div class="pull-left form-group">
                        <label for="collection_date_from" class="control-label col-sm-1">
                            Collection Date From
                        </label>
                        <div class="col-sm-2">
                            <div class='input-group date'>
                                <input type="text" name="collection_date_from" id="collection_date_from" class="form-control big datepick2" required value='<?=$collection_date_from?>'>
                            </div>    
                        </div>
                        <label for="collection_date_to" class="control-label col-sm-1">
                            Collection Date To
                        </label>
                        <div class="col-sm-2">
                            <div class='input-group date'>
                                <input type="text" name="collection_date_to" id="collection_date_to" class="form-control big datepick2" value='<?=$collection_date_to?>'>
                            </div>    
                        </div>
                        <label for="delivery_date_from" class="control-label col-md-1">
                            Del. Date From
                        </label>
                        <div class="col-sm-2">
                            <div class='input-group date'>
                                <input type="text" name="delivery_date_from" id="delivery_date_from" class="form-control big datepick2" required value='<?=$delivery_date_from?>'>
                            </div>    
                        </div>
                    
                    <label for="delivery_date_to" class="control-label col-md-1">
                            Del. Date To
                        </label>
                        <div class="col-sm-2">
                            <div class='input-group date'>
                                <input type="text" name="delivery_date_to" id="delivery_date_to" class="form-control big datepick2" value='<?=$delivery_date_to?>'>
                            </div>    
                        </div>
                        <label for="employee_id" class="control-label col-md-1" style="clear:both;">
                            Driver
                        </label>
                        <div class="col-sm-2">
                            <div class='input-group date'>
                                <select name="employee_id" class="form-control" id="employee_id">
                                    <option value="">--Select--</option>
                                    <?php
                                    $show_lat_long = empty($show_lat_long) ? 0 : $show_lat_long;
                                            
                                    if (!empty($drivers))
                                    {
                                        foreach ($drivers as $index => $row)
                                        {
                                    ?>
                                            <option value="<?=$row['id']?>"><?=$row['name']?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>    
                        </div>
                    
                    
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-primary" >Report</button>&nbsp;&nbsp;
                            <input id="ordering_criteria" type="checkbox" name="my-checkbox" checked data-on-color="danger" data-on-text="ORDER" data-off-text="TIME" />
                            <input id="counting_criteria" type="checkbox" name="my-cnt-checkbox" checked data-on-color="danger" data-on-text="BOX CNT" data-off-text="ORD CNT" />
                        </div>
                </div>
            </form>
        </div>
        
        <div class="row" id="reportContainer">
            <div class="col-sm-12">
                <div class="box box-color box-bordered">
                    <div class="box-title">
                        <h3>
                            <i class="fa fa-table"></i>
                            Live Feeds / Delivery Console
                        </h3>
                    </div>

                    <!--<div class="box-content nopadding">-->
                    <table class="table table-nomargin dataTable table-bordered dataTable-scroll-y " id="menuTable">
                            <thead>
                                    <tr>
                                            <th>Order Number</th>
                                            <th>Customer</th>
                                            <th>Mobile / Phone</th>
                                            <th>Status</th>
                                            <th>Personnel</th>
                                            <th>Address</th>
                                            <th>Updated Date/Time</th>
                                            <?php
//                                            if (strtolower($role) == 'manager')
                                            {
                                                echo '<th>Operations</th>';
                                            }
                                            if (!empty($show_lat_long))
                                            {
                                                echo '<th>Lat / Long</th>';
                                            }
                                            ?>
                                    </tr>
                            </thead>
                            
                            <tbody id="liveFeedData">
                            </tbody>
                    </table>
                    <!--</div>-->
                </div>
            </div>
            
            <div class="box-content">
                <?php
                if (!empty($statuses))
                {
                ?>
                    <ul class="tiles tiles-left nomargin tiles-small" style="padding-left:15px">
                <?php
                    foreach ($statuses as $status => $row)
                    {
                ?>
                        <li style="background-color:<?=$row['label_color']?>">
                                <span class="label label-info" id="<?=$status?>_count"><?=empty($status_count[$status]) ? 0 : $status_count[$status]?></span>
                                <a href="#">
                                        <span>
                                                <i style="color:<?=$row['font_color']?>;padding-top: 0px;" class="fa <?=$row['glyphicon']?>"></i>
                                        </span>
                                        <span class="name" style="padding-right:0px;padding-left:0px;white-space: normal;display: inline-block;color:<?=$row['font_color']?>"></span>
                                </a>
                        </li>
                <?php
                    }
                ?>
                    </ul>    
                <?php
                }
                ?>
            </div>

            
        </div>
    </div>
</div>



    <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel"><span id="formTitleContainer"></span></h4>
              </div>
              <div class="modal-body">

                <form class="form-horizontal" role="form" id="reassignDriverForm">
                    
                    <input type="hidden" id="old_employee_id" name="old_employee_id">
                    <input type="hidden" id="order_id" name="order_id">
                    <input type="hidden" id="id" name="id">

                    <div class="col-md-12">
                        <div class="form-group row">
                            <label for="textfield" class="control-label col-sm-3">Driver</label>
                            <div class="col-md-9">
                                <select name="employee_id" class="form-control" id="employee_id">
                                    <?php
                                    if (!empty($drivers))
                                    {
                                        foreach ($drivers as $index => $row)
                                        {
                                    ?>
                                            <option value="<?=$row['id']?>"><?=$row['name']?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
                  <br><br>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="selectDriverButton">Select</button>
              </div>
            </div>
          </div>
        </div>



<div id="loadingDiv_bakgrnd">
    <span id="img_load_chart">Pulling Data...</span>
</div>



<script type="text/javascript">
$(document).ready(function () {
    $('.datepick2').datepicker({
       format: "dd/mm/yyyy",
       autoclose: true,
    });
    
   $('#collection_date_from').change(function(){
     var collection_date_from = $(this).val();
     $('#collection_date_to').val(collection_date_from);
   });
   
   $('#delivery_date_from').change(function(){
      var delivery_date_from = $(this).val();
      $('#delivery_date_to').val(delivery_date_from);
   });
    pullData();
    
    $('input[name="my-checkbox"]').on('switchChange.bootstrapSwitch', function(event, state) {
        $('#ordering_criteria_flag').val(state)
    });
    $('input[name="my-cnt-checkbox"]').on('switchChange.bootstrapSwitch', function(event, state) {
        $('#counting_criteria_flag').val(state)
    });

    function pullData()
    {
        $('#loadingDiv_bakgrnd').show();
        data = $('#searchForm').serialize();
        
        $.ajax({
            data:data,
            url: "<?=base_url()?>admin/report/getLiveFeedsData/<?=$show_lat_long?>/",
            cache: false,
            dataType : 'json',
            type : 'post',
        })
        .done(function( response ) {
            $('#liveFeedData').html(response.html);
    
            $(response.statuses).each(function(index, status_obj){
                    for (status in status_obj)
                    {
                        $('#'+ status + '_count').html(status_obj[status]);
                    }
            })

            $('#loadingDiv_bakgrnd').hide();
        })
        .complete(function() {
            setTimeout(pullData, 5000);
        });
    }
    
    $('body').on('click', '.fake-reassign-driver-class', function (){
        var info = $(this).attr('rel');
        infoArr = info.split('@@##@@');
        
        var titleBarMsg = "Reassign <b>" + infoArr[3] + "</b> for order number <b>" + infoArr[2] + "</b>";
        $('#formTitleContainer').html(titleBarMsg);
        $('#old_employee_id').val(infoArr[1]);
        $('#order_id').val(infoArr[0]);
        $('#id').val(infoArr[4]);
        
        $('#myModal').modal('toggle');
    })
    
    $('#selectDriverButton').click(function (){
        if ($('#old_employee_id').val() == $('#employee_id').val())
        {
            alert('New driver can not be same as old driver');
        }
        else
        {
            $('#loadingDiv_bakgrnd').show();
            
            $.ajax({
                data:$('#reassignDriverForm').serialize(),
                url: "<?=base_url()?>admin/order/reassignDriver",
                cache: false,
                dataType : 'json',
                type : 'post',
            })
            .done(function( response ) {
                if (response.status == 'error')
                {
                    alert(response.message);
                }
                else
                {
                    $('#myModal').modal('toggle');
                }
                
                $('#loadingDiv_bakgrnd').hide();            
            });
        }
    })
    
    $("[name='my-checkbox']").bootstrapSwitch();
    $("[name='my-cnt-checkbox']").bootstrapSwitch();
})
</script>