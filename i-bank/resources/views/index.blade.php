<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The iBank</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .center {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            height: 100vh;
        }

        .big-text {
            font-size: 72px;
            font-family: "Georgia", serif;
            font-weight: bold;
            color: #1b1b32;
            margin: 20px 0;
        }

        .logo {
            width: 150px;
        }
    </style>
</head>
<body>
<div class="center">
    <img src="{{ asset('logo/iBank-3.png') }}" alt="iBank Logo" class="logo">
    <p class="big-text">
        The iBank
    </p>
    <button
        class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-2 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"
        onclick="location.href='register'" type="submit">Enter
    </button>
</div>
</body>
</html>
