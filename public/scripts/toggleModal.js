const allModals = document.querySelectorAll('.modal-delete');

document.addEventListener('mousedown', function (event) {
    for (const modal of allModals) {
        const modalForm = modal.querySelector('.modal-form');
        const modalContent = modal.querySelector('.modal-form__content');

        if (modalForm.classList.contains('visible') && !modalContent.contains(event.target)) {
            modalForm.classList.remove('visible');
        }
    }
});

for (const modal of allModals) {
    const buttonOpen = modal.querySelector('.open-modal');
    const modalForm = modal.querySelector('.modal-form');
    buttonOpen.addEventListener('click', function (event) {
        event.stopPropagation();
        modalForm.classList.toggle('visible');
    });
}
