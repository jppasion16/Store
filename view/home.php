<div class="row">
    <!-- form div -->
    <div class="col-md-4">
        <h3>Daily Record Entry</h3>
        <div class="form-group row">
            <label for="datepicker1" class="col-sm-4 col-md-12 col-form-label">Date</label>
            <div class="col-sm-8 col-md-12">
                <input type="text" id="datepicker1" class="datepicker form-control" value="<?= $txtDateToday; ?>">
            </div>
        </div>

        <form id="myForm" name="myForm">
            <?=$txtStoreForm; ?>
        </form>
        <div class="form-group row">
            <label for="txtRemarks" class="col-sm-4 col-md-12 col-form-label">Remarks</label>
            <div class="col-sm-8 col-md-12">
                <textarea id="txtRemarks" class="form-control" rows="3"></textarea>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-12">
                <div id="formAlert" class="alert" role="alert" style="display:none"></div>
            </div>
            <div class="col-12 text-right">
                <div id="formSpinner" class="spinner-border text-primary" role="status" style="display:none">
                    <span class="sr-only">Loading...</span>
                </div>
                <button type="button" id="btnSave" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
    <!-- divider -->
    <div class="col-12 d-sm-block d-md-none">
        <hr>
    </div>
    <!-- chart div -->
    <div class="col-md-8">
        <h3>Data Chart</h3>
        <div class="row">
            <div class="col-6">
                <div class="form-group row">
                    <label for="dateFrom" class="col-lg-4 col-form-label">Date From</label>
                    <div class="col-lg-8">
                        <input type="text" id="dateFrom" class="form-control" value="<?=$txtDateFrom; ?>" placeholder="yyyy-mm-dd">
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group row">
                    <label for="dateTo" class="col-lg-4 col-form-label">Date To</label>
                    <div class="col-lg-8">
                        <input type="text" id="dateTo" class="form-control" value="<?=$txtDateToday; ?>" placeholder="yyyy-mm-dd">
                    </div>
                </div>
            </div>
        </div>
        
        <canvas id="myChart"></canvas>
    </div>
</div>
<hr>
<div class="row">
    <div class="col">
        <h3>Detailed Report</h3>
        <div class="table-responsive">
            <table id="myDetailedReport" class="table table-bordered table-hover table-fixed">
                <thead>
                    <tr>
                        <th class="align-top" scope="col">Date</th>
                        <?=$txtDailyRecordReportHeader; ?>
                    </tr>
                </thead>
                <tbody>
                    <?=$txtDailyRecordReportContent; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<input type="hidden" id="txtLastDateRecord" value="<?=$txtReportDateReset?>">

<script>
/**
    defining functions
 */
function datePicker_onChange(){
    // TODO: spinner or loading something
    $("#formAlert").slideUp();
    $("#formSpinner").hide();
    $.ajax({
        url: "assets/home.ajax.php",
        method: "POST",
        data: {
            postDate: $("#datepicker1").val(),
            postAction: "GoForm"
        },
        dataType: "text",
        success: function(result){
            // clear current data
            var arrInput = $("#myForm :input");
            arrInput.each(function(){
                $(this).val("");
            });
            // populate data
            var arrResult = result.split("|");
            $.each(arrResult, function(idx, value){
                if(idx == 0) $("#txtRemarks").val(value);
                else{
                    var arrVal = value.split("~");
                    $("#rawdata-"+arrVal[0]).val(arrVal[1]);
                }
            });
        }
    });
}

function myChart_update(chart){
    $.ajax({
        url: "assets/home.ajax.php",
        method: "POST",
        data: {
            postDateFrom: $("#dateFrom").val(),
            postDateTo: $("#dateTo").val(),
            postAction: "GoChart"
        },
        dataType: "json",
        success: function(result){
            console.log(result);
            chart.type = result.type;
            chart.data.labels = result.data.labels;
            
            chart.data.datasets.splice(0,chart.data.datasets.length);
            for(i = 0; i < result.SeqNo.length; i++){
                idx = result.SeqNo[i];
                chart.data.datasets.push({
                    label: result.data.datasets[idx].label,
                    data: result.data.datasets[idx].data,
                    fill: false,
                    backgroundColor: result.data.datasets[idx].backgroundColor,
                    borderColor: result.data.datasets[idx].borderColor,
                });
            }
            // for(i = 0; i < result.data.datasets.length; i++){
            //     chart.data.datasets.push({
            //         label: result.data.datasets[i].label,
            //         data: result.data.datasets[i].data,
            //         fill: false,
            //         backgroundColor: result.data.datasets[i].backgroundColor,
            //         borderColor: result.data.datasets[i].borderColor,
            //     });
            // }
            chart.update();
        }
    });
}

