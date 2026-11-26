<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoices';

    protected $fillable = [
        'no_invoice',
        'tanggal_invoice',
        'jatuh_tempo',
        'id_siswa',
        'id_sekolah',
        'status',
        'is_auto_generated',
        'grand_total',
        'potongan',
        'payment_proof',
        'payment_date',
        'payment_notes',
    ];

    protected $casts = [
        'tanggal_invoice' => 'date',
        'jatuh_tempo' => 'date',
        'payment_date' => 'datetime',
        'grand_total' => 'decimal:2',
        'potongan' => 'decimal:2',
        'is_auto_generated' => 'boolean',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function (Invoice $invoice) {
            if (empty($invoice->no_invoice)) {
                $invoice->no_invoice = self::generateInvoiceNumber($invoice->tanggal_invoice);
            }
        });
    }

    /**
     * Relasi ke siswa
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa');
    }

    /**
     * Relasi ke profil sekolah
     */
    public function profilSekolah()
    {
        return $this->belongsTo(ProfilSekolah::class, 'id_sekolah')->withDefault(function () {
            return ProfilSekolah::first() ?? new ProfilSekolah([
                'nama_sekolah' => 'SIS SEKOLAH',
                'alamat' => 'Jl. Pendidikan Modern No. 1, Jakarta',
            ]);
        });
    }

    /**
     * Relasi ke invoice detail
     */
    public function invoiceDetails()
    {
        return $this->hasMany(InvoiceDetail::class, 'id_invoice');
    }

    /**
     * Relasi ke user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate nomor invoice otomatis dengan format INV-SAYYIDAH-[BulanRomawi]-[NomorUrut]
     * Logika: Reset nomor urut kembali ke 0001 setiap berganti bulan.
     * Dijamin unik untuk mencegah kesalahan SQL 1062 Duplicate entry.
     */
    public static function generateInvoiceNumber($tanggalInvoice = null, $ignoreInvoiceId = null)
    {
        $date = $tanggalInvoice ? \Carbon\Carbon::parse($tanggalInvoice) : now();
        $bulanRomawi = \App\Helpers\TerbilangHelper::toRoman($date->month);
        $prefix = 'INV-SAYYIDAH-' . $bulanRomawi . '-';

        // Cari nomor terbesar yang sudah digunakan untuk format prefix bulan ini
        $existingNumbers = self::where('no_invoice', 'like', $prefix . '%')
            ->when($ignoreInvoiceId, function ($q) use ($ignoreInvoiceId) {
                $q->where('id', '!=', $ignoreInvoiceId);
            })
            ->pluck('no_invoice');

        $maxNum = 0;
        foreach ($existingNumbers as $no) {
            $parts = explode('-', $no);
            $num = intval(end($parts));
            if ($num > $maxNum) {
                $maxNum = $num;
            }
        }

        // Juga cek invoice dengan filter tanggal jika ada format lain
        $lastInvoice = self::whereYear('tanggal_invoice', $date->year)
            ->whereMonth('tanggal_invoice', $date->month)
            ->when($ignoreInvoiceId, function ($q) use ($ignoreInvoiceId) {
                $q->where('id', '!=', $ignoreInvoiceId);
            })
            ->orderBy('id', 'desc')
            ->first();

        if ($lastInvoice) {
            $parts = explode('-', $lastInvoice->no_invoice);
            $lastNum = intval(end($parts));
            if ($lastNum > $maxNum) {
                $maxNum = $lastNum;
            }
        }

        $counter = $maxNum + 1;

        // Uniqueness guarantee loop
        do {
            $newNumber = str_pad($counter, 4, '0', STR_PAD_LEFT);
            $candidate = $prefix . $newNumber;

            $existsQuery = self::where('no_invoice', $candidate);
            if ($ignoreInvoiceId) {
                $existsQuery->where('id', '!=', $ignoreInvoiceId);
            }

            if (!$existsQuery->exists()) {
                return $candidate;
            }

            $counter++;
        } while (true);
    }
}
