import $ from 'jquery';

let iFrame, bgVideo, loaderStep0, loaderStep1, loaderStep2, loaderStep3, resetIframeLoader, playerWrapper = ''

if ( virtualTourData.xml !== undefined ) {
	document.addEventListener("load", initPano2vr() );
}

if ( $('#video-background').length ) {
	bgVideo = document.getElementById('video-background');
	bgVideo.volume = 0.0;
	bgVideo.autoplay = true;
	console.log(bgVideo.volume)
	bgVideo.addEventListener('loadeddata', function(e) {
		$('#loaderStep1').addClass('pano-loaded')
		$('#loaderStep1 .loader-wrapper').append($('#loaderStep0 .loader-container')).fadeIn(200)
		$('#loaderStep0').hide()
		// bgVideo.play();
	}, true);
}

function initPano2vr() {
    // create the panorama player with the container
    let pano=new pano2vrPlayer("container");
    // load the configuration
    pano.on("imagesready", function() {
        $('.ggskin_image').fadeOut(200)
        setTimeout( function () {
            $('#loaderStep1').addClass('pano-loaded')
            $('#loaderStep1 .loader-wrapper').append($('#loaderStep0 .loader-container')).fadeIn(200)
            $('#loaderStep0').hide()
            // $('.ggskin_image').fadeIn(200)
        }, 200)
    });
    window.addEventListener("load", function() {
        pano.readConfigUrlAsync(virtualTourData.xml);
    });
    $('div.ggskin_image').append($('.loader-container') )
    if (window.navigator.userAgent.match(/Safari/i)) {
        // fix for white borders, rotation on iPhone
        function iosHfix(e) {
            window.scrollTo(0, 1);
            var container=document.getElementById("container");
            var oh=container.offsetHeight;
            document.documentElement.style.setProperty('height', '100vh');
            if (oh!=container.offsetHeight) {
                container.style.setProperty('height',"100%");
                container.style.setProperty('top', 0);
            } else {
                container.style.setProperty('height',window.innerHeight+"px");
                let topOffset = $(container).offset().top * -1
                if ( topOffset > 0 ) {
                    container.style.setProperty('top', topOffset + 'px');
                }
                if ( topOffset < 0 ) {
                    container.style.setProperty('top', 0);
                }
            }
            window.scrollTo(0, 0);
            pano.setViewerSize(container.offsetWidth, container.offsetHeight);
        };
        setTimeout(iosHfix,0);
        setTimeout(iosHfix,100);
        window.addEventListener("resize", function() {
            setTimeout(iosHfix,0);
            // hide toolbar on iPad happens with a delay
            setTimeout(iosHfix,500);
            setTimeout(iosHfix,1000);
            setTimeout(iosHfix,2000);
        });
    }
}

$("body").on('click', function(){
    $('#iframe_1').trigger('focus');
});

let isReloadingIframe = false;

const iframes = {
	desktop: virtualTourData.virtual_tour_desktop,
    mobile: virtualTourData.virtual_tour_mobile,
}

window.addEventListener('load', function () {
	// Set element vars
	playerWrapper = document.getElementsByTagName('body')[0]
	loaderStep0 = document.getElementById("loaderStep0")
	loaderStep1 = document.getElementById("loaderStep1")
	loaderStep2 = document.getElementById("loaderStep2")
	loaderStep3 = document.getElementById("loaderStep3")
	resetIframeLoader = document.getElementById("resetIframeLoader")
	iFrame = document.getElementById("iframe_1");

	const playButton = document.getElementById('play-button')

	playButton.addEventListener('click', function () {
		$(loaderStep2).fadeOut(1000)
		hideInstructions()
	})

	const helpButton = document.getElementById('help-button')

	helpButton.addEventListener('click', function (e) {
		e.preventDefault();
		showInstructions(true);
	})

	checkScreenSize('load');
})

