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
use URL;
class MainController extends Controller
{   
    // login page in app
    public function GetMerchantData(Request $request){ 
	
		// echo"<pre>";  print_r($request->all());  die;
		
        $access_token = $request->session()->get('access_token');
        $shop = $request->session()->get('shop');
        $app_id =  DB::table('shopify_url_credentials')->where('admin_url', $request->shop)->value('id');
		    $check_siteUlr = DB::table('shopify_url_credentials')->where('admin_url', $request->shop)->value('site_url');
		
    		if($check_siteUlr){
    			DB::table('shopify_url_credentials')->where('admin_url', $request->shop)->update(array('site_url' => $check_siteUlr));
    		}else{
    			DB::table('shopify_url_credentials')->insert(['site_url' => $request->site_url]);
    		}

        $data['user']['username'] = $request->uname;
        $data['user']['password'] = $request->psw;

        $save_account = \App\MigrationAccount::where('uname', $request->uname)->first();

        // echo"<pre>"; print_r($save_account);  die;

        if(!$save_account){
          \App\MigrationAccount::where('app_id', $app_id)->update(['active' => 0]);
          $save_account = new MigrationAccount;
          $save_account->app_id = $app_id;
          $save_account->uname = $request->uname;
          $save_account->psw = $request->psw;
          $save_account->checkout_type = $request->checkout_type;
          $save_account->active = 1;
          $save_account->save();
          
          $data = [
            ['merchant_account_id' => $save_account->id, 'merchant_key' => 'apiKey', 'merchant_data' => $request->uname],
            ['merchant_account_id' => $save_account->id, 'merchant_key' => 'secretKey', 'merchant_data' => $request->psw]
          ];
          $result_data = \App\MerchantData::insert($data);
        }
        else
        {
          DB::table('migration_accounts')
            ->where('uname', $request->uname)
            ->where('psw', $request->psw)
            ->where('active', '1')
            ->limit(1)
            ->update(array('checkout_type' => $request->checkout_type, 'tax' => $request->tax));
        }

        // if($save_account){
        //   $this->CreateWebHooks($access_token, $shop);
        // }

        $apiKey = \App\MerchantData::where([['merchant_account_id', '=', $save_account->id],['merchant_key', '=', 'apiKey']])->value('merchant_data');
        $secretKey = \App\MerchantData::where([['merchant_account_id', '=', $save_account->id],['merchant_key', '=', 'secretKey']])->value('merchant_data');

        $final['apiKey'] = $apiKey;
        $final['secretKey'] = $secretKey;
        return json_encode($final);
    }

