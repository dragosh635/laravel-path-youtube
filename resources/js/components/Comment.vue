<template>
    <div class="media my-3">
        <avatar :size="30" :username="comment.user.name" class="mr-2"></avatar>

        <div class="media-body">
            <h6 class="mt-0">{{comment.user.name}}</h6>
            <small>{{ comment.body }}</small>

            <div class="d-flex">
                <votes :entity_owner="comment.user.id" :entity_id="comment.id" :default_votes="comment.votes"></votes>
                <button @click="addingReply = !addingReply" class="btn btn-default btn-sm ml-3" :class="{'btn-default': !addingReply, 'btn-danger': addingReply}">
                    {{ addingReply ? 'Cancel' : 'Add Reply' }}
                </button>
            </div>

            <div v-if="addingReply" class="form-inline my-4 w-full">
                <input v-model="body" type="text" class="form-control form-control-sm w-80"/>
                <button @click="addReply" class="btn btn-sm btn-primary">
                    <small>Add Reply</small>
                </button>
            </div>

            <replies ref="replies" :comment="comment"></replies>
        </div>

    </div>
</template>

<script>
/* Avatar library for vue */
import Avatar from 'vue-avatar';
import Replies from './Replies.vue';

export default {
    name: "Comment",
    components: {
        Replies,
        Avatar,
    },
    data() {
        return {
            body: '',
            addingReply: false
        }
    },
    props: {
        comment: {
            required: true,
            default: () => ({})
        },
        video: {
            required: true,
            default: () => ({})
        }
    },
    methods: {
        /**
         * Add a reply for a comment
         */
        addReply() {
            if ( !this.body ) {
                return;
            }

            axios.post( `${APP_URL}/comments/${this.video.id}`, {
                comment_id: this.comment.id,
                body: this.body
            } ).then( ( {data} ) => {
                this.body = '';
                this.addingReply = false;
                this.$refs.replies.addReply( data );
            } );
        }
    }

}
</script>
