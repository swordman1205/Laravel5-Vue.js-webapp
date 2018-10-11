<template>
    <b-container fluid class="search-view">
        <b-col lg="12" sm="12" class="no-padding">
            <b-row>
                <b-col lg="3" sm="12" md="6">
                    <SearchViewFilter :change-view="changeView">
                    </SearchViewFilter>
                </b-col>
                <b-col lg="9" sm="12" md="6" class="no-padding">
                    <template>
                        <SearchViewResults
                                :resultsprop="results"
                                :current_page="current_page"
                                :next_page_url="next_page_url"
                                :prev_page_url="next_page_url"
                                v-show="selectedView == 'list'">
                        </SearchViewResults>
                        <SearchViewMap :resultsprop="results" v-show="selectedView == 'map'">
                        </SearchViewMap>
                    </template>
                </b-col>
            </b-row>
        </b-col>
    </b-container>
</template>

<script>
    import SearchViewFilter from './SearchViewFilter'
    import SearchViewResults from './SearchViewResults'
    import SearchViewMap from './SearchViewMap'

    export default {
        name: "SearchView",
        components: {
            SearchViewFilter,
            SearchViewResults,
            SearchViewMap
        },
        props: {
            current_page: null,
            next_page_url: null,
            prev_page_url: null,
            results: {
                type: Array,
                default: []
            }
        },
        data() {
            return {
                selectedView: 'list'
            }
        },
        methods: {
            changeView(view) {
                this.selectedView = view;
            }
        }
    }
</script>
