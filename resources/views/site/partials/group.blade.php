{{--
  Section "Group Kami".

  Dipakai bersama oleh Beranda dan Tentang Kami, selalu diletakkan paling
  bawah — setelah blok CTA, tepat sebelum footer.
--}}
@php
    $grup = [
        ['berkas' => 'akr',              'nama' => 'PT Aneka Karya Rakaindo'],
        ['berkas' => 'bawono',           'nama' => 'Bawono Business Strategy'],
        ['berkas' => 'scp',              'nama' => 'SCP'],
        ['berkas' => 'ruangsinggah',     'nama' => 'Ruang Singgah Hotel & Apartment'],
        ['berkas' => 'arahinn-dekorasi', 'nama' => 'ArahInn'],
    ];
@endphp

<section class="group-section">
  <div class="wrap">
    <div class="section-head center reveal">
      <span class="eyebrow">{{ __('site.group.eyebrow') }}</span>
      <h2>{!! __('site.group.title', ['highlight' => '<span class="gold-text">'.__('site.group.highlight').'</span>']) !!}</h2>
      <p class="lead" style="margin-inline:auto">
        {{ __('site.group.lead') }}
      </p>
    </div>

    <div class="group-grid">
      @foreach ($grup as $anggota)
        <div class="group-item reveal">
          <div class="group-logo">
            <img src="{{ asset('img/group/' . $anggota['berkas'] . '.png') }}"
                 alt="{{ $anggota['nama'] }}" loading="lazy" />
          </div>
          <span class="group-name">{{ $anggota['nama'] }}</span>
        </div>
      @endforeach
    </div>
  </div>
</section>
