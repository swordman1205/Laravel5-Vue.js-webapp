<template>
<bs-drawer ref="drawer"
           side="left"
           bg="white"
           sidebar="md"
           class="bs-drawer__animate">
    <div class="search-view-filter">
        <div class="search-view-filter__view-options__mobile">
            <div>
                <button class="white-solid-button" id="filter-mobile-button" v-on:click="showFilters()">filtra</button>
                <!--<button class="white-solid-button">quando</button>-->
            </div>
            <div>
                <button class="blue-solid-button" id="view-mobile-button" v-on:click="changeButtonText()">mappa</button>
            </div>
        </div>
        <div class="search-view-filter__view-options">
            <b-form-group>
                <b-form-radio-group buttons
                                    button-variant="outline-secondary"
                                    v-model="selectedView"
                                    @change="onChangeView"
                                    :options="views"/>
            </b-form-group>
        </div>
        <div class="search-view-filter-container">
            <h6 class="search-view-filter__results">Filtra risultati</h6>
            <div class="search-view-filter__section search-view-filter__sports">
                <h6 class="search-view-filter__section__title">SPORTS</h6>
                <v-select id="sport-select" v-model="filters.sport"
                          label="name"
                          value="id"
                          :filterable="false"
                          :options="sports"
                          @search="onSearch">
                    <template slot="no-options">
                        Scrivi per selezionare lo sportnell'elenco
                    </template>
                    <template slot="option" slot-scope="option">
                        <div class="d-center">
                            {{ option.name }}
                        </div>
                    </template>
                    <template slot="selected-option" slot-scope="option">
                        <div class="selected d-center">
                            {{ option.name }}
                        </div>
                    </template>
                </v-select>
            </div>
            <div v-show="getParameterByName('latitude') != 'null' && getParameterByName('longitude') != 'null'"
                 class="search-view-filter__section search-view-filter__distance">
                <h6 class="search-view-filter__section__title">DISTANZA</h6>
                <vue-slider v-model="filters.distance"
                            v-bind="distanceSliderOptions">
                    <div class="search-view-filter__distance__tooltip" slot="tooltip" slot-scope="{ value }">
                        {{ value }}
                    </div>
                </vue-slider>
            </div>
            <div class="search-view-filter__section search-view-filter__age">
                <h6 class="search-view-filter__section__title">ETÀ</h6>
                <div class="search-view-filter__section__range">
                    <span>da</span>
                    <masked-input type="text"
                                  class="form-control"
                                  v-model="filters.age.from"
                                  :mask="[/\d/, /\d/]"
                                  :guide="false"
                                  placeholder="anni">
                    </masked-input>
                    <span>a</span>
                    <masked-input type="text"
                                  class="form-control"
                                  v-model="filters.age.to"
                                  :mask="[/\d/, /\d/]"
                                  :guide="false"
                                  placeholder="anni">
                    </masked-input>
                </div>
            </div>
            <div class="search-view-filter__section search-view-filter__date">
                <!--<h6 class="search-view-filter__section__title">DATA PROVA GRATUITA</h6>
                <datepicker :inline="true"
                            calendar-class="search-view-filter__date__datepicker"
                            v-model="filters.date.formatted">
                </datepicker>-->
                <h6 class="search-view-filter__section__title">GIORNO & ORARIO</h6>
                <custom-selector :selected="filters.day"
                                 value-field="value"
                                 :options="days"
                                 :is-multiple="true"
                                 placeholder="seleziona giorni"
                                 v-model="filters.days">
                </custom-selector>
                <div class="search-view-filter__section__range mt-3">
                    <span>da</span>
                    <masked-input type="text"
                                  class="form-control"
                                  v-model="filters.hours.startTime"
                                  :mask="[/\d/, /\d/, ':', /\d/, /\d/]"
                                  :guide="false"
                                  placeholder="hh:mm">
                    </masked-input>
                    <span>a</span>
                    <masked-input type="text"
                                  class="form-control"
                                  v-model="filters.hours.endTime"
                                  :mask="[/\d/, /\d/, ':', /\d/, /\d/]"
                                  :guide="false"
                                  placeholder="hh:mm">
                    </masked-input>
                </div>
            </div>
            <div class="search-view-filter__section search-view-filter__disability">
                <h6 class="search-view-filter__section__title">DISABILITÀ</h6>
                <b-form-group class="mt-1">
                    <b-form-checkbox-group stacked
                                           class="orangogo-checkbox"
                                           v-model="filters.disability"
                                           :options="disabilityOptions">
                    </b-form-checkbox-group>
                </b-form-group>
            </div>
            <div class="search-view-filter__section search-view-filter__show-results-mobile">
                <button class="orange-gradient-button" v-on:click="hideFilters()">mostra risultati</button>
            </div>
        </div>

    </div>
