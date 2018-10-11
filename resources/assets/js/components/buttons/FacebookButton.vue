<template>
  <b-button class="btn--facebook" variant="default" :href="redirectUrl">
    <div class="btn--facebook__content">
      <b-img src="/images/facebook.svg" />
      Facebook
    </div>
  </b-button>
</template>

<script>
  import {mapGetters} from 'vuex';

  export default {
    name: 'FacebookButton',
    props: {},
    computed: {
      ...mapGetters({
        redirectUrl: 'facebookLogin'
      })
    }
  }
</script>
