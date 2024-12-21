<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Shopify;
use View;
use Input;
use DB;
use Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class CartController extends HomeController
{
  	protected $app;

  	// default function calls before page load checkout
  	public function Checkout(Request $request)
  	{
  		// echo "<pre>";	print_r($request->all());	die;
  		$user_type = $request->user_type;
  		$shop = $request->shop;
		$this->shop = $shop = str_replace('https://', '', $request->shop);
		Session::put('shop', false);
		Session::put('access_token', false);
		Session::put('shop', $shop);
		$raw = json_encode($request->all());
		$wallet_address = DB::table('shopify_url_credentials')->where('site_url', $shop)->value('wallet_address');
		$access_token = DB::table('shopify_url_credentials')->where('site_url', $shop)->value('token');
		$app_id =  DB::table('shopify_url_credentials')->where('site_url', $shop)->value('id');
        $save_account = \App\MigrationAccount::where([['app_id', '=', $app_id],['active', '=', 1]])->value('id');
		$applicaton_id =  \App\MerchantData::where([['merchant_account_id', '=', $save_account],['merchant_key', '=', 'applicaton_id']])->value('merchant_data');
		$api_key =  \App\MerchantData::where([['merchant_account_id', '=', $save_account],['merchant_key', '=', 'apikey']])->value('merchant_data');
		Session::put('access_token', $access_token);
		$products = array();
		$total_price = 0;$total_quantity=0;
		$no = 1;
		$keyData =  DB::table('migration_accounts')->where('id', $save_account)->first();
        $uname = isset($keyData->uname) ? $keyData->uname:'';
		$shop2 =  DB::table('shopify_url_credentials')->where('token', $access_token)->value('admin_url');
		$this->foo = Shopify::retrieve($shop2, $access_token);
		// echo "<pre>";	print_r($request->all());	echo "<br>";
		foreach ($request->all() as $key => $value) {
			if(($key != 'checkout_name') &&($key != '_token') &&($key != 'currency') &&($key != 'shop') &&($key != 'button') &&($key != 'customer_id')  &&($key != 'all_data')){
				// $priceEX = explode('.', $request->total_price);
				$priceEX = $request->total_price;
				if($key == 'product_id'){
					foreach($value as $v){
						$productID_ = $v;
						$variantID = $this->getVariant($productID_, $shop2, $access_token);
						$array['no'] = $no;
						$array['title'] =  str_replace('_', ' ', $key);	
						$array['product_id'] = $productID_;
						$array['varient_id'] = $variantID;
						$product = $this->foo->get('products/'.$array['product_id']);
						//echo"<pre>"; print_r($product);	die;
						$array['product_price'][] = $product['product']['variants'][0]['price'];
						$array['productTitle'][] = $product['product']['title'];

						$string = $product['product']['body_html'];
						$explode = array_slice(explode(' ', $string), 0, 10);
						$implode = implode(" ",$explode); 
						$array['productDES'][] = $implode;

						$custom = $this->foo->get('products/'.$array['product_id'], ['cubrid_field_seek(result)'=>'images']);
			            foreach ($custom['product']['images'] as $key_1 => $value_1) {
			                $array['image'][] = $value_1['src'];
			                break;
			            }
					}
					$array['quantity'] = isset($request->quantity) ? $request->quantity:'1';
					$total_quantity = $total_quantity + $array['quantity'];
					$array['price'] = isset($priceEX) ? $priceEX:'';
					$array['shipping_rates'] = $request->shipping_rates;

					$array['h_country'] = $request->h_country;
					$array['h_state'] = $request->h_state;
					$array['h_zipcode'] = $request->h_zipcode;
					$array['h_shipping_method'] = $request->h_shipping_method;

		            // echo"<pre>"; print_r($array);	die;
					$cart_data[] = $array;
					$total_price += $array['price'];
					$products['total_price'] = $total_price;
					$products['product'][] = $array;
					$products['product']['titles'][] = $key;
				}
			}
			$products['currency'] = $request['currency'];
			$products['customer_id'] = $request['customer_id'];	
			$no++;
		}
		$my = $this->foo->get('shop');
		if($request['customer_id']){
				$get_customer_data = $customer = $this->foo->get('customers/'.$request['customer_id']);
				$customer_data['customer_email'] =$customer['customer']['email'] ? $customer['customer']['email'] : '';
				$customer_data['customer_first_name'] = isset($customer['customer']['first_name']) ? $customer['customer']['first_name'] : '';
				$customer_data['customer_last_name'] = isset($customer['customer']['last_name']) ? $customer['customer']['last_name'] : '';
				$customer_data['customer_phone'] = isset($customer['customer']['addresses'][0]['phone']) ? $customer['customer']['addresses'][0]['phone'] : '';
				$customer_data['cus_add_first_name'] = isset($customer['customer']['default_address']['first_name']) ? $customer['customer']['default_address']['first_name'] : '';
				$customer_data['cus_add_last_name'] = isset($customer['customer']['default_address']['last_name']) ? $customer['customer']['default_address']['last_name'] : '';
				$customer_data['cus_add_company'] = isset($customer['customer']['default_address']['company']) ? $customer['customer']['default_address']['company'] : '';
				$customer_data['cus_add_phone'] = isset($customer['customer']['default_address']['phone']) ? $customer['customer']['default_address']['phone'] : '';
				$customer_data['cus_add_address1'] = isset($customer['customer']['default_address']['address1']) ? $customer['customer']['default_address']['address1'] : '';
				$customer_data['cus_add_address2'] = isset($customer['customer']['default_address']['address2']) ? $customer['customer']['default_address']['address2'] : '';
				$customer_data['cus_add_city'] = isset($customer['customer']['default_address']['city']) ? $customer['customer']['default_address']['city'] : '';
				$customer_data['cus_add_zip'] = isset($customer['customer']['default_address']['zip']) ? $customer['customer']['default_address']['zip'] : '';
				$customer_data['province'] = isset($customer['customer']['default_address']['province']) ? $customer['customer']['default_address']['province'] : '';
				$customer_data['country_code'] = isset($customer['customer']['default_address']['country_code']) ? $customer['customer']['default_address']['country_code'] : '';
				$customer_email = $customer_email = $get_customer_data['customer']['email'];
				$customer_name = $get_customer_data['customer']['first_name'].' '.$get_customer_data['customer']['last_name'];
				$user_data=array("user"=>array("email"=>$get_customer_data['customer']['email'],"firstname"=>$get_customer_data['customer']['first_name'],"lastname"=>$get_customer_data['customer']['last_name'],"authProvider"=>"facebook","authProviderId"=>$applicaton_id?$applicaton_id:"string","deviceVendor"=>"string","deviceId"=>"string","deviceName"=>"string","deviceOsVersion"=>"string"));
				$customer_id= $request['customer_id']?$request['customer_id']:'';
				$method = "POST";
				$api_query = "accounts/sessions/create";
				$controller = new MainController;
				$result = $controller->api_kachyng($method, $api_query, json_encode($user_data), $uname);
				$sessionToken = json_decode($result, true);
				
				if(is_array($sessionToken['user']['fundExternals'])){
					foreach ($sessionToken['user']['fundExternals'] as $key => $value) {
						$card['fundExternalId'] = $value['fundExternalId'];
						$card['cardDisplay'] = $value['cardDisplay'];
						$card['cardRef'] = $value['cardRef'];
						$saved_cards[] = $card;
					}
				}
				$sessionToken= $sessionToken['sessionToken'];
				$user='login';
			}else{
				$customer_data['customer_email'] = $customer_data['customer_first_name'] = $customer_data['customer_last_name'] = $customer_data['customer_phone'] = $customer_data['cus_add_first_name'] = $customer_data['cus_add_last_name'] = $customer_data['cus_add_company'] = $customer_data['cus_add_phone'] = $customer_data['cus_add_address1'] = $customer_data['cus_add_address2'] = $customer_data['cus_add_city'] = $customer_data['cus_add_zip'] = $customer_data['province'] = $customer_data['country_code'] = '';
				$user='guest';
			}
		$countries=$this->getAllCountries();
		$shipping_rates = '';
		return View::make('pages.checkout_details')->with(array(
			'shop' => $request->shop,
			'store_name' => $my['shop']['name'],
			'wallet_address' => $wallet_address,
			'currency_symbol' => $request->currency_symbol,
			'total_price'=> number_format($total_price, 2),
			'currency'=>$request->currency,
			'titles'=>$products['product']['titles'],
			'customer_data'=>$customer_data,
			'cart_datas' => $cart_data,
			'total_quantity' => $total_quantity,
			'raw' => $raw,
			'user'=>$user,
			'customer_email'=>(isset($customer_email)?$customer_email:''),
			'customer_id'=>(isset($customer_id)?$customer_id:''),
			'sessionToken'=>(isset($sessionToken)?$sessionToken:''),
			'saved_cards'=>(isset($saved_cards)?$saved_cards:''),
			'country'=>$countries,
			'shipping_rates' => $shipping_rates,
			'user_type' => $user_type,
		));
	}

		// function calls on clicking payment button
	public function CardSubmit(Request $request)
	{	
		parse_str($request->payment_form, $order_data);
		$raw_data = $order_data;
		$getSHOP = json_decode($raw_data['raw_data'], true);
		$raw_data['shop'] = $getSHOP['shop'];
		$shop = str_replace('https://', '', $raw_data['shop']);
		$access_token =  DB::table('shopify_url_credentials')->where('site_url', $shop)->value('token');
		$shop2 =  DB::table('shopify_url_credentials')->where('token', $access_token)->value('admin_url');
		if(!empty($shop2)){
			if (strpos($shop2,'https://') === false){
				$admin_url = 'https://'.$shop2;
			}
		}
		$this->foo = Shopify::retrieve($shop2, $access_token);
		$app_id =  DB::table('shopify_url_credentials')->where('site_url', $shop)->value('id');
        $save_account = \App\MigrationAccount::where([['app_id', '=', $app_id],['active', '=', 1]])->value('id');
        $applicaton_id =  \App\MerchantData::where([['merchant_account_id', '=', $save_account],['merchant_key', '=', 'applicaton_id']])->value('merchant_data');
        $api_key =  \App\MerchantData::where([['merchant_account_id', '=', $save_account],['merchant_key', '=', 'apikey']])->value('merchant_data');
		$fbapikey =  \App\MerchantData::where([['merchant_account_id', '=', $save_account],['merchant_key', '=', 'fbapikey']])->value('merchant_data');
		$keyData =  DB::table('migration_accounts')->where('id', $save_account)->first();
        $uname = isset($keyData->uname) ? $keyData->uname:'';
		if (array_key_exists("customer_id",$raw_data)) {
			$get_customer_data = $this->foo->get('customers/'.$raw_data['customer_id']);
		}		
		$no = $total_quantity = $total_price = 0;
		$productIN = $raw_data['raw_data'];
		$pro_dec = json_decode($productIN, true);
		// echo"<pre>";  print_r($pro_dec);	die;
		foreach($pro_dec['product_id'] as $v){
			$productID_ = $v;
			$product_title = $this->foo->get('products/'.$productID_, ['fields'=>'title']);
			$variantID = $this->getVariant($productID_, $shop2, $access_token);
			$pro['title'] = $product_title['product']['title'];
			$pro['id'] = $productID_;
			$pro['varient_id'] = $variantID;
			$array = $pro;
		}
		$array['quantity'] = '1';
		// $priceEX = explode('.', $pro_dec['total_price']);
		$priceEX = $pro_dec['total_price'] + @$pro_dec['shipping_rates'];
		$array['price'] = trim($priceEX);
		$array['original_price'] = trim($priceEX);
		$total_price = trim($priceEX);	

		$add_raw = json_decode($order_data['raw_data'], true);		
		$options['order']['line_items'][] = $array;
		$options['order']['customer']['first_name'] = $order_data['customer_first_name'];
		$options['order']['customer']['last_name'] = $order_data['customer_last_name'];
		$options['order']['customer']['email'] = $order_data['customer_email'];
		$options['order']['billing_address']['first_name'] = $order_data['customer_first_name'];
		$options['order']['billing_address']['last_name'] = $order_data['customer_last_name'];
		$options['order']['billing_address']['address1'] = $order_data['cus_add_address1'].' '.$order_data['cus_add_address2'];

		$options['order']['billing_address']['phone'] = $order_data['customer_phone'];
		$options['order']['billing_address']['city'] = $order_data['cus_add_city'];
		$options['order']['billing_address']['province'] = $add_raw['address']['province'];
		$options['order']['billing_address']['country'] = $add_raw['address']['country'];
		$options['order']['billing_address']['zip'] = $add_raw['address']['zip'];
		$options['order']['shipping_address']['first_name'] = $order_data['customer_first_name'];
		$options['order']['shipping_address']['last_name'] = $order_data['customer_last_name'];
		$options['order']['shipping_address']['address1'] = $order_data['cus_add_address1'];

		$options['order']['shipping_address']['phone'] = $order_data['customer_phone'];
		$options['order']['shipping_address']['city'] = $order_data['cus_add_city'];
		$options['order']['shipping_address']['province'] = $add_raw['address']['province'];
		$options['order']['shipping_address']['country'] = $add_raw['address']['country'];
		$options['order']['shipping_address']['zip'] = $add_raw['address']['zip'];
		$options['order']['email'] = $order_data['customer_email'];
		$options['order']['financial_status'] = 'paid';
		$order['customer']['email'] = $order_data['customer_email'];
		$order['customer']['first_name'] = $order_data['customer_first_name'];
		$order['customer']['last_name'] = $order_data['customer_last_name'];
		$order['customer']['authProvider'] = 'facebook';
		$order['customer']['authProviderId'] = $fbapikey;
		$order['customer']['mobile'] = $order_data['customer_phone'];
		// echo"<pre>";  print_r($order);	die;
		if (array_key_exists("line_items",$options['order']))
  		{
	  		foreach ($options['order']['line_items'] as $key => $value) {
	            $product['name'] = ' ' .trim($value['title']);
				$product['price'] = $value['price'];
	            $product['upc'] = (int) $value['id'] ;
	            $product['active'] = true;
	            // $product['quantity'] = (string)$value['quantity'];                
	            $product['quantity'] = '1';                
	            $custom = $this->foo->get('products/'.$value['id'], ['fields'=>'body_html,images']);
	            $product['description'] = trim(substr($custom['product']['body_html'], 0, 30));
	            $product['longDescription'] =trim($custom['product']['body_html']);
					foreach ($custom['product']['images'] as $key_1 => $value_1) {
						$image['is_deafult'] = "true";
						$image['url'] = $value_1['src'];
						$product['images'][0] = $image;
						break;
					}
				if(!empty($order_data['green_add_address1'])){
					$shipping['addressName'] =  @$order_data['green_add_address1'] .' '. @$order_data['green_add_address2'];
					$shipping['addressLine1'] =  @$order_data['green_add_address1'];
					$shipping['addressLine2'] =  @$order_data['green_add_address2'];
					$shipping['cityName'] =  @$order_data['green_city'];
					$shipping['postalCode'] =   @$order_data['green_add_zip'];
					$shipping['regionName'] =  @$order_data['green_add_province'];
					$shipping['countryCode'] =  @$order_data['cus_add_country'];
				}else{
					$shipping['addressName'] =   @$order_data['cus_add_address1'] .' '. @$order_data['cus_add_address2'];
					$shipping['addressLine1'] =   @$order_data['cus_add_address1'];
					$shipping['addressLine2'] =   @$order_data['cus_add_address2'];
					$shipping['cityName'] =   @$order_data['cus_add_city'];
					$shipping['postalCode'] =    @$order_data['cus_add_zip'];
					$shipping['regionName'] =   @$order_data['cus_add_city'];
					$shipping['countryCode'] =   @$order_data['cus_add_country'];
				}
				
	            $product['package_dimensions']['height'] = 0;
	            $product['package_dimensions']['length'] = 0;
	            $product['package_dimensions']['width'] = 0;
	            $product['package_dimensions']['length_unit'] = 'string';
	            $product['package_dimensions']['weight'] = 0;
	            $product['package_dimensions']['weight_unit'] = 'string';
	            $product['shippable'] = true;
	            $product['google_category'] = 'string';
	            $product['taxable'] = true;
	            $product['on_sale'] = true;
				$product['sale_price'] = (int) $value['original_price']/100;
	            $order['products'][] = $product;
				$order['shippingAddress'][] = $shipping;
	        }
  		}
       
		$raw_data = json_decode($raw_data['raw_data'], true);
		// $currenCYY = explode('.', $raw_data['total_price']);
		$currenCYY = $raw_data['total_price'] + @$add_raw['shipping_rates'];
        $currency = 'USD';
        $total_price = trim($currenCYY);
		if(array_key_exists("customer_id",$raw_data)){
		   	if(array_key_exists("Saved_Card",$order_data))
		   	{
				$rdata = array();
				$rdata['cardToken'] = $order_data['Saved_Card'];
				$rdata['amount'] = $total_price;
				$rdata['currencyCode'] = 'USD';
				$rdata['transaction_identifier'] = 'transaction_56789';
				$rdata['paymentRequestType'] = 'authorize';
				$rdata['customerId'] = @$raw_data['customer_id'];
				$rdata['email'] = $order_data['customer_email'];
				$rdata['saveCard'] = 'Y';
				$request_data = $rdata;
				$api_query='https://app.kachyng.com/api/v3/form/payment';
			}
			else
			{
				$rdata = array();
				$rdata['cardToken'] = $order_data['payment_method_token'];
				$rdata['amount'] = $total_price;
				$rdata['currencyCode'] = 'USD';
				$rdata['transaction_identifier'] = 'transaction_56789';
				$rdata['paymentRequestType'] = 'authorize';
				$rdata['customerId'] = @$raw_data['customer_id'];
				$rdata['email'] = $order_data['customer_email'];
				$rdata['saveCard'] = 'Y';
				$request_data = $rdata;
				$api_query='https://app.kachyng.com/api/v3/form/payment';
			}
		}else{
			// guest checkout
			$tokenSD = DB::table('card_tokens')->where('user_email', $order_data['customer_email'])->get()->last();	
			$rdata = array();
			$rdata['cardToken'] = $tokenSD->card_token;
			$rdata['amount'] = $total_price;
			$rdata['currencyCode'] = 'USD';
			$rdata['transaction_identifier'] = 'transaction_56789';
			$rdata['paymentRequestType'] = 'authorize';
			$rdata['customerId'] = '123454';
			$rdata['email'] = $order_data['customer_email'];
			$rdata['saveCard'] = 'Y';
			$request_data = $rdata;
			$api_query='https://app.kachyng.com/api/v3/form/payment';
		}
		$controller = new MainController;
		$method = 'POST';		
		$PaymentDataSendResult = $controller->api_kachyng($method, $api_query, json_encode($request_data), trim($uname));
		$PaymentDataSend = json_decode($PaymentDataSendResult, true);


		// echo "<pre>";	print_r($options);	die;

		if($PaymentDataSend['success'] == '1'){
			$method = 'POST';		
			$api_query = "buy/order";
			$result = $controller->api_kachyng($method, $api_query, json_encode($order), trim($uname));
			$response_api = json_decode($result, true);
			if($response_api['success'] == true){
				$order_response =  $this->foo->createOrders($options);
				$data_query = [['method' => 'Order', 'data' => $result],];
				$result_data = \App\Testdata::insert($data_query);
				$data_query = [['buy_url' => $response_api['buy_url'], 'orderNumber' => $response_api['orderNumber']],];
				$insertData = DB::table('success_payment')->insert(
				    [
				    	'shopify_user_id' => @$raw_data['customer_id'], 'email' => $order_data['customer_email'], 'meta_data' => $PaymentDataSendResult, 'shopify_order_id' => $order_response['order']['id']
					]
				);
				$result_data = \App\Orders::insert($data_query);
				$output['redirect_url'] = $order_response['order']['order_status_url'];
				$output['success'] = 'true'; 
				return json_encode($output);
			}else{
				$output['success'] = 'false'; 
				$output['error'] = $result; 
				return json_encode($output);
			}			
		}else{
			$output['success'] = 'false'; 
			$output['error'] = $PaymentDataSend; 
			return json_encode($output);
		}
	}

	public function getVariant($product_id, $shop2, $access_token){
		$product_id = $product_id;
		$shop2 = $shop2;
		$admin_url = 'https://'.$shop2;
		$access_token = $access_token;
		$product_data_url = $admin_url.'/admin/products/'.$product_id.'.json';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$product_data_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$header = array(
			'X-Shopify-Access-Token: '.$access_token,
			'Content-Type: application/json',
			'Host: '.$shop2);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		$server_output = curl_exec ($ch);
		$result_proDuct=json_decode($server_output);
		$product_info = $result_proDuct->product->variants;
		$variant_id = $product_info[0]->id;
		return $variant_id;
	}

	public function getShippingRates()
	{
		$url = "https://acmeshop.xyz/cart";
		$curl = curl_init();
		curl_setopt_array($curl, [
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $url,
			CURLOPT_USERAGENT => 'Codular Sample cURL Request'
		]);
		$resp = curl_exec($curl);
		curl_close($curl);
		$results = json_decode($resp, true);
		echo"<pre>"; print_r($results);	die;	 	
	}

	// currency symbol icon
	public function currency_symbol($value){
		$currency_symbols = '<i class="fa fa-'.strtolower($value).'" aria-hidden="true"></i>';
		return $currency_symbols;
	}

	public function CheckoutGet(){
		$shop = "https://".Session::get('shop')."/cart";
		return Redirect::away($shop);
	}

	public function PendingPayment($rawdata){
		$id = DB::table('pendingpayments')->insertGetId(['data' => $rawdata]);
		return $id;
	}
	
	
	
	/***********************************************************************************
		fetch all active shipping careers with shipping rates for store
	************************************************************************/
	public function activeCarriers(Request $request)
	{	
		$options= array("checkout" => array (
		"email" => $request->get('email'),
		"shipping_address" => array(
				"first_name"=>$request->get('fname'),
				"last_name"=>$request->get('lname'),
				"address1"=>"#123 Housee nu 123",
				"city"=>"Chandigarh",
				"province_code"=>$request->get('state'),
				"country_code"=>$request->get('country'),
				"phone"=>'(123)456-7890',
				"zip"=>$request->get('zip_code'),
			),
		));
		$shop = Session::get('shop');
		$access_token = Session::get('access_token');
		$shop2 =  DB::table('shopify_url_credentials')->where('token', $access_token)->value('admin_url');
		if(!empty($shop2)){
			if (strpos($shop2,'https://') === false){
				$admin_url = 'https://'.$shop2;
			}
		}
		// print_r($shop2);	die;
		$this->app = Shopify::retrieve($shop, $access_token);
		$check_token='';
		$carrier_rates=array();
		$check_token=Session::get('checkout_token');
		$line_array = $request->get('line_items');
		foreach($line_array as $key => $val){
			$line_array[$key]['variant_id'] = $val['varient_id'];
		}
   	    $options['checkout']['line_items']= $line_array;
		$rand_acct=Session::get('rand_token');
		$chec_data=json_encode($options);
		
		Session::put('product_id', $line_array[0]['product_id']);
			$access_token = Session::get('access_token');
			$data=json_encode($options);
			$storeUrl = DB::table('shopify_url_credentials')->where('token', $access_token)->value('admin_url');
			$store_url = $admin_url;
			$url = $store_url.'/admin/checkouts.json';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$header = array(
				'X-Shopify-Access-Token: '.$access_token,
				'Content-Type: application/json',
				'Host: '.$shop2);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			$server_output = curl_exec($ch);
			$carrier_rates=json_decode($server_output);
		try 
		{	
			$carrier_rates = $this->app->createcheckouts($options);
			echo "<pre>";	print_r($carrier_rates);	die(' YY ');
			if(!empty($carrier_rates)){				
				Session::put('checkout_token', $carrier_rates->checkout->token);
				$check_token=Session::get('checkout_token');
				$access_token = Session::get('access_token');
				$storeUrl = DB::table('shopify_url_credentials')->where('token', $access_token)->value('admin_url');
				if(!empty($storeUrl)){
					if (strpos($storeUrl,'https://') === false){
						$store_url = 'https://'.$storeUrl;
					}
				}else{
					$store_url = 'https://naveen-kachyng.myshopify.com';
				}
				$url = $store_url.'/admin/checkouts/'.$carrier_rates->checkout->token.'/shipping_rates.json';
				//$url = $store_url.'/admin/checkouts.json';
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL,$url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$header = array(
					'X-Shopify-Access-Token: '.$access_token,
					'Content-Type: application/json',
					'Host: '.@$storeUrl);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
				$server_output = curl_exec ($ch);
				$res_array=json_decode($server_output);
				$array = json_decode(json_encode($res_array), true);
				if(!empty(@$array['errors']['checkout']['base'][0]['code'] == 'does_not_require_shipping'))
				{
					$chec_tk_cnt=DB::table('secure_info')->where('checkout_token',$check_token)->count();
						if($chec_tk_cnt>0):
							$chec_id=DB::table('secure_info')->where('checkout_token', $carrier_rates->checkout->token)->update(
							['checkout_token' => $carrier_rates->checkout->token, 'shipping_rates' => @$stre_shipping,'status'=>'insert','checkout_data'=>$chec_data]
							);
						else:
							$chec_id=DB::table('secure_info')->insert(
							['checkout_token' => $carrier_rates->checkout->token, 'shipping_rates' => @$stre_shipping,'status'=>'insert','checkout_data'=>$chec_data]
							);
						endif;
							$email_cnt=DB::table('users')->where('email', $request->get('email'))->where('shop_token',$access_token)->count();
						if($email_cnt>0):
							$id=DB::table('users')->where('email', $request->get('email'))->where('shop_token',$access_token)->update(
							['name' => $request->get('fname').' '.$request->get('lname'), 'email' => $request->get('email'),'crypto_address'=>$request->get('addon_add'),'type'=>'guest']
							); 
						else:
							$id=DB::table('users')->insert(
							['name' => $request->get('fname').' '.$request->get('lname'), 'email' => $request->get('email'),'crypto_address'=>$request->get('addon_add'),'type'=>'guest','shop_token'=>$access_token]
							);
						endif;
                   //$che= $this->checkBalanceWithoutShipping($carrier_rates);
				   
				    $che = array();
				    $che['msg'] = 'No shipping Available';
                    return $che;
				   
				}else{
				if(!empty(@$array['errors']['checkout']))
				{
					$red_array['msg']=$array['errors']['checkout']['base'][0]['message'];
				}
				if(!empty(@$array['shipping_rates'])){
					$red_array = $array['shipping_rates'];
					foreach($red_array as $key => $data) {
						if(is_array($data)){
						$a=array();
							if(array_key_exists('delivery_range', $data)){
								if(is_array($data['delivery_range'])){
									foreach($data['delivery_range'] as $k=>$v){
										//$a[$k]=substr($v, 0,strpos($v,'T'));
										if(!empty($v)){
											$date2  = substr($v, 0,strpos($v,'T'));
											$date1 = date('Y-m-d');
											$diff = abs(strtotime($date1) - strtotime($date2));
											$years = floor($diff / (365*60*60*24));
											$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
											$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
											$a[$k]=$days;
										}
									}
									if(!empty($a[$k])){
										$red_array[$key]['delivery_range']=$a[$k];
									} 
								}
							}
						}	
					}
				}
				
				curl_close ($ch);
				if(!@$red_array){
					$red_array['msg']="Shopify's shipping rates apis are taking longer than expected. Try again by re-typing zip Code.";
				}
				$stre_shipping=json_encode($red_array);
				Session::put('shipping_rates', $red_array);
		    $chec_tk_cnt=DB::table('secure_info')->where('checkout_token',$check_token)->count();
			if($chec_tk_cnt>0):
		          $chec_id=DB::table('secure_info')->where('checkout_token', $carrier_rates->checkout->token)->update(
				    ['checkout_token' => $carrier_rates->checkout->token, 'shipping_rates' => @$stre_shipping,'status'=>'insert','checkout_data'=>$chec_data]
				);
	        else:
        	$chec_id=DB::table('secure_info')->insert(
		    ['checkout_token' => $carrier_rates->checkout->token, 'shipping_rates' => @$stre_shipping,'status'=>'insert','checkout_data'=>$chec_data]
		        );
             endif;
			 $email_cnt=DB::table('users')->where('email', $request->get('email'))->where('shop_token',$access_token)->count();
	        if($email_cnt>0):
	            $id=DB::table('users')->where('email', $request->get('email'))->where('shop_token',$access_token)->update(
			    ['name' => $request->get('fname').' '.$request->get('lname'), 'email' => $request->get('email'),'crypto_address'=>$request->get('addon_add'),'type'=>'guest']
			    ); 
            else:
				$id=DB::table('users')->insert(
			    ['name' => $request->get('fname').' '.$request->get('lname'), 'email' => $request->get('email'),'crypto_address'=>$request->get('addon_add'),'type'=>'guest','shop_token'=>$access_token]
			    );
		    endif;
			
			//echo"<pre>";  print_r($red_array);  die;
             return response()->json($red_array); 
        }
        } 
	} 
	catch (\GuzzleHttp\Exception\ClientException $e) 
		{
			$response = $e->getMessage();
			$red_array=array();
			$red_array['msg']='Address or Zip code is invalid. Try again by entering correct information.';
		}  	
	}


	/***********************************************************************************
		fetch all countries in the world from db
	************************************************************************/
    public function getAllCountries()
	{
        $c_codes= DB::table('countries')
                ->get();
				//print_r($c_codes);  die;
        $cntry_codes=array();
        $array = json_decode(json_encode($c_codes), true);
            for($i=0;$i<count($array);$i++) {
            	# code...
            	$cntry_codes[$i]['c_name']=$array[$i]['name'];
            	$st_data=json_decode($array[$i]['state_data']);
            	$cntry_codes[$i]['c_code']=strtoupper($array[$i]['code']);
            }
                //
            return $cntry_codes;

	}
	
	/***********************************************************************************
	fetch all provinces in the world from db
	************************************************************************/
    public function allStates(Request $request)
	{
        $code=strtolower($request->code);
        $states = DB::table("countries")->where('code',$code)->get();
        $array = json_decode(json_encode($states), true);
        $st_array=array();
            for($i=0;$i<count($array);$i++) {
            	# code...
            	$st_data= json_decode($array[$i]['state_data']);
               for($i=0;$i<count($st_data);$i++)
               {
               	 $ex_code=explode('-', $st_data[$i]->code);
                 $st_array[$i]['st_name']=$st_data[$i]->name;
                 $st_array[$i]['st_code']=$ex_code[1];
               }
            }
        return response()->json($st_array);
	}
	
	
	/***********************************************************************************
		fetch all selected provinces in the world from db
	************************************************************************/
    public function afStates(Request $request)
	{
	    $code=strtolower($request->code);
        $states = DB::table("countries")->where('code',$code)->get();
        $array = json_decode(json_encode($states), true);
        $st_array=array();
            for($i=0;$i<count($array);$i++) {
            	# code...
            	$st_data= json_decode($array[$i]['state_data']);
               for($i=0;$i<count($st_data);$i++)
               {
               	 $ex_code=explode('-', $st_data[$i]->code);
                 $st_array[$i]['st_name']=$st_data[$i]->name;
                 $st_array[$i]['st_code']=$ex_code[1];
               }
            }
        return response()->json($st_array);
	}


  	/***********************************************************************************
		update total price when shipping careeier selcted from front-end
	************************************************************************/
	public function totalPrice($price)
	{
		//print_r($price);  die;
	    $re_price=$price;
	    $split_val=explode('-', $re_price);
	    $data=array('price'=>$split_val[0],'cname'=>$split_val[1].'-'.$split_val[2].'-'.$split_val[3]);
	    $c_name=$split_val[1].'-'.$split_val[2].'-'.$split_val[3];
	    $check_token=Session::get('checkout_token');
	    $chec_tk_cnt=DB::table('active_carriers')->where('checkout_token',$check_token)->count();
			if($chec_tk_cnt>0):
		          $chec_id=DB::table('active_carriers')->where('checkout_token', $check_token)->update(
				    ['cname' => $c_name, 'rate' => $split_val[0],'status'=>'update']
				);
	        else:
        	$chec_id=DB::table('active_carriers')->insert(
		    ['checkout_token' => $check_token, 'rate' => $split_val[0],'status'=>'insert','cname'=>$c_name]
		        );
             endif;
		$data['shipping'] = @$split_val[3];
	    $enc_data=json_encode($data);
		//Session::put('price', $data['price']);
		//echo"<pre>"; print_r($enc_data);  die;
	    return $enc_data;
	}
	
	public function cartTokenSave(Request $request)
	{
		if(!empty($request->cardtoken) && !empty($request->utype) && !empty($request->metadata) && !empty($request->uemail))
		{
			// echo "<pre>";	print_r($request->all());	die;
			// $checkExit = DB::table('card_tokens')->where('user_email',$request->uemail)->count();
			// if($checkExit == 0)
			// {
				$insertData = DB::table('card_tokens')->insert(
				    [
				    	'card_token' => $request->cardtoken, 'user_type' => $request->utype,
				    	'meta_data' => json_encode($request->metadata), 'user_email' =>  $request->uemail
					]
				);
				return response()->json([
	            	'success' => true,
	            	'message' => 'Data added.'
        		], 200);
			// }else
			// {	
			// 	$updateData = DB::table('card_tokens')->where('user_email', $request->uemail)->update(
			// 	 	array(
			// 	 		'card_token' => $request->token, 'user_type' => $request->utype,
			// 	    	'meta_data' => $request->metadata, 'user_email' =>  $request->uemail
			// 	 	)
			// 	);  
			// 	return response()->json([
	  //           	'success' => true,
	  //           	'message' => 'Data updated.'
   //      		], 200);
			// }
		}else
		{
			return response()->json([
            	'success' => false,
            	'message' => 'Something went wrong.!'
        	], 200);
		}
	}

	public function refundPayment(Request $request){
		$url = $request->url;
		$expURL = explode('&shop=', $url);
		$againEXP = explode('com', $expURL[1]);
		$shop = $againEXP[0].'com';
		$app_id =  DB::table('shopify_url_credentials')->where('site_url', $shop)->value('id');
		$save_account = \App\MigrationAccount::where([['app_id', '=', $app_id],['active', '=', 1]])->value('id');
		$keyData =  DB::table('migration_accounts')->where('id', $save_account)->first();
		$uname = isset($keyData->uname) ? $keyData->uname:'';
		$orderID = $request->orderID;
		$email = $request->email;
		$checkRecord = DB::table('success_payment')->where('email', $email)->where('shopify_order_id', $orderID)->get();
		$orderRefund = DB::table('order_refund')->where('email', $email)->where('shopify_order_id', $orderID)->get();
		if(count($checkRecord)>0 && count($orderRefund)==0){
			$checkRecord = DB::table('success_payment')->where('email', $email)->where('shopify_order_id', $orderID)->first();
			$metaData = json_decode($checkRecord->meta_data, true);
			if($metaData['success'] == '1'){
				$transactionId = $metaData['transaction']['transactionId'];
				$api_query = 'https://app.kachyng.com/api/v3/payment';
				$rdata = array();
				$rdata['transactionId'] = $transactionId;
				$rdata['paymentRequestType'] = "void";
				$rdata['transaction_identifier'] = 'transaction_23424234';
				$request_data = $rdata;
				$controller = new MainController;
				$method = 'PUT';		
				$PaymentDataSendResult = $controller->api_kachyng($method, $api_query, json_encode($request_data), trim($uname));
				$PaymentDataSend = json_decode($PaymentDataSendResult, true);
				if($PaymentDataSend['success'] == '1'){
					$insertData = DB::table('order_refund')->insert(
					    [
					    	'email' => $email, 'meta_data' => $PaymentDataSendResult, 'shopify_order_id' => $orderID
						]
					);
					$data = array();
					$data['success'] = 'true'; 
					$data['message'] = 'success'; 
					return json_encode($data);
				}else{
					$output['success'] = 'false'; 
					$output['error'] = $PaymentDataSend; 
					return json_encode($output);
				}
			}else{
				$data = array();
				$data['success'] = 'false';
				$data['message'] = 'payment failed';
				return json_encode($data);
			}
		}else{
			$data = array();
			$data['success'] = 'false';
			$data['message'] = 'Record not found';
			return json_encode($data);
		}
	}


	public function testOrderC(Request $request)
	{	
		$response = array(
			'success' => 'false',
			'message' => '2652571074725',
		);
		return response()->json($response);
		$order_meta = $request->all();
		$email = $request->email;
		$shopifyURL = $request->admin_url;
		$shopify_cre = DB::table('shopify_url_credentials')->where('site_url', $shopifyURL)->first();
		$orderD = array();
		$orderD['email'] = $email;
		$orderD['financial_status'] = "pending";
		$orderD['send_receipt'] = "false";
		foreach($order_meta['metadata']['items'] as $order){
			$order_line['variant_id'] = $order['variant_id'];
			$order_line['quantity'] = $order['quantity'];
			$order_itmes['line_items'][] = $order_line;
		}
		$line_itme = array_merge($orderD,$order_itmes);
		$order_data['order'] = $line_itme;
		$encodeData = json_encode($order_data);

		$baseURl = 'https://'.$shopifyURL.'/admin/api/2020-01/orders.json';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$baseURl);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST,'POST');
		curl_setopt($ch, CURLOPT_POSTFIELDS,$encodeData);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
		$header = array(
		  'Content-Type: application/json',
		  'X-Shopify-Access-Token: '.$shopify_cre->token,
		  'Host: '.$shopifyURL
		 );
		curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
		$server_output = curl_exec($ch);
		$result = json_decode($server_output);

		if(array_key_exists("errors",$result)){
			$response = array(
				'success' => 'false',
				'message' => $result->errors,
			);
			return response()->json($response);
		}else{
			$response = array(
				'success' => 'true',
				'message' => 'success',
				'order_id' => $result->order->id,
			);
			return response()->json($response);
		}
	}
	
}
