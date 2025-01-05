import './bootstrap';

// Global olarak toggleDarkMode fonksiyonunu tanımlayalım
window.toggleDarkMode = function() {
    const currentTheme = localStorage.getItem('theme') || 'morTema';
    const themeMap = {
        // Light -> Dark
        'morTema': 'morTemaDark',
        'lavanderTema': 'lavanderTemaDark',
        'mintTema': 'mintTemaDark',
        'peachTema': 'morTemaDark',     // Dark karşılığı olmayan temalar için varsayılan
        'skyTema': 'morTemaDark',
        'roseTema': 'morTemaDark',
        'sunsetTema': 'morTemaDark',
        // Dark -> Light
        'morTemaDark': 'morTema',
        'lavanderTemaDark': 'lavanderTema',
        'mintTemaDark': 'mintTema'
    };
    
    // İkonları değiştir
    document.querySelector('.light-mode-icon').classList.toggle('hidden');
    document.querySelector('.dark-mode-icon').classList.toggle('hidden');
    
    // Eğer mevcut tema dark/light karşılığı varsa onu kullan
    const newTheme = themeMap[currentTheme] || 'morTema';
    
    // Yeni temayı uygula
    document.documentElement.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);

    // Livewire event'ini tetikle
    Livewire.dispatch('theme-changed', { theme: newTheme });
};

// Sayfa yüklendiğinde son seçilen temayı yükle
document.addEventListener('DOMContentLoaded', () => {
    const savedTheme = localStorage.getItem('theme') || 'morTema';
    document.documentElement.setAttribute('data-theme', savedTheme);
    
    // Dark/Light mode ikonlarını güncelle
    const isDark = savedTheme.includes('Dark');
    document.querySelector('.light-mode-icon').classList.toggle('hidden', isDark);
    document.querySelector('.dark-mode-icon').classList.toggle('hidden', !isDark);
});

// Livewire tema değişikliği event listener'ı
Livewire.on('theme-changed', ({ theme }) => {
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('theme', theme);
    
    // Dark/Light mode ikonlarını güncelle
    const isDark = theme.includes('Dark');
    document.querySelector('.light-mode-icon').classList.toggle('hidden', isDark);
    document.querySelector('.dark-mode-icon').classList.toggle('hidden', !isDark);
});
