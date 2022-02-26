<html>
    <head>
        <?php $baseurl = base_url();
        ?>        
        <script src="<?php echo base_url();?>assets/plugins/jquery-1.10.2.min.js" type="text/javascript"></script>

        <script>
            $(document).ready(function()
            {
                $('.list h3').click(function() {
                    $(this).next('ol').toggle();
                })

            });
        </script>
        <style>
            body
            {
                font-family:"verdana" ;
            }

            h3
            {
                cursor:pointer;
            }
        </style>
    </head>
    <body>
        <?php $url = site_url();
        ?>

        <ol>
            <li class="list"><h3>Validate Login</h3>
                    <ol class="sublist" >
                        <div>
                            <li> Method Type : POST</li> 
                            <li>Parameters Are:
                            <ol>
                                <li>username* : UserName</li>
                                <li>password* : Password</li>
                                <li>encode(optional) : [if set encode parameter then response in zipped format gzip header-encoding : gzip]</li>
                            </ol>
                            </li>
                            <li>Method Name :  <?php echo $url ?>admin/api/validateLogin</li>

                            <li>Response</li><br>
                            <pre>
                                    {
                                        "status": "error",
                                        "code": "467",
                                        "message": "Username/Password can not be empty."
                                    }

                                    {
                                        "status": "error",
                                        "code": "468",
                                        "message": "Invalid username/password."
                                    }

                                    {
                                        "status": "error",
                                        "code": "469",
                                        "message": "Your account is blocked. Kindly contact admin."
                                    }

                                    {
                                        "status": "error",
                                        "code": "492",
                                        "message": "No active Shipment Batch found. Kindly report this incidence to Admin."
                                    }

                                    {
                                        "status": "error",
                                        "code": "493",
                                        "message": "No boxes defined for Shipment Batch <SHIPMENT_BATCH>. Kindly report this incidence to Admin."
                                    }

                                    {
                                        "status": "success",
                                        "code": "200",
                                        "data": [
                                            {
                                                "user_id": "31",
                                                "role": "driver",
                                                "name": "Mr Ibrahim",
                                                "eod_required": true,
                                                "current_eod_status": "ready"
                                            }
                                    }        

                                    {
                                        "status": "success",
                                        "code": "200",
                                        "data": [
                                            {
                                                "user_id": "31",
                                                "role": "warehousemanager",
                                                "name": "Mr Ibrahim",
                                                <del>"shipment_batches" : [
                                                    {
                                                        "id": "1",
                                                        "booking_confirmation": "Booking Confirmation Text",
                                                        "confirmation_type": "BoookingConfirmation Type",
                                                        "vessel_name": "Vessel text",
                                                        "voyage_number": "Voyage Name",
                                                        "eta_singapore": "2015-01-01 00:00:01",
                                                        "ship_onboard": "0",
                                                        "eta_jakarta": "2015-02-01 00:00:01",
                                                        "eta_postki": "2015-03-01 00:00:01",
                                                        "bl_number": "",
                                                        "load_date": "0000-00-00",
                                                        "created_by": null,
                                                        "created_at": null,
                                                        "updated_at": null,
                                                        "orders_count": "78",
                                                        "batch": "Booking Confirmation Text",
                                                    },
                                                ]</del>,
                                                "shipment_batch":"656",
                                                "boxes":[  
                                                   {  
                                                      "name":"Half",
                                                      "capacity":"238",
                                                      "scanned":"238"
                                                   },
                                                   {  
                                                      "name":"Jumbo",
                                                      "capacity":"600",
                                                      "scanned":"600"
                                                   },
                                                   {  
                                                      "name":"Giant",
                                                      "capacity":"2",
                                                      "scanned":"1"
                                                   }
                                                ],
                                                "eod_required": false,
                                            }
                                    }        
                            </pre>
                                                       </div>
                    </ol>
                </li>

                
                
            <li class="list"><h3>Update Status</h3>
                    <ol class="sublist" >
                        <div>
                            <li> Method Type : POST</li> 
                            <li>Parameters Are:
                            <ol>
                                <li>order_id* : Order Id of order (Information is coded in QR Code)</li>
                                <li>employee_id* : Driver who scanned the QR Code</li>
                                <li>order_number* : Order Number (Information is coded in QR Code)</li>
                                <li>lattitude* : Lattitude where QR Code was scanned</li>
                                <li>longitude* : Longitude where QR Code was scanned</li>
                                <del><li>batch_id : Send by warehouse manager while doing final scan.</li></del>
                                <del><li>shipment_batch_id : Send by warehouse manager while doing final scan and selecting values from drop down.</li></del>
                                <li>metadata : Extra data related to device from which scan was done, it should be an array.</li>
                                <li>encode(optional) : [if set encode parameter then response in zipped format gzip header-encoding : gzip]</li>
                            </ol>
                            </li>
                            <li>Method Name :  <?php echo $url ?>admin/api/updateStatus</li>

                            <li>Response</li><br>
                            <pre>
                                    {
                                        'status' : 'error',
                                        'code' : '470',
                                        'message' : 'Order Id/Order No/Lat-Long/Employee Id can not be empty.',
                                    }

                                    {
                                        'status' : 'error',
                                        'code' : '471',
                                        'message' : "Proximity limit reached. Defined proximity is 200(M) and scanned one is 20000(M)"
                                    }

                                    {
                                        'status' : 'error',
                                        'code' : '472',
                                        'message' : "Collection date empty for POSTKI/00008."
                                    }

                                    {
                                        'status' : 'error',
                                        'code' : '473',
                                        'message' : "Cooling off period for consecutive scan is 10 mins, but 24 encountered."
                                    }

                                    {
                                        'status' : 'error',
                                        'code' : '474',
                                        'message' : "Expected role warehousemanager, driver encountered."
                                    }

                                    {
                                        "status": "error",
                                        "code": "475",
                                        "message": "Order cycle already completed for POSTKI-000019"
                                    }

                                    {
                                        "status": "error",
                                        "code": "482",
                                        "message": "Can not scan as 00001 is marked as canceled."
                                    }

                                    {
                                        "status": "error",
                                        "code": "483",
                                        "message": "Can not scan as 00001 is marked as KIV."
                                    }

                                    {
                                        "status": "error",
                                        "code": "485",
                                        "message": "Proximity limit reached. Google API also attempted. Defined proximity is 600(M) and scanned ones are Google - 1200(M), Normal - 2000(M)."
                                    }

                                    {
                                        "status": "error",
                                        "code": "486",
                                        "message": "Please make sure you are scanning correct QR Code. Seems like this QR Code doesn't belong to us."
                                    }

                                    {
                                        "status": "error",
                                        "code": "487",
                                        "message": "Hold your horses! You are scanning old QR Codes of POS TKI on pre printed stationery. Try scanning with one generated with Software."
                                    }

                                    {
                                        "status": "error",
                                        "code": "488",
                                        "message": "Hold your horses! You are scanning old QR Codes of POS TKI on pre printed stationery. Try scanning with one generated with Software."
                                    }


                                    {
                                        "status": "error",
                                        "code": "491",
                                        "message": "Box(es) capacity not defined for <BATCH_NAME>. Kindly report this incidence to Admin."
                                                                OR
                                        "message": "Capacity exhaused for Order <ORDER NUMBER> for Batch <SHIPMENT BATCH> => Box <BOX>"
                                    }

                                    //FOR DRIVER
                                    {
                                        "status": "success",
                                        "code": "200",
                                        "data": {
                                                    "id": 25,
                                                    "status": "box_collected",
                                                    "outstanding_amount": 100,
                                                    "cash_collection": true,
                                                    "voucher_cash": true,
                                                    "order_id": 12,
                                                    "eod_status": 'ready', (possible values are ready and no)
                                                },
                                    }                   
                                    
                                    
                                    //FOR WAREHOUSE MANAGER
                                    {
                                        "status": "success",
                                        "code": "200",
                                        "data": {
                                                    "id": 25,
                                                    "status": "box_collected",
                                                    "outstanding_amount": 100,
                                                    "cash_collection": true,
                                                    "voucher_cash": true,
                                                    "order_id": 12,
                                                    "eod_status": 'ready', (possible values are ready and no),
                                                    "shipment_batch":"656",
                                                    "boxes":[  
                                                       {  
                                                          "name":"Half",
                                                          "capacity":"238",
                                                          "scanned":"238"
                                                       },
                                                       {  
                                                          "name":"Jumbo",
                                                          "capacity":"600",
                                                          "scanned":"600"
                                                       },
                                                       {  
                                                          "name":"Giant",
                                                          "capacity":"2",
                                                          "scanned":"1"
                                                       }
                                                    ],
                                                },
                                    }                   
                                    
                                    
                                    </pre>
                                                       </div>
                    </ol>
                </li>
                
                
            <li class="list"><h3>Update Cash Collection</h3>
                    <ol class="sublist" >
                        <div>
                            <li> Method Type : POST</li> 
                            <li>Parameters Are:
                            <ol>
                                <li>id* : Id returned from API 2</li>
                                <li>cash_collected* : Amount collected.</li>
                                <li>voucher_cash : Voucher cash amount, if any.</li>
                                <li>comments : Comments, if any</li>
                                <li>encode(optional) : [if set encode parameter then response in zipped format gzip header-encoding : gzip]</li>
                            </ol>
                            </li>
                            <li>Method Name :  <?php echo $url ?>admin/api/updateCashCollectionDetails</li>

                            <li>Response</li><br>
                            <pre>
                                    {
                                        'status' : 'error',
                                        'code' : '471',
                                        'message' : 'Id/Cash Collected can not be empty.',
                                    }

                                    {
                                        "status": "success",
                                        "code": "200"
                                    }                   </pre>
                                                       </div>
                    </ol>
                </li>
                
                
            <li class="list"><h3>Update EOD Status</h3>
                    <ol class="sublist" >
                        <div>
                            <li> Method Type : POST</li> 
                            <li>Parameters Are:
                            <ol>
                                <li>employee_id* : Employee</li>
                                <li>metadata : Extra data related to device from which scan was done, it should be an array.</li>
                                <li>encode(optional) : [if set encode parameter then response in zipped format gzip header-encoding : gzip]</li>
                            </ol>
                            </li>
                            <li>Method Name :  <?php echo $url ?>admin/api/updateEODStatus</li>

                            <li>Response</li><br>
                            <pre>
                                    {
                                        'status' : 'error',
                                        'code' : '480',
                                        'message' : 'Employee Id can not be empty.',
                                    }

                                    {
                                        'status' : 'error',
                                        'code' : '481',
                                        'message' : 'EOD already done.',
                                    }

                                    {
                                        "status": "success",
                                        "code": "200",
                                        "data": {
                                                    "status" : "yes"
                                                }
                                    }                   </pre>
                                                       </div>
                    </ol>
                </li>
                
                
            <li class="list"><h3>Driver Task Listing</h3>
                    <ol class="sublist" >
                        <div>
                            <li> Method Type : GET</li> 
                            <li>Parameters Are:
                            <ol>
                                <li>employee_id* : Employee</li>
                                <li>date : Date (Y-m-d) of which listing is to b required. If not send current date will be picked.</li>
                                <li>encode(optional) : [if set encode parameter then response in zipped format gzip header-encoding : gzip]</li>
                            </ol>
                            </li>
                            <li>Method Name :  <?php echo $url ?>admin/api/getDateWiseTaskListingByEmployee</li>

                            <li>Response</li><br>
                            <pre>
                                    {
                                        'status' : 'error',
                                        'code' : '480',
                                        'message' : 'Employee Id can not be empty.',
                                    }

                                    {
                                    "status": "success",
                                    "code": "200",
                                    "data": [
                                        "delivery" : [{
                                                        "id": "101",
                                                        "order_id": "68",
                                                        "order_number": "POSTKI/000032",
                                                        "customer_name": "jatin Maheshwari",
                                                        "mobile": "3123213",
                                                        "residence_phone": "0",
                                                        "employee_id": "36",
                                                        "status": "booking_attended_by_driver",
                                                        "driver": "driver1",
                                                        "updated_at": "2015-01-04 15:22:54",
                                                        "lattitude": "1.3538",
                                                        "longitude": "103.9676"
                                                    },
                                                    {
                                                        "id": "102",
                                                        "order_id": "69",
                                                        "order_number": "POSTKI/000033",
                                                        "customer_name": "Aisha",
                                                        "mobile": "92383800",
                                                        "residence_phone": "**",
                                                        "employee_id": "36",
                                                        "status": "booking_attended_by_driver",
                                                        "driver": "driver1",
                                                        "updated_at": "2015-01-04 15:22:59",
                                                        "lattitude": "1.4281",
                                                        "longitude": "103.7726"
                                                    }],
                                        "collection" : [{
                                                    "id": "101",
                                                    "order_id": "68",
                                                    "order_number": "POSTKI/000032",
                                                    "customer_name": "jatin Maheshwari",
                                                    "mobile": "3123213",
                                                    "residence_phone": "0",
                                                    "employee_id": "36",
                                                    "status": "booking_attended_by_driver",
                                                    "driver": "driver1",
                                                    "updated_at": "2015-01-04 15:22:54",
                                                    "lattitude": "1.3538",
                                                    "longitude": "103.9676"
                                                },
                                                {
                                                    "id": "102",
                                                    "order_id": "69",
                                                    "order_number": "POSTKI/000033",
                                                    "customer_name": "Aisha",
                                                    "mobile": "92383800",
                                                    "residence_phone": "**",
                                                    "employee_id": "36",
                                                    "status": "booking_attended_by_driver",
                                                    "driver": "driver1",
                                                    "updated_at": "2015-01-04 15:22:59",
                                                    "lattitude": "1.4281",
                                                    "longitude": "103.7726"
                                                }]
                                        ]
                                    ]
                                }                 </pre>
                                                       </div>
                    </ol>
                </li>

                
            <li class="list"><h3>Driver EOD Status</h3>
                    <ol class="sublist" >
                        <div>
                            <li> Method Type : GET</li> 
                            <li>Parameters Are:
                            <ol>
                                <li>employee_id* : Employee</li>
                                <li>date : Date (Y-m-d) of which listing is to b required. If not send current date will be picked.</li>
                                <li>encode(optional) : [if set encode parameter then response in zipped format gzip header-encoding : gzip]</li>
                            </ol>
                            </li>
                            <li>Method Name :  <?php echo $url ?>admin/api/getEODStatus</li>

                            <li>Response</li><br>
                            <pre>
                                    {
                                        'status' : 'error',
                                        'code' : '480',
                                        'message' : 'Employee Id can not be empty.',
                                    }

                                    {
                                        "status": "success",
                                        "code": "200",
                                        <s>"data": []</s>
                                        "data":{}
                                    }

                                    {
                                        "status": "success",
                                        "code": "200",
                                        "data": {
                                            "status": "yes"
                                        }
                                    }

             </pre>
                                                       </div>
                    </ol>
                </li>

                
            <li class="list"><h3>Driver Order Ordering</h3>
                    <ol class="sublist" >
                        <div>
                            <li> Method Type : POST</li> 
                            <li>Parameters Are:
                            <ol>
                                <li>order_ids* : Array of order ids.</li>
                                <li>order_nos* : Array of order merit.</li>
                                <li>employee_id* : Employee Id.</li>
                                <li>type* : delivery or collection.</li>
                                <li>encode(optional) : [if set encode parameter then response in zipped format gzip header-encoding : gzip]</li>
                            </ol>
                            </li>
                            <li>Method Name :  <?php echo $url ?>admin/api/updateEmployeeOrderOrdering</li>

                            <li>Response</li><br>
                            <pre>
                                    {
                                        'status' : 'error',
                                        'code' : '484',
                                        'message' : 'Order Ids/Order Nos/Employee Id/Type can not be empty.',
                                    }

                                    {
                                        "status": "success",
                                        "code": "200",
                                    }
             </pre>
                                                       </div>
                    </ol>
                </li>

                
            <li class="list"><h3>Active Shipment Batch Name And Boxwise Capacity Listing (used at warehouse dashboard refresh)</h3>
                    <ol class="sublist" >
                        <div>
                            <li> Method Type : GET</li> 
                            <li>No Parameters</li>
                            <li>Method Name :  <?php echo $url ?>admin/api/getCurrentShipmentAndBoxCapacities</li>

                            <li>Response</li><br>
                            <pre>
                                    {
                                        "status": "error",
                                        "code": "492",
                                        "message": "No active Shipment Batch found. Kindly report this incidence to Admin."
                                    }

                                    {
                                        "status": "error",
                                        "code": "493",
                                        "message": "No boxes defined for Shipment Batch <SHIPMENT_BATCH>. Kindly report this incidence to Admin."
                                    }

                                    {
                                        "status": "success",
                                        "code": "200",
                                        "data": {
                                                "shipment_batch":"656",
                                                "boxes":[  
                                                   {  
                                                      "name":"Half",
                                                      "capacity":"238",
                                                      "scanned":"238"
                                                   },
                                                   {  
                                                      "name":"Jumbo",
                                                      "capacity":"600",
                                                      "scanned":"600"
                                                   },
                                                   {  
                                                      "name":"Giant",
                                                      "capacity":"2",
                                                      "scanned":"1"
                                                   }
                                                ]
                                            }
                                    }
             </pre>
                                                       </div>
                    </ol>
                </li>

                <h2>JAKARTA SIDE</h2>
                
                
            <li class="list"><h3>Validate Login</h3>
                    <ol class="sublist" >
                        <div>
                            <li> Method Type : POST</li> 
                            <li>Parameters Are:
                            <ol>
                                <li>username* : UserName</li>
                                <li>password* : Password</li>
                                <li>encode(optional) : [if set encode parameter then response in zipped format gzip header-encoding : gzip]</li>
                            </ol>
                            </li>
                            <li>Method Name :  <?php echo $url ?>admin/api/validateLoginJakarta</li>

                            <li>Response</li><br>
                            <pre>
                                    {
                                        "status": "error",
                                        "code": "467",
                                        "message": "Username/Password can not be empty."
                                    }

                                    {
                                        "status": "error",
                                        "code": "468",
                                        "message": "Invalid username/password."
                                    }

                                    {
                                        "status": "error",
                                        "code": "469",
                                        "message": "Your account is blocked. Kindly contact admin."
                                    }

                                    {
                                        "status": "error",
                                        "code": "489",
                                        "message": "Illegal operation performed. Trying to login into Jakarta module with other credentials."
                                    }

                                    {
                                        "status": "error",
                                        "code": "490",
                                        "message": "Illegal operation performed. Only Warehouse Manager type of users can login to this App."
                                    }

                                    {
                                        "status": "success",
                                        "code": "200",
                                        "data": {
                                                    "user_id": "36",
                                                    "role": "warehousemanager",
                                                    "name": "Imran",
                                                    "receiving_batches": [
                                                        {
                                                            "id": "1",
                                                            "name": "TEST",
                                                            "status": "open",
                                                            "order_count": "10",
                                                            "created_by": "2",
                                                            "created_at": null,
                                                            "updated_at": null,
                                                            "shipment_batches": "627<br/>626"
                                                        }
                                                    ],
                                                    "receiving_batch_orders": {
                                                        "183": {
                                                            "order_number": "86523",
                                                            "boxes_count": "1"
                                                        },
                                                        "577": {
                                                            "order_number": "87731",
                                                            "boxes_count": "1"
                                                        },    
                                    } 
                            </pre>
                                                       </div>
                    </ol>
                </li>

                
        </ol>
    </body>
</html>