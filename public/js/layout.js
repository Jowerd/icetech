document.addEventListener('DOMContentLoaded', function() {
    // CSRF token setup for AJAX requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    // მობილური მენიუს მართვა
    const navbarToggler = document.querySelector('.navbar-toggler');
    const mobileMenu = document.querySelector('.mobile-menu');
    const header = document.querySelector('.header');

    // მენიუს გახსნა/დახურვის ფუნქცია
    function toggleMobileMenu() {
        mobileMenu.classList.toggle('show');
        if (mobileMenu.classList.contains('show')) {
            navbarToggler.style.backgroundColor = 'rgba(255, 255, 255, 0.2)';
            // როცა მენიუ იხსნება, ჰედერი ყოველთვის უნდა ჩანდეს
            header.classList.remove('hidden');
        } else {
            navbarToggler.style.backgroundColor = 'rgba(255, 255, 255, 0.1)';
        }
    }

    // მენიუს ღილაკზე დაჭერა
    navbarToggler.addEventListener('click', function(e) {
        e.stopPropagation(); // შეაჩერე პროპაგაცია
        toggleMobileMenu();
    });

    // მენიუს დახურვა ლინკზე დაჭერისას
    const mobileMenuLinks = mobileMenu.querySelectorAll('.nav-link');
    mobileMenuLinks.forEach(link => {
        link.addEventListener('click', function() {
            mobileMenu.classList.remove('show');
            navbarToggler.style.backgroundColor = 'rgba(255, 255, 255, 0.1)';
        });
    });

    // მენიუს დახურვა დოკუმენტზე დაჭერისას
    document.addEventListener('click', function(e) {
        if (mobileMenu.classList.contains('show') && 
            !mobileMenu.contains(e.target) && 
            e.target !== navbarToggler) {
            mobileMenu.classList.remove('show');
            navbarToggler.style.backgroundColor = 'rgba(255, 255, 255, 0.1)';
        }
    });

    // ჰედერის სქროლის ლოგიკა
    let lastScroll = window.pageYOffset || document.documentElement.scrollTop;
    let accumulated = 0;
    const threshold = 100;
    let ticking = false;

    function updateMobileMenuPosition() {
        // მობილური მენიუს პოზიციის განახლება ჰედერის მდგომარეობის მიხედვით
        if (header.classList.contains('hidden')) {
            // თუ ჰედერი დამალულია, მენიუც უნდა დაიმალოს
            mobileMenu.style.top = '0';
            mobileMenu.classList.remove('show');
            navbarToggler.style.backgroundColor = 'rgba(255, 255, 255, 0.1)';
        } else {
            // ჰედერის სიმაღლის მიხედვით განვსაზღვროთ მობილური მენიუს პოზიცია
            const headerHeight = header.offsetHeight;
            mobileMenu.style.top = headerHeight + 'px';
        }
    }

    function onScroll() {
        const currentScroll = window.pageYOffset || document.documentElement.scrollTop;

        // თუ მობილური მენიუ გახსნილია, არ დავმალოთ ჰედერი სქროლისას
        if (mobileMenu.classList.contains('show')) {
            header.classList.remove('hidden');
            lastScroll = currentScroll;
            ticking = false;
            return;
        }

        // თუ გვერდის თავში ვართ, ჰედერი ყოველთვის ჩანს
        if (currentScroll <= 0) {
            header.classList.remove('hidden');
            accumulated = 0;
            lastScroll = currentScroll;
            ticking = false;
            updateMobileMenuPosition();
            return;
        }

        const delta = currentScroll - lastScroll;

        if (delta > 0) { // ქვემოთ სქროლი
            accumulated += delta;
            if (accumulated > threshold) {
                header.classList.add('hidden');
                updateMobileMenuPosition();
            }
        } else if (delta < 0) { // ზემოთ სქროლი
            header.classList.remove('hidden');
            accumulated = 0;
            updateMobileMenuPosition();
        }

        lastScroll = currentScroll;
        ticking = false;
    }

    window.addEventListener('scroll', function() {
        if (!ticking) {
            window.requestAnimationFrame(onScroll);
            ticking = true;
        }
    });

    // საწყისი პოზიციის დაყენება
    updateMobileMenuPosition();

    // ფანჯრის ზომის ცვლილებაზე რეაგირება
    window.addEventListener('resize', function() {
        updateMobileMenuPosition();
    });

    // AUTOCOMPLETE ფუნქცია
    const searchInput = document.querySelector('.searchInput');
    const searchSuggestions = document.getElementById('searchSuggestions');
    let currentFocus = -1;
    let searchTimeout;

    if (searchInput && searchSuggestions) {
        // შემავალი ტექსტის მოძებნა
        searchInput.addEventListener('input', function() {
            const query = this.value.trim();
            
            // წაშალე ძველი timeout
            clearTimeout(searchTimeout);
            
            if (query.length < 2) {
                hideSuggestions();
                return;
            }

            // დაყოვნება AJAX request-ისთვის (დებაუნსი)
            searchTimeout = setTimeout(() => {
                fetchSuggestions(query);
            }, 300);
        });

        // კლავიატურის ნავიგაცია
        searchInput.addEventListener('keydown', function(e) {
            const suggestions = searchSuggestions.querySelectorAll('.suggestion-item');
            
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                currentFocus++;
                if (currentFocus >= suggestions.length) currentFocus = 0;
                addActive(suggestions);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                currentFocus--;
                if (currentFocus < 0) currentFocus = suggestions.length - 1;
                addActive(suggestions);
            } else if (e.key === 'Enter') {
                if (currentFocus > -1 && suggestions[currentFocus]) {
                    e.preventDefault();
                    suggestions[currentFocus].click();
                }
            } else if (e.key === 'Escape') {
                hideSuggestions();
                currentFocus = -1;
            }
        });

        // ფოკუსის დაკარგვისას suggestions-ის დამალვა (დაყოვნებით)
        searchInput.addEventListener('blur', function() {
            setTimeout(() => {
                hideSuggestions();
            }, 200);
        });

        // ფოკუსისას suggestions-ის ჩვენება (თუ არის შედეგები)
        searchInput.addEventListener('focus', function() {
            if (this.value.trim().length >= 2 && searchSuggestions.children.length > 0) {
                showSuggestions();
            }
        });
    }

    // AJAX მოთხოვნა suggestions-ისთვის
    function fetchSuggestions(query) {
        // თუ არ არის Laravel route, შექმენი შესაბამისი endpoint
        const url = '/api/products/suggestions'; // ეს route უნდა შექმნათ Laravel-ში
        
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ query: query })
        })
        .then(response => response.json())
        .then(data => {
            displaySuggestions(data.suggestions || []);
        })
        .catch(error => {
            console.error('Search suggestions error:', error);
            hideSuggestions();
        });
    }