    // display option page
    public function GetDisplayOption(Request $request){
        $access_token = $request->session()->get('access_token');
        $shop = $request->session()->get('shop');
        $app_id =  DB::table('shopify_url_credentials')->where('admin_url', $request->shop)->value('id');
        $save_account = \App\MigrationAccount::where([['app_id', '=', $app_id],['active', '=', 1]])->value('id');        
        $api_key =  \App\MerchantData::where([['merchant_account_id', '=', $save_account],['merchant_key', '=', 'apikey']])->value('merchant_data');
        $data['merchantSetting']['account']['business_name'] = $request->business_name;
        $data['merchantSetting']['account']['dba_name'] = $request->dba_name;
        $data['merchantSetting']['account']['business_logo_url'] = $request->business_logo_url;
        $data['merchantSetting']['payment']['processor_name'] = $request->payment_processor;
        $data['merchantSetting']['payment']['processor_userid'] = $request->selected_payment_processor_id_or_key;
        $data['merchantSetting']['payment']['processor_password'] = $request->selected_payment_processor_password_or_secret_value;
        // $data['merchantSetting']['payment']['processor_account_number'] = $request->business_name;

        $method = "POST";
        $api_query = "business/setting/save";
        $encoded_data = json_encode($data);
        $result = $this->api_kachyng($method, $api_query, $encoded_data, $api_key);
        
        if ($request->business_name) { 
          \App\MerchantData::where([['merchant_account_id', '=', $save_account],['merchant_key', '=', 'business_name']])->delete(); 
        }
        if ($request->dba_name) {
          \App\MerchantData::where([['merchant_account_id', '=', $save_account],['merchant_key', '=', 'dba_name']])->delete(); 
        }
        if ($request->business_logo_url) { 
          \App\MerchantData::where([['merchant_account_id', '=', $save_account],['merchant_key', '=', 'business_logo_url']])->delete();
        }
        if ($request->payment_processor) {
          \App\MerchantData::where([['merchant_account_id', '=', $save_account],['merchant_key', '=', 'processor_name']])->delete(); 
        }
        if ($request->selected_payment_processor_id_or_key) { 
          \App\MerchantData::where([['merchant_account_id', '=', $save_account],['merchant_key', '=', 'processor_userid']])->delete(); 
        }
        if ($request->selected_payment_processor_password_or_secret_value) {
          \App\MerchantData::where([['merchant_account_id', '=', $save_account],['merchant_key', '=', 'processor_password']])->delete();
        }

        $data_database_query = [
          ['merchant_account_id' => $save_account, 'merchant_key' => 'business_name', 'merchant_data' => $request->business_name],
          ['merchant_account_id' => $save_account, 'merchant_key' => 'dba_name', 'merchant_data' => $request->dba_name],
          ['merchant_account_id' => $save_account, 'merchant_key' => 'business_logo_url', 'merchant_data' => $request->business_logo_url],
          ['merchant_account_id' => $save_account, 'merchant_key' => 'processor_name', 'merchant_data' => $request->payment_processor],
          ['merchant_account_id' => $save_account, 'merchant_key' => 'processor_userid', 'merchant_data' => $request->selected_payment_processor_id_or_key],
          ['merchant_account_id' => $save_account, 'merchant_key' => 'processor_password', 'merchant_data' => $request->selected_payment_processor_password_or_secret_value],
        ];
        $result_data = \App\MerchantData::insert($data_database_query);
        return json_encode($result);
    }

