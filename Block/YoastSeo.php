<?php

namespace MaxServ\YoastSeo\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\ScopeInterface;
use MaxServ\YoastSeo\Model\Entity\MetaProviderInterface;
use MaxServ\YoastSeo\Model\EntityConfigurationPool;

class YoastSeo extends Template
{
    /**
     * @var EntityConfigurationPool
     */
    protected $entityConfigurationPool;

    /**
     * @param Context $context
     * @param EntityConfigurationPool $entityConfigurationPool
     * @param array $data
     */
    public function __construct(
        protected \Magento\Cms\Model\Page $page,
        Context $context,
        EntityConfigurationPool $entityConfigurationPool,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->entityConfigurationPool = $entityConfigurationPool;
    }

    /**
     * @return MetaProviderInterface
     */
    public function getMetaProvider()
    {
        $configuration = $this->entityConfigurationPool
            ->getConfigurationByEntityType($this->getData('entity_type'));

        if (!$configuration || !$configuration->getMetaProvider()) {
            return null;
        }

        return $configuration->getMetaProvider();
    }

    /**
     * @return string
     */
    public function getCanonicalUrl()
    {
        $canonical = $this->getUrl('', [
            '_current' => true,
            '_use_rewrite' => true
        ]);

        $parameterStrip = $this->_scopeConfig->getValue(
            'yoastseo/general/strip_parameters_from_canonical',
            ScopeInterface::SCOPE_STORES
        );

        if (
            $canonical
            && $parameterStrip == true
            && strstr(substr($canonical, strrpos($canonical, '/')), '?') !== false
        ) {
            $canonical = substr($canonical, 0, strrpos($canonical, '/'));
        }

        if (
            $canonical
            && substr($canonical, -5) !== '.html'
            && substr($canonical, -1) !== '/'
        ) {
            $canonical .= '/';
        }

        $identifier = ($this->page->getIdentifier() ?? trim($this->_request->getOriginalPathInfo(), '/')) . '/';
        if (!strstr($canonical, $identifier)) {
            $canonical .= $identifier;
        }

        return $canonical;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->_scopeConfig->getValue(
            'general/locale/code',
            ScopeInterface::SCOPE_STORES
        );
    }

    /**
     * @return string
     */
    public function getStoreName()
    {
        return $this->_scopeConfig->getValue(
            'general/store_information/name',
            ScopeInterface::SCOPE_STORES
        );
    }

    /**
     * @return string
     */
    public function getFacebookAppId()
    {
        return $this->_scopeConfig->getValue(
            'yoastseo/facebook/app_id',
            ScopeInterface::SCOPE_STORES
        );
    }

    /**
     * @return string
     */
    public function getFacebookAdmins()
    {
        return $this->_scopeConfig->getValue(
            'yoastseo/facebook/admin_ids',
            ScopeInterface::SCOPE_STORES
        );
    }

    /**
     * @return string
     */
    public function getFacebookPages()
    {
        return $this->_scopeConfig->getValue(
            'yoastseo/facebook/facebook_pages',
            ScopeInterface::SCOPE_STORES
        );
    }

    /**
     * @return string
     */
    public function getTwitterSite()
    {
        return $this->getTwitterValue($this->_scopeConfig->getValue(
            'yoastseo/twitter/company_account',
            ScopeInterface::SCOPE_STORES
        )
        );
    }

    /**
     * @return string
     */
    public function getTwitterCreator()
    {
        return $this->getTwitterValue($this->_scopeConfig->getValue(
            'yoastseo/twitter/manager_account',
            ScopeInterface::SCOPE_STORE
        )
        );
    }

    private function getTwitterValue($value)
    {
        if (\is_null($value)) {
            $value = "";
        }
        if (\substr($value, 0, 1) !== '@') {
            $value = '@' . $value;
        }
        if ($value === '@') {
            $value = '';
        }

        return $value;
    }
}
