document.addEventListener('DOMContentLoaded', function () {
	if (typeof jmdFrontIntro === 'undefined') return;

	const intro = document.getElementById('jmd-intro');
	const video = document.getElementById('jmd-intro-video');
	const closeBtn = intro ? intro.querySelector('.jmd-intro__close') : null;
	const logoWrap = intro ? intro.querySelector('.jmd-intro__logo-wrap') : null;

	if (!intro || !video || !closeBtn || !logoWrap) return;

	const state = {
		isClosing: false,
		isFinished: false,
		hasOpened: false
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

	function getCurrentIntroWidth() {
		return logoWrap.getBoundingClientRect().width;
	}

    function setInitialLogoSize() {
        const vw = window.innerWidth;
        const vh = window.innerHeight;
        const mobile = vw < 576;

        let width;

        if (mobile) {
            width = Math.min(vw * 0.82, 320);
        } else {
            width = Math.min(vw * 0.66, 980);
        }

        logoWrap.style.setProperty('--intro-logo-width', `${width}px`);

        const closedGap = mobile ? 8 : 12;
        logoWrap.style.setProperty('--intro-gap', `${closedGap}px`);

        const openDistance = Math.max((vh * 0.5) - (logoWrap.offsetHeight * 0.12), vh * 0.38);
        logoWrap.style.setProperty('--open-distance', `${openDistance}px`);
    }

    function setTargetTransformVars() {
        const navLogo = document.querySelector('.custom-logo-link img');
        const introRect = logoWrap.getBoundingClientRect();

        const introCenterX = introRect.left + introRect.width / 2;
        const introCenterY = introRect.top + introRect.height / 2;

        let targetCenterX;
        let targetCenterY;
        let scale;

        if (navLogo) {
            const navRect = navLogo.getBoundingClientRect();
            targetCenterX = navRect.left + navRect.width / 2;
            targetCenterY = navRect.top + navRect.height / 2;
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

	function openIntro() {
		setInitialLogoSize();
		setTargetTransformVars();
		lockScroll();

        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                video.muted = !!jmdFrontIntro.muted;

                const playPromise = video.play();
                if (playPromise && typeof playPromise.catch === 'function') {
                    playPromise.catch(() => {});
                }

                setTimeout(() => {
                    intro.classList.add('is-open');
                    state.hasOpened = true;
                }, 350);
            });
        });
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
		}, 1000);

		setTimeout(() => {
			finalizeIntro();
		}, 1900);
	}

	function handleScrollClose() {
		if (!state.hasOpened || state.isClosing || state.isFinished) return;

		const scrollTop = window.pageYOffset || document.documentElement.scrollTop || 0;
		if (scrollTop > 8) {
			closeIntro();
		}
	}

	closeBtn.addEventListener('click', function () {
		closeIntro();
	});

	video.addEventListener('timeupdate', function () {
		if (!isFinite(video.duration) || video.duration <= 0) return;

		const remaining = video.duration - video.currentTime;
		if (remaining <= END_THRESHOLD_SECONDS) {
			closeIntro();
		}
	});

	video.addEventListener('ended', function () {
		closeIntro();
	});

	window.addEventListener('resize', function () {
		if (state.isFinished) return;
		setInitialLogoSize();
		setTargetTransformVars();
	});

	window.addEventListener('wheel', function (e) {
		if (state.isFinished || state.isClosing) return;
		if (Math.abs(e.deltaY) > 4) {
			closeIntro();
		}
	}, { passive: true });

	window.addEventListener('touchmove', function () {
		if (state.isFinished || state.isClosing) return;
		closeIntro();
	}, { passive: true });

	window.addEventListener('scroll', handleScrollClose, { passive: true });

	openIntro();
});