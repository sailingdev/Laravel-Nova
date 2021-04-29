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
  
                <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
            
                    <div  class="mt-2">
                        <label for="about" class="block text-gray-700">
                            <i class="fa fa-edit"></i> TEXT <span>*</span>
                        </label>
                        <div class="mt-1 text-left">
                            <validation-provider rules="required" v-slot="{ errors }" name="post text">
                                <textarea rows="6" class="shadow-lg mt-1 block w-full sm:text-sm border-gray-300 rounded-md
                                    focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                                    placeholder="Enter text" v-model="keywords"
                                ></textarea>
                                <p class="px-4 py-3 mt-2 leading-normal text-red-100 bg-red-700 rounded-lg" v-if="errors.length > 0"> {{ errors[0] }}</p>
                            </validation-provider>
                        </div>
                    </div>

                    <div class="mt-2">
                        <label class="block text-sm font-medium text-gray-700">
                            <i class="fa fa-upload"></i> UPLOAD MEDIA
                        </label>
                        <div class="mt-1 text-left">
                            <VueFileAgent @change="uploadFile($event, 'media')"  
                                :deletable="false"
                                :accept="'image/jpg, image/jpeg, image/png, video/mp4'" 
                                :maxSize="'5MB'"
                                :helpText="'Click or drop to upload a file'"  ref="media"
                            ></VueFileAgent>
                            <p class="mt-4 text-sm text-gray-500">
                            Images (png, gif, jpeg) or Videos (mp4) only
                            </p>
                        </div>
                    </div>

                    <div class="mt-2">
                        <label for="about" class="block text-gray-700">
                            <i class="fa fa-link"></i> URL TO ARTICLE
                        </label>
                        <div class="mt-1 text-left">
                            <input type="text" class="form-control" 
                                placeholder="Enter text" v-model="keywords"
                            />
                        </div> 
                    </div>

                    <div class="mt-2">
                        <label for="about" class="block text-gray-700">
                            <i class="fa fa-calendar"></i> SCHEDULE DATE AND TIME
                        </label>
                        <div class="mt-1 text-left">
                            <date-time-picker :placeholder="new Date().toDateString()" @change="startDateChanged"
                                :value="startDate" :dateFormat="'Y-m-d H:i'" :enableTime="true" :altFormat="'Y-m-d H:i'">
                            </date-time-picker>
                        </div> 
                    </div>

                    <div class="mt-2">
                        <label for="about" class="block text-gray-700">
                            <i class="fa fa-object-group"></i> PAGE GROUPS
                        </label>
                        <div class="mt-1 text-left">
                            <v-select :options="pageGroups" class="rd__column-selector" v-model="pageGroupSelected" 
                                :multiple="true"></v-select>
                        </div> 
                    </div>

                    <div class="mt-2">
                        <validation-provider rules="required" v-slot="{ errors }" name="post text">
                            <label for="about" class="block text-gray-700">
                                <i class="fa fa-edit"></i> POST REFERENCE/TAG <span>*</span>
                            </label>
                            <div class="mt-1 text-left">
                                <input type="text" class="form-control" placeholder="Enter text" v-model="postReference" />
                            </div>
                            <p class="px-4 py-3 mt-2 leading-normal text-red-100 bg-red-700 rounded-lg" v-if="errors.length > 0"> {{ errors[0] }}</p>
                        </validation-provider>
                    </div>

                </div>
                <div class="px-4 py-3 bg-gray-20 text-left sm:px-6 mt-2">
                    <button :disabled="processing" @click="submit" type="submit" class="inline-flex justify-center py-3 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <span v-if="processing"><loader class="text-60" :fillColor="'#ffffff'" /></span>
                        <span v-else>SCHEDULE</span>
                    </button>
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
import vSelect from 'vue-select'
// import Vue from 'vue'
import { ValidationObserver, ValidationProvider, extend, localize } from 'vee-validate'
import en from 'vee-validate/dist/locale/en.json'
import * as rules from 'vee-validate/dist/rules'

Object.keys(rules).forEach(rule => {
  extend(rule, rules[rule])
})
localize('en', en)
// setInteractionMode('lazy')
export default {
    name: 'SubmitForm',
    data () {
        return {
            processing: false,
            text: '',
            startDate:'',
            postReference: '',
            pageGroupSelected: '',
            pageGroups: ['Mona', 'Ilemona'],
            errorResponse: {},
            displayForm: true,
            displaySubmitSuccess: false,
            markets: []
        }
    },
    components: {
        vSelect,
        ValidationObserver,
        ValidationProvider
    },
    methods: {
        uploadFile (e, t) {
            
        },
        startDateChanged(data) {
           this.startDate = data
        },
        newOption (newVal) {
            this.pageGroupSelected = newVal
        },
        submit () {
            // this.processing = true
        }
    }
}
</script>
<style scoped>
    label {
        font-size: 14px;
        color: #7c858e;
        font-weight: bold;
    }
    label span {
        color: #900;
    }
    label i {
        display: inline-block;
        margin-right: 2px;
    } 
    input, select {
        height: 42px;
    }
    .rd__column-selector > input  {
        padding: 13px 10px !important; 
    }
</style>
