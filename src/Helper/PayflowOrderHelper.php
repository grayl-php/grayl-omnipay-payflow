<?php

   namespace Grayl\Omnipay\Payflow\Helper;

   use Grayl\Mixin\Common\Traits\StaticTrait;
   use Grayl\Omnipay\Common\Entity\OmnipayGatewayCreditCard;
   use Grayl\Omnipay\Common\Helper\OmnipayOrderHelperCreditCardAbstract;
   use Grayl\Omnipay\Payflow\Controller\PayflowAuthorizeRequestController;
   use Grayl\Omnipay\Payflow\Controller\PayflowCaptureRequestController;
   use Grayl\Omnipay\Payflow\PayflowPorter;
   use Grayl\Store\Order\Controller\OrderController;

   /**
    * A package of functions for working with Payflow and orders
    * These are kept isolated to maintain separation between the main library and specific user functionality
    *
    * @package Grayl\Omnipay\Payflow
    */
   class PayflowOrderHelper extends OmnipayOrderHelperCreditCardAbstract
   {

      // Use the static instance trait
      use StaticTrait;

      /**
       * Creates a new PayflowAuthorizeRequestController for authorizing a payment using data from an order
       *
       * @param OrderController          $order_controller An OrderController entity to translate from
       * @param OmnipayGatewayCreditCard $credit_card      An Omnipay OmnipayGatewayCreditCard entity to use
       *
       * @return PayflowAuthorizeRequestController
       * @throws \Exception
       */
      public function newPayflowAuthorizeRequestControllerFromOrder ( OrderController $order_controller,
                                                                      OmnipayGatewayCreditCard $credit_card ): PayflowAuthorizeRequestController
      {

         // Create a new PayflowAuthorizeRequestController for authorizing a payment
         $request = PayflowPorter::getInstance()
                                 ->newPayflowAuthorizeRequestController();

         // Translate the OrderController and its sub classes
         $this->translateOrderController( $request->getRequestData(),
                                          $order_controller );

         // Translate the credit card information
         PayflowHelper::getInstance()
                      ->translateOmnipayGatewayCreditCard( $request->getRequestData(),
                                                           $credit_card );

         // Return the created entity
         return $request;
      }


      /**
       * Creates a new PayflowCaptureRequestController for capturing a payment using data from an order
       *
       * @param OrderController $order_controller An OrderController entity to translate from
       * @param string          $reference_id     The Omnipay reference ID from a previous authorization
       *
       * @return PayflowCaptureRequestController
       * @throws \Exception
       */
      public function newPayflowCaptureRequestControllerFromOrder ( OrderController $order_controller,
                                                                    string $reference_id ): PayflowCaptureRequestController
      {

         // Create a new PayflowCaptureRequestController for capturing payment
         $request = PayflowPorter::getInstance()
                                 ->newPayflowCaptureRequestController( $reference_id );

         // Translate only the OrderData entity into the capture request, the rest was sent with the authorization
         $this->translateOrderData( $request->getRequestData(),
                                    $order_controller->getOrderData() );

         // Return the created entity
         return $request;
      }

   }