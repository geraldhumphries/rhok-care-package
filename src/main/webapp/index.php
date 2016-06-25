<?php
require_once(__dir__ . '/public_html/bower_components/convio-api-php_library/ConvioOpenAPI.php');
require_once(__dir__ . '/public_html/bower_components/simple_html_dom.php');

class index {

	public function __construct() {
		return json_encode($this->parseHTML());
	}

	/**
	 * Convio API does not have eCommerce support
	 * API Key and Authentication content has been removed for security.
	 * @return string
	 */
	protected function grabProducts() {
		// Base Configuration
		$convioAPI = new ConvioOpenAPI;
		$convioAPI->host       = 'secure3.convio.net';
		$convioAPI->short_name = 'careca';
		$convioAPI->api_key    = '';

		// Authentication Configuration
		$convioAPI->login_name     = '';
		$convioAPI->login_password = '';

		// Choose Format (If not set, a PHP object will be returned)
		$convioAPI->response_format = 'json';

		// Set API Parameters
		$params = array('VIEW_CATALOG' => true, 'store_id' => 1461);

		// Make API call (ApiServlet_apiMethod)
		return $convioAPI->call('Ecommerce', 'VIEW_CATALOG', $params);
	}

	/**
	 * Grabs content from convio ecommerce website and parse products out.
	 * @return array
	 */
	protected function parseHTML() {
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

		return $products;
	}
}

$index = new index();

?>