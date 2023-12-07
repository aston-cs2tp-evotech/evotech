document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('contactForm').addEventListener('submit', function (event) {
        event.preventDefault();
        validateForm();
    });
});

function validateForm() {
    var name = document.getElementById('name').value;
    var email = document.getElementById('email').value;
    var description = document.getElementById('description').value;
    var date = document.getElementById('date').value;

    if (!name || !email || !description || !date) {
        alert('Please fill in all the fields');
        return;
    }

    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert('PLease enter valid email');
        return;
    }

    var currentDate = new Date();
    var selectedDate = new Date(date);
    if (selectedDate > currentDate) {
        alert('Please select a present or previous date');
        return;
    }

    alert('Thanks for submitting, we will get back to you shortly. :D');

    
    
    
}
