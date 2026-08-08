import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
console.log("APP JS LOADED");
document.addEventListener("DOMContentLoaded", () => {

    document.querySelectorAll(".glow-hover").forEach(card => {

        card.addEventListener("mousemove", (e) => {

            const rect = card.getBoundingClientRect();

            card.style.setProperty("--x", `${e.clientX - rect.left}px`);
            card.style.setProperty("--y", `${e.clientY - rect.top}px`);

        });

    });

});

document.addEventListener("DOMContentLoaded", () => {

    console.log("DOM READY");

    const cards = document.querySelectorAll(".glow-hover");

    console.log(cards);

});