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
     * Mutator to clean up opening hours before saving.
     * Ensures empty days are [] instead of [null].
     */
    public function setOpeningHoursAttribute($value)
    {
        if (is_array($value)) {
            foreach ($value as $day => $times) {
                if (is_array($times)) {
                    // Remove null values and empty strings
                    $cleanedTimes = array_values(array_filter($times, function ($time) {
                        return !is_null($time) && $time !== '';
                    }));
                    $value[$day] = $cleanedTimes;
                }
            }
        }
        $this->attributes['opening_hours'] = json_encode($value);
    }

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
            $currentTime = (empty($timeArray) || !isset($timeArray[0]) || $timeArray[0] === null) 
                ? 'Closed' 
                : (string)$timeArray[0];

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
