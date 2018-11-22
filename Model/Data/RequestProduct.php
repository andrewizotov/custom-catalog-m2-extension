<?php

namespace Andrew\CustomCatalog\Model\Data;

class RequestProduct extends \Magento\Framework\Api\AbstractSimpleObject implements
    \Andrew\CustomCatalog\Api\Data\RequestProductInterface
{
    /**
     * Get option
     *
     * @return string
     */
    public function getVpn()
    {
        return $this->_get(self::VPN);
    }

    /**
     * Get option
     *
     * @return string
     */
    public function getEntityId()
    {
        return $this->_get(self::ENTITY_ID);
    }

    /**
     * Get option
     *
     * @return string
     */
    public function getCopyWriteInfo()
    {
        return $this->_get(self::COPY_WRITE_INFO);
    }

    /**
     * Set option
     *
     * @param  string $vpn
     * @return $this
     */
    public function setVpn(string $vpn)
    {
        return $this->setData(self::VPN, $vpn);
    }

    /**
     * Set option
     *
     * @param  string $info
     * @return $this
     */
    public function setCopyWriteInfo(string $info)
    {
        return $this->setData(self::COPY_WRITE_INFO, $info);
    }

    /**
     * Set option
     *
     * @param  string $info
     * @return $this
     */
    public function setEntityId(string $info)
    {
        return $this->setData(self::ENTITY_ID, $info);
    }
}
