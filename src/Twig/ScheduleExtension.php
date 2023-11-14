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
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
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

        // Créez un tableau pour stocker les horaires de chaque jour de la semaine
        $schedules = [];

        $days = [
            'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'
        ];

        foreach ($days as $day) {
            $amMethod = 'get' . $day . 'AM';
            $pmMethod = 'get' . $day . 'PM';
            $closeAmMethod = 'getClose' . strtolower($day) . 'AM';
            $closePmMethod = 'getClose' . strtolower($day) . 'PM';

            $amTime = $latestSchedule->$amMethod();
            $pmTime = $latestSchedule->$pmMethod();

            if ($amTime && $pmTime == "00:00:00") {
                $schedules[$day] = null; // Horaire fermé
            } else {
                $schedules[$day] = [
                    'open' => $amTime->format('H:i'),
                    'close' => $latestSchedule->$closeAmMethod()->format('H:i') . ', ' .
                        $pmTime->format('H:i') . '-' . $latestSchedule->$closePmMethod()->format('H:i')
                ];
            }
        }

        return $schedules;
    }

}
