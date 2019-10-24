<?php

// phpcs:disable Magento2.Files.LineLength.MaxExceeded
namespace Xigen\Postbox\Block\Checkout;

/**
 * LayoutProcessor class
 */
class LayoutProcessor implements \Magento\Checkout\Block\Checkout\LayoutProcessorInterface
{
    /**
     * @param $result
     *
     * @return mixed
     */
    public function getShippingFormFields($result)
    {
        if (isset($result['components']['checkout']['children']['steps']['children']
            ['shipping-step']['children']['shippingAddress']['children']
            ['shipping-address-fieldset'])
        ) {
            $customShippingFields = $this->getFields('shippingAddress.custom_attributes', 'shipping');
        
            $shippingFields = $result['components']['checkout']['children']['steps']['children']
            ['shipping-step']['children']['shippingAddress']['children']
            ['shipping-address-fieldset']['children'];
        
            $shippingFields = array_replace_recursive($shippingFields, $customShippingFields);
        
            $result['components']['checkout']['children']['steps']['children']
            ['shipping-step']['children']['shippingAddress']['children']
            ['shipping-address-fieldset']['children'] = $shippingFields;
        }

        return $result;
    }

    public function getBillingFormFields($result)
    {
        if (isset($result['components']['checkout']['children']['steps']['children']
            ['billing-step']['children']['payment']['children']
            ['payments-list'])
        ) {
            $paymentForms = $result['components']['checkout']['children']['steps']['children']
            ['billing-step']['children']['payment']['children']
            ['payments-list']['children'];
        
            foreach ($paymentForms as $paymentMethodForm => $paymentMethodValue) {
                $paymentMethodCode = str_replace('-form', '', $paymentMethodForm);
        
                if (!isset($result['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['payments-list']['children'][$paymentMethodCode . '-form'])) {
                    continue;
                }
        
                $billingFields = $result['components']['checkout']['children']['steps']['children']
                ['billing-step']['children']['payment']['children']
                ['payments-list']['children'][$paymentMethodCode . '-form']['children']['form-fields']['children'];
        
                $customBillingFields = $this->getFields('billingAddress' . $paymentMethodCode . '.custom_attributes', 'billing');
        
                $billingFields = array_replace_recursive($billingFields, $customBillingFields);
        
                $result['components']['checkout']['children']['steps']['children']
                ['billing-step']['children']['payment']['children']
                ['payments-list']['children'][$paymentMethodCode . '-form']['children']['form-fields']['children'] = $billingFields;
            }
        }

        return $result;
    }

    /**
     * @param $result
     *
     * @return mixed
     */
    public function process($result)
    {
        $result = $this->getShippingFormFields($result);
        $result = $this->getBillingFormFields($result);

        return $result;
    }

    /**
     * @param $scope
     * @param $addressType
     *
     * @return array
     */
    public function getFields($scope, $addressType)
    {
        $fields = [];
        foreach ($this->getAdditionalFields($addressType) as $field) {
            $fields[$field] = $this->getField($field, $scope);
        }

        return $fields;
    }

    /**
     * @param $attributeCode
     * @param $scope
     *
     * @return array
     */
    public function getField($attributeCode, $scope)
    {
        $field = [
            'config' => [
                'customScope' => $scope,
                'template' => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/input'
            ],
            'dataScope' => $scope . '.' . $attributeCode,
            'sortOrder' => '333',
            'visible' => true,
            'provider' => 'checkoutProvider',
            'validation' => [],
            'options' => [],
            'label' => __('po_box')
        ];

        return $field;
    }

    /**
     * @param string $addressType
     *
     * @return array
     */
    public function getAdditionalFields($addressType = 'shipping')
    {
        $shippingAttributes = [];
        $billingAttributes = [];
        $shippingAttributes[] = 'po_box';
        $billingAttributes[] = 'po_box';

        return $addressType == 'shipping' ? $shippingAttributes : $billingAttributes;
    }
}
