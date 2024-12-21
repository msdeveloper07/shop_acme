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

class ShopifyCartController extends HomeController
{
  	protected $app;


  	public function createPendingOrder(Request $request)
	{
		$order_meta = $request->all();
		$email = $request->email;
		$shopifyURL = $request->admin_url;
		$shopify_cre = DB::table('shopify_url_credentials')->where('site_url', $shopifyURL)->first();
		$app_id =  DB::table('shopify_url_credentials')->where('site_url', $shopifyURL)->value('id');
		$save_account = \App\MigrationAccount::where([['app_id', '=', $app_id],['active', '=', 1]])->value('id');
		$keyData =  DB::table('migration_accounts')->where('id', $save_account)->first();
		$tax = isset($keyData->tax) ? $keyData->tax:'';
		$orderD = array();
		$orderD['email'] = $email;
		$orderD['financial_status'] = "pending";
		$orderD['send_receipt'] = "false";
		$orderD['taxes_included'] = "false";
		$orderD['total_tax'] = $tax;
		$shipping["amount"] = $request->shipping_rates;
		$shipping["currency_code"] = 'USD';
		$shipping_money["shop_money"] = $shipping;
		$shipping_money["presentment_money"] = $shipping;
		$orderD["total_shipping_price_set"] = $shipping_money;
		foreach($order_meta['metadata']['items'] as $order){
			$order_line['variant_id'] = $order['variant_id'];
			$order_line['quantity'] = $order['quantity'];
			$order_itmes['line_items'][] = $order_line;
		}
		$line_itme = array_merge($orderD,$order_itmes);
		$order_data['order'] = $line_itme;
		$encodeData = json_encode($order_data);

		// echo"<pre>"; print_r($order_data); die;

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

		if(array_key_exists("errors",$result))
		{
			$response = array(
				'success' => 'false',
				'message' => $result->errors,
			);
			return response()->json($response);
		}
		else
		{
			DB::table('create_order_api')
			->insert([
				'order_id' => $result->order->id, 
				'meta_data' => json_encode($result), 
				'shop' => $shopifyURL,
				'user_email' => @$email,
				'user_id' => @$request->user_id,
				'country' => $request->country,
				'state' => $request->state,
				'zip' => $request->zip,
				'province_code' => $request->pcode,
				'shipping_rates' => $request->shipping_rates,
				'shipping_method' => $request->shipping_method,
				'currency' => $request->currency,
				'user_type' => $request->user_type
			]);
			$response = array(
				'success' => 'true',
				'message' => 'success',
				'order_id' => $result->order->id,
			);
			return response()->json($response);
		}
	}

	public function updatePendingOrder(Request $request)
	{
		// echo"<pre>"; print_r($request->all());	die;
		$order_id = $request->order_id;
		$affected = DB::table('create_order_api')->where('order_id', $order_id)->get();
		if(count($affected)>=1)
		{
			$affected = DB::table('create_order_api')->where('order_id', $order_id)->update(
			['shipping_rates' => $request->shipping, 'shipping_method' => $request->ship_method, 'shipping_days' => $request->ship_day, 'total_price' => $request->total_price]
			);
			$response = array(
				'success' => 'true',
				'message' => 'Data Updated..!'
			);
			return response()->json($response);
		}
		else
		{
			$response = array(
				'success' => 'false',
				'message' => 'No data found..!'
			);
			return response()->json($response);
		}
	}	

	public function kachyngshipping($shop)
	{
		$shipping_package = DB::table('store_carrier_package')->where('shop', $shop)->get();
		// $url = "https://app.kachyng.com/api/v3/carriers";
		// $username = "apiKey";
		// $password = "6epqv6XgTAD0ciFugCfq6fS6hJmprDIg";
		// $ch = curl_init();
		// curl_setopt($ch, CURLOPT_URL,$url);
		// curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		// curl_setopt($ch, CURLOPT_POSTFIELDS, '');
		// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// $header = array(
		// 	'authorization: Basic '.base64_encode("apiKey:6epqv6XgTAD0ciFugCfq6fS6hJmprDIg")
		// );
		// curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		// $server_output = curl_exec ($ch);
		// $result = json_decode($server_output);
		return $shipping_package;	
	}

