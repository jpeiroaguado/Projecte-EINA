function toggleDarkMode() {
    document.documentElement.classList.toggle('dark');
    const isDark = document.documentElement.classList.contains('dark');
    localStorage.setItem('theme', isDark ? 'dark' : 'light');

    const icon = document.getElementById('theme-icon');
    if (icon) icon.textContent = isDark ? '🌞': '🌙' ;
}

// Execute quan el DOM esta carregat
window.addEventListener('DOMContentLoaded', () => {
    const icon = document.getElementById('theme-icon');
    const isDark = localStorage.getItem('theme') === 'dark';

    if (isDark) {
        document.documentElement.classList.add('dark');
        if (icon) icon.textContent = '🌞';
    } else {
        document.documentElement.classList.remove('dark');
        if (icon) icon.textContent = '🌙';
    }

    // Expose la funció globalment perquè puga ser usada
    window.toggleDarkMode = toggleDarkMode;
});
