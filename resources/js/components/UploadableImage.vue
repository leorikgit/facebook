<template>
    <div>
        <img class="object-cover"
             src="https://image.shutterstock.com/z/stock-photo-colorful-hot-air-balloons-flying-over-mountain-at-dot-inthanon-in-chiang-mai-thailand-1033306540.jpg"
             alt="User bacground image"
            ref="userImage">
    </div>
</template>

<script>
    import Dropzone from 'dropzone'
    export default {
        name: "UploadableImage",
        datA:  () =>{
            return{
                dropzone:null
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
                        alert('uploaded!');
                    }
                }
            }
        },
        props:[
            'imageWidth',
            'imageHeight',
            'location'

        ]


    }
</script>

<style scoped>

</style>
