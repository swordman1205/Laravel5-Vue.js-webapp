<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseGalleryImage extends Model
{
    protected $fillable = ['course_id', 'file_path'];

    public function updateCourseId($courseId){
        $this->course_id = $courseId;
        $this->save();
    }
}
