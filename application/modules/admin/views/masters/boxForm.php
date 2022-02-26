<div class="page-content main_container_padding">
    
    <div class="row">
            <div class="col-sm-12">
                    <div class="box box-bordered box-color">
                            <div class="box-title">
                                    <h3>
                                            <i class="fa fa-th-list"></i><?php echo $form_caption; ?></h3>
                            </div>
                            <div class="box-content nopadding">
                                <form action="<?php echo base_url(); ?>admin/masters/addBox" class="form-horizontal form-bordered" method='post' id="boxForm" name='boxForm'>
                                            
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
                                                    <label for="textfield" class="control-label col-sm-2">Name<span class="required">*</span></label>
                                                    <div class="col-sm-10">
                                                        <input type="hidden" name="id" value="<?php echo set_value('id', empty($id) ? "" : $id); ?>" />
                                                        <input type="text" class="form-control" placeholder="Enter Name" name="name" value="<?php echo set_value('name', empty($name) ? "" : $name); ?>" style="width:50%" required/>
                                                    </div>
                                            </div>
                                            <div class="form-group">
                                                    <label for="password" class="control-label col-sm-2">Short Name<span class="required">*</span></label>
                                                    <div class="col-sm-10">
                                                            <input type="text" class="form-control" placeholder="Enter Short Name" name="short_name" value="<?php echo set_value('short_name', empty($short_name) ? "" : $short_name); ?>" style="width:50%"  required/>
                                                    </div>
                                            </div>
                                            <div class="form-group">
                                                    <label for="volume" class="control-label col-sm-2">Volume (M<sup>3</sup>)<span class="required">*</span></label>
                                                    <div class="col-sm-10">
                                                            <input type="text" class="form-control" placeholder="Enter Volume (in Cubic Meter)" name="volume" value="<?php echo set_value('volume', empty($volume) ? "" : $volume); ?>" style="width:25%"  required/>
                                                    </div>
                                            </div>
                                            <div class="form-group">
                                                    <label for="password" class="control-label col-sm-2">Description</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" placeholder="Enter Description" name="description" value="<?php echo set_value('description', empty($description) ? "" : $description); ?>"/>
                                                    </div>
                                            </div>
                                            <div class="form-group">
                                                    <label for="password" class="control-label col-sm-2">Order  Id<span class="required">*</span></label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" placeholder="Enter Order Id" name="order_id" value="<?php echo set_value('order_id', empty($order_id) ? "" : $order_id); ?>" required style="width:15%" />
                                                    </div>
                                            </div>
                                            <div class="form-group">
                                                    <label class="control-label col-sm-2">Collection Commission Base Amount<span class="required">*</span></label>
                                                    <div class="col-sm-10">
                                                        <input pattern='[+-][0-9.,]+([0-9]+)?' type='number' class="form-control" placeholder="Commission Amt" name="collection_commission" value="<?php echo set_value('collection_commission', empty($collection_commission) ? "" : $collection_commission); ?>" required style="width:15%" />
                                                        <input type="hidden" name="delivery_commission" value="<?php echo set_value('delivery_commission', empty($delivery_commission) ? "" : $delivery_commission); ?>"/>
                                                    </div>
                                            </div>
                                                        
                                    
                                            <div class="form-actions col-sm-offset-2 col-sm-10">
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                    <a href="<?php echo base_url(); ?>admin/masters/boxList" class="btn default">Back</a>
                                            </div>
                                    </form>
                            </div>
                    </div>
            </div>
    </div>
    
</div>