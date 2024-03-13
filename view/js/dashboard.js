function showPage(pageId) {
  // Hide all pages
  var pages = document.getElementsByClassName("page");
  for (var i = 0; i < pages.length; i++) {
    pages[i].style.display = "none";
  }

  // Show the selected page
  var selectedPage = document.getElementById(pageId + "Page");
  selectedPage.style.display = "block";

  // Remove 'active' class from all navigation links
  var navLinks = document.querySelectorAll(".nav-link");
  for (var i = 0; i < navLinks.length; i++) {
    navLinks[i].classList.remove("active");
  }

  // Add 'active' class to the clicked navigation link
  var clickedNavLink = document.querySelector('a[href="#"][onclick="showPage(\'' + pageId + '\')"]');
  clickedNavLink.classList.add("active");
}

