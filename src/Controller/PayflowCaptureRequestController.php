<?php

namespace Grayl\Omnipay\Payflow\Controller;

use Grayl\Omnipay\Common\Controller\OmnipayRequestControllerAbstract;
use Grayl\Omnipay\Payflow\Entity\PayflowCaptureRequestData;
use Grayl\Omnipay\Payflow\Entity\PayflowCaptureResponseData;

/**
 * Class PayflowCaptureRequestController
 * The controller for working with PayflowCaptureRequestData entities
 * @method PayflowCaptureRequestData getRequestData()
 * @method PayflowCaptureResponseController sendRequest()
 *
 * @package Grayl\Omnipay\Payflow
 */
class PayflowCaptureRequestController extends
    OmnipayRequestControllerAbstract
{

    /**
     * Creates a new PayflowCaptureResponseController to handle data returned from the gateway
     *
     * @param PayflowCaptureResponseData $response_data The PayflowCaptureResponseData entity received from the gateway
     *
     * @return PayflowCaptureResponseController
     */
    public function newResponseController($response_data): object
    {

        // Return a new PayflowCaptureResponseController entity
        return new PayflowCaptureResponseController(
            $response_data,
            $this->response_service
        );
    }

}