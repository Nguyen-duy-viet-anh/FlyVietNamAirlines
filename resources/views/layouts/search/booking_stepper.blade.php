@props(['currentStep' => 1])

<div class="booking-stepper-container">
    <div class="stepper-flex">
        <div class="steps-wrapper">
            <div class="step-item {{ $currentStep == 1 ? 'active' : ($currentStep > 1 ? 'completed' : '') }}">
                <div class="step-number">1</div>
                <span class="step-text">Select Flight</span>
            </div>
            
            <div class="step-item {{ $currentStep == 2 ? 'active' : ($currentStep > 2 ? 'completed' : '') }}">
                <div class="step-number">2</div>
                <span class="step-text">Passenger</span>
            </div>
            
            <div class="step-item {{ $currentStep == 3 ? 'active' : ($currentStep > 3 ? 'completed' : '') }}">
                <div class="step-number">3</div>
                <span class="step-text">Review</span>
            </div>
            
            <div class="step-item {{ $currentStep == 4 ? 'active' : ($currentStep > 4 ? 'completed' : '') }}">
                <div class="step-number">4</div>
                <span class="step-text">Payment</span>
            </div>
        </div>

        <div class="edit-search-section">
            @if($currentStep == 1)
                <a id="btnEditSearch" class="edit-search-link">
                    <i class="fas fa-edit"></i>
                    <span>Edit search</span>
                </a>
            @else
                <a href="{{ route('flights.search', request()->all()) }}" class="edit-search-link">
                    <i class="fas fa-edit"></i>
                    <span>Edit search</span>
                </a>
            @endif
        </div>
    </div>
</div>
