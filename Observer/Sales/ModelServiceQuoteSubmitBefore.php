<?php

namespace Xigen\Postbox\Observer\Sales;

/**
 * ModelServiceQuoteSubmitBefore class
 */
class ModelServiceQuoteSubmitBefore implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Xigen\Postbox\Helper\Data
     */
    protected $helper;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Magento\Quote\Model\QuoteRepository
     */
    protected $quoteRepository;

    /**
     * ModelServiceQuoteSubmitBefore constructor.
     * @param \Magento\Quote\Model\QuoteRepository $quoteRepository
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Xigen\Postbox\Helper\Data $helper
     */
    public function __construct(
        \Magento\Quote\Model\QuoteRepository $quoteRepository,
        \Psr\Log\LoggerInterface $logger,
        \Xigen\Postbox\Helper\Data $helper
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->logger = $logger;
        $this->helper = $helper;
    }

    /**
     * Execute observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute(
        \Magento\Framework\Event\Observer $observer
    ) {
        /** @var \Magento\Sales\Model\Order $order */
        $order = $observer->getOrder();
        $quote = $this->quoteRepository->get($order->getQuoteId());
        $this->helper->transportFieldsFromExtensionAttributesToObject(
            $quote->getBillingAddress(),
            $order->getBillingAddress(),
            'extra_checkout_billing_address_fields'
        );
        if ($order->getShippingAddress()) {
            $this->helper->transportFieldsFromExtensionAttributesToObject(
                $quote->getShippingAddress(),
                $order->getShippingAddress(),
                'extra_checkout_shipping_address_fields'
            );
        }
    }
}
