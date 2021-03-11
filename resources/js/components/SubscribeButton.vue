<template>
    <button v-on:click="toggleSubscription" class="btn btn-danger btn-subscribed">
        <p v-if="owner">{{ count }} Subscribers</p>
        <p v-else-if="subscribed">Unsubscribe {{ count }} </p>
        <p v-else>Subscribe {{ count }} </p>
    </button>
</template>

<script>
/**
 * Numer vue library
 */
import numeral from 'numeral';

export default {
    props: {
        channel: {
            type: Object,
            require: true,
            default: () => {
                {}
            }
        },
        initialSubscriptions: {
            type: Array,
            required: true,
            default: () => []
        }
    },
    data: function () {
        return {
            subscriptions: this.initialSubscriptions
        }
    },
    computed: {
        /**
         * Return if the user is subscribed to a channel
         *
         * @returns {boolean}
         */
        subscribed() {

            if ( !__auth() || this.channel.user_id === __auth().id ) {
                return false;
            }

            return !!this.subscription;
        },
        /**
         * Return if the user is the owner of a channel
         *
         * @returns {boolean}
         */
        owner() {
            return __auth() && this.channel.user_id === __auth().id;
        },
        /**
         * Count the number of subscriptions
         *
         * @returns {*}
         */
        count() {
            return numeral( this.subscriptions.length ).format( '0a' );
        },
        /**
         * Check if the authenticated user has a subscription
         *
         * @returns {null|*}
         */
        subscription() {
            if ( !__auth() ) {
                return null;
            }
            return this.subscriptions.find( subscription => subscription.user_id === __auth().id );
        }
    },
    methods: {
        /**
         * Subscribe / unsubscribe
         */
        toggleSubscription() {
            if ( !__auth() ) {
                return alert( 'Please login to subscribe' );
            }

            if ( this.owner ) {
                return alert( 'You cannot subscribe to your channel' );
            }

            if ( this.subscribed ) {
                // Unsubscribe - delete subscription
                axios.delete( `${APP_URL}/channels/${this.channel.id}/subscriptions/${this.subscription.id}` )
                     .then( () => {
                         this.subscriptions = this.subscriptions.filter( subscription => subscription.id !== this.subscription.id )
                     } )
            } else {
                // Subscribe - create subscription
                axios.post( `${APP_URL}/channels/${this.channel.id}/subscriptions` )
                     .then( response => {
                         this.subscriptions = [
                             ...this.subscriptions,
                             response.data
                         ]
                     } )
            }
        }
    }
}
</script>
