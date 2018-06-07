<?php

namespace App\Controller\Admin;

use App\Form\MaintenanceType;
use App\Service\MaintenanceManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @Route("/acp")
 * @Security("has_role('ROLE_ADMIN')")
 */
class MaintenanceController extends Controller implements AdminControllerInterface
{
    /**
     * @Route("/maintenance", name="admin_maintenance")
     */
    public function index(Request $request, MaintenanceManager $maintenanceManager, TranslatorInterface $translator): Response
    {
        $form = $this->createForm(MaintenanceType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $status = $form->getData()['status'];

            $maintenanceManager->setMaintenanceStatus($status);
            $maintenanceManager->updateMaintenance();

            $this->addFlash('success', $translator->trans($status ? 'maintenance.turned.on' : 'maintenance.turned.off'));
            return $this->redirectToRoute('admin_maintenance');
        }


        return $this->render('admin/maintenance/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}