<form role="form" class='query-form' class="form-horizontal">
  <div class="form-group">
    <label for="sel1">Select list:</label>
    <select class="form-control" name='form-timespan'>
      <option >Today</option>
      <option>Yesterday</option>
      <option>This week</option>
      <option>Last week</option>
      <option>This month</option>
      <option>Last month</option>
      <option>Last 3 month</option>
      <option>Last 6 month</option>
      <option>This year <script>document.write(new Date().getFullYear())</script></option>
      <option>Beginning of time</option>
    </select>
  </div>
</form>
