<?php

namespace App\Form;

use App\Service\TermsManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class TermsType extends AbstractType
{
    protected $termsManager;

    public function __construct(TermsManager $termsManager)
    {
        $this->termsManager = $termsManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('contents' , TextareaType::class, [
            'constraints' => [
                new NotBlank()
            ],
            'data' => $this->termsManager->getTerms()
        ]);
    }
}