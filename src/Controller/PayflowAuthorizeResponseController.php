<?php

namespace Grayl\Omnipay\Payflow\Controller;

use Grayl\Gateway\Common\Entity\ResponseDataAbstract;
use Grayl\Gateway\Common\Service\ResponseServiceInterface;
use Grayl\Omnipay\Common\Controller\OmnipayResponseControllerAbstract;
use Grayl\Omnipay\Payflow\Entity\PayflowAuthorizeResponseData;
use Grayl\Omnipay\Payflow\Service\PayflowAuthorizeResponseService;

/**
 * Class PayflowAuthorizeResponseController
 * The controller for working with PayflowAuthorizeResponseData entities
 *
 * @package Grayl\Omnipay\Payflow
 */
class PayflowAuthorizeResponseController extends
    OmnipayResponseControllerAbstract
{

    /**
     * The PayflowAuthorizeResponseData object that holds the gateway API response
     *
     * @var PayflowAuthorizeResponseData
     */
    protected ResponseDataAbstract $response_data;

    /**
     * The PayflowAuthorizeResponseService entity to use
     *
     * @var PayflowAuthorizeResponseService
     */
    protected ResponseServiceInterface $response_service;

}