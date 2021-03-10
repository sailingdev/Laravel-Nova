<template>
    <div class="batches-to-process mt-5 mb-5 w-full">
        <h1 class="text-left text-xl text-80 font-dark pt-4 pl-6">Batch Status</h1>
        <div v-if="loading" class="rounded-lg flex items-center justify-center relative">
            <loader class="text-60" />
        </div>
        
        <div v-else class="w-full mx-auto p-8">   
            <div class="shadow-md" v-if="displayForm">
                
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
                        <div>Keywords to skip</div>
                        <div>Typetag to Duplicate</div>
                    </div>
                    <div>
                        <div class="content-box flex w-full" v-for="(batch, key) in batches" :key="key">
                            <div>{{ batch.batch_id }}</div>
                            <div>{{ batch.date }}</div>
                            <div>{{ batch.to_create }}</div>
                            <div>{{ batch.skipped }}</div>
                            <div> <input class="form-control" type="text" v-model="batches[key]['type_tag']" /> </div>
                        </div>
                    </div>
                     <div class="px-4 py-3 bg-gray-20 text-left sm:px-6 mt-2">
                        <button :disabled="processing" @click="prepareForDispatch" class="inline-flex justify-center py-3 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <span v-if="processing"><loader class="text-60" :fillColor="'#ffffff'" /></span>
                            <span v-else>Create Campaigns</span>
                        </button> 
                        <span class="ml-2 text-red-600 text-sm"> {{validateError}} </span>
                    </div>
                </div>

            </div>

            <div v-if="displaySubmitSuccess">
                <div class="mt-1 px-10 py-5 pb-6 border-2 border-gray-300  border-dashed rounded-md notify-submit-success">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1em" height="1em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path d="M9 19.414l-6.707-6.707l1.414-1.414L9 16.586L20.293 5.293l1.414 1.414" fill="#3da35a"/></svg>
                    <h4 class="text-2xl text-center text-3xl text-80 font-dark px-4 py-4"> Thank you! </h4>
                    <p class="mt-2 mb-2 text-center"> Batch processing in progress. Please check back in few minutes time</p>
                </div> 
            </div>
        </div>
    </div>
</template>
<script>
import DynamicTable from '../../../../../nova/resources/js/components/RevenueDriver/DynamicTable'
export default {
    name: "BatchesToProcess",
    data () {
        return {
            loading: false,
            processing: false,
            batches: [],
            errorResponse: {},
            validateError: null,
            displayForm: true,
            displaySubmitSuccess: false
        }
    },
    components: {
        DynamicTable
    },
    props: {
        card: { 
            required: true
        },
    },
    mounted () {
        this.loadBatchesToProcess()
    },
    methods: {
        loadBatchesToProcess () {
            this.loading = true
            axios.get('/nova-vendor/' + this.card.component + '/load-batches-to-process')
            .then(response => {
                const data = response.data.data
                data.forEach((record, index) => {
                    record.type_tag = ''
                })
                this.batches = data
            }).catch(error => {
                this.errorResponse = error.response.data
            })
            .finally(() => {
                this.loading = false
            })
        },
        prepareForDispatch () {
            this.validateError = null
            let valErrCount = 0;
            this.batches.forEach((record, index) => {
                if (record.type_tag == '') {
                    valErrCount++
                }
            })
            if (valErrCount > 0) {
                this.validateError = 'Please enter a type tag for each of the batches'
                return false
            } 
            return this.createCampaigns(this.preparePayload()) 
        },
        preparePayload () {
            return this.batches.map(record => {
                return {
                    batch_id: record.batch_id,
                    type_tag: record.type_tag 
                }
            })
        },
        createCampaigns (payload) {
            this.processing = true
            axios.post('/nova-vendor/' + this.card.component + '/create-campaign', {
                data: JSON.stringify(payload)
            })
            .then(response => {
                const data = response.data.data
                this.displayForm = false
                this.displaySubmitSuccess = true
            }).catch(error => {
                this.errorResponse = error.response.data
            })
            .finally(() => {
                this.processing = false
            })
        }
    },
    computed: { 
          values() {
            let values = []
            if (this.batches.length > 0) {
                this.batches.forEach((record, index) => {
                    const row = {
                        "Batch ID": record.batch_id,
                        "Date": record.date,
                        'Keywords To Create': record.to_create,
                        'Keywords Skipped': record.skipped,
                        'Type Tag To Duplicate': '<input type="text" class="form-control">'
                    };
                    values.push(row)
                }); 
            }
            return values
        }
    }
}
</script>
<style> 
    table.vue-table thead > tr > th {
        word-spacing: 100vw;
        white-space: nowrap !important;
        word-break: keep-all !important; 
    } 
    .header-box div, .content-box div {
        width: 20%;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;  
        text-align: center !important;
        justify-content: center !important; 
        height: auto !important;
         border: 1px solid #ddd !important;
         text-align: center !important; 
         color: #212529;
    }
    .header-box div { 
        background-color: #2d3748 !important;
        color: #fff !important;
        padding: 20px 0px;
        box-sizing: border-box;
        font-size: 13.5px !important; 
        font-weight: bolder;
    }
    .content-box div {
        padding: 15px 5px;
        box-sizing: border-box; 
        font-size: 15.5px !important;
    }
    
    .content-box:nth-of-type(odd) {
    background-color: rgba(0, 0, 0, 0.05);
    }

    .content-box:hover {
    color: #212529;
    background-color: rgba(0, 0, 0, 0.075);
    }
</style>