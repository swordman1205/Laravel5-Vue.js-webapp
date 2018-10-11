<?php

namespace App\Console\Commands;

use App\Company;
use App\CompanyGalleryImage;
use App\Course;
use App\CourseDay;
use App\CourseGalleryImage;
use App\Disability;
use App\Federation;
use App\LegalForm;
use App\Sport;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Http\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ImportOldImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->timestamp = time();

        $this->importCompanies();

        $this->importCourses();

    }

    private function importCourses()
    {
        $courses = Course::all();
        $storage = Storage::disk('public');
        $this->info("Importo immagini di {$courses->count()} corsi");
        $this->output->progressStart($courses->count());

        foreach ($courses as $course) {

            if ($course->old_id) {
                $files = ($storage->files("/old_migration/{$course->company->old_id}/{$course->old_id}"));
                if ($files) {
                    foreach ($files as $file) {
                        if (strpos($file, 'thumb_') === false) {
                            if (!$course->course_image || $course->course_image == NULL) {
                                $course->course_image = "/storage/" . $file;
                                $course->save();
                                $course->fresh();
                            } else {
                                CourseGalleryImage::create([
                                    'course_id' => $course->id,
                                    'file_path' => "/storage/" . $file
                                ]);
                            }
                        }
                    }
                }
            }
            $this->output->progressAdvance();
        }
        $this->output->progressFinish();
    }

    private function importCompanies()
    {
        $companies = Company::all();
        $storage = Storage::disk('public');
        $this->info("Importo immagini di {$companies->count()} società");
        $this->output->progressStart($companies->count());

        foreach ($companies as $company) {

            if ($company->old_id) {
                $files = ($storage->files("/old_migration/{$company->old_id}"));
                if ($files) {
                    foreach ($files as $file) {
                        if (strpos($file, 'thumb_') === false) {
                            if (!$company->logo_path || $company->logo_path == NULL) {
                                $company->logo_path = "/storage/" . $file;
                                $company->save();
                                $company->fresh();
                            } else {
                                CompanyGalleryImage::create([
                                    'company_id' => $company->id,
                                    'file_path' => "/storage/" . $file
                                ]);
                            }
                        }
                    }
                }
            }
            $this->output->progressAdvance();
        }
        $this->output->progressFinish();
    }


}
