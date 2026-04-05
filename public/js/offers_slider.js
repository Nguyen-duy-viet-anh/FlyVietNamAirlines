document.addEventListener('DOMContentLoaded', function () {
    const track = document.querySelector('.carousel-track');
    const nextBtn = document.querySelector('.nav-btn.next');
    const prevBtn = document.querySelector('.nav-btn.prev');
    const container = document.querySelector('.carousel-container');

    if (!track || !nextBtn || !prevBtn || !container) return;

    let scrollAmount = 0;
    const cardWidth = document.querySelector('.offer-card').offsetWidth + 20; // width + gap
    const maxScroll = track.scrollWidth - container.offsetWidth;

    nextBtn.addEventListener('click', () => {
        if (scrollAmount < maxScroll) {
            scrollAmount += cardWidth;
            if (scrollAmount > maxScroll) scrollAmount = maxScroll;
            track.style.transform = `translateX(-${scrollAmount}px)`;
        } else {
            // Optional: Loop back to start
            scrollAmount = 0;
            track.style.transform = `translateX(0)`;
        }
    });

    prevBtn.addEventListener('click', () => {
        if (scrollAmount > 0) {
            scrollAmount -= cardWidth;
            if (scrollAmount < 0) scrollAmount = 0;
            track.style.transform = `translateX(-${scrollAmount}px)`;
        } else {
            // Optional: Loop to end
            scrollAmount = maxScroll;
            track.style.transform = `translateX(-${scrollAmount}px)`;
        }
    });

    // Handle window resize
    window.addEventListener('resize', () => {
        scrollAmount = 0;
        track.style.transform = `translateX(0)`;
    });

    // Handle offer card clicks to pre-fill the search form
    const offerCards = document.querySelectorAll('.offer-card');
    offerCards.forEach(card => {
        card.addEventListener('click', function() {
            const originId = this.getAttribute('data-origin-id');
            const destinationId = this.getAttribute('data-destination-id');
            const flightType = this.getAttribute('data-flight-type');

            // Find search form elements
            const originSelect = document.getElementById('origin_id');
            const destSelect = document.getElementById('destination_id');
            const flightTypeRadios = document.getElementsByName('flight_type');

            if (originSelect) {
                originSelect.value = originId;
                originSelect.dispatchEvent(new Event('change'));
            }

            if (destSelect) {
                destSelect.value = destinationId;
                destSelect.dispatchEvent(new Event('change'));
            }
            
            // Set flight type radios correctly (One way or Round trip)
            if (flightTypeRadios.length > 0) {
                flightTypeRadios.forEach(radio => {
                    if (radio.value === flightType) {
                        radio.checked = true;
                        radio.dispatchEvent(new Event('change'));
                    }
                });
            }

            // Scroll to the top of the search form smoothly
            const searchForm = document.getElementById('searchForm');
            if (searchForm) {
                // Determine target scroll position (center the form)
                searchForm.scrollIntoView({ behavior: 'smooth', block: 'center' });
                
                // Add a simple highlight effect to the form
                searchForm.style.transition = 'box-shadow 0.3s ease, border-color 0.3s ease';
                searchForm.style.boxShadow = '0 0 25px rgba(255, 152, 0, 0.6)';
                searchForm.style.borderColor = '#ff9800';
                
                setTimeout(() => {
                    searchForm.style.boxShadow = '';
                    searchForm.style.borderColor = '';
                }, 1500);
            }
        });
    });
});
