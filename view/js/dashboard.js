// Function to show the page based on the ID
function showPage(pageId, productID = null, customerID = null, adminID = null, customerIDforOrder = null) {
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

  // Add 'active' class to the clicked navigation link except for if the page is an edit page
  if (pageId !== "editProduct" && pageId !== "editCustomer" && pageId !== "editAdmin") {
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

  // If navigating to editCustomerPage, populate form with customer details
  if (pageId === "editCustomer" && customerID !== null) {
    getCustomerDetails(customerID, function(response) {
      // Parse JSON response
      var customerDetails = JSON.parse(response);

      // Populate form fields with customer details
      document.getElementById("editcustomerID").value = customerID;
      document.getElementById("editcustomerUsername").value = customerDetails.customerUsername;
      document.getElementById("editcustomerEmail").value = customerDetails.customerEmail;
      document.getElementById("editcustomerAddress").value = customerDetails.customerAddress;
    });
  }

  // If navigating to orders and the customerIDforOrder is not null, filter the orders by customerID
  if (pageId === "orders" && customerIDforOrder !== null) {
    // Filter the orders table by customerID
    var ordersTable = $('#ordersTable').DataTable();
    ordersTable.column(1).search(customerIDforOrder).draw();
    var resetButton = document.getElementById('resetTableFiltersButton');
    resetButton.style.display = 'block';

    function resetTableFilters() {
      ordersTable.search('').columns().search('').draw();
      resetButton.style.display = 'none';
    }

    // Attach reset functionality to the reset button
    resetButton.addEventListener('click', resetTableFilters);
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
  var productsTable = new DataTable('#productsTable');
  $('#lowStockProductsTable').DataTable({
    // Show 5 rows by default
    pageLength: 5,
    lengthMenu: [5, 10, 25, 50, 75, 100]
  });
  $('#customersTable').DataTable();
  $('#adminsTable').DataTable();
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
      callback(response);
    },
    error: function(xhr, status, error) {
      callback(null);
    }
  });
}

// Function to fetch customer details asynchronously
function getCustomerDetails(customerID, callback) {
  $.ajax({
    url: '/api/getCustomer',
    method: 'POST',
    data: { customerID: customerID },
    success: function(response) {
      callback(response);
    },
    error: function(xhr, status, error) {
      callback(null);
    }
  });
}

// Function to delete a product asynchronously
function deleteProduct() {
  // Get product ID
  var productID = document.getElementById('editproductID').value;
  $.ajax({
    url: '/api/deleteProduct',
    method: 'POST',
    data: { productID: productID },
    success: function(response) {
      // Show success message
      var deleteProductMessage = document.getElementById('productUpdate');
      deleteProductMessage.innerHTML = productID + ' deleted successfully';
      deleteProductMessage.style.display = 'block';
      deleteProductMessage.classList.add('alert-success', 'alert-dismissible');
      // Remove the product row from the table
      var row = document.getElementById('productsTableRow' + productID);
      row.remove();
      showPage('products');
      
    },
    error: function(xhr, status, error) {
      // Show error message
      var deleteProductMessage = document.getElementById('productUpdate');
      deleteProductMessage.innerHTML = error + ': ' + xhr.responseText;
      deleteProductMessage.style.display = 'block';
      deleteProductMessage.classList.add('alert-danger', 'alert-dismissible');
      showPage('products');
    }
  });
}

// Function to delete a customer asynchronously
function deleteCustomer() {
  // Get customer ID
  var customerID = document.getElementById('editcustomerID').value;
  $.ajax({
    url: '/api/deleteCustomer',
    method: 'POST',
    data: { customerID: customerID },
    success: function(response) {
      // Show success message
      var deleteCustomerMessage = document.getElementById('customerUpdate');
      deleteCustomerMessage.innerHTML = 'Customer ' + customerID + ' deleted successfully';
      deleteCustomerMessage.style.display = 'block';
      deleteCustomerMessage.classList.add('alert-success', 'alert-dismissible');
      // Remove the customer row from the table
      var row = document.getElementById('customersTableRow' + customerID);
      row.remove();
      showPage('customers');
    },
    error: function(xhr, status, error) {
      // Show error message
      var deleteCustomerMessage = document.getElementById('customerUpdate');
      deleteCustomerMessage.innerHTML = 'Error deleting customer';
      deleteCustomerMessage.style.display = 'block';
      deleteCustomerMessage.classList.add('alert-danger', 'alert-dismissible');
      showPage('customers');
    }
  });
}


// When page is loaded, and either editProductSuccess or addProductSuccess is set as a GET parameter, show the product page
$(document).ready(function() {
  var urlParams = new URLSearchParams(window.location.search);
  if (urlParams.has('editProductSuccess')) {
    showPage('products');
    // Remove the GET parameter from the URL
    window.history.replaceState({}, document.title, "/" + "admin");
  } else if (urlParams.has('addProductSuccess')) {
    showPage('products');
    // Remove the GET parameter from the URL
    window.history.replaceState({}, document.title, "/" + "admin");
  }
});


// Function to edit a customer asynchronously
$("#editCustomerForm").submit(function(e) {
  e.preventDefault();

  // Get form data
  var customerID = $("#editcustomerID").val();
  var customerUsername = $("#editcustomerUsername").val();
  var customerEmail = $("#editcustomerEmail").val();
  var customerAddress = $("#editcustomerAddress").val();
  var customerPassword = $("#editcustomerPassword").val();

  // Make AJAX request
  $.ajax({
    url: "/api/editCustomer",
    method: "POST",
    data: {
      customerID: customerID,
      customerUsername: customerUsername,
      customerEmail: customerEmail,
      customerAddress: customerAddress,
      customerPassword: customerPassword
    },
    success: function(response) {
      // Show success message
      var editCustomerMessage = document.getElementById("customerUpdate");
      editCustomerMessage.innerHTML = "Customer " + customerUsername + " updated successfully";
      editCustomerMessage.style.display = "block";
      editCustomerMessage.classList.add("alert-success", "alert-dismissible");

      // Update the customer details in the table
      document.getElementById("columnCustomerUsername_" + customerID).innerHTML = customerUsername;
      document.getElementById("columnCustomerEmail_" + customerID).innerHTML = customerEmail;
      document.getElementById("columnCustomerAddress_" + customerID).innerHTML = customerAddress;


      showPage("customers");
    },
    error: function(xhr, status, error) {
      // Show error message
      var editCustomerMessage = document.getElementById("customerUpdate");
      editCustomerMessage.innerHTML = "Error updating customer";
      editCustomerMessage.style.display = "block";
      editCustomerMessage.classList.add("alert-danger", "alert-dismissible");
      showPage("customers");
    }
  });

});

