<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Planet Express</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body>
        <form method="GET" action="/books/search">
            <input type="text" name="title" id="title" placeholder="Title" />
            <input type="text" name="author" id="author" placeholder="Author" />
            <input type="text" name="category" id="category" placeholder="Category" />
            <input type="number" name="pageNumber" id="pageNumber" value="1" class="hidden" />
            <x-form-button>Search</x-form-button>
        </form>
    </body>
</html>
