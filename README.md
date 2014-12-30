srsly-breakdown
===============

Breakdown of a portion of the servers displayed on srsly.de by continent and country.

### How it works

This is a very small project (lots of boilerplate thanks to [Laravel 5](https://github.com/laravel/laravel/tree/develop)) with only a couple important files.

To understand how it works, take a look in the following places:

* [`app/helpers.php`](https://github.com/kylestev/srsly-breakdown/blob/master/app/helpers.php) - business logic
* [`app/Http/routes.php`](https://github.com/kylestev/srsly-breakdown/blob/master/app/Http/routes.php) - http routing
* [`resources/templates/main.blade.php`](https://github.com/kylestev/srsly-breakdown/blob/master/resources/templates/main.blade.php) - the one and only view used in this application

Charts are generated using [Highcharts](http://www.highcharts.com/).
