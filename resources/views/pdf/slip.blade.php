<!-- resources/views/pdf/transaction_slip.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Transaction Slip</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .border-separator {
            border-top: 1px solid #333; /* Change color as needed */
            margin: 20px 0; /* Adjust spacing as needed */
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }
        table td {
            border: none;
            padding
        }
    </style>
</head>
<body>
    <h1 style="text-align: center">Struk Belanja</h1>
    <p style="text-align: center; font-size: 24px; margin: 0px 0px 2px 0px">Jl.bangka Selatan No.24</p>
    <p style="text-align: center; font-size: 20px; margin: 0px 0px 2px 0px">{{ $tgl_transaksi }}</p>

    <div class="border-separator"></div>

    <table>
        @foreach($datas as $data)
        <tr>
            <td>{{ $data->nama_brg }}</td>
            <td>X{{ $data->qty }}</td>
        </tr>
        <tr>
            <td>{{ $data->harga_jual }}</td>
            <td>{{ $data->subtotal_item }}</td>
        </tr>
        @endforeach
    </table>

    <div class="border-separator"></div>

    <p>Total: Rp.{{ $total_brg }}</p>
    <p>Bayar: Rp.{{ $bayar_brg }}</p>
    <p>Kembali: Rp.{{ $kembali_brg }}</p>
    <div style="text-align: center">Terima kasih telah berbelanja!</div>
</body>
</html>