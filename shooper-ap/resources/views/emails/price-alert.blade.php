<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    @vite('resources/css/app.css')
</head>

<body class="relative font-montserrat bg-[#343434]">
    <div class="w-full p-5 flex justify-center">
        <h1 class="uppercase font-bold text-xl text-white">price alert</h1>
    </div>
    <div class="w-full h-auto flex">
        <div class="relative w-full h-auto flex flex-col gap-2.5 items-center bg-[#343434]">
            <img src="{{ $priceAlert->product->filename }}" class="w-48 rounded-xl" />
            <h1 class="text-white font-bold">{{$priceAlert->product->name}} </h1>
        </div>
        <div class="relative w-full h-auto flex flex-col gap-2.5 items-center justify-center bg-[#343434]">
            <span class="text-white tracking-wider text-right">Desired Price: <span class="text-xl font-bold text-[#F36F3F]">{{$priceAlert->desired_price}} €</span></span>
            <span class="text-white tracking-wider text-right">Product Price: <span class="text-xl font-bold text-[#F36F3F]">{{$minPrice}} €</span></span>
        </div>
    </div>
</body>

</html>