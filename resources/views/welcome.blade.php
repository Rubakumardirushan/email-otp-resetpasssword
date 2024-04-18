<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            font-family: Arial, sans-serif; /* Use a common sans-serif font */
            background-color: #f8f9fa; /* Set a light background color */
            text-align: center; /* Center-align text */
            padding-top: 50px; /* Add some space from the top */
        }
        h1 {
            color: #007bff; /* Set heading color to blue */
            margin-bottom: 20px; /* Add some space below the heading */
        }
        a {
            color: #007bff; /* Set link color to blue */
            text-decoration: none; /* Remove underline */
        }
        a:hover {
            text-decoration: underline; /* Underline on hover */
        }
    </style>
</head>
<body>
    <h1>Hello, {{ Auth::user()->name }}!</h1>
    <a href="logout">Logout</a>
</body>
</html>