const messageHandler = (event) => {
	if(!event.data.type) return;

	console.log('event', event)
	console.log("received data event type " + event.data.type)
	switch (event.data.type) {
		case "ResponseFromUE4":
			console.log("UE4->iframe: " + event.data.descriptor)
			myHandleResponseFunction(event.data.descriptor);
			break;
		case "stage1_inqueued":
			// If switching iframe src, don't show loaders again
			if ( isReloadingIframe ) return
			loaderStep1.style.visibility = "visible";
			// Show the header
			// playerWrapper.classList.add('playing');
			break;
		case "stage2_deQueued":
			// loading screen 1 hides
			break;
		case "stage3_slotOccupied":
			// loaderStep1.style.display = "none";
			// loaderStep2.style.visibility = "visible";
			break;
		case "stage4_playBtnShowedUp":

			//loading screen 2 hides
			loaderStep2.style.visibility = "hidden";
			iFrame.style.visibility = "visible";

			// If switching iframe src, don't show loaders again
			if ( isReloadingIframe ) return

			loaderStep3.style.visibility = "visible";
			// let playButton = document.getElementById("playButtonParent");
			// playButton.click();
			// onPlayBtnPressed();
			break;
		case "stage5_playBtnPressed":
			// hide reset loader if visible
			if ( $(resetIframeLoader).is(':visible') ) {
				$(resetIframeLoader).fadeOut()
			}
			$(iFrame).focus();

			// If switching iframe src, don't show loaders again
			if ( isReloadingIframe ) return

			// Hide first loader
			console.log('bg video', bgVideo)
			if ( bgVideo ) {
				bgVideo.pause()
			}
			$(loaderStep1).fadeOut(1000)

			// Show the header
			// playerWrapper.classList.add('playing')

			showInstructions();
			// Show the play button
			setTimeout( function () {
				loaderStep2.classList.add('loaded')
				setTimeout( () => {
					$('#play-button-wrapper').css({ opacity: 1, 'pointer-events': 'all' })
				}, 1000)
			}, 800)

			// Show the virtual tour
			iFrame.style.visibility = "visible";

			break;
		case "_focus":
			$(iFrame).focus();
			hideInstructions()
			break;
		case "isIframe":
			let obj = {
				cmd: 'isIframe',
				value: true
			};
			sendToMainPage(obj);
			break;

		case "QueueNumberUpdated":
			console.log("QueueNumberUpdated. New queuePosition: : " +  event.data.queuePosition)
			break;

		case "stage3_1_AppAcquiringProgress":
			console.log("stage3_1_AppAcquiringProgress percent: " + JSON.stringify( event.data.percent))
			break;

		case "stage3_2_AppPreparationProgress":
			console.log("stage3_2_AppPreparationProgress percent:" + JSON.stringify( event.data.percent))
			break;
		case "shortCuts":
			console.log("Key pressed");
			break;
		default:
			console.error("Unhandled message data type");
			break;
	}
}

window.addEventListener('message', messageHandler);

window.addEventListener('message', (message) => {
	console.log('iframe message', message)
	if (message.data.type === '_focus') {
		$(iFrame).focus();
	}
})

window.addEventListener("resize", function() {
	checkScreenSize('resize')
})

const showInstructions = showClose => {
	const instructions = document.getElementById('instructions-container')
	instructions.classList.add('visible')

	const helpButton = document.getElementById('help-button')
	helpButton.style.display = 'none'
}

const hideInstructions = () => {
	const instructions = document.getElementById('instructions-container')
	instructions.classList.remove('visible')

	const helpButton = document.getElementById('help-button')
	helpButton.style.display = 'flex'
}

const checkScreenSize = referrer => {
	const windowWidth = $(window).width()
	const windowRatio = windowWidth / $(window).height()
	const videoRatio = 16/9
	if ( $(iFrame).length ) {
		if ( windowWidth > 1024 ) {
			if ( iFrame.src !== iframes.desktop ) {
				switchIframe('desktop', referrer)
			}
			if ( windowRatio <= videoRatio ) {
				$('body').addClass('portrait')
			} else {
				$('body').removeClass('portrait')
			}
		} else {
			if ( iFrame.src !== iframes.mobile ) {
				switchIframe('mobile', referrer)
			}
		}
		console.log()
	}
}

const switchIframe = (layout, referrer) => {
	if ( referrer === 'resize' ) {
		isReloadingIframe = true
		resetIframeLoader.style.display = "flex";
	}
	iFrame.src = iframes[layout]
}

const onPlayBtnPressed = () => {
	loaderStep2.style.visibility = "hidden";
	loaderStep3.style.visibility = "hidden";
	iFrame.style.visibility = "visible";
}

function sendToMainPage(obj) {
	let origin = "*"
	iFrame.contentWindow.postMessage(JSON.stringify(obj), origin);
}

function switchTo(val) {
	console.log("=== Registered switchTo action, Value is: ", val);

	let descriptor = {
		Teleport: val
	};
	//emitUIInteraction(descriptor);
	let obj ={
			cmd: "sendToUe4",
			value: descriptor,
	};
	sendToMainPage(obj)
}

let isFullScreen = false

function goToFullScreen() {
	var cmd = isFullScreen ? "Off" : "On";
	isFullScreen = !isFullScreen;
	console.log("=== Registered full screen action, Value is: ", cmd);
	let descriptor = {
		FullScreen: cmd
	};
	//emitUIInteraction(descriptor);
	let obj =
		{
			cmd: "sendToUe4",
			value: descriptor,
		}
	sendToMainPage(obj)
}

        		