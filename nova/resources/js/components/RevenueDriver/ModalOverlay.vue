<template>
     <div v-if="showModal" class="modal fixed flex w-full h-full top-0 left-0 items-center justify-center">
          <!-- h-full  -->
        <div class="modal-overlay absolute w-full bg-gray-900 opacity-50"></div>
        <!-- opacity-1  -->
        <div class="modal-container bg-white w-11/12 md:max-w-md mx-auto rounded shadow-lg z-50 overflow-y-auto">
        
        <div @click="closeModal" class="modal-close absolute top-0 right-0 cursor-pointer flex flex-col items-center mt-4 mr-4 text-white text-sm z-50">
            <svg class="fill-current text-white" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
            <path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"></path>
            </svg>
            <span class="text-sm">(Esc)</span>
        </div>

        <!-- Add margin if you want to see some of the overlay behind the modal-->
        <div class="modal-content py-6 text-center px-6"> 
            <!--Body-->
            <slot></slot>

            <!--Footer-->
            <div class="flex justify-end pt-2">
                <button @click="closeModal" class="modal-close px-4 bg-red-500 p-3 rounded-lg text-white hover:bg-red-400">Close</button>
            </div>
            
        </div>
        </div>
    </div>
</template>
<script>
export default {
    name: 'ModalOverlay',
    data () {
        return {
            showModal: false
        }
    },
    props: {
        modalStatus: {
            required: true,
            type: Boolean
        }
    },
    watch: {
        modalStatus (n, o) {
            if (n == true) {
                // alert('A for apple')
                this.showModal = true
            }
            else {
                // alert('B for ball formerly ' + this.showModal)
                
                this.showModal = false

                // alert('B for ball now ' + this.showModal)
            }
        }
    },
    methods: {
        closeModal () {
            this.$emit('modalClosed')
        }
    },
}
</script>
<style scoped>
    .modal {
        z-index: 90900999;
        overflow-y: scroll;
    }
    .modal-container {
        max-width: 80%;
        max-height: calc(100vh - 10px);
    }
    img {
        max-height: 400px;
    }
</style>