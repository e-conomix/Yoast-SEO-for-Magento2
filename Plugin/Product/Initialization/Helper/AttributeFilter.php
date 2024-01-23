<?php
/**
 * @author Felix Hamader <hamader@e-conomix.at>
 * @copyright Copyright (c) 2024 E-CONOMIX GmbH (https://e-conomix.at)
 * @created 22.01.2024
 */

namespace MaxServ\YoastSeo\Plugin\Product\Initialization\Helper;

use Magento\Catalog\Controller\Adminhtml\Product\Initialization\Helper\AttributeFilter as Subject;
use Magento\Catalog\Model\Product;
use Magento\Framework\App\Request\Http as Request;

/**
 * Class AttributeFilter
 *
 * @package MaxServ\YoastSeo\Plugin\Product\Initialization\Helper
 */
class AttributeFilter
{
    protected Request $request;

    /**
     * AttributeFilter constructor
     *
     * @param Request $request
     */
    public function __construct(
        Request $request
    ) {
        $this->request = $request;
    }

    /**
     * @param Subject $subject
     * @param Product $product
     * @param array $productData
     * @param array $useDefaults
     *
     * @return array
     */
    public function beforePrepareProductAttributes(Subject $subject, Product $product, array $productData, array $useDefaults)
    {
        $changedValues = $this->request->getPost('yoast_changed_values', []);
        $changedTitle = isset($changedValues['yoast_changed_meta_title']) && $changedValues['yoast_changed_meta_title'] === 'true';

        if ($changedTitle) {
            $useDefaults['meta_title'] = $productData['meta_title'] ? '0' : '1';
        }

        $aDebug = $useDefaults['meta_title'];

        return [$product, $productData, $useDefaults];
    }
}
