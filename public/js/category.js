document.addEventListener('DOMContentLoaded', function() {
    const gridButton = document.querySelector('.view-mode[data-view="grid"]');
    const listButton = document.querySelector('.view-mode[data-view="list"]');
    const productsContainer = document.querySelector('.row.g-3');

    if (gridButton && listButton && productsContainer) {
        const currentView = localStorage.getItem('productView') || 'grid';

        if (currentView === 'list') {
            productsContainer.classList.remove('products-grid');
            productsContainer.classList.add('products-list');
            listButton.classList.add('active', 'btn-secondary');
            listButton.classList.remove('btn-outline-secondary');
            gridButton.classList.remove('active', 'btn-secondary');
            gridButton.classList.add('btn-outline-secondary');
        } else {
            productsContainer.classList.add('products-grid');
            productsContainer.classList.remove('products-list');
            gridButton.classList.add('active', 'btn-secondary');
            gridButton.classList.remove('btn-outline-secondary');
            listButton.classList.remove('active', 'btn-secondary');
            listButton.classList.add('btn-outline-secondary');
        }

        gridButton.addEventListener('click', function() {
            productsContainer.classList.remove('products-list');
            productsContainer.classList.add('products-grid');

            gridButton.classList.add('active', 'btn-secondary');
            gridButton.classList.remove('btn-outline-secondary');

            listButton.classList.remove('active', 'btn-secondary');
            listButton.classList.add('btn-outline-secondary');

            localStorage.setItem('productView', 'grid');
        });

        listButton.addEventListener('click', function() {
            productsContainer.classList.remove('products-grid');
            productsContainer.classList.add('products-list');

            listButton.classList.add('active', 'btn-secondary');
            listButton.classList.remove('btn-outline-secondary');

            gridButton.classList.remove('active', 'btn-secondary');
            gridButton.classList.add('btn-outline-secondary');

            localStorage.setItem('productView', 'list');
        });
    }
});
