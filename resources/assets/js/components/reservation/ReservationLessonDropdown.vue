<template>
  <div class="reservation__lesson__dropdown">
    <div class="reservation__lesson__dropdown__selected" v-click-outside="clickOutside" @click="toggleList">
      <ReservationLessonCard
        :lesson="selected || selectedLesson">
      </ReservationLessonCard>
      <i class="fa fa-angle-down" v-if="!selectedLesson || !selectedLesson.specific"></i>
    </div>
    <ul class="list-unstyled reservation__lesson__dropdown__list" v-if="show">
      <li v-for="(lesson, index) in lessons" :key="index">
        <ReservationLessonCard
          :lesson="lesson"
          :select-lesson="select">
        </ReservationLessonCard>
      </li>
    </ul>
  </div>
</template>

<script>
  import ReservationLessonCard from './ReservationLessonCard'
  import ClickOutside from '../../directives/ClickOutside'

  export default {
    name: "ReservationLessonDropdown",
    props: {
      lessons: {
        type: Array,
        required: true
      },
      selectedLesson: Object,
      selectLesson: Function
    },
    data () {
      return {
        show: false,
        selected: null
      }
    },
    components: {
      ReservationLessonCard
    },
    methods: {
      select (lesson) {
        this.toggleList();
        this.selected = lesson;
        this.selectLesson(lesson);
      },
      toggleList () {
        if (this.selectedLesson.specific) return;
        this.show = !this.show;
      },
      clickOutside () {
        this.show = false;
      }
    },
    directives: {
      ClickOutside
    }
  }
</script>