// Suggestions-ის ჩვენება განახლებული ვერსია
function displaySuggestions(suggestions) {
    searchSuggestions.innerHTML = '';
    currentFocus = -1;

    if (suggestions.length === 0) {
        hideSuggestions();
        return;
    }

    suggestions.forEach((suggestion, index) => {
        const div = document.createElement('div');
        div.className = 'suggestion-item';
        
        // განსხვავებული HTML სტრუქტურა იმისდა მიხედვით, პროდუქტია თუ კატეგორია
        if (suggestion.type === 'product') {
            div.innerHTML = `
                <div class="suggestion-content-with-image">
                    <div class="suggestion-image">
                        <img src="${suggestion.image}" alt="${suggestion.name}" onerror="this.src='${getDefaultImage()}'" loading="lazy">
                    </div>
                    <div class="suggestion-details">
                        <div class="suggestion-name">${highlightMatch(suggestion.name, searchInput.value)}</div>
                        ${suggestion.category ? `<div class="suggestion-category">${suggestion.category}</div>` : ''}
                        ${suggestion.formatted_price ? `<div class="suggestion-price">${suggestion.formatted_price}</div>` : ''}
                    </div>
                </div>
            `;
        } else {
            // კატეგორიისთვის
            div.innerHTML = `
                <div class="suggestion-content-with-image">
                    <div class="suggestion-image">
                        <img src="${suggestion.image}" alt="${suggestion.name}" onerror="this.src='${getDefaultImage()}'" loading="lazy">
                    </div>
                    <div class="suggestion-details">
                        <div class="suggestion-name">${highlightMatch(suggestion.name, searchInput.value)}</div>
                        <div class="suggestion-category">${suggestion.category}</div>
                    </div>
                </div>
            `;
        }
        
        div.addEventListener('click', function() {
            searchInput.value = suggestion.name;
            hideSuggestions();
            // Navigate to product/category URL
            window.location.href = suggestion.url;
        });

        searchSuggestions.appendChild(div);
    });

    showSuggestions();
}
// Default image function
function getDefaultImage() {
    return '/images/no-image.jpg'; // შეცვალეთ თქვენი default image path-ით
}

