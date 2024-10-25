<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Laravel</title>
        @vite('resources/css/app.css')
    </head>
    <body class="h-screen flex items-center justify-center bg-gray-100">

    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-sm">
        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-4">
                {{ session('success') }}
            </div>
        @endif
            <div class="space-y-4">
                <label for="voucher" class="block text-sm font-medium text-gray-700">Voucher Code</label>
                <input type="text" name="voucher" id="voucher" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                @error('voucher')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <button id="verify" type="submit" class="w-full py-2 px-4 bg-blue-500 text-white font-semibold rounded-md shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Submit
            </button>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        $('#verify').on('click', function(e){
            alert($('#voucher').val())
        })
    </script>
</body>
</html>
