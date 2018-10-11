<script>
    import FileUpload from '../course/FileUpload';
    import FilesUpload from '../course/FilesUpload';

    import * as VueGoogleMaps from 'vue2-google-maps';


    export default {
        name: "Profile",
        components: {
            'vue-google-autocomplete': VueGoogleMaps.Autocomplete,
            'file-upload': FileUpload,
            'files-upload': FilesUpload
        },
        props: {
            'companyId': String,
            user: {},
        },
        data() {
            return {
                message: null,
                legalForms: [],
                typologies: [],
                federations: [],
                types: ['geocode'],
                imageMaxSize: 3, // megabyte
                company: {
                    name: null,
                    publicName: null,
                    legalFormId: null,
                    typologyId: null,
                    email: null,
                    phoneNumber: null,
                    fiscalCode: null,
                    oldAddress: null,
                    address: null,
                    addressComponents: null,
                    latitude: null,
                    longitude: null,
                    promotionAgency: null,
                    logo: null,
                    logoPath: '',
                    description: null,
                    vatNumber: null,
                    socialShare: null,
                    federationId: null,
                    statute: {
                        text: null,
                        file: null,
                        filePath: ''
                    },
                    privacyPolicy: {
                        text: null,
                        file: null,
                        filePath: ''
                    }
                },
                validationErrors: {
                    name: false,
                    legalForm: false,
                    typology: false,
                    email: false,
                    phoneNumber: false,
                    fiscalCode: false,
                    address: false
                }
            }
        },
        computed: {
            statuteFileName() {
                if (this.company.statute.file) {
                    this.company.statute.text = this.company.statute.file.name;
                    return this.company.statute.file.name;
                }
                else if (this.company.statute.filePath) {
                    this.company.statute.text = this.company.statute.filePath;
                    return this.company.statute.filePath;
                }
                return null;
            },
            privacyPolicyFileName() {
                if (this.company.privacyPolicy.file !== null) {
                    this.company.privacyPolicy.text = this.company.privacyPolicy.file.name;
                    return this.company.privacyPolicy.file.name;
                }

                else if (this.company.privacyPolicy.filePath) {
                    this.company.privacyPolicy.text = this.company.privacyPolicy.filePath;
                    return this.company.privacyPolicy.filePath;
                }
                return null;
            },
        },
        mounted() {
            this.message = null;
            this.initCompany();
            this.getFederations();
            this.getTypologies();
            this.getLegalForms();
        },
        watch: {
            'company': {
                handler: function (val, oldVal) {
                    this.message = null;
                },
                deep: true
            },
            'company.name'() {
                this.validationErrors.name = false;
            },
            'company.legalFormId'() {
                this.validationErrors.legalForm = false;
            },
            'company.typologyId'() {
                this.validationErrors.typology = false;
            },
            'company.email'() {
                this.validationErrors.email = false;
            },
            'company.phoneNumber'() {
                this.validationErrors.phoneNumber = false;
            },
            'company.fiscalCode'() {
                this.validationErrors.fiscalCode = false;
            },
            'company.addressComponents'() {
                this.validationErrors.address = false;
            },
        },
        methods: {
            initCompany() {
                axios({
                    method: 'get',
                    url: '/companies/' + this.companyId
                }).then((response) => {
                    this.company = {
                        name: response.data.company.name,
                        publicName: response.data.company.public_name,
                        legalFormId: response.data.company.legal_form_id,
                        typologyId: response.data.company.typology_id,
                        email: response.data.company.email ? response.data.company.email : this.user.email,
                        phoneNumber: response.data.company.phone_number,
                        fiscalCode: response.data.company.fiscal_code,
                        address: response.data.company.address,
                        oldAddress: response.data.company.address,
                        addressComponents: null,
                        latitude: response.data.company.latitude,
                        longitude: response.data.company.longitude,
                        promotionAgency: response.data.company.promotion_agency,
                        logo: null,
                        logoPath: response.data.company.logo_path !== null ? response.data.company.logo_path : '',
                        description: response.data.company.description,
                        vatNumber: response.data.company.vat_number,
                        socialShare: response.data.company.social_share,
                        federationId: response.data.company.federation_id,
                        statute: {
                            text: response.data.company.statute,
                            file: null,
                            filePath: response.data.company.statute_path !== null ? response.data.company.statute_path : ''
                        },
                        privacyPolicy: {
                            text: response.data.company.privacy_policy,
                            file: null,
                            filePath: response.data.company.privacy_policy_path !== null ? response.data.company.privacy_policy_path : ''
                        },
                        gallery: response.data.company.gallery_images.map((image) => {
                            return image.file_path;
                        })
                    }

                }).catch((error) => {
                    this.$emit('init-error', 'Could not load company');
                });
            }
            ,
            getFederations() {
                axios({
                    method: 'get',
                    url: '/federations'
                }).then((response) => {
                    this.federations = response.data.federations;

                }).catch((error) => {
                    this.$emit('init-error', 'Could not load federations');
                });
            }
            ,
            getTypologies() {
                axios({
                    method: 'get',
                    url: '/typologies'
                }).then((response) => {
                    this.typologies = response.data.typologies;

                }).catch((error) => {
                    this.$emit('init-error', 'Could not load typologies');
                });
            }
            ,
            getLegalForms() {
                axios({
                    method: 'get',
                    url: '/legal-forms'
                }).then((response) => {
                    this.legalForms = response.data.legalForms;

                }).catch((error) => {
                    this.$emit('init-error', 'Could not load legal forms');
                });
            }
            ,
            postForm() {
                this.message = null;

                if (this.validation()) {
                    let formData = new FormData()
                    formData.append('logo', this.company.logo);
                    formData.append('statute-file', this.company.statute.file);
                    formData.append('privacy-policy-file', this.company.privacyPolicy.file);
                    formData.append('logo', this.company.logo);
                    for (let key in this.company) {
                        if (key !== 'addressComponents' && this.company[key] !== null) {
                            formData.append(key, this.company[key]);
                        }
                    }
                    for (let key in this.company.addressComponents) {
                        formData.append('addressComponents[' + key + ']', this.company.addressComponents[key]);
                        for (let key2 in this.company.addressComponents[key]) {
                            formData.append('addressComponents[' + key + '][' + key2 + ']', this.company.addressComponents[key][key2]);
                        }
                    }


                    formData.append('_method', 'PUT');

                    if (this.company.gallery.length > 0) {
                        this.company.gallery.forEach((item, index) => {
                            formData.append('gallery[' + index + ']', item);
                        });
                    }
                    axios({
                        method: 'POST',
                        url: '/companies/' + this.companyId,
                        data: formData,
                    }).then((response) => {
                        this.$emit('init-success', 'Profilo salvato correttamente');
                    }).catch((error) => {
                        this.message = 'Error';
                        if (error.response.status === 422) {
                            this.validationErrors = error.response.data.errors;
                            this.$emit('init-error', 'Impossibile validare il form');
                        }
                        else {
                            this.$emit('init-error', 'Internal server error');
                        }
                    });
                }
            }
            ,
            validation() {
                let passed = true;
                this.validationErrors = {
                    name: false,
                    legalForm: false,
                    typology: false,
                    email: false,
                    phoneNumber: false,
                    fiscalCode: false,
                    address: false
                };

                if (!this.company.name) {
                    this.validationErrors.name = true;
                    passed = false;
                }

                if (!this.company.legalFormId) {
                    this.validationErrors.legalForm = true;
                    passed = false;
                }

                if (!this.company.typologyId) {
                    this.validationErrors.typology = true;
                    passed = false;
                }
                
                if (!this.company.email) {
                    this.validationErrors.email = true;
                    passed = false;
                }

                if (!this.company.phoneNumber) {
                    this.validationErrors.phoneNumber = true;
                    passed = false;
                }

                if (!this.company.fiscalCode) {
                    this.validationErrors.fiscalCode = true;
                    passed = false;
                }

                if (!this.company.address) {
                    this.validationErrors.address = true;
                    passed = false;
                }

                if (this.company.address !== this.company.oldAddress && !this.company.addressComponents) {
                    this.validationErrors.address = true;
                    passed = false;
                }

                if (!passed) {
                    window.scrollTo(0, 200);
                }

                return passed;
            }
            ,
            getAddressData(payload) {
                this.company.address = payload.formatted_address;
                this.company.addressComponents = payload.address_components;
                this.company.latitude = payload.geometry.location.lat();
                this.company.longitude = payload.geometry.location.lng();
            }
            ,
            onFile: function (file) {
                this.company.logo = file;
                this.company.logoPath = '';
            }
            ,
            onSizeExceeded(size) {
                // this.message = 'Image ${size}Mb size exceeds limits of ${this.imageMaxSize}Mb!';
            }
            ,
            onFileGallery: function (file) {
                this.company.gallery.push(file);
            }
            ,
            removeFileGallery: function (index) {
                this.company.gallery.splice(index, 1);
            }
            ,
            onStatuteFileChange(e) {
                const files = e.target.files || e.dataTransfer.files;

                if (!files.length) {
                    return;
                }

                this.company.statute.file = files[0];
            }
            ,
            onPrivacyPolicyFileChange(e) {
                const files = e.target.files || e.dataTransfer.files;

                if (!files.length) {
                    return;
                }

                this.company.privacyPolicy.file = files[0];
            }
        }
    }
</script>

<style scoped>

</style>