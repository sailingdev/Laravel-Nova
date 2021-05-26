<template>
    <div class="batches-to-process mt-5 mb-5 w-full"> 

        <div v-if="displaySubmitSuccess">
            <div class="mt-1 mb-5 px-10 py-5 pb-6 border-2 border-gray-300  border-dashed rounded-md notify-submit-success">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1em" height="1em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path d="M9 19.414l-6.707-6.707l1.414-1.414L9 16.586L20.293 5.293l1.414 1.414" fill="#3da35a"/></svg>
                <h4 class="text-2xl text-center text-3xl text-80 font-dark px-4 py-4"> Thank you! </h4>
                <p class="mt-2 mb-2 text-center"> Batch processing in progress. Please check back in few minutes time</p>
            </div> 
        </div>

        <div v-if="loading" class="rounded-lg flex items-center justify-center relative">
            <loader class="text-60" />
        </div>
        
        <div v-else class="w-full mx-auto p-8">   
            <div class="shadow-md pt-6 pb-6" v-if="displayForm">
                
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
                
                <div v-else class="px-4 py-3"> 
                    <div class="batch-container">
                        <div class="px-3 py-4 flex justify-center">
                            <table class="w-full text-md rounded mb-4 table-striped table-bordered">
                                <thead class="bg-black ">
                                    <tr class="">
                                        <th class="text-left p-3 px-5">DELETE</th>
                                        <th class="text-left p-3 px-5">KEYWORD</th>
                                        <th class="text-left p-3 px-5">TYPETAG TO DUPLICATE</th>
                                        <th class="text-left p-3 px-5">CREATE ACTION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-b hover:bg-orange-100 bg-white" v-for="(batch, key) in batches" :key="key" >
                    
                                        <td class="p-3 px-5 flex justify-center">
                                            <button @click="deleteKeyword(batch, key)" type="button" class="text-sm bg-red-500 hover:bg-red-700 text-white py-1 px-2 rounded focus:outline-none focus:shadow-outline">
                                                <i class="fa fa-times-circle"></i>
                                            </button>
                                        </td>
                                        <td class="p-3 px-5">{{ batch.keyword }}</td>
                                        <td class="p-3 px-5">
                                            <input type="text" v-model="batches[key].type_tag" placeholder="Enter type tag to duplicate" class="form-control">
                                            <span v-if="processingKeyErr == key" class="ml-2 text-red-600 text-sm"> {{ validateError  }} </span>
                                        </td>
                                        <td class="p-3 px-5">
                                            <button :disabled="processingKey == key" type="button" @click="prepareForDispatch(key)" class="mr-3 text-sm bg-green-500 hover:bg-green-700 text-white py-1 px-2 rounded focus:outline-none focus:shadow-outline">
                                                <span v-if="processingKey == key"><loader class="text-60" :fillColor="'#ffffff'" /></span>
                                                <span v-else><i class="fa fa-check-circle"></i> &nbsp; Create</span>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div> 
                    </div> 
                </div>

            </div>

            <button :disabled="mocking" type="button" @click="mockDuplicator()" class="mr-3 mt-4 text-sm bg-green-500 hover:bg-green-700 text-white py-1 px-2 rounded focus:outline-none focus:shadow-outline">
                <span v-if="mocking"><loader class="text-60" :fillColor="'#ffffff'" /></span>
                <span v-else><i class="fa fa-check-circle"></i> &nbsp; Mock Duplicator Scheduler</span>
            </button>
        </div>
    </div>
</template>
<script>
import DynamicTable from '../../../../../nova/resources/js/components/RevenueDriver/DynamicTable'
export default {
    name: "CreateFromRelated",
    data () {
        return {
            loading: false,
            processingKey: null,
            processingKeyErr: null,
            batches: [],
            errorResponse: {},
            displayForm: true,
            displaySubmitSuccess: false,
            mocking: false
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
                this.batches = data
                if (this.displaySubmitSuccess) {
                    this.displaySubmitSuccess = false
                }
            }).catch(error => {
                this.errorResponse = error.response.data
            })
            .finally(() => {
                this.loading = false
            })
        },
        prepareForDispatch (key) {
            let loader = []
            if (this.batches[key].type_tag == null) {
                this.processingKeyErr = key
                this.validateError = 'Please enter a valid type tag'
                return false
            }
            this.processingKeyErr = this.validateError = null
            this.processingKey = key 
            console.log(typeof this.batches[key])
            return this.createCampaign(this.batches[key]) 
        },
        createCampaign (payload) {
            axios.post('/nova-vendor/' + this.card.component + '/create-campaign', {
                data: payload
            })
            .then(response => {
                const data = response.data.data
                this.displaySubmitSuccess = true
                this.loadBatchesToProcess()
                this.$emit('formSubmitted')
            }).catch(error => {
                this.errorResponse = error.response.data
            })
            .finally(() => {
                this.processingKey = null
            })
        },
        mockDuplicator () {
            this.mocking = true
            axios.post('/nova-vendor/' + this.card.component + '/mock-duplicator')
            .then(response => {
                alert('Mock scheduler ran successfully')
            }).catch(error => {
            })
            .finally(() => {
                this.mocking = false
            })
        },
        deleteKeyword (keyword, key) {
            this.$confirm({
                message: `Are you sure you wish to delete this keyword?`,
                button: {
                    no: 'No',
                    yes: `Yes, I'm sure`
                },
                callback: confirm => {
                    if (confirm) {
                       this.batches.splice(key, 1)
                        axios.delete('/nova-vendor/' + this.card.component + '/delete-keyword', {
                            data: {
                                id: keyword.id
                            }
                        }).catch(error => {   
                            this.errorResponse = error.response.data
                        })
                    }
                }
            })
            
        },
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
        flex: 1 1 100%;
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
<style scoped>
    table tr td, table tr th {
        font-size: 14px;
    }
</style>