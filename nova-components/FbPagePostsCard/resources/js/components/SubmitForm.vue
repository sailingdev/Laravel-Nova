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
                                <textarea rows="6" class="shadow-lg mt-1 block w-full sm:text-sm border-gray-300 rounded-md
                                    focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                                    placeholder="Enter text" v-model="text"
                                ></textarea>
                                <p class="px-4 py-3 mt-2 leading-normal text-red-100 bg-red-700 rounded-lg" v-if="textInputError != ''">{{ textInputError }}</p>
                        </div>
                    </div>

                    <div class="mt-2">
                        <label class="block text-sm font-medium text-gray-700">
                            <i class="fa fa-upload"></i> UPLOAD MEDIA
                        </label>
                        <div class="mt-1 text-left">
                            <VueFileAgent @change="uploadFile($event, 'media')"  
                                :deletable="true"
                                :accept="'image/jpg, image/jpeg, image/png, video/mp4'" 
                                :maxSize="'5MB'"
                                :maxFiles="1"
                                @select="filesSelected($event)"
                                @beforedelete="onBeforeDelete($event)"
                                @delete="fileDeleted($event)"
                                v-model="fileRecords"
                                :helpText="'Click or drop to upload a file'"  ref="media"
                            ></VueFileAgent>
                            <p class="mt-4 text-sm text-gray-500">
                            Images (png, gif, jpeg) or Videos (mp4) only
                            </p>
                            <p class="px-4 py-3 mt-2 leading-normal text-red-100 bg-red-700 rounded-lg" v-if="mediaInputError != ''">{{ mediaInputError }}</p>
                        </div>
                    </div>

                    <div class="mt-2">
                        <label for="about" class="block text-gray-700">
                            <i class="fa fa-link"></i> URL TO ARTICLE
                        </label>
                        <div class="mt-1 text-left">
                            <input type="text" class="form-control"  placeholder="Enter url" v-model="postUrl"
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
                        <p class="px-4 py-3 mt-2 leading-normal text-red-100 bg-red-700 rounded-lg" v-if="pageGroupInputError != ''">{{ pageGroupInputError }}</p>
                    </div>

                    <div class="mt-2">
                        <label for="about" class="block text-gray-700">
                            <i class="fa fa-edit"></i> POST REFERENCE/TAG <span>*</span>
                        </label>
                        <div class="mt-1 text-left">
                            <input type="text" class="form-control" placeholder="Enter text" v-model="postReference" />
                        </div>
                        <p class="px-4 py-3 mt-2 leading-normal text-red-100 bg-red-700 rounded-lg" v-if="postReferenceInputError != ''">{{ postReferenceInputError }}</p>
                    </div>

                </div>
                <div class="px-4 py-3 bg-gray-20 text-left sm:px-6 mt-2">
                    <button :disabled="processing" @click="submit" type="submit" class="d-block justify-center py-3 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
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

export default {
    name: 'SubmitForm',
    data () {
        return {
            processing: false,
            text: 'A sample text',
            media: [],
            postUrl: 'http://revenedriver.com/testing',
            startDate:'2021-05-12 12:20',
            postReference: 'Post 1',
            pageGroupSelected: ['Group 1', 'Group 2'],
            pageGroups: [],
            errorResponse: {},
            displayForm: true,
            displaySubmitSuccess: false,

            textInputError: '',
            mediaInputError: '',
            pageGroupInputError: '',
            postReferenceInputError: '',
            
            fileRecords: [],
            uploadUrl: 'https://www.mocky.io/v2/5d4fb20b3000005c111099e3',
            uploadHeaders: { 'X-Test-Header': 'vue-file-agent' },
            fileRecordsForUpload: [], // maintain an upload queue,
            formData: {}
        }
    },
    props: {
        card: {
            required: true
        }
    },
    components: {
        vSelect
    },
    methods: {
        uploadFile (e, t) {
            this.media = e.target.files[0]
        },
        startDateChanged(data) {
           this.startDate = data
        },
        submit () {
            this.processing = true
            if (this.text == '') {
                this.textInputError = 'Please enter a text for the post'
                return false
            } else {
                this.textInputError = ''
            }
            if (this.pageGroupSelected.length < 1) {
                this.pageGroupInputError = 'Please select a page group'
                return false
            } else {
                this.pageGroupInputError = ''
            }
            if (this.postReference == '') {
                this.postReferenceError = 'Please enter a tag or reference for this post'
                return false
            } else {
                this.postReferenceError = ''
            }
          
           
            this.textInputError = this.pageGroupInputError = this.postReferenceError = ''

            this.formData = new FormData() 

            this.formData.append('text', this.text)
            this.formData.append('url', this.postUrl)
            this.formData.append('start_date', this.startDate)
            this.formData.append('page_groups', JSON.stringify(this.pageGroupSelected))
           
            this.formData.append('reference', this.postReference)
            let uploadedMedia = []

            // It is not accepting multiple uploads for now 
            // if (this.media.length > 0) { 
            //     this.media.forEach(e => {
            //         uploadedMedia.push(e)
            //     })
            // }
            this.formData.append('media', this.media)
            axios.defaults.headers.post['Content-Type'] = 'multipart/form-data'
            axios.post('/nova-vendor/' + this.card.component + '/submit-page-post', this.formData)  
            .then(response => {  
                this.displayForm = false
                this.displaySubmitSuccess = true
            }).catch(error => {   
                this.errorResponse = error.response.data
            }).finally(() => {
                this.processing = false
            })
        }, 
        deleteUploadedFile: function (fileRecord) {
            // Using the default uploader. You may use another uploader instead.
            this.$refs.media.deleteUpload(this.uploadUrl, this.uploadHeaders, fileRecord);
        },
        filesSelected: function (fileRecordsNewlySelected) {
            var validFileRecords = fileRecordsNewlySelected.filter((fileRecord) => !fileRecord.error);
            this.fileRecordsForUpload = this.fileRecordsForUpload.concat(validFileRecords);
        },
        onBeforeDelete: function (fileRecord) {
            var i = this.fileRecordsForUpload.indexOf(fileRecord);
            if (i !== -1) {
                // queued file, not yet uploaded. Just remove from the arrays
                this.fileRecordsForUpload.splice(i, 1);
                var k = this.fileRecords.indexOf(fileRecord);
                if (k !== -1) this.fileRecords.splice(k, 1);
                } else {
                if (confirm('Are you sure you want to delete?')) {
                    this.$refs.media.deleteFileRecord(fileRecord); // will trigger 'delete' event
                }
            }
        },
        fileDeleted: function (fileRecord) {
            var i = this.fileRecordsForUpload.indexOf(fileRecord);
            if (i !== -1) {
            this.fileRecordsForUpload.splice(i, 1);
            } else {
            this.deleteUploadedFile(fileRecord);
            }
        },
        processAnother () {
            this.displayForm = true
            this.displaySubmitSuccess = false
        }
    },
    mounted () {
        axios.get('/nova-vendor/' + this.card.component + '/load-page-groups')  
        .then(response => {  
            this.pageGroups = response.data.data
        }).catch(error => {   
            this.errorResponse = error.response.data
        }).finally(() => {
            this.loading = false
        })
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
