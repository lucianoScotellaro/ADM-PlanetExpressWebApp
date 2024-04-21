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
        <div class="flex flex-row h-screen">
            <div class="basis-1/4"></div>
            <div class="basis-3/4 overflow:auto">
                <ul role="list" class="divide-y divide-gray-100">
                    @foreach($books as $book)
                        <x-list-element :book="$book" :user="$user"></x-list-element>
                    @endforeach
                </ul>
            </div>
        </div>
    </body>
</html>
