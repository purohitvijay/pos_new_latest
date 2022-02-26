<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            
               <div class="pull-right">
                        <button id="btnPrint" class="btn btn-primary" style="margin-right: 14px">
                            <i class="fa fa-print"></i>Print
                        </button>
                    
                        <button id="downloadXls" class="btn btn-primary" style="margin-right: 14px">
                            <i class="fa fa-download"></i>Download as Xls
                        </button>
                    
                        <button id="downloadPdf" class="btn btn-primary" style="margin-right: 14px">
                            <i class="fa fa-file"></i>Download as Pdf
                        </button> 
                </div>
            
            <div class="col-sm-12">
                <div class="box box-color box-bordered">
                    <div class="box-title">
                        <h3>
                            <i class="fa fa-table"></i>
                            OutStanding Order Payment
                        </h3>
                        <ul class="tabs">
                            <li class="active fake-link-days-class" rel="7">
                                <a data-toggle="tab" href="#t7">7 Days</a>
                            </li>
                            <li class="fake-link-days-class" rel="30">
                                <a data-toggle="tab" href="#t8">30 Days</a>
                            </li>
                            <li class="fake-link-days-class" rel="60">
                                <a data-toggle="tab" href="#t9">60 Days</a>
                            </li>
                            <li class="fake-link-days-class" rel="365">
                                <a data-toggle="tab" href="#t9">1 Year</a>
                            </li>
                            <li class="fake-link-days-class" rel="all">
                                <a data-toggle="tab" href="#t10">All</a>
                            </li>
                        </ul>
                    </div>
                    <!--<div class="box-content nopadding">-->
                    <table class="table table-hover table-nomargin table-bordered" id="menuTable">

                    </table>
                    <!--</div>-->
                </div>
            </div>
        </div>
    </div>
</div>

<div id="loadingDiv_bakgrnd">
    <span id="img_load_chart">Loading...</span>
</div>



<script>
jQuery(document).ready(function () {
    function initTables(obj)
    {
        extraParams = collectParams(obj);
       
        $('#menuTable').dataTable({
            "bFilter": true,
            "bDestroy": true,
            "bLengthChange": true,
            "iDisplayLength": 20,
            "sAjaxSource": "<?php echo base_url(); ?>admin/order/getOutStandingOrderPaymentData", //datasource
            "sAjaxDataProp": "aData",
            "bServerSide": true, //serverside , 
            "bProcessing": true,
            "aoColumns": [
                {"mDataProp": "order_number", "sTitle": "Order #"},
                {"mDataProp": "collection_date", "sTitle": "Collection Date", "sClass" : "fake-customer-name-class"},
                {"mDataProp": "name", "sTitle": "Name", "sClass" : "fake-mobile-class"},
                {"mDataProp": "mobile", "sTitle": "Phone Number"},
                {"mDataProp": "boxes_name", "sTitle": "Boxes"},
                {"mDataProp": "boxes_quantity", "sTitle": "Qty"},
                {"mDataProp": "kabupaten", "sTitle": "Destination"},
                {"mDataProp": "grand_total", "sTitle": "Order Total"},
                {"mDataProp": 'discount', "sTitle": "Discount"},
                {"mDataProp": 'total_cash_deposit', "sTitle": "Deposit"},
                {"mDataProp": "outstanding_amount", "sTitle": "Amt Outstanding"},
            ],
            "fnServerParams": function ( aoData ) {
                if (typeof extraParams !== 'undefined')
                {
                    $(extraParams.name).each(function(index, varName){
                        aoData.push({"name":varName, "value" :extraParams.val[index]});
                    })
                }
            },
            "aLengthMenu": [
                [20, 50, 100, -1],
                [20, 50, 100, "All"] // change per page values here
            ],
            "bSort": true
        });
    }
    
    $('.fake-link-days-class').click(function(index, tmpObj){
         initTables($(this));
    })
    
    function collectParams(linkObj)
    { 
       var obj = ['name', 'val'];
       obj.name = new Array();
       obj.val = new Array();
       
       obj.name[0] = 'days';
       obj.val[0] = $(linkObj).attr('rel');
       return obj;
    } 
    initTables();
    
        $("#btnPrint").click(function () {
            printElement(document.getElementById("menuTable"));
            window.print();
        });
    
        $("#downloadPdf").click(function () {
        var doc = new jsPDF();              
        var json = doc.autoTableHtmlToJson(document.getElementById("menuTable"));
        var headerText = $(".reportText").text();

        doc.autoTableSetDefaults({
            addPageContent: function() {
                    doc.text(headerText, 1, 1)
              },  
        });

        doc.autoTable(json.columns, json.data, {
            startY: 20, 
            theme: 'grid',
            styles: {
              overflow: 'linebreak',
              fontSize: 7,
              rowHeight: 12,
              columnWidth: 'wrap',
              fileColor: [255, 0, 0],
            },
            margin: {top: 20, left: 3},
            headerStyles: {
            cellPadding: 2,
            lineWidth: 0,
            valign:'top',
            fontStyle: 'bold',
            halign: 'left',    //'center' or 'right'
            fillColor: [211, 211, 211],
            textColor: [78, 53, 73], //Black     
            rowHeight:20
            },

        }); 

        var a = window.document.createElement("a");
        a.href=doc.output('datauristring');

        var today = new Date();
        var date = today.getDate()+'-'+(today.getMonth()+1)+'-'+today.getFullYear();
        var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
        var dateTime = date+'_'+time;

        a.download="OutstandingOrderPayment_"+dateTime+".pdf";
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);                
       
    });
    
        $("#downloadXls").click(function () {
           
        var days = $('.tabs li.active').attr('rel');
        var today = new Date();
        var date = today.getDate()+'-'+(today.getMonth()+1)+'-'+today.getFullYear();
        var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
        var dateTime = date+'_'+time;

        window.location.href = "<?= base_url();?>admin/order/downloadOustStandingOrderPayment?current_datetime=" +dateTime+ "&days=" +days;
        });
    });
</script>