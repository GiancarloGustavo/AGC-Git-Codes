document.addEventListener('DOMContentLoaded', () => {
    const theme = localStorage.getItem('theme') || 'light';
    const body = document.body;

    if (theme === 'dark') {
        body.classList.add('dark');
        body.classList.remove('light');
    }else {
        body.classList.remove('dark');
        body.classList.add('light');
    }
})   

