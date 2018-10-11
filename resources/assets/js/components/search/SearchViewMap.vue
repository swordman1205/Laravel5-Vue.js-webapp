<template>
    <div class="search-view-map">
        <google-map ref="mapRef"
                    :center="{ lat: resultsprop.length > 0 ? resultsprop[0].latitude * 1 : 0, lng: resultsprop.length > 0 ? resultsprop[0].longitude * 1 : 0 }"
                    map-type-id="terrain"
                    :style="{width: '100%', height: '100%'}">
            <google-map-marker :key="index"
                               :ref="'marker-' + index"
                               v-for="(result, index) in results"
                               :position="getPosition(result)"
                               :clickable="true"
                               icon="/images/map-marker.svg"
                               @click="showCourseInfo(index)">
            </google-map-marker>
        </google-map>
    </div>
</template>

<script>
    import {gmapApi} from 'vue2-google-maps'
    import SearchResultCard from '../home/HomeCourseCard'

    export default {
        name: 'SearchViewMap',
        props: {
            resultsprop: {
                type: Array,
                default: []
            }
        },
        data() {
            return {
                results: null,
                height: null,
                map: null
            }
        },
        mounted() {
            let self = this;

            this.results = this.resultsprop;

            const mapWrapper = document.getElementsByClassName('search-view-map')[0];
            setTimeout(() => {
                this.height = mapWrapper.offsetHeight
            })
            this.map = this.$refs.mapRef;
            this.fitBounds();

            this.$bus.$on('filtered', function (courses) {
                self.results = courses;
                self.fitBounds();
            });
        },
        computed: {
            google: gmapApi
        },
        methods: {
            fitBounds() {
                let self = this;
                this.map.$mapPromise.then(() => {
                    let bounds = new self.google.maps.LatLngBounds();
                    self.results.forEach(function (result) {
                        bounds.extend(self.getPosition(result));
                    });
                    self.map.fitBounds(bounds);
                })
            },
            getPosition(result) {
                return {lat: result.latitude * 1, lng: result.longitude * 1}
            },
            showCourseInfo(index) {
                const CardComponent = Vue.extend(SearchResultCard)
                const cardInstance = new CardComponent({
                    propsData: {
                        course: this.resultsprop[index],
                        insideMap: true
                    }
                })
                cardInstance.$mount()
                const infoWindow = new this.google.maps.InfoWindow({
                    content: `<div class="results-info-window">${cardInstance.$el.outerHTML}</div>`
                })
                const marker = this.$refs[`marker-${index}`][0].$markerObject
                infoWindow.open(this.map, marker)

                this.google.maps.event.addListener(infoWindow, 'domready', () => {
                    const iwElements = document.getElementsByClassName("gm-style-iw")
                    for (const el of iwElements) {
                        el.style.top = '20px';
                        let children = el.previousSibling.children
                        children[1].style.display = 'none'
                        children[3].style.display = 'none'

                        let closeBtn = el.nextSibling
                        closeBtn.style.marginRight = '35px'
                        closeBtn.style.marginTop = '16px'
                    }
                })
            }
        }
    }
</script>
