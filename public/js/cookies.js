// Self-invoking function to create a private scope and avoid global variable conflicts.
(function() {
    // --- 1. DOM Element Selection ---
    // Select all necessary elements once and store them in constants.
    const banner = document.getElementById('cookieBanner');
    const modal = document.getElementById('cookieModal');
    const analyticsToggle = document.getElementById('analytics');

    // --- 2. Core Functions ---

    /**
     * Updates Google Consent Mode based on user's choice.
     * @param {boolean} analyticsGranted - Whether analytics cookies are granted.
     */
    function updateGtagConsent(analyticsGranted) {
        if (typeof gtag !== 'function') {
            console.warn('gtag function not found. Google Analytics consent not updated.');
            return;
        }
        gtag('consent', 'update', {
            'analytics_storage': analyticsGranted ? 'granted' : 'denied',
            'ad_storage': 'denied' // Assuming ad_storage is not used or always denied.
        });
    }

    /**
     * Proactively deletes Google Analytics cookies.
     * This is a great privacy-enhancing feature from your code.
     */
    function deleteGaCookies() {
        const domain = window.location.hostname;
        document.cookie = `_ga=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; domain=.${domain}`;
        document.cookie = `_gid=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; domain=.${domain}`;
        // Replace G-0VJEBWMJL1 with your actual Measurement ID if it changes.
        document.cookie = `_ga_G-0VJEBWMJL1=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; domain=.${domain}`;
    }

    /**
     * Hides the cookie banner with a fade-out effect.
     */
    function hideBanner() {
        if (!banner) return;
        // The CSS should handle the transition.
        banner.classList.remove('show');
    }

    /**
     * Hides the cookie modal.
     */
    function hideModal() {
        if (!modal) return;
        modal.classList.remove('show');
    }

    /**
     * Handles the "Accept All" action.
     */
    function handleAccept() {
        localStorage.setItem('cookie_consent_status', 'accepted');
        updateGtagConsent(true);
        hideBanner();
        hideModal();
    }

    /**
     * Handles the "Decline All" action.
     */
    function handleDecline() {
        localStorage.setItem('cookie_consent_status', 'declined');
        updateGtagConsent(false);
        deleteGaCookies(); // Proactively delete GA cookies on decline.
        hideBanner();
        hideModal();
    }

    /**
     * Saves the custom settings from the modal.
     */
    function handleSaveSettings() {
        if (!analyticsToggle) return;
        
        localStorage.setItem('cookie_consent_status', 'custom');
        
        if (analyticsToggle.checked) {
            localStorage.setItem('cookie_analytics_consent', 'true');
            updateGtagConsent(true);
        } else {
            localStorage.setItem('cookie_analytics_consent', 'false');
            updateGtagConsent(false);
            deleteGaCookies();
        }
        hideModal();
    }

    /**
     * Opens the settings modal.
     */
    function handleOpenSettings() {
        hideBanner(); // Hide banner if it's open
        if (modal) modal.classList.add('show');
    }


    // --- 3. Event Listeners and Initialization ---

    document.addEventListener('DOMContentLoaded', function() {
        // Make functions globally accessible for the `onclick` attributes in the HTML.
        // This is a safe way to expose them from within our private scope.
        window.acceptCookies = handleAccept;
        window.declineCookies = handleDecline;
        window.openCookieSettings = handleOpenSettings;
        window.closeCookieSettings = hideModal; // Re-use hideModal function
        window.saveSettings = handleSaveSettings;

        // Add event listener to close modal on background click (from your code, great idea!)
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    hideModal();
                }
            });
        }
        
        // --- Main Initialization Logic ---
        const consentStatus = localStorage.getItem('cookie_consent_status');

        if (consentStatus) {
            // User has already made a choice. Apply their settings silently.
            let analyticsConsent = false;
            if (consentStatus === 'accepted') {
                analyticsConsent = true;
            } else if (consentStatus === 'custom') {
                analyticsConsent = localStorage.getItem('cookie_analytics_consent') === 'true';
            }
            updateGtagConsent(analyticsConsent);
            if (analyticsToggle) analyticsToggle.checked = analyticsConsent;

        } else {
            // User is visiting for the first time. Show the banner after a short delay.
            if (banner) {
                setTimeout(() => banner.classList.add('show'), 500);
            }
        }
    });

})(); // Immediately execute the function.