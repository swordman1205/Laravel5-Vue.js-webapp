<template>
  <div class="search-view-results-sort-by">
    <b-dropdown right variant="link">
      <template slot="button-content">
        <i class="fa fa-sort-amount-down"></i>
        ORDINA PER:
      </template>
      <b-dropdown-item v-for="(item, index) in items"
                       :key="index"
                       @click="sortBy(item)">
        {{item.label}}
        <i class="fa fa-check" v-if="selectedItem === item"></i>
      </b-dropdown-item>
    </b-dropdown>
  </div>
</template>

<script>
  export default {
    name: "SearchViewResultsSortBy",
    props: {
      doSort: {
        type: Function,
        required: true
      }
    },
    data () {
      return {
        items: [
          { label: 'Prezzo PIù BASSO', value: 'price_lowest' },
          { label: 'Prezzo PIù ALTO', value: 'price_highest' },
          { label: 'Ora', value: 'now' },
          { label: 'Distanza', value: 'distance' },
          { label: 'Valutazione', value: 'rating' }
        ],
        selectedItem: null
      }
    },
    methods: {
      sortBy (item) {
        this.selectedItem = item
        if (this.doSort) {
          this.doSort(item.value)
        }
      }
    }
  }
</script>
