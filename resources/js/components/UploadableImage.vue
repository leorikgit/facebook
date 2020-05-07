<template>
    <div>
        <img class="object-cover w-full"
             :src="imageObject.data.attributes.path"
             :class="classes"
             :alt="alt"
             ref="userImage">
    </div>
</template>

<script>
    import Dropzone from 'dropzone'
    export default {
        name: "UploadableImage",
        data:  () =>{
            return{
                dropzone:null,
                uploadedFile: null
            }
        },
        mounted(){
            this.dropzone = new Dropzone(this.$refs.userImage, this.settings);
        },
        computed: {
            settings(){
                return {
                    params:{
                        'width': this.imageWidth,
                        'height': this.imageHeight,
                        'location' : this.location
                    },
                    paramName: 'image',
                    url: '/api/user-images',
                    acceptedFiles: 'image/*',
                    headers: {
                        'X-CSRF-TOKEN': document.head.querySelector('meta[name=csrf-token]').content
                    },
                    success: (e,res) => {
                        this.uploadedFile = res
                    }
                }
            },
            imageObject(){
                return this.uploadedFile || this.userImage;
            }
        },
        props:[
            'imageWidth',
            'imageHeight',
            'location',
            'userImage',
            'classes',
            'alt'

        ]


    }
</script>

<style scoped>

</style>
