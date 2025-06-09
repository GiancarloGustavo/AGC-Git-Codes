document.addEventListener('DOMContentLoaded', () => {
    const theme = localStorage.getItem('theme') || 'light';

    const themeBtn = document.getElementById('theme-btn');
    const body = document.body;

    if (theme === 'dark') {
        body.classList.add('dark');
        body.classList.remove('light');
        themeBtn.innerHTML = `<i class="fa-solid fa-sun"></i>`;
    }else {
        body.classList.remove('dark');
        body.classList.add('light');
        themeBtn.innerHTML = `<i class="fa-solid fa-moon"></i>`;
    }

    themeBtn.addEventListener('click', () => {
        if (body.classList.contains('dark')) {
            body.classList.remove('dark');
            body.classList.add('light');
            themeBtn.innerHTML = `<i class="fa-solid fa-moon"></i>`;
            localStorage.setItem('theme', 'light');

            window.location.reload();
        } else {
            body.classList.remove('light');
            body.classList.add('dark');
            themeBtn.innerHTML = `<i class="fa-solid fa-sun"></i>`;
            localStorage.setItem('theme', 'dark');

            window.location.reload();
        }
    });

})   

