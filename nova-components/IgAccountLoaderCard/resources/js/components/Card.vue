<template>
    <card class="flex flex-col items-center justify-center">
        <div class="px-3 py-3">
            <SubmitForm :card="card" @formSubmitted="formSubmitted"/>
        </div>
    </card>
</template>

<script>
import SubmitForm from './SubmitForm'
export default {
    props: [
        'card',
    ],
    components: {
        SubmitForm
    },
    mounted() {
        //
    },
}
</script>
