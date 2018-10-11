<template>
  <div class="search-view-filter-sports-template">
    {{ item.name }}
  </div>
</template>

<script>
  export default {
    name: "SearchViewFilterSportTemplate",
    props: {
      item: {
        type: Object,
        required: true
      }
    }
  }  
</script>
