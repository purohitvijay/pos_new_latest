<div class="page-content main_container_padding">

    <input type="hidden" name="luckyDrawId" id="luckyDrawId" value="0">
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-bordered box-color">
                <div class="box-title">
                    <?php
                        if (empty($id))
                        {
                            $title = "Add Lucky Draw";
                        }
                        else
                        {
                            $title = "Edit Lucky Draw <b>$name</b>";  
                        }
                    ?>
                    <h3><i class="fa fa-dollar"></i><?=$title?></h3>
                    <ul class="tabs">
                        <li class="active fake-link-days-class" rel="365">
                            <a  href="<?php echo base_url(); ?>admin/luckyDraw/luckyDrawList?haveSideBar=0">Back</a>
                        </li>
                    </ul>
                </div>
                <div class="box-content nopadding">
                    <form action="<?php echo base_url(); ?>admin/luckyDraw/luckyDrawCount" class="form-horizontal form-bordered" method='post' id="luckyDraw" name='luckyDraw'>

                        <?php
                        $errors = validation_errors();
                        if (!empty($errors)) {
                            ?>
                            <div class="alert alert-danger active">
                                <button class="close" data-dismiss="alert"></button>
                                <span><?php echo $errors; ?></span>
                            </div>
                            <?php
                        }
                        
                        ?> 

                        <div class="form-group">
                            <label for="name" class="control-label col-sm-2">Name</label>
                            <div class="col-sm-10 input-group">
                                <input id="name" type="text" class="form-control" name="name" value="<?php echo set_value('name', empty($name) ? "" : $name); ?>" style="width:25%" required/>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="number_of_prize" class="control-label col-sm-2">No. Of Prizes</label>
                            <div class="col-sm-10 input-group">
                                <input min="1" id="number_of_prize" type="number" class="form-control" name="number_of_prize" value="<?php echo set_value('number_of_prize', empty($number_of_prize) ? "" : $number_of_prize); ?>" style="width:15%" required/>
                            </div>
                        </div>

                        <?php
                        if (empty($id))
                        {
                        ?>
                        <div class="form-group">
                            <label for="shipment_date_from" class="control-label col-sm-2">Shipment Date From</label>
                            <div class="col-sm-10 input-group">

                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                <input id="shipment_date_from" type="text" class="form-control datepick" name="shipment_date_from" value="<?php echo set_value('shipment_date_from', empty($shipment_date_from) ? "" : $shipment_date_from); ?>" style="width:15%" required/>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="shipment_date_to" class="control-label col-sm-2">Shipment Date To</label>
                            <div class="col-sm-10 input-group">

                                <span class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </span>
                                <input id="shipment_date_to" type="text" class="form-control datepick" name="shipment_date_to" value="<?php echo set_value('shipment_date_to', empty($shipment_date_to) ? "" : $shipment_date_to); ?>" style="width:15%" required/>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="agents_to_be_excluded" class="control-label col-sm-2">Agents To Be Excluded</label>
                            <div class="col-sm-10 input-group">

                                <?php
                                if (!empty($agents))
                                {
                                ?>
                                <Select  style="margin-bottom:2px;width:200px;" name="excluded_agent_id[]" id="agentIdSelect"  class="form-control " multiple="">
                                        <!--<option value="">--Select--</option>-->
                                <?php
                                        foreach ($agents as $index => $row)
                                        {
                                            $selected = $row['is_excluded_in_lucky_draw'] == 'yes' ? 'selected': '';
                                ?>
                                            <option <?=$selected?> value="<?=$row['id']?>"><?=$row['name']?></option>
                                <?php
                                        }
                                ?>
                                    </Select>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                        
                        <div class="form-group hidden" id="luckyDrawCount">
                            <label  class="control-label col-sm-2">Total Participants</label>
                            <div class="col-sm-10">
                                <span id="count"></span>
                            </div>
                        </div>

                        <div class="form-group hidden" id="alreadyExist">
                            <label  class="control-label col-sm-2">Duplicate Participants</label>
                            <div class="col-sm-10">
                                <span id="alreadyExistParticipant"></span>
                            </div>
                        </div>

                        <div class="form-group hidden" id="totalMigrate">
                            <label  class="control-label col-sm-2">Total Participants Imported</label>
                            <div class="col-sm-10">
                                <span id="totalAddedParticipant"></span>
                            </div>
                        </div>
                        
                        <?php
                        }
                        ?>

                        <div class="form-actions col-sm-offset-2 col-sm-10">
                            <!--<button class="btn-primary btn" id="clearSearchButton" type="button">Reset</button>-->
                            
                            <?php
                            if (empty($id))
                            {
                            ?>
                            <button type="submit" class="btn btn-primary hidden" id="migrationData">Push Data To Lucky Draw DB</button>
                            <button type="submit" class="btn btn-primary" id="submitBtn"><?php echo mlLang('lblSubmitBtn'); ?></button>
                            <?php
                            }
                            else
                            {
                            ?>
                            <input type="hidden" name="id" value="<?=$id?>">
                            <button type="submit" class="btn btn-primary" id="submitEditBtn"><?php echo mlLang('lblSubmitBtn'); ?></button>
                            <?php
                            }
                            ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
<div id="loadingDiv_bakgrnd">
    <span id="img_load_chart">Loading...</span>
</div>
<script type="text/javascript">
    var isCheck = "";
    $(document).ready(function() {
        $("#agentIdSelect").multiselect();
        $("#shipment_date_from").datepicker
                ({
                    dateFormat: 'dd/mm/yy',
                    onSelect: function(selected)
                    {
                        $("#shipment_date_to").datepicker("option", "minDate", selected)
                    }
                });
        $('#shipment_date_to').datepicker
                ({
                    dateFormat: 'dd/mm/yy',
                    onSelect: function(selected)
                    {
                        $("#shipment_date_from").datepicker("option", "maxDate", selected)
                    }
                });
        // $('#submitBtn').click(function(event){
        $('#luckyDraw').submit(function(e) {
            e.preventDefault();
            var $form = $(this);
            // check if the input is valid
            if (!$form.valid())
                return false;
//            event.preventDefault();
            
            $('#img_load_chart').html('Loading....');
            
            $('#loadingDiv_bakgrnd').show();
            $.ajax({
                type: 'POST',
                data: $('#luckyDraw').serialize(),
                url: "<?= base_url(); ?>admin/luckyDraw/getLuckuDrawParticipantCount",
                success: function(data) {
                    $('#count').html(data);
                    $('#luckyDrawCount').removeClass('hidden');
                    if (data > 0)
                    {
                        $('#migrationData').removeClass('hidden');
                    }
                    $('#loadingDiv_bakgrnd').hide();
                }
            });
        });
        $('#migrationData').click(function(event) {
            event.preventDefault();
            $('#loadingDiv_bakgrnd').show();
            $.ajax({
                type: 'POST',
                dataType: 'JSON',
//                data : $('#luckyDraw').serialize(),
                url: "<?= base_url(); ?>admin/luckyDraw/checkIsDrawAlreadyExist/"+$('#luckyDrawId').val(),
                success: function(data) {
                    $('#loadingDiv_bakgrnd').hide();
                    var status = data.status;
                    if (status == "error")
                    {
                        var msg = data.msg;                        
                        alert('There were some imports encountered for which winner was not declared. Do you still want to proceed further?');
                        return false;
                    }
                    else
                    {
                        migrateData();
                    }
                }
            });
        });
        
        $('#submitEditBtn').click(function(event) {
            event.preventDefault();
            $('#loadingDiv_bakgrnd').show();
            
            $.ajax({
                type: 'POST',
                dataType: 'JSON',
                data : $('#luckyDraw').serialize(),
                url: "<?= base_url(); ?>admin/luckyDraw/updateLuckyDraw",
                success: function(data) {
                    $('#loadingDiv_bakgrnd').hide();
                    if(data.status == "success")
                    {
                        window.location.href='<?=base_url()?>admin/luckyDraw/luckyDrawList?haveSideBar=0'
                    }
                    else
                    {
                        alert(data.msg);
                    }
                }
            });
        });
    });
    function migrateData()
    {
        $('#img_load_chart').html('Pushing data to Lucky Draw DB. Thanks for exhibiting patience in advance.');
        $('#loadingDiv_bakgrnd').show();
        $.ajax({
            type: 'POST',
            dataType: 'JSON',
            data: $('#luckyDraw').serialize(),
            url: "<?= base_url(); ?>admin/luckyDraw/importDataToLuckyDrawDB",
            success: function(data) {
                if (data.status == 'success')
                {
                    $('#alreadyExistParticipant').html(data.alreadyExist);
                    $('#totalAddedParticipant').html(data.total);
                    $('#luckyDrawId').val(data.luckyDrawId);
                    $('#alreadyExist').removeClass('hidden');
                    $('#totalMigrate').removeClass('hidden');
                    $('#loadingDiv_bakgrnd').hide();
                    
                    window.location.href='<?=base_url()?>admin/luckyDraw/luckyDrawList?haveSideBar=0'
                }
            }
        });
    }
</script>