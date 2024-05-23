<?php
/**
 * @author Felix Hamader <hamader@e-conomix.at>
 * @copyright Copyright (c) 2024 E-CONOMIX GmbH (https://e-conomix.at)
 * @created 22.01.2024
 */

namespace MaxServ\YoastSeo\Plugin\Product\Initialization\Helper;

use Magento\Catalog\Controller\Adminhtml\Product\Initialization\Helper\AttributeFilter as Subject;
use Magento\Catalog\Model\Product;

/**
 * Class AttributeFilter
 *
 * @package MaxServ\YoastSeo\Plugin\Product\Initialization\Helper
 */
class AttributeFilter
{
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
        if(isset($useDefaults['meta_title'])){
            $useDefaults['meta_title'] = (string) $useDefaults['meta_title'] == '1'
                ? (string) ($productData['meta_title'] != $productData['name'])
                : (string) !$productData['meta_title'];

        }

        return [$product, $productData, $useDefaults];
    }
}
