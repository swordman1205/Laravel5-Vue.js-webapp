<?php

namespace App;

use App\Services\Filterable;
use Cviebrock\EloquentSluggable\Sluggable;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Laravel\Scout\Searchable;
use League\Geotools\Coordinate\Coordinate;
use League\Geotools\Geotools;

class Course extends Model
{

    use Searchable, Filterable, Sluggable;

    public $appends = ['seo_name_short', 'recipient', 'seo_name'];

    public $dates = ['start_date', 'end_date'];
    // public $appends = ['seo_name_short', 'recipient', 'seo_name', 'course_image'];
    protected $guarded = [];

    /**
     * @param $courseInput
     * @param $placeDetails
     * @param $companyId
     * @return Course
     */
    public static function createCourse($courseInput, $placeDetails, $companyId)
    {
        $course = new Course();
        $course->company_id = $companyId;
        $course->name = $courseInput['name'];

        if (isset($courseInput['sportId'])) {
            $course->sport_id = $courseInput['sportId'];
        }
        if (isset($courseInput['federationId'])) {
            $course->federation_id = $courseInput['federationId'];
        }
        if (isset($courseInput['address'])) {
            $course->address = $courseInput['address'];
        }
        if (isset($placeDetails['route'])) {
            $course->route = $placeDetails['route'];
        }
        if (isset($placeDetails['house_number'])) {
            $course->house_number = $placeDetails['house_number'];
        }
        if (isset($placeDetails['postal_code'])) {
            $course->postal_code = $placeDetails['postal_code'];
        }
        if (isset($placeDetails['municipality'])) {
            $course->municipality = $placeDetails['municipality'];
        }
        if (isset($placeDetails['province'])) {
            $course->province = $placeDetails['province'];
        }
        if (isset($placeDetails['region'])) {
            $course->region = $placeDetails['region'];
        }
        if (isset($placeDetails['country'])) {
            $course->country = $placeDetails['country'];
        }
        if (isset($courseInput['latitude'])) {
            $course->latitude = $courseInput['latitude'];
        }
        if (isset($courseInput['longitude'])) {
            $course->longitude = $courseInput['longitude'];
        }

        $initial_steps = new Collection([
            'step_1' => true,
            'step_2' => false,
            'step_3' => false,
            'step_4' => false,
            'step_5' => false,
            'step_6' => false,
            'step_7' => false,
            'step_8' => false,
            'step_9' => false
        ]);

        $course->completed_steps = $initial_steps;

        $course->save();

        return $course;
    }

    /**
     * @return string
     */
    public function searchableAs()
    {
        return 'courses';
    }

    /**
     * @return array
     */
    public function toSearchableArray()
    {
        $this->sport;
        $this->company;
        $array = $this->toArray();

        return $array;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class)->withDefault();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function courseSubscriptions()
    {
        return $this->hasMany(CourseSubscription::class);
    }

