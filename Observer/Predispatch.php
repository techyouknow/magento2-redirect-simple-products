<?php

namespace Techyouknow\RedirectSimpleProducts\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class Predispatch implements ObserverInterface {

    protected $_redirect;
    protected $_productTypeConfigurable;
    protected $_productRepository;
    protected $_storeManager;
    protected $scopeConfig;

    public function __construct (
        \Magento\Framework\App\Response\Http $redirect,
        \Magento\ConfigurableProduct\Model\Product\Type\Configurable $productTypeConfigurable,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
    ) {
        $this->_redirect = $redirect;
        $this->_productTypeConfigurable = $productTypeConfigurable;
        $this->_productRepository = $productRepository;
        $this->_storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
    }

    public function execute(Observer $observer)
    {
        $pathInfo = $observer->getEvent()->getRequest()->getPathInfo();

        /** If the module is disabled, we don't need to do anything. */
        if (!$this->isModuleEnabled()) {
            return;
        }

        /** If it's not a product view we don't need to do anything. */
        if (strpos($pathInfo, 'product') === false) {
            return;
        }

        $request = $observer->getEvent()->getRequest();
        $simpleProductId = $request->getParam('id');
        if (!$simpleProductId) {
            return;
        }

        $simpleProduct = $this->_productRepository->getById($simpleProductId, false, $this->_storeManager->getStore()->getId());
        if (!$simpleProduct || $simpleProduct->getTypeId() != \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE) {
            return;
        }
        try {
            $childId = $simpleProductId;
            $parentId = $this->getParentId($childId);
            if($childId != $parentId) {
                $configProduct =$this->_productRepository->getById($parentId);
                $configType = $configProduct->getTypeInstance();
                $attributes = $configType->getConfigurableAttributesAsArray($configProduct);

                $options = [];
                foreach ($attributes as $attribute) {
                    $id = $attribute['attribute_id'];
                    $value = $simpleProduct->getData($attribute['attribute_code']);
                    $options[$id] = $value;
                }

                // Pass on any query parameters to the configurable product's URL.
                $query = $request->getQuery();
                if (is_object($query)) {
                    $query = $query->toArray();
                }
                $query = $query ? '?' . http_build_query($query) : '';

                // Generate hash for selected product options.
                $hash = $options ? '#' . http_build_query($options) : '';

                $configProductUrl = $configProduct->getUrlModel()->getUrl($configProduct) . $query . $hash;
                $this->_redirect->setRedirect($configProductUrl, 301);
            } else {
                return;
            }
    
        } catch (NoSuchEntityException $noSuchEntityException) {
            return;
        }
    }

    public function isModuleEnabled() {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        return $this->scopeConfig->getValue('techyouknowredirectsimpleproducts/general/enable', $storeScope);
    }

    public function getParentId($childId) {
        $currentWebsiteId = $this->_storeManager->getStore()->getWebsiteId();
        $parentProduct = $this->_productTypeConfigurable->getParentIdsByChild($childId);
        if(count($parentProduct) > 0){
            foreach ($parentProduct as $parentProductId) {
                if(isset($parentProductId)){
                    $websiteIds = $this->_productRepository->getById($parentProductId)->getWebsiteIds();
                    if(in_array($currentWebsiteId, $websiteIds)){
                        return $parentProductId;
                    }
                }
            }
        } else {
            if(isset($parentIds[0])){
                return $parentIds[0];
            }
        }
       
        return $childId;
    }
}
