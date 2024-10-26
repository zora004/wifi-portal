<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>C-ONE Wifi Portal</title>
        <link rel="icon" href="{{ asset('/assets/icons/favicon.ico') }}" type="image/x-icon">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        @vite('resources/css/app.css')
        <style>
            .bg-image{
                position: relative;
                width: 100%;
                height: 100vh;
                background-image: url('/assets/images/background.jpg');
                background-size: 100%;
                background-repeat: no-repeat;
                background-position: center;
            }
            .bg-image::after{
                content: '';
                position: absolute;
                width: 100%;
                height: 100%;
                top: 0;
                left: 0;
                background-color: rgba(0,0,0,0.55);
            }
            #loading {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(255, 255, 255, 0.8);
                z-index: 9999; /* Make sure it’s on top of other elements */
                display: none; /* Initially hidden */
                justify-content: center;
                align-items: center;
            }
            .spinner {
                border: 5px solid rgba(255, 255, 255, 0.3);
                border-top: 5px solid #9c5e0c; /* Spinner color */
                border-radius: 50%;
                width: 50px;
                height: 50px;
                animation: spin 1s linear infinite; /* Spin animation */
                margin: auto;
                margin-top: 45vh;
            }
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        </style>
    </head>
    <body class="h-screen flex items-center justify-center bg-image">
        {{-- bg-gray-100 --}}
        {{-- dark:bg-slate-900 --}}
        <div class="bg-white/10 backdrop-blur-md px-6 py-8 rounded-lg ring-1 ring-slate-900/5 shadow-xl w-full max-w-sm z-40">
                <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-4 opacity-0 transition-opacity duration-500 ease-in-out hidden" id="success_container">
                    <p id="success_msg" class="text-sm">success message</p> 
                </div>
                <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-4 opacity-0 transition-opacity duration-500 ease-in-out hidden" id="error_container">
                    <p id="error_msg" class="text-sm">error message</p>
                </div>
                
                <div class="space-y-3">
                    <img src="{{ asset('/assets/images/coffeelogo_mockup_transparent.png') }}" alt="asdas">
                    <p class="text-center text-sm text-white subpixel-antialiased font-light italic ">"Where Every Sip is a Smash Hit!"</p>
                    <label for="voucher" class="block text-sm font-medium text-white uppercase">Voucher Code</label>
                    <input type="text" name="voucher" id="voucher" style="text-transform: uppercase;" class="peer required:border-yellow-500 mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-brown-500 focus:border-brown-500 font-bold" required placeholder="SHUTTLE-FITG-WBER">
                    <p class="mt-2 invisible peer-invalid:visible text-yellow-600 text-sm">
                    Please provide a voucher code.
                    </p>
                </div>
                <div class="items-center justify-center hidden" id="loading-container">
                    <span class="w-full relative inline-flex">
                        <button type="button" class="w-full inline-flex items-center justify-center py-2 px-4 my-2 font-semibold leading-6 text-sm shadow rounded-md text-yellow-500 bg-[#67391b]  transition ease-in-out duration-150 cursor-not-allowed ring-1 ring-slate-900/10 dark:ring-slate-200/20" disabled="">
                        Processing...
                        </button>
                        <span class="flex absolute h-4 w-4 top-0 right-0 -mt-0 -mr-1">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-4 w-4 bg-yellow-500"></span>
                        </span>
                    </span>
                </div>
                <button id="verify" type="submit" class="w-full py-2 px-4 my-2 bg-yellow-500 text-white font-semibold rounded-md shadow-sm hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                Submit
                </button>
        </div>
        <div id="loading">
            <div class="spinner"></div>
        </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        $(window).on('load', function() {
            $('#loading').fadeOut(); // Hide the loading spinner once the page is fully loaded
            let fadeTimeout, errorFadeTimeout;

            $('#voucher').on('input', function() {
                if ($(this).val().trim() === '') {
                    $(this).addClass('required:border-red-500')
                } else {
                    $(this).removeClass('required:border-red-500')
                }
            });

            $('#voucher').on('keypress', function(event) {
                if (event.which === 13) {
                    submit($('#voucher').val())
                }
            });

            $('#verify').on('click', function(e){
                submit($('#voucher').val())
            })

            function submit(voucher){
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                enableLoading()

                if(voucher){
                    var url = 'voucher/:voucher'
                    url = url.replace(':voucher', voucher.toUpperCase())
                    console.log(url)
                    $.ajax({
                        type: 'PUT',
                        url: url,
                        dataType: 'json',
                        success: function(response){
                            const title = response.title
                            const message = response.message
                            disableLoading()
                            success(message)
                        }, error: function(response){
                            const title = response.responseJSON.title
                            const message = response.responseJSON.message
                            disableLoading()
                            error(message)
                        }
                    })
                }else{
                    setTimeout(() => {
                        // Hide loading state
                        disableLoading()
                    }, 500); // Simulate a 2-second loading time
                }
            }

            function success(message) {
                const fadeElement = document.getElementById('success_container');
                $('#success_container').removeClass('hidden');
                $('#error_container').addClass('hidden');

                $('#success_msg').html(`<i class="fa-regular fa-circle-check"></i> ${message}`);
                if (fadeTimeout) {
                    clearTimeout(fadeTimeout);
                    fadeElement.classList.remove('opacity-100', 'hidden');
                    fadeElement.classList.add('opacity-0'); // Reset opacity to 0
                } else {
                    $('#success_container').removeClass('hidden'); // Show container if hidden
                    $('#error_container').addClass('hidden'); // Hide error container
                }

                // Fade in
                fadeElement.classList.remove('opacity-0');
                fadeElement.classList.add('opacity-100');

                // Set a timeout for fading out
                fadeTimeout = setTimeout(() => {
                    fadeElement.classList.remove('opacity-100');
                    fadeElement.classList.add('opacity-0');

                    // Set another timeout to hide the element after fading out
                    setTimeout(() => {
                        fadeElement.classList.add('hidden');
                    }, 400); // Match with the fade-out duration
                }, 5000); // Show the message for 5 seconds
            }

            function error(message) {
                const fadeElement = document.getElementById('error_container');
                $('#success_container').addClass('hidden');
                $('#error_container').removeClass('hidden');

                $('#error_msg').html(`<i class="fa-solid fa-circle-exclamation"></i> ${message}`);
                if (errorFadeTimeout) {
                    clearTimeout(errorFadeTimeout);
                    fadeElement.classList.remove('opacity-100', 'hidden');
                    fadeElement.classList.add('opacity-0'); // Reset opacity to 0
                } else {
                    $('#success_container').addClass('hidden'); // Hide success container
                    $('#error_container').removeClass('hidden'); // Show error container
                }

                // Fade in the error message
                fadeElement.classList.remove('opacity-0');
                fadeElement.classList.add('opacity-100');

                // Set a timeout for fading out
                errorFadeTimeout = setTimeout(() => {
                    fadeElement.classList.remove('opacity-100');
                    fadeElement.classList.add('opacity-0');

                    // Set another timeout to hide the element after fading out
                    setTimeout(() => {
                        fadeElement.classList.add('hidden');
                    }, 400); // Match with the fade-out duration
                }, 5000); // Show the message for 5 seconds
            }

            function enableLoading(){
                $('#loading-container').removeClass('hidden')
                $('#verify').addClass('hidden')
            }

            function disableLoading(){
                $('#loading-container').addClass('hidden')
                $('#verify').removeClass('hidden')
            }
        });

        $(document).ready(function() {
            $('#loading').fadeIn(); // Show the loading spinner when the document is ready
        });
    </script>
</body>
</html>
