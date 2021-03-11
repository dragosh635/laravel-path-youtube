<template>
    <div class="card mt-5 p-5">
        <div v-if="auth" class="form-inline my-4 w-full">
            <input v-model="newComment" type="text" class="form-control form-control-sm w-80"/>
            <button @click="addComment" class="btn btn-sm btn-primary">
                <small>Add comment</small>
            </button>
        </div>

        <comment v-for="comment in comments.data" :key="comment.id" :comment="comment" :video="video"></comment>

        <div v-if="comments.next_page_url" @click="fetchComments" class="text-center">
            <div class="btn btn-success">Load More</div>
        </div>
        <span v-else class="text-center">No more comments to show</span>
    </div>
</template>

<script>

import Comment from './Comment.vue';

export default {
    name: "Comments",
    props: {
        video: {
            required: true,
            default: () => ({})
        }
    },
    components: {
        Comment
    },
    mounted() {
        this.fetchComments();
    },
    computed: {
        auth() {
            return __auth();
        }
    },
    data: () => ({
        comments: {
            data: []
        },
        newComment: ''
    }),
    methods: {
        /**
         * Returns all the comments
         */
        fetchComments() {

            const url = this.comments.next_page_url ? this.comments.next_page_url : `${APP_URL}/videos/${this.video.id}/comments`;

            axios.get( url )
                 .then( ( {data} ) => {
                     this.comments = {
                         ...data,
                         data: [
                             ...this.comments.data,
                             ...data.data
                         ]
                     };
                 } )
        },
        /**
         * Add a new comment
         */
        addComment() {
            if ( !this.newComment ) {
                return;
            }

            axios.post( `${APP_URL}/comments/${this.video.id}`, {
                body: this.newComment
            } ).then( ( {data} ) => {
                this.comments = {
                    ...this.comments,
                    data: [
                        data,
                        ...this.comments.data
                    ]
                }
            } );
        }
    }

}
</script>
