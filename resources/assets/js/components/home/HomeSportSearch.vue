<template>
  <b-input-group class="home-sport-search">
    <b-form-input v-model="search_string" class="search-world-input home-sport-search__input" :placeholder="placeholder"></b-form-input>
    <b-input-group-append>
      <b-button :href="getSearchUrl()" variant="secondary" class="search-world-button home-sport-search__btn--search">
        <i class="fa fa-search"></i>
      </b-button>
    </b-input-group-append>
  </b-input-group>
</template>

<script>
  export default {
      name: 'HomeSportSearch',
      props: {
          url: String,
          placeholder: String
      },
      data() {
          return {
              search_string: null,
              coords: []
          }
      },
      mounted() {
          this.getLocation();
          $(".search-world-input").keyup(function(event) {
              event.preventDefault();
              if (event.keyCode == 13) {
                  location.href = $(this).parent().find(".search-world-button").attr("href");
              }
          });
      },
      methods: {
          getSearchUrl() {
              if (this.coords.length != 0) {
                  return this.url +
                      '?search_string=' +
                      this.search_string +
                      '&latitude=' +
                      this.coords[0] +
                      '&longitude=' +
                      this.coords[1]
              } else {
                  return this.url +
                      '?search_string=' +
                      this.search_string +
                      '&latitude=' +
                      null +
                      '&longitude=' +
                      null
              }
          },
          getLocation() {
              if (navigator.geolocation) {
                  navigator.geolocation.getCurrentPosition(this.returnPosition);
              }
          },
          returnPosition(position) {
              this.coords.push(position.coords.latitude);
              this.coords.push(position.coords.longitude);

              this.$bus.$emit('geolocalizated', {
                  latitude: position.coords.latitude,
                  longitude : position.coords.longitude,
              });
          },
      }
  }
</script>
