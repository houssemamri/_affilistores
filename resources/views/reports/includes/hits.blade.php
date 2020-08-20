<div class="row">
    <div class="col-lg-12">
        <select id="product-hits-period" class="form-control">
            <option value="week">This Week</option>
            <option value="month">This Month</option>
            <option value="year">This Year</option>
            <option value="custom">Custom Date</option>
        </select>
        <div class="hits-custom-date">
            <div class="form-group">
                <label class="bmd-label-floating">From</label>
                <input type="date" class="form-control hits-start-date" >
            </div>
            <div class="form-group">
                <label class="bmd-label-floating">To</label>
                <input type="date" class="form-control hits-end-date" disabled>
            </div>
            <div class="form-group">
                <a href="#" class="btn btn-primary hits-btn-show">Show</a>
            </div>
        </div>
       
    </div>
</div>

<canvas class="hits-ct-chart">

</canvas>
           