const images = document.querySelectorAll('.galerie img');
const lightbox = document.getElementById('lightbox');
const lightboxImg = lightbox.querySelector('img');

images.forEach(img => {
    img.addEventListener('click', () => {
    lightboxImg.src = img.src;
    lightbox.classList.add('active'); 
    })
})

closeBtn.addEventListener('click',() => {
    lightbox.classList.remove('active');
});
//Fermer la lightbox en cliquant en dehors de l'image
lightbox.addEventListener('click', e => {
if(e.target === lightbox) {
lightbox.classList.remove('active');
}
});