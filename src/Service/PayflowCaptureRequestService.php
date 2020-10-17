<?php

namespace Grayl\Omnipay\Payflow\Service;

use Grayl\Omnipay\Common\Service\OmnipayRequestServiceAbstract;
use Grayl\Omnipay\Payflow\Entity\PayflowCaptureRequestData;
use Grayl\Omnipay\Payflow\Entity\PayflowCaptureResponseData;
use Grayl\Omnipay\Payflow\Entity\PayflowGatewayData;
use Omnipay\Payflow\Message\Response;

/**
 * Class PayflowCaptureRequestService
 * The service for working with the Payflow capture requests
 *
 * @package Grayl\Omnipay\Payflow
 */
class PayflowCaptureRequestService extends
    OmnipayRequestServiceAbstract
{

    /**
     * Sends a PayflowCaptureRequestData object to the Payflow gateway and returns a response
     *
     * @param PayflowGatewayData        $gateway_data A configured PayflowGatewayData entity to send the request through
     * @param PayflowCaptureRequestData $request_data The PayflowCaptureRequestData entity to send
     *
     * @return PayflowCaptureResponseData
     * @throws \Exception
     */
    public function sendRequestDataEntity(
        $gateway_data,
        $request_data
    ): object {

        // Use the abstract class function to send the capture request and return a response
        return $this->sendCaptureRequestData(
            $gateway_data,
            $request_data
        );
    }


    /**
     * Creates a new PayflowCaptureResponseData object to handle data returned from the gateway
     *
     * @param Response $api_response The response entity received from a gateway
     * @param string   $gateway_name The name of the gateway
     * @param string   $action       The action performed in this response (authorize, capture, etc.)
     * @param string[] $metadata     Extra data associated with this response
     *
     * @return PayflowCaptureResponseData
     */
    public function newResponseDataEntity(
        $api_response,
        string $gateway_name,
        string $action,
        array $metadata
    ): object {

        // Return a new PayflowCaptureResponseData entity
        return new PayflowCaptureResponseData(
            $api_response,
            $gateway_name,
            $action,
            $metadata['amount']
        );
    }

}