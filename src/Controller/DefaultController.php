<?php
declare(strict_types=1);

namespace App\Controller;

use App\Document\Repository;
use App\Document\User;
use App\Document\Package;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="index-page")
     */
    function index(Request $request): Response
    {
        return new Response(phpinfo());
    }

    /**
     * @Route("/list", methods={"GET"}, name="mongo-list")
     */
    function list(DocumentManager $dm): Response
    {
        $users = $dm->getRepository(User::class)->findAll();

        return new JsonResponse($users);
    }

    /**
     * @Route("/create", methods={"GET"}, name="mongo-create")
     */
    function create(DocumentManager $dm): Response
    {
        $repo = (new Repository)
            ->setType(Repository::TYPE_COMPOSER)
            ->setUrl('https://packagist.org');

        $dm->persist($repo);

        $package = (new Package)
            ->setNamespace('silex/silex')
            ->setVersion('>1');

        $dm->persist($package);

        $dm->flush();

        return new JsonResponse(['Status' => 'OK']);
    }
}
