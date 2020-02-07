<?php
/*
file di.xml:

<?xml version="1.0"?>
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:ObjectManager/etc/config.xsd">
<preference for="Magento\CatalogWidget\Block\Product\ProductsList" type="Magenerds\GermanLaw\Block\Product\ProductsList" />
</config>

*/
namespace Magenerds\GermanLaw\Block\Product;

class ProductsList extends \Magento\CatalogWidget\Block\Product\ProductsList
{

		protected $_template = 'Magento_CatalogWidget::product/widget/content/grid.phtml';

		protected $_httpContext;

		/**
     * Hold after price html string
     *
     * @var null|string
     */
    protected $_afterPriceHtml = null;

    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Rule\Model\Condition\Sql\Builder $sqlBuilder,
        \Magento\CatalogWidget\Model\Rule $rule,
        \Magento\Widget\Helper\Conditions $conditionsHelper,
        array $data = []
    ) {
        $this->_httpContext = $httpContext;
        parent::__construct(
            $context,
            $productCollectionFactory,
            $catalogProductVisibility,
            $httpContext,
            $sqlBuilder,
            $rule,
            $conditionsHelper,
            $data
        );
    }

    /**
     * Internal constructor, that is called from real constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
		}

		public function afterPriceBlock($sku)
		{
				$customBlockHtml = $this->_layout
							->createBlock('Magenerds\GermanLaw\Block\AfterPrice','after_product_price_'.$sku)
							->setTemplate('Magenerds_GermanLaw::price/after.phtml');
				$this->_afterPriceHtml =$customBlockHtml->toHtml();
				return $this->_afterPriceHtml;
		}



}

