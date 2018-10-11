<template>
  <h2>Verifying your email...</h2>
</template>

<script>
  export default {
    name: 'UserVerify',
    props: {
      error: String
    },
    mounted () {
      if (this.error) {
        window.alerts.error(this.error);
      } else {
        window.alerts.success('Your email has been verified!');
      }
      setTimeout(() => {
        window.location.href = '/';
      }, 3000);
    }
  }
</script>
