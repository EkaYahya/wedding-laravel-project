<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Undangan Pernikahan</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Montserrat:wght@400;600&display=swap"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css"
        rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    @foreach ($undangan as $undangan)
        <!-- Carousel Start -->
        <div class="container-fluid p-0 mb-5 pb-5" id="home">
            <div id="header-carousel" class="carousel slide carousel-fade" data-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item position-relative active" style="height: 100vh; min-height: 400px;">
                        <img class="position-absolute w-100 h-100" src="img/carousel-1.jpg" style="object-fit: cover;">
                        <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                            <div class="p-3" style="max-width: 900px;">

                                <div class="d-inline-block border-top border-bottom border-light py-3 px-4">
                                    <h3 class="text-uppercase font-weight-normal text-white m-0"
                                        style="letter-spacing: 2px; margin-bottom: 50px;">
                                        Dengan Hormat Kami Mengundang Saudara/i:
                                    </h3>
                                </div>
                                <!-- Menampilkan nama tamu dari $guest -->
                                <h1 class="display-1 font-secondary text-white mt-n3 mb-md-4">
                                    {{ $guest->name ?? 'Tamu Undangan' }}
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>
                <a class="carousel-control-prev justify-content-start" href="#header-carousel" data-slide="prev">
                    <div class="btn btn-primary px-0" style="width: 68px; height: 68px;">
                        <span class="carousel-control-prev-icon mt-3"></span>
                    </div>
                </a>
                <a class="carousel-control-next justify-content-end" href="#header-carousel" data-slide="next">
                    <div class="btn btn-primary px-0" style="width: 68px; height: 68px;">
                        <span class="carousel-control-next-icon mt-3"></span>
                    </div>
                </a>
            </div>
        </div>
        <!-- Carousel End -->


        <!-- Video Modal Start -->
        <div class="modal fade" id="videoModal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <!-- 16:9 aspect ratio -->
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe class="embed-responsive-item"
                                src="" id="video" allowscriptaccess="always"
                                allow="autoplay"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Video Modal End -->


        <!-- About Start -->
        <div class="container-fluid py-5" id="about">
            <div class="container py-5">
                <div class="section-title position-relative text-center">
                    <h6 class="text-uppercase text-primary mb-3" style="letter-spacing: 3px;">Tentang</h6>
                    <h1 class="font-secondary display-4">{{ $undangan->nama_pasangan }}</h1>
                    <i class="far fa-heart text-dark"></i>
                </div>
                <div class="row m-0 mb-4 mb-md-0 pb-2 pb-md-0">
                    <div class="col-md-6 p-0 text-center text-md-right">
                        <div class="h-100 d-flex flex-column justify-content-center bg-secondary p-5">
                            <h3 class="mb-3">Mempelai Pria</h3>
                            <p>{{ $undangan->keterangan_laki2 }}</p>
                            <h3 class="font-secondary font-weight-normal text-muted mb-3">
                                <i class="fa fa-male text-primary pr-3"></i>
                                {{ $undangan->nama_laki2 }}
                            </h3>
                        </div>
                    </div>
                    <div class="col-md-6 p-0" style="min-height: 400px;">
                        <img class="position-absolute w-100 h-100" src="{{ Storage::url($undangan->foto_pria) }}"
                            style="object-fit: cover;">
                    </div>
                </div>
                <div class="row m-0">
                    <div class="col-md-6 p-0" style="min-height: 400px;">
                        <img class="position-absolute w-100 h-100" src="{{ Storage::url($undangan->foto_prmp) }}"
                            style="object-fit: cover;">
                    </div>
                    <div class="col-md-6 p-0 text-center text-md-left">
                        <div class="h-100 d-flex flex-column justify-content-center bg-secondary p-5">
                            <h3 class="mb-3">Mempelai Perempuan</h3>
                            <p>{{ $undangan->keterengan_prpmp }}</p>
                            <h3 class="font-secondary font-weight-normal text-muted mb-3">
                                <i class="fa fa-female text-primary pr-3"></i>
                                {{ $undangan->nama_prmp }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- About End -->


        <!-- Event Start -->
        <div class="container-fluid py-5" id="event">
            <div class="container py-5">
                <div class="section-title position-relative text-center">
                    <h6 class="text-uppercase text-primary mb-3" style="letter-spacing: 3px;">Event</h6>
                    <h1 class="font-secondary display-4">{{ $undangan->nama_resepsi }}</h1>
                    <i class="far fa-heart text-dark"></i>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-6 text-center">
                        <h5 class="font-weight-normal text-muted mb-3 pb-3">
                            {{ $undangan->keterangan_resepsi }}
                        </h5>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 border-right border-primary">
                        <div class="text-center text-md-right mr-md-3 mb-4 mb-md-0">
                            <img class="img-fluid mb-4" src="{{ Storage::url($undangan->foto_akad) }}" alt="">
                            <h4 class="mb-3">Akad</h4>
                            <div class="mb-2" style="font-weight: bold;">{{ $undangan->tempat_resepsi }}</div>
                            <p class="mb-0">{{ \Carbon\Carbon::parse($undangan->tanggal_resepsi)->format('d-m-Y') }}</p>
                            <p class="mb-0">{{ $undangan->jam_resepsi }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-center text-md-left ml-md-3">
                            <img class="img-fluid mb-4" src="{{ Storage::url($undangan->foto_resepsi) }}" alt="">
                            <h4 class="mb-3">Resepsi</h4>
                            <div class="mb-2" style="font-weight: bold;">{{ $undangan->tempat_resepsi }}</div>
                            <p class="mb-0">{{ \Carbon\Carbon::parse($undangan->tanggal_resepsi)->format('d-m-Y') }}</p>
                            <p class="mb-0">{{ $undangan->jam_resepsi }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Event End -->
        @endforeach

        <!-- RSVP Start -->
        <div class="container-fluid py-5" id="rsvp">
            <div class="container py-5">
                <div class="section-title position-relative text-center">
                    <h6 class="text-uppercase text-primary mb-3" style="letter-spacing: 3px;">Kehadiran</h6>
                    <h1 class="font-secondary display-4">Kehadiran Anda adalah kebahagiaan kami</h1>
                    <i class="far fa-heart text-dark"></i>
                </div>

                <!-- Form RSVP -->
                <div class="row justify-content-center mt-5">
                    <div class="col-lg-6">
                        <h4 class="text-center mb-4">Konfirmasi Kehadiran</h4>
                        <form action="{{ route('guests.updateRSVP', $guest->slug) }}" method="POST"
                              class="bg-light p-4 rounded shadow">
                            @csrf
                            @method('PUT')

                            <!-- Dropdown untuk kehadiran -->
                            <div class="form-group">
                                <label for="will_attend" class="text-lg">Apakah Anda Akan Hadir?</label>
                                <select id="will_attend" name="will_attend"
                                    class="form-control bg-secondary border-0 p-3" style="height: 3.5rem;" required>
                                    <option value="1" {{ ($guest->will_attend ?? null) == 1 ? 'selected' : '' }}>
                                        Ya
                                    </option>
                                    <option value="0" {{ ($guest->will_attend ?? null) === 0 ? 'selected' : '' }}>
                                        Tidak
                                    </option>
                                </select>
                            </div>

                            <!-- Dropdown jumlah orang -->
                            <div class="form-group">
                                <label for="number_of_guests" class="text-lg">Berapa Orang Yang Bersama Anda?</label>
                                <select id="number_of_guests" name="number_of_guests"
                                    class="form-control bg-secondary border-0 p-3" style="height: 3.5rem;" required>
                                    @for ($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}"
                                            {{ ($guest->number_of_guests ?? 1) == $i ? 'selected' : '' }}>
                                            {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block mt-4">
                                Konfirmasi Kehadiran
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Greeting and QR Code Section -->
                <div class="row justify-content-center mt-5">
                    <!-- Comment Form for Greetings -->
                    <div class="col-lg-6 mb-5">
                        <h4 class="text-center mb-4">Kirim Ucapan Selamat</h4>
                        <form action="{{ route('guests.updateGreeting', $guest->slug) }}" method="POST"
                              class="bg-light p-4 rounded shadow">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="name" value="{{ $guest->name }}" readonly>
                            <div class="form-group">
                                <label for="greeting_message" class="text-lg">Ucapan</label>
                                <textarea id="greeting_message" name="greeting_message" rows="3" maxlength="500"
                                          class="form-control bg-secondary border-0 p-3"
                                          placeholder="Tulis ucapan selamat..."
                                          required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block mt-4">
                                Kirim Ucapan
                            </button>
                        </form>
                    </div>

                    <!-- Display QR Code -->
                    <div class="col-lg-6 mb-5 text-center">
                        <h4 class="text-center mb-4">Scan QR Code untuk Kehadiran</h4>
                        <div class="bg-white p-4 rounded shadow d-flex flex-column align-items-center">
                            <!-- QR Code Image dengan ID -->
                            <div id="qrcode-container">
                                {!! QrCode::size(200)->generate(route('guests.updateAttendance', $guest->slug)) !!}
                            </div>
                            
                            <!-- Download Button menggunakan JavaScript -->
                            <button class="btn btn-primary mt-3" onclick="downloadQRCode()">
                                <i class="fa fa-download mr-2"></i>Download QR Code
                            </button>
                        </div>
                        <p class="mt-3">
                            Silakan scan QR ini saat kedatangan untuk konfirmasi kehadiran Anda.
                        </p>
                    </div>
                </div>

                <!-- Display of all greetings as comments -->
                <div class="row justify-content-center mt-5">
                    <div class="col-lg-8">
                        <h4 class="text-center mb-4">Ucapan dari Tamu Lain</h4>
                        <div class="list-group">
                            @foreach ($allGreetings as $greeting)
                                <div class="list-group-item bg-light mb-2 p-3 rounded">
                                    <h5 class="mb-1">{{ $greeting->name }}</h5>
                                    <p class="mb-0">{{ $greeting->greeting_message }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- RSVP End -->

        <!-- Footer Start -->
        <div class="container-fluid bg-dark text-white py-5" id="contact" style="margin-top: 90px;">
            <div class="container text-center py-5">
                <div class="section-title position-relative text-center">
                    <h1 class="font-secondary display-3 text-white">Thank You</h1>
                    <i class="far fa-heart text-white"></i>
                </div>
                <div class="d-flex justify-content-center mb-4">
                    <a class="btn btn-lg btn-outline-light btn-lg-square mr-2" href="#">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a class="btn btn-lg btn-outline-light btn-lg-square mr-2" href="#">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a class="btn btn-lg btn-outline-light btn-lg-square mr-2" href="#">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a class="btn btn-lg btn-outline-light btn-lg-square" href="#">
                        <i class="fab fa-instagram"></i>
                    </a>
                </div>
                <div class="d-flex justify-content-center py-2">
                    <p class="text-white">info@example.com</p>
                    <span class="px-3">|</span>
                    <p class="text-white">+012 345 6789</p>
                </div>
                <p class="m-0">
                    &copy;
                    <a class="text-primary" href="#">Domain Name</a>.
                    Designed by
                    <a class="text-primary" href="https://htmlcodex.com">HTML Codex</a>
                </p>
            </div>
        </div>
        <!-- Footer End -->

        <!-- Scroll to Bottom -->
        <i class="fa fa-2x fa-angle-down text-white scroll-to-bottom"></i>

        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-outline-primary btn-lg-square back-to-top">
            <i class="fa fa-angle-double-up"></i>
        </a>
        <script>
        function downloadQRCode() {
            // Ambil elemen QR code
            const qrCodeContainer = document.getElementById('qrcode-container');
            const svg = qrCodeContainer.querySelector('svg');
            
            // Jika elemen adalah SVG
            if (svg) {
                // Buat canvas
                const canvas = document.createElement('canvas');
                const context = canvas.getContext('2d');
                
                // Set ukuran canvas
                canvas.width = svg.width.baseVal.value;
                canvas.height = svg.height.baseVal.value;
                
                // Konversi SVG ke string XML
                const svgData = new XMLSerializer().serializeToString(svg);
                const svgBlob = new Blob([svgData], {type: 'image/svg+xml;charset=utf-8'});
                const svgURL = URL.createObjectURL(svgBlob);
                
                // Buat image baru
                const image = new Image();
                
                // Ketika image sudah load
                image.onload = function() {
                    // Gambar image ke canvas
                    context.drawImage(image, 0, 0);
                    URL.revokeObjectURL(svgURL);
                    
                    // Konversi canvas ke data URL
                    const imgURL = canvas.toDataURL('image/png');
                    
                    // Buat link untuk download
                    const downloadLink = document.createElement('a');
                    downloadLink.download = 'qr-code-{{ $guest->slug }}.png';
                    downloadLink.href = imgURL;
                    downloadLink.click();
                }
                
                // Set source image
                image.src = svgURL;
            }
            // Jika elemen adalah IMG
            else if (qrCodeContainer.querySelector('img')) {
                const img = qrCodeContainer.querySelector('img');
                // Buat canvas
                const canvas = document.createElement('canvas');
                const context = canvas.getContext('2d');
                
                // Set ukuran canvas
                canvas.width = img.width;
                canvas.height = img.height;
                
                // Gambar image ke canvas
                context.drawImage(img, 0, 0);
                
                // Konversi canvas ke data URL
                const imgURL = canvas.toDataURL('image/png');
                
                // Buat link untuk download
                const downloadLink = document.createElement('a');
                downloadLink.download = 'qr-code-{{ $guest->slug }}.png';
                downloadLink.href = imgURL;
                downloadLink.click();
            }
        }
        </script>
        <!-- JavaScript Libraries -->
        <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
        <script src="lib/easing/easing.min.js"></script>
        <script src="lib/waypoints/waypoints.min.js"></script>
        <script src="lib/owlcarousel/owl.carousel.min.js"></script>
        <script src="lib/isotope/isotope.pkgd.min.js"></script>
        <script src="lib/lightbox/js/lightbox.min.js"></script>

        <!-- Template Javascript -->
        <script src="js/main.js"></script>
    
</body>

</html>
