<?php

namespace Andrew\CustomCatalog\Api\Data;

interface  RequestProductInterface
{
    const VPN = 'vpn';
    const COPY_WRITE_INFO = 'copy_write_info';
    const ENTITY_ID = 'entity_id';

    /**
     * Get option
     *
     * @return string
     */
    public function getVpn();

    /**
     * Get option
     *
     * @return string
     */
    public function getCopyWriteInfo();

    /**
     * Get option
     *
     * @return string
     */
    public function getEntityId();

    /**
     * Set option
     *
     * @param string $vpn
     * @return $this
     */
    public function setVpn(string $vpn);

    /**
     * Set option
     *
     * @param string $info
     * @return $this
     */
    public function setCopyWriteInfo(string $info);

    /**
     * Set option
     *
     * @param string $info
     * @return $this
     */
    public function setEntityId(string $info);
}