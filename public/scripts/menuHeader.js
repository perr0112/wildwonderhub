const menu = document.getElementById('hamburger-menu');

menu.addEventListener('click', () => {
    document.querySelector('.ul-links').classList.toggle('open');
});