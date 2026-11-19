document.addEventListener('DOMContentLoaded', () => {
    const t = document.getElementById('menuToggle');
    const s = document.getElementById('sidebar');

    if (t && s) {
        t.addEventListener('click', () => {
            s.classList.toggle('open');
        });
    }

    document.querySelectorAll('[data-confirm]').forEach(el => {
        el.addEventListener('click', e => {
            if (!confirm(el.dataset.confirm)) {
                e.preventDefault();
            }
        });
    });

    const jenis = document.querySelectorAll('input[name="jenis_pesanan"]');

    function toggleOrder() {
        const v = document.querySelector('input[name="jenis_pesanan"]:checked')?.value;

        document.querySelectorAll('.field-meja').forEach(x => {
            x.hidden = v !== 'Makan di Tempat';
        });

        document.querySelectorAll('.field-pelanggan').forEach(x => {
            x.hidden = v !== 'Bungkus';
        });

        const meja = document.querySelector('[name="nomor_meja"]');
        const nama = document.querySelector('[name="nama_pelanggan"]');

        if (meja) meja.required = v === 'Makan di Tempat';
        if (nama) nama.required = v === 'Bungkus';
    }

    jenis.forEach(x => {
        x.addEventListener('change', toggleOrder);
    });

    toggleOrder();
});