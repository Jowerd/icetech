// ქუქების მართვის სკრიპტი
document.addEventListener('DOMContentLoaded', function() {
    // ქუქის ბანერის ჩვენება თუ არ არის თანხმობა
    if (!getCookieConsent()) {
        setTimeout(function() {
            document.getElementById('cookieBanner').classList.add('show');
        }, 1000);
    }
    
    // Google Analytics-ის ინიციალიზება თუ არის თანხმობა
    if (getCookieConsent() === 'accepted') {
        initGoogleAnalytics();
    }
});

// ქუქების მიღება
function acceptCookies() {
    setCookieConsent('accepted');
    hideCookieBanner();
    initGoogleAnalytics();
}

// ქუქების უარყოფა
function declineCookies() {
    setCookieConsent('declined');
    hideCookieBanner();
    disableGoogleAnalytics();
}

// ქუქის ბანერის დამალვა
function hideCookieBanner() {
    const banner = document.getElementById('cookieBanner');
    banner.classList.add('hide');
    setTimeout(function() {
        banner.style.display = 'none';
    }, 400);
}

// ქუქის პარამეტრების გახსნა
function openCookieSettings() {
    document.getElementById('cookieModal').classList.add('show');
}

// ქუქის პარამეტრების დახურვა
function closeCookieSettings() {
    document.getElementById('cookieModal').classList.remove('show');
}

// პარამეტრების შენახვა
function saveSettings() {
    const analyticsEnabled = document.getElementById('analytics').checked;
    
    if (analyticsEnabled) {
        setCookieConsent('accepted');
        initGoogleAnalytics();
    } else {
        setCookieConsent('declined');
        disableGoogleAnalytics();
    }
    
    closeCookieSettings();
    hideCookieBanner();
}

// ქუქის თანხმობის მოძებნა
function getCookieConsent() {
    return localStorage.getItem('cookie_consent');
}

// ქუქის თანხმობის შენახვა
function setCookieConsent(consent) {
    localStorage.setItem('cookie_consent', consent);
}

// Google Analytics-ის ინიციალიზება
function initGoogleAnalytics() {
    if (typeof gtag !== 'undefined') {
        gtag('consent', 'update', {
            'analytics_storage': 'granted'
        });
    }
}

// Google Analytics-ის გათიშვა
function disableGoogleAnalytics() {
    if (typeof gtag !== 'undefined') {
        gtag('consent', 'update', {
            'analytics_storage': 'denied'
        });
    }
    
    // Google Analytics კუკის წაშლა
    document.cookie = '_ga=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
    document.cookie = '_ga_G-0VJEBWMJL1=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
}

// მოდალის დახურვა ფონზე დაჭერით
document.addEventListener('click', function(e) {
    const modal = document.getElementById('cookieModal');
    if (e.target === modal) {
        closeCookieSettings();
    }
});