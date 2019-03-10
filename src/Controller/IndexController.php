<?php

namespace App\Controller;

use App\Repository\CommitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /** @var CommitRepository */
    private $commitRepository;

    public function __construct(CommitRepository $commitRepository)
    {
        $this->commitRepository = $commitRepository;
    }

    /**
     * @Route("/{page}", name="index")
     * @param int|null $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(int $page = null)
    {
        $commits = $this->commitRepository->getPagedResults($page);

        return $this->render('index/index.html.twig', [
            'commits' => $commits,
        ]);
    }
}
