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

// Tema değişimi için event listener
window.addEventListener('theme-changed', (event) => {
    const theme = event.detail.theme;
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('theme', theme);
});

// Sayfa yüklendiğinde tema kontrolü
document.addEventListener('DOMContentLoaded', () => {
    const savedTheme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', savedTheme);
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
