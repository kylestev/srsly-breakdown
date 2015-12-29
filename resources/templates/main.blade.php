<!DOCTYPE html>
<html lang="en">
<head>
  <title>"Srsly" VNC Server Breakdown</title>
  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
  <style type="text/css">
    .table > tbody > tr:first-child > td:first-child {
      width: 68%;
    }
  </style>
</head>
<body>

  <div class="container" id="app">
    <div id="piechartContainer" style="min-width: 310px; max-width: 600px; height: 400px; margin: 0 auto"></div>

    <form class="form-horizontal">
      <div class="form-group">
        <label class="control-label col-offset-sm-2 col-sm-4" for="report-year">
          Year:
        </label>
        <div class="col-sm-4">
          <select id="report-year" name="report-year" class="form-control" v-model="year">
            <option value="2015">2015</option>
            <option value="2014">2014</option>
          </select>
        </div>
      </div>
    </form>

    <template v-for="(name, report) in countries">
      <continent-table :name="name" :countries="report.countries"></continent-table>
    </template>
  </div>

  <!-- js -->

  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.7.0/underscore-min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/highcharts/4.0.4/highcharts.js"></script>
  <script type="text/javascript">
    window.makeChart = function (continents) {
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
            animation: false,
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
    };
  </script>
  <script src="/js/app.js"></script>
</body>
</html>