<template>
    <b-modal ref="successModal" hide-footer hide-header no-close-on-backdrop>
        <b-container>
            <b-row class="justify-content-md-center">
                <b-col md="auto"><i class="fas fa-check-circle mb-3"
                                    style="font-size: 32pt; color: lawngreen;"></i>
                </b-col>
            </b-row>
            <b-row class="justify-content-md-center">
                <b-col class="text-center">
                    <h4 class="text-uppercase font-weight-bold">Prenotazione effettuata con successo</h4>
                    <p>Riceverai una email di conferma</p>
                    <hr>
                </b-col>
            </b-row>
            <b-row class="justify-content-md-center mb-4">
                <b-col md="auto">
                    <b-button style="color:white; background-color:#3b5998; border:none; width:60px;" class="mr-2 sm-2"
                              cols="2"><i class="fab fa-facebook-f"></i></b-button>
                    <b-button style="color:white; background-color:#0084b4; border:none; width:60px;" class="mr-2 sm-2"
                              cols="2"><i class="fab fa-twitter"></i></b-button>
                    <b-button style="color:white; background-color:#dd4b39; border:none; width:60px;" class="mr-2 sm-2"
                              cols="2"><i class="fab fa-google-plus-g"></i></b-button>
                    <b-button style="color:white; background-color:#0077B5; border:none; width:60px;" class="mr-2 sm-2"
                              cols="2"><i class="fab fa-linkedin-in"></i></b-button>
                    <b-button style="color:white; background-color:#25d366; border:none; width:60px;" class="mr-2 sm-2"
                              cols="2"><i class="fab fa-whatsapp"></i></b-button>
                    <b-button style="color:white; background-color:#ffbb00; border:none; width:60px;" class="mr-2 sm-2"
                              cols="2"><i class="far fa-envelope"></i></b-button>
                </b-col>
            </b-row>
            <b-row class="justify-content-md-center">
                <b-col md="auto">
                    <b-button :href="courseHref()"
                              class="text-uppercase btn orange-gradient-button text-capitalize btn-default btn-block pl-4 pr-4">
                        Torna alla pagina del corso
                    </b-button>
                </b-col>
            </b-row>
        </b-container>
    </b-modal>
</template>

<script>
    export default {
        name: "ReservationSuccess",
        props: {
            'reservation': {},
            'course': {}
        },
        mounted() {
            this.$refs.successModal.show();
        },
        methods: {
            courseHref() {
                return `/corsi/${this.course.slug}`
            }
        }

    }
</script>