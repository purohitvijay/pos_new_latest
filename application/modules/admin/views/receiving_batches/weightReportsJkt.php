<div class="container-fluid">

    <?php
    if (!empty($message))
    {
        ?>
        <div class="alert alert-success" style="margin-top:20px" role="alert"><?= $message ?></div>
        <?php
    }
    ?>

    <div class="page-header">

        <form id="searchForm">
            <input class="searchFormClass" type="hidden" name="search_customer_id" id="customerIdHidden">
            <fieldset class="form-border">
                <legend class="form-border">Search Form</legend>
                <div class="row" style="padding-left: 20px">
                    <div class="col-md-12">


                        <div class="form-group row">

                            <div class="col-md-2">
                                Receiving Batch
                                <?php
                                if (!empty($receiving_batches))
                                {
                                    ?>
                                    <Select name="search_receiving_batch_id" id="search_receiving_batch_id" class="form-control searchFormClass" required>
                                        <option value="">--Select--</option>
                                        <?php
                                        foreach ($receiving_batches as $index => $row)
                                        {
                                            $selected = !empty($receiving_batch_id) && $receiving_batch_id == $row['id'] ? "Selected='Selected'" : '';
                                            ?>
                                            <option <?= $selected ?> value="<?= $row['id'] ?>"><?= $row['name'] . " (" . $row['status'] . ")" ?></option>
                                            <?php
                                        }
                                        ?>
                                    </Select>
                                    <?php
                                }
                                else
                                {
                                    echo '<br><b>No Active Batch Found.</b>';
                                }
                                ?>
                            </div> 

                            <div class="form-group row">

                                <div class="col-md-2">
                                    Shipment Batch
                                    <Select name="search_shipment_batch_id" id="search_shipment_batch_id" class="form-control searchFormClass">
                                        <option value="">--Select--</option>
                                    </select>
                                </div> 

                                <div class="col-md-2">
                                    Discrepancy

                                    <Select name="weight_discrepancy" id="weight_discrepancy" class="form-control searchFormClass">
                                        <option value="">--Select--</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>                                  
                                    </Select>

                                </div>
                                
                                <div id="shipment_batch_message_holder" class="col-md-3 hide" style="width:188px;margin-left:20px;background-color:#d9edf7;padding:16px;color:#31708f">
                                </div>
                                
                                <div class="clearfix"></div>
                                </br>
                                <div class="col-md-2" style="margin-left:15px;margin-top:15px;">
                                    Not Received
                                    <input type="checkbox" name="box_not_received" value="1"  class="form-control searchFormClass" id="box_not_received" style="margin-top:-25px; margin-left:10px;">

                                </div>
                                 <div class="col-md-2" style="margin-top:15px;">
                                    Received
                                    <input type="checkbox" name="box_received" value="1"  class="form-control searchFormClass" id="box_received" style="margin-top:-25px; margin-left:10px;">

                                </div> 
                                <div class="col-md-2">
                                    Locations
                                    <?php
                                    if (!empty($locations))
                                    {
                                        ?>
                                        <Select name="search_locations_id" id="search_locations_id" class="form-control searchFormClass">
                                            <option value="">--Select--</option>
                                            <?php
                                            foreach ($locations as $index => $row)
                                            {
                                                $selected = !empty($location_id) && $location_id == $row['id'] ? "Selected='Selected'" : '';
                                                ?>
                                                <option <?= $selected ?> value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                                                <?php
                                            }
                                            ?>
                                        </Select>
                                        <?php
                                    }
                                    ?>
                                </div> 
                                <div class="col-md-2">
                                    Kabupaten
                                    <Select name="search_kabupaten_id" id="search_kabupaten_id" class="form-control searchFormClass">
                                        <option value="">--Select--</option>

                                    </Select>
                                </div>

                                <div class="col-md-4">
                                    &nbsp;
                                    <div class="input-group">                                        
                                        <button class="btn-primary btn" id="clearSearchButton" type="button">Reset</button>
                                        <button class="btn-primary btn" id="customerSelectButton" type="submit">Search</button>                                        
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
            </fieldset>
        </form>

        <div class="row">
            <div class="col-sm-12">
                <div class="box box-color box-bordered">
                    <div class="box-title">
                        <h3>
                            <i class="fa fa-table"></i>
                            Order List
                        </h3>                        
                    </div>
                    <!--<div class="box-content nopadding">-->
                    <table class="table table-hover table-nomargin table-bordered" id="menuTable">

                    </table>
                    <!--</div>-->
                </div>
            </div>
        </div>
    </div>
