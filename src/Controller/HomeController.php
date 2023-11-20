<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\Contact;
use App\Entity\Service;
use App\Entity\Testimonial;
use App\Form\ContactType;
use App\Form\TestimonialType;
use App\Repository\CarRepository;
use App\Repository\ServiceRepository;
use App\Service\MailerService;
use App\Twig\Extension\AppExtension;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\EventListener\AbstractSessionListener;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class HomeController extends AbstractController
{
    private $em;
    private $mailerService;
    private $requestStack;
    private $twig;
    private $serviceRepository;
    private $carRepository;

    public function __construct(EntityManagerInterface $em, MailerService $mailerService, private UrlGeneratorInterface $router,
                                RequestStack $requestStack, ServiceRepository $serviceRepository, Environment $twig,CarRepository $carRepository)
    {
        $this->em = $em;
        $this->mailerService = $mailerService;
        $this->requestStack = $requestStack;
        $this->twig = $twig;
        $this->serviceRepository = $serviceRepository;
        $this->carRepository = $carRepository;

    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $response = new Response(); // A supprimer apres fin dev
        $testimonial = $this->em->getRepository(Testimonial::class)
            ->findBy(['approved' => true], ['id' => 'ASC']);

        $lastSixCars = $this->carRepository->findLastSixCars();

        $response->headers->set(AbstractSessionListener::NO_AUTO_CACHE_CONTROL_HEADER, 'true'); // A supprimer apres fin dev

        return $this->render('home/index.html.twig', [
            'approvedTestimonials' => $testimonial,
            'offres' => $lastSixCars,
        ]);
    }


    #[Route('vehicule/{id}', name: 'app_vehicule_show', methods: ['GET', 'POST'])]
    public function show(Car $car,Request $requete): Response
    {
        $erreurs = false;
        $recaptcha_keys_public = $this->getParameter('recaptcha_keys_public');
        $contact = new Contact();
        $contact->setSubject($car->getName() ?? '');
        $formulaire = $this->createForm(ContactType::class,$contact);

        $formulaire->handleRequest($requete);

        if ($formulaire->isSubmitted()) {
            if ($formulaire->isValid()) {

                $token = $formulaire->getData();
                $recaptcha_keys_secret = $this->getParameter('recaptcha_keys_secret');

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('secret' => $recaptcha_keys_secret, 'response' => $token)));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                curl_close($ch);
                $arrResponse = json_decode($response, true);

                $contact = $formulaire->getData();

                $this->em->persist($contact);
                $this->em->flush();

                if ($this->mailerService->envoyerContact($contact) !== 0) {

                    $this->mailerService->envoyerAccuse($contact);
                    $this->addFlash('success', 'Votre message a été envoyé');
                } else {
                    $this->addFlash('danger', "Erreur lors de l'envoie de lors de l'envoi de la demande");
                }

                return $this->redirectToRoute('app_vehicule_show', ['id' => $car->getId()]);
            }
        }

        return $this->render('garage/detail_vehicule.html.twig', [
            'car' => $car,
            'formulaire' => $formulaire->createView(),
            'erreurs' => $erreurs,
            'recaptcha_keys_public' => $recaptcha_keys_public,
        ]);
    }

    #[Route('/services', name: 'app_services')]
    public function services(): Response
    {
        $response = new Response(); // A supprimer apres fin dev
        $services = $this->em->getRepository(Service::class)
            ->findBy([], ['id' => "ASC"]);
        $response->headers->set(AbstractSessionListener::NO_AUTO_CACHE_CONTROL_HEADER, 'true'); // A supprimer apres fin dev

        return $this->render('garage/service.html.twig', [
            'services' => $services
        ]);
    }

    #[Route('/vehicule', name: 'app_vehicule')]
    public function vehicule(): Response
    {
        return $this->render('garage/vehicules.html.twig', [
            'controller_name' => 'Garage V Parrot',
        ]);
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(Request $requete)
    {
        $erreurs = false;
        $recaptcha_keys_public = $this->getParameter('recaptcha_keys_public');

        $contact = new Contact();
        $formulaire = $this->createForm(ContactType::class);

        $formulaire->handleRequest($requete);

        if ($formulaire->isSubmitted()) {
            if ($formulaire->isValid()) {

                $token = $formulaire->getData();
                $recaptcha_keys_secret = $this->getParameter('recaptcha_keys_secret');

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('secret' => $recaptcha_keys_secret, 'response' => $token)));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                curl_close($ch);
                $arrResponse = json_decode($response, true);

                $contact = $formulaire->getData();

                $this->em->persist($contact);
                $this->em->flush();

               # if ($this->mailerService->envoyerContact($contact) !== 0) {

                   # $this->mailerService->envoyerAccuse($contact);
                    $this->addFlash('success', 'Votre message a été envoyé');
               # } else {
                #    $this->addFlash('danger', "Erreur lors de l'envoie de lors de l'envoi de la demande");
                #}

                return $this->redirect(
                    $this->router->generate('app_contact')
                );
            }
        }

        $response = new Response();
        $response->headers->set(AbstractSessionListener::NO_AUTO_CACHE_CONTROL_HEADER, 'true'); // A supprimer apres fin dev

        return $this->render('garage/contact.html.twig', [
            'formulaire' => $formulaire->createView(),
            'erreurs' => $erreurs,
            'recaptcha_keys_public' => $recaptcha_keys_public,
        ]);
    }

    #[Route('/public/testimonial', name: 'app_public_testimonial')]
    public function testimonial(Request $requete)
    {
        $erreurs = false;
        $recaptcha_keys_public = $this->getParameter('recaptcha_keys_public');

        $testimonial = new Testimonial();
        $formulaire = $this->createForm(TestimonialType::class, $testimonial);

        $formulaire->handleRequest($requete);

        if ($formulaire->isSubmitted()) {
            if ($formulaire->isValid()) {

                $token = $formulaire->getData();
                $recaptcha_keys_secret = $this->getParameter('recaptcha_keys_secret');

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('secret' => $recaptcha_keys_secret, 'response' => $token)));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                curl_close($ch);
                $arrResponse = json_decode($response, true);

                $testimonial = $formulaire->getData();

                $this->em->persist($testimonial);
                $this->em->flush();

                #if ($this->mailerService->envoyerTestimonial($testimonial) !== 0) {
                    $this->addFlash('success', 'Votre avis a été envoyé');
               # } else {
                   # $this->addFlash('danger', "Erreur lors de l'envoie de lors de l'envoi de la demande");
               # }

                return $this->redirect(
                    $this->router->generate('app_public_testimonial')
                );
            }
        }

        $response = new Response();
        $response->headers->set(AbstractSessionListener::NO_AUTO_CACHE_CONTROL_HEADER, 'true'); // A supprimer apres fin dev

        return $this->render('garage/testimonial.html.twig', [
            'formulaire' => $formulaire->createView(),
            'erreurs' => $erreurs,
            'recaptcha_keys_public' => $recaptcha_keys_public,
        ]);
    }


    #[Route('/card/filtered', name: 'car_list_filtered')]
    public function carListFilteredAction(Request $request)
    {
        $repository =  $this->em->getRepository(Car::class);
        $cars = $repository->findAll();

        $mileage = $request->request->get('mileage');
        $price = $request->request->get('price');
        $year = $request->request->get('year');

        if ($mileage !== null) {
            $cars = array_filter($cars, function ($car) use ($mileage) {
                return $car->getMileage() <= $mileage;
            });
        }

        if ($price !== null) {
            $cars = array_filter($cars, function ($car) use ($price) {
                return $car->getPrice() <= $price;
            });
        }

        if ($year !== null) {
            $cars = array_filter($cars, function ($car) use ($year) {
                return $car->getCirculationYear() <= $year;
            });
        }

        $html = $this->renderView('garage/vehicule_filter.html.twig', [
            'cars' => $cars,
        ]);

        return new Response($html);
    }

    #[Route('/access_denied', name: 'app_access_denied')]
    public function accessDenied(SessionInterface $session)
    {
        return $this->render('garage/access-denied.html.twig', [
        ]);
    }


}
