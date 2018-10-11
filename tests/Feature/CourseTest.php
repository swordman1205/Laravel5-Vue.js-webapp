<?php

namespace Tests\Feature;

use App\Course;
use App\Federation;
use App\User;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CourseTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test CourseController@storeCourse with proper field data
     */
    public function testSuccessfulStoreCourse()
    {
        $federation = Federation::create(['name' => 'Test Federation']);
        $response = $this->json('POST', '/api/courses',
            [
                'course' => [
                    'name' => 'Course 1',
                    'federationId' => $federation->id,
                    'address' => 'Via Perrone, 5, 10122 Torino TO, Italy',
                    'addressComponents' => json_decode('[{"long_name": "5", "short_name": "5", "types": ["street_number"]},
                                        {"long_name": "Via Perrone", "short_name": "Via Perrone", "types": ["route"]},
                                        {"long_name": "Torino", "short_name": "Torino", "types": ["locality", "political"]},
                                        {"long_name": "Torino", "short_name": "Torino", "types": ["administrative_area_level_3", "political"]},
                                        {"long_name": "Città Metropolitana di Torino", "short_name": "TO", "types":["administrative_area_level_2", "political"]},
                                        {"long_name": "Piemonte", "short_name": "Piemonte", "types": ["administrative_area_level_1", "political"]},
                                        {"long_name": "Italien", "short_name": "IT", "types": ["country", "political"]},
                                        {"long_name": "10122", "short_name": "10122", "types": ["postal_code"]}]'),
                    'latitude' => 45.0737146,
                    'longitude' => 7.6734678999999915]
            ])
            ->assertStatus(200);
        $course = Course::latest()->first();
        $this->assertEquals($course->name, 'Course 1');
        $this->assertEquals($course->federation_id, $federation->id);
        $this->assertEquals($course->route, 'Via Perrone');
        $this->assertEquals($course->address, 'Via Perrone, 5, 10122 Torino TO, Italy');
        $this->assertEquals($course->postal_code, '10122');
        $this->assertEquals($course->house_number, '5');
        $this->assertEquals($course->latitude, 45.0737146);
        $this->assertEquals($course->longitude, 7.6734678999999915);
        $this->assertEquals($course->province, 'TO');
        $this->assertEquals($course->region, 'Piemonte');
        $this->assertEquals($course->country, 'IT');
    }

    /**
     * Test CourseController@storeCourse with invalid field data
     */
    public function testFailedStoreCourse()
    {
        $federation = Federation::create(['name' => 'Test Federation']);

        // Invalid Federation id
        $response = $this->json('POST', '/api/courses',
            [
                'course' => [
                    'name' => 'Course 1',
                    'federationId' => 99999,
                    'address' => 'Via Perrone, 5, 10122 Torino TO, Italy',
                    'addressComponents' => json_decode('[{"long_name": "5", "short_name": "5", "types": ["street_number"]},
                                        {"long_name": "Via Perrone", "short_name": "Via Perrone", "types": ["route"]},
                                        {"long_name": "Torino", "short_name": "Torino", "types": ["locality", "political"]},
                                        {"long_name": "Torino", "short_name": "Torino", "types": ["administrative_area_level_3", "political"]},
                                        {"long_name": "Città Metropolitana di Torino", "short_name": "TO", "types":["administrative_area_level_2", "political"]},
                                        {"long_name": "Piemonte", "short_name": "Piemonte", "types": ["administrative_area_level_1", "political"]},
                                        {"long_name": "Italien", "short_name": "IT", "types": ["country", "political"]},
                                        {"long_name": "10122", "short_name": "10122", "types": ["postal_code"]}]'),
                    'latitude' => 45.0737146,
                    'longitude' => 7.6734678999999915]
            ])
            ->assertStatus(422);

        // No long or lat
        $response = $this->json('POST', '/api/courses',
            [
                'course' => [
                    'name' => 'Course 1',
                    'federationId' => 99999,
                    'address' => 'Via Perrone, 5, 10122 Torino TO, Italy',
                    'addressComponents' => json_decode('[{"long_name": "5", "short_name": "5", "types": ["street_number"]},
                                        {"long_name": "Via Perrone", "short_name": "Via Perrone", "types": ["route"]},
                                        {"long_name": "Torino", "short_name": "Torino", "types": ["locality", "political"]},
                                        {"long_name": "Torino", "short_name": "Torino", "types": ["administrative_area_level_3", "political"]},
                                        {"long_name": "Città Metropolitana di Torino", "short_name": "TO", "types":["administrative_area_level_2", "political"]},
                                        {"long_name": "Piemonte", "short_name": "Piemonte", "types": ["administrative_area_level_1", "political"]},
                                        {"long_name": "Italien", "short_name": "IT", "types": ["country", "political"]},
                                        {"long_name": "10122", "short_name": "10122", "types": ["postal_code"]}]'),
                ]
            ])
            ->assertStatus(422);
    }

    /**
     * Test CourseController@storeCourse with proper field data
     */
    public function testSuccessfulUpdateCourseDates()
    {
        $newCourse = new Course();
        $newCourse->name = 'Course 1';
        $newCourse->route = 'Via Perrone';
        $newCourse->address = 'Via Perrone, 5, 10122 Torino TO, Italy';
        $newCourse->postal_code = '10122';
        $newCourse->house_number = '5';
        $newCourse->latitude = 45.0737146;
        $newCourse->longitude = 7.6734678999999915;
        $newCourse->province = 'TO';
        $newCourse->region = 'Piemonte';
        $newCourse->country = 'IT';
        $newCourse->save();

        $courseDays[0] = [
            'name' => 'Sunday',
            'shortName' => 'Su',
            'isActive' => true,
            'startTime' => [
                'HH' => '15',
                'mm' => '00'],
            'endTime' => [
                'HH' => '16',
                'mm' => '15']];
        $response = $this->json('PUT', '/api/courses/' . $newCourse->id . '/dates',
            [
                'courseDates' => [
                    'endDate' => '2018-06-03T12:48:00.000Z',
                    'startDate' => '2018-06-01T12:48:00.000Z'
                ],
                'courseDays' => $courseDays
            ])
            ->assertStatus(200);
        $course = Course::find($newCourse->id);
        $this->assertEquals(substr($course->start_date, 0, 10), '2018-06-01');
        $this->assertEquals(substr($course->end_date, 0, 10), '2018-06-03');

        $courseDay = $course->days()->first();
        $this->assertEquals($courseDay->start_time, '15:00');
        $this->assertEquals($courseDay->end_time, '16:15');
        $this->assertEquals($courseDay->day, 'Sunday');
        $this->assertEquals($courseDay->course_id, $newCourse->id);
    }

    /**
     * Test CourseController@updateDates with invalid field data
     */
    public function testFailedUpdateDates()
    {
        $newCourse = new Course();
        $newCourse->name = 'Course 1';
        $newCourse->route = 'Via Perrone';
        $newCourse->address = 'Via Perrone, 5, 10122 Torino TO, Italy';
        $newCourse->postal_code = '10122';
        $newCourse->house_number = '5';
        $newCourse->latitude = 45.0737146;
        $newCourse->longitude = 7.6734678999999915;
        $newCourse->province = 'TO';
        $newCourse->region = 'Piemonte';
        $newCourse->country = 'IT';
        $newCourse->save();

        // No data
        $response = $this->json('PUT', '/api/courses/' . $newCourse->id . '/dates',
            [

            ])
            ->assertStatus(422);
    }
}
