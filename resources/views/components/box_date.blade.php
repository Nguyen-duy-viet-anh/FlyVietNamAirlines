<div class="form-group">
    <label>Ngày đi</label>
    <input type="date" name="departure_date" class="form-control" required min="{{ date('Y-m-d') }}">
</div>
<div class="form-group">
    <label>Ngày về</label>
    <input type="date" name="return_date" class="form-control" min="{{ date('Y-m-d') }}">
</div>
