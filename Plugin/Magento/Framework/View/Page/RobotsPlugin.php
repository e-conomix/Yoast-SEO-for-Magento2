<?php

namespace MaxServ\YoastSeo\Plugin\Magento\Framework\View\Page;

use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Magento\Framework\View\Page\Config as Subject;

class RobotsPlugin
{
    /**
     * @var State
     */
    private $state;
    private $registry;

    /**
     * @param State $state
     */
    public function __construct(
        State $state,
        \Magento\Framework\Registry $registry
    ) {
        $this->state = $state;
        $this->registry = $registry;
    }

    /**
     * Change the behavior of the getRobots method
     *
     * @param Subject $subject
     * @param string $result
     * @return string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetRobots(Subject $subject, $result)
    {
        if ($this->state->getAreaCode() !== Area::AREA_FRONTEND) {
            return 'NOINDEX,NOFOLLOW';
        }

        $product = $this->registry->registry('product');
        if($product){
            return $product->getData('yoast_robots_instructions');
        }
        return $result;
    }
}
