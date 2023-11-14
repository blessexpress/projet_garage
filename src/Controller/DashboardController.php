<?php

namespace App\Controller;

use App\Repository\CarRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dashboard')]
class DashboardController extends AbstractController
{
    #[Route('/', name: 'app_dashboard')]
    public function index(): Response
    {
        if ($this->isGranted('ROLE_USER') ) {
            return $this->redirectToRoute('app_dashboard_admin');
        }

        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }

    #[Route(path: '/admin', name: 'app_dashboard_admin')]
    public function admin(CarRepository $carRepository,UserRepository $userRepository): Response
    {
        $countUsers = $userRepository->countAllUsers();
        $countCar=$carRepository->CountCar();

        return $this->render('dashboard/index.html.twig', [
            'countUsers' => $countUsers,
            'countCar' => $countCar,
        ]);

    }
}
