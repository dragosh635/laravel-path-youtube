<template>
    <div>
        <div class="media my-3" v-for="reply in replies.data">
            <a href="#" class="mr-3">
                <avatar :size="30" :username="reply.user.name" class="mr-2"></avatar>
            </a>

            <div class="media-body">
                <h6 class="mt-0">{{ reply.user ? reply.user.name : 'No Name' }}</h6>
                <small>{{ reply.body }}</small>
                <votes :entity_owner="reply.user.id" :entity_id="reply.id" :default_votes="reply.votes"></votes>
            </div>
        </div>

        <div class="text-center" v-if="comment.repliesCount > 0 && replies.next_page_url">
            <button @click="fetchReplies" class="btn btn-info btn-sm">Load Replies</button>
        </div>


    </div>
</template>

<script>
import Avatar from 'vue-avatar';

export default {
    name: "Replies",
    props: ['comment'],
    components: {
        Avatar
    },

    data() {
        return {
            replies: {
                data: [],
                next_page_url: `${APP_URL}/comments/${this.comment.id}/replies`
            }
        }
    },
    methods: {
        /**
         * Get all the replies for a comment
         */
        fetchReplies() {
            axios.get( this.replies.next_page_url )
                 .then( ( {data} ) => {
                     this.replies = {
                         ...data,
                         data: [
                             ...this.replies.data,
                             ...data.data
                         ]
                     };
                 } )
        },
        /**
         * Save a reply in the db
         *
         * @param reply
         */
        addReply( reply ) {
            this.replies = {
                ...this.replies,
                data: [
                    reply,
                    ...this.replies.data
                ]
            }
        }
    }
}
</script>
