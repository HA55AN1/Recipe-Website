const menuBtn = document.getElementById("menu_btn");
const navLinks = document.getElementById("nav_links");
const menuBtnIcon = menuBtn.querySelector("i");

menuBtn.addEventListener("click", (e) => {
  navLinks.classList.toggle("open");

  const isOpen = navLinks.classList.contains("open");
  menuBtnIcon.setAttribute("class", isOpen ? "bi bi-x-lg" : "bi bi-list");
});

navLinks.addEventListener("click", (e) => {
  navLinks.classList.remove("open");
  menuBtnIcon.setAttribute("class", "bi bi-list");
});


let userBtn = document.querySelector('#user-btn');

userBtn.addEventListener('click', function(){
	let userBox = document.querySelector('.user-box');
	userBox.classList.toggle('active');
})








const updateForm = document.querySelector('.update-container');
const closeBtn = document.querySelector('#close-form');

// When the user clicks "Cancel"
closeBtn?.addEventListener('click', () => {
    updateForm.style.display = 'none';

    // Remove the ?edit=... from the URL without reloading the page
    const url = new URL(window.location.href);
    url.searchParams.delete("edit");
    window.history.replaceState({}, document.title, url.pathname);
});

// Hide form on page load if there's no ?edit param
window.addEventListener('load', function () {
    const url = new URL(window.location.href);
    if (!url.searchParams.has("edit")) {
        updateForm.style.display = 'none';
    }
});



// Toggle visibility of edit comment form
function toggleEditForm(formId) {
    const form = document.getElementById(formId);
    if (form.style.display === "none" || form.style.display === "") {
      form.style.display = "block";
    } else {
      form.style.display = "none";
    }
  }
  