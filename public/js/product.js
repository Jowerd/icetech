// public/js/product.js
document.addEventListener('DOMContentLoaded', function() {
    // Show more products functionality
    const showMoreButton = document.querySelector('.show-more-products');
    if (showMoreButton) {
        showMoreButton.addEventListener('click', function() {
            const container = document.querySelector('.similar-products-container');
            container.classList.toggle('show-all');
            this.classList.toggle('expanded');
        });
    }
    
    // Show more description functionality
    const showMoreDescriptionButton = document.querySelector('.show-more-description');
    if (showMoreDescriptionButton) {
        showMoreDescriptionButton.addEventListener('click', function() {
            const descriptionWrapper = document.querySelector('.product-description-wrapper');
            const descriptionGradient = document.querySelector('.description-gradient');
            
            // Toggle classes
            descriptionWrapper.classList.toggle('description-collapsed');
            descriptionWrapper.classList.toggle('description-expanded');
            this.classList.toggle('expanded');
            
            // Fade out gradient when expanded
            if (descriptionWrapper.classList.contains('description-expanded')) {
                descriptionGradient.style.opacity = '0';
            } else {
                descriptionGradient.style.opacity = '1';
            }
        });
    }
    
    // ბმულის კოპირების ფუნქციონალი
    const copyLinkButton = document.querySelector('.copy-link');
    
    if (copyLinkButton) {
        copyLinkButton.addEventListener('click', function() {
            const url = this.getAttribute('data-url');
            
            // ბმულის კოპირება
            navigator.clipboard.writeText(url).then(function() {
                // დროებითი ტექსტის ჩვენება კოპირების შემდეგ
                const originalTitle = copyLinkButton.getAttribute('title');
                copyLinkButton.setAttribute('title', 'დაკოპირებულია!');
                copyLinkButton.classList.add('copied');
                
                // ღილაკის იკონის ცვლილება
                const icon = copyLinkButton.querySelector('i');
                icon.classList.remove('fa-link');
                icon.classList.add('fa-check');
                
                // 2 წამის შემდეგ დაბრუნება საწყის მდგომარეობაში
                setTimeout(function() {
                    copyLinkButton.setAttribute('title', originalTitle);
                    copyLinkButton.classList.remove('copied');
                    icon.classList.remove('fa-check');
                    icon.classList.add('fa-link');
                }, 2000);
            }).catch(function() {
                console.error('ბმულის კოპირება ვერ მოხერხდა');
            });
        });
    }
    
});