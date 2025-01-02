<!-- Chart.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

@extends('layouts.master')

@section('title', 'Admin Dashboard')

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

  <!-- Payments Table Section -->
<div class="col-md-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Recent Payments</h4>
            <div class="table-responsive">
            <script>
  $(document).ready(function() {
    var table = $('#paymentsTable').DataTable();

    // ربط البحث المخصص مع DataTables
    $('#customSearch').on('input', function() {
      table.search($(this).val()).draw();
    });
  });
</script>

                <table class="table table-bordered" id="paymentsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Order ID</th>
                            <th>Amount</th>
                            <th>Payment Method</th>
                            <th>Status</th>
                            <th>Processed At</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($paymentsData as $payment)
                        @php
                            // تحديد اللون بناءً على الـ status
                            $statusColor = '';
                            switch(strtolower($payment->status)) {
                                case 'complete':
                                    $statusColor = 'bg-success'; // أخضر
                                    break;
                                case 'pending':
                                    $statusColor = 'bg-warning'; // أصفر
                                    break;
                                case 'failed':
                                    $statusColor = 'bg-danger'; // أحمر
                                    break;
                                default:
                                    $statusColor = 'bg-secondary'; // محايد
                            }
                        @endphp
                        <tr>
                            <td>{{ $payment->id }}</td>
                            <td>{{ $payment->order->user->name ?? 'N/A' }}</td>
                            <td>{{ $payment->order->id ?? 'N/A' }}</td>
                            <td>${{ number_format($payment->amount, 2) }}</td>
                            <td>{{ $payment->payment_method }}</td>
                            <td>
                                <span class="badge {{ $statusColor }}">{{ ucfirst($payment->status) }}</span>
                            </td>
                            <td>{{ $payment->processed_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                    @endforeach
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
    const ordersCtx = document.getElementById('salesChart').getContext('2d');
    const ordersData = @json($ordersData); // Dynamic data from controller
    const ordersLabels = ordersData.map(item => `Month ${item.month}`);
    const ordersValues = ordersData.map(item => item.count);

    const ordersChart = new Chart(ordersCtx, {
        type: 'line',
        data: {
            labels: ordersLabels,
            datasets: [{
                label: 'Orders',
                data: ordersValues,
                borderColor:  'rgb(253, 206, 223)',
                backgroundColor: 'rgba(224, 183, 220, 0.2)',
            
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
    const stockCtx = document.getElementById('ordersChart').getContext('2d');
    const stockData = @json($stockData); // Dynamic data from controller
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
              
                'rgb(253, 206, 223)',
                'rgb(253, 206, 223)',
                'rgb(253, 206, 223)',
                'rgb(253, 206, 223)'

          
             
                 
                
                ],
                borderColor: [
                 
                'rgb(253, 206, 223)',
                'rgb(253, 206, 223)',
                'rgb(253, 206, 223)',
                'rgb(253, 206, 223)'
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
};

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


@endsection

@section('scripts')
<script>
window.onload = function () {
    // Sales Chart (Monthly)
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    const salesData = @json($salesData); // Dynamic data from controller
    const salesLabels = salesData.map(item => `${item.year}-${item.month}`);
    const salesValues = salesData.map(item => item.total);

    const salesChart = new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: salesLabels,
            datasets: [{
                label: 'Sales',
                data: salesValues,
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

    // Orders Chart (Monthly)
    const ordersCtx = document.getElementById('ordersChart').getContext('2d');
    const ordersData = @json($ordersData); // Dynamic data from controller
    const ordersLabels = ordersData.map(item => `${item.year}-${item.month}`);
    const ordersValues = ordersData.map(item => item.count);

    const ordersChart = new Chart(ordersCtx, {
        type: 'bar',
        data: {
            labels: ordersLabels,
            datasets: [{
                label: 'Orders',
                data: ordersValues,
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

    // تفعيل DataTable على جدول المدفوعات
    $('#paymentsTable').DataTable({
        "lengthChange": false,
        "paging": false,
        "info": false,
        "searching": true,
    });
};


<script>
  $(document).ready(function() {
    // Initialize DataTable for the payments table with search enabled
    $('#paymentsTable').DataTable({
      "paging": true, // تمكين التقسيم
      "searching": true, // تمكين البحث
      "ordering": true, // تمكين الترتيب
      "info": true // تمكين عرض المعلومات
    });
  });
</script>




</script>




@endsection