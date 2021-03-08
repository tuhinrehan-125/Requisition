<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('config:clear');
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('config:cache');
    return 'DONE'; //Return anything
});


Route::get('/', function () {
  return redirect('/login');
});
Route::get('/lang/{locale}', 'HomeController@changeLang')->middleware('localeCheck');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/product', 'HomeController@getItem')->name('get.all.item');
Route::get('/category', 'HomeController@getCategory')->name('get.all.category');
Route::post('/category/store', 'HomeController@storeCategory')->name('store.one.category');
Route::get('/category/edit', 'HomeController@editCategory')->name('edit.category');
Route::post('/category/update', 'HomeController@updateCategory')->name('update.one.category');
Route::post('/category/delete', 'HomeController@deleteCategory')->name('delete.one.category');

Route::post('/item/store', 'HomeController@storeItem')->name('store.item');
//Route::get('/item/{id}', 'HomeController@getOneItem')->name('get.one.item');
Route::post('/item/update', 'HomeController@updateOneItem')->name('update.one.item');
Route::post('/item/delete', 'HomeController@deleteOneItem')->name('delete.one.item');

Route::get('/product/edit-product', 'HomeController@edit_product')->name('product.edit');
Route::get('/get-items/{cat_id}', 'HomeController@getItemsByCat')->name('items.by.category');
Route::get('/items/getSubCategory/{id}', 'HomeController@getSubCategory')->name('sub-category.get');

// -------------Vendor by tuhin-----------
Route::get('/vendor', 'VendorController@getVendor')->name('get.all.vendor');
Route::post('/vendor/store', 'VendorController@store')->name('store.vendor');
Route::get('/vendor/{id}', 'VendorController@getOneVendor')->name('get.one.vendor');
Route::post('/vendor/update', 'VendorController@update')->name('update.vendor');
Route::post('/vendor/delete', 'VendorController@delete')->name('delete.vendor');
// -------------Department by tuhin-----------
Route::get('/department', 'DepartmentController@getDepartment')->name('get.all.department');
Route::post('/department/store', 'DepartmentController@store')->name('store.department');
Route::get('/department/{id}', 'DepartmentController@getOneDepartment')->name('get.one.department');
Route::post('/department/update', 'DepartmentController@update')->name('update.department');
Route::post('/department/delete', 'DepartmentController@delete')->name('department.vendor');

Route::post('/order/store', 'HomeController@storeOrder')->name('store.order');
Route::post('/order/update', 'HomeController@updateOrder')->name('update.order');
Route::get('/create/requisition', 'HomeController@createOrder')->name('add.new.order');

Route::get('/requisition/edit/{id}', 'HomeController@editOrder')->name('editorder');
Route::get('/get-order-items', 'HomeController@getOrderItems')->name('getOrderItems');

//Route::get('/home/created-orders', 'HomeController@createdOrders');

//superadmin // operator

Route::get('/order/detail/{order_id}', 'HomeController@viewOrderDetail')->name('view.order.detail');
Route::get('/approved/requisition', 'HomeController@getApprovedOrder')->name('get.approved.order');
Route::get('/received/product', 'HomeController@receivedProduct')->name('received.product');
Route::get('/pending/requisition', 'HomeController@getPendingOrder')->name('get.pending.order');
Route::post('/pending-requisition/delete', 'HomeController@pendingDelete')->name('pending.delete');
Route::get('/reject/requisition', 'HomeController@getRejectOrder')->name('get.reject.order');
Route::get('/pending-edit/{id}', 'HomeController@editPending')->name('edit.pending.requisition');
Route::post('/order/approve', 'RoleController@approveRequisition');
Route::post('/order/reject', 'RoleController@rejectRequisition');
Route::post('/order/forward', 'RoleController@forwardRequisition');
Route::post('/order/delivered', 'RoleController@deliveredRequisition');
Route::post('/order/received', 'RoleController@receivedRequisition');

Route::get('/home/trash', 'HomeController@allTrash');

Route::get('/home/user', 'HomeController@getUser');
Route::post('/user/store', 'HomeController@storeUser');
Route::post('/user/delete', 'HomeController@deleteUser');
//Route::get('/user/{id}', 'HomeController@getOneUser');
Route::get('/user/edit', 'HomeController@editUser')->name('user.edit');
Route::post('/user/update', 'HomeController@updateOneUser');
Route::post('/item-qty/update', 'HomeController@addDelQty');
Route::post('/single-qty/update', 'HomeController@addsingleDelQty');

Route::post('/search/order', 'HomeController@searchOrder');
Route::get('/profile', 'HomeController@getProfile');
Route::post('/change/password', 'HomeController@changePassword');
Route::post('/change/file', 'HomeController@changeFile');
Route::get('/delivered/requisition', 'HomeController@deliverReqForAll');
Route::post('/order/status/update', 'HomeController@updateStatusByAM');

Route::post('/restore', 'HomeController@restore');

Route::post('/permanent-delete', 'HomeController@permanentDelete');
Route::post('/search/survey', 'HomeController@searchSurvey');
Route::post('/search/certificate', 'HomeController@searchCertificate');


// Route::get('/home/deliver/order', 'HomeController@deliverReq');

//purchase by imtiaz.
Route::get('/purchase', 'PurchaseController@index');
Route::post('/purchase/store', 'PurchaseController@store')->name('store.purchase');
Route::get('/purchase/{id}', 'PurchaseController@show')->name('show.purchase');
Route::post('/purchase/update', 'PurchaseController@update')->name('update.purchase');
Route::get('/view-purchase/view', 'PurchaseController@view_purchase');
Route::post('/purchase/delete', 'PurchaseController@destroy')->name('delete.purchase');
Route::get('/purchase/getProduct/{id}', 'PurchaseController@getProductByCategory')->name('product.get');

//stock by imtiaz.
Route::get('/stock', 'StockController@index');
Route::get('/my-stock', 'StockController@mystock');
Route::post('/add-opening-stock', 'StockController@addOpeningStock');


Route::get('/report/requisition', 'HomeController@report_requisition')->name('report-requisition.index');
Route::post('/search/requisition', 'HomeController@searchRequisition');

Route::get('/report/stock', 'HomeController@report_stock')->name('report-stock.index');
Route::post('/search/stock', 'HomeController@searchStock');

Route::get('/report/total-purchase', 'PurchaseController@report_purchase')->name('report-purchase.index');
Route::post('/search/total-purchase', 'PurchaseController@searchPurchase');

Route::get('/report/total-delivered', 'HomeController@report_total_delivered')->name('report-total-delivered.index');
Route::post('/search/total-delivered', 'HomeController@searchTotalDelivered');

Route::get('/report/total-received-product', 'HomeController@report_total_received')->name('report-total-received-product.index');
Route::post('/search/total-received-product', 'HomeController@search_total_received');
