<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\Equipment;
use App\Entity\Feature;
use App\Entity\Gallery;
use App\Entity\Option;
use App\Entity\User;
use App\Form\CarType;
use App\Repository\CarRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/car')]
#[IsGranted("ROLE_USER")]
class CarController extends AbstractController
{
    public function __construct(
        private Security $security,
        private EntityManagerInterface $em,
        private RequestStack $requestStack,
        Private AuthorizationCheckerInterface $authChecker
    )
    {
    }

    #[Route('/', name: 'app_car_index', methods: ['GET'])]
    public function index(CarRepository $carRepository): Response
    {
        $user = $this->security->getUser();
        if ($user && $user->getRoles() === ['ROLE_USER']) {
            $cars = $carRepository->findByUser($user);
        } else {
            $cars = $carRepository->findAll();
        }

        return $this->render('car/index.html.twig', [
            'cars' => $cars,
        ]);
    }

    #[Route('/new', name: 'app_car_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $car = new Car();
        $feature = new Feature();
        $option = new Option();
        $equipment = new Equipment();

        $car->addFeature($feature);
        $car->addOption($option);
        $car->addEquipment($equipment);
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $userInterface = $this->security->getUser();

            if (method_exists($userInterface, 'getId')) {
                $userId = $userInterface->getId();
                $user = $entityManager->getRepository(User::class)->find($userId);
                if ($user) {
                    $car->setUser($user);
                }
            }

            // Convertir UserInterface en entité User
            if ($userInterface instanceof UserInterface) {
                $username = $userInterface->getUsername();
                $user = $entityManager->getRepository(User::class)->findOneByUsername($username);
                if ($user) {
                    $car->setUser($user);
                }
            }

            $entityManager->persist($car);

            // Handle Galleries
            $galleriesData = $form->get('galleries')->getData();
            if (!empty($galleriesData)) {
                foreach ($galleriesData as $galleryData) {
                    if (!empty($galleryData)) {
                        $gallery = new Gallery();
                        $gallery->setImageFile($galleryData);
                        $gallery->setCar($car);
                        $entityManager->persist($gallery);
                    }
                }
            }

            // Handle Features
            $featuresData = $form->get('features')->getData();
            if (!empty($featuresData)) {
                foreach ($featuresData as $featureData) {
                    if ($featureData instanceof Feature && $featureData->getTitle() !== null) {
                        $feature = new Feature();
                        $feature->setTitle($featureData->getTitle());
                        $entityManager->persist($feature);
                        $car->addFeature($feature);
                    }
                }
            }

            // Handle Options
            $optionsData = $form->get('options')->getData();
            if (!empty($optionsData)) {
                foreach ($optionsData as $optionData) {
                    if ($optionData instanceof Feature && $optionData->getTitle() !== null) {
                        $option = new Option();
                        $option->setTitle($optionData->getTitle());
                        $entityManager->persist($option);
                        $car->addOption($option);
                    }
                }
            }

            // Handle Equipment
            $equipmentData = $form->get('equipment')->getData();
            if (!empty($equipmentData)) {
                foreach ($equipmentData as $equipmentItem) {
                    if ($equipmentItem instanceof Feature && $equipmentItem->getTitle() !== null) {
                        $equipment = new Equipment();
                        $equipment->setTitle($equipmentItem->getTitle());
                        $entityManager->persist($equipment);
                        $car->addEquipment($equipment);
                    }
                }
            }

            // Persist all changes to the database
            $entityManager->flush();
            $this->addFlash('success', "La mise à jour a été effectuée avec succès.");
            return $this->redirectToRoute('app_car_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('car/new.html.twig', [
            'car' => $car,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_car_show', methods: ['GET'])]
    public function show(Car $car): Response
    {
        return $this->render('car/show.html.twig', [
            'car' => $car,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_car_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Car $car, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_car_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('car/edit.html.twig', [
            'car' => $car,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_car_delete', methods: ['POST'])]
    public function delete(Request $request, Car $car, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $car->getId(), $request->request->get('_token'))) {
            $entityManager->remove($car);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_car_index', [], Response::HTTP_SEE_OTHER);
    }

}
