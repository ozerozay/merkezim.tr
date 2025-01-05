import './bootstrap';

// Tema değiştirme fonksiyonu
Livewire.on('theme-changed', ({ theme }) => {
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('theme', theme);
});

// Dark Mode Toggle Function
function toggleDarkMode() {
    const currentTheme = localStorage.getItem('theme') || 'morTema';
    const themeMap = {
        // Light -> Dark
        'morTema': 'morTemaDark',
        'lavanderTema': 'lavanderTemaDark',
        'mintTema': 'mintTemaDark',
        // Dark -> Light
        'morTemaDark': 'morTema',
        'lavanderTemaDark': 'lavanderTema',
        'mintTemaDark': 'mintTema',
    };
    
    // İkonları değiştir
    document.querySelector('.light-mode-icon').classList.toggle('hidden');
    document.querySelector('.dark-mode-icon').classList.toggle('hidden');
    
    // Eğer mevcut tema dark/light karşılığı varsa onu kullan
    const newTheme = themeMap[currentTheme] || 'morTema';
    
    // Yeni temayı uygula
    document.documentElement.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);
}

// Sayfa yüklendiğinde doğru ikonu göster
document.addEventListener('DOMContentLoaded', () => {
    const savedTheme = localStorage.getItem('theme') || 'morTema';
    document.documentElement.setAttribute('data-theme', savedTheme);
});
