<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="{{action('SituacaoController@create')}}" method="post">
    @csrf
        <input type="text" name="situacao">
        <button type="submit">Send</button>
    </form>
</body>
</html>