    // facebook and google page data
    public function SetScripts(Request $request){
      $access_token = $request->session()->get('access_token');
      $shop = $request->session()->get('shop');
      $app_id =  DB::table('shopify_url_credentials')->where('admin_url', $request->shop)->value('id');

      $save_account = \App\MigrationAccount::where([['app_id', '=', $app_id],['active', '=', 1]])->value('id');
      //$api_key =  \App\MerchantData::where([['merchant_account_id', '=', $save_account],['merchant_key', '=', 'apikey']])->value('merchant_data');
      $api_key =  \App\MerchantData::where([['merchant_account_id', '=', $save_account]])->value('merchant_data');
      $data['merchantSetting']['socialAccount']['facebook']['apikey'] = $request->api_key;
      $data['merchantSetting']['socialAccount']['facebook']['secretkey'] = $request->secret_key;
      $data['merchantSetting']['socialAccount']['facebook']['applicaton_id'] = $request->application_key;
      // $data['merchantSetting']['socialAccount']['facebook']['callback_url'] = $request->business_name;
      $data['merchantSetting']['socialAccount']['facebook']['pageId'] = $request->page_id;
      $data['merchantSetting']['socialAccount']['facebook']['pixel'] = $request->pixel_id;
      $data['merchantSetting']['socialAccount']['google']['google_client_key'] = $request->client_id;
      $data['merchantSetting']['socialAccount']['google']['google_client_secret'] = $request->google_client_secret;
      $data['merchantSetting']['socialAccount']['google']['google_pixel'] = $request->google_pixel;
      $method = "POST";
      $api_query = "business/setting/save";
      $encoded_data = json_encode($data);
      $result = $this->api_kachyng($method, $api_query, $encoded_data, $api_key);
      if ($request->api_key) {
       \App\MerchantData::where([['merchant_account_id', '=', $save_account],['merchant_key', '=', 'fbapikey']])->delete();
        }
      if ($request->secret_key) { 
        \App\MerchantData::where([['merchant_account_id', '=', $save_account],['merchant_key', '=', 'fbsecretkey']])->delete(); 
      }
      if ($request->application_key) {
       \App\MerchantData::where([['merchant_account_id', '=', $save_account],['merchant_key', '=', 'applicaton_id']])->delete(); 
     }
      if ($request->page_id) {
       \App\MerchantData::where([['merchant_account_id', '=', $save_account],['merchant_key', '=', 'pageId']])->delete(); 
     }
      if ($request->pixel_id) {
       \App\MerchantData::where([['merchant_account_id', '=', $save_account],['merchant_key', '=', 'pixel']])->delete(); 
     }
      if ($request->client_id) {
       \App\MerchantData::where([['merchant_account_id', '=', $save_account],['merchant_key', '=', 'google_client_key']])->delete(); 
     }
      if ($request->google_client_secret) {
       \App\MerchantData::where([['merchant_account_id', '=', $save_account],['merchant_key', '=', 'google_client_secret']])->delete(); 
     }
      if ($request->google_pixel) {
       \App\MerchantData::where([['merchant_account_id', '=', $save_account],['merchant_key', '=', 'google_pixel']])->delete(); 
     }
      $data_database_query = [
        ['merchant_account_id' => $save_account, 'merchant_key' => 'fbapikey', 'merchant_data' => $request->api_key],
        ['merchant_account_id' => $save_account, 'merchant_key' => 'fbsecretkey', 'merchant_data' => $request->secret_key],
        ['merchant_account_id' => $save_account, 'merchant_key' => 'applicaton_id', 'merchant_data' => $request->application_key],
        ['merchant_account_id' => $save_account, 'merchant_key' => 'pageId', 'merchant_data' => $request->page_id],
        ['merchant_account_id' => $save_account, 'merchant_key' => 'pixel', 'merchant_data' => $request->pixel_id],
        ['merchant_account_id' => $save_account, 'merchant_key' => 'google_client_key', 'merchant_data' => $request->client_id],
        ['merchant_account_id' => $save_account, 'merchant_key' => 'google_client_secret', 'merchant_data' => $request->google_client_secret],
        ['merchant_account_id' => $save_account, 'merchant_key' => 'google_pixel', 'merchant_data' => $request->google_pixel],         
      ];
      $result_data = \App\MerchantData::insert($data_database_query);
      return json_encode($result);
    }

