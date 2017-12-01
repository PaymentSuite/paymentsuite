<?php

namespace PaymentSuite\PaylandsBundle\Services;

use PaymentSuite\PaylandsBundle\Exception\CreateFormException;
use PaymentSuite\PaylandsBundle\Form\Type\PaylandsType;
use PaymentSuite\PaylandsBundle\PaylandsMethod;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class PaylandsFormFactory.
 *
 * @author Santi Garcia <sgarcia@wearemarketing.com>, <sangarbe@gmail.com>
 */
class PaylandsFormFactory
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * PaylandsFormFactory constructor.
     *
     * @param FormFactoryInterface  $formFactory
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(FormFactoryInterface $formFactory, UrlGeneratorInterface $urlGenerator)
    {
        $this->formFactory = $formFactory;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * Creates the payment form.
     *
     * @param mixed $data
     * @param array $options
     *
     * @return FormInterface
     *
     * @throws CreateFormException
     */
    public function create($data = null, array $options = [])
    {
        $options = array_merge($options, [
            'action' => $this->urlGenerator->generate('paymentsuite_paylands_execute'),
            'method' => 'POST',
        ]);

        if (is_array($data)) {
            $data = $this
                ->formFactory
                ->create(PaylandsType::class)
                ->submit($data, true)
                ->getData();
        }

        if (is_null($data) || $data instanceof PaylandsMethod) {
            return $this->formFactory->create(PaylandsType::class, $data, $options);
        }

        throw new CreateFormException();
    }
}
