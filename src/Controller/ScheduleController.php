<?php

namespace App\Controller;


use App\Entity\Schedule;
use App\Form\ScheduleType;
use App\Repository\ScheduleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/schedule')]
#[IsGranted("ROLE_ADMIN")]
class ScheduleController extends AbstractController
{
    public function __construct(
        private Security $security,
        private EntityManagerInterface $em,
        private RequestStack $requestStack
    ){}
    #[Route('/edit', name: 'app_schedule_edit', methods: ['GET', 'POST'])]
    #[IsGranted("ROLE_ADMIN")]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        $schedule = $this->em->getRepository(Schedule::class)->findOneBy([], ['id' => 'DESC']);
        if (!empty($schedule)) {
            $schedule=$schedule;
        } else {
            $schedule = new Schedule();
        }

        $form = $this->createForm(ScheduleType::class, $schedule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', "Mise à jour a été effectuée");

            return $this->redirectToRoute('app_schedule_edit', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('schedule/edit.html.twig', [
            'schedule' => $schedule,
            'form' => $form,
        ]);
    }

}
