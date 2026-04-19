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
function updateAdultOptions(adultSelect, childCount = 0, infantCount = 0) {
    if (!adultSelect) return;
    const currentValue = parseInt(adultSelect.value) || 1;
    // Tối thiểu: 1 người lớn, và phải >= số trẻ sơ sinh
    const minAdult = Math.max(1, infantCount);
    // Tối đa: 9 - Trẻ em - Sơ sinh
    const maxAdult = 9 - childCount - infantCount;

    adultSelect.innerHTML = '';
    for (let i = minAdult; i <= Math.max(minAdult, maxAdult); i++) {
        const option = new Option(i.toString(), i, false, i === currentValue);
        adultSelect.appendChild(option);
    }
}

function updateChildOptions(childSelect, adultCount, infantCount = 0) {
    if (!childSelect) return;
    // Tối đa: 9 - Người lớn - Sơ sinh
    const maxChild = 9 - adultCount - infantCount;
    const currentValue = parseInt(childSelect.value) || 0;

    childSelect.innerHTML = '';
    for (let i = 0; i <= Math.max(0, maxChild); i++) {
        const option = new Option(i.toString(), i, false, i === currentValue);
        childSelect.appendChild(option);
    }
}

function updateInfantOptions(infantSelect, adultCount, childCount = 0) {
    if (!infantSelect) return;
    // Quy tắc: Sơ sinh <= Người lớn VÀ Tổng <= 9
    // => Sơ sinh <= min(Người lớn, 9 - Người lớn - Trẻ em)
    const maxInfant = Math.min(adultCount, 9 - adultCount - childCount);
    const currentValue = parseInt(infantSelect.value) || 0;

    infantSelect.innerHTML = '';
    for (let i = 0; i <= Math.max(0, maxInfant); i++) {
        const option = new Option(i.toString(), i, false, i === currentValue);
        infantSelect.appendChild(option);
    }
}

/** Validate passenger counts: infants <= adults; total passengers <= 9 */
function validatePassengerCounts(form) {
    const adultSelect = form.querySelector('#adult_count');
    const childSelect = form.querySelector('#child_count');
    const infantSelect = form.querySelector('#infant_count');
    const infantError = form.querySelector('#infantError');
    const submitBtn = form.querySelector('button[type="submit"]');

    if (!adultSelect || !infantSelect) return true;

    const adult = parseInt(adultSelect.value) || 0;
    const infant = parseInt(infantSelect.value) || 0;

    let isValid = true;

    // 1. Kiểm tra sơ sinh <= người lớn (dù dropdown đã hạn chế nhưng vẫn check cho chắc)
    if (infant > adult) {
        if (infantError) infantError.classList.remove('hidden');
        isValid = false;
    } else {
        if (infantError) infantError.classList.add('hidden');
    }

    // Cập nhật trạng thái nút bấm
    if (submitBtn) {
        submitBtn.disabled = !isValid;
        submitBtn.style.opacity = isValid ? '1' : '0.5';
        submitBtn.style.cursor = isValid ? 'pointer' : 'not-allowed';
    }

    return isValid;
}

// ===== Date Helpers =====
// - Ensure return date is strictly after departure
/** Add days to YYYY-MM-DD string (returns YYYY-MM-DD) */
function addDays(dateStr, days) {
    const d = new Date(dateStr);
    d.setDate(d.getDate() + days);
    return d.toISOString().slice(0,10);
}

/** Sync return date min to departure date (return >= departure) */
function syncReturnDate(departureInput, returnInput) {
    if (!departureInput || !returnInput) return;
    const departValue = departureInput.value;

    if (departValue) {
        // Cho phép ngày về tối thiểu là bằng ngày đi
        returnInput.min = departValue;
        if (returnInput.value && returnInput.value < departValue) {
            returnInput.value = departValue;
        }
    } else {
        // when no departure selected, allow return from today (or server-provided min)
        returnInput.min = departureInput.min || new Date().toISOString().slice(0,10);
    }
}

/** Validate departure/return dates: return must not be before departure */
function validateDates(form) {
    const departInput = form.querySelector('input[name="departure_date"]');
    const returnInput = form.querySelector('input[name="return_date"]');
    if (!departInput || !returnInput) return true;

    const depart = departInput.value;
    const ret = returnInput.value;
    if (depart && ret) {
        if (ret < depart) {
            alert('Ngày về không được trước ngày đi!');
            return false;
        }
    }
    return true;
}

