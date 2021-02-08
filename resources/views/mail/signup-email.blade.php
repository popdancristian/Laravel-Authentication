Hello {{ $email_data['name'] }},
<br>
Welcome to my first laravel8 website!

<br><br>
Please click the bellow link to verify your email!
<a href = "http://127.0.0.1:8000/verify?code={{ $email_data['verification_code']}}">Click me!</a>

<br>
Thank you!
