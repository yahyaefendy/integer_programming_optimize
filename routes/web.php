<?php

use Illuminate\Support\Facades\Route;

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

Route::get('', 'Controller@index')->name('controller.index');
Route::get('create', 'Controller@create')->name('controller.create');
Route::post('store', 'Controller@store')->name('controller.store');
Route::get('addItem', 'Controller@addItem')->name('controller.addItem');
Route::post('saveItem/{id}', 'Controller@saveItem')->name('controller.saveItem');
Route::get('editItem/{id}/{id_product}', 'Controller@editItem')->name('controller.editItem');
Route::get('deleteItem/{id}/{id_product}', 'Controller@deleteItem')->name('controller.deleteItem');
Route::post('updateItem', 'Controller@updateItem')->name('controller.updateItem');

Route::get('generateForm', 'GenerateController@generateForm')->name('generate.generateForm');
Route::get('generateCancel/{id}', 'GenerateController@generateCancel')->name('generate.generateCancel');

Route::get('constraint', 'ConstraintController@constraint')->name('constraint.constraint');
Route::post('saveConstraint', 'ConstraintController@saveConstraint')->name('constraint.saveConstraint');
Route::post('updateConstraint', 'ConstraintController@updateConstraint')->name('constraint.updateConstraint');

Route::get('formData/{id}', 'Controller@formData')->name('controller.formData');
Route::get('editData/{id}', 'Controller@editData')->name('controller.editData');
Route::post('saveData', 'Controller@saveData')->name('controller.saveData');
Route::post('updateData/{id}', 'Controller@updateData')->name('controller.updateData');
