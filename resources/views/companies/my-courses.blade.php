<div>
    <b-container>
        <b-row class="w-100">
            <b-col cols="12" md="">
                <h2 class="font-weight-bold">I miei corsi (@{{courses.length}})</h2>
            </b-col>
            <b-col cols="12" md="" offset-lg="4">
                <b-button type="button" class="orange-gradient-button w-100" @click="createCourse">CREA CORSO
                </b-button>
            </b-col>
        </b-row>
        <b-row class="w-100 mt-4 dashboard__courses__filter-container">

            <div class="dropdown">
                <button class="btn-dropdown-filter dropdown-toggle dashboard__courses__filter-dropdown" type="button" id="dropdownMenuButton"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="float-left p-l-20">@{{filterSport ? filterSport.name : 'Sport'}}</span>
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a @click="clearSportFilter()" class="dropdown-item">Tutti gli sport</a>
                    <a v-for="sport in sports" @click="filterBySport(sport)" class="dropdown-item">@{{sport.name}}</a>
                </div>
            </div>


            <div class="dropdown m-l-10">
                <button class="btn-dropdown-filter dropdown-toggle dashboard__courses__filter-dropdown" type="button" id="dropdownMenuButton"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="float-left p-l-20">@{{actualAge ? actualAge: 'Età'}}</span>
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a @click="clearAgeFilter()" class="dropdown-item">Tutte le età</a>
                    <a v-for="age in ages" @click="filterByAge(age)" class="dropdown-item">@{{age}}</a>
                </div>
            </div>

        </b-row>
        <b-row v-for="(course, courseIndex) in courses" :key="courseIndex" :class="hiddenCourse(course)"
               class="mt-4 border-bottom">
            <b-row class="w-100">
                <b-col cols="12" md="3">
                    <a :href="buildCourseUrl(course)"><img class="course-image" :src="course.course_image"/></a>
                </b-col>
                <b-col cols="12" md="9">
                    <i class="company-icon mr-2 fas fa-map-marker-alt"></i>
                    <span class="text-uppercase company-dashboard__course-address">@{{ course.route }}
                        <span v-if="course.house_number" class=""> @{{ course.house_number }}</span>
                        <span v-if="course.province" class="">, @{{ course.province }}</span>
                    </span>
                    <h2 class="font-weight-bold">@{{course.name}}</h2>
                    <b-row>
                        <span class="float-left p-r-10 border-right-grey"><b>@{{course.sport.name}}</b></span>
                        <span class="float-left p-r-10 p-l-10 border-right-grey">@{{course.start_age}} - @{{ course.end_age }} anni</span>
                        <span class="float-left p-r-10 p-l-10 border-right-grey">@{{course.level}}</span>
                        <span class="float-left p-l-10">Periodo 02/01 - 02/10</span>
                    </b-row>
                    <b-row class="mt-3">
                        <div class="d-inline-block">
                            <div v-for="day in activeDays[courseIndex]" class="company-day text-center"
                                 :class="getDayClass(day)">@{{ day.shortName }}
                            </div>
                        </div>

                    </b-row>
                </b-col>
            </b-row>
            <b-row class="w-100 mb-2 mt-2">
                <b-col cols="auto" md="5" class="aligned-items-center"></b-col>

                <b-col cols="12" md="7">
                    <b-row class="text-right w-100 dashboard__courses__item__menu">
                        <div class="border-left pl-3 pr-3">
                            <span class="text-uppercase">
                                Attivo
                                <label class="ml-2 switch">
                                    <input type="checkbox" :id="getActiveCourseCheckboxId(course.id)"
                                           v-model="course.is_active"
                                           @change="activate(course, courseIndex)">
                                    <span class="slider round"></span>
                                </label>
                            </span>
                        </div>
                        <div class="border-left pl-3 pr-3">
                            <span class="text-uppercase cursor-pointer" @click="edit(course.slug)">
                                Modifica<i class="cursor-pointer company-icon ml-2 fas fa-pencil-alt"
                                           ></i>
                            </span>
                        </div>

                        <div class="border-left pl-3 pr-3">
                            @if(count(\Illuminate\Support\Facades\Auth::user()->companies) > 1)
                                <span class="text-uppercase cursor-pointer" @click="duplicationModalShow = !duplicationModalShow">Duplica<i
                                            class="company-icon ml-2 far fa-clone"></i></span>
                            @else
                                <span class="text-uppercase cursor-pointer" @click="duplicate(course.id)">Duplica<i
                                            class="company-icon ml-2 far fa-clone"></i></span>
                            @endif
                        </div>
                        <div class="border-left pl-3 pr-3">
                            <span class="text-uppercase cursor-pointer" @click="modalShow = !modalShow">Elimina<i
                                        class="company-remove-icon ml-2 fas fa-times"
                                       ></i></span></span>

                        </div>
                        <b-modal v-model="duplicationModalShow" @ok="duplicateIn(course.id)">
                            <div class="form-group">
                                <legend>Seleziona la società</legend>
                                <b-form-select v-model="selectedCompany"
                                               value-field="id" text-field="name"
                                               :options="companies" class="form-control">
                                </b-form-select>
                            </div>
                        </b-modal>
                        <b-modal v-model="modalShow" @ok="remove(course.id, courseIndex)">
                            Sei sicuro di voler eliminare questo corso?
                        </b-modal>
                    </b-row>
                </b-col>
            </b-row>
        </b-row>


    </b-container>
</div>
