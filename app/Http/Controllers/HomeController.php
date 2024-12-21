<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Shopify;
use View;
use Input;
use DB;
use App\AbundandCart;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use URL;
use App\ShopifyOrders;

class HomeController extends Controller
{
  // protected $shop = "divyanshu-test1.myshopify.com";
  protected $foo;
  protected $access_token;
  
    protected $scopes = ['read_shipping', 'write_shipping','read_products','write_products','read_analytics', 'read_checkouts', 'write_checkouts', 'read_reports', 'write_reports', 'read_orders', 'write_orders','read_themes','write_themes','read_customers', 'write_customers','read_price_rules','write_price_rules','unauthenticated_write_checkouts','unauthenticated_write_customers', 'read_script_tags', 'write_script_tags'];
  
  public function getPermission()
  {
    $this->shop = Input::get('site_url').'.myshopify.com';
    $this->foo = Shopify::make($this->shop, $this->scopes);
    return $this->foo->redirect();
  }

  public function getPermission_a($url)
  {    
    $this->shop = $url;
    $this->foo = Shopify::make($this->shop, $this->scopes);
    return $this->foo->redirect();
  }
  
  public function getResponse(Request $request)
  {
    $this->getPermission_a($request->shop);
    $user = $this->foo->auth()->getUser();
    $access_token = $user->token;
   // print_r($access_token); die;
    $shp_cnt = DB::table('shopify_url_credentials')->where('admin_url',request()->shop)->count();

    if($shp_cnt > 0){
      DB::table('shopify_url_credentials')
		->where('admin_url', request()->shop)
		->update([
				'admin_url' => request()->shop, 
				'site_url' => request()->shop, 
				'store_url' => request()->shop, 
				'token' => $access_token,
				'wallet_address'=>''
			]);
    }else{
      DB::table('shopify_url_credentials')
			->insert([
				'admin_url' => request()->shop, 
				'site_url' => request()->shop, 
				'store_url' => request()->shop, 
				'token' => $access_token,
				'wallet_address'=>''
			]);
    } 
	  
    DB::table('shopify_url_credentials')->insert(['site_url' => request()->shop, 'wallet_address' => '', 'token' => $access_token]);

    return redirect('https://'.request()->shop.'/admin/settings/channels');
  }

  public function getLogin()
  {    

    /*$shop = 'ka-testing-store.myshopify.com';
    $this->shop = $shop;
    $this->foo = Shopify::make($this->shop, $this->scopes);
    return $this->foo->redirect();*/

    if (request()->has('shop')) {
        $access_token = DB::table('shopify_url_credentials')->where('site_url', request()->shop)->value('token');
        $shop = request()->shop;
        $this->afterLogin($shop, $access_token);
        
    }else{
      return View::make('pages.home');
    }
  }

