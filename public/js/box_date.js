/**
 * Box Date JS - Date restriction for flight booking
 * Ngày về chỉ từ ngày đi trở đi
 */

 /**
 * Sync return date min to departure date
 * @param {HTMLInputElement} departureInput - Ngày đi input
 * @param {HTMLInputElement} returnInput - Ngày về input
 */
function syncReturnDate(departureInput, returnInput) {
    if (!departureInput || !returnInput) return;
    
    const departValue = departureInput.value;
    if (departValue) {
        returnInput.min = departValue;
        if (returnInput.value && returnInput.value < departValue) {
            returnInput.value = departValue;
        }
    } else {
        returnInput.min = departureInput.min || new Date().toISOString().split('T')[0];
    }
}

// Init on DOM ready
document.addEventListener('DOMContentLoaded', function() {
    const searchForms = document.querySelectorAll('#searchForm');
    searchForms.forEach(form => {
        const departureInput = form.querySelector('input[name="departure_date"]');
        const returnInput = form.querySelector('input[name="return_date"]');
        if (departureInput && returnInput) {
            departureInput.addEventListener('change', function() {
                syncReturnDate(departureInput, returnInput);
            });
            // Initial sync
            syncReturnDate(departureInput, returnInput);
        }
    });
});
