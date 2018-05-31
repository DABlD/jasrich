<div class="col-md-10 col-md-offset-2" style="margin-top: 1%;">
    <div class="panel panel-primary">
        <div class="panel-body">
        	<div class="overfulow" style="margin-left: 1%; margin-right: 1%;" class="table-responsive">
        		<center><h1><b>REPORTS</b></h1>
        		<hr style="background: linear-gradient(red,yellow,blue);" width="80%"></center>
   
        		<div class="form-inline">
					<select name="type" id="type" style="width: 15%;" onchange="activateFPR()">
						<option></option>
						<option value="inventory">Inventory</option>
						<option value="sales">Sales</option>
					</select>
					
					<input type="text" id="date" class="btn btn-default" placeholder="Select Report Type First" disabled>
                    <button class="btn btn-info" id="getButton" onclick="getReport()">GET REPORT</button>

        			<!-- <button class="btn btn-warning btn-small" style="position: absolute; right: 5%;" id="auditButton" onclick="auditButton()">Audit Trail</button> -->
        		</div>
                <br>
                <div>
                    <div id="preloader" style="position: absolute; left: 0; top: 0; z-index: 999; width: 100%; height: 100%; overflow: visible; background: rgba(51,51,51,0.5) url('<?= base_url() . 'img/hourglass.svg'?>') no-repeat center center; display: none;"></div>

                    <div class="row myChart" style="display: none;">
                        <div class="col-md-10" style="padding-right: 0px">
                            <canvas id="myChart"></canvas>
                        </div>
                        <div class="col-md-2" style="padding-left: 0px; padding-right: 0px">
                            <div class="col-md-12" style="padding-left: 0px; padding-right: 0px">
                                <div class="panel panel-primary">
                                  <div class="panel-heading">Expected Sales/Expense next 3 Months</div>
                                  <div class="panel-body salesAnalysis">
                                    
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="divTable" style="display: none;">
                  <table id="auditTrail" class="table table-striped table-bordered table-hover">
                     <thead>
                         <tr>
                             <th style="background-color: #ebe6e6;">ITEM ID</th>
                             <th style="background-color: #ebe6e6;">NAME</th>
                             <th style="background-color: #ebe6e6;">STOCKS</th>
                             <th style="background-color: #ebe6e6;">IN/OUT</th>
                         </tr>
                     </thead>
                     <tbody></tbody>
                  </table>
                    </div>
                </div>

				<br>
			</div>
        </div>
    </div>
</div>
<div class="col-md-10 col-md-offset-2" style="margin-top: 1%;">
    <div class="panel panel-primary">
        <div class="panel-body">
            <div class="col-md-4">
                <div style="text-align: center;" id="aei">Almost Expired Items</div>
                <div style="overflow-y: scroll; height: 400px;">
                    <table class="table table-striped table-bordered table-hover" style="overflow-y: scroll; height: 400px;">
                        <thead>
                            <tr>
                                <th style="background-color: #ebe6e6;">ITEM NAME</th>
                                <th style="background-color: #ebe6e6;">EXPIRATION</th>
                            </tr>
                        </thead>
                        <tbody id="expired">
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-4">
                <div style="text-align: center;" id="lsi">Low Stock Items</div>
                <div style="overflow-y: scroll; height: 400px;">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th style="background-color: #ebe6e6;">ITEM NAME</th>
                                <th style="background-color: #ebe6e6;">STOCKS LEFT</th>
                            </tr>
                        </thead>
                        <tbody id="lowStocks">
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-4">
                <div style="text-align: center;" id="ii">Inactive Items</div>
                <div style="overflow-y: scroll; height: 400px;">
                    <table class="table table-striped table-bordered table-hover" style="overflow-y: scroll; height: 400px;">
                        <thead>
                            <tr>
                                <th style="background-color: #ebe6e6;">ITEM NAME</th>
                                <th style="background-color: #ebe6e6;">LAST BOUGHT</th>
                            </tr>
                        </thead>
                        <tbody id="inactive">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="https://cdn.datatables.net/r/bs-3.3.5/jqc-1.11.3,dt-1.10.8/datatables.min.js"></script>
