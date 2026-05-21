@extends('layouts.app')
@section('title', 'Terbitkan Resep Baru')

@section('content')
<form action="{{ route('resep.store') }}" method="POST" id="formResep">
    @csrf

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- DATA PASIEN & DOKTER -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-card border border-bordercolor rounded-lg shadow-sm p-5">
                <h3 class="font-bold text-title mb-4 border-b border-bordercolor pb-2">Informasi Resep</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold mb-1">Pilih Pasien</label>
                        <select name="id_pasien" class="w-full border border-bordercolor rounded px-3 py-2 focus:ring-2 focus:ring-primary bg-mainbg" required>
                            <option value="">-- Cari Pasien --</option>
                            @foreach($pasien as $p)
                                <option value="{{ $p->id_pasien }}">{{ $p->no_rekam_medis }} - {{ $p->nama_lengkap }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    @if(Auth::user()->role === 'admin')
                        <div>
                            <label class="block text-sm font-semibold mb-1">Dokter Pemeriksa</label>
                            <select name="id_dokter" class="w-full border border-bordercolor rounded px-3 py-2 focus:ring-2 focus:ring-primary bg-mainbg" required>
                                <option value="">-- Pilih Dokter --</option>
                                @foreach($dokter as $d)
                                    <option value="{{ $d->id_dokter }}">{{ $d->nama_dokter }} ({{ $d->spesialisasi }})</option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        <!-- Jika yang login dokter, kunci pilihan ke dirinya sendiri -->
                        <div>
                            <label class="block text-sm font-semibold mb-1">Dokter Pemeriksa</label>
                            <input type="text" value="{{ Auth::user()->dokter->nama_dokter ?? 'Unknown' }}" class="w-full border border-bordercolor rounded px-3 py-2 bg-gray-200 text-gray-600 font-bold cursor-not-allowed" readonly>
                        </div>
                    @endif

                    <div class="bg-info/10 p-3 rounded border border-info/20 mt-4">
                        <p class="text-xs font-bold text-info mb-1">Detail Sistem</p>
                        <p class="text-xs text-textsec mb-1"><span class="font-semibold">Tgl:</span> {{ now()->format('d M Y') }}</p>
                        <p class="text-xs text-textsec"><span class="font-semibold">User Entry:</span> {{ Auth::user()->username }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- FORM DINAMIS DETAIL OBAT -->
        <div class="lg:col-span-2">
            <div class="bg-card border border-bordercolor rounded-lg shadow-sm p-5">
                <div class="flex justify-between items-center border-b border-bordercolor pb-2 mb-4">
                    <h3 class="font-bold text-title">Daftar Obat (Detail Resep)</h3>
                    <button type="button" onclick="tambahBaris()" class="bg-primary hover:bg-primary-hover text-white px-3 py-1.5 rounded text-xs font-bold transition shadow-sm">
                        + Tambah Obat Lain
                    </button>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left" id="tabelObat">
                        <thead>
                            <tr class="text-xs text-textsec uppercase border-b border-bordercolor">
                                <th class="pb-2 font-bold w-[35%]">Pilih Obat (Stok)</th>
                                <th class="pb-2 font-bold w-[15%] text-right">Harga</th>
                                <th class="pb-2 font-bold w-[12%] text-center">Qty</th>
                                <th class="pb-2 font-bold w-[23%]">Aturan Pakai</th>
                                <th class="pb-2 font-bold w-[15%] text-right">Subtotal</th>
                                <th class="pb-2 font-bold text-center w-[5%]">X</th>
                            </tr>
                        </thead>
                        <tbody id="badanTabel">
                            <!-- Baris 1 Default -->
                            <tr class="baris-obat border-b border-bordercolor/50">
                                <td class="py-2 pr-2">
                                    <select name="obat[0][id_obat]" class="select-obat w-full border border-bordercolor rounded px-2 py-1.5 text-sm focus:ring-primary" onchange="hitungSubtotal(this)" required>
                                        <option value="" data-harga="0">-- Pilih Obat --</option>
                                        @foreach($obat as $o)
                                            <!-- Menyisipkan data harga di atribut data-harga -->
                                            <option value="{{ $o->id_obat }}" data-harga="{{ $o->harga }}">{{ $o->nama_obat }} (Stok: {{ $o->stok }})</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="py-2 pr-2 text-right text-sm font-semibold text-textsec">
                                    Rp <span class="span-harga">0</span>
                                </td>
                                <td class="py-2 pr-2">
                                    <input type="number" name="obat[0][jumlah]" min="1" class="input-jumlah w-full border border-bordercolor rounded px-2 py-1.5 text-sm focus:ring-primary text-center" oninput="hitungSubtotal(this)" required>
                                </td>
                                <td class="py-2 pr-2">
                                    <input type="text" name="obat[0][aturan_pakai]" class="w-full border border-bordercolor rounded px-2 py-1.5 text-sm focus:ring-primary" placeholder="Cth: 3x1" required>
                                </td>
                                <td class="py-2 pr-2 text-right text-sm font-bold text-success">
                                    Rp <span class="span-subtotal">0</span>
                                </td>
                                <td class="py-2 text-center">
                                    <button type="button" onclick="hapusBaris(this)" class="text-danger hover:text-red-700 font-bold px-2 disabled:opacity-30" disabled>&times;</button>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="bg-gray-50 border-t-2 border-bordercolor">
                                <td colspan="4" class="py-3 px-2 text-right font-bold uppercase text-sm text-title">Grand Total:</td>
                                <td class="py-3 px-2 text-right font-black text-primary text-lg">Rp <span id="grandTotal">0</span></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="mt-6 pt-4 text-right space-x-3">
                    <a href="{{ route('resep.index') }}" class="text-textsec hover:text-textmain font-semibold text-sm">Batal</a>
                    <button type="submit" class="bg-success hover:bg-green-600 text-white px-6 py-2 rounded shadow font-bold transition">Simpan Resep & Kurangi Stok</button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- TEMPLATE BARIS JAVASCRIPT (HIDDEN) -->
<table class="hidden">
    <tbody id="templateBaris">
        <tr class="baris-obat border-b border-bordercolor/50">
            <td class="py-2 pr-2">
                <select name="obat[INDEX][id_obat]" class="select-obat w-full border border-bordercolor rounded px-2 py-1.5 text-sm focus:ring-primary" onchange="hitungSubtotal(this)" required>
                    <option value="" data-harga="0">-- Pilih Obat --</option>
                    @foreach($obat as $o)
                        <option value="{{ $o->id_obat }}" data-harga="{{ $o->harga }}">{{ $o->nama_obat }} (Stok: {{ $o->stok }})</option>
                    @endforeach
                </select>
            </td>
            <td class="py-2 pr-2 text-right text-sm font-semibold text-textsec">
                Rp <span class="span-harga">0</span>
            </td>
            <td class="py-2 pr-2">
                <input type="number" name="obat[INDEX][jumlah]" min="1" class="input-jumlah w-full border border-bordercolor rounded px-2 py-1.5 text-sm focus:ring-primary text-center" oninput="hitungSubtotal(this)" required>
            </td>
            <td class="py-2 pr-2">
                <input type="text" name="obat[INDEX][aturan_pakai]" class="w-full border border-bordercolor rounded px-2 py-1.5 text-sm focus:ring-primary" placeholder="Cth: 3x1" required>
            </td>
            <td class="py-2 pr-2 text-right text-sm font-bold text-success">
                Rp <span class="span-subtotal">0</span>
            </td>
            <td class="py-2 text-center">
                <button type="button" onclick="hapusBaris(this)" class="text-danger hover:text-red-700 font-bold px-2 text-lg">&times;</button>
            </td>
        </tr>
    </tbody>
</table>

<script>
    let barisIndex = 1; 
    
    function tambahBaris() {
        let template = document.getElementById('templateBaris').innerHTML;
        let barisBaruHTML = template.replace(/INDEX/g, barisIndex);
        document.getElementById('badanTabel').insertAdjacentHTML('beforeend', barisBaruHTML);
        barisIndex++;
        updateTombolHapus();
    }
    
    function hapusBaris(btn) {
        btn.closest('tr').remove();
        updateTombolHapus();
        hitungGrandTotal(); // Update grand total jika ada baris dihapus
    }
    
    function updateTombolHapus() {
        let daftarBaris = document.querySelectorAll('#badanTabel .baris-obat');
        let tombolPertama = document.querySelector('#badanTabel .baris-obat button');
        tombolPertama.disabled = (daftarBaris.length <= 1);
    }

    // Fungsi Kalkulasi Harga Dinamis
    function hitungSubtotal(element) {
        let baris = element.closest('tr');
        let select = baris.querySelector('.select-obat');
        let inputJumlah = baris.querySelector('.input-jumlah');
        let spanHarga = baris.querySelector('.span-harga');
        let spanSubtotal = baris.querySelector('.span-subtotal');

        // Ambil harga dari atribut data-harga pada option yang dipilih
        let harga = 0;
        if (select.selectedIndex > 0) {
            harga = select.options[select.selectedIndex].getAttribute('data-harga');
        }

        // Ambil jumlah
        let jumlah = inputJumlah.value || 0;
        let subtotal = harga * jumlah;

        // Format ke dalam rupiah (ribuan)
        spanHarga.innerText = new Intl.NumberFormat('id-ID').format(harga);
        spanSubtotal.innerText = new Intl.NumberFormat('id-ID').format(subtotal);
        spanSubtotal.setAttribute('data-nilai', subtotal); // Simpan nilai asli tanpa format

        hitungGrandTotal();
    }

    function hitungGrandTotal() {
        let subtotals = document.querySelectorAll('.span-subtotal');
        let grandTotal = 0;

        subtotals.forEach(function(item) {
            let nilai = item.getAttribute('data-nilai') || 0;
            grandTotal += parseInt(nilai);
        });

        document.getElementById('grandTotal').innerText = new Intl.NumberFormat('id-ID').format(grandTotal);
    }
</script>
@endsection