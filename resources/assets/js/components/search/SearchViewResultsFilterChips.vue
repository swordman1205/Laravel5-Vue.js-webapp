<template>
  <div class="search-view-results-filter-chips">
    <div class="search-view-results-filter-chips__card" v-for="(filter, index) in filters" :key="index">
      <h6 class="search-view-results-filter-chips__card__text">{{filter.display}}</h6>
      <a class="search-view-results-filter-chips__card__btn--remove" href="javascript:void(0)" @click="cancelFilter(filter)"><i class="fa fa-times"></i></a>
    </div>
  </div>
</template>

<script>
  export default {
    name: "SearchViewResultsFilterChips",
    props: {
      filters: Array,
      cancelFilter: Function
    }
  }
</script>
