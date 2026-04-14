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
		const TOP_WIDTH = 163.79;
		const BOTTOM_WIDTH = 188.39;
		const TOP_RATIO = TOP_WIDTH / BOTTOM_WIDTH;

		let bottomWidth;

		if (isMobile()) {
			bottomWidth = Math.min(vw * 0.78, 460);
		} else {
			bottomWidth = Math.min(vw * 0.64, 930);
		}

		logoWrap.style.setProperty('--logo-bottom-width', `${bottomWidth}px`);
		logoWrap.style.setProperty('--logo-top-width-ratio', `${TOP_RATIO}`);
	}

	function setLogoAndVideoPlacement() {
		setInitialLogoSize();

		// Force layout so measurements reflect the current responsive widths
		void logoWrap.offsetWidth;
		void videoWrap.offsetWidth;
		void topLogo.offsetWidth;
		void bottomLogo.offsetWidth;

		const mobile = isMobile();
		const gap = mobile ? 14 : 20;

		const videoRect = videoWrap.getBoundingClientRect();
		const logoWrapRect = logoWrap.getBoundingClientRect();
		const topRect = topLogo.getBoundingClientRect();
		const bottomRect = bottomLogo.getBoundingClientRect();

		// Place top half above the video with a fixed gap.
		// We store Y values relative to the logoWrap top because the logo halves are absolutely positioned inside it.
		const topY = (videoRect.top - gap - topRect.height) - logoWrapRect.top;

		// Closed state: bottom half tucked directly under the top half, forming the complete logo.
		const bottomClosedY = topY + topRect.height;

		// Open state: bottom half moves fully below the video, with the same gap.
		const bottomOpenY = (videoRect.bottom + gap) - logoWrapRect.top;

		logoWrap.style.setProperty('--logo-top-y', `${topY}px`);
		logoWrap.style.setProperty('--bottom-closed-y', `${bottomClosedY}px`);
		logoWrap.style.setProperty('--bottom-open-y', `${bottomOpenY}px`);
	}

	function setTargetTransformVars() {
		const navLogo = document.querySelector('.custom-logo-link img');
		const introRect = logoWrap.getBoundingClientRect();

		const introCenterX = introRect.left + (introRect.width / 2);
		const introCenterY = introRect.top + (introRect.height / 2);

		let targetCenterX;
		let targetCenterY;
		let scale;

		if (navLogo) {
			const navRect = navLogo.getBoundingClientRect();
			targetCenterX = navRect.left + (navRect.width / 2);
			targetCenterY = navRect.top + (navRect.height / 2);
			scale = navRect.width / introRect.width;
		} else {
			const target = getTargetMetrics();
			targetCenterX = window.innerWidth / 2;
			targetCenterY = target.topOffset + (target.height / 2);
			scale = target.width / introRect.width;
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

		setTimeout(() => {
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