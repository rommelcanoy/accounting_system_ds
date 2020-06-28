<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// Route::resource('company_info', 'CompanyInfoController');
Route::resource('reports', 'ReportController');

Route::resource('dashboard', 'DashboardController');
Route::post('dashboard/update', 'DashboardController@update')->name('dashboard.update');
Route::get('/getCompany/{id}','DashboardController@getCompany');

Route::resource('account', 'AccountController');
Route::get('print_accounts', 'AccountController@print');
Route::post('account/update', 'AccountController@update')->name('account.update');
Route::get('account/destroy/{id}', 'AccountController@destroy');

Route::resource('journal', 'JournalController');
Route::post('journal/update', 'JournalController@update')->name('journal.update');
Route::get('journal/destroy/{id}', 'JournalController@destroy');
Route::get('print', 'JournalController@print')->name('journal.print');

Route::get('ledger', 'GeneralLedgerController@index')->name('ledger.index');

Route::get('trial_balance', 'ReportController@trial_balance')->name('trial_balance.index');
Route::get('balance_sheet', 'ReportController@balance_sheet')->name('balance_sheet.index');
Route::get('income_statement', 'ReportController@income_statement')->name('income_statement.index');
Route::get('statement_oe', 'ReportController@statement_oe')->name('statement_oe.index');
Route::get('statement_cf', 'ReportController@statement_cf')->name('statement_cf.index');
// Route::resource('chart_account', 'ChartAccountController');

// Route::get('dashboard', 'DashboardController@index');
// Route::post('dashboard/add', 'DashboardController@add_company')->name('company.add');

// Route::get('account', 'AccountController@index');
// Route::post('account/insert', 'AccountController@insert')->name('account.insert');

// Route::get('journal', 'JournalController@index');
// Route::post('journal/insert', 'JournalController@insert')->name('journal.insert');

// Journal
// Route::get('journal', 'JournalEntryController');
