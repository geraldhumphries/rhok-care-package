<?php
require_once(__dir__ . '/public_html/bower_components/convio-api-php_library/ConvioOpenAPI.php');
require_once(__dir__ . '/public_html/bower_components/simple_html_dom.php');

class index {

	public function __construct() {
		
		if (!isset($_POST['action']))
			$_POST['action'] = 'getProductList';

		switch ($_POST['action']) {
			case 'getProductList': return $this->parseProductHTML();
		}
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
	protected function parseProductHTML() {
		$html = file_get_html('https://secure3.convio.net/careca/site/Ecommerce?VIEW_CATALOG=true&store_id=1461&NAME=&FOLDER=0');

		$products = array();

		foreach($html->find('form') as $form) {
			
			$product = array();
			foreach($form->find('input') as $input)
				$product[$input->getAttribute('name')] = $input->getAttribute('value');

			foreach ($form->find('img') as $img)
				$product['img'] = $this->str_replace_first('..', 
				'https://secure3.convio.net/careca', $img->getAttribute('src'));

			foreach($form->find('span') as $span)
				$product[$span->getAttribute('class')] = $span->plaintext;

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
		
		return json_encode($products);
	}
	static function str_replace_first($from, $to, $subject)
	{
	    $from = '/'.preg_quote($from, '/').'/';

	    return preg_replace($from, $to, $subject, 1);
	}
}

$index = new index();

?>
