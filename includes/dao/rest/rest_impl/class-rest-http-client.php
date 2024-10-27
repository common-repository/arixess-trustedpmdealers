<?php

namespace TrustedPMDealers;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Rest_HTTP_Client extends Abstract_Dao {
    /**
     * HTTP response status; will contain associative array representing
     * the HTTP version, status code, and reason phrase.
     */
    private $response_status = null;

    /**
     * HTTP response header; will contain associative array of header
     * attributes returned from the cURL request.
     */
    private $response_header = null;

    /**
     * HTTP response body; will contain a string representing the body
     * of the response returned from the cURL request.
     */
    private $response_body = null;

    function makeRequest($url, $request_method, $request_body = null, $request_header = null ) {
        $this->response_header = null;
        $this->response_body = null;

        if ( $this->is_curl() ) {
            $ch = curl_init();
        }
        if ( ! $ch ) {
            throw new \Exception( 'cURL not init' );
        }

        curl_setopt( $ch, CURLOPT_URL, $url );
        if ( $request_header !== null ) {
            curl_setopt( $ch, CURLOPT_HTTPHEADER, $request_header );
        }

        curl_setopt( $ch, CURLOPT_HEADERFUNCTION, array( $this, 'handle_response_header' ) );
        curl_setopt( $ch, CURLOPT_WRITEFUNCTION, array( $this, 'handle_response_body' ) );

        // Additional options need to be set for PUT and POST requests.
        if ( $request_method == 'POST' ) {
            curl_setopt( $ch, CURLOPT_POST, true );
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $request_body );
        } else if ( $request_method == 'PUT' ) {
            curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'PUT' );
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $request_body );
        } else if ( $request_method == 'DELETE' ) {
            curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'DELETE' );
        }
        $response = curl_exec($ch);
        curl_close($ch);

        return array(
            'status'    =>  $this->response_status,
            'header'    =>  $this->response_header,
            'body'      =>  $this->response_body,
        );
    }

    private function is_curl() {
        if ( function_exists('curl_version') )
          return true;
        else
          throw new \Exception( 'cURL not defined' );
    }
    /**
     * Process an incoming response header following a cURL request and
     * store the header in $this->responseHeader.
     *
     * @param Object $ch: The cURL handler instance.
     * @param String $header_data: The header to handle; expects header to come in one line at a time.
     * @return Int: The length of the input data.
     */
    private function handle_response_header( $ch, $header_data ) {
        // If we haven't found the HTTP status yet, then try to match it.
        trpmd_log('RESPONSE STATUS');
        trpmd_log($this->response_status);
        if ( $this->response_status == null ) {
            $regex = '/^\s*HTTP\s*\/\s*(?P<protocolVersion>\d*\.\d*)\s*(?P<statusCode>\d*)\s(?P<reasonPhrase>.*)\r\n/';
            preg_match( $regex , $header_data, $matches );
            foreach ( array( 'protocolVersion', 'statusCode', 'reasonPhrase' ) as $part ) {
                if ( isset( $matches[ $part ] ) ) {
                    $this->response_status[ $part ] = $matches[ $part ];
                }
            }
        }

        // Digest HTTP header attributes.
        if ( ! isset( $responseStatusMatches ) || empty( $responseStatusMatches ) ) {
            $regex = '/^\s*(?P<attributeName>[a-zA-Z0-9-]*):\s*(?P<attributeValue>.*)\r\n/';
            preg_match( $regex, $header_data, $matches );

            if ( isset( $matches['attributeName'] ) ) {
                $this->response_header[ $matches['attributeName'] ] = isset( $matches['attributeValue'] ) ? $matches['attributeValue'] : null;
            }
        }
        return strlen( $header_data );
    }

    /**
     * Process an incoming response body following a cURL request
     * and store the body in $this->responseBody.
     *
     * @param Object $ch: The cURL handler instance.
     * @param String $bodyData: The body data to handle.
     * @return Int: The length of the input data.
     */
    private function handle_response_body($ch, $bodyData ) {
        $this->response_body .= $bodyData;
        return strlen( $bodyData );
    }
}