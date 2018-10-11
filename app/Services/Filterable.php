<?php

namespace App\Services;


use League\Geotools\Coordinate\Coordinate;
use League\Geotools\Geotools;

trait Filterable
{
    /**
     * @param $builder
     * @return mixed
     */
    public function scopeByAge($builder, $age)
    {
        if (count($age) != 0) {
            if ($age['from'] != "" && $age['to'] != "") {
                return $builder->where('start_age', '<=', $age['from'])->where('end_age', '>=', $age['to']);
            }
        }
    }

    /**
     * @param $builder
     * @return mixed
     */
    public function scopeByActivate($builder)
    {
        return $builder->where('is_active', 1);
    }

    /**
     * @param $builder
     * @return mixed
     */
    public function scopeByPrice($builder, $price)
    {
        if (isset($price['to']) && isset($price['from'])) {
            if ($price['to'] != "" && $price['from'] != "") {
                if (count($price) != 0) {
                    if ($price['from'] != "" && $price['to'] != "") {
                        $fromPrice = str_replace('€', '', $price['from']);
                        $fromPrice = str_replace(',', '', $fromPrice);
                        $toPrice = str_replace('€', '', $price['to']);
                        $toPrice = str_replace(',', '', $toPrice);

                        return $builder->whereBetween('lesson_price', [$fromPrice + 0, $toPrice + 0]);
                    }
                }
            } else {
                return $builder;
            }
        }
    }

    /**
     * @param $builder
     * @return mixed
     */
    public function scopeBySport($builder, $sport)
    {
        if ($sport != null) {
            return $builder->where('sport_id', $sport['id']);
        }
    }

    /**
     * @param $builder
     * @return mixed
     */
    public function scopeByDistance($builder, $distance, $latitude = null, $longitude = null)
    {
        if ($distance == "50m") {
            $distance = 0.05;
        } else {
            $distance = str_replace('km', '', $distance);
        }

        if ($latitude && $longitude && $latitude != 'null' && $longitude != 'null')
            return $builder->where('latitude', '<=', $latitude + ($distance * 0.008983))
                ->where('latitude', '>=', $latitude - ($distance * 0.008983))
                ->where('longitude', '<=', $longitude + ($distance * 0.008983))
                ->where('longitude', '>=', $longitude - ($distance * 0.008983));
        return $builder;
    }

    /**
     * @param $builder
     * @return mixed
     */
    public function scopeByDisabilities($builder, $disabilities)
    {
        foreach ($disabilities as $disability) {
            $builder = $builder->whereHas("disabilities", function ($q) use ($disability) {
                $q->where('disability_id', $disability);
            });
        }

        return $builder;
    }

    /**
     * @param $builder
     * @return mixed
     */
    public function scopeByDays($builder, $days)
    {
        $array_of_days = [
            'Lunedì',
            'Martedì',
            'Mercoledì',
            'Giovedì',
            'Venerdì',
            'Sabato',
            'Domenica',
        ];

        foreach ($days as $day) {
            $builder = $builder->whereHas("days", function ($q) use ($array_of_days, $day) {
                $q->where('day', $array_of_days[$day]);
            });
        }

        return $builder;
    }

    /**
     * @param $builder
     * @return mixed
     */
    public function scopeByTime($builder, $fromHour, $toHour)
    {
        if ($fromHour != '' && $toHour != '') {
            $fromHour = str_replace(':', '', $fromHour);
            $fromHour = $fromHour . '00';
            $toHour = str_replace(':', '', $toHour);
            $toHour = $toHour . '00';

            return $builder->whereHas("days", function ($q) use ($fromHour, $toHour) {
                $q->where('start_time', '<=', $fromHour)->where('end_time', '>=', $toHour);
            });
        }
    }

    /**
     * @param $builder
     * @return mixed
     */
    public function scopeByFreeTrial($builder, $freeTrial)
    {
        if ($freeTrial) {
            return $builder->where('has_trial_lesson', 1)->where('lesson_price', 0);
        } else {
            return $builder;
        }
    }
}