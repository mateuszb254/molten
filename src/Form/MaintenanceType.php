<?php

namespace App\Form;

use App\Form\Model\Maintenanc;
use App\Service\MaintenanceManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class MaintenanceType extends AbstractType
{
    private $maintenanceManager;

    public function __construct(MaintenanceManager $maintenanceManager)
    {
        $this->maintenanceManager = $maintenanceManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('status', ChoiceType::class, [
            'choices' => [
                'turn.on' => true,
                'turn.off' => false
            ],
            'data' => !$this->maintenanceManager->getMaintenanceStatus(),
            'label' => 'label.maintenance.status'
        ]);
    }
}