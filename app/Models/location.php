<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class location extends Model
{
    protected $fillable = [
        'city_name',
        'address',
        'phone_number',
        'opening_hours',
        'map_url'
    ];

    protected $casts = [
        'opening_hours' => 'array',
    ];

    /**
     * Get grouped opening hours for display.
     * Logic: Monday to Thursday: 15:00-22:00
     */
    public function getFormattedHoursAttribute(): array
    {
        $hours = $this->opening_hours;
        if (!$hours) return [];

        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $grouped = [];
        
        $currentGroup = [];
        $lastTime = null;

        foreach ($days as $day) {
            $timeArray = $hours[$day] ?? [];
            $currentTime = empty($timeArray) ? 'Closed' : $timeArray[0];

            if ($currentTime === $lastTime) {
                $currentGroup[] = $day;
            } else {
                if (!empty($currentGroup)) {
                    $grouped[] = $this->formatDayRange($currentGroup, $lastTime);
                }
                $currentGroup = [$day];
                $lastTime = $currentTime;
            }
        }

        if (!empty($currentGroup)) {
            $grouped[] = $this->formatDayRange($currentGroup, $lastTime);
        }

        return $grouped;
    }

    private function formatDayRange(array $days, string $time): string
    {
        $start = ucfirst($days[0]);
        if (count($days) > 1) {
            $end = ucfirst(end($days));
            return "{$start} to {$end}: {$time}";
        }
        return "{$start}: {$time}";
    }

    /**
     * Include formatted_hours in the model's array/JSON representation.
     */
    protected $appends = ['formatted_hours'];
}
