
<head>
    <title>MDI Enterprise Pte Ltd</title>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- css -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/public/css/style.css">
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans:400,300,700" />



</head>

<div class="modal-header" style="background-color:#E41B23 ;">
    <h4 class="text-center" style="color:white;"><?= lang("Order_ID"); ?> : <?= $order_number; ?></h4>
    <hr>
</div>


<div class="modal-body" style="background-color:#E41B23 ;">

    <div class="row">
        <div class="modallink">
            <a href="#" id="go-left" class="pull-left"><span class="glyphicon glyphicon-circle-arrow-left"></span> <?= lang("Previous_Status"); ?></a> <a href="#" id="go-right" class="pull-right"><?= lang("Next_Status"); ?> <span class="glyphicon glyphicon-circle-arrow-right"></span></a>

        </div>

        <div class="spacer">
        </div>

    </div>
    <div class="slider div-center"><!-- The slider -->
        <div><!-- A mandatory div used by the slider -->
            <!-- Each div below is considered a slide -->
            <?php
            if (!empty($previous_status))
            {
                foreach ($previous_status as $idx => $rec)
                {
                    $previous_date = isset($rec['date']) ? $rec['date'] : false;
                    ?>
                    <div class="a"><p style="color:#dedede;"><i> <?= lang("Previous_Status"); ?> :  <?php echo lang($rec['status']) ?></p><img src="<?php echo base_url(); ?>assets/public/image/<?php echo $rec['status'] ?>.png" alt="" height="90px" width="90px"><br><p style="color:#dedede;">
                            <?php
                            if ($previous_date == false)
                            {
                                echo $previous_date;
                            }
                            else
                            {
                                echo lang("Date");
                                echo " : ";
                                echo date('d/m/y', strtotime($previous_date));
                            }
                            ?> </i></div>
                        <?php
                    }
                }
                ?>
          
                <?php 
                if (!empty($current_status))
                {
                    foreach ($current_status as $idx => $rec)
                
                {
                    ?>
                <div class="b"><p style="color:#dedede;"><?= lang("Current_Status"); ?> : <i class="p-white"> <?= lang($rec['status']); ?></i></p><img src="<?php echo base_url(); ?>assets/public/image/<?= $rec['status']; ?>.png" alt="<?= lang($rec['status']); ?>" height="90px" width="90px">
                    <br><p style="color:white;"><?= lang("Date"); ?> : <?= date('d/m/y', strtotime($rec['date'])) ?>
                        <?php
                        if (!empty($receiver))
                        {
//                            p($receiver);
                            ?>
                        <br><p style="color:white;"><?= lang("receiver"); ?> : <?= $receiver ?>

                <?php } ?>     
                </div>
                <?php } }
            else
            { ?>
            <div class="b"><p style="color:#dedede;"> <h1> Order in Process </h1></p></div>
            <?php } ?>
            <!--<div class="c"><p style="color:#dedede;"><i> Next Status : JAKARTA</p><img src="<?php echo base_url(); ?>assets/public/image/ready_for_receiving_at_jakarta.png" alt="Shipped Icon" height="90px" width="90px"><br><p style="color:#dedede;">Date : --</i></div>-->

        </div>

    </div>


    <?php
    if (!empty($image_id))
    {
        ?>

        <div class="spacer">
        </div>

        <hr>   
        <div class="div-center">

            <h4 class="text-center" style="color:white;"> <?= lang("Image_download_:_Available"); ?></h4>
            <div style="text-align: center;">
    <?php
    foreach ($image_id as $row)
    {
        ?>
                    <div style="width:32%; display: inline-block; margin-bottom: 4%;">
                        <img src="<?php echo base_url(); ?>assets/dynamic/jkt_images/extracted_images/<?= $row['master_id']; ?>/<?= $row['name']; ?>" alt="Download Image" height="100px" width="100px">


                        <a id="submit_button" name="submit_button" class="btn btn-download" href="<?php echo base_url(); ?>public/index/downloadJakartaImage/<?= $row['id'] ?>">Download</a>
                    </div>
        <?php } ?>
            </div>

            <div style="clear: both;"></div>
        </div>
<?php } ?>

    <!--    <div class="spacer">
        </div>-->


    <script>
        $(".slider").diyslider({
            width: "270px", // width of the slider
            height: "220px", // height of the slider
            display: 1, // number of slides you want it to display at once
            start: <?= count($previous_status) ?> + 1,
            animationDuration: 500,
            loop: false // disable looping on slides
        }); // this is all you need!

        // use buttons to change slide
        $("#go-left").bind("click", function () {
            // Go to the previous slide
            $(".slider").diyslider("move", "back");
        });
        $("#go-right").bind("click", function () {
            // Go to the previous slide
            $(".slider").diyslider("move", "forth");
        });

    </script>


    <script src="<?php echo base_url(); ?>assets/public/js/jquery.diyslider.min.js"></script>
</div>	

<div class="modal-footer" style="background:#BC3B1B;" >
    <button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true"><?= lang("Close"); ?></button> 


</div>	