	public function kachyngshippingRates($orderDB)
	{
		echo""; print_r($orderDB);  die;
		$ship_api["carrier_ids"] = array("se-307468");
		$ship_api["validate_address"] = "validate_only";
		$ship_api["from_name"] = "";
		$ship_api["from_phone"] = "";
		$ship_api["from_address_line1"] = "";
		$ship_api["from_address_residential_indicator"] = "no";
		$ship_api["from_country_code"] = "US";
		$ship_api["from_postal_code"] = "78756";
		$ship_api["from_city_locality"] = "";
		$ship_api["from_state_province"] = "";
		$ship_api["to_name"] = "";
		$ship_api["to_phone"] = "";
		$ship_api["to_address_line1"] = "";
		$ship_api["to_address_residential_indicator"] = "no";
		$ship_api["to_country_code"] = "US";
		$ship_api["to_postal_code"] = $orderDB->zip;
		$ship_api["to_city_locality"] = "";
		$ship_api["to_state_province"] = "";	
		$ship_api["weight"]["value"] = "17";	
		$ship_api["weight"]["unit"] = "pound";	
		$ship_api["dimensions"]["length"] = "36";	
		$ship_api["dimensions"]["width"] = "12";	
		$ship_api["dimensions"]["height"] = "24";	
		$ship_api["dimensions"]["unit"] = "inch";	
		$ship_api["service_codes"] = array();	
		$ship_api["package_types"] = array();	
		echo"<pre>"; print_r(json_encode($ship_api));
	}

