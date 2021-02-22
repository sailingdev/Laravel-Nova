<template>
    <div class="submit-keywords-form-wrapper">
        <h1 class="text-center text-3xl text-80 font-dark px-4 py-4">Submit Keywords</h1>
        
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
                                Keywords
                            </label>
                            <div class="mt-1 px-10 py-5 pb-6 border-2 border-gray-300  border-dashed rounded-md">
                                <textarea rows="13" class="shadow-lg mt-1 block w-full sm:text-sm border-gray-300 rounded-md
                                focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                                placeholder="hp latop
                                best notebooks
                                mattress"
                                v-model="keywords"
                                ></textarea>
                                <p class="mt-4 text-sm text-gray-500">
                                    Enter keywords. One per line.
                                </p>
                            </div>
                        
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Market
                            </label>
                            <div class="w-full block mt-1 justify-center p-6 border-2 border-gray-300 border-dashed rounded-md">
                                <div class="w-full select-market-wrapper"> 
                                    <select v-model="market" class="mt-1 block w-full p-3 pr-4 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-lg">
                                        <option value="DE">Germany</option>
                                        <option value="UK">United Kingdom</option>
                                        <option value="US">United States</option>
                                    </select> 
                                </div>
                                <p class="mt-4 text-sm text-gray-500">
                                    Select target market
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
            <div class="mt-1 px-10 py-5 pb-6 border-2 border-gray-300  border-dashed rounded-md notify-submit-success">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1em" height="1em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path d="M9 19.414l-6.707-6.707l1.414-1.414L9 16.586L20.293 5.293l1.414 1.414" fill="#3da35a"/></svg>
                <h4 class="text-2xl text-center text-3xl text-80 font-dark px-4 py-4"> Thank you! </h4>
                <p class="mt-2 mb-2"> Keywords have been successfully submitted. Batch processing in progress</p>
                <p class="mt-3 mb-4"> Batch ID: <span class="font-dark text-xl font-semibold">{{ batchId }}</span></p>

                <div>
                    <p>
                        <button @click="processAnother" class="justify-center py-3 px-5 border border-transparent shadow-sm text-lg font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"> 
                            Process another 
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
            keywords: '',
            market: 'US',
            errorResponse: {},
            displayForm: true,
            displaySubmitSuccess: false,
            batchId: ''
        }
    },
    props: {
        card: {
            required: true
        }
    },
    methods: {
        submit () { 
            if (this.keywords != '' && this.market != '') {
                this.processing = true
                axios.post('/nova-vendor/' + this.card.component + '/submit-keywords', {
                    keywords: this.keywords,
                    market: this.market
                })
                .then(response => {
                    this.displayForm = false
                    this.displaySubmitSuccess = true
                    this.batchId = response.data.data
                    this.keywords = ''
                    this.market = 'US'
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
        } 
    }
}
</script>
<style scoped>
    .submit-keywords-form-wrapper textarea {
        min-height: 150px;
        border: 1px solid #ccc;
        padding: 10px 15px;
        white-space: pre-line;
    }
    .submit-keywords-form-wrapper textarea:focus {
        border: 1px solid indigo !important;
    }
    .submit-keywords-form-wrapper label {
        font-size: 22px;
        margin-bottom: 5px;
    }
    .submit-keywords-form-wrapper .container-box {
        margin: 20px auto;
        min-width: 650px;
        max-width: 100%;
    }
    .notify-submit-success svg {
        width: 150px;
        height: 150px;
        display: block;
        margin: auto;
    }
</style>