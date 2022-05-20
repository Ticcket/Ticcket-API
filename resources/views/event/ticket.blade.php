<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
    <title>Ticcket - {{ ucfirst($ticket->event->title) }}</title>
</head>
<body>
    <div class="w-full max-w-md m-auto mt-10">
        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <h2 class="font-medium leading-tight text-2xl mt-0 mb-2 text-gray-800 text-center">Thank You For Registering!</h2>
            <table class="mt-10 mb-5 m-auto w-full text-center">
                <tbody>
                    <tr>
                        <th>Full Name</th>
                        <th>Email Address</th>
                    </tr>
                    <tr>
                        <td>{{ $ticket->name }}</td>
                        <td>{{ $ticket->email }}</td>
                    </tr>
                </tbody>
            </table>
            <hr />
            <img class="m-auto" src="{{ $ticket->url }}" alt="{{ $ticket->email }}">
            <p class="text-red-500 text-center font-small leading-tight">* Make Sure To Save The Ticket, In Case You Didn't Receive An Email.</p>
        </div>
    </div>
</body>
</html>
