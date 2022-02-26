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
                    <div class="box box-bordered box-color">
                            <div class="box-title">
                                    <h3>
                                            <i class="fa fa-th-list"></i>Image Upload Form</h3>
                            </div> 
                      
                            <div class="box-content nopadding">
                                <form class="form-horizontal form-bordered" method='post' id="zipUploadForm" name='boxForm'>
                                            
                                            <div class="form-group">
                                                    <label for="textfield" class="control-label col-sm-2">ZIP File<span class="required">*</span></label>
                                                    <div class="col-sm-10">                                                       
                                                        <input type="file" class="form-control" accept="application/zip,application/x-zip,application/x-zip-compressed" name="zip_file" style="padding-bottom:40px" required/>
                                                    </div>
                                            </div>  
                                    
                                            <div class="form-actions col-sm-offset-2 col-sm-10">
                                                    <button type="submit" class="btn btn-primary" id="submitBtn"><?php echo mlLang('lblSubmitBtn'); ?></button>                                                    
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

<div class="alert alert-danger alert-dismissable" id="error_holder_parent" style="margin:11px">
    <button data-dismiss="alert" class="close" type="button">×</button>
    <strong id="error_holder"></strong>
</div>
<div class="alert alert-success alert-dismissable" id="success_holder_parent" style="margin:11px">
    <button data-dismiss="alert" class="close" type="button">×</button>
    <strong id="success_holder"></strong>
</div>

<script type="text/javascript">
$(document).ready(function()
{
    $('#error_holder_parent').hide();
    $('#success_holder_parent').hide();
    
    $("#zipUploadForm").on('submit',(function(e) {
        
        $('#img_load_chart').html('Loading.....');
        $('#loadingDiv_bakgrnd').show();
        
        e.preventDefault();
        $.ajax({
             url: "<?=base_url()?>admin/order/imageUploadJkt",
             type: "POST",
             data:  new FormData(this),
             contentType: false,
             cache: false,
             processData:false,
             dataType : 'json',
             success: function(data){
                if (data.status == false)
                {
                    $('#loadingDiv_bakgrnd').hide();
                    alert(data.message);
                    addLog(data.message);
                    return;
                }
                else
                {
                    initiateImport(data.data);
                }
                
             }           
        });
   }));

    var es;
    // initiate users import
    function initiateImport(file)
    {
        $('#progress_view_model').modal('show');

        url = "<?=base_url()?>admin/order/extractZipAndProcessImage/"+file;

        es = new EventSource(url);
        
        $('#loadingDiv_bakgrnd').show();
        
        // listen server event
        //a message is received
        es.addEventListener('message', function(e)
        {
            var result = JSON.parse( e.data );

            console.log(result)
            console.log(e.lastEventId)
            // check server response  if  event response is "Close" than close the event object
            // other wise show progress bar
            if(e.lastEventId == 'CLOSE')
            {
                es.close();
                $('#loadingDiv_bakgrnd').hide();
            }
            else
            {
//                addLog(result.message);
                
                if (result.data.type == 'error')
                {
                    $('#error_holder_parent').show();
                    $('#error_holder').append("<br/>" + result.data.message);
                }
                else
                {
                    $('#success_holder_parent').show();
                    $('#success_holder').append("<br/>" + result.data.message);
                }
                
                $('#img_load_chart').html(result.data.message);
            }
        });


        es.addEventListener('error', function(e)
        {
            addLog('Error occurred');
            es.close();
            $('#loadingDiv_bakgrnd').hide();
//            $('#img_load_chart').html('Error occurred');
        });
    }

    function stopUsersImport()
    {
        es.close();
        addLog('Interrupted');
        $('.close_button').css("display","block");
    }

    function addLog(message)
    {
        console.log(message)
    }

    if(typeof(EventSource) == "undefined")
    {
        alert('Events not supported.');
        $("#content").html("");
    }
});
</script>
