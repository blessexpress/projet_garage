<?php

namespace App\Controller;

use App\Entity\Testimonial;
use App\Form\Testimonial1Type;
use App\Form\TestimonialType;
use App\Repository\TestimonialRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/testimonial')]
class TestimonialController extends AbstractController
{
    public function __construct(
        private Security $security,
        private EntityManagerInterface $em,
        private RequestStack $requestStack
    ){}

    #[Route('/', name: 'app_testimonial_index', methods: ['GET'])]
    #[IsGranted("ROLE_ADMIN")]
    public function index(TestimonialRepository $testimonialRepository): Response
    {
        return $this->render('testimonial/index.html.twig', [
            'testimonials' => $testimonialRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_testimonial_new', methods: ['GET', 'POST'])]
    #[IsGranted("ROLE_USER")]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $testimonial = new Testimonial();
        $isAdmin = $this->isGranted('ROLE_ADMIN');

        $form = $this->createForm(TestimonialType::class, $testimonial, ['is_admin' => $isAdmin]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($testimonial);
            $entityManager->flush();

            $user = $this->security->getUser();

            if ($user && in_array('ROLE_ADMIN', $user->getRoles())) {
                $this->addFlash('success', 'Votre témoignage a été enregistré.');
                return $this->redirectToRoute('app_testimonial_index', [], Response::HTTP_SEE_OTHER);
            } else {

                $this->addFlash('success', 'Votre témoignage a été enregistré.');
                return $this->redirectToRoute('app_testimonial_new');
            }
        }

        return $this->render('testimonial/new.html.twig', [
            'testimonial' => $testimonial,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_testimonial_show', methods: ['GET'])]
    #[IsGranted("ROLE_ADMIN")]
    public function show(Testimonial $testimonial): Response
    {
        return $this->render('testimonial/show.html.twig', [
            'testimonial' => $testimonial,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_testimonial_edit', methods: ['GET', 'POST'])]
    #[IsGranted("ROLE_ADMIN")]
    public function edit(Request $request, Testimonial $testimonial, EntityManagerInterface $entityManager): Response
    {
        $isAdmin = $this->isGranted('ROLE_ADMIN');
        $form = $this->createForm(TestimonialType::class, $testimonial, ['is_admin' => $isAdmin]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_testimonial_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('testimonial/edit.html.twig', [
            'testimonial' => $testimonial,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_testimonial_delete', methods: ['POST'])]
    #[IsGranted("ROLE_ADMIN")]
    public function delete(Request $request, Testimonial $testimonial, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$testimonial->getId(), $request->request->get('_token'))) {
            $entityManager->remove($testimonial);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_testimonial_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/testimonial/approve', name: 'app_testimonial_approve', methods: ['POST'])]
    #[IsGranted("ROLE_ADMIN")]
    public function approveTestimonial(Request $request,EntityManagerInterface $entityManager):  JsonResponse
    {
        if ($request->isXmlHttpRequest()) {
            $testimonialId = $request->request->get('id');
            $token = $request->request->get('_token');

            if ($this->isCsrfTokenValid('approve', $token)) {

                $testimonial = $entityManager->getRepository(Testimonial::class)->find($testimonialId);
                if ($testimonial) {
                    $testimonial->setApproved(true);
                    $entityManager->flush();

                    return new JsonResponse(['success' => true]);
                }
            }
        }

        return new JsonResponse(['success' => false]);
    }
}
