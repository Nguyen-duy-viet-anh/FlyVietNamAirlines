/**
 * Flight Helper JS - Enhanced with passenger counts and airport exclusion + date restriction
 */

// ===== UI Helpers =====
// - Toggle dropdowns, format currency, toggle mobile menu
/** Toggle dropdown (hiện/ẩn) */
function toggleDropdown(targetId) {
    const element = document.getElementById(targetId);
    if (!element) return;
    element.style.display = (element.style.display === 'none' || element.style.display === '') ? 'block' : 'none';
}

/** Định dạng số thành VNĐ (1.500.000) */
function formatCurrencyVN(number) {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

/** Toggle mobile menu */
function toggleMenu() {
    const menu = document.querySelector('.navbar .menu');
    if (!menu) return;
    menu.style.display = menu.style.display === 'flex' || menu.style.display === 'block' ? 'none' : 'flex';
}

// ===== Airport Helpers =====
// - Swap, update options and keep origin/destination exclusive
/** Swap origin and destination select values */
function swapAirports(form = document) {
    const origin = form.querySelector('select[name="origin_id"]');
    const destination = form.querySelector('select[name="destination_id"]');

    if (origin && destination) {
        const oldOriginVal = origin.value;
        const oldDestVal = destination.value;

        console.log('swapAirports starting:', { origin: oldOriginVal, destination: oldDestVal });

        // 1. Nạp lại đầy đủ options cho cả 2 ô chọn (không loại trừ bất kỳ ai)
        // Việc này giúp đảm bảo giá trị chúng ta sắp gán vào (oldDestVal/oldOriginVal) chắc chắn tồn tại trong DOM.
        updateAirportOptions(origin, null);
        updateAirportOptions(destination, null);

        // 2. Thực hiện đổi giá trị
        origin.value = oldDestVal;
        destination.value = oldOriginVal;

        // 3. Sau khi đổi xong, chạy lại logic loại trừ sân bay trùng nhau để giao diện hợp lệ
        updateAirportOptions(origin, destination.value);
        updateAirportOptions(destination, origin.value);

        console.log('swapAirports completed:', { origin: origin.value, destination: destination.value });
    }
}

/** Update airport options by hiding (removing) conflicting option from full list */
function updateAirportOptions(targetSelect, excludeValue) {
    if (!targetSelect) return;

    const currentValue = targetSelect.value;
    const placeholderText = targetSelect.querySelector('option[value=""]')?.textContent || '-- Chọn --';
    
    // Ưu tiên sử dụng danh sách đầy đủ từ window.airportFullOptions
    let fullOptions = window.airportFullOptions || [];
    
    if (fullOptions.length === 0) {
        // Fallback: Build full options list from both origin/destination selects if global list missing
        try {
            const opts = Array.from(document.querySelectorAll('#origin_id option, #destination_id option'));
            const mapped = opts.map(o => ({ value: o.value, text: o.text })).filter(o => o.value !== '');
            const seen = Object.create(null);
            fullOptions = mapped.reduce((acc, cur) => {
                if (!seen[cur.value]) {
                    seen[cur.value] = true;
                    acc.push(cur);
                }
                return acc;
            }, []);
        } catch (err) {
            console.warn('Could not build airport list:', err);
        }
    }

    // Rebuild: placeholder + all except exclude
    targetSelect.innerHTML = `<option value="">${placeholderText}</option>`;

    (fullOptions || []).forEach(originalOpt => {
        if (originalOpt.value !== excludeValue) {
            const newOpt = new Option(originalOpt.text, originalOpt.value, false, originalOpt.value === currentValue);
            targetSelect.appendChild(newOpt);
        }
    });
}

// ===== Passenger Helpers =====
// - Update child/infant options and validate counts
function updateChildOptions(childSelect, adultCount) {
    if (!childSelect) return;
    const maxChild = 9 - adultCount;
    const currentValue = parseInt(childSelect.value) || 0;

    childSelect.innerHTML = '<option value="0">0</option>';

    for (let i = 0; i <= Math.max(0, maxChild); i++) {
        const option = new Option(i.toString(), i, false, i === currentValue);
        childSelect.appendChild(option);
    }
}

function updateInfantOptions(infantSelect, adultCount) {
    if (!infantSelect) return;
    const currentValue = parseInt(infantSelect.value) || 0;

    infantSelect.innerHTML = '<option value="0">0</option>';

    for (let i = 0; i <= adultCount; i++) {
        const option = new Option(i.toString(), i, false, i === currentValue);
        infantSelect.appendChild(option);
    }
}

/** Validate passenger counts: infants <= adults; adults+children <= 9 */
function validatePassengerCounts(form) {
    const adult = parseInt(form.querySelector('#adult_count').value) || 0;
    const child = parseInt(form.querySelector('#child_count').value) || 0;
    const infant = parseInt(form.querySelector('#infant_count').value) || 0;
    const errorEl = form.querySelector('#infantError');

    if (infant > adult) {
        if (errorEl) errorEl.style.display = 'block';
        return false;
    }

    if (adult + child > 9) {
        alert('Tổng số người lớn và trẻ em không được vượt quá 9 người!');
        return false;
    }

    if (errorEl) errorEl.style.display = 'none';
    return true;
}

// ===== Date Helpers =====
// - Ensure return date is strictly after departure
/** Add days to YYYY-MM-DD string (returns YYYY-MM-DD) */
function addDays(dateStr, days) {
    const d = new Date(dateStr);
    d.setDate(d.getDate() + days);
    return d.toISOString().slice(0,10);
}

/** Sync return date min to departure date (return >= departure + 1 day) */
function syncReturnDate(departureInput, returnInput) {
    if (!departureInput || !returnInput) return;
    const departValue = departureInput.value;

    if (departValue) {
        const minReturn = addDays(departValue, 1); // must be after departure
        returnInput.min = minReturn;
        if (returnInput.value && returnInput.value <= departValue) {
            returnInput.value = minReturn;
        }
    } else {
        // when no departure selected, allow return from today (or server-provided min)
        returnInput.min = departureInput.min || new Date().toISOString().slice(0,10);
    }
}

/** Validate departure/return dates: return must be strictly after departure */
function validateDates(form) {
    const departInput = form.querySelector('input[name="departure_date"]');
    const returnInput = form.querySelector('input[name="return_date"]');
    if (!departInput || !returnInput) return true;

    const depart = departInput.value;
    const ret = returnInput.value;
    if (depart && ret) {
        if (ret <= depart) {
            alert('Ngày về phải sau ngày đi!');
            return false;
        }
    }
    return true;
}

// ===== Initialization =====
document.addEventListener('DOMContentLoaded', function() {
    const searchForms = document.querySelectorAll('#searchForm');
    searchForms.forEach(form => {
        // Form submit
        form.addEventListener('submit', function(e) {
            if (!validatePassengerCounts(this) || !validateDates(this)) {
                e.preventDefault();
            }
        });

        // Airport dynamic exclude
        const originSelect = form.querySelector('#origin_id');
        const destinationSelect = form.querySelector('#destination_id');
        
        if (originSelect) {
            originSelect.addEventListener('change', function() {
                if (destinationSelect) {
                    updateAirportOptions(destinationSelect, this.value);
                }
            });
        }
        
        if (destinationSelect) {
            destinationSelect.addEventListener('change', function() {
                if (originSelect) {
                    updateAirportOptions(originSelect, this.value);
                }
            });
        }

        // Initial hide: don't show origin choice in destination and vice-versa
        if (originSelect && destinationSelect) {
            updateAirportOptions(destinationSelect, originSelect.value);
            updateAirportOptions(originSelect, destinationSelect.value);
        }
        
        // Passenger dynamic
        const adultSelect = form.querySelector('#adult_count');
        const childSelect = form.querySelector('#child_count');
        const infantSelect = form.querySelector('#infant_count');
        
        if (adultSelect) {
            adultSelect.addEventListener('change', function() {
                const adultValue = parseInt(this.value) || 1;
                if (childSelect) updateChildOptions(childSelect, adultValue);
                if (infantSelect) updateInfantOptions(infantSelect, adultValue);
                validatePassengerCounts(form);
            });
        }
        
        if (childSelect) {
            childSelect.addEventListener('change', () => validatePassengerCounts(form));
        }
        
        if (infantSelect) {
            infantSelect.addEventListener('change', () => validatePassengerCounts(form));
        }
        
        // Date restriction: return date > departure date
        const departureInput = form.querySelector('input[name="departure_date"]');
        const returnInput = form.querySelector('input[name="return_date"]');
        if (departureInput && returnInput) {
            departureInput.addEventListener('change', function() {
                syncReturnDate(departureInput, returnInput);
            });
            // Initial sync
            syncReturnDate(departureInput, returnInput);
        }

        // Flight Type Toggle (One-way / Round-trip)
        const flightTypeRadios = form.querySelectorAll('input[name="flight_type"]');
        const returnDateGroup = form.querySelector('input[name="return_date"]')?.closest('.form-group');

        function updateFlightTypeUI() {
            const selectedType = form.querySelector('input[name="flight_type"]:checked')?.value;
            const isOneWay = (selectedType === 'one_way');

            // Bật/tắt class CSS trên form
            form.classList.toggle('is-one-way', isOneWay);

            // Xử lý các thay đổi phụ trợ
            const returnInput = form.querySelector('input[name="return_date"]');
            if (returnInput) {
                if (isOneWay) {
                    returnInput.value = '';
                    returnInput.required = false;
                } else {
                    returnInput.required = true;
                }
            }

            // Cập nhật class active cho label
            flightTypeRadios.forEach(r => {
                r.closest('label').classList.toggle('active', r.checked);
            });
        }

        flightTypeRadios.forEach(radio => {
            radio.addEventListener('change', updateFlightTypeUI);
        });

        // Initial UI state
        updateFlightTypeUI();

        // Ensure swap buttons call swapAirports reliably (avoid relying on inline onclick)
        const swapButtons = form.querySelectorAll('.swap-btn');
        swapButtons.forEach((btn, idx) => {
            // force visual debug styles in case an overlay blocks clicks
            try { btn.style.zIndex = 9999; btn.style.position = 'relative'; } catch (err) {}
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('flightHelper.js: swap button clicked', idx);
                console.log('flightHelper.js: origin/destination before', {
                    origin: form.querySelector('#origin_id')?.value,
                    destination: form.querySelector('#destination_id')?.value
                });
                try {
                    swapAirports(form);
                } catch (err) {
                    console.error('swapAirports error:', err);
                }
                console.log('flightHelper.js: origin/destination after', {
                    origin: form.querySelector('#origin_id')?.value,
                    destination: form.querySelector('#destination_id')?.value
                });
            });
        });
    });
});
