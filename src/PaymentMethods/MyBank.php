<?php declare(strict_types=1);
/**
 * Copyright © 2022 MultiSafepay, Inc. All rights reserved.
 * See DISCLAIMER.md for disclaimer details.
 */
namespace MultiSafepay\Shopware6\PaymentMethods;

use MultiSafepay\Shopware6\Handlers\MyBankPaymentHandler;

class MyBank implements PaymentMethodInterface
{
    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getName(): string
    {
        return 'MyBank - Bonifico Immediato';
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getDescription(): string
    {
        return 'Pay with ' . $this->getName();
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getPaymentHandler(): string
    {
        return MyBankPaymentHandler::class;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getGatewayCode(): string
    {
        return 'MYBANK';
    }

    /**
     * {@inheritDoc}
     *
     * @return string|null
     */
    public function getTemplate(): ?string
    {
        return null;
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getMedia(): string
    {
        return __DIR__  . '/../Resources/views/storefront/multisafepay/logo/mybank.png';
    }

    /**
     * {@inheritDoc}
     *
     * @return array
     */
    public function getTranslations(): array
    {
        return [
            'de-DE' => [
                'name'        => $this->getName(),
                'description' => 'Bezahlen mit '. $this->getName(),
            ],
            'en-GB' => [
                'name'        => $this->getName(),
                'description' => $this->getDescription(),
            ],
        ];
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getType(): string
    {
        return 'redirect';
    }
}
