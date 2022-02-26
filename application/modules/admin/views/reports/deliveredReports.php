    <style>
        /* Absolute Center Spinner */
        .loading {
            position: fixed;
            z-index: 999;
            height: 2em;
            width: 2em;
            overflow: show;
            margin: auto;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
        }

        /* Transparent Overlay */
        .loading:before {
            content: '';
            display: block;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.3);
        }

        /* :not(:required) hides these rules from IE9 and below */
        .loading:not(:required) {
            /* hide "loading..." text */
            font: 0/0 a;
            color: transparent;
            text-shadow: none;
            background-color: transparent;
            border: 0;
        }

        .loading:not(:required):after {
            content: '';
            display: block;
            font-size: 10px;
            width: 1em;
            height: 1em;
            margin-top: -0.5em;
            -webkit-animation: spinner 1500ms infinite linear;
            -moz-animation: spinner 1500ms infinite linear;
            -ms-animation: spinner 1500ms infinite linear;
            -o-animation: spinner 1500ms infinite linear;
            animation: spinner 1500ms infinite linear;
            border-radius: 0.5em;
            -webkit-box-shadow: rgba(0, 0, 0, 0.75) 1.5em 0 0 0, rgba(0, 0, 0, 0.75) 1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) 0 1.5em 0 0, rgba(0, 0, 0, 0.75) -1.1em 1.1em 0 0, rgba(0, 0, 0, 0.5) -1.5em 0 0 0, rgba(0, 0, 0, 0.5) -1.1em -1.1em 0 0, rgba(0, 0, 0, 0.75) 0 -1.5em 0 0, rgba(0, 0, 0, 0.75) 1.1em -1.1em 0 0;
            box-shadow: rgba(0, 0, 0, 0.75) 1.5em 0 0 0, rgba(0, 0, 0, 0.75) 1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) 0 1.5em 0 0, rgba(0, 0, 0, 0.75) -1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) -1.5em 0 0 0, rgba(0, 0, 0, 0.75) -1.1em -1.1em 0 0, rgba(0, 0, 0, 0.75) 0 -1.5em 0 0, rgba(0, 0, 0, 0.75) 1.1em -1.1em 0 0;
        }

        /* Animation */

        @-webkit-keyframes spinner {
            0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }
        @-moz-keyframes spinner {
            0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }
        @-o-keyframes spinner {
            0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }
        @keyframes spinner {
            0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }
    </style>
    <div class="loading" style="display: none" >
        Destination  load
    </div>
    <div class="container-fluid maindiv">
        <div class="page-header">
            <form action="<?= base_url() ?>admin/report/deliveredReports" method="post">
                <fieldset class="form-border">
                    <legend class="form-border">Search Form</legend>
                    <div class="row" style="padding-left: 20px">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <div class="col-md-1">
                                    <div>
                                        <input  type="checkbox" id="tristate" class="destination_change" name="received"  <?php
                                        if ($received == "Checked")
                                            echo 'checked="checked"';
                                        else if ($received == "indeterminate")
                                            echo 'indeterminate="indeterminate"';
                                        ?>>
                                        <input  type="hidden" id="tristate_input"  name="received_input" >
                                    </div>
                                    <div>
                                        <span id="tristate-value" class="output"></span>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    By Receiving batch                                    
                                    <Select name="shipment_batch_ids" id="shipment_batch_ids" class="form-control destination_change">
                                        <option value=""> Select option</option>
                                        <?php
                                        foreach ($shipment_batches as $index => $row) {
                                            $selected = !empty($shipment_batch_selected) && $shipment_batch_selected == $row['id'] ? "Selected='Selected'" : '';
                                            ?>
                                            <option  <?= $selected ?> value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                                        <?php }
                                        ?>
                                    </Select>
                                </div>                                
                                <div class="col-md-2">
                                    By Destination                                
                                    <Select name="search_by_destination_id" id="search_by_destination_id" class="form-control searchFormClass">

                                    </Select>
                                </div>                                                           
                                <div class="col-md-2">
                                    Box No   
                                    <input type="text" class="form-control" name="box_no" id="box_no" value="<?= $box_no ?>">
                                </div> 

                                <div class="col-md-2">
                                    Date From
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                        <input type="text" name="date_from" id="date_from" class="form-control big datepick2 destination_change" value='<?= $date_from ?>'>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    Date To
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                        <input type="text" name="date_to" id="date_to" class="form-control big datepick2 destination_change"  value='<?= $date_to ?>'>
                                    </div>
                                </div> 
                                <div class="col-md-1">
                                    <button class="btn-primary btn" id="customerSelectButton" type="submit" style="margin-top: 20px;" >Search</button>
                                </div>
                            </div>
                            <!--                        <div class="form-group row"> 
                                                        <div class="col-md-4">
                                                            &nbsp;
                                                            <div class="input-group">                                        
                                                                <button class="btn-primary btn" id="customerSelectButton" type="submit">Search</button>
                                                                <button class="btn-primary btn" id="clearSearchButton" type="button" style="margin-left: 14px">Reset</button>
                                                                <button id="downloadXls" class="btn btn-primary" style="margin-left: 14px"><i class="fa fa-download"></i>Download as Xls </button>
                                                            </div>
                                                        </div>
                                                    </div>-->
                        </div>
                    </div>
                </fieldset>
            </form>
            <div class="row" id="reportContainer">
                <div class="col-sm-12">
                    <div class="box box-color box-bordered">
                        <div class="row">
                            <div class="col-sm-6">
                                <h3 class="reportText">
                                    <i class="fa fa-table"></i>
                                    Delivery Status Jakarta <?= empty($date_from) ? "" : "As Of " . $date_from . "-" . $date_to ?><?= empty($batches_names) ? '' : 'For Shipment Batch ' . implode(', ', $batches_names) ?>
                                </h3>
                            </div>
                            <div class="col-sm-6">
                                <?php if (!empty($records['box_data'])) { ?>
                                    <button id="downloadXls" class="btn btn-primary pull-right" style="margin-top: 17px;"><i class="fa fa-download"></i>Download as Xls </button>
                                <?php } ?>
                            </div>
                        </div>
                        <?php if (!empty($records['box_data'])) { ?>
                            <div id='div_table_data'>
                                <table class="table_data table table-hover table-nomargin dataTable table-bordered dataTable-scroll-y" style="page-break-after: always" id="dataTable_deliveredReports">
                                    <thead>
                                        <tr>
                                            <th>Order#</th>  
                                            <th>Sh Batch</th>
                                            <th>Box</th>  
                                            <th>Qty</th>
                                            <th>Destination</th>
                                            <th>Kabupaten</th>
                                            <th>Coll date</th>
                                            <th>To Date</th>
                                            <th>SOB</th>
                                            <th>To Date</th>
                                            <th>Recd@jkt</th>
                                            <th>To Date</th>
                                            <th>Delivery@JKT Date</th>
                                            <th>To Date</th>
                                            <th>Driver</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($records["box_data"] as $key => $value) {
                                            $received_at_jakarta_warehouse_date = $this->reportsModel->get_status_date_by_order_id($value["id"], "received_at_jakarta_warehouse");
                                            $delivery_date = $this->reportsModel->get_status_date_by_order_id($value["id"], "delivered_at_jkt_picture_not_taken");

                                            $to_collection_date_diff = date_diff(date_create($value['collection_date']), date_create($delivery_date));
                                            $to_ship_onboard_diff = date_diff(date_create($value['ship_onboard']), date_create($delivery_date));
                                            $to_jkt_received_date_diff = date_diff(date_create($received_at_jakarta_warehouse_date), date_create($delivery_date));
                                            $to_delivery_date_diff = date_diff(date_create($delivery_date), date_create($value['collection_date']));

                                            $collection_date = (!empty($value['collection_date'])) ? date("d/m/Y", strtotime($value['collection_date'])) : '';
                                            $ship_onboard = (!empty($value['ship_onboard'])) ? date("d/m/Y", strtotime($value['ship_onboard'])) : '';
                                            $jkt_received_date = (!empty($received_at_jakarta_warehouse_date)) ? date("d/m/Y", strtotime($received_at_jakarta_warehouse_date)) : '';
                                            $jkt_delivery_date = (!empty($delivery_date)) ? date("d/m/Y", strtotime($delivery_date)) : '';

                                            echo"<tr>";
                                            echo"<td style='text-align: center'>" . $value['order_number'] . "</td>";
                                            echo"<td style='text-align: center'>" . $value['batch_name'] . "</td>";
                                            echo"<td style='text-align: center'>" . $value['box'] . "</td>";
                                            echo"<td style='text-align: center'>" . $value['quantity'] . "</td>";
                                            echo"<td style='text-align: center'>" . $value['location_name'] . "</td>";
                                            echo"<td style='text-align: center'>" . $value['kabupatens_name'] . "</td>";
                                            echo"<td style='text-align: center'>" . $collection_date . "</td>";
                                            echo"<td style='text-align: center'>" . $to_collection_date_diff->format("%a") . "</td>";
                                            echo"<td style='text-align: center'>" . $ship_onboard . "</td>";
                                            echo"<td style='text-align: center'>" . $to_ship_onboard_diff->format("%a") . "</td>";
                                            echo"<td style='text-align: center'>" . $jkt_received_date . "</td>";
                                            echo"<td style='text-align: center'>" . $to_jkt_received_date_diff->format("%a") . "</td>";
                                            echo"<td style='text-align: center'>" . $jkt_delivery_date . "</td>";
                                            echo"<td style='text-align: center'>" . $to_delivery_date_diff->format("%a") . "</td>";
                                            echo"<td style='text-align: center'>" . $value['username'] . "</td>";
                                            echo"<td style='text-align: center'>" . $value['memo'] . "</td>";
                                            echo"</tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>                            
                                <b><h3>
                                        <br>
                                        <table>
                                            <tr><th>
                                                    <i class="fa fa-table"></i>
                                                    Summary
                                                </th></tr>
                                        </table>
                                    </h3></b>
                                <table class="table table-nomargin dataTable table-bordered dataTable-scroll-y " style="page-break-after: always" id="menuTable">
                                    <?php
                                    $total_quantity = 0;
                                    foreach ($records["box_data"] as $key => $value) {
                                        $total_quantity += $value["quantity"];
                                    }
                                    echo '</tr>';
                                    echo "<tr>"
                                    . " <th>Qty</th>"
                                    . "<td><b>" . $total_quantity . " </b></td>"
                                    . "</tr>";
                                    ?>
                                </table>
                            </div>
                        <?php } else { ?>
                            <div class="alert alert-warning alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong>Oops! </strong>No records Found.
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Markdown parser -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pagedown/1.0/Markdown.Converter.min.js"></script>
    <script src="<?= base_url() ?>assets/js/jquery.tristate.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $(".destination_change").on("change", function ()
            {
                console.log($(this).attr('id'));
                if($(this).attr('id') == "tristate")
                    get_destination(true);
                else
                get_destination(false);
            });
            $("#dataTable_deliveredReports").DataTable({
                columnDefs: [
                    {className: 'text-center', targets: [3, 7, 9, 11, 13]},
                ],
            });
            $(function () {
                function output(state, value) {
                    $('#tristate-value').text(value);
                    if (state === true)
                        $('#tristate_input').val('Checked');
                    else if (state === false)
                        $('#tristate_input').val('Unchecked');
                    else if (state === null)
                        $('#tristate_input').val('indeterminate');
                }
                var $tristate = $('#tristate').tristate({
                    checked: "Received",
                    unchecked: "Not Received",
                    indeterminate: "Not Received And Received",

                    init: output,
                    change: output
                });
            get_destination(false);

            });
            $('.datepick2').datepicker({
                format: "dd/mm/yyyy"
            });
    <?php
    if (!empty($records['box_data'])) {
        ?>
                $("#btnPrint").click(function () {
                    printElement(document.getElementById("reportContainer"));
                    window.print();
                });

                $("#downloadPdf").click(function () {
                    var doc = new jsPDF('p', 'pt');

                    var headerText = $(".reportText").text();
                    var setHeaderText = doc.splitTextToSize(headerText, 500);

                    doc.autoTableSetDefaults({
                        addPageContent: function () {
                            doc.text(setHeaderText, 1, 1);
                        },
                    });

                    var headers = $('#div_table_data .b1 .h3');

                    $('#div_table_data table').each(function (index, val) {
                        var json = doc.autoTableHtmlToJson(val);
                        doc.text($(headers[index]).text(), 100, doc.autoTableEndPosY() + 80);

                        doc.autoTable(json.columns, json.data, {
                            startY: doc.autoTableEndPosY() + 90,
                            theme: 'grid',
                            styles: {
                                overflow: 'linebreak',
                                fontSize: 11,
                                rowHeight: 12,
                                columnWidth: 'wrap',
                                border: 2,
                            },
                            margin: {top: 80, left: 100},
                            headerStyles: {
                                cellPadding: 2,
                                lineWidth: 0,
                                valign: 'top',
                                fontStyle: 'bold',
                                halign: 'left', //'center' or 'right'
                                fillColor: [211, 211, 211],
                                textColor: [78, 53, 73], //Black     
                                rowHeight: 0
                            }

                        });
                    });

                    var a = window.document.createElement("a");
                    a.href = doc.output('datauristring');

                    var today = new Date();
                    var date = today.getDate() + '-' + (today.getMonth() + 1) + '-' + today.getFullYear();
                    var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
                    var dateTime = date + '_' + time;

                    a.download = "DeliveredBoxesReport_" + dateTime + ".pdf";
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);

                });

                $("#downloadXls").click(function ()
                {
                    var date_from = $("#date_from").val();
                    var date_to = $("#date_to").val();
                    var received = $("#tristate_input").val();
                    console.log(received);
                    var shipment_batch_ids = $("#shipment_batch_ids").val();
                    var search_by_destination_id = $("#search_by_destination_id").val();
                    var box_no = $("#box_no").val();
                    var today = new Date();
                    var date = today.getDate() + '-' + (today.getMonth() + 1) + '-' + today.getFullYear();
                    var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
                    var dateTime = date + '_' + time;

                    if (shipment_batch_ids == null)
                    {
                        shipment_batch_ids = "";
                    }

                    window.open("<?= base_url(); ?>admin/report/downloadDeliveredXlsReport?received=" + received + "&date_from=" + date_from + "&date_to=" + date_to + "&shipment_batch_ids=" + shipment_batch_ids + "&search_by_destination_id=" + search_by_destination_id + "&box_no=" + box_no + "&current_datetime=" + dateTime);
                });

    <?php } ?>
        });

        function get_destination(re)
        {
            $(".loading").show();
            var date_from = $("#date_from").val()
            var date_to = $("#date_to").val();
            var received = $("#tristate_input").val();
            var shipment_batch_ids = $("#shipment_batch_ids").val();
            var destination_id = $("#search_by_destination_id").val();
            if(re)
            {
                if(received == "Checked")
                          received      = "indeterminate";
                else if(received == "Unchecked")
                          received      = "Checked";
                else if(received == "indeterminate")
                          received      = "Unchecked";
            }
            myData = {date_from: date_from, date_to: date_to, received: received, shipment_batch_ids: shipment_batch_ids};
            console.log(myData);
            $.ajax({
                data: myData,
                type: "post",
                dataType: 'json',
                url: "<?= base_url(); ?>admin/report/get_destination",
                success: function (data)
                {
                    $('#search_by_destination_id').find('option').remove().end().append('<option value="">Select option</option>');
                    var search_by_destination_id = '<?= $search_by_destination_id ?>';                
                    $.each(data, function (key, value)
                    {
                    if(search_by_destination_id != '')
                        var selected = (search_by_destination_id == value.id) ? "Selected='Selected'" : '';
                    else
                        var selected = (destination_id != '' && destination_id == value.id) ? "Selected='Selected'" : '';

                        $('#search_by_destination_id').append('<option ' + selected + ' value="' + value.id + '">' + value.name + '</option>');
                    });
                    $(".loading").hide();
                }
            });

        }
    </script>