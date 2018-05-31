<div class="col-md-10 col-md-offset-2" style="margin-top: 1%;">
    <div class="panel panel-primary">
        <div class="panel-body">
            <div>
                <div id="preloader" style="position: fixed; left: 0; top: 0; z-index: 999; width: 100%; height: 100%; overflow: visible; background: rgba(51,51,51,1) url('<?= base_url() . 'img/hourglass.svg'?>') no-repeat center center;"></div>
                
                <?php if($this->session->role == 'admin'): ?>
                    <canvas id="myChart"></canvas>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?= base_url() ?>js/jquery.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/charts.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/moment.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/numeral.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/randomColor.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>js/select2.min.js"></script>
<script>
    
    document.title = 'Dashboard';
    
    <?php if($this->session->role == 'admin'): ?>
    $('#preloader').fadeOut();

    var start = moment().startOf('week').format('YYYY-MM-DD');

    var days = [];

    for (var i = 0; i < 7; i++) 
    {
        days.push(moment(start).add(i, 'd').format('YYYY-MM-DD'));
    }

    getSalesPerDay(JSON.stringify(days));

    function getSalesPerDay(days)
    {
        var orderData = [];
        $.ajax({
            type: 'POST',
            data: {days: JSON.parse(days)},
            url: '<?= base_url() . "Admin/getSalesPerDay" ?>',
            success: function(result2){
                var temp = JSON.parse(result2);
                for (var i = 0; i < temp.length; i++) {
                    var dateSales = [];
                    var sales = 0;
                    var date;
                    for (var j = 0; j < temp[i].length; j++) {
                        sales = temp[i][j].prod_price * temp[i][j].qty;
                        date = temp[i][j].date;
                    }
                    dateSales.push(sales);
                    dateSales.push(date);
                    orderData.push(dateSales);
                }
                createDataSet(orderData);
            },
        });

        console.log(orderData);
    }

    function createDataSet(orderData)
    {
        var sales = [];
        for (var i = 0; i < orderData.length; i++) 
        {
            sales.push(orderData[i][0]);
        }

        console.log(sales);
        var color = randomColor({
            luminosity: 'bright'
        });
        var dataset = [];

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

        // console.log(dataset);
        createChart(dataset);
    }

    function createChart(dataset)
    {
        var labels = [];
        for (var i = 0; i < days.length; i++) {
            labels.push(moment(days[i]).format('MMMM Do'));
        }

        var ctx = document.getElementById("myChart").getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
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
                                return 'P' + numeral(label).format('0,0.00');
                            }
                        }
                    }]
                },
                responsiveAnimationDuration: 3000,
                title:
                {
                    display: true,
                    text: "Current Week Sales",
                },
            }
        });
    }

    <?php else: ?>
    $('#preloader').fadeOut();
    <?php endif; ?>
</script>