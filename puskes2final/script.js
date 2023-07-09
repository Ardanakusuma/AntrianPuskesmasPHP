
function editDokter(id) {
    window.location.href = "ubahdokter.php?id=" + id;
}

function hapusDokter(id) {
    if (confirm("Apakah Anda yakin ingin menghapus data dokter dengan ID " + id + "?")) {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "hapusdokter.php?id=" + id, true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    alert("Data dokter berhasil dihapus.");
                    window.location.reload();
                } else {
                    alert("Terjadi kesalahan saat menghapus data dokter.");
                }
            }
        };
        xhr.send();
    }
}

function editPasien(id) {
    window.location.href = "ubahpasien.php?id=" + id;
}

function hapusPasien(id) {
    if (confirm("Apakah Anda yakin ingin menghapus data pasien dengan ID " + id + "?")) {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "hapuspasien.php?id=" + id, true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    alert("Data pasien berhasil dihapus.");
                    window.location.reload();
                } else {
                    alert("Terjadi kesalahan saat menghapus data pasien.");
                }
            }
        };
        xhr.send();
    }
}

function editResepsionis(id) {
    window.location.href = "ubahresepsionis.php?id=" + id;
}

function hapusResepsionis(id) {
    if (id === "1") {
        alert("Data dengan ID 1 tidak dapat dihapus.");
        return;
    }

    if (confirm("Apakah Anda yakin ingin menghapus data resepsionis dengan ID " + id + "?")) {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "hapusresepsionis.php?id=" + id, true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var response = xhr.responseText;
                    if (response.includes("Data dengan ID 1 tidak dapat dihapus.")) {
                        alert("Data dengan ID 1 tidak dapat dihapus.");
                    } else {
                        alert("Data resepsionis berhasil dihapus.");
                        window.location.reload();
                    }
                } else {
                    alert("Terjadi kesalahan saat menghapus data resepsionis.");
                }
            }
        };
        xhr.send();
    }
}




function closeNestedFloatWindow() {
    var nestedFloatWindow = document.querySelector(".nested-float-window");
    if (nestedFloatWindow) {
        nestedFloatWindow.parentNode.removeChild(nestedFloatWindow);
        document.querySelector(".float-window").style.display = "block";
    }
}


/option antrian/
function openNestedFloatWindowOption(id, ruangan) {
    var floatWindow = document.querySelector('.float-window');

    // Hapus semua elemen anak float window
    while (floatWindow.firstChild) {
        floatWindow.removeChild(floatWindow.firstChild);
    }

    var nestedFloatWindow = document.createElement('div');
    nestedFloatWindow.className = 'nested-float-window';
    nestedFloatWindow.innerHTML = `
        <h2>Ubah urutan antrian</h2>
        <p>Antrian dengan ID "${id}" akan diubah ke-</p>
        <form action="antrian/ubahantrian.php" method="get">
            <label for="urutan">Urutan</label>
            <input type="text" name="urutan" size="5" id="urutan" required>
            <input type="hidden" name="id" value="${id}">
            <input type="hidden" name="ruangan" value="${ruangan}">
            <br><br>
            <button type="submit">OK</button>
            <button onclick="closeNestedFloatWindow()">Kembali</button>
        </form>
    `;

    floatWindow.appendChild(nestedFloatWindow);
    floatWindow.style.display = 'block';
}

function closeNestedFloatWindow() {
    var floatWindow = document.querySelector('.float-window');
    floatWindow.style.display = 'none';
}


function openNested2FloatWindowOption(id, ruangan) {
    var floatWindow = document.querySelector('.float-window');

    // Hapus semua elemen anak float window
    while (floatWindow.firstChild) {
        floatWindow.removeChild(floatWindow.firstChild);
    }

    var nestedFloatWindow = document.createElement('div');
    nestedFloatWindow.className = 'nested-float-window';
    nestedFloatWindow.innerHTML = `
        <h2>Ubah status pasien</h2>
        <p>Pilih status untuk data dengan ID "${id}":</p>
        <button onclick="window.location.href='antrian/ubahantrian.php?id=${id}&status=1&ruangan=${ruangan}'">Menunggu</button>
        <button onclick="window.location.href='antrian/ubahantrian.php?id=${id}&status=2&ruangan=${ruangan}'">Diperiksa</button>
        <button onclick="window.location.href='antrian/ubahantrian.php?id=${id}&status=3&ruangan=${ruangan}'">Selesai</button>
        <button onclick="closeNested2FloatWindow()">Kembali</button>
        <br>
    `;

    floatWindow.appendChild(nestedFloatWindow);
    floatWindow.style.display = 'block';
}

function closeNested2FloatWindow() {
    var floatWindow = document.querySelector('.float-window');
    floatWindow.style.display = 'none';
    floatWindow.innerHTML = '';
}



//detail
function openFloatWindow(id, nama, hp, spesialis, status, ktp_kk, ruangan) {
    var floatWindow = document.createElement('div');
    floatWindow.className = 'float-window-detail';

    floatWindow.innerHTML = `
        <h2>Data Pasien</h2>
        <div class="image-container">
            <img src="pasien/img/${ktp_kk}">
        </div>
        <p><span>Nama   </span>: ${nama}</p>
        <p><span>No HP  </span>: ${hp}</p>
        <p><span>Berobat</span>: ${spesialis}</p>
        <p><span>Status </span>: ${status}</p>
        <button onclick="closeFloatWindow()">Tutup</button>
    `;

    document.body.appendChild(floatWindow);
}



function closeFloatWindow() {
    var floatWindow = document.querySelector('.float-window-detail');
    floatWindow.parentNode.removeChild(floatWindow);
}


       

// script.js
feather.replace();

const sidebar = document.querySelector(".sidebar");

if (sidebar) {
  const init = () => {
    attachEvents();
  };

  const hamburgerMenu = document.querySelector(".hamburger-menu");
  const hamburgerMenuContainer = document.querySelector(
    ".hamburger-menu__container"
  );
  const nav = document.querySelector(".nav");

  const attachEvents = () => {
    hamburgerMenuContainer.addEventListener("click", toggleMenu);
  };

  const toggleMenu = () => {
    hamburgerMenu.classList.toggle("hamburger-menu--open");
    nav.classList.toggle("nav--open");
  };

  init();
}


