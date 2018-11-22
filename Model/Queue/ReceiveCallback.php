<?php

namespace Andrew\CustomCatalog\Model\Queue;

class ReceiveCallback
{
    public static function saveProduct()
    {
        return function ($msg) {
            echo ' [x] Received ', $msg->body, "\n";
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $productData = json_decode($msg->body, true);
            $productRepository = $objectManager->get(\Magento\Catalog\Api\ProductRepositoryInterface::class);
            $product = $productRepository->getById($productData['entity_id']);
            if ($product) {
                $product->setVpn($productData['vpn']);
                $product->setCopyWriteInfo($productData['copy_write_info']);
                $productRepository->save($product);
            }
        };
    }
}
