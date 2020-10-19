<?php

   namespace Grayl\Omnipay\Payflow;

   use Grayl\Mixin\Common\Traits\StaticTrait;
   use Grayl\Omnipay\Common\OmnipayPorterAbstract;
   use Grayl\Omnipay\Payflow\Controller\PayflowAuthorizeRequestController;
   use Grayl\Omnipay\Payflow\Controller\PayflowCaptureRequestController;
   use Grayl\Omnipay\Payflow\Entity\PayflowAuthorizeRequestData;
   use Grayl\Omnipay\Payflow\Entity\PayflowCaptureRequestData;
   use Grayl\Omnipay\Payflow\Entity\PayflowGatewayData;
   use Grayl\Omnipay\Payflow\Service\PayflowAuthorizeRequestService;
   use Grayl\Omnipay\Payflow\Service\PayflowAuthorizeResponseService;
   use Grayl\Omnipay\Payflow\Service\PayflowCaptureRequestService;
   use Grayl\Omnipay\Payflow\Service\PayflowCaptureResponseService;
   use Grayl\Omnipay\Payflow\Service\PayflowGatewayService;
   use Omnipay\Omnipay;
   use Omnipay\Payflow\ProGateway;

   /**
    * Front-end for the Payflow Omnipay package
    * @method PayflowGatewayData getSavedGatewayDataEntity ( string $endpoint_id )
    *
    * @package Grayl\Omnipay\Payflow
    */
   class PayflowPorter extends OmnipayPorterAbstract
   {

      // Use the static instance trait
      use StaticTrait;

      /**
       * The name of the config file for the Payflow package
       *
       * @var string
       */
      protected string $config_file = 'gateway.omnipay.payflow.php';


      /**
       * Creates a new Omnipay ApiGateway object for use in a PayflowGatewayData entity
       *
       * @param array $credentials An array containing all of the credentials needed to create the gateway API
       *
       * @return ProGateway
       * @throws \Exception
       */
      public function newGatewayAPI ( array $credentials ): object
      {

         // Create the Omnipay PayflowGatewayData api entity
         /* @var $api ProGateway */
         $api = Omnipay::create( 'Payflow_Pro' );

         // Set the environment's credentials into the API
         $api->setUsername( $credentials[ 'username' ] );
         $api->setPassword( $credentials[ 'password' ] );
         $api->setVendor( $credentials[ 'vendor' ] );
         $api->setPartner( $credentials[ 'partner' ] );

         // Return the new instance
         return $api;
      }


      /**
       * Creates a new PayflowGatewayData entity
       *
       * @param string $endpoint_id The API endpoint ID to use (typically "default" is there is only one API gateway)
       *
       * @return PayflowGatewayData
       * @throws \Exception
       */
      public function newGatewayDataEntity ( string $endpoint_id ): object
      {

         // Grab the gateway service
         $service = new PayflowGatewayService();

         // Get an API
         $api = $this->newGatewayAPI( $service->getAPICredentials( $this->config,
                                                                   $this->environment,
                                                                   $endpoint_id ) );

         // Configure the API as needed using the service
         $service->configureAPI( $api,
                                 $this->environment );

         // Return the gateway
         return new PayflowGatewayData( $api,
                                        $this->config->getConfig( 'name' ),
                                        $this->environment );
      }


      /**
       * Creates a new PayflowAuthorizeRequestController entity
       *
       * @return PayflowAuthorizeRequestController
       * @throws \Exception
       */
      public function newPayflowAuthorizeRequestController (): PayflowAuthorizeRequestController
      {

         // Create the PayflowQueryRequestData entity
         $request_data = new PayflowAuthorizeRequestData( 'authorize',
                                                          $this->getOffsiteURLs() );

         // Return a new PayflowQueryRequestController entity
         return new PayflowAuthorizeRequestController( $this->getSavedGatewayDataEntity( 'default' ),
                                                       $request_data,
                                                       new PayflowAuthorizeRequestService(),
                                                       new PayflowAuthorizeResponseService() );
      }


      /**
       * Creates a new PayflowCaptureRequestController entity
       *
       * @return PayflowCaptureRequestController
       * @throws \Exception
       */
      public function newPayflowCaptureRequestController (): PayflowCaptureRequestController
      {

         // Create the PayflowQueryRequestData entity
         $request_data = new PayflowCaptureRequestData( 'capture',
                                                        $this->getOffsiteURLs() );

         // Return a new PayflowQueryRequestController entity
         return new PayflowCaptureRequestController( $this->getSavedGatewayDataEntity( 'default' ),
                                                     $request_data,
                                                     new PayflowCaptureRequestService(),
                                                     new PayflowCaptureResponseService() );
      }

   }