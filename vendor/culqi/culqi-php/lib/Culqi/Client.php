<?php
namespace Culqi;

use Culqi\Error as Errors;

/**
 * Class Client
 *
 * @package Culqi
 */
class Client {
	public function request( $method, $url, $api_key, $data = NULL, $secure_url = false ) {
		try {
			$url_params = is_array($data) ? '?' . http_build_query($data) : '';
			$headers = [
				'Authorization'		=> sprintf( 'Bearer %s', $api_key ),
				'Content-Type'		=> 'application/json',
				'Accept'			=> 'application/json',
				'Accept-Encoding'	=> '*',
			];

			$options = [ 'timeout' => 120 ];

			if( is_array( $data ) && isset( $data['amount'] ) )
				$data['amount'] = strval( $data['amount'] );

			if($method == "GET") {

				update_option('kono_4', print_r($data['enviroment']. $url . $url_params,true));
				update_option('kono_7', print_r($headers,true));
				update_option('kono_8', print_r($options,true));

				$response = \Requests::get($data['enviroment']. $url . $url_params, $headers, $options);

				update_option('kono_9', print_r($response,true));

			} else if($method == "POST") {
				$response = \Requests::post($data['enviroment'] . $url, $headers, json_encode($data), $options);

			} else if($method == "PATCH") {
				$response = \Requests::patch($data['enviroment'] . $url, $headers, json_encode($data), $options);
			} else if($method == "DELETE") {
				$response = \Requests::delete($data['enviroment']. $url . $url_params, $headers, $options);
			}
		} catch (\Exception $e) {
			throw new Errors\UnableToConnect();
		}
		if ($response->status_code >= 200 && $response->status_code <= 206) {
            //echo var_dump($response->status_code);
			return json_decode($response->body);
		}
		if ($response->status_code == 400) {
			throw new Errors\UnhandledError($response->body, $response->status_code);
		}
		if ($response->status_code == 401) {
			throw new Errors\AuthenticationError();
		}
		if ($response->status_code == 404) {
			throw new Errors\NotFound();
		}
		if ($response->status_code == 403) {
			throw new Errors\InvalidApiKey();
		}
		if ($response->status_code == 405) {
			throw new Errors\MethodNotAllowed();
		}
		throw new Errors\UnhandledError($response->body, $response->status_code);
	}
}
