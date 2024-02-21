const allCards = document.querySelectorAll('.card-component');
// card-expand, card-content-expanded
// console.log(allCards);

for (const card of allCards) {
    const cardExpand = card.querySelector('.card-expand');
    const cardContentHidden = card.querySelector('.card-content-expanded');
    const svgStateIcon = card.querySelector('.expanded-state-icon');
    // console.log(cardExpand, cardContentHidden);
    cardExpand.addEventListener('click', () => {
        cardContentHidden.classList.toggle('hidden');
        if (cardContentHidden.classList.contains('hidden')) {
            svgStateIcon.style.transform = 'rotate(180deg)';
        } else {
            svgStateIcon.style.transform = 'rotate(0deg)';
        }
    });
}
