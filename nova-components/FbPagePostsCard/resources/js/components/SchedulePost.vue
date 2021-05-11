<template>
    <div>
        <div class="text-center pb-3">
            <p class="text-2xl font-bold"> SCHEDULE THIS POST </p>
        </div> 
        <div class="mx-auto lg:mx-0 w-full pt-3 border-b-2 border-green-500 opacity-25"></div>

        <div v-if="scheduleSuccess" class="px-4 py-3 leading-normal text-green-100 bg-green-700 rounded-lg" role="alert">
            Thank you! Post has been successfully scheduled.
        </div>

        <div class="flex items-center h-auto flex-wrap" >

            <!--Main Col-->
            <div id="profile" class="w-3/5 rounded-lg lg:rounded-l-lg lg:rounded-r-none shadow-2xl bg-white">
            
                <div class="p-4 md:p-12 text-center lg:text-left">
                    <p class="pt-4 text-xs font-bold flex items-center justify-start">
                        <span class="h-4 fill-current text-green-700 pr-4 pt-1"><i class="fa fa-suitcase"></i> Post Reference </span>
                        <span class="text-sm">{{ post.reference }}</span> 
                    </p>
                    <p class="pt-4 text-xs font-bold flex items-center justify-start">
                        <span class="h-4 fill-current text-green-700 pr-4 pt-1"><i class="fa fa-link"></i> External URL </span>
                        <span class="text-sm">{{ post.url }}</span> 
                    </p>

                    <template v-if="scheduleSuccess">
                        <p class="pt-4 text-xs font-bold flex items-center justify-start">
                            <span class="h-4 fill-current text-green-700 pr-4 pt-1"><i class="fa fa-object-group"></i> Page Groups</span>
                            <span class="text-sm">{{ newSchedule.page_group }}</span> 
                        </p>
                        <p class="pt-4 text-xs font-bold flex items-center justify-start">
                            <span class="h-4 fill-current text-green-700 pr-4 pt-1"><i class="fa fa-calendar"></i> Scheduled Date </span>
                            <span class="text-sm">{{ newSchedule.date }}</span> 
                        </p>
                        <p class="pt-4 text-xs font-bold flex items-center justify-start">
                            <span class="h-4 fill-current text-green-700 pr-4 pt-1"><i class="fa fa-clock-o"></i> Scheduled Time </span>
                            <span class="text-sm">{{ newSchedule.time }} (UTC)</span> 
                        </p>
                    </template>

                    <p class="pt-4 text-xs font-bold flex items-center justify-start">
                        <span class="h-4 fill-current text-green-700 pr-4 pt-1"><i class="fa fa-book-reader"></i> Post Text</span>
                    </p>
                    <p class="pt-3 text-sm text-left"> {{ post.text }} </p> 
                </div>

                <div class="px-6 py-5 bg-white space-y-6 sm:p-6" v-if="!scheduleSuccess">
                     <div class="mt-2">
                        <label for="about" class="block text-gray-700 text-left">
                            <i class="fa fa-calendar"></i> SCHEDULE DATE AND TIME
                        </label>
                        <div class="mt-1 text-left">
                            <VueCtkDateTimePicker v-model="scheduleStartDate" :format="'YYYY-MM-DD HH:mm'"/>
                        </div> 
                        <p class="mt-4 text-sm text-gray-500 text-left"> <b>The UTC time zone is used. Please select with UTC in mind.</b> </p>
                    </div>

                    <div class="mt-2">
                        <label for="about" class="block text-gray-700 text-left">
                            <i class="fa fa-object-group"></i> PAGE GROUPS
                        </label>
                        <div class="mt-1 text-left">
                            <v-select :options="schedulePageGroups" class="rd__column-selector" v-model="schedulePageGroupSelected" 
                                :multiple="true">
                            </v-select>
                        </div>
                    </div>

                     <div class="py-3 bg-gray-20 text-left sm:px-6 mt-2">
                        <button :disabled="processing" @click="schedule" type="submit" class="d-block justify-center py-3 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <span v-if="processing"><loader class="text-60" :fillColor="'#ffffff'" /></span>
                            <span v-else>SCHEDULE</span>
                        </button>
                        <p class="px-4 py-3 mt-2 leading-normal text-red-100 bg-red-700 rounded-lg" v-if="scheduleFormInputError != ''">{{ scheduleFormInputError }}  </p>
                    </div>
                </div>

            </div>
                
            <div class="w-2/5"> 
                <img :src="post.media" class="rounded-lg shadow-2xl block">
            </div>
            
        </div>
    </div>
</template>
<script>
import vSelect from 'vue-select'
import DatePicker from 'vue2-datepicker'
import 'vue2-datepicker/index.css'

import VueCtkDateTimePicker from 'vue-ctk-date-time-picker';
import 'vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css';
export default {
    name: 'SchedulePost',
    data () {
        return {
            scheduleStartDate: null,
            schedulePageGroupSelected: [],
            schedulePageGroups: [],
            errorResponse: {},
            scheduleFormInputError: '',
            processing: false,
            scheduleSuccess: false,
            newSchedule: {}
        }
    },
    props: {
        card: {
            required: true
        },
        post: {
            required: true,
            type: Object
        },
        setUpdateAlert: {
            type: Boolean,
            default: false
        }
    },
    components: {
        vSelect,
        DatePicker,
        VueCtkDateTimePicker
    },
    computed: {
        showUpdateAlert () {
            if (this.setUpdateAlert == true) {
               return true
            }
            return false
        }
    },
    methods: { 
        schedule () {
            if (this.scheduleStartDate === null) {
                this.scheduleFormInputError = 'Please select a schedule for the post'
                return false
            }
            else if (this.schedulePageGroupSelected.length < 1) {
                this.scheduleFormInputError = 'Please select at least a group to post into'
                return false
            } 
            this.processing = true
            this.scheduleFormInputError = ''
            axios.defaults.headers.post['Content-Type'] = 'multipart/form-data'
            axios.post('/nova-vendor/' + this.card.component + '/schedule-page-post', {
                start_date:  this.scheduleStartDate,
                page_groups: JSON.stringify(this.schedulePageGroupSelected),
                fb_page_post_id: this.post.id
            })  
            .then(response => {  
                this.newSchedule = response.data.data
                this.scheduleStartDate = null
                this.schedulePageGroupSelected = []
                this.scheduleSuccess = true
                this.$emit('triggerScheduleReload')
            }).catch(error => {   
                this.errorResponse = error.response.data
            }).finally(() => {
                this.processing = false
            })
        }
    },
     mounted () { 
        axios.get('/nova-vendor/' + this.card.component + '/load-page-groups')  
        .then(response => {  
            this.schedulePageGroups = response.data.data
        }).catch(error => {   
            this.errorResponse = error.response.data
        }).finally(() => {
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