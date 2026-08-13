{{--
  Ikon layanan untuk halaman depan.

  Admin memilih ikon lewat nama kelas Tabler (mis. "tabler-sofa"). Halaman depan
  tidak memuat font ikon Vuexy, jadi nama tersebut dipetakan ke SVG inline di
  sini. Daftar ini harus selaras dengan $iconOptions di
  resources/views/admin/services/form.blade.php.

  @param string|null $icon
--}}
@php $icon = $icon ?: 'tabler-sofa'; @endphp

<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4"
     stroke-linecap="round" stroke-linejoin="round" width="26" height="26" aria-hidden="true">
  @switch($icon)
    @case('tabler-home')
      <path d="M3 10.5 12 4l9 6.5V20a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-9.5Z"/><path d="M9 21v-6h6v6"/>
      @break

    @case('tabler-building-store')
      <path d="M3 10h18"/><path d="M4 10V6.5A1.5 1.5 0 0 1 5.5 5h13A1.5 1.5 0 0 1 20 6.5V10"/>
      <path d="M5 10v10h14V10"/><path d="M9 20v-6h6v6"/>
      @break

    @case('tabler-building-arch')
      <path d="M4 21V9l8-5 8 5v12"/><path d="M9 21v-7a3 3 0 0 1 6 0v7"/><path d="M2 21h20"/>
      @break

    @case('tabler-ruler-2')
      <path d="M17 3 3 17l4 4L21 7l-4-4Z"/><path d="m14 6 2 2"/><path d="m11 9 2 2"/><path d="m8 12 2 2"/><path d="m5 15 2 2"/>
      @break

    @case('tabler-pencil')
      <path d="M4 20h4l10-10-4-4L4 16v4Z"/><path d="m13.5 6.5 4 4"/>
      @break

    @case('tabler-cube-3d-sphere')
      <path d="M12 3 20 7.5v9L12 21l-8-4.5v-9L12 3Z"/><path d="M12 12 20 7.5"/><path d="M12 12v9"/><path d="M12 12 4 7.5"/>
      @break

    @case('tabler-armchair')
      <path d="M5 11V8a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v3"/>
      <path d="M5 11a2 2 0 0 0-2 2v3h18v-3a2 2 0 0 0-2-2"/>
      <path d="M6 16v3"/><path d="M18 16v3"/><path d="M7 11h10"/>
      @break

    @case('tabler-bulb')
      <path d="M9.5 16a6 6 0 1 1 5 0v2a1 1 0 0 1-1 1h-3a1 1 0 0 1-1-1v-2Z"/><path d="M10 21h4"/>
      @break

    @case('tabler-paint')
      <path d="M4 5.5A1.5 1.5 0 0 1 5.5 4h11A1.5 1.5 0 0 1 18 5.5v4A1.5 1.5 0 0 1 16.5 11h-11A1.5 1.5 0 0 1 4 9.5v-4Z"/>
      <path d="M18 7.5h1.5A1.5 1.5 0 0 1 21 9v3a1.5 1.5 0 0 1-1.5 1.5H12"/>
      <path d="M11 13.5v3"/><rect x="9" y="16.5" width="4" height="4.5" rx="1"/>
      @break

    @case('tabler-tools')
      <path d="M6 3 3 6l3.5 3.5L9 7 6 3Z"/><path d="m7.5 8.5 9 9"/>
      <path d="M14 4.5a3.5 3.5 0 0 1 5 4.5l-9 9a2.1 2.1 0 0 1-3-3l9-9a3.5 3.5 0 0 1-2-1.5Z"/>
      @break

    @case('tabler-messages')
      <path d="M3 6a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2H8l-4 3V6Z"/>
      <path d="M16 9h3a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-1v3l-3-3h-4"/>
      @break

    @default {{-- tabler-sofa --}}
      <path d="M4 12V8.5A2.5 2.5 0 0 1 6.5 6h11A2.5 2.5 0 0 1 20 8.5V12"/>
      <path d="M3.5 12A1.5 1.5 0 0 1 5 13.5V17h14v-3.5A1.5 1.5 0 0 1 20.5 12"/>
      <path d="M5 17v2"/><path d="M19 17v2"/><path d="M8 12h8"/>
  @endswitch
</svg>
