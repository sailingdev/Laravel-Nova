<template>
    <card class="block justify-center items-center fb-page-posts-dashboard">
        <div class="px-3 py-3">
            <SubmitForm :card="card" @formSubmitted="formSubmitted" />
            <ScheduledDrafts :card="card" ref="scheduledDrafts" />
            <PostLibrary :card="card" ref="postLibrary" />
            
            <div class="modal opacity-0 pointer-events-none fixed w-full h-full top-0 left-0 flex items-center justify-center">
                <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>
                
                <div class="modal-container bg-white w-11/12 md:max-w-md mx-auto rounded shadow-lg z-50 overflow-y-auto">
                
                <div class="modal-close absolute top-0 right-0 cursor-pointer flex flex-col items-center mt-4 mr-4 text-white text-sm z-50">
                    <svg class="fill-current text-white" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
                    <path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"></path>
                    </svg>
                    <span class="text-sm">(Esc)</span>
                </div>

                <!-- Add margin if you want to see some of the overlay behind the modal-->
                <div class="modal-content py-4 text-left px-6">
                    <!--Title-->
                    <div class="flex justify-between items-center pb-3">
                    <p class="text-2xl font-bold">Simple Modal!</p>
                    <div class="modal-close cursor-pointer z-50">
                        <svg class="fill-current text-black" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
                        <path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"></path>
                        </svg>
                    </div>
                    </div>

                    <!--Body-->
                    <p>Modal content can go here</p>
                    <p>...</p>
                    <p>...</p>
                    <p>...</p>
                    <p>...</p>

                    <!--Footer-->
                    <div class="flex justify-end pt-2">
                    <button class="px-4 bg-transparent p-3 rounded-lg text-indigo-500 hover:bg-gray-100 hover:text-indigo-400 mr-2">Action</button>
                    <button class="modal-close px-4 bg-indigo-500 p-3 rounded-lg text-white hover:bg-indigo-400">Close</button>
                    </div>
                    
                </div>
                </div>
            </div>

        </div>
    </card>
</template>

<script>
import SubmitForm from './SubmitForm'
import ScheduledDrafts from './ScheduledDrafts'
import PostLibrary from './PostLibrary'
export default {
    props: [
        'card'
    ],
    components: {
        SubmitForm,
        ScheduledDrafts,
        PostLibrary
    },
     methods: {
        formSubmitted () {
            this.$refs.scheduledDrafts.loadScheduledDrafts()
            this.$refs.postLibrary.loadPostLibrary()
        } 
    }
}
</script>
<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,400;0,500;0,700;1,400;1,500;1,700&display=swap');
    .fb-page-posts-dashboard {
        font-family: DM Sans;
    }
    .t-display-header button {
        bottom: 25px !important;
    }
    table tr td, table tr th {
        font-size: 14px;
    }
</style>
