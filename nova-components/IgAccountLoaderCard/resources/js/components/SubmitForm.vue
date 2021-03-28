<template>
    <div class="rd__submit-list-form-wrapper">
        <h1 class="text-center text-3xl text-80 font-dark px-4 py-4">Load Instagram Account IDs <small>(Work in Progress)</small></h1>
        
        <div class="container-box shadow-sm rounded" v-if="displayForm">
            <div class="shadow sm:rounded-md sm:overflow-hidden"> 
                
                <div v-if="Object.entries(errorResponse).length > 0">
                    <div class="px-4 py-3 leading-normal text-red-100 bg-red-700 rounded-lg" role="alert">
                        <h4 class="mt-2 mb-2"> {{ errorResponse.message }} </h4>
                        <p v-for="(error, index) in errorResponse.errors" :key="index" class="text-sm"> 
                            => {{ error[0] }} 
                        </p>
                    </div>
                </div>

                <div>
                    <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                        <div>
                            <label for="about" class="block text-sm font-medium text-gray-700">
                                Facebook Page IDs
                            </label>
                            <div class="mt-1 px-10 py-5 pb-6 border-2 border-gray-300  border-dashed rounded-md">
                                <textarea rows="13" class="shadow-lg mt-1 block w-full sm:text-sm border-gray-300 rounded-md
                                focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                                placeholder="108032627632083
                                103847081336171
                                103152925105389"
                                v-model="fbPageIDs"
                                ></textarea>
                                <p class="mt-4 text-sm text-gray-500">
                                    Enter facebook page IDs to load for. One per line.
                                </p>
                            </div>
                        
                        </div>

                        
                    </div>
                    <div class="px-4 py-3 bg-gray-20 text-right sm:px-6 mt-2">
                        <button :disabled="processing" @click="submit" type="submit" class="inline-flex justify-center py-3 px-6 border border-transparent shadow-sm text-lg font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <span v-if="processing"><loader class="text-60" :fillColor="'#ffffff'" /></span>
                            <span v-else>Submit</span>
                        </button>
                    </div>
                </div> 
            </div> 
        </div>
             
        <div v-if="displaySubmitSuccess">
            <div class="mt-1 px-10 py-5 pb-6 border-2 border-gray-300  border-dashed rounded-md rd__notify-submit-success">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1em" height="1em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path d="M9 19.414l-6.707-6.707l1.414-1.414L9 16.586L20.293 5.293l1.414 1.414" fill="#3da35a"/></svg>
                <h4 class="text-2xl text-center text-3xl text-80 font-dark px-4 py-4"> Request was successful! </h4>
                
                <div v-if="igAccounts.length < 1">
                    <div class="px-4 py-3 leading-normal text-gray-100 bg-gray-700 rounded-lg text-center" role="alert">
                        <p> No record </p>
                    </div> 
                </div>

                <div v-else>
                     <div class="header-box flex">
                        <div class>Facebook Page ID</div>  
                        <div> Instagram Account ID </div>
                    </div>
                    <div>
                        <div class="content-box flex w-full" v-for="(igAccount, key) in igAccounts" :key="key">
                            <div>{{ igAccount.facebook_page_id }}</div>
                            <div>{{ igAccount.instagram_account_id }}</div>
                        </div>
                    </div>
                </div> 

                <div>
                    <p class="mt-3">
                        <button @click="processAnother" class="justify-center py-3 px-5 border border-transparent shadow-sm text-lg font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"> 
                            Load another 
                        </button>
                    </p>
                </div>
            </div> 
        </div>

    </div>
</template>
<script> 
export default {
    name: "SubmitForm",
    data () {
        return {
            processing: false,
            fbPageIDs: '', 
            errorResponse: {},
            displayForm: true,
            displaySubmitSuccess: false,
            igAccounts: [],
            markets: []
        }
    },
    props: {
        card: {
            required: true
        }
    }, 
    methods: {
        submit () { 
            if (this.fbPageIDs != '') {
                this.processing = true
                axios.post('/nova-vendor/' + this.card.component + '/load-ig-accounts', {
                    fb_page_ids: this.fbPageIDs,
                })
                .then(response => {
                    this.displayForm = false
                    this.fbPageIDs = ''
                    this.displaySubmitSuccess = true
                    this.igAccounts = response.data.data
                }).catch(error => {
                    this.errorResponse = error.response.data
                })
                .finally(() => {
                    this.processing = false
                })
            }
        },
      
        processAnother () {
            this.displaySubmitSuccess = false
            this.batchId = ''
            this.displayForm = true
        },

   
      
    }
}
</script>
<style scoped>
    .header-box div, .content-box div {
        width: 25%;
    }
    .bg-orange-600 { background-color: #dd6b20; }
</style>
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
    .batch-container {
        border-bottom: 1px solid #ccc !important;
        padding: 40px 0;
    }
    .batch-container h4 {
        background-color: rgba(0, 0, 0, 0.08) !important;
        border-top: 1px solid #2b6cb0 !important;
    }
</style>