<?php
declare(strict_types=1);

namespace CommunityEngineering\CurrencyPrecision\Plugin\Model\Quote\Address\Total;

use Magento\Quote\Api\Data\ShippingAssignmentInterface as ShippingAssignment;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address\Total;

/**
 * Class Grand
 *
 * @package CommunityEngineering\CurrencyPrecision\Plugin\Model\Quote\Address\Total
 */
class Grand
{
    /**
     * @var \Magento\Framework\Pricing\PriceCurrencyInterface
     */
    private $priceCurrency;

    /**
     * Grand constructor.
     *
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
     */
    public function __construct(
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
    ) {
        $this->priceCurrency = $priceCurrency;
    }

    /**
     * @param \Magento\Quote\Model\Quote\Address\Total\Grand $subject
     * @param $result
     * @param \Magento\Quote\Model\Quote $quote
     * @param \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     *
     * @return \Magento\Quote\Model\Quote\Address\Total\Grand
     */
    public function afterCollect(
        \Magento\Quote\Model\Quote\Address\Total\Grand $subject,
        $result,
        Quote $quote,
        ShippingAssignment $shippingAssignment,
        Total $total
    ) {

        $quoteCurrencyCode = $quote->getQuoteCurrencyCode();
        $baseCurrencyCode = $quote->getBaseCurrencyCode();

        if (!empty($quoteCurrencyCode) && !empty($baseCurrencyCode)) {
            $this->priceCurrency->getCurrency()->setData('currency_code', $baseCurrencyCode);

            $baseGrandTotal = $total->getBaseGrandTotal();
            if ($baseGrandTotal !== null) {
                $total->setBaseGrandTotal($this->priceCurrency->round((float)$baseGrandTotal));
            }

            $this->priceCurrency->getCurrency()->setData('currency_code', $quoteCurrencyCode);
        }

        return $result;
    }
}