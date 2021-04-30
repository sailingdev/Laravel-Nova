<template>
    <card class="flex flex-col items-center justify-center">
        <div class="px-3 py-3">
            <SubmitForm :card="card" />
            <ScheduledDrafts :card="card" />
        </div>
    </card>
</template>

<script>
import SubmitForm from './SubmitForm'
import ScheduledDrafts from './ScheduledDrafts'
export default {
    props: [
        'card'
    ],
    components: {
        SubmitForm,
        ScheduledDrafts
    },
    mounted() {
        //
    },
}
</script>
