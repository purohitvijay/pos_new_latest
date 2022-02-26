<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title>Online Tracking</title>
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

						<img class="pull-left" src="<?php echo base_url(); ?>assets/public/image/logo-big.png" alt="Tracking Image"><h1 class="h1-top pull-left"></h1>
				
					
				</div>
			</div>
		<hr>
		

	</div>	
		
	<div id="modalContent">
	<div class="row text-center">
			
	
		<form class="form-horizontal" id="form_horizontal" action="#" method="post">
			
			<div class="form-outer2 div-center">

				<!-- Form Name -->
				<legend>Please enter following information to download your photos</legend>

				<!-- Text input-->
				<div class="form-group">

				  <input id="textinput" name="phone_input" placeholder="Phone Number" class="form-control input" required="" type="text">
					
				</div>
				

				<!-- Text input-->
				<div class="form-group">

				  <input id="order_input" name="order_input" placeholder="Order Number" class="form-control input-md" required="" type="text">
				</div>

				<!-- Button -->
				<div class="form-bottom">
				<div class="form-group">
					
						<button id="submit_button" name="submit_button" class="btn btn-primary" data-toggle="modal"  href="result.php">Check Now</button>
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



</body>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/public/js/jquery.fittext.js"></script>
	<script src="<?php echo base_url(); ?>assets/public/js/app.js"></script>
	<script src="<?php echo base_url(); ?>assets/public/js/bootstrap.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/public/js/jquery.diyslider.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/public/js/jquery-validate/jquery.validate.min.js"></script>
        
	<script>
		jQuery(".h1-top").fitText(1.0 , { minFontSize: '21px', maxFontSize: '40px' });
	
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
                                url: "<?php echo base_url().'public/'.$lang.'/index/showsmsresult'; ?>",
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