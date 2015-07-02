<?php

namespace Locastic\TcomPayWay\AuthorizeForm\Helpers;

use Locastic\TcomPayWay\AuthorizeForm\Model\Payment;

/**
 * Used for generating signature for payment request
 *
 * Class SignatureGenerator
 * @package Locastic\TcomPayWay\AuthorizeForm\Helpers
 */
class SignatureGenerator
{
    const METHOD_NAME = 'authorize-form';

    /**
     * Based on payment model generates signature
     *
     * @param string  $secretKey
     * @param Payment $payment
     *
     * @return string
     */
    public static function getSignature($secretKey, Payment $payment)
    {
        $string = self::METHOD_NAME.$secretKey;

        $string .= $payment->getPgwShopId().$secretKey;
        $string .= $payment->getPgwOrderId().$secretKey;
        $string .= $payment->getPgwAmount().$secretKey;
        $string .= $payment->getPgwAuthorizationType().$secretKey;
        $string .= $payment->getPgwAuthorizationToken().$secretKey;
        $string .= $payment->getPgwLanguage().$secretKey;
        $string .= $payment->getPgwReturnMethod().$secretKey;
        $string .= $payment->getPgwSuccessUrl().$secretKey;
        $string .= $payment->getPgwFailureUrl().$secretKey;
        $string .= $payment->getPgwFirstName().$secretKey;
        $string .= $payment->getPgwLastName().$secretKey;
        $string .= $payment->getPgwStreet().$secretKey;
        $string .= $payment->getPgwCity().$secretKey;
        $string .= $payment->getPgwPostCode().$secretKey;
        $string .= $payment->getPgwCountry().$secretKey;
        $string .= $payment->getPgwPhoneNumber().$secretKey;
        $string .= $payment->getPgwEmail().$secretKey;
        $string .= $payment->getPgwmerchantData().$secretKey;
        $string .= $payment->getPgwOrderInfo().$secretKey;
        $string .= $payment->getPgwOrderItems().$secretKey;
        $string .= $payment->getPgwDisableInstallments().$secretKey;

        return hash('sha512', $string);
    }
}
