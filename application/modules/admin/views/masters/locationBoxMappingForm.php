<div class="page-content main_container_padding">
    
    <?php
    if (!empty($message))
    {
    ?>
        <div class="alert alert-success" style="margin-top:20px" role="alert"><?=$message?></div>
    <?php
    }
    ?>

    
    <div class="row">
        <div class="col-sm-12">
            <fieldset>
                <legend >Search Form</legend>
                <form action="<?php echo base_url(); ?>admin/masters/locationBoxMapping" class="form-horizontal form-bordered" method='post'>
                    <input type="hidden" name="searchForm" value="1">
                     <div class="form-group">
                            <label for="location" class="control-label col-sm-2">Location(s)</label>
                            <div class="col-sm-3" style="height:50px">
                                <?php
                                if (!empty($locations))
                                {
                                ?>
                                    <select name="locations_selected[]" multiple id="location" class="multiselect">
                                <?php
                                    $str = '';
                                    $locations_selected_str = '';
                                    foreach ($locations as $index => $row)
                                    {
                                        $name = htmlspecialchars($row['name']);
                                        $locations_selected_str .= "<input type='hidden' name='locations_selected_name[{$row['id']}]' value='$name'>";
                                        
                                        $selected = !empty($locations_selected) && in_array($row['id'], $locations_selected) ? 'Selected' : '';
                                        $str .= "<option $selected value='{$row['id']}'>{$row['name']}</option>";
                                    }
                                    echo $str;
                                ?>
                                    </select>
                                <?php
                                    echo $locations_selected_str;
                                }
                                else
                                {
                                    echo "No locations found.";
                                }
                                ?>
                            </div>
                            <label for="box" class="control-label col-sm-2">Box(es)</label>
                            <div class="col-sm-3" style="height:50px">
                                <?php
                                if (!empty($boxes))
                                {
                                ?>
                                
                                    <select id="box" name="boxes_selected[]" multiple class="multiselect">
                                <?php
                                    $boxes_selected_str = '';
                                    foreach ($boxes as $index => $row)
                                    {
                                        $name = htmlspecialchars($row['name']);
                                        $boxes_selected_str .= "<input type='hidden' name='boxes_selected_name[{$row['id']}]' value='$name'>";
                                        $selected = !empty($boxes_selected) && in_array($row['id'], $boxes_selected) ? 'Selected' : '';
                                ?>
                                        <option <?=$selected?> value='<?=$row['id']?>'><?=$row['name']?></option>
                                <?php
                                    }
                                ?>
                                        </select>
                                <?php
                                    echo $boxes_selected_str;
                                }
                                else
                                {
                                    echo "No boxes found.";
                                }
                                ?>
                            </div>
                            <div class="col-sm-2">
                                <button id="searchButtonForm" type="submit" class="btn btn-primary"><?php echo mlLang('lblSubmitBtn'); ?></button>
                                <a href="<?php echo base_url(); ?>admin/masters/agentList" class="btn default">Reset</a>
                            </div>
                    </div>
                </form>                
            </fieldset>
        </div>
    </div>
    
    <?php
    if (!empty($locations_selected))
    {
    ?>
    <div class="row">
            <div class="col-sm-12">
                    <div class="box box-bordered box-color">
                            <div class="box-title">
                                    <h3>
                                        <i class="fa fa-th-list"></i>Location Box Price Mapping
                                    </h3>
                            </div>
                            <div class="box-content nopadding">
                                <form action="<?php echo base_url(); ?>admin/masters/locationBoxMapping" class="form-horizontal form-bordered" method='post' id="boxForm" name='boxForm'>

                                    <?php
                                    foreach ($locations_selected as $index => $location)
                                    {
                                        $location_name = $locations_names[$index];
                                    ?>
                                    <input type="hidden" name="locations_selected[]" value="<?=$location?>">
                                    <input type="hidden" name="locations_selected_name[<?=$location?>]" value="<?=$location_name?>">
                                    
                                    <div class="form-group">
                                            <label for="textfield" class="control-label col-sm-2"><b><?=$location_name?></b></span></label>
                                            <div class="col-sm-10">
                                                
                                                    <?php
                                                    foreach ($boxes_selected as $inner_index => $box)
                                                    {
                                                        $price = isset($records[$location][$box]) ? $records[$location][$box] : '';
                                                                
                                                        $box_name = $boxes_names[$inner_index];
                                                        if (empty($index))
                                                        {
                                                    ?>
                                                            <input type="hidden" name="boxes_selected[]" value="<?=$box?>">
                                                            <input type="hidden" name="boxes_selected_name[<?=$box?>]" value="<?=$box_name?>">
                                                    <?php
                                                        }
                                                    ?>
                                                        <span class="pull-left" style="padding-right:10px"><?=$box_name?>
                                                            <input type="text" class="form-control" name="prices[<?=$location?>_#_<?=$box?>]" value="<?=$price?>" size="8"/>
                                                        </span>
                                                    <?php
                                                    }
                                                    ?>
                                                </span>
                                            </div>
                                    </div>
                                    <?php
                                    }
                                    ?>
                                    
                                    
                                    <div class="form-actions col-sm-offset-2 col-sm-10">
                                            <button type="submit" class="btn btn-primary"><?php echo mlLang('lblSubmitBtn'); ?></button>
                                            <a href="<?php echo base_url(); ?>admin/masters/agentList" class="btn default"><?php echo mlLang('lblBackBtn'); ?></a>
                                    </div>
                                    
                                </form>
                            </div>
                    </div>
            </div>
    </div>
    <?php
    }
    ?>
</div>

<script type="text/javascript">
$(document).ready(function (){
    $(".multiselect").multiselect();
    
    $('#searchButtonForm').click(function (event){
        
        if($('#location :selected').length == 0)
        {
            alert('Please selecct at least one location.');
            event.preventDefault();
        }
        if($('#box :selected').length == 0)
        {
            alert('Please selecct at least one box.');
            event.preventDefault();
        }
    })
})
</script>