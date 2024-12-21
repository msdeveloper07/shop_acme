<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Shopify;
use View;
use Input;
use DB;
use App\Shopify_url_credential;
use Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class WebhookController extends CartController
{
	/*------------

function verify_webhook($data, $hmac_header) {
	$calculated_hmac = base64_encode(hash_hmac('sha256', $data, SHOPIFY_SECRET, true));
	return ($hmac_header == $calculated_hmac);
}

if (isset($_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'])) {
	$hmac_header = $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'];
	$data = file_get_contents('php://input');
	$verified = verify_webhook($data, $hmac_header);
	error_log('Webhook verified: '. var_export($verified, true)); //check error.log to see the result
} else {
	error_log('Request not from shopify');
}






	*/
	public function BackUrl(Request $request, $shop)
	{
        	$app_id =  DB::table('shopify_url_credentials')->where('site_url', $shop)->value('id');
	        $save_account = \App\MigrationAccount::where([['app_id', '=', $app_id],['active', '=', 1]])->value('id');
	        $api_key =  \App\MerchantData::where([['merchant_account_id', '=', $save_account],['merchant_key', '=', 'apikey']])->value('merchant_data');
	        $access_token =  DB::table('shopify_url_credentials')->where('site_url', $shop)->value('token');
	        
	        $this->foo = Shopify::retrieve($shop, $access_token);
			$controller = new MainController;
			if($response['type'] == 'order'){
				// $method = 'POST';
				// $api_query= $response['order']['href'];
				// $encoded_data = NULL;
				// $order_webhook_raw = $controller->api_kachyng($method, $api_query,$encoded_data, $api_key);
				$order_webhook_raw = '{"success":true,"transactionInfo":{"transactionId":"FACC20FF406D43EF9290BB19062CCB59","transactionTime":"1372292244045","productName":"ADigitalproduct","productId":"98B1A0BBF6024B2B917E73C4B95460F4","productPrice":"9.99","quantity":"1","merchantName":"AnAppStore","fundAccountName":"visaending3808","fundAccountId":"D000AF7B774C49389ACF6CC36A38CD19","transactionRef":"eabe8ba0","status":"DENIED","cardType":"visa","logo":null,"orderNumber":"123456","products":[{"productId":"98B1A0BBF6024B2B917E73C4B95460F4","productName":"ADigitalproduct","productNumber":null,"productPrice":9.99,"productDescription":"ADigitalproduct","productCatchPhrase":null,"productImage":null,"characteristicDesc":null,"merchantName":"AnAppStore","productCategory":"DefaultProductCategory","productSubCategory":null,"isGeneric":false,"hasVariants":null,"quantity":"1","smartURL":"jXrtRR","cartCount":0,"purchaseCount":0,"images":[],"variantList":[]}]},"orderLineInfo":[{"productId":"98B1A0BBF6024B2B917E73C4B95460F4","productName":"ADigitalproduct","productNumber":null,"productPrice":9.99,"productDescription":"ADigitalproduct","productCatchPhrase":null,"productImage":null,"characteristicDesc":null,"merchantName":"AnAppStore","productCategory":"DefaultProductCategory","productSubCategory":null,"isGeneric":false,"hasVariants":null,"quantity":"1","smartURL":"jXrtRR","cartCount":0,"purchaseCount":0,"images":[],"variantList":[]}],"orderNumber":"123456","orderAmount":9.99}';
				$order_webhook = json_decode($order_webhook_raw, true);
				$CheckOrder = $this->foo->get('orders', ['fields'=>'id']);
				
				if(array_search($order_webhook['orderNumber'], array_column($CheckOrder['orders'], 'id')) !== False) {
					echo "found";
				}else{
					echo "not found";
				}
			}elseif ($response['type'] == 'product') {
				// $method = 'POST';
				// $api_query= $response['product']['href'];
				// $encoded_data = NULL;
				// $product_webhook_raw = $controller->api_kachyng($method, $api_query,$encoded_data, $api_key);
				$product_webhook_raw = '{"success":true,"productInfo":{"productId":"9962B2B807B7413084AF6F93AE9C7F72","productnumber":"1","productName":"Divyanewutesttask","productPrice":1,"productDescription":"asdad","productCatchPhrase":"\r\n","productImage":"https://img.kachyng.com/297c/16f6/ded9/9c18/e191e2dbf9c8e91e/4d8c80d293129b4f2319a6eac64b314c.jpeg","characteristicDesc":null,"merchantName":"svinoth.subbu+co@gmail.com","productCategory":"DefaultProductCategory","productSubCategory":null,"isGeneric":false,"hasVariants":false,"quantity":null,"smartURL":"geShwi","cartCount":0,"purchaseCount":0,"images":["https://img.kachyng.com/297c/16f6/ded9/9c18/e191e2dbf9c8e91e/4d8c80d293129b4f2319a6eac64b314c.jpeg"],"variantList":[]}}';
				$product_webhook = json_decode($product_webhook_raw, true);
				$CheckProducts = $this->foo->get('products', ['fields'=>'id']);
				// echo "<pre>";print_r($CheckProducts);echo "</pre>";
				if(array_search($product_webhook['productInfo']['productnumber'], array_column($CheckProducts['products'], 'id')) !== False) {
				    $options=array('product'=>array('id'=> $product_webhook['productInfo']['productnumber'],'title'=>$product_webhook['productInfo']['productName'],'body_html'=>$product_webhook['productInfo']['productDescription'],'vendor'=>$product_webhook['productInfo']['merchantName'],'images'=>array(0=>array('src'=>$product_webhook['productInfo']['productImage'],),),'variants'=>array(0=>array('price'=>$product_webhook['productInfo']['productPrice']),),),);
					$json_decode_raw = $this->foo->modify('products/'.$product_webhook['productInfo']['productnumber'] ,$options);
				} else {					
					$options=array('product'=>array('title'=>$product_webhook['productInfo']['productName'],'body_html'=>$product_webhook['productInfo']['productDescription'],'vendor'=>$product_webhook['productInfo']['merchantName'],'images'=>array(0=>array('src'=>$product_webhook['productInfo']['productImage'],),),'variants'=>array(0=>array('price'=>$product_webhook['productInfo']['productPrice']),),),);
					$json_decode_raw =  $this->foo->createProducts($options);
				}
					$json_decode = $json_decode_raw['product'];
					$product_data['name'] = $json_decode['title'];        
			        $product_data['standardCost'] = $json_decode['variants']['0']['price'];
			        $product_data['description'] = $json_decode['body_html'];
			        $product_data['imageURL'] = 'https://cdn.shopify.com/s/files/1/0121/6666/0153/products/Chrysanthemum_7bca169f-695b-4b9e-9c29-34996697ae3b.jpg?v=1530691426';

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
			                $product_data['characteristicNameValueRequest']['characteristicNames'] = $characteristicNames;
			            }
			        }
			        $product_data['characteristicNameValueRequest']['variantImages'] = array();
			        $cart['cartItems'][] = $product_data; 
					$encoded_data = json_encode($cart);      
			        $method = "POST";
			        $api_query = "buy/product/add";

			        $controller = new MainController;
			        $result = $controller->api_kachyng($method, $api_query, $encoded_data, $api_key);
			        $database_query = [['method' => 'result create', 'data' => json_encode($result)],];
			        $result_data = \App\Testdata::insert($database_query);

			        $response_api = json_decode($result, true);
			        echo "<pre>";print_r($response_api);die;
			        $data_query = [['product_id' => $json_decode['id'], 'api_product_id' => $response_api['productDetails'][0]['productNumber'], 'smart_url' => $response_api['productDetails'][0]['smartURL']],];
			        $result_data = \App\ProductUpload::insert($data_query);
			}
	
	}

	public function GetBackUrl()
	{
		echo "string";
	}
}


/*
{
    "type": "product",
         "product": {
            "href": "https://app.kachyng.com/api/v2/buy/A06D044893754C9CBF9937192AAFEE2E/getProductDetail"
      }
}

{
    "type": "order",
         "order": {
            "href": "https://app.kachyng.com/api/v2/buy/order/55FFFAF219394C488F85BD1541914474"
      }
}
*/