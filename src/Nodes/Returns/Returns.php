<?php

namespace Shopee\Nodes\Returns;

use Shopee\Nodes\NodeAbstract;
use Shopee\RequestParametersInterface;
use Shopee\ResponseData;

class Returns extends NodeAbstract
{
    /**
     * Confirm return.
     *
     * @param array|RequestParametersInterface $parameters
     * @return ResponseData
     */
    public function confirmReturn($parameters = []): ResponseData
    {
        return $this->post('/api/'.$this->version.'/returns/confirm', $parameters);
    }

    /**
     * Dispute return.
     *
     * @param array|RequestParametersInterface $parameters
     * @return ResponseData
     */
    public function disputeReturn($parameters = []): ResponseData
    {
        return $this->post('/api/'.$this->version.'/returns/dispute', $parameters);
    }

    /**
     * Get return list.
     *
     * @param array|RequestParametersInterface $parameters
     * @return ResponseData
     */
    public function getReturnList($parameters = []): ResponseData
    {
        return $this->post('/api/'.$this->version.'/returns/get', $parameters);
    }

    /**
     * Use this api to get detail information of a returned order.
     *
     * @param array|RequestParametersInterface $parameters
     * @return ResponseData
     */
    public function getReturnDetail($parameters = []): ResponseData
    {
        return $this->post('/api/'.$this->version.'/returns/detail', $parameters);
    }
}
