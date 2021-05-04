<template>
    <div>
        <div class="text-center pb-3">
            <p class="text-2xl font-bold"> {{ post.reference }} </p>
        </div> 
        <div class="mx-auto lg:mx-0 w-full pt-3 border-b-2 border-green-500 opacity-25"></div>

        <div v-if="showUpdateAlert" class="px-4 py-3 leading-normal text-green-100 bg-green-700 rounded-lg" role="alert">
            Update was successful!
        </div>

        <div class="flex items-center h-auto flex-wrap" >

            <!--Main Col-->
            <div id="profile" class="w-3/5 rounded-lg lg:rounded-l-lg lg:rounded-r-none shadow-2xl bg-white">
            
                <div class="p-4 md:p-12 text-center lg:text-left">
                        
                    <p class="pt-4 text-xs font-bold flex items-center justify-start">
                        <span class="h-4 fill-current text-green-700 pr-4 pt-1"><i class="fa fa-object-group"></i> Page Groups</span>
                        <span class="text-sm">{{ post.page_group }}</span> 
                    </p>
                    <p class="pt-4 text-xs font-bold flex items-center justify-start">
                        <span class="h-4 fill-current text-green-700 pr-4 pt-1"><i class="fa fa-calendar"></i> Scheduled Date </span>
                        <span class="text-sm">{{ post.date }}</span> 
                    </p>
                    <p class="pt-4 text-xs font-bold flex items-center justify-start">
                        <span class="h-4 fill-current text-green-700 pr-4 pt-1"><i class="fa fa-clock-o"></i> Scheduled Time </span>
                        <span class="text-sm">{{ post.time }}</span> 
                    </p>
                    <p class="pt-4 text-xs font-bold flex items-center justify-start">
                        <span class="h-4 fill-current text-green-700 pr-4 pt-1"><i class="fa fa-book-reader"></i> Post Text</span>
                    </p>
                    <p class="pt-3 text-sm text-left"> {{ post.text }} </p>

                    <div class="pt-12 pb-8">
                        <button @click="editDraft" class="bg-green-700 hover:bg-green-900 text-white font-bold py-2 px-4 rounded-full">
                            Edit Draft
                        </button> 
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
export default {
    name: 'ViewPost',
    props: {
        post: {
            required: true,
            type: Object
        },
        setUpdateAlert: {
            type: Array,
            default: false
        }
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
        editDraft () {
            this.$emit('switchToEditMode', this.post)
        },
    }
}
</script>