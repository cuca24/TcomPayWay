<?php

namespace Locastic\TcomPayWay\AuthorizeDirect\Model;

use Locastic\TcomPayWay\AuthorizeDirect\Helpers\SignatureGenerator;
use Locastic\TcomPayWay\Helpers\MiscHelper;
use Locastic\TcomPayWay\Model\Payment as BasePayment;
use Locastic\TcomPayWay\Model\PaymentInterface;

/**
 * This model is used for preparing standard model of payment (athorize-form)
 *
 * Class Payment
 * @package Locastic\TcomPayWay\AuthorizeForm\Model
 */
class Payment extends BasePayment implements PaymentInterface
{

    /**
     * @var int
     */
    private $pgwInstallments;

    /**
     * @var string
     */
    private $pgwCardNumber;

    /**
     * @var string
     */
    private $pgwCardExpirationDate;

    /**
     * @var string
     */
    private $pgwCardVerificationData;


    /**
     * Payment constructor.
     *
     * @param $data
     */
    public function __construct($data) {
        $this->pgwShopId                        = $this->getParam($data, 'pgw_shop_id');
        $this->secretKey                        = $this->getParam($data, 'secret_key');
        $this->pgwOrderId                       = $this->getParam($data, 'pgw_order_id');
        $this->pgwAmount                        = $this->getParam($data, 'pgw_amount');
        $this->pgwAuthorizationType             = $this->getParam($data, 'pgw_authorization_type');
        $this->pgwSuccessUrl                    = $this->getParam($data, 'pgw_success_url');
        $this->pgwFailureUrl                    = $this->getParam($data, 'pgw_failure_url');
        $this->pgwCardNumber                    = $this->getParam($data, 'pgw_card_number');
        $this->pgwCardExpirationDate            = $this->getParam($data, 'pgw_card_expiration_date');
        $this->pgwCardVerificationData          = $this->getParam($data, 'pgw_card_verification_data');

        $this->setPgwOrderItems($this->getParam($data, 'pgw_order_items'));
        $this->setPgwFirstName($this->getParam($data, 'pgw_first_name'));
        $this->setPgwLastName($this->getParam($data, 'pgw_last_name'));
        $this->setPgwStreet($this->getParam($data, 'pgw_street'));
        $this->setPgwPostCode($this->getParam($data, 'pgw_post_code'));
        $this->setPgwCity($this->getParam($data, 'pgw_city'));
        $this->setPgwCountry($this->getParam($data, 'pgw_country'));
        $this->setPgwEmail($this->getParam($data, 'pgw_email'));
        $this->sandbox = $this->getParam($data, 'sandbox', true);
    }

    /**
     * @param $data
     * @param $param
     * @param null $default
     * @return specified parameter from data
     */
    private function getParam($data, $param, $default = null) {

        if (isset($data[$param])) {
            return $data[$param];
        }

        return $default;
    }

    /**
     * @return int
     */
    public function getPgwInstallments()
    {
        return $this->pgwInstallments;
    }

    /**
     * @param int $pgwInstallments
     */
    public function setPgwInstallments($pgwInstallments)
    {
        $this->pgwInstallments = $pgwInstallments;
    }

    /**
     * @return mixed
     */
    public function getPgwCardNumber()
    {
        return $this->pgwCardNumber;
    }

    /**
     * @param mixed $pgwCardNumber
     */
    public function setPgwCardNumber($pgwCardNumber)
    {
        $this->pgwCardNumber = $pgwCardNumber;
    }

    /**
     * @return mixed
     */
    public function getPgwCardExpirationDate()
    {
        return $this->pgwCardExpirationDate;
    }

    /**
     * @param mixed $pgwCardExpirationDate
     */
    public function setPgwCardExpirationDate($pgwCardExpirationDate)
    {
        $this->pgwCardExpirationDate = $pgwCardExpirationDate;
    }

    /**
     * @return mixed
     */
    public function getPgwCardVerificationData()
    {
        return $this->pgwCardVerificationData;
    }

    /**
     * @param mixed $pgwCardVerificationData
     */
    public function setPgwCardVerificationData($pgwCardVerificationData)
    {
        $this->pgwCardVerificationData = $pgwCardVerificationData;
    }

    /**
     * @return string
     */
    public function getPgwSignature()
    {
        return SignatureGenerator::generateSignature($this);
    }

    /**
     * @param string $pgwFirstName
     */
    public function setPgwFirstName($pgwFirstName)
    {
        $this->pgwFirstName = MiscHelper::clearUTF($pgwFirstName);
    }

    /**
     * @param string $pgwLastName
     */
    public function setPgwLastName($pgwLastName)
    {
        $this->pgwLastName = MiscHelper::clearUTF($pgwLastName);
    }

    /**
     * @param string $pgwStreet
     */
    public function setPgwStreet($pgwStreet)
    {
        $this->pgwStreet = MiscHelper::clearUTF($pgwStreet);
    }

    /**
     * @param string $pgwCity
     */
    public function setPgwCity($pgwCity)
    {
        $this->pgwCity = MiscHelper::clearUTF($pgwCity);
    }

    /**
     * @param string $pgwPostCode
     */
    public function setPgwPostCode($pgwPostCode)
    {
        $this->pgwPostCode = MiscHelper::clearUTF($pgwPostCode);
    }

    /**
     * @return string
     */
    public function getApiEndPoint()
    {
        if ($this->sandbox) {
            return 'https://pgwtest.ht.hr/services/payment/api/authorize-direct';
        }

        return 'https://pgw.ht.hr/services/payment/api/authorize-direct';
    }

    /**
     * @param array $pgwResponse
     * @return bool
     */
    public function isPgwResponseValid($pgwResponse)
    {
        return $pgwResponse['pgw_signature'] == SignatureGenerator::generateSignatureFromArray(
                $this->secretKey,
                $pgwResponse
            );
    }

    /**
     * @param array $pgwResponse
     * @return bool
     */
    public static function checkPgwResponseValid($pgwResponse, $secretKey)
    {
        return $pgwResponse['pgw_signature'] == SignatureGenerator::generateSignatureFromArray(
                $secretKey,
                $pgwResponse
            );
    }
}
