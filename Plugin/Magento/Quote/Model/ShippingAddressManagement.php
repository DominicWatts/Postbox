<?php

namespace Xigen\Postbox\Plugin\Magento\Quote\Model;

/**
 * ShippingAddressManagement class
 */
class ShippingAddressManagement
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Xigen\Postbox\Helper\Data
     */
    private $helper;

    /**
     * ShippingAddressManagement constructor.
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Xigen\Postbox\Helper\Data $helper
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Xigen\Postbox\Helper\Data $helper
    ) {
        $this->logger = $logger;
        $this->helper = $helper;
    }

    /**
     * @param \Magento\Quote\Model\ShippingAddressManagement $subject
     * @param $cartId
     * @param \Magento\Quote\Api\Data\AddressInterface $address
     */
    public function beforeAssign(
        \Magento\Quote\Model\ShippingAddressManagement $subject,
        $cartId,
        \Magento\Quote\Api\Data\AddressInterface $address
    ) {
        $extAttributes = $address->getExtensionAttributes();
        if (!empty($extAttributes)) {
            $this->helper->transportFieldsFromExtensionAttributesToObject(
                $extAttributes,
                $address,
                'extra_checkout_shipping_address_fields'
            );
        }
    }
}
