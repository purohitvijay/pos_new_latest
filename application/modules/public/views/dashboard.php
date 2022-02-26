<!doctype html>

<html lang="en">
    <head>
        <meta charset="utf-8">

        <title><?= lang("Online_Tracking") ?></title>
        <meta name="description" content="The HTML5 Herald">
        <meta name="author" content="Postki Tracking">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

        <!--[if lt IE 9]>
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/public/css/style.css">
        <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans:400,300,700" />



    </head>

    <body>

        <div class="red_top">

            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6" >

                    <img class="logo pull-left" src="<?php echo base_url(); ?>assets/public/image/track_icon.png" alt="Tracking Image"><h1 class="h1-top pull-left"><?= lang("ONLINE_TRACKING"); ?> </h1>

                </div>
            </div>
            <hr>	
            <div class="row text-center">
                <h3>: : <?= lang("Available_Status") ?> : :</h3>
            </div>


            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-lg-offset-2 col-md-12 col-sm-12 col-xs-3">

                        <div class="div-inline">
                            <img src="<?php echo base_url(); ?>assets/public/image/booking_attended_by_driver.png" alt="Collect Icon" height="100px" width="100px">
                            <div>
                                <p class="text-center"><?= lang("Collection") ?></p>
                            </div>
                        </div>

                        <div class="div-inline arrow-right">
                            <img src="<?php echo base_url(); ?>assets/public/image/arrow.png" alt="Collect Icon" height="90px" width="90px">
                        </div>

                        <div class="div-inline arrow-down">
                            <img src="<?php echo base_url(); ?>assets/public/image/arrow_down.png" alt="Collect Icon" height="90px" width="90px">
                        </div>


                        <div class="div-inline">
                            <img src="<?php echo base_url(); ?>assets/public/image/ready_for_receiving_at_jakarta.png" alt="Collect Icon" height="100px" width="100px">
                            <p class="text-center"><?= lang("Shipped") ?></p>
                        </div>

                        <div class="div-inline arrow-right">
                            <img src="<?php echo base_url(); ?>assets/public/image/arrow.png" alt="Collect Icon" height="90px" width="90px">
                        </div>

                        <div class="div-inline arrow-down">
                            <img src="<?php echo base_url(); ?>assets/public/image/arrow_down.png" alt="Collect Icon" height="90px" width="90px">
                        </div>


                        <div class="div-inline">
                            <img src="<?php echo base_url(); ?>assets/public/image/collected_at_warehouse.png" alt="Collect Icon" height="100px" width="100px">
                            <p class="text-center"><?= lang("Jakarta") ?></p>
                        </div>

                        <div class="div-inline arrow-right">
                            <img src="<?php echo base_url(); ?>assets/public/image/arrow.png" alt="Collect Icon" height="90px" width="90px">
                        </div>

                        <div class="div-inline arrow-down">
                            <img src="<?php echo base_url(); ?>assets/public/image/arrow_down.png" alt="Collect Icon" height="90px" width="90px">
                        </div>

                        <div class="div-inline">
                            <img src="<?php echo base_url(); ?>assets/public/image/recipient_received.png" alt="Collect Icon" height="100px" width="100px">
                            <p class="text-center"><?= lang("Received") ?></p>
                        </div>

                    </div>
                </div>		

            </div>


            &nbsp
            <div>
                &nbsp
            </div>

        </div>	

        <div id="modalContent">
            <div class="row text-center">


                <form class="form-horizontal" id="form_horizontal" action="#" method="post">

                    <div class="form-outer div-center">

                        <!-- Form Name -->
                        <legend><?= lang("Please_enter_following_information") ?></legend>

                        <!-- Text input-->
                        <div class="form-group">

                            <input id="phone_input" name="phone_input" placeholder="<?= lang("Phone_Number") ?>" class="form-control input" required="" type="text">

                        </div>


                        <!-- Text input-->
                        <div class="form-group">

                            <input id="order_input" name="order_input" placeholder="<?= lang("Order_Number") ?>" class="form-control input-md" required="" type="text">
                        </div>

                        <!-- Button -->
                        <div class="form-bottom">
                            <div class="form-group">
                                <button id="submit_button" name="submit_button" class="btn btn-primary" data-toggle="modal" href="result.php"><?= lang("Check_Now") ?></button>

<!--<input type="submit" id="submit_button" name="submit_button" class="btn btn-primary" value="Check Now">-->
                            </div>
                        </div>

                    </div>

                </form>
            </div>

            &nbsp

        </div>

        <!-- Modal -->
        <div class="modal fade" id="remoteModal" tabindex="-1" role="dialog" aria-labelledby="remoteModalLabel" aria-hidden="true">

            <div class="modal-dialog">  
                <div class="modal-content" id="modal_body"> 
                </div>  
            </div>  
        </div> 



    </body>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <!--<script src="<?php echo base_url(); ?>assets/public/js/jquery.min.1.12.0.js"></script>-->
    <script src="<?php echo base_url(); ?>assets/public/js/jquery.fittext.js"></script>
    <script src="<?php echo base_url(); ?>assets/public/js/app.js"></script>
    <script src="<?php echo base_url(); ?>assets/public/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/public/js/jquery.diyslider.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/public/js/jquery-validate/jquery.validate.min.js"></script>


    <script>
        jQuery(".h1-top").fitText(1.0, {minFontSize: '21px', maxFontSize: '40px'});

        jQuery(document).ready(function ($) {
            
            <?php  $lang = ($this->uri->segment(2)) ? $this->uri->segment(2) : "en"; ?>
            $('#form_horizontal').validate({
                rules: {
                    phone_input: {
                        required: true
                    },
                    order_input: {
                        required: true
                    }
                },
                messages: {
                    phone_input: {
                        required: "<?= lang("Please_Enter_Phone_Number") ?>."
                    },
                    order_input: {
                        required: "<?= lang("Please_Enter_Order_Number") ?>."
                    }
                },
                submitHandler: function (form) {
                    $.ajax({
                        type: 'POST',
                        data: $('#form_horizontal').serialize(),
                        dataType: 'json',
                        url: "<?php echo base_url().'public/'.$lang.'/index/validate'; ?>",
                        success: function (res)
                        {
                            var status = res.status;
                            var msg = res.msg;
                            var view = res.view;
                            if (status == "error")
                            {
                                $('#errorMsg_reg').removeClass('hidden');
                                $('#errorMsg_reg').html('<span>' + msg + '</span>');
                                alert(msg);
                            }
                            else
                            {
//                                    alert(msg);
                                $('#modal_body').html(view);
                                $('#remoteModal').modal('show');

                                $(':input', '#form_horizontal')
                                        .removeAttr('checked')
                                        .removeAttr('selected')
                                        .not(':button, :submit, :reset, :hidden, :radio, :checkbox')
                                        .val('');
                            }
                        }
                    });
                }
            });
        });

    </script>

</html>