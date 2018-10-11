<!--
Inspired by https://github.com/dhhb/vue-base64-file-upload
-->
<script>
    export default {
        name: 'files-upload',

        props: {
            imageClass: {
                type: String,
                default: ''
            },
            inputClass: {
                type: String,
                default: ''
            },
            accept: {
                type: String,
                default: 'image/png,image/gif,image/jpeg'
            },
            maxSize: {
                type: Number,
                default: 10 // megabytes
            },
            disablePreview: {
                type: Boolean,
                default: false
            },
            fileName: {
                type: String,
                default: ''
            },
            placeholder: {
                type: String,
                default: 'Click here to upload image'
            },
            filePaths: {
                type: Array,
                default: ''
            }
        },

        data() {
            return {
                files: [],
                previews: [],
                visiblePreview: false
            };
        },

        mounted () {
          if(this.filePaths.length > 0){
              this.previews = JSON.parse(JSON.stringify(this.filePaths)); // Hack to have non reactive between the variables
          }
        },

        computed: {
            wrapperStyles() {
                return {
                    'position': 'relative',
                    'width': '100%'
                };
            },

            fileInputStyles() {
                return {
                    'width': '100%',
                    'position': 'absolute',
                    'top': 0,
                    'left': 0,
                    'right': 0,
                    'bottom': 0,
                    'opacity': 0,
                    'overflow': 'hidden',
                    'outline': 'none',
                    'cursor': 'pointer'
                };
            },

            textInputStyles() {
                return {
                    'width': '100%',
                    'cursor': 'pointer'
                };
            },

            previewImages() {
                return this.previews || this.defaultPreview;
            }
        },

        methods: {
            removeImage (index){
                this.previews.splice(index, 1);
                this.$emit('remove-file-index', index);

            },
            onChange(e) {
                const files = e.target.files || e.dataTransfer.files;
                if (!files.length) {
                    return;
                }
                this.files = files;

                for(let index = 0; index < files.length; index++) {
                    const size = files[index].size && (files[index].size / Math.pow(1000, 2));

                    // check file max size
                    if (size > this.maxSize) {
                        this.$emit('size-exceeded', size);
                        return;
                    }

                    // update files
                    this.$emit('file', files[index]);

                    const reader = new FileReader();

                    reader.onload = e => {
                        const dataURI = e.target.result;

                        if (dataURI) {
                            this.previews.push(dataURI);
                        }
                    };

                    // read blob url from file data
                    reader.readAsDataURL(files[index]);
                }
            }
        }
    }
</script>