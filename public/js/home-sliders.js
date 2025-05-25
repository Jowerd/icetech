document.addEventListener('DOMContentLoaded', function() {
    const sliderConfigs = [
        {
            container: '.category-slider-container',
            slider: '.category-slider',
            slides: '.category-slide',
            prevBtn: '.category-control-prev',
            nextBtn: '.category-control-next',
            indicatorsContainer: '.category-slider-indicators',
            indicatorClass: 'category-indicator'
        },
        {
            container: '.popular-slider-container',
            slider: '.popular-slider',
            slides: '.popular-slide',
            prevBtn: '.popular-control-prev',
            nextBtn: '.popular-control-next',
            indicatorsContainer: '.popular-slider-indicators',
            indicatorClass: 'popular-indicator'
        },
        {
            container: '.newest-slider-container',
            slider: '.newest-slider',
            slides: '.newest-slide',
            prevBtn: '.newest-control-prev',
            nextBtn: '.newest-control-next',
            indicatorsContainer: '.newest-slider-indicators',
            indicatorClass: 'newest-indicator'
        }
    ];

    const minSwipeDistance = 50;
    let isMobile = window.innerWidth < 768;

    // Initialize all sliders
    sliderConfigs.forEach(initSlider);
    
    // Handle main carousel
    initMainCarousel();
    
    // Initialize reviews slider
    initReviewsSlider();

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

        // Set up event listeners
        if (prevBtn) prevBtn.addEventListener('click', () => moveSlider(-1));
        if (nextBtn) nextBtn.addEventListener('click', () => moveSlider(1));

        // Touch events
        sliderContainer.addEventListener('touchstart', handleTouchStart, { passive: true });
        sliderContainer.addEventListener('touchmove', handleTouchMove, { passive: true });
        sliderContainer.addEventListener('touchend', handleTouchEnd, { passive: true });

        // Responsive behavior
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

    function initMainCarousel() {
        const mainCarousel = document.getElementById('mainCarousel');
        if (!mainCarousel || typeof bootstrap === 'undefined' || !bootstrap.Carousel) return;
        
        const carousel = new bootstrap.Carousel(mainCarousel);
        let carouselStartX = 0, carouselEndX = 0, carouselStartY = 0, carouselEndY = 0;
        let isCarouselSwiping = false;

        mainCarousel.addEventListener('touchstart', e => {
            if (!e.target.closest('a') && !e.target.closest('.card') && !e.target.closest('[role="button"]')) {
                carouselStartX = e.touches[0].clientX;
                carouselStartY = e.touches[0].clientY;
                carouselEndX = carouselStartX;
                carouselEndY = carouselStartY;
            }
        }, { passive: true });

        mainCarousel.addEventListener('touchmove', e => {
            if (isCarouselSwiping === false) {
                const currentX = e.touches[0].clientX;
                const currentY = e.touches[0].clientY;
                const diffX = Math.abs(currentX - carouselStartX);
                const diffY = Math.abs(currentY - carouselStartY);
                
                if (diffX > diffY && diffX > 10) {
                    isCarouselSwiping = true;
                }
            }
            
            if (isCarouselSwiping) {
                carouselEndX = e.touches[0].clientX;
                carouselEndY = e.touches[0].clientY;
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
            carouselStartX = carouselEndX = carouselStartY = carouselEndY = 0;
        }, { passive: true });
    }

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
        // Changed: maxIndex now equals the total number of slides minus slidesPerView
        // This allows for showing one new card at a time
        let maxIndex = totalSlides - slidesPerView;
        
        let startX = 0, endX = 0;
        let isDragging = false;
        const swipeThreshold = 50;
        
        // Auto-sliding parameters
        const autoSlideInterval = 5000;
        let autoSlideTimer = null;
        let isPaused = false;
        
        if (indicatorsContainer) createReviewsIndicators();
        updateReviewsSlider();
        startAutoSlide();
        
        sliderContainer.style.cursor = 'grab';
        
        // Touch events
        sliderContainer.addEventListener('touchstart', e => {
            startX = e.touches[0].clientX;
            isDragging = true;
            pauseAutoSlide();
        }, { passive: true });
        
        sliderContainer.addEventListener('touchmove', e => {
            if (isDragging) endX = e.touches[0].clientX;
        }, { passive: true });
        
        sliderContainer.addEventListener('touchend', () => {
            handleDragEnd();
            resumeAutoSlide();
        });
        
        // Mouse events
        sliderContainer.addEventListener('mousedown', e => {
            e.preventDefault();
            startX = e.clientX;
            isDragging = true;
            sliderContainer.style.cursor = 'grabbing';
            pauseAutoSlide();
        });
        
        document.addEventListener('mousemove', e => {
            if (isDragging) endX = e.clientX;
        });
        
        document.addEventListener('mouseup', () => {
            if (isDragging) {
                sliderContainer.style.cursor = 'grab';
                handleDragEnd();
                resumeAutoSlide();
            }
        });
        
        // Pause on hover
        sliderContainer.addEventListener('mouseenter', pauseAutoSlide);
        sliderContainer.addEventListener('mouseleave', resumeAutoSlide);
        
        // Responsive behavior
        window.addEventListener('resize', () => {
            slidesPerView = calculateReviewsSlidesPerView();
            // Changed: Update maxIndex on resize
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
            // Changed: This maintains the total width but positions slides differently
            // Each slide now takes its own percentage width
            const slideWidth = 100 / totalSlides;
            slider.style.width = `${totalSlides * 100 / slidesPerView}%`;
            
            slides.forEach(slide => {
                slide.style.width = `${slideWidth}%`;
            });
            
            // Changed: The offset is now calculated based on individual slide width
            // This ensures smooth one-by-one slide movement
            const offset = slideWidth * currentIndex;
            slider.style.transform = `translateX(-${offset}%)`;
            
            if (indicatorsContainer) updateReviewsIndicators();
        }
        
        function createReviewsIndicators() {
            indicatorsContainer.innerHTML = '';
            // Changed: Create one indicator for each possible position
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
            indicators.forEach((ind, idx) => {
                ind.classList.toggle('active', idx === currentIndex);
            });
        }
        
        function goToReviewsSlide(index) {
            currentIndex = index;
            updateReviewsSlider();
            restartAutoSlide();
        }
        
        function moveReviewsSlider(direction) {
            currentIndex += direction;
            // Changed: Ensure currentIndex stays within bounds
            currentIndex = Math.max(0, Math.min(currentIndex, maxIndex));
            updateReviewsSlider();
            restartAutoSlide();
        }
        
        function startAutoSlide() {
            if (autoSlideTimer) clearInterval(autoSlideTimer);
            
            autoSlideTimer = setInterval(() => {
                if (!isPaused) {
                    // Changed: Loop back to the beginning when reaching the end
                    if (currentIndex >= maxIndex) {
                        goToReviewsSlide(0);
                    } else {
                        moveReviewsSlider(1);
                    }
                }
            }, autoSlideInterval);
        }
        
        function restartAutoSlide() {
            startAutoSlide();
        }
        
        function handleDragEnd() {
            if (!isDragging) return;
            
            const diff = endX - startX;
            if (Math.abs(diff) > swipeThreshold) {
                moveReviewsSlider(diff > 0 ? -1 : 1);
            }
            
            isDragging = false;
            startX = endX = 0;
        }
        
        function pauseAutoSlide() {
            isPaused = true;
        }
        
        function resumeAutoSlide() {
            isPaused = false;
        }
    }

    // ========= ემოჯის ფუნქციონალობის სექცია =========
    
    // ემოჯების დამატების ფუნქცია ლოკალური (HTML-ის onclick-ს არ გამოიყენებს)
    function addEmojiLocal(emoji) {
        const textarea = document.getElementById('content');
        if (!textarea) return; // შემოწმება, თუ ტექსტარეა არსებობს
        
        // კურსორის პოზიციის დამახსოვრება
        const startPos = textarea.selectionStart;
        const endPos = textarea.selectionEnd;
        
        // ჩასვი ემოჯი მიმდინარე კურსორის პოზიციაზე
        const textBeforeCursor = textarea.value.substring(0, startPos);
        const textAfterCursor = textarea.value.substring(endPos);
        textarea.value = textBeforeCursor + emoji + textAfterCursor;
        
        // კურსორის გადატანა ემოჯის შემდეგ
        textarea.selectionStart = startPos + emoji.length;
        textarea.selectionEnd = startPos + emoji.length;
        
        // ფოკუსის დაბრუნება ტექსტარეაზე
        textarea.focus();
    }
    
    // ემოჯების სელექტორში არსებული ღილაკებისთვის ივენთ მსმენელების ინიციალიზაცია
    function initializeEmojiSelectorButtons() {
        const emojiSelector = document.getElementById('emojiSelector');
        if (!emojiSelector) return;
        
        const selectorButtons = emojiSelector.querySelectorAll('button');
        selectorButtons.forEach(button => {
            // თავიდან event listener-ების წაშლა დუბლიკატების თავიდან ასაცილებლად
            const newButton = button.cloneNode(true);
            button.parentNode.replaceChild(newButton, button);
            
            // HTML-ის onclick ატრიბუტების წაშლა
            newButton.removeAttribute('onclick');
            
            newButton.addEventListener('click', function(e) {
                e.preventDefault(); // ფორმის გადაგზავნის პრევენცია
                e.stopPropagation(); // იბლოკება კლიკის გავრცელება
                const emoji = this.textContent;
                if (emoji) {
                    addEmojiLocal(emoji);
                }
            });
        });
    }
    
    // ემოჯების სელექტორის გამოჩენა/დამალვა
    function toggleEmojiSelectorLocal() {
        const emojiSelector = document.getElementById('emojiSelector');
        if (!emojiSelector) return;
        
        if (emojiSelector.style.display === 'none' || emojiSelector.style.display === '') {
            emojiSelector.style.display = 'grid';
            initializeEmojiSelectorButtons();
        } else {
            emojiSelector.style.display = 'none';
        }
    }
    
    // ემოჯების გამოსაჩენი ღილაკისთვის ივენთ მსმენელის დამატება
    const toggleEmojiBtn = document.querySelector('.emoji-btn, #toggleEmojiBtn');
    if (toggleEmojiBtn) {
        // წავშალოთ ნებისმიერი არსებული onclick ატრიბუტი
        toggleEmojiBtn.removeAttribute('onclick');
        
        // წავშალოთ არსებული event listeners დუბლიკატების თავიდან ასაცილებლად
        const newToggleBtn = toggleEmojiBtn.cloneNode(true);
        toggleEmojiBtn.parentNode.replaceChild(newToggleBtn, toggleEmojiBtn);
        
        newToggleBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            toggleEmojiSelectorLocal();
        });
    }
    
// დოკუმენტზე კლიკის დამატება ემოჯის სელექტორის დასამალად
document.addEventListener('click', function(e) {
    const emojiSelector = document.getElementById('emojiSelector');
    
    // ასე ვპოულობთ ღილაკს ID-ით ან კლასით და ტექსტის შემოწმებით
    let toggleBtn = document.getElementById('toggleEmojiBtn');
    if (!toggleBtn) {
        const emojiButtons = document.querySelectorAll('.emoji-btn');
        for (const btn of emojiButtons) {
            if (btn.textContent.includes('➕')) {
                toggleBtn = btn;
                break;
            }
        }
    }
    
    if (emojiSelector && 
        emojiSelector.style.display !== 'none' && 
        !emojiSelector.contains(e.target) && 
        (!toggleBtn || !toggleBtn.contains(e.target))) {
        emojiSelector.style.display = 'none';
    }
});
    
    // თავდაპირველი ინიციალიზაცია
    initializeEmojiSelectorButtons();
    
    // ფაილის არჩევის ვიზუალური გაუმჯობესება
    const fileInput = document.getElementById('image');
    const fileLabel = document.querySelector('.custom-file-label');
    
    if (fileInput && fileLabel) {
        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                // ფაილის სახელის გამოჩენა არჩევის შემდეგ
                const fileName = this.files[0].name;
                fileLabel.innerHTML = `<i class="bi bi-file-earmark me-2"></i>${fileName}`;
            } else {
                // თუ ფაილი არ არის არჩეული, დაბრუნდეს საწყისი ტექსტი
                fileLabel.innerHTML = `<i class="bi bi-upload me-2"></i>აირჩიეთ ფაილი`;
            }
        });
    }
    
    // ვარსკვლავების რეიტინგის ვიზუალური ეფექტი
    const ratingInputs = document.querySelectorAll('.star-rating input');
    const ratingLabels = document.querySelectorAll('.star-rating label');
    
    if (ratingInputs.length > 0 && ratingLabels.length > 0) {
        ratingInputs.forEach(input => {
            input.addEventListener('change', function() {
                const rating = this.value;
                
                // ყველა ვარსკვლავის გასუფთავება
                ratingLabels.forEach(label => {
                    label.classList.remove('active');
                });
                
                // მონიშნული ვარსკვლავების აქტიურად დაყენება
                for (let i = 0; i < rating; i++) {
                    if (ratingLabels[4-i]) {
                        ratingLabels[4-i].classList.add('active');
                    }
                }
            });
        });
    }
    
