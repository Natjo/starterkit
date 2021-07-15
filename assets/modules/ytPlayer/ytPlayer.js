function ytPlayer(cta, events) {
    const ready = () => {
        let player;
        const videoId = cta.dataset.videoId;

        const onPlayerStateChange = (e) => {
            if (e.data == YT.PlayerState.ENDED) {
                events.onPlayerStateChange();
            }
        };
        const onPlayerReady = (e) => {
            this.play = () => {
                player.seekTo(0);
            }
            events.onPlayerReady();
        };
        player = new YT.Player(`player-${videoId}`, {
            videoId: videoId,
            playerVars: {
                mute: 1,
                autoplay: 0,
                controls: 1,
                autohide: 1,
                showinfo: 0,
                rel: 0,
            },
            events: {
                onReady: onPlayerReady,
                onStateChange: onPlayerStateChange,
            },
        });
    };

    if (!document.getElementById("yt_api")) {
        let tag = document.createElement("script");
        tag.src = "https://www.youtube.com/iframe_api";
        tag.id = "yt_api";
        tag.setAttribute("defer", "");
        const firstScriptTag = document.getElementsByTagName("script")[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
        window.onYouTubeIframeAPIReady = () => {
            ready();
        };
    } else {
        ready();
    }
}
export default ytPlayer;
