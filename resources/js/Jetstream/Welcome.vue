<template>
    <div>
        <div class="py-6 px-2 md:px-20 bg-white border-b border-gray-200">

            <div class="mt-8 text-2xl flex flex-col w-full items-center">
                <form @submit.prevent="submit" class="px-4 w-full md:w-2/3">
                    <textarea class="w-full rounded-lg" v-model="form.post" placeholder="what&apos;s on your mind..."></textarea>
                    <input ref="post_upload"  accept="image/*" @change="photoPreview" type="file" class="hidden" />
                    <div class="flex justify-between">
                        <!-- post text button -->
                        <button class="ml-4 inline-flex items-center px-4 py-2 bg-pink-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-pink-700 active:bg-pink-900 focus:outline-none focus:border-pink-900 focus:shadow-outline-blue transition ease-in-out duration-150"  @click="makePost">
                            <svg xmlns="http://www.w3.org/2000/svg" class="fill-current w-4 h-4" viewBox="0 0 24 24"><path d="M24 0l-6 22-8.129-7.239 7.802-8.234-10.458 7.227-7.215-1.754 24-12zm-15 16.668v7.332l3.258-4.431-3.258-2.901z"/></svg>
                            
                        </button>
                        <!-- photo button -->
                        <button class="inline-flex items-center px-4 py-2 bg-purple-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-pink-700 active:bg-purple-900 focus:outline-none focus:border-indigo-600 focus:shadow-outline-gray transition ease-in-out duration-150" @click="openModal">
                            <svg xmlns="http://www.w3.org/2000/svg" class="fill-current w-4 h-4" viewBox="0 0 24 24"><path d="M18 5l-2-3h-8l-2 3h-6v17h24v-17h-6zm4 7h-4.079c.581 3.754-2.312 7-5.921 7-3.612 0-6.501-3.248-5.921-7h-4.079v-5h5.07l2-3h5.859l2 3h5.071v5zm-10-3c-2.243 0-4 1.73-4 3.939 0 2.239 1.794 4.061 4 4.061s4-1.822 4-4.061c0-2.209-1.757-3.939-4-3.939zm-.436 3.555c-.632.503-1.461.5-1.852-.006-.39-.506-.194-1.324.438-1.827.632-.502 1.461-.499 1.851.007.391.505.195 1.323-.437 1.826z"/></svg>
                        </button>
                    </div>
                    <post-image-modal :show="showable">
                        <template #title>
                            upload photos
                        </template>
                        <template #content>
                            <div class="my-2 flex justify-center items-center" v-show="! preview" >
                                <svg xmlns="http://www.w3.org/2000/svg" class="fill-current text-blue-400 h-24 w-24" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" @click="openFile">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="my-2 flex justify-center items-center" v-show="preview">
                                <span class="block rounded-full w-48 h-48 cursor-pointer"
                                      :style="'background-size: cover; background-repeat: no-repeat; background-position: center center; background-image: url(\'' + preview + '\');'" @click="openFile">
                                </span>
                            </div>
                            <div>
                                <input class="w-full rounded-lg" placeholder="Add Comment" type="text" v-model="form.post">
                            </div>

                            <div class="pt-3 w-full flex justify-end ml-auto">
                                <image-post-button @click="setImageForPost" class="mx-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="fill-current w-4 h-4" viewBox="0 0 24 24"><path d="M24 0l-6 22-8.129-7.239 7.802-8.234-10.458 7.227-7.215-1.754 24-12zm-15 16.668v7.332l3.258-4.431-3.258-2.901z"/></svg>
                                </image-post-button>
                                <image-post-button @click="closeModal">
                                    cancel
                                </image-post-button>
                            </div>
                        </template>
                    </post-image-modal>
                </form>

                <Feeds />
            </div>
        </div>

        
    </div>
</template>

<script>
    import { defineComponent } from 'vue'
    import JetApplicationLogo from '@/Jetstream/ApplicationLogo'
    import PostImageModal from '@/Pages/Post/PostImage'
    import Feeds from '@/Pages/Post/Feeds.vue'
    import ImagePostButton from '@/Jetstream/Button'

    export default defineComponent({
        components: {
            JetApplicationLogo,
            PostImageModal,
            Feeds,
            ImagePostButton
        },

        data() {
            return{
                showable :  false,

                preview : null,

                form:{
                    post: '',
                    post_image : '',
                },
            }
        },

        methods :{
            makePost(){

                if(this.form.post.length > 4 || this.form.post_image){
                    this.$inertia.post('/posts/store',this.form);
                    this.form.post = '';
                    this.form.post_image = null;
                    this.preview = null;
                    if(this.showable == true){
                        this.showable = false
                    }
                }
            },

            setImageForPost(){
                
                if (this.$refs.post_upload) {

                    this.form.post_image = this.$refs.post_upload.files[0]; 

                    this.makePost();
                }

            },

            photoPreview() {
                const reader = new FileReader();

                reader.onload = (e) => {
                    this.preview = e.target.result;
                };

                reader.readAsDataURL(this.$refs.post_upload.files[0]);
            },

            openModal(){
                this.showable = true;
            },

            closeModal(){
                this.showable = false;
            },

            openFile(){
                
                this.$refs.post_upload.click()
            },
        }
    })
</script>
