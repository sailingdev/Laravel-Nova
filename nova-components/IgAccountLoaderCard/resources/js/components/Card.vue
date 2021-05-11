<template>
    <card class="flex flex-col items-center justify-center">
        <div class="px-3 py-3">
            <SubmitForm :card="card"/>
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
<style>
  @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,400;0,500;0,700;1,400;1,500;1,700&display=swap');
  .rd__submit-list-form-wrapper {
    font-family: DM Sans;
  }
</style>