// ფორმის გაგზავნის დადასტურების შეტყობინების მართვა
const commentForm = document.querySelector('form#commentForm');
if (commentForm) {
    commentForm.addEventListener('submit', function(e) {
        // ყველა სავალდებულო ველის შემოწმება
        const requiredFields = commentForm.querySelectorAll('[required]');
        let hasEmptyFields = false;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                hasEmptyFields = true;
                field.classList.add('is-invalid');
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        if (hasEmptyFields) {
            e.preventDefault();
            alert('გთხოვთ შეავსოთ ყველა სავალდებულო ველი');
            return false;
        }
    });
    
    // ველების შეცვლისას ვალიდაციის ნიშნების მოხსნა
    const formInputs = commentForm.querySelectorAll('input, textarea');
    formInputs.forEach(input => {
        input.addEventListener('input', function() {
            if (this.value.trim()) {
                this.classList.remove('is-invalid');
            }
        });
    });
}
    
    // წარმატების შეტყობინების ავტომატური დამალვა
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

// გლობალური ფუნქციები toggleEmojiSelector და addEmoji, რომლებიც HTML onclick-ისთვისაა საჭირო
function toggleEmojiSelector() {
    const emojiSelector = document.getElementById('emojiSelector');
    if (!emojiSelector) return;
    
    if (emojiSelector.style.display === 'none' || emojiSelector.style.display === '') {
        emojiSelector.style.display = 'grid';
    } else {
        emojiSelector.style.display = 'none';
    }
}

