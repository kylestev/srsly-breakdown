<?php

Route::get('/', function () {
    return redirect('/2015');
});

Route::get('/{year}', 'SummaryController@summary');
Route::get('/api/continents/{year?}', 'SummaryController@jsonSummary');
