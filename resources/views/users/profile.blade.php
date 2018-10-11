@extends('layouts.main')
@section('title', 'User Profile')
@section('content')
    <user-profile inline-template v-cloak user-id="{{$user->id}}">
        <b-row class="mt-4">
            <sidebar :sidebar-index="sidebarIndex" @go-to="goTo" inline-template>
                @include('users.sidebar')
            </sidebar>
            <b-container>
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
                <user-reservations :reservationsprop="{{$reservations}}" v-if="sidebarIndex === 0" @init-error="initError" @init-success="initSuccess" inline-template>
                    @include('users.reservations')
                </user-reservations>
                <user-account inline-template v-if="sidebarIndex === 1" v-show="modify === null"  :user-id="userId"
                              @modify-email="modifyEmail" @modify-password="modifyPassword"
                              @init-error="initError" @init-success="initSuccess">
                    @include('users.user-account')
                </user-account>
                <modify-email inline-template v-if="sidebarIndex === 1" v-show="modify === 'email'" :user-id="userId"
                              @reset-modify="resetModify" @init-error="initError" @init-success="initSuccess">
                    @include('users.modify-email')
                </modify-email>
                <div v-if="sidebarIndex === 4">
                    @include('users.purchases')
                </div>
                <modify-password inline-template v-if="sidebarIndex === 1" v-show="modify === 'password'"
                                 :user-id="userId"
                                 @reset-modify="resetModify" @init-error="initError" @init-success="initSuccess">
                    @include('users.modify-password')
                </modify-password>
            </b-container>
        </b-row>
    </user-profile>
@endsection
@section('script')
    <script>
        window.baseUrl = '{{env('APP_URL')}}'
    </script>
@endsection