<?php

namespace App\Form\Type;

use App\Validator\Constraints\Recaptcha;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * This class is used to render recaptcha field
 * more: https://developers.google.com/recaptcha/docs/display
 */
class RecaptchaType extends AbstractType
{
    /**
     * Public key provided by Google
     * Set in service.yaml as 'repcatcha.public_key'
     * @var string
     */
    protected $publicKey;

    /**
     * Specifies if recaptcha should be checked
     * Set in service.yaml as 'recaptcha.enabled'
     *
     * @var $enabled
     */
    protected $enabled;

    /**
     * RecaptchaType constructor.
     * @param string $publicKey
     * @param bool $enabled
     */
    public function __construct(string $publicKey, bool $enabled)
    {
        $this->publicKey = $publicKey;
        $this->enabled = $enabled;
    }

    /**
     * It prevents to display RecaptchaType if `enable` flag is set as false
     *
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (!$this->enabled) {
            return;
        }

        $view->vars['attr']['data-sitekey'] = $this->publicKey;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => false,
            'mapped' => false,
            'attr' => [
                'class' => 'g-recaptcha',
                'data-sitekey' => null,
                'data-theme' => 'dark'
            ],
            'constraints' => new Recaptcha(),
            'error_bubbling' => false
        ]);
    }
}