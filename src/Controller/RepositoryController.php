<?php

namespace App\Controller;

use App\Document\Repository;
use App\Form\RepositoryType;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/repository")
 */
class RepositoryController extends AbstractController
{
    /**
     * @Route("/", name="repository_index", methods={"GET"})
     */
    public function index(DocumentManager $dm): Response
    {
        $repositories = $dm
            ->getRepository(Repository::class)
            ->findAll();

        return $this->render(
            'repository/index.html.twig',
            [
                'repositories' => $repositories,
            ]
        );
    }

    /**
     * @Route("/new", name="repository_new", methods={"GET","POST"})
     */
    public function new(DocumentManager $dm, Request $request): Response
    {
        $repository = new Repository();
        $form = $this->createForm(RepositoryType::class, $repository);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dm->persist($repository);
            $dm->flush();

            return $this->redirectToRoute('repository_index');
        }

        return $this->render(
            'repository/new.html.twig',
            [
                'repository' => $repository,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="repository_show", methods={"GET"})
     */
    public function show(DocumentManager $dm, string $id): Response
    {
        if (!$repository = $dm->getRepository(Repository::class)->find($id)) {
            throw new NotFoundHttpException();
        }

        return $this->render(
            'repository/show.html.twig',
            [
                'repository' => $repository,
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="repository_edit", methods={"GET","POST"})
     */
    public function edit(DocumentManager $dm, Request $request, string $id): Response
    {
        if (!$repository = $dm->getRepository(Repository::class)->find($id)) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(RepositoryType::class, $repository);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dm->flush();

            return $this->redirectToRoute(
                'repository_index',
                [
                    'id' => $repository->getId(),
                ]
            );
        }

        return $this->render(
            'repository/edit.html.twig',
            [
                'repository' => $repository,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="repository_delete", methods={"DELETE"})
     */
    public function delete(DocumentManager $dm, Request $request, string $id): Response
    {
        if (!$repository = $dm->getRepository(Repository::class)->find($id)) {
            throw new NotFoundHttpException();
        }

        if ($this->isCsrfTokenValid('delete' . $repository->getId(), $request->request->get('_token'))) {
            $dm->remove($repository);
            $dm->flush();
        }

        return $this->redirectToRoute('repository_index');
    }
}
