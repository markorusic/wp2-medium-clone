<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Contact</title>
    <style>
    body {
        font-family: Helvetica;
    }
  </style>
</head>
<body>
    <h3>Nova poruka sa kontakt forme</h3>
    <ul style="list-style-type: none; padding: 0; margin: 0;">
        @foreach($data as $key => $value)
            <li>{{ ucfirst($key) }} - {{ $value }}</li>
        @endforeach
    </ul>
</body>
</html>
