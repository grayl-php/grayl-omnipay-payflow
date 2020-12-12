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
       * @param string  $transaction_id     The internal transaction ID
       * @param float   $amount             The amount to charge
       * @param string  $currency           The base currency of the amount
       * @param string  $ip_address         The IP of the client
       * @param string  $first_name         The  first name
       * @param string  $last_name          The  last name
       * @param string  $email_address      The email address
       * @param string  $address_1          The address part 1
       * @param ?string $address_2          The address part 2
       * @param string  $city               The city
       * @param string  $state              The state
       * @param string  $postcode           The postcode
       * @param string  $country            The country
       * @param ?string $phone              The phone
       * @param string  $credit_card_number The credit card number
       * @param string  $expiry_month       The credit card expiry month (2 digit)
       * @param string  $expiry_year        The credit card expiry year (4 digit)
       * @param string  $cvv                The credit card CVV
       *
       * @return PayflowAuthorizeRequestController
       * @throws \Exception
       */
      public function newPayflowAuthorizeRequestController ( string $transaction_id,
                                                             float $amount,
                                                             string $currency,
                                                             string $ip_address,
                                                             string $first_name,
                                                             string $last_name,
                                                             string $email_address,
                                                             string $address_1,
                                                             ?string $address_2,
                                                             string $city,
                                                             string $state,
                                                             string $postcode,
                                                             string $country,
                                                             ?string $phone,
                                                             string $credit_card_number,
                                                             string $expiry_month,
                                                             string $expiry_year,
                                                             string $cvv ): PayflowAuthorizeRequestController
      {

         // Create the PayflowQueryRequestData entity
         $request_data = new PayflowAuthorizeRequestData( 'authorize',
                                                          $this->getOffsiteURLs() );

         // Set the order parameters
         $request_data->setTransactionID( $transaction_id );
         $request_data->setAmount( $amount );
         $request_data->setCurrency( $currency );
         $request_data->setClientIP( $ip_address );

         // Set the customer parameters
         $request_data->setFirstName( $first_name );
         $request_data->setLastName( $last_name );
         $request_data->setEmail( $email_address );
         $request_data->setAddress1( $address_1 );
         $request_data->setAddress2( $address_2 );
         $request_data->setCity( $city );
         $request_data->setState( $state );
         $request_data->setPostcode( $postcode );
         $request_data->setCountry( $country );
         $request_data->setPhone( $phone );

         // Set the credit card parameters
         $request_data->setCreditCardNumber( $credit_card_number );
         $request_data->setCreditCardExpiryMonth( $expiry_month );
         $request_data->setCreditCardExpiryYear( $expiry_year );
         $request_data->setCreditCardCVV( $cvv );

         // Return a new PayflowQueryRequestController entity
         return new PayflowAuthorizeRequestController( $this->getSavedGatewayDataEntity( 'default' ),
                                                       $request_data,
                                                       new PayflowAuthorizeRequestService(),
                                                       new PayflowAuthorizeResponseService() );
      }


      /**
       * Creates a new PayflowCaptureRequestController entity
       *
       * @param string $transaction_id The internal transaction ID
       * @param float  $amount         The amount to charge
       * @param string $currency       The base currency of the amount
       * @param string $ip_address     The IP of the client
       * @param string $reference_id   A reference ID from a previously submitted PayflowAuthorizeRequestController
       *
       * @return PayflowCaptureRequestController
       * @throws \Exception
       */
      public function newPayflowCaptureRequestController ( string $transaction_id,
                                                           float $amount,
                                                           string $currency,
                                                           string $ip_address,
                                                           string $reference_id ): PayflowCaptureRequestController
      {

         // Create the PayflowQueryRequestData entity
         $request_data = new PayflowCaptureRequestData( 'capture',
                                                        $this->getOffsiteURLs() );

         // Set the order parameters
         $request_data->setTransactionID( $transaction_id );
         $request_data->setAmount( $amount );
         $request_data->setCurrency( $currency );
         $request_data->setClientIP( $ip_address );
         $request_data->setTransactionReference( $reference_id );

         // Return a new PayflowQueryRequestController entity
         return new PayflowCaptureRequestController( $this->getSavedGatewayDataEntity( 'default' ),
                                                     $request_data,
                                                     new PayflowCaptureRequestService(),
                                                     new PayflowCaptureResponseService() );
      }

   }