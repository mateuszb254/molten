<?php

namespace App\Controller\Admin;

use App\Entity\DownloadLink;
use App\Form\DownloadLinkType;
use App\Repository\DownloadLinkRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @Route("/acp/download")
 * @Security("has_role('ROLE_ADMIN')")
 */
class DownloadController extends AbstractController implements AdminControllerInterface
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @Route("/", name="admin_download_manage")
     */
    public function index(Request $request, DownloadLinkRepository $downloadLinkRepository): Response
    {
        $links = $downloadLinkRepository->findAll();

        return $this->render('admin/download/index.html.twig', [
            'links' => $links
        ]);
    }

    /**
     * @Route("/add", name="admin_download_add")
     */
    public function add(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(DownloadLinkType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var $downloadLink DownloadLink */
            $downloadLink = $form->getData();

            $em->persist($downloadLink);
            $em->flush();

            $this->addFlash('success', $this->translator->trans('download.links.add.success'));
            return $this->redirectToRoute('admin_download_add');
        }

        return $this->render('admin/download/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="admin_download_edit")
     */
    public function edit(Request $request, DownloadLink $downloadLink): Response
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(DownloadLinkType::class, $downloadLink);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var $downloadLink DownloadLink */
            $downloadLink = $form->getData();

            $em->persist($downloadLink);
            $em->flush();

            $this->addFlash('success', $this->translator->trans('download.links.edit.success'));
            return $this->redirectToRoute('admin_download_manage');
        }

        return $this->render('admin/download/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="admin_download_delete")
     */
    public function delete(Request $request, DownloadLink $downloadLink): Response
    {
        if ($this->isCsrfTokenValid('delete' . $downloadLink->getId(), $request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($downloadLink);
            $em->flush();

            $this->addFlash('success', 'download.links.remove.success');
        }

        return $this->redirectToRoute('admin_download_manage');

    }
}