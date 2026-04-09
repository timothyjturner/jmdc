document.addEventListener('DOMContentLoaded', function () {
	console.log('[JMD Intro] DOMContentLoaded fired');

	if (typeof jmdFrontIntro === 'undefined') {
		console.log('[JMD Intro] FAIL: jmdFrontIntro is undefined');
		return;
	}

	console.log('[JMD Intro] jmdFrontIntro:', jmdFrontIntro);

	const intro = document.getElementById('jmd-intro');
	const video = document.getElementById('jmd-intro-video');
	const closeBtn = intro ? intro.querySelector('.jmd-intro__close') : null;
	const logoWrap = intro ? intro.querySelector('.jmd-intro__logo-wrap') : null;
	const topLogo = intro ? intro.querySelector('.jmd-intro__logo--top') : null;
	const bottomLogo = intro ? intro.querySelector('.jmd-intro__logo--bottom') : null;

	console.log('[JMD Intro] intro:', intro);
	console.log('[JMD Intro] video:', video);
	console.log('[JMD Intro] closeBtn:', closeBtn);
	console.log('[JMD Intro] logoWrap:', logoWrap);
	console.log('[JMD Intro] topLogo:', topLogo);
	console.log('[JMD Intro] bottomLogo:', bottomLogo);

	if (!intro || !video || !closeBtn || !logoWrap || !topLogo || !bottomLogo) {
		console.log('[JMD Intro] FAIL: Missing one or more required elements');
		return;
	}

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
		const metrics = {
			width: Number(cfg.logoWidth),
			height: Number(cfg.logoHeight),
			topOffset: Number(cfg.topOffset)
		};
		console.log('[JMD Intro] getTargetMetrics:', metrics);
		return metrics;
	}

	function setInitialLogoSize() {
		const vw = window.innerWidth;
		const vh = window.innerHeight;
		const mobile = vw < MOBILE_BREAKPOINT;

		const TOP_WIDTH = 163.79;
		const BOTTOM_WIDTH = 188.39;
		const TOP_RATIO = TOP_WIDTH / BOTTOM_WIDTH;

		let bottomWidth;

		if (mobile) {
			bottomWidth = Math.min(vw * 0.82, 320);
		} else {
			bottomWidth = Math.min(vw * 0.66, 980);
		}

		logoWrap.style.setProperty('--logo-bottom-width', `${bottomWidth}px`);
		logoWrap.style.setProperty('--logo-top-width-ratio', `${TOP_RATIO}`);

		const closedGap = mobile ? 7 : 10;
		logoWrap.style.setProperty('--intro-gap', `${closedGap}px`);

		const openDistance = vh * 0.5;
		logoWrap.style.setProperty('--open-distance', `${openDistance}px`);

		console.log('[JMD Intro] setInitialLogoSize:', {
			vw,
			vh,
			mobile,
			bottomWidth,
			topRatio: TOP_RATIO,
			closedGap,
			openDistance
		});
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

			console.log('[JMD Intro] Using nav logo for target:', {
				navRect,
				scale
			});
		} else {
			const target = getTargetMetrics();
			targetCenterX = window.innerWidth / 2;
			targetCenterY = target.topOffset + (target.height / 2);
			scale = target.width / introRect.width;

			console.log('[JMD Intro] Using fallback target metrics:', {
				target,
				scale
			});
		}

		const deltaX = targetCenterX - introCenterX;
		const deltaY = targetCenterY - introCenterY;

		logoWrap.style.setProperty('--target-x', `${deltaX}px`);
		logoWrap.style.setProperty('--target-y', `${deltaY}px`);
		logoWrap.style.setProperty('--target-scale', `${scale}`);

		console.log('[JMD Intro] setTargetTransformVars:', {
			introRect,
			introCenterX,
			introCenterY,
			targetCenterX,
			targetCenterY,
			deltaX,
			deltaY,
			scale
		});
	}

	function lockScroll() {
		document.documentElement.classList.add('jmd-intro-lock');
		document.body.classList.add('jmd-intro-lock');
		console.log('[JMD Intro] Scroll locked');
	}

	function unlockScroll() {
		document.documentElement.classList.remove('jmd-intro-lock');
		document.body.classList.remove('jmd-intro-lock');
		console.log('[JMD Intro] Scroll unlocked');
	}

	function finalizeIntro() {
		if (state.isFinished) {
			console.log('[JMD Intro] finalizeIntro skipped: already finished');
			return;
		}

		state.isFinished = true;
		unlockScroll();
		intro.classList.add('is-hidden');

		console.log('[JMD Intro] finalizeIntro: intro hidden');

		setTimeout(() => {
			if (intro && intro.parentNode) {
				intro.parentNode.removeChild(intro);
				console.log('[JMD Intro] intro removed from DOM');
			}
		}, 700);
	}

	function closeIntro(reason = 'unknown') {
		if (state.isClosing || state.isFinished) {
			console.log('[JMD Intro] closeIntro skipped:', {
				reason,
				isClosing: state.isClosing,
				isFinished: state.isFinished
			});
			return;
		}

		console.log('[JMD Intro] closeIntro fired:', reason, {
			currentTime: video.currentTime,
			duration: video.duration,
			readyState: video.readyState,
			paused: video.paused,
			scrollTop: window.pageYOffset || document.documentElement.scrollTop || 0,
			canClose: state.canClose,
			hasOpened: state.hasOpened,
			introClassName: intro.className
		});

		state.isClosing = true;

		intro.classList.remove('is-open');
		intro.classList.add('is-closing');

		try {
			video.pause();
			console.log('[JMD Intro] video paused during close');
		} catch (e) {
			console.log('[JMD Intro] video pause error:', e);
		}

		setTimeout(() => {
			setTargetTransformVars();
			intro.classList.add('is-fading-out');
			intro.classList.add('is-transitioning-out');
			console.log('[JMD Intro] close phase 2 classes added:', intro.className);
		}, 1000);

		setTimeout(() => {
			finalizeIntro();
		}, 1900);
	}

	function handleScrollClose() {
		const scrollTop = window.pageYOffset || document.documentElement.scrollTop || 0;
		console.log('[JMD Intro] handleScrollClose called:', {
			scrollTop,
			canClose: state.canClose,
			hasOpened: state.hasOpened,
			isClosing: state.isClosing,
			isFinished: state.isFinished
		});

		if (!state.canClose || !state.hasOpened || state.isClosing || state.isFinished) return;

		if (scrollTop > 8) {
			closeIntro('scroll position');
		}
	}

	function openIntro() {
		console.log('[JMD Intro] openIntro called');

		setInitialLogoSize();
		setTargetTransformVars();
		lockScroll();

		requestAnimationFrame(() => {
			console.log('[JMD Intro] requestAnimationFrame #1');
			requestAnimationFrame(() => {
				console.log('[JMD Intro] requestAnimationFrame #2');

				video.muted = !!jmdFrontIntro.muted;
				console.log('[JMD Intro] video.muted set to:', video.muted);

				try {
					video.pause();
				} catch (e) {
					console.log('[JMD Intro] video.pause before start error:', e);
				}

				try {
					video.currentTime = 0;
					console.log('[JMD Intro] video.currentTime reset to 0');
				} catch (e) {
					console.log('[JMD Intro] video.currentTime reset error:', e);
				}

				const startPlayback = () => {
					console.log('[JMD Intro] startPlayback called', {
						currentTime: video.currentTime,
						duration: video.duration,
						readyState: video.readyState,
						paused: video.paused
					});

					try {
						video.currentTime = 0;
					} catch (e) {
						console.log('[JMD Intro] startPlayback currentTime reset error:', e);
					}

					const playPromise = video.play();

					if (playPromise && typeof playPromise.then === 'function') {
						playPromise
							.then(() => {
								console.log('[JMD Intro] video.play resolved', {
									currentTime: video.currentTime,
									duration: video.duration,
									readyState: video.readyState,
									paused: video.paused
								});
							})
							.catch((err) => {
								console.log('[JMD Intro] video.play rejected:', err);
							});
					} else {
						console.log('[JMD Intro] video.play returned no promise');
					}
				};

				if (video.readyState >= 1) {
					console.log('[JMD Intro] video already has metadata, starting playback now');
					startPlayback();
				} else {
					console.log('[JMD Intro] waiting for loadedmetadata before playback');
					video.addEventListener('loadedmetadata', startPlayback, { once: true });
				}

				setTimeout(() => {
					intro.classList.add('is-open');
					state.hasOpened = true;
					console.log('[JMD Intro] is-open added', {
						className: intro.className
					});
				}, 350);

				setTimeout(() => {
					state.canClose = true;
					console.log('[JMD Intro] canClose enabled');
				}, 1200);
			});
		});
	}

	closeBtn.addEventListener('click', function () {
		console.log('[JMD Intro] close button clicked');
		closeIntro('close button');
	});

	video.addEventListener('loadedmetadata', function () {
		console.log('[JMD Intro] event: loadedmetadata', {
			currentTime: video.currentTime,
			duration: video.duration,
			readyState: video.readyState
		});
	});

	video.addEventListener('loadeddata', function () {
		console.log('[JMD Intro] event: loadeddata', {
			currentTime: video.currentTime,
			duration: video.duration,
			readyState: video.readyState
		});
	});

	video.addEventListener('canplay', function () {
		console.log('[JMD Intro] event: canplay', {
			currentTime: video.currentTime,
			duration: video.duration,
			readyState: video.readyState
		});
	});

	video.addEventListener('play', function () {
		console.log('[JMD Intro] event: play', {
			currentTime: video.currentTime,
			duration: video.duration
		});
	});

	video.addEventListener('playing', function () {
		console.log('[JMD Intro] event: playing', {
			currentTime: video.currentTime,
			duration: video.duration
		});
	});

	video.addEventListener('pause', function () {
		console.log('[JMD Intro] event: pause', {
			currentTime: video.currentTime,
			duration: video.duration
		});
	});

	video.addEventListener('timeupdate', function () {
		console.log('[JMD Intro] event: timeupdate', {
			currentTime: video.currentTime,
			duration: video.duration,
			canClose: state.canClose
		});

		if (!state.canClose) return;
		if (!isFinite(video.duration) || video.duration <= 0) return;

		const remaining = video.duration - video.currentTime;
		if (remaining <= END_THRESHOLD_SECONDS) {
			closeIntro('video near end');
		}
	});

	video.addEventListener('ended', function () {
		console.log('[JMD Intro] event: ended', {
			canClose: state.canClose
		});

		if (!state.canClose) return;
		closeIntro('video ended');
	});

	video.addEventListener('error', function () {
		console.log('[JMD Intro] event: error', video.error);
	});

	window.addEventListener('resize', function () {
		console.log('[JMD Intro] window resize');

		if (state.isFinished) return;
		setInitialLogoSize();
		setTargetTransformVars();
	});

	window.addEventListener('wheel', function (e) {
		console.log('[JMD Intro] window wheel', {
			deltaY: e.deltaY,
			canClose: state.canClose,
			isFinished: state.isFinished,
			isClosing: state.isClosing
		});

		if (!state.canClose || state.isFinished || state.isClosing) return;
		if (Math.abs(e.deltaY) > 4) {
			closeIntro('wheel');
		}
	}, { passive: true });

	window.addEventListener('touchmove', function () {
		console.log('[JMD Intro] window touchmove', {
			canClose: state.canClose,
			isFinished: state.isFinished,
			isClosing: state.isClosing
		});

		if (!state.canClose || state.isFinished || state.isClosing) return;
		closeIntro('touchmove');
	}, { passive: true });

	window.addEventListener('scroll', handleScrollClose, { passive: true });

	console.log('[JMD Intro] About to call openIntro');
	openIntro();
});