function addEmoji(emoji) {
    const textarea = document.getElementById('content');
    if (!textarea) return;
    
    const startPos = textarea.selectionStart;
    const endPos = textarea.selectionEnd;
    
    const textBeforeCursor = textarea.value.substring(0, startPos);
    const textAfterCursor = textarea.value.substring(endPos);
    textarea.value = textBeforeCursor + emoji + textAfterCursor;
    
    textarea.selectionStart = startPos + emoji.length;
    textarea.selectionEnd = startPos + emoji.length;
    
    textarea.focus();
}
document.addEventListener('DOMContentLoaded', function () {
    const slider = document.querySelector('.blog-slider');
    const sliderContainer = document.querySelector('.blog-slider-container');

    if (!slider || !sliderContainer) return;

    let isDragging = false;
    let startPosition = 0;
    let currentTranslate = 0;
    let prevTranslate = 0;
    let animationID = 0;
    let currentIndex = 0;
    let slideWidth = 0;
    let slidesPerView = 3;

    function setSlidePerView() {
        if (window.innerWidth < 576) {
            slidesPerView = 1;
        } else if (window.innerWidth < 992) {
            slidesPerView = 2;
        } else {
            slidesPerView = 3;
        }
        updateSlideWidth();
    }

    function updateSlideWidth() {
        const containerWidth = sliderContainer.getBoundingClientRect().width;
        slideWidth = containerWidth / slidesPerView;

        // დაკონფიგურებული track-ის და თითოეული slide-ის სიგანე
        const slides = slider.querySelectorAll('.blog-slider-item');
        slider.style.width = `${slides.length * slideWidth}px`;
        slides.forEach(slide => {
            slide.style.width = `${slideWidth}px`;
        });

        updateSliderConstraints();
        setSliderPosition();
    }

    function updateSliderConstraints() {
        const slides = slider.querySelectorAll('.blog-slider-item');
        const maxIndex = Math.max(0, slides.length - slidesPerView);

        if (currentIndex > maxIndex) {
            currentIndex = maxIndex;
        }
        currentTranslate = -currentIndex * slideWidth;
    }

    function setSliderPosition() {
        slider.style.transform = `translateX(${currentTranslate}px)`;
    }

    function animation() {
        setSliderPosition();
        if (isDragging) animationID = requestAnimationFrame(animation);
    }

    function touchStart(event) {
        if (event.target.closest('a') || event.target.closest('button')) return;
        const touch = event.type.includes('mouse') ? event : event.touches[0];
        isDragging = true;
        startPosition = touch.clientX;
        prevTranslate = currentTranslate;

        cancelAnimationFrame(animationID);
        animation();
        sliderContainer.style.cursor = 'grabbing';
    }

    function touchMove(event) {
        if (!isDragging) return;
        const touch = event.type.includes('mouse') ? event : event.touches[0];
        const currentPosition = touch.clientX;
        currentTranslate = prevTranslate + currentPosition - startPosition;

        const slides = slider.querySelectorAll('.blog-slider-item');
        const maxIndex = Math.max(0, slides.length - slidesPerView);
        const minTranslate = -maxIndex * slideWidth;

        currentTranslate = Math.max(minTranslate, Math.min(0, currentTranslate));
    }

    function touchEnd() {
        if (!isDragging) return;
        isDragging = false;
        cancelAnimationFrame(animationID);

        const movedBy = currentTranslate - prevTranslate;
        const slides = slider.querySelectorAll('.blog-slider-item');
        const maxIndex = Math.max(0, slides.length - slidesPerView);

        if (Math.abs(movedBy) > 50) {
            if (movedBy < 0) {
                currentIndex = Math.min(maxIndex, currentIndex + 1);
            } else {
                currentIndex = Math.max(0, currentIndex - 1);
            }
        }

        currentTranslate = -currentIndex * slideWidth;
        setSliderPosition();
        sliderContainer.style.cursor = 'grab';
    }

    // Events bound to container for proper drag
    sliderContainer.addEventListener('mousedown', touchStart);
    sliderContainer.addEventListener('mousemove', touchMove);
    document.addEventListener('mouseup', touchEnd);

    sliderContainer.addEventListener('touchstart', touchStart, { passive: false });
    sliderContainer.addEventListener('touchmove', e => {
        e.preventDefault();
        touchMove(e);
    }, { passive: false });
    sliderContainer.addEventListener('touchend', touchEnd);

    // Handle responsive resize
    window.addEventListener('resize', setSlidePerView);

    // ინტიანალიზაცია
    setSlidePerView();
});