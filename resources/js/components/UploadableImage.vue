<template>
    <div>
        <img class="object-cover w-full"
             :src="userImage.data.attributes.path"
             :class="classes"
             :alt="alt"
             ref="userImage">
    </div>
</template>

<script>
    import { mapGetters } from 'vuex';
    import Dropzone from 'dropzone'
    export default {
        name: "UploadableImage",
        data:  () =>{
            return{
                dropzone:null,

            }
        },
        mounted(){
            if(this.authUser.data.user_id.toString() == this.$route.params.userId){
                this.dropzone = new Dropzone(this.$refs.userImage, this.settings);
            }

        },
        computed: {
            ...mapGetters({
                authUser: 'authUser'
            }),
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
                        this.$store.dispatch('fetchAuthUser');
                        this.$store.dispatch('fetchUser', this.$route.params.userId)
                        this.$store.dispatch('fetchPost', this.$route.params.userId)
                    }
                }
            },

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
