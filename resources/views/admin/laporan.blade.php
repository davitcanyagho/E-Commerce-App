<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <h1 class="text-center mb-3">Laporan Penjualan</h1>
    <div class="container table-container">
        <table class="table table-striped table-bordered mt-2">
            <thead class="thead-dark">
                <tr>
                    <th>Deskripsi</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Total Pesanan</td>
                    <td>{{ $totalOrders }}</td>
                </tr>
                <tr>
                    <td>Total Produk</td>
                    <td>{{ $totalProducts }}</td>
                </tr>
                <tr>
                    <td>Total Pelanggan</td>
                    <td>{{ $totalCustomers }}</td>
                </tr>
                <tr>
                    <td>Total Pendapatan</td>
                    <td>Rp.{{ number_format($totalRevenue) }}</td>
                </tr>
                <tr>
                    <td>Pendapatan Bulan Ini</td>
                    <td>Rp.{{ number_format($revenueThisMonth) }}</td>
                </tr>
                <tr>
                    <td>Pendapatan Bulan Lalu ({{ $lastMonthName }})</td>
                    <td>Rp.{{ number_format($revenueLastMonth) }}</td>
                </tr>
                <tr>
                    <td>Pendapatan 30 Hari Terakhir</td>
                    <td>Rp.{{ number_format($revenueLastThirtyDays) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
