<!DOCTYPE html>
<html>

<head>
    <title>Laravel Practicle</title>
</head>

<body>
    <h1>{{ str_replace('-', ' ', strtoupper($details['name'])) }} Company Account Successfully Created.</h1>
    <p>hear all the detail for login</p>
    <ul>
        <li>Login URL : <a href="http://{{ $details['domain'] }}/">{{ $details['domain'] }}</a></li>
        <li>Username : {{ $details['email'] }}</li>
        <li>Password : {{ $details['password'] }}</li>
    </ul>
    <p>Thank you</p>
</body>

</html>