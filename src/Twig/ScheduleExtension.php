<?php

namespace App\Twig;

use App\Entity\Schedule;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class ScheduleExtension extends AbstractExtension
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('truncate', [$this, 'truncateFilter']),
        ];
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('displaySchedule', [$this, 'displaySchedule']),
        ];
    }

    public function truncateFilter(string $string, int $length = 30, string $suffix = '...'): string
    {
        if (mb_strlen($string) <= $length) {
            return $string;
        }

        return rtrim(mb_substr($string, 0, $length)) . $suffix;
    }

    public function displaySchedule()
    {
        // Récupérer les dernières données d'horaire depuis la base de données
        $latestSchedule = $this->em->getRepository(Schedule::class)->findOneBy([], ['id' => 'DESC']);

        // Si aucun horaire n'a été trouvé, initialiser le tableau avec des valeurs nulles
        $schedules = [];

        if (!$latestSchedule) {
            // Si aucune donnée n'est trouvée, on remplit le tableau avec null pour chaque jour
            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            foreach ($days as $day) {
                $schedules[$day] = null;  // Horaire fermé pour chaque jour
            }

            return $schedules;
        }

        // Créez un tableau pour stocker les horaires de chaque jour de la semaine
        $days = [
            'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'
        ];

        foreach ($days as $day) {
            // Générer les noms de méthodes dynamiques
            $amMethod = 'get' . $day . 'AM';
            $pmMethod = 'get' . $day . 'PM';
            $closeAmMethod = 'getClose' . strtolower($day) . 'AM';
            $closePmMethod = 'getClose' . strtolower($day) . 'PM';

            // Vérifier que les méthodes existent et ne retournent pas null
            if (method_exists($latestSchedule, $amMethod) && method_exists($latestSchedule, $pmMethod)) {
                $amTime = $latestSchedule->$amMethod();
                $pmTime = $latestSchedule->$pmMethod();

                // Vérifier si les horaires sont valides
                if ($amTime && $pmTime && $amTime->format('H:i') !== "00:00" && $pmTime->format('H:i') !== "00:00") {
                    $closeAmTime = method_exists($latestSchedule, $closeAmMethod) ? $latestSchedule->$closeAmMethod() : null;
                    $closePmTime = method_exists($latestSchedule, $closePmMethod) ? $latestSchedule->$closePmMethod() : null;

                    // Construire l'horaire du jour
                    $schedules[$day] = [
                        'open' => $amTime->format('H:i'),
                        'close' => ($closeAmTime ? $closeAmTime->format('H:i') : 'N/A') . ', ' .
                            ($pmTime ? $pmTime->format('H:i') : 'N/A') . '-' . 
                            ($closePmTime ? $closePmTime->format('H:i') : 'N/A')
                    ];
                } else {
                    // Horaire fermé ou invalidé
                    $schedules[$day] = null;
                }
            } else {
                // Si les méthodes n'existent pas, on marque l'horaire comme fermé
                $schedules[$day] = null;
            }
        }

        // Retourner le tableau des horaires avec des valeurs valides ou fermées
        return $schedules;
    }
}
