<template>
    <b-col lg="12" sm="12" class="search-view-results">
        <element-clip-loader :loadingprop="false"
                             :color="'#f4812e'"
                             :size="'100px'"
                             :text="''">
        </element-clip-loader>
        <div class="col-lg-12 col-sm-12 col-md-12 search-view-results__header">
            <div class="search-view-results__header__count-free-trial">
                <div class="col-xs-12 mobile-hidden">
                    <h6 class="search-view-results__header__counts" v-if="results.length != 1">{{resultsLength()}}
                        risultati
                        vicino a te</h6>
                    <h6 class="search-view-results__header__counts" v-if="results.length == 1">{{resultsLength()}}
                        risultato
                        vicino a te</h6>
                </div>
                <div class="col-xs-12 search-view-results__free-trial-filter">
                    <switches v-model="freeTrial"
                              theme="bootstrap"
                              color="orango"
                              type-bold="true">
                    </switches>
                    <h6 class="mb-0 ml-3">PROVA GRATUITA</h6>
                    <!--<SearchViewResultsSortBy :do-sort="sortBy">
                    </SearchViewResultsSortBy>
                    </div>-->
                </div>
                <!--<SearchViewResultsFilterChips :filters="filters"
                :cancel-filter="cancelFilter">
                </SearchViewResultsFilterChips>-->
            </div>
            <div class="search-view-results__body">
                <SearchViewResultCard :course="item"
                                      :is-detail="true"
                                      v-for="(item,index) in results"
                                      :key="index"
                >
                </SearchViewResultCard>
            </div>
            <Paginator :data-set="dataSet" @page_changed="fetch"></Paginator>
        </div>
    </b-col>
</template>

<script>
    import Switches from 'vue-switches'
    import SearchViewResultsFilterChips from './SearchViewResultsFilterChips'
    import SearchViewResultsSortBy from './SearchViewResultsSortBy'
    import SearchViewResultCard from '../search/ResultsCourseCard'
    import Paginator from '../Paginator'

    export default {
        name: "SearchViewResults",
        components: {
            Switches,
            SearchViewResultsFilterChips,
            SearchViewResultsSortBy,
            SearchViewResultCard,
            Paginator
        },
        props: {
            resultsprop: {
                type: Array,
                default: []
            },
            current_page: null,
            next_page_url: null,
            prev_page_url: null,
        },
        created() {
            var self = this;
        },
        mounted() {
            var self = this;

            this.results = this.resultsprop;
            this.dataSet = {
                current_page: self.current_page,
                next_page_url: self.next_page_url,
                prev_page_url: self.prev_page_url,
            };
            this.$bus.$on('filtered', function (courses) {
                self.results = [];
                if (courses)
                    self.results = courses;
            });
        },
        data() {
            return {
                dataSet: false,
                filters: {},
                freeTrial: true,
                results: []
            }
        },
        methods: {
            resultsLength() {
                if (this.results.length)
                    return this.results.length;
                else {
                    return _.size(this.results)
                }

            },
            url(page) {
                if (!page) {
                    let query = location.search.match(/page=(\d+)/);

                    page = query ? query[1] : 1;
                }
                return (window.location.href + `&page=${page}`);
            },
            fetch: function (page = 1, params = {}) {
                let self = this;
                axios.get(this.url(page), {
                    params: params
                }).then((result) => {
                    self.dataSet = result.data;
                    let courses = result.data.data.map(course => {
                        self.$set(course, 'showNested', false);
                        return course;
                    });
                    self.results = courses;
                }).catch(() => {
                });
            },
            cancelFilter() {
                // todo
            },
            sortBy(item) {
                // todo
            }
        },
        watch: {
            freeTrial: {
                handler: function () {
                    this.$bus.$emit('free-trial');
                },
                deep: true,
            },
        },
    }
</script>
