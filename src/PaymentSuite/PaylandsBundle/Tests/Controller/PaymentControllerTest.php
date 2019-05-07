<?php

namespace PaymentSuite\PaylandsBundle\Tests\Controller;

use PaymentSuite\PaylandsBundle\Controller\PaymentController;
use PaymentSuite\PaylandsBundle\Exception\CardInvalidException;
use PaymentSuite\PaylandsBundle\Form\Type\PaylandsType;
use PaymentSuite\PaylandsBundle\PaylandsMethod;
use PaymentSuite\PaylandsBundle\Services\Interfaces\PaylandsSettingsProviderInterface;
use PaymentSuite\PaylandsBundle\Services\PaylandsFormFactory;
use PaymentSuite\PaylandsBundle\Services\PaylandsManager;
use PaymentSuite\PaylandsBundle\Services\PaylandsSettingsProviderDefault;
use PaymentSuite\PaymentCoreBundle\Services\Interfaces\PaymentBridgeInterface;
use PaymentSuite\PaymentCoreBundle\ValueObject\RedirectionRoute;
use PaymentSuite\PaymentCoreBundle\ValueObject\RedirectionRouteCollection;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Forms;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PaymentControllerTest extends TestCase
{
    /**
     * @var RedirectionRouteCollection
     */
    private $redirectionRoutes;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var PaymentBridgeInterface
     */
    private $paymentBridge;

    /**
     * @var PaylandsFormFactory
     */
    private $paymentFormFactory;
    /**
     * @var PaylandsSettingsProviderDefault
     */
    private $settingsProvider;

    protected function setUp()
    {
        $this->redirectionRoutes = $this->getRedirectionRoutesCollection();
        $this->urlGenerator = $this->getUrlGeneratorMock();
        $this->paymentBridge = $this->getPaymentBridgeMock();
        $this->paymentFormFactory = new PaylandsFormFactory(
            $this->getFormFactory(),
            $this->urlGenerator,
            $this->getSettingsProviderMock()
        );
        $this->settingsProvider = new PaylandsSettingsProviderDefault('api_key', 'api_signature', [], [], 'validation_service');
    }

    public function testExecuteAction()
    {
        $isValidPaymentMethod = function (PaylandsMethod $method) {
            $this->assertEquals('test-name', $method->getPaymentName());
            $this->assertEquals('123', $method->getCustomerExternalId());
            $this->assertEquals('cust-token', $method->getCustomerToken());
            $this->assertEquals('card-uuid', $method->getCardUuid());
            $this->assertTrue($method->isOnlyTokenizeCard());

            return true;
        };

        $paymentManager = $this->prophesize(PaylandsManager::class);
        $paymentManager
            ->processPayment(Argument::that($isValidPaymentMethod))
            ->shouldBeCalled();

        $controller = new PaymentController(
            $paymentManager->reveal(),
            $this->paymentFormFactory,
            $this->redirectionRoutes,
            $this->paymentBridge,
            $this->urlGenerator,
            $this->settingsProvider
        );

        $request = Request::create('/', 'POST', [
            'paylands' => [
                'customerExternalId' => '123',
                'customerToken' => 'cust-token',
                'cardUuid' => 'card-uuid',
                'onlyTokenizeCard' => true,
            ],
        ]);

        $response = $controller->executeAction($request);

        $this->assertEquals('/pyalands/success', $response->getTargetUrl());
    }

    public function testExecuteActionIfInvalidForm()
    {
        $paymentManager = $this->prophesize(PaylandsManager::class);
        $paymentManager
            ->processPayment(Argument::any())
            ->shouldNotBeCalled();

        $controller = new PaymentController(
            $paymentManager->reveal(),
            $this->paymentFormFactory,
            $this->redirectionRoutes,
            $this->paymentBridge,
            $this->urlGenerator,
            $this->settingsProvider
        );

        $request = Request::create('/', 'POST', []);

        $response = $controller->executeAction($request);

        $this->assertEquals('/pyalands/failure', $response->getTargetUrl());
    }

    public function testExecuteActionIfCardInvalid()
    {
        $isValidPaymentMethod = function (PaylandsMethod $method) {
            $this->assertEquals('test-name', $method->getPaymentName());
            $this->assertEquals('123', $method->getCustomerExternalId());
            $this->assertEquals('cust-token', $method->getCustomerToken());
            $this->assertEquals('card-uuid', $method->getCardUuid());
            $this->assertTrue($method->isOnlyTokenizeCard());

            return true;
        };

        $paymentManager = $this->prophesize(PaylandsManager::class);
        $paymentManager
            ->processPayment(Argument::that($isValidPaymentMethod))
            ->shouldBeCalled()
            ->willThrow(new CardInvalidException());

        $controller = new PaymentController(
            $paymentManager->reveal(),
            $this->paymentFormFactory,
            $this->redirectionRoutes,
            $this->paymentBridge,
            $this->urlGenerator,
            $this->settingsProvider
        );

        $request = Request::create('/', 'POST', [
            'paylands' => [
                'customerExternalId' => '123',
                'customerToken' => 'cust-token',
                'cardUuid' => 'card-uuid',
                'onlyTokenizeCard' => true,
            ],
        ]);

        $response = $controller->executeAction($request);

        $this->assertEquals('/pyalands/card_invalid', $response->getTargetUrl());
    }

    public function testExecuteActionIfProcessPaymentError()
    {
        $isValidPaymentMethod = function (PaylandsMethod $method) {
            $this->assertEquals('test-name', $method->getPaymentName());
            $this->assertEquals('123', $method->getCustomerExternalId());
            $this->assertEquals('cust-token', $method->getCustomerToken());
            $this->assertEquals('card-uuid', $method->getCardUuid());
            $this->assertTrue($method->isOnlyTokenizeCard());

            return true;
        };

        $paymentManager = $this->prophesize(PaylandsManager::class);
        $paymentManager
            ->processPayment(Argument::that($isValidPaymentMethod))
            ->shouldBeCalled()
            ->willThrow(new \Exception());

        $controller = new PaymentController(
            $paymentManager->reveal(),
            $this->paymentFormFactory,
            $this->redirectionRoutes,
            $this->paymentBridge,
            $this->urlGenerator,
            $this->settingsProvider
        );

        $request = Request::create('/', 'POST', [
            'paylands' => [
                'customerExternalId' => '123',
                'customerToken' => 'cust-token',
                'cardUuid' => 'card-uuid',
                'onlyTokenizeCard' => true,
            ],
        ]);

        $response = $controller->executeAction($request);

        $this->assertEquals('/pyalands/failure', $response->getTargetUrl());
    }

    /**
     * @return UrlGeneratorInterface
     */
    private function getUrlGeneratorMock()
    {
        $urlGenerator = $this->prophesize(UrlGeneratorInterface::class);
        $urlGenerator
            ->generate('paymentsuite_paylands_execute')
            ->willReturn('/paylands/execute');
        $urlGenerator
            ->generate('success', Argument::cetera())
            ->willReturn('/pyalands/success');
        $urlGenerator
            ->generate('failure', Argument::cetera())
            ->willReturn('/pyalands/failure');
        $urlGenerator
            ->generate('card_invalid', Argument::cetera())
            ->willReturn('/pyalands/card_invalid');

        return $urlGenerator->reveal();
    }

    /**
     * @return RedirectionRouteCollection
     */
    private function getRedirectionRoutesCollection()
    {
        $redirectionRoutes = new RedirectionRouteCollection();
        $redirectionRoutes->addRedirectionRoute(new RedirectionRoute('success', false, ''), 'success');
        $redirectionRoutes->addRedirectionRoute(new RedirectionRoute('failure', false, ''), 'failure');
        $redirectionRoutes->addRedirectionRoute(new RedirectionRoute('card_invalid', false, ''), 'card_invalid');

        return $redirectionRoutes;
    }

    /**
     * @return PaylandsSettingsProviderInterface
     */
    private function getSettingsProviderMock()
    {
        $settingsProvider = $this->prophesize(PaylandsSettingsProviderInterface::class);
        $settingsProvider
            ->getPaymentName()
            ->willReturn('test-name');

        return $settingsProvider->reveal();
    }

    /**
     * @return FormFactoryInterface
     */
    private function getFormFactory()
    {
        $formFactory = Forms::createFormFactoryBuilder()
            ->addType(new PaylandsType())
            ->addExtension(new HttpFoundationExtension())
            ->getFormFactory();

        return $formFactory;
    }

    /**
     * @return PaymentBridgeInterface
     */
    private function getPaymentBridgeMock()
    {
        $paymentBridge = $this->prophesize(PaymentBridgeInterface::class);

        return $paymentBridge->reveal();
    }
}
