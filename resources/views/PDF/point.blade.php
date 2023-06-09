<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print Point Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</head>
<body>
    @foreach ($data as $key=>$item)
        <table class="table table-bordered" style="border: 1px solid black;">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Jarak</th>
                    <th>Tanggal</th>
                    <th>Jenis Busur</th>
                    <th>Rambahan</th>
                </tr>
                <tr>
                    <th>{{ $item['username'] }}</th>
                    <th>{{ $item['point_jarak'] }}</th>
                    <th>{{ $item['point_tanggal'] }}</th>
                    <th>{{ $item['jenis_busur_name'] }}</th>
                    <th>{{ $item['point_rambahan'] }}</th>
                </tr>
            </thead>
            <tbody>
                {{-- @foreach ($item['point_detail'] as $key2=>$item2)
                    <tr></tr>
                @endforeach --}}
            </tbody>
        </table>
    @endforeach

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>
</body>
</html>