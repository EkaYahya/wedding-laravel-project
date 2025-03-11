import "./bootstrap";
import '../css/app.css';
import "./dashboard";

import { Html5Qrcode } from "html5-qrcode";

function startQRScanner(updateAttendanceUrl) {
    const qrReader = new Html5Qrcode("qr-reader"); // ID kontainer untuk menampilkan video scanner

    qrReader
        .start(
            { facingMode: "environment" },
            {
                fps: 10,
                qrbox: 250,
            },
            (decodedText) => {
                if (decodedText === updateAttendanceUrl) {
                    window.location.href = updateAttendanceUrl;
                    console.log(updateAttendanceUrl);
                } else {
                    alert("QR code tidak valid untuk tamu ini.");
                }
                qrReader.stop(); // Hentikan scanner setelah berhasil
            },
            (errorMessage) => {
                console.log(errorMessage);
            }
        )
        .catch((err) => {
            console.error("Gagal memulai QR scanner:", err);
        });
}
