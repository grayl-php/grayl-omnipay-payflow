<?php

   namespace Grayl\Omnipay\Payflow\Service;

   use Grayl\Omnipay\Common\Service\OmnipayRequestServiceAbstract;
   use Grayl\Omnipay\Payflow\Entity\PayflowAuthorizeRequestData;
   use Grayl\Omnipay\Payflow\Entity\PayflowAuthorizeResponseData;
   use Grayl\Omnipay\Payflow\Entity\PayflowGatewayData;
   use Omnipay\Payflow\Message\Response;

   /**
    * Class PayflowAuthorizeRequestService
    * The service for working with the Payflow authorize requests
    *
    * @package Grayl\Omnipay\Payflow
    */
   class PayflowAuthorizeRequestService extends OmnipayRequestServiceAbstract
   {

      /**
       * Sends a PayflowAuthorizeRequestData object to the Payflow gateway and returns a response
       *
       * @param PayflowGatewayData          $gateway_data A configured PayflowGatewayData entity to send the request through
       * @param PayflowAuthorizeRequestData $request_data The PayflowAuthorizeRequestData entity to send
       *
       * @return PayflowAuthorizeResponseData
       * @throws \Exception
       */
      public function sendRequestDataEntity ( $gateway_data,
                                              $request_data ): object
      {

         // Use the abstract class function to send the authorize request and return a response
         return $this->sendAuthorizeRequestData( $gateway_data,
                                                 $request_data );
      }


      /**
       * Creates a new PayflowAuthorizeResponseData object to handle data returned from the gateway
       *
       * @param Response $api_response The response entity received from a gateway
       * @param string   $gateway_name The name of the gateway
       * @param string   $action       The action performed in this response (authorize, capture, etc.)
       * @param string[] $metadata     Extra data associated with this response
       *
       * @return PayflowAuthorizeResponseData
       */
      public function newResponseDataEntity ( $api_response,
                                              string $gateway_name,
                                              string $action,
                                              array $metadata ): object
      {

         // Return a new PayflowAuthorizeResponseData entity
         return new PayflowAuthorizeResponseData( $api_response,
                                                  $gateway_name,
                                                  $action,
                                                  $metadata[ 'amount' ] );
      }

   }