
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

     <?php
    if (!empty($image_id))
    {
        ?>
        <div class="div-center">

            <h4 class="text-center" style="color:white;"> <?= lang("Image_download_:_Available"); ?></h4>
            <div style="width:600px;text-align: center;">
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


