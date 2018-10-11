<template>
  <div class="reservation__address">
    <i class="fa fa-map-marker-alt"></i>
    {{ address }}
  </div>
</template>

<script>
  export default {
    name: "ReservationAddress",
    props: {
      address: {
        type: String,
        required: true
      }
    }
  }
</script>