</div>
<div id="loadingDiv_bakgrnd">
    <span id="img_load_chart">Loading...</span>
</div>


<script>
        jQuery(document).ready(function () {
            function initTables()
            {
                extraParams = collectParams();

                $('#menuTable').dataTable({
                    "bFilter": false,
                    "bLengthChange": true,
                    "iDisplayLength": 20,
                    "sAjaxSource": "<?php echo base_url(); ?>admin/receiving_batch/getOrdersWeighReportsJkt", //datasource
                    "sAjaxDataProp": "aData",
                    "bServerSide": true,
                    "bProcessing": true,
                    "bDestroy": true,
                    "aoColumns": [
                        {"mDataProp": "order_number", "sTitle": "Order #"},
                        {"mDataProp": 'customer_name', "sTitle": "Pengirim", 'bSortable': false},
                        {"mDataProp": 'boxes', "sTitle": "Size", "bSortable": false},
                        {"mDataProp": 'quantities', "sTitle": "Qty", "bSortable": false},
                        {"mDataProp": 'locations', "sTitle": "Location(s)", "bSortable": false},
                        {"mDataProp": 'kabupatens', "sTitle": "Kabupaten(s)", "bSortable": false},
                        {"mDataProp": 'weight', "sTitle": "Weight Singapore", 'bSortable': false},
                        {"mDataProp": 'jkt_weight', "sTitle": "Weight Jakarta", 'bSortable': false},
                        {"mDataProp": 'jkt_reference_no', "sTitle": "Reference Number", 'bSortable': false},
                        {"mDataProp": 'jkt_received_date', "sTitle": "Received Date", 'bSortable': false},
                        {"mDataProp": 'jkt_receiver', "sTitle": "Penerima", 'bSortable': false}
                    ],
                    "fnServerParams": function (aoData) {
                        if (typeof(extraParams) != 'undefined')
                        {
                            
                            $(extraParams.name).each(function (index, varName) {
                                if (typeof(varName) != 'undefined')
                                {
                                    aoData.push({"name": varName, "value": extraParams.val[index]});
                                }
                            })
                        }
                    },
                    "aLengthMenu": [
                        [20,
                            50,
                            100,
                            -1],
                        [20,
                            50,
                            100,
                            "All"] // change per page values here
                    ],
                    "bSort": true
                });
            }

            function collectParams()
            {
                var obj = ['name',
                    'val'];
                obj.name = new Array();
                obj.val = new Array();

                $('#searchForm *').each(function (index, tmpObj) {
                    if ($(tmpObj).hasClass('searchFormClass'))
                    {
                        if ($(tmpObj).attr('type') == 'checkbox')
                        {
                            obj.name[index] = $(tmpObj).attr('name');
                            obj.val[index] = $(tmpObj).is(':checked') == true ? 1 : 0;
                        }
                        else
                        {
                            obj.name[index] = $(tmpObj).attr('name');
                            obj.val[index] = $(tmpObj).val();
                        }
                    }
                });

                return obj;
            }

            $('#searchForm').submit(function (event) {
                event.preventDefault();

                initTables();
            })

            $('#clearSearchButton').click(function () {
                window.location.href = ''
            })

            initTables();

            $('body').on('click', '.fake-receiving-batch-class', function () {
                $('#loadingDiv_bakgrnd').show();

                order_id = $(this).attr('rel');

                $.ajax({
                    data: {order_id: order_id},
                    url: "<?= base_url() ?>admin/receiving_batch/getOrderDetailsByIdJkt",
                    cache: false,
                    dataType: 'html',
                    type: 'post',
                })
                        .done(function (response) {
                            $("#orderDetailsContainer").html(response);

                            $('#loadingDiv_bakgrnd').hide();
                        });
            });

            $('#search_receiving_batch_id').change(function () {
                $('#shipment_batch_message_holder').addClass('hide')
                
                var receiving_batch = $(this).val();
                $("#search_shipment_batch_id > option").remove();
                $('#search_shipment_batch_id').append("<option value=''>--Select--</option>");
                if (receiving_batch != "")
                {
                    $('#loadingDiv_bakgrnd').show();
                    $.ajax({
                        data: {receiving_batch: receiving_batch},
                        url: "<?= base_url() ?>admin/receiving_batch/getShipmentBatchesJkt",
                        cache: false,
                        dataType: 'html',
                        type: 'post',
                    }).done(function (response) {
                        $('#search_shipment_batch_id').append(response); //here we will append these new select options to a dropdown with the id 'cities'
                        $('#loadingDiv_bakgrnd').hide();
                    });
                }
            });

             $('#search_locations_id').change(function () {

                var location_id = $(this).val();
                $("#search_kabupaten_id > option").remove();
                $('#search_kabupaten_id').append("<option value=''>--Select--</option>");
                if (location_id != "")
                {
                    $('#loadingDiv_bakgrnd').show();
                    $.ajax({
                        data: {location_id: location_id},
                        url: "<?= base_url() ?>admin/receiving_batch/getKabupatenByLocationId",
                        cache: false,
                        dataType: 'html',
                        type: 'post',
                    }).done(function (response) {
                        $('#search_kabupaten_id').append(response); //here we will append these new select options to a dropdown with the id 'cities'
                        $('#loadingDiv_bakgrnd').hide();
                    });
                }
            });
            
            $('#search_receiving_batch_id').change(function () {
                getLocationDropdown();
            });
            
            $('#search_shipment_batch_id').change(function () {
                populateShipmentMessageHolder($(this).val());
                getLocationDropdown();
            });
            
            $('#weight_discrepancy').change(function () {
                getLocationDropdown();
            });
            
            $('#box_received').change(function () {
                getLocationDropdown();
            });
            
            $('#box_not_received').change(function () {
                getLocationDropdown();
            });
        });
        
        function getLocationDropdown()
        {
            var receiving_batch_id = $('#search_receiving_batch_id').val();
            var shipment_batch_id = $('#search_shipment_batch_id').val();
            var weight_discrepancy = $('#weight_discrepancy').val();
            var box_received = $('#box_received').is(":checked") == true ? 1 : 0;
            var box_not_received = $('#box_not_received').is(":checked") == true ? 1 : 0;
            
            $("#search_locations_id > option").remove();
            $('#search_locations_id').append("<option value=''>--Select--</option>");
                    $('#loadingDiv_bakgrnd').show();
                    $.ajax({
                        data: {
                               search_receiving_batch_id: receiving_batch_id,
                               search_shipment_batch_id : shipment_batch_id,
                               weight_discrepancy : weight_discrepancy,
                               box_received : box_received,
                               box_not_received : box_not_received                               
                                },
                        url: "<?= base_url() ?>admin/receiving_batch/getLocationDropdown",
                        cache: false,
                        dataType: 'html',
                        type: 'post',
                    }).done(function (response) {
                        $('#search_locations_id').append(response); //here we will append these new select options to a dropdown with the id 'cities'
                        $('#loadingDiv_bakgrnd').hide();
                    });
        }
        
        function populateShipmentMessageHolder(shipment_batch_id)
        {
            if (shipment_batch_id > 0) 
            {
                $('#loadingDiv_bakgrnd').show();
                $.ajax({
                    data: {search_shipment_batch_id : shipment_batch_id},
                    url: "<?= base_url() ?>admin/order/getMaxOrderJktStatusDaysByShipmentBatch/" + shipment_batch_id,
                    cache: false,
                    dataType: 'html',
                    type: 'get',
                }).done(function (response) {
                    $('#shipment_batch_message_holder').html(response).removeClass('hide');
                    $('#loadingDiv_bakgrnd').hide();
                });
            }
        }
</script>