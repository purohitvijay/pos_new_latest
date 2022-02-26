<div class="container-fluid">
    <div class="page-header">
        <div class="row" >
            <form action="<?= base_url() ?>admin/report/weeklyCollectionReports" method="post">
                <div class="pull-left form-group">
                    <label for="collection_date_from" class="control-label pull-left">
                        Date<br> (From)
                    </label>
                    <div class="pull-left" style="padding-left:10px">
                        <div class='input-group date'>
                            <input type="text" name="collection_date_from" id="collection_date_from" class="form-control big datepick2" required value='<?= $collection_date_from ?>'>
                        </div>    
                    </div>
                    <label for="collection_date_to" class="control-label pull-left">
                        &nbsp;&nbsp;Date<br>&nbsp;&nbsp; (To)
                    </label>
                    <div class="pull-left" style="padding-left:10px">
                        <div class='input-group date'>
                            <input type="text" name="collection_date_to" id="collection_date_to" class="form-control big datepick2" required value='<?= $collection_date_to ?>'>
                        </div>    
                    </div>

                    <div class="pull-left" style="margin-left:5px;">
                        <button type="submit" class="btn btn-primary" >Report</button>
                    </div>
                </div>

                <div class="pull-right">
                    <?php
                    if (!empty($records))
                    {
                        ?>
                        <button id="btnPrint" class="btn btn-primary" style="margin-right: 14px">
                            <i class="fa fa-print"></i>Print
                        </button>
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
                            Weekly Collection Forecast As Of <?= $collection_date_from ?> -  <?= $collection_date_to ?>
                        </h3>
                    </div>
                    <?php
                    if (!empty($records))
                    {
                        ?>
                        <table class="table table-hover table-nomargin dataTable table-bordered dataTable-scroll-y "  style="page-break-after: always" id="menuTable">
                            <thead>   
                                <tr><th>Date</th>
                                    <?php
                                    foreach ($records['data'] as $idx => $row)
                                    {

                                        echo "<th class='center'>" . date('d/m/Y',strtotime($idx)) . "</th>";
                                    }
                                    ?>
                                    <th>Totals</th>
                            </thead>
                            </tr>
                            <?php
                            $boxes_total = array();
                            foreach ($records['box_header'] as $index => $value)
                            {
                                echo "<tr><th>" . $value . "</th>";
                                $total = 0;
                                foreach ($records['data'] as $idx => $box_row)
                                {
                                    if (isset($box_row[$index]))
                                    {
                                        echo "<td>" . $box_row[$index] . "</td>";
                                        $total += $box_row[$index];
                                    }
                                    else
                                    {
                                        echo "<td></td>";
                                    }

                                    $box_total[$idx] = array_sum($records['data'][$idx]);
                                }
                                echo "<td>" . $total . "</td>";
                                echo "</tr>";
                            }
                            ?>
                            <tr><th>S/Total</th>
                                <?php
                                $grand_total = 0;
                                foreach ($box_total as $idx => $value)
                                {
                                    echo "<td>" . $value . "</td>";
                                    $grand_total += $value;
                                }
                                ?>
                                <td><?php echo $grand_total; ?></td>
                            </tr>
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
    </div>
</div>
<script type="text/javascript">
        $(document).ready(function () {
//            $('.datepick2').datepicker({
//                format: "dd/mm/yyyy"
//            });
            $("#collection_date_from").datepicker({
                format: "dd/mm/yyyy"
            })
                    .on('changeDate', function (selected) {
                        $('#collection_date_from').datepicker('hide');
                        startDate = new Date(selected.date.valueOf());
                        startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
                        $('#collection_date_to').datepicker('setStartDate', startDate);

                        endDate = new Date(selected.date.valueOf());
                        endDate.setDate(endDate.getDate() + 7);
                        $('#collection_date_to').datepicker('setEndDate', endDate);

                    });

            $("#collection_date_to").datepicker({
                format: "dd/mm/yyyy"
            }).on('changeDate', function (selected) {
                $('#collection_date_to').datepicker('hide');
                FromEndDate = new Date(selected.date.valueOf());
                FromEndDate.setDate(FromEndDate.getDate() - 7);
                $('#collection_date_from').datepicker('setStartDate', FromEndDate);

                startEndDate = new Date(selected.date.valueOf());
                startEndDate.setDate(startEndDate.getDate(new Date(selected.date.valueOf())));
                $('#collection_date_from').datepicker('setEndDate', startEndDate);
            });
<?php
if (!empty($records))
{
    ?>
                $("#btnPrint").click(function () {
                    printElement(document.getElementById("reportContainer"));
                    window.print();
                });
    <?php
}
?>
        })
</script>