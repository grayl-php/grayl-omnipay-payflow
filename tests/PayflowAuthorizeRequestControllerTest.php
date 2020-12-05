<?php

   namespace Grayl\Test\Omnipay\Payflow;

   use Grayl\Gateway\PDO\PDOPorter;
   use Grayl\Omnipay\Common\Entity\OmnipayGatewayCreditCard;
   use Grayl\Omnipay\Payflow\Controller\PayflowAuthorizeRequestController;
   use Grayl\Omnipay\Payflow\Controller\PayflowAuthorizeResponseController;
   use Grayl\Omnipay\Payflow\Controller\PayflowCaptureRequestController;
   use Grayl\Omnipay\Payflow\Controller\PayflowCaptureResponseController;
   use Grayl\Omnipay\Payflow\Entity\PayflowGatewayData;
   use Grayl\Omnipay\Payflow\PayflowPorter;
   use PHPUnit\Framework\TestCase;

   /**
    * Test class for the Payflow package
    *
    * @package Grayl\Omnipay\Payflow
    */
   class PayflowAuthorizeRequestControllerTest extends TestCase
   {

      /**
       * Test setup for sandbox environment
       */
      public static function setUpBeforeClass (): void
      {

         // Change the PDO API to sandbox mode
         PDOPorter::getInstance()
                  ->setEnvironment( 'sandbox' );

         // Change the API environment to sandbox mode
         PayflowPorter::getInstance()
                      ->setEnvironment( 'sandbox' );
      }


      /**
       * Tests the creation of a PayflowGatewayData
       *
       * @throws \Exception
       */
      public function testCreatePayflowGatewayData (): void
      {

         // Create the object
         $gateway = PayflowPorter::getInstance()
                                 ->getSavedGatewayDataEntity( 'default' );

         // Check the type of object returned
         $this->assertInstanceOf( PayflowGatewayData::class,
                                  $gateway );
      }


      /**
       * Creates a test OmnipayGatewayCreditCard object to be used in a test
       *
       * @return OmnipayGatewayCreditCard
       */
      public function testCreateOmnipayGatewayCreditCard (): OmnipayGatewayCreditCard
      {

         // Create the OmnipayGatewayCreditCard
         $credit_card = PayflowPorter::getInstance()
                                     ->newOmnipayGatewayCreditCard( '4111111111111111',
                                                                    12,
                                                                    2025,
                                                                    '869' );

         // Check the type of object returned
         $this->assertInstanceOf( OmnipayGatewayCreditCard::class,
                                  $credit_card );

         // Return the object
         return $credit_card;
      }


      /**
       * Tests the creation of a PayflowAuthorizeRequestController object
       *
       * @param OmnipayGatewayCreditCard $credit_card A configured OmnipayGatewayCreditCard entity with payment information
       *
       * @depends testCreateOmnipayGatewayCreditCard
       * @return PayflowAuthorizeRequestController
       * @throws \Exception
       */
      public function testCreatePayflowAuthorizeRequestController ( OmnipayGatewayCreditCard $credit_card ): PayflowAuthorizeRequestController
      {

         // Create the object
         $request = PayflowPorter::getInstance()
                                 ->newPayflowAuthorizeRequestController( 'test-' . time(),
                                                                         88.00,
                                                                         'USD',
                                                                         $_SERVER[ 'REMOTE_ADDR' ],
                                                                         'Jake',
                                                                         'Doe',
                                                                         'jakedoe@fake.com',
                                                                         '1234 Fake Rd.',
                                                                         null,
                                                                         'Las Vegas',
                                                                         'NV',
                                                                         89131,
                                                                         'US',
                                                                         null,
                                                                         $credit_card->getNumber(),
                                                                         $credit_card->getExpiryMonth(),
                                                                         $credit_card->getExpiryYear(),
                                                                         $credit_card->getCVV() );

         // Check the type of object returned
         $this->assertInstanceOf( PayflowAuthorizeRequestController::class,
                                  $request );

         // Check the total
         $this->assertEquals( 88.00,
                              $request->getRequestData()
                                      ->getAmount() );

         // Return the object
         return $request;
      }


      /**
       * Performs an authorization using a request
       *
       * @param PayflowAuthorizeRequestController $request A configured PayflowAuthorizeRequestController to send
       *
       * @depends      testCreatePayflowAuthorizeRequestController
       * @return PayflowAuthorizeResponseController
       * @throws \Exception
       */
      public function testSendPayflowAuthorizeRequestController ( PayflowAuthorizeRequestController $request ): PayflowAuthorizeResponseController
      {

         // Authorize the payment
         $response = $request->sendRequest();

         // Check the type of object returned
         $this->assertInstanceOf( PayflowAuthorizeResponseController::class,
                                  $response );

         // Return the response
         return $response;
      }


      /**
       * Checks a PayflowAuthorizeResponseController for data and errors
       *
       * @param PayflowAuthorizeResponseController $response A PayflowAuthorizeResponseController returned from the gateway
       *
       * @depends testSendPayflowAuthorizeRequestController
       */
      public function testPayflowAuthorizeResponseController ( PayflowAuthorizeResponseController $response ): void
      {

         // Make sure it worked
         $this->assertTrue( $response->isSuccessful() );
         $this->assertFalse( $response->isPending() );
         $this->assertNotNull( $response->getReferenceID() );
         $this->assertNotNull( $response->getAVSCode() );
         $this->assertNotNull( $response->getCVVCode() );
         $this->assertNotNull( $response->getAmount() );

      }


      /**
       * Tests the creation of a PayflowCaptureRequestController object
       *
       * @param PayflowAuthorizeResponseController $auth_response A PayflowAuthorizeResponseController returned from the gateway
       *
       * @depends testSendPayflowAuthorizeRequestController
       * @return PayflowCaptureRequestController
       * @throws \Exception
       */
      public function testCreatePayflowCaptureRequestController ( PayflowAuthorizeResponseController $auth_response ): PayflowCaptureRequestController
      {

         // Create the object
         $request = PayflowPorter::getInstance()
                                 ->newPayflowCaptureRequestController( 'test-' . time(),
                                                                       88.00,
                                                                       'USD',
                                                                       $_SERVER[ 'REMOTE_ADDR' ],
                                                                       $auth_response->getReferenceID() );

         // Check the type of object returned
         $this->assertInstanceOf( PayflowCaptureRequestController::class,
                                  $request );

         // Check the total
         $this->assertEquals( 88.00,
                              $request->getRequestData()
                                      ->getAmount() );

         // Return the object
         return $request;
      }


      /**
       * Performs a capture using a request
       *
       * @param PayflowCaptureRequestController $request A configured PayflowAuthorizeRequestData to send
       *
       * @depends      testCreatePayflowCaptureRequestController
       * @return PayflowCaptureResponseController
       * @throws \Exception
       */
      public function testSendPayflowCaptureRequestController ( PayflowCaptureRequestController $request ): PayflowCaptureResponseController
      {

         // Capture the payment
         $response = $request->sendRequest();

         // Check the type of object returned
         $this->assertInstanceOf( PayflowCaptureResponseController::class,
                                  $response );

         // Return the response
         return $response;
      }


      /**
       * Checks a PayflowCaptureResponseController for data and errors
       *
       * @param PayflowCaptureResponseController $response A PayflowCaptureResponseController returned from the gateway
       *
       * @depends testSendPayflowCaptureRequestController
       */
      public function testPayflowCaptureResponseController ( PayflowCaptureResponseController $response ): void
      {

         // Make sure it worked
         $this->assertTrue( $response->isSuccessful() );
         $this->assertFalse( $response->isPending() );
         $this->assertNotNull( $response->getReferenceID() );
      }

   }