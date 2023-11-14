<?php

namespace App\Service;

use App\Entity\Contact;

use App\Entity\Testimonial;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class MailerService
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private EntityManagerInterface $em,
        private MailerInterface $mailer,
        private string $sender_address,
        private string $destinataire,
        private string $website_name,
        private string $noreply,
    )
    {
    }

    public function envoyerContact(Contact $contact)
    {
        if ($contact->getEmail()) {
            $expediteur = $contact->getEmail();
        }
        $message = (
        new TemplatedEmail())
            ->from($expediteur)
            ->to($this->sender_address)
            ->subject($this->website_name . ' - ' . $contact->getSubject())
            ->htmlTemplate('courriel/contact.html.twig')
            ->context([
                'contact' => $contact
            ]);
        $this->mailer->send($message);
    }

    public function envoyerTestimonial(Testimonial $testimonial)
    {

        $message = (
        new TemplatedEmail())
            ->from($this->noreply)
            ->to($this->sender_address)
            ->subject($this->website_name . ' - ' . $testimonial->getFullname())
            ->htmlTemplate('courriel/testimonial.html.twig')
            ->context([
                'testimonial' => $testimonial
            ]);
        $this->mailer->send($message);
    }

    public function envoyerAccuse(Contact $contact)
    {
        if ($contact->getEmail()) {
            $expediteur = $contact->getEmail();
        }

        $message = (
        new TemplatedEmail())
            ->from($this->noreply)
            ->to($expediteur)
            ->subject($this->website_name . ' - ' . $contact->getSubject())
            ->htmlTemplate('courriel/accuse.html.twig')
            ->context([
                'contact' => $contact
            ]);

        $this->mailer->send($message);

    }



}
