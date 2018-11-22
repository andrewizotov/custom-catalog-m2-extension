<?php

namespace Andrew\CustomCatalog\Model\Queue;

interface MessageInterface
{

    public function setContent(array $data);

    public function getContent();
}
