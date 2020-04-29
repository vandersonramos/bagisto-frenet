<?php

namespace VandersonRamos\Frenet\Carriers;

use VandersonRamos\Frenet\Helper\Data;
use Webkul\Shipping\Carriers\AbstractShipping;
use Webkul\Checkout\Models\CartShippingRate;
use Webkul\Checkout\Facades\Cart;


class Carrier extends AbstractShipping
{

    protected $code = 'vandersonramos_frenet';
    private $is_centimeters = false;
    private $is_grams = false;
    private $api;

    /**
     * Check some data before starting
     * Carrier constructor.
     */
    public function __construct()
    {
        if (!$this->isAvailable()) {
            return false;
        }

        if ($this->getConfigData('dimension_type') === 'cm') {
            $this->is_centimeters = true;
        }

        if ($this->getConfigData('weight_type') === 'gr') {
            $this->is_grams = true;
        }

        $token = $this->getConfigData('token');
        $this->api = \Frenet\ApiFactory::create($token);

    }

    /**
     * @return array
     */
    public function calculate(): array
    {
        /** @var \Webkul\Checkout\Models\Cart $cart */
        $cart = Cart::getCart();
        $rates = [];

        /**
         * Here we will create a quote request for sending to API.
         *
         * @var \Frenet\Command\Shipping\QuoteInterface $quote
         */
        $quote = $this->api->shipping()->quote()
            ->setRecipientCountry('BR')
            ->setSellerPostcode(Data::cleanZipCode(core()->getConfigData('sales.shipping.origin.zipcode')))
            ->setRecipientPostcode(Data::cleanZipCode($cart->shipping_address->postcode))
            ->setShipmentInvoiceValue(number_format($cart->grand_total, 2,'.', ''));


        $items = $cart->items()->get()->filter(function($item) {
            return $item->type === 'simple';
        })->values();


        foreach ($items as $item) {

            if ($this->is_centimeters) {

                $length = ($item->product->depth  ?: Data::DEFAULT_LENGTH);
                $height = ($item->product->height ?: Data::DEFAULT_HEIGHT);
                $width  = ($item->product->width  ?: Data::DEFAULT_WIDTH);

            } else {

                $length = ($item->product->depth  ? $item->product->depth  * 100 : Data::DEFAULT_LENGTH * 100);
                $height = ($item->product->height ? $item->product->height * 100 : Data::DEFAULT_HEIGHT * 100);
                $width  = ($item->product->width  ? $item->product->width  * 100 : Data::DEFAULT_WIDTH * 100);
            }

            $weight = $this->getTotalPackageWeight($item->weight);

            $quote->addShippingItem($item->sku, $item->quantity, $weight, $length, $height, $width, null);
        }

        /**
         * The method `execute()` sends the request and parse the body result to a object type.
         *
         * @var \Frenet\ObjectType\Entity\Shipping\QuoteInterface $result
         */
        $result = $quote->execute();
        $services = $result->getShippingServices();

        /** @var \Frenet\ObjectType\Entity\Shipping\Quote\ServiceInterface $service */
        foreach ($services as $service) {

            if (!$service->isError()) {
                $rates[] = $this->appendShippingReturn($service);
            }
        }

        return $rates;
    }

    /**
     * Append shipping value to return
     * @param object $carrier
     * @return object
     */
    protected function appendShippingReturn(object $carrier): object
    {
        $shippingRate = new CartShippingRate;
        $deliveryTime = null;
        $shippingRate->carrier = $this->code;
        $shippingRate->carrier_title = $this->getConfigData('title');
        $shippingRate->method = $carrier->getServiceCode();

        if ($this->getConfigData('show_delivery_time')) {
            $deliveryTime = $carrier->getDeliveryTime() + (int) $this->getConfigData('add_days');
        }

        $shippingRate->method_title = Data::formatTitle(
            $carrier->getCarrier(),
            $carrier->getServiceDescription(),
            $deliveryTime
        );

        $shippingRate->price = $carrier->getShippingPrice();
        $shippingRate->base_price = $carrier->getShippingPrice();

        return $shippingRate;
    }

    /**
     * @param $weight
     * @return float
     */
    protected function getTotalPackageWeight($weight): float
    {
        if ($this->is_grams) {
            return Data::fixPackageWeight($weight);
        }

        return floatval($weight);
    }
}