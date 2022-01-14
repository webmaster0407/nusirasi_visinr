<?php

use App\Mail\OrderShipped;
use Illuminate\Support\Facades\Mail;


Route::group(['scheme' => 'https'], function () {
    // Route::get(...)->name(...);
});

Route::get('/', 'Number\CodeController@index')
    ->name('index');

Route::group(['prefix' => 'kodas'], function () {
    Route::get('{code}', 'Number\CodeController@read')
        ->name('number.code.read');
});

//Route::group(['prefix' => 'numeris'], function () {
    Route::get('/{number}', 'Number\NumberController@read')
        ->where(['number' => '[0-9]+'])
        ->name('number.number.read');

    Route::post('/{number}', 'Number\CommentController@create')
        ->where(['number' => '[0-9]+'])
        ->name('number.comment.create');
//});

/*
 * Logs
 */
Route::get('/logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')
    ->middleware('AllowOnlyAdminByIP');

/*
 * CFG
 */
Route::get('/cfg', function () {
    dd(Config('app_config'));
})->middleware('AllowOnlyAdminByIP');

/*
 * robots1.txt generator
 */
Route::get('/robots1.txt','Sitemap\SitemapController@robotsGenerator')->name('sitemap.robotsGenerator');

/*
 * Sitemap
 */
Route::group(['prefix' => 'sitemap'], function () {
    Route::get('index.xml','Sitemap\SitemapController@index')
        ->name('sitemap.index');

    Route::get('{section}.xml','Sitemap\SitemapController@read')
        ->where('section', '[a-z]+')->name('sitemap.read');

    /*
    Route::get('code/{code}.xml','Sitemap\SitemapController@readByCode')
        ->where('code', '[0-9]+')->name('sitemap.readByCode');
    */

    Route::get('code/{code}/{chunk}.xml','Sitemap\SitemapController@readByCodeChunk')
        ->where('code', '[0-9]+')->where('chunk', '[0-9]+')->name('sitemap.readByCodeChunk');
});

Route::get('/scrape8', function () {
    Artisan::call('kienonumeris_lt:scrape');
});
Route::post('/searchkey', 'Number\CodeController@search');
Route::post('/ajax_read', 'Number\NumberController@ajax_read');
Route::get('/lastcomments', 'Number\CommentController@index')
    ->name('comments');


Route::get('/comment/{number}', 'Number\CommentController@showComment')->name('number.comment.view');

Route::get('/contact', 'ContactController@show')->name('contact');

Route::post('/send-mail', function() {
    
    Mail::to('odincovaprofit@gmail.com')->send(new OrderShipped());
    $data = [
        'status' => '1',
        'data'  => null
    ];

    echo json_encode($data);
});

