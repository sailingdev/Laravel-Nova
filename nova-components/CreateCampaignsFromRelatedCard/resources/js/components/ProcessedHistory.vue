<template>
    <div class="keyword-batches-wrapper my-5">
        <h1 class="text-left text-xl text-80 font-dark pt-4 pl-7">Last 10 tasks</h1> 

        <div v-if="loading" class="rounded-lg flex items-center justify-center relative">
            <loader class="text-60" />
        </div>

        <div v-else class="w-full mx-auto p-8">   
            <div class="shadow-md">
                
                <div v-if="Object.entries(errorResponse).length > 0">
                    <div class="mt-4 mb-4 px-4 py-3 leading-normal text-red-100 bg-red-700 rounded-lg" role="alert">
                        <h4 class="mt-2 mb-2"> {{ errorResponse.message }} </h4>
                        <p v-for="(error, index) in errorResponse.errors" :key="index" class="text-sm"> 
                            => {{ error[0] }} 
                        </p>
                    </div>
                </div>

                <div v-if="batches.length < 1"> 
                    <div class="px-4 py-3 leading-normal text-gray-100 bg-gray-700 rounded-lg text-center" role="alert">
                        <p> No record </p>
                    </div> 
                </div>
                
                <div v-else>
                     <div class="header-box flex">
                        <div class>Batch ID</div>
                        <div>Date</div>
                        <div>Keywords to create</div> 
                        <div> Status </div>
                    </div>
                    <div>
                        <div class="content-box flex w-full" v-for="(batch, key) in batches" :key="key">
                            <div>{{ batch.batch_id }}</div>
                            <div>{{ batch.date }}</div>
                            <div>{{ batch.to_create }}</div>
                            <div> {{ batch.status }} </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: "ProcessedHistory",
    data () {
        return {
            loading: false,
            batches: [],
            errorResponse: {}
        }
    },
    props: {
        card: {
            required: true
        }
    },
    mounted () {
        this.processHistory()
    },
    methods: {
        processHistory () {
            this.loading = true
            axios.get('/nova-vendor/' + this.card.component + '/processed-batch-history')
            .then(response => {
                this.batches = response.data.data
            }).catch(error => {
                this.errorResponse = error.response.data
            })
            .finally(() => {
                this.loading = false
            })
        }
    }
}
</script>
<style scoped>
    .header-box div, .content-box div {
        width: 25%;
    }
</style>