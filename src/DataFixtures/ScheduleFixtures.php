<?php

namespace App\DataFixtures;

use App\Entity\Schedule;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ScheduleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $schedule = new Schedule();
        $now = new \DateTime();

        // Lundi
        $schedule->setMondayAM((clone $now)->setTime(8, 45));
        $schedule->setClosemondayAM((clone $now)->setTime(12, 0));
        $schedule->setMondayPM((clone $now)->setTime(14, 0));
        $schedule->setClosemondayPM((clone $now)->setTime(18, 0));

        // Mardi
        $schedule->setTuesdayAM((clone $now)->modify('+1 day')->setTime(8, 45));
        $schedule->setClosetuesdayAM((clone $now)->modify('+1 day')->setTime(12, 0));
        $schedule->setTuesdayPM((clone $now)->modify('+1 day')->setTime(14, 0));
        $schedule->setClosetuesdayPM((clone $now)->modify('+1 day')->setTime(18, 0));

        // Mercredi
        $schedule->setWednesdayAM((clone $now)->modify('+2 days')->setTime(8, 45));
        $schedule->setClosewednesdayAM((clone $now)->modify('+2 days')->setTime(12, 0));
        $schedule->setWednesdayPM((clone $now)->modify('+2 days')->setTime(14, 0));
        $schedule->setClosewednesdayPM((clone $now)->modify('+2 days')->setTime(18, 0));

        // Jeudi
        $schedule->setThursdayAM((clone $now)->modify('+3 days')->setTime(8, 45));
        $schedule->setClosethursdayAM((clone $now)->modify('+3 days')->setTime(12, 0));
        $schedule->setThursdayPM((clone $now)->modify('+3 days')->setTime(14, 0));
        $schedule->setClosethursdayPM((clone $now)->modify('+3 days')->setTime(18, 0));

        // Vendredi
        $schedule->setFridayAM((clone $now)->modify('+4 days')->setTime(8, 45));
        $schedule->setClosefridayAM((clone $now)->modify('+4 days')->setTime(12, 0));
        $schedule->setFridayPM((clone $now)->modify('+4 days')->setTime(14, 0));
        $schedule->setClosefridayPM((clone $now)->modify('+4 days')->setTime(18, 0));

        // Samedi
        $schedule->setSaturdayAM((clone $now)->modify('+5 days')->setTime(8, 45));
        $schedule->setClosesaturdayAM((clone $now)->modify('+5 days')->setTime(12, 0));
        $schedule->setSaturdayPM((clone $now)->modify('+5 days')->setTime(14, 0));
        $schedule->setClosesaturdayPM((clone $now)->modify('+5 days')->setTime(18, 0));

        // Dimanche
        $schedule->setSundayAM(new \DateTime('00:00:00'));
        $schedule->setClosesundayAM(new \DateTime('00:00:00'));
        $schedule->setSundayPM(new \DateTime('00:00:00'));
        $schedule->setClosesundayPM(new \DateTime('00:00:00'));

        $manager->persist($schedule);
        $manager->flush();
    }
}
