<?php

namespace App\Controller;

use App\Repository\CommitRepository;
use Doctrine\DBAL\DBALException;
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
    public function index($page = 1)
    {
        try {
            $commits = $this->commitRepository->getPagedResults($page);
        } catch (\TypeError $e) {
            return $this->redirectToRoute('index');
        } catch (DBALException $e) {
            return $this->redirectToRoute('index');
        }

        $lastPage = $this->commitRepository->getLastPageNumber();

        if (empty($commits)) {
            return $this->redirectToRoute('index');
        }

        return $this->render('index/index.html.twig', [
            'commits' => $commits,
            'page' => $page ?? 1,
            'lastPage' => $lastPage,
        ]);
    }
}
