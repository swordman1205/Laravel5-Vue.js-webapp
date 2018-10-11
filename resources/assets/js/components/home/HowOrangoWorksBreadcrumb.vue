<template>
  <div class="how-orango-works-breadcrumb">
    <div class="how-orango-works-breadcrumb__step" v-for="(step, index) in steps" :key="index">
      <i class="fa how-orango-works-breadcrumb__step__icon" :class="step.icon"></i>
      <div class="how-orango-works-breadcrumb__step__content">
        <h4 class="how-orango-works-breadcrumb__step__content__title">{{step.title}}</h4>
        <p class="how-orango-works-breadcrumb__step__content__description">{{step.description}}</p>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'HowOrangoWorksBreadcrumb',
    data () {
      return {
        steps: [
          {
            icon: 'fa-search',
            title: 'Trova',
            description: 'il tuo sport tra tutte le associazioni vicine a te'
          },
          {
            icon: 'fa-hand-point-up',
            title: 'Scegli',
            description: 'il corso giusto per orari, età e disabilità'
          },
          {
            icon: 'fa-check',
            title: 'Prenota',
            description: 'online la tua prova gratuita e buon divertimento!'
          }
        ]
      }
    }
  }
</script>
