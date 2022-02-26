<div class="container-fluid">
    <div class="row">
        <div class="pull-right" style="margin-top : 10px;">
            <?php
                if (!empty($records)) {
                    ?>
                    <button id="btnPrint" class="btn btn-primary" style="margin-right: 14px">
                        <i class="fa fa-print"></i>Print
                    </button>
                    <?php
                }
            ?>
        </div>

    </div>
    <div class="row" id="reportContainer"> 
            <div class="row">
                <div class="pull-right" style="margin-top : 10px;">
                    <button onclick="BottomFunction()" id="myBtn"  class="btn btn-primary" style="margin-right: 27px">Bottom</button>
                </div>
                <div class="pull-left" style="margin-left : 30px;">
<!--                   <h3>
                        POS TKI SERVICES
                    </h3> 
                    <h4>No 1 Kaki Bukit Road 1 # 03-50 Enterprise one Singapore 415934</h4>
                    <h4><span> Tel : (65) 6297 4805</span><span style="margin-left:20px;">Fax :(65) 6297 4827</span></h4>
                    <h4>Email : Pak Nursal</h4>-->
                    
                    <h4>Nama Pemberitahu / PPJK &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;:  PT INDOFAST TRASINDO</h4>
                    <h4>Nomor / Tanggal / BL &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : <?= $batch ?> /&nbsp;<?= $ship_onboard?> /&nbsp;<?= $bl_number?></h4>
                    <h4>Nomor / Tanggal / Packing List : <?= $batch ?> /&nbsp;<?= $ship_onboard?></h4>
                    <h4>Nomor Kontainer &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp; : <?= $container_number ?> </h4>
                    <h4>Nomor Segel &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : <?= $seal_number ?></h4>
                    </hr>
                </div>
            </div>
        <div class="col-sm-12">
            <div class="box box-color box-bordered">
                <style>
                    </style>
                    <script>
                        //Get the button
                        var mybutton = document.getElementById("myBtn");

                        // When the user scrolls down 20px from the top of the document, show the button
                        window.onscroll = function() {scrollFunction()};

                        function BottomFunction() 
                        {
                            window.scrollTo(0,document.body.scrollHeight);
                        }                    
                    </script>
                
                <?php
                    if (!empty($records)) { 
                        
                        ?>
                <h3>Manifest BEA SHP <?php echo $batch;?></h3>
               
                        <!--<div class="box-content nopadding">-->
                        <table class="table table-hover table-nomargin dataTable table-bordered dataTable-scroll-y " style="page-break-after: always" id="menuTable" >
                            <thead>
                                <tr>
                                    <th rowspan="2">No.</th>
                                    <th rowspan="2">Nomor Kemasan</th>
                                    <th rowspan="2">SIZE</th>
                                    <th rowspan="2">URAIAN BARANG</th>
                                    <th colspan="4" class="text-center">PENGIRIM</th>
                                    <th>QTY.</th>
                                    <!--<th>TUJUAN</th>-->
                                    <th>DESTINATION</th>
                                    <th colspan="4" class="text-center">PENERIMA</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th>No Paspor</th>
                                    <th>Nama</th>
                                    <th>Alamat</th>
                                    <th>Tel/HP</th>
                                    <th></th>
                                    <!--<th></th>-->
                                    <th></th>
                                    <th>Nama</th>
                                    <th>Alamat</th>
                                    <th>Kota Propinsi</th>
                                    <th>Tel/HP</th>
                                </tr>
        <?php
        $sno = 1;

//        $boxes_count = 0;

        foreach ($records as $index => $row) {
            $count = $row['count'];
            $boxes = explode('@@##@@', $row['box']);
            $locations = explode('@@##@@', $row['location']);
            $quantity = explode('@@##@@', $row['quantity']);
            $destination = explode('@@##@@', $row['destination']);
//                                    
            for ($i = 0; $i < $count; $i++) {

//                $boxes_count = $boxes_count + $quantity[$i];
                ?>
                                        <tr>
                                            <td><?= $sno++; ?></td>
                                            <td><?= $row['order_number'] ?></td>
                                            <td><?= ucwords($boxes[$i]) ?></td>
                                            <td><?= ucwords($row['recipient_item_list']) ?></td>
                                            <td><?= $row['passport_id_number']?></td>
                                            <td><?= ucwords($row['customer_name']) ?></td>
                                            <td><?= ucwords($row['customer_building_street']) ?></td>
                                            <td><?= $row['customer_mobile']?></td>
                                            <td><?= $quantity[$i] ?></td>
                                            <!--<td></td>-->
                                            <td><?= ucwords($destination[$i]) ?></td>
                                            <td><?= ucwords($row['recipient_name']) ?></td>
                                            <td><?= ucwords($row['building_street']) ?></td>
                                            <td><?= ucwords($locations[$i]) ?></td>
                                            <td><?= $row['recipient_mobile']?></td>
                                        </tr>
                <?php
            }
            
            if (!empty($boxes))
            {
                foreach ($boxes as $box_index => $box_name)
                {
                    if (empty($boxes_count[$box_name]))
                    {
                        $boxes_count[$box_name] = $quantity[$box_index];
                    }
                    else
                    {
                        $boxes_count[$box_name] += $quantity[$box_index];
                    }
                }
            }
        }
        ?>
                            </tbody>
                        </table>
<!--
-->                        <div>
<!--                            <h3>
                                <i class="fa fa-table"></i>
                                Summary
                            </h3>-->
                        </div>

        
                        <?php
                        if (!empty($boxes_count))
                        {
                        ?>
                            <br><br>
                            <table class="table table-nomargin dataTable table-bordered dataTable-scroll-y ">
                                <?php
                                $i = 0;
                                
                                $total_boxes = 0;
                                
                                foreach ($boxes_count as $box => $count)
                                {
                                    $total_boxes += $count;
                                    
                                    if ($i++ % 2 == 1)
                                    {
                                        //echo $i . 'in top';
                                        echo "<th style='width:40%'>$box</th><td style='width:10%'>$count</td></tr><tr>";
                                    }
                                    else
                                    {
//                                        /echo $i . 'in bottoms';
                                        echo "<tr><th style='width:40%'>$box</th><td style='width:10%'>$count</td>";
                                    }
                                }
                                
                                echo '</tr>';
//                                echo "<tr>"
//                                . " <th>Delivery Total</th>"
//                                        . "<td><b>".$delivery." </b></td>"
//                                . " <th>Collection Total</th>"
//                                        . "<td><b>".$collection." </b></td>"
//                                . "</tr>";
                                echo "<tr>"
                                . " <th>Total</th>"
                                        . "<td><b>".$total_boxes." </b></td>"
                                . "</tr>";
                                ?>
                            </table>
                    <!--</div>-->
                    <?php
                        }
                    } else {
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
    $(document).ready(function() {

<?php
    if (!empty($records)) {
        ?>
                document.getElementById("btnPrint").onclick = function() {
                    printElement(document.getElementById("reportContainer"));
                    window.print();
                }
        <?php
    }
?>
    })
</script>