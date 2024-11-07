document.addEventListener("DOMContentLoaded", function() {
    function calculateTotalAmount() {
        // Get the values of number of adults and children entered by the user
        var numOfAdults = document.getElementById('no_of_adults').value;
        var numOfChildren = document.getElementById('no_of_children').value;

        // Get the prices of adults and children from the selected package
        var adultPrice = parseFloat(document.querySelector('input[name="package_id"]:checked + .package-content .adult-price').innerText);
        var childPrice = parseFloat(document.querySelector('input[name="package_id"]:checked + .package-content .child-price').innerText);

        // Calculate the total amount
        var totalAmount = (numOfAdults * adultPrice) + (numOfChildren * childPrice);

        // Display the total amount in the input field
        document.getElementById('total_amount').value = totalAmount.toFixed(2); // Limit to 2 decimal places
    }

    // Add event listeners to input fields for number of adults and children
    document.getElementById('no_of_adults').addEventListener('input', calculateTotalAmount);
    document.getElementById('no_of_children').addEventListener('input', calculateTotalAmount);
});