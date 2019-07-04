<?php

namespace App\Controller;

use App\Document\Requirement;
use App\Form\PackageType;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/package")
 */
class RequirementController extends AbstractController
{
    /**
     * @Route("/", name="package_index", methods={"GET"})
     */
    public function index(DocumentManager $dm): Response
    {
        return $this->render(
            'package/index.html.twig',
            [
                'packages' => $dm->getRepository(Requirement::class)->findAll(),
            ]
        );
    }

    /**
     * @Route("/new", name="package_new", methods={"GET","POST"})
     */
    public function new(DocumentManager $dm, Request $request): Response
    {
        $package = new Requirement();
        $form = $this->createForm(PackageType::class, $package);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dm->persist($package);
            $dm->flush();

            return $this->redirectToRoute('package_index');
        }

        return $this->render(
            'package/new.html.twig',
            [
                'package' => $package,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="package_show", methods={"GET"})
     */
    public function show(DocumentManager $dm, string $id): Response
    {
        if (!$package = $dm->getRepository(Requirement::class)->find($id)) {
            throw new NotFoundHttpException();
        }

        return $this->render(
            'package/show.html.twig',
            [
                'package' => $package,
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="package_edit", methods={"GET","POST"})
     */
    public function edit(DocumentManager $dm, Request $request, string $id): Response
    {
        if (!$package = $dm->getRepository(Requirement::class)->find($id)) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(PackageType::class, $package);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dm->flush();

            return $this->redirectToRoute(
                'package_index',
                [
                    'id' => $package->getId(),
                ]
            );
        }

        return $this->render(
            'package/edit.html.twig',
            [
                'package' => $package,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="package_delete", methods={"DELETE"})
     */
    public function delete(DocumentManager $dm, Request $request, string $id): Response
    {
        if (!$package = $dm->getRepository(Requirement::class)->find($id)) {
            throw new NotFoundHttpException();
        }

        if ($this->isCsrfTokenValid('delete' . $package->getId(), $request->request->get('_token'))) {
            $dm->remove($package);
            $dm->flush();
        }

        return $this->redirectToRoute('package_index');
    }
}
