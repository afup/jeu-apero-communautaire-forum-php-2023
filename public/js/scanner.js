import QrScanner from "./qr-scanner.min.js";

const video = document.getElementById('qr-video');
const camQrResult = document.getElementById('cam-qr-result');
const successCode = document.getElementById('success-code');
const badCode = document.getElementById('bad-code');

function setResult(label, result) {
    console.log(result.data);
    const pattern = /flash\/[a-zA-Z0-9]+$/;

    if (pattern.test(result.data)) {
        successCode.style.display = 'block';
        scanner.stop();
        window.location = result.data;
    } else {
        badCode.style.display = 'block';
        setTimeout(() => {
            badCode.style.display = 'none';
        }, 2000);
    }

}

const scanner = new QrScanner(video, result => setResult(camQrResult, result), {
    highlightScanRegion: true,
    highlightCodeOutline: true,
    maxScansPerSecond: 5
});

scanner.start();