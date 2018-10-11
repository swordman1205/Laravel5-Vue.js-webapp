<?php

namespace App\Http\Controllers;

use App\Course;
use App\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll() {
        $documents = Document::all();
        return response()->json(compact('documents'));
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllByCourse($courseId) {
        $documents = Document::all();
        $course = Course::find($courseId);

        foreach ($documents as $document) {
            $document->checked = false;
            foreach ($course->documents as $courseDocument) {
                if ($courseDocument->id == $document->id) {
                    $document->checked = true;
                    break;
                }
            }
        }

        return response()->json(compact('documents'));
    }
}
