document.addEventListener('DOMContentLoaded', function() {
    
    // --- 1. Custom Dropdown-ების (Select) გენერაცია ---
    const selects = document.querySelectorAll('.custom-select-styled');
    
    selects.forEach(select => {
        // ვქმნით მთავარ შესაფუთ კონტეინერს
        const wrapper = document.createElement('div');
        wrapper.className = 'custom-select-wrapper';
        select.parentNode.insertBefore(wrapper, select);
        wrapper.appendChild(select);
        
        // ვმალავთ სტანდარტულ სელექტს
        select.style.display = 'none';

        // ვქმნით ხილულ ღილაკს
        const trigger = document.createElement('div');
        trigger.className = 'custom-select-trigger';
        const selectedText = select.options[select.selectedIndex]?.text || '';
        trigger.innerHTML = `<span class="selected-text">${selectedText}</span><i class="bi bi-chevron-down"></i>`;
        wrapper.appendChild(trigger);

        // ვქმნით ჩამოსაშლელ სიას (Options)
        const optionsList = document.createElement('div');
        optionsList.className = 'custom-select-options';

        Array.from(select.options).forEach(option => {
            const optDiv = document.createElement('div');
            optDiv.className = 'custom-option' + (option.selected ? ' selected' : '');
            optDiv.textContent = option.text;
            optDiv.dataset.value = option.value;
            
            optDiv.addEventListener('click', function(e) {
                e.stopPropagation(); 
                
                // ვცვლით ნამდვილი სელექტის მნიშვნელობას ფორმისთვის
                select.value = this.dataset.value;
                
                // ტექსტის განახლება ღილაკზე
                trigger.querySelector('.selected-text').textContent = this.textContent;
                
                // კლასების განახლება
                optionsList.querySelectorAll('.custom-option').forEach(o => o.classList.remove('selected'));
                this.classList.add('selected');
                
                // სიის დახურვა
                wrapper.classList.remove('open');
            });
            
            optionsList.appendChild(optDiv);
        });

        wrapper.appendChild(optionsList);

        // ღილაკზე კლიკის ივენთი
        trigger.addEventListener('click', function(e) {
            e.stopPropagation();
            // ვხურავთ ყველა სხვა ღია დროფდაუნს
            document.querySelectorAll('.custom-select-wrapper').forEach(w => {
                if (w !== wrapper) w.classList.remove('open');
            });
            wrapper.classList.toggle('open');
        });
    });

    // ეკრანის ნებისმიერ სხვა ადგილას კლიკისას დროფდაუნების დახურვა
    document.addEventListener('click', function() {
        document.querySelectorAll('.custom-select-wrapper').forEach(w => w.classList.remove('open'));
    });


    // --- 2. ფასის ორმაგი სკალის მართვის ფუნქცია ---
    function initDualSlider(minRangeId, maxRangeId, displayId, trackId, hiddenMinId, hiddenMaxId) {
        const minRange = document.getElementById(minRangeId);
        const maxRange = document.getElementById(maxRangeId);
        const display = document.getElementById(displayId);
        const track = document.getElementById(trackId);
        const hiddenMin = document.getElementById(hiddenMinId);
        const hiddenMax = document.getElementById(hiddenMaxId);

        if (!minRange || !maxRange) return;

        const updateSlider = (e) => {
            let minVal = parseInt(minRange.value);
            let maxVal = parseInt(maxRange.value);

            if (minVal > maxVal - 10) {
                if (e && e.target.id === minRangeId) {
                    minRange.value = maxVal - 10;
                    minVal = maxVal - 10;
                } else {
                    maxRange.value = minVal + 10;
                    maxVal = minVal + 10;
                }
            }

            display.textContent = minVal + ' ₾ - ' + maxVal + ' ₾';
            hiddenMin.value = minVal;
            hiddenMax.value = maxVal;

            const absMin = parseInt(minRange.min);
            const absMax = parseInt(minRange.max);
            const rangeDiff = (absMax - absMin) === 0 ? 1 : (absMax - absMin);
            
            const percent1 = ((minVal - absMin) / rangeDiff) * 100;
            const percent2 = ((maxVal - absMin) / rangeDiff) * 100;
            
            track.style.background = `linear-gradient(to right, #e2e8f0 ${percent1}%, #00a4bd ${percent1}%, #00a4bd ${percent2}%, #e2e8f0 ${percent2}%)`;
        };

        minRange.addEventListener('input', updateSlider);
        maxRange.addEventListener('input', updateSlider);
        updateSlider();
    }

    initDualSlider('desktopMinRange', 'desktopMaxRange', 'desktopPriceDisplay', 'desktopSliderTrack', 'desktopMinPriceHidden', 'desktopMaxPriceHidden');
    initDualSlider('mobileMinRange', 'mobileMaxRange', 'mobilePriceDisplay', 'mobileSliderTrack', 'mobileMinPriceHidden', 'mobileMaxPriceHidden');


    // --- 3. Grid და List ხედის შეცვლა ---
    const viewBtns = document.querySelectorAll('.btn-view-mode');
    const pContainer = document.getElementById('productsContainer');

    if (viewBtns.length > 0 && pContainer) {
        const savedView = localStorage.getItem('catViewMode');
        
        if (savedView === 'list') {
            pContainer.classList.remove('view-grid');
            pContainer.classList.add('view-list');
            updateActiveBtn('list');
        }

        viewBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const view = this.getAttribute('data-view');
                if (view === 'list') {
                    pContainer.classList.remove('view-grid');
                    pContainer.classList.add('view-list');
                    localStorage.setItem('catViewMode', 'list');
                } else {
                    pContainer.classList.remove('view-list');
                    pContainer.classList.add('view-grid');
                    localStorage.setItem('catViewMode', 'grid');
                }
                updateActiveBtn(view);
            });
        });

        function updateActiveBtn(activeView) {
            viewBtns.forEach(b => {
                b.classList.remove('active');
                if (b.getAttribute('data-view') === activeView) {
                    b.classList.add('active');
                }
            });
        }
    }
});