    // getall merchant data from database by id 
    public function GetAllData(Request $request){
      $access_token = $request->session()->get('access_token');
      $shop = $request->session()->get('shop');
      $app_id =  DB::table('shopify_url_credentials')->where('site_url', $shop)->value('id');
      $save_account = \App\MigrationAccount::where([['app_id', '=', $app_id],['active', '=', 1]])->value('id');        

      $data['uname']  = \App\MigrationAccount::where([['app_id', '=', $app_id],['active', '=', 1]])->value('uname');        
      $data['psw']  = \App\MigrationAccount::where([['app_id', '=', $app_id],['active', '=', 1]])->value('psw');
      
      $data['apikey'] = \App\MerchantData::where([['merchant_account_id', '=', $save_account],['merchant_key', '=', 'apikey']])->value('merchant_data');
      $data['secretKey'] = \App\MerchantData::where([['merchant_account_id', '=', $save_account],['merchant_key', '=', 'secretKey']])->value('merchant_data');
      
      $data['business_name'] = \App\MerchantData::where([['merchant_account_id', '=', $save_account],['merchant_key', '=', 'business_name']])->value('merchant_data');
      $data['dba_name'] = \App\MerchantData::where([['merchant_account_id', '=', $save_account],['merchant_key', '=', 'dba_name']])->value('merchant_data');
      $data['business_logo_url'] = \App\MerchantData::where([['merchant_account_id', '=', $save_account],['merchant_key', '=', 'business_logo_url']])->value('merchant_data');
      $data['payment_processor'] = \App\MerchantData::where([['merchant_account_id', '=', $save_account],['merchant_key', '=', 'processor_name']])->value('merchant_data');
      $data['selected_payment_processor_id_or_key'] = \App\MerchantData::where([['merchant_account_id', '=', $save_account],['merchant_key', '=', 'processor_userid']])->value('merchant_data');
      $data['selected_payment_processor_password_or_secret_value'] = \App\MerchantData::where([['merchant_account_id', '=', $save_account],['merchant_key', '=', 'processor_password']])->value('merchant_data');
      
      $data['fbapikey'] = \App\MerchantData::where([['merchant_account_id', '=', $save_account],['merchant_key', '=', 'fbapikey']])->value('merchant_data');
      $data['fbsecretkey'] = \App\MerchantData::where([['merchant_account_id', '=', $save_account],['merchant_key', '=', 'fbsecretkey']])->value('merchant_data');
      $data['application_key'] = \App\MerchantData::where([['merchant_account_id', '=', $save_account],['merchant_key', '=', 'applicaton_id']])->value('merchant_data');
      $data['page_id'] = \App\MerchantData::where([['merchant_account_id', '=', $save_account],['merchant_key', '=', 'pageId']])->value('merchant_data');
      $data['pixel_id'] = \App\MerchantData::where([['merchant_account_id', '=', $save_account],['merchant_key', '=', 'pixel']])->value('merchant_data');
      
      $data['client_id'] = \App\MerchantData::where([['merchant_account_id', '=', $save_account],['merchant_key', '=', 'google_client_key']])->value('merchant_data');
      $data['google_client_secret'] = \App\MerchantData::where([['merchant_account_id', '=', $save_account],['merchant_key', '=', 'google_client_secret']])->value('merchant_data');
      $data['google_pixel'] = \App\MerchantData::where([['merchant_account_id', '=', $save_account],['merchant_key', '=', 'google_pixel']])->value('merchant_data');
 
      return json_encode($data);
    }

