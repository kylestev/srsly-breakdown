<!DOCTYPE html>
<html lang="en">
<head>
  <title>"Srsly" VNC Server Breakdown</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
  <style type="text/css">
    .table > tbody > tr:first-child > td:first-child {
      width: 68%;
    }
  </style>
</head>
<body>

  <div class="container">
    <div id="piechartContainer" style="min-width: 310px; max-width: 600px; height: 400px; margin: 0 auto"></div>

    @foreach($continents as $cont_name => $continent)

    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">{{ $cont_name }} Server Breakdown</h3>
      </div>
      <table class="table table-striped">
        <tbody>
            @foreach(array_get($continent, 'countries') as $country => $count)

              <tr>
                <td>{{ $country }}</td>
                <td>{{ $count }}</td>
              </tr>
          @endforeach

        </tbody>
      </table>
    </div>

    @endforeach
  </div>

  <!-- js -->

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.7.0/underscore-min.js"></script>
  <script src="http://cdnjs.cloudflare.com/ajax/libs/highcharts/4.0.4/highcharts.js"></script>
  <script src="http://code.highcharts.com/modules/drilldown.js"></script>

  <script type="text/javascript">
    $(function () {

      $.get('/api/continents/{{ $year }}', function (continents) {
        var continentData = [],
          countries = {},
          drilldownSeries = [];

        var total = _.reduce(continents, function (memo, cont) { return memo + cont.count; }, 0);

        $.each(continents, function (name, data) {
          continentData.push({
            name: name,
            y: data.count * 100.0 / total,
            drilldown: name
          });

          var ctotal = _.reduce(data.countries, function (memo, country) { return memo + country; }, 0);

          drilldownSeries.push({
            name: name,
            id: name,
            data: _.map(data.countries, function (val, key) { return [key, 100.0 * val / ctotal]; })
          });
        });

        var config = {
          chart: {
            type: 'pie'
          },
          title: {
            text: 'Breakdown of Public VNC Servers by Continent'
          },
          subtitle: {
            text: 'Click a continent to view the country breakdown'
          },
          plotOptions: {
            series: {
              dataLabels: {
                enabled: true,
                format: '{point.name}: {point.y:.2f}%'
              }
            }
          },
          tooltip: {
            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>'
          },

          series: [{
            name: 'Continents',
            colorByPoint: true,
            data: continentData
          }],
          drilldown: {
            series: drilldownSeries
          }
        };

        $('#piechartContainer').highcharts(config);
      });

    });
  </script>
</body>
</html>