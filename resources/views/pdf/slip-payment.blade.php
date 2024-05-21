<!-- resources/views/pdf/transaction_slip.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Transaction Slip</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Transaction Slip</h1>
    <p>Transaction ID: {{ $transaksi_id }}</p>
    <p>Total Barang: {{ $total_brg }}</p>
    <p>Bayar Barang: {{ $bayar_brg }}</p>
    <p>Kembali Barang: {{ $kembali_brg }}</p>

    <h2>Items</h2>
    <table>
        <thead>
            <tr>
                <th>Kode Barang</th>
                <th>Nama Barang</th>
                <th>Merk</th>
                <th>Harga Beli</th>
                <th>Harga Jual</th>
                <th>Qty</th>
                <th>Subtotal Item</th>
            </tr>
        </thead>
        <tbody>
            @foreach($datas as $data)
                <tr>
                    <td>{{ $data['kode_barang'] }}</td>
                    <td>{{ $data['nama_brg'] }}</td>
                    <td>{{ $data['merk'] }}</td>
                    <td>{{ $data['harga_beli'] }}</td>
                    <td>{{ $data['harga_jual'] }}</td>
                    <td>{{ $data['qty'] }}</td>
                    <td>{{ $data['subtotal_item'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>