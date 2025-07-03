export function showModalDetail(id) {
    const modalDetail = new HSOverlay(document.querySelector('#modal-detail'));
    const modalBody = document.querySelector('#modal-detail .modal-body');
    const btnTrigger = document.querySelector('#btn_detail_' + id);
    const fotoMasuk = btnTrigger.getAttribute('data-foto_masuk');
    const fotoPulang = btnTrigger.getAttribute('data-foto_pulang');

    let result = "<div class='flex flex-col gap-3'>";

    result += fotoMasuk ? `<img src="${fotoMasuk}" alt="Foto Masuk" class="w-full h-auto">` :
        `<p class="text-center">Tidak ada foto masuk</p>`;

    result += `<hr />`;

    result += fotoPulang ? `<img src="${fotoPulang}" alt="Foto Pulang" class="w-full h-auto">` :
        `<p class="text-center">Tidak ada foto pulang</p>`;

    result += "</div>";

    modalBody.innerHTML = result;
    modalDetail.open();
}

export function closeModal() {
    const modalDetail = new HSOverlay(document.querySelector('#modal-detail'));
    modalDetail.close();
}