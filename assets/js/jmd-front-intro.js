document.addEventListener('DOMContentLoaded', function () {
	if (typeof jmdFrontIntro === 'undefined') return;

	const intros = document.querySelectorAll('#jmd-intro');
	if (intros.length > 1) {
		intros.forEach((node, index) => {
			if (index > 0) node.remove();
		});
	}

	const intro = document.getElementById('jmd-intro');
	const video = document.getElementById('jmd-intro-video');
	const closeBtn = intro ? intro.querySelector('.jmd-intro__close') : null;
	const stage = intro ? intro.querySelector('.jmd-intro__stage') : null;
	const topLogo = intro ? intro.querySelector('.jmd-intro__logo--top') : null;
	const bottomLogo = intro ? intro.querySelector('.jmd-intro__logo--bottom') : null;
	const videoWrap = intro ? intro.querySelector('.jmd-intro__video-wrap') : null;
	const soundToggle = intro ? intro.querySelector('.jmd-intro__sound-toggle') : null;

	if (!intro || !video || !closeBtn || !stage || !topLogo || !bottomLogo || !videoWrap || !soundToggle) return;

	const state = {
		isClosing: false,
		isFinished: false,
		hasOpened: false,
		canClose: false
	};

	const MOBILE_BREAKPOINT = Number(jmdFrontIntro.mobileBreakpoint || 576);
	const END_THRESHOLD_SECONDS = Number(jmdFrontIntro.endThresholdSeconds || 0.75);

	function isMobile() {
		return window.innerWidth < MOBILE_BREAKPOINT;
	}

	function getTargetMetrics() {
		const cfg = isMobile() ? jmdFrontIntro.mobile : jmdFrontIntro.desktop;
		return {
			width: Number(cfg.logoWidth),
			height: Number(cfg.logoHeight),
			topOffset: Number(cfg.topOffset)
		};
	}

	function setLayoutVars() {
		const vw = window.innerWidth;
		const vh = window.innerHeight;
		const TOP_WIDTH = 163.79;
		const BOTTOM_WIDTH = 188.39;
		const TOP_RATIO = TOP_WIDTH / BOTTOM_WIDTH;

		let bottomWidth;
		let videoHeight;
		let videoGap;
		let stageTop;

		if (isMobile()) {
			bottomWidth = Math.min(vw * 0.72, 420);
			videoHeight = Math.round(bottomWidth / 1.18);
			videoGap = 12;
		} else {
			bottomWidth = Math.min(vw * 0.56, 820);
			videoHeight = Math.round(bottomWidth / 1.62);
			videoGap = 18;
		}

		stage.style.setProperty('--logo-bottom-width', `${Math.round(bottomWidth)}px`);
		stage.style.setProperty('--logo-top-width-ratio', `${TOP_RATIO}`);
		stage.style.setProperty('--video-width', `${Math.round(bottomWidth)}px`);
		stage.style.setProperty('--video-height', `${Math.round(videoHeight)}px`);
		stage.style.setProperty('--video-gap', `${videoGap}px`);

		// Let widths apply, then measure closed logo height
		void stage.offsetWidth;
		void topLogo.offsetWidth;
		void bottomLogo.offsetWidth;

		const topRect = topLogo.getBoundingClientRect();

		/*
			Place the stage so that when OPEN:
			- the video is vertically centered
			- the top logo remains above it
			- the closed logo starts lower, closer to just below the header
		*/
		stageTop = Math.round((vh / 2) - (topRect.height + videoGap + (videoHeight / 2)));

		if (isMobile()) {
			stageTop += 6;
		} else {
			stageTop += 32;
		}

		stage.style.setProperty('--stage-top', `${stageTop}px`);

		return {};
	}

	function setTargetTransformVars() {
		const navLogo = document.querySelector('.custom-logo-link img');
		const stageRect = stage.getBoundingClientRect();

		// At close time, video row is collapsed, so stage bounds equal the closed logo bounds
		const introCenterX = stageRect.left + (stageRect.width / 2);
		const introCenterY = stageRect.top + (stageRect.height / 2);

		let targetCenterX;
		let targetCenterY;
		let scale;

		if (navLogo) {
			const navRect = navLogo.getBoundingClientRect();
			targetCenterX = navRect.left + (navRect.width / 2);
			targetCenterY = navRect.top + (navRect.height / 2);
			scale = navRect.width / stageRect.width;
		} else {
			const target = getTargetMetrics();
			targetCenterX = window.innerWidth / 2;
			targetCenterY = target.topOffset + (target.height / 2);
			scale = target.width / stageRect.width;
		}

		const deltaX = targetCenterX - introCenterX;
		const deltaY = targetCenterY - introCenterY;

		stage.style.setProperty('--target-x', `${Math.round(deltaX)}px`);
		stage.style.setProperty('--target-y', `${Math.round(deltaY)}px`);
		stage.style.setProperty('--target-scale', `${scale}`);
	}

	function lockScroll() {
		document.documentElement.classList.add('jmd-intro-lock');
		document.body.classList.add('jmd-intro-lock');
	}

	function unlockScroll() {
		document.documentElement.classList.remove('jmd-intro-lock');
		document.body.classList.remove('jmd-intro-lock');
	}

	function finalizeIntro() {
		if (state.isFinished) return;

		state.isFinished = true;
		unlockScroll();
		intro.classList.add('is-hidden');
		document.body.classList.remove('jmd-intro-transitioning');

		setTimeout(() => {
			if (intro && intro.parentNode) {
				intro.parentNode.removeChild(intro);
			}
		}, 700);
	}

	function closeIntro() {
		if (state.isClosing || state.isFinished) return;

		state.isClosing = true;
		intro.classList.remove('is-open');
		intro.classList.add('is-closing');

		try {
			video.pause();
		} catch (e) {}

		// Wait for the video row to collapse so stage bounds equal the closed logo
		setTimeout(() => {
			setLayoutVars();
			setTargetTransformVars();
			intro.classList.add('is-fading-out');
			intro.classList.add('is-transitioning-out');
			document.body.classList.add('jmd-intro-transitioning');
		}, 950);

		setTimeout(() => {
			finalizeIntro();
		}, 1850);
	}

	function handleScrollClose() {
		if (!state.canClose || !state.hasOpened || state.isClosing || state.isFinished) return;

		const scrollTop = window.pageYOffset || document.documentElement.scrollTop || 0;
		if (scrollTop > 8) {
			closeIntro();
		}
	}

	function openIntro() {
		setLayoutVars();
		setTargetTransformVars();
		lockScroll();

		intro.classList.remove('is-open', 'is-closing', 'is-fading-out', 'is-transitioning-out', 'is-hidden');

		void intro.offsetWidth;
		void stage.offsetWidth;

		video.muted = !!jmdFrontIntro.muted;
		updateSoundButton();

		try {
			video.pause();
			video.currentTime = 0;
		} catch (e) {}

		const startPlayback = () => {
			const playPromise = video.play();
			if (playPromise && typeof playPromise.catch === 'function') {
				playPromise.catch(() => {});
			}
		};

		if (video.readyState >= 1) {
			startPlayback();
		} else {
			video.addEventListener('loadedmetadata', startPlayback, { once: true });
		}

		setTimeout(() => {
			intro.classList.add('is-open');
			state.hasOpened = true;
		}, 450);

		setTimeout(() => {
			state.canClose = true;
		}, 1350);
	}

	closeBtn.addEventListener('click', function () {
		closeIntro();
	});

	function updateSoundButton() {
		if (video.muted) {
			soundToggle.classList.add('is-muted');
			soundToggle.classList.remove('is-unmuted');
			soundToggle.setAttribute('aria-label', 'Unmute video');
			soundToggle.setAttribute('aria-pressed', 'false');
		} else {
			soundToggle.classList.remove('is-muted');
			soundToggle.classList.add('is-unmuted');
			soundToggle.setAttribute('aria-label', 'Mute video');
			soundToggle.setAttribute('aria-pressed', 'true');
		}
	}

	soundToggle.addEventListener('click', function () {
		video.muted = !video.muted;

		if (!video.muted && video.paused) {
			const playPromise = video.play();
			if (playPromise && typeof playPromise.catch === 'function') {
				playPromise.catch(() => {});
			}
		}

		updateSoundButton();
	});

	video.addEventListener('timeupdate', function () {
		if (!state.canClose) return;
		if (!isFinite(video.duration) || video.duration <= 0) return;

		const remaining = video.duration - video.currentTime;
		if (remaining <= END_THRESHOLD_SECONDS) {
			closeIntro();
		}
	});

	video.addEventListener('ended', function () {
		if (!state.canClose) return;
		closeIntro();
	});

	window.addEventListener('resize', function () {
		if (state.isFinished) return;
		setLayoutVars();
		setTargetTransformVars();
	});

	window.addEventListener('wheel', function (e) {
		if (!state.canClose || state.isFinished || state.isClosing) return;
		if (Math.abs(e.deltaY) > 4) {
			closeIntro();
		}
	}, { passive: true });

	window.addEventListener('touchmove', function () {
		if (!state.canClose || state.isFinished || state.isClosing) return;
		closeIntro();
	}, { passive: true });

	window.addEventListener('scroll', handleScrollClose, { passive: true });

	openIntro();
});