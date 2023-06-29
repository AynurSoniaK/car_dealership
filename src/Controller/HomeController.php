<?php

namespace App\Controller;

use App\Service\CallApiService;
use App\Repository\CarRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CarCategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(EntityManagerInterface $em, PaginatorInterface $paginator, Request $request, CallApiService $callApiService, CarRepository $CarRepo, CarCategoryRepository $CarCategoryRepo): Response
    {
        $weatherData = $callApiService->getWeatherData();
        $searchTerm = $request->query->get('search');
        $categories = $CarCategoryRepo->findAll();
        $categoryIds = (array) $request->query->get('categories');
        $query = $CarRepo->getFilteredQuery($searchTerm, $categoryIds);
        
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), 
            20 
        );

        return $this->render('home/index.html.twig', [
            'weatherData' => $weatherData,
            'pagination' => $pagination,
            'searchTerm' => $searchTerm,
            'categories' => $categories,
            'selectedCategories' => $categoryIds,
        ]);
    }
}
