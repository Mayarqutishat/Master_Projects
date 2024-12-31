@extends('layouts.master')

@section('title', 'customer Dashboard')

@section('content')
<div class="row">
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card card-sales">
            <div class="card-body">
                <h4 class="card-title">Total Sales</h4>
                <p>{{ $totalSales ?? 'N/A' }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card card-orders">
            <div class="card-body">
                <h4 class="card-title">New Orders</h4>
                <p>{{ $newOrdersCount ?? 'N/A' }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card card-tasks">
            <div class="card-body">
                <h4 class="card-title">Completed Orders</h4>
                <p>{{ $completedOrdersCount ?? 'N/A' }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card card-customers">
            <div class="card-body">
                <h4 class="card-title">New Customers</h4>
                <p>{{ $newCustomersCount ?? 'N/A' }}</p>
            </div>
        </div>
    </div>
</div>

  <!-- Charts Section -->
  <div class="row">
                    <div class="col-md-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Sales Overview</h4>
                                <canvas id="salesChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Orders Overview</h4>
                                <canvas id="ordersChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Recent Payments</h4>
                <div class="table-responsive">
                    <table class="table table-bordered" id="paymentsTable">
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>User</th>
                                <th>Order ID</th>
                                <th>Amount</th>
                                <th>Payment Method</th>
                                <th>Status</th>
                                <th>Processed At</th>
                            </tr>
                        </thead>
                        <tbody>
                          
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

<script>
    window.onload = function () {
        // Orders Chart (Monthly)
        const ordersCtx = document.getElementById('myAreaChart').getContext('2d');
        const ordersData = @json($ordersData);
        const ordersLabels = ordersData.map(item => `Month ${item.month}`);
        const ordersValues = ordersData.map(item => item.count);

        const ordersChart = new Chart(ordersCtx, {
            type: 'line',
            data: {
                labels: ordersLabels,
                datasets: [{
                    label: 'Orders',
                    data: ordersValues,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 2,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Stock Chart (Product Stock)
        const stockCtx = document.getElementById('myBarChart').getContext('2d');
        const stockData = @json($stockData);
        const stockLabels = stockData.map(item => item.name);
        const stockValues = stockData.map(item => item.stock);

        const stockChart = new Chart(stockCtx, {
            type: 'bar',
            data: {
                labels: stockLabels,
                datasets: [{
                    label: 'Stock',
                    data: stockValues,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
</script>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<!-- plugins:js -->
<script src="assets/vendors/js/vendor.bundle.base.js"></script>
    <script src="assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="assets/js/off-canvas.js"></script>
    <script src="assets/js/hoverable-collapse.js"></script>
    <script src="assets/js/misc.js"></script>
    <script src="assets/js/settings.js"></script>
    <script src="assets/js/todolist.js"></script>

    <script>
      // Sales Chart
      var ctxSales = document.getElementById('salesChart').getContext('2d');
      new Chart(ctxSales, {
          type: 'line',
          data: {
              labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
              datasets: [{
                  label: 'Sales ($)',
                  data: [12000, 15000, 10000, 18000, 14000, 19000],
                  borderColor: '#F1D4E5',
                  fill: false
              }]
          }
      });

      // Orders Chart
      var ctxOrders = document.getElementById('ordersChart').getContext('2d');
      new Chart(ctxOrders, {
          type: 'bar',
          data: {
              labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
              datasets: [{
                  label: 'Orders',
                  data: [300, 400, 350, 450, 500, 550],
                  backgroundColor: '#F1D4E5'
              }]
          }
      });



      document.getElementById('saveSaleBtn').addEventListener('click', function() {
    var productName = document.getElementById('productName').value;
    var saleAmount = document.getElementById('saleAmount').value;
    var status = document.getElementById('status').value;

    if (productName && saleAmount && status) {
        alert("New sale added: " + productName + " - $" + saleAmount + " - " + status);
        // Here you can add functionality to actually save the data, e.g., make an API request
        // For now, just close the modal after adding the sale
        $('#createModal').modal('hide');
    } else {
        alert("Please fill out all fields.");
    }
});

  </script>
<!-- إضافة مكتبة jQuery و DataTables -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
  // تفعيل DataTables على الجدول
  $('table').DataTable();
});
</script>
<script>$(document).ready(function() {
  // تفعيل DataTables على الجدول
  var table = $('table').DataTable();

  // ربط البحث في Navbar مع DataTable
  $('.search-field input').on('input', function() {
    var searchValue = $(this).val();
    table.search(searchValue).draw();  // يقوم بتصفية الجدول بناءً على النص المدخل
  });
});
</script>
<script>
  $(document).ready(function() {
    // Initialize DataTable for the payments table
    $('#paymentsTable').DataTable({
      "paging": true,
      "searching": true,
      "ordering": true,
      "info": true
    });
  });

  
</script>
@endpush
@endsection
