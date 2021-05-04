<template>
    <div class="rd__submit-list-form-wrapper w-80p m-auto">
        <h1 class="text-center text-3xl text-80 font-dark px-4 py-4">Edit Post</h1>
        
        <div class="container-box">
            <div class="shadow-lg py-5 px-5 sm:rounded-md sm:overflow-hidden"> 
                
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
                        <label for="about" class="block text-gray-700 text-left">
                            <i class="fa fa-edit"></i> TEXT <span>*</span>
                        </label>
                        <div class="mt-1 text-left">
                                <textarea rows="6" class="shadow-lg mt-1 block w-full sm:text-sm border-gray-300 rounded-md
                                    focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                                    placeholder="Enter text" v-model="editText"
                                ></textarea>
                        </div>
                    </div>

                    <div class="mt-2">
                        <label class="block text-sm font-medium text-gray-700 text-left">
                            <i class="fa fa-upload"></i> UPLOAD MEDIA
                        </label>
                        <div class="flex items-center">
                            
                            <div class="w-1/2">
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
                                    
                                </div>
                            </div>
                            <div class="w-1/2">
                                <img :src="post.media" class="rounded-lg shadow-2xl max-w-full">
                            </div>
                        </div>
                    </div>

                    <div class="mt-2">
                        <label for="about" class="block text-gray-700 text-left">
                            <i class="fa fa-link"></i> URL TO ARTICLE
                        </label>
                        <div class="mt-1 text-left">
                            <input type="text" class="form-control"  placeholder="Enter url" v-model="editPostUrl" />
                        </div> 
                    </div>

                    <div class="mt-2">
                        <label for="about" class="block text-gray-700 text-left">
                            <i class="fa fa-calendar"></i> SCHEDULE DATE AND TIME
                        </label>
                        <div class="mt-1 text-left">
                            <!-- date picker package not working on this component. Needed to move on, wasting too much time here -->
                            <input type="text" v-model="editStartDate" class="form-control">
                        </div> 
                    </div>

                    <div class="mt-2">
                        <label for="about" class="block text-gray-700 text-left">
                            <i class="fa fa-object-group"></i> PAGE GROUPS
                        </label>
                        <div class="mt-1 text-left">
                            <v-select :options="pageGroups" class="rd__column-selector" v-model="editPageGroupSelected" 
                                :multiple="true"></v-select>
                        </div>
                    </div>

                    <div class="mt-2">
                        <label for="about" class="block text-gray-700 text-left">
                            <i class="fa fa-edit"></i> POST REFERENCE/TAG <span>*</span>
                        </label>
                        <div class="mt-1 text-left">
                            <input type="text" class="form-control" placeholder="Enter text" v-model="editPostReference" />
                        </div>
                    </div>

                </div>
                <div class="px-4 py-3 bg-gray-20 text-left sm:px-6 mt-2">
                    <button :disabled="processing" @click="update" type="submit" class="d-block justify-center py-3 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <span v-if="processing"><loader class="text-60" :fillColor="'#ffffff'" /></span>
                        <span v-else>UPDATE SCHEDULE</span>
                    </button>
                      <p class="px-4 py-3 mt-2 leading-normal text-red-100 bg-red-700 rounded-lg" v-if="inputError != ''">{{ inputError }}</p>
                </div>
            </div> 
        </div>

    </div>
</template>
<script>
import vSelect from 'vue-select' 

export default {
    name: 'EditSchedule',
    data () {
        return {
            processing: false,
            editText: this.post.text,
            media: this.post.media,
            editPostUrl: this.post.url,
            editStartDate: this.post.date + ' ' + this.post.time,
            editPostReference: this.post.reference,
            editPageGroupSelected: this.preparePageGroup(this.post.page_group),
            pageGroups: [],
            errorResponse: {}, 
 
            inputError: '', 
            
            fileRecords: [],
            fileRecordsForUpload: [], 
            formData: {},
            mediaEvent: {}
        }
    },
    props: {
        post: {
            required: true
        },
        card: {
            required: true
        }
    },
    components: {
        vSelect
    },
    methods: {
        preparePageGroup (pageGroups) {
            return pageGroups.split(',')
        },
        uploadFile (e, t) {
            this.mediaEvent = e
            this.media = e.target.files[0]
        },
        editStartDateChanged(data) {
            console.log(data)
           this.editStartDate = data
        },
        update () {
            if (this.editText == '') {
                this.inputError = 'Please enter a text for the post' 
                return false
            }  
            if (this.editPageGroupSelected.length < 1) {
                this.inputError = 'Please select a page group'
                return false
            }  
            if (this.editPostReference == '') {
                this.inputError = 'Please enter a tag or reference for this post'
                return false
            }  
          
            this.inputError = ''
            this.processing = true
            

            this.formData = new FormData() 

            this.formData.append('text', this.editText)
            this.formData.append('url', this.editPostUrl)
            console.log('New date is ', this.editStartDate)
            this.formData.append('start_date', this.editStartDate)
            this.formData.append('page_groups', JSON.stringify(this.editPageGroupSelected))
            this.formData.append('fb_page_post_scheduler_id',  this.post.fb_page_post_scheduler_id)
            this.formData.append('fb_page_post_id',  this.post.fb_page_post_id)
            this.formData.append('reference', this.editPostReference)
            this.formData.append('return_scheduler_resource', true)
            let uploadedMedia = [] 

            this.formData.append('media', this.media == this.post.media ? '' : this.media)
            axios.defaults.headers.post['Content-Type'] = 'multipart/form-data'
           
            axios.post('/nova-vendor/' + this.card.component + '/update-page-post', this.formData)  
            .then(response => { 
                this.$emit('formUpdated', response.data.data)
            }).catch(error => {   
                this.errorResponse = error.response.data
            }).finally(() => {
                this.processing = false
            })
        }, 
        deleteUploadedFile: function (fileRecord) {
            // Using the default uploader. You may use another uploader instead.
            this.$refs.media.deleteUpload('', '', fileRecord);
        },
        filesSelected: function (fileRecordsNewlySelected) {
            // var validFileRecords = fileRecordsNewlySelected.filter((fileRecord) => !fileRecord.error);
            // this.fileRecordsForUpload = this.fileRecordsForUpload.concat(validFileRecords);
        },
        onBeforeDelete: function (fileRecord) {
            var i = this.fileRecordsForUpload.indexOf(fileRecord);
            if (i !== -1) {
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
    textarea {
        resize: none;
    }
    label {
        font-size: 15px;
        color: #7c858e;
        font-weight: bold;
        margin-top: 50px !important;
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
