<template>
    <card class="block justify-center items-center fb-page-posts-dashboard">
        <div class="px-3 py-3">
            <!-- <SubmitForm :card="card" @formSubmitted="formSubmitted" /> -->
            <ScheduledDrafts :card="card" ref="scheduledDrafts" />
            <PostLibrary :card="card" ref="postLibrary" /> 
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
        },
        toggleModal () { 
            const body = document.querySelector('body')
            const modal = document.querySelector('.modal')
            modal.classList.toggle('opacity-0')
            modal.classList.toggle('pointer-events-none')
            body.classList.toggle('modal-active')
        }
    },
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