    // hit kachyng api with without authemtication
    public function api_kachyng($method, $api_query, $encoded_data = NULL, $api_key = NULL, $sessionToken = NULL){
		
		//echo"<pre>"; print_r($api_query);  die;
		
		
        if(trim($api_key)){
          $header[] = 'authorization : Basic '.base64_encode('apiKey:'.$api_key); 
        }

        if(trim($sessionToken)){
          $header[] = 'authorization : Basic '.base64_encode('sessionToken:'.$sessionToken); 
        }
        
        $header[] = 'content-type : application/json'; 
        $header[] = 'cache-control: no-cache'; 
        
        if (strpos($api_query, 'https') !== false) {
          $curl_url = $api_query;
        }else{
          $curl_url = "https://app.kachyng.com/api/v2/".$api_query;
        }

      // echo"<pre>"; print_r($encoded_data);  echo"<br>";
      // print_r($api_query); echo "<br>";
      // print_r($api_key); echo "<br>";
      // print_r($method); echo "<br>";
      // echo"<pre>"; print_r($header);  die;

		//print_r($header);  die('dsds');
		
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $curl_url,
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
		
		//echo"<pre>"; print_r($response);  die;
		
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
          return "cURL Error #:" . $err;
        } else {
          return $response;
        }
    }

    // create webhooks for product upload
    public function CreateWebHooks($access_token, $shop){
        $this->foo = Shopify::retrieve($shop, $access_token);
        $webhooks = $this->foo->getWebhooksAll();
        $base_url = str_replace('http', 'https', URL::to('/'));
        
        if($this->searchForId('products/create', $webhooks['webhooks']) == ''){
          $options_create['webhook']['topic'] = "products/create";
          $options_create['webhook']['address'] = $base_url."/webhooks/". $shop ."/products/create";
          $options_create['webhook']['format'] = "json";
          $this->foo->createWebhooks($options_create);
        }

        if($this->searchForId('products/create', $webhooks['webhooks']) == ''){
          $options_delete['webhook']['topic'] = "products/delete";
          $options_delete['webhook']['address'] = $base_url."/webhooks/". $shop ."/products/delete";
          $options_delete['webhook']['format'] = "json";
          $this->foo->createWebhooks($options_delete);
        }

        if($this->searchForId('products/create', $webhooks['webhooks']) == ''){
          $options_update['webhook']['topic'] = "products/update";
          $options_update['webhook']['address'] = $base_url."/webhooks/". $shop ."/products/update";
          $options_update['webhook']['format'] = "json";
          $this->foo->createWebhooks($options_update);
        }


        if($this->searchForId('orders/create', $webhooks['webhooks']) == ''){
          $options_update['webhook']['topic'] = "orders/create";
          $options_update['webhook']['address'] = $base_url."/webhooks/". $shop ."/orders/create";
          $options_update['webhook']['format'] = "json";
          $this->foo->createWebhooks($options_update);
        }

        if($this->searchForId('orders/updated', $webhooks['webhooks']) == ''){
          $options_update['webhook']['topic'] = "orders/updated";
          $options_update['webhook']['address'] = $base_url."/webhooks/". $shop ."/orders/updated";
          $options_update['webhook']['format'] = "json";
          $this->foo->createWebhooks($options_update);
        }

        $webhookss = $this->foo->getWebhooksAll();
        echo "<pre>";print_r($webhookss);
    }


    // create webhooks for product upload
    public function CreateWebHookss(Request $request){
		//print_r($request->all()); die;
        $access_token = $request->session()->get('access_token');
        $shop = $request->session()->get('shop');
        $this->foo = Shopify::retrieve($shop, $access_token);
        $webhooks = $this->foo->getWebhooksAll();
        $base_url = str_replace('http', 'https', URL::to('/'));
        // $this->foo->delete('webhooks/319166545977');
        
        if($this->searchForId('products/create', $webhooks['webhooks']) == ''){
          $options_create['webhook']['topic'] = "products/create";
          $options_create['webhook']['address'] = $base_url."/webhooks/". $shop ."/products/create";
          $options_create['webhook']['format'] = "json";
          $this->foo->createWebhooks($options_create);
        }

        if($this->searchForId('products/create', $webhooks['webhooks']) == ''){
          $options_delete['webhook']['topic'] = "products/delete";
          $options_delete['webhook']['address'] = $base_url."/webhooks/". $shop ."/products/delete";
          $options_delete['webhook']['format'] = "json";
          $this->foo->createWebhooks($options_delete);
        }

        if($this->searchForId('products/create', $webhooks['webhooks']) == ''){
          $options_update['webhook']['topic'] = "products/update";
          $options_update['webhook']['address'] = $base_url."/webhooks/". $shop ."/products/update";
          $options_update['webhook']['format'] = "json";
          $this->foo->createWebhooks($options_update);
        }


        if($this->searchForId('orders/create', $webhooks['webhooks']) == ''){
          $options_update['webhook']['topic'] = "orders/create";
          $options_update['webhook']['address'] = $base_url."/webhooks/". $shop ."/orders/create";
          $options_update['webhook']['format'] = "json";
          $this->foo->createWebhooks($options_update);
        }

        if($this->searchForId('orders/updated', $webhooks['webhooks']) == ''){
          $options_update['webhook']['topic'] = "orders/updated";
          $options_update['webhook']['address'] = $base_url."/webhooks/". $shop ."/orders/updated";
          $options_update['webhook']['format'] = "json";
          $this->foo->createWebhooks($options_update);
        }

        $webhookss = $this->foo->getWebhooksAll();
        echo "<pre>";print_r($webhookss);
    }

    // helping function
    public function searchForId($id, $array) {
      foreach ($array as $key => $val) {
        if ($val['topic'] == $id) {
          return $val['address'];
        }
      }
      return null;
    }
}