<?php

   namespace Grayl\Test\Omnipay\Payflow;

   use Grayl\Gateway\PDO\PDOPorter;
   use Grayl\Omnipay\Common\Entity\OmnipayGatewayCreditCard;
   use Grayl\Omnipay\Payflow\Controller\PayflowAuthorizeRequestController;
   use Grayl\Omnipay\Payflow\Controller\PayflowAuthorizeResponseController;
   use Grayl\Omnipay\Payflow\Controller\PayflowCaptureRequestController;
   use Grayl\Omnipay\Payflow\Controller\PayflowCaptureResponseController;
   use Grayl\Omnipay\Payflow\Entity\PayflowGatewayData;
   use Grayl\Omnipay\Payflow\Helper\PayflowOrderHelper;
   use Grayl\Omnipay\Payflow\PayflowPorter;
   use Grayl\Store\Order\Controller\OrderController;
   use Grayl\Store\Order\OrderPorter;
   use PHPUnit\Framework\TestCase;

   /**
    * Test class for the Payflow package using an order
    *
    * @package Grayl\Omnipay\Payflow
    */
   class PayflowAuthorizeRequestControllerTestFromOrder extends TestCase
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
       * Creates a test order object with good data
       * TODO: Change this test data
       *
       * @return OrderController
       * @throws \Exception
       */
      public function testCreateOrderController (): OrderController
      {

         // Create the test object
         $order = OrderPorter::getInstance()
                             ->newOrderController();

         // Check the type of object returned
         $this->assertInstanceOf( OrderController::class,
                                  $order );

         // Basic order data
         $data = $order->getOrderData();
         $data->setAmount( 100.00 );
         $data->setDescription( 'Payflow test order' );

         // Customer creation
         $order->setOrderCustomer( OrderPorter::getInstance()
                                              ->newOrderCustomer( $order->getOrderID(),
                                                                  'Jane',
                                                                  'Doe',
                                                                  'janedoe@fake.com',
                                                                  '1234 Fake Rd.',
                                                                  '#3307',
                                                                  'Las Vegas',
                                                                  '89131',
                                                                  'NV',
                                                                  'US',
                                                                  null ) );

         // Items
         $order->putOrderItem( OrderPorter::getInstance()
                                          ->newOrderItem( $order->getOrderID(),
                                                          'item1',
                                                          'Testing Item',
                                                          '2',
                                                          22.98 ) );
         $order->putOrderItem( OrderPorter::getInstance()
                                          ->newOrderItem( $order->getOrderID(),
                                                          'item2',
                                                          'Testing Item 2',
                                                          '3',
                                                          19.49 ) );
         $order->putOrderItem( OrderPorter::getInstance()
                                          ->newOrderItem( $order->getOrderID(),
                                                          'item3',
                                                          'Testing Item 3',
                                                          '1',
                                                          9.99 ) );

         // Save the order
         $order->saveOrder();

         // Return the object
         return $order;
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
       * Tests the creation of a PayflowAuthorizeRequestController object from an order
       *
       * @param OrderController          $order_controller A configured OrderController with order information
       * @param OmnipayGatewayCreditCard $credit_card      A configured OmnipayGatewayCreditCard entity with payment information
       *
       * @depends testCreateOrderController
       * @depends testCreateOmnipayGatewayCreditCard
       * @return PayflowAuthorizeRequestController
       * @throws \Exception
       */
      public function testCreatePayflowAuthorizeRequestControllerFromOrder ( OrderController $order_controller,
                                                                             OmnipayGatewayCreditCard $credit_card ): PayflowAuthorizeRequestController
      {

         // Create the object
         $request = PayflowOrderHelper::getInstance()
                                      ->newPayflowAuthorizeRequestControllerFromOrder( $order_controller,
                                                                                       $credit_card );

         // Check the type of object returned
         $this->assertInstanceOf( PayflowAuthorizeRequestController::class,
                                  $request );

         // Check the total
         $this->assertEquals( 114.42,
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
       * @depends      testCreatePayflowAuthorizeRequestControllerFromOrder
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
       * @return string
       */
      public function testPayflowAuthorizeResponseController ( PayflowAuthorizeResponseController $response ): string
      {

         // Make sure it worked
         $this->assertTrue( $response->isSuccessful() );
         $this->assertFalse( $response->isPending() );
         $this->assertNotNull( $response->getReferenceID() );
         $this->assertNotNull( $response->getAVSCode() );
         $this->assertNotNull( $response->getCVVCode() );
         $this->assertNotNull( $response->getAmount() );

         // Return the reference ID
         return $response->getReferenceID();
      }


      /**
       * Creates an OrderPayment object from a PayflowAuthorizeResponseController and checks it for errors
       *
       * @param OrderController                    $order_controller A configured OrderController with order information
       * @param PayflowAuthorizeResponseController $response         A PayflowAuthorizeResponseController returned from the gateway
       *
       * @depends      testCreateOrderController
       * @depends      testSendPayflowAuthorizeRequestController
       * @return OrderController
       * @throws \Exception
       */
      public function testCreateOrderPaymentFromPayflowAuthorizeResponseController ( OrderController $order_controller,
                                                                                     PayflowAuthorizeResponseController $response ): OrderController
      {

         // Create a new OrderPayment record from the authorize response
         PayflowOrderHelper::getInstance()
                           ->newOrderPaymentFromOmnipayResponseController( $response,
                                                                           $order_controller,
                                                                           null );

         // Grab the created payment
         $payment = $order_controller->getOrderPayment();

         // Test the data
         $this->assertTrue( $payment->isSuccessful() );
         $this->assertNotNull( $payment->getReferenceID() );
         $this->assertEquals( $response->getReferenceID(),
                              $payment->getReferenceID() );
         $this->assertEquals( 'authorize',
                              $payment->getAction() );
         $this->assertEquals( 'payflow',
                              $payment->getProcessor() );
         $this->assertEquals( $response->getAmount(),
                              $payment->getAmount() );

         // Return the modified order
         return $order_controller;
      }


      /**
       * Tests the creation of a PayflowCaptureRequestController object from an order
       *
       * @param OrderController $order_controller A configured OrderController with order information
       * @param string          $reference_id     The Omnipay reference ID from a previous authorization
       *
       * @depends testCreateOrderController
       * @depends testPayflowAuthorizeResponseController
       * @return PayflowCaptureRequestController
       * @throws \Exception
       */
      public function testCreatePayflowCaptureRequestControllerFromOrder ( OrderController $order_controller,
                                                                           string $reference_id ): PayflowCaptureRequestController
      {

         // Create the object
         $request = PayflowOrderHelper::getInstance()
                                      ->newPayflowCaptureRequestControllerFromOrder( $order_controller,
                                                                                     $reference_id );

         // Check the type of object returned
         $this->assertInstanceOf( PayflowCaptureRequestController::class,
                                  $request );

         // Check the total
         $this->assertEquals( 114.42,
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
       * @depends      testCreatePayflowCaptureRequestControllerFromOrder
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


      /**
       * Creates an OrderPayment from a PayflowCaptureResponseController capture request and checks it for errors
       *
       * @param OrderController                  $order_controller A configured OrderController with order information
       * @param PayflowCaptureResponseController $response         A PayflowCaptureResponseController returned from the gateway
       *
       * @depends      testCreateOrderPaymentFromPayflowAuthorizeResponseController
       * @depends      testSendPayflowCaptureRequestController
       * @throws \Exception
       */
      public function testCreateOrderPaymentFromCaptureResponseController ( OrderController $order_controller,
                                                                            PayflowCaptureResponseController $response ): void
      {

         // Create a new OrderPayment record from the capture response
         PayflowOrderHelper::getInstance()
                           ->newOrderPaymentFromOmnipayResponseController( $response,
                                                                           $order_controller,
                                                                           null );

         // Make sure the order is paid
         $this->assertTrue( $order_controller->isOrderPaid() );

         // Grab the newly created payment
         $payment = $order_controller->getOrderPayment();

         // Test the data
         $this->assertTrue( $payment->isSuccessful() );
         $this->assertNotNull( $payment->getReferenceID() );
         $this->assertEquals( $response->getReferenceID(),
                              $payment->getReferenceID() );
         $this->assertEquals( 'capture',
                              $payment->getAction() );
         $this->assertEquals( 'payflow',
                              $payment->getProcessor() );
         $this->assertEquals( $response->getAmount(),
                              $payment->getAmount() );
      }

   }