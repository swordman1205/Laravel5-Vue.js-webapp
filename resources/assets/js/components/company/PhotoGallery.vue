<template>
  <div class="company-photo-gallery">
    <a :href="photo.file_path" class="fancybox-img company-photo-gallery__image" data-fancybox="fancybox-img" v-for="(photo, index) in photos" :key="index" :style="{width: getWidth(index), height: getHeight(), display: index>calcPhotos.length ? 'none':'block'}">
      <div class="mask" v-if="index == calcPhotos.length">+ {{photos.length - calcPhotos.length}} foto</div>
      <img :src="photo.file_path" :class="index == calcPhotos.length ? 'more' : ''">
    </a>
  </div>
</template>

<script>
  export default {
    name: "PhotoGallery",
    props: {
      photos: {
        type: Array,
        required: true
      }
    },
    data () {
      return {}
    },
    computed: {
      calcPhotos () {
        return this.photos.slice(0, 2)
      }
    },
    methods: {
      getWidth (index) {
        let width;
        if (this.photos.length > 2) {
          width = 40
        } else if (this.photos.length === 2) {
          width = 50
        } else {
          width = 100
        }
        if (index == this.calcPhotos.length) width /= 2;
        return width + "%";
      },
      getHeight () {
        let height;
        if (this.photos.length > 2) {
          height = '12vw'
        } else if (this.photos.length === 2) {
          height = '15vw'
        } else {
          height = '30vw'
        }
        return height
      }
    }
  }
</script>
