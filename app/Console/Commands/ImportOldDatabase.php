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

class ImportOldDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:old';

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

        $this->importSports();

        //$this->importCourses();
    }

    private function importCompanies()
    {
        $societas_old = DB::table('sgt_societa_n')->get();


        $this->info("Importo {$societas_old->count()} società");
        $this->output->progressStart($societas_old->count());
        foreach ($societas_old as $societa_old) {

            if ($societa_old->indirizzo_descr)
                $indirizzo_descr = $societa_old->indirizzo_descr;
            else
                $indirizzo_descr = "$societa_old->indirizzo, $societa_old->civico\r\n$societa_old->comune ($societa_old->provincia)\r\n$societa_old->cap";

            if (!Company::where('old_id', $societa_old->id)->exists()) {
                $societa = Company::create([
                    "name" => $societa_old->societa,
                    "public_name" => $societa_old->societa,
                    'email' => $societa_old->email,
                    'website' => $societa_old->url,
                    'facebook_account' => mb_strimwidth($societa_old->fb, 0, 191, "..."),
                    'phone_number' => $societa_old->telefono1,
                    'fiscal_code' => $societa_old->codiceFiscale,
                    'vat_number' => $societa_old->piva,
                    'description' => $societa_old->note,
                    'promotion_agency' => $societa_old->ente,

                    "federation_id" => $this->createAndAssociateFederation($societa_old->federazione),
                    "legal_form_id" => $this->createAndAssociateLegalForm($societa_old->forma_giuridica),

                    "affiliation" => $societa_old->affiliazione,
                    "affiliation_code" => $societa_old->codiceAffiliazione,

                    'route' => $societa_old->indirizzo,
                    'house_number' => $societa_old->civico,
                    'municipality' => $societa_old->comune,
                    'postal_code' => $societa_old->cap,
                    'province' => $societa_old->provincia,
                    'region' => $societa_old->regione,
                    'country' => "IT",
                    'latitude' => $societa_old->X,
                    'longitude' => $societa_old->Y,
                    'address' => $indirizzo_descr,

                    'trial_adhesion' => $societa_old->adesioneProva,
                    'discount_adhesion' => $societa_old->adesioneSconto,

                    'created_at' => $societa_old->insertDate,
                    'updated_at' => $societa_old->updateDate,
                    "import_batch" => $this->timestamp,
                    "old_id" => $societa_old->id
                ]);

                $societa->typology_id = 2;
                if ($societa_old->scopo_lucro) {
                    $societa->typology_id = 1;
                }
                $societa->save();

                $this->createAndAssociateUser($societa_old, $societa);
            }
            $this->output->progressAdvance();
        }

        $this->output->progressFinish();
    }

    private function createAndAssociateFederation($federazione = null)
    {
        if (!$federazione)
            return null;

        $federation = Federation::where('name', $federazione)->first();

        if (empty($federation)) {
            $federation = Federation::create([
                'name' => $federazione
            ]);
        }

        return $federation->id;
    }

    private function createAndAssociateLegalForm($forma_legale = null)
    {
        if (!$forma_legale)
            return null;

        $legal_form = LegalForm::where('name', $forma_legale)->first();

        if (empty($legal_form)) {
            $legal_form = LegalForm::create([
                'name' => $forma_legale
            ]);
        }

        return $legal_form->id;
    }

    private function createAndAssociateUser($societa_old, $societa)
    {
        $user = User::where('email', $societa_old->email_user)->first();

        if (empty($user)) {
            $user = User::create([
                'first_name' => $societa_old->email_user,
                'last_name' => " ",
                'email' => $societa_old->email_user,
                'password' => bcrypt($societa_old->password)
            ]);
        }

        $societa->users()->attach($user);
    }

    private function importSports()
    {
        $sports_old = DB::table('sgt_disciplina')->get();

        $this->info("Importo {$sports_old->count()} sport");
        $this->output->progressStart($sports_old->count());

        foreach ($sports_old as $sport_old) {

            if (!Sport::where('old_id', $sport_old->id)->exists()) {
                $sport = Sport::create([
                    "name" => $sport_old->descr,
                    "slug" => $sport_old->descrKey,
                    "old_id" => $sport_old->id
                ]);

                if ($sport_old->immagineCorso)
                    $sport->course_cover_image = "/images/sports/courses/" . $sport_old->immagineCorso;
                if ($sport_old->immagineSocieta)
                    $sport->company_cover_image = "/images/sports/companies/" . $sport_old->immagineSocieta;

                $sport->save();
            }
            $this->output->progressAdvance();
        }
        $this->output->progressFinish();
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
