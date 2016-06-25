<?php
/*
 * Copyright 2010 Convio, Inc.
*/
require_once('ConvioOpenAPI.php');

// Base Configuration
$convioAPI = new ConvioOpenAPI;
$convioAPI->host       = 'secure3.convio.net';
$convioAPI->short_name = 'careca';
$convioAPI->api_key    = '';

// Authentication Configuration
$convioAPI->login_name     = '';
$convioAPI->login_password = '';

// Choose Format (If not set, a PHP object will be returned)
$convioAPI->response_format = 'xml';

// Set API Parameters
$params = array('cons_id' => '');

// Make API call (ApiServlet_apiMethod)
$response = $convioAPI->call('CRContentAPI', 'listLinkProviders', $params);

print_r($response);
