<template>
  <b-button class="btn--google" variant="default" :href="redirectUrl">
    <div class="btn--google__content">
      <b-img src="/images/google.svg" />
      Google
    </div>
  </b-button>
</template>

<script>
  import {mapGetters} from 'vuex';

  export default {
    name: 'GoogleButton',
    props: {},
    computed: {
      ...mapGetters({
        redirectUrl: 'googleLogin'
      })
    }
  }
</script>
