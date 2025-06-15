// MENU TOGGLE //
const menuToggle = document.querySelector(".menu-toggle input");
const nav = document.querySelector("nav ul");
const menuOverlay = document.querySelector(".overlay");

menuToggle.addEventListener("click", function () {
  nav.classList.toggle("slide");
  menuOverlay.classList.toggle("active");
  document.body.style.overflow = nav.classList.contains("slide");
});

// Close menu when clicking overlay
menuOverlay.addEventListener("click", () => {
  nav.classList.remove("slide");
  menuOverlay.classList.remove("active");
  menuToggle.checked = false;
  document.body.style.overflow = "auto";
});

// SCROLL
const sections = document.querySelectorAll("section");
const navbarLinks = document.querySelectorAll("header nav ul li a");

window.addEventListener("scroll", () => {
  const scrollY = window.scrollY;
  sections.forEach((sec) => {
    const offset = sec.offsetTop - 100;
    const height = sec.offsetHeight;
    const id = sec.getAttribute("id");

    if (scrollY >= offset && scrollY < offset + height) {
      navbarLinks.forEach((link) => link.classList.remove("active"));
      document.querySelector(`header nav ul li a[href*="${id}"]`)?.classList.add("active");
    }
  });
});

// IKLAN POPUP
const gambar = document.querySelector(".gambar");
const popupGambar = document.querySelector(".popup-gambar");

document.querySelectorAll(".logos-slide img").forEach((img) => {
  img.addEventListener("click", () => {
    gambar.style.visibility = "visible";
    gambar.style.opacity = "1";
    popupGambar.src = img.src;
  });
});

gambar.addEventListener("click", (e) => {
  if (e.target === gambar) {
    gambar.style.visibility = "hidden";
    gambar.style.opacity = "0";
  }
});

//Slide Iklan
var copy = document.querySelector(".logos-slide").cloneNode(true);
document.querySelector(".iklan").appendChild(copy);
