<?php

   namespace Grayl\Omnipay\Payflow\Controller;

   use Grayl\Gateway\Common\Entity\ResponseDataAbstract;
   use Grayl\Gateway\Common\Service\ResponseServiceInterface;
   use Grayl\Omnipay\Common\Controller\OmnipayResponseControllerAbstract;
   use Grayl\Omnipay\Payflow\Entity\PayflowCaptureResponseData;
   use Grayl\Omnipay\Payflow\Service\PayflowCaptureResponseService;

   /**
    * Class PayflowCaptureResponseController
    * The controller for working with PayflowCaptureResponseData entities
    *
    * @package Grayl\Omnipay\Payflow
    */
   class PayflowCaptureResponseController extends OmnipayResponseControllerAbstract
   {

      /**
       * The PayflowCaptureResponseData object that holds the gateway API response
       *
       * @var PayflowCaptureResponseData
       */
      protected ResponseDataAbstract $response_data;

      /**
       * The PayflowCaptureResponseService entity to use
       *
       * @var PayflowCaptureResponseService
       */
      protected ResponseServiceInterface $response_service;

   }