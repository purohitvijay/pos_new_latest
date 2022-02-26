<?php
if (!empty($results))
{
?>
<div class="row">
        <div class="col-sm-12">
                <div class="box box-color box-bordered">
                        <div class="box-title">
                                <h3>
                                        <i class="fa fa-table"></i>
                                        Look Up Results
                                </h3>
                        </div>
                        <div class="box-content nopadding">
                                <table class="table table-hover table-nomargin">
                                        <thead>
                                                <tr>
                                                        <th>Name</th>
                                                        <th>Postal Code</th>
                                                        <th>Block</th>
                                                        <th>Street</th>
                                                        <th>Building/ Estate</th>
                                                        <th>Unit</th>
                                                        <th>Passport</th>
                                                        <th class='hidden-350'>Mobile</th>
                                                        <th class='hidden-350'>Phone</th>
                                                        <th class='hidden-1024'></th>
                                                </tr>
                                        </thead>
                                        <tbody>
<?php
        foreach ($results as $index => $row)
        {

?>
                                                <tr>
                                                        <td id="name_<?=$row['id'];?>"><?=$row['name'];?></td>
                                                        <td id="pin_<?=$row['id'];?>"><?=$row['pin'];?></td>
                                                        
                                                        <td id="block_<?=$row['id'];?>"><?=$row['block'];?></td>
                                                        <td id="street_<?=$row['id'];?>"><?=$row['street'];?></td>
                                                        <td id="building_<?=$row['id'];?>"><?=$row['building'];?></td>
                                                        <td id="unit_<?=$row['id'];?>"><?=$row['unit'];?></td>
                                                        <td id="passport_id_number_<?=$row['id'];?>"><?=$row['passport_id_number'];?></td>
                                                        
                                                        <input type="hidden" value="<?=$row['email'];?>" id="email_<?=$row['id'];?>">
                                                        <input type="hidden" value="<?=$row['repeated_customer'];?>" id="repeated_customer_<?=$row['id'];?>">
                                                        <input type="hidden" value="<?=$row['passport_img'];?>" id="passport_img_<?=$row['id'];?>">
                                                        
                                                        <input type="hidden" value="<?=$row['lattitude'];?>" id="lattitude_<?=$row['id'];?>">
                                                        <input type="hidden" value="<?=$row['longitude'];?>" id="longitude_<?=$row['id'];?>">
                                                        
                                                        <td id="mobile_<?=$row['id'];?>" class='hidden-350'><?=$row['mobile'];?></td>
                                                        <td id="residence_phone_<?=$row['id'];?>" class='hidden-350'><?=$row['residence_phone'];?></td>
                                                        <td><input onclick="$('#customerIdHidden').val(this.value)" type="radio" name="selectCustomerRadio" class="selectCustomerRadio" value="<?=$row['id'];?>"></td>
                                                        <input type="hidden" id="customerIdHidden">
                                                        <input type="hidden" id="isRepeatedCustomer_<?=$row['id'];?>" value="<?=$row['is_repeated_customer'];?>">
                                                        <input type="hidden" id="customer_type_id_<?=$row['id'];?>" value="<?=$row['customer_type_id'];?>">
                                                        <input type="hidden" id="media_type_id_<?=$row['id'];?>" value="<?=$row['media_type_id'];?>">
                                                </tr>
                                                
<?php
        }
?>
                                                
                                        </tbody>
                                </table>
                            </div>
            </div>
        </div>
    </div>
<?php
}
?>