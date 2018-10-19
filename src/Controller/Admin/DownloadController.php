<?php

namespace App\Controller\Admin;

use App\Entity\DownloadLink;
use App\Form\DownloadLinkCollectionType;
use App\Form\DownloadLinkType;
use Doctrine\Common\Collections\ArrayCollection;
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
    public function index(Request $request): Response
    {
        $form = $this->createForm(DownloadLinkCollectionType::class);
        /** @var DownloadLink[] $links */
        $originalLinks = $form->get('links')->getData();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $links = $form->get('links')->getData();

            $em = $this->getDoctrine()->getManager();

            foreach ($links as $link) {
                if (!in_array($link, $originalLinks)) $em->persist($link);
            }

            foreach ($originalLinks as $originalLink) {
                if (!in_array($originalLink, $links)) $em->remove($originalLink);
            }

            $em->flush();

            return $this->redirectToRoute('admin_download_manage');
        }

        return $this->render('admin/download/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}