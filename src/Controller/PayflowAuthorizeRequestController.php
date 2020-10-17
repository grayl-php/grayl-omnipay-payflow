<?php

namespace Grayl\Omnipay\Payflow\Controller;

use Grayl\Omnipay\Common\Controller\OmnipayRequestControllerAbstract;
use Grayl\Omnipay\Payflow\Entity\PayflowAuthorizeRequestData;
use Grayl\Omnipay\Payflow\Entity\PayflowAuthorizeResponseData;

/**
 * Class PayflowAuthorizeRequestController
 * The controller for working with PayflowAuthorizeRequestData entities
 * @method PayflowAuthorizeRequestData getRequestData()
 * @method PayflowAuthorizeResponseController sendRequest()
 *
 * @package Grayl\Omnipay\Payflow
 */
class PayflowAuthorizeRequestController extends
    OmnipayRequestControllerAbstract
{

    /**
     * Creates a new PayflowAuthorizeResponseController to handle data returned from the gateway
     *
     * @param PayflowAuthorizeResponseData $response_data The PayflowAuthorizeResponseData entity received from the gateway
     *
     * @return PayflowAuthorizeResponseController
     */
    public function newResponseController($response_data): object
    {

        // Return a new PayflowAuthorizeResponseController entity
        return new PayflowAuthorizeResponseController(
            $response_data,
            $this->response_service
        );
    }

}