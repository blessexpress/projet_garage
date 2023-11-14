<?php

namespace App\Entity;

use App\Repository\ScheduleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ScheduleRepository::class)]
class Schedule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: "time")]
    private $mondayAM;

    #[ORM\Column(type: "time")]
    private $closemondayAM;

    #[ORM\Column(type: "time")]
    private $mondayPM;

    #[ORM\Column(type: "time")]
    private $closemondayPM;


    #[ORM\Column(type: "time")]
    private $tuesdayAM;

    #[ORM\Column(type: "time")]
    private $closetuesdayAM;


    #[ORM\Column(type: "time")]
    private $tuesdayPM;

    #[ORM\Column(type: "time")]
    private $closetuesdayPM;

    #[ORM\Column(type: "time")]
    private $wednesdayAM;

    #[ORM\Column(type: "time")]
    private $closewednesdayAM;

    #[ORM\Column(type: "time")]
    private $wednesdayPM;

    #[ORM\Column(type: "time")]
    private $closewednesdayPM;

    #[ORM\Column(type: "time")]
    private $thursdayAM;

    #[ORM\Column(type: "time")]
    private $closethursdayAM;

    #[ORM\Column(type: "time")]
    private $thursdayPM;

    #[ORM\Column(type: "time")]
    private $closethursdayPM;

    #[ORM\Column(type: "time")]
    private $fridayAM;

    #[ORM\Column(type: "time")]
    private $closefridayAM;

    #[ORM\Column(type: "time")]
    private $fridayPM;

    #[ORM\Column(type: "time")]
    private $closefridayPM;

    #[ORM\Column(type: "time")]
    private $saturdayAM;

    #[ORM\Column(type: "time")]
    private $closesaturdayAM;


    #[ORM\Column(type: "time")]
    private $saturdayPM;

    #[ORM\Column(type: "time")]
    private $closesaturdayPM;

    #[ORM\Column(type: "time")]
    private $sundayAM;

    #[ORM\Column(type: "time")]
    private $closesundayAM;

    #[ORM\Column(type: "time")]
    private $sundayPM;

    #[ORM\Column(type: "time")]
    private $closesundayPM;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getTime(string $day, string $period): ?\DateTime
    {
        return $this->times[$day . $period] ?? null;
    }

    public function setTime(string $day, string $period, \DateTime $time): self
    {
        $this->times[$day . $period] = $time;
        return $this;
    }

    /**
     * Get the value of mondayAM
     */
    public function getMondayAM()
    {
        return $this->mondayAM;
    }

    /**
     * Set the value of mondayAM
     *
     * @return  self
     */
    public function setMondayAM($mondayAM)
    {
        $this->mondayAM = $mondayAM;

        return $this;
    }

    /**
     * Get the value of mondayPM
     */
    public function getMondayPM()
    {
        return $this->mondayPM;
    }

    /**
     * Set the value of mondayPM
     *
     * @return  self
     */
    public function setMondayPM($mondayPM)
    {
        $this->mondayPM = $mondayPM;

        return $this;
    }

    /**
     * Get the value of tuesdayAM
     */
    public function getTuesdayAM()
    {
        return $this->tuesdayAM;
    }

    /**
     * Set the value of tuesdayAM
     *
     * @return  self
     */
    public function setTuesdayAM($tuesdayAM)
    {
        $this->tuesdayAM = $tuesdayAM;

        return $this;
    }

    /**
     * Get the value of tuesdayPM
     */
    public function getTuesdayPM()
    {
        return $this->tuesdayPM;
    }

    /**
     * Set the value of tuesdayPM
     *
     * @return  self
     */
    public function setTuesdayPM($tuesdayPM)
    {
        $this->tuesdayPM = $tuesdayPM;

        return $this;
    }

    /**
     * Get the value of wednesdayAM
     */
    public function getWednesdayAM()
    {
        return $this->wednesdayAM;
    }

    /**
     * Set the value of wednesdayAM
     *
     * @return  self
     */
    public function setWednesdayAM($wednesdayAM)
    {
        $this->wednesdayAM = $wednesdayAM;

        return $this;
    }

    /**
     * Get the value of wednesdayPM
     */
    public function getWednesdayPM()
    {
        return $this->wednesdayPM;
    }

    /**
     * Set the value of wednesdayPM
     *
     * @return  self
     */
    public function setWednesdayPM($wednesdayPM)
    {
        $this->wednesdayPM = $wednesdayPM;

        return $this;
    }

    /**
     * Get the value of thursdayAM
     */
    public function getThursdayAM()
    {
        return $this->thursdayAM;
    }

    /**
     * Set the value of thursdayAM
     *
     * @return  self
     */
    public function setThursdayAM($thursdayAM)
    {
        $this->thursdayAM = $thursdayAM;

        return $this;
    }

    /**
     * Get the value of thursdayPM
     */
    public function getThursdayPM()
    {
        return $this->thursdayPM;
    }

    /**
     * Set the value of thursdayPM
     *
     * @return  self
     */
    public function setThursdayPM($thursdayPM)
    {
        $this->thursdayPM = $thursdayPM;

        return $this;
    }

    /**
     * Get the value of fridayAM
     */
    public function getFridayAM()
    {
        return $this->fridayAM;
    }

    /**
     * Set the value of fridayAM
     *
     * @return  self
     */
    public function setFridayAM($fridayAM)
    {
        $this->fridayAM = $fridayAM;

        return $this;
    }

    /**
     * Get the value of fridayPM
     */
    public function getFridayPM()
    {
        return $this->fridayPM;
    }

    /**
     * Set the value of fridayPM
     *
     * @return  self
     */
    public function setFridayPM($fridayPM)
    {
        $this->fridayPM = $fridayPM;

        return $this;
    }

    /**
     * Get the value of saturdayAM
     */
    public function getSaturdayAM()
    {
        return $this->saturdayAM;
    }

    /**
     * Set the value of saturdayAM
     *
     * @return  self
     */
    public function setSaturdayAM($saturdayAM)
    {
        $this->saturdayAM = $saturdayAM;

        return $this;
    }

    /**
     * Get the value of saturdayPM
     */
    public function getSaturdayPM()
    {
        return $this->saturdayPM;
    }

    /**
     * Set the value of saturdayPM
     *
     * @return  self
     */
    public function setSaturdayPM($saturdayPM)
    {
        $this->saturdayPM = $saturdayPM;

        return $this;
    }

    /**
     * Get the value of sundayAM
     */
    public function getSundayAM()
    {
        return $this->sundayAM;
    }

    /**
     * Set the value of sundayAM
     *
     * @return  self
     */
    public function setSundayAM($sundayAM)
    {
        $this->sundayAM = $sundayAM;

        return $this;
    }

    /**
     * Get the value of sundayPM
     */
    public function getSundayPM()
    {
        return $this->sundayPM;
    }

    /**
     * Set the value of sundayPM
     *
     * @return  self
     */
    public function setSundayPM($sundayPM)
    {
        $this->sundayPM = $sundayPM;

        return $this;
    }

    /**
     * Get the value of closemondayAM
     */
    public function getClosemondayAM()
    {
        return $this->closemondayAM;
    }

    /**
     * Set the value of closemondayAM
     *
     * @return  self
     */
    public function setClosemondayAM($closemondayAM)
    {
        $this->closemondayAM = $closemondayAM;

        return $this;
    }

    /**
     * Get the value of closemondayPM
     */
    public function getClosemondayPM()
    {
        return $this->closemondayPM;
    }

    /**
     * Set the value of closemondayPM
     *
     * @return  self
     */
    public function setClosemondayPM($closemondayPM)
    {
        $this->closemondayPM = $closemondayPM;

        return $this;
    }

    /**
     * Get the value of closetuesdayAM
     */
    public function getClosetuesdayAM()
    {
        return $this->closetuesdayAM;
    }

    /**
     * Set the value of closetuesdayAM
     *
     * @return  self
     */
    public function setClosetuesdayAM($closetuesdayAM)
    {
        $this->closetuesdayAM = $closetuesdayAM;

        return $this;
    }

    /**
     * Get the value of closetuesdayPM
     */
    public function getClosetuesdayPM()
    {
        return $this->closetuesdayPM;
    }

    /**
     * Set the value of closetuesdayPM
     *
     * @return  self
     */
    public function setClosetuesdayPM($closetuesdayPM)
    {
        $this->closetuesdayPM = $closetuesdayPM;

        return $this;
    }

    /**
     * Get the value of closewednesdayAM
     */
    public function getClosewednesdayAM()
    {
        return $this->closewednesdayAM;
    }

    /**
     * Set the value of closewednesdayAM
     *
     * @return  self
     */
    public function setClosewednesdayAM($closewednesdayAM)
    {
        $this->closewednesdayAM = $closewednesdayAM;

        return $this;
    }

    /**
     * Get the value of closethursdayAM
     */
    public function getClosethursdayAM()
    {
        return $this->closethursdayAM;
    }

    /**
     * Set the value of closethursdayAM
     *
     * @return  self
     */
    public function setClosethursdayAM($closethursdayAM)
    {
        $this->closethursdayAM = $closethursdayAM;

        return $this;
    }

    /**
     * Get the value of closewednesdayPM
     */
    public function getClosewednesdayPM()
    {
        return $this->closewednesdayPM;
    }

    /**
     * Set the value of closewednesdayPM
     *
     * @return  self
     */
    public function setClosewednesdayPM($closewednesdayPM)
    {
        $this->closewednesdayPM = $closewednesdayPM;

        return $this;
    }

    /**
     * Get the value of closethursdayPM
     */
    public function getClosethursdayPM()
    {
        return $this->closethursdayPM;
    }

    /**
     * Set the value of closethursdayPM
     *
     * @return  self
     */
    public function setClosethursdayPM($closethursdayPM)
    {
        $this->closethursdayPM = $closethursdayPM;

        return $this;
    }

    /**
     * Get the value of closesaturdayAM
     */
    public function getClosesaturdayAM()
    {
        return $this->closesaturdayAM;
    }

    /**
     * Set the value of closesaturdayAM
     *
     * @return  self
     */
    public function setClosesaturdayAM($closesaturdayAM)
    {
        $this->closesaturdayAM = $closesaturdayAM;

        return $this;
    }

    /**
     * Get the value of closefridayAM
     */
    public function getClosefridayAM()
    {
        return $this->closefridayAM;
    }

    /**
     * Set the value of closefridayAM
     *
     * @return  self
     */
    public function setClosefridayAM($closefridayAM)
    {
        $this->closefridayAM = $closefridayAM;

        return $this;
    }

    /**
     * Get the value of closesaturdayPM
     */
    public function getClosesaturdayPM()
    {
        return $this->closesaturdayPM;
    }

    /**
     * Set the value of closesaturdayPM
     *
     * @return  self
     */
    public function setClosesaturdayPM($closesaturdayPM)
    {
        $this->closesaturdayPM = $closesaturdayPM;

        return $this;
    }

    /**
     * Get the value of closefridayPM
     */
    public function getClosefridayPM()
    {
        return $this->closefridayPM;
    }

    /**
     * Set the value of closefridayPM
     *
     * @return  self
     */
    public function setClosefridayPM($closefridayPM)
    {
        $this->closefridayPM = $closefridayPM;

        return $this;
    }

    /**
     * Get the value of closesundayAM
     */
    public function getClosesundayAM()
    {
        return $this->closesundayAM;
    }

    /**
     * Set the value of closesundayAM
     *
     * @return  self
     */
    public function setClosesundayAM($closesundayAM)
    {
        $this->closesundayAM = $closesundayAM;

        return $this;
    }

    /**
     * Get the value of closesundayPM
     */
    public function getClosesundayPM()
    {
        return $this->closesundayPM;
    }

    /**
     * Set the value of closesundayPM
     *
     * @return  self
     */
    public function setClosesundayPM($closesundayPM)
    {
        $this->closesundayPM = $closesundayPM;

        return $this;
    }

}
