{{-- Search Alert section --}}
<div class="alert alert-warning no-results-alert">
    <i class="fas fa-exclamation-triangle no-results-icon"></i>
    
    @if(isset($noReturnAvailable) && $noReturnAvailable)
        <h3 class="text-danger">Không có chuyến bay chiều về khả dụng</h3>
        <p class="summary-empty-box text-muted">
            Chúng tôi tìm thấy chuyến bay chiều đi, nhưng <strong>không có chiều về</strong> khả dụng cho ngày bạn chọn ({{ \Carbon\Carbon::parse(request('return_date'))->format('d/m/Y') }}). 
            Đối với đặt vé khứ hồi, cả hai chiều phải có chuyến bay.
        </p>
        <div class="mt-25">
            <p class="text-bold mb-15">Gợi ý:</p>
            <ul class="no-results-list">
                <li>Nên thử một <strong>Ngày về</strong> khác</li>
                <li>Đổi loại hành trình thành <strong>Một chiều</strong> nếu bạn chỉ cần chiều đi</li>
            </ul>
        </div>
    @else
        <h3>Không tìm thấy chuyến bay</h3>
        <p>Rất tiếc, chúng tôi không tìm thấy chuyến bay nào cho lộ trình và ngày bạn chọn. Vui lòng chọn ngày khác hoặc tìm kiếm lại.</p>
    @endif
    
    <div class="mt-30">
        <a href="#" id="btnEditSearchAgain" class="btn-continue text-center" style="background: #003366; padding: 10px 25px; font-size: 16px;">
            <i class="fas fa-search"></i> Chỉnh sửa tìm kiếm
        </a>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnEditAgain = document.getElementById('btnEditSearchAgain');
        if (btnEditAgain) {
            btnEditAgain.addEventListener('click', function(e) {
                e.preventDefault();
                const formContainer = document.getElementById('editSearchFormContainer');
                if (formContainer) {
                    formContainer.classList.add('show-edit-form');
                    formContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        }
    });
</script>
