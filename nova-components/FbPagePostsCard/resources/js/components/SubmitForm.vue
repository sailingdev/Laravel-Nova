<template>
    <div class="rd__submit-list-form-wrapper">
        <h1 class="text-center text-3xl text-80 font-dark px-4 py-4">Draft Post (Work In Progress)</h1>
        
        <div class="container-box" v-if="displayForm">
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
                            <label for="about" class="block text-gray-700">
                                <i class="fa fa-edit"></i> TEXT <span>*</span>
                            </label>
                            <div class="mt-1 text-left">
                                <textarea rows="6" class="shadow-lg mt-1 block w-full sm:text-sm border-gray-300 rounded-md
                                    focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                                    placeholder="Enter text" v-model="keywords"
                                ></textarea>
                            </div> 
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                <i class="fa fa-upload"></i> UPLOAD MEDIA
                            </label>
                            <div class="mt-1 text-left">
                                <VueFileAgent @change="uploadFile($event, 'profilePhoto')"  
                                    :deletable="false"
                                    :accept="'image/jpg, image/jpeg, image/png'" 
                                    :maxSize="'5MB'"
                                    :helpText="'Click or drop to upload a file'"  ref="profilePhoto"
                               ></VueFileAgent>
                                <p class="mt-4 text-sm text-gray-500">
                                   Images (png, gif, jpeg) or videos (mp4) only
                                </p>
                            </div>
                        </div>

                        <div>
                            <label for="about" class="block text-gray-700">
                                <i class="fa fa-edit"></i> URL TO ARTICLE
                            </label>
                            <div class="mt-1 text-left">
                                <textarea rows="6" class="shadow-lg mt-1 block w-full sm:text-sm border-gray-300 rounded-md
                                    focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                                    placeholder="Enter text" v-model="keywords"
                                ></textarea>
                            </div> 
                        </div>
                         <div>
                            <label for="about" class="block text-gray-700">
                                <i class="fa fa-edit"></i> SCHEDULE DATE AND TIME
                            </label>
                            <div class="mt-1 text-left">
                                <date-time-picker :placeholder="new Date().toDateString()" @change="startDateChanged"
                                    :value="start_date" :dateFormat="'Y-m-d H:i'" :enableTime="true" :altFormat="'Y-m-d'">
                                </date-time-picker>
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
    name: 'SubmitForm',
    data () {
        return {
            processing: false,
            text: '',
            errorResponse: {},
            displayForm: true,
            displaySubmitSuccess: false,
            markets: []
        }
    },
    components: {
    },
    methods: {
        uploadFile (e, t) {
            
        },
        startDateChanged(data) {
           this.startDate = data
        },
    }
}
</script>
<style scoped>
    label {
        font-size: 16px;
        font-weight: bold;
    }
</style>
