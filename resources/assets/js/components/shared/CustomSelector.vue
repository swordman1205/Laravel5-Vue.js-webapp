<template>
  <div class="orangogo-custom-selector" :class="innerClass" v-click-outside="closeList">
    <div class="orangogo-custom-selector__input" :class="{'orangogo-custom-selector__input--empty': selectedList.length === 0}" @click="toggle">
      {{displaySelected || placeholder}}
      <i class="fa" :class="'fa-' + icon + '-' + (opened ? 'up' : 'down')"></i>
    </div>
    <ul class="orangogo-custom-selector__list" v-if="opened">
      <li class="orangogo-custom-selector__list__item" :class="{'orangogo-custom-selector__list__item--selected': isOptionSelected(option)}" v-for="(option, index) in options" :key="index" @click="selectOption(option)">
        {{getOptionDisplay(option)}}
        <i class="fa fa-check" v-if="isOptionSelected(option)"></i>
      </li>
    </ul>
  </div>
</template>

<script>
  import ClickOutside from '../../directives/ClickOutside'

  export default {
    name: "CustomSelector",
    props: {
      selected: {
        required: true
      },
      options: {
        type: Array,
        required: true
      },
      placeholder: String,
      valueField: {
        type: String,
        default: 'id'
      },
      displayField: {
        type: String,
        default: 'name'
      },
      isMultiple: {
        type: Boolean,
        default: false
      },
      icon: {
        type: String,
        default: 'caret',
      },
      innerClass: String,
      onChange: Function
    },
    directives: {
      ClickOutside
    },
    data () {
      return {
        valueSelected: null,
        opened: false
      }
    },
    computed: {
      displaySelected () {
        const displays = []
        for (const value of this.selectedList) {
          const display = this.options.find(option => value === (typeof option === 'object' ? option[this.valueField] : option))
          if (display) {
            displays.push(typeof display === 'object' ? display[this.displayField] : display)
          }
        }
        return displays.join(', ')
      },
      selectedList () {
        let selectedArr
        if (!this.isMultiple) {
          selectedArr = this.valueSelected ? [this.valueSelected] : []
        } else {
          selectedArr = this.valueSelected || []
        }
        return selectedArr
      }
    },
    created () {
      this.valueSelected = this.selected
    },
    methods: {
      isOptionSelected (option) {
        const value = typeof option === 'object' ? option[this.valueField] : option
        return this.selectedList.indexOf(value) > -1
      },
      getOptionDisplay (option) {
        return typeof option === 'object' ? option[this.displayField] : option
      },
      selectOption (option) {
        const value = typeof option === 'object' ? option[this.valueField] : option
        if (this.isMultiple) {
          if (!this.valueSelected) this.valueSelected = []
          const index = this.valueSelected.indexOf(value)
          if (index > -1) {
            this.valueSelected.splice(index, 1)
          } else {
            this.valueSelected.push(value)
          }
        } else {
          if (this.valueSelected === value) {
            this.valueSelected = null
          } else {
            this.valueSelected = value
          }
        }
        if (this.onChange) {
          this.onChange(this.valueSelected)
        }
      },
      toggle () {
        this.opened = !this.opened
      },
      closeList () {
        this.opened = false
      }
    },
      watch: {
          valueSelected: {
              handler: function(newValue) {
                  this.$bus.$emit('day_selected', this.valueSelected);
              },
              deep: true
          },
      },
  }
</script>
