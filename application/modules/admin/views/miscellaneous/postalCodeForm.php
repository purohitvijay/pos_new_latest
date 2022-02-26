<div class="page-content main_container_padding">

    <div class="row">
        <div class="col-sm-12">
            <div class="box box-bordered box-color">
                <div class="box-title">
                    <h3>
                        <i class="fa fa-th-list"></i><?php echo $form_caption; ?></h3>
                </div>
                <div class="box-content nopadding">
                    <form action="<?php echo base_url(); ?>admin/miscellaneous/addPostalCode" class="form-horizontal form-bordered" method='post' id="postalCodeForm" name='postalCodeForm'>

                        <?php
                        $errors = validation_errors();
                        if (!empty($errors))
                        {
                            ?>
                            <div class="alert alert-danger active">
                                <button class="close" data-dismiss="alert"></button>
                                <span><?php echo $errors; ?></span>
                            </div>
                            <?php
                        }
                        ?> 
                        <div class="left-content" style="float:left; width :45%">
                        <div class="form-group">
                            <label for="postal_code" class="control-label col-sm-3">Postal Code<span class="required">*</span></label>
                            <div class="col-sm-9">
                                <input type="hidden" name="id" value="<?php echo set_value('id', empty($id) ? "" : $id); ?>" />
                                <input type="text" id="postal_code" class="form-control" placeholder="Enter Postal Code" name="postal_code" value="<?php echo set_value('postal_code', empty($postal_code) ? "" : $postal_code); ?>" style="width:50%" required/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="building" class="control-label col-sm-3">Building</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" placeholder="Enter Building" name="building" value="<?php echo set_value('building', empty($building) ? "" : $building); ?>" style="width:50%" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="building" class="control-label col-sm-3">Block<span class="required">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" placeholder="Enter Block" name="block" value="<?php echo set_value('block', empty($block) ? "" : $block); ?>" style="width:50%"  required/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="street" class="control-label col-sm-3">Street<span class="required">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" placeholder="Enter Street" name="street" value="<?php echo set_value('street', empty($street) ? "" : $street); ?>" style="width:50%"  required/>
                            </div>
                        </div>
                         <div class="form-group">
                            <label for="building_type" class="control-label col-sm-3">Building Type<span class="required">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" id="building_type" class="form-control" placeholder="Enter Building Type" name="building_type" value="<?php echo set_value('building_type', empty($building_type) ? "" : $building_type); ?>" style="width:50%"  required/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="building_type" class="control-label col-sm-3">Latitude<span class="required">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" id="latitude" class="form-control" placeholder="Enter Latitude" name="latitude" value="<?php echo set_value('latitude', empty($latitude) ? "1.3" : $latitude); ?>" style="width:50%"  required readonly="readonly"/>
                            </div>
                        </div>
                            
                         <div class="form-group">
                            <label for="building_type" class="control-label col-sm-3">Longitude<span class="required">*</span></label>
                            <div class="col-sm-9">
                                <input type="text" id="longitude" class="form-control" placeholder="Enter Longitude" name="longitude" value="<?php echo set_value('longitude', empty($longitude) ? "103.8" : $longitude); ?>" style="width:50%"  required readonly="readonly"/>
                            </div>
                        </div>
                        
                        </div>
                        <div class="left-content" style="float:left; width :48%;margin-right : 10px;">
                            <div id="map-canvas" style="width:700px;height:400px;"></div>
                        </div>

                        <div class="form-actions col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary"><?php echo mlLang('lblSubmitBtn'); ?></button>
                            <a href="<?php echo base_url(); ?>admin/miscellaneous/postalCodeList" class="btn default"><?php echo mlLang('lblBackBtn'); ?></a>
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

<script type="text/javascript" src="http://maps.google.com/maps/api/js?key=AIzaSyCLXIO90NgRz7vaHB96f1f4ZdkuG5znL6Q&sensor=true"></script>
<script type="text/javascript">
    $('document').ready(function(){ 
        $("#building_type").tokenInput("<?php echo base_url();?>admin/miscellaneous/getBuildingType", {
                    theme: "facebook",
                    minChars: '2',
                    tokenLimit: '1',
                    allowFreeTagging: true,
                    tokenValue: 'name'
                });
     var latitude = $('#latitude').val();
     var longitude = $('#longitude').val();
     initMap(latitude,longitude);
     
     $('#postal_code').blur(function(){
        var postalcode = $(this).val();
        $('#loadingDiv_bakgrnd').show();
        $.ajax({
            type: 'POST',
            url: '<?php echo base_url();?>admin/miscellaneous/getLatLong?postalcode='+postalcode,
            dataType: 'json',
            contentType: false,
            success: function (data) 
               {
                  $('#longitude').val(data.longitude);
                  $('#latitude').val(data.lattitude);
                  initMap(data.lattitude,data.longitude);
                  $('#loadingDiv_bakgrnd').hide();
               }
        })
     });
    });
    
    var markers = [];
   function initMap(lat,long) {
        var mapOptions = {
            center: new google.maps.LatLng(lat, long),
            zoom: 15,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var infoWindow = new google.maps.InfoWindow();
        var map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
        
            var myLatlng = new google.maps.LatLng(lat, long);
            var marker = new google.maps.Marker({
                position: myLatlng,
                map: map,
            });
        }
</script>