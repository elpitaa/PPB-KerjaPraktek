@extends('template.layout')
@section('title', 'Home | SiKaPe')
@section('content')



    <section class="bg-white dark:bg-gray-900 mt-16">
        <div class="py-8 px-4 mx-auto max-w-screen-xl min-h-fit lg:py-16 flex flex-col-reverse lg:flex-row">
            <div class=" text-left w-full lg:w-2/4 p-4 border-e-2 border-gray-400">
                <h1
                    class="mb-4 text-4xl font-extrabold tracking-tight leading-none text-gray-900 md:text-5xl lg:text-6xl dark:text-white">
                    Selamat Datang</h1>
                <p class="mb-8 text-lg pe-4 font-normal text-justify text-gray-500 lg:text-xl dark:text-gray-400">
                    <span class="font-bold text-blue-700 dark:text-gray-400">SiKaPe</span> merupakan sistem informasi
                    kerja prakter yang
                    bertujuan memudahkan mahasiswa
                    dan dosen pembimbing dalam melaksanakan prosedur kerja prakter. Sistem ini memungkinkan mahasiswa untuk
                    melakukan pengajuan kerja praktek,pendaftaran proposal kerja prakter, bimbingan laporan kerja praktek,
                    pendaftaran sidang kerja praktek, dan input nilai kerja praktek. Sistem ini juga memungkinkan dosen
                    untuk melakukan validasi pengajuan, proses bimbingan, revisi laporan, validasi laporan dan sidang kerja
                    praktek serta input nilai kerja praktek.

                </p>
                <div class="flex flex-col space-y-4 sm:flex-row sm:justify-start sm:space-y-0">
                    {{-- <a href="#"
                        class="py-3 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-blue-700 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                        Learn more
                    </a> --}}
                    <button id="dropdownDefaultButton" data-dropdown-toggle="dropdown"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 sm:ms text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                        type="button">Login<svg class="w-2.5 h-2.5 ms-3" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 4 4 4-4" />
                        </svg>
                    </button>

                    <!-- Dropdown menu -->

                    <div id="dropdown"
                        class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 border border-blue-700">
                        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">
                            <li>
                                <a href="{{ route('filament.mahasiswa.auth.login') }}"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Mahasiswa</a>
                            </li>
                            <li>
                                <a href="{{ route('filament.dosen.auth.login') }}"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Dosen</a>
                            </li>
                            <li>
                                <a href="{{ route('filament.admin.auth.login') }}"
                                    class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Admin</a>
                            </li>
                        </ul>
                    </div>

                </div>

            </div>
            <div class="w-full lg:w-2/4 p-4 ">
                <img class="h-5/6" src="{{ asset('img/logoUtm.png') }}" alt="" srcset="">
            </div>
        </div>
    </section>

    {{-- <section class="bg-white dark:bg-gray-900">
        <div class="h-screen">

        </div>
    </section> --}}


@endsection
