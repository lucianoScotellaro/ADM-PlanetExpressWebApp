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
        <header>
            <h1 class="text-center text-bold text-4xl">Utenti che cedono: {{$book->title}}</h1>
        </header>
        @foreach($proposers as $proposer)
            <div class="flex flex-row justify-between m-5 border-solid border-4 border-gray-200 items-center rounded-md">
                <div class="basis-1/2 inline-flex m-2">
                    <div>
                        <x-book-image src="{{asset('img/profile-image-not-found.jpg')}}" class="w-1/6"></x-book-image>
                    </div>
                    <div>
                        <x-book-title>{{ $proposer->name }}</x-book-title>
                        <x-book-author>{{ $proposer->email }}</x-book-author>
                    </div>
                </div>
                <div class="flex basis-1/2 justify-end items-center">
                    <x-button class="m-2" href="{{'/users/'.$proposer->id}}">Visualizza profilo</x-button>
                </div>
            </div>
        @endforeach
    </body>
</html>
