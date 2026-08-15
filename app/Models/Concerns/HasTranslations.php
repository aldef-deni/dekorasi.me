<?php

namespace App\Models\Concerns;

/**
 * Memberi model kemampuan menyimpan terjemahan per bahasa.
 *
 * Bahasa utama (Indonesia) tetap berada di kolom aslinya. Bahasa lain
 * disimpan pada kolom JSON "translations" berbentuk:
 *
 *   { "en": { "title": "...", "excerpt": "..." } }
 *
 * Model yang memakainya wajib mendefinisikan $translatable.
 */
trait HasTranslations
{
    /**
     * Ambil nilai kolom sesuai bahasa aktif.
     * Bila terjemahannya kosong, otomatis mundur ke bahasa utama supaya
     * halaman tidak pernah tampil kosong.
     */
    public function t(string $field): mixed
    {
        $locale = app()->getLocale();

        if ($locale === \App\Http\Middleware\SetLocale::PRIMARY || ! in_array($field, $this->translatable(), true)) {
            return $this->{$field};
        }

        $nilai = data_get($this->translations, $locale.'.'.$field);

        return filled($nilai) ? $nilai : $this->{$field};
    }

    /**
     * Isi terjemahan untuk satu bahasa. Kolom yang dikosongkan tidak disimpan
     * agar JSON-nya tetap ramping.
     *
     * @param  array<string, mixed>  $values
     */
    public function setTranslation(string $locale, array $values): void
    {
        $bersih = collect($values)
            ->only($this->translatable())
            ->filter(fn ($nilai) => filled($nilai))
            ->all();

        $semua = $this->translations ?? [];

        if ($bersih === []) {
            unset($semua[$locale]);
        } else {
            $semua[$locale] = $bersih;
        }

        $this->translations = $semua ?: null;
    }

    /** Nilai tersimpan untuk sebuah bahasa — dipakai form admin. */
    public function translation(string $locale, string $field): mixed
    {
        return data_get($this->translations, $locale.'.'.$field);
    }

    /** Apakah bahasa ini sudah punya terjemahan? */
    public function hasTranslation(string $locale): bool
    {
        return filled(data_get($this->translations, $locale));
    }

    /** @return list<string> */
    public function translatable(): array
    {
        return $this->translatable ?? [];
    }
}
