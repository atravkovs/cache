<?php
/**
 * ScandiPWA_Cache
 *
 * @category    ScandiPWA
 * @package     ScandiPWA_Cache
 * @author      Ilja Lapkovskis <ilja@scandiweb.com | info@scandiweb.com>
 * @copyright   Copyright (c) 2019 Scandiweb, Ltd (https://scandiweb.com)
 */

namespace ScandiPWA\Cache\Observer\Response;

use Magento\Catalog\Model\ResourceModel\AbstractCollection;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Event\Observer;

/**
 * Class TagEntityResponse
 * Add Entity tag to a Cache object for future response tagging
 *
 * @package ScandiPWA\Cache\Observer
 */
class TagEntityResponse extends CacheableObserver
{
    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer): void
    {
        if (!$this->isGraphQl || !$this->isCacheable) {
            return;
        }
        
        $entity = $observer->getEntity();
        if ($entity === null) {
            $data = $observer->getData();
            if (array_key_exists('collection', $data)) {
                
                /** @var AbstractCollection $collection */
                $collection = $data['collection'];
                foreach ($collection->getItems() as $entity) {
                    $this->addEntityTags($entity);
                }
            }
        }
        $this->addEntityTags($entity);
    }
    
    protected function addEntityTags($entity)
    {
        if ($entity instanceof IdentityInterface) {
            $this->cache->addIdentities($entity->getIdentities());
        }
    }
}
