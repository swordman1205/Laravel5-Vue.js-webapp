<?php

use App\LegalForm;
use App\Sport;
use App\Typology;
use Illuminate\Database\Seeder;

class PopulateSports extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Typology::create(array('name' => 'Con fini di lucro'));
        Typology::create(array('name' => 'Senza fini di lucro'));

        LegalForm::create(array("ASD - Associazione sportiva dilettantistica"));
        LegalForm::create(array("SSD ARL - Società Sportive Dilettantistiche a responsabilità limitata"));
        LegalForm::create(array("SPA - Società per azioni"));
        LegalForm::create(array("SRL - Società a responsabilità limitata"));
        LegalForm::create(array("Associazione culturale"));
        LegalForm::create(array("Onlus"));
        LegalForm::create(array("Altro"));

        \App\Document::create(array("Carta d'identità"));
        \App\Document::create(array("Codice fiscale"));
        \App\Document::create(array("Certificato medico per attività agonistica"));
        \App\Document::create(array("Certificato medico per attività non agonistica"));

        $sports = [
            ['name' => 'American Football'],
            ['name' => 'Archery'],
            ['name' => 'Athletics'],
            ['name' => 'Badminton'],
            ['name' => 'Baseball'],
            ['name' => 'Basketball'],
            ['name' => 'Bowls'],
            ['name' => 'Boxing'],
            ['name' => 'Canoeing'],
            ['name' => 'Cricket'],
            ['name' => 'Curling'],
            ['name' => 'Cycling'],
            ['name' => 'Darts'],
            ['name' => 'Disability Sport'],
            ['name' => 'Diving'],
            ['name' => 'Equestrian'],
            ['name' => 'Fencing'],
            ['name' => 'Football'],
            ['name' => 'Formula 1'],
            ['name' => 'Gaelic Games'],
            ['name' => 'Golf'],
            ['name' => 'Gymnastics'],
            ['name' => 'Handball'],
            ['name' => 'Hockey'],
            ['name' => 'Horse Racing'],
            ['name' => 'Ice Hockey'],
            ['name' => 'Judo'],
            ['name' => 'Mixed Martial Arts'],
            ['name' => 'Modern Pentathlon'],
            ['name' => 'Motorsport'],
            ['name' => 'Netball'],
            ['name' => 'Olympic Sports'],
            ['name' => 'Rowing'],
            ['name' => 'Rugby League'],
            ['name' => 'Rugby Union'],
            ['name' => 'Sailing'],
            ['name' => 'Shooting'],
            ['name' => 'Snooker'],
            ['name' => 'Squash'],
            ['name' => 'Swimming'],
            ['name' => 'Table Tennis'],
            ['name' => 'Taekwondo'],
            ['name' => 'Tennis'],
            ['name' => 'Triathlon'],
            ['name' => 'Volleyball'],
            ['name' => 'Weightlifting'],
            ['name' => 'Winter Sports'],
            ['name' => 'Wrestling']];

        Sport::insert($sports);
    }
}
