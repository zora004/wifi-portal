@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<body class="h-screen flex items-center justify-center bg-image">
    <div class="bg-white/10 backdrop-blur-md px-6 py-8 rounded-lg ring-1 ring-slate-900/5 shadow-xl w-full max-w-sm z-40">
            <div class="bg-white shadow-md p-4 rounded-lg mb-4 transition-opacity duration-500 ease-in-out opacity-100 border border-green-300 hidden" id="success_container">
                <p id="success_title" class="text-sm font-bold uppercase text-green-600">success message</p> 
                <p id="success_msg" class="text-sm italic text-gray-700">success message</p> 
            </div>
            <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-4 opacity-0 transition-opacity duration-500 ease-in-out hidden" id="error_container">
                <p id="error_title" class="text-sm font-bold uppercase">error title</p>
                <p id="error_msg" class="text-sm italic">error message</p>
            </div>
            
            <div class="space-y-3">
                <img src="{{ asset('/assets/images/coffeelogo_mockup_transparent2.png') }}" alt="asdas">
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
                            success(title, message)
                        }, error: function(response){
                            const title = response.responseJSON.title
                            const message = response.responseJSON.message
                            disableLoading()
                            error(title, message)
                        }
                    })
                }else{
                    setTimeout(() => {
                        // Hide loading state
                        disableLoading()
                    }, 500); // Simulate a 2-second loading time
                }
            }

            function success(title, message) {
                const fadeElement = document.getElementById('success_container');
                $('#success_container').removeClass('hidden');
                $('#error_container').addClass('hidden');

                $('#success_title').html(`<i class="fa-regular fa-circle-check"></i> ${title}`);
                $('#success_msg').html(`~${message}`);
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

            function error(title, message) {
                const fadeElement = document.getElementById('error_container');
                $('#success_container').addClass('hidden');
                $('#error_container').removeClass('hidden');

                $('#error_title').html(`<i class="fa-solid fa-circle-exclamation"></i> ${title}`);
                $('#error_msg').html(`~${message}`);
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
@endsection
