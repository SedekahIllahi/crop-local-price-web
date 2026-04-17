<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Harga Bapok</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .kop { display: flex; align-items: center; margin-bottom: 16px; }
        .kop-logo { height: 60px; width: 60px; margin-right: 16px; }
        .kop-text { line-height: 1.2; }
        .kop-title { font-size: 16px; font-weight: bold; }
        .kop-subtitle { font-size: 14px; font-weight: 600; }
        .kop-address { font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 4px; text-align: center; vertical-align: middle; }
        th { background: #059669; color: #ffffff; }
        .text-left { text-align: left; }
    </style>
</head>
<body>


    <table style="width:100%; margin-bottom:4px; border:none;">
        <tr>
            <!-- Logo Column -->
            <td style="border:none; width:80px; text-align:center; vertical-align:middle;">
                @if(isset($logoBase64) && $logoBase64)
                    <img src="{{ $logoBase64 }}" width="70" height="70" style="object-fit:contain;">
                @endif
            </td>
            <!-- Text Column -->
            <td style="border:none; vertical-align:middle; text-align:center;" colspan="5">
                <div style="font-size:20px; font-weight:bold; margin-bottom:2px;">PEMERINTAH KABUPATEN BANTUL</div>
                <div style="font-size:17px; font-weight:bold; margin-bottom:2px;">DINAS KOPERASI, UMKM, PERINDUSTRIAN, DAN PERDAGANGAN</div>
                <div style="font-size:14px; margin-bottom:2px;">Komplek Pemda II Manding Bantul, Jl. Lingkar Timur Manding Trirenggo Bantul</div>
                <div style="font-size:14px; margin-bottom:2px;">Telepon: (0274-2810422 / 0812 2566 5517) Email: diskukmpp@bantulkab.go.id</div>
                <div style="font-size:14px; margin-bottom:2px;">Website: https://dkukmpp.bantulkab.go.id</div>
            </td>
        </tr>
    </table>

    <h2 style="text-align:center; margin-bottom:8px;">Laporan Harga Bapok Harian</h2>
    <p style="text-align:center; margin-bottom:4px;">Pasar: {{ $pasar->nama_pasar }}</p>

    <table>
        <thead>
            <tr>
                <th style="width:40px; background-color:#059669; color:#ffffff;">No</th>
                <th style="width:250px; background-color:#059669; color:#ffffff;">Nama Komoditas</th>
                <th style="width:100px; background-color:#059669; color:#ffffff;">Tanggal</th>
                <th style="width:120px; background-color:#059669; color:#ffffff;">Harga</th>
                <th style="width:100px; background-color:#059669; color:#ffffff;">Status</th>
                <th style="width:150px; background-color:#059669; color:#ffffff;">Created By</th>
            </tr>
        </thead>
        <tbody>
            @foreach($komoditasData as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td class="text-left">{{ $item['nama_komoditas'] }}</td>
                <td>{{ $item['tanggal'] }}</td>
                <td>{{ $item['harga_formatted'] }}</td>
                <td>{{ ucfirst($item['status']) }}</td>
                <td>{{ $item['created_by'] }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" style="border:none; text-align:right; font-size:12px; padding-top:10px;">
                    Dicetak : {{ now()->format('d M Y H:i:s') }}
                </td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
