<?php

namespace Grayl\Omnipay\Payflow\Entity;

use Grayl\Omnipay\Common\Entity\OmnipayGatewayDataAbstract;
use Omnipay\Payflow\ProGateway;

/**
 * Class PayflowGatewayData
 * This entity for the Payflow API
 * @method void __construct(ProGateway $api, string $gateway_name, string $environment)
 * @method void setAPI(ProGateway $api)
 * @method ProGateway getAPI()
 *
 * @package Grayl\Omnipay\Payflow
 */
class PayflowGatewayData extends
    OmnipayGatewayDataAbstract
{

    /**
     * Fully configured Omnipay Payflow gateway entity
     *
     * @var ProGateway
     */
    protected $api;

}