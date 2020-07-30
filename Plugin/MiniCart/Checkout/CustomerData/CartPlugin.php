<?php

namespace Magenerds\GermanLaw\Plugin\MiniCart\Checkout\CustomerData;

class CartPlugin {

		/**
     * Xml pah to checkout sidebar display value
     */
    const XML_PATH_DISPLAY_MINICART_EXTRA = 'germanlaw/price/display_minicart_extra';

		protected $checkoutSession;
    protected $checkoutHelper;
    protected $quote;
		protected $scopeConfig;
		protected $storeManager;

		/**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

		/**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

		/**
     * @var \Magento\Framework\UrlInterface
     */
    protected $_urlBuilder;

    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Checkout\Helper\Data $checkoutHelper,
				\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
				\Magento\Store\Model\StoreManagerInterface $storeManager,
				\Magento\Framework\UrlInterface $urlBuilder
    ) {

        $this->checkoutSession = $checkoutSession;
        $this->checkoutHelper = $checkoutHelper;
				$this->_scopeConfig = $scopeConfig;
				$this->_storeManager = $storeManager;
				$this->_urlBuilder = $urlBuilder;
    }


		/**
     * Returns the configuration if asterisk is used or not
     *
     * @return mixed
     */
		public function getIsNeedToDisplayInMinicart()
    {
        return (bool)$this->_scopeConfig->getValue(
						self::XML_PATH_DISPLAY_MINICART_EXTRA,
						\Magento\Store\Model\ScopeInterface::SCOPE_STORE,
						$this->_storeManager->getStore()->getId()
				);
    }

		/**
     * Get active quote
     *
     * @return \Magento\Quote\Model\Quote
     */
    protected function getQuote()
    {
        if (null === $this->quote) {
            $this->quote = $this->checkoutSession->getQuote();
        }
        return $this->quote;
    }

    protected function getDiscountAmount()
    {
        $discountAmount = 0;
        foreach($this->getQuote()->getAllVisibleItems() as $item){
            $discountAmount += ($item->getDiscountAmount() ? $item->getDiscountAmount() : 0);
        }
        return $discountAmount;
    }

		/**
     * Returns the link to the configured shipping page
     *
     * @return string
     */
    protected function _getCmsLink()
    {
        return $this->_urlBuilder->getUrl(null, ['_direct' => $this->_scopeConfig->getValue(
            'germanlaw/price/shipping_page',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->_storeManager->getStore()->getId()
        )]);
    }

		/**
     * Returns the configured tax text
     *
     * @return \Magento\Framework\Phrase|string|void
     */
    public function getTaxText()
    {
        $taxText = __($this->_scopeConfig->getValue(
            'germanlaw/price/tax_text',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->_storeManager->getStore()->getId()
        ));

        $taxRate = '';

        // insert tax rate
        $taxText = str_replace('%s', $taxRate, $taxText);
				$taxText = str_replace('  ', ' ', $taxText);

        // insert link to shipping page
        if (strstr($taxText, '[') && strstr($taxText, ']') && $link = $this->_getCmsLink()) {
            $href = '<a href="'. $link . '">';
            $taxText = str_replace('[', $href, $taxText);
            $taxText = str_replace(']', '</a>', $taxText);
        }

        return $taxText;
    }

		/**
		 * @param \Magento\Checkout\CustomerData\Cart $subject
		 * @param array $result
		 * @return array
		 */
    public function afterGetSectionData(\Magento\Checkout\CustomerData\Cart $subject, array $result)
    {
				$result['display_minicart_extra_no_html'] = $this->getIsNeedToDisplayInMinicart();
        //$result['extra_data'] = $result['subtotalAmount'] * 10 / 100;
				$result['extra_data_tax_text'] = $this->getTaxText();
        return $result;
    }
}