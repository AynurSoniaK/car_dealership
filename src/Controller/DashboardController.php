<?php

namespace App\Controller;

use App\Entity\Car;
use App\Form\CarType;
use App\Repository\CarRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="app_dashboard")
     */
    public function index(CarRepository $CarRepo): Response
    {
        $cars = $CarRepo->findAll();

        return $this->render('dashboard/index.html.twig', [
            "cars" => $cars,
        ]);
    }

    /**
     * @Route("/create_car/{id}", name="car_create", defaults={"id"=null})
     * @Route("/modify_car/{id}", name="car_modify")
     */
    public function create_car($id, Request $request, EntityManagerInterface $manager)
    {
        $car = $id ? $manager->getRepository(Car::class)->find($id) : new Car();

        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($car);
            $manager->flush();

            return $this->redirectToRoute("app_dashboard");
        }

        return $this->render('dashboard/create_modify_car.html.twig', [
            "formCar" => $form->createView(),
            "car" => $car,
            "modification" => $car->getId() !== null
        ]);
    }


    /**
     * @Route("/delete_car/{id}", name="car_delete")
     */
    public function delete_car($id, EntityManagerInterface $manager, CarRepository $carRepository)
    {
        $car = $carRepository->find($id);

        if (!$car) {
            throw $this->createNotFoundException('The car does not exist');
        }

        $manager->remove($car);
        $manager->flush();

        return $this->redirectToRoute("app_dashboard");
    }
}
