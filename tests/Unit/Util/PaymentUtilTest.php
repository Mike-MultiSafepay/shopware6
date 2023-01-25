<?php declare(strict_types=1);
/**
 * Copyright © 2019 MultiSafepay, Inc. All rights reserved.
 * See DISCLAIMER.md for disclaimer details.
 */

namespace MultiSafepay\Shopware6\Tests\Unit\Util;

use MultiSafepay\Shopware6\PaymentMethods\Generic;
use MultiSafepay\Shopware6\PaymentMethods\Generic2;
use MultiSafepay\Shopware6\PaymentMethods\Generic3;
use MultiSafepay\Shopware6\PaymentMethods\Generic4;
use MultiSafepay\Shopware6\PaymentMethods\Generic5;
use MultiSafepay\Shopware6\PaymentMethods\PaymentMethodInterface;
use MultiSafepay\Shopware6\Util\PaymentUtil;
use PHPUnit\Framework\TestCase;

class PaymentUtilTest extends TestCase
{
    /**
     * @return void
     */
    public function testPaymentMethodsHavingCorrectInterface(): void
    {
        foreach (PaymentUtil::GATEWAYS as $gateway) {
            /** @var PaymentMethodInterface $paymentMethod */
            $paymentMethod = new $gateway();
            $this->assertInstanceOf(PaymentMethodInterface::class, $paymentMethod);
        }
    }

    /**
     * Test if a gateway has a template
     */
    public function testPaymentMethodsHavingATemplateStringOrNull()
    {
        foreach (PaymentUtil::GATEWAYS as $gateway) {
            /** @var PaymentMethodInterface $paymentMethod */
            $paymentMethod = new $gateway();
            //Don't test iDEAL because ideal has a template.
            if ($paymentMethod->getTemplate() === null) {
                $this->assertNull($paymentMethod->getTemplate());
                continue;
            }

            $this->assertStringStartsWith('@MltisafeMultiSafepay', $paymentMethod->getTemplate());
        }
    }

    /**
     * Test if Payment Methods have the correct payment handler
     *
     * @throws \ReflectionException
     */
    public function testPaymentMethodsHavingCorrectPaymentHandler()
    {
        foreach (PaymentUtil::GATEWAYS as $gateway) {
            if (in_array($gateway, [Generic2::class, Generic3::class, Generic4::class, Generic5::class])) {
                //These are different cases, we can skip them for now
                continue;
            }
            $paymentMethod = new $gateway();
            $gatewayClassName = (new \ReflectionClass($paymentMethod))->getShortName();
            $classToFind = '\MultiSafepay\Shopware6\Handlers\\' . $gatewayClassName . 'PaymentHandler';
            $this->assertTrue(class_exists($classToFind), $classToFind);
        }
    }
}
