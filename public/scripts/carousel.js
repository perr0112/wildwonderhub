const carouselItems = document.querySelectorAll('.carousel-content__item');
const totalItems = carouselItems.length;
const nextBtn = document.querySelector('.arrow-right');
const prevBtn = document.querySelector('.arrow-left');

let index = 0;
let slideInterval;

updateButtonsVisibility();

function updateButtonsVisibility() {
    prevBtn.style.display = index === 0 ? 'none' : 'block';
    nextBtn.style.display = index === totalItems - 1 ? 'none' : 'block';
}

function moveCarousel(newIndex) {
    if (newIndex >= 0 && newIndex < totalItems) {
        index = newIndex;
        const res = -index * 100;
        carouselItems.forEach(item => {
            item.style.transform = `translateX(${res}%)`;
        });
        updateButtonsVisibility();
    }
}

function startAutoSlide() {
    slideInterval = setInterval(() => {
        moveCarousel(index === totalItems - 1 ? 0 : index + 1);
    }, 4000);
}

function stopAutoSlide() {
    clearInterval(slideInterval);
}

nextBtn.addEventListener('click', () => {
    stopAutoSlide();
    moveCarousel(index + 1);
    startAutoSlide();
});

prevBtn.addEventListener('click', () => {
    stopAutoSlide();
    moveCarousel(index - 1);
    startAutoSlide();
});

startAutoSlide();
