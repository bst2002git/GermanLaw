<?php
namespace Magenerds\GermanLaw\Model\Plugin;

use Magento\Catalog\Pricing\Price;
use Magento\Framework\Pricing\Render\PriceBox as BasePriceBox;
use Magento\Msrp\Pricing\Price\MsrpPrice;
use Magento\Catalog\Model\Product\Pricing\Renderer\SalableResolverInterface;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Pricing\SaleableInterface;
use Magento\Framework\Pricing\Price\PriceInterface;
use Magento\Framework\Pricing\Render\RendererPool;
use Magento\Framework\App\ObjectManager;
use Magento\Catalog\Pricing\Price\MinimalPriceCalculatorInterface;

class FinalPriceBox extends \Magento\Catalog\Pricing\Render\FinalPriceBox
{

    public function __construct(
        Context $context,
        SaleableInterface $saleableItem,
        PriceInterface $price,
        RendererPool $rendererPool,
        array $data = [],
        SalableResolverInterface $salableResolver = null,
        MinimalPriceCalculatorInterface $minimalPriceCalculator = null,
        \Magento\Framework\Registry $registry
    ) {
        parent::__construct($context, $saleableItem, $price, $rendererPool, $data);
        $this->_registry = $registry;
    }

    public function wrapResult($html)
    {
        return '<div class="price-box '.$this->getData('css_classes').'" '.'data-role="priceBox" '.'data-product-id="'.$this->getSaleableItem()->getId().'"'.'>'.$html.'(Price/Kilometer)</div>';
    }
}