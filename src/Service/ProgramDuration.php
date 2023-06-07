<?php

namespace App\Service;

use App\Entity\Program;

class ProgramDuration
{
    public function calculate(Program $program)
    {
        $totalDuration = 0;
        foreach($program->getSeasons() as $key => $season) {
            foreach($season->getEpisodes() as $key => $episode) {
                $totalDuration += $episode->getDuration();
            }
        }
        $days = floor($totalDuration / 1440);
        $hours = floor(($totalDuration % 1440) / 60);
        $minutes = $totalDuration - ($days * 1440) - ($hours * 60);
        return $days . " Jours, " . $hours . " heures et " . $minutes . " Minutes";
    }
}