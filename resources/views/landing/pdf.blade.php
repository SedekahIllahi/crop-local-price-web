<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tabel Harga PDF</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .kop { display: flex; align-items: center; margin-bottom: 16px; }
        .kop-logo { height: 60px; width: 60px; margin-right: 16px; }
        .kop-text { line-height: 1.2; }
        .kop-title { font-size: 16px; font-weight: bold; }
        .kop-subtitle { font-size: 14px; font-weight: 600; }
        .kop-address { font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 4px; text-align: center; }
        th { background: #f0f0f0; }
        .harga-max { background: #fecaca; }
        .harga-min { background: #bbf7d0; }
    </style>
</head>
<body>
    <table style="width:100%; margin-bottom:4px; border:none;">
        <tr>
            <td style="width:110px; border:none; vertical-align:middle; text-align:center;">
                <img src="{{ public_path('images/logo_kabBantul.png') }}" alt="Logo Bantul" style="width:90px; height:auto; max-height:100px; display:inline-block; vertical-align:middle;">
            </td>
            <td style="border:none; vertical-align:middle; text-align:center;">
                <div style="font-size:20px; font-weight:bold; letter-spacing:1px; margin-bottom:2px;">PEMERINTAH KABUPATEN BANTUL</div>
                <div style="font-size:17px; font-weight:bold; margin-bottom:2px;">DINAS KOPERASI, UMKM, PERINDUSTRIAN, DAN PERDAGANGAN </div>
                <div style="font-size:14px; margin-bottom:2px;">Komplek Pemda II Manding Bantul, Jl. Lingkar Timur Manding Trirenggo Bantul</div>
                <div style="font-size:14px; margin-bottom:2px;">Telepon: (0274-2810422 / 0812 2566 5517) Email: diskukmpp@bantulkab.go.id</div>
                <div style="font-size:14px; margin-bottom:2px;">Website: https://dkukmpp.bantulkab.go.id</div>
            </td>
        </tr>
    </table>
    <hr style="border:1px solid #222; margin-bottom:10px; margin-top:2px;">
    <h2 style="margin-bottom:8px;">Tabel Harga Komoditas</h2>
    <p style="margin-bottom:4px;">Periode: {{ $start->format('d M Y') }} - {{ $end->format('d M Y') }}</p>
    @if($pasarNama ?? false)
    <p style="margin-bottom:4px;">Pasar: {{ $pasarNama }}</p>
    @endif
    <p style="margin-bottom:4px;">
        Komoditas: {{ $komoditasNama ?? 'Semua Komoditas' }}
    </p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Komoditas</th>
                @foreach($tanggalList as $tanggal)
                    <th>{{ $tanggal->format('d M') }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($komoditasData as $item)
            <tr>
                <td>{{ $item['no'] }}</td>
                <td style="text-align: left;">{{ $item['nama'] }} @if(!empty($item['satuan']))({{ $item['satuan'] }})@endif</td>
                @foreach($tanggalList as $tanggal)
                    @php 
                        $key = $tanggal->format('Y-m-d');
                        $hargaString = $item['harga'][$key] ?? '-';
                        $hargaNumeric = $hargaString !== '-' ? (int) str_replace(['Rp', '.', ' '], '', $hargaString) : null;
                    @endphp
                    <td class="@if($hargaNumeric == $item['max']) harga-max @elseif($hargaNumeric == $item['min']) harga-min @endif">
                        {{ $hargaString }}
                    </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
    <div style="margin-top:16px; text-align:right; font-size:12px;">Dicetak : {{ \Carbon\Carbon::now()->format('d M Y H:i:s') }}</div>
</body>
</html>
