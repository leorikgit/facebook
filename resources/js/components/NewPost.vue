<template>
    <div class="bg-white rounded shadow w-2/3 p-4 ">
        <div class="flex justify-between items-center">
           <div class="">
               <div class="w-8">
                   <img :src="authUser.data.attributes.profile_image.data.attributes.path" class="h-8 w-9 object-cover rounded-full">
               </div>
           </div>
           <div class="flex-1 flex px-4">
               <input v-model="postMessage" type="text" placeholder="Make a post" class="h-8  bg-gray-200 rounded-full pl-4 focus:outline-none text-sm w-full">

            <transition name="fade">
                <button v-if='postMessage' class="px-3 bg-gray-200 rounded-lg py-1 ml-2 py-1" @click="postHandler">Post</button>

            </transition>
           </div>
           <div>
               <button ref="postImage" class="dz-clickable w-12 h-12 flex item-center justify-center rounded-full bg-gray-200 flex focus:outline-none">
                   <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class=" dz-clickable fill-current w-5 h-5"><path d="M21.8 4H2.2c-.2 0-.3.2-.3.3v15.3c0 .3.1.4.3.4h19.6c.2 0 .3-.1.3-.3V4.3c0-.1-.1-.3-.3-.3zm-1.6 13.4l-4.4-4.6c0-.1-.1-.1-.2 0l-3.1 2.7-3.9-4.8h-.1s-.1 0-.1.1L3.8 17V6h16.4v11.4zm-4.9-6.8c.9 0 1.6-.7 1.6-1.6 0-.9-.7-1.6-1.6-1.6-.9 0-1.6.7-1.6 1.6.1.9.8 1.6 1.6 1.6z"/></svg>
               </button>
           </div>

        </div>
        <div class="dropzone-previews">
            <div id="dz-template" class="hidden">
                <div class="dz-preview dz-file-preview p-4">
                    <div class="dz-details">
                        <img data-dz-thumbnail class="w-32 h-32"/>
                        <div class="dz-filename"><span data-dz-name></span></div>
                        <div class="dz-size" data-dz-size></div>
                    </div>
                    <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>
                    <button data-dz-remove class="">Remove</button>

                </div>
            </div>
        </div>

    </div>

</template>

<script>
    import _ from 'lodash';
    import { mapGetters } from 'vuex';
    import Dropzone from 'dropzone';
    export default {

        name: "NewPost",
        data: () => {
            return {
                dropzone: null,
            }
        },
        mounted() {
            this.dropzone = new Dropzone(this.$refs.postImage, this.settings)
        },
        computed: {
        ...mapGetters({
            authUser: 'authUser'
        }),
            settings() {
                return {
                    paramName: 'image',
                    url: '/api/posts',
                    acceptedFiles: 'image/*',
                    params: {
                        'width': 1000,
                        'height': 1000,
                    },
                    previewsContainer: '.dropzone-previews',
                    previewTemplate: document.querySelector('#dz-template').innerHTML,
                    success: (e, res) =>{
                        this.dropzone.removeAllFiles()
                        this.$store.commit('pushNewPost', res)
                    },
                    clickable: '.dz-clickable',
                    autoProcessQueue: false,
                    headers: {
                        'X-CSRF-TOKEN': document.head.querySelector('meta[name=csrf-token]').content
                    },
                    sending:(file, xhr, formData)=>{
                        formData.append('body', this.$store.getters.postMessage);
                    }
                };
            },
            postMessage:{
                get(){
                    return this.$store.getters.postMessage;
                },
                set: _.debounce(function(postMessage){
                    this.$store.commit('setPostMessage', postMessage);
                }, 300),

            }
        },
        methods: {
            postHandler(){
                if(this.dropzone.getAcceptedFiles().length){
                    this.dropzone.processQueue()
                }else{
                    this.$store.dispatch('postMessage')
                }

                this.$store.commit('setPostMessage', '')
            }
        }


    }
</script>

<style scoped>
    .fade-enter-active, .fade-leave-active{
        transition: opacity .5s;
    }
    .fade-enter, .fade-leave-to{
        opacity: 0;
    }
</style>
