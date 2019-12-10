<?php

namespace Magenerds\GermanLaw\Model\Plugin;

class ProductAbstractPlugin {

		/**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;


    /**
     * @var \Magento\Framework\Registry
     */
    protected $_registry;

		/**
     * @var \Magento\Catalog\Model\Product
     */
		protected $_product;

		/**
     * Hold after price html string
     *
     * @var null|string
     */
    protected $_afterPriceHtml = null;

		/**
     * Hold layout
     *
     * @var \Magento\Framework\View\LayoutInterface
     */
    protected $_layout;

    /**
     * Constructor
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Catalog\Model\Product $product
     * @param \Magento\Catalog\Helper\Product $catalogProduct
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param array $data
     */
		public function __construct(
			\Magento\Backend\Block\Template\Context $context,
		  \Magento\Framework\Registry $registry,
			\Magento\Framework\View\LayoutInterface $layout

		){
		      $this->_scopeConfig = $context->getScopeConfig();
		      $this->_registry = $registry;
					$this->_layout = $layout;
		}

    /**
     * @param Product $product
     * @return string
     */
    public function afterGetProductPrice($subject, $result, $product)
    {
				try{
              $result .= $this->_getAfterPriceHtml();

        } catch (\Exception $ex) {
            // if an error occurs, just render the default since it is preallocated
            return $result;
        }

				return $result;

    }

		/**
     * Renders and caches the after price html
     *
     * @return null|string
     */
    protected function _getAfterPriceHtml()
    {
        if (null === $this->_afterPriceHtml) {
            $afterPriceBlock = $this->_layout->createBlock('Magenerds\GermanLaw\Block\AfterPrice', 'after_product_price');
            $afterPriceBlock->setTemplate('Magenerds_GermanLaw::price/after.phtml');
            $this->_afterPriceHtml = $afterPriceBlock->toHtml();
        }

        return $this->_afterPriceHtml;
    }

}