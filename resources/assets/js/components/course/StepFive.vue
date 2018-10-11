<script>
    import FileUpload from './FileUpload';
    import FilesUpload from './FilesUpload';

    export default {
        components: {
            'file-upload': FileUpload,
            'files-upload': FilesUpload
        },
        name: "StepFive",
        props: {
            'course': Object,
            'description': Object
        },
        data() {
            return {
                loading: false,
                imageMaxSize: 3, // megabytes,
                message: null,
                textValidationError: false
            }
        },
        methods: {
            isLoading() {
                return this.loading;
            },
            onFile: function (file) {
                this.description.photo = file;
                this.description.filePath = '';
            },
            onFileGallery: function (file) {
                this.description.gallery.push(file);
            },
            removeFileGallery: function (index) {
                this.description.gallery.splice(index, 1);
            },
            onSizeExceeded(size) {
                this.message = 'Immagine troppo grande, prova con una piu piccola';
            },
            previousStep() {
                this.$emit('update-step', 4);
            },
            postForm() {
                if (this.validation()) {
                    this.loading = true;
                    this.$bus.$emit('searching');

                    let formData = new FormData()
                    formData.append('text', this.description.text);
                    formData.append('photo', this.description.photo);

                    if (this.description.gallery.length > 0) {
                        this.description.gallery.forEach((item, index) => {
                            formData.append('gallery[' + index + ']', item);
                        });
                    }
                    axios({
                        method: 'post',
                        url: '/courses/' + this.course.courseId + '/description',
                        data: formData,
                    }).then((response) => {
                        this.loading = false;
                        this.$bus.$emit('filtered');
                        this.$emit('update-step', 6, 5);
                    }).catch((error) => {
                        this.loading = false;
                        this.$bus.$emit('filtered');
                        this.message = 'Error';
                    });
                }
            },
            validation() {
                this.textValidationError = false;
                if (!this.description.text) {
                    this.textValidationError = true;
                    return false;
                }
                return true;
            }

        }
    }
</script>
<style scoped>

</style>