    public function courseValidSubscriptions()
    {
        return $this->hasMany(CourseSubscription::class)->where("registration_deadline", ">", date("Y-m-d"));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function documents()
    {
        return $this->belongsToMany(Document::class);
    }

    /**
     * @param $courseInput
     * @param $placeDetails
     */
    public function updateCourse($courseInput, $placeDetails, $company_id)
    {
        $this->name = $courseInput['name'];

        if (isset($courseInput['sportId'])) {
            $this->sport_id = $courseInput['sportId'];
        }
        if (isset($courseInput['federationId'])) {
            $this->federation_id = $courseInput['federationId'];
        }
        if (isset($courseInput['address'])) {
            $this->address = $courseInput['address'];
        }
        if (isset($placeDetails['route'])) {
            $this->route = $placeDetails['route'];
        }
        if (isset($placeDetails['house_number'])) {
            $this->house_number = $placeDetails['house_number'];
        }
        if (isset($placeDetails['postal_code'])) {
            $this->postal_code = $placeDetails['postal_code'];
        }
        if (isset($placeDetails['municipality'])) {
            $this->municipality = $placeDetails['municipality'];
        }
        if (isset($placeDetails['province'])) {
            $this->province = $placeDetails['province'];
        }
        if (isset($placeDetails['region'])) {
            $this->region = $placeDetails['region'];
        }
        if (isset($placeDetails['country'])) {
            $this->country = $placeDetails['country'];
        }
        if (isset($courseInput['latitude'])) {
            $this->latitude = $courseInput['latitude'];
        }
        if (isset($courseInput['longitude'])) {
            $this->longitude = $courseInput['longitude'];
        }
        $this->company_id = $company_id;

        $this->save();
    }

    /**
     * @param $dates
     */
    public function updateDates($dates)
    {
        $this->start_date = new DateTime(($dates['startDate']));
        $this->end_date = new DateTime(($dates['endDate']));

        $this->updateSteps('step_2');

        $this->save();
    }

    /**
     * @param $step
     */
    public function updateSteps($step)
    {
        $completed_steps = json_decode($this->completed_steps);
        $completed_steps->$step = true;
        $this->completed_steps = json_encode($completed_steps);

        if ($this->isComplete()) {
            $this->is_complete = 1;
            $this->is_active = 1;
        }
    }

    /**
     * @return bool
     */
    public function isComplete()
    {
        $completed_steps = json_decode($this->completed_steps);

        foreach ($completed_steps as $completed_step => $value) {

            if ($completed_step == "step_8") {
                if ($value == false && $this->has_lesson_packages == 1) {
                    return false;
                }
                break;
            }

            if ($completed_step == "step_9") {
                if ($value == false && $this->has_subscriptions == 1) {
                    return false;
                }
                break;
            }

            if ($value == false) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param $inputs
     * @param $image
     */
    public function updateStepThree($inputs)
    {
        if (isset($inputs['startAge'])) {
            $this->start_age = $inputs['startAge'];
        }

        if (isset($inputs['endAge'])) {
            $this->end_age = $inputs['endAge'];
        }

        if (isset($inputs['level'])) {
            $this->level = $inputs['level'];
        }

        $this->updateSteps('step_3');

        $this->save();
    }

    /**
     * @param $inputs
     * @param $image
     */
    public function updateStepFour($inputs, $image)
    {
        if (isset($inputs['name'])) {
            $this->responsible_name = $inputs['name'];
        }

        if (isset($image)) {
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(storage_path('app/public/course/'), $imageName);
            $this->responsible_photo = '/storage/course/' . $imageName;
        }

        if (isset($inputs['email']) && $inputs['hasEmail']) {
            $this->responsible_email = $inputs['email'];
        } else {
            $this->responsible_email = null;
        }

        $this->services()->detach();

        if (isset($inputs['checkedServices'])) {
            $this->services()->attach($inputs['checkedServices']);
        }

        $this->updateSteps('step_4');

        $this->save();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function services()
    {
        return $this->belongsToMany(Service::class)->withTimestamps();
    }

    /**
     * @param $courseInput
     * @param $placeDetails
     * @param $companyId
     * @return Course
     */

    /**
     * @param $audience
     */
    public function updateAudience($audience)
    {
        if (isset($audience['forAbleBodied'])) {
            if ($audience['forAbleBodied'] == 'true') {
                $this->for_able_bodied = 1;
            } else {
                $this->for_able_bodied = 0;
            }
        }

        $this->disabilities()->detach();

        if ($audience['forDisabled'] && isset($audience['checkedDisabilities'])) {
            $checkedDisabilities = explode(',', $audience['checkedDisabilities']);
            $this->disabilities()->attach($checkedDisabilities);
        }

        $this->save();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function disabilities()
    {
        return $this->belongsToMany(Disability::class)->withTimestamps();
    }

    /**
     * @param $gender
     */
    public function updateGender($gender)
    {
        $gender['male'] == 'true' ? $this->is_for_male = 1 : $this->is_for_male = 0;
        $gender['female'] == 'true' ? $this->is_for_female = 1 : $this->is_for_female = 0;
        $this->save();
    }

    /**
     * @param $offer
     */
    public function updateOffer($offer)
    {
        if (isset($offer['hasSubscription'])) {
            $this->has_subscriptions = $offer['hasSubscription'];
        }

        if (isset($offer['hasLessons'])) {
            $this->has_lesson_packages = $offer['hasLessons'];
        }

        if (isset($offer['isIncluded'])) {
            $this->membership_fee_included = $offer['isIncluded'];
            $this->membership_fee = $offer['quote'];
        }

        $this->updateSteps('step_7');
        $this->save();
    }

    /**
     * @param $lesson
     */
    public function updateLesson($lesson)
    {
        if (isset($lesson['isDuringCourseTime'])) {
            $this->lesson_during_course_time = $lesson['isDuringCourseTime'];
            $this->lesson_date_time = null;
            if (!$lesson['isDuringCourseTime'] && isset($lesson['specificDate'])) {
                $dateTime = new DateTime($lesson['specificDate']['date']);
                $dateTime->setTime($lesson['specificDate']['time']['hours'], $lesson['specificDate']['time']['minutes']);
                $this->lesson_date_time = $dateTime;
            }
        }
        $this->lesson_price = 0;
        if (!$lesson['isFreeLesson'] && isset($lesson['price'])) {
            $this->lesson_price = $lesson['price'];
        }
        $this->lesson_equipments = null;
        if (isset($lesson['equipments'])) {
            $this->lesson_equipments = $lesson['equipments'];
        }

        $this->updateSteps('step_6');

        $this->save();
    }

    /**
     * @param $input
     */
    public function updateSubscriptions($input)
    {
        if (isset($input['subscriptions'])) {
            $subscriptions = $input['subscriptions'];
            $this->subscriptions()->detach();
            foreach ($subscriptions as $courseSubscription) {
                $subscription = Subscription::find($courseSubscription['subscriptionId']);
                $this->subscriptions()->save($subscription, [
                    'price' => $courseSubscription['price'],
                    'registration_deadline' => new DateTime(($courseSubscription['registrationDeadline'])),
                ]);
            }
        }
        $this->subscriptionServices()->detach();
        if (isset($input['includedServices'])) {
            $includedServiceIds = $input['includedServices'];
            foreach ($includedServiceIds as $id) {
                $subscriptionService = SubscriptionService::find($id);
                $this->subscriptionServices()->save($subscriptionService, [
                    'is_excluded' => false]);
            }
        }

        if (isset($input['excludedServices'])) {
            $excludedServiceIds = $input['excludedServices'];
            foreach ($excludedServiceIds as $id) {
                $subscriptionService = SubscriptionService::find($id);
                $this->subscriptionServices()->save($subscriptionService, [
                    'is_excluded' => true]);
            }
        }

        $this->updateSteps('step_9');
        $this->save();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function subscriptions()
    {
        return $this->belongsToMany(Subscription::class)->withTimestamps()->withPivot('price', 'registration_deadline');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function subscriptionServices()
    {
        return $this->belongsToMany(SubscriptionService::class)->withTimestamps()->withPivot('is_excluded');
    }

    /**
     * @param $input
     */
    public function updateLessonPackages($input)
    {
        LessonPackage::where('course_id', $this->id)->delete();
        foreach ($input['lessonPackages'] as $lessonPackageInput) {
            LessonPackage::create([
                'course_id' => $this->id,
                'n_lessons' => $lessonPackageInput['nLessons'],
                'price' => $lessonPackageInput['price'],
                'start_date' => new DateTime($lessonPackageInput['startDate']),
                'end_date' => new DateTime($lessonPackageInput['endDate'])
            ]);
        }
        $this->lessonPackageServices()->detach();

        if (isset($input['includedLessonServices'])) {
            $includedServiceIds = $input['includedLessonServices'];
            foreach ($includedServiceIds as $id) {
                $lessonPackageService = LessonPackageService::find($id);
                $this->lessonPackageServices()->save($lessonPackageService, [
                    'is_excluded' => false]);
            }
        }

        if (isset($input['excludedLessonServices'])) {
            $excludedServiceIds = $input['excludedLessonServices'];
            foreach ($excludedServiceIds as $id) {
                $lessonPackageService = LessonPackageService::find($id);
                $this->lessonPackageServices()->save($lessonPackageService, [
                    'is_excluded' => true]);
            }
        }

        $this->updateSteps('step_8');
        $this->save();
    }

    /**
     * @return $this
     */
    public function lessonPackageServices()
    {
        return $this->belongsToMany(LessonPackageService::class)->withTimestamps()->withPivot('is_excluded');;
    }

    /**
     * @param $description
     * @param $courseImage
     * @param $galleryImages
     */
    public function updateDescription($description, $courseImage, $galleryImages)
    {
        if (isset($description)) {
            $this->description = $description;
        }

        if (isset($courseImage)) {
            $imageName = $this->id . '-' . time() . '.' . $courseImage->getClientOriginalExtension();
            $courseImage->move(storage_path('app/public/course/'), $imageName);
            $this->course_image = '/storage/course/' . $imageName;
        }

        CourseGalleryImage::where('course_id', $this->id)->delete();

        if (isset($galleryImages)) {
            foreach ($galleryImages as $key => $image) {
                $imageName = $this->id . '-' . time() . $key . '.' . $image->getClientOriginalExtension();
                $image->move(storage_path('app/public/course/'), $imageName);
                CourseGalleryImage::create([
                    'course_id' => $this->id,
                    'file_path' => '/storage/course/' . $imageName
                ]);
            }
        }
        $this->updateSteps('step_5');
        $this->save();
    }

    /**
     * @param $isActive
     */
    public function updateIsActive($isActive)
    {
        $this->is_active = $isActive == 'true';
        $this->save();
    }

    /**
     * @return mixed
     */
    public function replicateCourse()
    {
        return DB::transaction(function () {
            $newCourse = $this->replicate();
            $newCourse->save();
            $this->days()->each(function ($day) use ($newCourse) {
                $newDay = $day->replicate();
                $newDay->save();
                $newDay->updateCourseId($newCourse->id);
            });
            $disabilityIds = $this->disabilities()->pluck('disabilities.id');
            $newCourse->disabilities()->attach($disabilityIds);

            $serviceIds = $this->services()->pluck('services.id');
            $newCourse->services()->attach($serviceIds);

            // Workaround to get proper id. Laravel (bug) gives relation table id instead of subscription id.
            $subscriptionIds = $this->subscriptions()->pluck('subscriptions.id');
            $this->subscriptions()->each(function ($subscription, $key) use ($newCourse, $subscriptionIds) {
                $newCourse->subscriptions()->save($subscription, [
                    'subscription_id' => $subscriptionIds[$key],
                    'price' => $subscription->price,
                    'registration_deadline' => $subscription->registration_deadline]);
            });

            // Same workaround as above
            $subscriptionServiceIds = $this->subscriptionServices()->pluck('subscription_service_id');
            $this->subscriptionServices()->each(function ($service, $key) use ($newCourse, $subscriptionServiceIds) {
                $newCourse->subscriptionServices()->save($service, [
                    'subscription_service_id' => $subscriptionServiceIds[$key],
                    'is_excluded' => $service->is_excluded]);
            });

            $this->lessonPackages()->each(function ($lessonPackage) use ($newCourse) {
                $newLessonPackage = $lessonPackage->replicate();
                $newLessonPackage->save();
                $newLessonPackage->updateCourseId($newCourse->id);
            });

            // Same workaround as above
            $lessonPackageServicesIds = $this->lessonPackageServices()->pluck('lesson_package_service_id');
            $this->lessonPackageServices()->each(function ($service, $key) use ($newCourse, $lessonPackageServicesIds) {
                $newCourse->lessonPackageServices()->save($service, [
                    'lesson_package_service_id' => $lessonPackageServicesIds[$key],
                    'is_excluded' => $service->is_excluded]);
            });

            $this->galleryImages()->each(function ($image) use ($newCourse) {
                $newImage = $image->replicate();
                $newImage->save();
                $newImage->updateCourseId($newCourse->id);
            });

            return $newCourse;
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function days()
    {
        return $this->hasMany(CourseDay::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lessonPackages()
    {
        return $this->hasMany(LessonPackage::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function galleryImages()
    {
        return $this->hasMany(CourseGalleryImage::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getRecipientAttribute()
    {
        $recipient = "adulti";
        $from_age = $this->start_age;
        $to_age = $this->end_age;
        if ($to_age < 18) {
            if ($to_age < 12) {
                $recipient = "bambini";
            } else {
                if ($from_age < 12) {
                    $recipient = "bambini e ragazzi";
                } else {
                    $recipient = "ragazzi";
                }
            }
        } else {
            if ($from_age < 12) {
                $recipient = "bambini, ragazzi e adulti";
            } else {
                if ($from_age < 18) {
                    $recipient = "ragazzi e adulti";
                }
            }
        }

        return $recipient;
    }

    /**
     * @return string
     */
    public function getSeoNameAttribute()
    {
        $sport = $this->sport ? "di {$this->sport->name}" : "";

        $seo_name = "Corso $sport per " . $this->recipient;

        return $seo_name;
    }

    /**
     * Delete function
     */
    public function delete()
    {
        DB::transaction(function () {
            $this->days()->delete();
            $this->disabilities()->detach();
            $this->services()->detach();
            $this->subscriptions()->detach();
            $this->subscriptionServices()->detach();
            $this->lessonPackages()->delete();
            $this->lessonPackageServices()->detach();
            $this->galleryImages()->delete();
        });
        parent::delete();
    }

    /**
     * @return string
     */
    public function getSeoNameShortAttribute()
    {
        $sport = $this->sport ? "di {$this->sport->name}" : "";

        $name = "Corso $sport";
        return $name;
    }

    /**
     * @param $lat
     * @param $long
     */
    public function calculateDistancesFromHere($lat, $long)
    {
        $geotools = new Geotools();
        $my_position = null;

        if ($lat != "null" && $long != "null") {
            $my_position = new Coordinate([$lat, $long]);
        }

        if ($my_position) {
            $course_coords = new Coordinate([$this->latitude, $this->longitude]);
            $distance = $geotools->distance()->setFrom($my_position)->setTo($course_coords);
            $this->distance_from_my_position = $distance->in('km')->vincenty();
            $this->distance_from_my_position = round($this->distance_from_my_position, 2);
        }
    }


    public function getCourseImageAttribute($value)
    {
        if (!empty($value))
            return $value;

        if ($this->sport)
            return $this->sport->course_cover_image;

        return "/images/sports/sports.png";
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
                'source' => ['id', 'seo_name', 'company.name', 'municipality'],
                'separator' => '-'
            ]
        ];
    }

    /**
     * Restituisce il numero di sottoscrizioni e pacchetti acquistati
     *
     * @return mixed
     */
    public function getSubscribersNumber()
    {
        return CourseSubscription::where('course_id', $this->id)->count() +
            LessonPackage::where('course_id', $this->id)->count();

    }

    public function getShortAddress(){
        $short_address = $this->route . " " .  $this->house_number . ", " . $this->municipality;
        return $short_address;
    }

    public function getPeriod() {
        $period_string = "dal " . ($this->start_date? $this->start_date->format('d/m/Y') : '') . " al " . ($this->end_date? $this->end_date->format('d/m/Y'): '');
        return $period_string;
    }
}
