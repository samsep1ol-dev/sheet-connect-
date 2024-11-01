// Wait for the DOM to be fully loaded
console.log('Oie')

document.addEventListener('DOMContentLoaded', function(event) {
    var navbarToggler = document.querySelectorAll('.navbar-toggler')[0];
    navbarToggler.addEventListener('click', function(e) {
      e.target.children[0].classList.toggle('active');
    });
  
    var html = document.querySelectorAll('html')[0];
    var themeToggle = document.querySelectorAll('*[data-bs-toggle-theme]')[0];
  
    // Set the default theme to 'dark'
    html.setAttribute('data-bs-theme', 'dark');
  
    if (themeToggle) {
      themeToggle.addEventListener('click', function(event) {
        event.preventDefault();
        if (html.getAttribute('data-bs-theme') === 'dark') {
          html.setAttribute('data-bs-theme', 'light');
        } else {
          html.setAttribute('data-bs-theme', 'dark');
        }
      });
    }
  });
  