<template>
    <div class="keyword-batches-wrapper my-5">
        <h1 class="text-center text-2xl  text-80 font-dark pt-4 mt-2 mb-2">Last 10 tasks</h1> 

        <div v-if="loading" class="rounded-lg flex items-center justify-center relative">
            <loader class="text-60" />
        </div>

        <div v-else class="mx-auto shadow-md pt-6 pb-6">   
            <div class="w-90p">
                
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
                
                <div v-else > 
                    <table class="w-full text-md shadow-md rounded mb-4 table-striped table-bordered">
                        <thead class="bg-black ">
                            <tr class="border-b">
                                <th class="text-left p-3 px-5">Keywords to create</th>
                                <th class="text-left p-3 px-5">Feed</th>
                                <th class="text-left p-3 px-5">Market</th>
                                <th class="text-left p-3 px-5">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b hover:bg-orange-100 bg-white" v-for="(batch, key) in batches" :key="key"> 
                                <td class="p-3 px-5"> {{ batch.keyword }} </td>
                                <td class="p-3 px-5"> {{ batch.feed }} </td>
                                 <td class="p-3 px-5">{{ batch.market }}</td>
                                <td class="p-3 px-5 flex justify-center">
                                    <button type="button"  class="mr-3 text-sm text-white py-2 px-2 rounded focus:outline-none focus:shadow-outline" 
                                     :class="batchStatusExtraClass(batch.status)">
                                       {{ batch.status }} 
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
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
        },
        batchStatusExtraClass (status) {
            if (status === 'pending') {
                return 'bg-grey-500 hover:bg-grey-700'
            } 
            else if (status == 'processing') {
               return 'bg-orange-500 hover:bg-orange-700';
            }
            else {
               return 'bg-green-500 hover:bg-green-700'
            }
        }
    }
}
</script>
<style scoped>
    .header-box div, .content-box div {
        width: 25%;
    }
    .bg-orange-600 { background-color: #dd6b20; }
</style>