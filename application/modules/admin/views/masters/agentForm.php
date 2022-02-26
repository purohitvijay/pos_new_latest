<div class="page-content main_container_padding">
    
    <div class="row">
            <div class="col-sm-12">
                    <div class="box box-bordered box-color">
                            <div class="box-title">
                                    <h3>
                                            <i class="fa fa-th-list"></i><?php echo $form_caption; ?></h3>
                            </div>
                            <div class="box-content nopadding">
                                <form action="<?php echo base_url(); ?>admin/masters/addAgent" class="form-horizontal form-bordered" method='post' id="boxForm" name='boxForm'>
                                            
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
                                                    <label for="name" class="control-label col-sm-2">Name<span class="required">*</span></label>
                                                    <div class="col-sm-10">
                                                        <input type="hidden" name="id" value="<?php echo set_value('id', empty($id) ? "" : $id); ?>" />
                                                        <input id="name"  type="text" class="form-control" placeholder="Enter Name" name="name" value="<?php echo set_value('name', empty($name) ? "" : $name); ?>" style="width:50%" required/>
                                                    </div>
                                            </div>
                                            <div class="form-group">
                                                    <label for="email" class="control-label col-sm-2">Email</label>
                                                    <div class="col-sm-10">
                                                            <input id="email" type="text" class="form-control" placeholder="Enter Email" name="email" value="<?php echo set_value('email', empty($email) ? "" : $email); ?>" style="width:50%"/>
                                                    </div>
                                            </div>
                                            <div class="form-group">
                                                    <label for="mobile" class="control-label col-sm-2">Mobile</label>
                                                    <div class="col-sm-10">
                                                            <input id="mobile" type="text" class="form-control" placeholder="Enter Mobile" name="mobile" value="<?php echo set_value('mobile', empty($mobile) ? "" : $mobile); ?>" style="width:50%"/>
                                                    </div>
                                            </div>
                                            <div class="form-group">
                                                    <label for="phone" class="control-label col-sm-2">Phone</label>
                                                    <div class="col-sm-10">
                                                            <input id="phone" type="text" class="form-control" placeholder="Enter Phone" name="phone" value="<?php echo set_value('phone', empty($phone) ? "" : $phone); ?>" style="width:50%"/>
                                                    </div>
                                            </div>
                                            <div class="form-group">
                                                    <label for="Address" class="control-label col-sm-2">Address</label>
                                                    <div class="col-sm-10">
                                                            <textarea id="address" type="text" class="form-control" placeholder="Enter Address" name="address" style="width:50%"><?php echo set_value('address', empty($address) ? "" : $address); ?></textarea>
                                                    </div>
                                            </div>
                                            <div class="form-group">
                                                    <label for="joining_date" class="control-label col-sm-2">Date Of Joining</label>
                                                    <div class="col-sm-10">
                                                            <input id="joining_date" type="text" class="form-control datepick" name="joining_date" value="<?php echo set_value('joining_date', empty($joining_date) ? "" : $joining_date); ?>" style="width:10%"/>
                                                    </div>
                                            </div>
                                            <div class="form-group">
                                                    <label for="commission" class="control-label col-sm-2">Commission ($)</label>
                                                    <div class="col-sm-10">
                                                        <input size="5" maxlength="5" type="commission" class="form-control" step='0.01' value='0.00' placeholder='0.00'  name="commission" value="<?php echo set_value('commission', empty($commission) ? "" : $commission); ?>" style="width:7%"/>
                                                    </div>
                                            </div>
                                    
                                            <div class="form-actions col-sm-offset-2 col-sm-10">
                                                    <button type="submit" class="btn btn-primary"><?php echo mlLang('lblSubmitBtn'); ?></button>
                                                    <a href="<?php echo base_url(); ?>admin/masters/agentList" class="btn default"><?php echo mlLang('lblBackBtn'); ?></a>
                                            </div>
                                    </form>
                            </div>
                    </div>
            </div>
    </div>
    
</div>

<script type="text/javascript">    
$(document).ready(function (){

    $('.datepick').datepicker({
        dateFormat: "dd/mm/yy"
    })
})
</script>