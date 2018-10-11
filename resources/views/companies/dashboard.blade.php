@extends('layouts.main')
@section('title', 'La Tua Dashboard')
@section('navbar-title', $company->name)
@section('current-company', $company)


@section('content')
    <company-dashboard company-id="{{$company->id}}" :company="{{$company}}" user-id="{{$user->id}}" inline-template v-cloak>
        <b-row class="mt-4">
            <sidebar :sidebar-index="sidebarIndex" @go-to="goTo" inline-template v-cloak>
                @include('companies.sidebar')
            </sidebar>
            <b-col cols="12" md="9" lg="10" offset-md="3" offset-lg="2" class="m-t-20 pt-4 pt-sm-0">
                <div class="dashboard-alerts">
                    <b-alert :show="dismissCountDownError"
                             dismissible
                             fade
                             variant="danger"
                             @dismissed="dismissCountDownError=0"
                             @dismiss-count-down="countDownErrorChanged">
                        @{{error}}
                    </b-alert>
                    <b-alert :show="dismissCountDownSuccess"
                             dismissible
                             fade
                             variant="success"
                             @dismissed="dismissCountDownSuccess=0"
                             @dismiss-count-down="countDownSuccessChanged">
                        @{{ success }}
                    </b-alert>
                </div>
                <b-button v-if="modify" variant="link" class="font-color-orange mb-4" @click="resetModify">
                    <span class="text-uppercase font-weight-bold ml-1">
                    <i class="fas fa-chevron-left"></i>
                    Torna ad account utente</span>
                </b-button>
                <my-courses inline-template v-if="sidebarIndex === 0" :courses="courses" :sports="sports" :company="{{$company}}"
                            :companies="{{\Illuminate\Support\Facades\Auth::user()->companies}}"
                            @init-error="initError" @init-success="initSuccess">
                    @include('companies.my-courses')
                </my-courses>
                <bookings v-if="sidebarIndex === 1" :reservationsprop="{{$reservations}}"
                          @init-error="initError" @init-success="initSuccess" inline-template>
                    @include('companies.bookings')
                </bookings>
                <profile inline-template v-if="sidebarIndex === 2" :user="{{$user}}" :company-id="companyId"
                         @init-error="initError" @init-success="initSuccess">
                    @include('companies.profile')
                </profile>
                <user-account inline-template v-if="sidebarIndex === 3 && modify === null" :company-id="companyId" :user-id="userId"
                              @modify-email="modifyEmail" @modify-password="modifyPassword"
                              @init-error="initError" @init-success="initSuccess">
                    @include('users.user-account')
                </user-account>
                <modify-email inline-template v-if="sidebarIndex === 3 && modify === 'email'" :user-id="userId"
                              @reset-modify="resetModify" @init-error="initError" @init-success="initSuccess">
                    @include('users.modify-email')
                </modify-email>
                @if ($purchases)
                <div v-if="sidebarIndex === 4">
                    @include('companies.purchases')
                </div>
                @endif
                <modify-password inline-template v-if="sidebarIndex === 3 && modify === 'password'"
                                 :user-id="userId"
                                 @reset-modify="resetModify" @init-error="initError" @init-success="initSuccess">
                    @include('users.modify-password')
                </modify-password>
            </b-col>
        </b-row>
    </company-dashboard>
@endsection
@section('script')
    <script>
        window.baseUrl = '{{env('APP_URL')}}'
    </script>
@endsection