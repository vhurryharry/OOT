<?php

declare(strict_types=1);

namespace App\Template;

use App\Database;
use DateTime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CourseExtension extends AbstractExtension
{
    /**
     * @var Database
     */
    protected $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('getSeparateDates', [$this, 'getSeparateDates']),
            new TwigFunction('getTotalDates', [$this, 'getTotalDates']),
        ];
    }

    public function getSeparateDates(array $course): array
    {
        $output = [];
        $groupedDates = [];

        foreach ($course['options'] as $option) {
            if ($option['combo']) {
                continue;
            }

            $dates = json_decode($option['dates'], true);

            foreach ($dates as $date) {
                $date = new DateTime($date);
                $groupedDates[$option['title']][$date->format('F')][] = $date;
            }
        }

        foreach ($groupedDates as $option => $months) {
            foreach ($months as $month => $groupedDate) {
                $days = [];

                foreach ($groupedDate as $date) {
                    $days[] = $date->format('j');
                }

                $output[] = [
                    'option' => $option,
                    'details' => sprintf('%s %s', $month, implode(', ', $days)),
                ];
            }
        }

        return $output;
    }

    public function getTotalDates(array $course): string
    {
        $dates = json_decode($course['dates'], true);
        $firstDay = min($dates);
        $lastDay = max($dates);

        return sprintf(
            '%s %s-%s, %s',
            (new DateTime($firstDay))->format('F'),
            (new DateTime($firstDay))->format('j'),
            (new DateTime($lastDay))->format('j'),
            (new DateTime($firstDay))->format('Y'),
        );
    }
}
