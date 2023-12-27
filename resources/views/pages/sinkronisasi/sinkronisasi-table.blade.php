<table class="table table-bordered">
    <thead>
        <tr>
            @foreach ($data_query[0] as $col => $value)
            <th scope="col">{{ $col }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($data_query as $row)
        <tr>
            @foreach ($row as $value)
            <td>{{ $value }}</td>
            @endforeach
        </tr>
        @endforeach
    </tbody>
</table>