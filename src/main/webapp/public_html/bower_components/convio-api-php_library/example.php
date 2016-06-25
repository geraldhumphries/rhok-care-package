<?php
/*
 * Copyright 2010 Convio, Inc.
*/
require_once('ConvioOpenAPI.php');
require_once('../simple_html_dom.php');

class test {

	public function __construct() {
		

		$this->cookies();
		//$this->parse($this->grabProducts());
	}

	protected function grabProducts() {
		// Base Configuration
		$convioAPI = new ConvioOpenAPI;
		$convioAPI->host       = 'secure3.convio.net';
		$convioAPI->short_name = 'careca';
		$convioAPI->api_key    = '8RAF4u8UkAcruPR';

		// Authentication Configuration
		$convioAPI->login_name     = 'rhok';
		$convioAPI->login_password = 'rhok2016';

		// Choose Format (If not set, a PHP object will be returned)
		$convioAPI->response_format = 'json';

		// Set API Parameters
		$params = array('VIEW_CATALOG' => true, 'store_id' => 1461);

		// Make API call (ApiServlet_apiMethod)
		return $convioAPI->call('Ecommerce', 'VIEW_CATALOG', $params);
	}

	protected function parse($response) {
		error_log($response);

		// $doc = new DOMDocument();
		// $doc->loadHTML($response);
		// echo $doc->saveHTML();
	}

	protected function cookies() {
		$html = file_get_html('https://secure3.convio.net/careca/site/Ecommerce?VIEW_CATALOG=true&store_id=1461&NAME=&FOLDER=0');

		$products = array();

		foreach($html->find('form') as $form) {
			
			$product = array();
			foreach($form->find('input') as $input) {
				$product[$input->getAttribute('name')] = $input->getAttribute('value');
			}

			$product['img'] = array();
			foreach ($form->find('img') as $img) {
				$product['img'] = $img->getAttribute('src');
			}
			
			foreach($form->find('span') as $span) {
				$product[$span->getAttribute('class')] = $span->plaintext;
			}

			unset($product['Explicit']);
			unset($product['ADD_TO_CART']);
			unset($product['quantity']);
			unset($product['FR_ID']);
			unset($product['PROXY_TYPE']);
			unset($product['PROXY_ID']);
			unset($product['CONFIGURE_PRODUCT']);
			
			if (isset($product['product_id']))
				$products[$product['product_id']] = $product;
		}

		$this->dbg_log($products);

		return $products;
	}	


	static function dbg_log($obj, $label = '')
	{
		if (!$label)
		{
			$trace = debug_backtrace();
			$label = $trace[0]['file'] . ' [' . $trace[0]['line'] . ']';
		}
	
		error_log($label . ': ' . print_r($obj, true));
	}
}

$test = new test();

?>
