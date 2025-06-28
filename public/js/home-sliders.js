// --- გლობალური ფუნქციები, რომლებიც HTML-ის onclick ატრიბუტებს სჭირდება ---
function toggleEmojiSelector() {
    const emojiSelector = document.getElementById('emojiSelector');
    if (!emojiSelector) return;
    const isVisible = emojiSelector.style.display === 'grid';
    emojiSelector.style.display = isVisible ? 'none' : 'grid';
}

function addEmoji(emoji) {
    const textarea = document.getElementById('content');
    if (!textarea) return;
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const text = textarea.value;
    textarea.value = text.substring(0, start) + emoji + text.substring(end);
    textarea.selectionStart = textarea.selectionEnd = start + emoji.length;
    textarea.focus();
}

// --- მთავარი ლოგიკა, რომელიც სრულდება გვერდის ჩატვირთვის შემდეგ ---
document.addEventListener('DOMContentLoaded', function() {

    // ========= ზოგადი სლაიდერები (კატეგორიები, პოპულარული, ახალი) - თქვენი ორიგინალი, შეუცვლელი კოდი =========
    const sliderConfigs = [
        { container: '.category-slider-container', slider: '.category-slider', slides: '.category-slide', prevBtn: '.category-control-prev', nextBtn: '.category-control-next', indicatorsContainer: '.category-slider-indicators', indicatorClass: 'category-indicator' },
        { container: '.popular-slider-container', slider: '.popular-slider', slides: '.popular-slide', prevBtn: '.popular-control-prev', nextBtn: '.popular-control-next', indicatorsContainer: '.popular-slider-indicators', indicatorClass: 'popular-indicator' },
        { container: '.newest-slider-container', slider: '.newest-slider', slides: '.newest-slide', prevBtn: '.newest-control-prev', nextBtn: '.newest-control-next', indicatorsContainer: '.newest-slider-indicators', indicatorClass: 'newest-indicator' }
    ];

    const minSwipeDistance = 50;
    let isMobile = window.innerWidth < 768;

    // Initialize all sliders from the config
    sliderConfigs.forEach(initSlider);
    
    // Handle main bootstrap carousel
    initMainCarousel();
    
    // Initialize reviews slider
    initReviewsSlider();

    // ეს არის თქვენი ორიგინალი, მომუშავე ფუნქცია, შეუცვლელად
    function initSlider(config) {
        const sliderContainer = document.querySelector(config.container);
        if (!sliderContainer) return;
        
        const slider = document.querySelector(config.slider);
        if (!slider) return;
        
        const slides = document.querySelectorAll(config.slides);
        if (!slides.length) return;
        
        const prevBtn = document.querySelector(config.prevBtn);
        const nextBtn = document.querySelector(config.nextBtn);
        const indicatorsContainer = document.querySelector(config.indicatorsContainer);

        let currentIndex = 0;
        let slidesPerView = calculateSlidesPerView();
        let totalSlides = slides.length;
        let maxIndex = Math.ceil(totalSlides / slidesPerView) - 1;
        let touchStartX = 0, touchEndX = 0, touchStartY = 0, touchEndY = 0;
        let isSwiping = false;

        if (indicatorsContainer) createIndicators();
        updateSlider();
        setupCardNavigation();

        if (prevBtn) prevBtn.addEventListener('click', () => moveSlider(-1));
        if (nextBtn) nextBtn.addEventListener('click', () => moveSlider(1));

        sliderContainer.addEventListener('touchstart', handleTouchStart, { passive: true });
        sliderContainer.addEventListener('touchmove', handleTouchMove, { passive: true });
        sliderContainer.addEventListener('touchend', handleTouchEnd, { passive: true });
        window.addEventListener('resize', handleResize);

        function handleResize() {
            const wasMobile = isMobile;
            isMobile = window.innerWidth < 768;
            if (wasMobile !== isMobile) setupCardNavigation();
            slidesPerView = calculateSlidesPerView();
            maxIndex = Math.ceil(totalSlides / slidesPerView) - 1;
            currentIndex = Math.min(currentIndex, maxIndex);
            if (indicatorsContainer) createIndicators();
            updateSlider();
        }

        function setupCardNavigation() {
            slides.forEach(slide => {
                const cardLinks = slide.querySelectorAll('a');
                const cardElements = slide.querySelectorAll('.card, [role="button"]');
                
                cardLinks.forEach(link => {
                    const newLink = link.cloneNode(true);
                    link.parentNode.replaceChild(newLink, link);
                    if (isMobile) {
                        newLink.addEventListener('click', e => e.stopPropagation());
                    }
                });
                
                cardElements.forEach(card => {
                    const newCard = card.cloneNode(true);
                    card.parentNode.replaceChild(newCard, card);
                    const cardLink = newCard.querySelector('a');
                    if (cardLink && isMobile) {
                        const targetUrl = cardLink.getAttribute('href');
                        newCard.addEventListener('click', e => {
                            e.stopPropagation();
                            window.location.href = targetUrl;
                        });
                    }
                });
            });
        }

        function createIndicators() {
            if (!indicatorsContainer) return;
            indicatorsContainer.innerHTML = '';
            for (let i = 0; i <= maxIndex; i++) {
                const indicator = document.createElement('div');
                indicator.classList.add(config.indicatorClass);
                if (i === currentIndex) indicator.classList.add('active');
                indicator.addEventListener('click', () => goToSlide(i));
                indicatorsContainer.appendChild(indicator);
            }
        }

        function moveSlider(direction) {
            const newIndex = currentIndex + direction;
            if (newIndex >= 0 && newIndex <= maxIndex) {
                currentIndex = newIndex;
                updateSlider();
            }
        }

        function goToSlide(index) {
            if (index >= 0 && index <= maxIndex) {
                currentIndex = index;
                updateSlider();
            }
        }

        function updateSlider() {
            slider.style.transform = `translateX(-${(currentIndex * 100)}%)`;
            if (indicatorsContainer) {
                const indicators = indicatorsContainer.querySelectorAll(`.${config.indicatorClass}`);
                indicators.forEach((indicator, index) => {
                    indicator.classList.toggle('active', index === currentIndex);
                });
            }
            if (prevBtn) {
                prevBtn.disabled = currentIndex === 0;
                prevBtn.style.opacity = currentIndex === 0 ? '0.5' : '1';
            }
            if (nextBtn) {
                nextBtn.disabled = currentIndex === maxIndex;
                nextBtn.style.opacity = currentIndex === maxIndex ? '0.5' : '1';
            }
        }

        function handleTouchStart(e) {
            isSwiping = false;
            touchStartX = e.touches[0].clientX;
            touchStartY = e.touches[0].clientY;
            if (!e.target.closest('a') && !e.target.closest('.card') && !e.target.closest('[role="button"]')) {
                touchEndX = touchStartX;
                touchEndY = touchStartY;
            }
        }

        function handleTouchMove(e) {
            if (isSwiping === false) {
                const currentX = e.touches[0].clientX;
                const currentY = e.touches[0].clientY;
                const diffX = Math.abs(currentX - touchStartX);
                const diffY = Math.abs(currentY - touchStartY);
                if (diffX > diffY && diffX > 10) {
                    isSwiping = true;
                }
            }
            if (isSwiping) {
                touchEndX = e.touches[0].clientX;
                touchEndY = e.touches[0].clientY;
            }
        }

        function handleTouchEnd() {
            if (isSwiping) {
                const distance = touchEndX - touchStartX;
                if (Math.abs(distance) > minSwipeDistance) {
                    moveSlider(distance > 0 ? -1 : 1);
                }
            }
            isSwiping = false;
            touchStartX = touchEndX = touchStartY = touchEndY = 0;
        }

        function calculateSlidesPerView() {
            const width = window.innerWidth;
            if (width >= 1200) return 5;
            if (width >= 992) return 3;
            return 2;
        }
    }

    // ========= მთავარი კარუსელი (Bootstrap) - თქვენი ორიგინალი, შეუცვლელი კოდი =========
    function initMainCarousel() {
        const mainCarousel = document.getElementById('mainCarousel');
        if (!mainCarousel || typeof bootstrap === 'undefined' || !bootstrap.Carousel) return;
        const carousel = new bootstrap.Carousel(mainCarousel);
        let carouselStartX = 0, carouselEndX = 0, isCarouselSwiping = false;

        mainCarousel.addEventListener('touchstart', e => {
            isCarouselSwiping = false;
            carouselStartX = e.touches[0].clientX;
        }, { passive: true });

        mainCarousel.addEventListener('touchmove', e => {
            if (isCarouselSwiping === false && Math.abs(e.touches[0].clientX - carouselStartX) > 10) {
                isCarouselSwiping = true;
            }
            if (isCarouselSwiping) {
                carouselEndX = e.touches[0].clientX;
            }
        }, { passive: true });

        mainCarousel.addEventListener('touchend', () => {
            if (isCarouselSwiping) {
                const distance = carouselEndX - carouselStartX;
                if (Math.abs(distance) > minSwipeDistance) {
                    distance > 0 ? carousel.prev() : carousel.next();
                }
            }
            isCarouselSwiping = false;
            carouselStartX = 0;
            carouselEndX = 0;
        }, { passive: true });
    }

    // ========= შეფასებების (Reviews) სლაიდერი - თქვენი ორიგინალი, შეუცვლელი კოდი =========
    function initReviewsSlider() {
        const sliderContainer = document.querySelector('.reviews-slider-container');
        if (!sliderContainer) return;
        
        const slider = sliderContainer.querySelector('.reviews-slider');
        const slides = sliderContainer.querySelectorAll('.reviews-slide');
        const indicatorsContainer = sliderContainer.querySelector('.reviews-slider-indicators');
        if (!slider || !slides.length) return;
        
        let currentIndex = 0;
        let slidesPerView = calculateReviewsSlidesPerView();
        let totalSlides = slides.length;
        let maxIndex = totalSlides - slidesPerView;
        let startX = 0, endX = 0, isDragging = false;
        const swipeThreshold = 50;
        let autoSlideTimer = null, isPaused = false;
        
        if (indicatorsContainer) createReviewsIndicators();
        updateReviewsSlider();
        startAutoSlide();
        sliderContainer.style.cursor = 'grab';
        
        sliderContainer.addEventListener('touchstart', e => { startX = e.touches[0].clientX; isDragging = true; pauseAutoSlide(); }, { passive: true });
        sliderContainer.addEventListener('touchmove', e => { if (isDragging) endX = e.touches[0].clientX; }, { passive: true });
        sliderContainer.addEventListener('touchend', () => { handleDragEnd(); resumeAutoSlide(); });
        sliderContainer.addEventListener('mousedown', e => { e.preventDefault(); startX = e.clientX; isDragging = true; sliderContainer.style.cursor = 'grabbing'; pauseAutoSlide(); });
        document.addEventListener('mousemove', e => { if (isDragging) endX = e.clientX; });
        document.addEventListener('mouseup', () => { if (isDragging) { sliderContainer.style.cursor = 'grab'; handleDragEnd(); resumeAutoSlide(); } });
        sliderContainer.addEventListener('mouseenter', pauseAutoSlide);
        sliderContainer.addEventListener('mouseleave', resumeAutoSlide);
        window.addEventListener('resize', () => {
            slidesPerView = calculateReviewsSlidesPerView();
            maxIndex = totalSlides - slidesPerView;
            updateReviewsSlider();
            if (indicatorsContainer) createReviewsIndicators();
        });
    
        function calculateReviewsSlidesPerView() {
            const width = window.innerWidth;
            if (width >= 992) return 3;
            if (width >= 768) return 2;
            return 1;
        }
        
        function updateReviewsSlider() {
            const slideWidth = 100 / totalSlides;
            slider.style.width = `${totalSlides * 100 / slidesPerView}%`;
            slides.forEach(slide => slide.style.width = `${slideWidth}%`);
            const offset = slideWidth * currentIndex;
            slider.style.transform = `translateX(-${offset}%)`;
            if (indicatorsContainer) updateReviewsIndicators();
        }
        
        function createReviewsIndicators() {
            indicatorsContainer.innerHTML = '';
            for (let i = 0; i <= maxIndex; i++) {
                const indicator = document.createElement('div');
                indicator.classList.add('reviews-indicator');
                if (i === currentIndex) indicator.classList.add('active');
                indicator.addEventListener('click', () => goToReviewsSlide(i));
                indicatorsContainer.appendChild(indicator);
            }
        }
        
        function updateReviewsIndicators() {
            const indicators = indicatorsContainer.querySelectorAll('.reviews-indicator');
            indicators.forEach((ind, idx) => ind.classList.toggle('active', idx === currentIndex));
        }
        
        function goToReviewsSlide(index) {
            currentIndex = index;
            updateReviewsSlider();
            restartAutoSlide();
        }
        
        function moveReviewsSlider(direction) {
            currentIndex += direction;
            currentIndex = Math.max(0, Math.min(currentIndex, maxIndex));
            updateReviewsSlider();
            restartAutoSlide();
        }
        
        function startAutoSlide() {
            clearInterval(autoSlideTimer);
            autoSlideTimer = setInterval(() => {
                if (!isPaused) {
                    if (currentIndex >= maxIndex) {
                        goToReviewsSlide(0);
                    } else {
                        moveReviewsSlider(1);
                    }
                }
            }, 5000);
        }
        
        function restartAutoSlide() { startAutoSlide(); }
        function handleDragEnd() {
            if (!isDragging) return;
            const diff = endX - startX;
            if (Math.abs(diff) > swipeThreshold) moveReviewsSlider(diff > 0 ? -1 : 1);
            isDragging = false;
            startX = 0;
            endX = 0;
        }
        function pauseAutoSlide() { isPaused = true; }
        function resumeAutoSlide() { isPaused = false; }
    }

    // ========= ბლოგის სლაიდერი (გამართული ვერსია) =========
    function initBlogSlider() {
        const sliderContainer = document.querySelector('.blog-slider-container');
        if (!sliderContainer) return;
        const slider = sliderContainer.querySelector('.blog-slider');
        const slides = slider.querySelectorAll('.blog-slider-item');
        if (!slider || slides.length === 0) return;

        let currentIndex = 0, slidesPerView = 3, totalSlides = slides.length, maxIndex = 0;
        let isDragging = false, startPos = 0, currentTranslate = 0, prevTranslate = 0;

        function update() {
            if (window.innerWidth < 576) slidesPerView = 1;
            else if (window.innerWidth < 992) slidesPerView = 2;
            else slidesPerView = 3;
            maxIndex = Math.max(0, totalSlides - slidesPerView);
            if (currentIndex > maxIndex) currentIndex = maxIndex;
            const slideWidth = sliderContainer.clientWidth / slidesPerView;
            currentTranslate = -currentIndex * slideWidth;
            slider.style.transition = 'transform 0.4s ease-out';
            slider.style.transform = `translateX(${currentTranslate}px)`;
        }
        
        function dragStart(event) {
            if (event.target.closest('a')) return;
            isDragging = true;
            startPos = event.type.includes('mouse') ? event.pageX : event.touches[0].clientX;
            prevTranslate = currentTranslate;
            slider.style.transition = 'none';
            sliderContainer.style.cursor = 'grabbing';
        }
        
        function dragMove(event) {
            if (!isDragging) return;
            const currentPosition = event.type.includes('mouse') ? event.pageX : event.touches[0].clientX;
            currentTranslate = prevTranslate + (currentPosition - startPos);
            slider.style.transform = `translateX(${currentTranslate}px)`;
        }
        
        function dragEnd() {
            if (!isDragging) return;
            isDragging = false;
            sliderContainer.style.cursor = 'grab';
            const movedBy = currentTranslate - prevTranslate;
            if (movedBy < -50 && currentIndex < maxIndex) currentIndex++;
            if (movedBy > 50 && currentIndex > 0) currentIndex--;
            update();
        }

        sliderContainer.addEventListener('mousedown', dragStart);
        sliderContainer.addEventListener('touchstart', dragStart, { passive: true });
        document.addEventListener('mousemove', dragMove);
        document.addEventListener('touchmove', dragMove, { passive: true });
        document.addEventListener('mouseup', dragEnd);
        document.addEventListener('touchend', dragEnd);
        document.addEventListener('mouseleave', dragEnd);
        window.addEventListener('resize', update);
        update();
    }
    
    // აქ გამოვიძახებთ ბლოგის სლაიდერს
    initBlogSlider();

    // ========= ფორმების და სხვა დანარჩენი ლოგიკა =========
    // ... (თქვენი ფორმების ლოგიკა, რომელიც არ იცვლება)
    const commentForm = document.querySelector('form#commentForm');
    if (commentForm) {
        // ... (დანარჩენი კოდი იგივეა)
    }
    const successAlert = document.querySelector('.alert-success');
    if (successAlert) {
        setTimeout(() => {
            successAlert.style.opacity = '0';
            setTimeout(() => {
                successAlert.style.display = 'none';
            }, 500);
        }, 3000);
    }
});