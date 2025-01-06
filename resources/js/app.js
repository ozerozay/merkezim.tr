import './bootstrap';

// Dark Mode Toggle Function
window.toggleDarkMode = function() {
    const currentTheme = localStorage.getItem('theme') || 'morTema';
    const themeMap = {
        // Light -> Dark
        'morTema': 'morTemaDark',
        'lavanderTema': 'lavanderTemaDark',
        'mintTema': 'mintTemaDark',
        'peachTema': 'morTemaDark',     
        'skyTema': 'morTemaDark',
        'roseTema': 'morTemaDark',
        'sunsetTema': 'morTemaDark',
        // Dark -> Light
        'morTemaDark': 'morTema',
        'lavanderTemaDark': 'lavanderTema',
        'mintTemaDark': 'mintTema'
    };
    
    // DOM elementlerini güvenli bir şekilde seç
    const lightIcon = document.querySelector('.light-mode-icon');
    const darkIcon = document.querySelector('.dark-mode-icon');
    
    if (lightIcon && darkIcon) {
        lightIcon.classList.toggle('hidden');
        darkIcon.classList.toggle('hidden');
    }
    
    // Eğer mevcut tema dark/light karşılığı varsa onu kullan
    const newTheme = themeMap[currentTheme] || 'morTema';
    
    // Yeni temayı uygula
    document.documentElement.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);

    // Livewire event'ini tetikle
    if (window.Livewire) {
        Livewire.dispatch('theme-changed', { theme: newTheme });
    }
};

// Sayfa yüklendiğinde son seçilen temayı yükle
document.addEventListener('DOMContentLoaded', () => {
    const savedTheme = localStorage.getItem('theme') || 'morTema';
    document.documentElement.setAttribute('data-theme', savedTheme);
    
    // DOM elementlerini güvenli bir şekilde seç
    const lightIcon = document.querySelector('.light-mode-icon');
    const darkIcon = document.querySelector('.dark-mode-icon');
    
    if (lightIcon && darkIcon) {
        const isDark = savedTheme.includes('Dark');
        lightIcon.classList.toggle('hidden', isDark);
        darkIcon.classList.toggle('hidden', !isDark);
    }
});

// Livewire tema değişikliği event listener'ı
if (window.Livewire) {
    Livewire.on('theme-changed', ({ theme }) => {
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem('theme', theme);
        
        // DOM elementlerini güvenli bir şekilde seç
        const lightIcon = document.querySelector('.light-mode-icon');
        const darkIcon = document.querySelector('.dark-mode-icon');
        
        if (lightIcon && darkIcon) {
            const isDark = theme.includes('Dark');
            lightIcon.classList.toggle('hidden', isDark);
            darkIcon.classList.toggle('hidden', !isDark);
        }
    });
}
