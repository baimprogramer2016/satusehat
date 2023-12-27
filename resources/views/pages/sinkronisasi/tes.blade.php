<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>

    <table class="table">
        <thead>
            <tr>
                @foreach ($data_query[0] as $col => $value)
                <th>{{ $col }}</th>
                @endforeach
            </tr>
        </thead>
        @foreach ($data_query as $row)
        <tr>
            @foreach ($row as $value)
            <td>{{ $value }}</td>
            @endforeach
        </tr>
        @endforeach
    </table>
</body>

</html>