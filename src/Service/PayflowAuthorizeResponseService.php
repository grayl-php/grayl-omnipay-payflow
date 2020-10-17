<?php

namespace Grayl\Omnipay\Payflow\Service;

use Grayl\Omnipay\Common\Service\OmnipayResponseServiceAbstract;
use Grayl\Omnipay\Payflow\Entity\PayflowAuthorizeResponseData;

/**
 * Class PayflowAuthorizeResponseService
 * The service for working with Payflow authorize responses
 *
 * @package Grayl\Omnipay\Payflow
 */
class PayflowAuthorizeResponseService extends
    OmnipayResponseServiceAbstract
{

    /**
     * Returns the AVS result code from a gateway API response
     *
     * @param PayflowAuthorizeResponseData $response_data The response object to pull the AVS code from
     *
     * @return string
     */
    public function getAVSCode($response_data): ?string
    {

        // Get the raw response data
        $data = $response_data->getAPIResponse()
            ->getData();

        // See if the AVS code is set
        if (isset($data['AVSZIP'])) {
            // Found
            return $data['AVSZIP'];
        }

        // Not found
        return null;
    }


    /**
     * Returns the CVV result code from a gateway API response
     *
     * @param PayflowAuthorizeResponseData $response_data The response object to pull the CVV code from
     *
     * @return string
     */
    public function getCVVCode($response_data): ?string
    {

        // Get the raw response data
        $data = $response_data->getAPIResponse()
            ->getData();

        // See if the CVV field is set
        if (isset($data['CVV2MATCH'])) {
            // Found
            return $data['CVV2MATCH'];
        }

        // Not found
        return null;
    }

}