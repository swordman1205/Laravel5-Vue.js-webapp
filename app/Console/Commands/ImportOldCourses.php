<?php

namespace App\Console\Commands;

use App\Company;
use App\Course;
use App\CourseDay;
use App\Disability;
use App\Federation;
use App\LegalForm;
use App\Sport;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportOldCourses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:courses';

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

        $this->importCourses();
    }


    private function importCourses()
    {
        $courses_old = DB::table('sgt_corso')->get();

        $this->info("Importo {$courses_old->count()} corsi");
        $this->output->progressStart($courses_old->count());

        foreach ($courses_old as $course_old) {

            $company_old = Company::where("old_id", $course_old->idSocieta)->first();
            $sport_old = Sport::where("old_id", $course_old->idDisciplina)->first();

            if ($company_old) {

                //COURSE NAME
                $name = '';
                if ($course_old->nome)
                    $name = $course_old->nome;
                else {
                    if ($sport_old)
                        $name = $sport_old->name;
                    $name .= " " . $course_old->comune;
                }

                //COURSE ADDRESS
                $indirizzo_descr = "$course_old->indirizzo, $course_old->civico\r\n$course_old->comune ($course_old->provincia)\r\n$course_old->cap";

                if (!Course::where('old_id', $course_old->id)->exists()) {
                    $course_new = Course::create([
                        "name" => $name,

                        "company_id" => $company_old ? $company_old->id : NULL,
                        "sport_id" => $sport_old ? $sport_old->id : NULL,

                        "start_date" => $course_old->daData,
                        "end_date" => $course_old->aData,

                        "start_age" => $course_old->daEta,
                        "end_age" => $course_old->aEta,

                        "description" => $course_old->note,
                        "lesson_equipments" => $course_old->noteAbbigliamento,
                        "membership_fee" => $course_old->quotaAssociativa,

                        "lesson_price" => $course_old->first_lesson_price,

                        'route' => $course_old->indirizzo,
                        'house_number' => $course_old->civico,
                        'municipality' => $course_old->comune,
                        'postal_code' => $course_old->cap,
                        'province' => $course_old->provincia,
                        'region' => $course_old->regione,
                        'country' => "IT",
                        'latitude' => $course_old->X,
                        'longitude' => $course_old->Y,

                        "level" => $this->getLevel($course_old->livello),

                        'address' => $indirizzo_descr,
                        'impianto' => $course_old->impianto,

                        'has_trial_lesson' => 1,

                        'completed_steps' => $this->getCompletedSteps(),
                        'is_active' => true,
                        'is_complete' => true,

                        'created_at' => $course_old->dataCreazione,
                        'updated_at' => $course_old->dataModifica,
                        "import_batch" => $this->timestamp,
                        "old_id" => $course_old->id
                    ]);

                    $giorni = [
                        'lun' => ['day' => 'Lunedì', 'dow' => 0],
                        'mar' => ['day' => 'Martedì', 'dow' => 1],
                        'mer' => ['day' => 'Mercoledì', 'dow' => 2],
                        'gio' => ['day' => 'Giovedì', 'dow' => 3],
                        'ven' => ['day' => 'Venerdì', 'dow' => 4],
                        'sab' => ['day' => 'Sabato', 'dow' => 5],
                        'dom' => ['day' => 'Domenica', 'dow' => 6],
                    ];

                    foreach ($giorni as $gio => $arr) {
                        if ($course_old->{$gio}) {
                            CourseDay::create([
                                'course_id' => $course_new->id,
                                'day' => $arr['day'],
                                'start_time' => $course_old->{$gio . 'DaOra'},
                                'end_time' => $course_old->{$gio . 'AOra'},
                                'dow' => $arr['dow'],
                            ]);
                        }
                    }


                    if ($course_old->disabilita)
                        foreach (json_decode($course_old->disabilita) as $disabilita) {
                            $disability = Disability::where('name', $disabilita)->first();
                            if (!$disability) {
                                $disability = Disability::create(['name' => $disabilita]);
                            }
                            $course_new->disabilities()->attach($disability);
                        }
                }
            }

            $this->output->progressAdvance();
        }

        $this->output->progressFinish();

    }

    private function getLevel($livello)
    {
        if ($livello == 0 || $livello == 100)
            return 'Principiante';

        if ($livello == 1)
            return 'Intermedio';

        if ($livello == 2)
            return 'Esperto';

        if ($livello == 3)
            return 'Professionista';

    }

    private function getCompletedSteps()
    {
        return "{
	\"step_1\": true,
	\"step_2\": true,
	\"step_3\": true,
	\"step_4\": true,
	\"step_5\": true,
	\"step_6\": true,
	\"step_7\": true,
	\"step_8\": true,
	\"step_9\": true,
	\"step_10\": true
}";
    }
}
