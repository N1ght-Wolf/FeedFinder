<form role="form" id='people-map-form' class="form-horizontal">
    <!--        select for the time-->

    <div class="form-group">
        <label for="sel1">Time</label>
        <select class="form-control time-select">
            <option>Today</option>
            <option>Yesterday</option>
            <option>This week</option>
            <option>Last week</option>
            <option>This month</option>
            <option>Last month</option>
            <option>Last 3 month</option>
            <option>Last 6 month</option>
            <option>This year
                <script>document.write(new Date().getFullYear())</script>
            </option>
            <option>Beginning of time</option>
        </select>
    </div>
<!--    end of select for time-->

<!--    start of heat map select-->
    <div class="form-group">
        <label for="sel1">Explore by</label>
        <select class="form-control yeah">
            <option value='users_interq_adminone'>County</option>
            <option value='users_interq_ukadminthree'>S.O.A (Super Output Area)</option>
        </select>
    </div>
<!--    end of heatmap select-->
</form>