// ===== Initialization =====
document.addEventListener('DOMContentLoaded', function() {
    console.log('flightHelper.js: DOMContentLoaded triggered');
    
    // Tìm tất cả các form tìm kiếm trên trang
    const searchForms = document.querySelectorAll('#searchForm');
    console.log('flightHelper.js: Found search forms:', searchForms.length);

    searchForms.forEach((form, index) => {
        console.log(`flightHelper.js: Initializing form #${index}`);

        const adultSelect = form.querySelector('#adult_count');
        const childSelect = form.querySelector('#child_count');
        const infantSelect = form.querySelector('#infant_count');
        const originSelect = form.querySelector('#origin_id');
        const destinationSelect = form.querySelector('#destination_id');
        const departureInput = form.querySelector('input[name="departure_date"]');
        const returnInput = form.querySelector('input[name="return_date"]');

        // 1. Logic Thay đổi số lượng khách
        if (adultSelect) {
            adultSelect.addEventListener('change', function() {
                const adultVal = parseInt(this.value) || 1;
                const childVal = childSelect ? (parseInt(childSelect.value) || 0) : 0;
                const infantVal = infantSelect ? (parseInt(infantSelect.value) || 0) : 0;
                
                console.log('flightHelper.js: Adult count changed to:', adultVal);
                if (childSelect) updateChildOptions(childSelect, adultVal, infantVal);
                if (infantSelect) updateInfantOptions(infantSelect, adultVal, childVal);
                validatePassengerCounts(form);
            });
        }
        
        if (childSelect) {
            childSelect.addEventListener('change', function() {
                const adultVal = adultSelect ? (parseInt(adultSelect.value) || 1) : 1;
                const childVal = parseInt(this.value) || 0;
                const infantVal = infantSelect ? (parseInt(infantSelect.value) || 0) : 0;
                
                console.log('flightHelper.js: Child count changed to:', childVal);
                // Khi đổi Trẻ em, cần cập nhật lại giới hạn cho Người lớn và Sơ sinh
                if (adultSelect) updateAdultOptions(adultSelect, childVal, infantVal);
                if (infantSelect) updateInfantOptions(infantSelect, adultVal, childVal);
                validatePassengerCounts(form);
            });
        }
        
        if (infantSelect) {
            infantSelect.addEventListener('change', function() {
                const adultVal = adultSelect ? (parseInt(adultSelect.value) || 1) : 1;
                const childVal = childSelect ? (parseInt(childSelect.value) || 0) : 0;
                const infantVal = parseInt(this.value) || 0;

                console.log('flightHelper.js: Infant count changed to:', infantVal);
                // Khi đổi Sơ sinh, cần cập nhật lại giới hạn cho Người lớn và Trẻ em
                if (adultSelect) updateAdultOptions(adultSelect, childVal, infantVal);
                if (childSelect) updateChildOptions(childSelect, adultVal, infantVal);
                validatePassengerCounts(form);
            });
        }

        // Chạy kiểm tra và cập nhật ban đầu ngay khi load
        const initialAdult = parseInt(adultSelect?.value) || 1;
        const initialChild = parseInt(childSelect?.value) || 0;
        const initialInfant = parseInt(infantSelect?.value) || 0;
        
        if (adultSelect) updateAdultOptions(adultSelect, initialChild, initialInfant);
        if (childSelect) updateChildOptions(childSelect, initialAdult, initialInfant);
        if (infantSelect) updateInfantOptions(infantSelect, initialAdult, initialChild);
        validatePassengerCounts(form);

        // 2. Logic Sân bay (Exclusion)
        if (originSelect && destinationSelect) {
            originSelect.addEventListener('change', function() {
                updateAirportOptions(destinationSelect, this.value);
            });
            destinationSelect.addEventListener('change', function() {
                updateAirportOptions(originSelect, this.value);
            });
            // Initial state
            updateAirportOptions(destinationSelect, originSelect.value);
            updateAirportOptions(originSelect, destinationSelect.value);
        }

        // 3. Logic Ngày tháng
        if (departureInput && returnInput) {
            departureInput.addEventListener('change', function() {
                syncReturnDate(departureInput, returnInput);
            });
            syncReturnDate(departureInput, returnInput);
        }

        // 4. Logic Đổi sân bay (Swap)
        const swapButtons = form.querySelectorAll('.swap-btn');
        swapButtons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                swapAirports(form);
            });
        });

        // 5. Logic Loại hành trình (Một chiều / Khứ hồi)
        const flightTypeRadios = form.querySelectorAll('input[name="flight_type"]');
        function updateFlightTypeUI() {
            const selectedType = form.querySelector('input[name="flight_type"]:checked')?.value;
            const isOneWay = (selectedType === 'one_way');
            form.classList.toggle('is-one-way', isOneWay);
            if (returnInput) {
                returnInput.value = isOneWay ? '' : returnInput.value;
                returnInput.required = !isOneWay;
            }
            flightTypeRadios.forEach(r => r.closest('label')?.classList.toggle('active', r.checked));
        }
        flightTypeRadios.forEach(radio => radio.addEventListener('change', updateFlightTypeUI));
        updateFlightTypeUI();

        // 6. Chặn Submit nếu không hợp lệ
        form.addEventListener('submit', function(e) {
            console.log('flightHelper.js: Form submit triggered');
            if (!validatePassengerCounts(form) || !validateDates(form)) {
                console.warn('flightHelper.js: Validation failed, stopping submit');
                e.preventDefault();
            }
        });

        // Chạy kiểm tra ban đầu ngay khi load
        validatePassengerCounts(form);
    });
});