</bs-drawer>
</template>

<script>

    import SportTemplate from './SearchViewFilterSportTemplate'
    import vueSlider from 'vue-slider-component'
    import datepicker from 'vuejs-datepicker'
    import createNumberMask from 'text-mask-addons/dist/createNumberMask'
    import * as VueGoogleMaps from 'vue2-google-maps'
    import vSelect from 'vue-select'
    import BsDrawer from 'vue-bs-drawer'

    export default {
        name: "SearchViewFilter",
        props: {
            changeView: Function
        },
        data() {
            return {
                first_search: 0,
                selectedView: 'list',
                views: [
                    {text: 'mappa', value: 'map'},
                    {text: 'lista', value: 'list'}
                ],
                filters: {
                    sport: null,
                    age: {},
                    hours: {},
                    days: [],
                    price: {},
                    disability: [],
                    distance: '10km',
                    freeTrial: true
                },
                sports: [],
                sportTemplate: SportTemplate,
                distanceSliderOptions: {
                    height: 3,
                    dotWidth: 20,
                    dotHeight: 20,
                    tooltip: 'always',
                    tooltipDir: 'bottom',
                    piecewise: true,
                    piecewiseLabel: true,
                    data: [
                        '50m',
                        '5km',
                        '10km',
                        '50km',
                        '500km'
                    ],
                    processStyle: {
                        backgroundColor: 'transparent'
                    },
                    piecewiseStyle: {
                        backgroundColor: '#cccccc',
                        width: '2px',
                        height: '18px'
                    },
                    labelStyle: {
                        marginTop: '-40px',
                        color: '#888'
                    },
                    sliderStyle: {
                        border: '3px solid #f4812e',
                        boxShadow: 'none'
                    }
                },
                days: [
                    {value: '0', name: 'Lunedì'},
                    {value: '1', name: 'Martedì'},
                    {value: '2', name: 'Mercoledì'},
                    {value: '3', name: 'Giovedì'},
                    {value: '4', name: 'Venerdì'},
                    {value: '5', name: 'Sabato'},
                    {value: '6', name: 'Domenica'}
                ],
                disabilityOptions: [
                    {text: 'Visiva', value: '1'},
                    {text: 'Uditivà', value: '2'},
                    {text: 'Motoria', value: '3'},
                    {text: 'Intelletivo-relazionale', value: '4'}
                ],
                currencyMask: null
            }
        },
        components: {
            'vue-google-autocomplete': VueGoogleMaps.Autocomplete,
            vueSlider,
            datepicker,
            'v-select': vSelect,
            BsDrawer
        },
        created() {
            var self = this;

            this.currencyMask = createNumberMask({
                prefix: '€',
                suffix: '',
                includeThousandsSeparator: true,
                thousandsSeparatorSymbol: ',',
                allowDecimal: true,
                decimalSymbol: '.',
                decimalLimit: 2,
            })

            this.$bus.$on('day_selected', function (args) {
                self.filters.days = args;
            });

            this.$bus.$on('free-trial', function (args) {
                self.filters.freeTrial = !self.filters.freeTrial;
            });
        },
        mounted() {
            this.$refs.drawer.closeDrawer()
        },
        methods: {
            getParameterByName(name, url) {
                if (!url) url = window.location.href;

                name = name.replace(/[\[\]]/g, '\\$&');
                var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
                    results = regex.exec(url);

                if (!results) return null;
                if (!results[2]) return '';

                return decodeURIComponent(results[2].replace(/\+/g, ' '));
            },
            onSearch(search, loading) {
                this.search(loading, search, this);
            },
            search: _.debounce((loading, search, vm) => {
                axios({
                    method: 'get',
                    url: '/sports/search?q=' + search
                }).then((response) => {
                    vm.sports = response.data.sports;
                    loading(false);

                }).catch((error) => {
                    this.message = 'Error';
                    loading(false);
                });
            }),
            toggleView(view) {
                this.selectedView = view
            },
            applyFilters() {
                this.$bus.$emit('searching');

                var self = this;

                axios({
                    method: 'post',
                    url: '/filterCourses',
                    data: {
                        filters: this.filters,
                        latitude: this.getParameterByName('latitude'),
                        longitude: this.getParameterByName('longitude'),
                        search_string: this.getParameterByName('search_string')
                    }
                }).then((response) => {
                    self.$bus.$emit('filtered', response.data);
                }).catch((error) => {
                    self.$bus.$emit('filtered', null);
                    window.alerts.error("Si è verificato un errore con la tua ricerca");
                });
            },
            getParameterByName(name, url) {
                if (!url) url = window.location.href;
                name = name.replace(/[\[\]]/g, '\\$&');
                var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
                    results = regex.exec(url);
                if (!results) return null;
                if (!results[2]) return '';
                return decodeURIComponent(results[2].replace(/\+/g, ' '));
            },
            getLabel(item) {
                if (!item) return ''
                return item.name
            },
            async doFilterSports(text) {
                if (!text) {
                    this.sports = []
                    return
                }
                try {
                    const res = await axios({
                        url: `/sports/search`,
                        params: {
                            q: text
                        }
                    })
                    this.sports = res.data.sports
                } catch (e) {
                    this.handleError(e)
                }
            },
            checkSports(val) {
                if (!val) {
                    this.sports = []
                }
            },
            onChangeView(val) {
                if (!this.changeView) return;
                this.changeView(val)
            },
            changeButtonText() {
                /*console.log(event.target.innerText);*/
                if ($('.search-view-results').is(':visible')) {
                    event.target.innerText = 'lista';
                    $('.search-view-results').hide();
                    $('.search-view-map').show();
                }
                else {
                    event.target.innerText = 'mappa';
                    $('.search-view-results').show();
                    $('.search-view-map').hide();
                }
            },
            showFilters(){
                if($('.search-view-filter-container').is(':visible')){
                    $('.search-view-filter-container').hide();
                    $('.search-view-results').show();
                    event.target.innerText = 'filtra';
                    $('#view-mobile-button').show();
                    $('#view-mobile-button').html('mappa');

                }
                else {
                    console.log('non visible');
                    $('.search-view-filter-container').show();
                    $('.search-view-results').hide();
                    $('.search-view-map').hide();
                    event.target.innerText = 'resetta filtri';
                    $('#view-mobile-button').hide();
                }
            },
            hideFilters(){
                $('.search-view-filter-container').hide();
                $('.search-view-results').show();
                $('#filter-mobile-button').html('filtra');
                $('#view-mobile-button').show();
                $('#view-mobile-button').html('mappa');
            }
        },
        watch: {
            filters: {
                handler: function (newValue) {
                    if (this.first_search < 5) {
                        this.first_search++;
                        return;
                    }
                    this.applyFilters();
                },
                deep: true,
            },
        },
    }
</script>
