<template>
    <div>
        <div class="w-100 h-64 overflow-y-hidden">
            <img class="object-cover" src="https://image.shutterstock.com/z/stock-photo-colorful-hot-air-balloons-flying-over-mountain-at-dot-inthanon-in-chiang-mai-thailand-1033306540.jpg" alt="User bacground image">

        </div>
    </div>
</template>

<script>
    export default {
        name: "Show",
        mounted() {
            axios.get('/api/users/' +  this.$route.params.userId)
            .then(res=>{
                this.user = res.data;
            })
            .catch(error=>{
                console.log('unable to load user');
            })
            .finally(()=>{
                this.loading = false;
            });
        },
        data: () => {
            return {
                user: null,
                loading: true
            }
        }

    }
</script>

<style scoped>

</style>