  public function afterLogin($shop, $access_token)
  { 
        $this->shop = $shop;
        $this->access_token = $access_token;
        $this->foo = Shopify::retrieve($shop, $access_token);
        $user = $this->foo->getUser();        
        $base_url = URL::to('/');
        $webhooks = $this->foo->getWebhooksAll();
        // $this->kachyngMerchant();
        if(array_search($base_url."/delete?site_url=".$this->shop, array_column($webhooks['webhooks'], 'address')) != False){
          $options['webhook']['topic'] = "app/uninstalled";
          $options['webhook']['address'] = $base_url."/delete?site_url=".$this->shop;
          $options['webhook']['format'] = "json";
          $this->foo->createWebhooks($options);
        }else{
          // echo "2";
        }
        session(['shop' => $shop]);
        session(['access_token' => $access_token]);

        $getabundantcarts = \App\AbundandCart::orderBy('created_at', 'desc')->get();
        $no=1;
        foreach ($getabundantcarts as $getabundantcart) {
            $split = explode('==', $getabundantcart->cart_update);
			//print_r($split);  die;
            $array['no'] = $no;
            $array['cart_user'] = $getabundantcart->cart_user;
            $array['cart_products'] = $getabundantcart->cart_products;
            $array['customer_id'] = $split[0];
            $array['order_id'] = @$split[1];
            $array['cart_sync'] = $getabundantcart->cart_sync;
            $array['admin_mail_send'] = $getabundantcart->admin_mail_send;
            $array['created_at'] = $getabundantcart->created_at->format('d, M Y g:i A');
            $abd_cart[] = $array;
            $no++;
        }
        $abandoned_checkouts = isset($abd_cart) ? $abd_cart:'';
        $app_id =  DB::table('shopify_url_credentials')->where('admin_url', $this->shop)->value('id');
		    $site_url =  DB::table('shopify_url_credentials')->where('admin_url', $this->shop)->value('site_url');
        $save_account = \App\MigrationAccount::where([['app_id', '=', $app_id],['active', '=', 1]])->value('id');
        $merchantdata =  \App\MerchantData::where([['merchant_account_id', '=', $save_account]])->pluck('merchant_data', 'merchant_key');
        $saved_data = json_decode($merchantdata,true);
		    $uname_pwd = DB::table('migration_accounts')->where('id', $save_account)->first();
		  
    		$pro_url = 'https://'.$this->shop.'/admin/products.json';
    		$ch = curl_init();
    		curl_setopt($ch, CURLOPT_URL,$pro_url);
    		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    		$header = array(
    			'X-Shopify-Access-Token: '.$access_token,
    			'Content-Type: application/json',
    			'Host: '.$this->shop);
    		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    		$server_output = curl_exec($ch);
    		$pro_result_array=json_decode($server_output); 
    		$pro_result=$server_output; 
    		$remain_count = DB::table('sync_products_record')->where('status',0)->get();
    		$remain_count_pro = count($remain_count);


        $pro_url = 'https://'.$this->shop.'/admin/orders.json?status=any&limit=249';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$pro_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $header = array(
          'X-Shopify-Access-Token: '.$access_token,
          'Content-Type: application/json',
          'Host: '.$this->shop);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $server_output = curl_exec($ch);
        $ord_result_array=json_decode($server_output); 
        $ord_result=$server_output; 
        $orders = json_decode($ord_result,true);
        // echo"<pre>"; print_r($orders);  die;
        foreach($orders['orders'] as $order){
          $idExit = DB::table('shopify_orders')->where('order_id',$order['id'])->count();
          if($idExit == 0)
          {
            $shopify_order = new ShopifyOrders();
            $shopify_order->order_id = $order['id'];
            $shopify_order->meta_data = json_encode($order);
            $shopify_order->save();
          }
        }

        $all_order = ShopifyOrders::orderBy('id', 'ASC')->get();

        $pro_result = @$pro_result;
        $pro_result_array = @$pro_result_array;
        $frontEnd = @$site_url;
        $apiKey = isset($saved_data['apiKey']) ? $saved_data['apiKey']:'';
        $secretKey = isset($saved_data['secretKey'])? $saved_data['secretKey']:'';
        $fbapikey = isset($saved_data['fbapikey']) ? $saved_data['fbapikey']:'';
        $fbsecretkey = isset($saved_data['fbsecretkey'])? $saved_data['fbsecretkey']:'';
        $applicaton_id = isset($saved_data['applicaton_id'])? $saved_data['applicaton_id']:'';
        $pageId = isset($saved_data['pageId']) ? $saved_data['pageId']:'';
        $pixel = isset($saved_data['pixel'])? $saved_data['pixel']:'';
        $google_client_key = isset($saved_data['google_client_key'])? $saved_data['google_client_key']:'';
        $google_client_secret = isset($saved_data['google_client_secret']) ? $saved_data['google_client_secret']:'';
        $google_pixel = isset($saved_data['google_pixel']) ? $saved_data['google_pixel']:'';
        $business_name = isset($saved_data['business_name'])? $saved_data['business_name']:'';
        $dba_name = isset($saved_data['dba_name']) ? $saved_data['dba_name']:'';
        $business_logo_url = isset($saved_data['business_logo_url'])? $saved_data['business_logo_url']:'';
        $processor_name = isset($saved_data['processor_name']) ? $saved_data['processor_name']:'';
        $processor_userid = isset($saved_data['processor_userid']) ? $saved_data['processor_userid']:'';
        $processor_password = isset($saved_data['processor_password'])? $saved_data['processor_password']:'';
        $uname = isset($uname_pwd->uname) ? $uname_pwd->uname:'';
        $psw = isset($uname_pwd->psw) ? $uname_pwd->psw:'';
        $tax = isset($uname_pwd->tax) ? $uname_pwd->tax:'';
        $checkoutType = $uname_pwd->checkout_type;
        $carriers = $this->carriersList();
        $get_package = DB::table('store_carrier_package')->where('shop',$this->shop)->first();
        //echo"<pre>"; print_r($get_package->package_code); die;
        // $carriers = DB::table('carriers')->get();
        echo  View::make('pages.app_connected', ['shop' => $this->shop,'saved' => '0','abandoned_checkouts' => $abandoned_checkouts,'apiKey' => $apiKey,'secretKey' => $secretKey, 'uname' => $uname, 'psw' => $psw, 'tax' => $tax,'frontEnd' => $frontEnd, 'all_order' => $all_order, 'remain_count_pro' =>$remain_count_pro, 'pro_result' => $pro_result, 'pro_result_array' => $pro_result_array, 'fbapikey' => $fbapikey,'fbsecretkey' => $fbsecretkey,'applicaton_id' => $applicaton_id,'pageId' => $pageId,'pixel' => $pixel,'google_client_key' => $google_client_key,'google_client_secret' => $google_client_secret,'google_pixel' => $google_pixel,'business_name' => $business_name,'dba_name' => $dba_name,'business_logo_url' => $business_logo_url,'processor_name' => $processor_name,'processor_userid' => $processor_userid,'processor_password' => $processor_password, 'carriers' => $carriers, 'package_code' => $get_package->package_code, 'checkout_type' => $checkoutType]);
  }

