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
     * Constructor function
     * @param Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        Psr\Log\LoggerInterface $logger
    ) {
        $this->logger = $logger;
    }
    
    /**
     * @param \Magento\Quote\Model\ShippingAddressManagement $subject
     * @param $cartId
     * \Magento\Quote\Api\Data\AddressInterface $address
     * @return array
     */
    public function beforeAssign(
        \Magento\Quote\Model\ShippingAddressManagement $subject,
        $cartId,
        \Magento\Quote\Api\Data\AddressInterface $address
    ) {
        $extAttributes = $address->getExtensionAttributes();
        if (!empty($extAttributes)) {
            try {
                $address->setPoBox($extAttributes->getPoBox());
            } catch (\Exception $e) {
                $this->logger->critical($e);
            }
        }
        return [$cartId, $address];
    }
}
