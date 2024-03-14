// Function to show the page based on the ID
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

  // Store the current page ID in localStorage
  localStorage.setItem("currentPage", pageId);
}

// Function to retrieve and show the last visited page on page load
function showLastVisitedPage() {
  // Retrieve the last visited page ID from localStorage
  var lastVisitedPage = localStorage.getItem("currentPage");
  
  // If a last visited page is found, show it
  if (lastVisitedPage) {
    showPage(lastVisitedPage);
  } else {
    // Default to showing the dashboard if no last visited page is found
    showPage("dashboard");
  }
}

// Call the function to show the last visited page on page load
showLastVisitedPage();
