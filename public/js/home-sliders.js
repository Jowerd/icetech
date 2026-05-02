// --- გლობალური ფუნქციები ---
function toggleEmojiSelector() {
    const emojiSelector = document.getElementById('emojiSelector');
    if (!emojiSelector) return;
    emojiSelector.style.display = emojiSelector.style.display === 'grid' ? 'none' : 'grid';
}

function addEmoji(emoji) {
    const textarea = document.getElementById('content');
    if (!textarea) return;
    const start = textarea.selectionStart;
    const end   = textarea.selectionEnd;
    textarea.value = textarea.value.substring(0, start) + emoji + textarea.value.substring(end);
    textarea.selectionStart = textarea.selectionEnd = start + emoji.length;
    textarea.focus();
}

// --- მთავარი ლოგიკა ---
document.addEventListener('DOMContentLoaded', function () {

    const minSwipeDistance = 50;

    const sliderConfigs = [
        { container: '.category-slider-container', slider: '.category-slider', slides: '.category-slide', prevBtn: '.category-control-prev', nextBtn: '.category-control-next', indicatorsContainer: '.category-slider-indicators', indicatorClass: 'category-indicator' },
        { container: '.popular-slider-container',  slider: '.popular-slider',  slides: '.popular-slide',  prevBtn: '.popular-control-prev',  nextBtn: '.popular-control-next',  indicatorsContainer: '.popular-slider-indicators',  indicatorClass: 'popular-indicator'  },
        { container: '.newest-slider-container',   slider: '.newest-slider',   slides: '.newest-slide',   prevBtn: '.newest-control-prev',   nextBtn: '.newest-control-next',   indicatorsContainer: '.newest-slider-indicators',   indicatorClass: 'newest-indicator'   }
    ];

    sliderConfigs.forEach(initSlider);
    initMainCarousel();
    initReviewsSlider();
    initBlogSlider();

    // ========= ზოგადი სლაიდერი =========
    function initSlider(config) {
        const sliderContainer = document.querySelector(config.container);
        if (!sliderContainer) return;
        const slider = document.querySelector(config.slider);
        if (!slider) return;
        const slides = document.querySelectorAll(config.slides);
        if (!slides.length) return;

        const prevBtn             = document.querySelector(config.prevBtn);
        const nextBtn             = document.querySelector(config.nextBtn);
        const indicatorsContainer = document.querySelector(config.indicatorsContainer);

        let currentIndex  = 0;
        let slidesPerView = getSPV();
        let totalSlides   = slides.length;
        let maxIndex      = Math.max(0, Math.ceil(totalSlides / slidesPerView) - 1);

        let touchStartX = 0, touchStartY = 0, touchEndX = 0;
        let dirLocked   = null; // null | 'h' | 'v'

        if (indicatorsContainer) createIndicators();
        updateSlider();

        if (prevBtn) prevBtn.addEventListener('click', () => moveSlider(-1));
        if (nextBtn) nextBtn.addEventListener('click', () => moveSlider(1));

        sliderContainer.addEventListener('touchstart', onTouchStart, { passive: true });
        sliderContainer.addEventListener('touchmove',  onTouchMove,  { passive: false });
        sliderContainer.addEventListener('touchend',   onTouchEnd,   { passive: true });

        // mouse drag
        let mouseStartX  = 0;
        let mouseDrag    = false;
        let mouseDidDrag = false;

        sliderContainer.style.cursor = 'grab';

        sliderContainer.addEventListener('mousedown', function(e) {
            e.preventDefault();
            mouseDrag    = true;
            mouseDidDrag = false;
            mouseStartX  = e.clientX;
            sliderContainer.style.cursor = 'grabbing';
        });

        document.addEventListener('mousemove', function(e) {
            if (!mouseDrag) return;
            if (Math.abs(e.clientX - mouseStartX) > 5) mouseDidDrag = true;
        });

        document.addEventListener('mouseup', function(e) {
            if (!mouseDrag) return;
            mouseDrag = false;
            sliderContainer.style.cursor = 'grab';
            if (mouseDidDrag) {
                const diff = e.clientX - mouseStartX;
                if (Math.abs(diff) > minSwipeDistance) moveSlider(diff > 0 ? -1 : 1);
            }
        });

        sliderContainer.addEventListener('click', function(e) {
            if (mouseDidDrag) { e.preventDefault(); mouseDidDrag = false; }
        }, true);

        window.addEventListener('resize', () => {
            slidesPerView = getSPV();
            maxIndex      = Math.max(0, Math.ceil(totalSlides / slidesPerView) - 1);
            currentIndex  = Math.min(currentIndex, maxIndex);
            if (indicatorsContainer) createIndicators();
            updateSlider();
        });

        function onTouchStart(e) {
            touchStartX = e.touches[0].clientX;
            touchStartY = e.touches[0].clientY;
            touchEndX   = touchStartX;
            dirLocked   = null;
        }

        function onTouchMove(e) {
            const dx = Math.abs(e.touches[0].clientX - touchStartX);
            const dy = Math.abs(e.touches[0].clientY - touchStartY);

            // პირველი სვლა — განვსაზღვრავთ მიმართულებას
            if (!dirLocked && (dx > 8 || dy > 8)) {
                dirLocked = dx > dy ? 'h' : 'v';
            }

            if (dirLocked === 'h') {
                // ჰორიზონტალური swipe — ვაჩერებთ გვერდის სქროლს
                e.preventDefault();
                touchEndX = e.touches[0].clientX;
            }
            // ვერტიკალური — preventDefault არ გამოვიძახებთ, გვერდი ისქროლება
        }

        function onTouchEnd() {
            if (dirLocked === 'h') {
                const dist = touchEndX - touchStartX;
                if (Math.abs(dist) > minSwipeDistance) moveSlider(dist > 0 ? -1 : 1);
            }
            dirLocked   = null;
            touchStartX = touchEndX = touchStartY = 0;
        }

        function moveSlider(dir) {
            const n = currentIndex + dir;
            if (n >= 0 && n <= maxIndex) { currentIndex = n; updateSlider(); }
        }

        function goToSlide(index) {
            if (index >= 0 && index <= maxIndex) { currentIndex = index; updateSlider(); }
        }

        function updateSlider() {
            slider.style.transform = `translateX(-${currentIndex * 100}%)`;
            if (indicatorsContainer) {
                indicatorsContainer.querySelectorAll(`.${config.indicatorClass}`)
                    .forEach((ind, i) => ind.classList.toggle('active', i === currentIndex));
            }
            if (prevBtn) { prevBtn.disabled = currentIndex === 0; prevBtn.style.opacity = currentIndex === 0 ? '0.5' : '1'; }
            if (nextBtn) { nextBtn.disabled = currentIndex === maxIndex; nextBtn.style.opacity = currentIndex === maxIndex ? '0.5' : '1'; }
        }

        function createIndicators() {
            indicatorsContainer.innerHTML = '';
            for (let i = 0; i <= maxIndex; i++) {
                const ind = document.createElement('div');
                ind.classList.add(config.indicatorClass);
                if (i === currentIndex) ind.classList.add('active');
                ind.addEventListener('click', () => goToSlide(i));
                indicatorsContainer.appendChild(ind);
            }
        }

        function getSPV() {
            const w = window.innerWidth;
            if (w >= 1200) return 5;
            if (w >= 992)  return 4;
            if (w >= 768)  return 3;
            return 2;
        }
    }

    // ========= მთავარი კარუსელი =========
    function initMainCarousel() {
        const mainCarousel = document.getElementById('mainCarousel');
        if (!mainCarousel || typeof bootstrap === 'undefined' || !bootstrap.Carousel) return;

        const carousel = new bootstrap.Carousel(mainCarousel);
        let startX = 0, startY = 0, endX = 0;
        let dirLocked = null;

        mainCarousel.addEventListener('touchstart', e => {
            startX    = e.touches[0].clientX;
            startY    = e.touches[0].clientY;
            endX      = startX;
            dirLocked = null;
        }, { passive: true });

        mainCarousel.addEventListener('touchmove', e => {
            const dx = Math.abs(e.touches[0].clientX - startX);
            const dy = Math.abs(e.touches[0].clientY - startY);
            if (!dirLocked && (dx > 8 || dy > 8)) {
                dirLocked = dx > dy ? 'h' : 'v';
            }
            if (dirLocked === 'h') {
                e.preventDefault();
                endX = e.touches[0].clientX;
            }
        }, { passive: false });

        mainCarousel.addEventListener('touchend', () => {
            if (dirLocked === 'h') {
                const dist = endX - startX;
                if (Math.abs(dist) > minSwipeDistance) dist > 0 ? carousel.prev() : carousel.next();
            }
            dirLocked = null;
            startX = endX = startY = 0;
        }, { passive: true });
    }

    // ========= შეფასებების სლაიდერი =========
    function initReviewsSlider() {
        const sliderContainer = document.querySelector('.reviews-slider-container');
        if (!sliderContainer) return;
        const slider     = sliderContainer.querySelector('.reviews-slider');
        const slides     = sliderContainer.querySelectorAll('.reviews-slide');
        const indicators = sliderContainer.querySelector('.reviews-slider-indicators');
        if (!slider || !slides.length) return;

        let currentIndex  = 0;
        let slidesPerView = getReviewsSPV();
        let totalSlides   = slides.length;
        let maxIndex      = Math.max(0, totalSlides - slidesPerView);

        let startX = 0, startY = 0, endX = 0;
        let dirLocked  = null;
        let isDragging = false;
        let autoTimer  = null;
        let isPaused   = false;
        const swipeThr = 50;

        if (indicators) createIndicators();
        updateSlider();
        startAuto();
        sliderContainer.style.cursor = 'grab';

        // Touch
        sliderContainer.addEventListener('touchstart', e => {
            startX = e.touches[0].clientX;
            startY = e.touches[0].clientY;
            endX   = startX;
            dirLocked = null;
            pauseAuto();
        }, { passive: true });

        sliderContainer.addEventListener('touchmove', e => {
            const dx = Math.abs(e.touches[0].clientX - startX);
            const dy = Math.abs(e.touches[0].clientY - startY);
            if (!dirLocked && (dx > 8 || dy > 8)) {
                dirLocked = dx > dy ? 'h' : 'v';
            }
            if (dirLocked === 'h') {
                e.preventDefault();
                endX = e.touches[0].clientX;
            }
        }, { passive: false });

        sliderContainer.addEventListener('touchend', () => {
            if (dirLocked === 'h') {
                const diff = endX - startX;
                if (Math.abs(diff) > swipeThr) move(diff > 0 ? -1 : 1);
            }
            dirLocked = null;
            resumeAuto();
        });

        // Mouse drag
        let mouseDidDrag = false;
        sliderContainer.addEventListener('mousedown', e => {
            e.preventDefault();
            startX = e.clientX; endX = startX;
            isDragging   = true;
            mouseDidDrag = false;
            sliderContainer.style.cursor = 'grabbing';
            pauseAuto();
        });
        document.addEventListener('mousemove', e => {
            if (!isDragging) return;
            endX = e.clientX;
            if (Math.abs(endX - startX) > 5) mouseDidDrag = true;
        });
        document.addEventListener('mouseup', () => {
            if (!isDragging) return;
            sliderContainer.style.cursor = 'grab';
            const diff = endX - startX;
            if (Math.abs(diff) > swipeThr) move(diff > 0 ? -1 : 1);
            isDragging = false;
            resumeAuto();
        });
        sliderContainer.addEventListener('click', function(e) {
            if (mouseDidDrag) { e.preventDefault(); mouseDidDrag = false; }
        }, true);

        sliderContainer.addEventListener('mouseenter', pauseAuto);
        sliderContainer.addEventListener('mouseleave', resumeAuto);

        window.addEventListener('resize', () => {
            slidesPerView = getReviewsSPV();
            maxIndex      = Math.max(0, totalSlides - slidesPerView);
            currentIndex  = Math.min(currentIndex, maxIndex);
            updateSlider();
            if (indicators) createIndicators();
        });

        function getReviewsSPV() {
            const w = window.innerWidth;
            if (w >= 992) return 3;
            if (w >= 768) return 2;
            return 1;
        }

        function updateSlider() {
            const sw = 100 / totalSlides;
            slider.style.width = `${totalSlides * 100 / slidesPerView}%`;
            slides.forEach(s => s.style.width = `${sw}%`);
            slider.style.transform = `translateX(-${sw * currentIndex}%)`;
            if (indicators) {
                indicators.querySelectorAll('.reviews-indicator')
                    .forEach((ind, i) => ind.classList.toggle('active', i === currentIndex));
            }
        }

        function createIndicators() {
            indicators.innerHTML = '';
            for (let i = 0; i <= maxIndex; i++) {
                const ind = document.createElement('div');
                ind.classList.add('reviews-indicator');
                if (i === currentIndex) ind.classList.add('active');
                ind.addEventListener('click', () => goTo(i));
                indicators.appendChild(ind);
            }
        }

        function goTo(index) { currentIndex = Math.max(0, Math.min(index, maxIndex)); updateSlider(); startAuto(); }
        function move(dir)   { goTo(currentIndex + dir); }
        function startAuto() {
            clearInterval(autoTimer);
            autoTimer = setInterval(() => { if (!isPaused) currentIndex >= maxIndex ? goTo(0) : move(1); }, 5000);
        }
        function pauseAuto()  { isPaused = true;  }
        function resumeAuto() { isPaused = false; }
    }

    // ========= ბლოგის სლაიდერი =========
    function initBlogSlider() {
        const sliderContainer = document.querySelector('.blog-slider-container');
        if (!sliderContainer) return;
        const slider = sliderContainer.querySelector('.blog-slider');
        const slides = slider.querySelectorAll('.blog-slider-item');
        if (!slider || !slides.length) return;

        let currentIndex     = 0;
        let slidesPerView    = 3;
        let maxIndex         = 0;
        let isDragging       = false;
        let startX = 0, startY = 0;
        let currentTranslate = 0, prevTranslate = 0;
        let dirLocked        = null;

        function update() {
            if (window.innerWidth < 576)      slidesPerView = 1;
            else if (window.innerWidth < 992) slidesPerView = 2;
            else                              slidesPerView = 3;
            maxIndex = Math.max(0, slides.length - slidesPerView);
            if (currentIndex > maxIndex) currentIndex = maxIndex;
            const sw = sliderContainer.clientWidth / slidesPerView;
            currentTranslate = -currentIndex * sw;
            slider.style.transition = 'transform 0.4s ease-out';
            slider.style.transform  = `translateX(${currentTranslate}px)`;
        }

        let mouseDidDrag = false;

        function dragStart(e) {
            e.preventDefault();
            isDragging    = true;
            dirLocked     = null;
            mouseDidDrag  = false;
            startX        = e.type.includes('mouse') ? e.pageX : e.touches[0].clientX;
            startY        = e.type.includes('mouse') ? e.pageY : e.touches[0].clientY;
            prevTranslate = currentTranslate;
            slider.style.transition = 'none';
            if (e.type.includes('mouse')) sliderContainer.style.cursor = 'grabbing';
        }

        function dragMove(e) {
            if (!isDragging) return;
            const cx = e.type.includes('mouse') ? e.pageX : e.touches[0].clientX;
            const cy = e.type.includes('mouse') ? e.pageY : e.touches[0].clientY;

            if (!dirLocked) {
                const dx = Math.abs(cx - startX);
                const dy = Math.abs(cy - startY);
                if (dx > 8 || dy > 8) {
                    if (dx > dy) {
                        dirLocked = 'h';
                    } else {
                        // ვერტიკალური სქროლი — drag-ს ვაჩერებთ
                        isDragging = false;
                        dirLocked  = 'v';
                        slider.style.transition = 'transform 0.4s ease-out';
                        slider.style.transform  = `translateX(${prevTranslate}px)`;
                        sliderContainer.style.cursor = 'grab';
                        return;
                    }
                }
            }

            if (dirLocked === 'h') {
                if (!e.type.includes('mouse')) e.preventDefault();
                currentTranslate = prevTranslate + (cx - startX);
                slider.style.transform = `translateX(${currentTranslate}px)`;
                mouseDidDrag = true;
            }
        }

        function dragEnd() {
            if (!isDragging) return;
            isDragging = false;
            sliderContainer.style.cursor = 'grab';
            if (dirLocked === 'h') {
                const moved = currentTranslate - prevTranslate;
                if (moved < -50 && currentIndex < maxIndex) currentIndex++;
                if (moved > 50  && currentIndex > 0)        currentIndex--;
            }
            dirLocked = null;
            update();
        }

        sliderContainer.addEventListener('mousedown',  dragStart);
        sliderContainer.addEventListener('touchstart', dragStart, { passive: true });
        sliderContainer.addEventListener('touchmove',  dragMove, { passive: false });
        sliderContainer.addEventListener('touchend',   dragEnd);
        document.addEventListener('mousemove',  dragMove);
        document.addEventListener('mouseup',    dragEnd);
        document.addEventListener('mouseleave', dragEnd);
        sliderContainer.addEventListener('click', function(e) {
            if (mouseDidDrag) { e.preventDefault(); mouseDidDrag = false; }
        }, true);
        window.addEventListener('resize', update);
        update();
    }

    // ========= სხვა =========
    const successAlert = document.querySelector('.alert-success');
    if (successAlert) {
        setTimeout(() => {
            successAlert.style.opacity = '0';
            setTimeout(() => { successAlert.style.display = 'none'; }, 500);
        }, 3000);
    }
});
// ===== Kitchen Assembly — Scroll Reveal =====
(function () {
    const reveals = document.querySelectorAll('.ka-reveal');
    if (!reveals.length) return;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('ka-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.15 });

    reveals.forEach(el => observer.observe(el));
})();
