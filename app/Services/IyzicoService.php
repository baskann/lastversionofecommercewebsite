<?php

namespace App\Services;

use Iyzipay\Options;
use Iyzipay\Model\Payment;
use Iyzipay\Model\PaymentCard;
use Iyzipay\Model\Buyer;
use Iyzipay\Model\Address;
use Iyzipay\Model\BasketItem;
use Iyzipay\Request\CreatePaymentRequest;

class IyzicoService
{
    private $options;

    public function __construct()
    {
        $this->options = new Options();
        $this->options->setApiKey(config('services.iyzico.api_key'));
        $this->options->setSecretKey(config('services.iyzico.secret_key'));
        $this->options->setBaseUrl(config('services.iyzico.base_url'));
    }

    public function createPayment($orderData, $cardData)
    {
        $request = new CreatePaymentRequest();
        $request->setLocale(\Iyzipay\Model\Locale::TR);
        $request->setConversationId($orderData['conversation_id']);
        $request->setPrice($orderData['price']);
        $request->setPaidPrice($orderData['paid_price']);
        $request->setCurrency(\Iyzipay\Model\Currency::TL);
        $request->setInstallment(1);
        $request->setBasketId($orderData['basket_id']);
        $request->setPaymentChannel(\Iyzipay\Model\PaymentChannel::WEB);
        $request->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);

        // Kart bilgileri
        $paymentCard = new PaymentCard();
        $paymentCard->setCardHolderName($cardData['card_holder_name']);
        $paymentCard->setCardNumber($cardData['card_number']);
        $paymentCard->setExpireMonth($cardData['expire_month']);
        $paymentCard->setExpireYear($cardData['expire_year']);
        $paymentCard->setCvc($cardData['cvc']);
        $paymentCard->setRegisterCard(0);
        $request->setPaymentCard($paymentCard);

        // Alıcı bilgileri
        $buyer = new Buyer();
        $buyer->setId($orderData['buyer']['id']);
        $buyer->setName($orderData['buyer']['name']);
        $buyer->setSurname($orderData['buyer']['surname']);
        $buyer->setGsmNumber($orderData['buyer']['gsm_number']);
        $buyer->setEmail($orderData['buyer']['email']);
        $buyer->setIdentityNumber($orderData['buyer']['identity_number']);
        $buyer->setLastLoginDate($orderData['buyer']['last_login_date']);
        $buyer->setRegistrationDate($orderData['buyer']['registration_date']);
        $buyer->setRegistrationAddress($orderData['buyer']['registration_address']);
        $buyer->setIp($orderData['buyer']['ip']);
        $buyer->setCity($orderData['buyer']['city']);
        $buyer->setCountry($orderData['buyer']['country']);
        $buyer->setZipCode($orderData['buyer']['zip_code']);
        $request->setBuyer($buyer);

        // Teslimat adresi
        $shippingAddress = new Address();
        $shippingAddress->setContactName($orderData['shipping_address']['contact_name']);
        $shippingAddress->setCity($orderData['shipping_address']['city']);
        $shippingAddress->setCountry($orderData['shipping_address']['country']);
        $shippingAddress->setAddress($orderData['shipping_address']['address']);
        $shippingAddress->setZipCode($orderData['shipping_address']['zip_code']);
        $request->setShippingAddress($shippingAddress);

        // Fatura adresi
        $billingAddress = new Address();
        $billingAddress->setContactName($orderData['billing_address']['contact_name']);
        $billingAddress->setCity($orderData['billing_address']['city']);
        $billingAddress->setCountry($orderData['billing_address']['country']);
        $billingAddress->setAddress($orderData['billing_address']['address']);
        $billingAddress->setZipCode($orderData['billing_address']['zip_code']);
        $request->setBillingAddress($billingAddress);

        // Sepet ürünleri
        $basketItems = [];
        foreach ($orderData['basket_items'] as $item) {
            $basketItem = new BasketItem();
            $basketItem->setId($item['id']);
            $basketItem->setName($item['name']);
            $basketItem->setCategory1($item['category']);
            $basketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
            $basketItem->setPrice($item['price']);
            $basketItems[] = $basketItem;
        }
        $request->setBasketItems($basketItems);

        // Ödeme işlemini gerçekleştir
        $payment = Payment::create($request, $this->options);

        return $payment;
    }
}
