<?php

namespace App;

use App\Services\GoogleMapsService;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use Sluggable;

    protected $guarded = [

    ];

    /**
     * @param $company
     * @param $user
     * @return mixed
     */
    public static function storeCompany($company, $user)
    {
        $company = self::create([
            'name' => $company->name,
            'typology_id' => $company->typology_id,
            'sport_id' => $company->sport_id,
            'registrant_id' => $user->id
        ]);
        $company->users()->attach($user);

        return $company;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('App\User')->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function registrant()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function federation()
    {
        return $this->belongsTo('App\Federation');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function purchaseElements()
    {
        return $this->hasMany(PurchaseElement::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function sports()
    {
        return $this->belongsToMany('App\Sport')->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function courses()
    {
        return $this->hasMany('App\Course');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function galleryImages()
    {
        return $this->hasMany('App\CompanyGalleryImage');
    }

    /**
     * @param $user
     * @return mixed
     */
    public function hasUser($user)
    {
        return $this->users->contains($user);
    }

    /**
     * @param $name
     */
    public function updateName($name)
    {
        $this->name = $name;
        $this->save();
    }

    /**
     * @param $address
     */
    public function updateAddress($address)
    {
        $this->address = $address;
        $this->save();
    }

    /**
     * @param $data
     */
    public function fillAddressData($data)
    {
        $this->address = $data['address'];
        $placeDetails = GoogleMapsService::getDetailsFromAddressComponents($data['addressComponents']);
        $this->updateGoogleAddress($placeDetails, $data['latitude'], $data['longitude']);
    }

    /**
     * @param $placeDetails
     * @param $latitude
     * @param $longitude
     */
    public function updateGoogleAddress($placeDetails, $latitude, $longitude)
    {
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
        if (isset($latitude)) {
            $this->latitude = $latitude;
        }
        if (isset($longitude)) {
            $this->longitude = $longitude;
        }
        $this->save();
    }

    /**
     * @param $inputs
     * @param $logo
     * @param $galleryImages
     * @param $statuteFile
     * @param $privacyPolicyFile
     */
    public function updateCompany($inputs, $logo, $galleryImages, $oldGalleryImages, $statuteFile, $privacyPolicyFile)
    {
        if (isset($inputs['name'])) {
            $this->name = $inputs['name'];
        }
        $this->public_name = null;
        if (isset($inputs['publicName'])) {
            $this->public_name = $inputs['publicName'];
        }
        if (isset($inputs['legalFormId'])) {
            $this->legal_form_id = $inputs['legalFormId'];
        }
        if (isset($inputs['typologyId'])) {
            $this->typology_id = $inputs['typologyId'];
        }
        if (isset($inputs['email'])) {
            $this->email = $inputs['email'];
        }
        if (isset($inputs['phoneNumber'])) {
            $this->phone_number = $inputs['phoneNumber'];
        }
        if (isset($inputs['fiscalCode'])) {
            $this->fiscal_code = $inputs['fiscalCode'];
        }

        if (isset($inputs['address']) && isset($inputs['addressComponents'])) {
            $this->address = $inputs['address'];
            $placeDetails = GoogleMapsService::getDetailsFromAddressComponents($inputs['addressComponents']);
            $this->route = $placeDetails['route'];
            $this->house_number = $placeDetails['house_number'];
            $this->postal_code = $placeDetails['postal_code'];
            $this->municipality = $placeDetails['municipality'];
            $this->province = $placeDetails['province'];
            $this->region = $placeDetails['region'];
            $this->country = $placeDetails['country'];

            if (isset($inputs['latitude'])) {
                $this->latitude = $inputs['latitude'];
            }
            if (isset($inputs['longitude'])) {
                $this->longitude = $inputs['longitude'];
            }
        }

        $this->promotion_agency = null;
        if (isset($inputs['promotionAgency'])) {
            $this->promotion_agency = $inputs['promotionAgency'];
        }

        if ($logo !== '') {
            $this->logo_path = null;
            if (isset($logo)) {
                $imageName = $this->id . '-' . time() . 'l.' . $logo->getClientOriginalExtension();
                $logo->move(storage_path('app/public/company'), $imageName);
                $this->logo_path = '/storage/company/' . $imageName;
            }
        }

        $this->description = null;
        if (isset($inputs['description'])) {
            $this->description = $inputs['description'];
        }

        $this->vat_number = null;
        if (isset($inputs['vatNumber'])) {
            $this->vat_number = $inputs['vatNumber'];
        }

        $this->social_share = null;
        if (isset($inputs['socialShare'])) {
            $this->social_share = $inputs['socialShare'];
        }
        $this->federation_id = null;
        if (isset($inputs['federationId'])) {
            $this->federation_id = $inputs['federationId'];
        }

        $this->statute = null;
        if (isset($inputs['statute']['text'])) {
            $this->statute = $inputs['statute']['text'];
        }

        $this->statute_path = null;
        if (isset($statuteFile)) {
            $fileName = $this->id . '-' . time() . 's.' . $statuteFile->getClientOriginalExtension();
            $statuteFile->move(storage_path('app/public/company'), $fileName);
            $this->statute_path = '/storage/company/' . $fileName;
        }

        $this->privacy_policy = null;
        if (isset($inputs['privacyPolicy']['text'])) {
            $this->privacy_policy = $inputs['privacyPolicy']['text'];
        }

        $this->privacy_policy_path = null;
        if (isset($privacyPolicyFile)) {
            $fileName = $this->id . '-' . time() . 'p.' . $privacyPolicyFile->getClientOriginalExtension();
            $privacyPolicyFile->move(storage_path('app/public/company'), $fileName);
            $this->privacy_policy_path = '/storage/company/' . $fileName;
        }

        $this->updateGallery($galleryImages, $oldGalleryImages, $inputs);

        $this->save();
    }

    /**
     * @param $galleryImages
     * @param $oldGalleryImages
     * @param $inputs
     */
    public function updateGallery($galleryImages, $oldGalleryImages, $inputs)
    {
        $actualGallery = CompanyGalleryImage::where('company_id', $this->id)->get();

        foreach ($actualGallery as $image) {
            $delete = true;
            foreach ($oldGalleryImages as $oldGalleryImage) {
                if ($image->file_path == $oldGalleryImage) {
                    $delete = false;
                }
            }
            if ($delete) {
                CompanyGalleryImage::where('id', $image->id)->delete();
            }
        }


        if (isset($galleryImages)) {
            foreach ($galleryImages as $key => $image) {
                $imageName = $this->id . '-' . time() . $key . '.' . $image->getClientOriginalExtension();
                $image->move(storage_path('app/public/company'), $imageName);
                CompanyGalleryImage::create([
                    'company_id' => $this->id,
                    'file_path' => '/storage/company/' . $imageName]);
            }
        }
        if ($inputs['gallery']) {
            foreach ($inputs['gallery'] as $image) {
                if (strpos($image, '/img/company/') !== false) {
                    CompanyGalleryImage::create([
                        'company_id' => $this->id,
                        'file_path' => $image]);
                }
            }
        }
    }

    public function getPublicNameAttribute($value)
    {
        if (!empty($value))
            return $value;

        return $this->name;
    }

    public function getLogoPathAttribute($value)
    {
        if (!empty($value))
            return $value;

        $course = $this->courses()->first();

        if ($course && $course->sport) {
            return $course->sport->company_cover_image;
        }
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
                'source' => ['id', 'name', 'municipality'],
                'separator' => '-'
            ]
        ];
    }

    public function legalForm()
    {
        return $this->belongsTo(LegalForm::class);
    }
}
