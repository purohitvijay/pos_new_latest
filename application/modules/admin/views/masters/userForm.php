<div class="page-content main_container_padding">

    <div class="row">
        <div class="col-sm-12">
            <div class="box box-bordered box-color">
                <div class="box-title">
                    <h3>
                        <i class="fa fa-th-list"></i><?php echo $form_caption; ?></h3>
                </div>
                <div class="box-content nopadding">
                    <form action="<?php echo base_url(); ?>admin/masters/addUser" class="form-horizontal form-bordered" method='post' id="userForm" name='userForm'>

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
                            <label for="username" class="control-label col-sm-2">Username<span class="required">*</span></label>
                            <div class="col-sm-10">
                                <input id="username" type="text" class="form-control" placeholder="Enter Username" name="username" value="<?php echo set_value('username', empty($username) ? "" : $username); ?>" style="width:50%" required/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="control-label col-sm-2">Email<span class="required">*</span></label>
                            <div class="col-sm-10">
                                <input id="email" type="text" class="form-control" placeholder="Enter Email" name="email" value="<?php echo set_value('email', empty($email) ? "" : $email); ?>" style="width:50%" required/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label  class="col-sm-2 control-label">Role<span class="required">*</span></label>
                            <div class="col-sm-10">
                                <?php echo form_dropdown('roleId', $roleArr, empty($roleId) ? NULL : $roleId, 'id = "roleId" class="form-control" style="width:30%" required'); ?>
                            </div>
                        </div> 
                        <div class="form-group">
                            <label  class="col-sm-2 control-label">Active<span class="required">*</span></label>
                            <div class="col-sm-10">
                                <?php echo form_dropdown('active', $activeArr, empty($active) ? NULL : $active, 'id = "active" class="form-control" style="width:30%" required'); ?>
                            </div>
                        </div> 
                        
                        <div class="form-group">
                            <label  class="col-sm-2 control-label">Geo Type<span class="required">*</span></label>
                            <div class="col-sm-10">
                                <?php echo form_dropdown('geo_type', $geoTypeArr, empty($geo_type) ? NULL : $geo_type, 'id = "geo_type" class="form-control" style="width:30%" required'); ?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="password" class="control-label col-sm-2">Password<span class="required">*</span></label>
                            <div class="col-sm-10">
                                <input id="password" type="password" class="form-control" placeholder="Enter Password" name="password" style="width:50%" value="<?php echo set_value('password', empty($password) ? "" : $password); ?>" required/>
                            </div>
                        </div>
                        <div class="form-actions col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary"><?php echo mlLang('lblSubmitBtn'); ?></button>
                            <a href="<?php echo base_url(); ?>admin/masters/userList" class="btn default"><?php echo mlLang('lblBackBtn'); ?></a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>