  	// default function calls before page load checkout
  	public function Checkout($id)
  	{
  		$order_id = $id;
  		$orderDB = DB::table('create_order_api')->where('order_id', $order_id)->first();
  		$shop = $orderDB->shop;
		$access_token = DB::table('shopify_url_credentials')->where('site_url', $shop)->value('token');
		$wallet_address = DB::table('shopify_url_credentials')->where('site_url', $shop)->value('wallet_address');
		$app_id =  DB::table('shopify_url_credentials')->where('site_url', $shop)->value('id');
	    $save_account = \App\MigrationAccount::where([['app_id', '=', $app_id],['active', '=', 1]])->value('id');
	    $checkoutType = \App\MigrationAccount::where([['app_id', '=', $app_id],['active', '=', 1]])->value('checkout_type');
		$applicaton_id =  \App\MerchantData::where([['merchant_account_id', '=', $save_account],['merchant_key', '=', 'applicaton_id']])->value('merchant_data');
		$api_key =  \App\MerchantData::where([['merchant_account_id', '=', $save_account],['merchant_key', '=', 'apikey']])->value('merchant_data');
		$keyData =  DB::table('migration_accounts')->where('id', $save_account)->first();
	    $uname = isset($keyData->uname) ? $keyData->uname:'';
	    $tax = isset($keyData->tax) ? $keyData->tax:'';

		$this->foo = Shopify::retrieve($shop, $access_token);
  		$order = $this->foo->get('orders/'.$order_id);
  		if(!empty($order['order'])){
			foreach($order['order']['line_items'] as $line_item){
				$productID_ = $line_item['product_id'];
				$variantID = $line_item['variant_id'];
				$array['no'] = '1';
				$array['title'] = $line_item['title'];	
				$array['product_id'] = $productID_;
				$array['varient_id'] = $variantID;
				$array['product_price'] = $line_item['price'];
				$array['productTitle'] = $line_item['title'];
				$array['productDES'] = $line_item['name'];
	            $array['quantity'] = $line_item['quantity'];
	            $array['price'] = $order['order']['total_price'];
				$array['shipping_rates'] = $orderDB->shipping_rates;
				$array['h_country'] = $orderDB->country;
				$array['h_state'] = $orderDB->state;
				$array['h_zipcode'] = $orderDB->zip;
				$array['h_shipping_method'] = $orderDB->shipping_method;
				$custom = $this->foo->get('products/'.$productID_, ['cubrid_field_seek(result)'=>'images']);
	            foreach ($custom['product']['images'] as $key_1 => $value_1) {
	                $array['image'] = $value_1['src'];
	                break;
	            }
	            $pId['product_id'][] = $productID_;
	            $cart_data[] = $array;
			}
			$total_price = $order['order']['total_price'];
			$products['total_price'] = $total_price;
			$products['product'][] = $array;
			$products['currency'] = $orderDB->currency;
			$products['customer_id'] = @$orderDB->user_id;
			$total_quantity = $array['quantity'];	
  		}
		$raw = array();
		$raw['shop'] = $shop;
		$raw['quantity'] = $array['quantity'];
		$raw['total_price'] = $total_price;
		$raw['user_type'] = $orderDB->user_type;
		$raw['customer_id'] = @$orderDB->user_id;
		$raw['h_country'] = $orderDB->country;
		$raw['h_state'] = $orderDB->state;
		$raw['h_zipcode'] = $orderDB->zip;
		$raw['shipping_rates'] = $orderDB->shipping_rates;
		$raw['h_shipping_method'] = $orderDB->shipping_method;
		$raw['currency_symbol'] = $orderDB->currency;
		$raw['product_id'] = $pId['product_id'];
		$raw['updates'] = '';
		$raw['product_id'] = $pId['product_id'];
		$raw['address'] = array("country"=>$orderDB->country,'province'=>$orderDB->state,'zip'=>$orderDB->zip);
		$raw['radio-shipping'] = $orderDB->shipping_rates;
		$raw['note'] = '';
		$raw['checkout'] = 'Check out';
		$raw['tax'] = $tax;
		// echo"<pre>"; print_r(json_encode($raw));	die;
		$my = $this->foo->get('shop');
		if($orderDB->user_id){
			$get_customer_data = $customer = $this->foo->get('customers/'.$orderDB->user_id);
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
			$customer_id= $orderDB->user_id?$orderDB->user_id:'';
			$method = "POST";
			$api_query = "accounts/sessions/create";
			$controller = new MainController;
			$result = $controller->api_kachyng($method, $api_query, json_encode($user_data), $uname);
			$sessionToken = json_decode($result, true);
			//echo"<pre>"; print_r($sessionToken);	die;
			if(is_array($sessionToken['user']['fundExternals'])){
				foreach ($sessionToken['user']['fundExternals'] as $key => $value) {
					$card['fundExternalId'] = $value['fundExternalId'];
					$card['cardDisplay'] = $value['cardDisplay'];
					$card['cardRef'] = $value['cardRef'];
					$card['nickName'] = $value['nickName'];
					$card['cardType'] = $value['cardType'];
					$card['expiry'] = $value['expiryMonth'].'/'.$value['expiryYear'];
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
		$kachyng_shippping = $this->kachyngshipping($shop);
		//$kachyng_shippping = $this->kachyngshippingRates($orderDB);
		$raw['user_email'] = $customer_data['customer_email'];
        //echo"<pre>"; print_r($raw); die;
        $getCarrierId = DB::table('store_carrier_package')->where('shop',$shop)->first();               
		return View::make('pages.custom_checkout_details')->with(array(
			'shop' => $shop,
			'store_name' => $my['shop']['name'],
			'wallet_address' => $wallet_address,
			'currency_symbol' => $orderDB->currency,
			'order_id' => $order_id,
			'total_price'=> number_format($total_price, 2),
			'currency'=>$orderDB->currency,
			//'titles'=>$products['product']['titles'],
			'customer_data'=>$customer_data,
			'cart_datas' => $cart_data,
			'total_quantity' => @$total_quantity,
			'raw' => json_encode($raw),
			'user'=>$user,
			'customer_email'=>(isset($customer_email)?$customer_email:''),
			'customer_id'=>(isset($customer_id)?$customer_id:''),
			'sessionToken'=>(isset($sessionToken)?$sessionToken:''),
			'saved_cards'=>(isset($saved_cards)?$saved_cards:''),
			'country'=>$countries,
			'shipping_rates' => '',
			'kachyng_shippping' => $kachyng_shippping,
			'user_type' => $orderDB->user_type,
			'checkout_type' => $checkoutType,
			'province_code' => $orderDB->province_code,
			'carrierId' => $getCarrierId->carrier_id,
			'tax' => $tax,
		));
	}

	// function calls on clicking payment button
	public function CardSubmit1(Request $request)
	{	
		parse_str($request->payment_form, $order_data);
		$order_id = $request->order_id;
		$shop = $request->shop;
		$raw_data = $order_data;
		$getSHOP = json_decode($raw_data['raw_data'], true);
		$raw_data['shop'] = $getSHOP['shop'];
		$shop2 = str_replace('https://', '', $shop);
		$access_token =  DB::table('shopify_url_credentials')->where('site_url', $shop)->value('token');
		//$shop2 =  DB::table('shopify_url_credentials')->where('token', $access_token)->value('admin_url');
		if(!empty($shop2)){
			if (strpos($shop2,'https://') === false){
				$admin_url = 'https://'.$shop2;
			}
		}

		// echo"<pre>";  print_r($order_data);	echo"<br>";
		// echo"<pre>";  print_r($raw_data);	die;

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
		//echo"<pre>";  print_r($pro_dec);	die;
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
		$options['order']['customer']['email'] = isset($order_data['customer_email'])?$pro_dec['user_email']:'';
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
		$options['order']['email'] = isset($order_data['customer_email'])?$pro_dec['user_email']:'';
		$options['order']['financial_status'] = 'paid';
		$order['customer']['email'] = isset($order_data['customer_email'])?$pro_dec['user_email']:'';
		$order['customer']['first_name'] = $order_data['customer_first_name'];
		$order['customer']['last_name'] = $order_data['customer_last_name'];
		$order['customer']['authProvider'] = 'facebook';
		$order['customer']['authProviderId'] = $fbapikey;
		$order['customer']['mobile'] = $order_data['customer_phone'];
		
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
  		if(!empty($order_data['customer_email']))
  		{
  			$email_user = $order_data['customer_email'];
  		}else{
  			$email_user = isset($order_data['customer_email'])?$pro_dec['user_email']:'';
  		}
  		$tokenSD = DB::table('card_tokens')->where('user_email', $email_user)->get()->last();
		$raw_dataa = json_decode($raw_data['raw_data'], true);
		$currenCYY = $raw_dataa['total_price'] + @$add_raw['shipping_rates'];
        $currency = 'USD';
        $total_price = trim($currenCYY);
        // echo"<pre>";  print_r($order_data);  die;
		if(array_key_exists("customer_id",$raw_dataa))
		{
		   	if(array_key_exists("Saved_Card",$order_data))
		   	{
				$rdata = array();
				$rdata['cardToken'] = $order_data['Saved_Card'];
				$rdata['amount'] = $total_price;
				$rdata['currencyCode'] = 'USD';
				$rdata['transaction_identifier'] = 'transaction_56789';
				$rdata['paymentRequestType'] = 'authorize';
				$rdata['customerId'] = @$raw_dataa['customer_id'];
				$rdata['email'] = $order_data['customer_email'];
				$rdata['saveCard'] = 'Y';
				$request_data = $rdata;
				$api_query='https://app.kachyng.com/api/v3/form/payment';
			}
			else
			{
				$rdata = array();
				$rdata['cardToken'] = isset($order_data['payment_method_token']) ? $tokenSD->card_token:'';
				$rdata['amount'] = $total_price;
				$rdata['currencyCode'] = 'USD';
				$rdata['transaction_identifier'] = 'transaction_56789';
				$rdata['paymentRequestType'] = 'authorize';
				$rdata['customerId'] = @$raw_dataa['customer_id'];
				$rdata['email'] = $order_data['customer_email'];
				$rdata['saveCard'] = 'Y';
				$request_data = $rdata;
				$api_query='https://app.kachyng.com/api/v3/form/payment';
			}
		}
		else
		{
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
		// echo"<pre>"; print_r($order_data);	die;
		$controller = new MainController;
		$method = 'POST';		
		$PaymentDataSendResult = $controller->api_kachyng($method, $api_query, json_encode($request_data), trim($uname));
		$PaymentDataSend = json_decode($PaymentDataSendResult, true);
		// echo"<pre>"; print_r(json_encode($request_data));  echo"<br>";
		// echo"<pre>"; print_r($api_query);  die;
		// echo"<pre>"; print_r($raw_data);  die;
		if($PaymentDataSend['success'] == '1')
		{
			$nameFull = $raw_data['customer_first_name'].' '.$raw_data['customer_last_name'];
			$order_dataa = array();
			$order_dataa['id'] = $order_id;
			$order_dataa['email'] = $raw_data['customer_email'];
			$order_dataa['total_price'] = $total_price;
			$order_dataa['total_tax'] = "2.00";
			$order_dataa['subtotal_price'] = $total_price;
			$order_dataa['financial_status'] = "paid";
			$order_dataa['fulfillment_status'] = "fulfilled";
			
			$raw_decoded = json_decode($raw_data['raw_data'],true);

			$shipping_add['first_name'] = $raw_data['customer_first_name'];
			$shipping_add['address1'] = $raw_data['cus_add_address1'];
			$shipping_add['phone'] = $raw_data['customer_phone'];
			$shipping_add['city'] = $raw_data['cus_add_city'];
			$shipping_add['zip'] = isset($raw_decoded['h_zipcode'])?$pro_dec['h_zipcode']:'';
			$shipping_add['province'] = isset($raw_decoded['h_state'])?$pro_dec['h_state']:'';
			$shipping_add['country'] = $getSHOP['h_country'];
			$shipping_add['last_name'] = $raw_data['customer_last_name'];
			$shipping_add['address2'] = $raw_data['cus_add_address2'];
			$shipping_add['company'] = "";
			if(!empty($raw_data['full_name'])){
				$shipping_add['name'] = $raw_data['full_name'];
			}else{
				$shipping_add['name'] = $nameFull;
			}
			$shipping_add['province_code'] = isset($raw_decoded['h_state'])?$pro_dec['h_state']:'';
			$order_dataa['shipping_address'] = $shipping_add;
			
			if(!empty($raw_data['green_name']) && !empty($raw_data['green_add_address1']))
			{
				$billing_address['first_name'] = $raw_data['green_name'];
				$billing_address['address1'] = $raw_data['green_add_address1'];
				$billing_address['city'] = $raw_data['green_city'];
				$billing_address['zip'] = isset($raw_decoded['h_zipcode'])?$pro_dec['h_zipcode']:'';
				$billing_address['province'] = isset($raw_decoded['h_state'])?$pro_dec['h_state']:'';
				$billing_address['country'] = $getSHOP['h_country'];
				$billing_address['last_name'] = $raw_data['green_last'];
				$billing_address['address2'] = $raw_data['green_add_address2'];
				$billing_address['company'] = "";
				$billing_address['name'] = $raw_data['full_name'];
				if(!empty($raw_data['full_name'])){
					$billing_address['name'] = $raw_data['full_name'];
				}else{
					$billing_address['name'] = $nameFull;
				}
				$billing_address['province_code'] = isset($raw_data['cus_add_province'])?$pro_dec['h_state']:'';
				$order_dataa['billing_address'] = $billing_address;
				$order_dataa['note'] = json_encode($billing_address);
			}


			$order_dataa['processing_method'] = 'direct';
			$shipping["amount"] = $raw_decoded['shipping_rates'];
			$shipping["currency_code"] = 'USD';
			$shipping_money["shop_money"] = $shipping;
			$shipping_money["presentment_money"] = $shipping;
			$order_dataa["total_shipping_price_set"] = $shipping_money;
			
			if(!empty($raw_dataa['customer_id']))
			{
				$customer["id"] = @$raw_dataa['customer_id'];
			}
			$customer["email"] = $raw_data['customer_email'];
			$customer["accepts_marketing"] = 'true';
			$customer["first_name"] = $raw_data['customer_first_name'];
			$customer["last_name"] = $raw_data['customer_last_name'];
			$customer["phone"] = $raw_data['customer_phone'];
			if(!empty($raw_dataa['customer_id']))
			{
				$customer["default_address"]["id"] = @$raw_dataa['customer_id'];
			}
			$customer["default_address"]["customer_id"] = @$raw_dataa['customer_id'];
			$customer["default_address"]["first_name"] = $raw_data['customer_first_name'];
			$customer["default_address"]["last_name"] = $raw_data['customer_last_name'];
			$customer["default_address"]["company"] = '';
			$customer["default_address"]["address1"] = $raw_data['cus_add_address1'];
			$customer["default_address"]["address2"] = $raw_data['cus_add_address2'];
			$customer["default_address"]["city"] = $raw_data['cus_add_city'];
			$customer["default_address"]["province"] = isset($raw_decoded['h_state'])?$pro_dec['h_state']:'';
			$customer["default_address"]["country"] = $getSHOP['h_country'];
			$customer["default_address"]["zip"] = isset($raw_decoded['h_zipcode'])?$pro_dec['h_zipcode']:'';
			$customer["default_address"]["phone"] = $raw_data['customer_phone'];
			$customer["default_address"]["name"] = $raw_data['full_name'];
			if(!empty($raw_data['full_name'])){
				$customer["default_address"]["name"] = $raw_data['full_name'];
			}else{
				$customer["default_address"]["name"] = $nameFull;
			}
			$customer["default_address"]["country_name"] = $getSHOP['h_country'];
			$customer["default_address"]["default"] = 'true';
			$order_dataa['customer'] = $customer;
			$json_decode = json_decode($PaymentDataSendResult, true);
			$additional_note= array();
			$additional_note[0]['name'] = 'Transaction Id';
			$additional_note[0]['value'] = $json_decode['transaction']['transactionId'];
			$additional_note[1]['name'] = 'Fund Account Id';
			$additional_note[1]['value'] = $json_decode['transaction']['fundAccountId'];
			$additional_note[2]['name'] = 'Status';
			$additional_note[2]['value'] = $json_decode['transaction']['status'];
			$additional_note[3]['name'] = 'cardScheme';
			$additional_note[3]['value'] = $json_decode['transaction']['cardScheme'];
			$additional_note[4]['name'] = 'Card Holder Name';
			$additional_note[4]['value'] = $json_decode['transaction']['cardHolderName'];
			$order_dataa['note_attributes'] = $additional_note;
			$order_update['order'] = $order_dataa;
			$encodeOrder = json_encode($order_update);
			$access_token = DB::table('shopify_url_credentials')->where('site_url', $raw_dataa['shop'])->value('token');
			$baseURl = "https://".$raw_dataa['shop']."/admin/api/2020-07/orders/".$order_id.".json";
			
			// echo"<pre>"; print_r($raw_data); echo"<br>";
			// echo"<pre>"; print_r($raw_decoded); echo"<br>";
			// echo"<pre>"; print_r($order_update); die;
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$baseURl);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST,'PUT');
			curl_setopt($ch, CURLOPT_POSTFIELDS,$encodeOrder);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
			$header = array(
			  'Content-Type: application/json',
			  'X-Shopify-Access-Token: '.$access_token,
			  'Host: '.$raw_dataa['shop']
			 );
			curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
			$server_output = curl_exec($ch);
			$result = json_decode($server_output);
			
			// echo"<pre>"; print_r($raw_data); echo"<br>";
			// echo"<pre>"; print_r($result); echo"<br>";
			// echo"<pre>"; print_r($baseURl); echo"<br>";
			// echo"<pre>"; print_r($header); echo"<br>";
			// echo"<pre>"; print_r($encodeOrder); echo"<br>";  die;

			$addresses["first_name"] = $raw_data['customer_first_name'];
			$addresses["last_name"] = $raw_data['customer_last_name'];
			$addresses["company"] = '';
			$addresses["address1"] = $raw_data['cus_add_address1'];
			$addresses["address2"] = $raw_data['cus_add_address2'];
			$addresses["city"] = $raw_data['cus_add_city'];
			$addresses["province"] = isset($raw_decoded['h_state'])?$pro_dec['h_state']:'';
			$addresses["country"] = $getSHOP['h_country'];
			$addresses["zip"] = isset($raw_decoded['h_zipcode'])?$pro_dec['h_zipcode']:''; 
			$addresses["phone"] = $raw_data['customer_phone'];
			$addresses["name"] = $raw_data['full_name'];
			$addresses["country_name"] = $getSHOP['h_country'];
			$add_['address'] = $addresses;
			$json_encode_add = json_encode($add_);
			$addresses_url = "https://".$raw_dataa['shop']."/admin/api/2020-07/customers/".$raw_dataa['customer_id']."/addresses.json";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$addresses_url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST,'POST');
			curl_setopt($ch, CURLOPT_POSTFIELDS, $json_encode_add);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
			$header = array(
			  'Content-Type: application/json',
			  'X-Shopify-Access-Token: '.$access_token,
			  'Host: '.$raw_dataa['shop']
			 );
			curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
			$server_output = curl_exec($ch);
			$result_address = json_decode($server_output);
			// echo"<pre>";  print_r($addresses_url);  echo"<br>";
			// echo"<pre>";  print_r($header);  echo"<br>";
			// echo"<pre>";  print_r($json_encode_add);  echo"<br>";
			// echo"<pre>";  print_r($result_address->customer_address->);  echo"<br>";
			$customer["id"] = @$raw_dataa['customer_id'];
			$customer["email"] = $raw_data['customer_email'];
			$customer["accepts_marketing"] = 'true';
			$customer["first_name"] = $raw_data['customer_first_name'];
			$customer["last_name"] = $raw_data['customer_last_name'];
			$customer["phone"] = $raw_data['customer_phone'];
			// $addresses["id"] = '';
			// $addresses["customer_id"] = @$raw_dataa['customer_id'];
			// $addresses["first_name"] = $raw_data['customer_first_name'];
			// $addresses["last_name"] = $raw_data['customer_last_name'];
			// $addresses["company"] = '';
			// $addresses["address1"] = $raw_data['cus_add_address1'];
			// $addresses["address2"] = $raw_data['cus_add_address2'];
			// $addresses["city"] = $raw_data['cus_add_city'];
			// $addresses["province"] = isset($raw_decoded['h_state'])?$pro_dec['h_state']:'';
			// $addresses["country"] = $getSHOP['h_country'];
			// $addresses["zip"] = isset($raw_decoded['h_zipcode'])?$pro_dec['h_zipcode']:''; 
			// $addresses["phone"] = $raw_data['customer_phone'];
			// $addresses["name"] = $raw_data['full_name'];
			// $addresses["country_name"] = $getSHOP['h_country'];
			// $addresses["default"] = 'true';
			// $customer["default_address"] = $addresses;
			$customer["addresses"] = array($addresses);
			$customer_dataa['customer'] = $customer;
			$encodeCustomer = json_encode($customer_dataa);
			$custer_baseURl = "https://".$raw_dataa['shop']."/admin/api/2020-07/customers/".$raw_dataa['customer_id'].".json";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$custer_baseURl);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST,'PUT');
			curl_setopt($ch, CURLOPT_POSTFIELDS,$encodeCustomer);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
			$header = array(
			  'Content-Type: application/json',
			  'X-Shopify-Access-Token: '.$access_token,
			  'Host: '.$raw_dataa['shop']
			 );
			curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
			$server_output = curl_exec($ch);
			$result = json_decode($server_output);

			// echo"<pre>"; print_r($header); echo"<br>";
			// echo"<pre>"; print_r($custer_baseURl); echo"<br>";
			// echo"<pre>"; print_r($encodeCustomer); echo"<br>";
			// echo"<pre>"; print_r($result); echo"<br>";  die;

	        $ch = curl_init();
	        curl_setopt($ch, CURLOPT_URL,$baseURl);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	        $header = array(
	          'X-Shopify-Access-Token: '.$access_token,
	          'Content-Type: application/json',
	          'Host: '.$raw_dataa['shop']);
	        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	        $server_output = curl_exec($ch);
	        $ord_result_array=json_decode($server_output); 
	        $ord_result=$server_output; 
	        $orders = json_decode($ord_result,true);
			$insertData = DB::table('success_payment')->insert(
			    [
			    	'shopify_user_id' => @$raw_dataa['customer_id'], 'email' => $order_data['customer_email'], 'meta_data' => $PaymentDataSendResult, 'shopify_order_id' => $order_id
				]
			);
			$output['redirect_url'] = $orders['order']['order_status_url'];
			$output['success'] = 'true'; 
			return json_encode($output);			
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
		// $orderD['send_receipt'] = "false";
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

	public function shippingRateEstimate(Request $request)
	{
		$shop = $request->s_op;
		$access_token = DB::table('shopify_url_credentials')->where('site_url', $shop)->value('token');
		$this->foo = Shopify::retrieve($shop, $access_token);
		$productsID = json_decode($request->productID, true);
		foreach($productsID as $pID_)
		{
			$variants = $this->foo->get('products/'.$pID_, ['fields'=>'variants']);
			$check_LB = $variants['product']['variants'][0]['weight_unit'];
			if($check_LB == "lb")
			{
				$weight[] = $variants['product']['variants'][0]['weight'] * 0.453592;
			}
			else
			{
				$weight[] = $variants['product']['variants'][0]['weight'];
			} 
		}
		$total_weight = array_sum($weight);
		$carrier_id = trim($request->carrier_id);
		$state = trim($request->state);
		$country = trim($request->country);
		$zip_code = trim($request->zip_code);
		$country_code = trim($request->countryID);
		$city = trim($request->city);
		$ship_api["carrier_ids"] = array($carrier_id);
		$ship_api["validate_address"] = "validate_only";
		// $ship_api["from_name"] = "";
		// $ship_api["from_phone"] = "";
		// $ship_api["from_address_line1"] = "";
		// $ship_api["from_address_residential_indicator"] = "no";
		$ship_api["from_country_code"] = "US";
		$ship_api["from_postal_code"] = "78756";
		// $ship_api["from_city_locality"] = "";
		// $ship_api["from_state_province"] = "";
		// $ship_api["to_name"] = "";
		// $ship_api["to_phone"] = "";
		// $ship_api["to_address_line1"] = "";
		// $ship_api["to_address_residential_indicator"] = "no";
		$ship_api["to_country_code"] = $country_code;
		$ship_api["to_postal_code"] = $zip_code;
		// $ship_api["to_city_locality"] = $city;
		// $ship_api["to_state_province"] = $state;	
		$ship_api["weight"]["value"] = $total_weight;	
		$ship_api["weight"]["unit"] = "kilogram";	
		// $ship_api["dimensions"]["length"] = "36";	
		// $ship_api["dimensions"]["width"] = "12";	
		// $ship_api["dimensions"]["height"] = "24";	
		// $ship_api["dimensions"]["unit"] = "inch";	
		// $ship_api["service_codes"] = array();	
		// $ship_api["package_types"] = array();
		$encoded_data = json_encode($ship_api);
		$url = "https://app.kachyng.com/api/v3/rate/estimate";
		$uname = "apiKey";
		$pwd = "6epqv6XgTAD0ciFugCfq6fS6hJmprDIg";
		$method = "POST";
		$header[] = 'authorization : Basic '.base64_encode('apiKey:'.$pwd); 
		$header[] = 'content-type : application/json'; 
		$header[] = 'cache-control: no-cache'; 
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => $method,
			CURLOPT_POSTFIELDS => $encoded_data,
			CURLOPT_HTTPHEADER => $header,
		));
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		$shipping_rate = json_decode($response, true);
		return $shipping_rate;
		//echo"<pre>"; print_r($shipping_rate);  die;
	}
	
}
