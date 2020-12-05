<?php

   namespace Grayl\Omnipay\Payflow;

   use Grayl\Mixin\Common\Traits\StaticTrait;
   use Grayl\Omnipay\Common\OmnipayPorterAbstract;
   use Grayl\Omnipay\Payflow\Config\PayflowAPIEndpoint;
   use Grayl\Omnipay\Payflow\Config\PayflowConfig;
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
    * @method PayflowGatewayData getSavedGatewayDataEntity ( string $api_endpoint_id )
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
      protected string $config_file = 'omnipay-payflow.php';

      /**
       * The PayflowConfig instance for this gateway
       *
       * @var PayflowConfig
       */
      protected $config;


      /**
       * Creates a new Omnipay ApiGateway object for use in a PayflowGatewayData entity
       *
       * @param PayflowAPIEndpoint $api_endpoint A PayflowAPIEndpoint with credentials needed to create a gateway API object
       *
       * @return ProGateway
       * @throws \Exception
       */
      public function newGatewayAPI ( $api_endpoint ): object
      {

         // Create the Omnipay PayflowGatewayData api entity
         /* @var $api ProGateway */
         $api = Omnipay::create( 'Payflow_Pro' );

         // Set the environment's credentials into the API
         $api->setUsername( $api_endpoint->getUsername() );
         $api->setPassword( $api_endpoint->getPassword() );
         $api->setVendor( $api_endpoint->getVendor() );
         $api->setPartner( $api_endpoint->getPartner() );

         // Return the new instance
         return $api;
      }


      /**
       * Creates a new PayflowGatewayData entity
       *
       * @param string $api_endpoint_id The API endpoint ID to use (typically "default" if there is only one API gateway)
       *
       * @return PayflowGatewayData
       * @throws \Exception
       */
      public function newGatewayDataEntity ( string $api_endpoint_id ): object
      {

         // Grab the gateway service
         $service = new PayflowGatewayService();

         // Get a new API
         $api = $this->newGatewayAPI( $service->getAPIEndpoint( $this->config,
                                                                $this->environment,
                                                                $api_endpoint_id ) );

         // Configure the API as needed using the service
         $service->configureAPI( $api,
                                 $this->environment );

         // Return the gateway
         return new PayflowGatewayData( $api,
                                        $this->config->getGatewayName(),
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
       * @param string $reference_id A reference ID from a previously submitted PayflowAuthorizeRequestController
       *
       * @return PayflowCaptureRequestController
       * @throws \Exception
       */
      public function newPayflowCaptureRequestController ( string $reference_id ): PayflowCaptureRequestController
      {

         // Create the PayflowQueryRequestData entity
         $request_data = new PayflowCaptureRequestData( 'capture',
                                                        $this->getOffsiteURLs() );

         // Set the reference ID
         $request_data->setTransactionReference( $reference_id );

         // Return a new PayflowQueryRequestController entity
         return new PayflowCaptureRequestController( $this->getSavedGatewayDataEntity( 'default' ),
                                                     $request_data,
                                                     new PayflowCaptureRequestService(),
                                                     new PayflowCaptureResponseService() );
      }

   }