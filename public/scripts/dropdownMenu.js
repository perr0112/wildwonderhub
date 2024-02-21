const allDropdownDiv = document.querySelectorAll('.settings-action');

for (const div of allDropdownDiv) {
    const iconSettings = div.querySelector('.settings-action__dropdown-btn');
    iconSettings.addEventListener('click', function () {
        div.classList.toggle('visible');
    });
    document.addEventListener('click', (event) => {
        if (div.classList.contains('visible') && !div.contains(event.target)) {
            div.classList.remove('visible');
        }
    });
}
