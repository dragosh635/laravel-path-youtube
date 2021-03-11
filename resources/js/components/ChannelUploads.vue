<template>
    <div class="col-md-8">
        <div class="card p-3 d-flex justify-content-center align-items-center" v-if="!selected">
            <div onclick="document.getElementById('video-files').click()" class="btn btn-default btn-danger">Upload</div>
            <input multiple type="file" ref="videos" id="video-files" style="display: none" v-on:change="upload"/>
        </div>
        <div class="card p-3" v-else>
            <div class="my-4" v-for="video in videos">
                <div class="progress mb-3">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" :style="{width: `${video.percentage || progress[video.name]}%`}" aria-valuenow="50" aria-valuemin="0" ariavaluemax="100">
                        {{ getPercentageText( video.percentage ) }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <img v-if="video.thumbnail" :src="getVideoThumb( video.thumbnail )" style="width: 100%" alt=""/>
                        <div v-else class="d-flex justify-content-center align-items-center" style="height: 180px; color: white; font-size: 18px; background: #808080;">
                            Loading thumbnail ...
                        </div>

                    </div>
                    <div class="col-md-4">
                        <a v-if="video.percentage && video.percentage===100" target="_blank" :href="getVideoUrl( video.id )">
                            {{ video.title }}
                        </a>
                        <h4 v-else class="text-center">
                            {{ video.title || video.name }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "ChannelUploads",
    data: () => ({
        selected: false,
        videos: [],
        progress: {},
        uploads: [],
        intervals: {}
    }),
    props: {
        channel: {
            type: Object,
            required: true,
            default: () => ({})
        }
    },
    methods: {
        /**
         * Return the text shown to the user based on the processed percentage of the video
         *
         * @param {Number} percentage
         * @returns {string}
         */
        getPercentageText( percentage ) {
            let text = '';
            if ( percentage ) {
                text = (percentage === 100) ? 'Video Processing completed' : 'Processing';
            } else {
                text = 'Uploading';
            }

            return text;
        },
        /**
         * Return the backend route for a specific video
         * @param {String} videoId
         * @returns {string}
         */
        getVideoUrl( videoId ) {
            return `${APP_URL}/videos/${videoId}`;
        },
        /**
         * Return the video url where the thumbnail is located
         *
         * @param {String} thumb
         * @returns {string}
         */
        getVideoThumb( thumb ) {
            return APP_URL + thumb;
        },
        /**
         * Handles the video upload from the channel
         */
        upload() {
            this.selected = !this.selected;

            this.videos = Array.from( this.$refs.videos.files );

            /* Call to the upload videos route - can handle more than one video */
            const uploaders = this.videos.map( video => {
                const form = new FormData();

                this.progress[video.name] = 0;

                form.append( 'title', video.name );
                form.append( 'video', video );


                return axios.post( `${APP_URL}/channels/${this.channel.id}/videos`, form, {
                    onUploadProgress: ( event ) => {
                        this.progress[video.name] = Math.ceil( event.loaded / event.total * 100 );
                        this.$forceUpdate();
                    }
                } ).then( ( {data} ) => {
                    this.uploads = [
                        ...this.uploads,
                        data
                    ]
                } );
            } )

            /* After all the videos are uploaded, update the view accordingly */
            axios.all( uploaders )
                 .then( () => {
                     this.videos = this.uploads;

                     this.videos.forEach( video => {
                         this.intervals[video.id] = setInterval( () => {
                             /* Make a call every 3 seconds to see how much of the video is processed in order to update the progress bar */
                             axios.get( `${APP_URL}/videos/${video.id}` ).then( ( {data} ) => {

                                 if ( data.percentage === 100 ) {
                                     clearInterval( this.intervals[video.id] );
                                 }

                                 this.videos = this.videos.map( ( v ) => {
                                     return (v.id === data.id) ? data : v;
                                 } );
                             } )
                         }, 3000 );
                     } )
                 } )
        }
    }
}
</script>
