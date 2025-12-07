<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold text-center text-gray-800">Welcome to the Dashboard</h1>
        <p class="text-center text-gray-600 mt-4">This is the homepage. You can log in or register to get started.</p>
        <div class="mt-8 flex justify-center">
            <a href="{{ route('login') }}" class="px-4 py-2 bg-blue-500 text-white font-medium rounded-md hover:bg-blue-600">Login</a>
            <a href="{{ route('register') }}" class="ml-4 px-4 py-2 bg-gray-500 text-white font-medium rounded-md hover:bg-gray-600">Register</a>
        </div>
    </div>
</body>
</html>