<!-- <script type="text/javascript" src="<?= base_url() ?>js/jquery.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/bootstrap.min.js"></script> -->
<script type="text/javascript" src="<?= base_url() ?>js/select2.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/swal.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/flatpickr.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/randomColor.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/moment.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/charts.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/numeral.js"></script>



<script type="text/javascript">
	$('#date').flatpickr({
		mode: 'range',
	});

    getExpired();
    getLowStocks();
    getInactive();

    function getExpired()
    {
        $.ajax({
            type: 'POST',
            url: '<?= base_url() . "Reports/getExpiredItems"?>',
            success: function(result)
            {
                var expired = JSON.parse(result);
                for(var i = 0; i < expired.length; i++)
                {
                    $('#expired').append('<tr><td>' + expired[i].name + '</td><td>' + expired[i].expiry_date + '</td></tr>');
                }
                $('#aei').append(' (' + expired.length + ')');
            }
        });
    }

    function getLowStocks()
    {
        $.ajax({
            type: 'POST',
            url: '<?= base_url() . "Reports/getLowStocks"?>',
            success: function(result)
            {
                var lowStocks = JSON.parse(result);
                for(var i = 0; i < lowStocks.length; i++)
                {
                    $('#lowStocks').append('<tr><td>' + lowStocks[i].name + '</td><td>' + lowStocks[i].stock + '</td></tr>');
                }
                $('#lsi').append(' (' + lowStocks.length + ')');
            }
        });
    }

    function getInactive()
    {
        $.ajax({
            type: 'POST',
            url: '<?= base_url() . "Reports/getInactive"?>',
            success: function(result)
            {
                var inactive = JSON.parse(result);
                for(var i = 0; i < inactive.length; i++)
                {
                    $('#inactive').append('<tr><td>' + inactive[i].name + '</td><td>' + inactive[i].last + '</td></tr>');
                }

                $('#ii').append(' (' + inactive.length + ')');
            }
        });
    }

    var start;
    var end;
    var type;
    
    var myChart;
    var done = 0;

    $(document).ready(function () {
        var oTable = $("#auditTrail").dataTable({
            "bProcessing": true,
            "sAjaxSource": "<?= base_url() . 'Reports/getAuditTrail' ?>",
            "sPaginationType": "full_numbers",
            "columns": [
                { "data": "id", "name": "audit_trail.id" },
                { "data": "username", "name": "audit_trail.name" },
                { "data": "action", "name": "audit_trail.qty" },
                { "data": "date_added", "name": "audit_trail.way"},
            ],
            "fnServerData": function (sSource, aoData, fnCallback)
            {
                $.ajax
                ({
                    "dataType": "json",
                    "type": "POST",
                    "url": sSource,
                    "data": aoData,
                    "success": fnCallback
                });
            },
        });

        oTable.api().column(3).order('desc').draw();
    });

    function auditButton()
    {
        if($('.myChart').is(':visible'))
        {
            $('.myChart').slideUp('fast');
        }
        $('#divTable').slideDown();
    }

    function getReport()
    {
        if($('#divTable').is(':visible'))
        {
            $('#divTable').slideUp('fast');
        }
        $('.myChart').slideDown('fast');
        $('#preloader').show();
        var date = $('#date').val();

		type = $('#type').val();
		start = date.split(' ')[0];
		end = date.split(' ')[2];

    	$.ajax({
            type: 'POST',
            data: {start: start, end: end, type: type},
            url: '<?= base_url() . "Reports/getSalesReport" ?>',
            success: function(result){
                if(done != 0)
                {
                    
                myChart.destroy();
                }
            	var data = JSON.parse(result);

                if(type == 'sales')
                {
                	var ins = data.in;
                	var outs = data.out;
                    createDataSet(outs, ins);
                }
                else
                {
                    var categorySales = data.categorySales;
                    var categories = data.categories;
                    createDataSet(categorySales, categories);
                }
            },
        });
    }

    function createDataSet(orderData, ins)
    {
        var dataset = [];
        var labels = [];

        if(type == 'sales')
        {
            var sales = [];
            var expense = [];

            for (var i = 0; i < orderData.length; i++) 
            {
                sales.push(orderData[i][0]);
                expense.push(ins[i][0]);
                labels.push(moment(orderData[i][1]).format('MMM Do'));
            }

            dataset.push({
                label: 'SALES',
                data: sales,
                backgroundColor: randomColor({
                    format: 'rgba',
                    alpha: 0.25,
                    hue: '#97bbcd',
                    luminosity: 'bright'
                }),
                borderColor: randomColor({
                    hue: '#97bbcd',
                    luminosity: 'bright'
                }),
                borderWidth: 1,
            });

            dataset.push({
                label: 'EXPENSE',
                data: expense,
                backgroundColor: randomColor({
                    format: 'rgba',
                    alpha: 0.25,
                    hue: '#c93138',
                    luminosity: 'bright'
                }),
                borderColor: randomColor({
                    hue: '#c93138',
                    luminosity: 'bright'
                }),
                borderWidth: 1,
            });
        }
        else
        {
            var ctr = 0;

            for(var i = 0; i < orderData[0][1].length; i++)
            {
                var sales = [];
                for(var j = 0; j < orderData.length; j++)
                {
                    sales.push(orderData[j][1][i]);
                    if(ctr == 0)
                    {
                        labels.push(moment(orderData[j][0]).format('MMM Do'));
                        if(j == orderData.length - 1)
                        {
                            ctr = 1;
                        }
                    }
                }

                color = randomColor({
                        format: 'rgba',
                        // alpha: 0.25,
                        luminosity: 'dark'
                    });
                dataset.push({
                    label: ins[i].category,
                    data: sales,
                    backgroundColor: color,
                    borderColor: color,
                    borderWidth: 1,
                });
            }
        }
        createChart(dataset, labels);
    }

    function createChart(dataset, labels)
    {
        if(type == 'sales')
        {
            var chartType = 'line';
        }
        else
        {
            var chartType = 'bar';
        }

        $('#myChart').html(null);
        var ctx = document.getElementById("myChart").getContext('2d');
        myChart = new Chart(ctx, {
            type: chartType,
            data: {
                labels: labels,
                datasets: dataset
            },
            options: 
            {
                scales: {
                    yAxes: [{
                        ticks: {
                            callback: function(label, index, labels) {
                                return type == 'sales' ? 'P' + numeral(label).format('0,0.00') : label;
                            }
                        }
                    }]
                },
                responsiveAnimationDuration: 3000,
                title:
                {
                    display: true,
                    text: moment(start).format('MMM Do') + ' - ' + moment(end).format('MMM Do') + 'Sales Report',
                },
            }
        });

        done++;
        $('#preloader').fadeOut('slow');
        getAnalysis();
    }

    function getAnalysis()
    {
        var date = $('#date').val();

        type = $('#type').val();
        start = date.split(' ')[0];
        end = date.split(' ')[2];

        $.ajax({
            type: 'POST',
            data: {type: type, start:start, end: end},
            url: '<?= base_url() . $this->session->role . "/getAnalysis"?>',
            success: function(result){
                result = JSON.parse(result);

                if(type == 'inventory')
                {
                    $('.salesAnalysis').html('');
                    console.log(result);
                }
                else if(type == 'sales')
                {
                    $('.salesAnalysis').html('');
                    for(i = 0; i < result.months.length; i++)
                    {
                        year = moment(result.months[i]).format('MMM DD ') + moment(result.months[i]).add(1, 'month').subtract(1, 'day').format('- MMM DD') + moment(result.months[i]).format(', YYYY') + '<br>';
                        sales = '<b>SALES: P' + numeral(result[i].sales).format('0,0.00') + '</b><br>';
                        expense = '<b>EXPENSE: P' + numeral(result[i].expense).format('0,0.00') + '</b><br><hr/>';
                        $('.salesAnalysis').append(year + sales + expense);
                    }
                }
            }
        })
    }

	function activateFPR()
	{
		$('#date').attr('placeholder', 'Select Date Range');
        $('#date').attr('disabled', false);
	}
</script>

<script type="text/javascript">
	document.title = "Reports";
	$('#type').select2({
		placeholder: 'Select Sales',
	});
	$("#date").css("height", $("#select2-type-container").height());
	$("#getButton").css("height", $("#select2-type-container").height());
</script>