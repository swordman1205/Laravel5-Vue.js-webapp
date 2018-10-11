<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseDay extends Model
{
    protected $fillable = [
        'day', 'course_id', 'start_time', 'end_time','dow'
    ];

    public function updateCourseId($courseId){
        $this->course_id = $courseId;
        $this->save();
    }
}
