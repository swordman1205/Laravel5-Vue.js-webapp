<?php

namespace App;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Laravel\Scout\Searchable;

class Sport extends Model
{
    use Searchable, Sluggable;

    protected $guarded = [];
    protected $hidden = ['created_at', 'updated_at'];

    /**
     * @param $search
     * @return mixed
     */
    public static function searchSports($search)
    {
        return self::where('name', 'like', '%'.$search.'%')->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function course() {
        return $this->hasMany('App\Course');
    }

    /**
     * @param $courses
     * @return static
     */
    public static function byCourses($courses)
    {
        $sports = new Collection();
        foreach ($courses as $course){
            $sports->push($course->sport);
        }

        return $sports->unique();
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => ['name'],
                'separator' => '-'
            ]
        ];
    }
}
