<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title><?= lang("customer") ?></title>
        <meta name="description" content="The HTML5 Herald">
        <meta name="author" content="Postki Tracking">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/public/css/style.css">
        <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans:400,300,700" />
        
        <style>            
           input[type="radio"] 
            {
                float:none;
                margin:0 auto;
                width:50px;
                background: #bc3b1b;
                color:white;
                border: 0px solid #CCC;
            }
        </style>
    </head>
    <body>
        <div class="red_top">	
            <div class="row text-center">
                <h3>: : <?= lang("customer") ?> : :</h3>
            </div>
        </div>	
        <div id="modalContent">
            <div class="row text-center" style="margin: 0;">
                <form class="form-horizontal" id="form_horizontal" action="#" enctype="multipart/form-data">
                    <div class="form-outer div-center">
                        <!-- Form Name -->
                        <legend><?= lang("Please_enter_following_information") ?></legend>
                        <!-- Text input-->
                        <div class="form-group">
                            <input id="mobile_number" name="mobile_number" placeholder="<?= lang("mobile_number") ?>" class="form-control input-md" required="" type="text">
                        </div>
                        <div class="form-group customers" >
                            
                        </div>
                        <!-- Text input-->
                        <div class="form-group">
                            <input id="id_number" name="id_number" placeholder="<?= lang("id_number") ?>" class="form-control input" required="" type="text">
                        </div>
                        <!-- file input-->
                        <div class="form-group">
                            <input id="passport" name="passport" placeholder="<?= lang("passport") ?>" class="form-control input" required="" type="file">
                        </div>
                        <!-- Button -->
                        <div class="form-bottom">
                            <div class="form-group">
                                <button id="submit_button" name="submit_button" class="btn btn-success" style="background-color: green;color: white" data-toggle="modal" href="result.php"><?= lang("upload") ?></button>
                                <a class="btn btn-warning" style="width: 38%;margin-top: 21px;" href="<?= base_url("customer")?>">Cancel</a>
                                
                            </div>
                        </div>
                    </div>
                </form>
            </div>
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
    <script src="<?php echo base_url(); ?>assets/public/js/jquery.fittext.js"></script>
    <script src="<?php echo base_url(); ?>assets/public/js/app.js"></script>
    <script src="<?php echo base_url(); ?>assets/public/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/public/js/jquery.diyslider.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/public/js/jquery-validate/jquery.validate.min.js"></script>
    <script>
        jQuery(".h1-top").fitText(1.0, {minFontSize: '21px', maxFontSize: '40px'});

        $("#mobile_number").focusout(function()
        {
            $(".customers").html("");
            var val  = $(this).val();
            $.ajax({
                type: 'GET',
                data: {"mobile":val},
                dataType: 'json',
                url: "<?php echo base_url().'public/'.$lang.'/customer/get_customer_by_mobile'; ?>",
                success: function (res)
                {
                    var status = res.status;
                    var data = res.data;
                    if (status == "error")
                    {
                        alert(msg);
                    }
                    else
                    {
                        if(data.length == 1)
                        {       
                            $(".customers").html('<input type="hidden" id="customer_id" name="customer_id" value="'+data[0].id+'" >'+data[0].name);
                        }
                        else if(data.length > 1)
                        {
                            var html = '';
                            for ( var index in data) 
                            {
                                html +='<input type="radio" id="customer_id"  name="customer_id" value="'+data[index].id+'" >'+data[index].name;
                            }
                            $(".customers").html(html);
                        }
                        else
                        {                        
                            alert("This mobile number is customer not found");                        
                        }
                        
                    }
                }
            });
        });
        
        jQuery(document).ready(function ($) 
        {            
            <?php  $lang = ($this->uri->segment(2)) ? $this->uri->segment(2) : "en"; ?>
            $('#form_horizontal').validate({
                rules: 
                {
                    phone_input: {
                        required: true
                    },
                    order_input: {
                        required: true
                    }
                },
                messages: 
                {
                    phone_input: {
                        required: "<?= lang("Please_Enter_Mobile_Number") ?>."
                    },
                    order_input: {
                        required: "<?= lang("Please_Enter_ID_Number") ?>."
                    }
                },
                submitHandler: function (form) 
                {
                    var formData = new FormData($("#form_horizontal")[0]);
                     $.ajax({
                        type: 'POST',
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        url: "<?php echo base_url().'public/'.$lang.'/customer/customer_passport_update'; ?>",
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
                                $(':input', '#form_horizontal')
                                .removeAttr('checked')
                                .removeAttr('selected')
                                .not(':button, :submit, :reset, :hidden, :radio, :checkbox')
                                .val('');
                                $(".customers").html("");
                                        alert(msg);
                            }
                        }
                    });
                }
            });
        });
    </script>
</html>