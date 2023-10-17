document.addEventListener('DOMContentLoaded', function () {
    const backgroundContainer = document.querySelector('.background-container');
    const backgroundImage = document.createElement('div');
    backgroundImage.classList.add('background-image');
    backgroundContainer.appendChild(backgroundImage);

    const images = ['teste.jpg', 'teste02.jpg', 'teste03.jpg'];
    let currentIndex = 0;

    function changeBackground() {
        currentIndex = (currentIndex + 1) % images.length;
        const nextImage = images[currentIndex];
        backgroundImage.style.backgroundImage = `url('${nextImage}')`;
    }

    setInterval(changeBackground, 5000); // 5000 milissegundos = 5 segundos
});
