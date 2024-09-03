<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>World Information App</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        input[type="text"] {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 300px;
        }

        button {
            padding: 10px 15px;
            font-size: 16px;
            border: none;
            background-color: #4CAF50;
            color: white;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 10px;
        }

        button:hover {
            background-color: #45a049;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        .pagination {
            text-align: center;
            margin-top: 20px;
        }

        .pagination a {
            color: #333;
            padding: 8px 16px;
            text-decoration: none;
            border: 1px solid #ccc;
            margin: 0 4px;
            border-radius: 4px;
            background-color: #f4f4f9;
        }

        .pagination a:hover {
            background-color: #ddd;
        }

        .weather-info {
            margin-left: 10px;
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>
    <h1>Countries of the World</h1>

    <form method="GET" action="/countries">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by country name...">
        <button type="submit">Search</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Population</th>
                <th>Region</th>
                <th>Languages</th>
                <th>Currencies</th>
                <th>Capital</th>
                <th>Weather</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($countries as $country)
            <tr>
                <td>{{ $country['name']['common'] }}</td>
                <td>{{ number_format($country['population']) }}</td>
                <td>{{ $country['region'] }}</td>
                <td>
                    @if(isset($country['languages']))
                        {{ implode(', ', $country['languages']) }}
                    @else
                        N/A
                    @endif
                </td>
                <td>
                    @if(isset($country['currencies']))
                        {{ implode(', ', array_keys($country['currencies'])) }}
                    @else
                        N/A
                    @endif
                </td>
                <td>{{ $country['capital'][0] ?? 'N/A' }}</td>
                <td>
                    @if(isset($country['capital'][0]))
                        <button class="load-weather" data-city="{{ $country['capital'][0] }}">Load Weather</button>
                        <span class="weather-info"></span>
                    @else
                        N/A
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="pagination">
        @for ($i = 1; $i <= ceil($total / $perPage); $i++)
            <a href="?page={{ $i }}{{ $search ? '&search=' . $search : '' }}">{{ $i }}</a>
        @endfor
    </div>

<script>
    $(document).on('click', '.load-weather', function() {
        var button = $(this);
        var city = button.data('city');

        // Disable the button to prevent multiple clicks
        button.prop('disabled', true).text('Loading...');

        $.get('/weather?city=' + encodeURIComponent(city), function(data) {
            button.next('.weather-info').text(data);
            button.remove();  // Remove button after loading weather
        }).fail(function() {
            button.prop('disabled', false).text('Load Weather');
            alert('Failed to load weather data. Please try again.');
        });
    });
</script>

</body>
</html>
 