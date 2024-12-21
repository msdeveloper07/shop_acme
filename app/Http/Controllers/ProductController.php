<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Shopify;
use View;
use Input;
use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use App\MigrationAccount;
use App\MerchantData;
use App\Testdata;
use App\ProductUpload;
use App\Orders;
use App\AbundandCart;
use URL;
use Response;
use App\Http\Controllers\MainController;
use Mail;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class ProductController extends Controller
{
    public function postCarrier(Request $request){
        print_r($_POST);

        // $json_decode = $request->all();
        // $TLog = new Logger('Test');
        // $TLog->pushHandler(new StreamHandler(storage_path('logs/Test.log')), Logger::INFO);
        // $TLog->info('TLog', array($json_decode));



    //    $obj = file_get_contents('php://input');
       // $obj = json_decode($obj, true);

      //  echo"<pre>"; print_r($obj); die;

         $data_raw = file_get_contents('php://input');  
         print_r($data_raw)  ;exit;    
         $json_decode_1 = json_decode($data_raw, true);
         $json_decode = json_decode($json_decode_1, TRUE);
        $orderLog = new Logger('shipping');
         $orderLog->pushHandler(new StreamHandler(storage_path('logs/shipping.log')), Logger::INFO);
     return   $orderLog->info('shipping log', $json_decode);

        // echo"<pre>"; print_r($request->all());   die();
    }
    public function ProductCreate(Request $request){
		
        $data_raw = file_get_contents('php://input');        
        if (isset($_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'])) {
            $data =  json_encode($data_raw);
        }
		
        $shop = 'naveen-kachyng.myshopify.com';
        $access_token =  DB::table('shopify_url_credentials')->where('admin_url', $shop)->value('token');
        $this->foo = Shopify::retrieve($shop, $access_token);
        $app_id =  DB::table('shopify_url_credentials')->where('admin_url', $shop)->value('id');
        $save_account = \App\MigrationAccount::where([['app_id', '=', $app_id],['active', '=', 1]])->value('id');        
        $api_key =  \App\MerchantData::where([['merchant_account_id', '=', $save_account],['merchant_key', '=', 'apikey']])->value('merchant_data');

        $keyData =  DB::table('migration_accounts')->where('id', $save_account)->first();
        $uname = isset($keyData->uname) ? $keyData->uname:'';
        $psw = isset($keyData->psw) ? $keyData->psw:'';

		if(!empty($data)){
		 	$json_decode_1 = json_decode($data, true);
			$json_decode = json_decode($json_decode_1, TRUE);

            $ProductLog = new Logger('products');
            $ProductLog->pushHandler(new StreamHandler(storage_path('logs/products.log')), Logger::INFO);
            $ProductLog->info('ProductLog', $json_decode);


			$product_data['kaProductNumber'] = $json_decode['id'];        
			$product_data['name'] = $json_decode['title'];        
			$product_data['standardCost'] = $json_decode['variants']['0']['price'];
			// $product_data['description'] = $json_decode['body_html'];
			$product_data['Category'] = '';
			$product_data['Brand'] = '';
			//$product_data['isTaxable'] = $json_decode['variants']['0']['taxable'];
			$product_data['isTaxable'] = '';
			$product_data['isSale'] = '';
			$product_data['isShipping'] = '';
			$product_data['width'] = '';
			$product_data['Height'] = '';
			$product_data['Depth'] = '';
			$product_data['whlUnit'] = '';
			$product_data['Weight'] = '';
			$product_data['weightUnit'] = '';
			$product_data['properitaryUrl'] = '';
			$product_data['longDescription'] = $json_decode['body_html'];
			$product_data['imageURL'] = @$json_decode['image']['src'] ? @$json_decode['image']['src'] : 'https://cdn.shopify.com/s/files/1/0419/3248/8866/files/thumb.cms.jpg?v=1593579230';
			$product_data['additionalImages'] = array();
			$quantity = 0;
			$hasVariants = 0;
			if(is_array($json_decode['variants'])){
				foreach ($json_decode['variants'] as $value) {
					$quantity += $value['inventory_quantity'];
					$hasVariants++;
				}
			}
			$product_data['uPCEAN'] = '123456780912';
			$product_data['standardQuantity'] = $quantity;
			$product_data['hasVariants'] = 'true';
			if(is_array($json_decode['options'])){
				foreach ($json_decode['options'] as $options) {
					$var_fields['charName'] = $options['name'];
					foreach ($options['values'] as $key => $value) {
						$name['name'] = $value; 
					}
					$var_fields['characteristicValues'][] = $name;
					$characteristicNames[] = $var_fields;        
					//$product_data['characteristicNameValueRequest']['characteristicNames'] = $characteristicNames;
					$product_data['characteristicNameValueRequest'] = array();
				}
			}
		
		   // $product_data['characteristicNameValueRequest']['variantImages'] = array();
			$cart['cartItems'][] = $product_data; 		
			$encoded_data = json_encode($cart); 
			$method = "POST";
			$api_query = "buy/product/add";
			$controller = new MainController;
			$result = $controller->api_kachyng($method, $api_query, $encoded_data, $uname);

            $PLog = new Logger('pro');
            $PLog->pushHandler(new StreamHandler(storage_path('logs/pro.log')), Logger::INFO);
            $PLog->info('PLog', $cart);


			$response_api = json_decode($result, true);
		}	
		else
		{
			$response_api=array();
			$response_api['msg']='Something wrong...';	
			return $response_api;
		}
		
    }

    public function ProductDelete(Request $request, $shop){
        // dummy funtion        
    }

    public function ProductUpdate(Request $request, $shop){
		//$api_query = "/buy/product/".$shop."/update";
		//print_r($api_query);  die;
        $access_token =  DB::table('shopify_url_credentials')->where('admin_url', $shop)->value('token');
        $this->foo = Shopify::retrieve($shop, $access_token);

        $app_id =  DB::table('shopify_url_credentials')->where('admin_url', $shop)->value('id');
        $save_account = \App\MigrationAccount::where([['app_id', '=', $app_id],['active', '=', 1]])->value('id');
        $merchantdata =  \App\MerchantData::where([['merchant_account_id', '=', $save_account]])->pluck('merchant_data', 'merchant_key');
        $saved_data = json_decode($merchantdata,true);
        $pixel_id = isset($saved_data['pixel'])? $saved_data['pixel']:'';

		//echo"<pre>"; print_r($access_token);  die;
		
        $data_raw = file_get_contents('php://input');        
        if (isset($_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'])) {
            $data =  json_encode($data_raw);
        }
		
		if(!empty($data)){
        $app_id =  DB::table('shopify_url_credentials')->where('admin_url', $shop)->value('id');
        $save_account = \App\MigrationAccount::where([['app_id', '=', $app_id],['active', '=', 1]])->value('id');        
        $api_key =  \App\MerchantData::where([['merchant_account_id', '=', $save_account],['merchant_key', '=', 'apikey']])->value('merchant_data');
		
        $json_decode_1 = json_decode($data, true);
        $json_decode = json_decode($json_decode_1, TRUE);

	 	$product_data['kaProductNumber'] = $json_decode['id'];
        $product_data['name'] = $json_decode['title'];        
        $product_data['standardCost'] = $json_decode['variants']['0']['price'];
        $product_data['description'] =  $json_decode['body_html'];
        $product_data['Category'] = '';
        $product_data['Brand'] = '';
        $product_data['isTaxable'] = '';
        $product_data['isSale'] = '';
        $product_data['isShipping'] = '';
        $product_data['width'] = '';
        $product_data['Height'] = '';
        $product_data['Depth'] = '';
        $product_data['whlUnit'] = '';
        $product_data['Weight'] = '';
        $product_data['weightUnit'] = '';
        $product_data['properitaryUrl'] = '';
        $product_data['longDescription'] = $json_decode['body_html'];
       
	   /*  if(is_array($json_decode['images'])){
            foreach ($json_decode['images'] as $key => $value) {
                if($key == 0){
                    $product_data['imageURL'] = $value['src'];

                }else{
                    $product_data['additionalImages'] = $value['src'];
                }
            }        
        } */
		
		$product_data['imageURL'] = 'https://cdn.shopify.com/s/files/1/0121/6666/0153/products/Chrysanthemum_7bca169f-695b-4b9e-9c29-34996697ae3b.jpg?v=1530691426';
		$product_data['additionalImages'] = array();
        $quantity = 0;
        $hasVariants = 0;
        if(is_array($json_decode['variants'])){
            foreach ($json_decode['variants'] as $value) {
                $quantity += $value['inventory_quantity'];
                $hasVariants++;
            }
        }
        $product_data['uPCEAN'] = '123456780912';
        $product_data['standardQuantity'] = $quantity;
        $product_data['hasVariants'] = 'true';
        if(is_array($json_decode['options'])){
            foreach ($json_decode['options'] as $options) {
                $var_fields['charName'] = $options['name'];
                foreach ($options['values'] as $key => $value) {
                    $name['name'] = $value; 
                   // $var_fields['characteristicValues'][] = $name;
                }
				$var_fields['characteristicValues'][] = $name;
                $characteristicNames[] = $var_fields;        
               // $product_data['characteristicNameValueRequest']['characteristicNames'] = $characteristicNames;
			   $product_data['characteristicNameValueRequest'] = array();
            }
        }
		
        //$product_data['characteristicNameValueRequest']['variantImages'] = array();
        $cart['cartItems'][] = $product_data; 
        $encoded_data = json_encode($cart); 

        $method = "POST";
        $api_query = "buy/product/add";

        $controller = new MainController;
        $result = $controller->api_kachyng($method, $api_query, $encoded_data, $api_key);
        $response_api = json_decode($result, true);
		
		

        $button_value_send = array('metafield'=>array('namespace'=>'inventory','key'=>'buyurl','value'=>$response_api['productDetails'][0]['smartURL'],'value_type'=>'string'),); 


		//$json_decode['id'] = '1571604693082';
        $buyurl = $this->foo->create('products/'. $json_decode['id'] .'/metafields', $button_value_send);   
        $pixel_array = array('metafield'=>array('namespace'=>'inventory','key'=>'pixel','value'=>$pixel_id,'value_type'=>'string'),);        
        $data_query = [['method' => 'pixel', 'data' => json_encode($pixel_id)],];
		$result_data = \App\Testdata::insert($data_query); 
		//echo"<pre>";  print_r($result_data);  die;	
		
        
        $data_query = [['method' => 'pixel_id', 'data' => json_encode($pixel_array)],];
        $result_data = \App\Testdata::insert($data_query); 
        $buyurl = $this->foo->create('products/'. $json_decode['id'] .'/metafields', $pixel_array); 
		}	
		else
		{
			$response_api=array();
			$response_api['msg']='Something wrong...';	
			return $response_api;
		}
        
    }

    public function OrderCreate(Request $request){
        $data_raw = file_get_contents('php://input');       
        if (isset($_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'])) {
            $data =  json_encode($data_raw);
        }
        $shop = 'naveen-kachyng.myshopify.com';
		if($data){
        $json_decode_1 = json_decode($data, true);
        $json_decode = json_decode($json_decode_1, TRUE);  

        $OrderLog = new Logger('orders');
        $OrderLog->pushHandler(new StreamHandler(storage_path('logs/orders.log')), Logger::INFO);
        $OrderLog->info('OrderLog', $json_decode);

        $access_token =  DB::table('shopify_url_credentials')->where('admin_url', $shop)->value('token');
        $app_id =  DB::table('shopify_url_credentials')->where('admin_url', $shop)->value('id');
        $save_account = \App\MigrationAccount::where([['app_id', '=', $app_id],['active', '=', 1]])->value('id');
        $fbapikey =  \App\MerchantData::where([['merchant_account_id', '=', $save_account],['merchant_key', '=', 'fbapikey']])->value('merchant_data');
        $api_key =  \App\MerchantData::where([['merchant_account_id', '=', $save_account],['merchant_key', '=', 'apikey']])->value('merchant_data');
        //print_r($app_id);  die;

        $keyData =  DB::table('migration_accounts')->where('id', $save_account)->first();
        $uname = isset($keyData->uname) ? $keyData->uname:'';
        $psw = isset($keyData->psw) ? $keyData->psw:'';


        $this->foo = Shopify::retrieve($shop, $access_token);

        $order['customer']['email'] = $json_decode['customer']['email'];
        $order['customer']['first_name'] = $json_decode['customer']['first_name'];
        $order['customer']['last_name'] = $json_decode['customer']['last_name'];
        $order['customer']['authProvider'] = 'facebook';
        $order['customer']['authProviderId'] = $fbapikey;
        $order['customer']['mobile'] = $json_decode['customer']['phone'];
		
		
		$order['shippingAddress']['addressName'] = @$json_decode['shipping_address']['address1'];
        $order['shippingAddress']['addressLine1'] = @$json_decode['shipping_address']['address1'];
        $order['shippingAddress']['addressLine2'] = @$json_decode['shipping_address']['address2'];
        $order['shippingAddress']['cityName'] = @$json_decode['shipping_address']['city'];
        $order['shippingAddress']['postalCode'] = @$json_decode['shipping_address']['zip'];
        $order['shippingAddress']['regionName'] = @$json_decode['shipping_address']['country'];
        $order['shippingAddress']['countryCode'] = @$json_decode['shipping_address']['country_code'];
        
        //https://ad-smart.myshopify.com/admin/products/1175612522553
        foreach ($json_decode['line_items'] as $key => $value) {
            $product['number'] = $value['product_id'];
            $product['name'] = $value['title'];
            $product['price'] = (int) $value['price'];
            $product['upc'] = $value['id'];
            $product['active'] = true;
            $product['quantity'] = (string)$value['quantity'];                
            $custom = $this->foo->get('products/'.$value['product_id'], ['fields'=>'body_html,images']);
            // $custom = $this->foo->get('products/1175612522553', ['fields'=>'body_html,images']);
            
            $product['description'] = $custom['product']['body_html'];
            $product['longDescription'] = $custom['product']['body_html'];

            foreach ($custom['product']['images'] as $key_1 => $value_1) {
                $image['is_deafult'] = "true";
                $image['url'] = $value_1['src'];
                $product['images'][] = $image;
                break;
            }
            
            $product['package_dimensions']['height'] = 0;
            $product['package_dimensions']['length'] = 0;
            $product['package_dimensions']['width'] = 0;
            $product['package_dimensions']['length_unit'] = 0;
            $product['package_dimensions']['weight'] = 0;
            $product['package_dimensions']['weight_unit'] = 0;

            $product['shippable'] = true;
            $product['google_category'] = 0;
            $product['taxable'] = $value['taxable'];
            $product['on_sale'] = true;
            $product['sale_price'] = (int) $value['price'];
            $order['products'][] = $product;
        }
        $encoded_data = json_encode($order);
        $data_query = [['method' => 'order create', 'data' => $encoded_data],];
        $result_data = \App\Testdata::insert($data_query);

        $method = "POST";
        $api_query = "buy/order";
        $controller = new MainController;
        $result = $controller->api_kachyng($method, $api_query, $encoded_data, $uname);

        $OLog = new Logger('odr');
        $OLog->pushHandler(new StreamHandler(storage_path('logs/odr.log')), Logger::INFO);
        $OLog->info('OLog', $order);


        $response_api = json_decode($result, true);
        
        $data_query = [['method' => 'Order', 'data' => $result],];
        $result_data = \App\Testdata::insert($data_query);
        
        $data_query = [['buy_url' => $response_api['buy_url'], 'orderNumber' => $response_api['orderNumber']],];
        $result_data = \App\Orders::insert($data_query);
		}	
		else
		{
			$response_api=array();
			$response_api['msg']='Something wrong...';	
			return $response_api;
		}
    }

    public function OrderUpdate(Request $request, $shop){
        $data_raw = file_get_contents('php://input');        
        if (isset($_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'])) {
            $data =  json_encode($data_raw);
        }
		if(!empty($data)){
        $data_query = [['method' => 'Order Updated', 'data' => $data],];
        $result_data = \App\Testdata::insert($data_query);
		}	
		else
		{
			$response_api=array();
			$response_api['msg']='Something wrong...';	
			return $response_api;
		}
    }

    public function AbundantCart(Request $request){
	
		$value = $request->raw_data;
        $customer_email = $request->customer_email;
        $exist_abundantcart = \App\AbundandCart::where('cart_user', '=', $customer_email)->value('id');
		//print_r($exist_abundantcart);  die;
        
        $raw_data = json_decode($value, true);
        $shop = str_replace('https://', '', $raw_data['shop']);
		//$shop = 'feizin.com';
        $access_token =  DB::table('shopify_url_credentials')->where('site_url', $shop)->value('token');
        $app_id =  DB::table('shopify_url_credentials')->where('site_url', $shop)->value('id');
		
        $save_account = \App\MigrationAccount::where([['app_id', '=', $app_id],['active', '=', 1]])->value('id');
        $fbapikey =  \App\MerchantData::where([['merchant_account_id', '=', $save_account],['merchant_key', '=', 'fbapikey']])->value('merchant_data');
        $api_key =  \App\MerchantData::where([['merchant_account_id', '=', $save_account],['merchant_key', '=', 'apikey']])->value('merchant_data');
		
        $this->foo = Shopify::retrieve($shop, $access_token);
        $method = 'POST';
        if($exist_abundantcart){
            echo "Already Added to AbundantCart";
        }else{
            // customer data           
            if(is_numeric($raw_data['customer_id'])){
                $customer = $this->foo->get('customers/'.$raw_data['customer_id']);
                $options['order']['customer']['email'] = $customer['customer']['email'] ? $customer['customer']['email'] : '';
                $options['order']['email'] = $customer['customer']['email'] ? $customer['customer']['email'] : '';
            }else{
                $customer = array();
                $options['order']['customer']['email'] = $customer_email;
            }
			
            $options['order']['customer']['first_name'] = $customer ? $customer['customer']['first_name'] : '';
            $options['order']['customer']['last_name'] = $customer ? $customer['customer']['last_name'] : '';
            $options['order']['billing_address']['first_name'] = $customer ? $customer['customer']['first_name'] : '';
            $options['order']['billing_address']['last_name'] = $customer ? $customer['customer']['last_name'] : '';
            $options['order']['billing_address']['address1'] = @$customer['customer']['default_address']['address1'] ? @$customer['customer']['default_address']['address1'].' '.@$customer['customer']['default_address']['address2'] : '';
            $options['order']['billing_address']['phone'] = @$customer['customer']['addresses'][0]['phone'] ? @$customer['customer']['addresses'][0]['phone'] : '';
            $options['order']['billing_address']['city'] = @$customer['customer']['default_address']['city'] ? @$customer['customer']['default_address']['city'] : '';
            $options['order']['billing_address']['province'] = @$customer['customer']['default_address']['province'] ? @$customer['customer']['default_address']['province'] : '';
            $options['order']['billing_address']['country'] = @$customer['customer']['default_address']['country_code'] ? @$customer['customer']['default_address']['country_code'] : '';
            $options['order']['billing_address']['zip'] = @$customer['customer']['default_address']['zip'] ? @$customer['customer']['default_address']['zip'] : '';
            $options['order']['shipping_address']['first_name'] = @$customer['customer']['first_name'] ? @$customer['customer']['first_name'] : '';
            $options['order']['shipping_address']['last_name'] = @$customer['customer']['last_name'] ? @$customer['customer']['last_name'] : '';
            $options['order']['shipping_address']['address1'] = @$customer['customer']['default_address']['address1']? @$customer['customer']['default_address']['address1'].' '.$customer['customer']['default_address']['address2']:'';
            $options['order']['shipping_address']['phone'] = @$customer['customer']['addresses'][0]['phone'] ? @$customer['customer']['addresses'][0]['phone'] : '';
            $options['order']['shipping_address']['city'] = @$customer['customer']['default_address']['city'] ? @$customer['customer']['default_address']['city'] : '';
            $options['order']['shipping_address']['province'] = @$customer['customer']['default_address']['province'] ? @$customer['customer']['default_address']['province'] : '';
            $options['order']['shipping_address']['country'] = @$customer['customer']['default_address']['country_code'] ? @$customer['customer']['default_address']['country_code'] : '';
            $options['order']['shipping_address']['zip'] = @$customer['customer']['default_address']['zip'] ? @$customer['customer']['default_address']['zip'] : '';
            $options['order']['financial_status'] = 'paid';
            $no = $total_quantity = $total_price = 0;

            foreach ($raw_data as $key => $value) {
                if( ($key != '_token') && ($key != 'button') && ($key != 'shop') && ($key != 'currency') && ($key != 'customer_id') && ($key != 'checkout_name') && ($key != 'all_data')){  
                    $other = explode(',', $value);  
                    $array['title'] = str_replace('_', ' ', $key);  
                    $array['id'] = $other['0'];
                    $array['varient_id'] = $other['1'];
                    $array['quantity'] = $other['2'];   
                    $array['price'] = $other['2']*($other['3']/100);
                    $array['original_price'] = $other['3']/100;
                    $total_price += $array['price'];            
                    $options['order']['line_items'][] = $array;
                    $no++;            
                }           
            }
            // customer data

            // katchyngarray
            if($raw_data['customer_id']){
            $order['customer']['email'] = $options['order']['customer']['email']?$options['order']['customer']['email']:'';
            }else{
                $order['customer']['email'] = $customer_email;
            }
			
            $order['customer']['first_name'] = $options['order']['customer']['first_name']?$options['order']['customer']['first_name']:'';
            $order['customer']['last_name'] = $options['order']['customer']['last_name']?$options['order']['customer']['last_name']:'';
            $order['customer']['authProvider'] = 'facebook';
            $order['customer']['authProviderId'] = $fbapikey;
            $order['customer']['mobile'] = $options['order']['shipping_address']['phone']?$options['order']['shipping_address']['phone']:'';
            foreach ($options['order']['line_items'] as $key => $value) {
                $product['number'] = (int) $value['id'];
                $product['name'] = $value['title'];
                $product['price'] = (int) $value['original_price']/100;
                $product['upc'] = (int) $value['id'];
                $product['active'] = true;
                $product['quantity'] = (string)$value['quantity'];                
                $custom = $this->foo->get('products/'.$value['id'], ['fields'=>'body_html,images']);
                // $custom = $this->foo->get('products/1175612522553', ['fields'=>'body_html,images']);
                
                $product['description'] = substr($custom['product']['body_html'], 0, 30);

                foreach ($custom['product']['images'] as $key_1 => $value_1) {
                    $image['is_deafult'] = "true";
                    $image['url'] = $value_1['src'];
                    $product['images'][0] = $image;
                    break;
                }
               
                $product['package_dimensions']['height'] = 0;
                $product['package_dimensions']['length'] = 0;
                $product['package_dimensions']['width'] = 0;
                $product['package_dimensions']['length_unit'] = 'string';
                $product['package_dimensions']['weight'] = 0;
                $product['package_dimensions']['weight_unit'] = 'string';

				$shipping['addressName'] = @$customer['customer']['default_address']['address1'];
				$shipping['addressLine1'] = @$customer['customer']['default_address']['address1'];
				$shipping['addressLine2'] = @$customer['customer']['default_address']['address2'];
				$shipping['cityName'] = @$customer['customer']['default_address']['city'];
				$shipping['postalCode'] = @$customer['customer']['default_address']['zip'];
				$shipping['regionName'] = @$customer['customer']['default_address']['province'];
				$shipping['countryCode'] =  @$customer['customer']['default_address']['cus_add_country'];
				
                $product['shippable'] = true;
                $product['google_category'] = 'string';
                $product['taxable'] = true;
                $product['on_sale'] = true;
                $product['sale_price'] = (int) $value['original_price']/100;
                $order['products'][] = $product;
				$order['shippingAddress'] = $shipping;
            }
            // katchyngarray
	
			
            //$api_key = 'yM4WyOTDAJJlFXUGB0BgspDuKz29klYJ';  
            $api_query = "buy/order";
            $controller = new MainController;
            $result = $controller->api_kachyng($method, $api_query,json_encode($order), $api_key);
            $response_api = json_decode($result, true);
			
            if($response_api['success'] == true){                
                $data_query = ['buy_url' => $response_api['buy_url'], 'orderNumber' => $response_api['orderNumber']];
                $result_data = \App\Orders::insertGetId($data_query);
                
                $cart_user = $options['order']['customer']['first_name']." ".$options['order']['customer']['last_name'];
                if($customer_email){
                    $cart_user = $customer_email;
                    $cart_update = $customer_email.'=='.$result_data;

                }else{
                    $cart_user = $options['order']['customer']['first_name']." ".$options['order']['customer']['last_name'];
                    $cart_update = $raw_data['customer_id'].'=='.$result_data;                    
                }

                
                $cart_products = $no;
                $cart_sync = 'Yes';
                $admin_mail_send = 'No';
                
                $data_query = [['data' => $request->raw_data,'cart_update' => $cart_update,'cart_user' => $cart_user, 'admin_mail_send'=>$admin_mail_send, 'cart_products' => $cart_products,'cart_sync' => $cart_sync,'method' => 'AbundantCart','created_at' => date("Y-m-d H:i:s", time())],];

                $result_data = \App\AbundandCart::insert($data_query);
                echo "New AbundantCart Abundant Cart Order created in katchyng";
				
				//print_r($result_data); die;
				
            }else{
                echo 'Internal Server Error'; 
            }
        }
    }

    public function AbundantCartEmail(Request $request){
        
        $shop = $request->shop;
        $access_token =  DB::table('shopify_url_credentials')->where('site_url', $shop)->value('token');
        if(!is_numeric($request->customer_id)){
            $first_name = 'Mr.';
            $last_name = 'User';
            $customer_id = $email = $request->customer_id;
        }else{
            $customer_id = $request->customer_id;
            $this->foo = Shopify::retrieve($shop, $access_token);
            $customer = $this->foo->get('customers/'.$customer_id);
            $first_name = $customer['customer']['first_name'] ? $customer['customer']['first_name'] : '';
            $last_name = $customer['customer']['last_name'] ? $customer['customer']['last_name'] : '';
            $email = $customer['customer']['email'] ? $customer['customer']['email'] : '';
        }
        $order_id = $request->order_id;
        $buy_url = \App\Orders::where('id', '=', $order_id)->value('buy_url');
        $send_mail = $this->sendmail($email, $first_name.' '.$last_name, $buy_url);

        if($send_mail == 1){
            $result_data = \App\AbundandCart::where('cart_update', $customer_id.'=='.$order_id)->update(['admin_mail_send' => 'Yes']);
        }
        return '1';
    }

    public function sendmail($to, $user_name, $buy_url){ 
        $subject = 'Abandoned Cart';
        $body = '<html><head><meta charset="utf-8&quot;"><title>Email Newsletter</title><style>body{margin: 0;}</style></head><body><table width="600px" border="0" cellspacing="0" cellpadding="0" style="margin: 0 auto; font-size: 16px;"> <tbody><tr><td><table width="100%" border="0" cellspacing="0" cellpadding="0"> <tbody><tr> <td>&nbsp;</td></tr><tr><td><a href="#"><img src="https://demo.stripo.email/content/guids/4ffbc93c-df82-445a-a392-e37b8ba067a3/images/30521532064820666.jpg" width="580px"></a></td></tr></tbody></table></td></tr><tr style="background:#fff;"> <td><table width="100%" border="0" cellspacing="0" cellpadding="0"> <tbody><tr><td width="18">&nbsp;</td><td><h2 style="padding-top: 40px; color: #333; font-size: 30px; font-family: lato; font-weight: 700; margin:0; text-align: center; padding-bottom: 20px;">Something Waiting For you</h2></td><td>&nbsp;</td><td>&nbsp;</td><td width="18">&nbsp;</td></tr><tr><td>&nbsp;</td><td><h6 style="margin-bottom: 20px; color: #333; font-size: 18px; font-family: lato; margin:0; font-weight: 500; line-height: 24px; text-align:center;">Hi '.$user_name.'</h6><p style="text-align:center; line-height: 24px; font-size:18px; color:#333; font-family:lato; margin-bottom:0; font-weight: 500;">We saw that you tried to place order on our website but did not complete the transaction. Are you facing any problem while placing the order?</p><p style="text-align:center; line-height: 24px; font-size:18px; color:#333; font-weight: 500; font-family:lato; margin-top: 9px;">To place your order again, please click this Link </p></td><td width="15">&nbsp;</td><td><img src="android.jpg" alt=""></td><td></td></tr><tr><td>&nbsp;</td><td style="padding-top:40px; text-align:center;"><h2 style="padding-top: 20px; color: #333; font-size: 24px; font-family: lato; font-weight: 700; margin:0; text-align: center; padding-bottom: 40px;">To place your order again, Please click this link</h2><a href="'.$buy_url.'" class="es-button" target="_blank" style=" background: #333; font-weight: 500; font-size: 20px; color: #fff; padding: 11px 30px; margin-bottom: 60px; text-align: center; display: inline-block; text-decoration: none;">Buy Now</a></td></tr></tbody></table> </td></tr></tbody></table></body></html>';
        $headers = 'Content-Type: text/html; charset=UTF-8';        
        return mail($to,$subject,$body,$headers);
		
		/* $data = array('name'=>"admin@satoshicoin.world");
		Mail::send(['text'=>'mail'], $data, function($message){
			 $message->to('manpreet.auspicioussoft@gmail.com', 'Tutorials Point')->subject('Abandoned Cart');
			 $message->from('admin@satoshicoin.world','admin@satoshicoin.world');
		});
		echo "Basic Email Sent. Check your inbox."; */
    }

    public function GetAbundantCart()
    {
       $getabundantcarts = \App\AbundandCart::all();
        $no=1;
        foreach ($getabundantcarts as $getabundantcart) {
            $split = explode('==', $getabundantcart->cart_update);
            $array['no'] = $no;
            $array['cart_user'] = $getabundantcart->cart_user;
            $array['cart_products'] = $getabundantcart->cart_products;
            $array['customer_id'] = $split[0];
            $array['order_id'] = $split[1];
            $array['cart_sync'] = $getabundantcart->cart_sync;
            $array['admin_mail_send'] = $getabundantcart->admin_mail_send;
            $array['created_at'] = $getabundantcart->created_at->format('d, M Y g:i A');
            $abd_cart[] = $array;
            $no++;
        }
        $abandoned_checkouts = $abd_cart;
        echo "<pre>";print_r($abandoned_checkouts);
    } 

    public function shippingrate()
    {   

        $json['checkout']['email'] = 'john.smith@example.com';
        $json['checkout']['line_items'][0]['id'] = 1590257516602;
        $json['checkout']['line_items'][0]['quantity'] = 1;
        
        $shop = 'smart-ad2.myshopify.com';
        $access_token = DB::table('shopify_url_credentials')->where('site_url', $shop)->value('token');
        $this->foo = Shopify::retrieve($shop, $access_token);
        $shipping_rates = $this->foo->createCheckouts($json);
        echo "<pre>";print_r($shipping_rates);die;
    }   
	
	public function ProductSyncToKynch(Request $request){
		
		$shop = $request->shop;
		$access_token =  DB::table('shopify_url_credentials')->where('admin_url', $shop)->value('token');
		$this->foo = Shopify::retrieve($shop, $access_token);
        $app_id =  DB::table('shopify_url_credentials')->where('admin_url', $shop)->value('id');
        $save_account = \App\MigrationAccount::where([['app_id', '=', $app_id],['active', '=', 1]])->value('id');        
        $api_key =  \App\MerchantData::where([['merchant_account_id', '=', $save_account],['merchant_key', '=', 'apikey']])->value('merchant_data');

		$pro_url = 'https://'.$shop.'/admin/products.json';
		//$pro_url = 'https://'.$shop.'/admin/products/1571625336922.json';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$pro_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$header = array(
			'X-Shopify-Access-Token: '.$access_token,
			'Content-Type: application/json',
			'Host: '.$shop);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		$server_output = curl_exec($ch);
		$pro_result=$server_output; 
		$shop = $request->shop;
		$data =  json_encode($pro_result);
		$data1 =  json_decode($data, true);
		$productData =  json_decode($data1, true);
		
		//echo"<pre>"; print_r($productData);  die;
		
		if(!empty($productData)){
			foreach($productData['products'] as $pro){
				$checkIdd = DB::table('sync_products_record')->where('product_id', $pro['id'])->where('status',1)->value('product_id');
				if($checkIdd){
					
				}else{
					$product_data['kaProductNumber'] = $pro['id'];
					$product_data['name'] = $pro['title'];        
					$product_data['standardCost'] = $pro['variants']['0']['price'];
					$product_des = substr($pro['body_html'],0,255);
					$product_data['description'] = $product_des ? $product_des : 'Dummy description';
					$product_data['Category'] = '';
					$product_data['Brand'] = '';
					$product_data['isTaxable'] = $pro['variants']['0']['taxable'];
					$product_data['isSale'] = '';
					$product_data['isShipping'] = $pro['variants']['0']['requires_shipping'];
					$product_data['width'] = '';
					$product_data['Height'] = '';
					$product_data['Depth'] = '';
					$product_data['whlUnit'] = $pro['variants']['0']['weight_unit'];
					$product_data['Weight'] = $pro['variants']['0']['weight'];
					$product_data['weightUnit'] = '';
					$product_data['properitaryUrl'] = '';
					$product_data['longDescription'] = $product_des ? $product_des : '.';
		$product_data['imageURL'] = @$pro['image']['src'] ? @$pro['image']['src'] : 'https://naveen.store/shopify_app/public/product_img/dummy_product.jpg';
					$product_data['additionalImages'] = array();
					
					$quantity = 0;
					$hasVariants = 0;
					if(is_array($pro['variants'])){
						foreach ($pro['variants'] as $value) {
							$quantity += $value['inventory_quantity'];
							$hasVariants++;
						}
					}
					$product_data['uPCEAN'] = '123456780912';
					$product_data['standardQuantity'] = $quantity;
					$product_data['hasVariants'] = 'true';
					if(is_array($pro['options'])){
						foreach ($pro['options'] as $options) {
							$var_fields['charName'] = $options['name'];
							foreach ($options['values'] as $key => $value) {
								$name['name'] = $value; 
							}
							$var_fields['characteristicValues'][] = $name;
							$characteristicNames[] = $var_fields;        
							$product_data['characteristicNameValueRequest'] = array();
						}
					}
					$cart['cartItems'][] = $product_data; 		
					$encoded_data = json_encode($cart);
					
					//print_r($encoded_data);  die;
					
					$method = "POST";
					$api_query = "buy/product/add";
					$controller = new MainController;
					$result = $controller->api_kachyng($method, $api_query, $encoded_data, $api_key);
					
					//echo"<pre>"; print_r($api_key);  die;
					
					$response_api = json_decode($result, true);
					$checkId = DB::table('sync_products_record')->where('product_id', $pro['id'])->value('product_id');
						if($response_api['success'] == 'success'){
							if($checkId){
								DB::table('sync_products_record')->where('product_id', $pro['id'])->update(['status' => 1]);
							}else{
								DB::table('sync_products_record')->insert(['product_id' => $pro['id'], 'status' => 1]);
							}
						}else{
							if($checkId){
								DB::table('sync_products_record')->where('product_id', $pro['id'])->update(['status' => 0]);
							}else{
								DB::table('sync_products_record')->insert(['product_id' => $pro['id'], 'status' => 0]);
							}
						}
					echo"<pre>"; print_r($result);  //die;
				}
			}
		}	
		else
		{
			$response_api=array();
			$response_api['msg']='Something wrong...';	
			return $response_api;
		}
	}
}