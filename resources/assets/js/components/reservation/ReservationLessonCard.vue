<template>
  <div class="reservation__lesson__datetime" :class="{'reservation__lesson__datetime--active': active}" v-if="lesson" @click="select">
    <div class="reservation__lesson__datetime__date">
      <h5 class="reservation__lesson__datetime__date--date">{{lesson.date}}</h5>
      <p class="reservation__lesson__datetime__date--month">{{lesson.month}}</p>
    </div>
    <div class="reservation__lesson__datetime__time">
      {{lesson.short_day}} - {{ lesson.start_time ? 'dalle ' + lesson.start_time + ' alle ' + lesson.end_time : 'dalle ' + lesson.time }}
    </div>
  </div>
</template>

<script>
  export default {
    name: "ReservationLessonCard",
    props: {
      lesson: Object,
      active: Boolean,
      selectLesson: Function
    },
    methods: {
      select () {
        if (this.selectLesson) {
          this.selectLesson(this.lesson);
        }
      }
    }
  }
</script>