$(document).ready(function(){
    datePicker_onChange(); // run once when document is ready
    // chartjs
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: []
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            },
            elements: {
                line: {
                    tension: 0
                }
            }
        }
    });
    myChart_update(myChart);

    // jquery ui datepicker
    var dateTodayYear = <?= $arrDateToday[0]; ?>;
    var dateTodayMonth = <?= ((int)$arrDateToday[1]-1); ?>;
    var dateTodayDay = <?= (int)$arrDateToday[2]; ?>;
    
    var dateFromYear = <?= $arrDateFrom[0]; ?>;
    var dateFromMonth = <?= ((int)$arrDateFrom[1]-1); ?>;
    var dateFromDay = <?= (int)$arrDateFrom[2]; ?>;
    
    $('#datepicker1').datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        showOtherMonths: true,
        maxDate: new Date(dateTodayYear, dateTodayMonth, dateTodayDay)
    });
    $("#datepicker1").change(function(){
        datePicker_onChange();
    });

    $('#dateFrom').datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        showOtherMonths: true,
        maxDate: new Date(dateFromYear, dateFromMonth, dateFromDay)
    });
    $("#dateFrom").change(function(){
        myChart_update(myChart);
    });

    $('#dateTo').datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        showOtherMonths: true,
        maxDate: new Date(dateTodayYear, dateTodayMonth, dateTodayDay)
    });
    $("#dateTo").change(function(){
        myChart_update(myChart);
    });

    $("#btnSave").click(function (){
        $("#formAlert").slideUp();
        $("#formSpinner").show();
        var arrInput = $("#myForm :input");
        var arrOutlinekey = new Array();
        var arrValue = new Array();
        var tmpIdx = 0;
        arrInput.each(function(){
            arrOutlinekey[tmpIdx] = this.id.split("-")[1];
            arrValue[tmpIdx] = $(this).val();
            tmpIdx++;
        });
        // console.log(arrOutlinekey.join("|"));
        $.ajax({
            url: "assets/home.ajax.php",
            method: "POST",
            data: {
                postIDList: arrOutlinekey.join("|"),
                postValList: arrValue.join("|"),
                postDate: $("#datepicker1").val(),
                postRemarks: $("#txtRemarks").val(),
                postAction: "SaveForm"
            },
            dataType: "text",
            success: function(result){
                // alert(result);
                // return;
                $("#formSpinner").hide();
                var arrResult = result.split("|");
                if(parseInt(arrResult[0])){
                    $("#formAlert").removeClass("alert-danger").addClass("alert-success").html(arrResult[1]).slideDown();
                    
                    var d1 = $("#dateFrom").val().split("-");
                    var d2 = $("#dateTo").val().split("-");
                    var c = $("#datepicker1").val().split("-");
                    var from = new Date(d1[0], parseInt(d1[1])-1, d1[2]);
                    var to = new Date(d2[0], parseInt(d2[1])-1, d2[2]);
                    var check = new Date(c[0], parseInt(c[1])-1, c[2]);
                    // console.log(from+"\n"+to+"\n"+check);
                    if(check >= from && check <= to){
                        myChart_update(myChart);
                    }
                    if(Date.parse($("#txtLastDateRecord").val()) < Date.parse($("#datepicker1").val())){
                        LoadDetailedReport($("#myDetailedReport>tbody tr").length - 1, true);
                    }
                }
                else $("#formAlert").removeClass("alert-success").addClass("alert-danger").html(arrResult[1]).slideDown();
            }
        });
    });

    // detailed report
    CreateDetailedReportLoader();
    LoadDetailedReport();
});

function CreateDetailedReportLoader(boolLoader = true){
    if(boolLoader){
        $("#myDetailedReport > tbody:last-child").append("<tr class=\"row-clear\"><td class=\"text-center\" colspan=\""+$("#myDetailedReport thead tr th").length+"\"><a id=\"myDetailedReportLoader\" href=\"javascript:void(0)\">Load More...</a></td></tr>");
        $("#myDetailedReportLoader").click(function (){
            LoadDetailedReport();
        });
    }
    else $("#myDetailedReport > tbody:last-child").append("<tr class=\"row-clear\"><td class=\"text-center font-italic\" colspan=\""+$("#myDetailedReport thead tr th").length+"\">No more record to show</td></tr>");
}

function LoadDetailedReport(LoadLimit = 10, reload = false){
    if(reload) $("#txtLastDateRecord").val("<?=$txtReportDateReset?>");

    $.ajax({
        url: "assets/home.ajax.php",
        method: "POST",
        data: {
            postLoadLimit: LoadLimit,
            postLastDateRecord: $("#txtLastDateRecord").val(),
            postAction: "GoReport"
        },
        dataType: "json",
        success: function(result){
            console.log(result);
            $("#myDetailedReportLoader").parent().parent().remove(); // a < td < tr

            if(reload) $("#myDetailedReport>tbody tr").remove();

            booLoader = (result.RecordDate.length < LoadLimit) ? false : true;

            var appendValue = "";
            var headerLength = $("#myDetailedReport thead tr th").length - 1;
            for(i = 0; i < result.RecordDate.length; i++){
                appendValue += "<tr>";
                appendValue += "<td>"+result.RecordDate[i]+"</td>";
                for(j = 0; j < headerLength; j++){
                    tmpIdx = j+1;
                    while(tmpIdx != result.SeqNo[i][j]){
                        if(tmpIdx == headerLength) break;
                        appendValue += "<td class=\"text-right\">0.00</td>";
                        tmpIdx++;
                    }
                    if(result.RawData[i][j]) val = result.RawData[i][j];
                    else val = "0.00";
                    appendValue += "<td class=\"text-right\">"+val+"</td>";
                    j = tmpIdx-1;
                }
                appendValue += "</tr>";
            }
            $("#txtLastDateRecord").val(result.txtLastDateRecord);
            $("#myDetailedReport > tbody:last-child").append(appendValue);
            CreateDetailedReportLoader(booLoader);
        }
    });
}

</script>