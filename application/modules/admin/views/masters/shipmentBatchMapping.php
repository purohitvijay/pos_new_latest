<div class="page-content main_container_padding">
    <div class="pull-right">
        <div class="right-btn-add"> <a href="#" class="btn default" id="add_new_box"><?php echo mlLang('lblAddNewBtn'); ?></a> </div>       
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-bordered box-color">
                <div class="box-title">
                    <h3>
                        <i class="fa fa-th-list"> Define Box Mapping For Shipment Batch <?php echo $shipment_batch_name;?></i></h3>
                </div>
                <div class="box-content nopadding">
                    <form action="<?php echo base_url(); ?>admin/masters/saveShipmentBatchMapping" class="form-horizontal form-bordered" method='post' id="boxForm" name='boxForm'>
                        <input type="hidden" id="shipment_batch_id" name="shipment_batch_id" value="<?php echo $shipment_batch_id; ?>" />
                        <input type="hidden" id="load_plan_status" name="load_plan_status" value="<?php echo $load_plan_status; ?>" />
                        <div class="form-group">
                            <label for="" class="control-label col-sm-2"><b>Box Name<b></label>
                                        <div class="col-sm-10">
                                            <label class="form-lable col-md-2"><b>Total Quantity</b></label>
                                            <label class="form-lable col-sm-1"><b>Scanned</b></label>
                                        </div>

                        </div>
                        <div id="shipment_batch_box_mapping">
                            <?php
                            if (!empty($box_arr))
                            {
                                foreach ($box_arr as $idx => $record)
                                {
                                    ?>
                                    <div class="form-group">
                                        <label for="password" class="control-label col-sm-2"><?php echo $record['name']; ?></label>
                                        <div class="col-sm-10">
                                            <input type="hidden" class="form-control box_id" name="box_id[]" value="<?php echo $record['box_id']; ?>" />
                                            <input type="text" class="form-control input_name" placeholder="Quantity" name="box_quantity[]" value="<?php echo $record['total']; ?>" style="width:8%;float:left;" onkeypress="return isNumber(event)" />
                                            <?php 
                                            if($load_plan_status == "edit")
                                            {
                                               echo "<input type='text' class='form-control input_name' name='box_scanned[]' value='".$record['scanned_quantity']."' style='width:8%; margin-left:80px!important; float:left;' readonly/>";
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                            else
                            {
                                ?>
                                 <div class="form-group">
                                     <label for="" class="control-label col-sm-2"></label>
                                        <div class="col-sm-10">
                                            <label class="form-lable col-md-2">No Records Found.</label>
                                        </div>

                                </div>
                          <?php
                          }
                            ?>
                        </div>
                        <div class="form-actions col-sm-offset-2 col-sm-10">
                            <button type="submit" id="submitBtn" class="btn btn-primary"><?php echo mlLang('lblSubmitBtn'); ?></button>
                            <a href="<?php echo base_url(); ?>admin/masters/shipmentBatchList" class="btn default"><?php echo mlLang('lblBackBtn'); ?></a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div id="loadingDiv_bakgrnd">
        <span id="img_load_chart">Loading...</span>
    </div>
</div>
<script type="text/javascript">
        $('document').ready(function () { 
            $('#submitBtn').click(function(e){
                e.preventDefault();
                 $(".box_id").each(function (index, item)
                {
                    $(this).removeClass('errorBox');
                    $(this).closest('div').find('.errorTxt').remove();
                    if($(this).val() == '0')
                    {
                        $(this).addClass('errorBox');
                        $(this).after('<span class="errorTxt"> Please select box.</span>');
                    }
                });
                
                var isError = $("body").find(".errorBox");
                if (isError.length == 0)
                    {
                        $('#submitBtn').attr('disabled', false);
                        $('#boxForm').submit();
                    }
                else
                    {
                        $('#submitBtn').attr('disabled', true);
                    }
               });
               
            var load_plan_status = $('#load_plan_status').val();

            if (load_plan_status == "edit")
            {
                $('#submitBtn').attr('disabled', true);
            }

            $('#add_new_box').click(function (e) {
                e.preventDefault();
                $('#loadingDiv_bakgrnd').show();
                $.ajax({
                    type: 'POST',
                    url: '<?php echo base_url(); ?>admin/masters/addShipmentBatchBox',
                    dataType: 'html',
                    contentType: false,
                    success: function (data)
                    {
                        $('#shipment_batch_box_mapping').append(data);
                        $('#loadingDiv_bakgrnd').hide();
                    }
                });

            });
            $('body').on('change', '.box_selected', function () {
                var selectedValue = $(this).val();
                var arr = [];
                var $this = $(this);

                $(".box_id").each(function (index, item)
                {
                    arr.push($(this).val());
                    $(this).removeClass('errorBox');
                    $(this).closest('div').find('.errorTxt').remove();
                    if($(this).val() == '0')
                    {
                        $(this).addClass('errorBox');
                        $(this).after('<span class="errorTxt"> Please select box.</span>');                        
                    }
                });
                
                var isError = $("body").find(".errorBox");
                if (isError.length == 0)
                    {
                        $('#submitBtn').attr('disabled', false);                                
                    }
                else
                    {
                        $('#submitBtn').attr('disabled', true);
                    }
                    
                var isDuplicate = hasDuplicates(arr)
                if (isDuplicate)
                {
                    $this.val('0');
                    alert('Box already selected.');
                }

            });

            $('body').on('focusout', '.input_name', function () {
                if (load_plan_status == "edit")
                {
                    var quantity = $(this).val();
                    var box_id = $(this).closest('div').find('.box_id').val();
                    var $this = $(this);

                    var shipment_batch_id = $('#shipment_batch_id').val();

                    $('#loadingDiv_bakgrnd').show();

                    $.ajax({
                        type: 'POST',
                        dataType: 'JSON',
                        data: {box_id: box_id, quantity: quantity, shipment_batch_id: shipment_batch_id},
                        url: "<?= base_url(); ?>admin/masters/checkShipmentBoxMappingQuantity",
                        success: function (data) {

                            $this.removeClass('okBox');
                            $this.removeClass('errorBox');
                            $this.closest('div').find('.errorTxt').remove();

                            var status = data.status;

                            if (status == "error")
                            {
                                var errorMsg = data.msg;

                                $this.addClass('errorBox');
                                $this.after('<span class="errorTxt"> ' + errorMsg + '</span>');
                            }
                            else
                            {
                                $this.addClass('okBox');
                            }
                            var isError = $("body").find(".errorBox");
                            if (isError.length == 0)
                            {
                                $('#submitBtn').attr('disabled', false);
                            }
                            else
                            {
                                $('#submitBtn').attr('disabled', true);
                            }
                            $('#loadingDiv_bakgrnd').hide();

                        }

                    });
                }
            });
        });

        function isNumber(evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        }
        function hasDuplicates(array) {
            var valuesSoFar = Object.create(null);
            for (var i = 0; i < array.length; ++i) {
                var value = array[i];
                if (value in valuesSoFar) {
                    return true;
                }
                valuesSoFar[value] = true;
            }
            return false;
        }
</script>
<style>
    .errorTxt{
        clear : both;
        margin-left : 10px;
        margin-top : 10px!important;
    }
    </style>