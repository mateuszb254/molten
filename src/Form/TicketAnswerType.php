<?php
namespace App\Form;

use App\Entity\TicketAnswer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketAnswerType extends AbstractType
{
    private const ADMIN_ROUTE_NAME = 'admin_ticket_show';

    private $request;

    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('content', TextareaType::class, [
            'label' => 'label.support_answer'
        ]);

        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $formEvent) {
            $form = $formEvent->getForm();

            if($this->request->get('_route') === self::ADMIN_ROUTE_NAME) {
                $form->add('status', CheckboxType::class, [
                    'label' => 'label.support.block_answers',
                    'mapped' => false,
                    'required' => false,
                ]);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TicketAnswer::class
        ]);
    }
}