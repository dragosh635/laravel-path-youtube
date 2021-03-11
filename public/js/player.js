var player = videojs( 'video' );
var viewLogged = false;

player.on( 'timeupdate', function () {
    var percentagePlayed = Math.ceil( player.currentTime() / player.duration() * 100 );

    if ( percentagePlayed > 5 && !viewLogged ) {

        axios.put( APP_URL + '/videos/' + window.CURRENT_VIDEO );

        viewLogged = true;
    }

} )
