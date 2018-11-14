<?php

namespace Andrew\CustomCatalog\Api;

interface ProductInterface
{
    /**
     * Gets by vpn.
     *
     * @api
     * @param string $vpn
     * @return array
     */
    public function getByVpn(string $vpn);

    /**
     * Update product.
     *
     * @api
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @param \Andrew\CustomCatalog\Api\Data\RequestProductInterface $BODY
     * @return \Andrew\CustomCatalog\Api\ProductInterface
     */
    public function update(\Andrew\CustomCatalog\Api\Data\RequestProductInterface $BODY);
}