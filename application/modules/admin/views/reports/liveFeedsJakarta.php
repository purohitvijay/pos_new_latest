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
            <div class="col-sm-12">
            <form action="<?=base_url()?>admin/report/getLiveFeedsJkt" method="post" id="searchForm">
                <input id="ordering_criteria_flag" name="ordering_criteria_flag" type="hidden">
                <!--<div class="pull-left form-group">-->
                        <label for="collection_date" class="control-label col-md-2">
                            <b> Receiving Batch Id</b>
                        </label>
                        <div class="col-sm-2">
                                <select name="receiving_batch_id"  class="form-control" id="receiving_batch_id" style="width:150px">
                                    <option value="">--Select--</option>
                                    <?php
                                    if (!empty($receiving_batches))
                                    {
                                        foreach ($receiving_batches as $index => $row)
                                        {
                                            $selected = $row['id'] == $receiving_batch_id ? "selected='selected'" : '';
                                    ?>
                                            <option <?=$selected?> value="<?=$row['id']?>"><?=$row['name']?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                        </div>
                    
                    
                        <div class="col-sm-7">
                            <button type="submit" class="btn btn-primary" >Report</button>&nbsp;
                            <input id="ordering_criteria" type="checkbox" name="my-checkbox" checked data-on-color="danger" data-on-text="BOX CNT" data-off-text="TIME">
                            <input id="counting_criteria" type="checkbox" name="my-cnt-checkbox" checked data-on-color="danger" data-on-text="BOX CNT" data-off-text="ORDER CNT">
                        </div>
                <!--</div>-->
            </form>
            </div>
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




<div id="loadingDiv_bakgrnd">
    <span id="img_load_chart">Pulling Data...</span>
</div>



<script type="text/javascript">
$(document).ready(function () {
    $('.datepick2').datepicker({
        format: "dd/mm/yyyy"
    })
    

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
            url: "<?=base_url()?>admin/report/getLiveFeedsDataJkt",
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
    
    
    $("[name='my-checkbox']").bootstrapSwitch();
    $("[name='my-cnt-checkbox']").bootstrapSwitch();
})
</script>