  // public function kachyngMerchant(Request $request){
  //     echo"<pre>";  print_r($request->all()); die;
  //     $data = array();
  //     $data['access_token'] = $access_token;
  //     $data['shop'] = $shop;
  //     return json_encode($data);
  // }

  public function Delete(Request $request)
  {
    $shop_data = DB::table('shopify_url_credentials')->where('site_url', request()->site_url)->get();
    echo "<pre>";print_r($shop_data);die;
  }

  public function dataFromShopify(Request $request){
		echo"<pre>"; print_r($request->all()); die;
  }
  
  public function carriersList()
  {
    $curl_url = "https://app.kachyng.com/api/v3/carriers";
    $carriers = $this->runCurl($curl_url);
    foreach ($carriers['data']['carriers'] as $carrier) {
      $idExit = DB::table('carriers')->where('carrier_id',$carrier['carrier_id'])->count();
      if($idExit == 0)
      {
        DB::table('carriers')->insert([
          'carrier_id' => $carrier['carrier_id'], 
          'carrier_code' => $carrier['carrier_code'], 
          'account_number' => $carrier['account_number'], 
          'requires_funded_amount' => $carrier['requires_funded_amount'],
          'balance' => $carrier['balance'],
          'nickname' => $carrier['nickname'],
          'friendly_name' => $carrier['friendly_name'],
          'primary_caarrier' => $carrier['primary'],
          'has_multi_package_supporting_services' => $carrier['has_multi_package_supporting_services'],
          'supports_label_messages' => $carrier['supports_label_messages'],
          'services' => json_encode($carrier['services']),
          'packages' => json_encode($carrier['packages']),
          'options' => json_encode($carrier['options'])
        ]);
      }
    }
    return $carriers;
  }

  public function carriersPackage(Request $request)
  {
    $carrier_id = $request->carrier_id;
    $shop = $request->shop;
    $curl_url = "https://app.kachyng.com/api/v3/carriers/".$carrier_id;
    $packages = $this->runCurl($curl_url);
    return $packages;
    //echo"<pre>"; print_r($packages); die;
  }

    public function runCurl($curl_url)
    {
      $uname = "apiKey";
      $pwd = "6epqv6XgTAD0ciFugCfq6fS6hJmprDIg";
      $method = "GET";
      $encoded_data = NULL;
      $header[] = 'authorization : Basic '.base64_encode('apiKey:'.$pwd); 
      $header[] = 'content-type : application/json'; 
      $header[] = 'cache-control: no-cache'; 
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
      // echo"<pre>"; print_r($response);  die;
      $err = curl_error($curl);
      curl_close($curl);
      $carriers = json_decode($response, true);
      return $carriers;
    }

    public function carriersPackageSave(Request $request)
    {
      //echo"<pre>"; print_r($request->all());  die;
      $package_code = $request->package_code;
      $package_name = $request->package_name;
      $package_des = $request->package_des;
      $carrier_id = $request->carrier_id;
      $shop = $request->shop;
      $idExit = DB::table('store_carrier_package')->where('shop',$shop)->where('carrier_id',$carrier_id)->count();
      if($idExit == 0)
      {
        DB::table('store_carrier_package')->insert([
          'package_code' => $package_code, 
          'package_name' => $package_name, 
          'package_des' => $package_des, 
          'carrier_id' => $carrier_id,
          'shop' => $shop
        ]);
        $data = array();
        $data['success'] = 'true';    
        $data['message'] = 'Data Inserted..!';  
        return json_encode($data);
      }
      else
      {
        $affected = DB::table('store_carrier_package')
              ->where('carrier_id', $carrier_id)
              ->where('shop', $shop)
              ->update([
                    'package_code' => $package_code, 'carrier_id' => $carrier_id, 'shop' => $shop, 
                    'package_name' => $package_name, 'package_des' => $package_des
                  ]);
        $data = array();
        $data['success'] = 'true';    
        $data['message'] = 'Data Updated..!';  
        return json_encode($data);  
      }
    }
}