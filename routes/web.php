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

header('Access-Control-Allow-Origin: *');
header( 'Access-Control-Allow-Headers: Authorization, Content-Type' );

//Clear Cache facade value:
Route::get('/clear-cache1', function() {
    $exitCode = Artisan::call('cache:clear');
    return '<h1>Cache facade value cleared</h1>';
});

//Clear Route cache:
Route::get('/route-clear', function() {
    $exitCode = Artisan::call('route:clear');
    return '<h1>Route cache cleared</h1>';
});

//Clear View cache:
Route::get('/view-clear', function() {
    $exitCode = Artisan::call('view:clear');
    return '<h1>View cache cleared</h1>';
});

//Clear Config cache:
Route::get('/config-cache', function() {
    $exitCode = Artisan::call('config:cache');
    return '<h1>Clear Config cleared</h1>';
});


// HomeController
Route::get('/', 'HomeController@getLogin');
Route::get('/clear-cache', function() {$exitCode = Artisan::call('cache:clear');});
Route::get('/oauth/authorize', 'HomeController@getResponse');
Route::post('/shopify', 'HomeController@getPermission');
Route::get('/shopify', 'HomeController@getPermission');
Route::get('/delete', 'HomeController@Delete');
Route::any('/swapp/carriers_list','HomeController@carriersList');
Route::any('/swapp/carriers_package','HomeController@carriersPackage');
Route::any('/swapp/carriers_package_save','HomeController@carriersPackageSave');

Route::post('/swapp/main/get_merchant', 'MainController@GetMerchantData');
Route::any('/swapp/main/get_all_data', 'MainController@GetAllData');
Route::any('/testwebhook', 'WebhookController@testWebhook');
Route::any('/swapp/cart-token','CartController@cartTokenSave');
Route::any('/swapp/api-send-merchant','CartController@kachyngMerchant');
Route::any('/swapp/refund','CartController@refundPayment');

Route::any('/swapp/carrier-services','CartController@carrierServicesNew');
Route::any('/swapp/carrier-services-rate','CartController@carrierServices');
Route::any('/swapp/carrier-services-rate-test','CartController@carrierServicesTest');
Route::any('/swapp/carrier_rates','ProductController@postCarrier');

Route::any('/swapp/get_rates','CartController@getShippingRates');

   /**********  MyCode   **************/
	Route::get('/webhooks', 'MainController@CreateWebHooks');
	Route::get('/webhookss', 'MainController@CreateWebHookss');
	Route::get('webhookss/{id}', function ($id) {return 'webhookss '.$id;});

	//	https://naveen.store/shopify_app/public/webhookss
	// https://naveen.store/shopify_app/public/webhooks/ad-smart.myshopify.com/products/update
	Route::post('swapp/products/create', 'ProductController@ProductCreate');
	Route::post('webhooks/{shop}/products/delete', 'ProductController@ProductDelete');
	Route::post('webhooks/{shop}/products/update', 'ProductController@ProductUpdate');
	Route::post('swapp/orders/create', 'ProductController@OrderCreate');
	Route::post('webhooks/{shop}/orders/update', 'ProductController@OrderUpdate');
	Route::post('/orders/abandoned_checkouts', 'ProductController@AbundantCart');
	Route::post('/orders/abandoned_checkouts_email', 'ProductController@AbundantCartEmail');
	Route::get('/getabundantcart', 'ProductController@GetAbundantCart');
	// naveen.store/shopify_app/public/getabundantcart
	Route::post('/product/sync-product', 'ProductController@ProductSyncToKynch');

	// New Route
	Route::any('/swapp/create_order','ShopifyCartController@createPendingOrder');
	Route::any('/swapp/update_order','ShopifyCartController@updatePendingOrder');
	Route::any('/swapp/checkout/{id}','ShopifyCartController@Checkout');
	Route::post('swapp/checkout-card-submit/card-submit1', 'ShopifyCartController@CardSubmit1');
	Route::get('/swapp/testfunction','ShopifyCartController@kachyngshipping');
	Route::any('/swapp/shipping_rate_estimate','ShopifyCartController@shippingRateEstimate');

	// CartController
	Route::post('swapp/checkout', 'CartController@Checkout');
	Route::get('swapp/checkout', 'CartController@Checkout');
	Route::post('swapp/checkout/payment', 'CartController@CheckoutPayment');
	Route::post('swapp/checkout/shipping', 'CartController@CheckoutShipping');
	Route::post('swapp/checkout/card_payment', 'CartController@CardPayment');
	Route::post('swapp/checkout-cardsubmit/cardsubmit', 'CartController@CardSubmit');
	Route::any('/carrier_rates','ProductController@postCarrier');

	// WebhookController
	Route::post('webhooks/{shop}/api', 'WebhookController@BackUrl');
	Route::get('shipping_rates', 'ProductController@shippingrate');

	Route::get('swapp/get-variant', 'CartController@getVariant');

	Route::post('swapp/checkout/activecarriers','CartController@activeCarriers');
	//Route::post('/checkout/withoutShipping', 'CartController@activeCarriers');
	Route::get('swapp/checkout-states/fetch-states', 'CartController@allStates');
	Route::get('swappswapp/checkout/fetch-afstates', 'CartController@afStates');
	Route::post('swapp/checkout/apply-code', 'CartController@applyDiscounts');
	Route::get('swapp/checkout/total-price/{id}', 'CartController@totalPrice');
	Route::any('/proxy/{any}', 'ProxyController@Proxy')->where('any', '.*');

	Route::fallback(function(){
	    return response()->json(['message' => 'Not Found.'], 404);
	})->name('api.fallback.404');
	
	//Test Function
	//Route::any('/swapp/check_ex', 'SubscriptionController@checkExitingMembershipTEST');
	/**********  End MyCode   **************/



