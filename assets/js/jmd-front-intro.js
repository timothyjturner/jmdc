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
	const logoWrap = intro ? intro.querySelector('.jmd-intro__logo-wrap') : null;
	const topLogo = intro ? intro.querySelector('.jmd-intro__logo--top') : null;
	const bottomLogo = intro ? intro.querySelector('.jmd-intro__logo--bottom') : null;
	const videoWrap = intro ? intro.querySelector('.jmd-intro__video-wrap') : null;

	if (!intro || !video || !closeBtn || !logoWrap || !topLogo || !bottomLogo || !videoWrap) return;

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

	function setInitialLogoSize() {
		const vw = window.innerWidth;
		const vh = window.innerHeight;
		const TOP_WIDTH = 163.79;
		const BOTTOM_WIDTH = 188.39;
		const TOP_RATIO = TOP_WIDTH / BOTTOM_WIDTH;

		let bottomWidth;
		let introTop;
		let closedGap;
		let openGap;

		if (isMobile()) {
			bottomWidth = Math.min(vw * 0.72, 420);
			introTop = Math.round(vh * 0.16);
			closedGap = 0;
			openGap = 12;
		} else {
			bottomWidth = Math.min(vw * 0.56, 820);
			introTop = Math.round(vh * 0.18);
			closedGap = 0;
			openGap = 18;
		}

		logoWrap.style.setProperty('--logo-bottom-width', `${bottomWidth}px`);
		logoWrap.style.setProperty('--logo-top-width-ratio', `${TOP_RATIO}`);
		logoWrap.style.setProperty('--intro-top', `${introTop}px`);

		return { closedGap, openGap };
	}

	function setLogoAndVideoPlacement() {
		const { closedGap, openGap } = setInitialLogoSize();

		void logoWrap.offsetWidth;
		void videoWrap.offsetWidth;
		void topLogo.offsetWidth;
		void bottomLogo.offsetWidth;

		const videoRect = videoWrap.getBoundingClientRect();
		const wrapRect = logoWrap.getBoundingClientRect();
		const topRect = topLogo.getBoundingClientRect();
		const bottomRect = bottomLogo.getBoundingClientRect();

		// Closed state should look like the full logo near the top, matching the mockup.
		const topY = 0;
		const bottomClosedY = topRect.height + closedGap;

		// Open state should be strictly:
		// TOP HALF
		// VIDEO
		// BOTTOM HALF
		// with no overlap
		const bottomOpenY = (videoRect.bottom - wrapRect.top) + openGap;

		// Wrapper height must include the whole open composition so header targeting works.
		const wrapHeight = bottomOpenY + bottomRect.height;

		logoWrap.style.setProperty('--logo-top-y', `${topY}px`);
		logoWrap.style.setProperty('--bottom-closed-y', `${bottomClosedY}px`);
		logoWrap.style.setProperty('--bottom-open-y', `${bottomOpenY}px`);
		logoWrap.style.setProperty('--logo-wrap-height', `${Math.ceil(wrapHeight)}px`);
	}

	function setTargetTransformVars() {
		const navLogo = document.querySelector('.custom-logo-link img');
		const wrapRect = logoWrap.getBoundingClientRect();

		const introCenterX = wrapRect.left + (wrapRect.width / 2);
		const introCenterY = wrapRect.top + (wrapRect.height / 2);

		let targetCenterX;
		let targetCenterY;
		let scale;

		if (navLogo) {
			const navRect = navLogo.getBoundingClientRect();
			targetCenterX = navRect.left + (navRect.width / 2);
			targetCenterY = navRect.top + (navRect.height / 2);
			scale = navRect.width / wrapRect.width;
		} else {
			const target = getTargetMetrics();
			targetCenterX = window.innerWidth / 2;
			targetCenterY = target.topOffset + (target.height / 2);
			scale = target.width / wrapRect.width;
		}

		const deltaX = targetCenterX - introCenterX;
		const deltaY = targetCenterY - introCenterY;

		logoWrap.style.setProperty('--target-x', `${deltaX}px`);
		logoWrap.style.setProperty('--target-y', `${deltaY}px`);
		logoWrap.style.setProperty('--target-scale', `${scale}`);
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

		// Let bottom half travel back up first, then calculate target on the closed composition
		setTimeout(() => {
			setLogoAndVideoPlacement();
			setTargetTransformVars();
			intro.classList.add('is-fading-out');
			intro.classList.add('is-transitioning-out');
		}, 900);

		setTimeout(() => {
			finalizeIntro();
		}, 1800);
	}

	function handleScrollClose() {
		if (!state.canClose || !state.hasOpened || state.isClosing || state.isFinished) return;

		const scrollTop = window.pageYOffset || document.documentElement.scrollTop || 0;
		if (scrollTop > 8) {
			closeIntro();
		}
	}

	function openIntro() {
		setLogoAndVideoPlacement();
		setTargetTransformVars();
		lockScroll();

		intro.classList.remove('is-open', 'is-closing', 'is-fading-out', 'is-transitioning-out', 'is-hidden');

		void intro.offsetWidth;
		void logoWrap.offsetWidth;

		video.muted = !!jmdFrontIntro.muted;

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
		}, 1300);
	}

	closeBtn.addEventListener('click', function () {
		closeIntro();
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
		setLogoAndVideoPlacement();
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