// Lazy loading for suggestion images
function setupLazyLoadingForSuggestions() {
    if ("IntersectionObserver" in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        imageObserver.unobserve(img);
                    }
                }
            });
        });

        // Observe all suggestion images
        document.querySelectorAll('.suggestion-image img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }
}

    // ტექსტში მატჩის გამოკვეთა
    function highlightMatch(text, query) {
        if (!query) return text;
        const regex = new RegExp(`(${query})`, 'gi');
        return text.replace(regex, '<strong>$1</strong>');
    }

    // აქტიური suggestion-ის მონიშვნა
    function addActive(suggestions) {
        if (!suggestions) return false;
        removeActive(suggestions);
        if (currentFocus >= suggestions.length) currentFocus = 0;
        if (currentFocus < 0) currentFocus = suggestions.length - 1;
        suggestions[currentFocus].classList.add('suggestion-active');
    }

    // აქტიური კლასის წაშლა
    function removeActive(suggestions) {
        for (let i = 0; i < suggestions.length; i++) {
            suggestions[i].classList.remove('suggestion-active');
        }
    }

    // Suggestions-ის ჩვენება
    function showSuggestions() {
        searchSuggestions.style.display = 'block';
        searchSuggestions.classList.add('show');
    }

    // Suggestions-ის დამალვა
    function hideSuggestions() {
        searchSuggestions.style.display = 'none';
        searchSuggestions.classList.remove('show');
        currentFocus = -1;
    }

    // საძიებო ტექსტის ანიმაცია (placeholder)
    const searchTerms = [
        "მაცივრები",
        "შოკ-მაცივრები",
        "საყინულე მაცივრები",
        "დახლ-მაცივრები",
        "მეტალის თაროები",
        "უჟანგავი ფოლადი & მაგიდა ნიჟარები",
        "კულინარიული ღუმელები",
        "ცომის საზელები & მიქსერები",
        "ხორცის & ძვლის ხერხი",
        "ხორცსაკეპები",
        "მაცივრის ძრავები",
        "მაცივრის ამაორთქლებელები",
        "მაცივრის კონდენსატორები",
        "ხინკლის ქვაბები",
        "ფრის შესაწვავი აპარატი",
        "გრილი",
        "ცომის ასაფუებლები",
        "პურის საჭრელები",
        "კაფე & ბარის ინვენტარი",
        "უჟანგავი ფოლადის ინვენტარი"
    ];

    const searchInputs = document.querySelectorAll('.searchInput');
    let currentIndex = 0;
    let currentText = '';
    let isDeleting = false;
    let typingSpeed = 100;
    let pauseTime = 2000;

    function typeEffect() {
        const currentTerm = searchTerms[currentIndex];

        if (isDeleting) {
            currentText = currentTerm.substring(0, currentText.length - 1);
            typingSpeed = 50;
        } else {
            currentText = currentTerm.substring(0, currentText.length + 1);
            typingSpeed = 100;
        }

        // განვაახლოთ placeholder მხოლოდ იმ შემთხვევაში, თუ input არ არის ფოკუსში
        searchInputs.forEach(input => {
            if (document.activeElement !== input && !input.value) {
                input.setAttribute('placeholder', currentText);
            }
        });

        if (!isDeleting && currentText === currentTerm) {
            isDeleting = true;
            typingSpeed = pauseTime;
        } else if (isDeleting && currentText === '') {
            isDeleting = false;
            currentIndex = (currentIndex + 1) % searchTerms.length;
        }

        setTimeout(typeEffect, typingSpeed);
    }

    if (searchInputs.length > 0) {
        typeEffect();
    }

    // Input focus/blur events for placeholder
    searchInputs.forEach(input => {
        input.addEventListener('focus', function() {
            if (!this.value) {
                this.setAttribute('placeholder', 'პროდუქტის ძიება...');
            }
        });
        input.addEventListener('blur', function() {
            if (!this.value) {
                this.setAttribute('placeholder', currentText);
            }
        });
    });

    // Document click to hide suggestions
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchSuggestions.contains(e.target)) {
            hideSuggestions();
        }
    });

    // Lazy loading for images
    document.addEventListener("DOMContentLoaded", function() {
        var lazyImages = [].slice.call(document.querySelectorAll("img.lazy"));
        
        if ("IntersectionObserver" in window) {
            let lazyImageObserver = new IntersectionObserver(function(entries, observer) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        let lazyImage = entry.target;
                        lazyImage.src = lazyImage.dataset.src;
                        lazyImage.classList.remove("lazy");
                        lazyImageObserver.unobserve(lazyImage);
                    }
                });
            });

            lazyImages.forEach(function(lazyImage) {
                lazyImageObserver.observe(lazyImage);
            });
        }
    });
});