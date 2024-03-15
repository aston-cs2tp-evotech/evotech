// Function to show the page based on the ID
function showPage(pageId, productID = null) {
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

  // Add 'active' class to the clicked navigation link except for if the page is editProduct
  if (pageId !== "editProduct"){
    var clickedNavLink = document.querySelector('a[href="#"][onclick="showPage(\'' + pageId + '\')"]');
    clickedNavLink.classList.add("active");

    // Store the current page ID in localStorage
    localStorage.setItem("currentPage", pageId);
  }

  // If navigating to editProductPage, populate form with product details
  if (pageId === "editProduct" && productID !== null) {
    getProductDetails(productID, function(response) {
      // Parse JSON response
      var productDetails = JSON.parse(response);


      // Populate form fields with product details
      document.getElementById("editproductID").value = productID;
      document.getElementById("editproductName").value = productDetails.productName;
      document.getElementById("editproductPrice").value = productDetails.productPrice;
      document.getElementById("editproductStock").value = productDetails.productStock;
      document.getElementById("editproductDescription").value = productDetails.productDescription;
      document.getElementById("editproductCategory").value = productDetails.productCategory;
      document.getElementById("editProductimagePreview").src = "/view/images/products/" + productID + "/" + productDetails.productImage;
    });
  }
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



$(document).ready(function () {
  $('#ordersTable').DataTable({
    // Enable select extension
    select: true,
    // Define columnDefs to customize the status column
    columnDefs: [
      {
        targets: 5, // Index of the status column (0-based index)
        searchable: true, // Allow searching/filtering
        orderable: true, // Allow ordering
      }
    ]
  });
  $('#productsTable').DataTable();
});

// Get ordersMessage element
var ordersMessage = document.getElementById('orderUpdate');

$('select[name="status"]').change(function() {
  // Extract order ID and new status value
  var orderID = $(this).closest('tr').attr('id').replace('ordersTableRow', ''); // Extract order ID from the row ID
  var newStatusID = $(this).val();
  
  // Make AJAX request to update order status
  $.ajax({
    url: '/api/updateOrderStatus', // Update the URL with the actual endpoint
    method: 'POST',
    data: { orderID: orderID, newStatusID: newStatusID },
    success: function(response) {
      // Update UI if necessary
      ordersMessage.innerHTML = response;
      ordersMessage.style.display = 'block';
      // Add alert-success and alert-dissmissible classes to the alert
      ordersMessage.classList.add('alert-success', 'alert-dismissible');
      
      // Change color of the row based on the new status
      var row = $('#ordersTableRow' + orderID); // Select the row using its ID
      row.removeClass(); // Remove all existing classes
      switch (newStatusID) {
        case '1':
          row.addClass('table-primary');
          break;
        case '2':
          row.addClass('table-success');
          break;
        case '3':
          row.addClass('table-info');
          break;
        case '4':
          row.addClass('table-warning');
          break;
        case '5':
          row.addClass('table-dark');
          break;
        case '6':
          row.addClass('table-danger');
          break;
        case '7':
          row.addClass('table-secondary');
          break;
      }
    },
    error: function(xhr, status, error) {
      // Handle errors
      ordersMessage.innerHTML = 'Error updating order status';
    }
  });
});

function previewImage(input, edit=false) {
  const element = edit ? 'editProductimagePreview' : 'addProductimagePreview';
  var preview = document.getElementById(element);
  console.log(preview);
  // if src already exists, remove it
  if (preview.src) {
    preview.src = '#';
  }
  if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function (e) {
          preview.src = e.target.result;
      }
      reader.readAsDataURL(input.files[0]);
  } else {
      preview.src = '#';
  }
}

// Function to fetch product details asynchronously
function getProductDetails(productID, callback) {
  $.ajax({
    url: '/api/getProduct',
    method: 'POST',
    data: { productID: productID },
    success: function(response) {
      console.log(response);
      callback(response);
    },
    error: function(xhr, status, error) {
      callback(null);
    }
  });
}
