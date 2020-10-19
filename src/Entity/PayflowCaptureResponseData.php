<?php

   namespace Grayl\Omnipay\Payflow\Entity;

   use Grayl\Omnipay\Common\Entity\OmnipayResponseDataAbstract;
   use Omnipay\Payflow\Message\Response;

   /**
    * Class PayflowCaptureResponseData
    * The class for working with a capture response from a PayflowGatewayData
    * @method void __construct( Response $api_response, string $gateway_name, string $action, float $amount )
    * @method void setAPIResponse( Response $api_response )
    * @method Response getAPIResponse()
    *
    * @package Grayl\Omnipay\Payflow
    */
   class PayflowCaptureResponseData extends OmnipayResponseDataAbstract
   {

      /**
       * The raw API response entity from the gateway
       *
       * @var Response
       */
      protected $api_response;

   }